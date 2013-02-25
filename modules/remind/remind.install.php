<?
/******************************
 * $File: remind.install.php
 * $Description: ����ģ�鰲װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "
DROP TABLE IF EXISTS `{remind}`;
CREATE TABLE IF NOT EXISTS `{remind}` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`nid` varchar(50) NOT NULL,
	`status` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '״̬',
	`order` smallint(6) NOT NULL DEFAULT '0' COMMENT '����',
	`type_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '����',
	`message` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '����Ϣ',
	`email` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '����',
	`phone` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '�ֻ�',
	`addtime` int(10)  NOT NULL DEFAULT '0',
	`addip` char(20)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `nid`(`nid`),
  KEY `type_id`(`type_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{remind_user}` (
 	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` smallint(11) unsigned NOT NULL,
	`remind` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id`(`user_id`),
  KEY `remind_id`(`remind_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{remind_type}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `nid` varchar(50) NOT NULL,
  `addtime` int(10)  NOT NULL DEFAULT '0',
  `addip` char(20)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name`(`name`),
  KEY `nid`(`nid`)
) ENGINE=MyISAM ;
";
$mysql->db_querys($sql);
