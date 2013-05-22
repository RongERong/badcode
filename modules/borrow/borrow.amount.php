<?php
/******************************
 * $File: borrow.amount.php
 * $Description: ����������
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$MsgInfo["amount_user_id_empty"] = "�û�ID������";
$MsgInfo["amount_type_id_empty"] = "�������ID������";
$MsgInfo["amount_apply_id_empty"] = "�������ID������";
$MsgInfo["amount_apply_check_yes"] = "�������Ѿ�ͨ�����";
$MsgInfo["amount_apply_update_success"] = "���³ɹ�";
$MsgInfo["amount_apply_add_success"] = "��Ȳ����ɹ����뵽�������н��ж�ȵ���˲���";

$MsgInfo["borrow_amount_type_title_empty"] = "��������";

$_G["linkages"]['borrow_amount_type']['webapply'] = "��վ����";
$_G["linkages"]['borrow_amount_type']['borrow_frost'] = "����";


class borrowAmountClass  {


	
	//��Ӷ�ȵļ�¼��borrow_amount_log��
	//user_id �û�id
	//type ���������� 
	//amount_type ��ȵ����� ��credit ���ö��  borrow_vouch �����  tender Ͷ�ʶ��
	//account  ��Ȳ����Ľ��
	//account_all �ܵĶ��
	//account_use ���ö��
	//account_nouse �����ö��
	//remark ��ȵļ�¼
	public static function  AddAmountLog($data){
		global $mysql;
		 //�ж��û��Ƿ����
        if (empty($data['user_id'])) {
            return "amount_user_id_empty";
        } 
        if($data['amount_type']=="worth" || $data['amount_type']=="second" ) return "";
		$sql = "select 1 from `{borrow_amount_log}` where nid='{$data['nid']}' ";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$data["account_frost"] = 0;
			$data["account_use"] = 0;
			$data["account_once"] = 0;
			$data["account_forever"] = 0;
			$data["account_all"] = 0;
			$sql = "select * from `{borrow_amount_log}` where user_id='{$data['user_id']}' and amount_type='{$data['amount_type']}' order by id desc";
			$result = $mysql->db_fetch_array($sql);
			if  ($result!=false){
				$data["account_frost"] = empty($result['account_frost'])?0:$result['account_frost'];
				$data["account_use"] = empty($result['account_use'])?0:$result['account_use'];
				$data["account_once"] = empty($result['account_once'])?0:$result['account_once'];
				$data["account_forever"] = empty($result['account_forever'])?0:$result['account_forever'];
				$data["account_all"] = empty($result['account_all'])?0:$result['account_all'];
			}
            
			if ($data['oprate']=="add"){
				$data["account_all"] = $data["account_all"] + $data["account"];
				$data["account_use"] = $data["account_use"] + $data["account"];
                if ($data['amount_style']=="once"){
				    $data["account_once"] = $data["account_once"] + $data["account"];
                }else if ($data['amount_style']=="forever"){
                     $data["account_forever"] = $data["account_forever"] + $data["account"];
                }
			}elseif ($data['oprate']=="reduce"){
				$data["account_all"] = $data["account_all"] - $data["account"];
				$data["account_use"] = $data["account_use"] - $data["account"]; 
                if ($data['amount_style']=="once"){
				    $data["account_once"] = $data["account_once"] - $data["account"];
                }else if ($data['amount_style']=="forever"){
                     $data["account_forever"] = $data["account_forever"] - $data["account"];
                }
			}elseif ($data['oprate']=="return"){
				$data["account_return"] = $data["account"];
				$data["account_use"] = $data["account_use"] + $data["account"];
				$data["account_frost"] = $data["account_frost"] - $data["account"];
			}elseif ($data['oprate']=="frost"){
				$data["account_frost"] = $data["account_frost"] + $data["account"];
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
			$name_frost = $data['amount_type']."_frost";
			$name_once = $data['amount_type']."_once";
			$name_forever = $data['amount_type']."_forever";
			
            
            //�ж��Ƿ���amout�û�����
			$sql = "select 1 from `{borrow_amount}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==false){
				$sql = "insert into `{borrow_amount}` set user_id='{$data['user_id']}'";
			    $mysql->db_query($sql);
			}
		
			$sql = "update `{borrow_amount}` set `{$name_use}` ={$data['account_use']},`{$name_frost}` ={$data['account_frost']},`{$name_once}` ={$data['account_once']},`{$name_forever}` ={$data['account_forever']},`{$name}` ={$data['account_all']} where user_id='{$data['user_id']}'";
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
			$data['username'] = urldecode($data['username']);
            $_sql .= " and p2.username = '{$data['username']}'";
        }
		
		//��������
		if (IsExiest($data['amount_type'])!=false) {
            $_sql .= " and p1.amount_type = '{$data['amount_type']}'";
        }
		
		//��������
		if (IsExiest($data['type'])!=false) {
            $_sql .= " and p1.type = '{$data['type']}'";
        }
		
		$_select = " p1.*,p2.username,p3.name as type_name";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from `{borrow_amount_log}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id  left join `{borrow_amount_type}` as p3 on p1.amount_type=p3.nid SQL ORDER LIMIT";
		
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
		
		$data['nid'] = $data["type"]."_".$data['user_id']."_".time();

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
		if (IsExiest($data['status'])!=false || $data['status']=="0") {
			$_sql .= " and p1.status='{$data['status']}' ";
		}
		$sql = "select p1.*,p2.username,p3.name as type_name from `{borrow_amount_apply}` as  p1 
		left join `{users}` as p2 on p1.user_id=p2.user_id left join `{borrow_amount_type}` as p3 on p3.nid=p1.amount_type $_sql ";
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
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username = '{$data['username']}' ";
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
			$_data["amount_style"] = $result['amount_style'];
			$_data["type"] = $result['type'];
			$_data["oprate"] = $result['oprate'];
			$_data["nid"] = $result['nid'];
			$_data["account"] = $data['account'];
			$_data["remark"] = "���������ͨ��";//type ���������� 
			$result = self::AddAmountLog($_data);
           
			$remind['nid'] = "amount_success";
            $remind['remind_nid'] =  "amount_success_".$_data['user_id']."_".$data["id"];
			$remind['code'] = "amount";
			$remind['article_id'] = $_data["id"];
			$remind['receive_userid'] = $_data["user_id"];
			$remind['title'] = "�������{$data["account"]}��ȣ���˳ɹ�";
			$remind['content'] = "�������{$data["account"]}��ȣ���˳ɹ��������ڿ�����ƽ̨�Ͻ��н�";
			remindClass::sendRemind($remind);
		}else{
			$remind['nid'] = "amount_false";
			$remind['receive_userid'] = $user_id;
            $remind['remind_nid'] =  "amount_false_".$user_id."_".$data["id"];
			$remind['code'] = "amount";
			$remind['article_id'] = $data["id"];
			$remind['title'] = "�������{$data["account"]}������ʧ��";
			$remind['content'] = "�������{$data["account"]}��ȣ����ʧ�ܣ���ʧ��ԭ��{$data['verify_remark']}��";
			remindClass::sendRemind($remind);
			$data['account'] = 0;
		}
		
		//������Ϣ
		$sql = "update `{borrow_amount_apply}` set status={$data['status']},verify_time='".time()."',verify_userid=".$_G['user_id'].",verify_remark='{$data['verify_remark']}',verify_contents='{$data['verify_contents']}',account='{$data['account']}' where id = {$data['id']}";
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
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%' ";
		}	
		if (IsExiest($data['status'])!=false || $data['status']=="0") {
			$_sql .= " and p1.status  = {$data['status']}";
		}	
		if (IsExiest($data['amount_type'])!=false) {
			$_sql .= " and p1.amount_type = '{$data['amount_type']}'";
		}		
		$_order = " order by p1.id desc";
		if(IsExiest($data['order'])!=false){
			$_order = " order by p1.status asc,p1.id desc";
		}
		$_select = 'p1.*,p2.username,p3.name as type_name';
		$sql = "select SELECT from {borrow_amount_apply} as p1 
				left join {users} as p2 on p1.user_id=p2.user_id
                left join `{borrow_amount_type}` as p3 on p1.amount_type=p3.nid 
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
	 * 7,����û��Ķ��
	 *
	 * @param Array $data = array("user_id"=>"");
	 * @return Array
	 * ����30�����µĶ��ͳһΪ��ʼ��ȣ�����30�ֵİ�һ�ּ�100����ۼ�
	 */
	 function GetAmountUsers($data = array()){
		global $mysql,$_G;
		if (!IsExiest($data['user_id'])) return "amount_user_id_empty";
        
		$sql = "select p1.* from `{borrow_amount}` as p1  where p1.user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$sql = "insert into `{borrow_amount}` set user_id='{$data['user_id']}'";
			$mysql->db_query($sql);
			$sql = "select p1.* from `{borrow_amount}` as p1  where p1.user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
		}
        
        //��ȡ���������״̬
        $type_result = self::GetAmountTypeList(array("limit"=>"all"));
        foreach ($type_result as $key => $value){
           $result[$value["nid"]."_status"] = $value['status'];
        }

        //��ֵ���
        require_once(DEAYOU_PATH."modules/account/account.class.php");
        $account_result = accountClass::GetAccountUsers(array("user_id"=>$data['user_id']));
		
        $RecoverCount = borrowCountClass::GetUsersRecoverCount(array("user_id"=>$data['user_id']));
        $RepayCount = borrowCountClass::GetUsersRepayCount(array("user_id"=>$data['user_id']));
		
        //$result["worth"] = $account_result["total"] - $account_result['repay'] - $account_result['frost_cash'];
        
		$result["worth"] = round(($account_result["balance"] + $RecoverCount['tender_now_account'] + $RecoverCount['recover_wait_capital'] )*0.8,2)- $RepayCount['repay_wait_account'];
		
		
		if($result["worth"]<0){$result["worth"]=0;}
        if ($data['type']!=""){
            //���ö��
            if ($data['type']=="credit"){
                $result['account_all'] = $result['credit'];
                $result['account_use'] = $result['credit_use'];
                $result['account_frost'] = $result['credit_frost'];
            }elseif ($data['type']=="vouch"){
                $result['account_all'] = $result['vouch'];
                $result['account_use'] = $result['vouch_use'];
                $result['account_frost'] = $result['vouch_frost'];
            }elseif ($data['type']=="pawn"){
                $result['account_all'] = $result['pawn'];
                $result['account_use'] = $result['pawn_use'];
                $result['account_frost'] = $result['pawn_frost'];
            }elseif ($data['type']=="vest"){
                $result['account_all'] = $result['vest'];
                $result['account_use'] = $result['vest_use'];
                $result['account_frost'] = $result['vest_frost'];
            }
            //��ֵ���[���˻��ܶ�����˻��ֽ�����Ӧ���˿-�����ܶ�-���ִ�����ܶ�]*0.8
            elseif ($data['type']=="worth"){
				$result['account_all'] = $result["worth"];
                $result['account_use'] = $result["worth"];
            }
        }
		return $result;
	}
    
    function GetAmountUserWorth($data=array()){
		global $mysql;
		$result=accountClass::GetAccountUsers(array("user_id"=>$data['user_id']));
		$account=$result['total'];
		$result=borrowClass::GetUserCount(array("user_id"=>$data['user_id']));
		$waitrepay=$result['borrow_repay_wait'];
		$sql="select sum(total) as num from `{account_cash}` where user_id='{$data['user_id']}' and status=0";
		$result=$mysql->db_fetch_array($sql);		//$_result['all']=round(($account-$waitrepay)*0.8-$waitrepay-$result['num'],2);
		$_result['all']=round(($account-$waitrepay-$result['num'])*0.8,2);
		if($_result['all'] < 0){
			$_result['all']	= 0;
		}
		return $_result;
	}
    
    
    function GetAmountTypeList($data = array()){
		global $mysql,$_G;		
        
		if ($_G['borrow_amount_list']!="") return $_G['borrow_amount_list'];
        
        $_sql = " where 1=1 ";
		$_select = "p1.*";	
        
        if ($data['status']!="" || $data['status']=="0"){
			$_sql .= " and p1.status= '{$data['status']}'";
		}
         
        if ($data['nid']!=""){
            $_nid = explode(",",$data['nid']);
            if (count($_nid)>0){
                foreach ($_nid as $_k => $_v){
                    $_nid[$_k] = "'{$_v}'";
                }
                $data['nid'] = join(",",$_nid);
            }
			$_sql .= " and p1.nid in ({$data['nid']})";
		}
		$_order = " order by p1.id ";
        
		$sql = "select SELECT from  `{borrow_amount_type}` as p1  SQL ORDER LIMIT";
		
       ;
		//�Ƿ���ʾȫ������Ϣ
		if ( IsExiest($data['limit'])!= false){
			if ($data['limit'] != "all" ){ $_limit = "  limit ".$data['limit']; }
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
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
	
		
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
	}
    
     /**
	 * ��ȡ�������������
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetAmountTypeOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = {$data['id']}";
		}
		$_select = "p1.*";
		$sql = "select $_select from `{borrow_amount_type}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
    
    /**
	 * �޸Ľ��������
	 *
	 * @param array ;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateAmountType($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['title'])) {
            return "borrow_amount_type_title_empty";
        } 
		
		$sql = "update `{borrow_amount_type}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
}
?>