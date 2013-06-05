<?php
// �Զ����� ��ת��
define('TASK_PATH',dirname(__FILE__));
$lock = TASK_PATH.'/task.roboot.lock';
$lock_date = is_file($lock)?date('Ymd',fileatime($lock)):0;
if(date('Ymd')==$lock_date){
    exit('ok');
}
require(TASK_PATH.'/../core/config.inc.php');
require_once(TASK_PATH."/../libs/Requests.php");
require_once(DEAYOU_PATH.'modules/account/account.class.php');
require_once(DEAYOU_PATH.'modules/borrow/borrow.class.php');
require_once(DEAYOU_PATH.'modules/borrow/borrow.fee.php');
Requests::register_autoloader();
$system = array();
$system_name = array();
$_system = $mysql->db_selects("system");

$system['con_cookie_status'] =0;
foreach ($_system as $key => $value){
	$system[$value['nid']] = $value['value'];
	$system_name[$value['nid']] = $value['name'];
}
$_G['system'] = $system;
$_G['_system'] = $_system;

$nowtime = strtotime(date('Y-m-d'));
$nexttime = $nowtime+86400;
$pdo = new PDO('mysql:host='.$db_config['host'].';dbname='.$db_config['name'].';charset='.$db_config['language'], $db_config['user'], $db_config['pwd']);

/*��ʼ�� ���������е���һ�ʵĻ���ʱ�� ��ֹ��������*/
$sql = 'SELECT `borrow_nid`,min(`repay_time`) as repay_time,`repay_period` FROM `deayou_borrow_repay` WHERE `repay_status`=0 AND `status`=1 AND `repay_type`=\'wait\' group by `borrow_nid`';
$data = $pdo->query($sql);
foreach($data as $value){
    $sql = 'UPDATE `deayou_borrow` SET `repay_next_time`='.$value['repay_time'].' where borrow_nid=\''.$value['borrow_nid'].'\'';
    $pdo->exec($sql);
}
unset($data);

// �ҳ���Ҫ ��Ҫ�Զ�����ı���Ϣ
$sql = 'SELECT `borrow_nid`,`name`,`borrow_type`,`borrow_style`,`borrow_period`,`borrow_frost_account`,`amount_type`,`username`,`award_status`,`award_false`,`award_scale`,`award_account` FROM `deayou_borrow` LEFT JOIN `deayou_users` ON `deayou_borrow`.`user_id`=`deayou_users`.`user_id` WHERE `borrow_type` in (SELECT `nid` FROM `deayou_borrow_type` WHERE `status`=1 AND `system_borrow_repay_status`=1 ) AND `repay_next_time` BETWEEN '.$nowtime.' AND '.$nexttime;
$data = $pdo->query($sql);
$borrow_data = [];
foreach($data as $value){
    $borrow_data[$value['borrow_nid']]=['borrow_nid'=>$value[0],'name'=>$value[1],'borrow_type'=>$value[2],'borrow_style'=>$value[3],'borrow_period'=>$value[4],'borrow_frost_account'=>$value[5],'amount_type'=>$value[6],'username'=>$value[7]];
    unset($value);
}
unset($data);
if(empty($borrow_data)){
    touch($lock);
    exit;
}
// �ӻ�����в�����Ҫ������˵�
$where = '`borrow_nid` IN (\''.implode('\',\'',array_keys($borrow_data)).'\') AND `repay_status`=0 AND `status`=1 AND `repay_type`=\'wait\' AND `repay_time` BETWEEN '.$nowtime.' AND '.$nexttime;
$data = $pdo->query('SELECT COUNT(`id`) as total FROM `deayou_borrow_repay` WHERE '.$where);
$total = 0;
foreach($data as $value){
    $total = $value['total'];
}
unset($data);
if(empty($total)){
    touch($lock);
    exit('no birth');
}
$ps = intval($total/5);
$ps = $total%5?$ps+1:$ps;
for($i=1;$i<=$ps;$i++){
    $sql = 'SELECT  `id` , `borrow_nid` , `user_id` , `repay_days` , `repay_time` , `repay_capital` , `repay_interest` , `repay_account` , `repay_account_all` , `repay_action_time` , `repay_period`,`repay_yestime` FROM `deayou_borrow_repay` WHERE '.$where.' ORDER BY `id` ASC LIMIT 0,5';
    $data = $pdo->query($sql);
    foreach($data as $repay){
        if(!empty($repay)){
            $borrow_nids[$repay["borrow_nid"]]=$repay["borrow_nid"];
            // Ͷ���˴���
            $repay_nid = $repay["borrow_nid"].'_'.$repay['user_id'].'_'.$repay['id'].'_'.$repay['repay_period'];
            $borrow_info = $borrow_data[$repay["borrow_nid"]];
            $borrow_url = '<a href="http://test.rongerong.com/invest/a'.$repay['borrow_nid'].'.html" target="_blank">'.$borrow_info['name'].'</a>';
            //�ж�����ǰ��������������
            if (empty($repay["repay_days"])){
                $late_days = (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$repay["repay_time"])))/86400;
                $repay["repay_days"] = $late_days;
            }
            // �����ܽ��
            if($repay['repay_account_all']<=0){
                $vip_status =0;
                $vip_result=usersClass::GetUsersVip(["user_id"=>$repay["user_id"]]);
                if(!empty($vip_result)){
                    $vip_status = $vip_result['status'];
                }
                $credit_result = borrowClass::GetBorrowCredit(['user_id'=>$repay['user_id']]);
                $_fee = [];
                $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
                $_fee["credit_fee"] =isset($credit_result['credit']['fee'])?$credit_result['credit']['fee']:0;//�ж��ǲ���vip
                $_fee["borrow_type"] = $borrow_info["borrow_type"];//�������
                $_fee["borrow_style"] = $borrow_info["borrow_style"];//���ʽ
                $_fee["type"] = "borrow_repay";//����
                $_fee["user_type"] = "borrow";//���ڽ���߻���Ͷ����
                $_fee["capital"] = $repay["repay_capital"];//���ڽ���߻���Ͷ����
                $_fee["interest"] = $repay["repay_interest"];//���ڽ���߻���Ͷ����
                $result = borrowFeeClass::GetFeeValue($_fee);
                $_fee_account = 0;
                foreach ($result as $key => $value){
                    $_fee_account += $value["account"];
                }
                //Ӧ�û�����ܶ
                $repay["repay_account_fee"]  = $_fee_account;
                $repay["repay_account_all"]  = $repay["repay_account"] + $_fee_account;
            }else{
                $repay["repay_account_fee"] = $repay["repay_account_all"]- $repay["repay_account"];
            }
            //�۳�����˵Ļ�����
            $log_info = [];
            $log_info["user_id"] = $repay["user_id"];//�����û�id
            $log_info["nid"] = "borrow_repay_".$repay_nid;//������
            $log_info["account_web_status"] = 0;//
            $log_info["account_user_status"] = 1;//
            $log_info["borrow_nid"] = $repay["borrow_nid"];//����
            $log_info["code"] = "borrow";//
            $log_info["code_type"] = "borrow_repay";//
            $log_info["code_nid"] = $repay["id"];//
            $log_info["money"] = $repay["repay_account"];//�������
            $log_info["income"] = 0;//����
            $log_info["expend"] = $log_info["money"];//֧��
            $log_info["balance_cash"] = 0;//�����ֽ��
            $log_info["balance_frost"] = -$log_info["money"];//�������ֽ��
            $log_info["frost"] = 0;//������
            $log_info["await"] = 0;//���ս��
            $log_info["repay"] = 0;//�������
            $log_info["type"] = "borrow_repay";//����
            $log_info["to_userid"] = 0;//����˭
            $log_info["remark"] = "��[{$borrow_url}]�����".$repay["repay_period"]."�ڻ���";
            accountClass::AddLog($log_info);
            if ($borrow_info["borrow_type"]=="day" || $repay["repay_period"] == $borrow_info["borrow_period"]){
                if ($borrow_info["borrow_frost_account"]>0){
                    //���һ��������Ľ��
                    $log_info["user_id"] = $repay["user_id"];//�����û�id
                    $log_info["nid"] = "borrow_repay_frost_".$repay["borrow_nid"]."_".$repay["user_id"];//������
                    $log_info["borrow_nid"] = $repay["borrow_nid"];//����
                    $log_info["account_web_status"] = 0;//
                    $log_info["account_user_status"] = 0;//
                    $log_info["code"] = "borrow";//
                    $log_info["code_type"] = "borrow_repay_frost";//
                    $log_info["code_nid"] = $repay["borrow_nid"];//
                    $log_info["money"] = $borrow_info["borrow_frost_account"];//�������
                    $log_info["income"] =0;//����
                    $log_info["expend"] = 0;//֧��
                    $log_info["balance_cash"] = $log_info["money"];//�����ֽ��
                    $log_info["balance_frost"] = 0;//�������ֽ��
                    $log_info["frost"] = -$log_info["money"];//������
                    $log_info["await"] = 0;//���ս��
                    $log_info["repay"] = 0;//�������
                    $log_info["type"] = "borrow_repay_frost";//����
                    $log_info["to_userid"] = 0;//����˭
                    $log_info["remark"] = "��[{$borrow_url}]���Ľⶳ";
                    accountClass::AddLog($log_info);
                }
            }
            //����ߵ����û�������
            $credit_log['user_id'] = $repay["user_id"];
            $credit_log['nid'] = "borrow_success";
            $credit_log['code'] = "borrow";		
            $credit_log['addtime'] = time();
            $credit_log['article_id'] = $repay["id"];
            $credit_log['value'] = round($repay["repay_capital"]/100);	
            $credit_log['type'] = '��������'.$repay["repay_capital"].'���õĻ���';		
            $result = creditClass::ActionCreditLog($credit_log);
            
            //�û���¼
            $user_log["user_id"] = $repay["user_id"];
            $user_log["code"] = "borrow";
            $user_log["type"] = "repay_success";
            $user_log["operating"] = "repay";
            $user_log["article_id"] = $repay["user_id"];
            $user_log["result"] = 1;
            $user_log["content"] = '�Խ���['.$borrow_url.']��������';
            usersClass::AddUsersLog($user_log);
            // ����� end
            $sql = 'select p1.id,p1.recover_account,p1.user_id,p1.recover_capital,p1.recover_interest,p1.tender_id,p2.username,p3.change_status,p3.change_userid,p4.username as change_username from `{borrow_recover}` as p1 left join `{users}` as p2 on p1.user_id=p2.user_id left join `{borrow_tender}` as p3 on p1.tender_id=p3.id left join `{users}` as p4 on p3.change_userid=p4.user_id where p1.recover_period='.$repay["repay_period"].' and  p1.borrow_nid='.$repay['borrow_nid'];
            $recover_result_data = $mysql->db_fetch_arrays($sql);
            foreach($recover_result_data as $recover_result){
                if(!empty($recover_result)){
                    $recove_id = $recover_result['id'];
                    if ($recover_result['change_status']==1){
                        $recove_userid = $recover_result['change_userid'];
                        $recove_username = $recover_result['change_username'];
                    }else{
                        $recove_userid = $recover_result['user_id'];
                        $recove_username = $recover_result['username'];
                    }
                    $recover_account = $recover_result['recover_account'];
                    //����ɹ����򽫻�����Ϣ���������ȥ
                    $_recover_nid = $repay['borrow_nid']."_".$recove_userid."_".$recover_result['id']."_".$repay['repay_period'];
                    $recover_nid = "tender_recover_yes_".$_recover_nid;//������
                    //Ͷ���˵��ʽ𷵻�
                    $log_info = [];
                    $log_info["user_id"] = $recove_userid;//�����û�id
                    $log_info["nid"] = "tender_recover_yes_".$_recover_nid;//������
                    $log_info["account_web_status"] = 0;//
                    $log_info["account_user_status"] = 1;//
                    $log_info["borrow_nid"] = $repay["borrow_nid"];//����
                    $log_info["code"] = "borrow";//
                    $log_info["code_type"] = "tender_recover_yes";//
                    $log_info["code_nid"] = $recove_id;//
                    $log_info["money"] = $recover_account;//�������
                    $log_info["income"] = $log_info["money"];//����
                    $log_info["expend"] = 0;//֧��
                    $log_info["balance_cash"] = $log_info["money"];//�����ֽ��
                    $log_info["balance_frost"] = 0;//�������ֽ��
                    $log_info["frost"] = 0;//������
                    $log_info["await"] = -$log_info["money"];//���ս��
                    $log_info["repay"] = 0;//�������
                    $log_info["type"] = "tender_recover_yes";//����
                    $log_info["to_userid"] = 0;//����˭
                    $log_info["remark"] = "�ͻ���{$borrow_info['username']}����[{$borrow_url}]����ĵ�".($repay['repay_period'])."�ڻ���";
                    accountClass::AddLog($log_info);
                            
                    
                    $user_log["user_id"] = $recove_userid;
                    $user_log["code"] = "tender";
                    $user_log["type"] = "recover_success";
                    $user_log["operating"] = "recover";
                    $user_log["article_id"] = $recove_userid;
                    $user_log["result"] = 1;
                    $user_log["content"] = "�յ�����[{$borrow_url}]�Ļ���";
                    usersClass::AddUsersLog($user_log);	
                
                    //�۳�����
                    //�ж��Ƿ���vip
                    $vip_status =0;
                    $vip_result=usersClass::GetUsersVip(array("user_id"=>$recove_userid));
                    if($vip_result==true){
                         $vip_status = $vip_result['status'];
                    }
                    $credit_result = borrowClass::GetBorrowCredit(array("user_id"=>$recove_userid));
                    $_fee["vip_status"] = $vip_status;//�ж��ǲ���vip
                    $_fee["credit_fee"] = isset($credit_result['credit']['fee'])?$credit_result['credit']['fee']:0;//�ж��ǲ���vip
                    $_fee["borrow_type"] = $borrow_info["borrow_type"];//�������
                    $_fee["borrow_style"] = $borrow_info["borrow_style"];//���ʽ
                    $_fee["type"] = "borrow_repay";//���ڽ���߻���Ͷ����
                    $_fee["user_type"] = "tender";//���ڽ���߻���Ͷ����
                    $_fee["capital"] = $recover_result["recover_capital"];//���ڽ���߻���Ͷ����
                    $_fee["interest"] = $recover_result["recover_interest"];//���ڽ���߻���Ͷ����
                    $result = borrowFeeClass::GetFeeValue($_fee);
                    $recover_fee = 0;
                    if ($result != false){
                        foreach ($result as $key => $value){
                            $recover_fee += $value["account"];
                            $log_info = [];
                            $log_info["user_id"] = $recove_userid;//�����û�id
                            $log_info["nid"] = "tender_recover_fee_".$value["nid"]."_".$_recover_nid;//������
                            $log_info["borrow_nid"] = $repay["borrow_nid"];//����
                            $log_info["account_web_status"] = 1;//
                            $log_info["account_user_status"] = 1;//
                            $log_info["code"] = "borrow";//
                            $log_info["code_type"] = "tender_recover_fee_".$value["nid"];//
                            $log_info["code_nid"] = $recover_result["id"];//
                            $log_info["money"] = $value['account'];//�������
                            $log_info["income"] = 0;//����
                            $log_info["expend"] = $log_info["money"];//֧��
                            $log_info["balance_cash"] = -$log_info["money"];//�����ֽ��
                            $log_info["balance_frost"] = 0;//�������ֽ��
                            $log_info["frost"] = 0;//������
                            $log_info["await"] = 0;//���ս��
                            $log_info["repay"] = 0;//�������
                            $log_info["type"] = "tender_recover_fee_".$value["nid"];//����
                            $log_info["to_userid"] = 0;//����˭
                            $log_info["remark"] =  '�û��ɹ���������۳�['.$borrow_url.']'.$log_info["money"].'Ԫ'.$value['name'];
                            accountClass::AddLog($log_info);
                        }
                    }
                    // Ͷ�꽱��
                    if($borrow_info['award_status']>0 && $borrow_info['award_false']==0){
                        $sql = 'select (`recover_account_all`-`recover_account_interest`) as acount from {borrow_tender} where id='.$recover_result['tender_id'].' and `award`=0';
                        $recover_capital = $mysql->db_fetch_array($sql);
                        if(!empty($recover_capital['acount'])){
                            if($roam_result['award_status']==2){
                                $award = round($recover_capital['acount']*$borrow_info['award_scale']/100,2);
                            }
                            if($borrow_info['award_status']==1){
                                $award = $borrow_info['award_account'];
                            }
                            if($award>0){
                                $log_info["user_id"] = $recove_userid;//�����û�id
                                $log_info["nid"] = "brrow_award_add_".$repay['borrow_nid']."_".$recove_userid."_".$recover_result['tender_id'];//������
                                $log_info["account_web_status"] = 1;//
                                $log_info["account_user_status"] = 1;//
                                $log_info["borrow_nid"] = $repay['borrow_nid'];//����
                                $log_info["code"] = "tender";//
                                $log_info["code_type"] = "brrow_tender_award";//
                                $log_info["code_nid"] = $recover_result['tender_id'];//
                                $log_info["money"] = $award;//�������
                                $log_info["income"] = $award;//����
                                $log_info["expend"] = 0;//֧��
                                $log_info["balance_cash"] = $award;//�����ֽ��
                                $log_info["balance_frost"] = 0;//�������ֽ��
                                $log_info["frost"] = 0;//������
                                $log_info["await"] = 0;//���ս��
                                $log_info["type"] = "brrow_tender_award";//����
                                $log_info["to_userid"] = $recove_userid;//����˭
                                $log_info["remark"] =  "Ͷ�ʽ��[{$borrow_url}]��õ�Ͷ�ʽ���";
                                accountClass::AddLog($log_info);
                                $remind['nid'] = "brrow_tender_award";
                                $remind['remind_nid'] = $_nid;
                                $remind['receive_userid'] = $recove_userid;
                                $remind['article_id'] = $recover_result['tender_id'];
                                $remind['code'] = "borrow";
                                $remind['title'] = 'Ͷ�꽱��';
                                $remind['content'] = '����Ͷ�ʵġ�'.$borrow_info["username"].'����['.$borrow_url.']��'.date('Y-m-d').'���'.$award.'Ԫ����';
                                remindClass::sendRemind($remind);
                            }
                            $sql ='update  `{borrow_tender}` set `award`=1 where `id`='.$recover_result['tender_id'];
                            $mysql->db_query($sql);
                        }
                    }
                    
                    //�������յ�����վ���� 
                    $remind['nid'] = "repay_success";
                    $remind['receive_userid'] = $repay["user_id"];
                    $remind['remind_nid'] =  "repay_success_".$repay["borrow_nid"]."_".$repay["user_id"]."_".$recover_result["id"];
                    $remind['code'] = "borrow";
                    $remind['article_id'] = $repay["user_id"];
                    $remind['title'] = "���ѶԿͻ�".$recove_username."�ɹ����";
                    $remind['content'] = "������".date("Y-m-d",time())."�Կͻ�".$recove_username."����ɹ��������".$recover_result['recover_account'];
                    remindClass::sendRemind($remind);
                    
                    //Ͷ�����յ�����վ���� 
                    $remind['nid'] = "recover_success";				
                    $remind['receive_userid'] = $recove_userid;
                    $remind['remind_nid'] =  "recover_success_".$repay["borrow_nid"]."_".$recove_userid."_".$recover_result["id"];
                    $remind['code'] = "invest";
                    $remind['article_id'] = $recove_userid;
                    $remind['title'] = "�û���".$borrow_info['username']."��������Ͷ�ʵĽ���[{$borrow_info['name']}]�Ѿ��ɹ����";
                    $remind['content'] = "�û���".$borrow_info['username']."����".date("Y-m-d",time())."������Ͷ�ʵĽ���[{$borrow_url}]�Ѿ��ɹ�����,�����".$recover_result['recover_account'];
                    remindClass::sendRemind($remind);


                    // ��Ͷ���˷����ֻ�����
                    $sql = 'select `phone` from `{users_info}` where `user_id`='.$recove_userid.' and `phone_status`=1 and `phone`!=\'\'';
                    $phone = $mysql->db_fetch_array($sql);
                    if(!empty($phone)){
                        $phone_data = [];
                        $phone_data['status'] = 1;
                        $phone_data['user_id'] = $recove_userid;
                        $phone_data['type'] = "recover_success";
                        $phone_data['phone'] = $phone['phone'];
                        $phone_data['contents'] = "�𾴵��������û�����Ͷ�ʵĽ���[{$borrow_info['name']}]��".$recover_result['recover_account']."�ɹ��ؿΪ������ʽ��ʹ���ʣ��������ֻ���Ͷƽ̨���±ꡣ��л���������ڵ�֧�֡�";
                        $phone_data['contents'] = iconv("GBK","UTF-8",$phone_data['contents']);
                        approveClass::SendSMS($phone_data);
                    }
                    
                    $sql = "update `{borrow_recover}` set recover_type='yes',recover_fee='{$recover_fee}',recover_yestime='".time()."',recover_account_yes = recover_account ,recover_capital_yes = recover_capital ,recover_interest_yes = recover_interest,status=1,recover_status=1 where id = '{$recover_result['id']}'";
                    $mysql->db_query($sql);
                    
                            
                     //����Ͷ�ʵ���Ϣ
                    $sql = "select count(1) as recover_times,sum(recover_account_yes) as recover_account_yes_num,sum(recover_interest_yes) as recover_interest_yes_num,sum(recover_capital_yes) as recover_capital_yes_num  from `{borrow_recover}` where tender_id='{$recover_result['tender_id']}' and recover_status=1";
                    $result = $mysql->db_fetch_array($sql);
                    $recover_times = $result['recover_times'];
                    
                    
                    $sql = "update  `{borrow_tender}` set recover_times={$recover_times},recover_account_yes= {$result['recover_account_yes_num']},recover_account_capital_yes =  {$result['recover_capital_yes_num']} ,recover_account_interest_yes = {$result['recover_interest_yes_num']},recover_account_wait= recover_account_all - recover_account_yes,recover_account_capital_wait = account - recover_account_capital_yes  ,recover_account_interest_wait = recover_account_interest -  recover_account_interest_yes  where id = '{$recover_result['tender_id']}'";
                    $mysql->db_query($sql);
                        
                    borrowCountClass::UpdateBorrowCount(array("user_id"=>$recove_userid,"borrow_nid"=>"{$repay['borrow_nid']}","nid"=>$recover_nid,"tender_recover_times_yes"=>1,"tender_recover_times_wait"=>-1,"tender_recover_yes"=>$recover_result['recover_account'],"tender_recover_wait"=>-$recover_result['recover_account'],"tender_capital_yes"=>$recover_result['recover_capital'],"tender_capital_wait"=>-$recover_result['recover_capital'],"tender_interest_yes"=>$recover_result['recover_interest'],"tender_interest_wait"=>-$recover_result['recover_interest']));
                }
            }

                //����ͳ����Ϣ
                borrowCountClass::UpdateBorrowCount(array("user_id"=>$repay["user_id"],"borrow_nid"=>"{$repay['borrow_nid']}","nid"=>"borrow_repay_".$repay['borrow_nid']."_".$repay['id']."_".$repay['repay_period'],"borrow_repay_yes_times"=>1,"borrow_repay_wait_times"=>-1,"borrow_repay_yes"=>$repay["repay_account"],"borrow_repay_wait"=>-$repay["repay_account"],"borrow_repay_interest_yes"=>$repay["repay_interest"],"borrow_repay_interest_wait"=>-$repay["repay_interest"],"borrow_repay_capital_yes"=>$repay["repay_capital"],"borrow_repay_capital_wait"=>-$repay["repay_capital"]));	
                
                if ($borrow_info['borrow_type']!="second"){
                    $_amount["user_id"] = $repay['user_id'];//�û�id
                    $_amount["amount_type"] = $borrow_info["amount_type"];//�������
                    $_amount["amount_style"] = "forever";
                    $_amount["type"] = "borrow_repay";
                    $_amount["oprate"] = "return";
                    $_amount["account"] = $repay['repay_capital'];
                    $_amount["nid"] = $_amount["type"]."_".$repay['user_id']."_".$repay['borrow_nid']."_".$repay['id'];
                    $_amount["remark"] = '����ɹ�['.$borrow_url.']������'.$repay['repay_capital'].'Ԫ���';
                    borrowAmountClass::AddAmountLog($_amount);
                }
            $sql = "update `{borrow_repay}` set repay_days='{$repay['repay_days']}',repay_action_time='".time()."',repay_account_all='{$repay['repay_account_all']}',repay_yestime='".time()."',repay_account_yes=repay_account,repay_interest_yes=repay_interest,repay_capital_yes=repay_capital, repay_fee='{$repay['repay_account_fee']}',repay_status=1,repay_type='yes',repay_step=4 where id='{$repay['id']}'";
            $mysql->db_query($sql);
        }
    }
}
// ����borrow ���ݱ�
if(!empty($borrow_nids)){
    foreach($borrow_nids as $borrow_nid){
             //�������
            $sql = "select count(1) as num,sum(repay_account) as yes_repay_account,sum(repay_capital) as yes_repay_capital,sum(repay_interest) as yes_repay_interest  from `{borrow_repay}` where borrow_nid='{$borrow_nid}' and repay_status=1";
            $num_result = $mysql->db_fetch_array($sql);
            if ($num_result==""){
                $repay_times = 0;
            }else{
                $repay_times = $num_result["num"];
            }
            
            //�����������
            $repay_fee_normal = 0;
            $sql = "select sum(repay_fee) as num  from `{borrow_repay}` where borrow_nid='{$borrow_nid}' and repay_status=1 and repay_type='yes'";
            $fee_result = $mysql->db_fetch_array($sql);
            if ($fee_result!=false){
                $repay_fee_normal = $fee_result["num"];
            }
            $sql = "select * from `{borrow_repay}` where borrow_nid='{$borrow_nid}' and repay_status=0 order by repay_period asc";
            $_result = $mysql->db_fetch_array($sql);
            if ($_result!=false){
                $repay_next_account = $_result["repay_account"];
                $repay_next_time = $_result["repay_time"];
                $repay_full_status=0;
            }else{
                $repay_next_account ="";
                $repay_next_time = "";
                $repay_full_status=1;
            }
            
            //����Ͷ�ʵ��˵�״̬�Ƿ��Ѿ�����
            $sql = "update `{borrow_tender}` set recover_full_status='{$repay_full_status}' where borrow_nid='{$borrow_nid}'";
            $mysql->db_query($sql);  
            
            
            $sql = "update `{borrow}` set repay_fee_normal='{$repay_fee_normal}',repay_full_status='{$repay_full_status}',repay_account_yes='{$num_result['yes_repay_account']}',repay_account_wait=repay_account_all-repay_account_yes,repay_account_capital_yes='{$num_result['yes_repay_capital']}',repay_account_capital_wait=repay_account_capital-repay_account_capital_yes,repay_account_interest_yes='{$num_result['yes_repay_interest']}',repay_account_interest_wait=repay_account_interest-repay_account_interest_yes,repay_times='{$repay_times}',repay_next_account='{$repay_next_account}',repay_next_time='{$repay_next_time}' where borrow_nid='{$borrow_nid}'";
            $mysql->db_query($sql);	//������Ļ�����
            //�ж��м�����ת�겻
            if (is_file(DEAYOU_PATH."modules/borrow/borrow.roam.php")){
                if ($borrow_info["borrow_type"]=="roam"){
                    $sql = "select sum(repay_capital) as num from `{borrow_repay}` where borrow_nid = '{$borrow_nid}' and repay_status=1";
                    $_recover_result = $mysql->db_fetch_array($sql);
                    if ($_recover_result==false){
                        $_recover_yes = 0;
                    }else{
                        $sql = "select account_min from `{borrow_roam}`  where borrow_nid = '{$borrow_nid}'";
                        $roam_result = $mysql->db_fetch_array($sql);
                        $_recover_yes = $_recover_result['num']/$roam_result['account_min'];
                    }
                    $sql = "update `{borrow_roam}` set recover_yes={$_recover_yes},recover_wait=portion_total-recover_yes where borrow_nid = '{$borrow_nid}'";
                    $mysql->db_query($sql);
                }
            }
        $sql="update {borrow_change} set status=0 where tender_id in (select id from {borrow_tender} where borrow_nid={$borrow_nid}) and status=2";
		$mysql->db_query($sql);
    }
}
touch($lock);