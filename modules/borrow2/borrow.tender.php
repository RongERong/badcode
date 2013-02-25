<?
/******************************
 * $File: borrow.tender.php
 * $Description: Ͷ�����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-08-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once(ROOT_PATH."modules/borrow/borrow.class.php");

$MsgInfo["borrow_flag_nid_exiest"] = "��ʶ���Ѿ�����";
class borrowTenderClass
{
	/**
	 * ���Ͷ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function AddTender($data = array()){
		global $mysql,$_G;
		
		
		
	
		
		//���������ж��Ƿ��Ѿ����ڡ����ɸġ�
		if ($borrow_result['verify_time']<time() - $borrow_result['borrow_valid_time']*60*60*24){
			
			return "tender_late_yes";
		}
		//���߲����жϽ���Ƿ���ȷ�����ɸġ�
		if(!is_numeric($data['account']) || $data['account']<0){
			return "tender_money_error";
		}
		
		
		//�ڰ˲����ж��Ƿ�С����СͶ�ʽ��ɸġ�
		if($data['account']<$borrow_result['tender_account_min']){
			return "��С��Ͷ�ʽ���С��{$borrow_result['tender_account_min']}��";
		}
		
		//֧�����벻��ȷ
		if (md5($data['paypassword'])!=$_G['user_result']['paypassword']){
			return "borrow_paypassword_error";
		}
		unset($data['paypassword']);
		
		//�ھŲ�,�ж��Ƿ����Ͷ�ʽ��ɸġ�
		/*
		if($data['account']>$borrow_result['tender_account_max']){
			return "����Ͷ�ʽ��ܴ���".($borrow_result['tender_account_max'])."��";
		}
		*/
		$tender_account_all = borrowClass::GetUserTenderAccount(array("user_id"=>$data["user_id"],"borrow_nid"=>$data['borrow_nid']));
		if ($tender_account_all+$data['account']>$borrow_result['tender_account_max'] && $borrow_result['tender_account_max']>0){
			$tender_account = $borrow_result['tender_account_max']-$tender_account_all;
			return"���Ѿ�Ͷ����{$tender_account_all},���Ͷ���ܽ��ܴ���{$borrow_result['tender_account_max']}������໹��Ͷ��{$tender_account}";
		}else{
			$data['account_tender'] = $data['account'];
			
			//�ж�Ͷ�ʵĽ���Ƿ���ڴ���Ľ��
			if ($borrow_result['borrow_account_wait']<$data['account']){
				$data['account'] = $borrow_result['borrow_account_wait'];
			}
			//�жϽ���Ƿ���һ����
			$account_result =  accountClass::GetAccountUsers(array("user_id"=>$data['user_id']));//��ȡ��ǰ�û������
			if ($account_result['balance']<$data['account']){
				return "tender_money_no";
			}
		}
		
		//��ʮ��������ǵ����꣬���жϵ����Ƿ�����ɡ��ɸġ�
		if($borrow_result['vouch_status']==1 && $borrow_result['vouch_account']!=$borrow_result['vouch_account_yes']){
			return "tender_vouch_full_no";
		}
		
		//��ʮһ�����ж��Ƿ���������ɸġ�
		if ($borrow_result['tender_friends']!=""){
			$_tender_friends = explode("|",$borrow_result['tender_friends']);
			$sql = "select username from {users} where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if (!in_array($result['username'],$_tender_friends)){
				return "tender_friends_error";
			}
		}
		
		
		
		//���Ͷ�ʵĽ����Ϣ
		$sql = "insert into `{borrow_tender}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		$tender_id = $mysql->db_insert_id();
		if ($tender_id>0){
			//1���۳����ý��
			$borrow_url = "<a href=/invest/a{$data['borrow_nid']}.html target=_blank>{$borrow_result['name']}</a>";
			$log_info["user_id"] = $data["user_id"];//�����û�id
			$log_info["nid"] = "tender_frost_".$data['user_id']."_".time();
			$log_info["money"] = $data['account'];//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = 0;//�����ֽ��
			$log_info["balance_frost"] = -$data['account'];//�������ֽ��
			$log_info["frost"] = $data['account'];//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "tender";//����
			$log_info["to_userid"] = $borrow_result['user_id'];//����˭
			if ($data['auto_status']==1){
				$log_info["remark"] = "�Զ�Ͷ��[{$borrow_url}]�������ʽ�";//��ע
			}else{
				$log_info["remark"] = "Ͷ��[{$borrow_url}]�������ʽ�";//��ע
			}
			accountClass::AddLog($log_info);
			
		
			//2�����½�����Ϣ
			$sql = "update  `{borrow}`  set borrow_account_yes=borrow_account_yes+{$data['account']},borrow_account_wait=borrow_account_wait-{$data['account']},borrow_account_scale=(borrow_account_yes/account)*100,tender_times=tender_times+1  where borrow_nid='{$data['borrow_nid']}'";
			$mysql->db_query($sql);//�����Ѿ�Ͷ���Ǯ
			
			//3������ͳ����Ϣ
			borrowClass::UpdateBorrowCount(array("user_id"=>$data['user_id'],"tender_times"=>1,"tender_account"=>$data['account'],"tender_frost_account"=>$data['account']));
		
		
			//4����������
			$borrow_url = "<a href=/invest/a{$borrow_result['borrow_nid']}.html target=_blank>{$borrow_result['name']}</a>";
			$remind['nid'] = "tender";
			$remind['code'] = "borrow";
			$remind['article_id'] = $tender_id;
			$remind['receive_userid'] = $data['user_id'];
			$remind['title'] = "�ɹ�Ͷ��{$borrow_result['name']}";
			$remind['content'] = "���ɹ�Ͷ����{$borrow_url}����ȴ�����Ա���";
			remindClass::sendRemind($remind);
		}
		$borrow=borrowClass::GetOne(array("borrow_nid"=>$data['borrow_nid']));
		if ($borrow['borrow_account_wait']==0 && $borrow['borrow_type']==4){
			$reverify['borrow_nid']=$data['borrow_nid'];
			$reverify['reverify_userid']=0;
			$reverify['reverify_remark']="�Զ����";
			$reverify['status']=3;
			$rever=borrowClass::Reverify($reverify);
		}		
		return $tender_id;
	}
	
	
	/**
	 * ���Ͷ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function CheckTender($data = array()){
		global $mysql,$_G;
		//��һ�����ж�borrow_nid�Ƿ�Ϊ��
		if (IsExiest($data['borrow_nid']) ==""){
			return "borrow_nid_empty";
		}
		
		//�ڶ������ж��Ƿ���ڽ���
		$borrow_result = borrowClass::GetOne(array("borrow_nid"=>$data['borrow_nid']));
		if (!is_array($borrow_result)){
			return "borrow_not_exiest";
		}
		
		//���������ж��˺��Ƿ�����
		if ($_G['user_result']['islock']==1){
			return "borrow_user_lock";
		}
		
		//���Ĳ����ж��˺��˺��Ƿ�һ��
		if (IsExiest($data['user_id']) ==""){
			return "borrow_user_id_empty";
		}
		
        //���岽���ж��Ƿ��Ѿ�ͨ��������ˡ����ɸġ�
		if ($borrow_result['verify_time'] == "" || $borrow_result['status'] != 1){
			return "tender_verify_no";
		}
		
		//���������ж��Ƿ����
		if ($borrow_result['verify_time'] <time() - $borrow_result['borrow_valid_time']*60*60*24){
		
			return "tender_late_yes";
		}
		
		
		return $borrow_result;
	}
}
?>
