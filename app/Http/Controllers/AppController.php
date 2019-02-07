<?php

namespace App\Http\Controllers;

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

    protected static $rules = [
        "default" => [],
    ];

    protected static $rulesMessages = [
        "default" => [
            'required' => '部分参数缺失 [:attribute]',
            'string'   => '格式错误，期望为字符串 [:attribute]',
            'integer'  => '格式错误，期望为整数 [:attribute]',
            'between'  => '范围错误 [:attribute],要求参数在:min - :max',
            'in'       => '限定范围错误 [:attribute],要求参数在 :values 内',
            'array'    => '格式错误[:attribute] 期望为数组',
            'url'      => '格式错误[:attribute] 期望为URL',
            'min'      => '数量不正确',
            'max'      => '选中数过多，最多允许:max个 [:attribute]',
            'unique'   => '数据重复 [:attribute]',
            'exists'   => '记录不存在 [:attribute]', //定义[code:xxx] 会返回$code码
        ],
    ];

    protected static $rulesCodes = [
        "default" => [
            "Exists" => Code::ERR_MODEL_NOT_FOUND //这里需要用大写,如果有下划线也去除boss_books => BossBooks
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
                    throw new InvalidParameterException(json_encode($errors, JSON_UNESCAPED_UNICODE), $code);
                }
                foreach ($v as $rule => $vv) {
                    if (isset($rulesCode[$rule])) {
                        $code = $rulesCode[$rule];
                        throw new InvalidParameterException(json_encode($errors, JSON_UNESCAPED_UNICODE), $code);
                    }
                }
                throw new InvalidParameterException(json_encode($errors, JSON_UNESCAPED_UNICODE), $code);
            }

        }
    }

    protected function rules($scene = 'default')
    {
        $rules = array_merge(self::$rules, static::$rules);
        $rule  = $rules['default'];
        if (isset($rules[$scene])) {
            $rule = array_merge($rule, $rules[$scene]);
        }
        return $rule;
    }

    protected function rulesMessages($scene = 'default')
    {
        $rulesMessages = array_merge(self::$rulesMessages, static::$rulesMessages);
        $rulesMessage  = $rulesMessages['default'];
        if (isset($rulesMessages[$scene])) {
            $rulesMessage = array_merge($rulesMessage, $rulesMessages[$scene]);
        }
        return $rulesMessage;
    }

    protected function rulesCodes($scene = 'default')
    {
        $rulesCodes = array_merge(self::$rulesCodes, static::$rulesCodes);
        $rulesCode  = $rulesCodes['default'];
        if (isset($rulesCodes[$scene])) {
            $rulesCode = array_merge($rulesCode, $rulesCodes[$scene]);
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
