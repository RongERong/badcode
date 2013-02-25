<?
/******************************
 * $File: articles.install.php
 * $Description: ���°�װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
$sql = "

CREATE TABLE IF NOT EXISTS `{articles}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '����û�',
  `type_id` varchar(100) DEFAULT '0' COMMENT '������Ŀ',
  `nid` varchar(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '����',
  `title` varchar(200) DEFAULT NULL COMMENT '��Ҫ����',
  `status` int(2) DEFAULT '0' COMMENT '����״̬',
  `litpic` varchar(255) DEFAULT NULL COMMENT '��������ͼ',
  `flag` varchar(250) DEFAULT NULL COMMENT '��������',
  `source` varchar(50) DEFAULT NULL COMMENT '������Դ',
  `public` int(2) NOT NULL,
  `password` varchar(50) NOT NULL,
  `publish` varchar(50) DEFAULT NULL COMMENT '�Ƿ񷢲�',
  `author` varchar(50) DEFAULT NULL COMMENT '����',
  `summary` varchar(255) DEFAULT NULL COMMENT '��Ҫ����',
  `contents` text COMMENT '����',
  `credits` longtext NOT NULL COMMENT '����',
  `tags` varchar(200) NOT NULL COMMENT '��ǩ',
  `order` int(11) DEFAULT '0' COMMENT '����',
  `hits` int(11) DEFAULT '0' COMMENT '�����',
  `comment_status` int(11) DEFAULT '0' COMMENT '�Ƿ�����',
  `comment_times` int(11) NOT NULL COMMENT '���۴���',
  `comment_usertype` varchar(50) NOT NULL COMMENT '�����û�',
  `addtime` varchar(50) DEFAULT NULL COMMENT '���ʱ��',
  `addip` varchar(50) DEFAULT NULL COMMENT '���ip',
  `update_time` varchar(50) NOT NULL,
  `update_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  KEY `name` (`name`),
  KEY `typeid_name` (`type_id`,`name`),
  KEY `typeid_name_status` (`type_id`,`name`,`status`),
  FULLTEXT KEY `type_id1` (`type_id`)
) ENGINE=MyISAM  ;



CREATE TABLE IF NOT EXISTS `{articles_pages}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '����û�',
  `nid` varchar(100) NOT NULL COMMENT '��ʶ��',
  `pid` int(11) DEFAULT '0' COMMENT '������Ŀ',
  `name` varchar(255) DEFAULT NULL COMMENT '����',
  `title` varchar(200) DEFAULT NULL COMMENT '��Ҫ����',
  `status` int(2) DEFAULT '0' COMMENT '����״̬',
  `litpic` varchar(255) DEFAULT NULL COMMENT '��������ͼ',
  `flag` varchar(250) DEFAULT NULL COMMENT '��������',
  `source` varchar(50) DEFAULT NULL COMMENT '������Դ',
  `public` int(2) NOT NULL,
  `password` varchar(50) NOT NULL,
  `publish` varchar(50) DEFAULT NULL COMMENT '�Ƿ񷢲�',
  `author` varchar(50) DEFAULT NULL COMMENT '����',
  `summary` varchar(255) DEFAULT NULL COMMENT '��Ҫ����',
  `contents` text COMMENT '����',
  `tags` varchar(200) NOT NULL COMMENT '��ǩ',
  `order` int(11) DEFAULT '0' COMMENT '����',
  `hits` int(11) DEFAULT '0' COMMENT '�����',
  `comment_status` int(11) DEFAULT '0' COMMENT '�Ƿ�����',
  `comment_times` int(11) NOT NULL COMMENT '���۴���',
  `comment_usertype` varchar(50) NOT NULL COMMENT '�����û�',
  `addtime` varchar(50) DEFAULT NULL COMMENT '���ʱ��',
  `addip` varchar(50) DEFAULT NULL COMMENT '���ip',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `pid` (`pid`),
  KEY `nid` (`nid`),
  KEY `name` (`name`),
  KEY `status` (`status`),
  KEY `pid_name` (`pid`,`name`)
) ENGINE=MyISAM   ;


CREATE TABLE IF NOT EXISTS `{articles_type}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `contents` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '10' COMMENT '����',
  PRIMARY KEY (`id`),
  KEY `user_id` (`name`),
  KEY `page_id` (`nid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  ;

INSERT INTO `{articles_type}` (`id`, `name`, `nid`, `pid`, `contents`, `order`) VALUES
(1, 'Ĭ������', 'default', 0, '', 10);

";
$mysql->db_querys($sql);
?>
