<?
/******************************
 * $File: borrow.reverify.php
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
require_once(ROOT_PATH."modules/users/users.class.php");
require_once(ROOT_PATH."modules/users/users.friends.php");

$MsgInfo["borrow_status_error"] = "���״̬����ȷ�����ԭ����ͨ��������뻹��ͨ��";

class borrowReverifyClass
{
    
    
    function ReverifyInfo($data){
        global $mysql,$_G;
        if (IsExiest($data["borrow_nid"])=="") return "borrow_nid_empty";
		//��ȡ����������Ϣ
	    //$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$data['borrow_nid']}'";
	//	$borrow_result = $mysql->db_fetch_array($sql);
        $borrow_result = borrowClass::GetView(array("borrow_nid"=>$data["borrow_nid"]));
		$borrow_result['borrow_url'] = "<a href={$_G['web_domain']}/invest/a{$borrow_result['borrow_nid']}.html target=_blank >{$borrow_result['name']}</a>";//�����ַ
        if ($borrow_result['borrow_full_status']==1){
		  return "borrow_fullcheck_yes";
		}elseif ($borrow_result['borrow_part_status']!=1 && $borrow_result['borrow_account_yes']!=$borrow_result['account']  && $borrow_result["type_part_status"]!=1){
			return "borrow_not_full";
        }elseif (($borrow_result['borrow_part_status']==1 || $borrow_result["type_part_status"]==1) && $borrow_result['borrow_account_yes']==0){
			return "borrow_part_not_account";
            
		}else{
		      //���ֽ��
		    if ($borrow_result["type_part_status"]==1){
		       $borrow_result["account"] = $borrow_result["borrow_account_yes"];
		    }
            //�ж��Ƿ���vip
            $borrow_result['vip_status'] =0;
		    $vip_result = usersvipClass::GetUsersVip(array("user_id"=>$borrow_result['user_id']));
            $borrow_result['vip_status'] = $vip_result["status"];
            
            //��һ������ص��ж�
            if ($data['step']==0){
                //�ж��Ƿ�����������
                if ($borrow_result['reverify_status']>0 && $borrow_result['reverify_status']!=$data['status']){
                    return "borrow_status_error";
                }
                echo "���ڸ����У��벻Ҫ�ر������";
        		//��������ʱ�Ĳ�����
        		$sql = " update `{borrow}` set reverify_userid='{$data['reverify_userid']}',reverify_remark='{$data['remark']}',reverify_contents='{$data['contents']}',reverify_time='".time()."',reverify_status='{$data['status']}' where borrow_nid='{$data['borrow_nid']}'";
                $mysql ->db_query($sql);
                
        		//������˼�¼
                $_verify['user_id'] = $_G['user_id'];
                $_verify['status'] = $data['status'];
                $_verify['borrow_nid'] = $data['borrow_nid'];
                $_verify['type'] = "reverify";
                $_verify['remark'] = $data['remark'];
                $_verify['contents'] = $data['contents'];
                borrowLoanClass::AddVerify($_verify);
                return array("result"=>1,"step"=>1,"key"=>"");	
            }else{
                $fun = "ReverifyStep".$data['step'];
                $borrow_result["key"] = $data['key'];
    		    $result = self::$fun($borrow_result);
                return $result;
            }
		}
        //����������
    }
	
	
	//��һ������������˵���Ϣ
	function ReverifyStep1($borrow_result){
		global $mysql,$_G;
        if($borrow_result=="") return "";
        $status = $borrow_result['reverify_status'];
        //�ɹ����
		if ($status == 3){
			//����ɹ����򽫻�����Ϣ���������ȥ
            echo "����Ϊ����˲�����صĽ��벻Ҫ�ر������";
			$_equal["account"] = $borrow_result["account"];
			$_equal["period"] = $borrow_result["borrow_period"];
			$_equal["apr"] = $borrow_result["borrow_apr"];
			$_equal["style"] = $borrow_result["borrow_style"];
			$_equal["borrow_type"] = $borrow_result["borrow_type"];
			$equal_result = borrowCalculateClass::GetType($_equal);
			foreach ($equal_result as $key => $value){
			     $key = $key+1;
				//��ֹ�ظ���ӻ�����Ϣ
				$sql = "select 1 from `{borrow_repay}` where user_id='{$borrow_result["user_id"]}' and repay_period='{$key}' and borrow_nid='{$borrow_result["borrow_nid"]}'";
				$result = $mysql->db_fetch_array($sql);
				if ($result==false){
					$sql = "insert into `{borrow_repay}` set `addtime` = '".time()."',";
					$sql .= "`addip` = '".ip_address()."',repay_type='wait',user_id='{$borrow_result["user_id"]}',status=1,`borrow_nid`='{$borrow_result["borrow_nid"]}',`repay_period`='{$key}',";
					$sql .= "`repay_time`='{$value['repay_time']}',`repay_account`='{$value['account_all']}',";
					$sql .= "`repay_interest`='{$value['account_interest']}',`repay_capital`='{$value['account_capital']}'";
					$mysql ->db_query($sql);
				}else{
					$sql = "update `{borrow_repay}` set `addtime` = '".time()."',";
					$sql .= "`addip` = '".ip_address()."',user_id='{$borrow_result["user_id"]}',status=1,`borrow_nid`='{$borrow_result["borrow_nid"]}',`repay_period`='{$key}',";
					$sql .= "`repay_time`='{$value['repay_time']}',`repay_account`='{$value['account_all']}',";
					$sql .= "`repay_interest`='{$value['account_interest']}',`repay_capital`='{$value['account_capital']}'";
					$sql .= " where user_id='{$borrow_result["user_id"]}' and repay_period='{$key}' and borrow_nid='{$borrow_result["borrow_nid"]}'";
					$mysql ->db_query($sql);
				}
			}
            //�������Ϣ
            $repay_times = count($equal_result);
			$_equal["type"] = "all";
			$equal_result = borrowCalculateClass::GetType($_equal);
			$repay_all = $equal_result['account_total'];
            
            //������ܽ�����ӡ�
			$log_info["user_id"] = $borrow_result["user_id"];//�����û�id
			$log_info["nid"] = "borrow_success_".$borrow_result["borrow_nid"]."_".$borrow_result["user_id"];//������
            $log_info["borrow_nid"] = $borrow_result['borrow_nid'];//����
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 1;//
			$log_info["code"] = "borrow";//
			$log_info["code_type"] = "success";//
			$log_info["code_nid"] = $borrow_result['borrow_nid'];//
			$log_info["money"] = $borrow_result["account"];//�������
			$log_info["income"] = $log_info["money"];//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $log_info["money"];//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
            $log_info["repay"] = $repay_all;//�������
			$log_info["type"] = "borrow_success";//����
			$log_info["to_userid"] = 0;//
			$log_info["remark"] =  "ͨ��[{$borrow_result["borrow_url"]}]�赽�Ŀ�";
			accountClass::AddLog($log_info);
            
            //�����
			$sql = "select frost_scale,frost_scale_vip from `{borrow_type}` where nid='{$borrow_result["borrow_type"]}'";
            $borrow_type_result = $mysql->db_fetch_array($sql);
            $frost_account = 0;
            if ($borrow_result["vip_status"]==1){
             $frost_account = round(($borrow_result["account"]*$borrow_type_result["frost_scale_vip"])/100,2);
            }else{
                $frost_account = round(($borrow_result["account"]*$borrow_type_result["frost_scale"])/100,2);
            }
			if ($frost_account>0){
				$log_info["user_id"] = $borrow_result["user_id"];//�����û�id
				$log_info["nid"] = "borrow_success_frost_".$borrow_result["borrow_nid"]."_".$borrow_result["user_id"];//������
				$log_info["borrow_nid"] = $borrow_result['borrow_nid'];//����
                $log_info["account_web_status"] = 0;//
                $log_info["account_user_status"] = 0;//
    			$log_info["code"] = "borrow";//
    			$log_info["code_type"] = "success_frost";//
    			$log_info["code_nid"] = $borrow_result['borrow_nid'];//
    			$log_info["money"] = $frost_account;//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = 0;//֧��
				$log_info["balance_cash"] = -$log_info["money"];//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] =$log_info["money"];//������
				$log_info["await"] = 0;//���ս��
				$log_info["repay"] = 0;//�������
				$log_info["type"] = "borrow_success_frost";//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] =  "�������[{$borrow_result["borrow_url"]}]{$frost_account}Ԫ�ı�֤��";
				accountClass::AddLog($log_info);
				$sql = "update `{borrow}` set borrow_frost_account='{$frost_account}' where borrow_nid='{$borrow_result["borrow_nid"]}'";
				$mysql->db_query($sql);
			}
			
			if ($borrow_result['borrow_type']=="second"){
				$_equal["account"] = $borrow_result["account"];
				$_equal["period"] = $borrow_result["borrow_period"];
				$_equal["apr"] = $borrow_result["borrow_apr"];
				$_equal["style"] = $borrow_result["borrow_style"];
				$_equal["borrow_type"] = $borrow_result["borrow_type"];
				$equal_result = borrowCalculateClass::GetType($_equal);
				$money = $equal_result['interest_total'];
				
				$log_info["user_id"] = $borrow_result['user_id']; //�����û�id
				$log_info["nid"] = "borrow_miao_repay_" . $borrow_result['borrow_nid']; //������
                $log_info["account_web_status"] = 0;//
                $log_info["account_user_status"] = 0;//
				$log_info["money"] = $money; //�������
				$log_info["income"] = $money; //����
				$log_info["expend"] = 0; //֧��
				$log_info["balance_cash"] = $money; //�����ֽ��
				$log_info["balance_frost"] = 0; //�������ֽ��
				$log_info["frost"] = -$money; //������
				$log_info["await"] = 0; //���ս��
				$log_info["repay"] = 0; //���ս��
				$log_info["type"] = "borrow_miao_repay"; //����
				$log_info["to_userid"] = 0; //����˭
				$log_info["remark"] = "���ⶳ{$money}Ԫ";
				accountClass::AddLog($log_info);
            }
			
            //�۳�����
            $credit_result = borrowClass::GetBorrowCredit(array("user_id"=>$borrow_result["user_id"]));
            $_fee["vip_status"] = $borrow_result["vip_status"];//�ж��ǲ���vip
            $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
            $_fee["borrow_type"] = $borrow_result["borrow_type"];//�������
            $_fee["borrow_style"] = $borrow_result["borrow_style"];//���ʽ
            $_fee["period"] = $borrow_result["borrow_period"];//���ʽ
            $_fee["type"] = "borrow_success";//���ڽ���߻���Ͷ����
            $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
            $_fee["capital"] = $borrow_result["account"];//���ڽ���߻���Ͷ����
            $_fee["interest"] = $repay_all-$_fee["capital"];//���ڽ���߻���Ͷ����
            $result = borrowFeeClass::GetFeeValue($_fee);
            if ($result != false){
                foreach ($result as $key => $value){
                    $log_info["user_id"] = $borrow_result["user_id"];//�����û�id
    				$log_info["nid"] = "borrow_success_fee_".$value["nid"]."_".$borrow_result["borrow_nid"]."_".$borrow_result["user_id"];//������
    				$log_info["borrow_nid"] = $borrow_result['borrow_nid'];//����
                    $log_info["account_web_status"] = 1;//
                    $log_info["account_user_status"] = 1;//
        			$log_info["code"] = "borrow";//
        			$log_info["code_type"] = "borrow_fee_".$value["nid"];//
        			$log_info["code_nid"] = $borrow_result['borrow_nid'];//
        			$log_info["money"] = $value['account'];//�������
    				$log_info["income"] = 0;//����
    				$log_info["expend"] = $log_info["money"];//֧��
    				$log_info["balance_cash"] = -$log_info["money"];//�����ֽ��
    				$log_info["balance_frost"] = 0;//�������ֽ��
    				$log_info["frost"] = 0;//������
    				$log_info["await"] = 0;//���ս��
    				$log_info["repay"] = 0;//�������
    				$log_info["type"] = "borrow_fee_".$value["nid"];//����
    				$log_info["to_userid"] = 0;//����˭
    				$log_info["remark"] =  "���ɹ����۳�[{$borrow_result["borrow_url"]}]{$log_info["money"]}Ԫ{$value['name']}";
    				accountClass::AddLog($log_info);
                }
            }
            
            //����ͳ����Ϣ
			borrowCountClass::UpdateBorrowCount(array("user_id"=>$borrow_result['user_id'],"borrow_nid"=>"{$borrow_result['borrow_nid']}","nid"=>"borrow_success_".$borrow_result['borrow_nid'],"borrow_success_times"=>1,"borrow_repay_times"=>$repay_times,"borrow_repay_wait_times"=>$repay_times,"borrow_account"=>$borrow_result["account"],"borrow_repay_account"=>$repay_all,"borrow_repay_wait"=>$repay_all,"borrow_repay_interest"=>$equal_result['interest_total'],"borrow_repay_interest_wait"=>$equal_result['interest_total'],"borrow_repay_capital"=>$equal_result['capital_total'],"borrow_repay_capital_wait"=>$equal_result['capital_total']));
            
			//���귢���߽���
			$remind['nid'] = "borrow_full_success";
            $remind['remind_nid'] =  "borrow_full_success_".$borrow_result["borrow_nid"]."_".$borrow_result["user_id"];
			$remind['receive_userid'] = $borrow_result['user_id'];
			$remind['article_id'] = $borrow_result['borrow_nid'];
			$remind['code'] = "borrow";
			$remind['title'] = "����[{$borrow_result["name"]}]������˳ɹ�";
			$remind['content'] = "�㷢���Ľ���[{$borrow_result["borrow_url"]}]��".date("Y-m-d",time())."�������ͨ��";
			remindClass::sendRemind($remind);
        }else{
            if ($borrow_result["borrow_frost_second"]>0){
                $log_info["user_id"] = $borrow_result['user_id']; //�����û�id
                $log_info["nid"] = "borrow_miao_false_" . $borrow_result['borrow_nid']; //������
                $log_info["account_web_status"] = 0;//
                $log_info["account_user_status"] = 1;//
                $log_info["money"] = $borrow_result["borrow_frost_second"]; //�������
                $log_info["income"] = 0; //����
                $log_info["expend"] = 0; //֧��
                $log_info["balance_cash"] = $log_info["money"]; //�����ֽ��
                $log_info["balance_frost"] = 0; //�������ֽ��
                $log_info["frost"] = -$log_info["money"]; //������
                $log_info["await"] = 0; //���ս��
                $log_info["repay"] = 0; //���ս��
                $log_info["type"] = "borrow_miao_false"; //����
                $log_info["to_userid"] = 0; //����˭
                $log_info["remark"] = "������ʧ�ܣ��ⶳ{$log_info["money"]}Ԫ";
                accountClass::AddLog($log_info);
            }
            
            //��ȷ���
               //��ȶ���
        	$_amount["user_id"] = $borrow_result['user_id'];//�û�id
        	$_amount["amount_type"] = $borrow_result["amount_type"];//�������
        	$_amount["amount_style"] = "forever";
        	$_amount["type"] = "borrow_cancel";
        	$_amount["oprate"] = "return";
            $_amount["account"] = $borrow_result['amount_account'];
        	$_amount["nid"] = $_amount["type"]."_".$borrow_result['user_id']."_".$borrow_result['borrow_nid'];
        	$_amount["remark"] = "����[{$borrow_result["borrow_url"]}]����ʧ�ܣ�����{$borrow_result['account']}Ԫ���";
            borrowAmountClass::AddAmountLog($_amount);	
           
            //���귢���߽���
			$remind['nid'] = "borrow_full_false";
            $remind['remind_nid'] =  "borrow_full_false_".$borrow_result["borrow_nid"]."_".$borrow_result["user_id"];
			$remind['receive_userid'] = $borrow_result['user_id'];
			$remind['article_id'] = $borrow_result['borrow_nid'];
			$remind['code'] = "borrow";
			$remind['title'] = "����[{$borrow_result["name"]}]�������ʧ��";
			$remind['content'] = "�㷢���Ľ���[{$borrow_result["borrow_url"]}]��".date("Y-m-d",time())."�������ʧ�ܣ�ʧ��ԭ�򣺡�{$borrow_result["reverify_remark"]}��";
			remindClass::sendRemind($remind);
        }
        return array("result"=>1,"step"=>2,"key"=>0);
	}
	
    
    //����Ͷ���˵���Ϣ
	function ReverifyStep2($borrow_result){
		global $mysql,$_G;
        if($borrow_result=="") return "";
        $status = $borrow_result['reverify_status'];
        //�ɹ����
        $sql = "select * from `{borrow_tender}` as p1 where  p1.borrow_nid='{$borrow_result['borrow_nid']}' limit {$borrow_result['key']},1";
		$tender_result = $mysql->db_fetch_array($sql);
		if ($tender_result==false){
			return array("result"=>1,"step"=>3,"key"=>0);
		}
        
		$tender_userid = $tender_result['user_id'];
		$tender_account = $tender_result['account'];
		$tender_id = $tender_result['id'];
		$borrow_nid = $borrow_result["borrow_nid"];
		$borrow_userid = $borrow_result["user_id"];
		$borrow_url = $borrow_result["borrow_url"];
		$borrow_name = $borrow_result["name"];
		if ($status == 3){
			//����ɹ����򽫻�����Ϣ���������ȥ
            echo "����ΪͶ���˲�����صĽ��벻Ҫ�ر������";
           //���Ͷ�ʵ��տ��¼
			$_equal["account"] = $tender_result['account'];
			$_equal["period"] = $borrow_result["borrow_period"];
			$_equal["apr"] = $borrow_result["borrow_apr"];
			$_equal["style"] = $borrow_result["borrow_style"];
			$_equal["type"] = "";
			$_equal["borrow_type"] = $borrow_result["borrow_type"];
			$equal_result = borrowCalculateClass::GetType($_equal);
			foreach ($equal_result as $period_key => $value){
                $period_key= $period_key+1;
				$repay_month_account = $value['account_all'];
				//��ֹ�ظ���ӻ�����Ϣ
				$sql = "select 1 from `{borrow_recover}` where user_id='{$tender_userid}' and borrow_nid='{$borrow_nid}' and recover_period='{$period_key}' and tender_id='{$tender_id}'";
				$result = $mysql->db_fetch_array($sql);
				if ($result==false){
					$sql = "insert into `{borrow_recover}` set recover_type='wait',`addtime` = '".time()."',";
					$sql .= "`addip` = '".ip_address()."',user_id='{$tender_userid}',status=1,`borrow_nid`='{$borrow_nid}',`borrow_userid`='{$borrow_userid}',`tender_id`='{$tender_id}',`recover_period`='{$period_key}',";
					$sql .= "`recover_time`='{$value['repay_time']}',`recover_account`='{$value['account_all']}',";
					$sql .= "`recover_interest`='{$value['account_interest']}',`recover_capital`='{$value['account_capital']}'";
					$mysql ->db_query($sql);
				}else{
					$sql = "update `{borrow_recover}` set `addtime` = '".time()."',";
					$sql .= "`addip` = '".ip_address()."',user_id='{$tender_userid}',status=1,`borrow_nid`='{$borrow_nid}',`borrow_userid`='{$borrow_userid}',`tender_id`='{$tender_id}',`recover_period`='{$period_key}',";
					$sql .= "`recover_time`='{$value['repay_time']}',`recover_account`='{$value['account_all']}',";
					$sql .= "`recover_interest`='{$value['account_interest']}',`recover_capital`='{$value['account_capital']}'";
					$sql .= " where user_id='{$tender_userid}' and recover_period='{$period_key}' and borrow_nid='{$borrow_nid}' and tender_id='{$tender_id}'";
					$mysql ->db_query($sql);
				}
			}
			$tender_nid = "tender_success_".$borrow_nid."_".$tender_userid."_".$tender_id;		
			//������,�۳�Ͷ���˵��ʽ�
			$log_info["user_id"] = $tender_userid;//�����û�id
			$log_info["nid"] = $tender_nid;//������
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 1;//
            $log_info["borrow_nid"] = $borrow_nid;//����
			$log_info["code"] = "borrow";//
			$log_info["code_type"] = "tender_success";//
			$log_info["code_nid"] = $tender_id;//
			$log_info["money"] = $tender_account;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = $tender_account;//֧��
			$log_info["balance_cash"] = 0;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = -$tender_account;//������
			$log_info["await"] = 0;//���ս��
			$log_info["repay"] = 0;//�������
			$log_info["type"] = "tender_success";//����
			$log_info["to_userid"] = $borrow_userid;//����˭
			$log_info["remark"] = "Ͷ��[{$borrow_url}]�ɹ�Ͷ�ʽ��۳�";
			accountClass::AddLog($log_info);
				
				
			//���岽,����Ͷ�ʱ����Ϣ
			$_equal["type"] = "all";
			$_equal["borrow_type"] = $borrow_result["borrow_type"];
			$equal_result = borrowCalculateClass::GetType($_equal);
			$recover_all = $equal_result['account_total'];
			$recover_interest_all = $equal_result['interest_total'];
			$recover_capital_all = $equal_result['capital_total'];
			$recover_times = count($equal_result);
			//��Ӵ��յĽ��
			$log_info["user_id"] = $tender_userid;//�����û�id
			$log_info["nid"] = "tender_success_frost_".$borrow_nid."_".$tender_userid."_".$tender_id;//������
            $log_info["borrow_nid"] = $borrow_nid;//����
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 0;//
			$log_info["code"] = "borrow";//
			$log_info["code_type"] = "tender_success_frost";//
			$log_info["code_nid"] = $tender_id;//
			$log_info["money"] = $recover_all;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = 0;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = $recover_all;//���ս��
			$log_info["repay"] = 0;//�������
			$log_info["type"] = "tender_success_frost";//����
			$log_info["to_userid"] = $borrow_userid;//����˭
			$log_info["remark"] =  "Ͷ��[{$borrow_url}]�ɹ����ս������";
			accountClass::AddLog($log_info);
			
            
            //������Ϣ
            $money = 0;
            //Ͷ�꽱���۳������ӡ�
			if ($borrow_result['award_status']==1 && $borrow_result['award_account']>0){
				$money = round(($tender_account/$borrow_result["account"])*$borrow_result['award_account'],2);
			}elseif ($borrow_result['award_status']==2){
				$money = round((($borrow_result['award_scale']/100)*$tender_account),2);
			}
			if ($money>0){
    			$log_info["user_id"] = $tender_userid;//�����û�id
    			$log_info["nid"] = "tender_award_add_".$borrow_nid."_".$tender_userid."_".$tender_id;//������
                $log_info["account_web_status"] = 0;//
                $log_info["account_user_status"] = 1;//
                $log_info["borrow_nid"] = $borrow_nid;//����
    			$log_info["code"] = "borrow";//
    			$log_info["code_type"] = "tender_award_add";//
    			$log_info["code_nid"] = $tender_id;//
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
    			$log_info["nid"] = "borrow_award_lower_".$borrow_nid."_".$borrow_userid."_".$tender_id;//������
                $log_info["borrow_nid"] = $borrow_nid;//����
                $log_info["account_web_status"] = 0;//
                $log_info["account_user_status"] = 1;//
    			$log_info["code"] = "borrow";//
    			$log_info["code_type"] = "borrow_award_lower";//
    			$log_info["code_nid"] = $tender_id;//
    			$log_info["money"] = $money;//�������
    			$log_info["income"] = 0;//����
    			$log_info["expend"] = $money;//֧��
    			$log_info["balance_cash"] = -$money;//�����ֽ��
    			$log_info["balance_frost"] = 0;//�������ֽ��
    			$log_info["frost"] = 0;//������
    			$log_info["await"] = 0;//���ս��
    			$log_info["repay"] = 0;//�������
    			$log_info["type"] = "borrow_award_lower";//����
    			$log_info["to_userid"] = $tender_userid;//����˭
    			$log_info["remark"] =  "�۳����[{$borrow_url}]�Ľ���";
    			accountClass::AddLog($log_info);    
                                
                $sql = "update `{borrow_tender}` set tender_award_fee='{$money}' where id='{$tender_id}'";
                $mysql->db_query($sql);
            }        
                
			$borrow_username = $borrow_result['username'];
			
			//�ƹ㽱�� $tender_account $borrow_result["borrow_period"] $borrow_result["borrow_apr"]   $recover_interest_all $tender_userid
			
			$_result=usersFriendsClass::GetUsersInviteOne(array("user_id"=>$tender_userid));			
			$invite = isset($_G["system"]["con_invite_tender_award"])?$_G["system"]["con_invite_tender_award"]:2;
			$award_account = round($invite*$recover_interest_all/100,2);
			if ($_result['user_id']>0 ){
				$log_info["user_id"] = $_result['user_id'];//�����û�id
    			$log_info["nid"] = "invite_award_add_".$borrow_nid."_".$tender_userid."_".$tender_id;//������
                $log_info["account_web_status"] = 1;//
                $log_info["account_user_status"] = 1;//
                $log_info["borrow_nid"] = $borrow_nid;//����
    			$log_info["code"] = "tender";//
    			$log_info["code_type"] = "invite_tender_award";//
    			$log_info["code_nid"] = $tender_id;//
    			$log_info["money"] = $award_account;//�������
    			$log_info["income"] = $award_account;//����
    			$log_info["expend"] = 0;//֧��
    			$log_info["balance_cash"] = $award_account;//�����ֽ��
    			$log_info["balance_frost"] = 0;//�������ֽ��
    			$log_info["frost"] = 0;//������
    			$log_info["await"] = 0;//���ս��
    			$log_info["type"] = "invite_tender_award";//����
    			$log_info["to_userid"] = $_result['user_id'];//����˭
    			$log_info["remark"] =  "�û�Ͷ�ʽ��[{$borrow_url}]��õ��ƹ㽱��";
    			accountClass::AddLog($log_info);
				
				$_invite['user_id']=$_result['user_id'];
				$_invite['tender_userid']=$tender_userid;
				$_invite['tender_account']=$tender_account;
				$_invite['tender_period']=$borrow_result["borrow_period"];
				$_invite['tender_apr']=$borrow_result["borrow_apr"];
				$_invite['award']=$award_account;
				usersClass::AddManageAccount($_invite);	
			}
			
			
			//Ͷ�����û���
			$credit_log['user_id'] = $tender_userid;
			$credit_log['nid'] = "tender_success";
			$credit_log['code'] = "borrow";
			$credit_log['type'] = "�ɹ�Ͷ��{$tender_account}���õĻ���";
			$credit_log['addtime'] = time();
			$credit_log['article_id'] =$tender_id;
			$credit_log['value'] = round($tender_account/100);
			$result = creditClass::ActionCreditLog($credit_log);
				
			//�����û�������¼
			$user_log["user_id"] = $tender_userid;
			$user_log["code"] = "tender";
			$user_log["type"] = "tender_success";
			$user_log["operating"] = "tender";
			$user_log["article_id"] = $tender_userid;
			$user_log["result"] = 1;
			$user_log["content"] = "����[{$borrow_url}]ͨ���˸���,[<a href=/protocol/a{$data['borrow_nid']}.html target=_blank>����˴�</a>]�鿴Э����";
			usersClass::AddUsersLog($user_log);	
				
				
			$sql = "update `{borrow_tender}` set status=1,tender_status=1,recover_account_all='{$equal_result['account_total']}',recover_account_interest='{$equal_result['interest_total']}',recover_account_wait=recover_account_all-recover_account_yes,recover_account_interest_wait=recover_account_interest-recover_account_interest_yes,recover_account_capital_wait='{$equal_result['capital_total']}'  where id='{$tender_id}'";
			$mysql->db_query($sql);
			
			//����ͳ����Ϣ
			borrowCountClass::UpdateBorrowCount(array("user_id"=>$tender_userid,"borrow_nid"=>"{$borrow_result['borrow_nid']}","nid"=>$tender_nid,"tender_success_times"=>1,"tender_success_account"=>$tender_account,"tender_frost_account"=>-$tender_account,"tender_recover_account"=>$recover_all,"tender_recover_wait"=>$recover_all,"tender_capital_account"=>$recover_capital_all,"tender_capital_wait"=>$recover_capital_all,"tender_interest_account"=>$recover_interest_all,"tender_interest_wait"=>$recover_interest_all,"tender_recover_times"=>$recover_times,"tender_recover_times_wait"=>$recover_times));
				
            
			//Ͷ���߽���
			$remind['nid'] = "tender_success";
			$remind['remind_nid'] = $tender_nid;
			$remind['receive_userid'] = $tender_userid;
			$remind['article_id'] = $borrow_nid;
			$remind['code'] = "borrow";
			$remind['title'] = "Ͷ��({$borrow_username})[{$borrow_name}]������˳ɹ�";
			$remind['content'] = "����Ͷ�ʵı�[{$borrow_url}]��".date("Y-m-d",time())."�Ѿ����ͨ��";
			remindClass::sendRemind($remind);
				
        }elseif ($status == 4){
            	//����Ͷ�ʵ�״̬
			$sql = "update `{borrow_tender}` set status=2 where id={$tender_id}";
			$mysql->db_query($sql);
			
			//����Ͷ���ʽ�
			$log_info["user_id"] = $tender_userid;//�����û�id
			$log_info["nid"] = "tender_false_".$borrow_nid."_".$tender_userid."_".$tender_id;//������
            $log_info["borrow_nid"] = $borrow_nid;//����
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 0;//
			$log_info["code"] = "borrow";//
			$log_info["code_type"] = "tender_false";//
			$log_info["code_nid"] = $tender_id;//
			$log_info["money"] = $tender_account;//�������
			$log_info["income"] = $tender_account;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $tender_account;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = -$tender_account;//������
			$log_info["await"] = 0;//���ս��
			$log_info["repay"] = 0;//�������
			$log_info["type"] = "tender_false";//����
			$log_info["to_userid"] = $borrow_userid;//����˭
			$log_info["remark"] =  "Ͷ��[{$borrow_url}]ʧ�ܷ��ص�Ͷ���";
			accountClass::AddLog($log_info);
			
        
            //������Ϣ
            if($borrow_result["award_false"]==1){
                $money = 0;
                //Ͷ�꽱���۳������ӡ�
    			if ($borrow_result['award_status']==1 && $borrow_result['award_account']>0){
    				$money = round(($tender_account/$borrow_result["account"])*$borrow_result['award_account'],2);
    			}elseif ($borrow_result['award_status']==2){
    				$money = round((($borrow_result['award_scale']/100)*$tender_account),2);
    			}
    			if ($money>0){
        			$log_info["user_id"] = $tender_userid;//�����û�id
        			$log_info["nid"] = "tender_award_add_".$borrow_nid."_".$tender_userid."_".$tender_id;//������
                    $log_info["account_web_status"] = 0;//
                    $log_info["account_user_status"] = 1;//
                    $log_info["borrow_nid"] = $borrow_nid;//����
        			$log_info["code"] = "borrow";//
        			$log_info["code_type"] = "tender_award_add";//
        			$log_info["code_nid"] = $tender_id;//
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
        			$log_info["nid"] = "borrow_award_lower_".$borrow_nid."_".$borrow_userid."_".$tender_id;//������
                    $log_info["borrow_nid"] = $borrow_nid;//����
                    $log_info["account_web_status"] = 0;//
                    $log_info["account_user_status"] = 1;//
        			$log_info["code"] = "borrow";//
        			$log_info["code_type"] = "borrow_award_lower";//
        			$log_info["code_nid"] = $tender_id;//
        			$log_info["money"] = $money;//�������
        			$log_info["income"] = 0;//����
        			$log_info["expend"] = $money;//֧��
        			$log_info["balance_cash"] = -$money;//�����ֽ��
        			$log_info["balance_frost"] = 0;//�������ֽ��
        			$log_info["frost"] = 0;//������
        			$log_info["await"] = 0;//���ս��
        			$log_info["repay"] = 0;//�������
        			$log_info["type"] = "borrow_award_lower";//����
        			$log_info["to_userid"] = $tender_userid;//����˭
        			$log_info["remark"] =  "�۳����[{$borrow_url}]�Ľ���";
        			accountClass::AddLog($log_info);    
                                    
                    $sql = "update `{borrow_tender}` set tender_award_fee='{$money}' where id='{$tender_id}'";
                    $mysql->db_query($sql);
                }     
            }
            
			//Ͷ���߽��� 
			$remind['nid'] = "tender_false";
			$remind['remind_nid'] = "tender_false_".$borrow_nid."_".$tender_userid."_".$tender_id;	
			$remind['code'] = "borrow";
			$remind['article_id'] = $borrow_nid;
			$remind['receive_userid'] = $tender_userid;
			$remind['title'] = "Ͷ�ʵı�[{$borrow_result["name"]}]�������ʧ��";
			$remind['content'] = "����Ͷ�ʵı�[{$borrow_url}]��".date("Y-m-d",time())."���ʧ��,ʧ��ԭ��{$borrow_result['reverify_remark']}";
			remindClass::sendRemind($remind);
        }
        return array("result"=>1,"step"=>2,"key"=>$borrow_result['key']+1);
   }
   
    //����
    function ReverifyStep3($borrow_result){
		global $mysql,$_G;
        if($borrow_result=="") return "";
        $reverify_status = $borrow_result['reverify_status'];
		$borrow_nid = $borrow_result["borrow_nid"];
		$status = $borrow_result['status'];
		$borrow_userid = $borrow_result['user_id'];
		$borrow_account = $borrow_result['account'];
		$borrow_name = $borrow_result['name'];
		$vouch_status = $borrow_result['vouch_status'];
        if ($reverify_status==3){
    		//����������
    	   //��ʮһ�������½������Ϣ$nowtime = time();
    		$nowtime= time();
            if ($borrow_result["borrow_type"]=="day"){
                $endtime = get_times(array("num"=>$borrow_result["borrow_period"],"time"=>$nowtime,"type"=>"day"));
            }else{
    	       	$endtime = get_times(array("num"=>$borrow_result["borrow_period"],"time"=>$nowtime));
    		}
    		if ($borrow_result["borrow_style"]=="season"){
    			$_each_time = "ÿ�����º�".date("d",$nowtime)."��";
    			$nexttime = get_times(array("num"=>3,"time"=>$nowtime));
    		}else{
    			$_each_time = "ÿ��".date("d",$nowtime)."��";
    			$nexttime = get_times(array("num"=>1,"time"=>$nowtime));
    		}
    		$_equal["account"] = $borrow_result['account'];
    		$_equal["period"] = $borrow_result["borrow_period"];
    		$_equal["style"] = $borrow_result["borrow_style"];
    		$_equal["apr"] = $borrow_result["borrow_apr"];
			$_equal["borrow_type"] = $borrow_result["borrow_type"];
    		$_equal["type"] = "all";
    		$equal_result = borrowCalculateClass::GetType($_equal);;
    		$sql = "update `{borrow}` set borrow_full_status=1,status='{$borrow_result['reverify_status']}',repay_account_all='{$equal_result['account_total']}',repay_account_interest='{$equal_result['interest_total']}',repay_account_capital='{$equal_result['capital_total']}',repay_account_wait='{$equal_result['account_total']}',repay_account_interest_wait='{$equal_result['interest_total']}',repay_account_capital_wait='{$equal_result['capital_total']}',repay_last_time='{$endtime}',repay_next_time='{$nexttime}',borrow_success_time='{$nowtime}',repay_each_time='{$_each_time}',repay_times='{$repay_times}'  where borrow_nid='{$borrow_nid}'";
          
    		$mysql->db_query($sql);
        }else{
            $sql = "update `{borrow}` set borrow_full_status=1,status='{$borrow_result['reverify_status']}'  where borrow_nid='{$borrow_nid}'";
    		$mysql->db_query($sql);
        }
        //����Զ�����
        if ($borrow_result["borrow_type"]=="second" && $reverify_status==3){
            $sql = "select id,user_id from `{borrow_repay}` where borrow_nid='{$borrow_result['borrow_nid']}'";
            $result = $mysql->db_fetch_array($sql);
            return array("result"=>2,"repay_id"=>$result['id'],"user_id"=>$result['user_id'],"step"=>"0","key"=>"0");
        }
		return array("result"=>0,"step"=>"","key"=>"");
	}
}
?>
