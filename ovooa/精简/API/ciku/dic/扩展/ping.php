<?php

function ping_time($ip) {
  $ping_cmd = "ping -c 3 -w 5 " . $ip;
  exec($ping_cmd, $info);
  $ping_time_line = end($info);
   
  $ping_time = explode("=", $ping_time_line)[1];
  $ping_time_min = explode("/", $ping_time)[0] / 1000.0;
  $ping_time_avg = explode("/", $ping_time)[1] / 1000.0;
  $ping_time_max = explode("/", $ping_time)[2] / 1000.0;
   
  $result = array();
  $result['ping_min'] = $ping_time_min;
  $result['ping_avg'] = $ping_time_avg;
  $result['ping_max'] = $ping_time_max;
   
  return $result;
}