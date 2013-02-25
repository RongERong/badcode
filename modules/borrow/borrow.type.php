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

$MsgInfo["borrow_type_title_empty"] = "���ⲻ��Ϊ��";
$MsgInfo["borrow_type_apr_error"] = "������ʲ���С����С����";
$MsgInfo["borrow_type_period_error"] = "������޵���߲���С�����";
$MsgInfo["borrow_type_validate_error"] = "��Ч�ڵ���߲���С�����";
$MsgInfo["borrow_type_check_error"] = "������޵���߲���С�����";
$MsgInfo["borrow_type_award_scale_error"] = "������������߲���С�����";
$MsgInfo["borrow_type_award_account_error"] = "����������߲���С�����";

class borrowTypeClass{
    
	function GetTypeList($data = array()){
		global $mysql,$_G;		
		
        //��ȡ���ʽ
        require_once("borrow.style.php");
        $_style_result = borrowStyleClass::GetStyleList(array("limit"=>"all"));
        $_sql = " where 1=1 ";
		$_select = "p1.*";
        if ($data['status']!="" || $data['status']=="0"){
			$_sql .= " and p1.status = '{$data['status']}'";
		}
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{borrow_type}` as p1  SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if ( IsExiest($data['limit'])!= false){
			if ($data['limit'] != "all" ){ $_limit = "  limit ".$data['limit']; }
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			
            foreach ($list as $key => $value){
                 $_styles = array();
			     if ($value["styles"]!=""){
    			     foreach ($_style_result as $_key => $_value){
    			         $style = explode(",",$value["styles"]);
                         if (in_array($_value["nid"],$style)){
    			             $_styles[] = "<span title='{$_value['title']}'>".$_value['title']."</span>"; 
                         }
                     }
    			     $list[$key]['styles_name'] = join("|",$_styles); 
                }
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
		$_sql = "where 1=1 ";
			 
		if (IsExiest($data['id'])!=false) {
			$_sql .= " and p1.id = '{$data['id']}'";
		}
		
		if (IsExiest($data['nid'])!=false) {
			$_sql .= " and p1.nid = '{$data['nid']}'";
		}
        
		require_once("borrow.style.php");//���ʽ
		$_select = "p1.*";
		$sql = "select $_select from `{borrow_type}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
            $result['style_result'] = borrowStyleClass::GetStyleList(array("limit"=>"all","nid"=>$result['styles']));
            
            for($i=$result['period_first'];$i<=$result['period_end'];$i++){
                if ($result['nid']=="day"){
                    $result['period_result'][] = array("name"=>$i."��","value"=>$i);
                }else{
                    $result['period_result'][] = array("name"=>$i."��","value"=>$i);
                }
            }
            
            for($i=$result['validate_first'];$i<=$result['validate_end'];$i++){
                $result['validate_result'][] = array("name"=>$i."��","value"=>$i);
            }
            
            $result['tender_account_min_result'] = explode(",",$result['tender_account_min']);
            $result['tender_account_max_result'] = explode(",",$result['tender_account_max']);
		}
		return $result;
	}
    
    
    /**
	 * �޸Ľ������
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function UpdateType($data = array()){
		global $mysql;
		
		 //�ж������Ƿ����
        if (!IsExiest($data['title'])) {
            return "borrow_type_title_empty";
        } 
		//�жϽ������
        if ($data['period_first']>$data['period_end']){
            return "borrow_type_period_error";
        }
        //�ж�����
         if ($data['apr_first']>$data['apr_end']){
            return "borrow_type_apr_error";
        }
        //�ж���Ч��
         if ($data['validate_first']>$data['validate_end']){
            return "borrow_type_validate_error";
        }//�ж����ʱ��
         if ($data['check_first']>$data['check_end']){
            return "borrow_type_check_error";
        }
        //�жϽ�������
         if ($data['award_scale_first']>$data['award_scale_end']){
            return "borrow_type_award_scale_error";
        }
        //�жϽ������
         if ($data['award_account_first']>$data['award_account_end']){
            return "borrow_type_award_account_error";
        }
        
		$sql = "update `{borrow_type}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql)." where id='{$data['id']}' ");
		return $data['id'];
	}
	
}
?>