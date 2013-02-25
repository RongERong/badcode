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
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
require_once(ROOT_PATH."modules/account/account.class.php");
require_once("borrow.calculates.php");

$MsgInfo["borrow_loan_userid_empty"] = "���Ĳ������󡣴����롾borrow_loan_userid_empty��";
$MsgInfo["borrow_loan_account_error"] = "���Ĳ������󡣴����롾borrow_loan_account_error��";
$MsgInfo["borrow_loan_borrowtype_error"] = "���Ĳ������󡣴����롾borrow_loan_borrowtype_error��";
$MsgInfo["borrow_loan_nid_error"] = "���Ĳ������󡣴����롾borrow_loan_nid_error��";
$MsgInfo["borrow_loan_name_empty"] = "������Ʋ���Ϊ��";
$MsgInfo["borrow_loan_account_empty"] = "������Ϊ��";
$MsgInfo["borrow_loan_contents_empty"] = "������ݲ���Ϊ��";
$MsgInfo["borrow_loan_account_over_use"] = "�����Ľ����ڿ��õĶ��";
$MsgInfo["borrow_loan_account_over_max"] = "�����Ľ����ڴ˱�������������";
$MsgInfo["borrow_loan_account_over_min"] = "�����Ľ��С�ڴ˱����������С���";
$MsgInfo["borrow_loan_apr_empty"] = "�����ʲ���Ϊ��";
$MsgInfo["borrow_loan_apr_over_max"] = "�����������ʴ��ڴ˱�����������������";
$MsgInfo["borrow_loan_apr_over_min"] = "������������С�ڴ˱����������С������";
$MsgInfo["borrow_loan_award_scale_empty"] = "������������Ϊ��";
$MsgInfo["borrow_loan_award_scale_over_max"] = "�����Ľ����������ڴ˱�����������������";
$MsgInfo["borrow_loan_award_scale_over_min"] = "�����Ľ�������С�ڴ˱����������С��������";
$MsgInfo["borrow_loan_award_account_empty"] = "��������Ϊ��";
$MsgInfo["borrow_loan_award_account_over_max"] = "�����Ľ��������ڴ˱����������������";
$MsgInfo["borrow_loan_award_account_over_min"] = "�����Ľ������С�ڴ˱����������С�������";
$MsgInfo["borrow_loan_account_multiple_error"] = "����д�Ľ���ȷ������������һ���ı���";
$MsgInfo["borrow_loan_valicode_error"] = "��֤�벻��ȷ";
$MsgInfo["borrow_loan_cancel_tender_error"] = "��Ͷ�ʲ��ܽ��г���";
$MsgInfo["borrow_late_days_error"] = "���Ĳ������󡣡�error:borrow_late_days_error��";
$MsgInfo["borrow_repay_empty"] = "���Ĳ������󡣡�error:borrow_repay_empty��";

require_once("borrow.class.php");
require_once("borrow.amount.php");
require_once("borrow.fee.php");
require_once("borrow.tender.php");
require_once(DEAYOU_PATH."modules/remind/remind.class.php");

class borrowLoanClass
{
	
    function ActionCheck($data = array()){
        global $mysql,$_G;
        
        
        
        //�ж��û��Ƿ����
        if ($data['user_id']=="") {
            return "borrow_loan_userid_empty";
        } 
		//�жϱ����Ƿ����
        if ($data['name']=="") {
            return "borrow_loan_name_empty";
        }
        //�жϽ������
        if ($data['borrow_apr']=="") {
            return "borrow_loan_apr_empty";
        }
        
		//�жϽ���Ƿ����
        if ($data['account']=="") {
            return "borrow_loan_account_empty";
        } 
        //�жϽ���Ƿ���ȷ
        if ($data['account']<0) {
            return "borrow_loan_account_error";
        } 
        
		//�жϽ�������Ƿ�Ϊ��
        if ($data['borrow_contents']=="") {
            return "borrow_loan_contents_empty";
        }
        
        //�ж������Ƿ���ȷ
        $sql = "select * from `{borrow_type}` where nid='{$data['borrow_type']}'";
        $type_result = $mysql->db_fetch_array($sql);
        if ($type_result==false){
            return "borrow_loan_borrowtype_error";
        }
       //�ж��Ƿ������ý����,��겻��Ҫ���
        $data['amount_account'] = 0;
        if ($type_result['amount_type']!="second"){
    		$amount_result = borrowAmountClass::GetAmountUsers(array("user_id"=>$data["user_id"],"type"=>$type_result['amount_type']));
    		if ($data['account']>$amount_result['account_use'] ){			
    			return "borrow_loan_account_over_use";
    		}	
            $data['amount_account']	= $data['account'];
        }
       
	
		
		//�ж��Ƿ�������Ľ���
		$max = $type_result['amount_end'];//���Ľ����
		if($data['account'] > $max){
			return  "borrow_loan_account_over_max";
		}
        //�ж��Ƿ������С�Ľ���
		$min = $type_result['amount_first'];//���Ľ����
		if($data['account'] < $min){
			return  "borrow_loan_account_over_min";
		}
        
        //�����ж�
		$max = $type_result['apr_end'];
		if($data['borrow_apr'] > $max){
			return  "borrow_loan_apr_over_max";
		}
		$min = $type_result['apr_first'];
		if($data['borrow_apr'] < $min){
			return  "borrow_loan_apr_over_min";
		}
        
        //�����ж�
        if ($data['award_status']==2){
            $max = intval($type_result['award_scale_end']);
        	if($data['award_scale']==""){
        	   return  "borrow_loan_award_scale_empty";
        	}
    		if(intval($data['award_scale']) > $max){
    			return  "borrow_loan_award_scale_over_max";
    		}
    		$min = intval($type_result['award_scale_first']);
            
    		if(intval($data['award_scale']) < $min){
    			return  "borrow_loan_award_scale_over_min";
    		}
        }elseif ($data['award_status']==1){
            $max = intval($type_result['award_account_end']);
        	if($data['award_account']==""){
        	   return  "borrow_loan_award_account_empty";
        	}
    		if(intval($data['award_account']) > $max){
    			return  "borrow_loan_award_account_over_max";
    		}
    		$min = intval($type_result['award_account_first']);
    		if(intval($data['award_account']) < $min){
    			return  "borrow_loan_award_account_over_min";
    		}
        }
        
        //�������ת��
        if ($data["borrow_type"]=="roam"){
            if ($data["roam_data"]["account_min"]<0){
                return "borrow_loan_account_min_error";
            }else if ($data["roam_data"]["account_min"]>$data["account"]){
                return "borrow_loan_account_min_account_max_error";
            }else if (!is_int($data["account"]/$data["roam_data"]["account_min"])){
                return "borrow_loan_account_min_account_error";
            }
            if ($data["roam_data"]["voucher"]==""){
                return "borrow_loan_roam_voucher_empty";
            }
            if ($data["roam_data"]["vouch_style"]==""){
                return "borrow_loan_roam_vouch_style_empty";
            }
            if ($data["roam_data"]["borrow_account_use"]==""){
                return "borrow_loan_roam_borrow_account_use_empty";
            }
            if ($data["roam_data"]["risk"]==""){
                return "borrow_loan_roam_risk_empty";
            }
                
                
        }
        
         //�жϽ��ı���
        if ($type_result['account_multiple']>0 && $data['account']%$type_result['account_multiple']!=0){
            return  "borrow_loan_account_multiple_error";
        }
        
        return $type_result;
    }
    
    
    /**
	 * ��ӽ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
    function  Add($data = array()){
        global $mysql,$_G;
        
        //�ж��û��Ƿ����
        if ($data['valicode']!=$_SESSION['valicode']) {
            return "borrow_loan_valicode_error";
        }
        unset($data['valicode']);
        
		//�ж�����Ƿ����㹻��Ϣ
		$_balance = accountClass::GetAccountUsers(array("user_id"=>$data['user_id']));
		$lixi = round($data['account']*$data['borrow_apr']*0.01/12,2);
		if($data['borrow_type']=="second" && $lixi > $_balance['balance']){
			return "borrow_account_no";
		}
		
        //��ӽ�������������
        $type_result = self::ActionCheck($data);
        if (!is_array($type_result)) return $type_result;
        $data['amount_type'] = $type_result['amount_type'];
        $data['amount_account'] = $data['account'];
        
        //���ɽ�������ˮ
		$sql="select max(id) as maxid from `{borrow}`";
		$nid=$mysql->db_fetch_array($sql);
		if ($nid['maxid']==""){
			$today = date("Ym");
			$data["borrow_nid"]=$today."00001";
		}else{
			$sql="select borrow_nid from `{borrow}` where id={$nid['maxid']}";
			$borrow_nid=$mysql->db_fetch_array($sql);
			$today = date("Ym");
			$pid = str_replace($today,'',$borrow_nid['borrow_nid']);
			if (strlen($pid)==strlen($borrow_nid['borrow_nid'])){
				$data["borrow_nid"]=$today."00001";
			}else{
				$pid = $today.str_pad($pid,5,"0",STR_PAD_LEFT);
				$data["borrow_nid"]=$pid+1;
			}
		}
        
        
        //��ת��
        if ($data["borrow_type"]=="roam"){
            $roam_data = $data["roam_data"];
            $roam_data["portion_total"] = $data["account"]/$roam_data["account_min"];
            $roam_data["portion_wait"] = $roam_data["portion_total"];
            $sql = "insert into `{borrow_roam}` set `user_id` = '".$data["user_id"]."',`borrow_nid` = '".$data["borrow_nid"]."'";
    		foreach($roam_data as $key => $value){
    			$sql .= ",`$key` = '$value'";
    		}
            $mysql->db_query($sql);
            unset($data["roam_data"]);
        }
		
		if ($data["borrow_type"]=="second"){
            $account_sql = "select * from `{account}` where user_id={$data['user_id']}";
            $account_result = $mysql->db_fetch_array($account_sql);
            $_equal["account"] = $data["account"];
            $_equal["period"] = $data["borrow_period"];
            $_equal["apr"] = $data["borrow_apr"];
            $_equal["style"] = $data["borrow_style"];
            $equal_result = borrowCalculateClass::GetType($_equal);
            $money = $equal_result[0]['account_interest'];
            if ($account_result['balance'] < $money){
                return "borrow_miao_account_no";
            }
            $log_info["user_id"] = $data['user_id']; //�����û�id
            $log_info["nid"] = "borrow_miao_success_" . $data['borrow_nid']; //������
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 0;//
            $log_info["money"] = $money; //�������
            $log_info["income"] = 0; //����
            $log_info["expend"] = 0; //֧��
            $log_info["balance_cash"] = -$money; //�����ֽ��
            $log_info["balance_frost"] = 0; //�������ֽ��
            $log_info["frost"] = $money; //������
            $log_info["await"] = 0; //���ս��
            $log_info["repay"] = 0; //���ս��
            $log_info["type"] = "borrow_miao_success"; //����
            $log_info["to_userid"] = 0; //����˭
            $log_info["remark"] = "������궳��{$money}Ԫ";
            accountClass::AddLog($log_info);
            $data['borrow_frost_second'] = $money;
        }
       
		$sql = "insert into `{borrow}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        $result = $mysql->db_query($sql);
        //$result = true;
        if ($result!=false){   
            $sql = "select * from `{borrow_type}` where nid='{$data['borrow_type']}'";
            $type_result = $mysql->db_fetch_array($sql);
            
            //���;�ֵ�겻��Ҫ���
            $borrow_url = "<a href={$_G['web_domain']}/invest/a{$data['borrow_nid']}.html target=_blank>{$data['name']}</a>";
            //��ȶ���
        	$_amount["user_id"] = $data['user_id'];//�û�id
        	$_amount["amount_type"] = $type_result["amount_type"];//�������
        	$_amount["amount_style"] = "forever";
        	$_amount["type"] = "borrow_frost";
        	$_amount["oprate"] = "frost";
            $_amount["account"] = $data['account'];
        	$_amount["nid"] = $_amount["type"]."_".$data['user_id']."_".$data['borrow_nid'];
        	$_amount["remark"] = "��������[{$borrow_url}]���۳�{$data['account']}Ԫ���";
            $result = borrowAmountClass::AddAmountLog($_amount);	
            
            
            //�Զ�����ͨ��
            if ($type_result['verify_auto_status']==1){
                $_verify["user_id"] = 0;
                $_verify["borrow_nid"] = $data['borrow_nid'];
                $_verify["status"] = 1;
                $_verify["remark"] = $type_result['verify_auto_remark'];
                $_verify["contents"] = "ϵͳ�Զ�����ɹ�";
				$result = self::VerifyLoan($_verify);
            }
        }
		return $data['borrow_nid'];
    }
    
     /**
	 * �޸Ľ���
	 *
	 * @param Array $data
	 * @return Boolen
	 */
    function  Update($data = array()){
        global $mysql,$_G;
        
        if ($data['borrow_nid']==""){
            return "borrow_loan_nid_error";
        }
        /*
        //�ж��û��Ƿ����
        if ($data['valicode']!=$_SESSION['valicode']) {
            return "borrow_loan_valicode_error";
        }
        unset($data['valicode']);
        */
        
        //�޸Ľ�������������
        
       	$sql = "update `{borrow}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
        $mysql->db_query($sql.(join(",",$_sql))." where borrow_nid='{$data['borrow_nid']}'");
		return $data['borrow_nid'];	
    }
    
    
	//������Ƿ���Է���
	function CheckLoan($data){		
		global $mysql,$_G;
		//ʵ����֤
		$sql = "select realname from `{approve_realname}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result['realname']==''){
			return "realname";
		}		
		//�ֻ���֤
		$sql = "select * from `{approve_sms}` where user_id='{$data['user_id']}' and status=1 ";
		$result = $mysql->db_fetch_array($sql);
		if ($result['phone']==""){
			return "phone";
		}	
		//������֤
		require_once(ROOT_PATH."modules/users/users.class.php");
		$email=usersClass::GetEmailActiveOne(array("user_id"=>$_G['user_id']));
		if($email['status']!=1){
			return "email";
		}	
		
		return "continue";
	}
    function GetLoanStep($data){	
		$result = self::CheckLoan(array("user_id"=>$data['user_id']));		
		if($result=='continue'){
			$_result['status'] = 1;
		}else{
			$_result['status'] = 0;
			$_result['url'] = "renzheng/index.html?type=".$result;
		}
		return $_result;
	}
    
    
	/**
	 * ������
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function VerifyLoan($data = array()){
		global $mysql,$_G;
        
		$sql = "select borrow_apr,borrow_type,borrow_nid,status,`name`,borrow_valid_time,user_id,account,amount_type,borrow_period from `{borrow}` where borrow_nid='{$data['borrow_nid']}'";
		$result = $mysql->db_fetch_array($sql);
		//�жϽ����Ƿ����
		if ($result==false){
			return "borrow_not_exiest";
		}else{
			$borrow_url = "<a href={$_G['web_domain']}/invest/a{$result['borrow_nid']}.html target=_blank>{$result['name']}</a>";
		}	
		
		//�жϽ���Ƿ��Ѿ�ͨ������Ҳֻ��״̬0�ſ��Խ��г���
		if($result['status']!=0){
			return "borrow_verify_error";
		}		
		
		
		if($data['status']==1){
			$status=1;
		}else{
			$status = 2;
		}
        //�ж��Ƿ�����ת��
        if ($result["borrow_type"]=="roam"){
            $months = $result['borrow_period']+12;
            $borrow_end_time = strtotime("+ {$months} months");
        }else{
		  $borrow_end_time = $result['borrow_valid_time']*60*60*24+time();//����ɹ���ʼ��ʱ��Ч��
       }
		//�޸���Ӧ����Ϣ
		$sql = "update `{borrow}` set verify_time='".time()."',verify_userid='{$data['user_id']}',verify_remark='{$data['remark']}',verify_contents='{$data['contents']}',borrow_end_time='{$borrow_end_time}',status={$status},borrow_status='{$data['status']}',borrow_account_wait=account-borrow_account_yes where  borrow_nid='{$data['borrow_nid']}' ";
		$mysql->db_query($sql);
		
		
		//������ͨ����������û�������¼
		if ($data['status']==1){
			$remind['nid'] = "borrow_first_success";
			$remind['receive_userid'] = $result['user_id'];
            $remind['remind_nid'] =  "borrow_first_success_".$result["borrow_nid"]."_".$result["user_id"];
			$remind['article_id'] = $result['user_id'];
			$remind['code'] = "borrow";
			$remind['title'] = "���Ľ���[{$result['name']}]����ɹ�";
			$remind['content'] = "���Ľ���[{$borrow_url}]��".date("Y-m-d",time())."�Ѿ�����ɹ�";
			remindClass::sendRemind($remind);
            
            //����ͳ����Ϣ
            borrowCountClass::UpdateBorrowCount(array("user_id"=>$result['user_id'],"borrow_nid"=>$result['borrow_nid'],"nid"=>"borrow_loan_".$result['borrow_nid']."_".$result['user_id'],"borrow_times"=>1));
            
            //�Զ�Ͷ�����
            require_once("borrow.auto.php");
            require_once("borrow.tender.php");
    		$res = borrowAutoClass::NewAutoTender(array("borrow_nid"=>$result['borrow_nid']));			
    		if ($res != false){
    			foreach ($res as  $key => $value){
		            if ($result["borrow_type"]=="roam"){
		                require_once("borrow.roam.php");//����
                        $roam_result = borrowRoamClass::GetRoamOne(array("borrow_nid"=>$result["borrow_nid"]));
                        $_tender['user_id'] = $key;
                        $_tender['borrow_nid'] = $result['borrow_nid'];
                        $_tender["portion"] = floor($value/$roam_result["account_min"]);
                        $_tender["contents"] = "�Զ�Ͷ��";					
                        $_result = borrowRoamClass::AddRoam($_tender);
		            }else{
        				$_tender['borrow_nid'] = $result['borrow_nid'];
        				$_tender['user_id'] = $key;
        				$_tender['account'] = $value;
        				$_tender['contents'] = "�Զ�Ͷ��";
        				$_tender['status'] = 0;
        				$_tender['auto_status'] = 1;
        				$_tender['nid'] = "tender_".$key.time().rand(10,99);//������						
        				$_result = borrowTenderClass::AddTender($_tender);
                    }
					if ($_result>0){
						$sql = "insert into `{borrow_autolog}` set borrow_nid='{$result['borrow_nid']}',user_id='{$key}',account='{$value}',remark='{$_result}',addtime='".time()."',addip='".ip_address()."'";
						$mysql->db_query($sql);
						
						$user_log["user_id"] = $_tender['user_id'];
						$user_log["code"] = "tender";
						$user_log["type"] = "tender";
						$user_log["operating"] = "auto_tender";
						$user_log["article_id"] = $_tender['user_id'];
						$user_log["result"] = 1;
						$user_log["content"] = date("Y-m-d H:i:s")."�Զ�Ͷ��[{$borrow_url}]�ɹ�,���Ϊ{$_tender['account']}";
						usersClass::AddUsersLog($user_log);	
					}
    			}    		 
    		}
			require_once(ROOT_PATH."modules/remind/remind.class.php");
			require_once(ROOT_PATH."modules/approve/approve.class.php");
			require_once(ROOT_PATH."modules/users/users.class.php");
			$result_remind_borrow=remindClass::GetRemindBorrowList(array("limit"=>"all","status"=>1));
			foreach($result_remind_borrow as $key => $value){
				$account=accountClass::GetOne(array("user_id"=>$value['user_id']));
				$info=usersClass::GetUsersInfo(array("user_id"=>$value['user_id']));
				$send_status=1;
				if ($value['apr_status']==1 && $result['borrow_apr']<$value['apr']){
					$send_status=0;
				}
				if ($value['borrow_type_status']==1 && $result['borrow_type']!=$value['borrow_type']){
					$send_status=0;
				}
				if ($value['borrow_period_status']==1 && ($result['borrow_period']<$value['borrow_period_start'] || $result['borrow_period']>$value['borrow_period_end'])){
					$send_status=0;
				}
				if ($value['account_status']==1 && $result['account']<$account['balance']){
					$send_status=0;
				}
				if ($send_status==1 && $value['user_id']!=$result['user_id']){
					$sms['status'] = 1;
					$sms['phone'] = $info['phone'];
					$sms['user_id'] = $value['user_id'];
					$sms['type'] = "borrow";
					if ($result['borrow_type']=="roam"){
						$type="��ת��";
					}else{
						$type="��Ѻ��";
					}
					$sms['contents'] = "�±�[{$result['name']}]�ѷ�����������[{$result['borrow_apr']}%]������[{$result['borrow_period']}����]�����[{$result['account']}Ԫ]������[{$type}]��";
					//approveClass::SendSMS($sms);
				}
			}
		}else{
		
		   //��ȷ���
        	$_amount["user_id"] = $result['user_id'];//�û�id
        	$_amount["amount_type"] = $result["amount_type"];//�������
        	$_amount["amount_style"] = "forever";
        	$_amount["type"] = "borrow_false";
        	$_amount["oprate"] = "return";
            $_amount["account"] = $result['account'];
        	$_amount["nid"] = $_amount["type"]."_".$result['user_id']."_".$result['borrow_nid'];
        	$_amount["remark"] = "��������[{$borrow_url}]���۳�{$data['account']}Ԫ���";
            borrowAmountClass::AddAmountLog($_amount);
			
			$remind['nid'] = "borrow_first_false";
			$remind['receive_userid'] = $result['user_id'];
            $remind['remind_nid'] =  "borrow_first_false_".$result["borrow_nid"]."_".$result["user_id"];
			$remind['article_id'] = $result['user_id'];
			$remind['code'] = "borrow";
			$remind['title'] = "���Ľ���[{$result['name']}]����ʧ��";
			$remind['content'] = "���Ľ���[{$borrow_url}]��".date("Y-m-d",time())."����ʧ�ܡ�ʧ��ԭ��[{$data['verify_remark']}]";
			remindClass::sendRemind($remind);
		}
        
		//������˼�¼
        $_verify['user_id'] = $data['user_id'];
        $_verify['status'] = $data['status'];
        $_verify['borrow_nid'] = $data['borrow_nid'];
        $_verify['type'] = "verify";
        $_verify['remark'] = $data['remark'];
        $_verify['contents'] = $data['contents'];
        self::AddVerify($_verify);
        
		
        return $data['borrow_nid'];
	}
    
     /**
	 * �������
	 *
	 * @param Array $data = step,status,borrow_nid,user_id
	 * @return Boolen
	 */
	function CancelLoan($data = array()){
		global $mysql,$_G;
        $step = $data['step'];
        //��һ�������Ƿ���Գ���
		$borrow_result = borrowClass::GetView(array("borrow_nid"=>$data['borrow_nid']));
		if (!is_array($borrow_result)){
			return "tender_borrow_not_exiest";
		}
        
        //5��ǰ̨�û��ĳ��꣬6�Ǻ�̨�ĳ���
        if ($borrow_result["tender_times"]>0 && $data['status']==5){
            return "borrow_cancel_tender_error";
        }
        
        //�޸���Ӧ����Ϣ
		$sql = "update `{borrow}` set status='{$data['status']}',cancel_time='".time()."',cancel_userid='{$data['user_id']}',cancel_remark='{$data['remark']}',cancel_contents='{$data['contents']}',cancel_status=1 where  borrow_nid='{$data['borrow_nid']}' ";
		$mysql->db_query($sql);
		
        //������˼�¼
        $_verify['user_id'] = $data['user_id'];
        $_verify['status'] = $data['status'];
        $_verify['borrow_nid'] = $data['borrow_nid'];
        $_verify['type'] = "cancel";
        $_verify['remark'] = $data['remark'];
        $_verify['contents'] = $data['contents'];
        self::AddVerify($_verify);
		
		
	   return 1;
       
    }
	/**
	 * ������ɹ�
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddVerify($data = array()){
		global $mysql,$_G;
        $sql = "insert into `{borrow_verify}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        $result = $mysql->db_query($sql);
    
    }
    
    
    function UpdateLateday($data){
        global $mysql,$_G;
        if ($data['days']==""){
            return "borrow_late_days_error";
        }
        $_time = 60*60*24*$data['days'];
        $sql = "update `{borrow}` set borrow_valid_time=borrow_valid_time+{$data['days']},borrow_end_time=borrow_end_time+{$_time} where borrow_nid='{$data['borrow_nid']}'";
        $result = $mysql->db_query($sql);
        return 1;
    }
    
    //��ȡ�����б�
   	function GetRepayList($data = array()){
		global $mysql;

		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where p1.borrow_nid=p2.borrow_nid and p2.user_id=p3.user_id ";
		if (IsExiest($data['borrow_nid'])!=""){
			if ($data['borrow_nid'] == "request"){
				$_sql .= " and p1.borrow_nid= '{$_REQUEST['borrow_nid']}'";
			}else{
				$_sql .= " and p1.borrow_nid= '{$data['borrow_nid']}'";
			}
		}
		
		if (IsExiest($data['user_id'])!=""){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}	
		if(IsExiest($data['borrow_type'])!=""){
			$_sql .=" and p2.borrow_type = '{$data['borrow_type']}'";
		}
		if(IsExiest($data['repay_type'])!=""){
			$_sql .=" and p1.repay_type = '{$data['repay_type']}'";
		}
		
		if (IsExiest($data['username'])!=""){
			$_sql .= " and p3.username like '%{$data['username']}%'";
		}	 
		
		if (IsExiest($data['status_nid'])=="late"){
			$_sql .= " and p1.repay_time <=".(time()+60*60*24);
            
		}	
        
		//ɸѡ������
		if (IsExiest($data['borrow_name'])!=""){
			$data['borrow_name'] = urldecode($data['borrow_name']);
			$_sql .= " and p2.name like '%{$data['borrow_name']}%'";
		}	
				
		if (IsExiest($data['repay_time'])!=""){
			if ($date['repay_time']<=0) $data['repay_time'] = time();
			$_repayment_time = get_mktime(date("Y-m-d",$data['repay_time']));
			$_sql .= " and p1.repay_time < '{$_repayment_time}'";
		}	 
		
		if (IsExiest($data['dotime2'])!=""){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.repay_time < ".get_mktime($dotime2);
			}
		}
		if (IsExiest($data['dotime1'])!=""){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.repay_time > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['late'])!=false){
			$_sql .= " and (p1.repay_time < ".time()." and p1.repay_status!=1) or (p1.repay_status=1 and p1.late_days>0)";
		}
		
		if (IsExiest($data['status'])!=""){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		if (IsExiest($data['repay_status'])!="" || $data['repay_status']=="0"){
			$_sql .= " and p1.repay_status in ({$data['repay_status']})";
		}
		
		
		if (IsExiest($data['borrow_status'])!=""){
			$_sql .= " and p2.status = '{$data['borrow_status']}'";
		}	
		
		if (IsExiest($keywords)!=""){
		
		    if ($keywords=="request"){
				if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
					$_sql .= " and p2.keywords like '%".urldecode($_REQUEST['keywords'])."%'";
				}
			}else{
				$_sql .= " and p2.name like '%".$keywords."%'";
			}
		}
		if (IsExiest($data['keywords'])!=""){
			$_sql .= " and p2.name like '%".urldecode($data['keywords'])."%'";
		}
		if (IsExiest($data['lateing'])!=""){
			$_sql .= " and p1.repay_time<".time();
		}
		
		if (IsExiest($data['type'])!=""){
			$_sql .= " and p1.repay_web=1";
		}
		
		$onetime = time()+1*30*24*60*60;
		$threetime = time()+3*30*24*60*60;
		$sixtime = time()+6*30*24*60*60;
		if (IsExiest($data['dodate'])!=false){
			if($data['dodate']=="onemonth"){
				$dodate = $onetime;
			}elseif($data['dodate']=="threemonth"){
				$dodate = $threetime;
			}elseif($data['dodate']=="sixmonth"){
				$dodate = $sixtime;
			}
			$_sql .= " and p1.repay_time > ".time()." and p1.repay_time <= '{$dodate}' ";
		}
		
		if (IsExiest($data['late_days'])!="" || $data['late_days']=="0"){
			$_sql .= " and (TO_DAYS(FROM_UNIXTIME(".time()."))-TO_DAYS(FROM_UNIXTIME(p1.repay_time))  )>".$data['late_days'];
		}
		
		$_order = " order by p1.repay_time asc";
		if (isset($data['order']) && $data['order']!="" ){
			if ($data['order'] == "repay_time"){
				$_order = " order by p1.repay_time asc ";
			}elseif ($data['order'] == "order"){
				$_order = " order by p1.repay_period asc ,p1.id desc";
			}elseif ($data['order'] == "status"){
				$_order = " order by p1.repay_status asc ,p1.repay_time asc,p1.id desc";
			}elseif ($data['order'] == "late"){
				$_order = " order by p1.repay_web asc";
			}
		}
		$_select = " p1.*,p2.name as borrow_name,p2.borrow_period_roam,p2.borrow_period,p2.vouch_status,p2.account,p2.borrow_apr,p2.borrow_type,p2.borrow_style,p3.username as borrow_username,p4.name as type_name,p4.title as type_title,p4.late_days as _late_days";
		$sql = "select SELECT from `{borrow_repay}` as p1 
        left join `{borrow}` as p2 on p1.borrow_nid = p2.borrow_nid 
        left join `{users}` as p3 on p3.user_id=p2.user_id 
        left join `{borrow_type}` as p4 on p4.nid=p2.borrow_type 
        {$_sql} ORDER LIMIT";
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
            $repay_all = 0;
            $repay_yes_all = 0;
    		foreach ($list as $key => $value){
    		    $repay_all += $value["repay_account"];//Ӧ�ս��
    		    $repay_yes_all += $value["repay_account_yes"];//Ӧ�ս��
    			$list[$key]['credit']=borrowClass::GetBorrowCredit(array("user_id"=>$value['user_id']));
                $repay_type_name = "";
                if ($value['repay_type']=="wait"){
                    $repay_type_name = "����";
                }elseif ($value['repay_type']=="yes"){
                    $repay_type_name ="��������";
                }elseif ($value['repay_type']=="advance"){
                    $repay_type_name ="��ǰ����";
                }elseif ($value['repay_type']=="late"){
                    $repay_type_name ="���ڻ���";
                }elseif ($value['repay_type']=="web"){
                    $repay_type_name ="��վ�渶";
                }
                $list[$key]['repay_type_name'] = $repay_type_name;
                $days= borrowClass::GetDays(array("repay_time"=>$value["repay_time"]));
                $list[$key]['webpay_status'] = 0;
                if ($days>0){
                    $list[$key]['late_days'] = $days;
                    if ($days>$value['_late_days']){
                        $list[$key]['webpay_status'] = 1;
                    }
                }
                 if ($value["borrow_type"]=="roam"){
                     $list[$key]["borrow_period"] = $value["borrow_period_roam"];
                }
				if($value["repay_time"] <= $onetime){
					$onemonth += $value["repay_account"];
			    }
			    if($value["repay_time"] <= $threetime){
					$threemonth += $value["repay_account"];
			    }
			    if($value["repay_time"] <= $sixtime){
					$sixmonth += $value["repay_account"];
			    }
			    $allmonth += $value["repay_account"];
				
    		}
            //$list["repay_all"] = $repay_all;
            //$list["repay_yes_all"] = repay_yes_all;
			return $list;
		}			 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));	
		$list = $list?$list:array();
		$repay_all = 0;
        $repay_yes_all = 0;
		foreach ($list as $key => $value){
		    $repay_all += $value["repay_account"];//Ӧ�ս��
		    $repay_yes_all += $value["repay_account_yes"]+$value["repay_fee"];//Ӧ�ս�
			$list[$key]['credit']=borrowClass::GetBorrowCredit(array("user_id"=>$value['user_id']));
            $repay_type_name = "";
            if ($value['repay_type']=="wait"){
                $repay_type_name = "����";
            }elseif ($value['repay_type']=="yes"){
                $repay_type_name ="��������";
            }elseif ($value['repay_type']=="advance"){
                $repay_type_name ="��ǰ����";
            }elseif ($value['repay_type']=="late"){
                $repay_type_name ="���ڻ���";
            }elseif ($value['repay_type']=="web"){
                    $repay_type_name ="��վ�渶";
                }
            $days= borrowClass::GetDays(array("repay_time"=>$value["repay_time"]));
            if ($days>0){
                $list[$key]['late_days'] = $days;
                if ($days>$value['_late_days']){
                    $list[$key]['webpay_status'] = 1;
                }
            }
            $list[$key]['repay_type_name'] = $repay_type_name;
             if ($value["borrow_type"]=="roam"){
                 $list[$key]["borrow_period"] = $value["borrow_period_roam"];
            }
			
		}		
		//ͳ��δ��������
		$lists = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, ""), $sql));	
		foreach ($lists as $key => $value){
			if($value["repay_time"] <= $onetime){
				$onemonth += $value["repay_account"];
			}
			if($value["repay_time"] <= $threetime){
				$threemonth += $value["repay_account"];
			}
			if($value["repay_time"] <= $sixtime){
				$sixmonth += $value["repay_account"];
			}
			$allmonth += $value["repay_account"];
		}
		return array(
            'list' => $list,
            'total' => $total,
            'repay_all' => $repay_all,
            'repay_yes_all' => $repay_yes_all,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page,
			'onemonth' => $onemonth,
			'threemonth' => $threemonth,
			'sixmonth' => $sixmonth,
			'allmonth' => $allmonth	
        );
		
	}
    
    
    	/**
	 * �鿴���꣬�˺������ڴ󲿷ֵĽ�����棬����    
	 *
	 * @param Array $data
	 * @return Array
	 */
    public static function GetRepayView($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
        
        
		if (IsExiest($data['user_id'])!=""){
			$_sql .= " and  p1.user_id = '{$data['user_id']}' ";
		}
		if (IsExiest($data['id'])!=""){
			$_sql .= " and  p1.id = '{$data['id']}' ";
		}
		if (IsExiest($data['borrow_nid'])!=""){
			$_sql .= " and  p1.borrow_nid = '{$data['borrow_nid']}' ";
		}
		$sql = "select  p1.*,p2.name as borrow_name,p2.borrow_type,p2.borrow_style,p3.username,p4.vip_late_scale,p4.all_late_scale  from `{borrow_repay}` as p1 
				 left join {borrow} as p2 on p1.borrow_nid=p2.borrow_nid
				 left join {borrow_type} as p4 on p4.nid=p2.borrow_type
				 left join {users} as p3 on p1.user_id=p3.user_id
				  $_sql
				";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "borrow_repay_empty";
        $account_result = accountClass::GetOne(array("user_id"=>$data["user_id"]));
        $result["account"] = $account_result;
        $days= borrowClass::GetDays(array("repay_time"=>$result["repay_time"]));
        $result["days"] = $days;
        $result["repay_all"] = $result["repay_account"];
         //�۳�����
          //�ж��Ƿ���vip
        $result["fee"] = "";
        $fee_result = borrowFeeClass::GetRepayFeeResult($result);
        if (is_array($fee_result)){
            foreach ($fee_result as $key => $value){
                $result["repay_all"] += $value["account"];
            }
            $result["fee"] = $fee_result;
        }
        
        //����
        if ($days>0){
            $vip_status =0;
    	    $vip_result = usersvipClass::GetUsersVip(array("user_id"=>$result['user_id']));
            if($vip_result==true){
                 $vip_status = $vip_result['status'];
            }
            if ($vip_status==1){
    			$repay_account = $result['repay_account']*$result['vip_late_scale']*0.01;
    		}else{
    			$repay_account = $result['repay_capital']*$result['all_late_scale']*0.01;
    		}
            
            $result["repay_late_account_all"] = round($repay_account,2);//���ڵ渶���ܶ�
        }
        
        return $result;
        
	}
    
    /**
	 * ���ڻ���鿴
	 *
	 * @param Array $data
	 * @return Array
	 */
    public static function GetRepayLate($data = array()){
        global $mysql;
        if ($data["repay_id"]=="") return "";
        $sql = "select * from `{borrow_repay}` where id='{$data["repay_id"]}'";
        $repay_result = $mysql->db_fetch_array($sql);
        
        $sql = "select  p1.*,p2.name as borrow_name,p2.borrow_type,p2.borrow_style,p3.username,p4.vip_late_scale,p4.all_late_scale  from `{borrow_recover}` as p1 
				 left join {borrow} as p2 on p1.borrow_nid=p2.borrow_nid
				 left join {users} as p3 on p1.user_id=p3.user_id
				 left join {borrow_type} as p4 on p4.nid=p2.borrow_type where p1.borrow_nid='{$repay_result['borrow_nid']}' and  p1.recover_period='{$repay_result['repay_period']}'";
        $recover_result = $mysql->db_fetch_arrays($sql);
        foreach ($recover_result as $key => $value){
            $vip_status =0;
    	    $vip_result = usersvipClass::GetUsersVip(array("user_id"=>$value['user_id']));
            if($vip_result==true){
                 $vip_status = $vip_result['status'];
            }
            if ($vip_status==1){
    			$recover_account = $value['recover_account']*$value['vip_late_scale']*0.01;
    		}else{
    			$recover_account = $value['recover_capital']*$value['all_late_scale']*0.01;
    		}
            $recover_result[$key]["recover_late_account"] = round($recover_account,2);
            $recover_result[$key]["vip_status"] = $vip_status;
        }
        return $recover_result;
    }
   	/**
	 * ��ǰ����鿴
	 *
	 * @param Array $data
	 * @return Array
	 */
    public static function GetRepayAdvance($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
        
        $return_result = array();
        
		if (IsExiest($data['user_id'])!=""){
			$_sql .= " and  p1.user_id = '{$data['user_id']}' ";
		}
		if (IsExiest($data['borrow_nid'])!=""){
			$_sql .= " and  p1.borrow_nid = '{$data['borrow_nid']}' ";
		}
		if (IsExiest($data['repay_status'])!=""){
			$_sql .= " and  p1.repay_status = '{$data['repay_status']}' ";
		}
        $sql = "select * from `{borrow}` where borrow_nid='{$data['borrow_nid']}'";
        $borrow_result = $mysql->db_fetch_array($sql);
        $return_result["borrow_name"] = $borrow_result["name"];
        
        
        $sql = "select sum(p1.repay_capital) as num ,sum(p1.repay_interest) as innum from `{borrow_repay}` as p1  {$_sql}";
        $repay_result = $mysql->db_fetch_array($sql);
        $return_result["repay_capital"] = $repay_result["num"];
        $return_result["repay_interest"] = $repay_result["innum"];
		$sql = "select  p1.*,p2.name as borrow_name,p2.borrow_type,p2.borrow_style,p3.username  from `{borrow_repay}` as p1 
				 left join {borrow} as p2 on p1.borrow_nid=p2.borrow_nid
				 left join {users} as p3 on p1.user_id=p3.user_id
				  $_sql
				";
		$result = $mysql->db_fetch_arrays($sql);
		if ($result==false) return "borrow_repay_empty";
        $return_result["repay"] = $result;
        
        $account_result = accountClass::GetOne(array("user_id"=>$data["user_id"]));
        $return_result["account"] = $account_result;
        
         //�۳�����
          //�ж��Ƿ���vip
        $vip_status =0;
	    $vip_result = usersvipClass::GetUsersVip(array("user_id"=>$data['user_id']));
        if($vip_result==true){
             $vip_status = $vip_result['status'];
        }
        $credit_result = borrowClass::GetBorrowCredit(array("user_id"=>$data["user_id"]));
        $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
        $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
        $_fee["borrow_type"] = $borrow_result["borrow_type"];//�������
        $_fee["borrow_style"] = $borrow_result["borrow_style"];//���ʽ
        $_fee["type"] = "borrow_repay_advance";//���ڽ���߻���Ͷ����
        $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
        $_fee["capital"] = $return_result["repay_capital"];//���ڽ���߻���Ͷ����
        $_fee["interest"] = $return_result["repay_interest"];//���ڽ���߻���Ͷ����
        $fee_result = borrowFeeClass::GetFeeValue($_fee);
        $return_result["repay_all"] =  $return_result["repay_capital"];
        if (is_array($fee_result)){
            foreach ($fee_result as $key => $value){
                $return_result["repay_all"] += $value["account"];
            }
            $return_result["fee"] = $fee_result;
        }
        return $return_result;
        
	}
    
    
    //��ȡ�����б�
   	function GetVerifyList($data = array()){
		global $mysql;

		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if (IsExiest($data['borrow_nid'])!=""){
			if ($data['borrow_nid'] == "request"){
				$_sql .= " and p1.borrow_nid= '{$_REQUEST['borrow_nid']}'";
			}else{
				$_sql .= " and p1.borrow_nid= '{$data['borrow_nid']}'";
			}
		}
		
		if (IsExiest($data['user_id'])!=""){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}	
	
		
		$_order = " order by p1.id asc";
		
		$_select = " p1.*,p2.username";
		$sql = "select SELECT from `{borrow_verify}` as p1 
        left join `{users}` as p2 on p2.user_id=p1.user_id 
        {$_sql} ORDER LIMIT";
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
    		foreach ($list as $key => $value){
                $type_name = "";
                if ($value['type']=="verify"){
                    $type_name = "����";
                }elseif ($value['type']=="reverify"){
                    $type_name ="����";
                }
                $list[$key]['type_name'] = $type_name;
                
                $status_name = "";
                if ($value['status']=="1"){
                    $status_name = "����ͨ��";
                }elseif ($value['status']=="2"){
                    $status_name ="����ͨ��";
                }elseif ($value['status']=="3"){
                    $status_name ="����ͨ��";
                }elseif ($value['status']=="4"){
                    $status_name ="���겻ͨ��";
                }elseif ($value['status']=="6"){
                    $status_name ="����";
                }
                $list[$key]['status_name'] = $status_name;
    		}
			return $list;
		}			 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));	
		$list = $list?$list:array();
		foreach ($list as $key => $value){
            $type_name = "";
            if ($value['type']=="verify"){
                $type_name = "����";
            }elseif ($value['type']=="reverify"){
                $type_name ="����";
            }
            $list[$key]['type_name'] = $type_name;
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	function CheckWorth($data = array()){
		global $mysql;
		if ($data['user_id']==""){
			return "user_id_no_exiest";
		}
		$sql = "select 1 from `{borrow}` where user_id = {$data['user_id']} and borrow_type='worth' and status in (0,1)";
		$result = $mysql->db_fetch_array($sql);		
		if($result==true){
			return 1;
		}else{
			return 0;
		}
	}
}
?>
