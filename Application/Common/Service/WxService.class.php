<?php

/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/7
 * Time: 下午2:06
 */

namespace Common\Service;

class WxService {

    /**
     * 判断是否为微信浏览器
     * @return bool
     */
    public static function isWxExplorer(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
    


}