<?php
require './config.php';
if($_REQUEST['new']!=1){
    die('');
}
$code = $db->query('TRUNCATE `mxgapi_spider`');
$code = $db->query('TRUNCATE `mxgapi_access`');
if($code){
    die('成功');
}else{
    die('失败');
}
