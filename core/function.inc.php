<?php
/******************************
 * $File: function.inc.php
 * $Description: ���������ļ�
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

/**
 * 1,��ȡIP��ַ
 */
function ip_address() {
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
			foreach ($matches[0] AS $xip) {
				if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
					$ip = $xip;
					break;
				}
			}
		}
		return $ip;
}

/**
 * 2,�жϺ����Ƿ���ڣ��������򷵻�false
 */
function IsExiest($val){
	if (isset($val) && ($val!="" || $val==0)){
		return $val;
	}else{
		return false;
	}
}


/**
 * 3,���ܳ���
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	// ��̬�ܳ׳��ȣ���ͬ�����Ļ����ɲ�ͬ���ľ���������̬�ܳ�
	$ckey_length = 4;
	// �ܳ�
	$key = md5($key ? $key : "dw10c20m05w18");
	// �ܳ�a�����ӽ���
	$keya = md5(substr($key, 0, 16));
	// �ܳ�b��������������������֤
	$keyb = md5(substr($key, 16, 16));
	// �ܳ�c���ڱ仯���ɵ�����
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	// ����������ܳ�
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	// ���ģ�ǰ10λ��������ʱ���������ʱ��֤������Ч�ԣ�10��26λ��������$keyb(�ܳ�b)������ʱ��ͨ������ܳ���֤����������
	// ����ǽ���Ļ�����ӵ�$ckey_lengthλ��ʼ����Ϊ����ǰ$ckey_lengthλ���� ��̬�ܳף��Ա�֤������ȷ
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	
	// �����ܳײ�
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	
	// �ù̶����㷨�������ܳײ�����������ԣ�����ܸ��ӣ�ʵ���϶Բ������������ĵ�ǿ��
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	
	// ���ļӽ��ܲ���
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		// ���ܳײ��ó��ܳ׽��������ת���ַ�
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	
	
	if($operation == 'DECODE') {
		// substr($result, 0, 10) == 0 ��֤������Ч��
		// substr($result, 0, 10) - time() > 0 ��֤������Ч��
		// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) ��֤����������
		// ��֤������Ч�ԣ��뿴δ�������ĵĸ�ʽ
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		// �Ѷ�̬�ܳױ������������Ҳ��Ϊʲôͬ�������ģ�������ͬ���ĺ��ܽ��ܵ�ԭ��
		// ��Ϊ���ܺ�����Ŀ�����һЩ�����ַ������ƹ��̿��ܻᶪʧ��������base64����
		return $keyc.str_replace('=', '', base64_encode($result));
	}
} 


/**
 * 4,����cookie
 * @param $param array('user_id' => '�û�id','cookie_id' => '����ID','time' => 'ʱ��','cookie_status' => '�Ƿ���cookie')
 * @return Null
 */
function SetCookies($data = array()){
	global $_G;
	//sessionID
	$_session_id = !IsExiest($data['cookie_id'])?md5("deayou_userid"):md5($data['cookie_id']);
	if (IsExiest($data['cookie_status'])!=false && $data['cookie_status'] == 1){
	    if ($data["time"]!=""){
	       setcookie("dy_cookie_time",$data["time"],time()+$data["time"],"/",$_SERVER["HTTP_HOST"],false,true);
           $_ctime = time()+$data["time"];
	    }else{
	       $_ctime = time()+$_COOKIE["dy_cookie_time"];
	       setcookie("dy_cookie_time", $_COOKIE["dy_cookie_time"],$_ctime,"/",$_SERVER["HTTP_HOST"],false,true);
	    }
		setcookie($_session_id,authcode($data['user_id'].",".time(),"ENCODE"),$_ctime,"/",$_SERVER["HTTP_HOST"],false,true);
	}else{
	    if ($data["time"]!=""){
	       $_SESSION["dy_cookie_time"] = $data["time"];
	    }else{
	        $data["time"] = $_SESSION["dy_cookie_time"]  ;
            $_SESSION["dy_cookie_time"] =$data["time"];
	    }
		$_SESSION[$_session_id] = authcode($data['user_id'].",".time(),"ENCODE");
		$_SESSION['login_endtime'] = time()+ $data["time"];
	}
}

/**
 * 5,��ȡcookie
 * @param $param array('session_id' => '����ID','time' => 'ʱ��','cookie_status' => '�Ƿ���cookie')
 * @return Null
 */
function GetCookies($data = array()){
	
	//sessionID
	$_session_id = !IsExiest($data['cookie_id'])?md5("deayou_userid"):md5($data['cookie_id']);
   
	$_time = !IsExiest($data['time'])?60*60:$data['time'];
	$_user_id = array(0);
	if (IsExiest($data['cookie_status'])!=false && $data['cookie_status'] == 1){
		$_user_id = explode(",",authcode(isset($_COOKIE[$_session_id])?$_COOKIE[$_session_id]:"","DECODE"));
		
	}else{
		$_user_id = explode(",",authcode(isset($_SESSION[$_session_id])?$_SESSION[$_session_id]:"","DECODE"));
	}
	
	return $_user_id[0]; 
}

/**
 * 6,���cookie
 * @param $param array('session_id' => '����ID')
 * @return Null
 */
function DelCookies($data = array()){
    global $_G;
	//sessionID
	$_session_id = !IsExiest($data['cookie_id'])?md5("deayou_userid"):md5($data['cookie_id']);
	setcookie($_session_id,"",time()-3600);
	$_SESSION[$_session_id] = "";
	$_SESSION['login_endtime'] = "";
	
}

/**
 * 7,ɾ��ָ��Ŀ¼���ļ��У��е������ļ�,���������ļ���
 * @param $param array('session_id' => '����ID')
 * @return Null
 */
function DelFile($dir) { 
	if (is_dir($dir)) { 
		$dh=opendir($dir);//��Ŀ¼ //�г�Ŀ¼�е������ļ���ȥ�� . �� .. 
		while (false !== ( $file = readdir ($dh))) { 
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file; 
				if(!is_dir($fullpath)) { 
					unlink($fullpath);
				} else { 
					del_file($fullpath); 
				} 
			}
		}
		closedir($dh); 
	} 
} 

/**
 * 8,��ȡ����ݷ�ʽ
 * @param $param 
 * @return Null
 */
function post_var($var,$type=""){
	
	if (is_array($var)){
		foreach ($var as $key =>$val){
			$_val = (isset($_POST[$val]) && $_POST[$val]!="")?$_POST[$val]:"";
			if ($_val==""){
				$_val=NULL;
			}elseif (is_array($_val) ){
			     $_val = join(",",$_val);
			}
			$result[$val] = $_val;
			
			if($val=="area"){//����
				$result[$val] = post_area();
			}elseif($val=="flag"){//����
				$result[$val] = !isset($_POST["flag"])?NULL:join(",",$_POST["flag"]);
			}elseif ($val=="clearlitpic"){
				if ($result["clearlitpic"]!="" && $result["clearlitpic"]==1){
					$result['litpic'] = NULL;
				}
				unset($result["clearlitpic"]);
			}elseif($val=="updatetime"){//����
				$result[$val] = time();
			}elseif($val=="updateip"){//����
				$result[$val] = ip_address();
			}elseif($val=="contents"){
				$result[$val] = htmlspecialchars($result[$val]);
			}
		}
		
		return $result;
	}else{
		return (!isset($_POST[$var]) || $_POST[$var]=="")?NULL:$_POST[$var];
	}
}


/**
 * 8,��ȡ����ݷ�ʽ
 * @param $param 
 * @return Null
 */
function post_var_utf($var,$type=""){
	
	if (is_array($var)){
		foreach ($var as $key =>$val){
			$_val = (isset($_POST[$val]) && $_POST[$val]!="")?$_POST[$val]:"";
			if ($_val==""){
				$_val=NULL;
			}elseif (!is_array($_val) ){
				if ($val!="content"){
					$_val = iconv('UTF-8', 'GB2312', nl2br($_val));
				}
			}else{
				$_val = join(",",$_val);
			}
			$result[$val] = $_val;
			
			if($val=="area"){//����
				$result[$val] = post_area();
			}elseif($val=="flag"){//����
				$result[$val] = !isset($_POST["flag"])?NULL:join(",",$_POST["flag"]);
			}elseif ($val=="clearlitpic"){
				if ($result["clearlitpic"]!="" && $result["clearlitpic"]==1){
					$result['litpic'] = NULL;
				}
				unset($result["clearlitpic"]);
			}elseif($val=="updatetime"){//����
				$result[$val] = time();
			}elseif($val=="updateip"){//����
				$result[$val] = ip_address();
			}elseif($_val == "content"){
				$result[$val] = htmlspecialchars($result[$val]);
			}
		}
		
		return $result;
	}else{
		return (!isset($_POST[$var]) || $_POST[$var]=="")?NULL:$_POST[$var];
	}
}



/**
 * 8,��ȡ����ݷ�ʽ
 * @param $param 
 * @return Null
 */
function post_var_gbk($var,$type=""){
	
	if (is_array($var)){
		foreach ($var as $key =>$val){
			$_val = (isset($_POST[$val]) && $_POST[$val]!="")?$_POST[$val]:"";
			if ($_val==""){
				$_val=NULL;
			}elseif (!is_array($_val) ){
				if ($val!="content"){
					$_val = iconv( 'GB2312','UTF-8', nl2br($_val));
				}
			}else{
				$_val = join(",",$_val);
			}
			$result[$val] = $_val;
			
			if($val=="area"){//����
				$result[$val] = post_area();
			}elseif($val=="flag"){//����
				$result[$val] = !isset($_POST["flag"])?NULL:join(",",$_POST["flag"]);
			}elseif ($val=="clearlitpic"){
				if ($result["clearlitpic"]!="" && $result["clearlitpic"]==1){
					$result['litpic'] = NULL;
				}
				unset($result["clearlitpic"]);
			}elseif($val=="updatetime"){//����
				$result[$val] = time();
			}elseif($val=="updateip"){//����
				$result[$val] = ip_address();
			}elseif($_val == "content"){
				$result[$val] = htmlspecialchars($result[$val]);
			}
		}
		
		return $result;
	}else{
		return (!isset($_POST[$var]) || $_POST[$var]=="")?NULL:$_POST[$var];
	}
}
function post_area($nid = ""){
	$pname = $nid."procvince";
	$cname = $nid."city";
	$aname = $nid."area";
	
	if (isset($_POST[$aname]) && $_POST[$aname]!=""){
		if ($_POST[$cname]==""){
			$area = $_POST[$pname];
		}else{
			$area = $_POST[$aname];
		}
	}else{
		if (isset($_POST[$cname]) && $_POST[$cname]!=""){
			$area = $_POST[$cname];
		}else{
			$area = isset($_POST[$pname])?$_POST[$pname]:"";
		}
	}
	return  $area;
}

/**
 * 9,��ȡĿ¼������
 * @param $param array('dir' => '��ַ')
 * @return Null
 */
function get_file($dir,$type='dir'){
	$result = "";
	if (is_dir($dir)) {
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				$_file = $dir."/".$file;
				if ($file !="." && $file != ".." && filetype($_file)==$type ){
					$result[] = $file;
				}
			}
			closedir($dh);
		}
	}
	return $result;
}
function read_file($filename) {
	if ( file_exists($filename) && is_readable($filename) && ($fd = @fopen($filename, 'rb')) ) {
		$contents = '';
		while (!feof($fd)) {
			$contents .= fread($fd, 8192);
		}
		fclose($fd);
		return $contents;
	} else {
		return false;
	}
}

function create_dir($dir,$dir_perms=0775){
	/* ѭ������Ŀ¼ */
	if (DIRECTORY_SEPARATOR!='/') {
		$dir = str_replace('\\','/', $dir);
	}
	
	if (is_dir($dir)){
		return true;
	}
	
	if (@ mkdir($dir, $dir_perms)){
		return true;
	}

	if (!create_dir(dirname($dir))){
		return false;
	}
	
	return mkdir($dir, $dir_perms);
	
}
function create_file($dir,$contents=""){
	$dirs = explode('/',$dir);
	if($dirs[0]==""){
		$dir = substr($dir,1);
	}
	create_dir(dirname($dir));
	@chmod($dir, 0777);
	if (!($fd = @fopen($dir, 'wb'))) {
		$_tmp_file = $dir . DIRECTORY_SEPARATOR . uniqid('wrt');
		if (!($fd = @fopen($_tmp_file, 'wb'))) {
			trigger_error("ϵͳ�޷�д���ļ�'$_tmp_file'");
			return false;
		}
	}
	fwrite($fd, $contents);
	fclose($fd);
	@chmod($dir, 0777);
	return true;
}
   

/**
 * 10,�����֤��
 * @param $param array('dir' => '��ַ')
 * @return Null
 */
 function check_valicode($type=""){
 	$msg = "";
 	if($_SESSION['valicode']!=$_POST['valicode']){
		$msg = array("��֤�벻��ȷ");
	}else{
		if ($type==""){
			$_SESSION['valicode'] = "";
		}
	}
	$_SESSION['valicode']="";
    unset($_SESSION['valicode']);
 	return $msg;
 }
 /**
 * 11,XMLת����
 * @param $param array('dir' => '��ַ')
 * @return Null
 */
 function struct_to_array($item) {                        
  if(!is_string($item)) {                                
    $item = (array)$item;                                
    foreach ($item as $key=>$val){                     
      $item[$key]  =  struct_to_array($val);             
    }                                                    
  }                                                      
  return $item;                                          
}  
function xml_to_array( $xml ) 
{ 
$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/"; 
if(preg_match_all($reg, $xml, $matches)) 
{ 
$count = count($matches[0]); 
for($i = 0; $i < $count; $i++) 
{ 
$subxml= $matches[2][$i]; 
$key = $matches[1][$i]; 
if(preg_match( $reg, $subxml )) 
{ 
$arr[$key] = xml_to_array( $subxml ); 
}else{ 
$arr[$key] = $subxml; 
} 
} 
} 
return $arr; 
} 
// Xml ת ����, ���������� 
function xmltoarray( $xml ) 
{ 
$arr = xml_to_array($xml); 
$key = array_keys($arr); 
return $arr[$key[0]]; 
} 

/**
 * ��URL��ʽ���ַ���ת��ΪID
 *
 * @param String $str
 * @return Array(goods_type, goods_id)
 */
function Url2Key($key,$type) {
	$key = base64_decode ( urldecode ( $key ) );
	return explode ($type, $key );
}


/**
 * 2,�жϺ����Ƿ���ڣ��������򷵻�false
 */
function check_rank($purview){
	global $_G,$_A;
	$admin_purview = explode(",",$_A['admin_result']['purview']);
	if (in_array("other_all",$admin_purview) || $_A['admin_result']['type_id']==1){
		return true;
	}else if (!in_array($purview,$admin_purview)){
		
		echo "<script>alert('��û��Ȩ��');history.go(-1);</script>";exit;
	}
}


//ȥ����Ӧ�Ĳ���
function url_format($url, $format = ''){
	if ($url=="") return "?";
	$_url =  explode("?",$url);
	$_url_for = "";
	if (isset($_url[1]) && $_url[1]!=""){
		$request = $_url[1];
		if ($request != ""){
			$_request = explode("&",$request);
			foreach ($_request as $key => $value){
				$_value = explode("=",$value);
				if (trim($_value[0])!=$format){
					$_url_for ="&" .$value;
				}
			}
		}
		$_url_for = substr($_url_for, 1,strlen($_url_for)); 
	}
	return "?".$_url_for;
}


//��ȡʱ��
function get_mktime($mktime){
	if ($mktime=="") return "";
	$dtime = trim(ereg_replace("[ ]{1,}"," ",$mktime));
	$ds = explode(" ",$dtime);
	$ymd = explode("-",$ds[0]);
	if (isset($ds[1]) && $ds[1]!=""){
		$hms = explode(":",$ds[1]);
		$mt = mktime(empty($hms[0])?0:$hms[0],!isset($hms[1])?0:$hms[1],!isset($hms[2])?0:$hms[2],!isset($ymd[1])?0:$ymd[1],!isset($ymd[2])?0:$ymd[2],!isset($ymd[0])?0:$ymd[0]);
	}else{
		$mt = mktime(0,0,0,!isset($ymd[1])?0:$ymd[1],!isset($ymd[2])?0:$ymd[2],!isset($ymd[0])?0:$ymd[0]);
	}
	return $mt;
}

/**
 * ��IDת��ΪURL��ʽ
 *
 * @param Integer $goods_id
 * @param String(eg:goods_vps/goods_hire) $goods_type
 * @return String
 */
function Key2Url($key,$type) {
	return  base64_encode ($type .$key ) ;
}


function post_maketime($name){
	
	$var = array("year","month","date","hour","min");
	foreach ($var as $val){
		$$val = !isset($_POST[$name."_".$val])?"0":$_POST[$name."_".$val];
	}
	return mktime($hour,$min,0,$month,$date,$year);

}


//����excel��ʽ��
function exportData($filename,$title,$data){
	require(ROOT_PATH.'libs/PHPExcel/PHPExcel.php');
	$cache = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
	PHPExcel_Settings::setCacheStorageMethod($cache);
	$objExcel = new PHPExcel();
	$objExcel->setActiveSheetIndex(0); 
	$objActSheet = $objExcel->getActiveSheet();
	$objActSheet->setTitle(iconv('GBK', 'UTF-8',$filename));
	$objActSheet->setCellValue('A1', iconv('GBK', 'UTF-8','������������'.$filename));
	$title_array = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	//�ϲ���Ԫ��
	$objActSheet->mergeCells('A1:'.$title_array[count($title)-1].'1');    
	//������ʽ   
	$objStyleA1 = $objActSheet->getStyle('A1');       
	$objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);   
	$objFontA1 = $objStyleA1->getFont();       
	$objFontA1->setName('����');       
	$objFontA1->setSize(18);     
	$objFontA1->setBold(true);
	// ���ñ�ͷ
	foreach($title as $k=>$v){
		$objActSheet->setCellValue($title_array[$k].'2', iconv('GBK','UTF-8',$v));
	}
	foreach($data as $key=>$value){
		$k=$key+3;
		foreach($value as $a=>$b){
			$objActSheet->setCellValueExplicit($title_array[$a].$k, iconv('GBK','UTF-8',$b),PHPExcel_Cell_DataType::TYPE_STRING); 
		}
	}
	if(preg_match('/MSIE/',$ua)) {  
		$outputFileName = str_replace('+','%20',urlencode('������������'.$filename));
	}else{
		$outputFileName = '������������'.$filename;
	}
	header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$outputFileName.'.xls"');
    header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
	$objWriter->setPreCalculateFormulas(false);
	$objWriter->save('php://output');
}


//����excel��ʽ��
function exportData_bak($filename,$title,$data){
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename="  . $filename . ".xls");
	if (is_array($title)){
		foreach ($title as $key => $value){
			echo (string)$value."\t";
		}
	}
	echo "\n";
	if (is_array($data)){
		foreach ($data as $key => $value){
			foreach ($value as $_key => $_value){
				echo (string)$_value."\t";
			}
			echo "\n";
		}
	}
}
/** 
* PHP��ȡ�ַ�����Ӣ�Ļ�ϳ���  
* @param $str string �ַ��� 
* @param $$charset string ���� 
* @return ���س��ȣ�1����=1λ��2Ӣ��=1λ 
*/  
function strLength($str,$charset='gb2312'){  
if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);  
$num = strlen($str);  
$cnNum = 0;  
for($i=0;$i<$num;$i++){  
if(ord(substr($str,$i+1,1))>127){  
$cnNum++;  
$i++;  
}  
}  
$enNum = $num-($cnNum*2);  
$number = ($enNum/2)+$cnNum;  
return ceil($number);  
}  


function modifier($fun,$value,$arr=""){
	global $_G;
	require_once(ROOT_PATH."plugins/magic/modifier.".$fun.".php");
	$_fun = "magic_modifier_".$fun;
	return $_fun($value,$arr,array("_G"=>$_G));
}

function num_big($num)
{
$d = array('��','Ҽ','��','��','��','��','½','��','��','��');
$e = array('Ԫ','ʰ','��','Ǫ','��','ʰ��','����','Ǫ��','��','ʰ��','����','Ǫ��','����');
$p = array('��','��');
$zheng='��'; //׷��"��"��
$final = array(); //���
$inwan=0; //�Ƿ�����
$inyi=0; //�Ƿ�����
$len_pointdigit=0; //С����󳤶�
$y=0;
if($c = strpos($num, '.')) //��С����,$cΪС����ǰ�м�λ��
{ 
$len_pointdigit = strlen($num)-strpos($num, '.')-1; // �ж�С������м�λ��
if($c>13) //�򵥵Ĵ�����
{
echo "����̫��,�Ѿ���������.";
die();
}
elseif($len_pointdigit>2) //$len_pointdigitС������м�λ
{
echo "С�����ֻ֧��2λ.";
die();
}
}
else //��С����
{
$c = strlen($num);
$zheng = '��';
}
for($i=0;$i<$c;$i++) //������������
{
$bit_num = substr($num, $i, 1); //���ֶ�ȡ ��->��
if($bit_num!=0 || substr($num, $i+1, 1)!=0) //��ǰ���� ��һλ������Ļ� �Ͳ���ʾ
@$low2chinses = $low2chinses.$d[$bit_num];
if($bit_num || $i==$c-1) 
@$low2chinses = $low2chinses.$e[$c-$i-1];
}
for($j=$len_pointdigit; $j>=1; $j--) //����С������
{
$point_num = substr($num, strlen($num)-$j, 1); //���ֶ�ȡ ��->��
if($point_num != 0)
@$low2chinses = $low2chinses.$d[$point_num].$p[$j-1];
//if(substr($num, strlen($num)-2, 1)==0 && substr($num, strlen($num)-1, 1)==0) //С�������λ����0
}
$chinses = str_split($low2chinses,2); //�ַ���ת��������
//print_r($chinses);
for($x=sizeof($chinses)-1;$x>=0;$x--) //������Ч����Ϣ
{
if($inwan==0&&$chinses[$x]==$e[4]) //�����ظ���"��"
{
$final[$y++] = $chinses[$x];
$inwan=1;
}
if($inyi==0&&$chinses[$x]==$e[8]) //�����ظ���"��"
{
$final[$y++] = $chinses[$x];
$inyi=1;
$inwan=0;
}
if($chinses[$x]!=$e[4]&&$chinses[$x]!=$e[8]) //��������,������ֵ����$final����
$final[$y++] = $chinses[$x];
}
$newstring=(array_reverse($final)); //$finalΪ�����飬$newstringΪ��������ʹ�õ�����
$nstring=join($newstring); //�������ַ���
if(substr($num,-2,1)==0 && substr($num,-1)<>0) //�ж�ԭ����λΪ0 ? ��λ��Ϊ0 ?
{ 
$nstring=substr($nstring,0,(strlen($nstring)-4))."��".substr($nstring,-4,4); //������һ������
}
$fen="��";
$fj=substr_count($nstring, $fen); //���û�в鵽�������
return $nstring=($fj==0)?$nstring.$zheng:$nstring; //�ͽ�"��"�ӵ�����
}

function isIdCard($number) {
    //��Ȩ���� 
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //У���봮 
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    //��˳��ѭ������ǰ17λ 
    for ($i = 0;$i < 17;$i++) { 
        //��ȡǰ17λ������һλ��������������תΪʵ�� 
        $b = (int) $number{$i}; 
 
        //��ȡ��Ӧ�ļ�Ȩ���� 
        $w = $wi[$i]; 
 
        //�Ѵ����֤��������ȡ��һλ���ֺͼ�Ȩ������ˣ����ۼ� 
        $sigma += $b * $w; 
    }
    //������� 
    $snumber = $sigma % 11; 
 
    //������Ŵ�У���봮����ȡ��Ӧ���ַ��� 
    $check_number = $ai[$snumber];
 
    if ($number{17} == $check_number) {
        return true;
    } else {
        return false;
    }
}

function get_times($data=array()){
  
	if (isset($data['time']) && $data['time']!=""){
		$time = $data['time'];//ʱ��
	}elseif (isset($data['date']) && $data['date']!=""){
		$time = strtotime($data['date']);//����
	}else{
		$time = time();//����ʱ��
	}
	if (isset($data['type']) && $data['type']!=""){ 
		$type = $data['type'];//ʱ��ת�����ͣ���day week month year
	}else{
		$type = "month";
	}
	if (isset($data['num']) && $data['num']!=""){ 
		$num = $data['num'];
	}else{
		$num = 1;
	}
	if ($type=="month"){
		$month = date("m",$time);
		$year = date("Y",$time);
		$_result = strtotime("$num month",$time);
		$_month = (int)date("m",$_result);
		if ($month+$num>12){
			$_num = $month+$num-12;
			$year = $year+1;
		}else{
			$_num = $month+$num;
		}
		
		if ($_num!=$_month){
		
			//$_result = strtotime("-1 day",strtotime("{$year}-{$_month}-01"));
		}
	}else{
		$_result = strtotime("$num $type",$time);
	}
	if (isset($data['format']) && $data['format']!=""){ 
		return date($data['format'],$_result);
	}else{
		return $_result;
	}

}

function del_file($path){
    if (file_exists($path)){
        if(is_file($path)){
            if(    !@unlink($path)    ){
                $show.="$path,";
            }
        } else{
            $handle = opendir($path);
            while (($file = readdir($handle))!='') {
                if (($file!=".") && ($file!="..") && ($file!="")){
                    if (is_dir("$path/$file")){
                        $show.=del_file("$path/$file");
                    } else{
                        if( !@unlink("$path/$file") ){
                            $show.="$path/$file,";
                        }
                    }
                }
            }
            closedir($handle);

            if(!@rmdir($path)){
                $show.="$path,";
            }
        }
    }
    return $show;
}

//������վIP����
function ip_control_all($arr='') {
	if(!is_array($arr)) return false;
	$server_name = $_SERVER["SERVER_NAME"];
	$user_ip = ip_address(); 
	foreach ($arr as $k => $v) { 
		if($server_name == $k) {
			$ip_arr = explode(',', $v); 
			if(!in_array($user_ip, $ip_arr)) {
				return true;
			}else{
				return false;
			}
		}
	}
}


//������վ��̨���õ�IP������
function ip_control($allow_ip=""){
	if($allow_ip=="") return false;
	$allow_ip_arr = explode(',', $allow_ip);
	$user_ip = ip_address(); 
	if(!in_array($user_ip, $allow_ip_arr)) {
		return true;
	}else{
		return false;
	}
}

//������֤
function is_email($email) {
	$exp = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
	if(preg_match($exp,$email)) {
		return true;
	}
	return false;
}

/**
 * SQL ADDSLASHES
 * ���ø�PHP�汾
 */
function saddslshes($string) {
	if (get_magic_quotes_gpc()) {
		$lastname = stripslashes($string);
	}else{
		$lastname = $string;
	}
	$lastname = mysql_real_escape_string($lastname);
	return $lastname;
}

/**
 * ���ݿ����֮ǰͳһ����������ݽ���ת��
 */
function addslshes_array($string){
	if(is_array($string)) {
		foreach ($string as $key => $val) {
			$string[$key] = saddslshes($val);
		}
	}else{
		$string = saddslshes($string);
	}
	return $string;
}

/**
 * ��֤�ǲ����ֻ�����
 *
 * @param $v string ����
 *
 * @return blean
 */
function isMobile($v) {
    $v = trim($v);
    if(!isset($v{10})){
        return false;
    }
    $cm = [134,135,136,137,138,139,150,151,152,157,158,159,187,188,147,182,183];
    $cu = [130,131,132,155,156,185,186,145];
    $ct = [133,153,180,181,189];
    $h1 = substr($v, 0,3);
    $h2 = substr($v, 0,4);
    return preg_match('/^1\d{10}$/', $v)?(in_array($h1, $cu)?true:(in_array($h1, $ct)?true:('1349'==$h2?true:(in_array($h1, $cm)?true:false)))):false;
}

function checkrobot($useragent = '') {
	static $kw_spiders = array('bot', 'crawl', 'spider' ,'slurp', 'sohu-search', 'lycos', 'robozilla');
	static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');

	$useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
	if(strpos($useragent, 'http://') === false && dstrpos($useragent, $kw_browsers)) return false;
	if(dstrpos($useragent, $kw_spiders)) return true;
	return false;
}
function checkmobile() {
	global $_G;
	$mobile = array();
	static $mobilebrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
				'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
				'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
				'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
				'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
				'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
				'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
	static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
			'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
			'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');

	$pad_list = array('pad', 'gt-p1000');

	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if(dstrpos($useragent, $pad_list)) {
		return false;
	}
	if(($v = dstrpos($useragent, $mobilebrowser_list, true))){
		$_G['mobile'] = $v;
		return '2';
	}
	if(($v = dstrpos($useragent, $wmlbrowser_list))) {
		$_G['mobile'] = $v;
		return '3'; //wml��
	}
	$brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
	if(dstrpos($useragent, $brower)) return false;

	$_G['mobile'] = 'unknown';
	if(isset($_G['mobiletpl'][$_GET['mobile']])) {
		return true;
	} else {
		return false;
	}
}

function dstrpos($string, $arr, $returnvalue = false) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}

function borrow_agreement($nid,$tid){
	global $mysql;
	$sql='select id from {borrow_tender} where borrow_nid='.$nid.' order by addtime asc';
	$data = $mysql->db_fetch_arrays($sql);
	foreach ($data as $key=>$value){
		$data[$key]=$value['id'];
	}
	$keys=array_keys($data, $tid);
	$keys = $keys[0]+1;
	return $nid.str_repeat('0',3-strlen($keys)).$keys;
}