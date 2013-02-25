<?
/******************************
 * $File: borrow.repay.php
 * $Description: �����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

require_once(ROOT_PATH."modules/users/users.class.php");
require_once(ROOT_PATH."modules/account/account.class.php");
require_once(ROOT_PATH."modules/remind/remind.class.php");
class borrowrepayClass
{

	
	//��һ������������˵���Ϣ
	function RepayStep0($data){
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
				return "borrow_repay_up_notrepay";
			}
		}
		
		//����Ƿ�����,���Ҽ������ڵķ���
		$late = borrowClass::LateInterest(array("time"=>$repay_time,"account"=>$repay_account,"capital"=>$repay_capital));
		$late_days = $late['late_days'];
		$late_interest = round($repay_account/100*0.4*$late_days,2);
		$late_reminder = round($repay_account/100*0.8*$late_days,2);
		$late_account = $late_interest;
		
		
		//�жϿ�������Ƿ񹻻���
		$account_result =  accountClass::GetAccountUsers(array("user_id"=>$borrow_userid));//��ȡ��ǰ�û������;
		if ($account_result['balance']<$repay_account+$late_account){
			return "borrow_repay_account_use_none";
		}
		
		$log_info["user_id"] = $borrow_userid;//�����û�id
		$log_info["nid"] = "borrow_repay_".$borrow_userid."_".$borrow_nid."_".$repay_id;//������
		$log_info["money"] = $repay_account;//�������
		$log_info["income"] = 0;//����
		$log_info["expend"] = $repay_account;//֧��
		$log_info["balance_cash"] = 0;//�����ֽ��
		$log_info["balance_frost"] = -$repay_account;//�������ֽ��
		$log_info["frost"] = 0;//������
		$log_info["await"] = 0;//���ս��
		$log_info["type"] = "borrow_repay";//����
		$log_info["to_userid"] = 0;//����˭
		$log_info["remark"] = "��[{$borrow_url}]�����".($repay_period+1)."�ڻ���";
		accountClass::AddLog($log_info);
		
		$user_log["user_id"] = $borrow_userid;
		$user_log["code"] = "borrow";
		$user_log["type"] = "repay_success";
		$user_log["operating"] = "repay";
		$user_log["article_id"] = $borrow_userid;
		$user_log["result"] = 1;
		$user_log["content"] = "�Խ���[{$borrow_url}]���л���";
		usersClass::AddUsersLog($user_log);	
		
		if ($repay_period+1 == $borrow_period){
			if ($borrow_frost_account>0){
				//�ڰ˲������һ��������Ľ��
				$log_info["user_id"] = $borrow_userid;//�����û�id
				$log_info["nid"] = "borrow_frost_repay_".$borrow_userid."_".$borrow_nid.$borrow_period;//������
				$log_info["money"] = $borrow_frost_account;//�������
				$log_info["income"] =0;//����
				$log_info["expend"] = 0;//֧��
				$log_info["balance_cash"] = $borrow_frost_account;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = -$borrow_frost_account;//������
				$log_info["await"] = 0;//���ս��
				$log_info["type"] = "borrow_frost_repay";//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] = "��[{$borrow_url}]���Ľⶳ";
				accountClass::AddLog($log_info);
			}
			//��֤��		 wdf 20121115
			$sql = "select account from `{borrow}` as p1  where  p1.user_id='{$data['user_id']}' and p1.borrow_nid='{$data['borrow_nid']}'";
			$_result = $mysql->db_fetch_array($sql);	
			
			
			$borrow_margin=isset($_G['system']['con_borrow_margin_fee'])?$_G['system']['con_borrow_margin_fee']:0.003;
			$borrow_margin_fee = $_result['account']*$borrow_margin/100;
			
			$log_info["user_id"] = $borrow_userid;//�����û�id
			$log_info["nid"] = "borrow_margin_fee_return_".$borrow_userid."_".$borrow_nid;//������
			$log_info["money"] = $borrow_margin_fee;//�������
			$log_info["income"] = $borrow_margin_fee;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $borrow_margin_fee;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = -$borrow_margin_fee;//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "borrow_margin_return_fee";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "�û���[$borrow_url]����ɹ�,�ⶳ{$borrow_margin_fee}Ԫ��֤��";
			accountClass::AddLog($log_info);
	
		}
		
		if ($late_interest>0){
			$log_info["user_id"] = $borrow_userid;//�����û�id
			$log_info["nid"] = "borrow_repay_late_".$borrow_userid."_".$borrow_nid."_".$repay_id;//������
			$log_info["money"] = $late_interest;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = $late_interest;//֧��
			$log_info["balance_cash"] = -$late_interest;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "borrow_repay_late";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "��[{$borrow_url}]����".($repay_period+1)."�ڵ����ڽ��Ŀ۳�";
			accountClass::AddLog($log_info);
			
			//���ɽ�۳�
			$log_info["user_id"] = $borrow_userid;//�����û�id
			$log_info["nid"] = "borrow_repay_reminder_0_".$borrow_nid.$repay_id;//������
			$log_info["money"] = $late_reminder;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = $late_reminder;//֧��
			$log_info["balance_cash"] = -$late_reminder;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "borrow_repay_reminder";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "��[{$borrow_url}]����".($repay_period+1)."�ڵ��������ɽ�Ŀ۳�";;
			//accountClass::AddLog($log_info);
			
			
		}
		
		// * �������ڵ���Ϣ
		$sql = "update`{borrow_repay}` set late_days = '{$late_days}',late_interest = '{$late_interest}',late_reminder = '{$late_reminder}' where id = {$data['repay_id']}";
		$mysql->db_query($sql);
		
		return 1;		
	}
	
	//�ڶ����������Ļ���
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
		if ($repay_result["repay_status"]==1){
			return "borrow_repay_error";
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
		
		$repay_id = $repay_result["id"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_period = $repay_result["repay_period"];
		$repay_web = $repay_result["repay_web"];
		$repay_vouch = $repay_result["repay_vouch"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_capital = $repay_result["repay_capital"];//�����
		$repay_interest = $repay_result["repay_interest"];//������Ϣ
		$repay_time = $repay_result["repay_time"];//����ʱ��
		
		//��ȡ����������Ϣ
		$borrow_nid = $data['borrow_nid'];
		$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$data['borrow_nid']}'";
		$borrow_result = $mysql->db_fetch_array($sql);
		$status = $borrow_result['status'];
		$borrow_userid = $borrow_result['user_id'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_username = $borrow_result['username'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		
		//��ʮ�������û���������
		if ($repay_web!=1 && $repay_vouch!=1){
			$sql = "select p1.* from `{borrow_recover}` as p1  where p1.`recover_period` = '{$repay_period}' and p1.borrow_nid='{$borrow_nid}' limit {$data['key']},1";
			$recover_result = $mysql->db_fetch_array($sql);
			if ($recover_result==false) return -1;
			$re_time = (strtotime(date("Y-m-d",$repay_time))-strtotime(date("Y-m-d",time())))/(60*60*24);
					
			
			//��ӽ���߻���
			if ($re_time!=0){
				if ($re_time<0){
					if ($re_time>=-3 && $re_time<=-1){
						$borrow_credit_nid = "borrow_repay_slow";
					}
					elseif ($re_time>=-30 && $re_time<-3){
						$borrow_credit_nid = "borrow_repay_late_common";
					}
					elseif ($re_time>=-90 && $re_time<-30){
						$borrow_credit_nid = "borrow_repay_late_serious";
					}
					elseif ( $re_time<-90){
						$borrow_credit_nid = "borrow_repay_late_spite";
					}
				}else{
					if ($re_time<=30 && $re_time>=3){
						$borrow_credit_nid = "borrow_repay_advance";
						$tender_credit_nid = "tender_repay_advance";
					}elseif ($re_time>=1 && $re_time<=3){
						$borrow_credit_nid = "borrow_repay_ontime";
						$tender_credit_nid = "tender_repay_ontime";
					}
				}
			}	
				
			if($borrow_credit_nid!=""){
				//��ӽ���߻���
				$credit_blog['user_id'] = $borrow_userid;
				$credit_blog['nid'] = $borrow_credit_nid;
				$credit_blog['code'] = "borrow";
				$credit_blog['type'] = "repay";
				$credit_blog['addtime'] = time();
				$credit_blog['article_id'] =$repay_id;
				$credit_blog['remark'] = "����[{$borrow_url}]��".($repay_period+1)."�ڻ���";
				creditClass::ActionCreditLog($credit_blog);
			}
			//����ߵ����û�������
			$credit_log['user_id'] = $borrow_userid;
			$credit_log['nid'] = "borrow_success";
			$credit_log['code'] = "borrow";
			$credit_log['type'] = "borrow";
			$credit_log['addtime'] = time();
			$credit_log['article_id'] =$repay[0]['id'];
			$credit_log['value'] = round($repay_capital*0.01);			
			$result = creditClass::ActionCreditLog($credit_log);
			
			
			
			
			
			//����Ͷ���˵�������Ϣ
			$late = borrowClass::LateInterest(array("time"=>$recover_result['recover_time'],"capital"=>$recover_result['recover_capital']));			
			
			//��Ϣ����7�죬��������ϢΪ0
			$late_days = $late['late_days'];	
			$late_interest = round($recover_result['recover_account']*0.004*$late_days/2,2);//����
			$late_reminder = round($recover_result['recover_account']*0.008*$late_days/2,2);//���ɽ�			
			$sql = "update  `{borrow_recover}` set recover_yestime='".time()."',recover_account_yes = recover_account ,recover_capital_yes = recover_capital ,recover_interest_yes = recover_interest ,status=1,recover_status=1,late_days={$late_days},late_interest={$late_interest} where id = '{$recover_result['id']}'";
			$mysql->db_query($sql);
			
			//���Ͷ���߻���
			if($tender_credit_nid!=""){
				$credit_blog['user_id'] = $recover_result['user_id'];
				$credit_blog['nid'] = $tender_credit_nid;
				$credit_blog['code'] = "tender";
				$credit_blog['type'] = "tender_repay";
				$credit_blog['addtime'] = time();
				$credit_blog['article_id'] =$repay_id;
				$credit_blog['remark'] = "�û�����[{$borrow_url}]��".($repay_period+1)."��Ͷ�ʻ���";
				creditClass::ActionCreditLog($credit_blog);
			}
			
				
			//��������
			if($late_interest>0){
				$log_info["user_id"] = $recover_result['user_id'];//�����û�id
				$log_info["nid"] = "tender_repay_late_".$recover_result['user_id']."_".$borrow_nid."_".$repay_id."_".$recover_result['id'];//������
				$log_info["money"] = $late_interest;//�������
				$log_info["income"] = $late_interest;//����
				$log_info["expend"] =0;//֧��
				$log_info["balance_cash"] = $late_interest;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["type"] = "late_repay_web";//����
				$log_info["to_userid"] = $borrow_userid;//����˭
				$log_info["remark"] = "�û�{$borrow_username}��[{$borrow_url}]�������".$late_days."�췣Ϣ����";
				//accountClass::AddLog($log_info);
			}
			
			
			//����Ͷ�ʵ���Ϣ
			$sql = "select count(1) as recover_times,sum(recover_account_yes) as recover_account_yes_num,sum(recover_interest_yes) as recover_interest_yes_num,sum(recover_capital_yes) as recover_capital_yes_num  from `{borrow_recover}` where tender_id='{$recover_result['tender_id']}' and recover_status=1";
			$result = $mysql->db_fetch_array($sql);
			$recover_times = $result['recover_times'];
			
			$sql = "update  `{borrow_tender}` set recover_times={$recover_times},recover_account_yes= {$result['recover_account_yes_num']},recover_account_capital_yes =  {$result['recover_capital_yes_num']} ,recover_account_interest_yes = {$result['recover_interest_yes_num']},recover_account_wait= recover_account_all - recover_account_yes,recover_account_capital_wait = account - recover_account_capital_yes  ,recover_account_interest_wait = recover_account_interest -  recover_account_interest_yes  where id = '{$recover_result['tender_id']}'";
			$mysql->db_query($sql);
				
				
			//�û��Խ���Ļ���
			$log_info["user_id"] = $recover_result['user_id'];//�����û�id
			$log_info["nid"] = "tender_repay_yes_".$recover_result['user_id']."_".$borrow_nid."_".$recover_result['id'];//������
			$log_info["money"] = $recover_result['recover_account'];//�������
			$log_info["income"] = $recover_result['recover_account'];//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $recover_result['recover_account'];//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = -$recover_result['recover_account'];//���ս��
			$log_info["type"] = "tender_repay_yes";//����
			$log_info["to_userid"] = $borrow_userid;//����˭
			$log_info["remark"] = "�ͻ���{$borrow_username}����[{$borrow_url}]����ĵ�".($repay_period+1)."�ڻ���";
			accountClass::AddLog($log_info);
			
			$user_log["user_id"] = $recover_result['user_id'];
			$user_log["code"] = "tender";
			$user_log["type"] = "recover_success";
			$user_log["operating"] = "recover";
			$user_log["article_id"] = $recover_result['user_id'];
			$user_log["result"] = 1;
			$user_log["content"] = "�յ�����[{$borrow_url}]�Ļ���";
			usersClass::AddUsersLog($user_log);	
			
			//���Ͷ���˷�Ϣ wdf
			$res_time = borrowClass::LateInterest(array("time"=>$repay_time,"account"=>$repay_account));
			$late_fee=round($repay_account*$res_time['late_days']*0.004,2);
			
			borrowClass::UpdateBorrowCount(array("user_id"=>$recover_result['user_id'],"tender_recover_times_yes"=>1,"tender_recover_times_wait"=>-1,"tender_recover_yes"=>$recover_result['recover_account'],"tender_recover_wait"=>-$recover_result['recover_account'],"tender_capital_yes"=>$recover_result['recover_capital'],"tender_capital_wait"=>-$recover_result['recover_capital'],"tender_interest_yes"=>$recover_result['recover_interest'],"tender_interest_wait"=>-$recover_result['recover_interest'],"late_add_account"=>$late_fee));
			
			
			$vip=usersClass::GetUsersVip(array("user_id"=>$recover_result['user_id']));
			if ($vip['status']==1){
				$service_fee=isset($_G['system']['con_repay_interest_service_vip'])?$_G['system']['con_repay_interest_service_vip']:8;
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
			accountClass::AddLog($log_info);
			
			if ($late_days>0 ){
				$log_info["user_id"] = $recover_result['user_id'];//�����û�id
				$log_info["nid"] = "tender_late_repay_yes_".$recover_result['user_id']."_".$borrow_nid."_".$recover_result['id'];//������
				$log_info["money"] = $late_interest;//�������
				$log_info["income"] = $late_interest;//����
				$log_info["expend"] = 0;//֧��
				$log_info["balance_cash"] = $late_interest;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["type"] = "tender_late_repay_yes";//����
				$log_info["to_userid"] = $value['user_id'];//����˭
				$log_info["remark"] = "�ͻ���{$borrow_username}����[{$borrow_url}]�������".$late_days."�컹��ķ���";
				accountClass::AddLog($log_info);			
			}
			
				$sql_b = "select username from `{users}` where user_id='{$recover_result['user_id']}'";
				$result_b = $mysql->db_fetch_array($sql_b);
				$tende_username = $result_b['username'];
				
				//�������յ�����վ���� add 20120920 wlz
				$remind['nid'] = "repay_yes";
				
				$remind['receive_userid'] = $borrow_userid;
				$remind['code'] = "borrow";
				$remind['article_id'] = $borrow_userid;
				$remind['title'] = "���ѶԿͻ�".$tende_username."�ɹ����";
				$remind['content'] = "������".date("Y-m-d",time())."�Կͻ�".$tende_username."����ɹ��������".$recover_result['recover_account'];
				
				remindClass::sendRemind($remind);
				
			
				$sql_a = "select username from `{users}` where user_id=$borrow_userid";
				$result_a = $mysql->db_fetch_array($sql_a);
				$borrow_username = $result_a['username'];
				
				//Ͷ�����յ�����վ���� add 20120920 wlz
				$remind['nid'] = "loan_pay";				
				$remind['receive_userid'] = $recover_result['user_id'];
				$remind['code'] = "invest";
				$remind['article_id'] = $recover_result['user_id'];
				$remind['title'] = "�û���".$borrow_username."��������Ͷ�ʵĽ���[{$borrow_url}]�Ѿ��ɹ����";
				$remind['content'] = "�û���".$borrow_username."����".date("Y-m-d",time())."������Ͷ�ʵĽ���[{$borrow_url}]�Ѿ��ɹ�����,�����".$recover_result['recover_account'];				
				remindClass::sendRemind($remind); 
				
				
		}else{
			
			//������վ�渶 ����߻�����ֿ۳�  wdf 2012 09 24
			if($repay_web==1){								
				$credit_blog['user_id'] = $borrow_userid;
				$credit_blog['nid'] = "borrow_repay_late_serious";
				$credit_blog['code'] = "borrow";
				$credit_blog['type'] = "repay";
				$credit_blog['addtime'] = time();
				$credit_blog['article_id'] =$repay_id;
				$credit_blog['remark'] = "����[{$borrow_url}]��".($repay_period+1)."�ڻ���";
				creditClass::ActionCreditLog($credit_blog);
				
				//����ߵ����û�������
				$credit_log['user_id'] = $borrow_userid;
				$credit_log['nid'] = "borrow_success";
				$credit_log['code'] = "borrow";
				$credit_log['type'] = "borrow";
				$credit_log['addtime'] = time();
				$credit_log['article_id'] =$repay['id'];
				$credit_log['value'] = round($repay_capital*0.01);				
				$result = creditClass::ActionCreditLog($credit_log);
			}
			return -1;
		}
		
		
		return 1;
		
	}
	
	
	//����������վ����
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
		$borrow_userid = $borrow_result['user_id'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_username = $borrow_result['username'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		return 1;
	}
	
	
	//���Ĳ�����������
	//borrow_nid,repay_id
	function RepayStep3($data){
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
		$borrow_userid = $borrow_result['user_id'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_username = $borrow_result['username'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		if ($repay_vouch==1){
			$sql = "select p1.* from `{borrow_vouch_recover}` as p1  where p1.`order` = '{$repay_period}' and p1.borrow_nid='{$borrow_nid}' limit {$data['key']},1";
			$vouch_recover_result = $mysql->db_fetch_array($sql);
			if ($vouch_recover_result ==false) return -1;
			$late_rate = isset($_G['system']['con_late_rate'])?$_G['system']['con_late_rate']:0.008;
			 $money = $vouch_recover_result['repay_account'];
		
			$log_info["user_id"] = $vouch_recover_result['user_id'];//�����û�id
			$log_info["nid"] = "vouch_tender_repay_yes_".$borrow_nid."_".$repay_id."_".$vouch_recover_result['id'];//������
			$log_info["money"] = $money;//�������
			$log_info["income"] = $money;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $money;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = -$money;//���ս��
			$log_info["type"] = "vouch_tender_repay_yes";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "�û��ɹ��Ե�������[$borrow_url]��".($repay_period+1)."�ڻ���";
			accountClass::AddLog($log_info);
		}
		return -1;
	}
	//�������������껹��e���
	//borrow_nid,repay_id
	function RepayStep4($data){
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
		$borrow_userid = $borrow_result['user_id'];
		$vouch_status = $borrow_result['vouch_status'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_username = $borrow_result['username'];
		$borrow_type = $borrow_result['borrow_type'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		if($borrow_type!=4){
			//�۳�������� wdf
			$borrow_manage_fee = $borrow_account*0.003;
			$log_info["user_id"] = $borrow_userid;//�����û�id
			$log_info["nid"] = "borrow_manage_fee_".$borrow_userid."_".$repay_id;
			$log_info["money"] = $borrow_manage_fee;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = $borrow_manage_fee;//֧��
			$log_info["balance_cash"] = -$borrow_manage_fee;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "borrow_manage_fee";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "���۳��������{$borrow_manage_fee}Ԫ";
			//$result = accountClass::AddLog($log_info);		
		}
		

		
		/* if ($borrow_type==5){
			$sql = "select * from `{borrow_vouch_recover}` where borrow_nid='{$borrow_nid}' and `order`={$repay_period}  limit {$data['key']},1";
			$vouch_recover_result = $mysql->db_fetch_array($sql);
			if ($vouch_recover_result==false){
				//��ʮһ��������˵�����ȵ�����
				$_data["user_id"] = $borrow_userid;
				$_data["amount_type"] = "vouch_borrow";
				$_data["type"] = "borrrow_vouch_repay";
				$_data["oprate"] = "return";
				$_data["nid"] = "borrrow_vouch_repay_".$borrow_userid."_".$borrow_nid."_".$repay_period;
				$_data["account"] = $repay_capital;
				$_data["remark"] =   "����[{$borrow_url}]��".($repay_period+1)."�ڻ�����ɣ�������ȷ���";
				borrowClass::AddAmountLog($_data);
				
				$sql = "update `{borrow_vouch_repay}` set repay_yestime = ".time().",repay_yesaccount = repay_account,status=1 where borrow_nid='{$borrow_nid}' and `order`={$repay_period}";
				$mysql->db_query($sql);
				return -1;
			}else{
				$_data["user_id"] = $vouch_recover_result['user_id'];
				$_data["amount_type"] = "vouch_tender";
				$_data["type"] = "borrrow_vouch_recover";
				$_data["oprate"] = "return";
				$_data["nid"] = "borrrow_vouch_recover_".$vouch_recover_result['user_id']."_".$borrow_nid."_".$vouch_recover_result['id'];
				$_data["account"] = $vouch_recover_result['repay_capital'];
				$_data["remark"] =  "������[{$borrow_url}]��".($repay_period+1)."�ڻ���ɹ���Ͷ�ʵ�����ȷ���";
				borrowClass::AddAmountLog($_data);
				$sql = "update `{borrow_vouch_recover}` set repay_yestime = ".time().",repay_yesaccount = {$vouch_recover_result['repay_account']},status=1 where id = {$vouch_recover_result['id']}";
				$mysql->db_query($sql);
				return -1;
			}
		
		
		
		}elseif ($borrow_type==4 || $borrow_type==1){
			//����Ͷ�ʶ�ȵ�����
			$_data["user_id"] = $borrow_userid;
			$_data["amount_type"] = "borrow";
			$_data["type"] = "borrrow_repay";
			$_data["oprate"] = "return";
			$_data["nid"] = "borrrow_repay_".$borrow_userid."_".$borrow_nid."_".$repay_id;
			$_data["account"] = $repay_capital;
			$_data["remark"] = "����[{$borrow_url}]�ɹ�����������";
			borrowClass::AddAmountLog($_data);
			return -1;
		} */
	
		return -1;
	}
	
	//���岽�������»�����Ϣ
	//borrow_nid,repay_id
	function RepayStep5($data){
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
		$repay_id = $repay_result["id"];
		$repay_account = $repay_result["repay_account"];//�����ܶ�
		$repay_status = $repay_result["repay_status"];//�����ܶ�
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
		$borrow_userid = $borrow_result['user_id'];
		$vouch_status = $borrow_result['vouch_status'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$borrow_username = $borrow_result['username'];
		$borrow_url = html_entity_decode("<a href=/invest/a{$borrow_nid}.html target=_blank style=color:blue>{$borrow_result['name']}</a>");//�����ַ
		
		
		
		
		if ($repay_status!=1){
			//������Ļ�����
			$sql = "update `{borrow}` set repay_account_yes= repay_account_yes + {$repay_account},repay_account_capital_yes= repay_account_capital_yes + {$repay_capital},repay_account_interest_yes= repay_account_interest_yes + {$repay_interest},repay_account_wait= repay_account_wait - {$repay_account},repay_account_capital_wait= repay_account_capital_wait - {$repay_capital},repay_account_interest_wait= repay_account_interest_wait - {$repay_interest} where borrow_nid='{$borrow_nid}'";
			$result = $mysql -> db_query($sql);
			
			
			//����ͳ����Ϣ
			borrowClass::UpdateBorrowCount(array("user_id"=>$borrow_userid,"borrow_repay_yes_times"=>1,"borrow_repay_wait_times"=>-1,"borrow_repay_yes"=>$repay_account,"borrow_repay_wait"=>-$repay_account,"borrow_repay_interest_yes"=>$repay_interest,"borrow_repay_interest_wait"=>-$repay_interest,"borrow_repay_capital_yes"=>$repay_capital,"borrow_repay_capital_wait"=>-$repay_capital));	
			
			$sql = "update `{borrow_repay}` set repay_status=1,repay_yestime='".time()."',repay_account_yes=repay_account,repay_interest_yes=repay_interest,repay_capital_yes=repay_capital where id='{$repay_id}'";
			$mysql->db_query($sql);
		}
		return 1;
	}
}
?>
