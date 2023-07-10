<?
class Cdsizhu{
	private $tiangan=array("癸","甲","乙","丙","丁","戊","己","庚","辛","壬");
	private $dizhi=array("亥","子","丑","寅","卯","辰","巳","午","未","申","酉","戌");
	private $shuxiang=array("猪","鼠","牛","虎","兔","龙","蛇","马","羊","猴","鸡","狗");
	private $jie=array("小寒","立春","惊蛰","清明","立夏","芒种","小暑","立秋","白露","寒露","立冬","大雪");
	private $wx=array("木","火","土","金","水");
	private $y;
	private $m;
	private $d;
	private $h;
	private $mi;
	private $pdate;
	private $pdatetime;
	private $curjqarray=array();
	private $jd;
	private $Truedat='';
	private $ifdunzi=false;
	private $dg;
	private $yg;
	private $mg;
	private $hg;
	private $dz;
	private $yz;
	private $mz;
	private $hz;
	private $dgx;
	private $ygx;
	private $mgx;
	private $hgx;
	private $dzx;
	private $yzx;
	private $mzx;
	private $hzx;
	private $bz_sx;
	private $zydate;
	private $ismobile=0;
	private $y_nayin='';
	public function __construct($params){
		$this->setinfo($params);
	}
	private function setinfo($params)
	{
		if(!is_array($params)){
			$date=date("Y-m-d H:i");
			$this->jd=0;
		}else{
			$date=@$params['date'];
			$this->jd=@$params['jd'];
		}
		$bjdate=strtotime($date);
		$this->pdate=$bjdate;
		if(!empty($this->jd)){
			$this->Truedat=$this->getTaiyang($bjdate);
			$this->pdate=$this->Truedat;
		}
		$this->y=date('Y',$this->pdate);
		$this->m=date('m',$this->pdate);
		$this->d=date('d',$this->pdate);
		$this->h=date('H',$this->pdate);
		$this->mi=date('i',$this->pdate);
		$this->pdatetime=date('Y-m-d H:i',$this->pdate);
		$this->curjqarray=$this->MkJieqi();
	}
	private function getTaiyang($time){
		$onedu=4*60;
		$jdcha=$this->jd-120;
		$jingfen=($jdcha-floor($jdcha))/60;
		$subtime=(floor($jdcha)+$jingfen)*$onedu;
		$zdate=intval(date('m',$time)).":".date('d',$time);
		$path=INCPATH."stime.txt";
		$arr=file($path);
		for($i=0;$i<count($arr);$i++){
			$b=explode(":",$arr[$i]);
			$comdate=$b[0].":".$b[1];
			if($comdate==$zdate){
				$truesubminute=$b[2];
				$truesubsecond=$b[3];
			break;
		}
	}
	if ($truesubminute<0){
		$truesubt=$truesubminute*60-$truesubsecond;
	}else{
		$truesubt=$truesubminute*60+$truesubsecond;
	}
		$taiyang=$time+$subtime+$truesubt;
		return $taiyang;
	}
	public function getTYdate(){
		return $this->Truedat;
	}
	public function getppdate(){
		return $this->pdate;
	}
	public function MkJieqi(){
		$path=INCPATH."jq.txt";
		$row=file($path);
		$i=($this->y-1900)*12;
		while(!empty($row[$i])){
			$Jdate=strtotime($row[$i]);
			if($Jdate>=$this->pdate){
				$curjqdate=$Jdate;
				$prejqdate=strtotime($row[($i-1)]);
				break;
			}
			$i++;
		}
		$xu=intval(date('m',$curjqdate))-1;
		$pxu=($xu+11)%12;
		$jqname0=$this->cndate($prejqdate);
		$jqname1=$this->cndate($curjqdate);
		$curjqname=$jqname1.$this->jie[$xu];
		$prejqname=$jqname0.$this->jie[$pxu];
		return array($curjqdate,$prejqdate,$curjqname,$prejqname);
	}
	public function getSizhu(){
		$jtime=$this->curjqarray[0];
		$monf=intval(date("m",$jtime));
		if($this->m==2){
			if($monf>2){
				$gzyear=$this->y;
			}else{
				$gzyear=$this->y-1;
			}
		}elseif ($this->m==1){
			$gzyear=$this->y-1;
		}else{
			$gzyear=$this->y;
		}
		$gzmonth=$monf%12;
		$dateyuan=mktime(0,0,0,2,10,1902);
		$dateystr=mktime(0,0,0,$this->m,$this->d,$this->y);
		$gzdate=floor(abs(($dateystr-$dateyuan)/60/60/24)+0.1);
		$gzarray=array();
		$ygz=$this->getYgz($gzyear);
		$gzarray=array_merge($gzarray,$ygz);
		$gzarray=array_merge($gzarray,$this->getMgz($gzmonth,$ygz[2]));
		if($this->h==23){
			$hadd=1;
			if(!$this->ifdunzi){
				$gzdate++;
				$hadd=0;
			}
		} else {
			$hadd = 0;
		}
		$dgz=$this->getDgz($gzdate);
		$gzarray=array_merge($gzarray,$dgz);
		$dgx=$dgz[2]+$hadd;
		$gzarray=array_merge($gzarray,$this->getTgz($dgx));
		return $gzarray;
	}
	public function getYgz($y){
		$this->yzx=($y-1864+1)%12;
		$this->ygx=($y-1864+1)%10;
		$this->yg=$this->tiangan[$this->ygx];
		$this->yz=$this->dizhi[$this->yzx];
		return array($this->yg,$this->yz,$this->ygx,$this->yzx);
	}
	public function getMgz($mzx,$ygx){
		$this->mzx=$mzx;
		$xu=(($ygx%5)*2+1)%10;
		$this->mgx=($xu+($mzx+9)%12)%10;
		$this->mg=$this->tiangan[$this->mgx];
		$this->mz=$this->dizhi[$this->mzx];
		return array($this->mg,$this->mz,$this->mgx,$this->mzx);
	}
	public function getDgz($gzdate){
		$this->dgx=($gzdate +1)%10;
		$this->dzx=($gzdate+1)%12;
		$this->dg=$this->tiangan[$this->dgx];
		$this->dz=$this->dizhi[$this->dzx];
		return array($this->dg,$this->dz,$this->dgx,$this->dzx);
	}
	public function getTgz($dgx){
		$xu=(2*($dgx%5)+9)%10;
		$this->hzx=(ceil($this->h/2)+1)%12;
		$this->hgx=($xu+abs($this->hzx-1))%10;
		$this->hg=$this->tiangan[$this->hgx];
		$this->hz=$this->dizhi[$this->hzx];
		return array($this->hg,$this->hz,$this->hgx,$this->hzx);
	}
	public function getsn($sex){
		$flag=0;
		$bz_sx=0;
		if($this->ygx%2==1) $flag=1;
		if(($sex==1 &&$flag==1) ||($sex==0 &&$flag==0)){
			$bz_sx=1;
		}
		$this->bz_sx=$bz_sx;
		return $bz_sx;
	}
	public function cndate($time){
		return date("Y年m月d日H时i分",$time);
	}
	public function xunkong($gx,$zx,$havxuns=false){
		$kong=array(array(0,11),array(9,10),array(7,8),array(5,6),array(3,4),array(1,2));
		$shou=array(1,11,9,7,5,3);
		$gx=$gx==0?10:$gx;
		$xu=(12+$zx-$gx)%12;
		foreach($kong as $k=>$v){
			if(in_array($xu,$v)){
				$gzkong=$this->dizhi[$v[0]].$this->dizhi[$v[1]];
				return $havxuns?array($gzkong ,$this->dizhi[$shou[$k]]):$gzkong;
			}
		}
		return false;
	}
	public function bznayin(){
		$this->y_nayin=$this->nayin($this->ygx,$this->yzx);
		return array($this->y_nayin,$this->nayin($this->mgx,$this->mzx),
		$this->nayin($this->dgx,$this->dzx),$this->nayin($this->hgx,$this->hzx));
	}
	public function nayin($gx,$zx){
		$narray=array("海中金","炉中火","大林木","路旁土","剑锋金","山头火","涧下水","城头土","白腊金","杨柳木 ", "泉中水","屋上土","霹雳火","松柏木","长流水","砂石金","山下火","平地木","壁上土","金薄金","覆灯火","天河水","大驿土","钗环金","桑柘木","大溪水","沙中土","天上火","石榴木","大海水");
		for($i=1;$i<=60;$i++){
			$tx=$i%10;
			$dx=$i%12;
			$key=floor(($i-1)/2)%30;
			if($gx==$tx &&$zx==$dx){
				return $narray[$key];
			}
		}
	}
	public function getwx($se=0){
		$wx=$se?$this->wxse:$this->wx;
		$garr=array($this->ygx,$this->mgx,$this->dgx,$this->hgx);
		foreach($garr as $ganx){
		$ganx=$ganx==0?10:$ganx;
		$gwx[]=$wx[ceil($ganx/2)-1];
		}
		$zarr=array($this->yzx,$this->mzx,$this->dzx,$this->hzx);
		foreach($zarr as $zhix){
			if(in_array($zhix,array(2,5,8,11))) $zwx[]=$wx[2];
			elseif($zhix==0 ||$zhix==1) $zwx[]=$wx[4];
			elseif($zhix==3 ||$zhix==4) $zwx[]=$wx[0];
			elseif($zhix==6 ||$zhix==7) $zwx[]=$wx[1];
			elseif($zhix==9 ||$zhix==10) $zwx[]=$wx[3];
		}
		return array($gwx,$zwx);
	}
	public function getsx(){
		return $this->shuxiang[$this->yzx];
	}
}