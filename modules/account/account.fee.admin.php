<?php
/******************************
 * $File: account.fee.inc.php
 * $Description: �ʽ������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("account.fee.php");//����
$_A["account_type"] = array("account"=>"��Ϣ","interest"=>"��Ϣ","capital"=>"����");

check_rank("account_fee");//����Ȩ��
//�޸�
if ($_REQUEST["p"] == "edit" || $_REQUEST["p"] == "new"){
   if ($_POST['name']!=""){
     $var = array("name","title","type","nid","status","fee_type","vip_account_all","vip_account_all_fee","vip_account_add","vip_account_add_fee","vip_account_max","all_account_all","all_account_all_fee","all_account_add","all_account_add_fee","all_account_max","vip_account_scale","vip_account_scale_max","all_account_scale","all_account_scale_max");

       	$data = post_var($var);
        if ($_POST["id"]==""){
            $result = accountFeeClass::AddFee($data);
    	     if ($result>0){
    			$msg = array("�ʽ������ӳɹ�","",$_A['query_url_all']);
    		}
        }else{
            $data["id"] = $_POST["id"];
    		$result = accountFeeClass::UpdateFee($data);
    		if ($result>0){
    			$msg = array("�ʽ�����޸ĳɹ�","",$_A['query_url_all']);
    		}
		}
        if ($msg==""){
    		$msg = array($MsgInfo[$result]);
        }
        
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "account";
		$admin_log["type"] = "fee";
		$admin_log["operating"] = $_REQUEST["p"];
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
    } elseif ($_REQUEST["p"] == "edit"){
        $data = array("id"=>$_REQUEST["id"]);
        $_A['account_fee_result'] = accountFeeClass::GetFeeOne($data);
    }
}elseif ($_REQUEST["p"] == "del"){
    
        $data["id"] = $_REQUEST["id"];
		$result = accountFeeClass::DeleteFee($data);
    	$msg = array("�ʽ����ɾ���ɹ�","",$_A['query_url_all']);
    	
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "account";
		$admin_log["type"] = "fee";
		$admin_log["operating"] = $_REQUEST["p"];
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
}elseif ($_REQUEST["p"] == "type_edit" || $_REQUEST["p"] == "type_new"){
   if ($_POST['name']!=""){
     $var = array("name","title","nid","status","remark");

       	$data = post_var($var);
        if ($_POST["id"]==""){
            $result = accountFeeClass::AddFeeType($data);
    	     if ($result>0){
    			$msg = array("�ʽ����������ӳɹ�","",$_A['query_url_all']."&p=type");
    		}
        }else{
            $data["id"] = $_POST["id"];
    		$result = accountFeeClass::UpdateFeeType($data);
    		if ($result>0){
    			$msg = array("�ʽ���������޸ĳɹ�","",$_A['query_url_all']."&p=type");
    		}
		}
        if ($msg==""){
    		$msg = array($MsgInfo[$result]);
        }
        
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "account";
		$admin_log["type"] = "fee_type";
		$admin_log["operating"] = $_REQUEST["p"];
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
    } elseif ($_REQUEST["p"] == "type_edit"){
        $data = array("id"=>$_REQUEST["id"]);
        $_A['account_fee_result'] = accountFeeClass::GetFeeTypeOne($data);
    }
}elseif ($_REQUEST["p"] == "type_del"){
    
        $data["id"] = $_REQUEST["id"];
		$result = accountFeeClass::DeleteFeeType($data);
    	$msg = array("�ʽ��������ɾ���ɹ�","",$_A['query_url_all']."&p=type");
    	
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "account";
		$admin_log["type"] = "fee_type";
		$admin_log["operating"] = $_REQUEST["p"];
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
}

$_A["sub_dir"] = "account.fee.tpl";
?>