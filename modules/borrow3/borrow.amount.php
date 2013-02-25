<?
/*
1,�û�����ҳ�棬��Ҫ��ȡ���еĶ�ȣ�������borrow.class.php function GetUserLog

2,�û����� -�������ҳ��
*/

$borrow_amount_type = array("borrow"=>"�����","vouch_borrow"=>"���������","vouch_tender"=>"һ���Զ��");

require_once(ROOT_PATH."modules/borrow/borrow.auto.php");
require_once(ROOT_PATH."modules/remind/remind.class.php");
class amountClass  extends autoClass {


	
	//��Ӷ�ȵļ�¼��borrow_amount_log��
	//user_id �û�id
	//type ���������� 
	//amount_type ��ȵ����� ��credit ���ö��  borrow_vouch �����  tender Ͷ�ʶ��
	//account  ��Ȳ����Ľ��
	//account_all �ܵĶ��
	//account_use ���ö��
	//account_nouse �����ö��
	//remark ��ȵļ�¼
	function  AddAmountLog($data){
		global $mysql;
		 //�ж��û��Ƿ����
        if (!IsExiest($data['user_id'])) {
            return "amount_user_id_empty";
        } 
		$sql = "select 1 from `{borrow_amount_log}` where nid='{$data['nid']}' ";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$data["account_use"] = 0;
			$data["account_all"] = 0;
			$sql = "select account_use,account_all from `{borrow_amount_log}` where user_id='{$data['user_id']}' and amount_type='{$data['amount_type']}' order by id desc";
			$result = $mysql->db_fetch_array($sql);
			if  ($result!=false){
				$data["account_use"] = $result['account_use'];
				$data["account_all"] = $result['account_all'];
			}
			if ($data['oprate']=="add"){
				$data["account_all"] = $data["account_all"] + $data["account"];
				$data["account_use"] = $data["account_use"] + $data["account"];
				$data["account_add"] = $data["account"];
			}elseif ($data['oprate']=="reduce"){
				$data["account_all"] = $data["account_all"] - $data["account"];
				$data["account_use"] = $data["account_use"] - $data["account"];
				$data["account_reduce"] = $data["account"];
			}elseif ($data['oprate']=="return"){
				$data["account_return"] = $data["account"];
				$data["account_use"] = $data["account_use"] + $data["account"];
			}elseif ($data['oprate']=="frost"){
				$data["account_frost"] = $data["account"];
				$data["account_use"] = $data["account_use"] - $data["account"];
			}
			
			//�����ȼ�¼
			$sql = "insert into `{borrow_amount_log}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
			foreach($data as $key => $value){
				$sql .= ",`$key` = '$value'";
			}
			$mysql->db_query($sql);
			$name = $data['amount_type'];
			$name_use = $data['amount_type']."_use";
			
			$sql = "select amounts from `{borrow_amount}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['amounts']!=false){
				$_amount = unserialize($result['amounts']);
			}
			$_amount[$name] = $data['account_all'];
			$_amount[$name_use] = $data['account_use'];
			$amounts = serialize($_amount);
			$sql = "update `{borrow_amount}` set `{$name_use}` ={$data['account_use']},`{$name}` ={$data['account_all']},`amounts`='{$amounts}' where user_id='{$data['user_id']}'";
			$mysql->db_query($sql);
		}
       
    	return $data['user_id'];
	}
	
	/**
	 * 4,��ö�ȼ�¼�б�
	 *
	 * @return Array
	 */
	function GetAmountLogList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		
		//�����û�id
        if (IsExiest($data['user_id'])!=false) {
            $_sql .= " and p1.user_id ='{$data['user_id']}'";
        }
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
            $_sql .= " and p2.username like '%{$data['username']}%'";
        }
		
		//��������
		if (IsExiest($data['amount_type'])!=false) {
            $_sql .= " and p1.amount_type = '{$data['amount_type']}'";
        }
		
		//��������
		if (IsExiest($data['type'])!=false) {
            $_sql .= " and p1.type = '{$data['type']}'";
        }
		
		$_select = " p1.*,p2.username";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from `{borrow_amount_log}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
		}			 
		
		//�ж��ܵ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		$num_sql = "select p1.oprate,sum(p1.account) as num from `{borrow_amount_log}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL group by p1.oprate ";
		$num_result =$mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $num_sql));
		$_num_result = array();
		if ($num_result!=false){
			foreach ($num_result as $key => $value){
				$_num_result[$value['oprate']] = $value['num'];
			}
		}
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		
		//add 20120831 wlz
		foreach($list as $key => $value){
			if($value['account_use'] < 0){
				$list[$key]['account_use'] = number_format(0,2);
			}
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,"oprate_add"=>$_num_result['add'],"oprate_reduce"=>$_num_result['reduce']+$_num_result['frost']+$_num_result['return']);
		return $result;
	}
	
	
	 /**
	 * ����û��Ķ�����루borrow_amount_apply��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddAmountApply($data = array()){
		global $mysql;
       //�ж��û��Ƿ����
        if (!IsExiest($data['user_id'])) {
            return "amount_user_id_empty";
        }
		$sql = "select 1 from `{borrow_amount}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$sql = "insert into `{borrow_amount}` set user_id='{$data['user_id']}'";
			$mysql->db_query($sql);
		}
		unset($data["pic_result"]);
		$data['nid'] = "apply".$user_id.time().rand(10,99);

		$sql = "insert into `{borrow_amount_apply}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        return $mysql->db_query($sql);
	}
	
	//����û��������¼��borrow_amount_apply��
	//id id 
	//user_id �û�id 
	function GetAmountApplyOne($data){
		global $mysql;
		$_sql = " where 1=1 ";
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id={$data['user_id']}  ";
		}
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id={$data['id']} ";
		}
		if (IsExiest($data['amount_type'])!=false) {
			$_sql .= " and p1.amount_type='{$data['amount_type']}' ";
		}
		if (IsExiest($data['status'])!=false || $data['status']==0) {
			$_sql .= " and p1.status='{$data['status']}' ";
		}
		$sql = "select p1.*,p2.username from `{borrow_amount_apply}` as  p1 
		left join `{users}` as p2 on p1.user_id=p2.user_id $_sql ";
		$result = $mysql ->db_fetch_array($sql);
		return $result;
	}
	
	/**
	 * �б�
	 *
	 * @return Array
	 */
	function GetAmountList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and p1.status = {$data['status']}";
		}
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.user_id = {$data['user_id']}";
		}
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p2.username like '%{$data['username']}%' ";
		}
		if (isset($data['type']) && $data['type']!=""){
			$_sql .= " and p1.type like '%{$data['type']}%' ";
		}
		$_select = 'p1.*,p2.username';
		$sql = "select SELECT from {borrow_amount} as p1 
				left join {users} as p2 on p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
				 
				 
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = intval($row['num']);
		
		//��ҳ���ؽ��
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		if  ($list!=false){
			foreach ($list as $key => $value){
				$list[$key] = self::GetAmountUsers(array("user_id"=>$value['user_id'],"amount_result"=>$value));
				$list[$key]['username'] = $value['username'];
			}
		}
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
		
	}
	
	
	
	function CheckAmountApply($data){
		global $mysql,$_G;
		
		 //�ж�ID�Ƿ����
        if (!IsExiest($data['id'])) {
            return "amount_apply_id_empty";
        } 
		
		$result = self::GetAmountApplyOne(array("id"=>$data['id']));//��ȡ��ȵ���Ϣ�����Ƿ��Ѿ�������
	
		if ($result['status']!=0){
			return "amount_apply_check_yes";
		}
		$amount_type = $result['amount_type'];
		$user_id = $result['user_id'];
		if ($data['status']==1){
			//��Ӷ�ȼ�¼
			$_data["user_id"] = $result['user_id'];
			$_data["amount_type"] = $result['amount_type'];
			$_data["type"] = "apply";
			$_data["oprate"] = $result['oprate'];
			$_data["nid"] = $result['nid'];
			$_data["account"] = $data['account'];
			$_data["remark"] = "���������ͨ��";//type ���������� 
			self::AddAmountLog($_data);
			
			//��ȡ�ɹ��������1%�Ĺ����.2012-9-17 by��Xiaowu
			if ($data['oprate']=="add"){
				require_once(ROOT_PATH."modules/account/account.class.php");
				$amount_fee=round($data['account']*$_G['system']['con_amount_fee']*0.01,2);
				$log_info["user_id"] = $result['user_id'];//�����û�id
				$log_info["nid"] = "borrow_amount_add_".$result['user_id']."_".$result['nid'];//������
				$log_info["money"] = $amount_fee;//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = $amount_fee;//֧��
				$log_info["balance_cash"] = -$amount_fee;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["type"] = "borrow_amount_add";//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] = "�û������ȳɹ��۳������";
				accountClass::AddLog($log_info);
			}
			$remind['nid'] = "amount_verify_yes";
			$remind['code'] = "amount";
			$remind['article_id'] = $_data["user_id"];
			$remind['receive_userid'] = $_data["user_id"];
			$remind['title'] = "�������{$data["account"]}��ȣ���˳ɹ�";
			$remind['content'] = "�������{$data["account"]}��ȣ���˳ɹ�";
			remindClass::sendRemind($remind);
		}else{
			$remind['nid'] = "amount_verify_no";
			$remind['code'] = "amount";
			$remind['article_id'] = $user_id;
			$remind['receive_userid'] = $user_id;
			$remind['title'] = "�������{$data["account"]}��ȣ����ʧ��,��ʧ��ԭ��{$data['verify_content']}��";
			$remind['content'] = "�������{$data["account"]}��ȣ����ʧ��,��ʧ��ԭ��{$data['verify_content']}��";
			remindClass::sendRemind($remind);
			$data['account'] = 0;
		}
		
		//������Ϣ
		$sql = "update `{borrow_amount_apply}` set status={$data['status']},verify_time='".time()."',verify_user=".$_G['user_id'].",verify_remark='{$data['verify_remark']}',account='{$data['account']}' where id = {$data['id']}";
		$mysql ->db_query($sql);
		
		return $data['id'];
	
	}
	
	
	/**
	 * �б�
	 *
	 * @return Array
	 */
	function GetAmountApplyList($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";		 
		
		if (IsExiest($data['status'])!=false) {
			$_sql .= " and p1.status = {$data['status']}";
		}
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = {$data['user_id']}";
		}
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username like '%{$data['username']}%' ";
		}		
		$_order = " order by p1.id desc";
		$_select = 'p1.*,p2.username';
		$sql = "select SELECT from {borrow_amount_apply} as p1 
				left join {users} as p2 on p1.user_id=p2.user_id
			    $_sql ORDER LIMIT ";
				 
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
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
	 * 1,��Ӷ��
	 *
	 * @param array $data =array("name"=>"�������","nid"=>"��ʶ��","default"=>"Ĭ�ϱ�ʶ��","credit_nid"=>"��������",""=>"��ע");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddAmountType($data = array()){
		global $mysql;
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "amount_type_name_empty";
        } 
		
		//�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "amount_type_nid_empty";
        }
		
		//����û����ڣ��ж��û��Ƿ����
		if (IsExiest($data['username'])!=false){
			$sql = "select user_id from `{borrow_amount_type}` where nid ='{$data['nid']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false) return "amount_type_nid_exiest";
		}
		
		
		$sql = "insert into `{borrow_amount_type}` set addtime='".time()."',addip='".ip_address()."',updatetime='".time()."',updateip='".ip_address()."',";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
    	return $mysql->db_insert_id();
	}
	
	/**
	 * 2,�޸Ķ��
	 *
	 * @param array $data =array("id"=>"id","name"=>"����","status"=>"״̬");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateAmountType($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "amount_type_name_empty";
        } 
		
		//�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "amount_type_nid_empty";
        }
		
		//����û����ڣ��ж��û��Ƿ����
		if (IsExiest($data['username'])!=false){
			$sql = "select user_id from `{borrow_amount_type}` where nid ='{$data['nid']}' and id!='{$data['id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false) return "amount_type_nid_exiest";
		}
		
		$sql = "update `{borrow_amount_type}` set updatetime='".time()."',updateip='".ip_address()."',";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
	
	/**
	 * 3,ɾ�����
	 *
	 * @param Array $data = array("id"=>"ID")
	 * @return Boolen
	 */
	function DelAmountType($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "amount_type_id_empty";
		
		$sql = "delete from `{borrow_amount_type}`  where id='{$data['id']}'";
    	$mysql -> db_query($sql);
		
		return $data['id'];
	}
	
	
	
	/**
	 * 4,��ö���б�
	 *
	 * @return Array
	 */
	function GetAmountTypeList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		
		//�����û�id
        if (IsExiest($data['user_id'])!=false) {
            $_sql .= " and p1.user_id ='{$data['user_id']}'";
        }
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
            $_sql .= " and p2.username like '%{$data['username']}%'";
        }
		
		$_select = " p1.*,p2.name as credit_name ";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from `{borrow_amount_type}` as p1 left join `{credit_class}` as p2 on p1.credit_nid=p2.nid SQL ORDER ";
		
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
	 * 6,��ö�ȵĵ�����¼
	 *
	 * @param Array $data = array("id"=>"");
	 * @return Array
	 */
	 function GetAmountTypeOne($data = array()){
		global $mysql;
		if (!IsExiest($data['id'])) return "amount_type_id_empty";
		$sql = "select p1.* from `{borrow_amount_type}` as p1  where id='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "amount_type_empty";
		return $result;
	}
	
	
	/**
	 * 7,����û��Ķ��
	 *
	 * @param Array $data = array("user_id"=>"");
	 * @return Array
	 * ����30�����µĶ��ͳһΪ��ʼ��ȣ�����30�ֵİ�һ�ּ�100����ۼ�
	 */
	 function GetAmountUsers($data = array()){
		global $mysql,$_G;
		if (!IsExiest($data['user_id'])) return "amount_user_id_empty";
		$borrow_first = isset($_G['system']['con_borrow_amount_first'])?$_G['system']['con_borrow_amount_first']:2000;
		
		if (isset($data['amount_result']) && $data['amount_result']!=""){
			$result = $data['amount_result'];
		}else{
			$sql = "select p1.* from `{borrow_amount}` as p1  where p1.user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==false){
				$sql = "insert into `{borrow_amount}` set user_id='{$data['user_id']}'";
				$mysql->db_query($sql);
				$sql = "select p1.* from `{borrow_amount}` as p1  where p1.user_id='{$data['user_id']}'";
				$result = $mysql->db_fetch_array($sql);
			}
		}
		if($_G['system']['con_borrow_credit']==1){
			$_result = borrowClass::GetBorrowCredit(array("user_id"=>$data['user_id']));
			$borrow_credit = ($_result['approve_credit']-50)*100;
			if($borrow_credit<0){
				$borrow_credit=0;
			}
		}else{
			$result["borrow"] = $borrow_first+$result['borrow'];
			$result["borrow_use"] = $borrow_first+$result['borrow_use'];
		}
		$result["borrow_nouse"] =$result["borrow"] -$result["borrow_use"];
		$result["vouch_borrow"] = $result['vouch_borrow'];
		$result["vouch_borrow_use"] = $result['vouch_borrow_use'];
		$result["vouch_borrow_nouse"] =$result["vouch_borrow"] -$result["vouch_borrow_use"];
		$result["once_amount"] = $result['once_amount'];
		$result["once_amount_use"] = $result['once_amount_use'];
		$result["once_amount_nouse"] =$result["once_amount"] -$result["once_amount_use"];
		$result["diya_borrow"] = $result['diya_borrow'];
		$result["diya_borrow_use"] = $result['diya_borrow_use'];
		$result["diya_borrow_nouse"] =$result["diya_borrow"] -$result["diya_borrow_use"];
		return $result;
	}
}
?>