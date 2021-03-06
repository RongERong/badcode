<?php
/******************************
 * $File: borrow.php
 * $Description: 借款类型文件
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * 组件：borrow.style.php  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('不能访问');//防止直接访问

$MsgInfo["borrow_flag_id_empty"] = "您的操作有误";
$MsgInfo["borrow_flag_name_empty"] = "名称不能为空";
$MsgInfo["borrow_flag_nid_empty"] = "标识名名称不能为空";
$MsgInfo["borrow_flag_nid_exiest"] = "标识名已经存在";

class borrowFlagClass{
    
	function GetFlagList($data = array()){
		global $mysql,$_G;		
		
        //获取还款方式
        require_once("borrow.style.php");
        $_style_result = borrowStyleClass::GetStyleList(array("limit"=>"all"));
        
		$_select = "p1.*,p2.fileurl as upfile_url";
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{borrow_flag}` as p1 left join `{users_upfiles}` as p2 on p1.upfiles_id=p2.id  SQL ORDER LIMIT";
		
		//是否显示全部的信息
		if ( IsExiest($data['limit'])!= false){
			if ($data['limit'] != "all" ){ $_limit = "  limit ".$data['limit']; }
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			
			return $list;
		}
		
		//判断总的条数
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array('count(1) as num', $_sql,'', ''), $sql));
		$total = intval($row['num']);
		
		//分页返回结果
		$data['page'] = !IsExiest($data['page'])?1:$data['page'];
		$data['epage'] = !IsExiest($data['epage'])?10:$data['epage'];
		$total_page = ceil($total / $data['epage']);
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
	
		
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
	}
    
     /**
	 * 获取单条借款类型信息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetFlagOne($data = array()){
		global $mysql;
        
         //判断id是否存在
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
	 * 添加借款费用
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddFlag($data = array()){
		global $mysql;
        
		 //判断名称是否存在
        if (!IsExiest($data['name'])) {
            return "borrow_flag_name_empty";
        } 
        
        //判断名称是否存在
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
	 * 修改借款费用
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateFlag($data = array()){
		global $mysql;
		
         //判断id是否存在
        if (!IsExiest($data['id'])) {
            return "borrow_flag_id_empty";
        } 
         //判断名称是否存在
        if (!IsExiest($data['name'])) {
            return "borrow_flag_name_empty";
        } 
        
        //判断名称是否存在
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
	 * 删除单条借款类型信息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function DeleteFlag($data = array()){
		global $mysql;
        
         //判断id是否存在
        if (!IsExiest($data['id'])) {
            return "borrow_flag_id_empty";
        } 
		$sql = "delete  from `{borrow_flag}` where id = {$data['id']} ";
		$result = $mysql->db_query($sql);
		
		return $result;
	}
    
    
}
?>