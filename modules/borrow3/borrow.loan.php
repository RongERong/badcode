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
		//����һ�����Ƚ�����д������Ϣ
		if($data['borrow_type']==1){
			$sql = "select * from `{rating_info}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['realname']==""){
				return "info";
			}			
			//���ڶ�������д������Ϣ
			$sql = "select * from `{rating_job}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['name']==""){
				return "work";
			}
			//�ٴ�������
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
			//������������д������Ϣ
			$sql = "select 1 from `{borrow_amount}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==""){
				return "amount";
			}
			
			
		}elseif($data['borrow_type']==2){
			//��Ѻ��һ�����Ƚ�����д��Ѻ����Ϣ
			$sql = "select * from `{rating_houses}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['name']==""){
				return "diya";
			}
			
			//�ٴ�������
			if($amount['vouch_borrow']!=0){			
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

			//������������д������Ϣ
			$sql = "select 1 from `{borrow_amount}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==""){
				return "amount";
			}
		}
				
		
		
		//�����������ϴ�����,�ж�ʵ����֤���ֻ���֤
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
