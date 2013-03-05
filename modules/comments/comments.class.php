<?
/******************************
 * $File: comments.class.php
 * $Description: ���۹���
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved

 �� {comment} �滻�� {comment}
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

//�����Ը������
require_once("comments.model.php");

class commentsClass{

	
	/**
	 * 1,�������
	 *
	 * @param array $data =array("name"=>"����");
	 * @param string $data;
	 * @return boolen(true,false)
	 */
	function AddComments($data = array()){
		global $mysql,$_G;
		 //�ж������Ƿ����
        if (!IsExiest($data['contents'])) {
            return "comments_contents_empty";
        }
		
		 //�ж��û��Ƿ����
        if (!IsExiest($data['user_id'])) {
            return "comments_user_id_empty";
        }
		
		$sql = "select username,reg_time from `{users}` where user_id='{$data['user_id']}'";
		$user_result = $mysql->db_fetch_array($sql);
		if ($user_result==false) return "comments_user_id_empty";
		
		
		//�ж��Ƿ��ע����û�
		if ($_G['system']['con_comments_status']==0){
			//return "comments_status_close";
		}
		
		//�ж��Ƿ��ע����û�
		if ($_G['system']['con_comments_time']>0){
			if ($user_result['reg_time']<time()-$_G['system']['con_comments_time']*60 ){
				return "comments_time_close";
			}
		}
		
		//�ж��Ƿ��ǽ�ֹ���û�
		if ($_G['system']['con_comments_users']!=""){
			$comments_users = explode("|",$_G['system']['con_comments_users']);
			if (in_array($user_result['username'],$comments_users)){
				return "comments_users_close";
			}
		}
		
		if ($data['site_id']!=""){
			$sql = "select type,value,nid from `{site}` where id='{$data['site_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['type']=="page"){
				$data['article_id'] = $result['value'];
				$data['code'] = 'articles';
				$data['type'] = 'page';
			}elseif ($result['type']=="article"){;
				$data['code'] = 'articles';
				$data['type'] = 'article';
			}elseif ($result['type']=="code"){;
				$data['code'] = $result['value'];
				$data['type'] = $result['nid'];
			}
		}
		if ($data['type']=="home"){
			$data['code'] = 'home';
			$data['type'] = 'user';
		}
		
		$sql = "insert into `{comment}` set addtime='".time()."',addip='".ip_address()."',";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
    	return $mysql->db_insert_id();
	}
	
	
	/**
	 * 3,��������
	 *
	 * @param Array $data = array("id"=>"ID")
	 * @return Boolen
	 */
	function ActionComments($data = array()){
		global $mysql;
		
		if (!IsExiest($data['id'])) return "comment_id_empty";
		if ($data['type'] == "delete"){
			$sql = "delete from `{comment}`  where id in ({$data['id']}) or pid in ({$data['id']}) or reply_id in ({$data['id']})";
			$mysql -> db_query($sql);
		}elseif ($data['type'] == "yes"){
			$sql = "update `{comment}`  set status=1 where id in ({$data['id']}) ";
			$mysql -> db_query($sql);
		}elseif ($data['type'] == "no"){
			$sql = "update `{comment}`  set status=2 where id in ({$data['id']}) ";
			$mysql -> db_query($sql);
		}
		return $data['id'];
	}
	
	
	
	
	/**
	 * 5,�������б�
	 *
	 * @return Array
	 */
	function GetCommentsList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		if (IsExiest($data['username'])!=""){
			$_sql .= " and  p2.username like '%{$data['username']}%' ";
		}
		
		$_select = " p1.*,p2.username";
		$_order = " order by p1.status asc,p1.id desc";
		$sql = "select SELECT from `{comment}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id  SQL ORDER LIMIT";
		
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$result = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			foreach ($result as $key => $value){
				$result[$key]["contents"] =  preg_replace('[\[\:([0-9]+)*\:\]]',"<img src=/data/images/face/$1.gif>", $value["contents"]);
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
		$_limit = " limit ".($data["epage"] * ($data["page"] - 1)).", {$data['epage']}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL','ORDER', 'LIMIT'), array($_select,$_sql,$_order, $_limit), $sql));
		foreach ($list as $key => $value){
			$list[$key]["contents"] =  preg_replace('[\[\:([0-9]+)*\:\]]',"<img src=/data/images/face/$1.gif>", $value["contents"]);
		}
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		return $result;
	}
	
	
	
	/**
	 * 6,������ĵ�����¼
	 *
	 * @param Array $data = array("id"=>"");
	 * @return Array
	 */
	 function GetCommentsOne($data = array()){
		global $mysql;
		if (!IsExiest($data['id'])) return "comments_id_empty";
		
		$sql = "select p1.*,p2.username from `{comment}` as p1  left join `{users}` as p2 on p1.user_id=p2.user_id   where p1.id='{$data['id']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return "comments_empty";
		$result["contents"] =  preg_replace('[\[\:([0-9]+)*\:\]]',"<img src=/data/images/face/$1.gif>", $result["contents"]);
		return $result;
	}
	
	
	/**
	 * 5,��������б�
	 *
	 * @return Array
	 */
	function GetCommentsSiteList($data = array()){
		global $mysql;
		
		$_sql = " where 1=1 ";
		
		if (isset($data['siteid']) && $data['siteid']!=""){
			$sql = "select * from `{site}` where id='{$data['siteid']}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['type']=="page"){
				$_sql .= " and p1.code='articles' and p1.type='page' and p1.article_id='{$result['value']}'";
			}elseif ($result['type']=="article"){
				$_sql .= " and p1.code='articles' and p1.type='article' and p1.article_id='{$data['article_id']}'";
			}elseif ($result['type']=="code"){
				$_sql .= " and p1.code='{$result['value']}' and p1.type='{$result['nid']}' and p1.article_id='{$data['article_id']}'";
			}
		}
		
		if (isset($data['code']) && $data['code']!=""){
			$_sql .= " and p1.code='{$data['code']}'";
		}
		
		if (isset($data['article_id']) && $data['article_id']!=""){
			$_sql .= " and p1.article_id='{$data['article_id']}'";
		}
		
		if (isset($data['status']) && ($data['status']!="" || $data['status']=="0")){
			$_sql .= " and p1.status='{$data['status']}'";
		}
		
		$_select = " p1.*,p2.username";
		$_order = " order by p1.id asc";
		$sql = "select SELECT from `{comment}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id SQL ORDER LIMIT";
		//�Ƿ���ʾȫ������Ϣ
		if (IsExiest($data['limit'])!=false){
			if ($data['limit'] != "all"){ $_limit = "  limit ".$data['limit']; }
			$result =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'SQL', 'ORDER', 'LIMIT'), array($_select, $_sql, $_order, $_limit), $sql));
			
			foreach ($result as $key => $value){
				$result[$key]["contents"] =  preg_replace('[\[\:([0-9]+)*\:\]]',"<img src=/data/images/face/$1.gif>", $value["contents"]);				
			}
			$_result = array();
			foreach ($result as $key => $value){
				if ($value['pid']==0){
					$_result[$value['id']]['value'] = $value;
				}
			}
			foreach ($result as $key => $value){
				if ($value['pid']>0){
					$_result[$value['pid']]['result'][] = $value;
				}
			}
			return array("list"=>$_result,"total"=>count($result));
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
		$list =  $list?$list:array();
		$_users = array();
		foreach ($list as $key => $value){
			$_cid[] = $value['id'];
		}
		$_list = array();
		if (count($_users)>0){
			$_users = join(',',$_users);
			$sql = "select p1.*,p2.username from `{comment}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id where tid in ({$_cid})";
			$result = $mysql->db_fetch_arrays($sql);
			$__list = array();
			foreach ($result as $_key => $_value){
				$__list[$_value['tid']][$_value['id']] = $_value;
			}
			foreach ($list as $key => $value){
				$_list[$key] = $value;
				$_list[$key]['sub_result'] = $__list[$value['id']];
				
			}
		}else{
			$_list = $list;
		}
		//�������յĽ��
		$result = array('list' =>$_list,'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
	}
	
    /**
     * ��ȡ�����б�
     * @param $module ģ��
     * @param $article_id ����ID
     * @param $statu ״̬
     * @param $page ҳ��
     * @param $page_size ÿҳ��¼��
     */
    public static function GetList ($data = array()) {
        global $mysql;
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 ";//ֱ�Ӷ����µ�����
		
		//�ж�ģ��
        if(IsExiest($data['code'])!=""){
			$_sql .= " and  c.code = '{$data['code']}' "; 
		}
		
		//�ж�����id
        if(IsExiest($data['article_id'])!=""){
			$_sql .= " and  c.article_id in ('{$data['article_id']}') "; 
		}
		
		//�ж�����״̬
        if(IsExiest($data['status'])!=""){
			$_sql .= " and  c.status = '{$data['status']}' "; 
		}
		//�ж�������
        if(IsExiest($data['user_id'])!=""){
			$_sql .= " and  c.user_id = '{$data['user_id']}' "; 
		}
		
		if(IsExiest($data['reply_userid'])!=""){
			$_sql .= " and  c.user_id = '{$data['user_id']}' "; 
		}
		
		$_select = "c.*, u.username";
		 $sql = "select SELECT from {comment} as c
                    left join {users} as u on c.user_id = u.user_id {$_sql} ";
		$__sql = "";
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
		
		//�������յĽ��
		$result = array('list' => $list?$list:array(),'total' => $total,'page' => $data['page'],'epage' => $data['epage'],'total_page' => $total_page);
		
		return $result;
    }	

}
?>