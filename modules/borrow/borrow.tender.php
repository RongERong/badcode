<?
/******************************
 * $File: borrow.tender.php
 * $Description: Ͷ�����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-08-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

$MsgInfo["tender_borrow_nid_empty"] = "���Ĳ������󡣡�error:tender_borrow_nid_empty��";
$MsgInfo["tender_borrow_not_exiest"] = "���Ĳ������󡣡�error:tender_borrow_not_exiest��";
$MsgInfo["tender_user_lock"] = "�����˺ű��������������Ա��ϵ��";
$MsgInfo["tender_late_yes"] = "�˱��ѹ���";
$MsgInfo["tender_full_yes"] = "�˱�������";
$MsgInfo["tender_self_yes"] = "����Ͷ���Լ��ı�";
$MsgInfo["tender_verify_no"] = "���Ĳ������󡣡�error:tender_verify_no��";
$MsgInfo["tender_money_error"] = "Ͷ�ʽ���ȷ";
$MsgInfo["borrow_paypassword_error"] = "֧�����벻��ȷ";
$MsgInfo["tender_borrowtype_error"] = "���Ĳ������󡣡�error:tender_borrowtype_error��";
$MsgInfo["borrow_password_error"] = "������벻��ȷ";
$MsgInfo["borrow_tender_valicode_error"] = "��֤�벻��ȷ";

require_once(DEAYOU_PATH."modules/account/account.class.php");
require_once(ROOT_PATH."modules/users/users.class.php");
class borrowTenderClass
{
	/**
	 * ���Ͷ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function AddTender($data = array()){
		global $mysql,$_G;
        
		$borrow_result = self::CheckTender(array("borrow_nid"=>$data['borrow_nid'],"user_id"=>$data['user_id']));
        if (!is_array($borrow_result)){
            return $borrow_result;
        }
		
		//�жϽ���Ƿ���ȷ�����ɸġ�
		if(!is_numeric($data['account']) || $data['account']<0){
			return "tender_money_error";
		}
		
		//�ж��Ƿ�С����СͶ�ʽ��ɸġ�
		if($data['account']<$borrow_result['tender_account_min']){
			return "��С��Ͷ�ʽ���С��{$borrow_result['tender_account_min']}��";
		}
		
		//֧�����벻��ȷ
		if ($data['auto_status']!=1){
			if (md5($data['paypassword'])!=$_G['user_result']['paypassword']){
				return "borrow_paypassword_error";
			}
			//��֤��
			if ($data['valicode']!=$_SESSION["valicode"]){
				return "borrow_tender_valicode_error";
			}
		}
		unset($data['valicode']);
		unset($data['auto_status']);
		unset($data['paypassword']);
		$_SESSION["valicode"] = "";
         //�ж������Ƿ���ȷ
        if ($borrow_result['borrow_password']!="" && md5($data["borrow_password"])!=$borrow_result['borrow_password']){
            return "borrow_password_error";
        }
		unset($data['borrow_password']);
		
        
        
		//�ж��Ƿ����Ͷ�ʽ��ɸġ�
		$tender_account_all = self::GetUserTenderAccount(array("user_id"=>$data["user_id"],"borrow_nid"=>$data['borrow_nid']));
        if ($data['account']>$borrow_result['tender_account_max'] && $borrow_result['tender_account_max']>0 ){
			return "�˱����Ͷ����ܴ���{$borrow_result['tender_account_max']}��";
        }elseif ($tender_account_all+$data['account']>$borrow_result['tender_account_max'] && $borrow_result['tender_account_max']>0){
			$tender_account = $borrow_result['tender_account_max']-$tender_account_all;
			return "���Ѿ�Ͷ����{$tender_account_all},���Ͷ���ܽ��ܴ���{$borrow_result['tender_account_max']}������໹��Ͷ��{$tender_account}";
		}else{
			$data['account_tender'] = $data['account'];
			
			//�ж�Ͷ�ʵĽ���Ƿ���ڴ���Ľ��
			if ($borrow_result['borrow_account_wait']<$data['account']){
				$data['account'] = $borrow_result['borrow_account_wait'];
			}
            
            
			//�жϿ��ý���Ƿ��㹻Ͷ��
			$account_result =  accountClass::GetAccountUsers(array("user_id"=>$data['user_id']));//��ȡ��ǰ�û������
			if ($account_result['balance']<$data['account']){
				return "tender_money_no";
			}
		}
		
		
		
		//���Ͷ�ʵĽ����Ϣ
		$sql = "insert into `{borrow_tender}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		$tender_id = $mysql->db_insert_id();
        
		if ($tender_id>0){
			//1���۳����ý��
			$borrow_url = "<a href=/invest/a{$data['borrow_nid']}.html target=_blank>{$borrow_result['name']}</a>";
			$log_info["user_id"] = $data["user_id"];//�����û�id
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 0;//
			$log_info["nid"] = "tender_frost_".$data['user_id']."_".$data['borrow_nid']."_".$tender_id;
			$log_info["borrow_nid"] = $data['borrow_nid'];//����
			$log_info["code"] = "borrow";//
			$log_info["code_type"] = "tender";//
			$log_info["code_nid"] = $tender_id;//
			$log_info["money"] = $data['account'];//�������
			$log_info["income"] = 0;//����
			$log_info["expend"] = 0;//֧��
			$log_info["balance_cash"] = 0;//�����ֽ��
			$log_info["balance_frost"] = -$data['account'];//�������ֽ��
			$log_info["frost"] = $data['account'];//������
			$log_info["await"] = 0;//���ս��
			$log_info["repay"] = 0;//�������
			$log_info["type"] = "tender";//����
			$log_info["to_userid"] = $borrow_result['user_id'];//����˭
			if ($data['auto_status']==1){
				$log_info["remark"] = "�Զ�Ͷ��[{$borrow_url}]�������ʽ�";//��ע
			}else{
				$log_info["remark"] = "Ͷ��[{$borrow_url}]�������ʽ�";//��ע
			}
			$result = accountClass::AddLog($log_info);
			//2�����½�����Ϣ
			$sql = "update  `{borrow}`  set borrow_account_yes=borrow_account_yes+{$data['account']},borrow_account_wait=borrow_account_wait-{$data['account']},borrow_account_scale=(borrow_account_yes/account)*100,tender_times=tender_times+1  where borrow_nid='{$data['borrow_nid']}'";
			$mysql->db_query($sql);//�����Ѿ�Ͷ���Ǯ
			
			//3������ͳ����Ϣ
			borrowCountClass::UpdateBorrowCount(array("user_id"=>$data['user_id'],"borrow_nid"=>$data['borrow_nid'],"nid"=>"tender_frost_".$data['user_id']."_".$data['borrow_nid']."_".$tender_id,"tender_times"=>1,"tender_account"=>$data['account'],"tender_frost_account"=>$data['account']));
		
		
			//4���������� Ͷ����
			$borrow_url = "<a href={$_G['web_domain']}/invest/a{$borrow_result['borrow_nid']}.html target=_blank>{$borrow_result['name']}</a>";
			$remind['nid'] = "tender"; 
            $remind['remind_nid'] =  "tender_".$data['user_id']."_".$tender_id;
			$remind['code'] = "invest";
			$remind['article_id'] = $tender_id;
			$remind['receive_userid'] = $data['user_id'];
			$remind['title'] = "�ɹ�Ͷ��[{$borrow_result['name']}]";
			$remind['content'] = "���ɹ�Ͷ����{$borrow_url}����ȴ�����Ա���";
			remindClass::sendRemind($remind);
			
			//5���������� �����
			$borrow_url = "<a href={$_G['web_domain']}/invest/a{$borrow_result['borrow_nid']}.html target=_blank>{$borrow_result['name']}</a>";
			$remind['nid'] = "borrow_tender";
            $remind['remind_nid'] =  "borrow_tender_".$borrow_result['user_id']."_".$tender_id;
			$remind['code'] = "borrow";
			$remind['article_id'] = $data["user_id"];
			$remind['receive_userid'] = $borrow_result['user_id'];
			$remind['title'] = "����[{$borrow_result['name']}]����Ͷ��";
			$remind['content'] = "���Ľ���{$borrow_url}����Ͷ�ʡ�";
			remindClass::sendRemind($remind);
			
			
		}
		return $tender_id;
	}
	
	//��ȡ�û�����Ͷ�ʶ������ȫ���ģ�Ҳ���Ե�����ĳ����
	function GetUserTenderAccount($data){
		global $mysql;
		$_sql = " where 1=1 ";
		if (IsExiest($data['user_id'])!=""){
			$_sql .= " and user_id='{$data['user_id']}' ";
		}
		if (IsExiest($data['borrow_nid'])!=""){
			$_sql .= " and borrow_nid='{$data['borrow_nid']}' ";
		}
		$sql = "select sum(account) as account_all from `{borrow_tender}` {$_sql}";
       
		$result = $mysql->db_fetch_array($sql);
		if ($result!=fasle ) {
			return $result["account_all"];
		}
		return 0;
	}
    
    
	/**
	 * ����Ƿ����Ͷ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function CheckTender($data = array()){
		global $mysql,$_G;
		//��һ�����ж�borrow_nid�Ƿ�Ϊ��
		if (IsExiest($data['borrow_nid']) ==""){
			return "tender_borrow_nid_empty";
		}
		
		//�ڶ������ж��Ƿ���ڽ���
		$borrow_result = borrowClass::GetView(array("borrow_nid"=>$data['borrow_nid']));
		if (!is_array($borrow_result)){
			return "tender_borrow_not_exiest";
		}
		
		//���������ж��˺��Ƿ�����
		if ($_G['user_result']['islock']==1){
			return "tender_user_lock";
		}
		
		//���岽���ж��Ƿ��Ѿ�ͨ��������ˡ����ɸġ�
		if ($borrow_result['verify_time'] == "" || $borrow_result['status'] != "1"){
			return "tender_verify_no";
		}
		
		//���������ж��Ƿ����
		if ($borrow_result['verify_time'] <time() - $borrow_result['borrow_valid_time']*60*60*24){
			return "tender_late_yes";
		}
		
		//�ж��Ƿ�����
		if ($borrow_result['account'] <=$borrow_result['borrow_account_yes']){
			return "tender_full_yes";
		}
        
		//����˲����Լ�Ͷ��
		if ($borrow_result['user_id'] == $data['user_id']){
			return "tender_self_yes";
		}
        
		//���������ж��Ƿ��Ѿ����ڡ����ɸġ�
		if ($borrow_result['verify_time']<time() - $borrow_result['borrow_valid_time']*60*60*24){
			
			return "tender_late_yes";
		}
		return $borrow_result;
	}
    
    
    
	/**
	 * Ͷ���б�
	 *
	 * @return Array
	 */
	function GetTenderList($data = array()){
		global $mysql;
		
		$_sql = "where 1=1 ";	
		
		//�ж��û�id
		if (IsExiest($data['user_id']) != false){
			if ($data['change_show']==1){
				$_sql .= " and (p1.change_status=1 and p1.change_userid={$data['user_id']}) or (p1.change_status!=1 and p1.user_id={$data['user_id']})";
			}else{
				$_sql .=" and p1.user_id={$data['user_id']}" ;
			}
		}
		
		//�жϽ���û�
		if (IsExiest($data['borrow_userid']) != false){
			$_sql .= " and p3.user_id = {$data['borrow_userid']}";
		}
		
		//�ѵ��û���
		if (IsExiest($data['username']) != false){
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		
		//�����������
		if (IsExiest($data['borrow_status']) != false){
			$_sql .= " and p3.`status` in ({$data['borrow_status']})";
		}
		
		if ($data['change_status']!=""){
			$_sql .= " and p1.`change_status` in  ({$data['change_status']})";
		}
		//�����������
		if (IsExiest($data['borrow_name']) != false){
			$_sql .= " and p3.`name` like '%".urldecode($data['borrow_name'])."%'";
		}
		//�����������
		if (IsExiest($data['borrow_nid']) != false){
			$_sql .= " and p3.`borrow_nid` = '{$data['borrow_nid']}'";
		}
		
		//Ͷ������
		if (IsExiest($data['tender_type']) != false){
            if ($data['tender_type']=="wait"){
                $_sql .= " and p3.`status` = 3 and p3.repay_full_status=0";
            }elseif ($data['tender_type']=="over"){
                 $_sql .= " and p3.`status` = 3 and p1.account=p1.recover_account_capital_yes";
            }
			
		}
		
		if (IsExiest($data['keywords'])!=""){
			$_sql .= " and (p3.name like '%".urldecode($data['keywords'])."%') ";
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
		
		//�жϽ��״̬
		if (IsExiest($data['status'])!=""){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		//�ж��Ƿ񵣱����
		if (IsExiest($data['vouch_status'])!=""){
			$_sql .= " and p3.vouch_status in ({$data['vouch_status']})";
		}
		
		//�������
		if (IsExiest($data['borrow_period'])!=""){
			$_sql .= " and p3.borrow_period = {$data['borrow_period']}";
		}
		
		//������
		if (IsExiest($data['flag'])!=""){
			$_sql .= " and p3.flag = {$data['flag']}";
		}
		
		//�����;
		if (IsExiest($data['borrow_use']) !=""){
			$_sql .= " and p3.borrow_use in ({$data['borrow_use']})";
		}
		
		//����û�����
		if (IsExiest($data['borrow_usertype']) !=""){
			$_sql .= " and p3.borrow_usertype = '{$data['borrow_usertype']}'";
		}
		
		
		//���
		if (IsExiest($data['borrow_style']) ){
			$_sql .= " and p3.borrow_style in ({$data['borrow_style']})";
		}
		
		//�ж���ת���Ƿ��Ѿ�������
		if (IsExiest($data['roam_status']) ){			
			$_sql .= " and ((p3.borrow_type='roam' and p1.recover_account_all !=  p1.recover_account_yes) or p3.borrow_type='pawn' ) ";
		}
		
		//���Ȩ��
		if (IsExiest($data['account1'])!=""){
			$_sql .= " and p1.account >= {$data['account1']}";
		}
		if (IsExiest($data['account2'])!=""){
			$_sql .= " and p1.account <= {$data['account2']}";
		}
		//���ٲ���
		if (IsExiest($data['dodate'])!=""){
			if($data['dodate']=='oneweek'){
				$dodate = time()-7*24*60*60; 				
			}elseif($data['dodate']=='twoweek'){
				$dodate = time()-2*7*24*60*60; 				
			}elseif($data['dodate']=='onemonth'){
				$dodate = time()-30*24*60*60; 				
			}
			$_sql .= " and p1.addtime >= {$dodate} and p1.addtime <= ".time();
		}
		//����
		$_order = " order by p1.id desc ";
		
		$_select = " p1.*,p2.username,
        p3.name as borrow_name,p3.account as borrow_account,p3.borrow_type,
        p4.username as borrow_username,p3.repay_account_wait as borrow_account_wait_all,
        p3.repay_account_interest_wait as borrow_interest_wait_all,p4.user_id as borrow_userid,p3.borrow_apr,p3.borrow_period,p3.borrow_account_scale,p5.credits,p7.name as borrow_type_name,p3.verify_time as borrow_verify_time";
		$sql = "select SELECT from `{borrow_tender}` as p1 
				 left join `{users}` as p2 on p1.user_id=p2.user_id
				 left join `{borrow}` as p3 on p1.borrow_nid=p3.borrow_nid
				 left join `{borrow_type}` as p7 on p7.nid=p3.borrow_type
				 left join `{users}` as p4 on p4.user_id=p3.user_id
				 left join `{credit}` as p5 on p5.user_id=p3.user_id
				 SQL ORDER LIMIT
				";
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
            foreach ($list as $key => $value){
                $period_name = "����";
                if ($value["borrow_type"]=="day"){
                    $period_name = "��";
                }
                $list[$key]["borrow_period_name"] =$value["borrow_period"].$period_name;
                if ($value['borrow_type']=="roam"){
                    $list[$key]['repay_last_time'] = strtotime("{$value["borrow_period"]} month",$value['verify_time']);
					
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
		$_limit = " limit ".($data['epage'] * ($data['page'] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		foreach ($list as $key => $value){
		      
            $period_name = "����";
            if ($value["borrow_type"]=="day"){
                $period_name = "��";
            }
           $list[$key]["borrow_period_name"] =$value["borrow_period"].$period_name;
           $list[$key]["credit"] = borrowClass::GetBorrowCredit(array("user_id"=>$value['borrow_userid']));
		  
			$chsql="select status,buy_time from `{borrow_change}` where tender_id={$value['id']}";
			$chresult=$mysql->db_fetch_array($chsql);
			if ($chresult['status']==1){
				$recsql="select count(1) as count_all,
				sum(recover_account_yes) as recover_account_yes_all,
				sum(recover_interest_yes) as recover_interest_yes_all
				from `{borrow_recover}` where user_id={$value['user_id']} and borrow_nid={$value['borrow_nid']} and recover_yestime<{$chresult['buy_time']} and tender_id={$value['id']} and recover_status=1";
				$recresult=$mysql->db_fetch_array($recsql);
				$list[$key]["recover_interest_yes_all"] = $recresult['recover_interest_yes_all'];
				$list[$key]["recover_account_yes_all"] = $recresult['recover_account_yes_all'];
				$list[$key]["count_all"] = $recresult['count_all'];
			}
			$recoversql="select count(1) as num from `{borrow_repay}` where borrow_nid={$value['borrow_nid']} and (repay_status=1 or repay_web=1)";
			$recoverresult=$mysql->db_fetch_array($recoversql);
			$list[$key]['norepay_num'] = $value['borrow_period'] - $recoverresult['num'];
            
            if ($value['borrow_type']=="roam"){
                $list[$key]['repay_last_time'] = strtotime("{$value["borrow_period"]} month",$value['verify_time']);;
            }
		}
      
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
    
    
    
	/**
	 * 6,�鿴Ͷ�ʱ� 
	 *
	 * @param Array $data = array("id"=>"Ͷ�����","tender_nid"=>"Ͷ�ʱ�ʶ��");
	 * @return Array
	 */
	public static function GetTenderOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		
		if (IsExiest($data['id'])!=""){
			$_sql .= " and  p1.id = '{$data['id']}' ";
		}
		
		if (IsExiest($data['tender_id'])!=""){
			$_sql .= " and  p1.tender_id = '{$data['tender_id']}' ";
		}
		
		$_select = " p1.*,p2.username,p3.name as borrow_name,p3.account as borrow_account,p3.borrow_period,p3.borrow_style,p3.borrow_use,p3.borrow_flag,p3.borrow_apr";
		$sql = "select {$_select} from `{borrow_tender}` as p1 
				 left join `{users}` as p2 on p1.user_id=p2.user_id
				 left join `{borrow}` as p3 on p1.borrow_nid=p3.borrow_nid
				 {$_sql}";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "borrow_tender_not_exiest";
		return $result;
	}
	
	
	
	/**
	 * 7,Ͷ�ʳ�����ֻҪ������Ͷ���˲���Ͷ������¿����ֶ��ĳ��أ����������һ���� 
	 *
	 * @param Array $data = array("id"=>"Ͷ�����","tender_nid"=>"Ͷ�ʱ�ʶ��");
	 * @return Array
	 */
	public static function CancelTender($data = array()){
		global $mysql;
		$sql = "select * from `{borrow_tender}` where tender_nid='{$data['tender_nid']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "borrow_tender_not_exiest";
		if ($result['tender_status']>0) return "borrow_tender_verify_yes";
		
		$sql = "update `{borrow_tender}` set status=0 where tender_nid='{$data['tender_nid']}'";
		$mysql->db_query($sql);
		
		
		return $data['tender_nid'];
	}
	
	
    
	
	
	//�տ���ϸ
	function GetRecoverList($data){
		global $mysql,$_G;
		
		$_sql = " where 1=1 ";
		if (IsExiest($data['user_id'])!=false){
			if ($data['change_show']==1){
				$_sql .= " and (p5.change_status=1 and p5.change_userid={$data['user_id']} and (p1.recover_yestime>p7.buy_time or p1.recover_yestime is NULL)) or (p5.change_status!=1 and p1.user_id={$data['user_id']})";
			}else{
				$_sql .= " and p1.user_id={$data['user_id']}";
			}
		}
		if (IsExiest($data['status'])!=false){
			$_sql .= " and p1.status={$data['status']}";
		}
		if (IsExiest($data['recover_status'])!=false){
			if($data['recover_status']==2){
				$_sql .= " and p1.recover_status=0";
			}else{
				$_sql .= " and p1.recover_status={$data['recover_status']}";
			}
		}
		if (IsExiest($data['borrow_status'])!=false){
			$_sql .= " and p2.status={$data['borrow_status']}";
		}
		if (IsExiest($data['username'])!=false){
			$_sql .= " and p3.username like '%{$data['username']}%' ";
		}
		//�������� 
		if (IsExiest($data['borrow_name'])!=false){
			$data['borrow_name'] = urldecode($data['borrow_name']);
			$_sql .= " and p2.name like '%{$data['borrow_name']}%' ";
		}
		//���������
		if (IsExiest($data['borrow_nid'])!=false){
			$_sql .= " and p1.borrow_nid = {$data['borrow_nid']}";
		}
		//������������
		if (IsExiest($data['borrow_type'])!=false){
			$_sql .= " and p2.borrow_type='{$data['borrow_type']}'";
		}
		
		if (IsExiest($data['web'])!=false){
			$_sql .= " and p6.web_status=2";
		}
		
		if (IsExiest($data['recover_status'])!=false || $data['recover_status']=="0"){
			$_sql .= " and p1.recover_status='{$data['recover_status']}'";
		}
		
		if (IsExiest($data['dotime1'])!=false){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.recover_time > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['dotime2'])!=false){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.recover_time < ".get_mktime($dotime2);
			}
		}
        
		if (IsExiest($data['late'])!=false){
			$_sql .= " and (p1.recover_time < ".time()." and p1.recover_status!=1) or (p1.recover_status=1 and p1.late_days>0)";
		}
        
        
		if (IsExiest($data['yestime1'])!=false){
			$yestime1 = ($data['yestime1']=="request")?$_REQUEST['yestime1']:$data['yestime1'];
			if ($yestime1!=""){
				$_sql .= " and p1.recover_yestime > ".get_mktime($yestime1);
			}
		}
		
		if (IsExiest($data['yestime2'])!=false){
			$yestime2 = ($data['yestime2']=="request")?$_REQUEST['yestime2']:$data['yestime2'];
			if ($yestime2!=""){
				$_sql .= " and p1.recover_yestime < ".get_mktime($yestime2);
			}
		}
        
		if (IsExiest($data['type'])!=false){
			if ($data['type']=="yes"){
				$_sql .= " and p1.recover_status =1 or p1.recover_web=1";
			}elseif ($data['type']=="wait"){
				$_sql .= " and p1.recover_status !=1 and p1.recover_web!=1";
			}elseif ($data['type']=="web"){
				$_sql .= " and p1.recover_web=1";
			}
		}
		if (IsExiest($data['change'])!=false){
			$_sql .= " and p1.recover_status =1 and p5.change_status=1";
		}
		if (IsExiest($data['money1'])!=false){
			$_sql .= " and p1.recover_account >= '{$data['money1']}'";
		}
		if (IsExiest($data['money2'])!=false){
			$_sql .= " and p1.recover_account <= '{$data['money2']}'";
		}
		if (IsExiest($data['borrow_nid'])!=false){
			$_sql .= " and p1.borrow_nid = '{$data['borrow_nid']}'";
		}
		$onetime = time()+1*30*24*60*60;
		$threetime = time()+3*30*24*60*60;
		$sixtime = time()+6*30*24*60*60;
		if (IsExiest($data['dodate'])!=false){
			if($data['dodate']=="onemonth"){
				$dodate = $onetime;
			}elseif($data['dodate']=="threemonth"){
				$dodate = $threetime;
			}elseif($data['dodate']=="sixmonth"){
				$dodate = $sixtime;
			}
			$_sql .= " and p1.recover_time > ".time()." and p1.recover_time <= '{$dodate}' ";
		}		
		if (IsExiest($data['keywords'])!=""){
			$_sql .= " and (p2.name like '%".urldecode($data['keywords'])."%') ";
		}
		
		$_order = " order by p2.id ";
		if (IsExiest($data['order'])!="" ){
			if ($data['order'] == "repay_time"){
				$_order = " order by p2.id desc,p1.recover_time asc";
			}elseif ($data['order'] == "order"){
				$_order = " order by p1.`order` desc,p1.id desc ";
			}elseif ($data['order'] == "recover_status"){
				$_order = " order by p1.`recover_status` asc,p1.id desc ";
			}
		}
		if($data['protocol']==1){
			$_select = 'p1.recover_period,p1.recover_time,sum(p1.recover_account) as recover_account,sum(p1.recover_capital) as recover_capital,sum(p1.recover_interest) as recover_interest';
			$_order = " order by p1.`recover_period` asc ";
			$group = "group by p1.recover_period";
		}else{
			$_select = 'p1.*,p6.name as borrow_type_name,p6.title as type_title,p1.recover_account_yes as recover_recover_account_yes,p2.name as borrow_name,p2.borrow_period,p2.borrow_type,p2.borrow_apr,p3.username,p4.username as borrow_username,p4.user_id as borrow_userid,p5.recover_account_yes as tender_recover_account_yes';
			$group = "";
		}
		
		
		
		
	
		$sql = "select SELECT from `{borrow_recover}` as p1 
				left join `{borrow}` as p2 on  p2.borrow_nid = p1.borrow_nid
				left join `{borrow_type}` as p6 on  p6.nid = p2.borrow_type
				left join `{users}` as p3 on  p3.user_id = p1.user_id
				left join `{users}` as p4 on  p4.user_id = p2.user_id
				left join `{borrow_tender}` as p5 on  p1.tender_id = p5.id
				left join `{borrow_change}` as p7 on  p1.tender_id = p7.id
			   {$_sql} $group ORDER LIMIT";		   
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list  = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
			return $list;
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(" count(*) as num ","",""),$sql));
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order , $limit), $sql));
	   foreach ($list as $key => $value){
	       $type_name = "";
	       if ($value["recover_type"]=="advance"){
	           $type_name = "��ǰ����";
	       }elseif ($value["recover_type"]=="yes"){
	           $type_name = "��������";
	       }elseif ($value["recover_type"]=="late"){
	           $type_name = "���ڻ���";
	       }elseif ($value["recover_type"]=="web"){
	           $type_name = "��վ�渶";
	       }
		   $days= borrowClass::GetDays(array("repay_time"=>$value["recover_time"]));
			if ($days>0){
				$list[$key]['late_days'] = $days;
			}
            if ($value["borrow_type"]=="roam"){
                 $list[$key]["borrow_period"] = 1;
            }
	       $list[$key]["recover_type_name"] = $type_name;
		   //������Ϣ�����
		   $vip_status = usersClass::GetUsersVipStatus(array("user_id"=>$value["user_id"]));
		   if($vip_status==1){
			  $list[$key]["interest_fee"] = round($list[$key]["recover_interest"]*0.08,2);
		   }else{
			  $list[$key]["interest_fee"] = round($list[$key]["recover_interest"]*0.1,2);
		   }
		   $list[$key]["account_wait"]=$list[$key]["recover_account"]-$list[$key]["interest_fee"];
		   
		   
	   }
	   $lists = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order , ''), $sql));
	   foreach ($lists as $key => $value){
			//ͳ�ƴ��ս��  $onetime $threetime $sixtime
		   if($value["recover_time"] <= $onetime){
				$onemonth += $value["recover_account"];
		   }
		   if($value["recover_time"] <= $threetime){
				$threemonth += $value["recover_account"];
		   }
		   if($value["recover_time"] <= $sixtime){
				$sixmonth += $value["recover_account"];
		   }
		   $allmonth += $value["recover_account"];
	   }
		return array(
            'list' => $list,
            'change' => $change,
            'all_capital' => $all_capital,
            'all_recover' => $all_recover,
            'web' => $web,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page,
			'onemonth' => $onemonth,
			'threemonth' => $threemonth,
			'sixmonth' => $sixmonth,
			'allmonth' => $allmonth			
        );
	}
    
    
    	
	//�ѳɹ��Ľ��
	function GetTenderBorrowList($data){
		global $mysql,$_G;
		$user_id =$data['user_id'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1";
		if (IsExiest($data['type'])!=""){
			if ($data['type']=="wait"){
				$_sql .= " and p1.recover_times<p2.borrow_period and p1.user_id={$user_id} and p1.change_status!=1";
			}elseif ($data['type']=="change"){
				$_sql .= " and p1.recover_account_all!=p1.recover_account_yes and  p1.change_userid={$user_id} and p1.change_status=1";
			}elseif ($data['type']=="yes"){
				$_sql .= " and p1.recover_times=p2.borrow_period and p1.user_id={$user_id} and p1.change_status=0";
			}
		}
		
		
		if (IsExiest($data['dotime1'])!=""){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['dotime2'])!=""){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		if (IsExiest($data['tender_status'])!=""){
			$_sql .= " and p1.status = {$data['tender_status']}";
		}
		if (IsExiest($data['keywords']) !=""){
			$_sql .= " and (p2.`name` like '%".urldecode($data['keywords'])."%') ";
		}
		if (IsExiest($data['borrow_status']) !=""){
			$_sql .= " and p2.status = {$data['borrow_status']}";
		}
		if (IsExiest($data['change_status']) !=""){
			$_sql .= " and p1.change_status = {$data['change_status']}";
		}
		if (IsExiest($data['change_userid']) !=""){
			$_sql .= " and p1.change_userid = '{$data['change_userid']}'";
		}
		if (IsExiest($data['user_id']) !=""){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		$_select  = "p1.id,p1.recover_account_yes,p2.borrow_nid,p2.borrow_nid,p2.name,p2.borrow_apr,p2.user_id,p2.borrow_type,p2.borrow_period,p1.recover_times,p1.account as tender_account,p1.recover_account_wait,p1.user_id as tuser,p2.account as borrow_account,p2.borrow_account_yes,p3.username as borrow_username,p4.credits,p5.account as change_account,p5.id as change_id";
		
		$sql = "select SELECT from `{borrow_tender}` as p1 left join `{borrow}` as p2 on p1.borrow_nid=p2.borrow_nid left join `{users}` as p3 on p2.user_id=p3.user_id left join `{credit}` as p4 on p2.user_id=p4.user_id left join `{borrow_change}` as p5 on p5.tender_id=p1.id {$_sql} ORDER";
	
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.`order` desc,p1.id desc', $_limit), $sql));
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array("count(*) as  num","",""),$sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p2.id desc', $limit), $sql));		
		$list = $list?$list:array();
		foreach ($list as $key => $value){
			$recoversql="select count(1) as num from `{borrow_repay}` where borrow_nid={$value['borrow_nid']} and (repay_status=1 or repay_web=1)";
			$recoverresult=$mysql->db_fetch_array($recoversql);
			$list[$key]['wait_times'] = $value['borrow_period'] - $recoverresult['num'];
			$list[$key]["credit"] = borrowClass::GetBorrowCredit(array("user_id"=>$value['user_id']));
			$chsql="select status,buy_time from `{borrow_change}` where tender_id={$value['id']}";
			$chresult=$mysql->db_fetch_array($chsql);
			if ($chresult['status']==1){
				$recsql="select count(1) as count_all,sum(recover_account_yes) as recover_account_yes_all from `{borrow_recover}` where user_id={$value['tuser']} and borrow_nid={$value['borrow_nid']} and (recover_yestime>{$chresult['buy_time']} or recover_yestime is NULL) and tender_id={$value['id']}";
				$recresult=$mysql->db_fetch_array($recsql);
				$list[$key]["recover_account_yes_all"] = $recresult['recover_account_yes_all'];
				$list[$key]["count_all"] = $recresult['count_all'];
			}
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
    
	function GetRecoverVouchList($data = array()){
		global $mysql;
	
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where p1.borrow_nid=p2.borrow_nid and p2.user_id=p3.user_id ";
		if (IsExiest($data['borrow_nid'])!=""){
			if ($data['borrow_nid'] == "request"){
				$_sql .= " and p1.borrow_nid= '{$_REQUEST['borrow_nid']}'";
			}else{
				$_sql .= " and p1.borrow_nid= '{$data['borrow_nid']}'";
			}
		}
		
		if (IsExiest($data['user_id'])!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}	 
		
		if (IsExiest($data['vouch_userid']) !=""){
			$_sql .= " and p1.user_id = '{$data['vouch_userid']}'";
		}	 
		
		if (IsExiest($data['username'])!=""){
			$_sql .= " and p3.username like '%{$data['username']}%'";
		}	 
		
		if (IsExiest($data['type'])=="late"){
			$_sql .= " and p1.repay_time<".time() ." and p1.status=0";
		}	 
		
		if (IsExiest($data['repay_time'])!=""){
			if ($date['repay_time']<=0) $data['repay_time'] = time();
			$_repayment_time = get_mktime(date("Y-m-d",$data['repay_time']));
			$_sql .= " and p1.repay_time < '{$_repayment_time}'";
		}	 
		
		if (IsExiest($data['dotime2'])!=""){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if ($dotime2!=""){
				$_sql .= " and p2.addtime < ".get_mktime($dotime2);
			}
		}
		if (IsExiest($data['dotime1'])!=""){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if ($dotime1!=""){
				$_sql .= " and p2.addtime > ".get_mktime($dotime1);
			}
		}
		
		if (IsExiest($data['status'])!=""){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		
		if (IsExiest($keywords)!=""){
		    if ($keywords=="request"){
				if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
					$_sql .= " and p2.name like '%".urldecode($_REQUEST['keywords'])."%'";
				}
			}else{
				$_sql .= " and p2.name like '%".$keywords."%'";
			}
			
		}
		
		$_order = " order by p1.id desc";
		if (isset($data['order']) && $data['order']!="" ){
			if ($data['order'] == "repayment_time"){
				$_order = " order by p1.repay_time asc ";
			}elseif ($data['order'] == "order"){
				$_order = " order by p1.order asc ,p1.id desc";
			}
		}
		
		$_select = " p1.*,p2.name as borrow_name,p2.borrow_period,p3.username as borrow_username";
		$sql = "select SELECT from `{borrow_vouch_recover}` as p1 left join `{borrow}` as p2 on p1.borrow_nid = p2.borrow_nid left join `{users}` as p3 on p3.user_id=p2.user_id {$_sql} ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
			
			foreach ($list as $key => $value){
				$late = self::LateInterest(array("time"=>$value['repay_time'],"account"=>$value['reapy_account']));
				if ($value['status']!=1){
					$list[$key]['late_days'] = $late['late_days'];
					$list[$key]['late_interest'] = $late['late_interest'];
				}
			}
			return $list;
		}			 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		foreach ($list as $key => $value){
			$late = self::LateInterest(array("time"=>$value['repay_time'],"account"=>$value['capital']));
			if ($value['status']!=1){
				$list[$key]['late_days'] = $late['late_days'];
				$list[$key]['late_interest'] = $late['late_interest'];
			}
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
}
?>
