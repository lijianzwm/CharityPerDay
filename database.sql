-- 随喜订单表
CREATE TABLE IF NOT EXISTS `sx_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `order_no` varchar(255) NOT NULL COMMENT '订单编号',
  `type_id` int(11) NOT NULL COMMENT '随喜项目id',
  `type_name` varchar(255) NOT NULL COMMENT '随喜项目名称',
  `money` decimal(11,2) NOT NULL COMMENT '随喜金额',
  `name` varchar(20) NOT NULL COMMENT '施主姓名',
  `payment` enum('WXPAY','ALIPAY') NOT NULL COMMENT '支付方式',
  `access` varchar(255) DEFAULT NULL COMMENT '通过哪个版本下的单',
  `status` enum('NONE','PAYSUCCESS') NOT NULL COMMENT '是否支付成功',
  `create_time` int(11) NOT NULL COMMENT '订单创建时间',
  `huixiang` TEXT DEFAULT NULL COMMENT '回向',
  `beizhu` varchar(255) DEFAULT NULL COMMENT '备注',
  `gongbu` enum('PRIVATE','PUBLIC') NOT NULL DEFAULT 'PUBLIC' COMMENT '是否公开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--随喜类型
CREATE TABLE IF NOT EXISTS `sx_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL COMMENT '随喜类型名称',
  `description` text DEFAULT NULL COMMENT '随喜简介',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;





