<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/4
 * Time: 上午11:28
 */

require_once "WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once "WxPay.JsApiPay.php";

function QRCodePay( $product, $notfyUrl ){
    $notify = new NativePay();
    $input = new WxPayUnifiedOrder();
    $input->SetProduct_id($product['product_id']);
    $input->SetGoods_tag($product['goods_tag']);
    $input->SetBody($product['body']);
    $input->SetAttach($product['attach']);
    $input->SetTotal_fee($product['fee']);
    $input->SetOut_trade_no($product['out_trade_no']);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetNotify_url($notfyUrl);
    $input->SetTrade_type("NATIVE");
    $result = $notify->GetPayUrl($input);
    $url2 = $result["code_url"];
    return "http://paysdk.weixin.qq.com/example/qrcode.php?data=".urlencode($url2);
//    return "http://" . $_SERVER['HTTP_HOST'] . "/example/qrcodePay.php?data=".urlencode($url2);

}

function jsapiPay($product, $notifyUrl ){
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid();
    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetProduct_id($product['product_id']);
    $input->SetGoods_tag($product['goods_tag']);
    $input->SetBody($product['body']);
    $input->SetAttach($product['attach']);
    $input->SetTotal_fee($product['fee']);
    $input->SetOut_trade_no($product['out_trade_no']);
    
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetNotify_url($notifyUrl);
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    $order = WxPayApi::unifiedOrder($input);

    return $tools->GetJsApiParameters($order);

}

function getUserInfo($code){
    $appid = WxPayConfig::APPID;
    $appsecret = WxPayConfig::APPSECRET;
    $access_token = "";
    //根据code获得Access Token
    $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
    $access_token_json = https_request($access_token_url);
    $access_token_array = json_decode($access_token_json, true);
    $access_token = $access_token_array['access_token'];
    $openid = $access_token_array['openid'];
    //根据Access Token和OpenID获得用户信息
    $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid ";
    $userinfo_json = https_request($userinfo_url);
    $userinfo_array = json_decode($userinfo_json, true);
    return $userinfo_array;
}

function https_request($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}


