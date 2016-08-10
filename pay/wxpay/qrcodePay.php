<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/4
 * Time: 下午6:36
 */

require_once ('WxPayService.php');

$orderNo = $_POST['title'];
$totalFee = $_POST['total_fee'];

$product['product_id'] = $_POST['product_id'];
$product['goods_tag'] = "准提心脉";
$product['body'] = "准提心脉";
$product['attach'] = "准提心脉";
$product['out_trade_no'] = $_REQUEST['title'];
$product['fee'] = intval($totalFee*100);

$notifyUrl = "http://139.129.22.123/pay/wxpay/notify.php";
//$notifyUrl = "http://" . $_SERVER['HTTP_HOST'] . "/ztsx/pay/wxpay/notify.php";

$img = QRCodePay($product, $notifyUrl);

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
</head>

<body style="background-color:#FFFFFF;">

<div class="container text-center">
    <h4 class="lead" style="color:#0078B6; font-weight:bold;">微信扫码支付</h4>
    <img src="<?php echo $img;?>" style="width:150px;height:150px;"/>
    <p>手机请长安图片选择识别二维码</p>
</div>
<script src="../../js/jquery.min.js"></script>
<script type="text/javascript">
    var countTime = 0;
    setInterval(function(){
        ++countTime;
        if( countTime > 3600 ){
            alert("二维码已失效");
        }

        $.ajax({
            url: "checkPayStatus.php",
            data: {
                out_trade_no:<?php echo $product['out_trade_no'] ?>
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    alert("支付成功!");
                    window.location.href="http://www.zhunti.net/channels/1141.html"
                }
            },
            error: function () {
                console.log("ajax请求失败!");
            }
        });
    }, 2000);
</script>

</body>