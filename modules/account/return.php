<?
/******************************
 * $File: return.php
 * $Description: �ʽ����ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
require_once ('../../core/config.inc.php');
error_reporting(E_ALL);
require_once ('account.class.php');
require_once ("account.payment.php");


//������
if (isset($_POST['respCode']) && $_POST['respCode']!=""){
	$result = accountClass::GetRecharge(array("nid"=>$_POST['merOrderNum']));
	if ($result==false){
		$msg = "֧��ʧ��";
	}elseif ($_POST['respCode']=="0000"){
		accountClass::OnlineReturn(array("trade_no"=>$_POST['merOrderNum']));
		$msg = "֧���ɹ�";
	} else {
		$msg = "֧��ʧ��";
	}
	echo "RespCode=0000|JumpURL=http://www.rongerong.com/?user&q=code/account/recharge";

}elseif (isset($_REQUEST['ipsbillno']) && $_REQUEST['ipsbillno']!=""){
	$billno = $_GET['billno'];
	$amount = $_GET['amount'];
	$mydate = $_GET['date'];
	$succ = $_GET['succ'];
	$msg = $_GET['msg'];
	$attach = $_GET['attach'];
	$ipsbillno = $_GET['ipsbillno'];
	$retEncodeType = $_GET['retencodetype'];
	$currency_type = $_GET['Currency_type'];
	$signature = $_GET['signature'];
	$content = $billno . $amount . $mydate . $succ . $ipsbillno . $currency_type;
	$result = accountpaymentClass::GetOne(array("nid"=>"ips"));
	$cert = $result['fields']['PrivateKey']['value'];
	$signature_1ocal = md5($content . $cert);
	
	if ($signature_1ocal == $signature){
	
		if ($succ == 'Y'){
			accountClass::OnlineReturn(array("trade_no"=>$billno));
			
			$msg = '���׳ɹ�';
		}else{
			$msg = '����ʧ�ܣ�';
		}
	}else{
		$msg = 'ǩ������ȷ��';
	}
	echo "<script>alert('{$msg}');location.href='/index.php?user&q=code/account/recharge';</script>";
}

?>