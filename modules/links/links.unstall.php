<?php
/******************************
 * $File: links.unstall.php
 * $Description: ж��
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$sql = "DROP TABLE `{links}`,`{links_type}`;";
$mysql->db_querys($sql);
