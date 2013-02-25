<?php
require('chinese.php');
require_once(ROOT_PATH."modules/borrow/borrow.class.php");
require_once(ROOT_PATH."modules/borrow/borrow.tender.php");
require_once(ROOT_PATH."modules/borrow/borrow.loan.php");
require_once(ROOT_PATH."modules/articles/articles.class.php");
$borrow_nid = $_REQUEST['nid'];
$result_head = borrowClass::GetView(array("borrow_nid"=>$borrow_nid));
$result_tender = borrowTenderClass::GetTenderList(array("borrow_nid"=>$borrow_nid,"limit"=>"all"));
$result_repay = borrowLoanClass::GetRepayList(array("borrow_nid"=>$borrow_nid,"limit"=>"all","order"=>"order"));
$articles = articlesClass::GetPageOne(array("id"=>13));

$pdf=new PDF_Chinese('P', 'mm', 'A4');
$pdf->Open();
$pdf->AddPage();
$pdf->AddGBFont();
$pdf->SetFont('GB','B',20);

$pdf->Cell('80');
$pdf->Cell(80,15,'���������ͬ',1,0,'C');

$pdf->SetFont('GB','',10);
$pdf->Cell(100,30,'',0,1);

$pdf->Cell(100,5,'����ţ�'.$result_head['borrow_nid'],0,1);
$pdf->Cell(100,5,'����ˣ�'.$result_head['username'],0,1);
$pdf->Cell(100,5,'���ʽ��'.$result_head['style_title'],0,1);
$pdf->Cell(100,5,'�����ˣ������������¼',0,1);
$pdf->Cell(100,5,'ǩ�����ڣ�'.date("Y-m-d",$result_head['borrow_success_time']),0,1);

//��������¼
$pdf->Cell(100,10,'',0,1);
$pdf->Cell(100,5,'1����������¼��',0,1);
$header=array('������','�����','�������','������','��ʼ��','������','�½�ֹ������','�ܻ��Ϣ'); //���ñ�ͷ
$data=array(); //���ñ���
//$data[0] = array('wdf111','3000.00Ԫ','6����','16.0%','2012-12-28','2013-06-28','ÿ��28��','3141.54 Ԫ');
foreach($result_tender as $key=>$value){

	$data[$key] = array($value['username'],$value['account'],$value['borrow_period_name'],$value['borrow_apr'],date("Y-m-d",$result_head['borrow_success_time']),date("Y-m-d",$result_head['repay_last_time']),$result_head['repay_each_time'],$value['recover_account_all']);

}
$width=array(25,25,20,20,25,25,25,25); //����ÿ�п��
for($i=0;$i<count($header);$i++){ //ѭ�������ͷ
	$pdf->Cell($width[$i],6,$header[$i],1,0,'C');
}
$pdf->Ln();
foreach($data as $row){//ѭ���������
	for($i=0;$i<count($header);$i++){
		$pdf->Cell($width[$i],6,$row[$i],1,0,'C');
	}
	$pdf->Ln();
}
$pdf->SetFont('GB','B',12);

	$total=array('�ܽ�',$result_head['account'],'',$result_head['borrow_apr'],date("Y-m-d",$result_head['borrow_success_time']),date("Y-m-d",$result_head['repay_last_time']),'�ܻ��Ϣ��',$result_head['repay_account_all']);

for($i=0;$i<count($total);$i++){ 
	$pdf->Cell($width[$i],6,$total[$i],1,0,'C');
}

//������ϸ
$pdf->SetFont('GB','',10);
$pdf->Cell(60,15,'',0,1);
$pdf->Cell(60,5,'2��������ϸ��',0,1);
$header=array('�������','������','Ӧ��ʱ��','���Ϣ','�����','������Ϣ'); //���ñ�ͷ
$data=array(); //���ñ���

foreach($result_repay as $key=>$value){
	$data[$key] = array($value['repay_period'],$value['borrow_apr'],date("Y-m-d",$value['repay_time']),$value['repay_account'],$value['repay_capital'],$value['repay_interest']);
}
$width=array(25,25,35,35,35,35); //����ÿ�п��
for($i=0;$i<count($header);$i++){ //ѭ�������ͷ
	$pdf->Cell($width[$i],6,$header[$i],1,0,'C');
}
$pdf->Ln();
foreach($data as $row){//ѭ���������
	for($i=0;$i<count($header);$i++){
		$pdf->Cell($width[$i],6,$row[$i],1,0,'C');
	}
	$pdf->Ln();
}
$articles = strip_tags($articles['contents']);
$contents = str_replace("&nbsp;", "", $articles);
$pdf->Cell(100,10,'',0,1);
$pdf->MultiCell(0, 4, $contents);
//$pdf->Cell(920,0,$articles,0, 1, 'C');
//$pdf->Image('themes/ryr/images/gongzhang.jpg',40);
$pdf->SetFont('GB','B',16);
//$pdf->Cell(180,0,'ǩ�����ڣ�'.date("Y-m-d",$result_head['borrow_success_time']),0,'','C');
/* $save_path = '../data/images/pdf/';
*/

$filename = $borrow_nid.'.pdf'; 

//I�鿴 Dֱ������  F����pdf�ļ�
$pdf->Output($save_path.$filename, 'D');
?>
