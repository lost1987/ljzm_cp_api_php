<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-6
 * Time: 下午3:22
 * 充值接口
 * index.php?m=1&orderId=xxx&user=xxx&gold=xxx&money=xxx&sign=xxx&sid=xxx&time=xxx&pt=xxx
 */
if(!defined('BASEPATH'))exit;

$time_local = time();
$time = $input -> get('time');
$key = $_platform['key'];//平台唯一标识
$orderId = $input -> get('orderId'); //订单号
$user = $input->get('user');//应该是用户的loginname或者account_name
$gold = $input->get('gold'); //元宝或点数
$money = $input->get('money'); //RMB
$sign = $input->get('sign');//签名
$sid = $input->get('sid');//服务器ID 例如1服就是1, 2服就是2

if($money*10 != $gold)return $errors[202];

$sign_arr = array(
    'key' => $key,
    'orderId' => $orderId,
    'user' => $user,
    'gold' => $gold,
    'money' => $money,
    'sid' => $sid,
    'time'=>$time
);

//按字母的自然顺序排序
ksort($sign_arr);

$sign_str = '';
foreach($sign_arr as $k => $v){
    $sign_str .= $v;
}

$signsource = sha1($sign_str);

if($sign != $signsource)return $errors[501];

//签名验证成功,开始写入数据
$user = $_platform['bflag'].$user;
$orderId = $_platform['bflag'].'_'.$orderId;
$db = new DB;
$dynamic_db = new DB;
try{
    //真实服务器ID
    $serverid = $_platform['bid'] * 10000 + intval($sid);
    $db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
    $db -> select_db(DB_NAME);

    $server = $db -> select('*') -> from(TB_SERVERS) -> where("id = $serverid") -> get() -> result_object();
    if(FALSE == $server)throw new Exception(502);

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

    $db -> select_db(DB_BASE);
    $base = $db -> select('aountid')->from("fr2_base")->where("loginname='$user'")->get()->result_object();
    if(FALSE==$base || empty($base->aountid))throw new Exception(504);
    $accountid = $base->aountid;

    $dynamic_db -> connect($server->ip.':'.$server->port,$server->dbuser,$server->dbpwd,TRUE);
    $dynamic_db -> select_db($server->dynamic_dbname);

    $userlocal = $dynamic_db -> select('id,name') -> from('fr_user') ->where("account_id = $accountid and server=$sid and state=0") -> get() -> result_object();
    if(FALSE==$userlocal || empty($userlocal->name ))throw new Exception(503);

    $uid = $userlocal -> id;
    $db -> trans_begin();
    $dynamic_db -> trans_begin();

    if(!$db -> query("update fr2_base set yuanbao=yuanbao+$gold,yuanbaonum=yuanbaonum+1 where aountid = $accountid")->queryState)
        throw new Exception(600);

    if(!$dynamic_db -> query("update fr_user set saveyuanbao=saveyuanbao+$gold,mask31=mask31+$gold  where id = $uid and server=$sid")->queryState)
        throw new Exception(601);

    if(!$dynamic_db -> query("insert into  fr2_record (type, id1, id2, param1, param2, param4, str, str2) values (0,$uid,0,90000001,$gold,44,'{$_SERVER['REMOTE_ADDR']}','$orderId')")->queryState)
        throw new Exception(602);

    if(!$dynamic_db -> query("insert into  fr2_analysis (type, id1, id2, param1, param2, param4, str, str2) values (0,$uid,0,90000001,$gold,44,'{$_SERVER['REMOTE_ADDR']}','$orderId')")->queryState)
        throw new Exception(603);

    $ht = $dynamic_db->query("select count(pid) as num from ht_topup where pid = $uid")->result_object();
    if($ht -> num == 0){
        if(!$dynamic_db->query("insert into ht_topup(pid) values($uid)") ->queryState)
            throw new Exception(604);
    }

    if(!$db -> query("insert into  fr2_payinfo (eventid) values('$orderId')")->queryState)
        throw new Exception(605);

    $db -> commit();
    $dynamic_db->commit();
    return '{"code":1,"message":"充值成功"}';
}catch (Exception $e){
    $db -> rollback();
    $db -> close();
    $dynamic_db->rollback();
    $dynamic_db->close();
    return $errors[$e->getMessage()];
}
