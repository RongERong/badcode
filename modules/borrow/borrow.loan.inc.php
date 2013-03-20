<?php
/******************************
 * $File: borrow.loan.inc.php
 * $Description: �û�����û����Ĵ����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("borrow.class.php");//����
require_once("borrow.loan.php");//����
require_once("borrow.type.php");//����

//�µĽ��
if ($_REQUEST['p']=="new"){
    
    if (isset($_POST['name']) && $_POST["name"]!=""){
        $var = array("name","borrow_type","borrow_use","borrow_password","account","borrow_period","borrow_apr","borrow_style","borrow_contents","borrow_valid_time","tender_account_min","tender_account_max","award_status","award_scale","award_account","award_false","valicode");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		
        //��Ѻ��
		if($data['borrow_type']=="pawn"){
			$_G['upimg']['code'] = "borrow";
			$_G['upimg']['type'] = "pawn";
			$_G['upimg']['user_id'] = $data["user_id"];
			$_G['upimg']['article_id'] = $data["user_id"];
			
			$_G['upimg']['file'] = "borrow_pawn_app";
			$pic_result1 = $upload->upfile($_G['upimg']);			
			if ($pic_result1!=false){
				$data["borrow_pawn_app"] = $pic_result1[0]["upfiles_id"];
				$data["borrow_pawn_app_url"] = $pic_result1[0]["filename"];
			}
			
			$_G['upimg']['file'] = "borrow_pawn_auth";
			$pic_result2 = $upload->upfile($_G['upimg']);
			if ($pic_result2!=false){
				$data["borrow_pawn_auth"] = $pic_result2[0]["upfiles_id"];
				$data["borrow_pawn_auth_url"] = $pic_result2[0]["filename"];
			}
			$_G['upimg']['file'] = "borrow_pawn_formalities";
			$pic_result3 = $upload->upfile($_G['upimg']);
			if ($pic_result3!=false){
				$data["borrow_pawn_formalities"] = $pic_result3[0]["upfiles_id"];
				$data["borrow_pawn_formalities_url"] = $pic_result3[0]["filename"];
			}	
			$data['borrow_pawn_type']=$_POST['borrow_pawn_type'];
			$data['borrow_pawn_time']=$_POST['borrow_pawn_time'];
			$data['borrow_pawn_description']=$_POST['borrow_pawn_description'];
		}
        	
	    //��ת��
		elseif($data['borrow_type']=="roam"){
		   $var = array("account_min","voucher","vouch_style","borrow_account","borrow_account_use","risk");
		   $_data = post_var($var);
           $data["roam_data"] = $_data; 
        }
        
        
        if ($data['borrow_password']!=""){
            $data['borrow_password'] = md5($data['borrow_password']);
        }
	   
		$result = borrowLoanClass::Add($data);
		if ($result>0){
			$msg = array($MsgInfo["borrow_success_msg"],"","/?user&q=code/borrow/loan&p=now");
		}else{
			$msg = array($MsgInfo[$result]);
		}
    }else{
		if ($_G["system"]["con_borrow_step_status"]==1){
			
			require_once(ROOT_PATH."modules/borrow/borrow.amount.php");
			$result = borrowLoanClass::CheckLoan(array("user_id"=>$_G['user_id'],"type_nid"=>$_REQUEST['type_nid']));
			$_U["type_nid"]=$_REQUEST['type_nid'];
			$type_nid=$_REQUEST['type_nid'];
			require_once("borrow.amount.php");//���
			
			if($_U["type_nid"]=="day"){
				$borrow_type = "credit";
			}elseif($_U["type_nid"]=="roam"){
				$borrow_type = "vest";
			}else{
				$borrow_type = $_U["type_nid"];
			}			
			if($borrow_type=="worth"){
				$worth_status = borrowLoanClass::CheckWorth(array("user_id"=>$_G["user_id"]));
			}
			
			$amount = borrowAmountClass::GetAmountUsers(array("user_id"=>$_G["user_id"]));
			
			$type = array("credit"=>"���ö��","day"=>"���ö��","vouch"=>"�������","pawn"=>"���Ŷ��","roam"=>"��ת���");
			if($result=="email"){
					
					header("Location:/?user&q=code/approve/email"); 
					
			}elseif($type_nid=="worth" && $amount['worth'] ==0 ){
				
				$msg = array("���ľ�ֵ��Ȳ������ܷ�����ֵ��","","/borrow/index.html");
				
			}elseif($type_nid=="worth" && $worth_status=="1" ){
				
				$msg = array("���ľ�ֵ�껹û������ȴ�����Ա���","","/borrow/index.html");
				
			}elseif($type_nid!="worth" && $type_nid!="second" && $amount[$borrow_type."_use"]==0){
				if ($result=="realname"){//ʵ����֤
					
					$msg = array("$type[$type_nid]Ϊ0,��������","","/?user&q=code/borrow/loan&p=loan_realname&type=".$type_nid);
								
				}elseif ($result=="phone"){//�ֻ���֤	
					$msg = array("$type[$type_nid]Ϊ0,��������","","/?user&q=code/borrow/loan&p=loan_phone&type=".$type_nid);
							
				}elseif ($result=="info"){//������Ϣ��д
					$msg = array("$type[$type_nid]Ϊ0,��������","","/?user&q=code/borrow/loan&p=info&type=".$type_nid);
					
				}elseif ($result=="amount"){//���������Ϣ��д	
					$msg = array("$type[$type_nid]Ϊ0,��������","","/?user&q=code/borrow/loan_amount&type=".$type_nid);
					
				}elseif ($result=="approve"){//��֤��Ϣ��д
					$msg = array("$type[$type_nid]Ϊ0,��������","","/?user&q=code/borrow/loan&p=approve");
					
				}elseif($result=="new"){  		
					//�������
					$_U["borrow_type_result"] = borrowTypeClass::GetTypeOne(array("nid"=>$_REQUEST["type_nid"]));
					if ($_U["borrow_type_result"]==false){
						$msg = array("���Ĳ�������");
					}
					//�û����
					require_once("borrow.amount.php");//���
					$_U["users_amount_result"] = borrowAmountClass::GetAmountUsers(array("user_id"=>$_G["user_id"]));
				   $_G["site_nid"] = "borrow";
					$template = "users_loan_new.html";//��ʼ�������
				}
			}else{
				//�������
					$_U["borrow_type_result"] = borrowTypeClass::GetTypeOne(array("nid"=>$_REQUEST["type_nid"]));
					if ($_U["borrow_type_result"]==false){
						$msg = array("���Ĳ�������");
					}
					//�û����
					require_once("borrow.amount.php");//���
					$_U["users_amount_result"] = borrowAmountClass::GetAmountUsers(array("user_id"=>$_G["user_id"]));
				   $_G["site_nid"] = "borrow";
					$template = "users_loan_new.html";//��ʼ�������
			}
		}else{
		
			//�������
			$_U["borrow_type_result"] = borrowTypeClass::GetTypeOne(array("nid"=>$_REQUEST["type_nid"]));
			if ($_U["borrow_type_result"]==false){
				$msg = array("���Ĳ�������");
			}
			//�û����
			require_once("borrow.amount.php");//���
			$_U["users_amount_result"] = borrowAmountClass::GetAmountUsers(array("user_id"=>$_G["user_id"]));
		    $_G["site_result"]["nid"] = "borrow";
			$template = "users_loan_new.html";//��ʼ�������
		
       } 
    }

}


//��ӻ�����Ϣ
elseif ($_REQUEST['p'] == "info"){	
	if ($_POST['submit']!=""){
			require_once(ROOT_PATH."/modules/rating/rating.class.php");
			require_once(ROOT_PATH."/modules/credit/credit.class.php");
			$var = array("sex","marry","children","income","birthday","edu","is_car","address","school_year","school","house","phone","jiguanprovince","jiguancity","hukouprovince","hukoucity","area","realname","card_id","phone_num","old_name","qq","post_id","house_status","borrow_password","car_status","nowhouse","houseaddress","housess","shouru","live_city");
			$data = post_var($var);
			$data['user_id'] = $_G['user_id'];
			$data['status'] = 1;
			$result = ratingClass::GetInfoOne($data);
			if (is_array($result)){
				$_result = ratingClass::UpdateInfo($data);
			}else{
				$_result = ratingClass::AddInfo($data);
				
				$credit_log['user_id'] = $_G['user_id'];
				$credit_log['nid'] = "info_credit";
				$credit_log['code'] = "borrow";
				$credit_log['type'] = "info_credit";
				$credit_log['addtime'] = time();
				$credit_log['article_id'] =$_G['user_id'];
				$credit_log['remark'] = "��д���������õĻ���";
				creditClass::ActionCreditLog($credit_log);
			}
			$template = "users_loan_work.html";//������Ϣ��д
			$_G["site_nid"] = "borrow";
	}else{
		$template = "users_loan_info.html";//������Ϣ��д
		$_G["site_nid"] = "borrow";
	}
}
//����Ľ��
elseif ($_REQUEST['p'] == "cancel"){
    require_once("borrow.cancel.php");//����
    $data['borrow_nid'] = $_REQUEST['borrow_nid'];
	$data['user_id'] = $_G['user_id'];
	$result = borrowCancelClass::UserCancel($data);
	if ($result>0){
		$msg = array($MsgInfo["borrow_cancel_success"],"","index.php?user&q=code/borrow/loan&p=now");
	}elseif (IsExiest($MsgInfo[$result])!=""){
		$msg = array($MsgInfo[$result]);
	}else{
		$msg = array("����ʧ�ܣ��������Ա��ϵ");
	}
}


elseif ($_REQUEST['p'] == "repays"){
	require_once("borrow.repay.php");
    $result = array();
    if ($_REQUEST['step']==""){
		$data['user_id'] = $_G['user_id'];
        $data["repay_id"] = $_REQUEST['repay_id'];
        $data["paypassword"] = $_REQUEST['paypassword'];
        if (md5($data['paypassword'])!= $_G["user_result"]["paypassword"]){
             $result = "borrow_repay_paypassword_error";
        }else{
		     $result = borrowRepayClass::RepayInfo($data);
        }
		if (!is_array($result)){
            $result = array("result"=>2,"name"=> $MsgInfo[$result]);
		}
	}else{
	     $data['repay_id'] = $_REQUEST['repay_id']; 
	     $data['step'] = $_REQUEST['step']; 
	     $data['key'] = $_REQUEST['key']; 
	     $data['user_id'] = $_G['user_id']; 
         $result = borrowRepayClass::RepayInfo($data); 
         if (!is_array($result)){
            $result = array("result"=>2,"name"=>$MsgInfo[$result]);
         }
	}
    $_U['repay_result'] = $result;
    $msg = "";
    $template = "users_loan_view.html";//������Ϣ��д
}
//��ǰ����
elseif ($_REQUEST['p'] == "repays_advance"){
	require_once("borrow.repay_advance.php");
     $result = array();
    if ($_REQUEST['step']==""){
		$data['user_id'] = $_G['user_id'];
        $data["borrow_nid"] = $_REQUEST['borrow_nid'];
        $data["paypassword"] = $_REQUEST['paypassword'];
        if (md5($data['paypassword'])!= $_G["user_result"]["paypassword"]){
             $_result = "borrow_repay_paypassword_error";
        }else{
		     $_result = borrowRepayAdvanceClass::RepayAdvanceInfo($data);
        }
		if (is_array($_result)){
		     $result = $_result;
        }else{
            $result = array("result"=>2,"name"=> $MsgInfo[$_result]);
		}
	}else{
	     $data['borrow_nid'] = $_REQUEST['borrow_nid']; 
	     $data['period'] = $_REQUEST['period']; 
	     $data['step'] = $_REQUEST['step']; 
	     $data['key'] = $_REQUEST['key']; 
	     $data['user_id'] = $_G['user_id']; 
         $result = borrowRepayAdvanceClass::RepayAdvanceInfo($data); 
         if (!is_array($result)){
              $result = array("result"=>2,"name"=>$MsgInfo[$result]);
         }
	}
    $_U['repay_result'] = $result;
    $msg = "";
    $template = "users_loan_view_advance.html";//������Ϣ��д
}

//���ڻ���
elseif ($_REQUEST['p'] == "repays_late"){
	require_once("borrow.repay_late.php");
    $result = array();
    if ($_REQUEST['step']==""){
		$data['user_id'] = $_G['user_id'];
        $data["repay_id"] = $_REQUEST['repay_id'];
        $data["paypassword"] = $_REQUEST['paypassword'];
        if (md5($data['paypassword'])!= $_G["user_result"]["paypassword"]){
             $result = "borrow_repay_paypassword_error";
        }else{
		     $result = borrowRepayLateClass::RepayLateInfo($data);
        }
		if (!is_array($result)){
            $result = array("result"=>2,"name"=> $MsgInfo[$result]);
		}
	}else{
	     $data['repay_id'] = $_REQUEST['repay_id']; 
	     $data['step'] = $_REQUEST['step']; 
	     $data['key'] = $_REQUEST['key']; 
	     $data['user_id'] = $_G['user_id']; 
         $result = borrowRepayLateClass::RepayLateInfo($data); 
         if (!is_array($result)){
            $result = array("result"=>2,"name"=>$MsgInfo[$result]);
         }
	}
    $_U['repay_result'] = $result;
    $msg = "";
    $template = "users_loan_view.html";//������Ϣ��д
}

//ʵ����֤
elseif ($_REQUEST['p'] == "realname"){	
	require_once(ROOT_PATH."modules/approve/approve.class.php");	
	if ($_POST['realname']!=""){
		$var = array("realname","card_id");
		$data = post_var($var);
        $data['realname'] = iconv("UTF-8","GBK",$data['realname']);
		$data['user_id'] = $_G['user_id'];		
		$result = approveClass::AddRealname($data);	
       echo 1;
       exit;
	}else{
	   $template = "users_loan_ajax_realname.html";//������Ϣ��д
    }
}

//��ӹ�����Ϣ
elseif ($_REQUEST['p'] == "view_roam"){	
    if (!empty($_POST["paypassword"])){
        require_once("borrow.roam.php");//����
        $data['user_id'] = $_G['user_id'];
        $data["borrow_nid"] = $_REQUEST['borrow_nid'];
        $data["paypassword"] = $_POST['paypassword'];
        $data["portion"] = $_POST['num'];
        $data["contents"] = $_POST['contents'];
		if($_POST['valicode']!=$_SESSION['valicode']){
			$_result = "valicode_error";
		}elseif (md5($data['paypassword'])!= $_G["user_result"]["paypassword"]){
             $_result = "borrow_roam_paypassword_error";
        }elseif (empty($_G["user_result"]["paypassword"])){
			$_result = "borrow_roam_paypassword_error";
		}else{
		     $_result = borrowRoamClass::AddRoam($data);
        }
		if (is_array($_result)){
		     $result = 1;
        }else{
            $result = $MsgInfo[$_result];
		}
        echo $result;
        exit;
    }
	$template = "users_loan_ajax_roam.html";//������Ϣ��д
}


/* �ϴ����ϵ����*/
//��ӹ�����Ϣ
elseif ($_REQUEST['p'] == "att"){	
	$template = "users_loan_ajax_att.html";//������Ϣ��д
}

//ʵ����֤
elseif ($_REQUEST['p'] == "approve"){	
	$template = "users_loan_approve.html";//������Ϣ��д
	$_G["site_nid"] = "borrow";
}
//�ֻ���֤
elseif ($_REQUEST['p'] == "phone"){	
	$template = "users_loan_ajax_phone.html";//������Ϣ��д
}
//Ͷ��ҳ��
elseif ($_REQUEST['p'] == "tender"){	
	$template = "detail.html";
}

//��������
elseif ($_REQUEST['p'] == "views"){	
    
}


//��ͨ��������
elseif ($_REQUEST['p'] == "view"){	
    $template = "users_loan_view.html";//��ʼ�������
}
//��ǰ��������
elseif ($_REQUEST['p'] == "view_advance"){	
    $template = "users_loan_view_advance.html";//��ʼ�������
}
//��ǰ��������
elseif ($_REQUEST['p'] == "loan_realname"){	
    $template = "users_loan_realname.html";//��ʼ�������
	$_G["site_nid"] = "borrow";
}
//��ǰ��������
elseif ($_REQUEST['p'] == "loan_phone"){	
    $template = "users_loan_phone.html";//��ʼ�������
	$_G["site_nid"] = "borrow";
}



//Ͷ��ҳ��
elseif ($_REQUEST['p'] == "tender"){	
	$template = "detail.html";
}


 
//�����б�Ľ��
elseif ($_REQUEST['p']=="now"){
    
}
 
//������Ϣ ������Ϣ ������Ϣ ��ϵ��Ϣ
elseif ($_REQUEST['p']=="work" || $_REQUEST['p']=="company"|| $_REQUEST['p']=="finance"|| $_REQUEST['p']=="contact"|| $_REQUEST['p']=="houses"){
    $template = "users_loan_info.html";
	$_G["site_nid"] = "borrow";
}

//�����б�Ľ��
elseif ($_REQUEST['p']=="count"){
    
}
//�����
elseif ($_REQUEST['p']=="plan"){
    $result = borrowCountClass::GetUsersRepayCount(array("user_id"=>$_G['user_id']));
    $_U['borrow_plan'] = $result;
	
}
//�����б�Ľ��
elseif ($_REQUEST['p']=="repay"){
    
}else{
    $template = "error.html";//��ʼ�������
}	
?>