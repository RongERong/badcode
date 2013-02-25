<?
/******************************
 * $File: borrow.cancel.php
 * $Description: ������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("borrow.loan.php");
require_once("borrow.fee.php");
require_once("borrow.calculates.php");
$MsgInfo["borrow_status_error"] = "���״̬����ȷ�����ԭ����ͨ��������뻹��ͨ��";
$MsgInfo["borrow_cancel_yes"] = "�Ѿ�ͨ�������ܽ����޸�";
$MsgInfo["borrow_loan_cance_error"] = "���Ĳ������󣺡�error:borrow_loan_cance_error��";

class borrowCancelClass
{
    
    
    function CancelInfo($data){
        global $mysql,$_G;
        if (IsExiest($data["borrow_nid"])=="") return "borrow_nid_empty";
		//��ȡ����������Ϣ
		$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$data['borrow_nid']}'";
		$borrow_result = $mysql->db_fetch_array($sql);
        $borrow_result['borrow_url'] = "<a href={$_G['web_domain']}/invest/a{$data['borrow_nid']}.html target=_blank style=color:blue>{$borrow_result['name']}</a>";//�����ַ
		if ($borrow_result['borrow_full_status']==1){
		  return "borrow_fullcheck_yes";
		}elseif ($borrow_result['status']==6){
		  return "borrow_cancel_yes";
		}else{
		  
            //��һ������ص��ж�
            if ($data['step']==0){
            	if ($borrow_result['cancel_status']>0){
    			     return "borrow_cancel_yes";
                }
                //�ж��Ƿ�����������
                echo "���ڳ����У��벻Ҫ�ر������";
        		//��������ʱ�Ĳ�����
        		$sql = " update `{borrow}` set cancel_userid='{$data['cancel_userid']}',cancel_remark='{$data['remark']}',cancel_contents='{$data['contents']}',cancel_time='".time()."',cancel_status='{$data['status']}' where borrow_nid='{$data['borrow_nid']}'";
                $mysql ->db_query($sql);
                
        		//������˼�¼
                $_verify['user_id'] = $_G['user_id'];
                $_verify['status'] = $data['status'];
                $_verify['borrow_nid'] = $data['borrow_nid'];
                $_verify['type'] = "over";
                $_verify['remark'] = $data['remark'];
                $_verify['contents'] = $data['contents'];
                borrowLoanClass::AddVerify($_verify);
                return array("result"=>1,"step"=>1,"key"=>"");	
            }else{
                $fun = "CancelStep".$data['step'];
                $borrow_result["key"] = $data['key'];
                
    		    $result = self::$fun($borrow_result);
                return $result;
            }
		}
        //����������
    }
	
	
    
    //����Ͷ���˵���Ϣ
	function CancelStep1($borrow_result){
		global $mysql,$_G;
        if($borrow_result=="") return "";
        $status = $borrow_result['cancel_status'];
        //�ɹ����
        $sql = "select * from `{borrow_tender}` as p1 where  p1.borrow_nid='{$borrow_result['borrow_nid']}' limit {$borrow_result['key']},1";
		$tender_result = $mysql->db_fetch_array($sql);
		if ($tender_result==false){
			return array("result"=>1,"step"=>2,"key"=>0);
		}
        
		$tender_userid = $tender_result['user_id'];
		$tender_account = $tender_result['account'];
		$tender_id = $tender_result['id'];
		$borrow_nid = $borrow_result["borrow_nid"];
		$borrow_userid = $borrow_result["user_id"];
		$borrow_url = $borrow_result["borrow_url"];
		$borrow_name = $borrow_result["name"];
 	   //����Ͷ�ʵ�״̬
       
       //Ͷ��״̬�����ֵΪ3��
		$sql = "update `{borrow_tender}` set status=3 where id={$tender_id}";
		$mysql->db_query($sql);
		
		//����Ͷ���ʽ�
		$log_info["user_id"] = $tender_userid;//�����û�id
		$log_info["nid"] = "tender_over_".$borrow_nid."_".$tender_userid."_".$tender_id;//������
        $log_info["borrow_nid"] = $borrow_nid;//����
		$log_info["code"] = "borrow";//
		$log_info["code_type"] = "tender_over";//
		$log_info["code_nid"] = $tender_id;//
		$log_info["money"] = $tender_account;//�������
		$log_info["income"] = $tender_account;//����
		$log_info["expend"] = 0;//֧��
		$log_info["balance_cash"] = $tender_account;//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = -$tender_account;//������
		$log_info["await"] = 0;//���ս��
		$log_info["repay"] = 0;//�������
		$log_info["type"] = "tender_over";//����
		$log_info["to_userid"] = $borrow_userid;//����˭
		$log_info["remark"] =  "Ͷ��[{$borrow_url}]ʧ�����귵�ص�Ͷ���";
		accountClass::AddLog($log_info);
		
		//Ͷ���߽��� 
		$remind['nid'] = "tender_over";
		$remind['receive_userid'] = $tender_userid;
		$remind['remind_nid'] = "tender_over_".$borrow_nid."_".$tender_userid."_".$tender_id;
		$remind['code'] = "borrow";
		$remind['article_id'] = $borrow_nid;
		$remind['title'] = "Ͷ�ʵı�[{$borrow_name}]ʧ������";
		$remind['content'] = "����Ͷ�ʵı�[{$borrow_url}]��".date("Y-m-d",time())."ʧ������,ʧ��ԭ��{$borrow_result['cancel_remark']}";
		remindClass::sendRemind($remind);
        return array("result"=>1,"step"=>1,"key"=>$borrow_result['key']+1);
   }
   
    //����
    function CancelStep2($borrow_result){
		global $mysql,$_G;
        if($borrow_result=="") return "";
        $borrow_nid = $borrow_result["borrow_nid"];
        $sql = "update `{borrow}` set status='{$borrow_result['cancel_status']}'  where borrow_nid='{$borrow_result["borrow_nid"]}'";
		$mysql->db_query($sql);
        
        
        //��ȷ���
        if ($borrow_result['amount_account']>0){
            //��ȶ���
        	$_amount["user_id"] = $borrow_result['user_id'];//�û�id
        	$_amount["amount_type"] = $borrow_result["amount_type"];//�������
        	$_amount["amount_style"] = "forever";
        	$_amount["type"] = "borrow_over";
        	$_amount["oprate"] = "return";
            $_amount["account"] = $borrow_result['amount_account'];
        	$_amount["nid"] = $_amount["type"]."_".$borrow_result['user_id']."_".$borrow_result['borrow_nid'];
        	$_amount["remark"] = "���ؽ���[{$borrow_result["borrow_url"]}]������{$borrow_result['account']}Ԫ���";
            borrowAmountClass::AddAmountLog($_amount);	
        }
        
        //����˽��� 
		$remind['nid'] = "borrow_over";
		$remind['receive_userid'] = $borrow_result["user_id"];
		$remind['remind_nid'] = "borrow_over_".$borrow_nid."_".$borrow_result["user_id"];
		$remind['code'] = "borrow";
		$remind['article_id'] = $borrow_nid;
		$remind['title'] = "����[{$borrow_result['name']}]ʧ������";
		$remind['content'] = "�������ı�[{$borrow_result['name']}]��".date("Y-m-d",time())."ʧ������,ʧ��ԭ��{$borrow_result['cancel_remark']}";
		remindClass::sendRemind($remind);
        
		return array("result"=>0,"step"=>"","key"=>"");
	}
    
    
    function UserCancel($data){
        global $mysql,$_G;
        $sql = "select * from `{borrow}` where borrow_nid='{$data['borrow_nid']}' and user_id='{$data['user_id']}'";
        $borrow_result= $mysql->db_fetch_array($sql);
        if ($borrow_result==false){
            return "borrow_loan_cance_error";
        }
        $sql = " update `{borrow}` set cancel_userid='{$data['user_id']}',cancel_remark='{$data['remark']}',cancel_contents='{$data['contents']}',cancel_time='".time()."',cancel_status='5',status='5' where borrow_nid='{$data['borrow_nid']}'";
		$mysql->db_query($sql);
        $borrow_url = "<a href={$_G['web_domain']}/view/borrow_nid={$borrow_result['borrow_nid']} target=_blank style=color:blue>{$borrow_result['name']}</a>";//�����ַ
        //��ȷ���
        if ($borrow_result['amount_account']>0 && $borrow_result['borrow_type']!='worth'){
            //��ȶ���
        	$_amount["user_id"] = $borrow_result['user_id'];//�û�id
        	$_amount["amount_type"] = $borrow_result["amount_type"];//�������
        	$_amount["amount_style"] = "forever";
        	$_amount["type"] = "borrow_cancel";
        	$_amount["oprate"] = "return";
            $_amount["account"] = $borrow_result['amount_account'];
        	$_amount["nid"] = $_amount["type"]."_".$borrow_result['user_id']."_".$borrow_result['borrow_nid'];
        	$_amount["remark"] = "���ؽ���[{$borrow_url}]������{$borrow_result['account']}Ԫ���";
            borrowAmountClass::AddAmountLog($_amount);	
        }
        //����˽��� 
		$remind['nid'] = "borrow_cancel";
		$remind['receive_userid'] = $borrow_result["user_id"];
		$remind['remind_nid'] = "borrow_cancel_".$borrow_result["borrow_nid"]."_".$borrow_result["user_id"];
		$remind['code'] = "borrow";
		$remind['article_id'] = $borrow_result["borrow_nid"];
		$remind['title'] = "���ı�[{$borrow_result['name']}]�ѳ���";
		$remind['content'] = "�������ı�[{$borrow_url}]��".date("Y-m-d",time())."����";
		remindClass::sendRemind($remind);
        
		return array("result"=>0,"step"=>"","key"=>"");
        
    }
}
?>
