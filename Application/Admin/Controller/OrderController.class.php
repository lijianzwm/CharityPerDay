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
        $this->display('orderList');
    }
}