<?php
/******************************
 * $File: credit.install.php
 * $Description: ����װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
$sql = "

CREATE TABLE IF NOT EXISTS `{credit}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '��ԱID',
  `credit` int(11) NOT NULL DEFAULT '0',
  `credits` longtext NOT NULL COMMENT '�ܻ���',
  PRIMARY KEY (`id`),
  KEY `user_id`(`user_id`)
) ENGINE=MyISAM  ;



CREATE TABLE IF NOT EXISTS `{credit_class}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid`(`nid`)
) ENGINE=MyISAM  ;


INSERT INTO `{credit_class}` (`id`, `name`, `nid`) VALUES
(1, '����', 'credit'),
(2, '���', 'gold');


CREATE TABLE IF NOT EXISTS `{credit_log}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `article_id` int(11) NOT NULL,
  `nid` varchar(200) NOT NULL,
  `value` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `remark` longtext NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `update_time` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id`(`user_id`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{credit_rank}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '�ȼ�����',
  `nid` varchar(100) NOT NULL COMMENT '����',
  `rank` varchar(50) DEFAULT '0' COMMENT '�ȼ�',
  `class_id` int(11) NOT NULL COMMENT '���ַ���',
  `point1` int(11) DEFAULT '0' COMMENT '��ʼ��ֵ',
  `point2` int(11) DEFAULT '0' COMMENT '����ֵ',
  `pic` varchar(100) DEFAULT NULL COMMENT 'ͼƬ',
  `remark` varchar(256) DEFAULT NULL COMMENT '��ע',
  `addtime` int(11) DEFAULT NULL COMMENT '���ʱ��',
  `addip` varchar(30) DEFAULT NULL COMMENT '���IP',
  PRIMARY KEY (`id`),
  KEY `nid`(`nid`),
  KEY `class_id`(`class_id`),
  KEY `point1`(`point1`),
  KEY `point2`(`point2`),
  KEY `point12`(`point1`,`point2`),
  KEY `point12_classid`(`point1`,`point2`,`class_id`),
  KEY `point12_nid`(`point1`,`point2`,`nid`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{credit_type}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '��������',
  `nid` varchar(50) DEFAULT NULL COMMENT '���ִ���',
  `code` varchar(50) NOT NULL COMMENT 'ģ��',
  `status` int(2) NOT NULL COMMENT '״̬',
  `class_id` varchar(50) NOT NULL COMMENT '����',
  `value` int(11) DEFAULT '0' COMMENT '������ֵ',
  `cycle` tinyint(1) DEFAULT '2' COMMENT '�������ڣ�1:һ��,2:ÿ��,3:�������,4:����',
  `award_times` tinyint(4) DEFAULT NULL COMMENT '��������,0:����',
  `interval` int(11) DEFAULT '1' COMMENT 'ʱ��������λ����',
  `remark` varchar(256) DEFAULT NULL COMMENT '��ע',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid`(`nid`)
) ENGINE=MyISAM;

";
$mysql->db_querys($sql);
?>
