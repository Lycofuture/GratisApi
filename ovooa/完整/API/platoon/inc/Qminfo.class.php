<?
require_once(INCPATH.'cdnongli.class.php');
require_once(INCPATH.'cdsizhu.class.php');
require_once(INCPATH.'cdqimen.class.php');
class Qminfo {
	private $cdnongli;
	private $cfg=array();
	private $dizhi=array("亥","子","丑","寅","卯","辰","巳","午","未","申","酉","戌");
	public $info=array();
	public function __construct($params = array())
	{
		$this->cdnongli=new Cdnongli();
		$this->cfg = $params;
		$this->set_info();
	}
	public function Qminfo($params)
	{
		return $this->__construct($params);
	}
	public function set_info()
	{
		$cfg=$this->cfg;
		$birthday=@$cfg['date'];
		$bjdate=strtotime($birthday);
		$jd=(@$cfg['zty']==1 &&isset($cfg['jd']) &&!empty($cfg['jd']))?$cfg['jd'] : 0;
		$nian=date('Y',$bjdate);
		$yue=date('m',$bjdate);
		$ri=date('d',$bjdate);
		$hh=date('H',$bjdate);
		$mm=date('i',$bjdate);
		$this->info['glstr']=date('Y年m月d日H时i分',$bjdate);
		if(@$cfg['datetype']!=1){
			$narr=$this->cdnongli->SolarToLunar($nian,$yue,$ri);
			$ntime=$this->dizhi[(ceil($hh/2)+1)%12];
			$ntime=$hh>=23?'夜'.$ntime:$ntime;
			$nlarr=array($narr[0],$narr[1],$narr[2],$ntime);
		}
		$bazi=new Cdsizhu(array('date'=>$birthday,'jd'=>$jd));
		$sizhu=$bazi->getSizhu();
		if(!empty($jd) &&@$cfg['zty']){
			$truedate=$bazi->getTYdate();
			$nian=date('Y',$truedate);
			$yue=date('m',$truedate);
			$ri=date('d',$truedate);
			$hh=date('H',$truedate);
			$mm=date('i',$truedate);
			$this->info['truedatestr']=date('Y年m月d日H时i分',$truedate);
			$this->info['truedate']=$truedate;
			$ntime=$this->dizhi[(ceil($hh/2)+1)%12];
			$ntime=$hh>=23?'夜'.$ntime:$ntime;
			$zty_nlarr=$this->cdnongli->SolarToLunar($nian,$yue,$ri);
			$this->info['zty_nlarr']=array($zty_nlarr[0],$zty_nlarr[1],$zty_nlarr[2],$ntime);
			$this->info['zty_nlstr']=$zty_nlarr[0].'年'.$zty_nlarr[1].$zty_nlarr[2]."日".$ntime.'时';
		}
		$this->info['nlarr']=$nlarr;
		$this->info['nlstr']=$nlarr[0]."年".$nlarr[1].$nlarr[2]."日".$nlarr[3]."时";
		$this->info['yg']=$sizhu[0];
		$this->info['yz']=$sizhu[1];
		$this->info['ygx']=$sizhu[2];
		$this->info['yzx']=$sizhu[3];
		$this->info['ygz']=$this->info['yg'].$this->info['yz'];
		$this->info['mg']=$sizhu[4];
		$this->info['mz']=$sizhu[5];
		$this->info['mgx']=$sizhu[6];
		$this->info['mzx']=$sizhu[7];
		$this->info['mgz']=$this->info['mg'].$this->info['mz'];
		$this->info['dg']=$sizhu[8];
		$this->info['dz']=$sizhu[9];
		$this->info['dgx']=$sizhu[10];
		$this->info['dzx']=$sizhu[11];
		$this->info['dgz']=$this->info['dg'].$this->info['dz'];
		$this->info['hg']=$sizhu[12];
		$this->info['hz']=$sizhu[13];
		$this->info['hgx']=$sizhu[14];
		$this->info['hzx']=$sizhu[15];
		$this->info['hgz']=$this->info['hg'].$this->info['hz'];
		$dxk=$bazi->xunkong($this->info['dgx'],$this->info['dzx'],true);
		$this->info['dxk']=$dxk[0];
		$this->info['dxs']=$dxs=$dxk[1];
		$hsk=$bazi->xunkong($this->info['hgx'],$this->info['hzx'],true);
		$this->info['hxk']=$hsk[0];
		$this->info['hxs']=$hxs=$hsk[1];
		$pdate=$bazi->getppdate();
		$config=array(
			'ismobile'=>@$cfg['ismobile'],
			'date'=>$pdate,
			'panmode'=>@$cfg['panmode'],
			'qjumode'=>@$cfg['qjumode'],
			'fpsn'=>@$cfg['fpsn'],
			'dgx'=>$this->info['dgx'],
			'dzx'=>$this->info['dzx'],
			'dxs'=>$this->info['dxs'],
			'hg'=>$this->info['hg'],
			'hgx'=>$this->info['hgx'],
			'hzx'=>$this->info['hzx'],
			'hxs'=>$hxs
		);
		$qm=new Cdqimen($config);
		$this->info['jqstr']=$qm->get_jqstr();
		$this->info['dunju']=$qm->dunju();
		$this->info['zhi']=$qm->get_zhifu();
		$this->info['fushou']=$qm->fushou;
		$this->info['qmpan']=$qm->paiqimen();
	}
	public function get_info(){
	return $this->info;
	}
}
