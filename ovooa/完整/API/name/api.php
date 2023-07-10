<?php
header('content-type:Application/json');
/* Start */
require ("../function.php"); // 引入函数文件
addApiAccess(100); // 调用统计函数
addAccess();//调用统计函数
require ('../../curl.php');//引进封装好的curl文件
require ('../../need.php');//引用封装好的函数文件
$type = @$_REQUEST["type"];
$name = @$_REQUEST['msg']?:@$_REQUEST['name'];
if(!($name)){
    Switch($type){
        case 'text':
        need::send('还没告诉我你的名字呢','text');
        break;
        default:
        need::send(Array('code'=>1,'text'=>'“'.$name.'还没告诉我你的名字呢'));
        break;
    }
}
$md5 = md5($name);
preg_match('/([0-9a-z]{3})/',$md5,$split);
$Array = str_split($split[1]);
foreach($Array as $k=>$v){
    Switch($k){
        case 0:
        Switch($v){
            case 0:
            $first = '清晨的第一缕阳光';
            break;
            case 1:
            $first = '雨后的彩虹';
            break;
            case 2:
            $first = '朝间的露水';
            break;
            case 3:
            $first = '午后一缕温暖的阳光';
            break;
            case 4:
            $first = '羞涩的晚霞';
            break;
            case 5:
            $first = '裙子上的蝴蝶结';
            break;
            case 6:
            $first = '天桥下折翼的喜鹊';
            break;
            case 7:
            $first = '干旱土地上的裂纹';
            break;
            case 8:
            $first = '积雪上摇晃的树影';
            break;
            case 9:
            $first = '夕阳灿烂的余晖';
            break;
            case 'a':
            $first = '复活节的晨曦';
            break;
            case 'b':
            $first = '草原上的孤影';
            break;
            case 'c':
            $first = '白色封皮的相册';
            break;
            case 'd':
            $first = '冬日里稀薄的阳光';
            break;
            case 'e':
            $first = '呼啸而过的耳旁风';
            break;
            case 'f':
            $first = '缥缈的一缕晨霞';
            break;
            case 'g':
            $first = '挂在铁窗上的一盆绿萝';
            break;
            case 'h':
            $first = '夏日桃树上的桃胶';
            break;
            case 'i':
            $first = '指向十二点的时钟';
            break;
            case 'j':
            $first = '万圣节的纸蝙蝠';
            break;
            case 'k':
            $first = '马克杯里的热可可';
            break;
            case 'l':
            $first = '在空中旋转的紫荆花瓣';
            break;
            case 'm':
            $first = '橱窗里的洋娃娃';
            break;
            case 'n':
            $first = '潮湿的沼泽地';
            break;
            case 'o':
            $first = '天空中那朵懒洋洋的云彩';
            break;
            case 'p':
            $first = '月下的桂花';
            break;
            case 'q':
            $first = '情人节的玫瑰花';
            break;
            case 'r':
            $first = '蟠桃园的仙桃';
            break;
            case 's':
            $first = '流淌的小溪';
            break;
            case 't':
            $first = '海边的贝壳';
            break;
            case 'u':
            $first = '飞蛾翅膀上的粉末';
            break;
            case 'v':
            $first = '秋日的枫叶';
            break;
            case 'w':
            $first = '林间小径上的绿苔';
            break;
            case 'x':
            $first = '新奇古怪的想法';
            break;
            case 'y':
            $first = '夜晚明亮的星星';
            break;
            case 'z':
            $first = '仙女们上的铃铛';
            break;
        }
        case 1:
        Switch($v){
            case 0:
            $second = '泡在福尔马林里面的内脏';
            break;
            case 1:
            $second = '震动翅膀的蝴蝶';
            break;
            case 2:
            $second = '夏日微风吹起的蒲公英';
            break;
            case 3:
            $second = '冬日落下的雪花';
            break;
            case 4:
            $second = '下落的过山车';
            break;
            case 5:
            $second = '不断滴落的沥青';
            break;
            case 6:
            $second = '南极的化石';
            break;
            case 7:
            $second = '璀璨的星河';
            break;
            case 8:
            $second = '饱受风霜的树皮';
            break;
            case 9:
            $second = '纯白色的短袖';
            break;
            case 'a':
            $second = '被摧残过的路障';
            break;
            case 'b':
            $second = '没有喝完的珍珠奶茶';
            break;
            case 'c':
            $second = '半路不更新的小说';
            break;
            case 'd':
            $second = '树叶上的虫卵';
            break;
            case 'e':
            $second = '恶魔的犄角';
            break;
            case 'f':
            $second = '天使的翅膀';
            break;
            case 'g':
            $second = '高速移动的列车';
            break;
            case 'h':
            $second = '喝完水的麻雀';
            break;
            case 'i':
            $second = '融化后的冰淇淋';
            break;
            case 'j':
            $second = '经验不足的幼豹';
            break;
            case 'k':
            $second = '池塘里咕咕叫的青蛙';
            break;
            case 'l':
            $second = '磨成粉的朱砂';
            break;
            case 'm':
            $second = '正在开花的仙人掌';
            break;
            case 'n':
            $second = '燃烧的纸花';
            break;
            case 'o':
            $second = '崭新的餐具';
            break;
            case 'p':
            $second = '令人震撼的想法';
            break;
            case 'q':
            $second = '与众不同的情书';
            break;
            case 'r':
            $second = '邻居家的除草剂';
            break;
            case 's':
            $second = '不断漏下的沙子';
            break;
            case 't':
            $second = '加了少许银子的金冠';
            break;
            case 'u':
            $second = '五颜六色的糖衣药丸';
            break;
            case 'v':
            $second = '六月份的迷幻梦境';
            break;
            case 'w':
            $second = '在雨天的擦肩而过';
            break;
            case 'x':
            $second = '缝在布娃娃上的纽扣';
            break;
            case 'y':
            $second = '发酵的奶酪';
            break;
            case 'z':
            $second = '毕业季的回眸';
            break;
        }
        case 2:
        Switch($v){
            case 0:
            $third = '维纳斯的眼泪';
            break;
            case 1:
            $third = '暴跌的比特币';
            break;
            case 2:
            $third = '黏糊糊的树脂';
            break;
            case 3:
            $third = '与众不同的硬币';
            break;
            case 4:
            $third = '碍眼的石头';
            break;
            case 5:
            $third = '飞过头顶的飞机';
            break;
            case 6:
            $third = '夏天的蝴蝶';
            break;
            case 7:
            $third = '秋天的鱼';
            break;
            case 8:
            $third = '冰镇的西瓜';
            break;
            case 9:
            $third = '奶油蛋糕的裙摆';
            break;
            case 'a':
            $third = '猫咪软软的肉垫';
            break;
            case 'b':
            $third = '狗勾机灵的鼻子';
            break;
            case 'c':
            $third = '沾了血的创可贴';
            break;
            case 'd':
            $third = '落灰的黑胶唱片';
            break;
            case 'e':
            $third = '破旧不堪的噩梦';
            break;
            case 'f':
            $third = '高架桥上的银河';
            break;
            case 'g':
            $third = '漂浮的星球模组';
            break;
            case 'h':
            $third = '发光的鳞片';
            break;
            case 'i':
            $third = '清明下的一场雨';
            break;
            case 'j':
            $third = '来自太平洋的海风';
            break;
            case 'k':
            $third = '挂着雨珠的玻璃窗';
            break;
            case 'l':
            $third = '拥有着曼妙身躯的艺妓';
            break;
            case 'm':
            $third = '装满星空的漂流瓶';
            break;
            case 'n':
            $third = '透亮坚硬的钻石';
            break;
            case 'o':
            $third = '炫目的迪斯科球';
            break;
            case 'p':
            $third = '有鸭子游过的江水';
            break;
            case 'q':
            $third = '透出温暖的裂缝';
            break;
            case 'r':
            $third = '故宫中的一片砖瓦';
            break;
            case 's':
            $third = '简洁的大厦';
            break;
            case 't':
            $third = '深海鱼吐出的气泡';
            break;
            case 'u':
            $third = '以前的旗袍';
            break;
            case 'v':
            $third = '少女的裙摆';
            break;
            case 'w':
            $third = '全新的百褶裙';
            break;
            case 'x':
            $third = '漂亮的小白鞋';
            break;
            case 'y':
            $third = '与众不同的项链';
            break;
            case 'z':
            $third = '深邃的目光';
            break;
        }
    }
}
Switch($type){
    case 'text':
    need::send('“'.$name.'”是由'.$first.'与'.$second.'和'.$third.'组成的。','text');
    break;
    default:
    need::send(Array('code'=>1,'text'=>'“'.$name.'”是由'.$first.'与'.$second.'和'.$third.'组成的。'));
    break;
}
//print_r($Array);
