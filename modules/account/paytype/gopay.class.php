<?php
require_once (ROOT_PATH."modules/account/payclasses/gopay/HttpClient.class.php");
class gopayPayment  {

    var $name = '������';//֧�������ر��Ƽ�����
    var $logo = 'GOPAY';
    var $version = 2.1;
    var $description = "��������";
    var $type = 1;//1->ֻ��������2->�������
    var $charset = 'GB2312';
	
    var $orderby = 3;
 
    public static function ToSubmit($data){
    	$submitUrl = 'https://www.gopay.com.cn/PGServer/Trans/WebClientAction.do?'; //    
		$backgroundMerUrl = "http://www.rongerong.com/modules/account/return.php"; 
		$frontMerUrl = "";  
		$gopayServerTime= trim(HttpClient::getGopayServerTime());
		$ServerTime=str_ireplace(" ","",$gopayServerTime);
		$tranCode = 8888;
		$merchantID = $data["merchantID"];
		$VerficationCode = $data["VerficationCode"];
		$merOrderNum = $data["trade_no"];
		$tranAmt = $data["money"];
		$feeAmt = 0;
		$currencyType = 156;
		$merURL = $data["return_url"];
		$tranDateTime =  trim(date("YmdHis",time()));
		$virCardNoIn = $data["virCardNoIn"];
		$tranIP = ip_address();
		$msgExt = '';
		$bankCode = $data["bankCode"];
		$userType = 1;
		$url = $submitUrl;
		$url .= "version=2.1&";//���״���
		$url .= "tranCode={$tranCode}&";//���״���
		$url .= "language=1&";//���״���
		$url .= "signType=1&";//���״���
		$url .= "merchantID={$merchantID}&";//�̻�ID
		$url .= "virCardNoIn={$virCardNoIn}&";//������ת���˺�
		$url .= "merOrderNum={$merOrderNum}&";//������
		$url .= "tranAmt={$tranAmt}&";//���׽��
		$url .= "feeAmt={$feeAmt}&";//������
		$url .= "currencyType={$currencyType}&";//���֣�156 �����
		$url .= "frontMerUrl={$frontMerUrl}&";//�̻�url
		$url .= "backgroundMerUrl={$backgroundMerUrl}&";//�̻�url
		$url .= "gopayServerTime={$gopayServerTime}&";//����ʱ��
		$url .= "tranDateTime={$tranDateTime}&";//����ʱ��
		$url .= "tranIP={$tranIP}&";//ip
		$url .= "bankCode={$bankCode}&";//�̻�url
		$url .= "userType={$userType}&";//�̻�url
$signStr="version=[2.1]tranCode=[$tranCode]merchantID=[$merchantID]merOrderNum=[$merOrderNum]tranAmt=[$tranAmt]feeAmt=[$feeAmt]tranDateTime=[{$tranDateTime}]frontMerUrl=[$frontMerUrl]backgroundMerUrl=[$backgroundMerUrl]orderId=[]gopayOutOrderId=[]tranIP=[$tranIP]respCode=[]gopayServerTime=[{$ServerTime}]VerficationCode=[{$VerficationCode}]";
		$signValue = md5($signStr);
		$url .= "signValue=$signValue";//�̻�url
		return array("url"=>$url,"sign"=>$signStr);
    }

   function GetFields(){
        return array(
                'merchantID'=>array(
                        'label'=>'�̻�ID',
                        'type'=>'string'
                ),
                'virCardNoIn'=>array(
                        'label'=>'�������ʺ�',
                        'type'=>'string'
                ),
                'VerficationCode'=>array(
                        'label'=>'ʶ����',
                        'type'=>'string'
                )
            );
    }
}
?>
