<?
/******************************
 * $File: borrow.repay_web.php
 * $Description: ��վ�渶�ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

require_once(ROOT_PATH."modules/users/users.class.php");
require_once(ROOT_PATH."modules/account/account.class.php");
require_once(ROOT_PATH."modules/remind/remind.class.php");
require_once("borrow.class.php");
class borrowrepaywebClass
{

	
	//��һ������������˵���Ϣ
	function RepayStep0($data){
		global $mysql;
		
		//�ж��Ƿ���Ի���
		if (IsExiest($data['repay_id'])==""){
			return "borrow_repay_id_empty";
		}
		if (IsExiest($data['user_id'])==""){
			return "borrow_user_id_empty";
		}
		if (IsExiest($data['borrow_nid'])==""){
			return "borrow_nid_empty";
		}
		
		
		//��һ������ȡ�������Ϣ
		$sql = "select p1.*,p2.username from `{borrow_repay}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.id={$data['repay_id']} and p1.user_id='{$data['user_id']}' and p1.borrow_nid='{$data['borrow_nid']}'";
		$repay_result= $mysql->db_fetch_array($sql);
		if ($repay_result==""){
			return "borrow_repay_id_empty";
		}
		if ($repay_result["user_id"]!=$data["user_id"]){
			return "borrow_user_id_empty";
		}
		if ($repay_result["status"]!=1){
			return "borrow_repay_error";
		}
		if ($repay_result["repay_status"]==1){
			return "borrow_repay_error";
		}
		if ($repay_result["repay_web"]==1){
			return "borrow_repay_web_error";
		}
		$repay_id = $repay_result["id"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_period = $repay_result["repay_period"];
		$repay_web = $repay_result["repay_web"];
		$repay_vouch = $repay_result["repay_vouch"];
		$repay_period = $repay_result["repay_period"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_capital = $repay_result["repay_capital"];//�����
		$repay_interest = $repay_result["repay_interest"];//������Ϣ
		$repay_time = $repay_result["repay_time"];//����ʱ��
		
		//��ȡ����������Ϣ
		$borrow_nid = $data['borrow_nid'];
		$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$data['borrow_nid']}'";
		$borrow_result = $mysql->db_fetch_array($sql);
		$status = $borrow_result['status'];
		$borrow_period = $borrow_result['borrow_period'];
		$borrow_frost_account = $borrow_result["borrow_frost_account"];
		$borrow_userid = $borrow_result['user_id'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		//�ж���һ���Ƿ��ѻ���
		if ($repay_period!=0){
			$_repay_period = $repay_period-1;
			$sql = "select repay_status from `{borrow_repay}` where `repay_period`=$_repay_period and borrow_nid={$borrow_nid}";
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false && $result['repay_status']!=1){
				//return "borrow_repay_up_notrepay";
			}
		}
		
		return 1;		
	}
	
	

	
	
	
	//����������������
	//borrow_nid,repay_id
	function RepayStep1($data){
		global $mysql,$_G;
		//�ж��Ƿ���Ի���
		if (IsExiest($data['repay_id'])==""){
			return "borrow_repay_id_empty";
		}
		if (IsExiest($data['user_id'])==""){
			return "borrow_user_id_empty";
		}
		if (IsExiest($data['borrow_nid'])==""){
			return "borrow_nid_empty";
		}
		
		//��һ������ȡ�������Ϣ
		$sql = "select p1.*,p2.username from `{borrow_repay}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.id={$data['repay_id']} and p1.user_id='{$data['user_id']}' and p1.borrow_nid='{$data['borrow_nid']}'";
		$repay_result= $mysql->db_fetch_array($sql);
		if ($repay_result==""){
			return "borrow_repay_id_empty";
		}
		if ($repay_result["user_id"]!=$data["user_id"]){
			return "borrow_user_id_empty";
		}
		if ($repay_result["status"]!=1){
			return "borrow_repay_error";
		}
		if ($repay_result["repay_status"]==1){
			return "borrow_repay_error";
		}
		if ($repay_result["repay_web_status"]==1){
			return "borrow_repay_web_error";
		}
		
		$repay_id = $repay_result["id"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_period = $repay_result["repay_period"];
		$repay_web = $repay_result["repay_web"];
		$repay_vouch = $repay_result["repay_vouch"];
		$repay_period = $repay_result["repay_period"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_capital = $repay_result["repay_capital"];//�����
		$repay_interest = $repay_result["repay_interest"];//������Ϣ
		$repay_time = $repay_result["repay_time"];//����ʱ��
		
		//��ȡ����������Ϣ
		$borrow_nid = $data['borrow_nid'];
		$type = $data['type'];
		$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$data['borrow_nid']}'";
		$borrow_result = $mysql->db_fetch_array($sql);
		$status = $borrow_result['status'];
		$borrow_userid = $borrow_result['user_id'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_username = $borrow_result['username'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		//��ȡͶ�ʻ�����Ϣ
		$sql = "select p1.*,p2.change_status,p2.change_userid from `{borrow_recover}` as p1 left join {borrow_tender} as p2 on p1.tender_id=p2.id where p1.`recover_period` = '{$repay_period}' and p1.borrow_nid='{$borrow_nid}'  limit {$data['key']},1";
		$recover_result = $mysql->db_fetch_array($sql);
		if ($recover_result==false) return -1;
		
		$money = $recover_result['repay_account'];
		$re_time = (strtotime(date("Y-m-d",$repay_time))-strtotime(date("Y-m-d",time())))/(60*60*24);
				
		
		$vip_status=usersClass::GetUsersVip(array("user_id"=>$recover_result['user_id']));
		$credit=borrowClass::GetBorrowCredit(array("user_id"=>$recover_result['user_id']));
		
		if ($type=="web" || $type=="five"){
			if($vip_status['status']==1){
				$recover_account = $recover_result['recover_account'];
				$more="���Ϊ��Ϣ��";
			}else{
				$recover_account = $recover_result['recover_capital'];
				$more="���Ϊ����";
			}
			$all_account=$recover_result['recover_account'];
		}elseif($type=="ten"){
			if ($recover_result['recover_web_five_status']==1){
				$re_sql="select sum(recover_account) as all_account,sum(recover_capital) as all_capital from {borrow_recover} where user_id={$recover_result['user_id']} and recover_web=0 and recover_web_ten_status=0 and recover_period!={$repay_period} and recover_status=0 and borrow_nid={$borrow_nid}";
			}else{
				$re_sql="select sum(recover_account) as all_account,sum(recover_capital) as all_capital from {borrow_recover} where user_id={$recover_result['user_id']} and recover_web=0 and recover_web_ten_status=0 and recover_web_five_status=0 and recover_status=0 and borrow_nid={$borrow_nid}";
			}
			$re_result=$mysql->db_fetch_array($re_sql);
			if($vip_status['status']==1){
				$recover_account = $re_result['all_account'];
				$more="���Ϊʣ�౾Ϣ��";
			}else{
				$recover_account = $re_result['all_capital'];
				$more="���Ϊʣ�౾��";
			}
			$all_account=$re_result['all_account'];
		}
		$web['money']=$recover_account;
		$web['user_id']=$recover_result['user_id'];
		$web['nid']="web_repay_".time();
		$web['type']="web_repay";
		if ($type=="web" || $type=="five"){
			$web['remark']="�û�Ͷ��{$borrow_url}��".($repay_period+1)."�������յ���վ�渶��{$recover_account}Ԫ��{$more}";
		}elseif($type=="ten"){
			$web['remark']="�û�Ͷ��{$borrow_url}���ڳ���30���յ���վ�渶��{$recover_account}Ԫ��{$more}";
		}
		accountClass::AddAccountWeb($web);
		//�û��Խ���Ļ���
		if ($recover_result['change_status']==1){
			$log_info["user_id"] = $recover_result['change_userid'];//�����û�id
		}else{
			$log_info["user_id"] = $recover_result['user_id'];//�����û�id
		}
		$log_info["nid"] = "tender_repay_yes_".$recover_result['user_id']."_".$borrow_nid."_".$recover_result['id']."_".$type;//������
		$log_info["money"] = $recover_account;//�������
		$log_info["income"] = $recover_account;//����
		$log_info["expend"] = 0;//֧��		
		$log_info["balance_cash"] = $recover_account;//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = 0;//������
		$log_info["await"] = -$all_account;//���ս��
		$log_info["type"] = "tender_repay_yes";//����
		$log_info["to_userid"] = 0;//����˭
		if ($type=="web"){
			$log_info["remark"] = "[{$borrow_url}]�����".($repay_period+1)."�����ڳ���10����վ�渶����";
		}elseif($type=="five"){
			$log_info["remark"] = "[{$borrow_url}]�����".($repay_period+1)."�����ڳ���5����վ�渶����";
		}elseif($type=="ten"){
			$log_info["remark"] = "[{$borrow_url}]�������ڳ���30����վ�渶ʣ��ȫ��";
		}
		accountClass::AddLog($log_info);
				
				
		$vip=usersClass::GetUsersVip(array("user_id"=>$recover_result['user_id']));
		if ($vip['status']==1){
			$service_fee_vip=isset($_G['system']['con_repay_interest_service_vip'])?$_G['system']['con_repay_interest_service_vip']:8;
		}else{
			$service_fee=isset($_G['system']['con_repay_interest_service'])?$_G['system']['con_repay_interest_service']:10;
		}
		$service_fee=round($recover_result['recover_interest']*$service_fee*0.01,2);
		$log_info["user_id"] = $recover_result['user_id'];//�����û�id
		$log_info["nid"] = "repay_interest_service_".$recover_result['user_id']."_".$borrow_nid.$recover_result['id'];//������
		$log_info["money"] = $service_fee;//�������
		$log_info["income"] = 0;//����
		$log_info["expend"] = $service_fee;//֧��
		$log_info["balance_cash"] = -$service_fee;//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = 0;//������
		$log_info["await"] = 0;//���ս��
		$log_info["type"] = "repay_interest_service";//����
		$log_info["to_userid"] = 0;//����˭
		$log_info["remark"] = "[{$borrow_url}]����۳�Ͷ����Ϣ�����";
		//accountClass::AddLog($log_info);
		//����ͳ����Ϣ
		
		if ($type=="web"){
			$sql = "update  `{borrow_recover}` set recover_web =1,recover_yestime='".time()."',recover_account_yes = recover_account ,recover_capital_yes = recover_capital ,recover_interest_yes = recover_interest ,status=1,late_days=0,late_interest=0 where id = '{$recover_result['id']}'";
			$mysql->db_query($sql);
		}elseif($type=="five"){
			$sql = "update  `{borrow_recover}` set recover_web_five_status =1,recover_yestime='".time()."',recover_account_yes = recover_account ,recover_capital_yes = recover_capital ,recover_interest_yes = recover_interest ,status=1,late_days=0,late_interest=0 where id = '{$recover_result['id']}'";
			$mysql->db_query($sql);
		}elseif($type=="ten"){
			$sql = "update  `{borrow_recover}` set recover_web_five_status=1,recover_web_ten_status =1,recover_yestime='".time()."',recover_account_yes = recover_account ,recover_capital_yes = recover_capital ,recover_interest_yes = recover_interest ,status=1,late_days=0,late_interest=0 where borrow_nid={$borrow_nid} and recover_web=0 and recover_web_ten_status=0 and recover_web_five_status=0 and recover_status=0 and user_id={$recover_result['user_id']}";
			$mysql->db_query($sql);
		}
		
		//����Ͷ�ʵ���Ϣ
		$sql = "select count(1) as recover_times,sum(recover_account_yes) as recover_account_yes_num,sum(recover_interest_yes) as recover_interest_yes_num,sum(recover_capital_yes) as recover_capital_yes_num  from `{borrow_recover}` where tender_id='{$recover_result['tender_id']}' and (recover_status=1 or recover_web=0 or recover_web_ten_status=0 or recover_web_five_status=0)";
		$result = $mysql->db_fetch_array($sql);
		$recover_times = $result['recover_times'];
		
		$sql = "update  `{borrow_tender}` set recover_times={$recover_times},recover_account_yes= {$result['recover_account_yes_num']},recover_account_capital_yes =  {$result['recover_capital_yes_num']} ,recover_account_interest_yes = {$result['recover_interest_yes_num']},recover_account_wait= recover_account_all - recover_account_yes,recover_account_capital_wait = account - recover_account_capital_yes  ,recover_account_interest_wait = recover_account_interest -  recover_account_interest_yes  where id = '{$recover_result['tender_id']}'";
		$mysql->db_query($sql);
		
		//վ��������
		$remind['nid'] = "loan_advanced";
		$remind['code'] = "borrow";
		$remind['article_id'] = $borrow_nid;
		$remind['receive_userid'] = $recover_result['user_id'];
		$remind['title'] = "��վ��({$borrow_result['name']})���е渶";
		$remind['content'] = "��վ�Խ���[{$borrow_url}]��".date("Y-m-d",time())."���е渶";
		remindClass::sendRemind($remind);
		
		
		
		return 1;
	}
	
	
	//���岽�������»�����Ϣ
	//borrow_nid,repay_id
	function RepayStep2($data){
		global $mysql,$_G;
		//�ж��Ƿ���Ի���
		if (IsExiest($data['repay_id'])==""){
			return "borrow_repay_id_empty";
		}
		if (IsExiest($data['user_id'])==""){
			return "borrow_user_id_empty";
		}
		if (IsExiest($data['borrow_nid'])==""){
			return "borrow_nid_empty";
		}
		
		//��һ������ȡ�������Ϣ
		$sql = "select p1.*,p2.username from `{borrow_repay}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.id={$data['repay_id']} and p1.user_id='{$data['user_id']}' and p1.borrow_nid='{$data['borrow_nid']}'";
		$repay_result= $mysql->db_fetch_array($sql);
		if ($repay_result==""){
			return "borrow_repay_id_empty";
		}
		if ($repay_result["user_id"]!=$data["user_id"]){
			return "borrow_user_id_empty";
		}
		if ($repay_result["status"]!=1){
			return "borrow_repay_error";
		}
		
		if ($repay_result["repay_status"]==1){
			return "borrow_repay_error";
		}
		
		if ($repay_result["repay_web_status"]==1){
			return "borrow_repay_web_error";
		}
		$repay_id = $repay_result["id"];
		
		
		$borrow_nid = $data['borrow_nid'];
		$type = $data['type'];
		if ($type=="web"){
			$sql = "update `{borrow_repay}` set repay_web=1,repay_yestime='".time()."',repay_account_yes=repay_account,repay_interest_yes=repay_interest,repay_capital_yes=repay_capital where id='{$repay_id}'";
			$mysql->db_query($sql);
		}elseif($type=="five"){
			$sql = "update `{borrow_repay}` set repay_web_five_status=1,repay_yestime='".time()."',repay_account_yes=repay_account,repay_interest_yes=repay_interest,repay_capital_yes=repay_capital where id='{$repay_id}'";
			$mysql->db_query($sql);
		}elseif($type=="ten"){
			$sql = "update `{borrow_repay}` set repay_web_ten_status=1,repay_yestime='".time()."',repay_account_yes=repay_account,repay_interest_yes=repay_interest,repay_capital_yes=repay_capital where borrow_nid='{$borrow_nid}' and repay_web=0 and repay_web_ten_status=0 and repay_status=0";
			$mysql->db_query($sql);
		}
	
		return 1;
	}
}
?>
