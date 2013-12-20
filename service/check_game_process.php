<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午5:58
 * 检查游戏进程的状态
 */

exec('tasklist|findstr /i "serverMgrMFC'.$sid.'.exe"',$output,$stat);
if(!empty($output[0]) && $stat == 0)return 1; //开启进程
else if($stat != 0)return -2; //关闭
else  return -1; //未开启进程
