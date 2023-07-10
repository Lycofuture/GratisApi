<?php
//require '../../need.php';
class 名片赞{
    public static function getSign($param, $key){
        $signPars = "";
        ksort($param);
        foreach ($param as $k => $v) {
            if ("sign" != $k && "" !== $v) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars = trim($signPars, '&');
        $signPars .= $key;
        $sign = md5($signPars);
        return $sign;
    }
    public static function getcard($Uin,$num){
        if($num < 2001 && $num > 100){
            $gid = 3703;//引流
        }else{
            $gid = 3702;//鸭架
        }
        if($num < 2501){
            $gid = 1661624;
        }else{
            $gid = 224850;
        }
        $params = [
            'api_token' => 171230,//Token_id
            'timestamp' => time(),//时间戳
            'gid'=>$gid
        ];
        $key = 'e091170974ceeb28890d3b8aec894bb5';//密钥
        $key = '74492bd2e7284cb1c07090c170c1af63';//密钥
        $url = 'http://54kami.cn.api.94sq.cn/api/order';
        $url = 'http://www.tnctc.cn.api.yilesup.net/api/order';
        $url = 'http://hlsq8.com.api.yilesup.net/api/order';
        if($num && is_numEric($num)){
            $params['num'] = $num;
        }
        if(preg_match('/[1-9][0-9]{5,11}/',$Uin)){
            $params['value1'] = $Uin;
        }else{
            return false;
        }
        $sign = self::getSign($params, $key);
        $params['sign'] = $sign;
        $Data = json_decode(need::teacher_curl($url,['post'=>$params, 'rtime'=>10]),true);
        if($Data['status'] == 0){
            $a = self::put('./cache/pay.txt',$Data['rmb']);
            return $Data;
        }else{
            $a = self::put($Uin, $Data['message']);
            return false;
        }
    }
    public static function put($Uin, $Msg = null, $format = false){
        if(!$format){
            file_put_contents($Uin,$Msg);
            return true;
        }else{
            $file = @file_get_contents($Uin);
            @unlink($Uin);
            return $file;
        }
    }
}
//echo 名片赞::getcard(2579988698,100);