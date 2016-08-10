<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/7
 * Time: 上午8:31
 */

require_once ('../../common/db.php');

$out_trade_no = $_POST['out_trade_no'];

$sql = "SELECT status FROM {$dbprefix}orders WHERE order_no = '{$out_trade_no}'";
$result = $db->query($sql);
list( $status ) = $result->fetch_row();

if( $status == "PAYSUCCESS" ){
    $ret['status'] = 1;
}else{
    $ret['status'] = 0;
}

echo json_encode($ret);


