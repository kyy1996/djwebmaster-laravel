<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-03
 * Time: 23:24
 */

namespace App\Http\Response;

use App\Model\Code;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * 响应
 * Class JsonResponse
 *
 * @package App\Http\Response
 */
class JsonResponse extends \Illuminate\Http\Response
{
    /**
     * 设置内容
     *
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    public function setContent($data = []): \Illuminate\Http\Response
    {
        return parent::setContent($this->generateData($data));
    }

    /**
     * 生成默认数据
     *
     * @param mixed $data
     * @return array
     */
    private function generateData($data): array
    {
        $defaultData = [
            'code' => Code::getCode(),
            'msg'  => Code::getMessage(),
            'data' => null,
        ];
        if ($data === null) {
            return $defaultData;
        }

        $defaultData['data'] = $data;
        return $defaultData;
    }

    /**
     * Morph the given content into JSON.
     *
     * @param  mixed $content
     * @return string
     */
    protected function morphToJson($content)
    {
        if ($content instanceof Jsonable) {
            return $content->toJson();
        } else if ($content instanceof Arrayable) {
            return json_encode($content->toArray(), JSON_UNESCAPED_UNICODE);
        }

        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}
