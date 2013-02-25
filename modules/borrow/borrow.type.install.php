<?php
/******************************
 * $File: borrow.style.int.php
 * $Description: ���ʽ��̨����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
$sql = "CREATE TABLE IF NOT EXISTS `{borrow_type}` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `nid` varchar(50) NOT NULL COMMENT '���ͱ�ʶ��',
  `status` int(11) NOT NULL COMMENT '״̬',
  `name` varchar(50) NOT NULL COMMENT '����',
  `title` varchar(50) NOT NULL COMMENT '����',
  `apr_first` decimal(11,2) NOT NULL COMMENT '��ʼ������',
  `apr_end` decimal(11,2) NOT NULL COMMENT '����������',
  `period_first` int(11) NOT NULL COMMENT '��ʼ��Ч��',
  `period_end` int(11) NOT NULL COMMENT '��ʼ������',
  `validate_first` int(11) NOT NULL COMMENT '��ʼ��Ч��',
  `validate_end` int(11) NOT NULL COMMENT '������Ч��',
  `styles` varchar(200) NOT NULL COMMENT '���ʽ',
  `frost_scale` decimal(11,2) NOT NULL COMMENT '���ᱣ֤�����',
  `late_days` int(11) NOT NULL COMMENT '��ÿ�ʼ���е渶',
  `vip_late_scale` decimal(11,2) NOT NULL COMMENT 'vip���ڵ渶��Ϣ����',
  `all_late_scale` decimal(11,2) NOT NULL COMMENT '��ͨ��Ա�渶�������',
  `system_borrow_full_status` int(11) NOT NULL COMMENT 'ϵͳ�������',
  `system_borrow_repay_status` int(11) NOT NULL COMMENT 'ϵͳ�û�����',
  `system_web_repay_status` int(11) NOT NULL COMMENT 'ϵͳ�����Զ��渶',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  ;";
$mysql->db_querys($sql);

?>