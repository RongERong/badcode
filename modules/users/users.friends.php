<?
/******************************
 * $File: users.vip.php
 * $Description: �û�vip�Ĺ�������
 * $Author: ahui 
 * $Time:2010-08-09
 * $Update:None 
 * $UpdateDate:None 
 * Copyright(c) 2012 by dycms.net. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���


class usersfriendsClass   {
	
	function usersfriendsClass(){
		//�������ݿ������Ϣ
		
        
	}
	
	function GetFriendsList($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id` = '{$data['user_id']}'";
		}
		
		if (IsExiest($data['friends_userid']) != false){
			$_sql .= " and p1.`friends_userid` = '{$data['friends_userid']}'";
		}
		
		$_select = "p1.*,p2.username,p3.username as friends_username,p4.name as type_name";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from `{users_friends}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id left join `{users}` as p3 on p1.friends_userid = p3.user_id left join `{users_friends_type}` as p4 on p1.type_id = p4.id SQL ORDER LIMIT";
		
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
     * ��ú��ѵĶ�̬
     * @param $param array('user_id' => '��ԱID')
	 * @return bool true/false
     */
	public static function GetUsersFriendsLog($data = array()){
		global $mysql;
		
		$sql = "select friends_userid  from `{users_friends}` where user_id={$data['user_id']} and status=1";
		$result = $mysql->db_fetch_arrays($sql);
			
		$_friend_userid = "";
		if ($result!=false){
			foreach ($result as $key => $value){
				$_friend_userid[] = $value['friends_userid'];
			}
		
			if ($_friend_userid!=""){
				$friend_userid = join(",",$_friend_userid);
			}
			$result =  usersClass::GetUserslogList(array("user_id"=>$friend_userid));
			return $result;
		}
		
		return "";
	}
	
	function DelFriends($data = array()){
		global $mysql;
		$sql = "delete from `{users_friends_invite}` where user_id = {$data['friends_userid']} and friends_userid = {$data['user_id']}";
		$mysql->db_query($sql);
		
		$sql = "delete from `{users_friends}`  where user_id={$data['user_id']} and friends_userid={$data['friends_userid']}";
		return $mysql -> db_query($sql);
		
	}
	
	
	function DeleteFriendsInvite($data = array()){
		global $mysql;
		$sql = "delete from `{users_friends_invite}` where user_id = {$data['user_id']} and friends_userid = {$data['friends_userid']}";
		$result = $mysql->db_query($sql);
		return $result;
		
	}
	
	
	function GetFriendsInvite($data){
		global $mysql,$_G;
		$_sql = " where 1=1 ";
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			$_sql .= " and p1.`user_id`  = '{$data['user_id']}'";
		}
		
		if (IsExiest($data['friends_userid']) != false){
			$_sql .= " and p1.`friends_userid`  = '{$data['friends_userid']}'";
		}
		
		if (IsExiest($data['status']) != false){
			$_sql .= " and p1.`status`  = '{$data['status']}'";
		}
		
		if (IsExiest($data['type']) != false){
			$_sql .= " and p1.`type`  = '{$data['type']}'";
		}
		
		$_select = "p1.*,p2.username,p3.username as friends_username,p3.reg_time as friend_reg_time";
		$sql = "select SELECT from `{users_friends_invite}` as p1
				left join `{users}` as p2 on p1.user_id = p2.user_id
				left join `{users}` as p3 on p1.friends_userid = p3.user_id
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
		foreach($list as $key=>$value){
			$vipstatus = usersvipClass::GetUsersVipStatus(array("user_id"=>$value['friends_userid']));			
			if($vipstatus==1){
				$account = isset($_G['system']['con_friend_vip_money'])?$_G['system']['con_friend_vip_money']:5;
				$vip_account += $account;
			}
		}
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'num' => $num,'vip_account'=>$vip_account);
		
		return $result;
	}
	
	function AddFriends($data){
		global $mysql;
		if ($data['friends_username']!=""){
			$sql  = "select user_id from `{users}` where username='{$data['friends_username']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==false) return "users_empty";
			$data['friends_userid'] = $result['user_id'];
		}
		unset($data['friends_username']);
		if ($data['friends_userid']==$data['user_id']){
			return "users_friends_self";
		}
		$sql = "select 1 from `{users_friends}` where user_id = {$data['user_id']} and friends_userid = {$data['friends_userid']}";
		$result = $mysql->db_fetch_array($sql);
		if($result!=false) return "users_friends_yes";
		if ($data['type']==""){
			$sql = "select 1 from `{users_friends}` where user_id = {$data['friends_userid']} and friends_userid = {$data['user_id']}";
			$result = $mysql->db_fetch_array($sql);
			if($result!=false){
				$sql = "insert into `{users_friends_invite}` set  addtime='".time()."',addip='".ip_address()."',user_id='{$data['friends_userid']}',friends_userid='{$data['user_id']}',content='{$data['content']}'";
				$mysql->db_query($sql);
			}
		}elseif ($data['type']=="invite"){
			$sql = "delete from `{users_friends_invite}` where user_id = {$data['user_id']} and friends_userid = {$data['friends_userid']}";
			$mysql->db_query($sql);
		}elseif ($data['type']=="reg"){
			$sql = "insert into `{users_friends}` set  addtime='".time()."',addip='".ip_address()."',user_id='{$data['friends_userid']}',friends_userid='{$data['user_id']}',type_id=1";
			$mysql->db_query($sql);
		}
		unset($data['type']);
		$sql = "insert into `{users_friends}` set  addtime='".time()."',addip='".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		 $mysql->db_query($sql);
		 return $mysql->db_insert_id();
	}
	
	
	/**
	 * 1,����û�����
	 *
	 * @param array $data =array("name"=>"�û���������","status"=>"״̬","degree"=>"ѧ��","in_year"=>"��ѧʱ��","professional"=>"רҵ");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddFriendsType($data = array()){
		global $mysql;
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "users_friends_type_name_empty";
        }
		
		
		$sql = "insert into `{users_friends_type}` set ";
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
	function UpdateFriendsType($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "users_friends_type_name_empty";
        }
		
		$sql = "update `{users_friends_type}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $result = $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		
		return $data['id'];
	}
	
	/**
	 * 3,ɾ���û�����
	 *
	 * @param Array $data = array("id"=>"ID")
	 * @return Boolen
	 */
	function DelFriendsType($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "users_friends_type_id_empty";
		
		
		
		if ($data['id'] == 1) return "users_friends_type_not_delete";
		
		$sql = "select 1 from `{users_admin}` where type_id='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result != false) return "users_friends_type_user_exiest";
		
		$sql = "delete from `{users_friends_type}`  where id='{$data['id']}'";
    	$mysql -> db_query($sql);
		
		return $data['id'];
	}
	
	
	
	/**
	 * 5,����û������б�
	 *
	 * @return Array
	 */
	function GetFriendsTypeList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		
		
		$_select = " p1.*";
		$_order = " order by p1.`order` desc,p1.id desc";
		$sql = "select SELECT from `{users_friends_type}` as p1  SQL ORDER ";
		
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
	 function GetFriendsTypeOne($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "users_friends_type_id_empty";
		
		$sql = "select p1.* from `{users_friends_type}` as p1   where p1.id='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);		
		if ($result==false) return "users_friends_type_empty";
		return $result;
	}
	
	function GetUsersInviteOne($data = array()){
		global $mysql;
		
		if (!IsExiest($data['user_id'])) return "users_friends_type_id_empty";
		
		$sql = "select p1.*,p2.username from `{users_friends_invite}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where p1.friends_userid='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);		
		if ($result==false) return "users_friends_invite_empty";		
		return $result;
	}
	
	
	
	/**
     * ��������û��б�
     * @param $param array('user_id' => '��ԱID')
	 * @return bool true/false
     */
	public static function GetUsersVisitList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = " where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .=" and p1.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['visit_userid']) && $data['visit_userid']!=""){
			$_sql .=" and p1.visit_userid = '{$data['visit_userid']}'";
		}
		$_order = " order by p1.id desc";
		$_select = " p1.*,p2.username as visit_username ";
		$sql = "select SELECT from `{users_visit}` as p1 left join `{users}` as p2 on  p1.visit_userid=p2.user_id {$_sql} ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}			 
				 
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	
	function GetUsersLogFriendsList($data){
		global $mysql;
		$_sql = " where p1.operating!='login' and p1.operating!='email_active' ";
		
		//�ж��Ƿ������û���
		if (IsExiest($data['type']) != false){
			if ($data['type']=="friends"){
				$_sql .= " and (p1.`user_id` in (select friends_userid from `{users_friends}` where user_id='{$data['user_id']}') or p1.user_id='{$data['user_id']}')";
			}
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by id desc";
		$sql = "select SELECT from `{users_log}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			foreach ($list as $key => $value){
				$list[$key]['content'] = html_entity_decode($value['content'],ENT_QUOTES);
			}
			return $list;
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
		foreach ($list as $key => $value){
			$list[$key]['content'] = "1".htmlspecialchars($value['content']);
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
	
	function GetUsersInviteList($data){
		global $mysql;
		$_sql = " where 1=1 ";
		
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .=" and p1.invite_userid = '{$data['user_id']}'";
		}
		
		$_select = "p1.*,p2.username,p2.reg_time as addtime";
		$_order = " order by id desc";
		$sql = "select SELECT from `{users_info}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			return $list;
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
			$vip=usersClass::GetUsersVip(array("user_id"=>$value['user_id']));
			 
			$list[$key]['vip_status']=$vip['status'];			
			$list[$key]['verify_time']=$vip['verify_time'];	
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
}
?>