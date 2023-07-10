<?php
exec('netstat -anpo | grep "php-cgi"| wc -l', $exec);
if($exec[0] > 200){
    exec('service php-fpm-74 restart', $return);
    print_r($return);
}else{
    echo 'false';
}
die();
