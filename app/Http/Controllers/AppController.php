<?php

namespace App\Http\Controllers;

use App\Common\Util;
use App\Model\Code;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
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
        if ($page !== null && is_integer($page) && +$page > 0) {
            $this->page = $page;
        }
        $pageSize = request()->input('pageSize');
        if ($pageSize !== null && is_integer($pageSize) && +$pageSize > 0) {
            $this->pageSize = $pageSize;
        }
    }

    protected static $rules = [
        "default" => [
            'page'     => 'filled|integer|min:1',
            'pageSize' => 'filled|integer|min:1',
        ],
    ];

    protected static $rulesMessages = [
        "default" => [
            'required' => '缺少必填参数[:attribute]',
            'filled'   => '[:attribute]不能为空',
            'string'   => '格式错误，[:attribute]应该为字符串',
            'integer'  => '格式错误，[:attribute]应该为整数',
            'between'  => '[:attribute]范围错误，要求参数在:min - :max',
            'in'       => '[:attribute]取值应该在 :values 内',
            'array'    => '格式错误，[:attribute]应该为数组',
            'url'      => '格式错误，[:attribute]应该为URL',
            'min'      => '[:attribute]数量不正确，至少为:min',
            'max'      => '选中数过多，最多允许:max个[:attribute]',
            'unique'   => '[:attribute]数据重复',
            'exists'   => '[:attribute]记录不存在', //定义[code:xxx] 会返回$code码
            'boolean'  => '[:attribute]只能是0或1',
        ],
    ];

    protected static $rulesCodes = [
        "default" => [
            "Exists" => Code::ERR_MODEL_NOT_FOUND,
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
    protected function checkValidate($data, $scene = 'default', &$errors = [], &$code = Code::ERR_PARAMETER)
    {
        $code      = Code::ERR_PARAMETER;
        $validator = Validator::make($data, $this->rules($scene), $this->rulesMessages($scene));
        if ($validator->fails()) {
            $rulesCode = $this->rulesCodes($scene);
            $failed    = $validator->failed();
            $errors    = Arr::flatten($validator->errors()->getMessages());
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
    function response($data = null, int $status = 200, array $headers = []): Response
    {
        return new \App\Http\Response\JsonResponse($data, $status, $headers);
    }
}
