<?php
namespace Home\Controller;
use Think\Controller;
use Common\Service\WxService;


class IndexController extends Controller {
    public function index(){
        $this->display("m_index");
    }

    public function yishan(){

    }

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