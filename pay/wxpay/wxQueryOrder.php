<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/28
 * Time: 下午6:06
 */


require_once('../../common/db.php');
require_once ('WxPay.Config.php');




$HOST = DBSERVER;
$DB_NAME = DBNAME;
$DB_USER = DBUSER;
$DB_PWD = DBPASS;
$DB_PORT = DBPORT;
$dbprefix = DB_PREFIX;



$sql = "SELECT id FROM {$dbprefix}orders WHERE order_no = '{$out_trade_no}'";

Log::DEBUG($sql);

$db = new mysqli($HOST, $DB_USER, $DB_PWD, $DB_NAME, $DB_PORT);

$result = $db->query($sql);

list( $id ) = $result->fetch_row();

$sql = "UPDATE {$dbprefix}orders SET status = 'PAYSUCCESS' WHERE id = {$id}" ;

Log::DEBUG($sql);

$db->query($sql);



