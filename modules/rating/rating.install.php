<?
/******************************
 * $File: ratting.install.php
 * $Description: �û���Ϣ���ϵİ�װ
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "

CREATE TABLE IF NOT EXISTS `{rating_assets}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `assetstype` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `account` varchar(30) NOT NULL,
  `other` text NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{rating_company}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�����û�',
  `status` int(2) NOT NULL COMMENT '��֤��0��ʾ����У�1��ʾͨ����2��ʾδͨ��',
  `name` varchar(100) NOT NULL COMMENT '��˾����',
  `tel` varchar(100) NOT NULL COMMENT '�绰',
  `address` varchar(200) NOT NULL COMMENT '��˾��ַ',
  `license_num` varchar(30) NOT NULL COMMENT 'ִ�պ�',
  `tax_num_guo` varchar(30) NOT NULL COMMENT '˰���(��˰)',
  `tax_num_di` varchar(30) NOT NULL COMMENT '˰���(��˰)',
  `rent_time` varchar(30) NOT NULL COMMENT '����',
  `rent_money` varchar(30) NOT NULL COMMENT '���',
  `hangye` varchar(30) NOT NULL,
  `people` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `{rating_contact}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `linkman2` varchar(30) NOT NULL COMMENT '��ż',
  `phone2` varchar(30) NOT NULL COMMENT '��ż�ֻ�',
  `linkman3` varchar(30) NOT NULL COMMENT 'ֱϵ����',
  `phone3` varchar(30) NOT NULL COMMENT 'ֱϵ�����ֻ�',
  `linkman4` varchar(30) NOT NULL COMMENT 'ͬ��',
  `phone4` varchar(30) NOT NULL COMMENT 'ͬ�µ绰',
  `linkman5` varchar(30) NOT NULL COMMENT '������ϵ��',
  `phone5` varchar(30) NOT NULL COMMENT '������ϵ�˵绰',
  `verify_userid` int(11) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `addtime` varchar(30) NOT NULL,
  `addip` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{rating_educations}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�����û�',
  `status` int(2) NOT NULL COMMENT '��֤��0��ʾ����У�1��ʾͨ����2��ʾδͨ��',
  `name` varchar(100) NOT NULL COMMENT 'ѧУ����',
  `degree` varchar(100) NOT NULL COMMENT 'ѧ��',
  `in_year` varchar(100) NOT NULL COMMENT '��ѧʱ��',
  `professional` varchar(100) NOT NULL COMMENT 'רҵ',
  `verify_userid` int(11) NOT NULL COMMENT '����û�id',
  `verify_remark` varchar(200) NOT NULL COMMENT '��˱�ע',
  `verify_time` varchar(50) NOT NULL COMMENT '���ʱ��',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{rating_finance}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `type` int(11) NOT NULL,
  `use_type` int(2) NOT NULL COMMENT '1Ϊ���룬2Ϊ֧��',
  `account` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `other` varchar(200) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  ;

CREATE TABLE IF NOT EXISTS `{rating_houses}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�����û�',
  `status` int(2) NOT NULL COMMENT '��֤��0��ʾ����У�1��ʾͨ����2��ʾδͨ��',
  `name` varchar(100) NOT NULL COMMENT '��˾����',
  `address` varchar(250) NOT NULL COMMENT '���ڵ�',
  `areas` varchar(200) NOT NULL COMMENT '�������',
  `in_year` varchar(100) NOT NULL COMMENT '�������',
  `repay` varchar(100) NOT NULL COMMENT '����״��',
  `holder1` varchar(100) NOT NULL COMMENT '����Ȩ1',
  `right1` varchar(100) NOT NULL COMMENT '�ݶ�1',
  `holder2` varchar(100) NOT NULL COMMENT '����Ȩ2',
  `right2` varchar(100) NOT NULL COMMENT '�ݶ�2',
  `load_year` varchar(100) NOT NULL COMMENT '��������',
  `repay_month` varchar(100) NOT NULL COMMENT 'ÿ�»���',
  `balance` varchar(100) NOT NULL COMMENT '������',
  `bank` varchar(100) NOT NULL COMMENT '����',
  `verify_userid` int(11) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  ;


CREATE TABLE IF NOT EXISTS `{rating_info}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `realname` varchar(30) NOT NULL,
  `code` int(11) NOT NULL,
  `card_id` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `phone_num` varchar(30) NOT NULL COMMENT '�ֻ���',
  `sex` int(2) NOT NULL,
  `marry` int(2) NOT NULL,
  `children` int(2) NOT NULL,
  `income` int(2) NOT NULL,
  `dignity` int(2) NOT NULL COMMENT '���',
  `birthday` varchar(30) NOT NULL,
  `school_year` varchar(30) NOT NULL,
  `school` varchar(100) NOT NULL,
  `edu` int(11) NOT NULL,
  `house` int(11) NOT NULL,
  `province` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `is_car` int(2) NOT NULL,
  `address` varchar(200) NOT NULL COMMENT '�־�ס��ַ',
  `phone` varchar(30) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM   ;


CREATE TABLE IF NOT EXISTS `{rating_job}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�����û�',
  `type` int(2) NOT NULL,
  `status` int(2) NOT NULL COMMENT '��֤��0��ʾ����У�1��ʾͨ����2��ʾδͨ��',
  `name` varchar(100) NOT NULL COMMENT '��˾����',
  `industry` varchar(30) NOT NULL,
  `department` varchar(50) NOT NULL COMMENT '����',
  `office` varchar(200) NOT NULL COMMENT 'ְλ',
  `address` varchar(100) NOT NULL,
  `peoples` varchar(30) NOT NULL COMMENT '����',
  `worktime1` varchar(100) NOT NULL COMMENT '��ְʱ��',
  `tel` varchar(30) NOT NULL,
  `verify_userid` int(11) NOT NULL,
  `verify_remark` varchar(200) NOT NULL,
  `verify_time` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM ;
";

$mysql->db_querys($sql);
