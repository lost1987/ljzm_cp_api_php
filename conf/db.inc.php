<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lost
 * Date: 13-3-6
 * Time: 上午11:42
 * To change this template use File | Settings | File Templates.
 */

//账号数据库,分发数据库
define('DB_HOST','221.228.196.138');
define('DB_NAME','mmo2d_admin');
define('DB_USER','root');
define('DB_PWD','li/5210270');
define('DB_TYPE','Mysql');
define('DB_PORT','3306');
define('DB_PREFIX','ljzm_');
define('DB_BASE','mmo2d_baseljzm');
define('TB_SERVERS',DB_PREFIX.'servers');
define('TB_BUISSNESSER',DB_PREFIX.'buissnesser');
define('TB_APIKEY',DB_PREFIX.'apikey');
define('TB_VERSION',DB_PREFIX.'versions');
define('TB_CVERSION',DB_PREFIX.'client_versions');
define('TB_SERIES',DB_PREFIX.'series');
define('TB_BACKUP',DB_PREFIX.'backlog');
define('TB_OPERATIONLOG',DB_PREFIX.'operationlog');