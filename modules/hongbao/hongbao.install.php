<?php
/******************************
 * $File: hongbao.install.php
 * $Description: ��װ������ݿ�
 * $Author: ada 
 * $Time:2012-12-11
 * $Update:
 * $UpdateDate:  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = " 

CREATE TABLE IF NOT EXISTS `deayou_hongbao_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` varchar(50) NOT NULL COMMENT '��ʶ��',
  `name` varchar(50) NOT NULL COMMENT '�������',
  `status` int(11) NOT NULL COMMENT '״̬',
  `desc` varchar(255) NOT NULL COMMENT '��ע',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

CREATE TABLE IF NOT EXISTS `deayou_hongbao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` varchar(100) NOT NULL COMMENT '��ʶ��',
  `name` varchar(255) NOT NULL COMMENT '�������',
  `status` int(2) NOT NULL COMMENT '���״̬',
  `order` int(11) NOT NULL COMMENT '����',
  `type_id` int(11) NOT NULL COMMENT '����id',
  `money` int(11) NOT NULL COMMENT '���',
  `percent` int(11) NOT NULL COMMENT '�к������',
  `available_time` int(11) NOT NULL COMMENT '��Чʱ��',
  `explode_time` int(11) NOT NULL COMMENT '���ʱ��',
  `mode` int(2) NOT NULL COMMENT '����ģʽ,0Ϊ�ֶ�,1Ϊ�Զ�,Ĭ��Ϊ0',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

CREATE TABLE IF NOT EXISTS `deayou_hongbao_mingxi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `name` varchar(255) NOT NULL COMMENT '�������',
  `money` int(11) NOT NULL COMMENT '���',
  `addtime` int(11) NOT NULL COMMENT '����ʱ��',
  `status` int(2) NOT NULL COMMENT '״̬��0Ϊʧ�ܣ�1Ϊ�ɹ���Ĭ��Ϊ0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

";

$mysql->db_querys($sql);
?>