<?php

namespace App\Api\Controller;

use App\Service\ErrorService;

/**
 *
 * 基础API控制器类
 *
*/
class BaseController
{



    /**
     *
     * @author chenhuian
     * @param string $code 错误码
     * @param array $data 数组
     * @return string
     *
    */
    static public function JSON($code='', $data=[])
    {

        header('Content-type: application/json;charset=utf-8');
        $outData = $code ? [
            'status'    => intval($code),
            'message'   => ErrorService::getMsg($code),
        ]:[];
        if ($code && $data) {
            $outData['data'] = (array)$data;
        }
        die(json_encode($outData, JSON_UNESCAPED_UNICODE));
    }
}
