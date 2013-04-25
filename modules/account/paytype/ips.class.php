<?php

class ipsPayment {

	var $name = '��ѶIPS����֧��3.0';//��ѶIPS����֧��3.0
	var $description = "��ѶIPS����֧��3.0";
	var $type = 1;//1->ֻ��������2->�������
    var $logo = 'IPS3';
    var $version = 20070615;
    var $charset = 'gb2312';
	
	
    public static function ToSubmit($payment){
   		$submitUrl = 'http://pay.ips.com.cn/ipayment.aspx?';
   		//$submitUrl = 'http://pay.ips.net.cn/ipayment.aspx?';
		$Date = date("Ymd",time());
		$Currency_Type = "RMB";
		$Mer_key = $payment['PrivateKey'];
		$Amount = number_format($payment['money'], 2, '.', '');
        $Signvar='billno'.$payment['trade_no'].'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype5'.$Mer_key;
		$SignMD5 = md5($Signvar);
		$url = $submitUrl;
		$url .= "Mer_code={$payment['member_id']}&";//�û���
		$url .= "Billno={$payment['trade_no']}&";//˽Կ
		$url .= "Amount={$Amount}&";//���׽��
		$url .= "Date={$Date}&";//��������
		$url .= "Currency_Type={$Currency_Type}&";//����
		$url .= "Gateway_Type=01&";//��������
		$url .= "Lang=GB&";//����
		$url .= "Merchanturl={$payment['return_url']}&";//���ص�ַ
		$url .= "FailUrl=&";//ʧ�ܵ�ַ
		$url .= "ErrorUrl=&";//�����ַ
		$url .= "DispAmount={$Amount}&";//���
		$url .= "OrderEncodeType=5&";//���
		$url .= "RetEncodeType=17&";//
		$url .= "Rettype=1&";//
        $url .= 'ServerUrl='.$payment['notify_url'];
		$url .= "&SignMD5={$SignMD5}";//
        $url .= "&DoCredit=1";// ����ֱ��
        $url .= "&Bankco={$payment['bankCode']}";//���д���
        error_log('$url=>'.$url.' $signStr=>'.$Signvar.' $SignMD5=>'.$SignMD5."\n", 3, "/var/www/rongerong/data/log/ips/my-ips-errors-".date('Y-m-d').".log");
        return array("url"=>$url,"sign"=>$Signvar);
		
    }

    function callback($in,&$paymentId,&$money,&$message,&$tradeno){
        $merId = $this->getConf($in['out_trade_no'],'member_id'); //�ʺ�
        $pKey = $this->getConf($in['out_trade_no'],'PrivateKey');
        $key = $pKey==''?'afsvq2mqwc7j0i69uzvukqexrzd0jq6h':$pKey;//˽Կֵ
        ksort($in);
        //�������Ϸ���
        $temp = array();
        foreach($in as $k=>$v){
            if($k!='sign'&&$k!='sign_type'){
                $temp[] = $k.'='.$v;
            }
        }
        $testStr = implode('&',$temp).$key;
        if($in['sign']==md5($testStr)){
            $paymentId = $in['out_trade_no'];    //֧������
            $money = $in['total_fee'];
            $message = $in['body'];
            $tradeno = $in['trade_no'];
            switch($in['trade_status']){
                case 'TRADE_FINISHED':
                    if($in['is_success']=='T'){                        
                        return PAY_SUCCESS;
                    }else{                        
                        return PAY_FAILED;
                    }
                    break;
                case 'TRADE_SUCCESS':
                    if($in['is_success']=='T'){                        
                        return PAY_SUCCESS;
                    }else{                        
                        return PAY_FAILED;
                    }
                    break;
                case 'WAIT_SELLER_SEND_GOODS':
                    if($in['is_success']=='T'){                        
                        return PAY_PROGRESS;
                    }else{                        
                        return PAY_FAILED;
                    }
                    break;
                case 'TRADE_SUCCES':    //�߼��û�
                    if($in['is_success']=='T'){
                        return PAY_SUCCESS;
                    }else{
                        return PAY_FAILED;
                    }
                    break;
            }

        }else{
            $message = 'Invalid Sign';            
            return PAY_ERROR;
        }
    }

    function serverCallback($in,&$paymentId,&$money,&$message){
        exit('reserved');
    }

    function applyForm($agentfield){
      $tmp_form='<a href="javascript:void(0)" onclick="document.applyForm.submit();">��������֧����</a>';
      $tmp_form.="<form name='applyForm' method='".$agentfield['postmethod']."' action='http://top.shopex.cn/recordpayagent.php' target='_blank'>";
      foreach($agentfield as $key => $val){
            $tmp_form.="<input type='hidden' name='".$key."' value='".$val."'>";
      }
      $tmp_form.="</form>";
      return $tmp_form;
    }
 	function pay_IPS_relay($status){
        switch ($status){
            case PAY_FAILED:
                $aTemp = 'failed';
                break;
            case PAY_TIMEOUT:
                $aTemp = 'timeout';
                break;
            case PAY_SUCCESS:
                $aTemp = 'succ';
                break;
            case PAY_CANCEL:
                $aTemp = 'cancel';
                break;
            case PAY_ERROR:
                $aTemp = 'status';
                break;
            case PAY_PROGRESS:
                $aTemp = 'progress';
                break;
        }
    }
    function GetFields(){
         return array(
                'member_id'=>array(
                        'label'=>'�ͻ���',
                        'type'=>'string'
                    ),
                'PrivateKey'=>array(
                        'label'=>'˽Կ',
                        'type'=>'string'
                )
            );
    }
}