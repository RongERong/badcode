<?php
/******************************
 * $File: users.model.php
 * $Description: �û�������Ϣ����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

//#borrow_url# ��ʾ����ĵ�ַ
//#borrow_name#  ��ʾ��������

$MsgInfo["users_empty"] = "�鲻�����û�";
$MsgInfo["users_username_empty"] = "�û�������Ϊ��";
$MsgInfo["users_username_long15"] = "�û������Ȳ��ܴ���15���ַ�";
$MsgInfo["users_username_long4"] = "�û������Ȳ���С��4���ַ�";
$MsgInfo["users_username_exist"] = "�û����Ѿ�����";
$MsgInfo["users_password_empty"] = "���벻��Ϊ��";
$MsgInfo["users_password_error"] = "���벻һ��";
$MsgInfo["users_password_long6"] = "���벻��С��6λ";
$MsgInfo["users_email_empty"] = "���䲻��Ϊ��";
$MsgInfo["users_email_long32"] = "���䳤�Ȳ��ܴ���32���ַ�";
$MsgInfo["users_email_error"] = "�����ʽ����ȷ";
$MsgInfo["users_email_exist"] = "�����Ѿ�����";
$MsgInfo["users_userid_empty"] = "�û���ID����Ϊ��";
$MsgInfo["users_valicode_empty"] = "��֤�벻��Ϊ��";
$MsgInfo["users_valicode_error"] = "��֤�벻��ȷ";
$MsgInfo["users_keywords_empty"] = "�˻�����Ϊ��";
$MsgInfo["users_reg_invite_username_not_exiest"] = "�����˵��û��������ڣ���ѡ���Ƿ���д��ȷ�����û����Ϊ��";

$MsgInfo["users_info_userid_empty"] = "�û�����id����Ϊ��";

$MsgInfo["users_admin_id_error"] = "�Ҳ�����Ӧ�ĺ�̨����Ȩ��";
$MsgInfo["users_admin_login_password_error"] = "�û������벻��ȷ��";
$MsgInfo["users_admin_login_password_error_msg"] = "�û�#username#�ڡ�".date("Y-m-d H:i:s")."����¼��̨#admin_url#�������";
$MsgInfo["users_admin_login_status_error"] = "�����˻��Ѿ������ᣬ�����վ����Ա��ϵ";
$MsgInfo["users_admin_login_status_error_msg"] = "�û�#username#�ڡ�".date("Y-m-d H:i:s")."����¼��̨#admin_url#��Ϊ�û�״̬�����������������¼��";
$MsgInfo["users_admin_login_admin_id_error"] = "�����ǹ���Ա�����ܹ����̨";
$MsgInfo["users_admin_login_admin_id_error_msg"] = "�û�#username#�ڡ�".date("Y-m-d H:i:s")."����¼��̨#admin_url#��Ϊ���ǹ���Ա�����ܵ�¼��̨��";
$MsgInfo["users_admin_login_success"] = "��¼�ɹ�";
$MsgInfo["users_admin_login_success_msg"] = "�û�#username#�ڡ�".date("Y-m-d H:i:s")."����¼��̨#admin_url#�ɹ���";


$MsgInfo["users_add_success"] = "ע��ɹ�";
$MsgInfo["phone_code_error"] = "�ֻ���֤�����";
$MsgInfo["users_add_success_msg"] = "�ڡ�".date("Y-m-d H:i:s")."����ӡ�#username#���û��ɹ���";
$MsgInfo["users_add_error"] = "�û�����Ӵ����������Ա��ϵ";
$MsgInfo["users_add_error_msg"] = "�ڡ�".date("Y-m-d H:i:s")."����ӡ�#username#���û�ʧ�ܡ�";
$MsgInfo["users_add_error"] = "�û�����Ӵ����������Ա��ϵ";
$MsgInfo["users_add_reg_email_title"] = "ע������ȷ��";


$MsgInfo["users_update_password_success_msg"] = "�ڡ�".date("Y-m-d H:i:s")."���޸ġ�#username#���û�����ɹ���";
$MsgInfo["users_update_password_error_msg"] = "�ڡ�".date("Y-m-d H:i:s")."����ӡ�#username#���û�����ʧ�ܡ�";
$MsgInfo["users_update_email_success_msg"] = "�ڡ�".date("Y-m-d H:i:s")."���޸ġ�#username#���û�����ɹ���";
$MsgInfo["users_update_email_error_msg"] = "�ڡ�".date("Y-m-d H:i:s")."����ӡ�#username#���û�����ʧ�ܡ�";


$MsgInfo["users_login_success"] = "��¼�ɹ���";
$MsgInfo["users_login_success_msg"] = "��".date("Y-m-d H:i:s")."����¼�ɹ���";
$MsgInfo["users_login_error"] = "�û����������";
$MsgInfo["users_login_error_msg"] = "�û���#keywords#���ڡ�".date("Y-m-d H:i:s")."����¼ʧ�ܡ�";;


$MsgInfo["users_active_success"] = "���伤��ɹ�";
$MsgInfo["users_active_pass"] = "�����ַ�Ѿ����ڣ������¼���";
$MsgInfo["users_active_yes"] = "���������Ѿ��������Ҫ��һ�μ���";
$MsgInfo["users_active_error"] = "��������������Ա��ϵ";


//����Ϊģ����ļ���Ӣ������
$MsgInfo["users_name_id"] = "ID";
$MsgInfo["users_name_username"] = "�û���";
$MsgInfo["users_name_email"] = "����";
$MsgInfo["users_name_logintime"] = "��¼����";
$MsgInfo["users_name_password"] = "����";
$MsgInfo["users_name_password1"] = "ȷ������";
$MsgInfo["users_name_reg_time"] = "ע��ʱ��";
$MsgInfo["users_name_reg_ip"] = "ע��ip";
$MsgInfo["users_name_up_time"] = "�ϴε�¼ʱ��";
$MsgInfo["users_name_up_ip"] = "�ϴε�¼IP";
$MsgInfo["users_name_last_time"] = "����¼ʱ��";
$MsgInfo["users_name_last_ip"] = "����¼IP";


$MsgInfo["users_name_order_last_time"] = "����¼ʱ������";
$MsgInfo["users_name_order_default"] = "Ĭ������";
$MsgInfo["users_name_order_up_time"] = "���ϴε�¼ʱ������";
$MsgInfo["users_name_order_reg_time"] = "��ע��ʱ������";

$MsgInfo["users_name_operatinger"] = "������";
$MsgInfo["users_name_operating"] = "����";
$MsgInfo["users_name_operating_id"] = "����id";
$MsgInfo["users_name_type"] = "����";
$MsgInfo["users_name_result"] = "���";
$MsgInfo["users_name_content"] = "����";
$MsgInfo["users_name_add_time"] = "���ʱ��";
$MsgInfo["users_name_add_ip"] = "���ip";

$MsgInfo["users_name_code"] = "ģ��";
$MsgInfo["users_name_last_ip"] = "����¼IP";
$MsgInfo["users_name_sousuo"] = "����";

$MsgInfo["users_name_new"] = "����û�";
$MsgInfo["users_name_edit"] = "�༭�û�";
$MsgInfo["users_name_del"] = "ɾ���û�";
$MsgInfo["users_name_submit"] = "�ύ";
$MsgInfo["users_name_reset"] = "����";
$MsgInfo["users_name_success"] = "�ɹ�";
$MsgInfo["users_name_false"] = "ʧ��";
$MsgInfo["users_name_edit_not_empty"] = "���޸���Ϊ��";

$MsgInfo["users_type_name_empty"] = "�������Ʋ���Ϊ��";
$MsgInfo["users_type_nid_empty"] = "���ͱ�ʶ������Ϊ��";
$MsgInfo["users_type_nid_exiest"] = "���ͱ�ʶ���Ѿ�����";
$MsgInfo["users_type_add_success"] = "������ͳɹ�";
$MsgInfo["users_type_update_success"] = "�޸����ͳɹ�";
$MsgInfo["users_type_del_success"] = "ɾ�����ͳɹ�";
$MsgInfo["users_type_empty"] = "���Ͳ�����";
$MsgInfo["users_type_id_empty"] = "����ID������";
$MsgInfo["users_type_upfiles_exiest"] = "������ͼƬ���ڣ�����ɾ��";



$MsgInfo["users_admin_type_name_empty"] = "�����������Ʋ���Ϊ��";
$MsgInfo["users_admin_type_user_exiest"] = "���й���Ա���ڣ�����ɾ������";
$MsgInfo["users_admin_type_not_delete"] = "��������Ա����ɾ��";
$MsgInfo["users_admin_type_id_empty"] = "��������ID����Ϊ��";
$MsgInfo["users_admin_type_nid_empty"] = "�������ͱ�ʶ������Ϊ��";
$MsgInfo["users_admin_type_nid_exiest"] = "�������ͱ�ʶ���Ѿ�����";
$MsgInfo["users_admin_type_add_success"] = "��ӹ������ͳɹ�";
$MsgInfo["users_admin_type_update_success"] = "�޸Ĺ������ͳɹ�";
$MsgInfo["users_admin_type_del_success"] = "ɾ���������ͳɹ�";
$MsgInfo["users_admin_type_empty"] = "�������Ͳ�����";
$MsgInfo["users_admin_type_id_empty"] = "��������ID������";
$MsgInfo["users_admin_type_upfiles_exiest"] = "����������ͼƬ���ڣ�����ɾ��";


$MsgInfo["users_admin_name_empty"] = "����Ա��������Ϊ��";
$MsgInfo["users_admin_password_empty"] = "����Ա���벻��Ϊ��";
$MsgInfo["users_admin_user_id_empty"] = "�û�ID����Ϊ��";
$MsgInfo["users_admin_update_success"] = "����Ա�����ɹ�";
$MsgInfo["users_admin_del_success"] = "����Աɾ���ɹ�";


$MsgInfo["usres_vip_apply_success"] = "VIP����ɹ�����ȴ�����Ա���";
$MsgInfo["users_vip_status_yes"] = "vip�Ѿ����ͨ�����벻Ҫ�����";
$MsgInfo["users_vip_balance_not"] = "vip����";
$MsgInfo["users_vip_new_error"] = "vip�����������";
$MsgInfo["users_vip_status_wait"] = "vip�Ѿ��ύ����ȴ����";



$MsgInfo["users_friends_type_name_empty"] = "�����������Ʋ���Ϊ��";
$MsgInfo["users_friends_type_user_exiest"] = "���к���Ա���ڣ�����ɾ������";
$MsgInfo["users_friends_type_not_delete"] = "��������Ա����ɾ��";
$MsgInfo["users_friends_type_id_empty"] = "��������ID����Ϊ��";
$MsgInfo["users_friends_type_nid_empty"] = "�������ͱ�ʶ������Ϊ��";
$MsgInfo["users_friends_type_nid_exiest"] = "�������ͱ�ʶ���Ѿ�����";
$MsgInfo["users_friends_type_add_success"] = "��Ӻ������ͳɹ�";
$MsgInfo["users_friends_type_update_success"] = "�޸ĺ������ͳɹ�";
$MsgInfo["users_friends_type_del_success"] = "ɾ���������ͳɹ�";
$MsgInfo["users_friends_type_empty"] = "�������Ͳ�����";
$MsgInfo["users_friends_type_id_empty"] = "��������ID������";
$MsgInfo["users_friends_type_upfiles_exiest"] = "����������ͼƬ���ڣ�����ɾ��";

$MsgInfo["users_friends_yes"] = "�Ѿ������ĺ���";
$MsgInfo["users_friends_self"] = "���ܼ��Լ�Ϊ����";



$MsgInfo["users_impression_userid_empty"] = "�㻹û��¼";
$MsgInfo["users_impression_to_userid_empty"] = "�����Ҳ���";
$MsgInfo["users_impression_empty"] = "ӡ����Ϊ��";
$MsgInfo["users_impression_long7"] = "ӡ���ܴ���7���ַ�";
$MsgInfo["users_impression_self_error"] = "���ܸ��Լ����ӡ��";



$MsgInfo["users_messages_userid_empty"] = "�㻹û��¼";
$MsgInfo["users_messages_to_userid_empty"] = "�����Ҳ���";
$MsgInfo["users_messages_empty"] = "����Ϣ����Ϊ��";
$MsgInfo["users_messages_contents_empty"] = "����Ϣ����Ϊ��";
$MsgInfo["users_messages_not_self"] = "���ܸ��Լ����Ͷ���Ϣ";


$MsgInfo["users_login_lock"] = "�����˺��ѱ��������������Ա��ϵ";

?>
