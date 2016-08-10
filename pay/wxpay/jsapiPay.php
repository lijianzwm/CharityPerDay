<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/18
 * Time: 下午1:54
 */

require_once "WxPay.JsApiPay.php";

$orderNo = date("YmdHis");
$totalFee = "1";
$productId = "123123123";

$product['product_id'] = $productId;
$product['goods_tag'] = "准提心脉";
$product['body'] = "准提心脉";
$product['attach'] = "准提心脉";
$product['out_trade_no'] = $orderNo;
$product['fee'] = intval($totalFee*100);
$notifyUrl = "http://" . $_SERVER['HTTP_HOST'] . "/ztsx/pay/wxpay/notify.php";
$notifyUrl = "http://paysdk.weixin.qq.com/example/notify.php";

$tools = new JsApiPay();

if(isset($_GET['code'])){
    $openId = $tools->GetOpenid($_GET['code']);
}else{
    $openId = $tools->GetOpenid();
}

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetProduct_id($product['product_id']);
$input->SetGoods_tag($product['goods_tag']);
$input->SetBody($product['body']);
$input->SetAttach($product['attach']);
$input->SetTotal_fee($product['fee']);
$input->SetOut_trade_no(date("YmdHis"));
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetNotify_url($notifyUrl);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付</title>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
</head>
<body>
<br/>
<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $totalFee; ?></span>元</b></font><br/><br/>
<div align="center">
    <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
</div>
</body>
</html>





