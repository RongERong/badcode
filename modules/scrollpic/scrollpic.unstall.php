<?php
/******************************
 * $File: scrollpic.unstall.php
 * $Description: ж�ع���
 * $Author: ahui 
 * $Time:2011-11-09
 * $Update:None 
 * $UpdateDate:None 
 * Copyright(c) 2012 by dycms.net. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���


$sql = "DROP TABLE `{scrollpic_type}`, `{scrollpic}`;";
$mysql->db_querys($sql);
