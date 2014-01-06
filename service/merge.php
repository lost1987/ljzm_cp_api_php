<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午5:15
 */
if(!defined('BASEPATH'))exit;
exec('tasklist|findstr /i "DBUnion.exe"',$output,$stat);//检测是否有进程存在
if(!empty($output[0]) && $stat == 0)return -2;//进程存在不进行操作


$from_serverids = $input -> get('from_server_ids');
$logid = $input->get('logid');
$command ="c:\\server".$sid."\\OpenServer DBUnion $logid $sid $from_serverids";
//error_log($command);
exec($command,$output,$stat);
if($stat == 0)
    return 1;
else
    return -1;