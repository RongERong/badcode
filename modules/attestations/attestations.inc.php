<?
/******************************
 * $File: attestations.inc.php
 * $Description: ֤�����Ϲ���
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

include_once("attestations.class.php");

if (isset($_POST['valicode']) && $_POST['valicode']!=$_SESSION['valicode']){
		$msg = array("��֤�����");
}else{
	$_SESSION['valicode'] = "";
	if ($_U['query_type'] == "list"){	
		
	}
	elseif($_U['query_type'] == "one"){
		if (isset($_POST['submit'])){
			$var = array("type_id");
			$data = post_var($var);
			
			$data['user_id'] = $_G['user_id'];
			$_G['upimg']['file'] = "pic";
			$_G['upimg']['code'] = "attestations";
			$_G['upimg']['type'] = "album";
			$_G['upimg']['user_id'] = $data["user_id"];
			$_G['upimg']['article_id'] = $data["attestations_id"];
			$data["pic_result"] = $upload->upfile($_G['upimg']);
			$result = attestationsClass::AddAttestations($data);
			if ($result>0){
				if ($_POST['type']!=''){
					header("Location: /?user&q=code/borrow/jrsh&type=list"); 
					//$msg = array("�����ɹ�","","/?user&q=code/borrow/jrsh&type=amount");
				}else{
					$msg = array("�����ɹ�","","/?user&q=code/borrow/loan&p=approve");
				}
			}else{
				if ($_POST['type']!=''){
					$msg = array($reuslt,"","/?user&q=code/borrow/jrsh&type=list");
				}else{
					$msg = array($reuslt,"","/?user&q=code/borrow/loan&p=approve");
				}				
			}
		}
	}
	elseif($_U['query_type'] == "more"){
		if (isset($_POST['remark'])){
			$var = array("remark","type_id");
			$data = post_var($var);
			$data['user_id'] = $_G['user_id'];
			$_G['upimg']['file'] = "pic";
			$_G['upimg']['code'] = "attestations";
			$_G['upimg']['type'] = "album";
			$_G['upimg']['user_id'] = $data["user_id"];
			$_G['upimg']['article_id'] = $data["attestations_id"];
			$data["pic_result"] = $upload->upfile($_G['upimg']);
			
			if ($pic_result!=""){
				foreach($pic_result as $key => $value){
					if($value!=""){
						$data['litpic'] = $value['filename'];
						$result = attestationsClass::AddAttestations($data);
					}
				}
			}
			
			if ($result!==true){
				$msg = array($reuslt);
			}else{
				$msg = array("�����ɹ�","","index.php?user&q=code/attestation");
			}
		}
		
	}
	elseif($_U['query_type'] == "view"){
		if (IsExiest($_REQUEST['user_id'])==""){
			echo "�벻Ҫ�Ҳ���";
			exit;
		}else{
			$str  = "<table ><tr><td>����</td><td width='20%'>�鿴</td></tr>";
			$data["user_id"] = $_REQUEST['user_id'];
			$data["limit"] = "all";
			$data["status"] = 1;
			$result = attestationClass::GetList($data);
			foreach ($result as $key => $value){
				$str .= "<tr height='30' style='border-bottom:1px solic #cccccc'>";
				$str .= "<td>{$value['type_name']}</td>";
				$str .= "<td ><a href='{$value['litpic']}' target='_blank'>�鿴</a></td>";
				
				$str .= "</tr>";
			}
			$str .= "</table>";
			echo $str;
			exit;
		
		}
	
	}
	elseif($_U['query_type'] == "study"){
		if (isset($_POST['submit'])){
			$data['user_id'] = $_G['user_id'];
			$data['code'] = "approve";
			$data['type'] = $_POST['type'];
			$data['nid'] = $_POST['nid'];
			$result = attestationsClass::AddAttestationsStudy($data);
			if ($_POST['type']=="borrow_study"){
				if ($result==true){
					echo "<script>location.href='/borrow_info/index.html'</script>";
				}else{
					$msg = array($reuslt,"","/borrow_study/index.html");
				}
			}else{
				if ($result==true){
					echo "<script>location.href='/tender_finsh/index.html'</script>";
				}else{
					$msg = array($reuslt,"","/tender_study/index.html");
				}
			}	
		}
	}
}



$template = "user_attestations.html";
?>
