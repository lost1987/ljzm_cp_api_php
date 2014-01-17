<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午5:14
 */
if(!defined('BASEPATH'))exit;
$logid = $input ->get('logid');
//关闭服务
$command= "c:\\server".$sid."\\OpenServer.exe CloseServer.exe ServerMgrMFC".$sid.'.exe';
exec($command,$output,$stat);
if($stat != 0)
    return -1;

sleep(5);

//这里开始操作系统函数c:\wamp\www\server10001\OpenServer ServerMgrMFC10001.exe
$command ="c:\\server".$sid."\\OpenServer ServerMgrMFC".$sid.'.exe';
exec($command,$output,$stat);
if($stat == 0){
    $db -> query("update ".TB_OPERATIONLOG." set state = 1 where id = $logid");
    return 1;
}
else
    return -1;

