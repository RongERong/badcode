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

$MsgInfo["borrow_fee_id_empty"] = "���Ĳ�������";
$MsgInfo["borrow_fee_name_empty"] = "���Ʋ���Ϊ��";
$MsgInfo["borrow_fee_nid_empty"] = "��ʶ�����Ʋ���Ϊ��";
$MsgInfo["borrow_fee_nid_exiest"] = "��ʶ���Ѿ�����";

class borrowFeeClass{
    
	function GetFeeList($data = array()){
		global $mysql,$_G;		
		
        //��ȡ���ʽ
        require_once("borrow.style.php");
        $_style_result = borrowStyleClass::GetStyleList(array("limit"=>"all"));
        
		$_select = "p1.*,p2.name as type_name";
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{borrow_fee}` as p1 left join `{borrow_fee_type}` as p2 on p1.type=p2.nid  SQL ORDER LIMIT";
		
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
    			             $_styles[] = "<span title='{$_value['title']}'>".$_value['name']."</span>"; 
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
	function GetFeeOne($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_fee_id_empty";
        } 
        
		$_sql = "where  p1.id = {$data['id']} ";
			 
		
		$_select = "p1.*";
		$sql = "select $_select from `{borrow_fee}` as p1 $_sql";
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
	function AddFee($data = array()){
		global $mysql;
        
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "borrow_fee_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "borrow_fee_nid_empty";
        } 
        $sql = "select 1 from `{borrow_fee}` where nid='{$data['nid']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "borrow_fee_nid_exiest"; 
        }
		
		$sql = "insert into `{borrow_fee}` set ";
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
	function UpdateFee($data = array()){
		global $mysql;
		
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_fee_id_empty";
        } 
         //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "borrow_fee_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "borrow_fee_nid_empty";
        } 
        
        $sql = "select 1 from `{borrow_fee}` where nid='{$data['nid']}' and id!='{$data['id']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "borrow_fee_nid_exiest"; 
        }
		
		$sql = "update `{borrow_fee}` set ";
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
	function DeleteFee($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_fee_id_empty";
        } 
		$sql = "delete  from `{borrow_fee}` where id = {$data['id']} ";
		$result = $mysql->db_query($sql);
		
		return $result;
	}
    
     /**
	 * ��ȡ��Ϣ��ֵ
	 *
	 * @param Array $data
	 * @return Boolen
	 */
    public static function GetFeeValue($data = array()){
        global $mysql;
        $_sql = "";
        $_result = [];
        $data["pay_tender"] = isset($data["pay_tender"])?$data["pay_tender"]:0;
        if ($data["pay_tender"]==1){
            $_sql = " and pay_tender=1";
        }
        $sql = "select * from `{borrow_fee}` where  FIND_IN_SET('{$data['borrow_type']}',borrow_types)  and type='{$data['type']}' and fee_type>0 and status=1 and user_type='{$data['user_type']}' {$_sql}";
         $result = $mysql->db_fetch_arrays($sql);
           
         if ($result !=false){
             foreach($result  as $key =>$value){
                $account =0;
                //�Ƿ�vip
                if ($data['vip_status']==1){
                    //����������
                    if ($value["fee_type"]==1){
                        //��Ϣ
                        if($value['account_scale_vip']=="account"){
                            $account = $data['capital'] + $data['interest'];
                        }
                        //��Ϣ
                        elseif($value['account_scale_vip']=="interest"){
                            $account = $data['interest'];
                        }
                        //����
                        elseif($value['account_scale_vip']=="capital"){
                            $account = $data['capital'];
                        }
                        //���ֵȼ�
                        if ($value['vip_rank']==1){
                            $account = $account*$data['credit_fee']*0.01;
                        }
                        //����
                        $account = $account*$value['vip_borrow_scale']*0.01;
                        
                        //�Ƿ�����޹ҹ�
                        if ($value["vip_period"]==1 && $data['period']>0){
                            if ($data['borrow_type']=="day"){
                                $data['period'] =1;
                            }
                            $account = $account * $data['period'];
                        }
                    }
                    //��������ʽ
                    elseif ($value["fee_type"]==2){
                        //��Ϣ
                        if($value['account_scales_vip']=="account"){
                            $account = $data['capital'] + $data['interest'];
                        }
                        //��Ϣ
                        elseif($value['account_scales_vip']=="interest"){
                            $account = $data['interest'];
                        }
                        //����
                        elseif($value['account_scales_vip']=="capital"){
                            $account = $data['capital'];
                        }
                        
                        if ($data['period']<=$value["vip_borrow_scales_month"]){
                             $scale = $value["vip_borrow_scales"];
                        }else{
                            $scale = $value["vip_borrow_scales"]+($data['period']-$value["vip_borrow_scales_month"])*$value["vip_borrow_scales_scale"];
                        }
                        //���ı���
                        if ($scale>$data["vip_borrow_scales_max"] && $data["vip_borrow_scales_max"]>0){
                            $scale = $data["vip_borrow_scales_max"];
                        }
                        
                        //������ʽ
                        $account = $account*$scale*0.01;
                    }
                    
                        
                }else{
                     //����������
                    if ($value["fee_type"]==1){
                        //��Ϣ
                        if($value['account_scale_all']=="account"){
                            $account = $data['capital'] + $data['interest'];
                        }
                        //��Ϣ
                        elseif($value['account_scale_all']=="interest"){
                            $account = $data['interest'];
                        }
                        //����
                        elseif($value['account_scale_all']=="capital"){
                            $account = $data['capital'];
                        }
                        //���ֵȼ�
                        if ($value['all_rank']==1){
                            $account = $account*$data['credit_fee']*0.01;
                        }
                        //����
                        $account = $account*$value['all_borrow_scale']*0.01;
                        //�Ƿ�����޹ҹ�
                        if ($value["all_period"]==1 && $data['period']>0){
                            if ($data['borrow_type']=="day"){
                                $data['period'] =1;
                            }
                            $account = $account * $data['period'];
                        }
                    }
                    //��������ʽ
                    elseif ($value["fee_type"]==2){
                        
                           
                        //��Ϣ
                        if($value['account_scales_all']=="account"){
                            $account = $data['capital'] + $data['interest'];
                        }
                        //��Ϣ
                        elseif($value['account_scales_all']=="interest"){
                            $account = $data['interest'];
                        }
                        //����
                        elseif($value['account_scales_all']=="capital"){
                            $account = $data['capital'];
                        }
                        
                        if ($data['period']<=$value["all_borrow_scales_month"]){
                             $scale = $value["all_borrow_scales"];
                        }else{
                            $scale = $value["all_borrow_scales"]+($data['period']-$value["all_borrow_scales_month"])*$value["all_borrow_scales_scale"];
                        }
                        //���ı���
                        if ($scale>$value["all_borrow_scales_max"] && $value["all_borrow_scales_max"]>0 ){
                            $scale = $value["all_borrow_scales_max"];
                        }
                        //������ʽ
                        $account = $account*$scale*0.01;
                    }
                }
                //�����Ƿ�ѡ��
                if (!empty($data["round"])){
                 $value['account'] = round($account,2);
               }else{
                    $value['account'] = $account;
                }
                 if ($account>0){
                    $_result[$key] = $value;
                 }
             }
         } 
         return $_result;
    }
    
    
    
	function GetFeeTypeList($data = array()){
		global $mysql,$_G;		
		
        //��ȡ���ʽ
        
		$_select = "p1.*";
		$_order = " order by p1.id ";
		$sql = "select SELECT from  `{borrow_fee_type}` as p1  SQL ORDER LIMIT";
		
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
	function GetFeeTypeOne($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_fee_type_id_empty";
        } 
        
		$_sql = "where  p1.id = {$data['id']} ";
			 
		
		$_select = "p1.*";
		$sql = "select $_select from `{borrow_fee_type}` as p1 $_sql";
		$result = $mysql->db_fetch_array($sql);
		
		return $result;
	}
    
    
    /**
	 * ��ӽ���������
	 *
	 * @param array $data;
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddFeeType($data = array()){
		global $mysql;
        
		 //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "borrow_fee_type_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "borrow_fee_type_nid_empty";
        } 
        $sql = "select 1 from `{borrow_fee_type}` where nid='{$data['nid']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "borrow_fee_type_nid_exiest"; 
        }
		
		$sql = "insert into `{borrow_fee_type}` set ";
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
	function UpdateFeeType($data = array()){
		global $mysql;
		
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_fee_type_id_empty";
        } 
         //�ж������Ƿ����
        if (!IsExiest($data['name'])) {
            return "borrow_fee_type_name_empty";
        } 
        
        //�ж������Ƿ����
        if (!IsExiest($data['nid'])) {
            return "borrow_fee_type_nid_empty";
        } 
        
        $sql = "select 1 from `{borrow_fee_type}` where nid='{$data['nid']}' and id!='{$data['id']}'";
        $result = $mysql->db_fetch_array($sql);
        if ($result!=false){
             return "borrow_fee_type_nid_exiest"; 
        }
		
		$sql = "update `{borrow_fee_type}` set ";
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
	function DeleteFeeType($data = array()){
		global $mysql;
        
         //�ж�id�Ƿ����
        if (!IsExiest($data['id'])) {
            return "borrow_fee_type_id_empty";
        } 
		$sql = "delete  from `{borrow_fee_type}` where id = {$data['id']} ";
		$result = $mysql->db_query($sql);
		
		return $result;
	}
    
     function GetRepayFeeResult($data){
        global $mysql;
        $vip_status =0;
	    $vip_result = usersvipClass::GetUsersVip(array("user_id"=>$data['user_id']));
        if($vip_result==true){
             $vip_status = $vip_result['status'];
        }
        $credit_result = borrowClass::GetBorrowCredit(array("user_id"=>$data["user_id"]));
        
        $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
        $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
        $_fee["borrow_type"] = $data["borrow_type"];//�������
        $_fee["borrow_style"] = $data["borrow_style"];//���ʽ
        $_fee["type"] = "borrow_repay";//���ڽ���߻���Ͷ����
        $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
        $_fee["capital"] = $data["repay_capital"];//���ڽ���߻���Ͷ����
        $_fee["interest"] = $data["repay_interest"];//���ڽ���߻���Ͷ����
        $fee_result = borrowFeeClass::GetFeeValue($_fee);
        if ($fee_result==false){
			$fee_result = array();
		}
        if($data["days"]>0){
            
            $_fee["round"] = 1;//�ж��ǲ���vip
            $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
            $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
            $_fee["borrow_type"] = $data["borrow_type"];//�������
            $_fee["borrow_style"] = $data["borrow_style"];//���ʽ
            $_fee["type"] = "borrow_repay_late";//���ڽ���߻���Ͷ����
            $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
            $_fee["capital"] = $data["repay_capital"];//���ڽ���߻���Ͷ����
            $_fee["interest"] = $data["repay_interest"];//���ڽ���߻���Ͷ����
            $_fee_result = borrowFeeClass::GetFeeValue($_fee);
            if (is_array($_fee_result)){
                foreach ($_fee_result as $key => $value){
                    $_account = $value["account"]*$data["days"];
                    $_fee_result[$key]["account"] = round($_account,2);
                }
                $fee_result = array_merge($fee_result,$_fee_result);
            }
        }
        //�������ڷ���  
        if($data["days"]>30){
            
            $_fee["round"] = 1;//�ж��ǲ���vip
            $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
            $_fee["credit_fee"] =$credit_result['credit']['fee'];//�ж��ǲ���vip
            $_fee["borrow_type"] = $data["borrow_type"];//�������
            $_fee["borrow_style"] = $data["borrow_style"];//���ʽ
            $_fee["type"] = "borrow_repay_late_remind";//���ڽ���߻���Ͷ����
            $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
            $_fee["capital"] = $data["repay_capital"];//���ڽ���߻���Ͷ����
            $_fee["interest"] = $data["repay_interest"];//���ڽ���߻���Ͷ����
            $_fee_result = borrowFeeClass::GetFeeValue($_fee);
            if (is_array($_fee_result)){
                foreach ($_fee_result as $key => $value){
                    $_account = $value["account"]*$data["days"];
                    $_fee_result[$key]["account"] = round($_account,2);
                    $_fee_result[$key]["web_status"] = 1;                                        
                }
                $fee_result = array_merge($fee_result,$_fee_result);
            }
        }
       
        return $fee_result;
    }
}
?>