<?php
header('content-type:application/json');
require ("../function.php"); // 引入函数文件
addApiAccess(140); // 调用统计函数
addAccess();//调用统计函数*/
require '../../curl.php';
require '../../need.php';
//$msg = @$_REQUEST['idiom'];//成语
$format = @$_REQUEST['format'];//模式
$type = @$_REQUEST['type'];//输出格式
$idiom = preg_quote(@$_REQUEST['idiom'],'/');//成语
$uin = @$_REQUEST['uin'];//用户唯一识别码
$load_url = 'localhost';//数据库链接
$load_user = '123456789';//用户账户
$load_password = '123456789';//密码
$load_name = '123456789';//用户名
$load_Part = '3306';//端口一般为3306不用动
$MySQL = new MySQLi($load_url,$load_user,$load_password,$load_name,$load_Part);//创建数据库链接
need::delfile(__DIR__.'/cache/', 1440);
need::delfile(__DIR__.'/s/', 1440);
if($MySQL->connect_error){
    Switch($type){
        case 'text':
        die('connect error:'.$MySQL->connect_errno);//连接失败
        break;
        default:
        need::send(array('code'=>-502,'text'=>'connect error:'.$MySQL->connect_errno));
        break;
    }
}else{
    $MySQL->query("set names utf8mb4");  // 连接成功并设置数据库字符集
}
/* 查询成语 */
if($format == 1){
    if(empty($idiom)){
        Switch($type){
            case 'text':
            need::send('请输入需要查询的成语','text');
            break;
            default:
            need::send(array('code'=>-1,'text'=>'请输入需要查询的成语'));
            break;
        }
    }
    $query = "SELECT * FROM `idiom` WHERE `Text` = '{$idiom}'";
    $data = $MySQL->query($query);
    $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
    if(empty($data)){
        Switch($type){
            case 'text':
            need::send('未查询到该成语相关信息','text');
            break;
            default:
            need::send(array('code'=>-2,'text'=>'未查询到该成语相关信息'));
            break;
        }
    }
    $text = $data[0]['content'];//解释
    $pinyin = $data[0]['pinyin'];//拼音
    $chu = $data[0]['from_Text'];//出自
    Switch($type){
        case 'text':
        need::send($idiom."\n拼音：{$pinyin}\n成语详解：{$text}",'text');
        break;
        default:
        need::send(array('code'=>1,'text'=>'获取成功','data'=>array('Text'=>$idiom,'Spell'=>$pinyin,'Content'=>$text)));
        break;
    }
}else
/* 接龙 */
if($format == 2){
    if(empty($uin)){
        Switch($type){
            case 'text':
            need::send('请输入用户唯一识别码','text');
            break;
            default:
            need::send(array('code'=>-3,'text'=>'请输入用户唯一识别码'));
            break;
        }
    }else{
        /* 指定成语 */
        if(!empty($idiom)){
            $idiom = $idiom;
        }else{
            /* 随机成语 */
            $from = 'SELECT * FROM `idiom`';//查询代码
            $select = $MySQL->query($from);//查询数据库
            $data = $select->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
            if($data){
                $rand = array_rand($data,1);//随机读取一个数组Key
                $array = $data[$rand];
                $idiom = $array['Text'];
            }else{
                Switch($type){
                    case 'text':
                    need::send('未知错误,请重试','text');
                    break;
                    default:
                    need::send(array('code'=>-15,'text'=>'未知错误,请重试'));
                    break;
                }
            }
        }
        /* 成语判断 */
        $file_name = './cache/'.md5($uin);
        $file = @file_get_contents($file_name);
        if(!empty($file)){
            if(preg_match('/[\n]'.$idiom.'/',$file)){
              //  print_r($s);
                Switch($type){
                    case 'text':
                    need::send('该成语已经使用过了哦','text');
                    break;
                    default:
                    need::send(array('code'=>-4,'text'=>'该成语已经使用过了哦'));
                    break;
                }
            }
        }
        $query = "SELECT * FROM `idiom` WHERE `Text` = '{$idiom}'";
        $data = $MySQL->query($query);
        $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
        if(empty($data)){
            $datae = need::teacher_curl('https://hanyu.baidu.com/s?wd='.$idiom.'&from=poem',[
                'Header'=>[
                    'Host: hanyu.baidu.com',
                    'Connection: keep-alive',
                    'Cache-Control: max-age=0',
                    'Upgrade-Insecure-Requests: 1',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                    'dnt: 1',
                    'X-Requested-With: mark.via',
                    'Sec-Fetch-Site: none',
                    'Sec-Fetch-Mode: navigate',
                    'Sec-Fetch-User: ?1',
                    'Sec-Fetch-Dest: document',
                    'Accept-Encoding: gzip, deflate',
                    'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
                ],
                'refer'=>'https://hanyu.baidu.com/chengyu',
                'cookie'=>'BAIDUID=D8B1702731F01D28C6556916C4B7007B:FG=1; delPer=0; __yjs_duid=1_9971eae90b3518ed16df31fe3631d3331631762039401; BIDUPSID=D8B1702731F01D28C6556916C4B7007B; BDRCVFR[5GQZCjFg8mf]=mk3SLVN4HKm; BAIDUID_BFESS=D8B1702731F01D28C6556916C4B7007B:FG=1; BDRCVFR[xdVWUXtXAam]=mk3SLVN4HKm; rsv_i=25f9d3qzrKIg4xPO1Qt%2BFpRzCoBZe4106%2F96nzMIwJVS1UA5V%2FiiY71uL4LeK%2Fh4kMbnyUT3701Jor1sDcZZ0Y%2Fi54EEJCs; PSCBD=25%3A1_49%3A1_24%3A1_22%3A1; SE_LAUNCH=5%3A1633921473__49%3A27232025__25%3A27232833_24%3A27232839_22%3A27232859; H_WISE_SIDS=110085_127969_176398_177371_178384_178646_179350_179380_179452_180276_181106_181118_181133_181484_181588_182253_182530_183030_183328_184010_184321_184560_184736_184794_184891_184895_185029_185241_185305_185519_185633_185654_185879_186015_186318_186411_186595_186635_186641_186844_186898_187042_187089_187292_187392_187432_187670_187726_187877_187928_187992_188039_188061_188182_188426_188594_188614_188670_188714_188730_188738_188805_188843_188873_188994_189086_189143_189257_189326_189391_189417; BA_HECTOR=8kaha1a4a50k05012n1gmar8u13; BDORZ=AE84CDB3A529C0F8A2B9DCDD1D18B695; ab_sr=1.0.1_ZmIzZmQ1YTY3OWE2ZTc2MzYxNjJlOGFhMTBiNjhmZDUxNWJhMTM2OTk1ODM4MzJiYTFkZDdiYmE1NWY2NmExYWE0ZTMwYzU5NjUzNzcyMWUzMWY2YTdlYmZjNDNmMzEwNDM1OGViMmFhMTI1NDRlMjJhZGI2MDhiYTcxYzExMmQ5Y2MwMmNjMmU5YTU3NWJkMjBiMjFiYTk2NjgzOTk2Zg=='
            ]);
            preg_match('/<script>window.basicInfo[\s]*=[\s]*([\s\S]*?);/',$datae,$e);
            /*
            preg_match('/title>(.*?)</i',$datae,$a);
            print_r($a);
            */
            $e_data = json_decode($e[1],true);//解释
            $e = $e_data['definition'];
            preg_match('/(.*?)##(.*)/',$e,$e);
            $e = $e[2];//解释
            preg_match('/<!-- 出处 -->([\s\S]*?)<!-- 例句 -->/',$datae,$source);
            preg_match('/<p>([\s\S]*?)<\/p>/',$source[1],$source);
            $source = trim($source[1])?:'暂无出处';//出处
            $spell = trim(str_replace(',',' ',need::teacher_curl('http://ovooa.com/API/pinyin/?msg='.$idiom.'&format=1&type=text&bol=,')));
            if(empty($data) && !empty($e)){
                $query = "INSERT INTO `idiom` (`id`, `Text`, `pinyin`, `content`, `from_Text`) VALUES (NULL, '{$idiom}', '{$spell}', '{$e}', '{$source}')";
                $code = $MySQL->query($query);
                /*echo $spell;exit;
                print_r($query);*/
                if(!$code){
                    Switch($type){
                        case 'text':
                        need::send('这可能不是一个成语','text');
                        break;
                        default:
                        need::send(array('code'=>-5,'text'=>'这可能不是一个成语1'));
                        break;
                    }
                }
            }
        }
        /* 接龙判断 */
        $query = "SELECT * FROM `idiom` WHERE `Text` = '{$idiom}'";
        $data = $MySQL->query($query);
        $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
        if(empty($data)){
            Switch($type){
                case 'text':
                need::send('这可能不是一个成语','text');
                break;
                default:
                need::send(array('code'=>-5,'text'=>'这可能不是一个成语'));
                break;
            }
        }
        $text = $data[0]['content'];//解释
        $pinyin = $data[0]['pinyin'];//拼音
        $chu = $data[0]['from_Text'];//出自
        $explode = explode(' ',$pinyin);
        $head = $explode[0];
        $tail = end($explode);
        //print_r($tail);exit();
        $f = @file_get_contents('./s/'.md5($uin));
        $f = trim($f);
        if(empty($f)){
            /* 尾音写入 */
            // $open = fopen('./s/'.md5($uin),'w');
            // fwrite($open,$tail);
            // fclose($open);
                file_put_contents(__DIR__.'/s/'.Md5($uin), $tail);
            /* 写入用过的成语 */
            // $open = fopen('./cache/'.md5($uin),'a');
            // fwrite($open,"\n{$idiom}");fclose($open);
            file_put_contents(__DIR__.'/cache/'.Md5($uin), PHP_EOL.$idiom, FILE_APPEND);
            Switch($type){
                case 'text':
                need::send('接龙开始！'."\n成语：{$idiom}\n拼音：{$pinyin}\n成语详解：{$text}",'text');
                break;
                default:
                need::send(array('code'=>1,'text'=>'接龙开始！','data'=>array('Text'=>$idiom,'Spell'=>$pinyin,'Content'=>$text)));
                break;
            }
        }else{
            /* 判断接龙首字音是否与之前尾音相同 */
            if($f == $head){
                /* 相同 */
                /* 写入成语 */
                file_put_contents(__DIR__.'/cache/'.Md5($uin), PHP_EOL.$idiom, FILE_APPEND);
                // $open = fopen('./cache/'.md5($uin),'a');
                // fwrite($open,"\n{$idiom}");
                // fclose($open);
                /* 写入尾音 */
                file_put_contents(__DIR__.'/s/'.Md5($uin), $tail);
                // $open = fopen('./s/'.md5($uin),'w');
                // fwrite($open,$tail);
                // fclose($open);
                Switch($type){
                    case 'text':
                    need::send('接龙成功！'."\n成语：{$idiom}\n拼音：{$pinyin}\n成语详解：{$text}",'text');
                    break;
                    default:
                    need::send(array('code'=>1,'text'=>'接龙成功','data'=>array('Text'=>$idiom,'Spell'=>$pinyin,'Content'=>$text)));
                    break;
                }
            }else{
                /* 不相同 */
                Switch($type){
                    case 'text':
                    need::send('请以'.$f.'开头的成语接龙','text');
                    break;
                    default:
                    need::send(array('code'=>-6,'text'=>'请以'.$f.'开头的成语接龙'));
                    break;
                }
            }
        }
    }
}else
if($format == 3 && $uin){
    if(!is_file('./cache/'.md5($uin))){
        Switch($type){
            case 'text':
            need::send('您并没有开始游戏哦','text');
            break;
            default:
            need::send(array('code'=>-18,'text'=>'您并没有开始游戏哦'));
            break;
        }
    }
    unlink('./cache/'.md5($uin));
    unlink('./s/'.md5($uin));
    Switch($type){
        case 'text':
        need::send('已结束成语接龙','text');
        break;
        default:
        need::send(array('code'=>1,'text'=>'已结束成语接龙'));
        break;
    }
}else
if($format == 4){
    $spell = @$_REQUEST['spell'];
    if(preg_match('/ā|á|ǎ|à|ō|ó|ǒ|ò|ē|é|ě|è|ī|í|ǐ|ì|ū|ú|ǔ|ù|ǖ|ǘ|ǚ|ǜ|ü/',$spell)){
        Switch($type){
            case 'text':
            need::send('请不要携带音标','text');
            break;
            default:
            need::send(array('code'=>-7,'text'=>'请不要携带音标'));
            break;
        }
    }
    if(mb_strlen($idiom) < 4 || mb_strlen($idiom) > 4){
        Switch($type){
            case 'text':
            need::send('请输入4个字的成语','text');
            break;
            default:
            need::send(array('code'=>-8,'text'=>'请输入4个字的成语'));
            break;
        }
    }
    $String = trim(str_replace(',',' ',need::teacher_curl('http://ovooa.com/API/pinyin/api.php?msg='.$idiom.'&format=1&type=text&bol=,')));
    if($spell != $String){
        Switch($type){
            case 'text':
            need::send('请输入正确的拼音','text');
            break;
            default:
            need::send(array('code'=>-14,'text'=>'请输入正确的拼音'.$String.$spell));
            break;
        }
    }
    $explode = explode(' ',$spell);
    if(count($explode) < 4 || count($explode) > 4){
        Switch($type){
            case 'text':
            need::send('请输入正确的拼音，每个字用空格隔开。例如：yi xin yi yi','text');
            break;
            default:
            need::send(array('code'=>-9,'text'=>'请输入正确的拼音，每个字用空格隔开。例如：yi xin yi yi'));
            break;
        }
    }
    $explain = @$_REQUEST['explain'];
    if(empty($explain)){
        Switch($type){
            case 'text':
            need::send('请输入成语解释','text');
            break;
            default:
            need::send(array('code'=>-10,'text'=>'请输入成语解释'));
            break;
        }
    }
    $from = @$_REQUEST['from'];
        if(empty($explain)){
        Switch($type){
            case 'text':
            need::send('请输入成语出处','text');
            break;
            default:
            need::send(array('code'=>-11,'text'=>'请输入成语出处'));
            break;
        }
    }
    $query = "SELECT * FROM `idiom` WHERE `Text` = '{$idiom}'";
    $data = $MySQL->query($query);
    $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
    if(!empty($data)){
        Switch($type){
            case 'text':
            need::send('添加失败,成语已存在','text');
            break;
            default:
            need::send(array('code'=>-12,'text'=>'添加失败,成语已存在'));
            break;
        }
    }
    $query = "INSERT INTO `idiom` (`id`, `Text`, `pinyin`, `content`, `from_Text`) VALUES (NULL, '{$idiom}', '{$spell}', '{$explain}', '{$from}')";
    $data = $MySQL->query($query);
    if($data){
        Switch($type){
            case 'text':
            need::send('添加成功','text');
            break;
            default:
            need::send(array('code'=>1,'text'=>'添加成功'));
            break;
        }
    }else{
        Switch($type){
            case 'text':
            need::send('添加失败','text');
            break;
            default:
            need::send(array('code'=>-13,'text'=>'添加失败'));
            break;
        }
    }
}else
if($format == 5){
    $spell = @$_REQUEST['spell'];
    $query = "SELECT * FROM `idiom` WHERE `Text` = '{$idiom}'";
    $data = $MySQL->query($query);
    $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
    if(!empty($data)){
        $explain = $data[0]['content'];
        $from = $data[0]['from_Text'];
        $id = $data[0]['id'];
        $query = "UPDATE `idiom` SET `Text` = '{$idiom}', `pinyin` = '{$spell}', `content` = '{$explain}', `from_Text` = '{$from}' WHERE `idiom`.`id` = {$id}";
        if(count(explode(' ',$spell)) == 4){
            $code = $MySQL->query($query);
            if($code){
                Switch($type){
                    case 'text':
                    need::send('修改成功','text');
                    break;
                    default:
                    need::send(array('code'=>1,'text'=>'修改成功'));
                    break;
                }
            }
        }else{
            Switch($type){
                case 'text':
                need::send('修改失败','text');
                break;
                default:
                need::send(array('code'=>-16,'text'=>'修改失败'));
                break;
            }
        }
    }
}else
if($format == 6){
    $md5 = md5($uin);
    $header = @trim(file_get_contents('./s/'.md5($uin)));
    $file = @file_get_contents('./cache/'.$md5);
    if(empty($file)){
        Switch($type){
            case 'text':
            need::send('您并没有开始游戏哦','text');
            break;
            default:
            need::send(array('code'=>-18,'text'=>'您并没有开始游戏哦'));
            break;
        }
    }
    $query = "SELECT * FROM `idiom` WHERE `pinyin` LIKE '{$header} %'";
    $data = $MySQL->query($query);
    $data = $data->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
    if(empty($data)){
        Switch($type){
            case 'text':
            $from = 'SELECT * FROM `idiom`';//查询代码
            $select = $MySQL->query($from);//查询数据库
            $data = $select->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
            if($data){
                $rand = array_rand($data,1);//随机读取一个数组Key
                $array = $data[$rand];
                $idiom = $array['Text'];
                need::send('太糟糕了,'.$header.'开头的成语,我也不会…我就帮你出一个吧… '.$idiom.' 这个怎么样？','text');
                break;
            }else{
                need::send('OMG,出错了','text');
                break;
            }
            default:
            $from = 'SELECT * FROM `idiom`';//查询代码
            $select = $MySQL->query($from);//查询数据库
            $data = $select->fetch_all(MYSQLI_ASSOC); // 从结果集中获取所有数据
            if($data){
                $rand = array_rand($data,1);//随机读取一个数组Key
                $array = $data[$rand];
                $idiom = $array['Text'];
                $spell = $array['pinyin'];
                $explode = explode(' ',$spell);
                $tail = end($explode);
                $open = fopen('./cache/'.$md5,'a');
                fwrite($open,"\n{$idiom}");
                fclose($open);
                /* 写入尾音 */
                $open = fopen('./s/'.$md5,'w');
                fwrite($open,$tail);
                fclose($open);
                need::send(array('code'=>-17,'text'=>'太糟糕了,'.$header.'开头的成语,我也不会…我就帮你出一个吧… '.$idiom.' 这个怎么样？'));
                break;
            }else{
                need::send(array('code'=>-17,'text'=>'OMG,出错了'));
                break;
            }
        }
    }else{
        foreach($data as $v){
            $text = $v['Text'];
            if(!strstr($file,"\n".$text)){
                $spell = $v['pinyin'];
                $content = $v['content'];
                $explode = explode(' ',$spell);
                $tail = end($explode);
                $open = fopen('./cache/'.$md5,'a');
                fwrite($open,"\n{$text}");
                fclose($open);
                /* 写入尾音 */
                $open = fopen('./s/'.$md5,'w');
                fwrite($open,$tail);
                fclose($open);
                Switch($type){
                    case 'text':
                    need::send('成语：'.$text."\n拼音：".$spell."\n解释：".$content."\n这次就帮你跳过吧！",'text');
                    break;
                    default:
                    need::send(array('code'=>1,'text'=>'获取成功','data'=>array('Text'=>$text,'Spell'=>$spell,'Content'=>$content)));
                    break;
                }
                break;
            }
        }
    }
}else{
    Switch($type){
        case 'text':
        need::send('这是空白选项，但是欢迎你们投稿未收录的成语，群：820323177');
        break;
        default:
        need::send(array('code'=>-404,'text'=>'这是空白选项，但是欢迎你们投稿未收录的成语，群：820323177'));
    }
}