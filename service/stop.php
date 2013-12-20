<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午5:14
 */

$command= "c:\\server".$sid."\\OpenServer.exe CloseServer.exe ServerMgrMFC".$sid.'.exe';
exec($command,$output,$stat);
if($stat == 0){
    $db -> query("update ".TB_OPERATIONLOG." set state = 1 where id = $logid");
    return 1;
}
else
    return -1;