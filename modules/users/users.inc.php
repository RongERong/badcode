<?php
/******************************
 * $File: users.inc.php
 * $Description: ���������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("users.vip.php");

//VIP����
if ($_U['query_type'] == "applyvip"){

	if (isset($_POST['submit'])){

		//�жϽ�������
		if (md5($_POST['paypassword'])!=$_G['user_result']['paypassword']){
			$msg = array("֧���������벻��ȷ");
		}else{
		     //�������vip����
            $var = array("remark","kefu_userid");
            $data = post_var($var);
			$data['user_id'] = $_G['user_id'];
			$data['years'] = 1;
			$result = usersClass::UsersVipApply($data);
            if ($result>0){
                $msg = array("Vip����ɹ�","","/?user&q=code/users/vip");
            }else{
                $msg = array($MsgInfo[$result],"","/vip/index.html");
            }
            /*
				
			*/	
		}	
	}
}



//VIP����
elseif ($_U['query_type'] == "vip_new"){
	if (isset($_POST['vip_type'])){
		$msg = check_valicode(1);
		if ($msg==""){
			$data['user_id'] = $_G['user_id'];
			$data['type'] =$_POST['vip_type'];
			$result = usersvipClass::UsersVipNew($data);
			if ($result===true){
				$msg = array($MsgInfo["usres_vip_apply_success"],"","/vip/index.html");
			}else{
				$msg = array($MsgInfo[$result],"","/vip/index.html");
			}	
		}
		echo $msg[0];
		exit;
	}else{
		$template = "users_info_vip.html";
	}
}
//������֤
elseif ($_U['query_type'] == "email_status"){
	$_U['site_name'] = "������֤";
	if (isset($_POST['email']) && $_POST['email']!="" ){
		$data['user_id'] = $_G['user_id'];
		$data['email'] = $_POST['email'];
		$result = usersClass::CheckEmail($data);
		if ($result==false){
			$result = usersClass::UpdateEmail($data);
			if ($result == false){
				$msg = array($result);	
			}else{
				$data['username'] = $_G['user_result']['username'];
				$data['webname'] = $_G['system']['con_webname'];
				$data['title'] = "ע���ʼ�ȷ��";
				$data['msg'] = RegEmailMsg($data);
				$data['type'] = "reg";
				if (isset($_SESSION['sendemail_time']) && $_SESSION['sendemail_time']+60*2>time()){
					$msg = array("��2���Ӻ��ٴ�����","",$url);
				}else{
					$result = usersClass::SendEmail($data);
					if ($result==true) {
						$_SESSION['sendemail_time'] = time();
						$msg = array("������Ϣ�Ѿ����͵��������䣬��ע����ա�","",$url);
					}
					else{
						$msg = array("����ʧ�ܣ��������Ա��ϵ��","",$url);
					}
				}
			}
		}else{
			$msg = array("��������д�������Ѿ�����","",$url);	
		}
	}
}
	
elseif ($_U['query_type'] == "protection"){
	if ((isset($_POST['type']) && $_POST['type'] == 1)){
		if (  $_G['user_result']['answer']=="" || $_POST['answer'] == $_G['user_result']['answer']){
			$_U['answer_type'] = 2;
		}else{
			$msg = array("����𰸲���ȷ","",$url);
		}
	}elseif (isset($_POST['type']) && $_POST['type'] == 2){
		$var = array("question","answer");
		$data = post_var($var);
		if ($data['answer']==""){
			$msg = array("����𰸲���Ϊ��","",$url);	
		}else{
			$data['user_id'] = $_G['user_id'];
			$result = usersClass::UpdateUsersInfo($data);
			if ($result == false){
				$msg = array($result);	
			}else{
				$msg = array("���뱣���޸ĳɹ�","",$url);	
			}
		}
	}
}


//������������
elseif ($_U['query_type'] == "paypwd"){
	if (isset($_POST['oldpassword'])){
		if ($_G['user_result']['paypassword'] == "" && md5($_POST['oldpassword']) !=$_G['user_result']['password']){
			$msg = array("���벻��ȷ�����������ĵ�¼����","",$url);	
		}elseif ($_G['user_result']['paypassword'] != "" && md5($_POST['oldpassword']) != $_G['user_result']['paypassword']){
			$msg = array("���벻��ȷ�����������ľɽ�������","",$url);	
		}else{
			$data['user_id'] = $_G['user_id'];
			$data['paypassword'] = $_POST['newpassword'];
			$result = usersClass::UpdatePayPassword($data);
			if ($result>0){
				$msg = array("���������޸ĳɹ�","","index.php?user&q=code/users/paypwd");
			}else{
				$msg = array($MsgInfo[$result]);
			}
		}
	}		
}

//������������
elseif ($_U['query_type'] == "getpaypwd"){
	if(isset($_REQUEST['id']) && $_REQUEST['id']!=""){
		
		if (isset($_POST['paypwd']) && $_POST['paypwd']!=""){
			if ($_POST['paypwd']==""){
				$msg = array("���벻��Ϊ��","",$url);
			}elseif ($_POST['paypwd']!=$_POST['paypwd1']){
				$msg = array("�������벻һ��","",$url);
			}elseif($_SESSION['valicode']!=$_POST['valicode']){
				$msg = array("��֤�벻��ȷ");
			}else{
				$data['user_id'] = $_G['user_id'];
				$data['paypassword'] =$_POST['paypwd'];
				//$result = $user->UpdateUser($data);
				$result = usersClass::UpdatePayPassword($data);
				$msg = array("���������޸ĳɹ�","","/?user&q=code/users/paypwd");
			}
		}else{
			$id = urldecode($_REQUEST['id']);
			$_id = explode(",",authcode(trim($id),"DECODE"));
			$data['user_id'] = $_id[0];
			if ($_id[1]+60*60<time()){
				$msg = array("��Ϣ�ѹ��ڣ����������롣");
			}elseif ($data['user_id']!=$_G['user_id']){
				$msg = array("����Ϣ���������Ϣ���벻Ҫ�Ҳ���");
			}
			
		}
	}elseif ($_SESSION['valicode']==$_POST['valicode']){
		$data['user_id'] = $_G['user_id'];
		$data['username'] = $_G['user_result']['username'];
		$data['email'] = $_G['user_result']['email'];
		$data['webname'] = $_G['system']['con_webname'];
		$data['title'] = "��������ȡ��";
		$data['key'] = "getPayPwd";
		$data['query_url'] = "code/users/getpaypwd";
		$data['msg'] = GetPaypwdMsg($data);
		$data['type'] = "getpaypwd";
		$result = usersClass::SendEmail($data);
		$msg = array("��Ϣ�ѷ��͵��������䣬��ע�����","","/?user&q=code/users/paypwd");
	}
}

//��¼��������
elseif ($_U['query_type'] == "userpwd"){
	if (isset($_POST['oldpassword'])){
		if (md5($_POST['oldpassword']) != $_G['user_result']['password']){
			$msg = array("���벻��ȷ�����������ľ�����","",$url);	
		}else{
			$data['user_id'] = $_G['user_id'];
			$data['password'] = $_POST['newpassword'];
			$result = usersClass::UpdatePassword($data);
			if ($result == false){
				$msg = array($result);	
			}else{
				$msg = array("��¼�����޸ĳɹ�","",$url);	
			}
		}
	}
}

//��Ϊ����
elseif ($_U['query_type'] == "raddfriend"){
		if (isset($_POST['type'])){
			$data['type'] = $_POST['type'];
			$data['content'] = nl2br($_POST['content']);
			$data['friends_userid'] = $_POST['friends_userid'];
			$data['user_id'] = $_G['user_id'];
			$result = usersClass::RAddFriends($data);
			if ($result==false){
				$msg = array($result,"","/?user&q=code/users/myfriend");	
			}else{
				$msg = array("�ɹ���Ӻ��ѳɹ�","","/?user&q=code/users/myfriend");	
			}
		}else{
			$result = usersClass::GetUsers(array("username"=>urldecode($_REQUEST['username'])));
			if ($result==false){
				echo "<script>alert('�Ҳ������û����벻Ҫ�Ҳ���');location.href='/?user'</script>";
				exit;
			}elseif ($result['user_id']==$_G['user_id']){
				echo "<script>alert('���ܼ��Լ�Ϊ����');location.href='/?user';</script>";
				exit;
			}else{
				echo "<form method='post' action='/?user&q=code/users/raddfriend'>";
				echo "<input type='hidden' name='friends_userid' value='{$result['user_id']}'>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;���ͣ�<select name='type'>";
				foreach ($_G["_linkages"]['friends_type'] as $key => $value){
					echo "<option value='{$value['value']}'>{$value['name']}</option>";
				}
				echo "</select></div><div align='left'><br>&nbsp;&nbsp;&nbsp;���ݣ�<textarea rows='5' cols='30' name='content'></textarea></div>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;<input type='submit' value='ȷ�����'></div>";
				echo "</form>";
				exit;
			}
		}
	}
	
elseif ($_U['query_type'] == "addfriend"){
	if (isset($_POST['type_id'])){
		$data['type_id'] = $_POST['type_id'];
		$data['type'] = $_REQUEST['type'];
		$data['status'] = 1;
		$data['content'] = nl2br($_POST['content']);
		$data['friends_username'] = urldecode($_REQUEST['username']);
		$data['user_id'] = $_G['user_id'];
		$result = usersClass::AddFriends($data);
		if ($result>0){
			$msg = array("��Ӻ��ѳɹ�","","/?user&q=code/users/myfriend");	
		}else{	
			$msg = array($MsgInfo[$result]);	
		
		}
	}else{
		$username = urldecode($_REQUEST['username']);
		$result = usersClass::GetUsers(array("username"=>$username));
		if ($result==false){
			$_U['ajax_msg'] = "�Ҳ������û����벻Ҫ�Ҳ���";
		}elseif ($result['user_id']==$_G['user_id']){
			$_U['ajax_msg'] = "���ܼ��Լ�Ϊ����";
		}
		$temlate_dir = "themes/{$_G['system']['con_template']}_member";
		$magic->template_dir = $temlate_dir;
		$template = "users_info_ajax.html";
	}
}
	
//�ٱ�
elseif ($_U['query_type'] == "addrebut"){
	if (isset($_POST['contents'])){
		$data['type_id'] = $_POST['type_id'];
		$data['contents'] = nl2br($_POST['contents']);
		$data['rebut_userid'] = $_POST['rebut_userid'];
		$data['user_id'] = $_G['user_id'];
		$result = usersClass::AddRebut($data);
		if ($result==false){			
			$msg = array($result,"","");	
		}else{		
			$msg = array("��л��ľٱ������ǽ���һʱ����������","","");	
		}
	}else{
		$result = usersClass::GetUsers(array("username"=>urldecode($_REQUEST['username'])));
		$username = urldecode($_REQUEST['username']);
		if ($result==false){
			echo "<script>alert('�Ҳ������û����벻Ҫ�Ҳ���');location.href='/?user'</script>";
			exit;
		}elseif ($result['user_id']==$_G['user_id']){
			echo "<script>alert('���ܾٱ��Լ�');</script>";
			exit;
		}else{
			echo "<div style='line-height:30px; text-align:left'>* ��վ�������������͹۵ط�ӳ������������ʵ���,�Թ�ͬά��һ�����ź͹�ƽ�Ľ��������<form method='post' action='/?user&q=code/users/addrebut'>";
			echo "<div align='left'>&nbsp;&nbsp;&nbsp;��Ҫ�ٱ����û���{$username}<input type='hidden' name='rebut_userid' value='{$result['user_id']}'></div>";
			echo "<div align='left'>&nbsp;&nbsp;&nbsp;���ͣ�<select name='type_id'>";
			foreach ($_G["_linkages"]['rebut_type'] as $key => $value){
				echo "<option value='{$value['value']}'>{$value['name']}</option>";
			}
			echo "</select></div><div align='left'>&nbsp;&nbsp;&nbsp;���ݣ�<textarea rows='2' cols='20' name='contents'></textarea></div>";
			echo "<div align='left'>&nbsp;&nbsp;&nbsp;<input type='submit' value='ȷ ��'></div>";
			echo "</form></div>";
			exit;
		}
	}
}
elseif ($_U['query_type'] == "delfriend"){
	$data['user_id'] = $_G['user_id'];
	$data['friends_userid'] = $_REQUEST['friends_userid'];
	$result = usersClass::DelFriends($data);
	if ($result==true){
		$msg = array("ɾ�����ѳɹ�","","/?user&q=code/users/myfriend");
	}else{
		$msg = array("ɾ������ʧ��","","/?user&q=code/users/myfriend");
	}
}


elseif ($_U['query_type'] == "delinvite"){
	$data['user_id'] = $_G['user_id'];
	$data['friends_userid'] = $_REQUEST['friends_userid'];
	$result = usersClass::DeleteFriendsInvite($data);
	if ($result==true){
		$msg = array("ɾ���ɹ�","","/?user&q=code/users/myfriend");
	}else{
		$msg = array("ɾ��ʧ��","","/?user&q=code/users/myfriend");
	}
}

//�������
elseif ($_U['query_type'] == "reginvite"){
	$_U['user_inviteid'] =  Key2Url($_G['user_id'],"reg_invite");
	$_U['user_manage'] = usersClass::GetUserManageOne(array("user_id"=>$_G['user_id']));
}	

//�������
elseif ($_U['query_type'] == "add_impression"){
	$data['user_id'] = $_G['user_id'];
	$data['to_userid'] = $_POST['to_userid'];
	$data['impression'] = iconv("UTF-8", "GB2312",$_POST['impression']);
	$result = usersClass::AddImpression($data);
	if ($result>0){
		echo $result;
	}else{
		echo $MsgInfo[$result];
	}
	exit;
}	


//�������
elseif ($_U['query_type'] == "get_impression"){
	$data['to_userid'] = $_POST['to_userid'];
	$data['id'] = $_POST['id'];
	$result = usersClass::GetImpression($data);
	if ($result!=false){
	$display = '<span title="'.$result["username"].' '.date("Y-m-d",$result['addtime']).' ��TA������">'.$result["impression"].'</span>';
	}
	echo $display;
	exit;
}	

//��Ӷ���Ϣ
elseif ($_U['query_type'] == "add_messages"){
	$data['send_userid'] = $_G['user_id'];
	$data['username'] = $_POST['username'];
	$data['contents'] = iconv("UTF-8", "GB2312",$_POST['contents']);
	$result = usersClass::AddMessages($data);
	if ($result>0){
		echo $result;
	}else{
		echo $MsgInfo[$result];
	}
	exit;
}	


//��������
elseif ($_U['query_type'] == "remind"){
	require_once(ROOT_PATH."modules/remind/remind.class.php");
	$_result = "";
	
	if (isset($_POST['type']) && $_POST['type']==1){
		
		foreach ($_POST as $key => $value){
			$_message = explode("message_",$key);
			if ($_message[0] == ""){
				$_result[$_message[1]]['message'] = $_POST[$key];
			}
			$_email = explode("email_",$key);
			if ($_email[0] == ""){
				$_result[$_email[1]]['email'] = $_POST[$key];
			}
			$_phone = explode("phone_",$key);
			if ($_phone[0] == ""){
				$_result[$_phone[1]]['phone'] = $_POST[$key];
			}
			
		}
		
		if ($_result!=""){
			$data['remind'] = serialize($_result);
		}else{
			$data['remind'] = "";
		}
		$data['user_id'] = $_G['user_id'];
		
		$result = remindClass::ActionRemindUser($data);
		if ($result!==true){
			$msg = array($result,"",$_U['query_url']);
		}else{
			$msg = array("�޸ĳɹ�");
		}
	}else{
		$data['user_id'] = $_G['user_id'];
		$result = remindClass::GetLists($data);
		if ($result!="" && is_array($result)){
			$_U['remind_list'] = $result;
		}else{
			$msg = array($result,"",$_U['query_url']);
		}
	}
}	
elseif ($_U['query_type'] == "addcareuser"){
	if($_REQUEST['id']==''){
		$data['user_id'] = $_G['user_id'];
		$data['care_user_id'] = $_REQUEST['care_user'];
		$result = userscareClass::AddUserCare($data);
		if ($result==true){
			$msg = array("��ע�û��ɹ�","","/?user&q=code/borrow/yonghu");
		}else{
			$msg = array("���ѹ�ע�����û�","","/?user&q=code/borrow/yonghu");
		}
	}else{
		$data['id'] = $_REQUEST['id'];
		$result = userscareClass::DeleteUserCare($data);
		if ($result==true){
			$msg = array("ɾ���û��ɹ�","","/?user&q=code/borrow/yonghu");
		}else{
			$msg = array("����ɾ���û�","","/?user&q=code/borrow/yonghu");
		}
	}	
}
//�ٱ�
elseif ($_U['query_type'] == "addrebut"){
	if (isset($_POST['contents'])){
		$data['type_id'] = $_POST['type_id'];
		$data['contents'] = nl2br($_POST['contents']);
		$data['rebut_userid'] = $_POST['rebut_userid'];
		$data['user_id'] = $_G['user_id'];
		$result = usersClass::AddRebut($data);
		if ($result==false){
			$msg = array($result,"","");	
		}else{
			$msg = array("��л��ľٱ������ǽ���һʱ����������","","");	
		}
	}else{
		$result = usersClass::GetUsers(array("username"=>urldecode($_REQUEST['username'])));
		
		if ($result==false){
			echo "<script>alert('�Ҳ������û����벻Ҫ�Ҳ���');location.href='/?user'</script>";
			exit;
		}elseif ($result['user_id']==$_G['user_id']){
			echo "<script>alert('���ܾٱ��Լ�');</script>";
			exit;
		}else{
			echo "<div style='line-height:30px; text-align:left'>* ��վ�������������͹۵ط�ӳ������������ʵ���,�Թ�ͬά��һ�����ź͹�ƽ�Ľ��������<form method='post' action='/?user&q=code/users/addrebut'>";
			echo "<div align='left'>&nbsp;&nbsp;&nbsp;��Ҫ�ٱ����û���{$_REQUEST['username']}<input type='hidden' name='rebut_userid' value='{$result['user_id']}'></div>";
			echo "<div align='left'>&nbsp;&nbsp;&nbsp;���ͣ�<select name='type_id'>";
			foreach ($_G["_linkages"]['rebut_type'] as $key => $value){
				echo "<option value='{$value['value']}'>{$value['name']}</option>";
			}
			echo "</select></div><div align='left'>&nbsp;&nbsp;&nbsp;���ݣ�<textarea rows='2' cols='20' name='contents'></textarea></div>";
			echo "<div align='left'>&nbsp;&nbsp;&nbsp;<input type='submit' value='ȷ ��'></div>";
			echo "</form></div>";
			exit;
		}
	}
}elseif ($_U['query_type'] == "images"){
	if (isset($_POST['submit'])){
		$data['user_id'] = $_G['user_id'];
		
		$_G['upimg']['code'] = "approve";
		$_G['upimg']['type'] = "image";
		$_G['upimg']['user_id'] = $data["user_id"];
		$_G['upimg']['article_id'] = $data["user_id"];
			
		$_G['upimg']['file'] = "images";
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=false){
			$data["images"] = $pic_result[0]["upfiles_id"];
		}
		$result = usersClass::AddUserImages($data);
		if ($result==false){
			$msg = array($result,"","");	
		}else{
			header("Location: /?user&q=code/borrow/jrsh&type=avatar"); 
			//$msg = array("��ӳɹ�","","/?user&q=code/borrow/jrsh&type=list");	
		}
	}	
}elseif ($_U['query_type'] == "delimages"){
	if (isset($_REQUEST['id'])){
		$data['id'] = $_REQUEST['id'];	
		
		$result = usersClass::DeleteUserImages($data);
		if ($result==false){
			$msg = array($result,"","");	
		}else{
			header("Location: /?user&q=code/borrow/jrsh&type=avatar"); 
			//$msg = array("ɾ���ɹ�","","/?user&q=code/borrow/jrsh&type=avatar");	
		}
	}	
}elseif ($_U['query_type'] == "manage"){	
	if (isset($_POST['submit'])){
		$var = array("realname","card_id","sex","rating_birthday_year","rating_birthday_mouth","rating_birthday_day","edu","address","email","linkman","linktel","resume");		
        $data = post_var($var);
		foreach($data as $key => $value){
			$data[$key] = iconv("UTF-8","gb2312",$data[$key]);
		}
		$data['user_id'] = $_G['user_id'];
		$_result = usersClass::GetUserManageOne($data);
		if($_result==true){
			$result = usersClass::UpdateUserManage($data);
		}else{
			$result = usersClass::AddUserManage($data);
		}
		if ($result==false){
			echo $result;exit;	
		}else{
			echo 1;exit;
		} 
		
	}	
}


if ($template==""){
$template = "users_info.html";
}
?>
