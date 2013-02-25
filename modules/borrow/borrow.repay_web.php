<?
/******************************
 * $File: borrow.repay_web.php
 * $Description: ��վ����渶
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("borrow.loan.php");
require_once("borrow.fee.php");
require_once("borrow.model.php");
require_once("borrow.calculates.php");

class borrowRepayWebClass
{
    //��ͨ�����
    function RepayWebInfo($data){
        global $mysql,$_G;
       
        if ($data['repay_id']==""){
            return "borrow_repay_id_error";
        }
        //��ȡ�����������+
        $sql = "select * from `{borrow_repay}` where  id='{$data['repay_id']}'";
        $repay_result = $mysql->db_fetch_array($sql);
        if ($repay_result==false){
            return "borrow_repay_error";
        }
        $data["user_id"] = $repay_result["user_id"];
        //��ȡ��صĽ��˵��+
       	$sql = "select p1.*,p2.username  from `{borrow}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.borrow_nid='{$repay_result['borrow_nid']}' and p1.user_id='{$data['user_id']}'";
        $borrow_result = $mysql->db_fetch_array($sql);
        if ($borrow_result==false){
            return "borrow_repay_borrow_error";
        }
        $repay_result['borrow_url'] = "<a href={$_G['web_domain']}/invest/a{$borrow_result['borrow_nid']}.html target=_blank style=color:blue>{$borrow_result['name']}</a>";//�����ַ
        $repay_result['borrow_name'] = $borrow_result["name"];
        $repay_result['borrow_username'] = $borrow_result["username"];
        $repay_result['borrow_type'] = $borrow_result["borrow_type"];
        $repay_result['borrow_style'] = $borrow_result["borrow_style"];
        $repay_result['borrow_period'] = $borrow_result["borrow_period"];
        $repay_result['borrow_frost_account'] = $borrow_result["borrow_frost_account"];
        $repay_result['amount_type'] = $borrow_result["amount_type"];
		if ($repay_result['repay_status']==1){
		  return "borrow_repay_yes";
		}elseif ($repay_result['status']!=1){
		   return "borrow_repay_status_error";
        }elseif ($repay_result['repay_web']==1){
		   return "borrow_repay_webstatus_error";
		}else{
		    $repay_account = $repay_result["repay_account"];//�����ܶ�
		    $repay_period = $repay_result["repay_period"];
            
            /*
    		//�ж���һ���Ƿ��ѻ���+
    		if ($repay_period!=1){
    			$_repay_period = $repay_period-1;
    			$sql = "select repay_status from `{borrow_repay}` where `repay_period`=$_repay_period and borrow_nid={$borrow_result['borrow_nid']}";
    			$result = $mysql->db_fetch_array($sql);
    			if ($result!=false && $result['repay_status']!=1){
    				return "borrow_repay_up_notrepay";
    			}
    		}
            */
              //�ж�����ǰ��������������+
            if ($repay_result["repay_days"]==""){
                $repay_result["repay_days"] =  borrowClass::GetDays(array("repay_time"=>$repay_result["repay_time"]));
            }
            
            //�Ƿ����ڻ���
            if ($repay_result["repay_days"]<=0){
                return "borrow_repay_web_error";
            }
            
            if ( $repay_result["repay_account_all"]<=0){
                 //�۳�����
                //�ж��Ƿ���vip
                $vip_status =0;
                $vip_result=usersClass::GetUsersVip(array("user_id"=>$repay_result["user_id"]));
                if($vip_result==true){
                     $vip_status = $vip_result['status'];
                }
                $repay_result['vip_status'] = $vip_status;
                $credit_result = borrowClass::GetBorrowCredit(array("user_id"=>$repay_result["user_id"]));
                $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
                $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
                $_fee["borrow_type"] = $repay_result["borrow_type"];//�������
                $_fee["borrow_style"] = $repay_result["borrow_style"];//���ʽ
                if ($late_days>0){
                    $_fee["type"] = "borrow_repay_late";//����
                }elseif ($late_days==0){
                    $_fee["type"] = "borrow_repay";//����
                }
                $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
                $_fee["capital"] = $repay_result["repay_capital"];//���ڽ���߻���Ͷ����
                $_fee["interest"] = $repay_result["repay_interest"];//���ڽ���߻���Ͷ����
                $result = borrowFeeClass::GetFeeValue($_fee);
                $_fee_account = 0;
                if ($result!=false){
                    foreach ($result as $key => $value){
                        $_fee_account += $value["account"];
                    }
                }
                //Ӧ�û�����ܶ
                $repay_result["repay_account_fee"]  = $_fee_account;
                $repay_result["repay_account_all"]  = $repay_result["repay_account"] + $_fee_account;
            }else{
                $repay_result["repay_account_fee"] = $repay_result["repay_account_all"]- $repay_result["repay_account"];
            }
            
            //��һ������ص��ж�
            if ($data['step']==0){
                //�ж��Ƿ�����������
                 
                
                 //��������Ϣд�������б���ȥ��+
                if ($repay_result["repay_web_time"]==""){
                    $sql = "update `{borrow_repay}` set repay_web_time='".time()."' where id='{$repay_result['id']}'";
                    $mysql->db_query($sql);
                }
                
                $sql = "update `{borrow_repay}` set repay_web_step=1 where id='{$repay_result['id']}'";
                $mysql->db_query($sql);
                
        		//��������ʱ�Ĳ�����
                return array("result"=>1,"step"=>1,"key"=>"0","name"=>"���ڻ����У��벻Ҫ�ر������");	
            }else{
                $fun = "RepayWebStep".$data['step'];
                $repay_result["key"] = $data['key'];
    		    $result = self::$fun($repay_result);
                return $result;
            }
		}
        //����������
    }
	
    //����Ͷ���˵���Ϣ
	function RepayWebStep1($repay_result){
		global $mysql,$_G;
        
        //�жϻ���״̬�Ƿ���ȷ
        if ($repay_result['repay_web_step']!=1){
            return "borrow_repay_step1_error";
        }
        if($repay_result=="") return "";
        //�ɹ����
        $sql = "select p1.*,p2.username,p3.borrow_type,p3.user_id as borrow_userid,p4.vip_late_scale,p4.all_late_scale,p6.change_status,p6.change_userid from `{borrow_recover}` as p1 
				left join `{users}` as p2 on p1.user_id=p2.user_id 
				left join `{borrow}` as p3 on p1.borrow_nid=p3.borrow_nid 
				left join `{borrow_type}` as p4 on p3.borrow_type=p4.nid 
				left join `{borrow_tender}` as p6 on p1.tender_id=p6.id 
				where p1.recover_period='{$repay_result["repay_period"]}' and  p1.borrow_nid='{$repay_result['borrow_nid']}' limit {$repay_result['key']},1";
		$recover_result = $mysql->db_fetch_array($sql);
		if ($recover_result==false){
            $sql = "update `{borrow_repay}` set repay_web_step=2 where id='{$repay_result['id']}'";
            $mysql->db_query($sql);
			return array("result"=>1,"step"=>2,"key"=>0,"name"=>"���ڽ�����󻹿�������벻Ҫ�ر������");
		}
        
		$recove_id = $recover_result['id'];
		if ($recover_result['change_status']==1){
			$recove_userid = $recover_result['change_userid'];
		}else{
			$recove_userid = $recover_result['user_id'];
		}
        $vip_status =0;
        $vip_result=usersClass::GetUsersVip(array("user_id"=>$recove_userid));
        if($vip_result==true){
             $vip_status = $vip_result['status'];
        }
		if ($vip_status==1){
			$recover_account = $recover_result['recover_account']*$recover_result['vip_late_scale']*0.01;
            $recover_capital =  $recover_result['recover_capital']*$recover_result['vip_late_scale']*0.01;
            $recover_interest =  $recover_result['recover_interest']*$recover_result['vip_late_scale']*0.01;
		}else{
			$recover_account = $recover_result['recover_capital']*$recover_result['all_late_scale']*0.01;
            $recover_capital =  $recover_result['recover_capital']*$recover_result['all_late_scale']*0.01;
		}
		$recover_period = $recover_result['recover_period'];
		$borrow_nid = $repay_result['borrow_nid'];
		$borrow_url = $repay_result['borrow_url'];
		$borrow_username = $repay_result['borrow_username'];
		$borrow_name = $repay_result['borrow_name'];
		//����ɹ����򽫻�����Ϣ���������ȥ
        $_recover_nid = $borrow_nid."_".$recove_userid."_".$recover_result['id']."_".$recover_period;
		$recover_nid = "tender_recover_yes_".$_recover_nid;//������
		//Ͷ���˵��ʽ𷵻�
		$log_info["user_id"] = $recove_userid;//�����û�id
		$log_info["nid"] = "tender_recover_web_".$_recover_nid;//������
        $log_info["borrow_nid"] = $borrow_nid;//����
        $log_info["account_web_status"] = 1;//
        $log_info["account_user_status"] = 1;
		$log_info["code"] = "borrow";//
		$log_info["code_type"] = "tender_recover_web";//
		$log_info["code_nid"] = $recove_id;//
		$log_info["money"] = $recover_account;//�������
		$log_info["income"] = $log_info["money"];//����
		$log_info["expend"] = 0;//֧��
		$log_info["balance_cash"] = $log_info["money"];//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = 0;//������
		$log_info["await"] = -$log_info["money"];//���ս��
		$log_info["repay"] = 0;//�������
		$log_info["type"] = "tender_recover_web";//����
		$log_info["to_userid"] = $recover_result["borrow_userid"];//����˭
	    $log_info["remark"] = "��վ��[{$borrow_url}]����ĵ�".($recover_period)."�ڵ渶����";
		accountClass::AddLog($log_info);
				
		
		$user_log["user_id"] = $recove_userid;
		$user_log["code"] = "tender";
		$user_log["type"] = "recover_success";
		$user_log["operating"] = "recover";
		$user_log["article_id"] = $recove_userid;
		$user_log["result"] = 1;
		$user_log["content"] = "�յ�����[{$borrow_url}]�Ļ���";
		usersClass::AddUsersLog($user_log);	
	
    	//�۳�����
        //�ж��Ƿ���vip
        $credit_result = borrowClass::GetBorrowCredit(array("user_id"=>$recover_userid));
        $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
        $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
        $_fee["borrow_type"] = $repay_result["borrow_type"];//�������
        $_fee["borrow_style"] = $repay_result["borrow_style"];//���ʽ
        $_fee["type"] = "borrow_repay";//���ڽ���߻���Ͷ����
        $_fee["user_type"] = "tender";//���ڽ���߻���Ͷ����
        $_fee["capital"] = $recover_result["recover_capital"];//���ڽ���߻���Ͷ����
        $_fee["interest"] = $recover_result["recover_interest"];//���ڽ���߻���Ͷ����
        $result = borrowFeeClass::GetFeeValue($_fee);
        $recover_fee = 0;
        if ($result != false){
            foreach ($result as $key => $value){
				
				//����vip��Ա���۳���Ϣ�����  wdf 20130115
				if($vip_status==0)continue;
				
                $recover_fee += $value["account"];
                $log_info["user_id"] = $recover_result["user_id"];//�����û�id
				$log_info["nid"] = "tender_recover_fee_".$value["nid"]."_".$_recover_nid;//������
				$log_info["borrow_nid"] = $recover_result['borrow_nid'];//����
                $log_info["account_web_status"] = 1;//
                $log_info["account_user_status"] = 1;
    			$log_info["code"] = "borrow";//
    			$log_info["code_type"] = "tender_recover_fee_".$value["nid"];//
    			$log_info["code_nid"] = $recover_result["id"];//
    			$log_info["money"] = $value['account'];//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = $log_info["money"];//֧��
				$log_info["balance_cash"] = -$log_info["money"];//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["repay"] = 0;//�������
				$log_info["type"] = "tender_recover_fee_".$value["nid"];//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] =  "��վ�ԡ�[{$repay_result["borrow_url"]}]���渶����۳�{$log_info["money"]}Ԫ{$value['name']}";
				accountClass::AddLog($log_info);
            }
        }
		
		//Ͷ�����յ�����վ���� 
		$remind['nid'] = "recover_web";				
		$remind['receive_userid'] = $recove_userid;
        $remind['remind_nid'] =  "recover_web_".$recover_result["borrow_nid"]."_".$recove_userid."_".$recover_result["id"];
		$remind['code'] = "invest";
		$remind['article_id'] = $recove_userid;
		$remind['title'] = "��վ������Ͷ�ʵĽ���[{$borrow_url}]�Ѿ��渶���";
		$remind['content'] = "��վ��".date("Y-m-d",time())."������Ͷ�ʵĽ���[{$borrow_url}]�Ѿ��ɹ��渶,�����".$recover_result['recover_account'];
        remindClass::sendRemind($remind);
      
        
            	
		 //���»��յ���Ϣ
         if ($vip_status==1){
            $sql = "update  `{borrow_recover}` set recover_type='web',recover_fee='{$recover_fee}',recover_yestime='".time()."',recover_account_yes = {$recover_account} ,recover_capital_yes = {$recover_capital} ,recover_interest_yes = {$recover_interest},status=1,recover_status=1,recover_web=1 where id = '{$recover_result['id']}'";
         }else{
            $sql = "update  `{borrow_recover}` set recover_type='web',recover_fee='{$recover_fee}',recover_yestime='".time()."',recover_account_yes = {$recover_account} ,recover_capital_yes = {$recover_capital} ,recover_interest_yes = 0,status=1,recover_status=1,recover_web=1  where id = '{$recover_result['id']}'";
         }
		$mysql->db_query($sql);
        
        
		$sql = "select count(1) as recover_times,sum(recover_account_yes) as recover_account_yes_num,sum(recover_interest_yes) as recover_interest_yes_num,sum(recover_capital_yes) as recover_capital_yes_num  from `{borrow_recover}` where tender_id='{$recover_result['tender_id']}' and recover_status=1";
		$result = $mysql->db_fetch_array($sql);
		$recover_times = $result['recover_times'];
       	$sql = "update  `{borrow_tender}` set recover_times={$recover_times},recover_account_yes= {$result['recover_account_yes_num']},recover_account_capital_yes =  {$result['recover_capital_yes_num']} ,recover_account_interest_yes = {$result['recover_interest_yes_num']},recover_account_wait= recover_account_all - recover_account_yes,recover_account_capital_wait = account - recover_account_capital_yes  ,recover_account_interest_wait = recover_account_interest -  recover_account_interest_yes  where id = '{$recover_result['tender_id']}'";
		$mysql->db_query($sql);
        	
		borrowCountClass::UpdateBorrowCount(array("user_id"=>$recove_userid,"borrow_nid"=>"{$repay_result['borrow_nid']}","nid"=>$recover_nid,"tender_recover_times_yes"=>1,"tender_recover_times_wait"=>-1,"tender_recover_yes"=>$recover_result['recover_account'],"tender_recover_wait"=>-$recover_result['recover_account'],"tender_capital_yes"=>$recover_result['recover_capital'],"tender_capital_wait"=>-$recover_result['recover_capital'],"tender_interest_yes"=>$recover_result['recover_interest'],"tender_interest_wait"=>-$recover_result['recover_interest']));
			
        
        return array("result"=>1,"step"=>1,"key"=>$repay_result['key']+1,"name"=>"����ΪͶ����[{$recover_result["username"]}]������صĽ��벻Ҫ�ر������");
   }
	
     //����Ͷ���˵���Ϣ
    function RepayWebStep2($repay_result){
       global $mysql;
       	//�жϻ���״̬�Ƿ���ȷ
        if ($repay_result['repay_web_step']!=2){
            return "borrow_repay_step2_error";
        }
		  
         $sql = "select * from `{borrow_recover}` where borrow_nid='{$repay_result["borrow_nid"]}' and recover_status=0 order by recover_period asc";
        $_result = $mysql->db_fetch_array($sql);
        if ($_result!=false){
            $recover_full_status=0;
        }else{
            $recover_full_status=1;
        }
        
        
        //����Ͷ�ʵ��˵�״̬�Ƿ��Ѿ�����
        $sql = "update `{borrow_tender}` set recover_full_status='{$recover_full_status}' where borrow_nid='{$repay_result["borrow_nid"]}'";
        $mysql->db_query($sql);  
        
        $sql = "select sum(recover_account_yes) as num from `{borrow_recover}` where borrow_nid='{$repay_result["borrow_nid"]}' and recover_period='{$repay_result['repay_period']}'";
        $result = $mysql->db_fetch_array($sql);
        $repay_web_account = $result["num"];
        
        $sql = "update `{borrow_repay}` set repay_web=1,repay_step=3,repay_web_account='{$repay_web_account}' where id='{$repay_result['id']}'";
        $mysql->db_query($sql); 
		
       
        return array("result"=>0,"step"=>0,"key"=>0,"name"=>"�渶�ɹ�");
    }
    
}    
    
    
?>
