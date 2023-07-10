<?php
class qqbotTopSdk
{
    public function Api_QQBOT($functionName, $paramsArr)
    {
        $url = "http://127.0.0.1:8081/MyQQHTTPAPI";
        $token = 'token';
        foreach ($paramsArr as $key => $value) {
            $params['c' . ($key + 1)] = $value;
        }
        $return = array(
            "function" => $functionName,
            "token" => $token,
            "params" => $params,
        );
        $output = $this->curl_post($url, json_encode($return));
        return json_decode($output, true);
    }

    public function curl_post($url, $post)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
    //返回1（#消息处理_继续），其他插件可继续处理此条消息
    //返回2（#消息处理_拦截），拦截信息，此条消息不再分发给其他插件，其他插件将不能再处理
    public function ret($status)
    {
        $return = ["status" => $status];
        return json_encode($return);
    }
    //自己的function，在插件里面调用：$MQ_Api->checkContent($content)
    public function checkBan($contac)
    {
        $arr = [
            '[file,',
            '[flashPic',
            '[Audio,',
            '[@',
            '[Reply,',
            '点歌',
            '[菜汪]',
        ];
        foreach ($arr as $key => $value) {
            if (strpos($contac, $value) !== false) {
                return true;
            }
        }
        return false;
    }
    public function checkContent($content)
    {
        $back = file_get_contents('https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=&client_secret=');
        $arr = json_decode($back, true);
        $access_token = $arr['access_token'];
        $str = "content={$content}";
        $url = 'https://aip.baidubce.com/rest/2.0/antispam/v2/spam?access_token=' . $access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        $data = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($data, true);
        if ($arr['result']['spam'] == '1') {
            return false;
        } else {
            return true;
        }
    }
}
class PluginManager
{
    private $_listeners = array();
    public function __construct()
    {
        $plugins = array();
        $plugins = $this->get_active_plugins();
        if ($plugins) {
            foreach ($plugins as $plugin) {
                $file_name = $plugin['name'];
                if ($this->plugin_is_on($file_name) == false) {
                    continue;
                }
                $file_path = "Plugins/{$file_name}/Actions.php";
                if (@file_exists($file_path)) {
                    include_once $file_path;
                    $plugin_name = $plugin['name'];
                    $class = $plugin_name . '_Actions';
                    if (class_exists($class)) {
                        new $class($this);
                    }
                }
            }
        }
    }
    public function plugin_is_on($filename)
    {
        $file_data = file_get_contents("Plugins/{$filename}/config.json");
        $file_data_json = json_decode($file_data);
        $plugin_status = $file_data_json->plugin_set->status;
        if ($plugin_status == true) {
            return true;
        } else {
            return false;
        }
    }
    public function get_active_plugins()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Plugins';
        $filesnames = scandir($dir);
        $plugins = array();
        foreach ($filesnames as $filename) {
            if ($filename != '.' && $filename != '..') {
                $plugins[] = array(
                    'name' => $filename,
                    'directory' => $dir,
                );
            }
        }
        return $plugins;
    }
    public function register($hook, &$reference, $method)
    {
        $key = get_class($reference) . '->' . $method;
        $this->_listeners[$hook][$key] = array(&$reference, $method);
    }
    public function trigger($hook, $data = '')
    {
        $result = '';
        if (isset($this->_listeners[$hook]) && is_array($this->_listeners[$hook]) && count($this->_listeners[$hook]) > 0) {
            foreach ($this->_listeners[$hook] as $listener) {
                $class = &$listener[0];
                $method = $listener[1];
                if (method_exists($class, $method)) {
                    $result .= $class->$method($data);
                }
            }
        }
        return $result;
    }
}
