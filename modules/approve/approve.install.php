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

CREATE TABLE IF NOT EXISTS `{approve}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` longtext NOT NULL,
  `credit` int(11) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_remark` varchar(250) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `{approve_edu}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `id5_status` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  `graduate` varchar(10) NOT NULL COMMENT '��ҵѧУ',
  `speciality` varchar(100) NOT NULL,
  `degree` varchar(50) NOT NULL,
  `enrol_date` varchar(50) NOT NULL,
  `graduate_date` varchar(50) NOT NULL,
  `edu_pic` int(11) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_id5_userid` int(11) NOT NULL,
  `verify_id5_time` varchar(50) NOT NULL,
  `verify_id5_remark` varchar(200) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM   ;



CREATE TABLE IF NOT EXISTS `{approve_edu_id5}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `card_type` varchar(50) NOT NULL,
  `graduate` varchar(100) NOT NULL COMMENT '��ҵԺУ',
  `speciality` varchar(100) NOT NULL COMMENT 'רҵ',
  `degree` varchar(50) NOT NULL COMMENT 'ѧ��',
  `enrol_date` varchar(50) NOT NULL COMMENT '��ѧ���',
  `graduate_date` int(50) NOT NULL COMMENT '��ҵ���',
  `result` varchar(100) NOT NULL COMMENT '��ҵ����',
  `style` varchar(50) NOT NULL COMMENT 'ѧ������',
  `value` varchar(200) NOT NULL,
  `message_status` int(2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `photo` text NOT NULL COMMENT '��Ƭ',
  `realname` varchar(50) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `contents` text NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{approve_id5}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `card_type` varchar(50) NOT NULL,
  `policeadd` varchar(100) NOT NULL,
  `checkphoto` text NOT NULL,
  `idname` varchar(50) NOT NULL,
  `value` varchar(200) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `message_status` int(2) NOT NULL,
  `identitycard` varchar(200) NOT NULL,
  `compstatus` int(2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `contents` text NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `card_type` (`card_type`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{approve_realname}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `realname` varchar(100) NOT NULL,
  `card_id` varchar(100) NOT NULL,
  `card_pic` varchar(100) NOT NULL,
  `card_pic1` varchar(200) NOT NULL,
  `card_pic2` varchar(200) NOT NULL,
  `id5_status` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  `type` varchar(30) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_id5_userid` int(11) NOT NULL,
  `verify_id5_time` varchar(50) NOT NULL,
  `verify_id5_remark` varchar(200) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `realname` (`realname`),
  KEY `card_id` (`card_id`),
  KEY `realname_cardid` (`realname`,`card_id`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{approve_sms}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `type` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `credit` int(11) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_remark` varchar(250) NOT NULL,
  `check_time` varchar(30) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `phone` (`phone`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{approve_smslog}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `type` varchar(50) NOT NULL COMMENT '��������',
  `phone` varchar(50) NOT NULL COMMENT '�����ֻ�',
  `status` int(2) NOT NULL COMMENT '����״̬',
  `contents` varchar(250) NOT NULL COMMENT '��������',
  `send_code` longtext NOT NULL COMMENT '���͵Ĵ���',
  `send_return` varchar(50) NOT NULL COMMENT '���ͷ�����Ϣ',
  `send_status` int(2) NOT NULL DEFAULT '0' COMMENT '����״̬',
  `send_time` varchar(50) NOT NULL COMMENT '����ʱ��',
  `code` varchar(50) NOT NULL COMMENT '��֤��',
  `code_status` int(2) NOT NULL COMMENT '��֤��״̬',
  `code_time` varchar(50) NOT NULL,
  `addtime` varchar(30) DEFAULT NULL COMMENT '���ʱ��',
  `addip` varchar(30) DEFAULT NULL COMMENT '���ip',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `phone` (`phone`),
  KEY `status` (`status`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{approve_video}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `credit` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_remark` varchar(250) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  ;

";

$mysql->db_querys($sql);
