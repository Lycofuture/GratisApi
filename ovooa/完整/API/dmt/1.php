<?php
require '../../curl.php';
for($i = 793 ; $i < 4093 ; $i++){
    $String = need::teacher_curl('https://cdn.jsdelivr.net/gh/ssrss/img/'.$i.'.jpg');
    file_put_Contents('./image/'.$i.'.jpg',$String);
    unset($String);
    //break;
}
echo 1;