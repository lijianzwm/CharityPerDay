<?php
namespace Home\Controller;
use Think\Controller;
use Common\Service\WxService;


class IndexController extends Controller {
    public function index(){
        if( WxService::isWxExplorer() ){
            $this->display("m_index");
        }else{
            $this->display("index");
        }
    }
}