<?php
header('content-Type:application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(96); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
/* End */
$type = @$_REQUEST["type"];
$data = need::teacher_curl('http://43.250.238.179:9090/showData?callback=');
$data = json_decode($data,true);
//print_r($data);exit;
$data = $data['data'];
$times = $data['times'];//时间
$total = $data['gntotal'];//总确诊数量
$death = $data['deathtotal'];//总死亡数量,向每位死者敬礼;
$sus = $data['sustotal'];//疑似数量
$cure = $data['curetotal'];//总治愈数量
$jwsr = $data['jwsrNum'];//境外输入
$asymptom = $data['asymptomNum'];//无症感染者
$econ = $data['econNum'];//现有确诊
$hecon = $data['heconNum'];//现有重症
/*
$picture = need::teacher_curl('http://sa.sogou.com/new-weball/page/sgs/epidemic',[
    'refer'=>' ',
    'ua'=>'Mozilla/5.0 (Linux; Android 10; PCLM10 Build/QKQ1.191021.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.92 Mobile Safari/537.36'
]);
preg_match('/"mapUrl":"(.*?)","chartUrl"/',$picture,$picture);
$picture = str_replace('\\','',$picture[1]);*/
$picture = 'http://ovooa.com/API/yiqing/1.jpg';
$msg = @$_REQUEST['msg'];
if(!$msg){
    Switch($type){
        case 'text':
        need::send("±img=".$picture."±\n".$times."\r累计确诊：".$total."\r累计死亡：".$death."\r现有疑似：".$sus."\r累计治愈：".$cure."\r现有无症感染：".$asymptom."\r境外输入：".$jwsr."\r现有确诊：".$econ."\r现有重症：".$hecon,'text');
        break;
        default:
        need::send(array("code"=>"1","text"=>"获取成功",'data'=>Array(
        'time'=>$times,
        'total'=>$total,
        'death'=>$death,
        'suspected'=>$sus,
        'cure'=>$cure,
        'asymptom'=>$asymptom,
        'overseas'=>$jwsr,
        'econ'=>$econ,
        'server'=>$hecon,
        "picture"=>$picture
        )),'json');
        break;
    }
}else{
    $data = $data['list'];
    foreach($data as $k=>$v){
        if(@preg_match('/'.$v['name'].'/',$msg)){
        /*判断是否有这个地区*/
            if(empty($v['city'])){
            /*判断副地区是否为空*/
                Switch($type){
                    case 'text':
                    echo '±img='.$picture.'±';
                    echo "\n";
                    echo $times;
                    echo "\n";
                    echo '查询地址：';
                    echo $v['name'];
                    echo "\n";
                    echo '累计确诊：';
                    echo $v['value'];
                    echo "\n";
                    echo '现有疑似：';
                    echo $v['susNum'];
                    echo "\n";
                    echo '累计死亡：';
                    echo $v['deathNum'];
                    echo "\n";
                    echo '累计治愈：';
                    echo $v['cureNum'];
                    echo "\n";
                    echo '新增确诊：';
                    echo $v['valueAdd'];
                    echo "\n";
                    echo '现存确诊：';
                    echo $v['econNum'];
                    echo "\n";
                    echo '现有无证感染：'.$v['asymptomNum'];
                    exit();
                    break;
                    default:
                    $Array = Array('code'=>1,'text'=>'查询成功','data'=>Array(
                        'time'=>$times,
                        'name'=>$v['name'],
                        'total'=>$v['value'],
                        'suspected'=>$v['susNum'],
                        'death'=>$v['deathNum'],
                        'cure'=>$v['cureNum'],
                        'totaladd'=>$v['valueAdd'],
                        'econ'=>$v['econNum'],
                        'asymptom'=>$v['asymptomNum']
                    ));
                    need::send($Array,'json');
                    break;
                }
            }else{
            /*副地区不为空*/
                foreach($v['city'] as $i=>$e){
                    if(@preg_match('/'.$e['name'].'/',$msg)){
                    /*判断是否可以正则到副地区*/
                        Switch($type){
                            case 'text':
                            echo '±img='.$picture.'±';
                            echo "\n";
                            echo $times;
                            echo "\n";
                            echo '查询地址：';
                            echo $e['name'];
                            echo "\n";
                            echo '累计确诊：';
                            echo $e['conNum'];
                            echo "\n";
                            echo '现有疑似：';
                            echo $e['susNum'];
                            echo "\n";
                            echo '累计死亡：';
                            echo $e['deathNum'];
                            echo "\n";
                            echo '累计治愈：';
                            echo $e['cureNum'];
                            echo "\n";
                            echo '新增确诊：';
                            echo $e['conNumAdd'];
                            echo "\n";
                            echo '现存确诊：';
                            echo $e['econNum'];
                            echo "\n";
                            echo '现有无证感染：'.$e['asymptomNum'];
                            exit();
                            break;
                            default:
                            $Array = Array('code'=>1,'text'=>'查询成功','data'=>Array(
                                'time'=>$times,
                                'name'=>$e['name'],
                                'total'=>$e['conNum'],
                                'suspected'=>$e['susNum'],
                                'death'=>$e['deathNum'],
                                'cure'=>$e['cureNum'],
                                'totaladd'=>$e['conNumAdd'],
                                'econ'=>$e['econNum'],
                                'asymptom'=>$e['asymptomNum'],
                                'picture'=>$picture
                            ));
                           need::send($Array,'json');
                            break;
                        }
                    }
                }
                /*加一层输出主城区*/
                Switch($type){
                    case 'text':
                    echo '±img='.$picture.'±';
                    echo "\n";
                    echo $times;
                    echo "\n";
                    echo '查询地址：';
                    echo $v['name'];
                    echo "\n";
                    echo '累计确诊：';
                    echo $v['value'];
                    echo "\n";
                    echo '现有疑似：';
                    echo $v['susNum'];
                    echo "\n";
                    echo '累计死亡：';
                    echo $v['deathNum'];
                    echo "\n";
                    echo '累计治愈：';
                    echo $v['cureNum'];
                    echo "\n";
                    echo '新增确诊：';
                    echo $v['valueAdd'];
                    echo "\n";
                    echo '现存确诊：';
                    echo $v['econNum'];
                    echo "\n";
                    echo '现有无证感染：'.$v['asymptomNum'];
                    exit();
                    break;
                    default:
                    $Array = Array('code'=>1,'text'=>'查询成功','data'=>Array(
                        'time'=>$times,
                        'name'=>$v['name'],
                        'total'=>$v['value'],
                        'suspected'=>$v['susNum'],
                        'death'=>$v['deathNum'],
                        'cure'=>$v['cureNum'],
                        'totaladd'=>$v['valueAdd'],
                        'econ'=>$v['econNum'],
                        'asymptom'=>$v['asymptomNum']
                    ));
                    need::send($Array,'json');
                    break;
                }
            }
        }else{
            /* 如果没有 拿出所有副地区 */
            foreach($data as $v){
                $Array[] = $v['city'];
            }
            foreach($Array as $v){
                foreach($v as $value){
                    if(@preg_match('/'.$value['name'].'/',$msg)){
                        Switch($type){
                            case 'text':
                            echo '±img='.$picture.'±';
                            echo "\n";
                            echo $times;
                            echo "\n";
                            echo '查询地址：';
                            echo $value['name'];
                            echo "\n";
                            echo '累计确诊：';
                            echo $value['conNum'];
                            echo "\n";
                            echo '现有疑似：';
                            echo $value['susNum'];
                            echo "\n";
                            echo '累计死亡：';
                            echo $value['deathNum'];
                            echo "\n";
                            echo '累计治愈：';
                            echo $value['cureNum'];
                            echo "\n";
                            echo '新增确诊：';
                            echo $value['conNumAdd'];
                            echo "\n";
                            echo '现存确诊：';
                            echo $value['econNum'];
                            echo "\n";
                            echo '现有无证感染：'.$value['asymptomNum'];
                            exit();
                            break;
                            default:
                            $Array = Array('code'=>1,'text'=>'查询成功','data'=>Array(
                                'time'=>$times,
                                'name'=>$value['name'],
                                'total'=>$value['conNum'],
                                'suspected'=>$value['susNum'],
                                'death'=>$value['deathNum'],
                                'cure'=>$value['cureNum'],
                                'totaladd'=>$value['conNumAdd'],
                                'econ'=>$value['econNum'],
                                'asymptom'=>$value['asymptomNum'],
                                'picture'=>$picture
                            ));
                           need::send($Array,'json');
                            break;
                        }
                    }
                }
            }
        }
    }
    /* 如果上面没有回复就输出全国 */
    Switch($type){
        case 'text':
        need::send("±img=".$picture."±\n".$times."\r累计确诊：".$total."\r累计死亡：".$death."\r现有疑似：".$sus."\r累计治愈：".$cure."\r现有无症感染：".$asymptom."\r境外输入：".$jwsr."\r现有确诊：".$econ."\r现有重症：".$hecon,'text');
        break;
        default:
        need::send(array("code"=>"1","text"=>"获取成功",'data'=>Array(
        'time'=>$times,
        'total'=>$total,
        'death'=>$death,
        'suspected'=>$sus,
        'cure'=>$cure,
        'asymptom'=>$asymptom,
        'overseas'=>$jwsr,
        'econ'=>$econ,
        'server'=>$hecon,
        "picture"=>$picture
        )),'json');
        break;
    }
}