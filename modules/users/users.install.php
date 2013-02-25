<?
/******************************
 * $File: users.install.php
 * $Description: �û���װ�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "

CREATE TABLE IF NOT EXISTS `{users}` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL COMMENT '�û���',
  `email` char(32) NOT NULL COMMENT '����',
  `password` char(32) NOT NULL COMMENT '����',
  `paypassword` varchar(100) NOT NULL COMMENT '֧������',
  `logintime` int(11) NOT NULL COMMENT '��¼����',
  `reg_ip` char(15) NOT NULL COMMENT 'ע��ip',
  `reg_time` int(10) NOT NULL COMMENT 'ע��ʱ��',
  `up_ip` char(15) NOT NULL COMMENT '��һ�ε�¼ip',
  `up_time` int(10) NOT NULL COMMENT '��һ�ε�¼ʱ��',
  `last_ip` char(15) NOT NULL COMMENT '����¼ip',
  `last_time` int(10) NOT NULL COMMENT '����¼ʱ��',
  PRIMARY KEY (`user_id`),
  KEY `id` (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `username_2` (`username`),
  KEY `email_2` (`email`),
  KEY `username_userid` (`user_id`,`username`),
  KEY `userid_email` (`user_id`,`email`),
  KEY `username_email` (`username`,`email`),
  KEY `username_email_userid` (`user_id`,`username`,`email`)
) ENGINE=MyISAM  COMMENT='�û���Ϣ��'  ;

INSERT INTO `{users}` (`user_id`, `username`, `email`, `password`, `paypassword`, `logintime`, `reg_ip`, `reg_time`, `up_ip`, `up_time`, `last_ip`, `last_time`) VALUES
(1, 'deayou', '5867950@qq.com', '169a865ce7f5330056588f1989c27371', '', 0, '','','','', '', '');


CREATE TABLE IF NOT EXISTS `{users_log}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `operating` varchar(50) NOT NULL,
  `article_id` varchar(50) NOT NULL,
  `result` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
   KEY `user_id_2` (`user_id`)
) ENGINE=MyISAM COMMENT='�û���¼��'   ;

CREATE TABLE IF NOT EXISTS `{users_type}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL  COMMENT '����',
  `nid` varchar(100) NOT NULL COMMENT '��ʶ��',
  `remark` varchar(200) NOT NULL  COMMENT '��ע',
  `litpic` varchar(100) NOT NULL  COMMENT '����ͷ��',
  `checked` int(2) NOT NULL COMMENT '�Ƿ�Ĭ������',
  `order` int(11) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM COMMENT='�û����ͱ�'  ;


INSERT INTO `{users_type}` (`id`, `name`, `nid`, `remark`, `litpic`, `checked`, `order`, `addtime`, `addip`) VALUES
(1, '��ͨ�û�', 'user', '', '', 1, 0, '', '');


CREATE TABLE IF NOT EXISTS `{users_admin}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `purview` longtext NOT NULL,
  `province` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `qq` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  `update_time` varchar(50) NOT NULL,
  `update_ip` varchar(50) NOT NULL,
  `logintimes` int(50) NOT NULL DEFAULT '0',
  `login_time` varchar(50) NOT NULL,
  `login_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `name` (`user_id`),
  KEY `nid` (`type_id`),
  KEY `utype_id` (`user_id`,`type_id`)
) ENGINE=MyISAM COMMENT='����Ա��' ;


INSERT INTO `{users_admin}` (`id`, `adminname`, `user_id`, `password`, `type_id`, `remark`, `purview`, `province`, `city`, `addtime`, `addip`, `update_time`, `update_ip`, `qq`, `logintimes`, `login_time`, `login_ip`) VALUES
(1, '����', 1, '169a865ce7f5330056588f1989c27371', 1, '�ܹ���Ա�˺�', '', 0, 0, '', '', '', '', '', 0, '', '');


CREATE TABLE IF NOT EXISTS `{users_adminlog}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `code` varchar(50) NOT NULL COMMENT '�û�ģ��',
  `type` varchar(50) NOT NULL COMMENT '����',
  `operating` varchar(50) NOT NULL COMMENT '�������',
  `article_id` varchar(50) NOT NULL COMMENT '����id',
  `result` varchar(50) NOT NULL COMMENT '���ؽ��',
  `content` text NOT NULL COMMENT '��������',
  `data` text NOT NULL COMMENT '����',
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `name` (`user_id`)
) ENGINE=MyISAM  COMMENT='����Ա��¼��' ;


CREATE TABLE IF NOT EXISTS `{users_admin_type}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `rank` text NOT NULL,
  `purview` text NOT NULL,
  `module` longtext NOT NULL,
  `remark` varchar(200) NOT NULL,
  `litpic` varchar(100) NOT NULL,
  `order` int(11) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  `update_time` varchar(50) NOT NULL,
  `update_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM COMMENT='����Ա���ͱ�' ;

INSERT INTO `{users_admin_type}` (`id`, `name`, `nid`, `rank`, `purview`, `module`, `remark`, `litpic`, `order`, `addtime`, `addip`, `update_time`, `update_ip`) VALUES
(1, '��������Ա', 'all', '', '', '', '', '', 10, '', '', '', '');


CREATE TABLE IF NOT EXISTS `{users_info}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `niname` varchar(50) NOT NULL COMMENT '�ǳ�',
  `type_id` int(11) NOT NULL COMMENT '�û�����',
  `status` int(2) NOT NULL COMMENT '�û�״̬',
  `birthday` varchar(100) NOT NULL COMMENT '����',
  `sex` varchar(10) NOT NULL COMMENT '�Ա�',
  `invite_userid` int(11) NOT NULL COMMENT '������',
  `invite_money` decimal(11,2) NOT NULL COMMENT '��������ɽ��',
  `realname` varchar(200) NOT NULL COMMENT '��ʵ����',
  `realname_status` int(2) NOT NULL COMMENT '��ʵ�����Ƿ���֤',
  `education` varchar(200) NOT NULL COMMENT 'ѧ��' ,
  `education_status` int(2) NOT NULL  COMMENT 'ѧ���Ƿ���֤',
  `phone` varchar(200) NOT NULL  COMMENT '�ֻ�',
  `phone_status` int(2) NOT NULL  COMMENT '�ֻ��Ƿ���֤',
  `video` varchar(200) NOT NULL DEFAULT ''  COMMENT '��Ƶ',
  `video_status` int(2) NOT NULL DEFAULT '0'  COMMENT '��Ƶ�Ƿ���֤',
  `qq` varchar(50) NOT NULL  COMMENT 'qq',
  `question` varchar(100) NOT NULL COMMENT '����',
  `answer` varchar(100) NOT NULL COMMENT '��',
  `province` int(11) NOT NULL COMMENT '���ڵ�ʡ',
  `city` int(11) NOT NULL COMMENT '���ڵس���',
  `area` int(11) NOT NULL COMMENT '���ڵ�',
  `intro` varchar(200) NOT NULL COMMENT '���',
  `interest` varchar(200) NOT NULL COMMENT '��Ȥ����',
  `impression` longtext NOT NULL COMMENT 'ӡ��',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  KEY `userid_typeid` (`user_id`,`type_id`)
) ENGINE=MyISAM  COMMENT='�û���Ϣ��' ;

INSERT INTO `{users_info}` (`id`, `user_id`, `niname`, `type_id`, `status`, `birthday`, `sex`) VALUES
(1, 1, '����', 1, 1, '', '��');


CREATE TABLE IF NOT EXISTS `{users_email}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` int(2) NOT NULL,
  `addtime` varchar(32) NOT NULL,
  `addip` varchar(32) NOT NULL,
  PRIMARY KEY (`id`), 
  KEY `id` (`id`), 
  KEY `user_id` (`user_id`),
  KEY `email` (`email`),
  KEY `userid_email` (`user_id`,`email`)
) ENGINE=MyISAM  COMMENT='�û������' ;




CREATE TABLE IF NOT EXISTS `{users_email_log}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `send_email` varchar(50) NOT NULL,
  `status` int(2) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `msg` text,
  `addtime` varchar(50) DEFAULT NULL,
  `addip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM COMMENT='���䷢�ͼ�¼'  ;




CREATE TABLE IF NOT EXISTS `{users_upfiles}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '����',
  `code` varchar(50) DEFAULT NULL COMMENT 'ģ��',
  `type` varchar(100) NOT NULL COMMENT '����',
  `article_id` varchar(50) DEFAULT NULL COMMENT '����ģ��id',
  `user_id` int(11) NOT NULL,
  `contents` varchar(200) NOT NULL COMMENT '���',
  `filetype` varchar(50) DEFAULT NULL COMMENT '�ļ�����',
  `filename` varchar(100) DEFAULT NULL COMMENT '�ļ�����',
  `filesize` int(10) DEFAULT '0' COMMENT '�ļ���С',
  `fileurl` varchar(200) DEFAULT NULL COMMENT '�ļ���ַ',
  `addtime` varchar(30) DEFAULT NULL COMMENT '���ʱ��',
  `addip` varchar(30) DEFAULT NULL COMMENT '���ip',
  `updatetime` varchar(30) DEFAULT NULL COMMENT '�޸�ʱ��',
  `updateip` varchar(30) DEFAULT NULL COMMENT '�޸�ip',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM COMMENT='�ϴ���¼��' ;



CREATE TABLE IF NOT EXISTS `{users_visit}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `visit_userid` int(11) DEFAULT NULL,
  `addip` varchar(30) DEFAULT NULL,
  `addtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `visit_userid` (`visit_userid`)
) ENGINE=MyISAM COMMENT='�û����ʱ�'   ;



CREATE TABLE IF NOT EXISTS `{users_vip}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�û�id',
  `status` int(2) NOT NULL COMMENT 'vip״̬',
  `years` int(2) NOT NULL COMMENT 'vip����',
  `money` int(11) NOT NULL COMMENT 'vip��Ǯ',
  `kefu_userid` int(11) NOT NULL COMMENT '�ͷ�id',
  `remark` varchar(250) NOT NULL COMMENT '��ע',
  `first_date` varchar(50) NOT NULL COMMENT '��ʼʱ��',
  `end_date` varchar(50) NOT NULL COMMENT '����ʱ��',
  `verify_userid` int(11) NOT NULL COMMENT '���id',
  `verify_time` varchar(50) NOT NULL COMMENT '���ʱ��',
  `verify_remark` varchar(250) NOT NULL COMMENT '��˱�ע',
  `addtime` varchar(50) NOT NULL COMMENT '����ʱ��',
  `addip` varchar(50) NOT NULL COMMENT '����ip',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
   KEY `status` (`status`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `{users_examines}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '�����û�',
  `code` varchar(100) NOT NULL COMMENT 'ģ��',
  `type` varchar(200) NOT NULL COMMENT '����',
  `article_id` int(11) NOT NULL COMMENT '����id',
  `result` varchar(100) NOT NULL COMMENT '���',
  `verify_userid` int(11) NOT NULL COMMENT '�����',
  `remark` varchar(200) NOT NULL COMMENT '��ע',
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
   KEY `status` (`user_id`)
) ENGINE=MyISAM ;



CREATE TABLE IF NOT EXISTS `{users_friends}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '�û�',
  `friends_userid` int(11) DEFAULT '0' COMMENT '����',
  `type_id` int(11) DEFAULT NULL,
  `status` int(2) DEFAULT '0' COMMENT '״̬',
  `type` int(2) DEFAULT '0' COMMENT '����',
  `content` varchar(255) DEFAULT NULL COMMENT '����',
  `addtime` varchar(50) DEFAULT NULL,
  `addip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
   KEY `status` (`user_id`),
   KEY `friends_userid` (`friends_userid`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `{users_friends_invite}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '�û�',
  `friends_userid` int(11) DEFAULT '0' COMMENT '����',
  `status` int(2) DEFAULT '0' COMMENT '״̬',
  `type` int(2) DEFAULT '0',
  `content` varchar(250) DEFAULT NULL,
  `addtime` varchar(50) DEFAULT NULL,
  `addip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
   KEY `status` (`user_id`),
   KEY `friends_userid` (`friends_userid`)
) ENGINE=MyISAM  ;

CREATE TABLE IF NOT EXISTS `{users_friends_type}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '10' COMMENT '����',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

INSERT INTO `{users_friends_type}` (`id`, `name`, `status`, `nid`, `remark`, `order`) VALUES
(1, '����', 1, '', '', 0),
(2, '����', 1, '', '', 0);



CREATE TABLE IF NOT EXISTS `{users_viplog}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `money` varchar(10) NOT NULL,
  `first_time` varchar(50) NOT NULL,
  `end_time` varchar(50) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{users_care}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `article_id` varchar(50) CHARACTER SET gbk NOT NULL,
  `code` varchar(30) CHARACTER SET gbk NOT NULL,
  `addtime` varchar(30) CHARACTER SET gbk NOT NULL,
  `addip` varchar(30) CHARACTER SET gbk NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM
";
$mysql->db_querys($sql);
?>
