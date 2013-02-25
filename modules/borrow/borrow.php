<?
/******************************
 * $File: borrow.php
 * $Description: ������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
 

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$_A['list_purview']["borrow"]["name"] = "������";
$_A['list_purview']["borrow"]["result"]["borrow_manage"] = array("name"=>"������","url"=>"code/borrow/manage","title"=>"�ɲ鿴ǰ̨�û����еĽ��");
$_A['list_purview']["borrow"]["result"]["borrow_first"] = array("name"=>"������","url"=>"code/borrow/first","title"=>"���ύ�Ľ����г��󡢱༭���������ӳ��Ȳ����͹���");
$_A['list_purview']["borrow"]["result"]["borrow_full"] = array("name"=>"������","url"=>"code/borrow/full","title"=>"�������������ˣ��鿴�����к��ѻ�����");
$_A['list_purview']["borrow"]["result"]["borrow_roam"] = array("name"=>"��ת����","url"=>"code/borrow/roam","title"=>"����ת��ĳ������ת���ع������в鿴�͹���");
$_A['list_purview']["borrow"]["result"]["borrow_late"] = array("name"=>"���ڽ��","url"=>"code/borrow/late","title"=>"�鿴���ڽ�ִ����վ�渶����Ӧ���˵Ĺ���");
$_A['list_purview']["borrow"]["result"]["borrow_amount"] = array("name"=>"�����","url"=>"code/borrow/amount","title"=>"���ڲ鿴�͹����û��ĸ��ֽ����");
$_A['list_purview']["borrow"]["result"]["borrow_repay"] = array("name"=>"������Ϣ","url"=>"code/borrow/repay","title"=>"���ڲ鿴�����û��Ļ�����ϸ");
$_A['list_purview']["borrow"]["result"]["borrow_recover"] = array("name"=>"�տ���Ϣ","url"=>"code/borrow/recover","title"=>"���ڲ鿴�����û����տ���Ϣ");
$_A['list_purview']["borrow"]["result"]["borrow_tender"] = array("name"=>"Ͷ����Ϣ","url"=>"code/borrow/tender","title"=>"���ڲ鿴�����û���Ͷ����Ϣ");
$_A['list_purview']["borrow"]["result"]["borrow_fee"] = array("name"=>"������","url"=>"code/borrow/fee","title"=>"�������ý�������в����ĸ��ַ��ù���");
$_A['list_purview']["borrow"]["result"]["borrow_type"] = array("name"=>"��������","url"=>"code/borrow/type","title"=>"�������ú͹������н����ֵĽ�����");
$_A['list_purview']["borrow"]["result"]["borrow_style"] = array("name"=>"���ʽ","url"=>"code/borrow/style","title"=>"���ڲ鿴�͹������н��Ļ��ʽ");
$_A['list_purview']["borrow"]["result"]["loan_pawn"] = array("name"=>"������Ѻ��","url"=>"code/borrow/loan&type_nid=pawn","title"=>"������Ѻ��");
$_A['list_purview']["borrow"]["result"]["loan_roam"] = array("name"=>"������ת��","url"=>"code/borrow/loan&type_nid=roam","title"=>"������ת��");
require_once("borrow.class.php");
require_once("borrow.reverify.php");
require_once("borrow.excel.php");

$_A['borrow_amount_type'] = $borrow_amount_type;
/**
 * �������Ϊ�յĻ�����ʾ���е��ļ��б�
**/

if(file_exists(ROOT_PATH."modules/borrow/borrow.".$_A['query_type'].".admin.php")){
	echo ROOT_PATH."modules/borrow/borrow.".$_A['query_type'].".admin.php";
    require_once(ROOT_PATH."modules/borrow/borrow.".$_A['query_type'].".admin.php");
}

//�鿴
elseif ($_A['query_type'] == "view"){
		$data['borrow_nid'] = $_REQUEST['borrow_nid'];
		$result = borrowClass::GetView($data);
		if (!is_array($result)){
			$msg = array($MsgInfo[$result]);
		}else{
			$_A['borrow_result'] = $result;
		}
}

//���󲢱༭
elseif ($_A['query_type'] == "first" ){
	check_rank("borrow_first");
	if ($_REQUEST['check']!=""){
		if (isset($_POST['borrow_nid']) && $_POST['borrow_nid']!=""){
			$msg = check_valicode();
			if ($_POST['verify_remark']==""){
				$msg = array("��˱�ע����Ϊ��","",$_A['query_url_all']);
			}
			if ($msg==""){
				$var = array("borrow_nid","status","verify_remark");
				$data = post_var($var);
				
				$result = borrowClass::Verify($data);
				if ($result>0){
					$msg = array($MsgInfo["borrow_verify_success"],"",$_A['query_url_all']);
				}else{
					$msg = array($MsgInfo[$result]);
				}
				
				//�������Ա������¼
				$admin_log["user_id"] = $_G['user_id'];
				$admin_log["code"] = "borrow";
				$admin_log["type"] = "borrow";
				$admin_log["operating"] = "verify";
				$admin_log["article_id"] = $result>0?$result:0;
				$admin_log["result"] = $result>0?1:0;
				$admin_log["content"] =  $msg[0];
				$admin_log["data"] =  $data;
				usersClass::AddAdminLog($admin_log);
			}
		}else{
			$data['borrow_nid'] = $_REQUEST['check'];
			$result = borrowClass::GetOne($data);
			if (!is_array($result)){
				$msg = array($MsgInfo[$result]);
			}elseif ($result['status']!=0){
				$msg = array($MsgInfo["borrow_not_exiest"]);
			}else{
				$_A['borrow_result'] = $result;
			}
		}
	}elseif ($_REQUEST['view']!=""){
		$data['borrow_nid'] = $_REQUEST['view'];
		$result = borrowClass::GetView($data);
		if (!is_array($result)){
			$msg = array($MsgInfo[$result]);
		}else{
			$_A['borrow_result'] = $result;
		}
	}elseif ($_REQUEST['first_edit']!=""){
		$data['borrow_nid'] = $_REQUEST['first_edit'];
		$borrow_result = borrowClass::GetOne($data);
		$_A['borrow_result'] = $borrow_result;
		if ($_POST['borrow_nid']){
           if ($borrow_result["borrow_type"]=="roam"){
                require_once("borrow.roam.php");
                $var = array("name","borrow_use","borrow_period","borrow_apr","borrow_contents","borrow_nid");
    			$data = post_var($var);
                
                $_var = array("borrow_nid","voucher","vouch_style","borrow_account","borrow_account_use","risk","upfiles_id");
                $_data = post_var($_var);
                $result = borrowRoamClass::UpdateRoam($_data);
                $file_data["id"] = $_POST["upfiles_id"];
                $file_data["contents"] = $_POST["upfiles_content"];
                adminClass::UpdateUpfiles($file_data);
           }else{
    			$var = array("name","borrow_use","borrow_period","borrow_style","borrow_apr","borrow_contents","borrow_day","borrow_valid_time","borrow_nid","tender_account_min","tender_account_max","order","borrow_pawn_app","borrow_pawn_auth","borrow_pawn_formalities","borrow_pawn_type","borrow_pawn_value","borrow_pawn_time","borrow_pawn_xin","borrow_pawn_description");
    			$data = post_var($var);
    			
    			$_G['upimg']['code'] = "borrow";
    			$_G['upimg']['type'] = "diya";
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
    			
    		}
			if ($borrow_result['status']!=0){
				$msg = array("�ǳ�������ܽ����޸�","",$_A['query_url'].$_A['site_url']);
			}
			$result = borrowLoanClass::Update($data);
			if ($result>0){
			     if ($borrow_result["borrow_type"]=="roam"){
			         $msg = array("�޸ĳɹ�","",$_A['query_url']."/roam&status_nid=first");
			     }else{
				$msg = array("�޸ĳɹ�","",$_A['query_url']."/".$_A['query_type']);
                }
			}else{
				$msg = array($MsgInfo[$result]);
			}
		}else{
            require_once("borrow.type.php");  
			$data['borrow_nid'] = $_REQUEST['first_edit'];
			$result = borrowClass::GetView($data);
			if (!is_array($result)){
				$msg = array($MsgInfo[$result]);
			}else{
			    $type_result =  borrowTypeClass::GetTypeOne(array("nid"=>$result['borrow_type']));
				$_A['borrow_result'] = $result;
				$_A['borrow_type_result'] = $type_result;
			}
		}
	}elseif ($_REQUEST['cancel']!=""){
	   if ($_POST['borrow_nid']!=""){
	       
    		$data['borrow_nid'] = $_POST['borrow_nid'];
    		$data['cancel_remark'] = $_POST['cancel_remark'];
    		$data['cancel_status'] = 1;
          
    		$result = borrowClass::Cancel($data);
    		
    		if($result>0){
    			 $msg = array("���سɹ�","",$_A['query_url'].$_A['site_url']."/".$_A['query_type']);
    		 }else{
    			$msg = array($MsgInfo[$result]);
    		 }
    		 
    		//�������Ա������¼
    		$admin_log["user_id"] = $_G['user_id'];
    		$admin_log["code"] = "borrow";
    		$admin_log["type"] = "borrow";
    		$admin_log["operating"] = "cancel";
    		$admin_log["article_id"] = $result>0?$result:0;
    		$admin_log["result"] = $result>0?1:0;
    		$admin_log["content"] =  $msg[0];
    		$admin_log["data"] =  $data;
    		usersClass::AddAdminLog($admin_log);
      }
	}elseif ($_POST['borrow_apr']!=""){
		$data['id'] = $_POST['id'];
		$data['borrow_apr'] = $_POST['borrow_apr'];
		$result=borrowClass::ActionBorrowApr($data);
		if($result>0){
			$msg = array("�޸����ʳɹ�","",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result]);
		}
	}

}
//������
elseif ($_A['query_type'] == "manage" ){
    check_rank("borrow_manage");//���Ȩ��
}
//�����
elseif ($_A['query_type'] == "roam" ){
    check_rank("borrow_roam");//���Ȩ��
}
//ת�ñ�
elseif ($_A['query_type'] == "change" ){
    check_rank("borrow_change");//���Ȩ��
	if($_REQUEST['_type']!='' && $_REQUEST['_type']=='excel'){
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['status'] = $_REQUEST['status'];
		$data['username'] = $_REQUEST['username'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		borrowexcel::borrowChangeList($data);
		exit;
	}
}

//���긴��
elseif ($_A['query_type'] == "full" ){
     check_rank("borrow_full");//���Ȩ��
}

//����
elseif ($_A['query_type'] == "repay"){
     check_rank("borrow_repay");//���Ȩ��
	if ($_REQUEST['id']!=""){
		$data['id']=$_REQUEST['id'];
		$result = borrowClass::LateRepay($data);
		if ($result>0){
			$msg = array($MsgInfo["web_late_repay"],"",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result]);
		}
	}elseif ($_REQUEST['view']!=""){
		$data['borrow_nid']=$_REQUEST['view'];
		$result = borrowClass::GetOne($data);
		$_A['borrow_result']=$result;
	}
}

//Ͷ��
elseif ($_A['query_type'] == "tender" ){
     check_rank("borrow_tender");//���Ȩ��
	if ($_REQUEST['id']!=""){
		$_A['borrow_tender_result'] = borrowTenderClass::GetTenderOne(array("id"=>$_REQUEST['id']));
	}

}

//����
elseif ($_A['query_type'] == "recover"){
     check_rank("borrow_recover");//���Ȩ��
	if ($_REQUEST['id']!=""){
		$data['id']=$_REQUEST['id'];
		$result = borrowClass::LateRepay($data);
		if ($result>0){
			$msg = array($MsgInfo["web_late_repay"],"",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result]);
		}
	}elseif ($_REQUEST['view']!=""){
		$data['borrow_nid']=$_REQUEST['view'];
		$result = borrowClass::GetOne($data);
		$_A['borrow_result']=$result;
	}
}


elseif ($_A['query_type'] == "flag" ){
     check_rank("borrow_flag");//���Ȩ��
	require_once("borrow.flag.php");
	if (isset($_POST['name'])){
			$var = array("name","nid","remark","order");
			$data = post_var($var);
			$_G['upimg']['file'] = "upfile";
			$_G['upimg']['mask_status'] = 0;
			$_G['upimg']['code'] = "borrow";
			$_G['upimg']['type'] = "flag";
			$_G['upimg']['user_id'] = $_G['user_id'];
			$_G['upimg']['article_id'] = "0";
			$pic_result = $upload->upfile($_G['upimg']);
			if (is_array($pic_result)){
			$data["upfiles_id"] = $pic_result[0]['upfiles_id'];
			}
			if ($_POST['id']!=""){
				$data['id'] = $_POST['id'];
				$result = borrowflagClass::Update($data);
				if ($result>0){
					$msg = array($MsgInfo["borrow_flag_update_success"],"",$_A['query_url_all']);
				}else{
					$msg = array($MsgInfo[$result]);
				}
				$admin_log["operating"] = "update";
			}else{
				$result = borrowflagClass::Add($data);
				if ($result>0){
					$msg = array($MsgInfo["borrow_flag_add_success"],"",$_A['query_url_all']);
				}else{
					$msg = array($MsgInfo[$result]);
				}
				$admin_log["operating"] = "add";
			}
			
			//�������Ա������¼
			$admin_log["user_id"] = $_G['user_id'];
			$admin_log["code"] = "borrow";
			$admin_log["type"] = "flag";
			$admin_log["article_id"] = $result>0?$result:0;
			$admin_log["result"] = $result>0?1:0;
			$admin_log["content"] =  $msg[0];
			$admin_log["data"] =  $data;
			usersClass::AddAdminLog($admin_log);
		
	}elseif ($_REQUEST['borrow_nid']!=""){
		$data['borrow_nid'] = $_REQUEST['borrow_nid'];
		if ($_POST['flag']==""){
		$data['flag'] ="";
		}else{
		$data['flag'] = join(",",$_POST['flag']);
		}
		$result = borrowClass::Update($data);
		$msg = array("�޸ĳɹ�");
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "borrow";
		$admin_log["type"] = "borrow";
		$admin_log["operating"] = "flag";
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
	}elseif ($_REQUEST['edit']!=""){
		$data['id'] = $_REQUEST['edit'];
		$result = borrowflagClass::GetOne($data);
		if (is_array($result)){
			$_A["borrow_flag_result"] = $result;
		}else{
			$msg = array($MsgInfo[$result]);
		}
	
	}elseif($_REQUEST['del']!=""){
		$data['id'] = $_REQUEST['del'];
		$result = borrowflagClass::Delete($data);
		if ($result>0){
			$msg = array($MsgInfo["borrow_flag_del_success"],"",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result]);
		}
		
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "borrow";
		$admin_log["type"] = "flag";
		$admin_log["operating"] = "del";
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
	}
	
}


elseif ($_A['query_type'] == "tool" ){
     check_rank("borrow_tool");//���Ȩ��
	if ($_REQUEST['key']!=""){
		require_once("borrow.tool.php");
		$data['key'] = $_REQUEST['key'];
		$result = borrowtoolClass::Check($data);
		
		echo json_encode($result);
		
		exit;
	}
}


?>