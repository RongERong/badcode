<?
/******************************
 * $File: linkages.install.php
 * $Description: ��������
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "

CREATE TABLE IF NOT EXISTS `{linkages}` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '����',
  `nid` varchar(50) NOT NULL COMMENT '��ʶ��',
  `value` text NOT NULL COMMENT 'ֵ',
  `status` smallint(2) unsigned DEFAULT '0' COMMENT '״̬',
  `order` smallint(6) DEFAULT '0' COMMENT '����',
  PRIMARY KEY (`id`),
   KEY `type_id`(`type_id`)
) ENGINE=MyISAM  ;

CREATE TABLE IF NOT EXISTS `{linkages_class}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  `order` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid`(`nid`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `{linkages_type}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '��������',
  `nid` varchar(50) DEFAULT NULL COMMENT '���ͱ�ʾ��',
  `pid` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL COMMENT '����ģ��',
  `order` int(11) NOT NULL COMMENT '����',
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `code` (`code`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM;

";

$mysql->db_querys($sql);
