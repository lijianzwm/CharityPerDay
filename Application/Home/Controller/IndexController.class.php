<?php
namespace Home\Controller;
use Think\Controller;
use Common\Service\WxService;


class IndexController extends Controller {
    public function index(){
        $this->display("m_index");
    }

    /**
     * 授权获取用户信息
     */
    public function authority(){
        //获取code
        $code = I("code");
        if( !$code ){
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING']);
            $urlObj["appid"] = C("WX_APPID");
            $urlObj["redirect_uri"] = "$baseUrl";
            $urlObj["response_type"] = "code";
            $urlObj["scope"] = "snsapi_userinfo";
            $urlObj["state"] = "STATE"."#wechat_redirect";
            $bizString = "";
            foreach ($urlObj as $k => $v){
                if($k != "sign"){
                    $bizString .= $k . "=" . $v . "&";
                }
            }
            $bizString = trim($bizString, "&");
            $url = C("WX_AUTHORIZE_URL")."?".$bizString;
            Header("Location: $url");
            exit();
        }else{
            //通过授权来获取用户信息
            $userinfo = WxService::getUserInfo($code);
            if( isset($userinfo['errcode'])){
                redirect(U("Index/index"));
            }
            $openid = $userinfo['openid'];
            $nickname = $userinfo['nickname'];
            redirect(U("Index/suixi", array('openid'=>$openid, 'nickname'=>$nickname)));
        }
    }

    /**
     * 随喜页面
     */
    public function suixi(){
        $openid = I("openid");
        $nickname = I("nickname");
        if ($openid && $nickname) {
            $this->assign("openid", $openid);
            $this->assign("nickname", $nickname);
            $this->display("suixi");
        }else{
            redirect(U("Index/authority"));
        }

    }

    /**
     * 功德林
     */
    public function gongdelin(){

        $itemNumPerPage = C("ITEM_PER_PAGE");
        $orders = M("order");
        $count = $orders->count();
        $Page = new \Think\Page($count, $itemNumPerPage);
        $show = $Page->show();// 分页显示输出
        $orderList = $orders->order("create_time desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();

        for( $i = 0; $i < count($orderList); ++$i ){
            if( $orderList[$i]['status'] == 'PAYSUCCESS' ){
                $orderList[$i]['isPayOK'] = 1;
            }else{
                $orderList[$i]['isPayOK'] = 0;
            }
            $createdTime = $orderList[$i]['create_time'];
            $currentTime = time();
            $dis = $currentTime - $createdTime;
            $min = floor($dis / 60);
            $hour = floor($min / 60);
            $day = floor($hour/24);
            $rTime = "";
            if( $min < 1 ){
                $rTime = "刚刚";
            }else if( $min < 60 ){
                $rTime = $min."分钟前";
            }else if( $hour < 24 ){
                $rTime = $hour . "小时前";
            }else if( $day >= 1 ){
                $rTime = $day . "天前";
            }
            $orderList[$i]['r_time'] = $rTime;
        }

        $this->assign('page', $show);// 赋值分页输出
        $this->assign('orderList', $orderList);
        $this->display("m_gongdelin");
    }



}