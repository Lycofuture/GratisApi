<?
header('content-type: application/json');
$url = 'http://127.0.0.1:8080/yiyan.api?type=text&a=1';
preg_match('~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~i', $url, $url);
var_dump($url);