<?php
Header('content-type: application/Json');
require ("../function.php"); // 引入函数文件
addAccess();//调用统计函数
addApiAccess(99); // 调用统计函数*/
require 'Lunar.php';
require '../../need.php';
$type = @$_REQUEST['type'];
$Time = @$_REQUEST['time'];

use com\nlf\calendar\Foto;
use com\nlf\calendar\LunarYear;
use com\nlf\calendar\util\HolidayUtil;
use com\nlf\calendar\Lunar;
use com\nlf\calendar\Solar;

$array = explode('-', (String)$Time);
$year = isset($array[0]) && $array[0] ? $array[0] : Date('Y');
$month = isset($array[1]) && $array[1] ? $array[1] : Date('m');
$day = isset($array[2]) && $array[2] ? $array[2] : Date('d');
$new = new datetime("${year}-${month}-${day} 08:00:00");
// print_r($new);exit;
$lunar = Solar::fromDate($new)->getLunar();
// $lunar = Lunar::fromYmd($year, $month, $day, '8:33');
// echo $lunar->getMonthInChinese();
// echo $lunar->getYearInChinese();
//print_r($lunar);
Switch($type){
    case 'text':
    need::send($lunar->toFullString(), 'text');
    break;
    default:
    $array = [
    	'time'=>[
    		'cn'=>[
    			'year'=>$lunar->getYearInChinese(),
    			'month'=>$lunar->getMonthInChinese(),
    			'day'=>$lunar->getDayInChinese(),
    		],
    		'data'=>[
    		    'year'=>$year,
    		    'month'=>$month,
    		    'day'=>$day
    		]
    	],
    	'data'=>[
    		'traditional_Chinese_calendar'=>[
    			'year'=>$lunar->getYearInGanZhi().'('.$lunar->getYearShengXiao().')',
    			'month'=>$lunar->getMonthInGanZhi().'('.$lunar->getMonthShengXiao().')',
    			'day'=>$lunar->getDayInGanZhi().'('.$lunar->getDayShengXiao().')'
    		],
    		'Nayin'=>[
    			'year'=>$lunar->getYearNaYin(),
    			'month'=>$lunar->getMonthNaYin(),
    			'day'=>$lunar->getDayNaYin()
    		],
    		'Constellation'=>$lunar->getXiu().$lunar->getZheng().$lunar->getAnimal().'('.$lunar->getXiuLuck().')',
    		'Taboo'=>$lunar->getPengZuGan().' '.$lunar->getPengZuZhi(),
    		'You_ge_Cheng'=>$lunar->getDayPositionXi().'('.$lunar->getDayPositionXiDesc().')',
    		'Yang'=>$lunar->getDayPositionYangGui().'('.$lunar->getDayPositionYangGuiDesc().')',
    		'Yin'=>$lunar->getDayPositionYinGui().'('.$lunar->getDayPositionYinGuiDesc().')',
    		'Fu_of_Wealth'=>$lunar->getDayPositionFu().'('.$lunar->getDayPositionFuDesc().')',
    		'God_of_Wealth'=>$lunar->getDayPositionCai().'('.$lunar->getDayPositionCaiDesc().')',
    		'Rush'=>$lunar->getChongDesc(),
    		'Fierce'=>$lunar->getSha()
    	]
    ];
    need::send(array('code'=>1, 'text'=>$lunar->toFullString(), 'data'=>$array), 'json');
    break;
}

/*
echo $lunar->toFullString() . "\n";

$s = '';
    $s .= $this;
    $s .= ' ';
    $s .= $this->getYearInGanZhi();
    $s .= '(';
    $s .= $this->getYearShengXiao();
    $s .= ')年 ';
    $s .= $this->getMonthInGanZhi();
    $s .= '(';
    $s .= $this->getMonthShengXiao();
    $s .= ')月 ';
    $s .= $this->getDayInGanZhi();
    $s .= '(';
    $s .= $this->getDayShengXiao();
    $s .= ')日 ';
    $s .= $this->getTimeZhi();
    $s .= '(';
    $s .= $this->getTimeShengXiao();
    $s .= ')时 纳音[';
    $s .= $this->getYearNaYin();
    $s .= ' ';
    $s .= $this->getMonthNaYin();
    $s .= ' ';
    $s .= $this->getDayNaYin();
    $s .= ' ';
    $s .= $this->getTimeNaYin();
    $s .= '] 星期';
    $s .= $this->getWeekInChinese();
    foreach ($this->getFestivals() as $f) {
      $s .= ' (' . $f . ')';
    }
    foreach ($this->getOtherFestivals() as $f) {
      $s .= ' (' . $f . ')';
    }
    $jq = $this->getJieQi();
    if (strlen($jq) > 0) {
      $s .= ' (' . $jq . ')';
    }
    $s .= ' ';
    $s .= $this->getGong();
    $s .= '方';
    $s .= $this->getShou();
    $s .= ' 星宿[';
    $s .= $this->getXiu();
    $s .= $this->getZheng();
    $s .= $this->getAnimal();
    $s .= '](';
    $s .= $this->getXiuLuck();
    $s .= ') 彭祖百忌[';
    $s .= $this->getPengZuGan();
    $s .= ' ';
    $s .= $this->getPengZuZhi();
    $s .= '] 喜神方位[';
    $s .= $this->getDayPositionXi();
    $s .= '](';
    $s .= $this->getDayPositionXiDesc();
    $s .= ') 阳贵神方位[';
    $s .= $this->getDayPositionYangGui();
    $s .= '](';
    $s .= $this->getDayPositionYangGuiDesc();
    $s .= ') 阴贵神方位[';
    $s .= $this->getDayPositionYinGui();
    $s .= '](';
    $s .= $this->getDayPositionYinGuiDesc();
    $s .= ') 福神方位[';
    $s .= $this->getDayPositionFu();
    $s .= '](';
    $s .= $this->getDayPositionFuDesc();
    $s .= ') 财神方位[';
    $s .= $this->getDayPositionCai();
    $s .= '](';
    $s .= $this->getDayPositionCaiDesc();
    $s .= ') 冲[';
    $s .= $this->getChongDesc();
    $s .= '] 煞[';
    $s .= $this->getSha();
    $s .= ']';
    return $s;
  }
*/