<?php
/******************************
 * $File: borrow.type.int.php
 * $Description: ��������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���


require_once("borrow.type.php");//����

check_rank("borrow_type");//����Ȩ��
//�޸�
if ($_REQUEST["p"] == "edit"){
   if ($_POST['id']!=""){
        $var = array("id","title","status","amount_type","part_status","frost_scale_vip","verify_auto_status","verify_auto_remark","award_status","award_false_status","award_scale_first","password_status","award_scale_end","award_account_first","award_account_end","amount_first","amount_end","check_first","check_end","tender_account_min","tender_account_max","apr_first","apr_end","period_first","period_end","validate_first","validate_end","styles","frost_scale","late_days","vip_late_scale","all_late_scale","description","system_borrow_full_status","system_borrow_repay_status","system_web_repay_status","account_multiple");
		$data = post_var($var);
		
		$result = borrowTypeClass::UpdateType($data);
		if ($result>0){
			$msg = array("��������޸ĳɹ�","",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result]);
		}
		
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "borrow";
		$admin_log["type"] = "type";
		$admin_log["operating"] = "edit";
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
    } 
}

?>