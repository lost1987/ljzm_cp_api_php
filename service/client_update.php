<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-23
 * Time: 上午11:20
 */

if(!defined('BASEPATH'))exit;
exec('tasklist|findstr /i "ClientUpdate.exe"',$output,$stat);//检测是否有进程存在
if(!empty($output[0]) && $stat == 0){//进程存在
        return -1;
}else{
    $sid = $input->get('sid');
    $filename = $input->get('filename');
    $versionid = $input->get('versionid');
    $logid = $input->get('logid');
    $command ="c:\\OpenServer ClientUpdate 1 ".$sid." $filename".".zip $versionid $logid";
    //error_log($command);
    exec($command,$output,$stat);
    if(!empty($output[0]) && $stat == 0){
        return 1;
    }else{
        return -2;
    }
}