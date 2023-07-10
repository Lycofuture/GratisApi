<?php
error_reporting(0);
$mod = $_GET['mod'];
$app_name = $_GET['app_name'];
if (!$mod) {
    return;
}
if ($mod == "plugin_switch" && $app_name) {
    plugin_switch($app_name);
}
function plugin_switch($plugin_name)
{
    $file_path = "Plugins/{$plugin_name}/config.json";
    $file_data = file_get_contents($file_path);
    $file_data_json = json_decode($file_data);
    $plugin_status = $file_data_json->plugin_set->status;
    if ($plugin_status == true) {
        $file_data = str_replace("true", "false", $file_data);
    } else {
        $file_data = str_replace("false", "true", $file_data);
    }
    echo $file_data, file_put_contents($file_path, $file_data);
}