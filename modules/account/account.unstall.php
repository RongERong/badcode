<?php
/******************************
 * $File: account.unstall.php
 * $Description: �ʽ�ж��
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���


$sql = "DROP TABLE `{account}`, `{account_balance}`, `{account_bank}`, `{account_cash}`, `{account_log}`, `{account_recharge}`, `{account_users}` ,`{account_users_bank}`,`{account_web}`,`{account_payment}`;";
$mysql->db_querys($sql);

?>