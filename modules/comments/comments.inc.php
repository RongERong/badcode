<?php
/******************************
 * $File:comment.inc.php
 * $Description: ���۹����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once('comments.class.php');

if ($_U['query_type'] == "new"){	
	$msg = check_valicode();
	if ($msg==""){
		$data=array();
		$data['user_id'] = $_G['user_id'];//������
		$data['contents'] = $_POST['comment_content'];//��������
		$data['article_id'] = $_POST['comment_parent'];//���۵�����
		$data['site_id'] = $_POST['site_id'];//���۵�վ��id
		$data['reply_id'] = $_POST['reply_id'];//�ظ���id
		$data['pid'] = $_POST['pid'];//�ظ���ĸid
		$data['type'] = $_POST['type'];//�ظ���ĸid
		$result=commentsClass::AddComments($data);
		if($result>0){
			$msg = array("�������۳ɹ�");
		}else{
			$msg = array($MsgInfo[$result]);
		}
	}
}	
	
$template  = "user_comments.html";
?>