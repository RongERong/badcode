<?php
/******************************
 * $File:code.php
 * $Description: ģ������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

//��ǰģ�����Ϣ
$code = $_A['query_class'] ;
if ($code==""){
	$msg = array("��������");
}else{
	if (file_exists("modules/$code/$code.php")){
		$_A['module_other_status'] = adminClass::GetModuleStatus(array("nid"=>$code));
		require_once("modules/$code/$code.php");
		$_A['module_other_result'] = $_A['list_purview'][$code]["result"];
		
		$magic->assign("_A",$_A);
		$magic->assign("_G",$_G);
		$template_tpl= "{$code}.tpl";//����������ģ���ֱ�Ӷ�ȡģ�����ڵ���Ӧģ��
		$magic->assign("template_dir","modules/{$code}/");
		$magic->assign("module_tpl",$template_tpl);
		$magic->assign("MsgInfo",$MsgInfo);
		$template = "admin_code.html";
	}else{
		$msg = array("{$code}ģ�鲻����");
	}
}
			
?>