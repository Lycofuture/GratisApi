<?php

/* Start */
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(111); // 调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

$Skey = @$_REQUEST['Skey'];//Skey

$pskey = @$_REQUEST['pskey'];//pskey

$uin = @$_REQUEST['uin'];//收款的人

$QQ = @$_REQUEST['QQ'];//提供Skey的账号

$Type = @$_REQUEST['type'];

$Select = @$_REQUEST['Select'];

$Money = @$_REQUEST['Money'];

$Title = @$_REQUEST['Title']?:'活动账单';

$Group = @$_REQUEST['Group'];

if(empty($Skey)){

    if($Type == 'text'){

        exit('请输入Skey');
        
    }else{
    
        echo need::json(array('code'=>-1,'text'=>'请输入Skey'));
    
        exit();
        
    }

}

if(empty($pskey)){

    if($Type == 'text'){

        exit('请输入pskey');
        
    }else{
    
        echo need::json(array('code'=>-2,'text'=>'pskey空白'));
        
        exit();
        
    }

}

if(!preg_match('/[1-9][0-9]{5,10}/',$QQ)){

    if($Type == 'text'){

        exit('请正确的输入提供Skey的账号');
        
    }else{
    
        echo need::json(array('code'=>-3,'text'=>'请正确的输入提供Skey的账号'));
        
        exit();
        
    }
    
}

if(!preg_match('/[1-9][0-9]{5,10}/',$uin)){

    if($Type == 'text'){

        exit('请正确的输入需要付款的账号');
        
    }else{
    
        echo need::json(array('code'=>-4,'text'=>'请正确的输入付款的账号'));
        
        exit();
        
    }
    
}

if(!preg_match('/[1-9][0-9]{5,10}/',$Group)){

    if($Type == 'text'){

        exit('请输入正确的群号');
        
    }else{
    
        echo need::json(array('code'=>-5,'text'=>'请输入正确的群号'));
        
        exit();
        
    }
    
}

if($Money > 500){

    if($Type == 'text'){

        exit('最高额度500块');
        
    }else{
    
        echo need::json(array('code'=>-12,'text'=>'最高额度500块'));
        
        exit();
        
    }
    
}
if(!preg_match('/^[0-9]+.*[0-9]+$/',$Money) && !preg_match('/^[0-9]+$/',$Money) && $Select == 1){
    Switch($type){
        case 'text':
        need::send('请输入正确金额','text');
        exit();
        break;
        default:
        need::send(Array('code'=>-12,'text'=>'请输入正确金额'));
        exit();
        break;
    }
}
/*
if(!preg_match('/^[0-9]+$/',$Money) and $Select == 1){
    Switch($type){
        case 'text':
        need::send('请输入正确金额','text');
        exit();
        break;
        default:
        need::send(Array('code'=>-12,'text'=>'请输入正确金额'));
        exit();
        break;
    }
}
*/
$Mone = (($Money * 200) % 2);

if($Mone === 1){
    
        if($Type == 'text'){
        
            echo '请输入双数金额';
            
            exit;
            
        }else{
    
            echo need::json(array('code'=>-10,'text'=>'请输入双数金额'));
        
            exit();
            
         }
        
    }else{
    

    if($Select == '1'){

        $Cookie = 'uin=o'.$QQ.'; p_uin=o'.$QQ.'; skey='.$Skey.'; p_skey='.$pskey;
        
        $Referer = 'https://mqq.tenpay.com/mqq/groupreceipts/index.shtml?uin='.$Group.'&type=4&_wv=1027&_wvx=4&from=appstore_aio';
        
        $ua = 'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080';
        
        $url = 'https://mqq.tenpay.com/cgi-bin/qcollect/qpay_collect_create.cgi?type=1&memo='.$Title.'&amount='.intval($Money *200).'&payer_list=[{"uin":"'.$QQ.'","amount":"'.intval($Money * 100).'"},{"uin":"'.$uin.'","amount":"'.intval($Money * 100).'"}]&num=2&recv_type=1&group_id='.$Group.'&uin='.$QQ.'&pskey='.$pskey.'&skey='. $Skey.'&skey_type=2';
        //https://mqq.tenpay.com/cgi-bin/qcollect/qpay_collect_create.cgi?type=1&memo=%E6%B4%BB%E5%8A%A8%E8%B4%A6%E5%8D%95&amount=2&payer_list=%5B%7B%22uin%22%3A%222354452553%22%2C%22amount%22%3A1%7D%2C%7B%22uin%22%3A%221757751945%22%2C%22amount%22%3A1%7D%5D&num=2&recv_type=1&group_id=820323177&uin=2354452553&pskey=3CA3wyUgLEGgcHEjWAjINqufvvg1jwW8WbunUsRJvcE_&skey=3CA3wyUgLEGgcHEjWAjINqufvvg1jwW8WbunUsRJvcE_&skey_type=2
        $data = json_decode(need::teacher_curl($url,[
         
            'refer'=>$Referer,
            'ua'=>$ua,
            'cookie'=>$Cookie
            
        ]),true);
            
            $code = $data['retcode'];
            
            if($code == '0'){
            
                if($Type == 'text'){
                
                    echo '订单已发起';
                    
                    echo "\n";
                    echo '订单号：'.$data['collection_no']."\n";
                    echo '金额：'.$Money."\n";
                    echo '发起时间：'.date('Y-m-d G:i');
                    
                 }else{
            
                    $array = array('code'=>1,'text'=>'发起成功','data'=>array('Money'=>$Money,'pay_id'=>$data['collection_no'],'Time'=>date('Y-m-d G:i'),'uin'=>$QQ,'pay_uin'=>$uin));
                
                    echo need::json($array);
                
                    exit();
                    
                }
                
             }else{
             
                 echo need::json(array('code'=>-6,'text'=>$data['retmsg']));
                 
                 exit();
                 
             }
             
         }else
         
         $pay_id = @$_REQUEST['payid'];
         
         if($Select == '2'){
         
             if(empty($pay_id)){
             
                 if($Type == 'text'){
                 
                     echo '请输入正确的订单号';
                     
                     exit;
                     
                 }else{
               
                     echo need::json(array('code'=>-7,'text'=>'请输入正确的订单号'));
                 
                     exit();
                     
                 }
                 
             }else{
             
            $URL = 'https://mqq.tenpay.com/cgi-bin/qcollect/qpay_collect_detail.cgi?collection_no='.$pay_id.'&uin='.$QQ.'&pskey='.$pskey.'&skey='.$Pskey.'&skey_type=2';
            $Cookie = 'uin=o'.$QQ.'; p_uin=o'.$QQ.'; skey='.$Skey.'; p_skey='.$pskey;
        
            $Referer = 'https://mqq.tenpay.com/mqq/groupreceipts/detail.shtml?_wv=1027&_wvx=4&collectionno='.$Group;
        
            $ua = 'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080';
            
            $data = json_decode(need::teacher_curl($URL,[
                
                'refer'=>$Referer,
                'ua'=>$ua,
                'cookie'=>$Cookie
                
            ]),true);
  //          print_r($data);exit();
            
            $Mon = $data['payer_list'][1]['state'];
            
            if($Mon != '0'){
            
                if($Type == 'text'){
                
                    echo '支付成功'."\n";
                    echo '支付金额：'.($data['payer_list'][1]['amount'] / 100)."\n";
                    echo '订单号：'.$pay_id;
                    
                    exit;
                    
                }else{
                
                    echo need::json(array('code'=>1,'text'=>'支付成功','data'=>array('Money'=>($data['payer_list'][1]['amount'] / 100),'pay_id'=>$pay_id,'Time'=>date('Y-m-d G:i'))));
                
                    exit();
                }
                
            }else{
            
                if($Type == 'text'){
                
                    echo '未支付';
                    
                    exit;
                    
                }else{
            
                    echo need::json(array('code'=>-8,'text'=>'未支付'));
                    
                    exit;
                    
                }
                
            }
            
        }
        
    }else
    
    if($Select == '3'){
    
        $URL = 'https://mqq.tenpay.com/cgi-bin/qcollect/qpay_collect_close.cgi?collection_no='.$pay_id.'&uin='.$QQ.'&pskey='.$pskey.'&skey='.$Skey.'&skey_type=2';
        
        $Cookie = 'uin=o'.$QQ.'; p_uin=o'.$QQ.'; skey='.$Skey.'; p_skey='.$pskey;
        
        $Referer = 'https://mqq.tenpay.com/mqq/groupreceipts/detail.shtml?_wv=1027&_wvx=4&collectionno='.$Group;
        
        $ua = 'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36 V1_AND_SQ_8.8.3_1818_YYB_D A_8080300 QQ/8.8.3.5470 NetType/4G WebP/0.4.1 Pixel/1080';
            
        $data = json_decode(need::teacher_curl($URL,[
                
            'refer'=>$Referer,
            'ua'=>$ua,
            'cookie'=>$Cookie
                
        ]),true);
        
        if($data['retcode'] == '0'){
        
            if($Type == 'text'){
            
                echo '关闭成功';
                
                echo "\n订单号：".$pay_id;
                
                exit;
                
            }else{
        
                echo need::json(array('code'=>1,'text'=>'关闭成功','data'=>array('pay_id'=>$pay_id)));
            
                exit();
                
            }
            
        }else{
        
            if($Type == 'text'){
            
                echo '关闭失败,未知错误,请重试';
                
                exit;
                
            }else{
        
                echo need::json(array('code'=>-9,'text'=>'关闭失败,未知错误,请重试'));
            
                exit();
                
            }
        
        }
        
    }else{

    if($Type == 'text'){

        echo '1为添加付款,2为查询付款,3为关闭付款';
    
        exit;
    
    }else{

        echo need::json(array('code'=>-11,'text'=>'1为添加付款,2为查询付款,3为关闭付款'));
    
        exit();
    
    }
    
}

}


