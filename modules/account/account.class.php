<?
/******************************
 * $File: account.class.php
 * $Description: �ʽ����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("account.model.php");
require_once("account.excel.php");
require_once("account.tongji.php");
require_once(ROOT_PATH."modules/credit/credit.class.php");

class accountClass{
	
	/**
	 * �ʽ������б�
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username = '".urldecode($data['username'])."'";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by p1.id desc";
		$sql = "select SELECT from {account} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])=="true"){
			
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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

		//add 20120831 wlz
		foreach($list as $key => $value){
			if($value['frost'] < 0){
				$list[$key]['frost'] = number_format(0,2);
			}
		}
		//�ܿ������ �ܶ�����
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('sum(`balance`) as num', $_sql,'', ''), $sql));
        $total_balance = $row['balance'];
        $row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('sum(`frost`) as num', $_sql,'', ''), $sql));
        $total_frost = $row['frost'];
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'total_balance' => $total_balance,'total_frost' => $total_frost);
		
		return $result;
	}
	
	
	/**
	 * �ʽ��¼�б�
	 *
	 * @return Array
	 */
	function GetLogList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username = '".urldecode($data['username'])."'";
		}
		
		if (IsExiest($data['type'])!=false) {
			$_sql .= " and p1.type = '{$data['type']}'";
		}
		
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$_sql .= " and p1.addtime >= '".strtotime($data['dotime1'])."'";
		}
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$_sql .= " and p1.addtime <= '".strtotime($data['dotime2'])."'";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by p1.id desc ";
		$sql = "select SELECT from {account_log} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])=="true"){
			
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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
		
        $row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('sum(`income_new`) as num', $_sql,'', ''), $sql));
        $total_income = $row['num'];
        $row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('sum(`expend_new`) as num', $_sql,'', ''), $sql));
        $total_expend = $row['num'];

		$total_num = $total_income-$total_expend;
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'total_income' => $total_income,'total_expend' => $total_expend,'total_num' => $total_num);
		
		return $result;
	}
	
	/**
	 * ��վ�����б�
	 *
	 * @return Array
	 */
	function GetBalanceList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p1.remark like '".urldecode($data['username'])."'";
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
		
		 if (IsExiest($data['type'])!=false) {
			$_sql .= " and p1.type = '{$data['type']}'";
		}else{
			//$_sql .= " and (type='borrow_success_manage' or type='borrow_success_account'  or type='borrow_change_sell_fee' or type='vip_success' or type='recharge_fee' or type='cash_fee' or type='late_repay_web' or type='borrow_change_buy_fee' or type='advance_web' or type='web_daicha' or type='web_tender_late_repay_yes' or type='repay_interest_service' or type='edu_approve' or type='realname_approve' or type='tender_late_repay_yes')";
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by p1.addtime desc ";		
		$sql = "select SELECT from {account_balance} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])==1){
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select,'','order by p1.addtime desc,p1.id desc', ""), $sql));
			$title = array("����","���","����","֧��","ʱ��");
			$linkage_result = $_G['linkage'];
			foreach ($result as $key => $value){
				if ($value['type']=="borrow_success_manage" || $value['type']=="borrow_success_account" || $value['type']=="borrow_change_sell_fee" || $value['type']=="vip_success" || $value['type']=="recharge_fee" || $value['type']=="cash_fee" || $value['type']=="borrow_change_buy_fee" || $value['type']=="advance_web" || $value['type']=="web_daicha" || $value['type']=="web_tender_late_repay_yes" || $value['type']=="repay_interest_service" || $value['type']=="realname_approve" || $value['type']=="edu_approve" || $value['type']=="tender_late_repay_yes"){
					if ($value['status']==0){ $value['status']="�����"; }elseif ($value['status']==1){$value['status']="��ֵ�ɹ�";}elseif ($value['status']==2){$value['status']="��ֵʧ��";}
					if ($value['type']=="recharge"){
						$_data[$key] = array($value['type'],$value['money'],$value['income'],$value['expend'],date("Y-m-d",$value['addtime']));
					}else{
						$_data[$key] = array($value['type'],$value['money'],$value['expend'],$value['income'],date("Y-m-d",$value['addtime']));
					}
				}
			}
			self::exportData(date("Y-m-d",time())."��վ���ü�¼����",$title,$_data);
			exit;
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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
		
		//�����ÿ����0.1Ԫ
		$sql = "select count(1) as gongyijin from `{account_balance}` where type='borrow_success_manage'";
		$money=$mysql->db_fetch_array($sql);
		$gongyijin=$money['gongyijin']/10;
		if (IsExiest($data['type'])!=false) {
			$_sql1= " and type = '{$data['type']}'";
		}else{
			$_sql1= " and (type='borrow_success_manage' or type='borrow_success_account'  or type='borrow_change_sell_fee' or type='vip_success' or type='recharge_fee' or type='cash_fee' or type='late_repay_web' or type='borrow_change_buy_fee' or type='advance_web' or type='web_daicha' or type='web_tender_late_repay_yes' or type='repay_interest_service' or type='edu_approve' or type='realname_approve' or type='tender_late_repay_yes')";
		}
		$sql = "select sum(money) as chengjiaofei from `{account_balance}` where 1=1 $_sql1";
		$money=$mysql->db_fetch_array($sql);
		$chengjiaofei=$money['chengjiaofei'];
		foreach ($list as $key => $value){
			if ($value['type']=="borrow_success_manage" || $value['type']=="borrow_success_account" || $value['type']=="borrow_change_sell_fee" || $value['type']=="vip_success" || $value['type']=="recharge_fee" || $value['type']=="cash_fee" || $value['type']=="borrow_change_buy_fee" || $value['type']=="advance_web" || $value['type']=="web_daicha" || $value['type']=="web_tender_late_repay_yes" || $value['type']=="repay_interest_service" || $value['type']=="realname_approve" || $value['type']=="edu_approve" || $value['type']=="tender_late_repay_yes"){
				$table[$key]['id']=$value['id'];
				$table[$key]['total']=$value['total'];
				$table[$key]['money']=$value['money'];
				$table[$key]['balance']=$value['balance'];
				$table[$key]['type']=$value['type'];
				$table[$key]['income']=$value['income'];
				$table[$key]['expend']=$value['expend'];
				$table[$key]['username']=$value['username'];
				$table[$key]['remark']=$value['remark'];
				$table[$key]['addtime']=$value['addtime'];
				$table[$key]['addip']=$value['addip'];
			}
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'gongyijin'=>$gongyijin,'chengjiaofei'=>$chengjiaofei,'table'=>$table);
		
		return $result;
		
	}
	
	
	/**
	 * �û������б�
	 *
	 * @return Array
	 */
	function GetUsersList($data = array()){
		global $mysql;

		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username = '".urldecode($data['username'])."'";
		}
		
		//�������� add 20120830 zy
		if (IsExiest($data['type'])!=false) {
			$_sql .= " and p1.type = '{$data['type']}'";
		}
		if (IsExiest($data['dotime1']) != false){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['dotime2'])!=false){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		$_select = "p1.*,p2.username";
		$_order = " order by p1.id desc ";
		$sql = "select SELECT from {account_users} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])=="true"){
			
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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
		
			$sql="select sum(p1.income) as _income,sum(p1.expend) as _expend from `{account_users}` as p1 left join {users} as p2 on p1.user_id=p2.user_id $_sql";
		$all_money=$mysql->db_fetch_array($sql);
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'account_income'=>round($all_money['_income'],2),'account_expend'=>round($all_money['_expend'],2),'account_all'=>round($all_money['_income'],2)-round($all_money['_expend'],2));
		
		return $result;
		
	}
	
	/**
	 * ��ȡ�����б�
	 *
	 * @return Array
	 */
	function GetCashList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username = '".urldecode($data['username'])."'";
		}
		
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
		
		//����״̬
		if (IsExiest($data['status'])!=false || $data['status']=="0") {
			$_sql .= " and p1.status = '{$data['status']}'";
		}
		
		$_select = "p1.*,p1.account as account_all,p2.username,p3.realname,p4.bank as bank_name";
		$_order = " order by p1.addtime desc ";
		$sql = "select SELECT from {account_cash} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				 left join {account_users_bank} p4 on p1.bank_id=p4.id
				 left join {users_info} as p3 on p1.user_id=p3.user_id
				SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])=="true"){
		
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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
		
		//add 20120831 wlz
		foreach($list as $key => $value){
			if($value['status']!=1)continue;
			$all += $value['total'];
			$credited_all += $value['credited'];
			$fee_all += $value['fee'];
		}
		
		foreach($list as $key=>$value){		
				$list[$key]['account'] = substr($value['account'],0,4)."****".substr($value['account'],-3,3);
			}
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'all' => $all,'credited_all'=>$credited_all,'fee_all'=>$fee_all);
		
		return $result;
		
	}
	
	/**
	 * �����˻��б�
	 *
	 * @return Array
	 */
	function GetBankList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['keywords'])!=false) {
			$_sql .= " and p1.`name` like '%{$data['keywords']}%'";
		}
		
		$_select = "p1.*";
		$_order = " order by p1.addtime desc ";
		$sql = "select SELECT from {account_bank} as p1 
				SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])=="true"){
			
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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
	 * �����˻��б�
	 *
	 * @return Array
	 */
	function GetUsersBankList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";
        
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username = '".urldecode($data['username'])."'";
		}
		if (IsExiest($data['realname'])!=false) {
			$_sql .= " and p3.realname = '".urldecode($data['realname'])."'";
		}

		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		$_order = " order by p1.id desc";
		$_select = "p1.*,p2.username,p3.realname,p4.name as bank_name";
		$sql = "select SELECT from `{account_users_bank}` as p1
		 left join `{users}` as p2 on p1.user_id=p2.user_id 
		 left join `{account_bank}` as p4 on p1.bank=p4.id 
		 left join `{approve_realname}` as p3 on p1.user_id=p3.user_id SQL ORDER LIMIT";
		
		//��������
		if (IsExiest($data['excel'])=="true"){
			
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$result = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			foreach($result as $key=>$value){		
				$result[$key]['account'] = substr($value['account'],0,4)."****".substr($value['account'],-3,3);
			}
			return $result;
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
	 * 1,��ȡ��ֵ�б�
	 *
	 * @param Array $data = arrray("user_id"=>"�û�id","status"=>"��ֵ״̬","username"=>"�û���","email"=>"����")
	 * @return Boolen
	 */
	function GetRechargeList($data = array()){
		global $mysql;

		$_sql = "where 1=1 ";
		
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		if (IsExiest($data['dotime1']) != false){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['dotime2'])!=false){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		//������״̬
		if (IsExiest($data['status'])!=false || $data['status']=="0") {
			$_sql .= " and p1.status = '{$data['status']}'";
		}
		
		//�����û���
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and p2.username = '".urldecode($data['username'])."'";
		}
		
		//��������
		if (IsExiest($data['email'])!=false) {
			$_sql .= " and p2.email like '%{$data['email']}%'";
		}
		
		//��������
		if (IsExiest($data['type'])!=false || $data['type']==0 ) {
			if($data['type'] != ""){
				$_sql .= " and p1.type = '{$data['type']}'";
			}
		}

		$_select = "p1.*,p2.username,p3.name as payment_name";
		if (IsExiest($data['order'])!=false) {
			if ($data['order']=="addtime_down"){
				$_order = " order by p1.addtime desc";
			}else{
				$_order = " order by p1.addtime asc";
			}
		}else{
			$_order = " order by p1.status asc,p1.addtime desc";
		}
		$sql = "select SELECT from {account_recharge} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				 left join {account_payment} as p3 on p1.payment=p3.id
				SQL ORDER LIMIT";	
		
		//��������
		if (IsExiest($data['excel'])=="true"){
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  '  order by p1.addtime desc,id desc', ""), $sql));
			$title = array("����","֧����ʽ","��ֵ���","��ֵʱ��","��ע","״̬","����ע");
			$linkage_result = $_G['linkage'];
			foreach ($result as $key => $value){
				if ($value['type']==1) {
					$value['type']="���ϳ�ֵ";
				}else{
					$value['type']="���³�ֵ";
				}
				if ($value['status']==0){ $value['status']="�����"; }elseif ($value['status']==1){$value['status']="��ֵ�ɹ�";}elseif ($value['status']==2){$value['status']="��ֵʧ��";}
				$_data[$key] = array($value['type'],$value['payment_name'],$value['money'],date("Y-m-d",$value['addtime']),$value['remark'],$value['remark'],$value['status'],$value['verify_remark']);
			}
			exportData(date("Y-m-d",time())."��ֵ��¼����",$title,$_data);
			exit;
		} 
		//�Ƿ���ʾȫ������Ϣ
		elseif (IsExiest($data['limit'])!=false){
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
			$all_recharge+=$value['money'];
			$all_fee+=$value['fee'];
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'all_recharge' => $all_recharge,'all_fee'=>$all_fee);
		
		return $result;
	}
	
	
	/**
	 * �鿴
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetRecharge($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = {$data['id']}";
		} 
		
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p2.user_id = {$data['user_id']}";
		}
		
		if (IsExiest($data['nid'])!=false) {
			$_sql .= " and p1.nid = '{$data['nid']}'";
		}
		$_select = "p1.*,p2.username,p2.email,p3.name as payment_name,p4.username as verify_username";
		$sql = "select $_select from `{account_recharge}` as p1 
				 left join `{users}` as p2 on p1.user_id=p2.user_id
				 left join `{account_payment}` as p3 on p1.payment = p3.id
				 left join `{users}` as p4 on p1.verify_userid=p4.user_id
				$_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
			
	}
	/*
	  ͳ�Ƴ�ֵ add 20120831 wdf
	*/
	function RechargeTongji($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		
		if (IsExiest($data['type'])!=false || $data['type'] == 0) {
			if($data['type']!=""){
				$_sql .= " and type = {$data['type']}";
			}
		} 
		
		if (IsExiest($data['username'])!=false) {
			$_sql .= " and name = {$data['username']}";
		} 
		
		$sql = "select sum(money) as recharge_sum from `{account_recharge}` $_sql ";
		$result = $mysql->db_fetch_arrays($sql);

		return $result;
	}
	
	
	/**
	 * ��ӳ�ֵ��¼
	 *
	 * @param Array $data = array("user_id"=>"�û�id","status"=>"״̬","money"=>"�������","remark"=>"��ע","type"=>"��ֵ����","payment"=>"��ֵ��ʽ","fee"=>"����","nid"=>"������");
	 * @return Boolen
	 */
	function AddRecharge($data = array()){
		global $mysql;
        
		//�ж��û�id�Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_user_id_empty";
		}
		$sql = "insert into `{account_recharge}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$result =  $mysql->db_query($sql);
		$id = $mysql->db_insert_id();
		
		return $id;
	}
	
	
	/**
	 * ��ֵ���
	 *
	 * @param Array $data =array("nid"=>"������","verify_remark"=>"��˱�ע","status"=>"���״̬")
	 * @return Boolen
	 */
	function VerifyRecharge($data = array()){
		global $mysql,$MsgInfo;
		
		//�ж϶������Ƿ����
		if (!IsExiest($data['nid'])) {
			return "account_nid_empty";
		}
		
		
		$sql = "select p1.*,p2.username,p3.name as payment_name from `{account_recharge}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id left join `{account_payment}` as p3 on p1.payment=p3.id where p1.`nid`='{$data['nid']}'";
		$result = $mysql->db_fetch_array($sql);
		
		$recharge_userid = $result['user_id'];//��ֵ�û�id
		$recharge_nid = $result['nid'];//��ֵ�ܶ�
		$recharge_money = $result['money'];//��ֵ�ܶ�
		$recharge_fee = $result['fee'];//����
		$recharge_balance = $result['balance'];//���
		$username = $result['username'];//�û���
		$payment = $result['payment_name'];//�û���
		$id = $result['id'];
		//�ж϶������Ƿ����
		if ($result==false) return "account_recharge_not_exiest";
		
		//�ж��Ƿ��Ѿ����
		if ($result['status']!=0) return "account_recharge_yes_verify";
		
		$sql = "select count(1) as num from `{account_recharge}` where `nid`='{$data['nid']}'";
		$result = $mysql->db_fetch_array($sql);
		//�ж϶������Ƿ�����
		if ($result['num']>1) return "account_recharge_nid_error";
		
		$sql = "update `{account_recharge}` set status='{$data['status']}',verify_time='".time()."',verify_userid='".$data['verify_userid']."',verify_remark='".$data['verify_remark']."',verify_content='".$data['verify_content']."' where nid = '{$data['nid']}'";
        $mysql->db_query($sql);
		
		if ($data['status']==1){
			$log_info["user_id"] = $recharge_userid;//�����û�id
			$log_info["nid"] = "recharge_".$data['nid'];//������ 
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 1;//
            $log_info["code"] = "account";//
    		$log_info["code_type"] = "recharge_success";//
    		$log_info["code_nid"] = $data['nid'];//
			$log_info["money"] = $recharge_money;//�������
			$log_info["income"] = $log_info["money"];//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = 0;//�����ֽ��
			$log_info["balance_frost"] = $log_info["money"];//�������ֽ��
			$log_info["frost"] = 0;//������
			$log_info["await"] = 0;//���ս��
			$log_info["repay"] = 0;//�������
			$log_info["type"] = "recharge";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "ͨ��{$payment}��ֵ��{$log_info['money']}Ԫ";//��ע
			$result = self::AddLog($log_info);
			if ($recharge_fee>0){
				$log_info["user_id"] = $recharge_userid;//�����û�id
				$log_info["nid"] = "recharge_fee_".$data['nid'];//������
                $log_info["account_web_status"] = 1;//
                $log_info["account_user_status"] = 1;//
                $log_info["code"] = "account";//
        		$log_info["code_type"] = "recharge_success_fee";//
        		$log_info["code_nid"] = $data['nid'];//
				$log_info["money"] = $recharge_fee;//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = $log_info["money"];//֧��
				$log_info["balance_cash"] = 0;//�����ֽ��
				$log_info["balance_frost"] = -$log_info["money"];//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["repay"] = 0;//�������
				$log_info["type"] = "recharge_fee";//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] = "���š�{$recharge_nid}����ֵ�ɹ��۳�{$recharge_fee}Ԫ������";//��ע
				$result = self::AddLog($log_info);
			}
			
			//�����û�������¼
			$user_log["user_id"] = $recharge_userid;
			$user_log["code"] = "account";
			$user_log["type"] = "recharge";
			$user_log["operating"] = "success";
			$user_log["article_id"] = $data['nid'];
			$user_log["result"] = 1;
			$user_log["content"] =  str_replace(array('#keywords#'), array($username), $MsgInfo["account_recharge_userlog_success"].$log_info["remark"] );
			usersClass::AddUsersLog($user_log);	
			
			
			//��ֵ�ɹ�վ�������� 
			$remind['nid'] = "recharge_success";
			$remind['receive_userid'] = $recharge_userid;
            $remind['remind_nid'] =  "recharge_success_".$recharge_userid."_".$data['nid'];
			$remind['code'] = "account";
			$remind['article_id'] = $recharge_userid;
			$remind['title'] = "��ֵ�ɹ�";
			$remind['content'] = "��ֵ".$recharge_money."�ɹ���";
			remindClass::sendRemind($remind);
			
		}else{
			//��ֵʧ��վ�������� 
			$remind['nid'] = "recharge_false";
			$remind['receive_userid'] = $recharge_userid;
            $remind['remind_nid'] =  "recharge_false_".$recharge_userid."_".$data['nid'];
			$remind['code'] = "account";
			$remind['article_id'] = $recharge_userid;
			$remind['title'] = "��ֵʧ��";
			$remind['content'] = "��ֵ".$recharge_money."��˲�ͨ����ʧ��ԭ��{$data['verify_remark']}";
			remindClass::sendRemind($remind);
		}
		
		
		return $id;
	}
	
	//��ֵͳ��
	function GetRechargeCount($data){
		global $mysql;
		$_sql = " where 1=1 and status=1";
		if ($data['user_id']!=""){
			$_sql .= " and p1.user_id={$data['user_id']}";
		}
		/* $sql = "select sum(p1.money) as account,sum(p1.fee) as account_fee,sum(p1.balance) as account_balance,p1.type  from `{account_recharge}` as p1 {$_sql} group by p1.type ";
		$result = $mysql->db_fetch_arrays($sql);
		if ($result==false) return "";
		$_arr = array("0"=>0,"1"=>1);//����0����1
		foreach ($result as $key => $value){
			$_result[$_arr[$value['type']]] = $value;
		}
		$_result['all']['account'] = $_result['1']['account']+ $_result['0']['account']+ $_result['other']['account'];
		$_result['all']['account_balance'] = $_result['1']['account_balance']+ $_result['0']['account_balance']+ $_result['other']['account_balance'];
		$_result['all']['account_fee'] = $_result['1']['account_fee']+ $_result['0']['account_fee']+ $_result['other']['account_fee']; 
		return $_result;*/
		$sql = "select sum(p1.money) as account,sum(p1.fee) as account_fee,sum(p1.balance) as account_balance,p1.type  from `{account_recharge}` as p1 {$_sql} ";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "";	
		return $result;
		
	}
	
	/**
	 * ��ֵ���
	 *
	 * @param Array $data =array("nid"=>"������","verify_remark"=>"��˱�ע","status"=>"���״̬")
	 * @return Boolen
	 */
	function AddLog($data = array()){
		global $mysql;
		
		//��һ������ѯ�Ƿ����ʽ��¼
		$sql = "select * from `{account_log}` where `nid` = '{$data['nid']}'";
		$result = $mysql -> db_fetch_array($sql);
		if ($result['nid']!="") return "account_log_nid_exiest";
		
		//�ڶ�������ѯԭ�������ʽ�
		$sql = "select * from `{account}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			$sql = "insert into `{account}` set user_id='{$data['user_id']}',total=0";
			$mysql->db_query($sql);
			$sql = "select * from `{account}` where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
		}
		
		//�������������û��Ĳ����¼
		$sql = "insert into `{account_log}` set ";
		
		$sql .= "nid='{$data['nid']}',";
		$sql .= "borrow_nid='{$data['borrow_nid']}',";
		$sql .= "account_web_status='{$data['account_web_status']}',";
		$sql .= "account_user_status='{$data['account_user_status']}',";
		$sql .= "code='{$data['code']}',";
		$sql .= "code_type='{$data['code_type']}',";
		$sql .= "code_nid='{$data['code_nid']}',";
		$sql .= "user_id='{$data['user_id']}',";
		$sql .= "type='{$data['type']}',";
		$sql .= "money='{$data['money']}',";
		$sql .= "remark='{$data['remark']}',";
		$sql .= "to_userid='{$data['to_userid']}',";
		
		$sql .= "balance_cash_new='{$data['balance_cash']}',";
		$sql .= "balance_cash_old='{$result['balance_cash']}',";
		$sql .= "balance_cash=balance_cash_new+balance_cash_old,";
		
		$sql .= "balance_frost_new='{$data['balance_frost']}',";
		$sql .= "balance_frost_old='{$result['balance_frost']}',";
		$sql .= "balance_frost=balance_frost_new+balance_frost_old,";
		
		$sql .= "balance_new=balance_cash_new+balance_frost_new,";
		$sql .= "balance_old='{$result['balance']}',";
		$sql .= "balance=balance_new+balance_old,";
		
		$sql .= "income_new='{$data['income']}',";
		$sql .= "income_old='{$result['income']}',";
		$sql .= "income=income_new+income_old,";
		
		$sql .= "expend_new='{$data['expend']}',";
		$sql .= "expend_old='{$result['expend']}',";
		$sql .= "expend=expend_new+expend_old,";
		
		$sql .= "frost_new='{$data['frost']}',";
		$sql .= "frost_old='{$result['frost']}',";
		$sql .= "frost=frost_new+frost_old,";
		
		$sql .= "await_new='{$data['await']}',";
		$sql .= "await_old='{$result['await']}',";
		$sql .= "await=await_new+await_old,";
		
		$sql .= "repay_new='{$data['repay']}',";
		$sql .= "repay_old='{$result['repay']}',";
		$sql .= "repay=repay_new+repay_old,";
		
		$sql .= "total_old='{$result['total']}',";
		$sql .= "total=balance+frost+await,";
		$sql .=" `addtime` = '".time()."',`addip` = '".ip_address()."'";
		$mysql->db_query($sql);
		$id = $mysql->db_insert_id();
		
        
		$sql = "select * from `{account_log}` where user_id='{$data['user_id']}' and id='{$id}'";
		$result = $mysql->db_fetch_array($sql);
		
		//���Ĳ��������û���
		$sql = "update `{account}` set income={$result['income']},expend='{$result['expend']}',";
		$sql .= "balance_cash={$result['balance_cash']},balance_frost={$result['balance_frost']},";
		$sql .= "frost={$result['frost']},";
		$sql .= "await={$result['await']},";
		$sql .= "balance={$result['balance']},";
		$sql .= "repay={$result['repay']},";
		$sql .= "total={$result['total']}";
		$sql .=" where user_id='{$data['user_id']}'";
		$mysql->db_query($sql);
		
      
		//�������������ܷ���
		$sql = "select * from `{account_balance}` where `nid` = '{$data['nid']}'";
		$result = $mysql -> db_fetch_array($sql);
		if ($result==false){
			//������վ�Ĳ����
			$sql = "select * from `{account_balance}` order by id desc";
			$result = $mysql -> db_fetch_array($sql);
			if ($result==false){
				$result['total'] = 0;
				$result['balance'] = 0;
			}
			$total = $result['total'] + $data['income'] + $data['expend'];
			$sql = "insert into `{account_balance}` set total='{$total}',balance={$result['balance']}+".$data['income'].",income='{$data['income']}',expend='{$data['expend']}',type='{$data['type']}',`money`='{$data['money']}',user_id='{$data['user_id']}',nid='{$data['nid']}',remark='{$data['remark']}', `addtime` = '".time()."',`addip` = '".ip_address()."'";
			$mysql->db_query($sql);
		}
            
		if ($data['account_web_status']==1){
    		//�������������û����ܷ���
    		$sql = "select * from `{account_web}` where `nid` = '{$data['nid']}'";
    		$result = $mysql -> db_fetch_array($sql);
    		if ($result==false){
    			//�����û��Ĳ����
    			$sql = "select * from `{account_web}`  order by id desc ";
    			$result = $mysql -> db_fetch_array($sql);
    			if ($result==false){
    				$result['total'] = 0;
    				$result['balance'] = 0;
    			}
    			$total = $result['total'] - $data['income'] + $data['expend'];
    			$sql = "insert into `{account_web}` set total='{$total}',balance={$result['balance']}-".$data['income']."+".$data['expend'].",income='{$data['income']}',expend='{$data['expend']}',type='{$data['type']}',`money`='{$data['money']}',user_id='{$data['user_id']}',nid='{$data['nid']}',remark='{$data['remark']}', `addtime` = '".time()."',`addip` = '".ip_address()."'";
    			$mysql->db_query($sql);
    		}
    	}	
		
		 if ($data['account_user_status']==1){
    		//�������������û����ܷ���
    		$sql = "select * from `{account_users}` where `nid` = '{$data['nid']}'";
    		$result = $mysql -> db_fetch_array($sql);
    		if ($result==false){
    			//�����û��Ĳ����
    			$sql = "select * from `{account_users}`  order by id desc ";
    			$_result = $mysql -> db_fetch_array($sql);
    			if ($_result==false){
    				$_result['total'] = 0;
    				$_result['balance'] = 0;
    			}
    			$total = $_result['total'] + $data['income'] - $data['expend'];
    			$sql = "insert into `{account_users}` set total='{$total}',balance={$_result['balance']}+".$data['income']."-".$data['expend'].",income='{$data['income']}',expend='{$data['expend']}',type='{$data['type']}',`money`='{$data['money']}',user_id='{$data['user_id']}',nid='{$data['nid']}',remark='{$data['remark']}', `addtime` = '".time()."',`addip` = '".ip_address()."'";
    			$mysql->db_query($sql);
    		}
    	}
        
		return $data['nid'];
		
	}
	
	/**
	 * �������
	 *
	 * @param Array $data =array("name"=>"����","nid"=>"��ʶ��","litpic"=>"����ͼ","cash_money"=>"���ֽ��","reach_day"=>"����ʱ��")
	 * @return Boolen
	 */
	function AddBank($data = array()){
		global $mysql;
		//�ж������Ƿ����
		if (!IsExiest($data['name'])) {
			return "account_bank_name_empty";
		}
		
		//�жϱ�ʶ���Ƿ����
		if (!IsExiest($data['nid'])) {
			return "account_bank_nid_empty";
		}
		
		$sql = "insert into `{account_bank}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		return $mysql->db_insert_id();
	
	}
	
	
	/**
	 * ����û�����
	 *
	 * @param Array $data =array("name"=>"����","nid"=>"��ʶ��","litpic"=>"����ͼ","cash_money"=>"���ֽ��","reach_day"=>"����ʱ��")
	 * @return Boolen
	 */
	function AddUsersBank($data = array()){
		global $mysql;
		//�ж������Ƿ����
		$sql = "select count(1) as num from `{account_users_bank}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result['num']>=5){
			return "account_users_bank_not5";
		}
		$sql = "insert into `{account_users_bank}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		
		$mysql->db_query($sql);
		return $mysql->db_insert_id();
		
	}
	
	
	/**
	 * �޸�����
	 *
	 * @param Array $data =array("name"=>"����","nid"=>"��ʶ��","litpic"=>"����ͼ","cash_money"=>"���ֽ��","reach_day"=>"����ʱ��")
	 * @return Boolen
	 */
	function UpdateBank($data = array()){
		global $mysql;
		
		//�ж�id�Ƿ����
		if (!IsExiest($data['id'])) {
			return "account_bank_id_empty";
		}
		
		//�ж������Ƿ����
		if (!IsExiest($data['name'])) {
			return "account_bank_name_empty";
		}
		
		//�жϱ�ʶ���Ƿ����
		if (!IsExiest($data['nid'])) {
			return "account_bank_nid_empty";
		}
		
		$sql = "update `{account_bank}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id='{$data['id']}'";
		$mysql->db_query($sql);
		return $data['id'];
	
	}

	
	
	/**
	 * �����û�������Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function UpdateUsersBank($data = array()){
		global $mysql;
		
		//�ж�id�Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_bank_userid_empty";
		}
		
		$sql = "update `{account_users_bank}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where user_id='{$data['user_id']}' and `id`='{$data['id']}'";
		$mysql->db_query($sql);
		return $data['user_id'];
	
	}
	
	
	/**
	 * ɾ���û�������Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function DeleteUsersBank($data = array()){
		global $mysql;
		
		//�ж�id�Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_bank_userid_empty";
		}
		
		//�ж�id�Ƿ����
		if (!IsExiest($data['id'])) {
			return "account_bank_id_empty";
		}
		
		$sql = "delete from `{account_users_bank}`  where user_id='{$data['user_id']}' and `id`='{$data['id']}'";
		$mysql->db_query($sql);
		return $data['id'];
	
	}
	/**
	 * ��ȡ�û��ʽ���Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = {$data['user_id']}";
		}
		
		$_select = "p1.*";
		$sql = "select $_select from `{account}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
	
	
	/**
	 * �޸�����
	 *
	 * @param Array $data =array("name"=>"����","nid"=>"��ʶ��","litpic"=>"����ͼ","cash_money"=>"���ֽ��","reach_day"=>"����ʱ��")
	 * @return Boolen
	 */
	function DeleteBank($data = array()){
		global $mysql;
		
		//�ж�id�Ƿ����
		if (!IsExiest($data['id'])) {
			return "account_bank_id_empty";
		}
		
		$sql = "delete from `{account_bank}`  where id='{$data['id']}'";
		$mysql->db_query($sql);
		return $data['id'];
	
	}
	
	function DeleteUserBank($data = array()){
		global $mysql;
		
		//�ж�id�Ƿ����
		if (!IsExiest($data['id'])) {
			return "account_bank_id_empty";
		}
		
		$sql = "delete from `{account_users_bank}`  where id='{$data['id']}'";
		$mysql->db_query($sql);
		return $data['id'];
	
	}
	
	
	
	function GetUsersBankOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		//�ж�id�Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_bank_userid_empty";
		}
		
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = '{$data['id']}'";
		}
		
		$_select = "p1.*,p2.username,p2.paypassword,p3.realname,p4.name as bank_name,p5.total,p5.balance,p5.balance_cash";
		$sql = "select $_select from `{account_users_bank}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id 
		left join `{account_bank}` as p4 on p1.bank=p4.id 
		left join `{account}` as p5 on p1.user_id=p5.user_id 
		left join `{approve_realname}` as p3 on p1.user_id=p3.user_id $_sql";
		$result = $mysql->db_fetch_array($sql);
		return $result;
	}
	
	function GetAccountUsers($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		//�ж�id�Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_bank_userid_empty";
		}
		
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		$_select = "p1.*";
		$sql = "select $_select from `{account}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
	
	
	//��ȡ������Ϣ
	function GetCashOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = {$data['id']}";
		} 
		
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = {$data['user_id']}";
		} 
		
		$_select = "p1.*,p2.username,p2.paypassword,p3.realname,p4.bank as bank_name,p5.username as verify_username";
		$sql = "select $_select from `{account_cash}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id 
		left join `{account_users_bank}` as p4 on p1.user_id=p4.user_id 
		left join `{users}` as p5 on p1.verify_userid=p5.user_id 
		left join `{approve_realname}` as p3 on p1.user_id=p3.user_id $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
	
	
	function AddCash($data = array()){
		global $mysql,$_G;
		
		//�ж��û��Ƿ�
		if (!IsExiest($data['user_id'])) {
			return "account_bank_user_id_empty";
		}
		
		$sql = "select balance,balance_cash from `{account}` where user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		if (IsExiest($_G['system']['con_account_balance_cash_status']) == 1){
			if ($result['balance_cash']<$data['total']){
				return "account_cash_max_errot";
			}
		}else{
			if ($result['balance']<$data['total']){
				return "account_cash_max_errot";
			}
		}
		
		$sql = "insert into `{account_cash}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		$id = $mysql->db_insert_id();
		$log_info["user_id"] = $data['user_id'];//�����û�id
		$log_info["nid"] = "cash_".$data['nid'];//������
        $log_info["account_web_status"] = 0;//
        $log_info["account_user_status"] = 0;//
		$log_info["money"] = $data['total'];//�������
		$log_info["income"] = 0;//����
		$log_info["expend"] = 0;//֧��
		$log_info["balance_cash"] = -$data['total'];//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = $data['total'];//������
		$log_info["await"] = 0;//���ս��
		$log_info["type"] = "cash";//����
		$log_info["to_userid"] = 0;//����˭
		$log_info["remark"] = "��������{$data['total']}Ԫ";//��ע
		$result = self::AddLog($log_info);
		
        $sql = "update `{account}` set frost_cash=frost_cash+{$data['total']} where user_id='{$data['user_id']}'";
        $mysql->db_query($sql);
        
		//�����û�������¼
		$user_log["user_id"] = $data['user_id'];
		$user_log["code"] = "account";
		$user_log["type"] = "cash";
		$user_log["operating"] = "require";
		$user_log["article_id"] = $id;
		$user_log["result"] = 1;
		$user_log["content"] =  $log_info["remark"];
		usersClass::AddUsersLog($user_log);	
		return $id;
	}
	
	/**
	 * ��ֵ���
	 *
	 * @param Array $data =array("nid"=>"������","verify_remark"=>"��˱�ע","status"=>"���״̬")
	 * @return Boolen
	 */
	function VerifyCash($data = array()){
		global $mysql,$MsgInfo;
		
		//�ж϶������Ƿ����
		if (!IsExiest($data['id'])) {
			return "account_cash_id_empty";
		}
		
		$sql = "select p1.* from `{account_cash}` as p1  where p1.`id`='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);
		//�ж϶������Ƿ����
		if ($result==false) return "account_cash_not_exiest";
		
		//�ж��Ƿ��Ѿ����
		if ($result['status']!=0) return "account_cash_yes_verify";
	
		$sql = "update `{account_cash}` set status='{$data['status']}',verify_time='".time()."',verify_userid='".$data['verify_userid']."',verify_remark='".$data['verify_remark']."',credited='".($result['total']-$result['fee']-$data['credit_card_cash_fee'])."',fee='".($result['fee']+$data['credit_card_cash_fee'])."' where id = '{$data['id']}'";
       $mysql->db_query($sql);
		
		$user_id = $result['user_id'];
		$cash_account = $result['total'];
		$cashaccount = $result['credited']-$data['credit_card_cash_fee'];
		$cash_fee = $result['fee'];
		
		
		$nid = $result['nid'];
		
		if ($data['status']==1){
			$log_info["user_id"] = $user_id;//�����û�id
			$log_info["nid"] = "cash_success_".$nid;//������
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 1;//
            $log_info["code"] = "account";//
    		$log_info["code_type"] = "cash_success";//
			$log_info["money"] = $cashaccount;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = $cashaccount;//֧��
			$log_info["balance_cash"] = 0;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = -$cash_account;//������
			$log_info["await"] = 0;//���ս��
			$log_info["repay"] = 0;//�������
			$log_info["type"] = "cash_success";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "���ֳɹ�{".$cashaccount."}Ԫ";//��ע
			$result = self::AddLog($log_info);
			
            if ($cash_fee>0){
				$log_info["user_id"] = $user_id;//�����û�id
				$log_info["nid"] = "cash_fee_".$nid;//������
                $log_info["account_web_status"] = 1;//
                $log_info["account_user_status"] = 1;//
                $log_info["code"] = "account";//
        		$log_info["code_type"] = "cash_success_fee";//
				$log_info["money"] = $cash_fee;//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = $cash_fee;//֧��
				$log_info["balance_cash"] = 0;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["repay"] = 0;//�������
				$log_info["type"] = "cash_fee";//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] = "����������{$cash_fee}Ԫ";//��ע
				$result = self::AddLog($log_info);
			}
            if(!empty($data['credit_card_cash_fee'])){
                // ���ÿ����ִ�������
				$log_info["user_id"] = $user_id;//�����û�id
				$log_info["nid"] = "credit_card_cash_fee_".$nid;//������
                $log_info["account_web_status"] = 1;//
                $log_info["account_user_status"] = 1;//
                $log_info["code"] = "account";//
        		$log_info["code_type"] = "credit_card_cash_fee";//
				$log_info["money"] = $data['credit_card_cash_fee'];//�������
				$log_info["income"] = 0;//����
				$log_info["expend"] = $data['credit_card_cash_fee'];//֧��
				$log_info["balance_cash"] = 0;//�����ֽ��
				$log_info["balance_frost"] = 0;//�������ֽ��
				$log_info["frost"] = 0;//������
				$log_info["await"] = 0;//���ս��
				$log_info["repay"] = 0;//�������
				$log_info["type"] = "credit_card_cash_fee";//����
				$log_info["to_userid"] = 0;//����˭
				$log_info["remark"] = "δͶ������������{".$data['credit_card_cash_fee']."}Ԫ";//��ע
				$result = self::AddLog($log_info);
            }
			//�����û�������¼
			$user_log["user_id"] = $user_id;
			$user_log["code"] = "account";
			$user_log["type"] = "cash";
			$user_log["operating"] = "success";
			$user_log["article_id"] = $data['id'];
			$user_log["result"] = 1;
			$user_log["content"] =  $log_info["remark"];
			usersClass::AddUsersLog($user_log);	
			
			//��ֵվ�������� add 20120918 wlz
			$remind['nid'] = "cash_success";		
			$remind['receive_userid'] = $user_id;
            $remind['remind_nid'] =  "cash_success_".$user_id."_".$data['id'];
        	$remind['code'] = "account";
			$remind['article_id'] = $user_id;
			$remind['title'] = "���ֳɹ�";
			$remind['content'] = "���ѳɹ��ɹ�������".$cashaccount."Ԫ";
			remindClass::sendRemind($remind);
		}elseif ($data['status']==2){
			$log_info["user_id"] = $user_id;//�����û�id
			$log_info["nid"] = "cash_false_".$nid;//������
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 0;//
            $log_info["code"] = "account";//
			$log_info["code_type"] = "cash_false";//
			$log_info["code_nid"] = $user_id;//
			$log_info["money"] = $cash_account;//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = $cash_account;//�����ֽ��
			$log_info["balance_frost"] = 0;//�������ֽ��
			$log_info["frost"] = -$cash_account;//������
			$log_info["await"] = 0;//���ս��
			$log_info["type"] = "cash_false";//����
			$log_info["to_userid"] = 0;//����˭
			$log_info["remark"] = "����{$cash_account}Ԫ����ʧ��";//��ע
			$result = self::AddLog($log_info);
			
			//�����û�������¼
			$user_log["user_id"] = $user_id;
			$user_log["code"] = "account";
			$user_log["type"] = "cash";
			$user_log["operating"] = "false";
			$user_log["article_id"] = $data['id'];
			$user_log["result"] = 1;
			$user_log["content"] =  $log_info["remark"];
			usersClass::AddUsersLog($user_log);	
			
			//��ֵվ�������� add 20120918 wlz
			$remind['nid'] = "cash_false";		
			$remind['receive_userid'] = $user_id;
            $remind['remind_nid'] =  "cash_false_".$user_id."_".$data['id'];
			$remind['code'] = "account";
			$remind['article_id'] = $user_id;
			$remind['title'] = "����ʧ��";
			$remind['content'] = "������".$cash_account."Ԫ��˲�ͨ����ԭ��{$data['verify_remark']}��";
			remindClass::sendRemind($remind);
		}
		return $data['id'];
	}
	
	
	
	/**
	 * �û�ȡ������
	 *
	 * @param Array $data =array("nid"=>"������","verify_remark"=>"��˱�ע","status"=>"���״̬")
	 * @return Boolen
	 */
	function CancelCash($data = array()){
		global $mysql,$MsgInfo;
		
		//�ж϶������Ƿ����
		if (!IsExiest($data['id'])) {
			return "account_cash_id_empty";
		}
		//�ж϶������Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_cash_user_id_empty";
		}
		
		$sql = "select p1.* from `{account_cash}` as p1  where p1.`id`='{$data['id']}' and p1.user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		
		//�ж϶������Ƿ����
		if ($result==false) return "account_cash_not_exiest";
		
		//�ж��Ƿ��Ѿ����
		if ($result['status']!=0) return "account_cash_yes_verify";
	
		$sql = "update `{account_cash}` set status='3',verify_time='".time()."',verify_userid='".$data['user_id']."',verify_remark='�û�ȡ��' where id = '{$data['id']}'";
       $mysql->db_query($sql);
		
		$user_id = $data['user_id'];
		$cash_account = $result['total'];
		$nid = "cash_cancel_".$_G['user_id'].time().rand(100,999).$data['status'];
		
		$log_info["user_id"] = $user_id;//�����û�id
		$log_info["nid"] = $nid;//������
        $log_info["account_web_status"] = 0;//
        $log_info["account_user_status"] = 0;//
		$log_info["money"] = $cash_account;//�������
		$log_info["income"] = 0;//����
		$log_info["expend"] = 0;//֧��
		$log_info["balance_cash"] = $cash_account;//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = -$cash_account;//������
		$log_info["await"] = 0;//���ս��
		$log_info["type"] = "cash_cancel";//����
		$log_info["to_userid"] = 0;//����˭
		$log_info["remark"] = "ȡ��{$cash_account}Ԫ����";//��ע
		$result = self::AddLog($log_info);
		
		//�����û�������¼
		$user_log["user_id"] = $recharge_userid;
		$user_log["code"] = "account";
		$user_log["type"] = "cash";
		$user_log["operating"] = "false";
		$user_log["article_id"] = $data['id'];
		$user_log["result"] = 1;
		$user_log["content"] =  $log_info["remark"];
		usersClass::AddUsersLog($user_log);	
		
		return $data['id'];
	}
	
	
	function GetBank($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = {$data['id']}";
		} 
		
		$_select = "p1.*";
		$sql = "select $_select from `{account_bank}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
	
	
	
	function GetAccount($data = array()){
		global $mysql;
		
		//�ж��û�id�Ƿ����
		if (!IsExiest($data['user_id'])) {
			return "account_user_id_empty";
		}
		$_select = "p1.*";
		$sql = "select $_select from `{account}` as p1 where p1.user_id='{$data['user_id']}'";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
	
	//�ʽ�۳��������ֳ���֤���� type,money,remark
	function Deduct($data){
		global $mysql,$_G;
		$account_result =  self::GetOne(array("user_id"=>$data['user_id']));		
		//if($account_result['use_money'] < $data['money']){
			//return "�˿ͻ��������㣬�������Ϊ{$account_result['use_money']}";
		//}
		if($data['money'] < 0){
			return "��������Ϊ����";
		}
		
		$nid = $data['type'].$data['user_id'].time().rand(1000,9999);
		$log_info['user_id'] = $data['user_id'];
		$log_info["nid"] = $nid;//������
		$log_info["money"] = $data['money'];//�������
		$log_info["income"] = 0;//����
		$log_info["expend"] = $data['money'];//֧��
		$log_info["balance_cash"] =-$data['money'];//�����ֽ��
		$log_info["balance_frost"] = 0;//�������ֽ��
		$log_info["frost"] = 0;//������
		$log_info["await"] = 0;//���ս��
		$log_info["type"] = $data['type'];//����
		$log_info["to_userid"] = 0;//����˭
		//$log_info["remark"] = "�۳�{$data['money']}Ԫ";//��ע
		$log_info["remark"] = $data["remark"];  //��ע
 		$result = self::AddLog($log_info);
		
		return true;
	}
	
	
	
	/**
	 * ��ȡ��վ�ʽ��¼
	 *
	 * @return Array
	 */
	function GetWebList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
			 
		//�����û�id
		if (IsExiest($data['user_id'])!=false) {
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		if (IsExiest($data['type'])!=false) {
			$_sql .= " and p1.type = '{$data['type']}'";
		}
		
		if (IsExiest($data['dotime1']) != false){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['dotime2'])!=false){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		
		$_select = "p1.*,p2.username";
		$_order = " order by p1.addtime desc ";
		$sql = "select SELECT from {account_web} as p1 
				 left join {users} as p2 on p1.user_id=p2.user_id
				SQL ORDER LIMIT";
		if (IsExiest($data['excel'])==1){
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select,'','order by p1.addtime desc,p1.id desc', ""), $sql));
			$title = array("�û���","����","��վ�渶���","��ע","ʱ��");
			$linkage_result = $_G['linkage'];
			foreach ($result as $key => $value){
				$_data[$key] = array($value['username'],$value['type'],$value['money'],$value['remark'],date("Y-m-d",$value['addtime']));
			}
			self::exportData(date("Y-m-d",time())."��վ�渶���ü�¼����",$title,$_data);
			exit;
		}
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
		
		$sql="select sum(p1.income) as _income,sum(p1.expend) as _expend from `{account_web}` as p1 left join {users} as p2 on p1.user_id=p2.user_id $_sql";
		$all_money=$mysql->db_fetch_array($sql);
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page,'account_income'=>round($all_money['_expend'],2),'account_expend'=>round($all_money['_income'],2),'account_all'=>round($all_money['_expend'],2)-round($all_money['_income'],2));
		
		return $result;
	}
	
	//���߳�ֵ�������ݴ���
	function  OnlineReturn ($data = array()){
		global $mysql;
		$trade_no = $data['trade_no'];
		$rechage_result = self::GetRecharge(array("nid"=>$trade_no));
		if($rechage_result['status']==0 && $rechage_result!=false){
				$credit_log['user_id'] = $rechage_result['user_id'];
				$credit_log['nid'] = "online_recharge";
				$credit_log['code'] = "account";
				$credit_log['type'] = "recharge_approve";
				$credit_log['addtime'] = time();
				$credit_log['article_id'] =$result['id'];
				$credit_log['remark'] = "�û����߳�ֵ���õĻ���";
				//creditClass::ActionCreditLog($credit_log);
				
				
				$rec['nid'] = $rechage_result['nid'];
				$rec['return'] = serialize($_REQUEST);
				$rec['status'] = 1;
				$rec['verify_userid'] = 0;
				$rec['verify_time'] = time();
				$rec['verify_remark'] = "�ɹ���ֵ";
				
				self::VerifyRecharge($rec);
                // ��ֵ��vip
                self::recharge_vip($rechage_result['user_id']);
		}
		return true;
	}
	
	//��ȡ���ڵص�ַ                             add wdf 20120905
	function GetCity($data){
		global $mysql;
		$sql = "select name from `{areas}` where id='{$data}'";
		$result = $mysql->db_fetch_array($sql);
		return $result;
	}
	
	//��ȡ��������                             add wdf 20120907
	function GetBankName($data){
		global $mysql;
		$sql = "select name as bank_name from `{linkages}` where type_id='44' and value='{$data}'";
		$result = $mysql->db_fetch_array($sql);
		return $result;
	}
	
    
    //���߳�ֵ�������ݴ���
	function  GetAccountType($data = array()){
	   	global $mysql;
        $_sql = " where 1=1 ";
        if ($data["type"]!=""){
            $_sql .= " and type='{$data['type']}'";
        }
        if ($data["account_type"]=="web"){
            $sql = "select type from `{account_web}` {$_sql} group by type";
            $result = $mysql->db_fetch_arrays($sql);
        }elseif ($data["account_type"]=="user"){
            $sql = "select type from `{account_users}`  {$_sql} group by type";
            $result = $mysql->db_fetch_arrays($sql);
        }
        return $result;
    }

    /**
     * ��ֵ����vip
     *
     */
    public static function recharge_vip ($user_id){
        global $mysql;
        // �ӳ�ֵ���в��� �״����õ��û� ʱ��ڵ�Ϊ2013-04-23 12:00:00
        $sql = 'select uid from {activity_20130423} where uid='.$user_id;
        $result = $mysql->db_fetch_array($sql);
        if(!empty($result)){
            return false;
        }
        $sql = 'select `money` from {account_recharge} where `user_id`='.$user_id.' and `status`=1 and `type`>0 and `addtime`>=1366689600 order by addtime asc limit 1';
        $money = $mysql->db_fetch_array($sql);
        if(empty($money) or $money['money']<1000){
            return false;
        }
        $sql = 'select `status`,`years`,`first_date`,`end_date` from {users_vip} where `user_id`='.$user_id;
        $vip = $mysql->db_fetch_array($sql);
        $first_date = time();
        $end_date = $first_date+31536000;
        if (empty($vip)){
            $sql = 'insert into {users_vip} (`user_id`,`status`,`years`,`first_date`,`end_date`) values ('.$user_id.',1,1,'.$first_date.','.$end_date.')';
        }else{
            if($vip['status']){
               $first_date = $vip['first_date'];
               $end_date = $vip['end_date']+31536000;
            }else{
                if(!empty($vip['end_date'])){
                    if($vip['end_date']<$first_date){
                        $end_date = $first_date+31536000;
                    }else{
                        $first_date = $vip['first_date'];
                        $end_date = $vip['end_date']+31536000;
                    }
                }
            }
            $sql = 'update {users_vip} set `status`=1,`years`='.($vip['years']+1).',`first_date`='.$first_date.',`end_date`='.$end_date.' where `user_id`='.$user_id;
        }
        $mysql->db_query($sql);
        $mysql->db_query('insert into {activity_20130423} (`uid`,`time`) values ('.$user_id.',\''.date('Y-m-d H:i:s').'\')');
    }
}