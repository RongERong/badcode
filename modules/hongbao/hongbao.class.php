<?php
/******************************
 * $File: hongbao.php
 * $Description: ����ļ�
 * $Author: ada
 * $Time:2012-12-12-11
 * $Update:
 * $UpdateDate: 
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$MsgInfo["hongbao_id_empty"] = "���Ĳ�������";
$MsgInfo["hongbao_name_empty"] = "���Ʋ���Ϊ��";
$MsgInfo["hongbao_nid_empty"] = "��ʶ�����Ʋ���Ϊ��";
$MsgInfo["hongbao_nid_exiest"] = "��ʶ���Ѿ�����";

class hongbaoClass{
    //����б�
	function GetHongbaoList($data = array()){
		global $mysql,$_G;		
        
		$_select = "p1.*,p2.name as type_name";
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{hongbao}` as p1 left join `{hongbao_type}` as p2 on p1.type_id=p2.id  SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if ( IsExiest($data['limit'])!= false){
			if ($data['limit'] != "all" ){ $_limit = "  limit ".$data['limit']; }
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			/*
            foreach ($list as $key => $value){
                 $_styles = array();
			     if ($value["styles"]!=""){
    			     foreach ($_style_result as $_key => $_value){
    			         $style = explode(",",$value["styles"]);
                         if (in_array($_value["nid"],$style)){
    			             $_styles[] = "<span title='{$_value['title']}'>".$_value['name']."</span>"; 
                         }
                     }
    			     $list[$key]['styles_name'] = join("|",$_styles); 
                }
			}*/
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
	 * ��ȡ���������Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetOne($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "hongbao_id_empty";
        } 
        
		$_sql = "where  p1.id = {$data['id']} ";
			 
		$_select = "p1.*";
		$sql = "select $_select from `{hongbao}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
    
    
    /**
	 * ��Ӻ��
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function Add($data = array()){
		global $mysql;
        
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "hongbao_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "hongbao_nid_empty";
        } 
        $sql = "select 1 from `{hongbao}` where nid='{$data['nid']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "hongbao_nid_exiest"; 
        }
		
		$sql = "insert into `{hongbao}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
        $id = $mysql->db_insert_id();
		return $id;
	}
	
    /**
	 * �޸ĺ��
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function Update($data = array()){
		global $mysql;
		
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "hongbao_id_empty";
        } 
         //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "hongbao_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "hongbao_nid_empty";
        } 
        
        $sql = "select 1 from `{hongbao}` where nid='{$data['nid']}' and id!='{$data['id']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "hongbao_nid_exiest"; 
        }
		
		$sql = "update `{hongbao}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
    
      /**
	 * ɾ�����������Ϣ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Delete($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "hongbao_id_empty";
        } 
		$sql = "delete  from `{hongbao}` where id = {$data['id']} ";
		$result = $mysql->db_query($sql);
		
		return $result;
	}
    
    /**********************
     * �������
     **********************/
	function GetTypeList($data = array()){
		global $mysql,$_G;		
		
        //��ȡ�������
        
		$_select = "p1.*";
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{hongbao_type}` as p1  SQL ORDER LIMIT";
		
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
	function GetTypeOne($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "hongbao_id_empty";
        } 
        
		$_sql = "where  p1.id = {$data['id']} ";
			 
		
		$_select = "p1.*";
		$sql = "select $_select from `{hongbao_type}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
    
    
    /**
	 * ��Ӻ������
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddType($data = array()){
		global $mysql;
        
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "hongbao_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "hongbao_nid_empty";
        }
        $sql = "select 1 from `{hongbao_type}` where nid='{$data['nid']}'"; 
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "hongbao_nid_exiest"; 
        }
		
		$sql = "insert into `{hongbao_type}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
        $id = $mysql->db_insert_id();
		return $id;
	}
	
    /**
	 * �޸ĺ������
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateType($data = array()){
		global $mysql;
		
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "hongbao_id_empty";
        } 
         //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "hongbao_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "hongbao_nid_empty";
        } 
        
        $sql = "select 1 from `{hongbao_type}` where nid='{$data['nid']}' and id!='{$data['id']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "hongbao_nid_exiest"; 
        }
		
		$sql = "update `{hongbao_type}` set ";
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
	function DeleteType($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "hongbao_id_empty";
        } 
		$sql = "delete  from `{hongbao_type}` where id = {$data['id']} ";
		$result = $mysql->db_query($sql);
		
		return $result;
	} 
}
?>