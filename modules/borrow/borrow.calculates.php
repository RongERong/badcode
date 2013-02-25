<?
/******************************
 * $File: borrow.calculate.php
 * $Description: �����Ϣ��������
 * $Author: Deayou 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/
if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
class borrowCalculateClass {
     
     /* ���ķ�ʽ����*/
     function GetType ($data = array()){	
    	//��ʽ,account,period,apr,time,style,type
    	//return account_all,account_interest��account_capital,repay_time
    	if (IsExiest($data["account"])=="") return "equal_account_empty";
    	if (IsExiest($data["period"])=="") return "equal_period_empty";
    	if (IsExiest($data["apr"])=="") return "equal_apr_empty";
    	$borrow_style =$data['style'];
		
    	if ($borrow_style=="month"){
    		return self::GetMonth($data);
    	}elseif ($borrow_style=="season"){
    		return self::GetSeason($data);
    	}elseif ($borrow_style=="end"){
    		return self::GetEnd($data);
    	}elseif ($borrow_style=="endmonth"){
    		return self::GetEndMonth($data);
    	}elseif ($borrow_style=="endday"){
    		return self::GetEndDay($data);
    	}
    }
    
    //�ȶϢ��
    //�����������ʡ���1+�����ʣ���������/[��1+�����ʣ���������-1] 
    //a*[i*(1+i)^n]/[(1+I)^n-1] 
    //��a��i��b������1��i��
     function GetMonth ($data = array()){
    	
    	$account = $data['account'];
    	$year_apr = $data['apr'];
    	$period = $data['period'];
        $time = $data['time'];
    	
    	$month_apr = $year_apr/(12*100);
    	$_li = pow((1+$month_apr),$period);
    	if ($account<0) return;
        
        if ($_li>1){
    	   $repay_account = round($account * ($month_apr * $_li)/($_li-1),2);//515.1
    	}else{
    	    $repay_account = $account;
    	}
    	$_result = array();
    	//$re_month = date("n",$borrow_time);
    	$_capital_all = 0;
    	$_interest_all = 0;
    	$_account_all = 0.00;
    	for($i=0;$i<$period;$i++){
    	    if ($_li<=1){
    	       $interest = 0;
    		}elseif ($i==0){
    			$interest = round($account*$month_apr,2);
    		}else{
    			$_lu = pow((1+$month_apr),$i);
    			$interest = round(($account*$month_apr - $repay_account)*$_lu + $repay_account,2);
    		}
    		
    		//echo $repay_account."<br>";
    		//��ֹһ��Ǯ������
    		if ($i==$period-1)
    		{
    			$capital = $account - $_capital_all;
    			$interest = $repay_account-$capital;
    		}else{
    			$capital =  $repay_account - $interest;
    		}
    		
    		//echo $capital."<br>";
    		$_account_all +=  $repay_account;
    		$_interest_all +=  $interest;
    		$_capital_all +=  $capital;
    		
    		$_result[$i]['account_all'] =  round($repay_account,2);
    		$_result[$i]['account_interest'] = round( $interest,2);
    		$_result[$i]['account_capital'] =  round($capital,2);
    		$_result[$i]['account_other'] =  round($repay_account*$period-$repay_account*($i+1),2);
    		$_result[$i]['repay_month'] =  round($repay_account,2);
    		$_result[$i]['repay_time'] = get_times(array("time"=>$time,"num"=>$i+1));
    	}
    	if ($data["type"]=="all"){
    		$_result_all['account_total'] =  round($_account_all,2);
    		$_result_all['interest_total'] =  round($_interest_all,2);
    		$_result_all['capital_total'] =  round($_capital_all,2);
    		$_result_all['repay_month'] =  round($repay_account,2);
    		$_result_all['month_apr'] = round($month_apr*100,2);
    		return $_result_all;
    	}
    	return $_result;
    }
	
    
    //�����ȶϢ��
     function GetSeason ($data = array()){
    	
    	//��������
    	if (isset($data['period']) && $data['period']>0){
    		$period = $data['period'];
    	}
    	
    	//������������Ǽ��ı���
    	if ($period%3!=0){
    		return false;
    	}
     
    	//�����ܽ��
    	if (isset($data['account']) && $data['account']>0){
    		$account = $data['account'];
    	}else{
    		return "";
    	}
    	
    	//����������
    	if (isset($data['apr']) && $data['apr']>0){
    		$year_apr = $data['apr'];
    	}else{
    		return "";
    	}
    	
    	
    	//����ʱ��
    	if (isset($data['time']) && $data['time']>0){
    		$time = $data['time'];
    	}else{
    		$time = time();
    	}
    	
    	//������
    	$month_apr = $year_apr/(12*100);
    	
    	//�õ��ܼ���
    	$_season = $period/3;
    	
    	//ÿ��Ӧ���ı���
    	$_season_money = round($account/$_season,2);
    	
    	//$re_month = date("n",$time);
    	$_yes_account = 0 ;
    	$repay_account = 0;//�ܻ����
    	$_capital_all = 0;
    	$_interest_all = 0;
    	$_account_all = 0.00;
    	for($i=0;$i<$period;$i++){
    		$repay = $account - $_yes_account;//Ӧ���Ľ��
    		
    		$interest = round($repay*$month_apr,2);//��Ϣ����Ӧ������������
    		$repay_account = $repay_account+$interest;//�ܻ����+��Ϣ
    		$capital = 0;
    		if ($i%3==2){
    			$capital = $_season_money;//����ֻ�ڵ������»���������ڽ���������
    			$_yes_account = $_yes_account+$capital;
    			$repay = $account - $_yes_account;
    			$repay_account = $repay_account+$capital;//�ܻ����+����
    		}
    		$_repay_account = $interest+$capital;
    		$_result[$i]['account_interest'] = round($interest,2);
    		$_result[$i]['account_capital'] = round($capital,2);
    		$_result[$i]['account_all'] =round($_repay_account,2);
    		
    		$_account_all +=  $_repay_account;
    		$_interest_all +=  $interest;
    		$_capital_all +=  $capital;
    		
    		$_result[$i]['account_other'] = round($repay,2);
    		$_result[$i]['repay_month'] = round($repay_account,2);
    		$_result[$i]['repay_time'] = get_times(array("time"=>$time,"num"=>$i+1));
    	}
    	if ($data["type"]=="all"){
    		$_result_all['account_total'] =  round($_account_all,2);
    		$_result_all['interest_total'] =  round($_interest_all,2);
    		$_result_all['capital_total'] =  round($_capital_all,2);
    		$_result_all['repay_month'] = "-";
    		$_result_all['repay_season'] = $_season_money;
    		$_result_all['month_apr'] = round($month_apr*100,2);
    		return $_result_all;
    	}
    	return $_result;
    }

    
    //���ڻ�����Ϣ
     function GetEnd ($data = array()){
    	
    	//��������
    	if (isset($data['period']) && $data['period']>0){
    		$period = $data['period'];
    	}
    	
     
    	//�����ܽ��
    	if (isset($data['account']) && $data['account']>0){
    		$account = $data['account'];
    	}else{
    		return "";
    	}
    	
    	//����������
    	if (isset($data['apr']) && $data['apr']>0){
    		$year_apr = $data['apr'];
    	}else{
    		return "";
    	}
    	
    	
    	//����ʱ��
    	if (isset($data['time']) && $data['time']>0){
    		$time = $data['time'];
    	}else{
    		$time = time();
    	}
    	
    	//������
    	$month_apr = $year_apr/(12*100);
    	
    	$interest = $month_apr*$period*$account;
    	
    	if (isset($data['type']) && $data['type']=="all"){
    		$_result_all['account_total'] =   round($account + $interest,2);
    		$_result_all['interest_total'] =  round($interest,2);
    		$_result_all['capital_total'] =  round($account,2);
    		$_result_all['repay_month'] =  round($account + $interest,2);
    		$_result_all['month_apr'] = round($month_apr*100,2);
    		return $_result_all;
    	}else{
    		$_result[0]['account_all'] = round($interest+$account,2);
    		$_result[0]['account_interest'] = round($interest,2);
    		$_result[0]['account_capital'] = round($account,2);
    		$_result[0]['account_other'] = round($account,2);
    		$_result[0]['repay_month'] = round($interest+$account,2);
    		$_result[0]['repay_time'] = get_times(array("time"=>$time,"num"=>$period));
    		
    		return $_result;
    	}
    }
    
    
    //���ڻ��������¸�Ϣ
     function GetEndMonth ($data = array()){
    	
    	//��������
    	if (isset($data['period']) && $data['period']>0){
    		$period = $data['period'];
    	}
     
    	//�����ܽ��
    	if (isset($data['account']) && $data['account']>0){
    		$account = $data['account'];
    	}else{
    		return "";
    	}
    	
    	//����������
    	if (isset($data['apr']) && $data['apr']>0){
    		$year_apr = $data['apr'];
    	}else{
    		return "";
    	}
    	
    	
    	//����ʱ��
    	if (isset($data['time']) && $data['time']>0){
    		$borrow_time = $data['time'];
    	}else{
    		$borrow_time = time();
    	}
    	
    	//������
    	$month_apr = $year_apr/(12*100);
    	
    	
    	//$re_month = date("n",$borrow_time);
    	$_yes_account = 0 ;
    	$repayment_account = 0;//�ܻ����
    	
    	$interest = round($account*$month_apr,2);//��Ϣ����Ӧ������������
    	for($i=0;$i<$period;$i++){
    		$capital = 0;
    		if ($i+1 == $period){
    			$capital = $account;//����ֻ�ڵ������»���������ڽ���������
    		}
    		$_account_all +=  $_repay_account;
    		$_interest_all +=  $interest;
    		$_capital_all +=  $capital;
    		
    		$_result[$i]['account_all'] = $interest+$capital;
    		$_result[$i]['account_interest'] = $interest;
    		$_result[$i]['account_capital'] = $capital;
    		$_result[$i]['account_other'] = round($account-$capital,2);
    		$_result[$i]['repay_year'] = $account;
    		$_result[$i]['repay_time'] = get_times(array("time"=>$borrow_time,"num"=>$i+1));
    	}
    	if ($data["type"]=="all"){
    		$_result_all['account_total'] =  $account + $interest*$period;
    		$_result_all['interest_total'] = $_interest_all;
    		$_result_all['capital_total'] = $account;
    		$_result_all['repay_month'] = $interest;
    		$_result_all['month_apr'] = round($month_apr*100,2);
    		return $_result_all;
    	}
    		return $_result;
    }
    
    
    
    //���ڻ��������츶Ϣ
     function GetEndDay ($data = array()){
    	if ( $data['account']=="" ) { return "";}  
    	if ( $data['period']=="" ) { return "";}  //����
    	if ( $data['apr']=="" ) { return "";}  
    
    	//����ʱ��
    	if (isset($data['time']) && $data['time']>0){
    		$borrow_time = $data['time'];
    	}else{
    		$borrow_time = time();
    	}
    	
    	//������
    	$day_apr = $data['apr']/365/100;
        $_interest_all = round($data['account']*$data['period']*$day_apr,2);
    	$_account_all = $_interest_all +$data['account'];
    	
    	if ($data["type"]=="all"){
    		$_result['account_total'] =   $_account_all;
    		$_result['interest_total'] = $_interest_all;
    		$_result['capital_total'] = $data['account'];
    		$_result['day_apr'] = round($$day_apr,2);
    	}else{
            $_result[0]['account_all'] = $_account_all;
            $_result[0]['account_interest'] = $_interest_all;
            $_result[0]['account_capital'] = $data['account'];
            $_result[0]['repay_time'] = get_times(array("time"=>$borrow_time,"num"=>$data["period"],"type"=>"day"));
    	}
    	return $_result;
    }


}

?>