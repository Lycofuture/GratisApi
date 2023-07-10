<?php
header('content-type:application/json');

require './curl.php';

print_r($_SERVER['HTTP_USER_AGENT']);