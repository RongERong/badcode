<?
/******************************
 * $File: comments.install.php
 * $Description: ����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "
CREATE TABLE IF NOT EXISTS `{comments}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `status` mediumint(2) NOT NULL,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `article_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `contents` longtext NOT NULL,
  `addtime` varchar(30) NOT NULL,
  `addip` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY user_id(`user_id`),
  KEY site_id(`site_id`),
  KEY `siteid_status`(`site_id`,`status`)
) ENGINE=MyISAM ;

INSERT INTO `deayou_system` (`name`, `type_id`, `nid`, `value`, `code`, `type`, `style`, `status`) VALUES
( '����״̬', 0, 'con_comments_status', '0', 'comments', '', 1, 1),
('�����Ƿ����', 0, 'con_comments_check_status', '1', 'comments', '', 1, 1),
('������ʱ��',  0, 'con_comments_time', '', 'comments', '', 1, 1),
('���˹ؼ���', 0, 'con_comments_keywords', '', 'comments', '', 1, 1),
('�����û�',0, 'con_comments_users', '', 'comments', '', 1, 1);

";

$mysql->db_querys($sql);
