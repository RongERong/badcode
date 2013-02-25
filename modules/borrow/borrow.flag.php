<?php
/******************************
 * $File: borrow.php
 * $Description: ��������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * �����borrow.style.php  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$MsgInfo["borrow_flag_id_empty"] = "���Ĳ�������";
$MsgInfo["borrow_flag_name_empty"] = "���Ʋ���Ϊ��";
$MsgInfo["borrow_flag_nid_empty"] = "��ʶ�����Ʋ���Ϊ��";
$MsgInfo["borrow_flag_nid_exiest"] = "��ʶ���Ѿ�����";

class borrowFlagClass{
    
	function GetFlagList($data = array()){
		global $mysql,$_G;		
		
        //��ȡ���ʽ
        require_once("borrow.style.php");
        $_style_result = borrowStyleClass::GetStyleList(array("limit"=>"all"));
        
		$_select = "p1.*,p2.fileurl as upfile_url";
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{borrow_flag}` as p1 left join `{users_upfiles}` as p2 on p1.upfiles_id=p2.id  SQL ORDER LIMIT";
		
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
	 * ��ȡ�������������Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetFlagOne($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_flag_id_empty";
        } 
        
		$_sql = "where  p1.id = {$data['id']} ";
			 
		
		$_select = "p1.*,p2.fileurl as upfile_url";
		$sql = "select $_select from `{borrow_flag}` as p1 left join `{users_upfiles}` as p2 on p1.upfiles_id=p2.id $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
    
    
    /**
	 * ��ӽ�����
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddFlag($data = array()){
		global $mysql;
        
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "borrow_flag_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "borrow_flag_nid_empty";
        } 
        $sql = "select 1 from `{borrow_flag}` where nid='{$data['nid']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "borrow_flag_nid_exiest"; 
        }
		
		$sql = "insert into `{borrow_flag}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
        $id = $mysql->db_insert_id();
		return $id;
	}
	
    /**
	 * �޸Ľ�����
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateFlag($data = array()){
		global $mysql;
		
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_flag_id_empty";
        } 
         //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "borrow_flag_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "borrow_flag_nid_empty";
        } 
        
        $sql = "select 1 from `{borrow_flag}` where nid='{$data['nid']}' and id!='{$data['id']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "borrow_flag_nid_exiest"; 
        }
		
		$sql = "update `{borrow_flag}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
    
      /**
	 * ɾ���������������Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function DeleteFlag($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_flag_id_empty";
        } 
		$sql = "delete  from `{borrow_flag}` where id = {$data['id']} ";
		$result = $mysql->db_query($sql);
		
		return $result;
	}
    
    
}
?>