<?php
/******************************
 * $File: borrow.amount.inc.php
 * $Description: �û�������û����Ĵ����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

require_once("borrow.amount.php");//����

//�µĽ��
if ($_REQUEST['p']==""){
    
    if (isset($_POST['name']) && $_POST["name"]!=""){
        $var = array("borrow_use","account","amount_style","amount_type","amount_account","remark","content");
		$data = post_var($var);
        $data['oprate'] = "add";
        $data['type'] = "apply";
		$data['user_id'] = $_G['user_id'];
	
		$result = borrowAmountClass::AddAmountApply($data);
		if ($result>0){	
				if($_POST['borrowtype']!=''){
					$msg = array("���Ķ�������Ѿ��ύ����ȴ���ˡ�","","/?user&q=code/borrow/jrsh&type=amount");
				}else{
					$msg = array("���Ķ�������Ѿ��ύ����ȴ���ˡ�","","/index.php?user&q=code/borrow/amount");
				}
		}else{
			$msg = array($MsgInfo[$result]);
		}
    }else{
        
    }
  
}else if ($_REQUEST['p']=="log"){
    
    
}else{
    $template = "error.html";//��ʼ�������
}	
?>