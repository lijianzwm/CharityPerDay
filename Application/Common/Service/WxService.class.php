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

    /**
     * 通过code获取用户信息
     * @param $code
     * @return mixed
     */
    public static function getUserInfo($code){
        $appid = C("WX_APPID");
        $appsecret = C("WX_APPSECRET");
        $access_token = "";
        //根据code获得Access Token
        $access_token_url = C("WX_ACCESS_TOKEN_OAUTH_URL")."?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $access_token_json = HttpsService::httpsRequest($access_token_url);
        $access_token_array = json_decode($access_token_json, true);
        $access_token = $access_token_array['access_token'];
        $openid = $access_token_array['openid'];
        //根据Access Token和OpenID获得用户信息
        $userinfo_url = C("WX_USERINFO_URL")."?access_token=$access_token&openid=$openid ";
        $userinfo_json = HttpsService::httpsRequest($userinfo_url);
        $userinfo_array = json_decode($userinfo_json, true);
        return $userinfo_array;
    }
    
    


}