<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-6
 * Time: 下午2:44
 * 六界之门 联运 API 接口 入口
 */
session_start();
set_time_limit(60);
header("content-type:text/html;charset=utf-8");
define('BASEPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
require 'conf/config.inc.php';
require 'requirements.php';

Engine::createDBClass();

$errors = require('conf/errors.php');//错误定义
$input = new Input();
$map = $input->get('m');
$pt = $input->get('pt');

if(empty($map))die($errors[100]);
if(empty($pt))die($errors[200]);

switch(DEBUG_MODE){
    case TRUE : error_reporting(1);break;
    case FALSE:error_reporting(0);break;
    default:error_reporting(1);
}

$api_map = require('conf/api_map.php');//取得API类型
if(!array_key_exists($map,$api_map))die($errors[101]);

$db = new DB;
$db -> connect(DB_HOST.':'.DB_PORT,DB_USER,DB_PWD);
$db -> select_db(DB_NAME);
$platform = $db -> query("select id,bkey,name,bflag from ".TB_BUISSNESSER." where bflag = '$pt'") -> result_object();
if(FALSE == $platform || empty($platform -> bkey))die($errors[201]);//取得平台唯一标识
$_platform = array(
     'key' => $platform->bkey,
     'bid' => $platform->id,
     'name' => $platform->name,
     'bflag' => $platform->bflag
);

$service_path = 'service/'.$api_map[$map].'.php';

$output = require($service_path);
if(!empty($output))
echo $output;




