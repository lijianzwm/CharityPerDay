<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/5
 * Time: 下午9:48
 */

ini_set('date.timezone','Asia/Shanghai');

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';
require_once ('log.php');

$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
Log::DEBUG("norify start!");

require_once ('../../common/db.php');
Log::DEBUG("db.php loaded!");

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg){

        Log::DEBUG("call back:" . json_encode($data));

        $notfiyOutput = array();
        if(!array_key_exists("transaction_id", $data)){
            Log::DEBUG("call back: illegal input param");
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            Log::DEBUG("call back: query order failed");
            $msg = "订单查询失败";
            return false;
        }

        $HOST = DBSERVER;
        $DB_NAME = DBNAME;
        $DB_USER = DBUSER;
        $DB_PWD = DBPASS;
        $DB_PORT = DBPORT;
        $dbprefix = DB_PREFIX;

        $out_trade_no = $data['out_trade_no'];//商户订单号

        $sql = "SELECT id FROM {$dbprefix}orders WHERE order_no = '{$out_trade_no}'";

        Log::DEBUG($sql);

        $db = new mysqli($HOST, $DB_USER, $DB_PWD, $DB_NAME, $DB_PORT);

        $result = $db->query($sql);

        list( $id ) = $result->fetch_row();

        $sql = "UPDATE {$dbprefix}orders SET status = 'PAYSUCCESS' WHERE id = {$id}" ;

        Log::DEBUG($sql);

        $db->query($sql);

        return true;
    }
}

$notify = new PayNotifyCallBack();
$notify->Handle();