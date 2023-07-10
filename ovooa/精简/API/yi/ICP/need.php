<?php

 class Bkn{
public function bkna($s) {
    $hash = 5381;
    for ($i = 0, $len = strlen($s); $i < $len; ++$i){
        $hash +=($hash << 5) + $this->charCodeAt($s, $i);
    }
    return $hash & 2147483647;
}
public function charCodeAt($str, $index){
    $char = mb_substr($str, $index, 1, 'UTF-8');
    $value = null;
    if (mb_check_encoding($char, 'UTF-8')){
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        $value = hexdec(bin2hex($ret));
    }
    return $value;
 }
}
$bkn = new getBkn;
set_time_limit(0);

function jsona($arr){
header('Content-type: application/json');
return stripslashes(json_encode($arr,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function vipgtka($skey){
    $salt=5381;
    $md5key='tencentQQVIP123443safde&!%^%1282';
    $hash=array();
    $hash[]=$salt<<5;
    for ($i=0; $i<strlen($skey); ++$i) {
       $acode=ord(substr($skey,$i,1));
       $hash[]=($salt<<5)+$acode;
       $salt=$acode;
    }
    $md5str=md5(join('',$hash).$md5key);
    return $md5str;
}

class gtk{
public function gtka($Skey)
{
    $hash = 5381;
    for ($i = 0, $len = strlen($Skey); $i < $len; ++$i)
    {
        $hash += ($hash << 5) + $this->charCodeAt($Skey, $i);
    }
    return $hash & 0x7fffffff;
}
public function charCodeAt($str, $index){
    $char = mb_substr($str, $index, 1, 'UTF-8');
    $value = null;
    if (mb_check_encoding($char, 'UTF-8')){
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        $value = hexdec(bin2hex($ret));
    }
    return $value;
 }
}
$gtk = new GETgtk;
set_time_limit(0);

    function cond() {
list($t1, $t2) = explode(' ', microtime());
return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);}

?>