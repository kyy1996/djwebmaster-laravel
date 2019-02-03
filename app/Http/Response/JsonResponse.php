<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-03
 * Time: 23:24
 */

namespace App\Http\Response;

use App\Model\Code;

/**
 * Class JsonResponse
 *
 * @package App\Http\Response
 */
class JsonResponse extends \Illuminate\Http\Response
{

    public function __construct($data = null, int $status = 200, array $headers = [])
    {
        parent::__construct($data, $status, $headers);
    }

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
}
