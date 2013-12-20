<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-6
 * Time: 下午3:23
 * 登录接口
 */

if(!defined('BASEPATH'))exit;

$time_local = time();
$time = $input -> get('time');
$username = $input->get('username');
$sign = $input->get('sign');
$cm = $input->get('cm');
$sid = $input->get('sid');


$sign_arr = array(
    'username' => $username,
    'time' => $time,
    'sid' => $sid,
    'key' => $_platform['key']
);

ksort($sign_arr);

$sign_str = '';
foreach($sign_arr as $k => $v){
    $sign_str .= $v;
}

$sign_str = sha1($sign_str);
if($sign_str != $sign)return $errors[501];

$db = new DB;
$db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
$db -> select_db(DB_NAME);

//验证服务器
$serverid = 10000*$_platform['bid'] + intval($sid);
$server = $db -> select("*")->from(TB_SERVERS)->where("id = $serverid")->get()->result_object();
if(FALSE == $server)return $errors[502];

if($server->stat == 0 || $server->status == 0)
        return $errors[102];

//验证用户
$username = $_platform['bflag'].$username;
$password = md5($username.$_platform['key'].PWD_FLAG);

require BASEPATH.'pages/main.php';
