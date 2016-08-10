<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/8/7
 * Time: 下午12:13
 */

namespace Admin\Controller;


use Think\Controller;

class OrderController extends Controller{

    public function orderList(){
        $itemNumPerPage = C("ITEM_PER_PAGE");
        $orderTable = M("order");
        $count = $orderTable->count();
        $Page = new \Think\Page($count, $itemNumPerPage);
        $show = $Page->show();// 分页显示输出
        $orderList = $orderTable->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('orderList', $orderList);
        $this->display('orderList');
    }
}