<?php
/******************************
 * $File: borrow.fee.install.php
 * $Description: ���õİ�װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
$sql = "CREATE TABLE IF NOT EXISTS `{borrow_style}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` varchar(50) NOT NULL COMMENT '��ʾ��',
  `status` int(11) NOT NULL COMMENT '�Ƿ�����',
  `name` varchar(50) NOT NULL COMMENT '����',
  `title` varchar(50) NOT NULL COMMENT '���ƣ��ɸ�',
  `contents` longtext NOT NULL COMMENT '�㷨��ʽ',
  `remark` longtext NOT NULL COMMENT '��ע',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  ;";
$mysql->db_querys($sql);

?>