<?php
/******************************
 * $File: admin.module.php
 * $Description: ģ���ദ��
 * $Author: ahui 
 * $Time:2011-11-09
 * $Update:None 
 * $UpdateDate:None 
 * Copyright(c) 2012 by dycms.net. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

//adminClass::SaveModules(array("nid"=>"group","table"=>"group"));
//adminClass::SaveModules(array("nid"=>"group","table"=>"group_type"));

$sql = "DROP TABLE `{group}`, `{group_type}`, `{group_member}`, `{group_articles}`, `{group_comments}`;";
$mysql->db_querys($sql);
