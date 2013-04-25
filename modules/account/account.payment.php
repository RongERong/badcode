<?
/******************************
 * $File: payment.class.php
 * $Description: ֧����ʽ���ļ�
 * $Author: ahui 
 * $Time:2010-08-09
 * $Update:None 
 * $UpdateDate:None 
 * Copyright(c) 2012 by dycms.net. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

class accountpaymentClass {

	/**
	 * �б�
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		$alllist = self::GetListAll();
		$_sql = "where 1=1 ";		 
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and status = {$data['status']}";
		}
		$sql = "select * from `{account_payment}` {$_sql} order by `order` desc";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			$result[$key]['logo'] = $alllist[$value['nid']]['logo'];
			$result[$key]['description'] = htmlspecialchars_decode($result[$key]['description']);
		}

		return $result;
	}
	
	/**
	 * �б�
	 *
	 * @return Array
	 */
	function GetListAll($data = array()){
		global $mysql;
		$result = get_file(ROOT_PATH."modules/account/paytype","file");
		$_result = "";
		if (is_array($result)){
			foreach ($result as $key => $value){
				$_nid = explode(".class.php",$value);
				$nid = $_nid[0];
				$_result[$nid]['nid'] = $nid;
				$classname = $nid."Payment";
				include_once(ROOT_PATH."modules/account/paytype/{$value}");
				$o = new $classname();
				$_result[$nid]['type'] = $o->type;
				$_result[$nid]['name'] = $o->name;
				$_result[$nid]['description'] = $o->description;
				$_result[$nid]['logo'] = "/data/images/payment/".$o->logo.".gif";
			}
		}
		return $_result;
	}
	
	/**
	 * �鿴
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$nid = $data['nid'];
		$classname = $nid."Payment";
		include_once(ROOT_PATH."modules/account/paytype/{$nid}.class.php");
		$o = new $classname();
		$_result['nid'] = $nid;
		$_result['type'] = $o->type;
		$_result['name'] = $o->name;
		$_result['description'] = $o->description;
		$_result['fields'] = $o->GetFields();
		$_result['logo'] = "/data/images/payment/".$o->logo.".gif";
		if ($_result['type']==1){
			$sql = "select * from `{account_payment}` where nid = '{$data['nid']}'";
			$result = $mysql->db_fetch_array($sql);
			
			if ($result!=false && $_result['type'] ==1 ){
				$_config = unserialize($result['config']);
				$_result['litpic'] = $result['litpic'];
				foreach ($_result['fields'] as $_key => $_value){
					$_result['fields'][$_key]['value'] =  isset($_config[$_key])?$_config[$_key]:"";
				}
				$_result['description'] = $result['description'];
			}
		}elseif (isset($data['id']) && $data['id']!=""){
			$sql = "select * from `{account_payment}` where id = '{$data['id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false && $_result['type'] ==1 ){
				$_config = unserialize($result['config']);
				foreach ($_result['fields'] as $_key => $_value){
					$_result['fields'][$_key]['value'] =  isset($_config[$_key])?$_config[$_key]:"";
				}
			}
			if ($result != false) return $result+$_result;
		}
		return $_result;
	}
	
	 /**
	 * ���
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Action($data = array()){
		global $mysql;
		$nid = $data['nid'];
		$type = $data['type'];
		unset($data['type']);
		$sql = "select * from `{account_payment}` where nid = '{$nid}'";
		$result = $mysql->db_fetch_array($sql);
		if (($result == false || $type=="new")  && $type!="edit"){
			$_sql = "";
			$sql = "insert into `{account_payment}` set ";
			foreach($data as $key => $value){
				$_sql[] = "`$key` = '$value'";
			}
			$mysql->db_query($sql.join(",",$_sql));
		}else{
			$_sql = $__sql = "";
			if (isset($data['id'])){
				$__sql .=" and id = '{$data['id']}'";
			}
			$sql = "update `{account_payment}` set ";
			foreach($data as $key => $value){
				$_sql[] .= "`$key` = '$value'";
			}
			$sql .= join(",",$_sql)." where nid = '$nid' {$__sql} ";
			$mysql->db_query($sql);
		}
        return $result['id'];
	}
	
	
	/**
	 * �޸�
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Update($data = array()){
		global $mysql;
		$id = $data['id'];
        if ($data['id'] == "") {
            return "";
        }
		
		$_sql = "";
		$sql = "update `{account_payment}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
        return $mysql->db_query($sql);
	}
	
	function ToSubmit($data = array()){
		global $mysql,$_G;
		$payment = isset($data['payment'])?$data['payment']:"";
		$data['webname'] = $_G['system']['con_webname'];//�ص���ַ
		$data['subject'] = isset($data['subject'])?$data['subject']:"";
		$data['body'] = isset($data['body'])?$data['body']:"";
		$data['trade_no'] = isset($data['trade_no'])?$data['trade_no']:"";
		$sql = "select * from `{account_payment}` where id = '{$payment}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "";
		if($result['config']!=""){
			$data = unserialize($result['config'])+$data;
            $data['notify_url'] = $_G['web_domain']."/modules/account/notify.php?ryrpyment=".$result['nid'];//֪ͨ��ַ
		    $data['return_url'] = $_G['web_domain']."/modules/account/return.php?ryrpyment=".$result['nid'];//�ص���ַ
			include_once(ROOT_PATH."modules/account/paytype/{$result['nid']}.class.php");
			$classname = $result['nid']."Payment";
			
			$payclass = new $classname;
			$result = $payclass->ToSubmit($data);
			if (is_array($result)){
				$url = $result['url'];
				$sign = $result['sign'];
			}else{
				$url = $result;
			}
			$sql = "update  `{account_recharge}` set url='{$url}',sign='{$sign}' where id='{$data['recharge_id']}'";
			$mysql->db_query($sql);
			return $url;
		}else{
			return "";
		}
		
	}
	
	
	
	/**
	 * ɾ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function Delete($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {account_payment}  where id in (".join(",",$id).")";
		$mysql->db_query($sql);
		return $id;
	}
	
}
?>
