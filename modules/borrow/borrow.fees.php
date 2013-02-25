<?
/******************************
 * $File: borrow.fee.php
 * $Description: p2p�ķ���
 * $Author: Deayou 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

class borrowFeeClass {
     
	 function GetType($data){
	 	global $mysql;
        if ($data['account']=="") return "";//��������
        if ($data['period']=="") return "";//������ޣ�����������Ϊ��������������Ϊ�·�
        if ($data['style']=="") return "";//�������
        if ($data['type']=="") return "";//�������
		$type = $data['type'];
		if($type=="0"){
			$result = self::CreditBorrowSuccess($data);
		}else if($type=="1"){
			$result = self::DiyaBorrowSuccess($data);
		}else if($type=="2"){
			$result = self::JingBorrowSuccess($data);
		}else if($type=="3"){
			$result = self::miaoBorrowSuccess($data);
		}
		return $result;
	 }
	 
     /* [���ñ�]
     ���ɹ����� 
     ���1���¹����Ϊ����1%�����2�����ڹ����Ϊ����2%��
     ÿ����һ���¹����������0.2%��������3���¹������Ϊ�����2.2%���Դ����ơ�
     ��������û����ɹ����ڽ赽�����ڿ۳���
     
     �������
     ���1������Ϊ����0.4%�����1������ÿ����һ����������0.04%��
     ������2������Ϊ0.44%���Դ����ơ���������û����ɹ����ڽ赽�����ڿ۳���
     */
     function CreditBorrowSuccess($data){
        global $mysql;
        if ($data['account']=="") return "";//��������
        if ($data['period']=="") return "";//������ޣ�����������Ϊ��������������Ϊ�·�
        if ($data['style']=="") return "";//�������
        //0����ʾ�ȶϢ����
        if ($data['style']=="0"){
            if ($data['period']>2){
                $_account = $data['account']*(0.02 + ($data['period']-2)*0.002);
            }else{
                $_account = $data['account']*$data['period']*0.01;
            }
         }
         //4����ʾ�������
         else if ($data['style']=="4"){
              $_account = $data['account']*(0.004 + ($data['period']-1)*0.0004);
            
         }
		 
		 //����
		else{
		  if ($data['period']>2){
                $_account = $data['account']*(0.02 + ($data['period']-2)*0.002);
            }else{
                $_account = $data['account']*$data['period']*0.01;
            }
		}
        return round($_account,2);
     }
     
     /* [��Ѻ��] 
    �ȶϢ���»���
    ���1���¹����Ϊ����1%�����2�����ڹ����Ϊ����2%��ÿ����һ���¹����������0.2%��
    ������3���¹������Ϊ�����2.2%��
    �Դ����ơ���������û����ɹ����ڽ赽�����ڿ۳���
    
    �¸�Ϣ����һ�λ���
    ���1���¹����Ϊ����1%�����2�����ڹ����Ϊ����2.3%��ÿ����һ���¹����������0.3%��
    ������3���¹������Ϊ�����2.6%��
    �Դ����ơ���������û����ɹ����ڽ赽�����ڿ۳���
     */
     function DiyaBorrowSuccess($data){
        global $mysql;
        if ($data['account']=="") return "";//�����
        if ($data['period']=="") return "";//�������
        if ($data['style']=="") return "";//�������
		
        //0����ʾ�ȶϢ����
        if ($data['style']=="0"){
            if ($data['period']>2){
                $_account = $data['account']*(0.02 + ($data['period']-2)*0.002);
            }else{
                $_account = $data['account']*$data['period']*0.01;
            }
        }
        //3�¸�Ϣ����һ�λ���
        else if($data['style']==3){
            if ($data['period']==1){
                $_account = $data['account']*$data['period']*0.01;
            }if ($data['period']==2){
                $_account = $data['account']*$data['period']*0.23;
            }else{
                $_account = $data['account']*(0.02 + ($data['period']-1)*0.003);
            }   
        }
        //4����ʾ���
        else if ($data['style']=="4"){
           $_account = $data['account']*(0.004 + ($data['period']-1)*0.0004);
        }
		
		//����
		else{
			$_account = 0;
		}
		
        return round($_account,2);
     }
     
     
      /* [���] 
     Ϊ����0.1%��
     */
     function MiaoBorrowSuccess($data){
        global $mysql;
        if ($data['account']=="") return "";//�����
        return round($data['account']*0.001,2);
     }
     
      
    /* [��ֵ��] 
    �ȶϢ���»��
    ���1���¹����Ϊ����0.5%�����2�����ڹ����Ϊ����1%��
    ÿ����һ���¹����������0.5%��
    ������3���¹������Ϊ�����1.5%��
    �Դ����ơ���������û����ɹ����ڽ赽�����ڿ۳���
    
    �¸�Ϣ����һ�λ���
    ���1���¹����Ϊ����0.5%�����2�����ڹ����Ϊ����1%��ÿ����һ���¹����������0.5%��
    ������3���¹������Ϊ�����1.5%���Դ����ơ���������û����ɹ����ڽ赽�����ڿ۳���
    
    ��ֵ��꣺
    ���1������Ϊ����0.2%�����1������ÿ����һ����������0.02%��
    ������2������Ϊ0.22%���Դ����ơ�
    ��������û����ɹ����ڽ赽�����ڿ۳���
    
     */
     function JingBorrowSuccess($data){
        global $mysql;
        if ($data['account']=="") return "";//��������
        if ($data['period']=="") return "";//������ޣ�����������Ϊ��������������Ϊ�·�
        if ($data['style']=="") return "";//�������
         //0����ʾ�ȶϢ����
        if ($data['style']=="0"){
            
                $_account = $data['account']*$data['period']*0.005;
            
        }
        //3�¸�Ϣ����һ�λ���
        else if($data['style']==3){
            
                $_account = $data['account']*$data['period']*0.005;
           
            
        }
        //4����ʾ���
        else if ($data['style']=="4"){
           $_account = $data['account']*0.001;
        }
		
		//����
		else{
			$_account = 0;
		}
        return round($_account,2);
     }
     
}
?>