<?php
/******************************
 * $File: borrow.style.install.php
 * $Description:���ʽ��װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "CREATE TABLE IF NOT EXISTS `{borrow_fee}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` varchar(50) NOT NULL COMMENT '��ʶ��',
  `name` varchar(50) NOT NULL COMMENT '����',
  `title` varchar(50) NOT NULL COMMENT '����',
  `status` int(11) NOT NULL COMMENT '״̬',
  `borrow_types` varchar(200) NOT NULL COMMENT '��ʽ',
  `type` varchar(50) NOT NULL COMMENT '����',
  `fee_type` int(2) NOT NULL COMMENT '��������',
  `vip_borrow_scale` decimal(11,2) NOT NULL COMMENT 'vip�������',
  `all_borrow_scale` decimal(11,2) NOT NULL COMMENT '��Ա�������',
  `vip_borrow_scales` decimal(11,2) NOT NULL COMMENT 'vip��������ʽ',
  `vip_borrow_scales_month` int(11) NOT NULL COMMENT 'vip��Ա����������',
  `vip_borrow_scales_scale` decimal(11,2) NOT NULL COMMENT 'vip��Ա���������ı���',
  `vip_borrow_scales_max` decimal(11,2) NOT NULL COMMENT 'vip���ı������ֵ',
  `all_borrow_scales` decimal(11,2) NOT NULL COMMENT '��ͨ��Ա��������ʽ',
  `all_borrow_scales_month` int(11) NOT NULL COMMENT '��ͨ��Ա��������ʽ������',
  `all_borrow_scales_scale` decimal(11,2) NOT NULL COMMENT '��ͨ��Ա��ʽ�����ı���',
  `all_borrow_scales_max` decimal(11,2) NOT NULL COMMENT '��ͨ��Ա��ߵ�����',
  `vip_advance_scale` decimal(11,2) NOT NULL COMMENT 'vip��ǰ����ı���',
  `vip_advance_days` int(11) NOT NULL COMMENT 'vip��ǰ���������',
  `all_advance_scale` decimal(11,2) NOT NULL COMMENT '��ͨ��Ա��ǰ����ı���',
  `all_advance_days` int(11) NOT NULL COMMENT '��ͨ��Ա��ǰ����ı���',
  `vip_repay_scale` decimal(11,2) NOT NULL COMMENT 'vip�����������',
  `all_repay_scale` decimal(11,2) NOT NULL COMMENT '��ͨ��Ա�����������',
  `vip_borrow_late_scale` decimal(11,2) NOT NULL COMMENT 'vip��������ڵı���',
  `vip_borrow_late_days` int(11) NOT NULL COMMENT 'vip��������ڵ�����',
  `all_borrow_late_scale` decimal(11,2) NOT NULL COMMENT '��ͨ��������ڱ���',
  `all_borrow_late_days` int(11) NOT NULL COMMENT '��ͨ��������ڵ�����',
  `vip_tender_late_scale` decimal(11,2) NOT NULL COMMENT 'vipͶ���������տ�ı���',
  `vip_tender_late_days` int(11) NOT NULL COMMENT 'vipͶ�������ڵ�����',
  `all_tender_late_scale` decimal(11,2) NOT NULL COMMENT '��ͨͶ�������ڵı���',
  `all_tender_late_days` int(11) NOT NULL COMMENT '��ͨͶ�������ڵ�����',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  ;";
$mysql->db_querys($sql);

?>