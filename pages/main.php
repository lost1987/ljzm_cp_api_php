<?php
/**
 * Created by PhpStorm.
 * User: lost
 * Date: 13-11-7
 * Time: 上午10:18
 */

if(!defined('BASEPATH'))exit;
//查询游戏版本
$ver = $db -> select("version") -> from(TB_CVERSION) -> where("id = $server->gamecver") -> get() -> result_object();
$series = $db -> select("name") -> from(TB_SERIES) -> where("id = $server->gameseries") -> get() -> result_object();
$binurl = BASEURL.$series->name.'_'.$ver->version.'.zip/bin/';
$resurl = BASEURL.$series->name.'_'.$ver->version.'.zip/';
$urlroot = BASEURL.$series->name.'_'.$ver->version.'.zip/';
$aid = $server->bid;//原始运营商ID
//规则 合服过的服务器是不能混服的 AND 混服的服务器还可以再合服一次
if($server->mergeid != 0 && $server->complexid !=0){//判断合服和混服 如果合服混服标识同时存在 那么以合服的地址ip为准
        $_server = $db -> select() -> from(TB_SERVERS) -> where("id=$server->mergeid") -> get() -> result_object();
        $server = $_server;
}else if($server -> mergeid !=0 && $server->complexid == 0){
        $_server = $db -> select() -> from(TB_SERVERS) -> where("id=$server->mergeid") -> get() -> result_object();
        $server = $_server;
}else if($server -> complexid != 0 && $server-> mergeid ==0 ){
        $_server = $db -> select() -> from(TB_SERVERS) -> where("id=$server->complexid") -> get() -> result_object();
        $server = $_server;
}

$swfstring = $binurl . 'ljzm_main10.swf?ver=' . $ver->version . '&server=' . $server->server_ip . '&port=' . $server->server_port . '&res=' . $resurl . '&bin=' . $binurl . '&loginname=' . $username . '&loginpwd=' . $password . '&logintime=123'  .  '&sid=' . $sid . '&loginmode=new&urlroot=' . $urlroot.'&aid='.$aid;
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css" media="screen">
        html, body	{ height:100%; }
        body { margin:0; padding:0; overflow:auto; text-align:center;
            background-color: #000000; }
        object:focus { outline:none; }
        #flashContent { display:none; }
    </style>
    <script type="text/javascript" src="js/swfobject.js"></script>
    <script type="text/javascript">
        var swfVersionStr = "10.0.0";
        var xiSwfUrlStr = "<?php echo BINURL; ?>playerProductInstall.swf";
        var flashvars = {};
        var params = {};
        params.quality = "high";
        params.bgcolor = "#000000";
        params.allowscriptaccess = "always";
        params.allowfullscreen = "true";
        var attributes = {};
        if (navigator.appName.indexOf("Microsoft") != -1) {
            attributes.id = "myFlash_ob";
            attributes.name = "myFlash_ob";
        } else {
            attributes.id = "myFlash_em";
            attributes.name = "myFlash_em";
        }
        attributes.align = "middle";
        swfobject.embedSWF(
            "<?php echo $swfstring; ?>", "flashContent",
            "100%", "100%",
            swfVersionStr, xiSwfUrlStr,
            flashvars, params, attributes);
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    </script>
</head>
<body>
<div id="flashContent">
</div>

<noscript>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%" id="myFlash_ob">
        <param name="movie" value="<?php echo $swfstring; ?>" />
        <param name="quality" value="high" />
        <param name="bgcolor" value="#000000" />
        <param name="allowScriptAccess" value="always" />
        <param name="allowFullScreen" value="true" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="<?php echo $swfstring; ?>" width="100%" height="100%">
            <param name="quality" value="high" />
            <param name="bgcolor" value="#000000" />
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="true" />
            <!--<![endif]-->
        </object>
</noscript>
</body>
</html>

