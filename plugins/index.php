<?php
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
$q = !isset($_REQUEST['q'])?"":$_REQUEST['q'];
$file = ROOT_PATH."/plugins/html/".$q.".inc.php";
/**
 * ���� ���ѣ��ô�Ĵ��룬�ô�Ĵ��� ��ô����˼ ˵��ȥѽ
 * ��php5.4��ʵ�� session_register
 */
if(!function_exists('session_register')){
    function session_register(){
        $args = func_get_args();
        foreach ($args as $key){
            $_SESSION[$key]=$GLOBALS[$key];
        }
    }
}
if(!function_exists('session_is_registered'))
{
    function session_is_registered($key){
        return isset($_SESSION[$key]);
    }
}
if(!function_exists('session_unregister')){
    function session_unregister($key){
        unset($_SESSION[$key]);
    }
}

if (file_exists($file)){
    include_once ($file);exit;
}