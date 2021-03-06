<?php

namespace App\Http\Controllers;

use App\Common\Util;
use App\Http\Response\JsonResponse;
use App\Model\Code;
use App\Model\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class AppController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $page = 1;

    protected $pageSize = 20;

    public function __construct()
    {
        //初始化分页
        $page = request()->input('page');
        if ($page !== null && is_int($page) && +$page > 0) {
            $this->page = $page;
        }
        $pageSize = request()->input('page_size') ?: request()->input('pageSize');
        if ($pageSize && is_numeric($pageSize) && +$pageSize > 0) {
            $this->pageSize = $pageSize;
        }
    }

    protected static $rules = [
        'default' => [
            'page'     => 'nullable|integer|min:1',
            'pageSize' => 'nullable|integer|min:1',
        ],
    ];

    protected static $rulesMessages = [
        'default' => [
            'required' => '缺少必填参数[:attribute]',
            'filled'   => '[:attribute]不能为空',
            'string'   => '格式错误，[:attribute]应该为字符串',
            'integer'  => '格式错误，[:attribute]应该为整数',
            'between'  => '[:attribute]范围错误，要求参数在:min - :max',
            'in'       => '[:attribute]取值应该在 :values 内',
            'array'    => '格式错误，[:attribute]应该为数组',
            'url'      => '格式错误，[:attribute]应该为URL',
            'email'    => '格式错误，[:attribute]应该为EMAIL地址',
            'min'      => '[:attribute]数量不正确，至少为:min',
            'max'      => '选中数过多，最多允许:max个[:attribute]',
            'unique'   => '[:attribute]数据重复',
            'exists'   => '[:attribute]记录不存在', //定义[code:xxx] 会返回$code码
            'boolean'  => '[:attribute]只能是0或1',
            'regex'    => '格式错误，[:attribute]不符合规则',
        ],
    ];

    protected static $rulesCodes = [
        'default' => [
            'Exists' => Code::ERR_MODEL_NOT_FOUND,
        ],
    ];

    /**
     * 验证
     *
     * @param array  $data   验证参数
     * @param string $scene  场景
     * @param array  $errors 错误
     * @param int    $code   错误码
     */
    protected function checkValidate($data, $scene = 'default', &$errors = [], &$code = Code::ERR_PARAMETER): void
    {
        $code      = Code::ERR_PARAMETER;
        $validator = Validator::make($data, $this->rules($scene), $this->rulesMessages($scene));
        if ($validator->fails()) {
            $rulesCode = $this->rulesCodes($scene);
            $failed    = $validator->failed();
            $errors    = Arr::flatten($validator->errors()->getMessages());
            /** @noinspection LoopWhichDoesNotLoopInspection */
            foreach ($failed as $para => $v) {
                if (isset($rulesCode[$para])) {
                    $code = $rulesCode[$para];
                    throw new InvalidParameterException(Util::toJson($errors), $code);
                }
                foreach ($v as $rule => $vv) {
                    if (isset($rulesCode[$rule])) {
                        $code = $rulesCode[$rule];
                        throw new InvalidParameterException(Util::toJson($errors), $code);
                    }
                }
                throw new InvalidParameterException(Util::toJson($errors), $code);
            }
        }
    }

    protected function rules($scene = 'default')
    {
        $rules = Util::arrayRecursiveMerge(self::$rules, static::$rules);
        $rule  = $rules['default'];
        if (isset($rules[$scene])) {
            $rule = Util::arrayRecursiveMerge($rule, $rules[$scene]);
        }
        return $rule;
    }

    protected function rulesMessages($scene = 'default')
    {
        $rulesMessages = Util::arrayRecursiveMerge(self::$rulesMessages, static::$rulesMessages);
        $rulesMessage  = $rulesMessages['default'];
        if (isset($rulesMessages[$scene])) {
            $rulesMessage = Util::arrayRecursiveMerge($rulesMessage, $rulesMessages[$scene]);
        }
        return $rulesMessage;
    }

    protected function rulesCodes($scene = 'default')
    {
        $rulesCodes = Util::arrayRecursiveMerge(self::$rulesCodes, static::$rulesCodes);
        $rulesCode  = $rulesCodes['default'];
        if (isset($rulesCodes[$scene])) {
            $rulesCode = Util::arrayRecursiveMerge($rulesCode, $rulesCodes[$scene]);
        }
        return $rulesCode;
    }

    /**
     * 返回json数据
     *
     * @param mixed $data
     * @param int   $status
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function response($data = null, int $status = 200, array $headers = []): Response
    {
        $extraFields = [];
        if ((strtoupper(request()->method()) <=> 'GET') === 0 && @explode('/', request()->path())[1] === 'page') {
            //注入菜单、用户角色信息
            $extraFields = [
                'menu' => Menu::getMenuForUser(Auth::user())->map(static function (Menu $item) {
//                    return $item->toArray();
                    return Arr::only($item->toArray(), ['id', 'title', 'description', 'url', 'icon_class', 'sort', 'group', 'parent_id']);
                })->sort(static function ($a, $b) {
                    return $a['sort'] - $b['sort'];
                })->groupBy('group')->map(static function ($menus) {
                    $menus = array_values(Util::list2tree($menus));
                    $menus = array_filter($menus, static function ($menu) {
                        //把没有子项的无用根节点删掉
                        return $menu['parent_id'] || $menu['url'] || @$menu['_child'];
                    });
                    return $menus;
                })->toArray(),
                'user' => Auth::user(),
            ];
        }
        return new JsonResponse($data, $status, $headers, $extraFields);
    }

    /**
     * 分页转数组
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $pagination
     * @return array
     */
    public function getPaginateResponse(LengthAwarePaginator $pagination): array
    {
        return [
            'items'     => $pagination->items(),
            'page_info' => [
                'page_index' => $pagination->currentPage(),
                'total'      => $pagination->total(),
                'last_page'  => $pagination->lastPage(),
                'page_size'  => $pagination->perPage(),
            ],
        ];
    }

    /**
     * 从关联数组转为带id的菜单列表
     *
     * @param array $array
     * @return array
     */
    protected function convertToIdMenu(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result [] = [
                'id'    => $key,
                'title' => $value,
            ];
        }
        return $result;
    }
}
