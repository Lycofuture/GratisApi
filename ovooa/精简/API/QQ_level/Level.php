<?php

class Level {
    
    /**
     * 第一级图片显示字段
     */
    public $mimage1;
    
    /**
     * 第二级图片显示字段
     */
    public $mimage2;
    
    /**
     * 第三级图片显示字段
     */
    public $mimage3;
    
    public $mimage4;
    /**
     * 构造函数：传入图片值
     * @return 无
     */
    function __construct() {
        $this->mimage1 = '👑';
        $this->mimage2 = '☀️';
        $this->mimage3 = '🌙';
        $this->mimage4 = '⭐';
        
    }
    
    /**
     * 根据活跃天数计算用户等级。(模仿qq的升级方式)
     * @return int
     * @access public
     */
    function get_rank($pscore) {
        $temp = $pscore+4;
        $trank = sqrt($temp)-2;
        $trank = floor($trank);
        return $trank;
    }
    
    /**
     * 用户等级标志,根据用户等级显示用户标志
     * 仿照qq等级的四进制显示
     * @return str
     * @access public
     */
    function get_score($pscore) {
        $str = '';
    //    $trank = $this->get_rank($pscore);//根据分数取得等级
        $tpicnum = base_convert($pscore,10,4);//转化为四进制
      //  $tpicnum = strrev($tpicnum);//翻转字符串
        //$mum = preg_match_all('/0/',$tpicnum);
        $tarray = str_split($tpicnum);//转化为数组
      //  sort($tarray);
        $tnum = count($tarray);
        foreach($tarray as $k=>$v) {
            Switch($v){
                case 0:
                for($i = 0 ; $i < $v ; $i++){
                    $image = "mimage".($v);
                    $String .= $this->$image;
                }
                case 1:
                for($i = 0 ; $i < $v ; $i++){
                    $image = "mimage".($v+1);
                    $String .= $this->$image;
                }
                case 2:
                for($i = 0 ; $i < $v ; $i++){
                    $image = "mimage".($v+1);
                    $String .= $this->$image;
                }
                case 3:
                for($i = 0 ; $i < $v ; $i++){
                    $image = "mimage".($v);
                    $String .= $this->$image;
                }
                
           /* for($i=0;$i<($v);$i++){
                $str .=$v. $this->$image;
            }
        }*/
        //$v = 0;
              /*  switch($v){
                 
                    case '0':
                        for($j = 0 ; $j <= $v ; $j++){
                            $str .= $this->$image;
                        }
                    case '1':
                        for($j = 0 ; $j < $v ; $j++){
                            $str .= $this->$image;
                        }
                    break;
                    case '2':
                        for($j = 0 ; $j < $v ; $j++){
                            $str .= $this->$image;
                        }
                    case '3':
                    for($j = 0 ; $j < $v ; $j++){
                        $str.=$this->$image;
                    }
                    break;
                  /*  default:
                        $str .= $this->mimage3;
                    break;*/
                }
            }
        
       // return json_encode($tarray,320) . $mum.$str;
      return $String.json_encode($tarray,320).$tpicnum;
    }
}
?>