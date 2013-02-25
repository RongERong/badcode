<?php
/******************************
 * $File: borrow.style.php
 * $Description: ���ʽ
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$MsgInfo["borrow_style_title_empty"] = "���ⲻ��Ϊ��";

class borrowStyleClass{
    
	function GetStyleList($data = array()){
		global $mysql,$_G;		
        
		if ($_G['borrow_style_list']!="") return $_G['borrow_style_list'];
        
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
        
		$sql = "select SELECT from  `{borrow_style}` as p1  SQL ORDER LIMIT";
		
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
	 * ��ȡ�������ʽ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetStyleOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = {$data['id']}";
		}
		$_select = "p1.*";
		$sql = "select $_select from `{borrow_style}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
    
    /**
	 * �޸Ļ��ʽ
	 *
	 * @param array ;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateStyle($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['title'])) {
            return "borrow_style_title_empty";
        } 
		
		$sql = "update `{borrow_style}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
	
}
?>