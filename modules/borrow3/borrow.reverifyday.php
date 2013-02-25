<?php

/******************************
 * $File: borrow.reverify.php
 * $Description: ������ļ�
 * $Author: XiaoWu 
 * $Time:2012-08-16
 * $Update:XiaoWu
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

class borrowreverifydayClass
{
	public static function ReverifyDay($data = array()){
		global $mysql,$_G;
		if (IsExiest($data["borrow_nid"])=="") return "borrow_nid_empty";
		$borrow_nid = $data["borrow_nid"];
		$sql = " update `{borrow}` set reverify_userid='{$data['reverify_userid']}',reverify_remark='{$data['reverify_remark']}',reverify_time='".time()."',status='{$data['status']}' where borrow_nid='{$borrow_nid}'";
		 $mysql ->db_query($sql);
		 $status = $data['status'];
		$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$data['borrow_nid']}'";
		$borrow_result = $mysql->db_fetch_array($sql);
		$borrow_userid = $borrow_result["user_id"];//����û�id
		$borrow_username = $borrow_result["username"];//����û�id
		$borrow_account = $borrow_result["account"];//�����
		$borrow_day = $borrow_result["borrow_day"];//�������
		$borrow_apr = $borrow_result["borrow_apr"];//�������
		$borrow_name = $borrow_result["name"];//��� ����
		$borrow_type = $borrow_result["type"];//��� ����
		$borrow_cash_status = $borrow_result["cash_status"];//�Ƿ����ֱ�
		$borrow_url = "<a href=\'/invest/a{$data['borrow_nid']}.html\' target=_blank>{$borrow_result['name']}</a>";
		$repaytime=time()+$borrow_day*60*60*24;
		$repayaccount=round($borrow_account+$borrow_account*$borrow_day*$borrow_apr/365/100,2);
		$repaylixi=round($borrow_account*$borrow_day*$borrow_apr/100/365,2);
		 if ($status == 3){
			$sql = " update `{borrow}` set borrow_full_status='1' where borrow_nid='{$borrow_nid}'";
			$mysql ->db_query($sql);
			
			//�������� ����ɹ� �۳�һ���Զ��Ϊ0  add 20120830 wlz
			if($borrow_result['borrow_type'] ==2 || $borrow_result['borrow_type'] ==3){
				$sql_a = "update `{borrow_amount}` set once_amount=0 where user_id='{$borrow_userid}'";
				$mysql->db_query($sql_a);
			}
			
			//����ߵ����û�������
			$credit_log['user_id'] = $borrow_userid;
			$credit_log['nid'] = "borrow_success";
			$credit_log['code'] = "borrow";
			$credit_log['type'] = "borrow";
			$credit_log['addtime'] = time();
			$credit_log['article_id'] =$repay_id;
			$credit_log['value'] = round($borrow_account*0.01);
			$credit_log['remark'] = "����[{$borrow_url}]�ɹ���������";
			$result = creditClass::ActionCreditLog($credit_log);
			
			
			$sql = "select 1 from `{borrow_repay}` where user_id='{$borrow_userid}' and repay_period='0' and borrow_nid='{$borrow_nid}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==false){
				$sql = "insert into `{borrow_repay}` set `addtime` = '".time()."',";
				$sql .= "`addip` = '".ip_address()."',user_id='{$borrow_userid}',status=1,`borrow_nid`='{$borrow_nid}',`repay_period`='0',";
				$sql .= "`repay_time`='{$repaytime}',`repay_account`='{$repayaccount}',";
				$sql .= "`repay_interest`='{$repaylixi}',`repay_capital`='{$borrow_account}'";
				$mysql ->db_query($sql);
			}
			
			//������ܽ�����ӡ�
			$log_info["user_id"] = $borrow_userid;//�����û�id
			$log_info["nid"] = "borrow_success_".$borrow_nid;//������
			$log_info["money"] = $borrow_account;//�������
			$log_info["income"] = $borrow_account;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $borrow_account;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
			$log_info["repay"] = $borrow_account;//���ս��
			$log_info["type"] = "borrow_success";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] =  "ͨ��[{$borrow_url}]�赽�Ŀ�";
			accountClass::AddLog($log_info);
			
			if ($borrow_type==3 || $borrow_type==5){
				if ($borrow_day==1){
					$borrow_manage_fee=0.004;
				}else{
					$borrow_manage_fee=($borrow_day-1)*0.0004+0.004;
				}
			}elseif($borrow_type==7){
				if ($borrow_day==1){
					$borrow_manage_fee=0.002;
				}else{
					$borrow_manage_fee=($borrow_day-1)*0.0002+0.002;
				}
			}
			$manage_fee = $borrow_account*$borrow_manage_fee;
			$log_info["user_id"] = $borrow_userid;//�����û�id
			$log_info["nid"] = "borrow_manage_fee_".$borrow_userid."_".$borrow_nid.$repay_id;//������
			$log_info["money"] = $manage_fee;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = $manage_fee;//֧��
			$log_info["balance_cash"] = -$manage_fee;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "borrow_manage_fee";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "�û����ɹ�[$borrow_url]�۳��������";
			accountClass::AddLog($log_info);
			
			//����ͳ����Ϣ
			borrowClass::UpdateBorrowCount(array("user_id"=>$borrow_userid,"borrow_success_times"=>1,"borrow_repay_times"=>1,"borrow_repay_wait_times"=>1,"borrow_account"=>$borrow_account,"borrow_repay_account"=>$repayaccount,"borrow_repay_wait"=>$repayaccount,"borrow_repay_interest"=>$repaylixi,"borrow_repay_interest_wait"=>$repaylixi,"borrow_repay_capital"=>$borrow_account,"borrow_repay_capital_wait"=>$borrow_account));
			
			
			
			
			$tender_result = borrowClass::GetTenderList(array("borrow_nid"=>$borrow_nid,"limit"=>"all"));
			foreach ($tender_result as $_key => $_value){
				
				$tender_id = $_value['id'];
				
				//����Ͷ���˵�״̬
				$sql = "update `{borrow_tender}` set status=1 where id={$tender_id}";
				$mysql->db_query($sql);
				
				//���Ͷ�ʵ��տ��¼
				$tender_account = $_value['account'];
				$recover_account=round($tender_account+$tender_account*$borrow_day*$borrow_apr/100/365,2);
				$recover_lixi=round($tender_account*$borrow_day*$borrow_apr/100/365,2);
				
				$tender_userid = $_value['user_id'];
				$sql = "insert into `{borrow_recover}` set `addtime` = '".time()."',";
				$sql .= "`addip` = '".ip_address()."',user_id='{$tender_userid}',status=1,`borrow_nid`='{$borrow_nid}',`borrow_userid`='{$borrow_userid}',`tender_id`='{$tender_id}',`recover_period`='0',";
				$sql .= "`recover_time`='{$repaytime}',`recover_account`='{$recover_account}',";
				$sql .= "`recover_interest`='{$recover_lixi}',`recover_capital`='{$tender_account}'";
				$mysql ->db_query($sql);
				
				$sql = "update `{borrow_tender}` set recover_account_all='{$recover_account}',recover_account_interest='{$recover_lixi}',recover_account_wait='{$recover_account}',recover_account_interest_wait='{$recover_lixi}',recover_account_capital_wait='{$tender_account}'  where id='{$tender_id}'";
				$mysql->db_query($sql);
				
				
				if ($_value['status']!=1){
					//������,�۳�Ͷ���˵��ʽ�
					$log_info["user_id"] = $tender_userid;//�����û�id
					$log_info["nid"] = "tender_succes_".$borrow_nid.$tender_userid.$tender_id;//������
					$log_info["money"] = $tender_account;//�������
					$log_info["income"] = 0;//����
					$log_info["expend"] = -$tender_account;//֧��
					$log_info["balance_cash"] = 0;//�����ֽ��
					$log_info["balance_frost"] = 0;//�������ֽ��
					$log_info["frost"] = -$tender_account;//������
					$log_info["await"] = 0;//���ս��
					$log_info["type"] = "tender_success";//����
					$log_info["to_userid"] = $borrow_userid;//����˭
					$log_info["remark"] = "Ͷ��[{$borrow_url}]�ɹ�Ͷ�ʽ��۳�";
					accountClass::AddLog($log_info);
					
					//���߲�,��Ӵ��յĽ��
					$log_info["user_id"] = $tender_userid;//�����û�id
					$log_info["nid"] = "tender_success_frost_".$borrow_nid.$tender_userid.$tender_id;//������
					$log_info["money"] = $recover_account;//�������
					$log_info["income"] = $recover_account;//����
					$log_info["expend"] = 0;//֧��
					$log_info["balance_cash"] = 0;//�����ֽ��
					$log_info["balance_frost"] = 0;//�������ֽ��
					$log_info["frost"] = 0;//������
					$log_info["await"] = $recover_account;//���ս��
					$log_info["type"] = "tender_success_frost";//����
					$log_info["to_userid"] = $borrow_userid;//����˭
					$log_info["remark"] =  "Ͷ��[{$borrow_url}]�ɹ����ս������";
					accountClass::AddLog($log_info);
					
					
					
					
					//�ھŲ�,��������
					$remind['nid'] = "tender_success";
					$remind['sent_user'] = "0";
					$remind['receive_user'] = $tender_userid;
					$remind['article_id'] = $borrow_nid;
					$remind['code'] = "borrow";
					$remind['title'] = "Ͷ��({$borrow_username})�ı�[<font color=red>{$borrow_name}</font>]������˳ɹ�";
					$remind['content'] = "����Ͷ�ʵı�[{$borrow_url}]��".date("Y-m-d",time())."�Ѿ����ͨ��";
					$remind['type'] = "system";
					remindClass::sendRemind($remind);
					
					
					//��ʮ��,Ͷ���ߵ����û�������
					$credit_log['user_id'] = $tender_userid;
					$credit_log['nid'] = "tender_success";
					$credit_log['code'] = "borrow";
					$credit_log['type'] = "tender";
					$credit_log['addtime'] = time();
					$credit_log['article_id'] =$tender_id;
					$credit_log['value'] = round($tender_account*0.01);
					$credit_log['remark'] = "Ͷ��[{$borrow_url}]�ɹ���������";
					$result = creditClass::ActionCreditLog($credit_log);
										
				}
				
				//����ͳ����Ϣ
				borrowClass::UpdateBorrowCount(array("user_id"=>$tender_userid,"tender_success_times"=>1,"tender_success_account"=>$tender_account,"tender_frost_account"=>-$tender_account,"tender_recover_account"=>$recover_account,"tender_recover_wait"=>$recover_account,"tender_capital_account"=>$tender_account,"tender_capital_wait"=>$tender_account,"tender_interest_account"=>$recover_lixi,"tender_interest_wait"=>$recover_lixi,"tender_recover_times"=>1,"tender_recover_times_wait"=>1));
						
			}
			$nowtime=time();
			$sql = "update `{borrow}` set repay_account_all='{$recover_account}',repay_account_interest='{$recover_lixi}',repay_account_capital='{$tender_account}',repay_account_wait='{$recover_account}',repay_account_interest_wait='{$recover_lixi}',repay_account_capital_wait='{$tender_account}',repay_last_time='{$repaytime}',repay_next_time='{$repaytime}',borrow_success_time='{$nowtime}',repay_each_time='{$repaytime}',repay_times='{$repaytime}'  where borrow_nid='{$borrow_nid}'";
			$mysql->db_query($sql);
			
			if($borrow_type==5){
				$_data["user_id"] = $borrow_userid;
				$_data["amount_type"] = "borrow";
				$_data["type"] = "borrow_success";
				$_data["oprate"] = "reduce";
				$_data["nid"] = "borrow_success_credit_".$borrow_userid."_".$borrow_nid.$value["id"];
				$_data["account"] = $borrow_account;
				$_data["remark"] = "����[{$borrow_url}]�������ͨ����������ö�ȼ���";
				borrowClass::AddAmountLog($_data);
			}elseif ($borrow_type==3){
				$_data["user_id"] = $borrow_userid;
				$_data["amount_type"] = "once_amount";
				$_data["type"] = "borrow_success";
				$_data["oprate"] = "reduce";
				$_data["nid"] = "borrow_success_credit_".$borrow_userid."_".$borrow_nid.$value["id"];
				$_data["account"] = $borrow_account;
				$_data["remark"] = "����[{$borrow_url}]�������ͨ�������һ���Զ�ȼ���";
				borrowClass::AddAmountLog($_data);
			}
			
			//��������
			$remind['nid'] = "borrow_review_yes";
			$remind['sent_user'] = "0";
			$remind['receive_user'] = $borrow_userid;
			$remind['code'] = "borrow";
			$remind['article_id'] = $borrow_nid;
			$remind['title'] = "�б�[{$borrow_name}]������˳ɹ�";
			$remind['content'] = "��Ľ���[{$borrow_url}]��".date("Y-m-d",time())."�Ѿ����ͨ��";
			$remind['type'] = "system";
			//remindClass::sendRemind($remind);
			
		 }elseif ($status == 4){
		 
			//��������Ͷ���ߵĽ�Ǯ��
			$tender_result = borrowClass::GetTenderList(array("borrow_nid"=>$borrow_nid,"limit"=>"all"));
			foreach ($tender_result as $key => $value){
				$tender_userid = $value['user_id'];
				$tender_account= $value['account'];
				$tender_id= $value['id'];
				$log_info["user_id"] = $tender_userid;//�����û�id
				$log_info["nid"] = "tender_false_".$tender_userid."_".$tender_id.$borrow_nid;//������
				$log_info["money"] = $tender_account;//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = 0;//֧��
				$log_info["balance_cash"] = $tender_account;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = -$tender_account;//������
				$log_info["await"] = 0;//���ս��
				$log_info["type"] = "tender_false";//����
				$log_info["to_userid"] = $borrow_userid;//����˭
				$log_info["remark"] =  "�б�[{$borrow_url}]ʧ�ܷ��ص�Ͷ���";
				accountClass::AddLog($log_info);
				
				
				//��������
				$remind['nid'] = "tender_false";
				$remind['sent_user'] = "0";
				$remind['code'] = "borrow";
				$remind['article_id'] = $borrow_nid;
				$remind['receive_user'] = $value['user_id'];
				$remind['title'] = "Ͷ�ʵı�[<font color=red>{$borrow_name}</font>]�������ʧ��";
				$remind['content'] = "����Ͷ�ʵı�[{$borrow_url}]��".date("Y-m-d",time())."���ʧ��,ʧ��ԭ��{$data['reverify_remark']}";
				$remind['type'] = "system";
				remindClass::sendRemind($remind);
			
				
				//��ʮ��,����Ͷ���˵�״̬
				$sql = "update `{borrow_tender}` set status=2 where id={$tender_id}";
				$mysql->db_query($sql);
				
				//����ͳ����Ϣ
				borrowClass::UpdateBorrowCount(array("user_id"=>$tender_userid,"tender_frost_account"=>-$tender_account,"tender_account"=>-$tender_account));
			}
			
			//��������
			$remind['nid'] = "borrow_review_no";
			$remind['sent_user'] = "0";
			$remind['code'] = "borrow";
			$remind['article_id'] = $borrow_nid;
			$remind['receive_user'] = $borrow_userid;
			$remind['title'] = "��������ı�[<font color=red>{$borrow_name}</font>]�������ʧ��";
			$remind['content'] = "��������ı�[{$borrow_url}]��".date("Y-m-d",time())."���ʧ��,ʧ��ԭ��{$data['repayment_remark']}";
			$remind['type'] = "system";
			//remindClass::sendRemind($remind);
		}
		if ($borrow_result['award_status']!=0){
			if ($status == 3 || $borrow_result['award_false']==1){
				$tender_result = borrowClass::GetTenderList(array("borrow_nid"=>$borrow_nid,"limit"=>"all"));
				foreach ($tender_result as $key => $value){
					//Ͷ�꽱���۳������ӡ�
					if ($borrow_result['award_status']==1){
						$money = round(($value['account']/$borrow_account)*$borrow_result['award_account'],2);
					}elseif ($borrow_result['award_status']==2){
						$money = round((($borrow_result['award_scale']/100)*$value['account']),2);
					}
					$tender_id = $value['id'];
					$tender_userid = $value['user_id'];
					$log_info["user_id"] = $tender_userid;//�����û�id
					$log_info["nid"] = "tender_award_add_".$tender_userid."_".$tender_id.$borrow_nid;//������
					$log_info["money"] = $money;//�������
					$log_info["income"] = $money;//����
					$log_info["expend"] = 0;//֧��
					$log_info["balance_cash"] = $money;//�����ֽ��
					$log_info["balance_frost"] = 0;//�������ֽ��
					$log_info["frost"] = 0;//������
					$log_info["await"] = 0;//���ս��
					$log_info["type"] = "tender_award_add";//����
					$log_info["to_userid"] = $borrow_userid;//����˭
					$log_info["remark"] =  "���[{$borrow_url}]�Ľ���";
					accountClass::AddLog($log_info);
				
					$log_info["user_id"] = $borrow_userid;//�����û�id
					$log_info["nid"] = "borrow_award_lower_".$borrow_userid."_".$tender_id.$borrow_nid;//������
					$log_info["money"] = $money;//�������
					$log_info["income"] = 0;//����
					$log_info["expend"] = -$money;//֧��
					$log_info["balance_cash"] = -$money;//�����ֽ��
					$log_info["balance_frost"] = 0;//�������ֽ��
					$log_info["frost"] = 0;//������
					$log_info["await"] = 0;//���ս��
					$log_info["type"] = "borrow_award_lower";//����
					$log_info["to_userid"] = $tender_userid;//����˭
					$log_info["remark"] =  "�۳����[{$borrow_url}]�Ľ���";
					accountClass::AddLog($log_info);
				}
			}
		}
		return $borrow_nid;
	}
}
?>