<?
/******************************
 * $File: borrow.loan.php
 * $Description: �û������û������
 * $Author: ahui 
 * $Time:2012-09-20
 * $Update:Ahui
 * $UpdateDate:2012-09-20  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

class borrowLoanClass
{
	
	//��һ������������˵���Ϣ
	function CheckLoan($data){		
		global $mysql,$_G;
		//��ȡ���
		$amount=amountClass::GetAmountUsers(array("user_id"=>$data['user_id']));
		
		//����һ�����Ƚ���ʵ����֤���ֻ���֤
		//ʵ����֤
		$sql = "select * from `{approve_realname}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result['realname']==""){
			return "realname";
		}	
		//�ֻ���֤
		$sql = "select * from `{approve_sms}` where user_id='{$data['user_id']}' and status=1 ";
		$result = $mysql->db_fetch_array($sql);
		if ($result['phone']==""){
			return "phone";
		}				
		//���ڶ�������д������Ϣ
		$sql_info = "select * from `{rating_info}` where user_id='{$data['user_id']}'";
		$result_info = $mysql->db_fetch_array($sql_info);
		
		$sql_work = "select * from `{rating_job}` where user_id='{$data['user_id']}'";
		$result_work = $mysql->db_fetch_array($sql_work);
		
		$sql_houses = "select * from `{rating_houses}` where user_id='{$data['user_id']}'";
		$result_houses = $mysql->db_fetch_array($sql_houses);
		
		$sql_finance = "select * from `{rating_finance}` where user_id='{$data['user_id']}'";
		$result_finance = $mysql->db_fetch_array($sql_finance);
		
		$sql_contact = "select * from `{rating_contact}` where user_id='{$data['user_id']}'";
		$result_contact = $mysql->db_fetch_array($sql_contact);
		
		$sql_company = "select * from `{rating_company}` where user_id='{$data['user_id']}'";
		$result_company = $mysql->db_fetch_array($sql_company);
		
		if ($result_info == false && $result_work==false && $result_houses==false &&$result_finance==false && $result_contact == false && $result_company == false){
			return "info";
		}
			
		//����������������
		if($data['borrow_type']==1 || $data['borrow_type']==6){
			if($amount['borrow']!=0){
				$sql = "select 1 from `{borrow_amount_apply}` where user_id='{$data['user_id']}' and status='0' and amount_type='borrow'";
				$result = $mysql->db_fetch_array($sql);
				if($result!=''){
					return "approve";
				}else{
					return "amount";
				}
			}else{			
				$sql = "select 1 from `{borrow_amount_apply}` where user_id='{$data['user_id']}' and status='0' and amount_type='borrow'";
				$result = $mysql->db_fetch_array($sql);
				if($result==''){
					return "amount";
				}else{
					return "approve";
				}
			}
		}elseif($data['borrow_type']==2){
			if($amount['vouch_borrow']!=0){
				$sql = "select 1 from `{borrow_amount_apply}` where user_id='{$data['user_id']}' and status='0' and amount_type='vouch_borrow'";
				$result = $mysql->db_fetch_array($sql);
				if($result!=''){
					return "approve";
				}else{
					return "amount";
				}
			}else{			
				$sql = "select 1 from `{borrow_amount_apply}` where user_id='{$data['user_id']}' and status='0' and amount_type='vouch_borrow'";
				$result = $mysql->db_fetch_array($sql);
				if($result==''){
					return "amount";
				}else{
					return "approve";
				}
			}
		}elseif($data['borrow_type']==3){
			if($amount['diya_borrow']!=0){
				$sql = "select 1 from `{borrow_amount_apply}` where user_id='{$data['user_id']}' and status='0' and amount_type='diya_borrow'";
				$result = $mysql->db_fetch_array($sql);
				if($result!=''){
					return "approve";
				}else{
					return "amount";
				}
			}else{			
				$sql = "select 1 from `{borrow_amount_apply}` where user_id='{$data['user_id']}' and status='0' and amount_type='diya_borrow'";
				$result = $mysql->db_fetch_array($sql);
				if($result==''){
					return "amount";
				}else{
					return "approve";
				}
			}
		}		
		
		$sql = "select 1 from `{borrow_amount}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==""){
			return "amount";
		}
		
		//�����Ĳ����ϴ�����
		require_once(ROOT_PATH."/modules/attestations/attestations.class.php");
		$result = attestationsClass::GetAttestationsUserCredit(array("user_id"=>$data['user_id']));
		if ( $result['work']['status']!=1 || $result['income']['status']!=1 || $result['bank_report']['status']!=1 || $_G['user_info']['realname_status']!=1 ){
			return "approve";
		}
	}
	
	function CheckRealname($data){	
		global $mysql,$_G;
		$sql = "select realname from `{approve_realname}` where user_id='{$data['user_id']}' ";
		$result = $mysql->db_fetch_array($sql);
		if($result['realname']!=''){
			return 1;
		}
		return 0;
	}
	
}
?>
