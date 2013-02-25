<?php
/******************************
 * $File: account.install.php
 * $Description: �ʽ�װ
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/


if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "

CREATE TABLE IF NOT EXISTS `{account}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '�û�����',
  `total` decimal(11,2) DEFAULT '0.00' COMMENT '�ʽ��ܶ�',
  `income` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '����',
  `expend` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '֧��',
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '���ý��',
  `balance_cash` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '������',
  `balance_frost` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '��������',
  `frost` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '������',
  `await` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '���ս��',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{account_balance}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` varchar(32) NOT NULL COMMENT '���׺�',
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `type` varchar(50) NOT NULL COMMENT '����',
  `money` decimal(11,2) NOT NULL,
  `total` decimal(11,2) NOT NULL COMMENT '��վ�ܽ��',
  `balance` decimal(11,2) NOT NULL COMMENT '��׬���',
  `income` decimal(11,2) NOT NULL COMMENT '����',
  `expend` decimal(11,2) NOT NULL COMMENT '֧��',
  `remark` text NOT NULL COMMENT '��ע',
  `addtime` varchar(32) NOT NULL,
  `addip` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `userid_type` (`user_id`,`type`),
  KEY `userid_nid_type` (`user_id`,`nid`,`type`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{account_bank}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT '��������',
  `nid` varchar(50) NOT NULL,
  `litpic` varchar(255) DEFAULT NULL COMMENT '����ͼ��ַ',
  `cash_money` varchar(100) DEFAULT NULL COMMENT '������ֽ��',
  `reach_day` int(11) NOT NULL COMMENT '��������',
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `name` (`name`)
) ENGINE=MyISAM ;



CREATE TABLE IF NOT EXISTS `{account_cash}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '�û�ID',
  `nid` varchar(100) NOT NULL,
  `status` int(2) DEFAULT '0' COMMENT '״̬',
  `account` varchar(100) DEFAULT '0' COMMENT '�˺�',
  `bank` varchar(302) DEFAULT NULL COMMENT '��������',
  `bank_id` int(30) NOT NULL,
  `branch` varchar(100) DEFAULT NULL COMMENT '֧��',
  `province` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `total` decimal(11,2) DEFAULT '0.00' COMMENT '�ܶ�',
  `credited` decimal(11,2) DEFAULT '0.00' COMMENT '�����ܶ�',
  `fee` varchar(20) DEFAULT '0' COMMENT '������',
  `verify_userid` decimal(11,2) DEFAULT NULL,
  `verify_time` int(11) DEFAULT NULL,
  `verify_remark` varchar(250) DEFAULT NULL,
  `addtime` varchar(11) DEFAULT NULL,
  `addip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM  ;

CREATE TABLE IF NOT EXISTS `{account_log}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` varchar(50) NOT NULL COMMENT '���׺�',
  `user_id` int(11) DEFAULT '0' COMMENT '�û�ID',
  `type` varchar(100) DEFAULT NULL COMMENT '����',
  `total` decimal(11,2) DEFAULT '0.00' COMMENT '�ܽ��',
  `total_old` decimal(11,2) NOT NULL COMMENT '�ϴ��ܽ��',
  `money` decimal(11,2) DEFAULT NULL COMMENT '�������',
  `income` decimal(11,2) DEFAULT '0.00' COMMENT '����',
  `income_old` decimal(11,2) NOT NULL,
  `income_new` decimal(11,2) NOT NULL,
  `expend` decimal(11,2) DEFAULT '0.00' COMMENT '֧��',
  `expend_old` decimal(11,2) NOT NULL,
  `expend_new` decimal(11,2) NOT NULL,
  `balance` decimal(11,2) DEFAULT '0.00' COMMENT '�������',
  `balance_old` decimal(11,2) NOT NULL COMMENT '�ɵĿ������',
  `balance_new` decimal(11,2) NOT NULL COMMENT '���µĽ��',
  `balance_cash` decimal(11,2) NOT NULL COMMENT '�����ֽ��',
  `balance_cash_old` decimal(11,2) NOT NULL,
  `balance_cash_new` decimal(11,2) NOT NULL,
  `balance_frost` decimal(11,2) NOT NULL COMMENT '�������ֶ�����',
  `balance_frost_old` decimal(11,2) NOT NULL,
  `balance_frost_new` decimal(11,2) NOT NULL,
  `frost` decimal(11,2) NOT NULL COMMENT '������',
  `frost_old` decimal(11,2) NOT NULL COMMENT '����ɽ��',
  `frost_new` decimal(11,2) NOT NULL COMMENT '�µĶ�����',
  `await` decimal(11,2) NOT NULL COMMENT '���ս��',
  `await_old` decimal(11,2) NOT NULL,
  `await_new` decimal(11,2) NOT NULL COMMENT '�µĴ������',
  `to_userid` int(11) DEFAULT NULL COMMENT '���׶Է�',
  `remark` varchar(250) DEFAULT '0' COMMENT '��ע',
  `addtime` varchar(11) DEFAULT NULL,
  `addip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid_2` (`nid`),
  KEY `nid` (`nid`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `userid_type` (`user_id`,`type`),
  KEY `userid_nid_type` (`user_id`,`nid`,`type`)
) ENGINE=MyISAM   ;


CREATE TABLE IF NOT EXISTS `{account_recharge}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` varchar(32) DEFAULT NULL COMMENT '������',
  `user_id` int(11) DEFAULT '0' COMMENT '�û�ID',
  `status` int(2) DEFAULT '0' COMMENT '״̬',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '���',
  `fee` decimal(11,2) NOT NULL COMMENT '����',
  `balance` decimal(11,2) NOT NULL COMMENT 'ʵ�ʵ������',
  `payment` varchar(100) DEFAULT NULL COMMENT '��������',
  `url` longtext NOT NULL COMMENT '��ַ',
  `sign` longtext NOT NULL COMMENT '����',
  `return` text COMMENT '���ص���ֵ',
  `type` varchar(10) DEFAULT '0' COMMENT '����',
  `remark` varchar(250) DEFAULT '0' COMMENT '��ע',
  `verify_userid` int(11) DEFAULT '0' COMMENT '�����',
  `verify_time` varchar(11) DEFAULT NULL COMMENT '���ʱ��',
  `verify_remark` varchar(250) DEFAULT '' COMMENT '��˱�ע',
  `addtime` varchar(11) DEFAULT NULL,
  `addip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid_2` (`nid`),
  KEY `user_id` (`user_id`),
  KEY `verify_userid` (`verify_userid`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{account_users}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` varchar(32) NOT NULL COMMENT '���׺�',
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `type` varchar(50) NOT NULL,
  `money` decimal(11,2) NOT NULL,
  `total` decimal(11,2) NOT NULL COMMENT '�û��ܽ��',
  `balance` decimal(11,2) NOT NULL COMMENT '���',
  `income` decimal(11,2) NOT NULL COMMENT '����',
  `expend` decimal(11,2) NOT NULL COMMENT '֧��',
  `remark` text NOT NULL COMMENT '��ע',
  `addtime` varchar(32) NOT NULL,
  `addip` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `userid_type` (`user_id`,`type`),
  KEY `userid_nid_type` (`user_id`,`nid`,`type`)
) ENGINE=MyISAM   ;


CREATE TABLE IF NOT EXISTS `{account_users_bank}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '�û�ID',
  `status` int(2) NOT NULL,
  `account` varchar(100) DEFAULT NULL COMMENT '�˺�',
  `bank` varchar(50) DEFAULT NULL COMMENT '��������',
  `branch` varchar(100) DEFAULT NULL COMMENT '֧��',
  `province` int(5) DEFAULT '0' COMMENT 'ʡ��',
  `city` int(5) DEFAULT '0' COMMENT '����',
  `area` int(5) DEFAULT '0' COMMENT '��',
  `addtime` varchar(11) DEFAULT NULL,
  `addip` varchar(15) DEFAULT NULL,
  `update_time` varchar(50) NOT NULL,
  `update_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM ;



CREATE TABLE IF NOT EXISTS `{account_web}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `money` varchar(10) NOT NULL,
  `type` varchar(30) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `remark` text NOT NULL,
  `addtime` varchar(30) NOT NULL,
  `addip` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `nid` (`nid`),
  KEY `user_id` (`nid`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{account_payment}` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '����',
  `nid` varchar(100) DEFAULT NULL COMMENT '��ʶ��',
  `status` smallint(3) unsigned DEFAULT '0' COMMENT '״̬',
  `litpic` varchar(100) NOT NULL COMMENT '����ͼ',
  `style` int(2) DEFAULT NULL COMMENT '����',
  `config` longtext COMMENT '�����Ϣ',
  `description` longtext COMMENT '����',
  `order` smallint(3) unsigned DEFAULT '0' COMMENT '����',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
";

$mysql->db_querys($sql);
