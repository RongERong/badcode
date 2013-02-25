<?
/******************************
 * $File: borrow.excel.php
 * $Description: ����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("borrow.model.php");

class borrowexcel {
	
	//�����û����ʽ��¼
	function AccountList($data){
		$title = array("Id","�û�����","�ܽ��","���ý��","������","���ս��");
		if ($data['page']>0){
			$_result = accountClass::GetList($data);
			$result  = $_result['list'];
		}else{
			$data['limit'] = "all";
			$result = accountClass::GetList($data);
		}
		foreach ($result as $key => $value){
			$_data[$key] = array($key+1,$value['username'],$value['total'],$value['balance'],$value['frost'],$value['await']);
		}
		exportData("�˺���Ϣ����",$title,$_data);
		exit;
	}
	
	
	//�����û����ʽ��¼
	function LogList($data){
		$title = array("Id","�û���","���׺�","����","�������","��ע","���ʱ��");
		if ($data['page']>0){
			$_result = accountClass::GetLogList($data);
			$result  = $_result['list'];
		}else{
			$data['limit'] = "all";
			$result = accountClass::GetLogList($data);
		}
		foreach ($result as $key => $value){
			$_data[$key] = array($key+1,$value['username'],$value['nid'],$value['type'],$value['money'],$value['remark'],date("Y-m-d H:i:s",$value['addtime']));
		}
		exportData("�˺���Ϣ����",$title,$_data);
		exit;
	}
	
	
	
	//
	function BorrowRepayList($data){
		$title = array("Id","�û���","���׺�","����","�������","��ע","���ʱ��");
		if ($data['page']>0){
			$_result = accountClass::GetLogList($data);
			$result  = $_result['list'];
		}else{
			$data['limit'] = "all";
			$result = accountClass::GetLogList($data);
		}
		foreach ($result as $key => $value){
			$_data[$key] = array($key+1,$value['username'],$value['nid'],$value['type'],$value['money'],$value['remark'],date("Y-m-d H:i:s",$value['addtime']));
		}
		exportData("�˺���Ϣ����",$title,$_data);
		exit;
	}
	
	
	
	//�����û����ʽ��¼
	function BadBorrowRepayList($data){
		$title = array("Id","�û���","���׺�","����","�������","��ע","���ʱ��");
		if ($data['page']>0){
			$_result = accountClass::GetLogList($data);
			$result  = $_result['list'];
		}else{
			$data['limit'] = "all";
			$result = accountClass::GetLogList($data);
		}
		foreach ($result as $key => $value){
			$_data[$key] = array($key+1,$value['username'],$value['nid'],$value['type'],$value['money'],$value['remark'],date("Y-m-d H:i:s",$value['addtime']));
		}
		exportData("�˺���Ϣ����",$title,$_data);
		exit;
	}
	
	
	
	//�����û����ʽ��¼
	function ChangeList($data){
		$title = array("Id","�û���","���׺�","����","�������","��ע","���ʱ��");
		if ($data['page']>0){
			$_result = accountClass::GetLogList($data);
			$result  = $_result['list'];
		}else{
			$data['limit'] = "all";
			$result = accountClass::GetLogList($data);
		}
		foreach ($result as $key => $value){
			$_data[$key] = array($key+1,$value['username'],$value['nid'],$value['type'],$value['money'],$value['remark'],date("Y-m-d H:i:s",$value['addtime']));
		}
		exportData("�˺���Ϣ����",$title,$_data);
		exit;
	}
	
}
?>