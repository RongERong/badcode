<?php
/******************************
 * $File: borrow.flag.install.php
 * $Description:��ǩ��װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "CREATE TABLE IF NOT EXISTS `{borrow_flag}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '����',
  `title` varchar(200) NOT NULL COMMENT '����',
  `status` int(2) NOT NULL COMMENT '״̬',
  `nid` varchar(100) NOT NULL COMMENT '��ʶ��',
  `style` int(2) NOT NULL COMMENT 'ͼƬ����ʽ',
  `fileurl` varchar(200) NOT NULL COMMENT '����ͼƬģʽ',
  `upfiles_id` int(11) NOT NULL COMMENT '�ϴ����ļ�id',
  `remark` varchar(200) NOT NULL COMMENT '��ע',
  `order` int(10) NOT NULL COMMENT '����',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  ;";
$mysql->db_querys($sql);

?>