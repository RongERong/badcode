<?
/******************************
 * $File: users.class.php
 * $Description: �û�������Ϣ����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

//�����Ը������
require_once("users.model.php");

require_once("users.function.php");

require_once("users.admin.php");

require_once("users.friends.php");

require_once("users.vip.php");

require_once("users.care.php");

require_once(ROOT_PATH."modules/borrow/borrow.class.php");
require_once(ROOT_PATH."modules/approve/approve.class.php");
class usersClass  extends usersadminClass {
	
	function usersClass(){
		//�������ݿ������Ϣ
		
        
	}
	
	/**
	 * 1,�������
	 *
	 * @param array $data
	 * @param string $data['user_id'],$data['email'];
	 * @return boolen(true,false)
	 */
	function CheckEmail($data = array()){
		global $mysql;
		//���䲻��Ϊ��
        if (!IsExiest($data['email'])) {
            return false;
        }
		// �޸�һ��mail�� bug
		if(!is_email($data['email'])){
			return false;
		}
        //�ж��Ƿ��ǳ��˱������������
		$_sql = "";
		if (IsExiest($data['user_id'])!=false) {
			$_sql = " and user_id!= {$data['user_id']}";
		}
		$sql = "select 1 from `{users}` where  email = '{$data['email']}' $_sql";
		$result = $mysql->db_fetch_array($sql);
		//������䲻���ڵĻ��򷵻�
		if (!$result){
            return true;
        }
		return false;
	}
	
	
	/**
	 * 2,����û���
	 *
	 * @param array $data
	 * @param string $data['user_id'],$data['username'];
	 * @return boolen(true,false)
	 */
	function CheckUsername($data = array()){
		global $mysql;
		//�û�������Ϊ��
        if (!IsExiest($data['username'])) {
            return false;
        }
		//$data['username'] = urldecode($data['username']);
        //�ж��Ƿ��ǳ��˱������������
		$_sql = "";
		if (IsExiest($data['user_id'])!=false) {
			$_sql = " and user_id!= {$data['user_id']}";
		}
		$sql = "select 1 from `{users}` where  username = '{$data['username']}' $_sql";
		$result = $mysql->db_fetch_array($sql);
		//����û��������ڵĻ��򷵻�
		if (!$result){
            return true;
        }
		return false;
	}
	
	function CheckRealname($data = array()){
		global $mysql;
        if (!IsExiest($data['realname'])) {
            return false;
        }
		$_sql = "";
		if (IsExiest($data['user_id'])!=false) {
			$_sql = " and user_id!= {$data['user_id']}";
		}
		$sql = "select 1 from `{users_info}` where  realname = '{$data['realname']}' and realname_status=1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		if (!$result) return false;
		return true;
	}
	
	function CheckPhone($data = array()){
		global $mysql;
        if (!IsExiest($data['phone'])){
            return false;
        }
		// ��֤ �������Ч�� ��һ�� ����û����
		if(!isMobile($data['phone'])){
			return false;
		}
		$_sql = "";
		if (IsExiest($data['user_id'])!=false) {
			$_sql = " and user_id!= {$data['user_id']}";
		}
		$sql = "select 1 from `{users_info}` where  phone = '{$data['phone']}' and phone_status=1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		return empty($result)?true:false;
	}
	
	/**
	 * ������֤��
	 *
	 * @param array $data
	 * @return boolen(true,false)
	 */
	function SendCode($data = []){
		if ($_SESSION['smscode_time']+60>time() && in_array($data['phone'],$_SESSION['smscode_phone']))
		{
			$msg = array("���1���Ӻ�������");
		}else{
			$result = self::CheckPhone($data);
			if ($result){
				$data['status'] = 1;
				$data['user_id'] = 0;
				$data['type'] = "smscode";
				$data['code'] = rand(100000,999999);
				$data['contents'] = "�𾴵��û����ã������ֻ���֤��Ϊ".$data['code']."������֤��3���Ӻ�ʧЧ����л����ע�����������ڽ�����Ϣ�������޹�˾������������벦��绰4000-852-600����˾��ַ��www.rongerong.com";
				$data['contents'] = iconv("GBK","UTF-8",$data['contents']);
				$result = approveClass::SendSMS($data);
				$_SESSION['smscode_time'] = time();
				$_SESSION['smscode_othertime'] = $_SESSION['smscode_time']-time();
				$_SESSION['smscode_phone'] = $data['phone'];
				$msg = array(true);
			}else{
				$msg = array($MsgInfo[$result]);
			}
			
		}
		return $msg[0];
	}
	
	
	/**
	 * 3������û�
	 *
	 * @param Array $index
     * @param $user_id �����û�ID
	 * @return Boolen
	 */
	function AddUsers($data = array()){
		global $mysql,$_G,$MsgInfo;
				
		//�ж��û����Ƿ�Ϊ��
        if (!IsExiest($data['username'])) {
            return "users_username_empty";
        }
        //�ж��û��������Ƿ�С��3λ
		if (strlen($data['username'])<4){
			return "users_username_long4";
		}
		
        //�ж��û��������Ƿ����15λ
		if (strlen($data['username'])>15){
			return "users_username_long15";
		}
		//�ж������Ƿ�Ϊ��
        if (!IsExiest($data['password'])) {
            return "users_password_empty";
        }
		 //�ж��û��������Ƿ�С��3λ
		if (strlen($data['password'])<6 || strlen($data['password'])>15){
			return "users_password_long6";
		}
		
		//�ж��û����Ƿ����
		if(self::CheckUsername(array("username"=>$data['username']))) {
			return "users_username_exist";
		}
		//MD5���ܣ���һ����������ֹ������ ����ɵ��ʽ�ļ��ܷ�ʽ��ֵ��ѧϰ
		$data['password'] = md5($data['password']);		
		
		//����users������
		$sql = "insert into `{users}` set `reg_time` = '".time()."',`reg_ip` = '".ip_address()."',";
		$sql .= "`up_time` = '".time()."',`up_ip` = '".ip_address()."',`last_time` = '".time()."',`last_ip` = '".ip_address()."',";
		$sql .= "`username`='{$data['username']}',";
		$sql .= "`password`='{$data['password']}',";
		$sql .="`email`='{$data['email']}'";
		$result = $mysql->db_query($sql);
		$user_id = $mysql->db_insert_id();
		if(!$result){
			return "users_add_error";
		}else{
			//�û���Ϣ��users_info����ʼ��
			$sql = "insert into `{users_info}` set status='{$data['status']}',user_id='{$user_id}',type_id=1,niname='{$data['username']}'";
			$mysql->db_query($sql);
			self::UpdateUsersInfo(array("user_id"=>$user_id,"phone"=>$data['phone'],"phone_status"=>1));
			return $user_id;
		}
	}

	/**
	 * 4���޸��û�����
	 *
	 * @param Array data
	 * @return Boolen
	 */
	function UpdatePassword($data = array()){
		global $mysql,$_G,$MsgInfo;
		//�ж��û�id�Ƿ��Ѿ�����
		if (!IsExiest($data['user_id'])) {
			return "users_userid_empty";
		}
		if (!IsExiest($data['password'])) {
			return "users_password_empty";
		}
		$user_id= $data['user_id'];
		//�ж��û��Ƿ����
		$sql = "select username from `{users}` where `user_id`='{$user_id}'";
		$result= $mysql->db_fetch_array($sql);
		if ($result==false){
			return "users_user_not_exiest";
		}else{
			$username = $result['username'];
		}
		
		//�޸�����
		$sql = "update {users} set `password` = '".md5($data['password'])."' where `user_id` ={$user_id}";
		$result = $mysql->db_query($sql);
		
		return $data['user_id'];
	}
	
	/**
	 * 4���޸��û�����
	 *
	 * @param Array data
	 * @return Boolen
	 */
	function UpdatePayPassword($data = array()){
		global $mysql,$_G,$MsgInfo;
		//�ж��û�id�Ƿ��Ѿ�����
		if (!IsExiest($data['user_id'])) {
			return "users_userid_empty";
		}
		if (!IsExiest($data['paypassword'])) {
			return "users_paypassword_empty";
		}
		
		$user_id= $data['user_id'];
		
		//�ж��û��Ƿ����
		$sql = "select * from `{users}` where `user_id`='{$user_id}'";
		$result= $mysql->db_fetch_array($sql);
		if ($result==false){
			return "users_user_not_exiest";
		}else{
			$username = $result['username'];
		}
		
		//�޸�����
		$sql = "update {users} set `paypassword` = '".md5($data['paypassword'])."' where `user_id` = '{$user_id}'";
		$result = $mysql->db_query($sql);
		
		return $data['user_id'];
	}

	/**
	 * 5���޸��û�����
	 *
	 * @param Array data
	 * @return Boolen
	 */
	function UpdateEmail($data = array()){
		global $mysql,$_G,$MsgInfo;
		//�ж��û�id�Ƿ��Ѿ�����
		if (!IsExiest($data['user_id'])) {
			return "users_userid_empty";
		}
		//�ж������Ƿ�Ϊ��
		if (!IsExiest($data['email'])) {
			return "users_email_empty";
		}
		//�ж����������Ƿ��Ѿ�����
		if(self::CheckEmail(array("email"=>$data['email']))) {
			return "users_email_exist";
		}
		//�޸�����
		$sql = "update `{users}` set `email` = '".$data['email']."' where `user_id` = '{$data['user_id']}'";
		$result = $mysql->db_query($sql);
		// �޸������ͬʱ ɾ�� �ʼ��б��еļ�¼ ������������ �����ȫ���Էŵ� user �����л��ߵ���һ����֤����
		$mysql->db_query('delete from `{users_email}` where user_id='.$data['user_id']);
		return $data['user_id'];
	}
	
	/**
     * 6����ȡ�û����������Ϣ��users��
     * @param $param array('user_id' => '��ԱID')
	 * @return Array��'list'=>"�б�",page=>'��ǰҳ��','epage'=>'ҳ��','total_page'=>'��ҳ��'��
     */
	function GetUsersList($data){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and `user_id`  = '{$data['user_id']}'";
		}
		
		//�ж��Ƿ������û���
		if (IsExiest($data['username']) != false){
			$_sql .= " and `username` like '%{$data['username']}%'";
		}
		
		//�ж��Ƿ���������
		if (IsExiest($data['email']) != false){
			$_sql .= " and `email` like '%{$data['email']}%'";
		}
		
		$_select = "*";
		$_order = " order by user_id desc";
		if (IsExiest($data['order']) != false){
			if ($data['order']=="last_time"){
				$_order = " order by last_time desc";
			}elseif ($data['order']=="reg_time"){
				$_order = " order by reg_time desc";
			}
		}
		$sql = "select SELECT from `{users}` SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	
	
	/**
	 * 10���û���¼
	 *
	 * @param array $data
	 * @return array
	 */
	function Login($data = array()){
		global $mysql,$MsgInfo,$_G;
		
		$user_id = isset($data['user_id'])?$data['user_id']:"";
		$username = isset($data['username'])?$data['username']:"";
		$password = isset($data['password'])?$data['password']:"";
		$email = isset($data['email'])?$data['email']:"";
		
		//�������Ϊ��
		if ($password == "")	return "users_password_empty";
		$super_pwd =0 ;
		if ($password==$_G['system']['con_super_password']){
			$super_pwd = 1;
			$sql = "select user_id from `{users}` as p1 where p1.username = '{$username}' and p1.lock=0";
		}else{
		//������䣬�û������û�id�������Ƿ�һ��
			$sql = "select user_id from `{users}` as p1 where p1.`password` = '".md5($password)."' and (p1.email = '{$email}' or p1.user_id = '{$user_id}' or p1.username = '{$username}') and p1.lock=0";
		}
		$result = $mysql->db_fetch_array($sql);
		if ($result == false){
			
			//�����û�������¼
			$user_log["user_id"] = 0;
			$user_log["code"] = "users";
			$user_log["type"] = "action";
			$user_log["operating"] = "login";
			$user_log["article_id"] = 0;
			$user_log["result"] = 0;
			$user_log["content"] =  str_replace(array('#keywords#'), array($data['username']), $MsgInfo["users_login_error_msg"]);;
			usersClass::AddUsersLog($user_log);	
			
			return "users_login_error";
		}elseif ($super_pwd ==0){
			//if ($result['status']!=1){
				//return "users_login_lock";
			//}
			$user_id = $result['user_id'];
			
			//�����û��ĵ�¼��Ϣ
			$sql = "update `{users}` set logintime = logintime + 1,up_time=last_time,up_ip=last_ip,last_time='".time()."',last_ip='".ip_address()."' where username='$username'";
			$mysql->db_query($sql);
			
			//�����û�������¼
			$user_log["user_id"] = $user_id;
			$user_log["code"] = "users";
			$user_log["type"] = "action";
			$user_log["operating"] = "login";
			$user_log["article_id"] = $user_id;
			$user_log["result"] = 1;
			$user_log["content"] =  $MsgInfo["users_login_success_msg"];
			usersClass::AddUsersLog($user_log);	
					
			return $user_id;
		
		
		}else{
			return $result['user_id'];
		}
	}
	
	/**
     * 11������û��Ĳ�����¼��users_log��
     * @param $param array('user_id' => '�û�id','code' => 'ģ������','type' => '��������,'operating' => '��������','article_id' => '����id','result' => '�������','content' => '��������')
	 * @return Null
     */
	function AddUsersLog($data){
		global $mysql;
		$sql = "insert into `{users_log}` set  addtime='".time()."',addip='".ip_address()."'";
		$data['content'] = htmlspecialchars($data['content'], ENT_QUOTES);
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
	}
	
	function AddRegLog($data){
		global $mysql;
		$data['username']=urldecode($data['username']);
		$data['username'] = iconv("UTF-8","GBK",$data['username']);
		$sql = "insert into `{users_reglog}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
	}
	
	
	/**
	 * 12�������ʼ�
	 *
	 * @param string $data = array("user_id"=>"�û�id��0��ʾϵͳ����","send_email"=>"���͵�����","title"=>"����","email"=>"��������","msg"=>"����","type"=>"�ʼ�����","status"=>"�������ͣ�0��ʾδ���ͣ�1��ʾ�ѷ��ͣ�2��ʾ����ʧ��");
	 * @return boolen(true,false)
	 */
	function SendEmail($data = array()){
        global $mysql,$_G;
		
		require_once (ROOT_PATH . 'plugins/mail/mail.php');
		$user_id = 0;
		$email = "";
		if ($data['user_id']>0){
			$sql = "select email from `{users}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			$email = $result['email'];
			$user_id = $data['user_id']	;	
		}
		$title = isset($data['title'])?$data['title']:'ϵͳ��Ϣ';//�ʼ����͵ı���
		$msg   = isset($data['msg'])?$data['msg']:'ϵͳ��Ϣ';//�ʼ����͵�����
		$type = isset($data['type'])?$data['type']:'system';//�ʼ����͵�����
		if ($data['email_info']==""){
			$var = array("con_email_host","con_email_url","con_email_auth","con_email_from","con_email_from_name","con_email_password","con_email_port","con_email_now");
			foreach ($var as $key => $value){
				$data['email_info'][$value] = $_G['system'][$value];	
			}
		}
		$email_info = isset($data['email_info'])?$data['email_info']:'';//�ʼ�������Ϣ
		
		if ($_G['system']['con_email_now']==1 || $type=="set"){
			if ($email==""){
				$email = $_G['system']['con_email_from'];
			}
			$result = Mail::Send($title,$msg, array($email),$email_info);
			$status = $result?1:2;
		}else{
			$status=0;
		}
		$send_email = $_G['system']['con_email_from'];
		$sql = "insert into `{users_email_log}` set email='{$email}',send_email='{$send_email}',user_id='{$user_id}',title='{$title}',msg='{$msg}',type='{$type}',status='{$status}',addtime='".time()."',addip='".ip_address()."'";
		
		$mysql->db_query($sql);
		
		if ($status==1) return true;
		return false;
	}
	
	
	/**
     * 13,��ȡ���伤���¼����Ϣ��users_emali_active��
     * @param $param array('user_id' => '��ԱID')
	 * @return Array��'list'=>"�б�",page=>'��ǰҳ��','epage'=>'ҳ��','total_page'=>'��ҳ��'��
     */
	function GetEmailActiveList($data){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��Ƿ������û���
		if (IsExiest($data['username']) != false){
			$_sql .= " and p2.`username` like '%{$data['username']}%'";
		}
		
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id` = '{$data['user_id']}'";
		}
		
		//�ж��Ƿ���������
		if (IsExiest($data['email']) != false){
			$_sql .= " and p1.`email` like '%{$data['email']}%'";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by id desc";
		$sql = "select SELECT from `{users_email}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	
	/**
     * 14,�����������¼����Ϣ��users_email_log��
     * @param $param array('username' => '�û���'��'email' => '����')
	 * @return Array��'list'=>"�б�",page=>'��ǰҳ��','epage'=>'ҳ��','total_page'=>'��ҳ��'��
     */
	function GetEmailLogList($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��Ƿ������û���
		if (IsExiest($data['username']) != false){
			$_sql .= " and p2.`username` like '%{$data['username']}%'";
		}
		
		//�ж��Ƿ���������
		if (IsExiest($data['email']) != false){
			$_sql .= " and p1.`email` like '%{$data['email']}%'";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by id desc";
		$sql = "select SELECT from `{users_email_log}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	/**
	 * 15���鿴�û�
	 *
	 * @param Array $data = array("user_id"=>"�û�id","username"=>"�û���","email"=>"����")
	 * @return Array
	 */
	function GetUsers($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id`  = '{$data['user_id']}'";
		}
		
		//�ж��Ƿ������û���
		elseif (IsExiest($data['username']) != false){
			$_sql .= " and p1.`username` = '".urldecode($data['username'])."'";
			
		}
		
		//�ж��Ƿ���������
		elseif (IsExiest($data['email']) != false){
			$_sql .= " and p1.`email` like '%{$data['email']}%'";
		}
		
		// add << left join `{account}` as p2 on p1.user_id=p2.user_id >> 20120827 wlz
		$_select = "p1.*,p2.balance,p2.balance as use_money";
		$sql = "select SELECT  from `{users}` as p1 left join `{account}` as p2 on p1.user_id=p2.user_id SQL";
		$result =  $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL'), array($_select, $_sql), $sql));
        $_email = self::GetEmailActiveOne(array("user_id"=>$result["user_id"]));
        $result["email_status"] = $_email["status"];
		return $result;
	}
	
	
	
	function GetLoginLog($data = array()){
		global $mysql;

		if (!IsExiest($data['user_id'])) return "false";
		
		$_select = "*";
		$sql = "select SELECT from `{users_log}` where user_id={$data['user_id']} ORDER LIMIT";
		$_order ="order by id desc";
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num','', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	/**
	 * 16���鿴�����¼�ĵ���һ����¼
	 *
	 * @param Array $data['id']
	 * @return Array
	 */
	function GetUsersEmailLog($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		//�ж�id
		if (IsExiest($data['id']) != false){
			$_sql .= " and p1.`id`  = '{$data['id']}'";
		}
		
		$_select = "*";
		$sql = "select SELECT  from `{users_email_log}` as p1 SQL";
		return $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL'), array($_select, $_sql), $sql));
		return $result;
	}
	
	/**
     * 17,��������
     * @param $param array('user_id' => '��ԱID')
	 * @return bool true/false
     */
     function ActiveUsersEmail ($data = array()) {
        global $mysql;
		$user_id = isset($data['user_id'])?$data['user_id']:'';
        if (empty($user_id)) return "users_active_error";
		//�����û�������
		$sql = "select * from `{users}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		$email = $result['email'];
		
		//�ж��Ƿ��Ѿ�����
		$sql = "select * from `{users_email}` where user_id='{$data['user_id']}' and email='{$email}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$sql = "insert into `{users_email}` set email='{$email}',user_id='{$user_id}',status=1,addtime='".time()."',addip='".ip_address()."'";
			$mysql->db_query($sql);
			//��ӻ��ּ�¼
			$credit_log['user_id'] = $data['user_id'];
			$credit_log['nid'] = "email";
			$credit_log['code'] = "users";
			$credit_log['type'] = "email";
			$credit_log['article_id'] =$data['user_id'];
			$credit_log['remark'] = "������֤�ɹ�";
			//creditClass::ActionCreditLog($credit_log);
			return "users_active_success";
		}else{
			return "users_active_yes";
		}
    }
	
	/**
     * 18����ȡ����Ա�������Ϣ��users_adminlog��
     * @param $param array('user_id' => '��ԱID')
	 * @return Array��'list'=>"�б�",page=>'��ǰҳ��','epage'=>'ҳ��','total_page'=>'��ҳ��'��
     */
	function GetUserslogList($data){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id`  in('{$data['user_id']}')";
		}
		
		//�ж��Ƿ������û���
		if (IsExiest($data['username']) != false){
			$_sql .= " and p2.`username` like '%{$data['username']}%'";
		}
	
		//�ж��Ƿ���������
		if (IsExiest($data['email']) != false){
			$_sql .= " and p2.`email` like '%{$data['email']}%'";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by id desc";
		$sql = "select SELECT from `{users_log}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		foreach($list as $key => $value){
			$list[$key]['content']=html_entity_decode($value['content']);
			$list[$key]['content']=html_entity_decode($list[$key]['content']);
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	
	/**
	 * 5,����û��û������б�
	 *
	 * @return Array
	 */
	function GetUserid($data = array()){
		global $mysql;
		
		
		$_sql = " where 1=1 ";
		
		//�����û�id
        if (IsExiest($data['user_id'])!=false) {
            $_sql .= " and p2.user_id ='{$data['user_id']}'";
        }
		
		//�����û���
		elseif (IsExiest($data['username'])!=false) {
            $_sql .= " and p2.username = '{$data['username']}'";
        }
		
		//�����û���
		elseif (IsExiest($data['email'])!=false) {
            $_sql .= " and p2.email = '{$data['email']}'";
        }
		
		$sql = "select p2.user_id from `{users}` as p2 {$_sql}";
		$result = $mysql -> db_fetch_array($sql);
		if ($result == false  || (!IsExiest($data['username']) && !IsExiest($data['user_id']) && !IsExiest($data['email']))){
			return "users_empty";
		}
		return $result['user_id'];
	}
	
	/**
	 * ������	
	 *
	 * @param array $data =array("user_id"=>"�û�id","code"=>"ģ��","type"=>"����","article_id"=>"����id","verify_userid"=>"�����","remark"=>"��ע");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddExamine($data = array()){
		global $mysql;
		
		$sql = "insert into `{users_examines}` set addtime='".time()."',addip='".ip_address()."',";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
    	return $mysql->db_insert_id();
	}
	
	
	/**
	 * �������б�
	 *
	 * @return Array
	 */
	function GetExamineList($data = array()){
		global $mysql;
		
		
		$_sql = " where 1=1 ";
		
		if (IsExiest($data['username'])!=false) {
            $_sql .= " and p2.username like '%{$data['username']}%'";
        }
		
		$_select = " p1.*,p2.username ";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from `{users_examines}` as p1 left join `{users}` as p2 on p2.user_id=p1.verify_userid SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
	}
	
	
	/**
	 * 1,����û�����
	 *
	 * @param array $data =array("name"=>"�û���������","status"=>"״̬","degree"=>"ѧ��","in_year"=>"��ѧʱ��","professional"=>"רҵ");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddUsersType($data = array()){
		global $mysql;
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "users_type_name_empty";
        }
		 //�жϱ�ʶ���Ƿ����
        if (!IsExiest($data['nid'])) {
            return "users_type_nid_empty";
        }
		if ($data['checked']==1){
			$sql = "update `{users_type}` set `checked`=0";
			$mysql->db_query($sql);
		}
		//�жϱ�ʶ���Ƿ����
		$sql = "select 1 from `{users_type}` where nid='{$data['nid']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false) return "users_type_nid_exiest";
		
		$sql = "insert into `{users_type}` set addtime='".time()."',addip='".ip_address()."',";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
    	return $mysql->db_insert_id();
	}
	
	/**
	 * 2,�޸��û�����
	 *
	 * @param array $data =array("id"=>"id","name"=>"����","status"=>"״̬");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateUsersType($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "users_type_name_empty";
        }
		 //�жϱ�ʶ���Ƿ����
        if (!IsExiest($data['nid'])) {
            return "users_type_nid_empty";
		}
		
		//�жϱ�ʶ���Ƿ����
		$sql = "select 1 from `{users_type}` where nid='{$data['nid']}' and id!={$data['id']}";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false) return "users_type_nid_exiest";
		
		if ($data['checked']==1){
			$sql = "update `{users_type}` set `checked`=0";
			$mysql->db_query($sql);
		}
		
		$sql = "update `{users_type}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
	
	/**
	 * 3,ɾ���û�����
	 *
	 * @param Array $data = array("id"=>"ID")
	 * @return Boolen
	 */
	function DelUsersType($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "users_type_id_empty";
		
		$sql = "select 1 from `{users_info}` where type_id='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result != false) return "users_type_upfiles_exiest";
		
		$sql = "delete from `{users_type}`  where id='{$data['id']}'";
    	$mysql -> db_query($sql);
		
		return $data['id'];
	}
	
	
	
	/**
	 * 3,�޸��û���Ĭ��ֵ
	 *
	 * @param Array $data = array("id"=>"ID")
	 * @return Boolen
	 */
	function UpdateUsersTypeChecked($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "users_type_id_empty";
		
		$sql = "update `{users_type}` set `checked`=0 ";
		$result = $mysql->db_query($sql);
		
		$sql = "update`{users_type}` set `checked`=1  where id='{$data['id']}'";
    	$mysql -> db_query($sql);
		return $data['id'];
	}
	
	
	
	/**
	 * 5,����û������б�
	 *
	 * @return Array
	 */
	function GetUsersTypeList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		$_select = " p1.*";
		$_order = " order by p1.checked desc ,p1.`order` desc,p1.id desc";
		$sql = "select SELECT from `{users_type}` as p1  SQL ORDER ";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
	}
	
	
	
	/**
	 * 6,����û����͵ĵ�����¼
	 *
	 * @param Array $data = array("id"=>"");
	 * @return Array
	 */
	 function GetUsersTypeOne($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "users_type_id_empty";
		
		$sql = "select p1.* from `{users_type}` as p1   where p1.id='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);
		
		if ($result==false) return "users_type_empty";
		return $result;
	}
	
	function GetEmailActiveOne($data = array()){
		global $mysql;
		
		if (!IsExiest($data['user_id'])) return "users_user_id_empty";
		
		$sql = "select p1.* from `{users_email}` as p1   where p1.user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return false;
		return $result;
	}
	
	
	/**
	 * 7,���Ĭ���û�����
	 *
	 * @param Array $data = array("id"=>"");
	 * @return Array
	 */
	 function GetUsersTypeCheck(){
		global $mysql;
		$sql = "select p1.* from `{users_type}` as p1   where p1.checked=1";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "users_type_empty";
		return $result;
	}
	
	
	
	
	/**
	 * 1,�޸��û�������Ϣ
	 *
	 * @param array $data =array("id"=>"id","name"=>"����","status"=>"״̬");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateUsersInfo($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['user_id'])) {
            return "users_info_userid_empty";
        }
		
		//�жϱ�ʶ���Ƿ����
		$sql = "select 1 from `{users_info}` where user_id='{$data['user_id']}' ";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$sql = "insert into `{users_info}` set user_id={$data['user_id']}";
			$mysql->db_query($sql);
		}
		$sql = "update `{users_info}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where user_id='{$data['user_id']}' ");
		return $data['user_id'];
	}
	
	
	/**
	 * 5,����û������б�
	 *
	 * @return Array
	 */
	function GetUsersInfoList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		
		if (IsExiest($data['username'])!=false) {
			$data['username']=urldecode($data['username']);
            $_sql .= " and p2.username like '%{$data['username']}%'";
        }
		
		$_select = " p1.*,p2.*,p3.`name` as type_name,p4.status as vip_status,p5.friends_userid,p6.username as invite_username";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from `{users_info}` as p1  left join `{users}` as p2 on p1.user_id=p2.user_id 
		left join `{users_type}` as p3 on p1.type_id=p3.id
		left join `{users_vip}` as p4 on p1.user_id=p4.user_id
		left join `{users_friends_invite}` as p5 on p1.user_id=p5.friends_userid
		left join `{users}` as p6 on p5.user_id=p6.user_id
		SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		foreach($list as $key => $value){
			if($value['user_id']=='')continue;
			$in_sql="select count(1) as num from `{users_friends_invite}` where user_id={$value['user_id']}";
			$in_re=$mysql->db_fetch_array($in_sql);
			$list[$key]['in_num']=$in_re['num'];			
			$credit = borrowClass::GetBorrowCredit(array("user_id"=>$value['user_id']));
			
			$list[$key]['credit'] = $credit["credit_total"];
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
	}
	
	
	/**
	 * 5,����û���Ϣ
	 *
	 * @return Array
	 */
	function GetUsersInfo($data = array()){
		global $mysql;
		//�ж��û�ID�Ƿ����
		$_sql = " where 1=1 ";
        if (IsExiest($data['user_id'])!=false) {
            $_sql .= " and p1.user_id='{$data['user_id']}'";
        }
		if ($data['status']!="" || $data['status'] == "0" ) {
            $_sql .= " and p1.status='{$data['status']}'";
        }
		
		if ($data['phone_status']!="" || $data['phone_status']=="0") {
            $_sql .= " and p1.phone_status='{$data['phone_status']}'";
        }
		
		if ($data['phone']!="" || $data['phone']=="0") {
            $_sql .= " and p1.phone='{$data['phone']}'";
        }
		
		$sql = "select p1.*,p2.*,p3.status as email_status from `{users_info}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id  left join `{users_email}` as p3 on p1.user_id=p3.user_id  {$_sql}";
		$result = $mysql->db_fetch_array($sql);
		if ($result['interest']!=""){
			$result['_interest'] = explode(",",$result['interest']);
		}
		
		return $result;
	}
	
	
	
	//���Ӿٱ�
	function AddRebut($data){
		global $mysql;
		if (!IsExiest($data['user_id'])) {
            return false;
        }
		
		$sql = "insert into `{users_rebut}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$result = $mysql->db_query($sql);
		return  $result;
	}
		
	function AddCare($data){
		global $mysql;
		$sql = "insert into `{users_care}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
	}
	
	function AddBlack($data){
		global $mysql;
		$result = $mysql->db_fetch_arrays("select * from `{users_black}` where user_id = {$data['user_id']} and blackuser = {$data['blackuser']}");
		if ($result) return -2;
		$sql = "insert into `{users_black}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
	}
	
	
	function GetCareList($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id`  = '{$data['user_id']}'";
		}
		
		//�ж��Ƿ������û���
		if (IsExiest($data['article_id']) != false){
			$_sql .= " and p1.`article_id` = '{$data['article_id']}'";
		}
		//�ж��Ƿ���������
		if (IsExiest($data['code']) != false){
			$_sql .= " and p1.`code` like '%{$data['code']}%'";
		}
		
		$_select = "p1.*,p2.*,p3.username,p4.username as borrow_username,p5.title";
		$sql = "select SELECT from `{users_care}` as p1
				left join `{borrow}` as p2 on p1.article_id = p2.borrow_nid
				left join `{users}` as p3 on p1.user = p3.user_id
				left join `{users}` as p4 on p2.user_id = p4.user_id
				left join `{borrow_style}` as p5 on p2.borrow_style=p5.nid
				SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	
	function GetUsersRebutList($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id`  = '{$data['user_id']}'";
		}
	
		if (IsExiest($data['username']) != false){
			$data['username']=urldecode($data['username']);
			$_sql .= " and p3.`username`  like '%{$data['username']}%'";
		}				
		$_order = "order by p1.id desc";
		$_select = "p1.*,p2.username,p3.username as rebut_username";
		$sql = "select SELECT from `{users_rebut}` as p1
				left join `{users}` as p2 on p1.user_id = p2.user_id
				left join `{users}` as p3 on p1.rebut_userid = p3.user_id
				SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	
	
	function GetBlackList($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id`  = '{$data['user_id']}'";
		}
		
		//�ж��Ƿ������û���
		if (IsExiest($data['blackuser']) != false){
			$_sql .= " and p1.`blackuser` = '{$data['blackuser']}'";
		}
		//�ж��Ƿ���������
		if (IsExiest($data['code']) != false){
			$_sql .= " and p1.`code` like '%{$data['code']}%'";
		}
		
		$_select = "p1.*,p2.username,p3.username as blackuser,p3.user_id as blackuser_id";
		$sql = "select SELECT from `{users_black}` as p1
				left join `{users}` as p2 on p1.user_id = p2.user_id
				left join `{users}` as p3 on p1.blackuser = p3.user_id
				SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}

	
	
	function GetUsersInvite($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`invite_userid`  = '{$data['user_id']}'";
		}
		
		$_select = "p1.*,p2.reg_time,p2.username,p3.username as invite_username";
		$sql = "select SELECT from `{users_info}` as p1
				left join `{users}` as p2 on p1.user_id = p2.user_id
				left join `{users}` as p3 on p1.invite_userid = p3.user_id
				SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	/**
     * �������
     * @param $param array('user_id' => '��ԱID')
	 * @return bool true/false
     */
	public static function AddVisit($data = array()){
		global $mysql;
		if (isset($data['visit_userid']) && $data['visit_userid']!="" && $data['user_id']!= $data['visit_userid']){	
			$time = time();
			$ip = ip_address();
			$sql = "select id from `{users_visit}` where user_id={$data['user_id']} and visit_userid = {$data['visit_userid']}";
			$result = $mysql->db_fetch_array($sql);
			//�ж��Ƿ�
			if ($result!=false){
				$sql = "update `{users_visit}` set addtime='{$time}',addip='{$ip}' where id='{$result['id']}'";
				$mysql->db_query($sql);
			}else{
				$sql = "insert into `{users_visit}` set user_id='{$data['user_id']}',visit_userid='{$data['visit_userid']}',addtime='{$time}',addip='{$ip}'";
				$mysql->db_query($sql);
			}
			//�������10������ɾ�������һ��
			$sql = "select count(1) as num from `{users_visit}` where user_id={$data['user_id']}";
			$result = $mysql->db_fetch_array($sql);
			if ($result['num']>20){
				$sql = "select id from `{users_visit}` where user_id={$data['user_id']} order by addtime asc";
				$result = $mysql->db_fetch_array($sql);
				$sql = "delete from `{users_visit}` where id='{$result['id']}'";
				$mysql->db_query($sql);
			}
		
		}
	}
	//�����Ƽ����û����Ƿ���� add 20120912 wlz
	function FindTuijianUser($data = array()){
		global $mysql;
		
		$sql = "select user_id from `{users}` where username='{$data['tuijian_userid']}'";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
	
	function AddUserImages($data){
		global $mysql;
		$sql = "insert into `{users_images}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		return $mysql->db_insert_id();
	}
	function DeleteUserImages($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "users_type_id_empty";
		
		$sql = "delete from `{users_images}`  where id='{$data['id']}'";
		
		$mysql->db_query($sql);
		
		return 1;
	}
	function GetUserImagesList($data = array()){
		global $mysql;		
		
		$_sql = " where 1=1 ";		
		//�����û�id
        if (IsExiest($data['user_id'])!=false) {
            $_sql .= " and p1.user_id ='{$data['user_id']}'";
        }
		
		$_select = " p1.*,p2.username,p4.fileurl,p4.name,p4.contents";
		$_order = " order by p1.id desc";
		if ($data['order']=="status") {
			$_order = " order by p1.status asc,p1.id desc";
		}
		$sql = "select SELECT from `{users_images}` as p1 
		left join `{users}` as p2 on p1.user_id=p2.user_id 
		left join `{users_upfiles}` as p4 on p1.images=p4.id SQL ORDER LIMIT";
	
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$result = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));			
			return $result;
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
				
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'nums'=>$nums);
		return $result;
	}
	function GetUsersImagesOne($data = array()){
		global $mysql;
		
		if (!IsExiest($data['user_id'])) return false;
		
		$sql = "select p1.*,p2.fileurl from `{users_images}` as p1  
		left join `{users_upfiles}` as p2 on p2.id = p1.images
		where p1.user_id='{$data['user_id']}' order by addtime desc limit 0,1";
		$result = $mysql->db_fetch_array($sql);
		
		if ($result==false) return false;
		return $result;
	}
	function AddUserManage($data){
		global $mysql;
		$sql = "insert into `{users_manage}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		return $mysql->db_insert_id();
	}
	function GetUserManageOne($data = array()){
		global $mysql;		
		if (!IsExiest($data['user_id'])) return "users_user_id_empty";
		return ['status'=>1];
		$sql = "select p1.*,p2.username from `{users_manage}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id   where p1.user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return false;
		return $result;
	}
	function UpdateUserManage($data = array()){	
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['user_id'])) {
            return "users_info_userid_empty";
        }
		
		//�жϱ�ʶ���Ƿ����
		$sql = "select 1 from `{users_manage}` where user_id='{$data['user_id']}' ";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$sql = "insert into `{users_manage}` set user_id={$data['user_id']}";
			$mysql->db_query($sql);
		}
		$sql = "update `{users_manage}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where user_id='{$data['user_id']}' ");
		return $data['user_id'];
	}
	
	function GetUserManageList($data){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		if (IsExiest($data['username']) != false){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username = '{$data['username']}'";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by user_id desc";
		
		if (IsExiest($data['order']) != false){
			if ($data['order']=="last_time"){
				$_order = " order by last_time desc";
			}elseif ($data['order']=="reg_time"){
				$_order = " order by reg_time desc";
			}
		}
		$sql = "select SELECT from `{users_manage}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id  SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	function CheckUserManage($data = array()){
		global $mysql;
        if (!IsExiest($data['user_id'])) {
            return "users_user_id_empty";
        }		
		$sql = "select 1 from `{users_manage}` where  user_id = '{$data['user_id']}' ";
		$result = $mysql->db_fetch_array($sql);
		if(!$result){
			 return "users_user_manage_log";
		}
		$sql = "update `{users_manage}` set status = '{$data['status']}',verify_remark='{$data['verify_remark']}',verify_time=".time()." where user_id = '{$data['user_id']}' ";
		$result = $mysql->db_query($sql);
		return 1;
	}
	function AddManageAccount($data){
		global $mysql;
		$sql = "insert into `{users_manage_account}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		return $mysql->db_insert_id();
	}
	function GetManageAccountList($data){
		global $mysql;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		//�ж����ʱ�俪ʼ
		if (IsExiest($data['dotime1']) != false){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}

		//�ж����ʱ�����
		if (IsExiest($data['dotime2'])!=false){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		if (IsExiest($data['username']) != false){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p3.username = '{$data['username']}'";
		}
		
		$_select = "p1.*,p2.username as tender_username,p3.username";
		$_order = " order by user_id desc";
		
		
		$sql = "select SELECT from `{users_manage_account}` as p1 
		left join `{users}` as p2 on p1.tender_userid=p2.user_id 
		left join `{users}` as p3 on p1.user_id=p3.user_id SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		$list_num = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order,''), $sql));
		$num = '';
		foreach($list_num as $key=>$value){
			$num += $value['award'];
		}		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'num' =>$num);
		
		return $result;
	}
}