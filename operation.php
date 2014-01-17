<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-12-11
 * Time: 下午5:11
 * 系统操作入口
 */

session_start();
set_time_limit(0);
header("content-type:text/html;charset=utf-8");
define('BASEPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
require 'conf/config.inc.php';
require 'requirements.php';

Engine::createDBClass();

$errors = require('conf/errors.php');//错误定义
$input = new Input();
$map = $input->get('m');

if(empty($map))die($errors[100]);


switch(DEBUG_MODE){
    case TRUE : error_reporting(1);break;
    case FALSE:error_reporting(0);break;
    default:error_reporting(1);
}

$api_map = require('conf/api_map.php');//取得API类型
if(!array_key_exists($map,$api_map))die($errors[101]);

$service_path = 'service/'.$api_map[$map].'.php';

//进行key验证
$key = $input->get('key');
$sid = $input->get('sid');
$time = $input->get('time');
$db = new DB;
$db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
$db -> select_db(DB_NAME);
$apicode = $db -> query("select code from ".TB_APIKEY." where id = 1") -> result_object() -> code;
$mykey = md5($time.$sid.$apicode);
if($mykey != $key)die($errors[501]);

$output = require($service_path);
if(!empty($output))
    echo $output;