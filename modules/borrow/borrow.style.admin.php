<?php
/******************************
 * $File: borrow.style.int.php
 * $Description: ���ʽ��̨����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$_A['list_purview']["borrow"]["result"]["borrow_style"] = array("name"=>"���ʽ","url"=>"code/borrow/style");

require_once("borrow.style.php");//����

check_rank("borrow_style");//����Ȩ��


//�޸Ļ��ʽ
if ($_REQUEST["p"] == "edit"){
   if ($_POST['id']!=""){
        $var = array("id","title","status","contents");
		$data = post_var($var);
		
		$result = borrowStyleClass::UpdateStyle($data);
		if ($result>0){
			$msg = array("���ʽ�޸ĳɹ�","",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result]);
		}
		
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "borrow";
		$admin_log["type"] = "style";
		$admin_log["operating"] = "edit";
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
    } 
}

?>