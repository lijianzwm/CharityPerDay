<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/8/6
 * Time: 下午4:05
 */

namespace Admin\Controller;


use Think\Controller;

class TypeController extends Controller{

    public function typeList(){
        $typeList = M("type")->select();
        $this->assign("typeList", $typeList);
        $this->display();
    }

    public function addType(){
        $this->display('addType');
    }

    public function addTypeHandler(){
        $type['name'] = I("name");
        $type['description'] = I("description");

        if( M("type")->add($type) ){
            $this->success("添加成功!", U("Type/typeList"));
        }else{
            $this->error("添加失败!");
        }
    }

    public function modifyType(){
        $typeId = I("id");
        $type = M("type")->where("id=$typeId")->find();
        if( $type ){
            $this->assign("type", $type);
            $this->display("modifyType");
        }else{
            $this->error("无此随喜类型!");
        }
    }

    public function modifyTypeHandler(){
        $type['id'] = I("id");
        $type['name'] = I("name");
        $type['description'] = I("description");

        if( M("type")->save($type) ){
            $this->success("修改成功!", U('Type/typeList'));
        }else{
            $this->error("修改前后内容一样,保存失败!");
        }

    }

}