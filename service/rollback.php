<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午5:15
 */
if(!defined('BASEPATH'))exit;
//这里开始操作系统函数c:\wamp\www\server10001\OpenServer ServerMgrMFC10001.exe
exec('tasklist|findstr /i "SQLExecution.exe"',$output,$stat);//检测是否有进程存在
if(!empty($output[0]) && $stat == 0)return -2;//进程存在不进行操作

$filename = $input->get('filename');
$logid = $input->get('logid');
$command ="c:\\server".$sid."\\OpenServer SQLExecution 3 ".$sid. " ".$filename.".sql $logid";
//error_log($command);
exec($command,$output,$stat);
if($stat == 0)
    return 1;
else
    return -1;