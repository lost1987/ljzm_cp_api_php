<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-11
 * Time: 下午4:29
 * 查询角色是否存在
 */
if(!defined('BASEPATH'))exit;
$time = $input -> get('time');
$key = $_platform['key'];//平台唯一标识
$user = $input->get('user');//应该是用户的loginname或者account_name
$sign = $input->get('sign');//签名
$sid = $input->get('sid');//服务器ID 例如1服就是1, 2服就是2

$sign_arr = array(
    'user' => $user,
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

//判断是否有合服或混服
if($server->mergeid != 0 && $server->complexid != 0){
    $server = $db->select()->from(TB_SERVERS)->where("id=$server->mergeid")->get()->result_object();
}else if($server->mergeid != 0 && $server->complexid == 0){
    $server = $db->select()->from(TB_SERVERS)->where("id=$server->mergeid")->get()->result_object();
}else if($server->complexid !=0 && $server->mergeid == 0){
    $server = $db->select()->from(TB_SERVERS)->where("id=$server->complexid")->get()->result_object();
    $sid = $server->id % 10000;
}
if(FALSE == $server)throw new Exception(502);

$user = $_platform['bflag'].$user;
//验证角色
$db -> connect($server->ip.':'.$server->port,$server->dbuser,$server->dbpwd);
$db -> select_db($server->dynamic_dbname);
$role = $db -> select('name,yuanbao,levels') -> from('fr_user') -> where("account_name = '$user' and  server=$sid") -> get() -> result_object();
if($role != FALSE && !empty($role->name)){
            $db ->connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
            $db -> select_db(DB_BASE);
            $base = $db->select('yuanbao,yuanbaonum') -> from("fr2_base") -> where("loginname = '$user'")->get() -> result_object();
            $returnparams = array(
                    "code" => 1,
                    "message"=>'角色存在',
                    "data" => array(
                        'name' => $role->name,
                        'yuanbao' => $role->yuanbao,
                        'level' => $role->levels,
                        'cznum' => $base->yuanbaonum,
                         'cz' => $base->yuanbao/RATIO
                    )
            );
            return json_encode($returnparams);
}
return '{"code":503,"message":"角色不存在"}';