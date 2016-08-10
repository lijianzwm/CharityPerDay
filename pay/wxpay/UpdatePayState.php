<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "WxPay.Api.php";
require_once('../../common/db.php');

//http://www.lijianzwm.com/ztxm/pay/wxpay/UpdatePayState.php?out_trade_no=1467263997
//http://www.lijianzwm.com/ztxm/pay/wxpay/UpdatePayState.php?out_trade_no=123123

$result = null;

if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
	$transaction_id = $_REQUEST["transaction_id"];
	$input = new WxPayOrderQuery();
	$input->SetTransaction_id($transaction_id);
	$result = WxPayApi::orderQuery($input);
}

if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
	$out_trade_no = $_REQUEST["out_trade_no"];
	$input = new WxPayOrderQuery();
	$input->SetOut_trade_no($out_trade_no);
	$result = WxPayApi::orderQuery($input);
}

if( $result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS'){
    if( $result['trade_state'] == 'SUCCESS' ){
        $HOST = DBSERVER;
        $DB_NAME = DBNAME;
        $DB_USER = DBUSER;
        $DB_PWD = DBPASS;
        $DB_PORT = DBPORT;
        $dbprefix = DB_PREFIX;
        $out_trade_no = $result['out_trade_no'];//商户订单号

        $sql = "SELECT id FROM {$dbprefix}orders WHERE order_no = '{$out_trade_no}'";

        $db = new mysqli($HOST, $DB_USER, $DB_PWD, $DB_NAME, $DB_PORT);
        $result = $db->query($sql);
        list( $id ) = $result->fetch_row();
        $sql = "UPDATE {$dbprefix}orders SET status = 'PAYSUCCESS' WHERE id = {$id}" ;
        $db->query($sql);
        $ret['error_code'] = 0;
        $ret['msg'] = "支付成功!";
    }else{
        $ret['error_code'] = 1;
        $ret['msg'] = "支付失败!";
    }
}else{
    $ret['error_code'] = 1;
    $ret['msg'] = "获取订单支付信息失败!";
}

echo json_encode($ret);

?>