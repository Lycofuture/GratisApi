<?
class Cdqimen{
	private $tiangan=array("癸","甲","乙","丙","丁","戊","己","庚","辛","壬");
	private $dizhi=array("亥","子","丑","寅","卯","辰","巳","午","未","申","酉","戌");
	private $jq=array(NULL,"立春","惊蛰","清明","立夏","芒种","小暑","立秋","白露","寒露","立冬","大雪","小寒");
	private	$zq=array(NULL,"雨水","春分","谷雨","小满","夏至","大暑","处暑","秋分","霜降","小雪","冬至","大寒");
	private $xt_bagua=array("坎","坤","震","巽","中","乾","兑","艮","离");
	private $gong=array("一","二","三","四","五","六","七","八","九");
	private $jiuxing=array("天蓬","天任","天冲","天辅","天英","天芮","天柱","天心","天禽");
	private $jiuxing8=array("天蓬","天任","天冲","天辅","天英","天芮","天柱","天心");
	private $jiuxing2=array("天蓬","天芮","天冲","天辅","天禽","天心","天柱","天任","天英");
	private $zp_sunshizhen=array(0,7,2,3,8,1,6,5,4);
	private $xunshou=array("甲子","甲戌","甲申","甲午","甲辰","甲寅","星奇","月奇","日奇");
	private $sqliuyi=array("戊","己","庚","辛","壬","癸","丁","丙","乙");
	private $bamen=array("休门","生门","伤门","杜门","景门","死门","惊门","开门");
	private $bamen2=array("休门","死门","伤门","杜门","死门","开门","惊门","生门","景门");
	private $bashen=array("值符","腾蛇","太阴","六合","白虎","玄武","九地","九天");
	private $fg_jiuxing=array("蓬","芮","冲","辅","禽","心","柱","任","英");
	private $fg_jiumen=array("休","死","伤","杜","中","开","惊","生","景");
	private $fg_jiushen_yang=array("符","蛇","阴","合","陈","常","雀","地","天");
	private $fg_jiushen_ying=array("符","蛇","阴","合","虎","常","玄","地","天");
	private $se_pre='';
	private $se_pre1='';
	private $se_end='';
	private $panmode=0;
	private $qjumode=0;
	private $birthdate;
	private $dgx;
	private $dzx;
	private $dxs;
	private $hg;
	private $hgx;
	private $hzx;
	private $hxs='';
	public $jqxu=0;
	public $juxu=0;
	public $yinyang=false;
	public $fushou='';
	public $jxindex=0;
	public $bmindex=0;
	public $fusguaxu=0;
	public $zfgong='';
	public $zsgong='';
	private $jigongmode=0;
	private $zgindex=5;
	private $dunju='';
	private $fpsn=0;
	public $jqstr='';
	private $yuandays=0;
	public $jqinfo=array();
	public $sqly=array();
	public function __construct($parent){
		$this->ismobile=isset($parent['ismobile'])?$parent['ismobile']:false;
		$this->panmode=$parent['panmode'];
		$this->qjumode=$parent['qjumode'];
		if(isset($parent['jigongmode'])) $this->jigongmode=$parent['jigongmode'];
		$this->dgx=$parent['dgx'];
		$this->dzx=$parent['dzx'];
		$this->dxs=$parent['dxs'];
		$this->hg=$parent['hg'];
		$this->hgx=$parent['hgx'];
		$this->hzx=$parent['hzx'];
		$this->hxs=$parent['hxs'];
		$this->fpsn=$parent['fpsn'];
		$this->jqstr=$this->make_jq($parent['date']);
		$this->set_dunju();
		$this->set_zhifu();
	}
	public function Cdqimen($parent){
		$this->__construct($parent);
	}
	public function make_jq($time){
		$y=date('Y',$time);
		$path=INCPATH."dbdate.txt";
		$row=file($path);
		$i=($y-1921)*12;
		while(!empty($row[$i])){
			$t=explode(",",$row[$i]);
			$Jdate=mktime($t[6],$t[7],0,$t[2],$t[3],$t[1]);
			$Qdate=mktime($t[10],$t[9],0,$t[2],$t[8],$t[1]);
			$jstr=date("Y年m月d日H时i分",$Jdate);
			$jqxu=$t[2]-1;
			$jqxu=$jqxu==0?12:$jqxu;
			$jname=$this->jq[$jqxu];
			if($time<$Jdate){
				$p=explode(",",$row[$i-1]);
				$zqdate=mktime($p[10],$p[9],0,$p[2],$p[8],$p[1]);
				$qstr=date("Y年m月d日H时i分",$zqdate);
				$zqxu=$p[2]-1;
				$zqxu=$zqxu==0?12:$zqxu;
				$qname=$this->zq[$zqxu];
				$this->jqxu=$zqxu*2;
				$this->jqinfo=array('curjq'=>$zqdate,'nextjq'=>$Jdate);
				return $qstr.$qname.'<br>&emsp;&emsp;&emsp;'.$jstr.$jname;
			}elseif($time>=$Jdate &&$time<$Qdate){
				$qstr=date("Y年m月d日H时i分",$Qdate);
				$qname=$this->zq[$jqxu];
				$this->jqinfo=array('curjq'=>$Jdate,'nextjq'=>$Qdate);
				$this->jqxu=$jqxu*2-1;
				return $jstr.$jname.'<br>&emsp;&emsp;&emsp;'.$qstr.$qname;
			}
			$i++;
		}
	}
	public function get_jqstr(){
		return $this->jqstr;
	}
	private function sanyuan(){
		if($this->qjumode==0){
			$j=0;
			for($i=1;$i<=60;$i++){
				$gx=$i%10;
				$zx=$i%12;
				if($gx==$this->dgx &&$zx==$this->dzx) break;
				$j++;
			}
			$j=floor($j/5)+1;
			if($j%3==1) $yuan="上元";
			elseif($j%3==2) $yuan="中元";
			else $yuan="下元";
			return $yuan;
		}
		elseif($this->qjumode==1){
			$yuan=$this->cde_zhirun();
			return $yuan;
		}
		elseif($this->qjumode==2){
			if(@$this->jqinfo['time']>=@$this->jqinfo['nextjq']){
				$jq2ptime=$this->jqinfo['time']-$this->jqinfo['nextjq'];
				if($jq2ptime<432000) $yuan="上元";
				elseif($jq2ptime>=432000 &&$jq2ptime<864000) $yuan="中元";
				else $yuan="下元";
			}else{
				$jq2ptime=@$this->jqinfo['time']-@$this->jqinfo['curjq'];
				if($jq2ptime<432000) $yuan="上元";
				elseif($jq2ptime>=432000 &&$jq2ptime<864000) $yuan="中元";
				else $yuan="下元";
			}
			return $yuan;
		}
	}
	function cde_zhirun(){
		$jqxu=$this->jqxu;
		$mayzhirun=false;
		if($this->jqxu=9 ||$this->jqxu==21) $mayzhirun=true;
		$bzpre=new Cdsizhu(array('date'=>date('Y-m-d H:i',$this->jqinfo['curjq'])));
		$sizhu_pre=$bzpre->getSizhu();
		$jqgx=$sizhu_pre[10];
		$jqzx=$sizhu_pre[11];
		$bznext=new Cdsizhu(array('date'=>date('Y-m-d H:i',$this->jqinfo['nextjq'])));
		$sizhu_nxt=$bznext->getSizhu();
		$njqgx=$sizhu_nxt[10];
		$njqzx=$sizhu_nxt[11];
		for($i=1;$i<=60;$i++){
			$gx=$i%10;
			$zx=$i%12;
			if($gx==$this->dgx &&$zx==$this->dzx){
				$gzx=$i;
			}
			if($gx==$jqgx &&$zx==$jqzx){
				$jqgzx=$i;
			}
			if($gx==$njqgx &&$zx==$njqzx){
				$njqgzx=$i;
			}
		}
		$sishou=array(array(0,1),array(15,4),array(30,7),array(45,10));
		foreach($sishou as $k=>$v){
			if($gzx>$v[0]){
				$shou=$k;
				$shoux=$v[0]+1;
				$shouzx=$v[1];
				$shougx=$v[0]%2==0?1:6;
			}
			if($jqgzx>$v[0]){
				$jqshou=$k;
				$jqshoux=$v[0]+1;
				$jqshouzx=$v[1];
				$jqshougx=$v[0]%2==0?1:6;
			}
			if($njqgzx>$v[0]){
				$njqshou=$k;
				$njqshoux=$v[0]+1;
				$njqshouzx=$v[1];
				$njqshougx=$v[0]%2==0?1:6;
			}
		}
		if($shou==$jqshou){
			if($shougx==$jqshougx &&$shouzx==$jqshouzx){
				$isbenjq=true;
			}else{
				$daycha=$jqgzx-$shoux+1;
				if($daycha<9){
					$isbenjq=true;
				}elseif($daycha>=9 &&$daycha<12){
					$jqxu--;
				}else{
					$jqxu--;
				}
			}
		}elseif($shou==$njqshou){
			$daycha=$shoux-$njqgzx+1;
			if($daycha<9){
				$jqxu++;
			}elseif($daycha>9 &&$daycha<12){
				if($mayzhirun) $isbenjq=true;
			}else{
				$isbenjq=true;
			}
		}else{
		$isbenjq=true;
		}
		$this->jqxu=$jqxu==0?24:$jqxu;
		$yarr=array('下元','上元','中元');
		$yuanx=ceil($gzx/5)%3;
		$yuandays=(($gzx/5)*10/2)%5;
		$this->yuandays=$yuandays==0?5:$yuandays;
		$yuan=$yarr[$yuanx];
		return $yuan;
	}
	public function set_dunju(){
		$yuan=$this->sanyuan();
		$array=array(NULL,"8,5,2","9,6,3","1,7,4","3,9,6","4,1,7","5,2,8","4,1,7","5,2,8","6,3,9","9,3,6","8,2,5","7,1,4","2,5,8",
		"1,4,7","9,3,6","7,1,4","6,9,3","5,8,2","6,9,3","5,8,2","4,7,1","1,7,4","2,8,5","3,9,6");
		$juarr=explode(',',$array[$this->jqxu]);
		switch($yuan){
			case "中元": $juxu=$juarr[1];break;
			case "上元": $juxu=$juarr[0];break;
			case "下元": $juxu=$juarr[2];break;
		}
		$this->juxu=$juxu;
		$djuxu=$this->gong[$juxu-1];
		$yuaninfo=$yuan;
		if($this->qjumode==1){
			$jqname=($this->jqxu%2)?$this->jq[ceil($this->jqxu/2)]:$this->zq[$this->jqxu/2];
			$yuaninfo='<font color="blue">'.$jqname.$yuan.'第'.$this->yuandays.'天</font>';
		}
		if($this->jqxu>=10 &&$this->jqxu<22){
			$this->yinyang=false;
			$this->dunju=$yuaninfo."阴遁".$djuxu."局";
			if($this->panmode==0 &&$this->fpsn==1) $this->yinyang=true;
		}else{
			$this->dunju=$yuaninfo."阳遁".$djuxu."局";
			$this->yinyang=true;
		}
	}
	function dunju(){
		return $this->dunju;
	}
	public function get_sanqiliuyi(){
		$appjuxu=$this->juxu-1;
		if($this->yinyang){
			for($i=0;$i<=8;$i++){
				$j=($i+$appjuxu)%9;
				$this->sqly[$j]=$this->sqliuyi[$i];
			}
		}else{
			$k=10;
			for($i=$appjuxu;$i<=$appjuxu+8;$i++){
				$sqx=$i%9;
				$j=($k+8)%9;
				$this->sqly[$sqx]=$this->sqliuyi[$j];
				$k--;
			}
		}
	}
	public function set_zhifu(){
		$this->get_sanqiliuyi();
		foreach($this->xunshou as $k=>$v){
			if($v=='甲'.$this->hxs){$fushou=$this->sqliuyi[$k];break;}
		}
		$this->fushou=$fushou;
		foreach($this->sqly as $k=>$v){
			if($fushou==$v){$this->fusguaxu=$k;break;}
		}
		$this->set_jxindex();
		$this->set_bmindex();
	}
	public function get_zhifu(){
		$zhifu=$this->jiuxing2[$this->fusguaxu];
		$zhishi=$this->bamen2[$this->fusguaxu];
		$zfgong=$this->gong[$this->jxindex];
		$zsgong=$this->gong[$this->bmindex];
		if($this->panmode==0){
			$zhifu='天'.$this->fg_jiuxing[$this->fusguaxu];
			$zhishi=$this->fg_jiumen[$this->fusguaxu].'门';
		}
		return array('zf'=>$zhifu,'zs'=>$zhishi,'zfg'=>$zfgong,'zsg'=>$zsgong);
	}
	private function set_bmindex(){
		$hxsx=array_search($this->hxs,$this->dizhi);
		$hzcha=($this->hzx-$hxsx+12)%12;
		$this->bmindex=$this->yinyang?($this->fusguaxu+$hzcha)%9 : ($this->fusguaxu-$hzcha+9)%9;
	}
	private function set_jxindex(){
		$sgguax=$this->_get_hgong();
		$this->jxindex=$sgguax;
	}
	public function paiqimen(){
		if($this->panmode){
			foreach($this->zp_sunshizhen as $v){
				$sq6y[]=$this->sqly[$v];
				$gong[]=$this->gong[$v];
		}
			$bamens=$this->get_bamen();
			$jiuxing=$this->get_jiuxing();
			$tianqin=$jiuxing['tq'];
			$jiuxings=$jiuxing['jx'];
			$bashens=$jiuxing['bs'];
			$tqgan=$jiuxing['tqgan'];
			$tp=$jiuxing['tp'];
			for($i=0;$i<=7;$i++){
				$qmdj[$i]='&emsp;&nbsp;'.$bashens[$i].'&emsp;&emsp;<br>';
				$qmdj[$i].=$tqgan[$i].'&nbsp;'.$bamens[$i].'&emsp;'.$tp[$i].'<br>';
				$qmdj[$i].=$tianqin[$i].'&nbsp;'.$jiuxings[$i].'&emsp;<b>'.$sq6y[$i].'</b>';
			}
			$qmdj[8]=$this->sqly[4];
		}else{
			$fginfo=$this->feigong();
			$tp=$fginfo['tp'];
			$tpshens=$fginfo['tpshens'];
			$dpshens=$fginfo['dpshens'];
			$jiuxings=$fginfo['jiuxings'];
			$bamens=$fginfo['bamens'];
			for($i=0;$i<=8;$i++){
				$qmdj[$i]=$tpshens[$i].'&emsp;'.$jiuxings[$i].'&emsp;&emsp;<br>';
				$qmdj[$i].='&emsp;&emsp;'.$bamens[$i].'&emsp;&emsp;<br>';
				$qmdj[$i].=$dpshens[$i].'&emsp;'.$this->xt_bagua[$i].'&emsp;<b>'.$this->sqly[$i].'</b>';
			}
		}
		return $qmdj;
	}
	function get_jiuxing(){
		$zindex=$this->fusguaxu==4?1:$this->fusguaxu;
		$jxindex=array_search($this->jiuxing2[$zindex],$this->jiuxing8);
		$jxgindex=$this->guaxu_zpxu($this->jxindex);
		$jxgindex=$jxgindex==8?5:$jxgindex;
		for($i=0;$i<=7;$i++){
			$tianqin[$i]="&emsp;";
			$jxi=($i+$jxgindex)%8;
			$jxv=($i+$jxindex)%8;
			$jiuxings[$jxi]=$this->jiuxing8[$jxv];
			if($jxv==5) $tianqindex=$jxi;
				$tpgan[$jxi]=$this->sqliuyi[$jxv];
				$bsi=($this->yinyang)?($i+$jxgindex)%8:($jxgindex-$i+8)%8;
				$bashens[$bsi]=$this->bashen[$i];
		}
		$tianqin[$tianqindex]=$this->se_pre.'禽'.$this->se_end;
		for($i=0;$i<=7;$i++){
			foreach($this->jiuxing as $k=>$v){
				if($v==$jiuxings[$i]){
					$gx=$this->zp_sunshizhen[$k];
					break;
				}
			}
			$tp[$i]=$this->sqly[$gx];
			$tptag[$gx]=true;
		}
		$tqgan=$this->_zp_tqgan($tianqindex,$tptag);
		return array('jx'=>$jiuxings,'tq'=>$tianqin,'tp'=>$tp,'tqgan'=>$tqgan,'bs'=>$bashens);
	}
	private function _zp_tqgan($tianqindex,$tptag){
		for($i=0;$i<=8;$i++){
			if(!isset($tptag[$i])){
				$tqgan[$tianqindex]=$this->sqly[$i];
				break;
			}
		}
		for($i=0;$i<=7;$i++){
			if(!isset($tqgan[$i])) $tqgan[$i]="&emsp;";
		}
		return $tqgan;
	}
	function get_bamen(){
		$bmindex=array_search($this->bamen2[$this->fusguaxu],$this->bamen);
		$bmgindex=$this->guaxu_zpxu($this->bmindex);
		$bmgindex=$bmgindex==8?5:$bmgindex;
		for($i=0;$i<=7;$i++){
			$bamens[($i+$bmgindex)%8]=$this->bamen[($i+$bmindex)%8];
		}
		return $bamens;
	}
	function feigong(){
		$fg_jx=array();
		$fg_bm=array();
		$fgtp=array();
		$fgtpjs=array();
		$fgdpjs=array();
		for($i=0;$i<=8;$i++){
			$fg_jx[($i+$this->jxindex)%9]=$this->fg_jiuxing[($i+$this->fusguaxu)%9];
			$fg_bm[($i+$this->bmindex)%9]=$this->fg_jiumen[($this->fusguaxu+$i)%9];
		}
		if($this->yinyang){
			for($i=0;$i<=8;$i++){
				$fgtpjs[($i+$this->jxindex)%9]=$this->fg_jiushen_yang[$i];
				$fgdpjs[($i+$this->fusguaxu)%9]=$this->fg_jiushen_yang[$i];
			}
		}else{
			$k=10;
			for($i=0;$i<=8;$i++){
				$j=($k+8)%9;
				$fgtpjs[($this->jxindex+$i)%9]=$this->fg_jiushen_ying[$j];
				$fgdpjs[($i+$this->fusguaxu)%9]=$this->fg_jiushen_ying[$j];
				$k--;
			}
		}
		for($i=0;$i<=8;$i++){
			foreach($this->fg_jiuxing as $k=>$v){
				if($v==$fg_jx[$i]){
					$gx=$k;
					break;
				}
			}
			$fgtp[$i]=$this->sqly[$gx];
		}
		return array('tp'=>$fgtp,'jiuxings'=>$fg_jx,'bamens'=>$fg_bm,'tpshens'=>$fgtpjs,'dpshens'=>$fgdpjs);
	}
	private function _get_hgong(){
		$sg=$this->hg;
		if($this->hg=='甲'){
			$jiazi=array(1,11,9,7,5,3);
			$sg=$this->sqliuyi[array_search($this->hzx,$jiazi)];
		}
		foreach($this->sqly as $k=>$v){
			if($v==$sg){
				return $k;
			}
		}
	}
	private function guaxu_zpxu($guaxu){
		return array_search($guaxu,$this->zp_sunshizhen);
	}
}