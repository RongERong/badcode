<?
/******************************
 * $File: account.php
 * $Description: �ʽ�ģ���̨�����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$_A['list_purview']["account"]["name"] = "�ʽ����";
$_A['list_purview']["account"]["result"]["account_list"] = array("name"=>"�ʽ��˺Ź���","url"=>"code/account/list");
$_A['list_purview']["account"]["result"]["account_log"] = array("name"=>"�ʽ��¼","url"=>"code/account/log");
$_A['list_purview']["account"]["result"]["account_bank"] = array("name"=>"�˺Ź���","url"=>"code/account/bank");
$_A['list_purview']["account"]["result"]["account_recharge"] = array("name"=>"��ֵ����","url"=>"code/account/recharge");
$_A['list_purview']["account"]["result"]["account_cash"] = array("name"=>"���ֹ���","url"=>"code/account/cash");
$_A['list_purview']["account"]["result"]["account_recharge_new"] = array("name"=>"��ӳ�ֵ","url"=>"code/account/recharge_new");
$_A['list_purview']["account"]["result"]["account_deduct"] = array("name"=>"�۳�����","url"=>"code/account/deduct");
$_A['list_purview']["account"]["result"]["account_web"] = array("name"=>"��վ����","url"=>"code/account/web");
$_A['list_purview']["account"]["result"]["account_users"] = array("name"=>"�û�����","url"=>"code/account/users");
//$_A['list_purview']["account"]["result"]["account_users_count"] = array("name"=>"�û��ʽ�ͳ��","url"=>"code/account/users_count");
//$_A['list_purview']["account"]["result"]["account_web_count"] = array("name"=>"��վ�ʽ�ͳ��","url"=>"code/account/web_count");
$_A['list_purview']["account"]["result"]["account_payment"] = array("name"=>"֧����ʽ","url"=>"code/account/payment");
$_A['list_purview']["account"]["result"]["account_fee"] = array("name"=>"�ʽ����","url"=>"code/account/fee");
require_once("account.class.php");
/**
 * �������Ϊ�յĻ�����ʾ���е��ļ��б�
**/
if(file_exists(ROOT_PATH."modules/account/account.".$_A['query_type'].".admin.php")){
    require_once(ROOT_PATH."modules/account/account.".$_A['query_type'].".admin.php");
}

elseif ($_A['query_type'] == "list"){
    check_rank("account_list");//���Ȩ��
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['username'] = $_REQUEST['username'];
		accountexcel::AccountList($data);
		exit;
	}
	
}


/**
 * ֧����ʽ
**/
elseif ($_A['query_type'] == "payment"){
	check_rank("account_payment");//���Ȩ��
	require_once("payment.php");
}


/**
 * ��վ����
**/
elseif ($_A['query_type'] == "web"){
	check_rank("account_web");//���Ȩ��
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$data['action'] = $_REQUEST['action'];
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['type'] = $_REQUEST['_type'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		$data['username'] = $_REQUEST['username'];
		accountexcel::WebLog($data);
		exit;
	}
	if ($_REQUEST['action']=="account" && $_REQUEST['_type']=="excel"){
		$data['action'] = $_REQUEST['action'];
		$data['page'] = $_REQUEST['page'];
		$data['type'] = $_REQUEST['type'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		accountexcel::WebListLog($data);
		exit;
	}
	
	if ($_REQUEST['action']=="repay" && $_REQUEST['_type']=="excel"){
		$data['epage'] = $_REQUEST['epage'];
		$data['page'] = $_REQUEST['page'];
		$data['type'] = $_REQUEST['type'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		$data['borrow_status'] = $_REQUEST['borrow_status'];
		$data['order'] = $_REQUEST['order'];
		$data['recover_status'] = $_REQUEST['recover_status'];
		$data['showtype'] = $_REQUEST['showtype'];
		accountexcel::RecoverListLog($data);
		exit;
	}
}

/**
 * �û�����
**/
elseif ($_A['query_type'] == "users"){
	
	check_rank("account_users");//���Ȩ��
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['type'] = $_REQUEST['_type'];
		$data['username'] = $_REQUEST['username'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		accountexcel::UsersLog($data);
		exit;
	}
}

elseif ($_A['query_type'] == "users_count"){
    check_rank("account_users_count");//���Ȩ��
}
elseif ($_A['query_type'] == "web_count"){
    check_rank("account_web_count");//���Ȩ��
}

/**
 * ��ֵ��¼
**/
elseif ($_A['query_type'] == "recharge"){
	check_rank("account_recharge");//���Ȩ��
	
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['username'] = $_REQUEST['username'];
		$data['status'] = $_REQUEST['status'];
		accountexcel::RechargeLog($data);
		exit;
	}elseif ($_REQUEST['view']!=""){
		if (isset($_POST['nid'])){
			$var = array("nid","status","verify_remark","verify_content");
			$data = post_var($var);
			$data['verify_userid'] = $_G['user_id'];
			$data['verify_time'] = time();
			$result = accountClass::VerifyRecharge($data);
			if ($result >0 ){
				$msg = array($MsgInfo["account_reacharge_verify_success"],"",$_A['query_url']."/recharge");
			}else{
				$msg = array($MsgInfo[$result]);
			}
			
			//�������Ա������¼
			$admin_log["user_id"] = $_G['user_id'];
			$admin_log["code"] = "account";
			$admin_log["type"] = "recharge";
			$admin_log["operating"] = "verify";
			$admin_log["article_id"] = $result>0?$result:0;
			$admin_log["result"] = $result>0?1:0;
			$admin_log["content"] =  $msg[0];
			$admin_log["data"] =  $data;
			usersClass::AddAdminLog($admin_log);
			
		}else{
			$data['id'] = $_REQUEST['view'];
			$_A['account_recharge_result'] = accountClass::GetRecharge($data);
		}
	}
}
// �ʽ������������
elseif($_A['query_type']=='batch_recharge'){
    if(!empty($_POST['ids']) && !empty($_POST['remark'])){
        $ids = explode(',',$_POST['ids']);
        foreach($ids as $key=>$value){
            if(empty($value)){
                unset($ids[$key]);
            }
        }
        $data = $mysql->db_fetch_arrays('select nid from {account_recharge} where id in ('.implode(',',$ids).')');
        $remark = iconv('utf-8','gbk',trim($_POST['remark']));
        foreach($data as $key=>$value){
            $recharge_data = ['nid'=>$value['nid'],'status'=>1,'verify_remark'=>$remark,'verify_content'=>$remark];
            accountClass::VerifyRecharge($recharge_data);
        }
        echo 'ok';
        exit;
    }else{
        echo 'wrong';
        exit;
    }
}
/**
 * �ʽ�ʹ�ü�¼
**/
elseif ($_A['query_type'] == "log"){
	check_rank("account_log");//���Ȩ��
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['type'] = $_REQUEST['_type'];
		$data['username'] = $_REQUEST['username'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		accountexcel::AccountLogList($data);
		exit;
	}
}
	/**
 * �˺Ź���
**/
elseif ($_A['query_type'] == "bank"){
	check_rank("account_bank");//���Ȩ��
	if ($_POST['type']=="user_id"){
		$var = array("username","user_id","email");
		$data = post_var($var);
		$result = usersClass::GetUserid($data);
		if ($result>0){
			echo "<script>location.href='{$_A['query_url_all']}&user_id={$result}'</script>";
		}else{
			$msg = array($MsgInfo[$result],"",$_A['query_url_all']);
		}
	}
	elseif ($_POST['type']=="update"){
		$var = array("id","user_id","province","city","account","bank","branch");
		$data = post_var($var);
		$result = accountClass::UpdateUsersBank($data);
		if ($result>0){
			$msg = array($MsgInfo["account_bank_users_update_success"],"",$_A['query_url_all']);
		}else{
			$msg = array($MsgInfo[$result],"",$_A['query_url_all']);
		}
		
		//�������Ա������¼
		$admin_log["user_id"] = $_G['user_id'];
		$admin_log["code"] = "account";
		$admin_log["type"] = "bank";
		$admin_log["operating"] = "users";
		$admin_log["article_id"] = $result>0?$result:0;
		$admin_log["result"] = $result>0?1:0;
		$admin_log["content"] =  $msg[0];
		$admin_log["data"] =  $data;
		usersClass::AddAdminLog($admin_log);
	}
	elseif ($_REQUEST['user_id']!=""){
		$data['user_id'] = $_REQUEST['user_id'];
		$data['id'] = $_REQUEST['id'];		
		$result = accountClass::GetUsersBankOne($data);
		if (is_array($result)){
			$_A['account_bank_result'] = $result;
		}else{
			$msg = array($MsgInfo[$result],"",$_A['query_url_all']);
		}
	}
	elseif ($_REQUEST['action']=="new" || $_REQUEST['action']=="edit" ){
		if (isset($_POST['name'])){
			$var = array("name","status","nid","litpic","cash_money","reach_day");
			$data = post_var($var);
			if ($_REQUEST['id']!=""){
				$data['id'] = $_REQUEST['id'];
				$result = accountClass::UpdateBank($data);
			}else{
				$result = accountClass::AddBank($data);
			}
			
			if ($result >0 ){
				if ($_REQUEST['id']!=""){
					$msg = array($MsgInfo["account_bank_update_success"],"",$_A['query_url']."/bank&action=bank");
				}else{
					$msg = array($MsgInfo["account_bank_add_success"],"",$_A['query_url']."/bank&action=bank");
				}
			}else{
				$msg = array($MsgInfo[$result]);
			}
		
			//�������Ա������¼
			$admin_log["user_id"] = $_G['user_id'];
			$admin_log["code"] = "account";
			$admin_log["type"] = "bank";
			$admin_log["operating"] = $_A['query_type']=="bank_edit"?"edit":"new";
			$admin_log["article_id"] = $result>0?$result:0;
			$admin_log["result"] = $result>0?1:0;
			$admin_log["content"] =  $msg[0];
			$admin_log["data"] =  $data;
			usersClass::AddAdminLog($admin_log);
		}
		
		elseif ($_REQUEST['action']=="del"){
			$data['id'] = $_REQUEST['id'];
			$result = accountClass::DeleteBank($data);
			if ($result >0){
				$msg = array($MsgInfo["account_bank_del_success"],"","{$_A['query_url']}/bank&action=bank");
			}else{
				$msg = array($MsgInfo[$result]);
			}
			
			//�������Ա������¼
			$admin_log["user_id"] = $_G['user_id'];
			$admin_log["code"] = "account";
			$admin_log["type"] = " bank";
			$admin_log["operating"] = 'del';
			$admin_log["article_id"] = $result>0?$result:0;
			$admin_log["result"] = $result>0?1:0;
			$admin_log["content"] =  $msg[0];
			$admin_log["data"] =  $data;
			usersClass::AddAdminLog($admin_log);
		}
		
		elseif ($_REQUEST['id']!=""){
			$data['id'] = $_REQUEST['id'];
			$_A['account_bank_result'] = accountClass::GetBank($data);
		}
	
	}
}


/**
 * ���ּ�¼
**/
elseif ($_A['query_type'] == "cash"){
	check_rank("account_cash");//���Ȩ��
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$data['page'] = $_REQUEST['page'];
		$data['epage'] = $_REQUEST['epage'];
		$data['username'] = $_REQUEST['username'];
		$data['dotime1'] = $_REQUEST['dotime1'];
		$data['dotime2'] = $_REQUEST['dotime2'];
		$data['status'] = $_REQUEST['status'];
		accountexcel::CashLog($data);
		exit;
	}elseif ($_REQUEST['action']=="view"){
		if (isset($_POST['status'])){
			$msg = check_valicode();
			if ($msg==""){
				$var = array("status","credited","fee","verify_remark","credit_card_cash_fee");
				$data = post_var($var);
				$data['id'] = $_REQUEST['id'];
				$data['verify_userid'] = $_G['user_id'];
				$data['verify_time'] = time();
				
				$result = accountClass::VerifyCash($data);
				if ($result >0 ){
					$msg = array($MsgInfo["account_cash_verify_success"],"",$_A['query_url']."/cash");
				}else{
					$msg = array($MsgInfo[$result]);
				}
				
				//�������Ա������¼
				$admin_log["user_id"] = $_G['user_id'];
				$admin_log["code"] = "account";
				$admin_log["type"] = "cash";
				$admin_log["operating"] = "verify";
				$admin_log["article_id"] = $result>0?$result:0;
				$admin_log["result"] = $result>0?1:0;
				$admin_log["content"] =  $msg[0];
				$admin_log["data"] =  $data;
				usersClass::AddAdminLog($admin_log);
			}
		}else{
			$data['id'] = $_REQUEST['id'];
			$_A['account_cash_result'] = accountClass::GetCashOne($data);
		}
	}
}
/**
 * �������ִ���
 */
elseif ($_A['query_type']=='batch_cash'){
         $data = post_var(['status','verify_remark']);
         $data['verify_userid'] = $_G['user_id'];
		 $data['verify_time'] = time();
         $data['verify_remark'] = iconv('UTF-8','GBK',$data['verify_remark']);
         $ids = $_POST['ids'];
         if(empty($ids)){
            exit('no');
         }
         $ids = explode(',',$ids);
         foreach($ids as $value){
            if(!empty($value)){
                $data['id'] = $value;
                accountClass::VerifyCash($data);
            }
         }
         //�������Ա������¼
				$admin_log["user_id"] = $_G['user_id'];
				$admin_log["code"] = "account";
				$admin_log["type"] = "batch_cash";
				$admin_log["operating"] = "verify";
				$admin_log["article_id"] = $result>0?$result:0;
				$admin_log["result"] = $result>0?1:0;
				$admin_log["content"] =  $msg[0];
				$admin_log["data"] =  $data;
				usersClass::AddAdminLog($admin_log);
                exit('ok');
}
/**
 * �۳�����
**/
elseif ($_A['query_type'] == "deduct"){
	check_rank("account_deduct");//���Ȩ��
	if(isset($_POST['username']) && $_POST['username']!=""){
		$_data['username'] = $_POST['username'];
		$result = usersClass::GetUsers($_data);
		if ($result==false){
			$msg = array("�û���������");
		}elseif ($_POST['valicode']!=$_SESSION['valicode']){
			$msg = array("��֤�벻��ȷ");
		}elseif ($_POST['money']>$result['use_money']){  //�ж����  add  wdf 20120905
			$msg = array("����");
		}else{
			$data['user_id'] = $result['user_id'];
			$data['money'] = $_POST['money'];
			$data['type'] = $_POST['type'];
			$data['remark'] = $_POST['remark'];
			$result = accountClass::Deduct($data);
			if ($result !== true){
				$msg = array($result);
			}else{
				$msg = array("�����ѳɹ��۳�","",$_A['query_url']."/log");
				$_SESSION['valicode'] = "";
			}
		}
	}
}



/**
 * ��ӷ���
**/
elseif ($_A['query_type'] == "recharge_new"){
	check_rank("account_recharge_new");//���Ȩ��
	if(isset($_POST['username']) && $_POST['username']!=""){
		$_data['username'] = $_POST['username'];
		$result = usersClass::GetUsers($_data);
		if ($result==false){
			$msg = array("�û���������");
		}else{
			$data['user_id'] = $result['user_id'];
			$data['status'] = 0;
			$data['type']==0;
			$data['payment'] = 0;
			$data['fee'] = 0;
			$data['balance'] = $_POST['money'];
			$data['money'] = $_POST['money'];
			$data['nid'] = $result['user_id'].time().rand(100,999);
			$data['remark'] = $_POST['remark'];
			$result = accountClass::AddRecharge($data);
			if ($result != true){
				$msg = array($result);
			}else{
				$msg = array("�����ɹ�","",$_A['query_url']."/recharge".$_A['site_url']);
			}
		}
	}
}
// �ϴ������
elseif($_A['query_type']=='batch_recharge_new'){
	check_rank("account_recharge_new");
	$filetype = ['application/excel', 'application/vnd.ms-excel', 'application/msexcel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.msexcel'];
	$type=strtolower(substr($_FILES['file']['name'],-3,3));
	if(is_uploaded_file($_FILES['file']['tmp_name']) && in_array($_FILES['file']['type'],$filetype) && in_array($type,['xlsx','xls'])){
		$dir = ROOT_PATH.'data/upfiles/'.$_FILES['file']['name'];
		if($_FILES['file']['error']==0){
			if(is_file($dir)){
				unlink($dir);
			}
			move_uploaded_file($_FILES['file']['tmp_name'],$dir);
			include(ROOT_PATH.'libs/PHPExcel/PHPExcel.php');
			include(ROOT_PATH.'libs/PHPExcel/PHPExcel/Reader/Excel5.php');
			$PHPExcel = new PHPExcel();
			$PHPReader = new PHPExcel_Reader_Excel5();
			$PHPExcel = $PHPReader->load($dir);
			$currentSheet = $PHPExcel->getSheet(0);
			$allRow = $currentSheet->getHighestRow();
			for($i=1;$i<=$allRow;$i++){
				$names = iconv('utf-8','gbk',$currentSheet->getCell('A'.$i)->getValue());
				$data[]=[$currentSheet->getCell('B'.$i)->getValue(),iconv('utf-8','gbk',$currentSheet->getCell('C'.$i)->getValue()),$names];
				$name[$names]=$names;
			}
			$userdata = $mysql->db_fetch_arrays('SELECT user_id,username FROM {users} WHERE username in(\''.implode('\',\'',$name).'\')');
			$ip = ip_address();
            foreach($userdata as $key=>$value){
                $uid_data[$value['username']] = $value['user_id'];
            }
			foreach($data as $key=>$value){
				$userv=$uid_data[$value[2]];
				if(!empty($userv)){
					$insert[]='(\''.$userv.time().rand(100,999).'\', \''.$userv.'\', 0, '.$value[0].', 0, \''.$value[0].'\', 0, 0, \''.$value[1].'\','.time().', \''.$ip.'\')';
				}
			}
			if(!empty($insert)){
				$sql = 'INSERT INTO {account_recharge} (`nid`, `user_id`, `status`, `money`, `fee`, `balance`, `payment`, `type`, `remark`,`addtime`, `addip`) VALUES '.implode(',',$insert);
				$mysql->db_query($sql);
			}
			unlink($dir);
			$msg = array("�����ɹ�","","?dyryr&q=code/account/recharge_new");
		}else{
			$msg = array("�ϴ�����","","?dyryr&q=code/account/recharge_new");
		}
	}else{
		$msg = array("�ļ��ĸ�ʽ����","","?dyryr&q=code/account/recharge_new");
	}
}
//��ֹ�Ҳ���
else{
	$msg = array("���������벻Ҫ�Ҳ���");
}
?>