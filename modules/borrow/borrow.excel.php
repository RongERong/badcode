<?php
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
require_once("borrow.change.php");

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
	function BorrowChangeList($data){
		
		if($data['status']=='' || $data['status']==2){
			$title = array("Id","ת����","Ͷ�����","����","��������","������","���ձ���","������Ϣ","ת�ü۸�","����ʱ��");
		}elseif($data['status']==1){
			$title = array("Id","ת����","Ͷ�����","����","��������","������","���ձ���","������Ϣ","ת�ü۸�","����ʱ��","������","����ʱ��");
		}elseif($data['status']==5){
			$title = array("Id","ת����","Ͷ�����","����","��������","������","���ձ���","������Ϣ","ת�ü۸�","����ʱ��","����ʱ��");
		}		
		if ($data['page']>0){
			$_result = borrowChangeClass::GetChangeList($data);
			$result  = $_result['list'];
		}else{
			$data['limit'] = "all";
			$result = borrowChangeClass::GetChangeList($data);
		}
		foreach ($result as $key => $value){
			/* if($data['status']=='' || $data['status']==2){
				$_data[$key] = array($key+1,$value['username'],$value['borrow_name'],$value['borrow_apr'],$value['wait_times']."/".$value['borrow_period'],$value['recover_account_capital_wait'],date("Y-m-d H:i:s",$value['addtime']));
			}elseif($data['status']==1){
				$_data[$key] = array($key+1,$value['username'],$value['borrow_name'],$value['borrow_apr'],$value['wait_times']."/".$value['borrow_period'],$value['recover_account_capital_wait'],date("Y-m-d H:i:s",$value['addtime']));
			}elseif($data['status']==5){
				$_data[$key] = array($key+1,$value['username'],$value['borrow_name'],$value['borrow_apr'],$value['wait_times']."/".$value['borrow_period'],$value['recover_account_capital_wait'],date("Y-m-d H:i:s",$value['addtime']));
			}  */
			 if( $data['status']=='' || $data['status']==2 ){
				$_data[$key] = array($key+1,$value['username'],$value['borrow_name'],$value['borrow_apr'],$value['wait_times'],$value['borrow_period'],$value['recover_account_capital_wait'],$value['recover_account_interest_wait'],$value['account'],date("Y-m-d H:i:s",$value['addtime']));
			}elseif($data['status']==1){
				$_data[$key] = array($key+1,$value['username'],$value['borrow_name'],$value['borrow_apr'],$value['wait_times'],$value['borrow_period'],$value['recover_account_capital_wait'],$value['recover_account_interest_wait'],$value['account'],date("Y-m-d H:i:s",$value['addtime']),$value['buy_username'],date("Y-m-d H:i:s",$value['buy_time']));
			}elseif($data['status']==5){
				$_data[$key] = array($key+1,$value['username'],$value['borrow_name'],$value['borrow_apr'],$value['wait_times'],$value['borrow_period'],$value['recover_account_capital_wait'],$value['recover_account_interest_wait'],$value['account'],date("Y-m-d H:i:s",$value['cancel_time']),date("Y-m-d H:i:s",$value['addtime']));
			} 
		}
		exportData("�˺���Ϣ����",$title,$_data);
		exit;
	}

    
    /**
     * ����������Ϣ
     */
    public function GetRepayList($data)
    {
        if ($data['page']>0) {
            $result = borrowLoanClass::GetRepayList($data);
            $result = $result['list'];
        } else {
            $data['limit'] = "all";
            $result = borrowLoanClass::GetRepayList($data);
        }
        if(!empty($result)){
            $title=['�����','�����','������','�������','�������','Ӧ��ʱ��','Ӧ����Ϣ','ʵ��ʱ��','ʵ������','ʵ����Ϣ','�������','ʵ���ܶ�','״̬'];
            $_data = [];
            foreach($result as $value){
                $_data[]=[$value['borrow_nid'],$value['borrow_username'],$value['borrow_name'],$value['repay_period'],$value['type_title'],date('Y-m-d',$value['repay_time']),$value['repay_account'],(empty($value['repay_yestime'])?'':date('Y-m-d',$value['repay_yestime'])),$value['repay_capital_yes'],$value['repay_interest_yes'],$value['repay_fee'],($value['repay_capital_yes']+$value['repay_interest_yes']+$value['repay_fee']),$value['repay_type_name']];
            }
            exportData("������֮������Ϣ",$title,$_data);
            exit;
        }
    }

    /**
     * ����������Ϣ
     */
    public function GetRecoverList($data)
    {
        if ($data['page']>0) {
            $result = borrowRecoverClass::GetRecoverList($data);
            $result = $result['list'];
        } else {
            $data['limit'] = "all";
            $result = borrowRecoverClass::GetRecoverList($data);
        }
        if(!empty($result)){
            $title=['�տ���','�����','������','�������','���Ϣ','��������','Ӧ��ʱ��','ʵ��ʱ��','ʵ���ܶ�','״̬'];
            $_data = [];
            $bool_status = ['���տ�','���տ�'];
            $borrow_type = ['credit'=>'���ñ�','vouch'=>'������','pawn'=>'��Ѻ��','second'=>'���','worth'=>'��ֵ��','day'=>'���','roam'=>'��ת��'];
            foreach($result as $value){
                $_data[]=[$value['username'],$value['borrow_nid'],$value['borrow_name'].'(��'.$value['repay_period'].'��)',$borrow_type[$value['borrow_type']],$value['recover_account'],$value['late_days'].'��',(empty($value['recover_time'])?'':date('Y-m-d',$value['recover_time'])),(empty($value['recover_yestime'])?'':date('Y-m-d',$value['recover_yestime'])),$value['recover_account_yes'],(isset($bool_status[$value['recover_status']])?$bool_status[$value['recover_status']]:'��')];
            }
            exportData("������֮������Ϣ",$title,$_data);
            exit;
        }
    }

    /**
     * ����Ͷ����Ϣ
     */
    public function GetTenderList($data)
    {
        if ($data['page']>0) {
            $result = borrowTenderClass::GetTenderList($data);
            $result = $result['list'];
        } else {
            $data['limit'] = "all";
            $result = borrowTenderClass::GetTenderList($data);
        }
        if(!empty($result)){
            $title=['Ͷ��ID','Ͷ����','Ͷ�ʽ��','Ͷ��ʱ��','Ͷ��״̬','�Ƿ�ת��','Ͷ������','����','����ʶ��','����ܶ�','�Զ�Ͷ��'];
            $status_arr = ['�����','�ɹ�','ʧ��'];
            $bool_status = ['��','��'];
            $_data = [];
            foreach($result as $value){
                $_data[]=[$value['id'],$value['username'],$value['account'],date('Y-m-d H:i:s',$value['addtime']),(isset ($status_arr[$value['status']])?$status_arr[$value['status']]:'�����'),(isset ($bool_status[$value['change_status']])?$bool_status[$value['change_status']]:'��'),(empty($value['contents'])?'':$value['contents']),$value['borrow_name'],$value['borrow_nid'],$value['borrow_account'],(isset ($bool_status[$value['auto_status']])?$bool_status[$value['auto_status']]:'��')];
            }
            exportData("������֮Ͷ����Ϣ",$title,$_data);
            exit;
        }
    }
	
}