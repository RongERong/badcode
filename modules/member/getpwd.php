<?
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���


if($_REQUEST['type']=="pwd"){

	if(isset($_REQUEST['id'])){
		$id = urldecode($_REQUEST['id']);
		$data = explode(",",authcode(trim($id),"DECODE"));
		$user_id = $data[0];
		$start_time = $data[1];
		if ($user_id==""){
			$updatepwd_msg = "���Ĳ������������Ҳ���";
		}elseif (time()>$start_time+60*60){
			$updatepwd_msg = "�������Ѿ����ڣ�����������";
		}else{
			$result = usersClass::GetUsers(array("user_id"=>$user_id));
			if ($result == false){
				$updatepwd_msg = "���Ĳ������������Ҳ���";
			}else{
				$_U['user_result'] =  $result;
				if ($_POST['password']!=""){
					if ($_POST['password']!=$_POST['password1']){
						$pwd_msg = "�������벻һ��";
					}else{
						$_data['user_id'] = $user_id;
						$_data['password'] = $_POST['password'];
						$result = usersClass::UpdatePassword($_data);
						if ($result>0){
							$updatepwd_msg = "�����޸ĳɹ�";
						}else{
							$pwd_msg = $MsgInfo[$result];
						}
					}
					$_U['pwd_msg'] =  $pwd_msg;
				}
			}
		}
	}else{
		$updatepwd_msg = "���Ĳ������������Ҳ���";
	}
	if ($updatepwd_msg !=""){
		echo "<script>alert('{$updatepwd_msg}');location.href='/'</script>";
	}
}


if($_REQUEST['type']=="paypwd"){
	if(isset($_REQUEST['id'])){
		$id = urldecode($_REQUEST['id']);
		$data = explode(",",authcode(trim($id),"DECODE"));
		$user_id = $data[0];
		$start_time = $data[1];
		if ($user_id==""){
			$updatepwd_msg = "���Ĳ������������Ҳ���";
		}elseif (time()>$start_time+60*60){
			$updatepwd_msg = "�������Ѿ����ڣ�����������";
		}else{
			$result = usersClass::GetUsers(array("user_id"=>$user_id));
			if ($result == false){
				$updatepwd_msg = "���Ĳ������������Ҳ���";
			}else{
				$_U['user_result'] =  $result;
				if ($_POST['password']!=""){
					if ($_POST['password']!=$_POST['password1']){
						$pwd_msg = "�������벻һ��";
					}else{
						$_data['user_id'] = $user_id;
						$_data['password'] = $_POST['password'];
						$result = usersClass::UpdatePayPassword($_data);
						if ($result>0){
							$updatepwd_msg = "�����޸ĳɹ�";
						}else{
							$pwd_msg = $MsgInfo[$result];
						}
					}
					$_U['pwd_msg'] =  $pwd_msg;
				}
			}
		}
	}else{
		$updatepwd_msg = "���Ĳ������������Ҳ���";
	}
	if ($updatepwd_msg !=""){
		echo "<script>alert('{$updatepwd_msg}');location.href='/'</script>";
	}
}

elseif(isset($_POST['email'])){
	$getpwd_msg = "";
	$var = array("email","valicode");
	$data = post_var($var);
	$msg = check_valicode();
	
	if ($msg!=""){
		$getpwd_msg = "��֤�벻��ȷ";
		$msg = "";

	}elseif ($data['email']==""){

		$getpwd_msg = "�����ַ����Ϊ��";
	}elseif ($data['valicode']==""){
		$getpwd_msg = "��֤�벻��Ϊ��";
	}else{
		$result = usersClass::GetUsers($data);
		if ($result==false){
			$getpwd_msg = "���䲻����";
		}else{
			$data['user_id'] = $result['user_id'];
			$data['email'] = $result['email'];
			$data['webname'] = $_G['system']['con_webname'];
			if ($_POST['pwdtype']==1){
				$data['title'] = "�û�ȡ�ص�¼����";
				$data['msg'] = GetpwdMsg($data);
			}else{
				$data['title'] = "�û�ȡ��֧������";
				$data['msg'] = GetPaypwdMsg($data);
			}
			$data['type'] = "reg";
			if (isset($_SESSION['sendpwd_time']) && $_SESSION['sendpwd_time']+60*2>time()){
				$getpwd_msg =  "��2���Ӻ��ٴ�����";
			}else{
				$result = usersClass::SendEmail($data);
				if ($result) {
					$_SESSION['sendpwd_time'] = time();
					$getpwd_msg =  "��Ϣ�ѷ��͵�{$data['email']}����ע�������������ʼ�";
					echo "<script>alert('{$getpwd_msg}');location.href='/'</script>";
				}
				else{
					$getpwd_msg =  "����ʧ�ܣ��������Ա��ϵ";
				}
			}
		}
	}
	$_U['getpwd_msg'] = $getpwd_msg;
}

elseif($_REQUEST['type']=="phone"){
  if(isset($_POST['phone'])){
		$getpwd_msg = "";
		$var = array("phone","pwdtype","valicode");
		$data = post_var($var);
		$msg = check_valicode();
		if ($msg!=""){
			$getpwd_msg = "��֤�벻��ȷ";
			$msg = "";
		}
		elseif ($data['phone']==""){
			$getpwd_msg = "�ֻ����벻��Ϊ��";
		}elseif ($data['valicode']==""){
			$getpwd_msg = "��֤�벻��Ϊ��";
		}else{
			$_data['phone'] = $data['phone'];
			$_data['phone_status'] = 1;
			$result = usersClass::GetUsersInfo($_data);
			if ($result==false){
				$getpwd_msg = "�ֻ������ڻ��ߴ��ֻ���û��֤";
			}else{
				if (isset($_SESSION['sendpwd_time']) && $_SESSION['sendpwd_time']+60*2>time()){
					$getpwd_msg =  "��2���Ӻ��ٴ�����";
				}else{
					//���Ͷ���
					require_once("modules/approve/approve.class.php");
					$code =rand(1000,9999);
					if ($data['pwdtype']==1){
						$send_sms['contents'] ="��ȡ�ص�¼������֤��Ϊ��{$code}"."[{$_G['system']['webname']}]";
						$type = 'getpwd';
					}else{
						$send_sms['contents'] ="��ȡ��֧��������֤��Ϊ��{$code}"."[{$_G['system']['webname']}]";
						$type = 'getpaypwd';
					}
					$send_sms['user_id'] = $result['user_id'];
					$send_sms['code'] = $code;
					$send_sms['type'] = $type;
					$result = approveClass::SendSMS($send_sms);
					echo "<script>location.href='/?user&q=getpwd&type=phone_code&phone={$data['phone']}&pwdtype={$data['pwdtype']}'</script>";
				}
			}
		}
		$_U['getpwd_msg'] = $getpwd_msg;
	}
}



elseif($_REQUEST['type']=="phone_code"){

	if(isset($_POST['code'])){
		$getpwd_msg = "";
		$var = array("code");
		$data = post_var($var);
		if ($_REQUEST['phone']==""){
			$getpwd_msg = "�ֻ����벻��Ϊ��";
		}elseif ($data['code']==""){
			$getpwd_msg = "��֤�벻��Ϊ��";
		}else{
			$_data['phone'] = $data['phone'];
			$_data['phone_status'] = 1;
			$result = usersClass::GetUsersInfo($_data);
			if ($result==false){
				$getpwd_msg = "�ֻ������ڻ��ߴ��ֻ���û��֤";
			}else{
				$_data['user_id'] = $result['user_id'];
				$_data['phone'] = $_REQUEST['phone'];
				$_data['code'] = $data['code'];
				if ($_REQUEST['pwdtype']==1){
					$_data['type'] = "getpwd";
				}else{
					$_data['type'] = "getpaypwd";
				}
				require_once("modules/approve/approve.class.php");
				$result = approveClass::CheckSmsCode($_data);
				
				if ($result>0){
					$active_id = urlencode(authcode($result['user_id'].",".time(),"ENCODE"));
					if ($_REQUEST['pwdtype']==1){
						$_url = "/?user&q=getpwd&type=pwd&id={$active_id}";
					}else{
						$_url = "/?user&q=getpwd&type=paypwd&id={$active_id}";
					}
					echo "<script>location.href='{$_url}'</script>";
				}else{
					$getpwd_msg = "��֤�벻��ȷ";
				}
			}
		}
		$_U['getpwd_msg'] = $getpwd_msg;
	}
}




elseif($_REQUEST['type']=="question"){

	if(isset($_POST['username'])){
		$getpwd_msg = "";
		$var = array("username","valicode");
		$data = post_var($var);
		$msg = check_valicode();
		if ($msg!=""){
			$getpwd_msg = "��֤�벻��ȷ";
			$msg = "";
		}
		elseif ($_REQUEST['username']==""){
			$getpwd_msg = "�û�������Ϊ��";
		}elseif ($data['valicode']==""){
			$getpwd_msg = "��֤�벻��Ϊ��";
		}else{
			$_data['username'] = $data['username'];
			$result = usersClass::GetUsers($_data);
			if ($result==false){
				$getpwd_msg = "�û�������";
			}else{
				$__data['user_id'] = $result['user_id'];
				$result = usersClass::GetUsersInfo($__data);
				if ($result['question']!="" && $result['answer']!=""){
					$_url = "/?user&q=getpwd&type=answer&username={$data['username']}&pwdtype={$_POST['pwdtype']}&question=".$result['question']."";
					echo "<script>location.href='{$_url}'</script>";
				}else{
					$getpwd_msg = "�㻹û�趨��ȫ����";
				}
			}
		}
		$_U['getpwd_msg'] = $getpwd_msg;
	}
}




elseif($_REQUEST['type']=="answer"){

	if(isset($_POST['answer'])){
		$getpwd_msg = "";
		$var = array("answer");
		$data = post_var($var);
		if ($_REQUEST['username']==""){
			$getpwd_msg = "�û�������Ϊ��";
		}else{
			$_data['username'] = $_REQUEST['username'];
			$result = usersClass::GetUsers($_data);
			if ($result==false){
				$getpwd_msg = "�û�������";
			}else{
				$__data['user_id'] = $result['user_id'];
				$result = usersClass::GetUsersInfo($__data);
				if ($result['answer']==$data['answer']){
					$active_id = urlencode(authcode($result['user_id'].",".time(),"ENCODE"));
					if ($_REQUEST['pwdtype']==1){
						$_url = "/?user&q=getpwd&type=pwd&id={$active_id}";
					}else{
						$_url = "/?user&q=getpwd&type=paypwd&id={$active_id}";
					}
					echo "<script>location.href='{$_url}'</script>";
				}else{
					$getpwd_msg = "�𰸲���ȷ";
				}
			}
		}
		$_U['getpwd_msg'] = $getpwd_msg;
	}
}

$title = 'ȡ������';
$template = 'user_getpwd.html';

?>