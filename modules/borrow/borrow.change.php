<?
/******************************
 * $File: borrow.change.php
 * $Description: �û������û������
 * $Author: ahui 
 * $Time:2012-09-20
 * $Update:Ahui
 * $UpdateDate:2012-09-20  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
require_once(ROOT_PATH."modules/account/account.class.php");
require_once(ROOT_PATH."modules/remind/remind.class.php");
require_once(ROOT_PATH."modules/borrow/borrow.class.php");
require_once(ROOT_PATH."modules/borrow/borrow.count.php");

$MsgInfo["borrow_change_action_error"] = "���Ĳ��������벻Ҫ�Ҳ���";
$MsgInfo["borrow_change_account_not_numeric"] = "ת�ý�����������";
$MsgInfo["borrow_change_account_most"] = "ת�ý���С��0";
$MsgInfo["borrow_change_action_success"] = "ת����Ϣ�����ɹ�";
$MsgInfo["borrow_change_status_yes"] = "����Ϣ�Ѿ�ת�ã���ȴ�����Ա���";
$MsgInfo["borrow_change_paypassword_error"] = "֧�����벻��ȷ";
$MsgInfo["borrow_change_wait_account_error"] = "ת�ý��ܴ��ڴ��ս��";
$MsgInfo["borrow_change_cancel_success"] = "ծȨת�ó����ɹ�";
$MsgInfo["borrow_change_web_success"] = "ծȨת�óɹ�����ȴ�����Ա���";
$MsgInfo["borrow_change_cancel_error"] = "ծȨת�ó���ʧ�ܣ��벻Ҫ�Ҳ���";
$MsgInfo["borrow_change_not_self"] = "���ܹ����Լ���ծȨ";
$MsgInfo["borrow_change_account_error"] = "��Ŀ��ý���";
$MsgInfo["borrow_change_buy_error"] = "ծȨ����ʧ��";
$MsgInfo["borrow_change_buy_success"] = "ծȨ����ɹ�";
$MsgInfo["borrow_change_verify_error"] = "ծȨ��˳ɹ�";
$MsgInfo["borrow_change_verify_success"] = "��վ��˳ɹ�";

class borrowChangeClass{
	
}

if ($_REQUEST['change_check']!=""){
	if (isset($_POST['remark']) && $_POST['remark']!=""){
		$msg = check_valicode();
		if ($msg==""){
			$var = array("status","remark");
			$data = post_var($var);
			$data['id'] = $_REQUEST['change_check'];
			$result = borrowChangeClass::WebVerifyChange($data);
			if ($result>0){
				$msg = array($MsgInfo["borrow_change_verify_success"],"",$_A['query_url_all']);
			}else{
				$msg = array($MsgInfo[$result]);
			}
			
			//�������Ա������¼
			$admin_log["user_id"] = $_G['user_id'];
			$admin_log["code"] = "borrow";
			$admin_log["type"] = "change";
			$admin_log["operating"] = "verify";
			$admin_log["article_id"] = $result>0?$result:0;
			$admin_log["result"] = $result>0?1:0;
			$admin_log["content"] =  $msg[0];
			$admin_log["data"] =  $data;
			borrowChangeClass::AddAdminLog($admin_log);
		}
	}
}
?>