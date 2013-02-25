<?php
/******************************
 * $File: borrow.tender.inc.php
 * $Description: �û�Ͷ���û����Ĵ����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("borrow.tender.php");//����
require_once("borrow.loan.php");//����
require_once("borrow.class.php");//����
require_once("borrow.type.php");//����

//Ͷ��
if ($_REQUEST['p']=="invest"){
    $borrow_result = borrowTenderClass::CheckTender(array("borrow_nid"=>$_REQUEST['borrow_nid']));
    if (is_array($borrow_result)){
        $_U['loan_tender'] = $borrow_result;
    	$account_result = accountClass::GetAccountUsers(array("user_id"=>$_G["user_id"]));
        $_U['loan_tender']['balance'] = $account_result["balance"];
        $borrow_type = borrowTypeClass::GetTypeOne(array("nid"=>$borrow_result["borrow_type"]));
         $_U['loan_tender']['password_status'] = $borrow_type["password_status"];
    }else{
        $msg = array($MsgInfo[$borrow_result],"","/");
    }
    $_G["site_nid"] = "borrow";
    $template = "users_tender_invest.html";

}
elseif ($_REQUEST['p']=="add"){
    
    //��������ӽ�ȥ
	$_tender['borrow_nid'] = $_POST['borrow_nid'];
	$_tender['user_id'] = $_G['user_id'];
	$_tender['account'] = $_POST['money'];
	$_tender['contents'] =  iconv("UTF-8", "GB2312", $_POST['contents']);
	$_tender['paypassword'] = $_POST['paypassword'];
	$_tender['borrow_password'] = $_POST['borrow_password'];
	$_tender['valicode'] = $_POST['valicode'];
	$_tender['status'] = 0;
	$_tender['nid'] = "tender_".$_G["user_id"]."_".time();
	$result = borrowTenderClass::AddTender($_tender);

	if ($result>0){
		$msg = array(1);
	}elseif (IsExiest($MsgInfo[$result])!=""){
		$msg = array($MsgInfo[$result],"","/index.php?user&q=code/borrow/tender&p=now");
	}else{
		$msg = array($result);
	}	
    echo $msg[0];
    exit;
    
}

//����Ͷ�ʵĽ��
elseif ($_REQUEST['p']=="now"){
    
    
}
//�ɹ�Ͷ��
elseif ($_REQUEST['p']=="success"){
    
}
//�ɹ�Ͷ��
elseif ($_REQUEST['p']=="wait" || $_REQUEST['p']=="agreement" ){
	$result = borrowCountClass::GetUsersRecoverCount(array("user_id"=>$_G['user_id']));
    $_U['borrow_wait'] = $result;
	
}


	
?>