<?php
header('content-type:Application/json');

require ("../function.php"); // 引入函数文件
addApiAccess(146); // 调用统计函数
addAccess();//调用统计函数*/
require '../../need.php';
$request = need::request();
$Msg = (@$request['Msg']?:@$request['msg']) ?: null;
$type = @$request['type'];

new bdbk(array('Msg'=>$Msg, 'type'=>$type));

class bdbk{
	protected $info = [];
	protected $array = [];
	protected $url;
	public function __construct(array $array){
		foreach($array as $k=>$v){
			$this->info[$k] = $v;
		}
		$this->ParameterException();
	}
	protected function ParameterException(){
		$info = $this->info;
		$Msg = $info['Msg'];
		if(!$Msg) {
			unset($this->array, $this->Msg);
			$this->array = array('code'=>-1, 'text'=>'请输入需要百科的内容');
			$this->returns();
			return;
		}
		$this->Getbdbk();
		return;
	}
	protected function Getbdbk(){
		$Msg = $this->info['Msg'];
		$url = 'https://baike.baidu.com/api/searchui/searchword?word='.urlencode($Msg).'&ajax=1&_='.time();
		$data = json_decode(need::teacher_curl($url,[
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'refer'=>'https://baike.baidu.com/'
		]), 1);
		// print_r($data);exit;
		$info = $data['page'];
		if($info == 'search'){
			unset($this->array, $this->Msg);
			$this->array = array('code'=>-2, 'text'=>'百度百科暂未收录该词条');
			$this->returns();
			return;
		}else{
			$url = 'https:'.$data['url'];
			$url = need::teacher_curl($url,[
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'refer'=>$url,
			'loadurl'=>1
		])?:$url;
			$this->url = $url;
			$this->preg();
			return;
		}
	}
	protected function preg(){
		$url = $this->url;
		// echo $url;
		$data = need::teacher_curl($url,[
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'refer'=>$url,
			'cookie'=>'BAIDUID=FD50FCD835DE802B9E101B143C1C037D:FG=1; delPer=0; BAIDUID_BFESS=FD50FCD835DE802B9E101B143C1C037D:FG=1; BIDUPSID=FD50FCD835DE802B9E101B143C1C037D; PSTM=1672990528; BDUSS=MybVVDcDJWNGNWd3hyRDloV0E1c0hwSHo3RU15eE42TGFxQnliZjZDb3BndDlqRUFBQUFBJCQAAAAAAAAAAAEAAAAO4OVfwr27or-ow8trbQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACn1t2Mp9bdjQk; BDUSS_BFESS=MybVVDcDJWNGNWd3hyRDloV0E1c0hwSHo3RU15eE42TGFxQnliZjZDb3BndDlqRUFBQUFBJCQAAAAAAAAAAAEAAAAO4OVfwr27or-ow8trbQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACn1t2Mp9bdjQk; BDPASSGATE=IlPT2AEptyoA_yiU4SO_3lIN8eDEUsCD34OtVnti3ECGh67BmhH84dx0Flz0UWysGSzk-YyfmqkCpjrFV6xjg0N_gRsLfk28niSDvbmPzMbLS1ZDfMUkCbDJMyYUruDxkhUaz4wT_OVEVFoKewPJpuo4isOlre2Hah40skLrg_beZVmpBXb6r7WTX76fRHvYDsi1yujEaVJAVFeBUu4NKDTucloiPy1tx_7bi3Mq3Q45rkoUGv8dL3cA1G8kJppg0PmV1QC7zMGnDkscwnxzLl1ejSa86bjyZE9d_KvxfMpGVdPeSsCDVDrXBL1vctD1Or2iTAWZmtgWDV9S98sUBph5HsPVCXTEYjUOLdOFvAGmGpM-ulO2IwDLtIMQO10H_QlcZq5CTyoFizOfqBm4gCjo_3T_i0FvM0wOCpePu8gvmHdWBpbht57jc7Jv-p_fT7afE5XiGa4l3G2s-37DDb44K_r82VEbqUuloizSjSr9DwrTWPwJcy-77WxmTWiB9QePNo3XK3O2nr64tQHV84uz-oSUqzaZwyqfRNWpTx9DN-9zobBoInHWvYvtzHx_O4GdvI4n_VDDnD2L81uwpzB9a4A01NJKOtD0JPITwc_IopwyXC9to_Sz3wFfZ7nDv3ltB_qcX8D6GEnfSFFmpbl0ZDLmHJTzvtLgETVYEVCX0lTBBqhiw8NV5oEqPebbPDDFg3Q0PjiopwkjNNWJgmB4AvgzEi25vXzJ3-8NCRnx7xJmwEg46g1r4o8TYv0XbpUwgBlCA5_S62PUCreFoI1OspfegRZzWq_IWF8zu579FugP-nnzEFyXiSrIuQtne-Uk; BDORZ=FAE1F8CFA4E8841CC28A015FEAEE495D; H_WISE_SIDS=219946_231979_234044_219623_232959_235441_232777_234426_235174_232053_236811_234020_237833_131862_232248_235226_234295_235545_237586_235170_234306_236237_240344_240367_240397_240306_240460_237837_236537_240591_236653_238755_240889_240447_240466_240596_239947_240725_241207_240783_240650_241297_240035_238226_216847_224268_227932_213362_229967_211986_238455_214799_239102_238510_223064_219942_238507_213036_228650_229154_204903_226628_241566_240904_237892_238982_230288_239492_232628_241076_241813_241849_242024_241718_242040_240734_241780_242125_242054_241797_242225_242158_242387_242375_232321_242266_241687_241699_242477_242517_242489_242421_242753_242758_242542_238267_222222_242942_242936_242952_241601_241964_242498_242233_238328_243120_243047_242127_242466_242990; H_WISE_SIDS_BFESS=219946_231979_234044_219623_232959_235441_232777_234426_235174_232053_236811_234020_237833_131862_232248_235226_234295_235545_237586_235170_234306_236237_240344_240367_240397_240306_240460_237837_236537_240591_236653_238755_240889_240447_240466_240596_239947_240725_241207_240783_240650_241297_240035_238226_216847_224268_227932_213362_229967_211986_238455_214799_239102_238510_223064_219942_238507_213036_228650_229154_204903_226628_241566_240904_237892_238982_230288_239492_232628_241076_241813_241849_242024_241718_242040_240734_241780_242125_242054_241797_242225_242158_242387_242375_232321_242266_241687_241699_242477_242517_242489_242421_242753_242758_242542_238267_222222_242942_242936_242952_241601_241964_242498_242233_238328_243120_243047_242127_242466_242990; BA_HECTOR=2gaha105018h2g24aga4o1qk1hrns7p1k; PSCBD=31%3A1_16%3A1_25%3A1; SE_LAUNCH=5%3A1673172851__31%3A27887267_16%3A27887267_25%3A27887688; X-Use-Search-BFF=1; PSINO=2'
		]);
		// print_r($data);exit;
		preg_match('/<meta name="description" content="(.*?)">/', $data, $Msg);
		preg_match('/<link rel="apple-touch-icon-precomposed" href="(.*?)" \/>/', $data, $image);
		$Msg = @$Msg[1];
		$image = (isset($image[1]) && $image[1] ? (preg_match('/^http/', $image[1]) ? $image[1] : 'https:'.$image[1]) : null);
		if(empty($Msg) || empty($image)){
			unset($this->array, $this->Msg);
			$this->array = array('code'=>-2, 'text'=>'百度百科暂未收录该词条');
			$this->returns();
			return;
		}
		unset($this->array, $this->Msg);
		$this->array = array('code'=>1, 'text'=>'获取成功', 'data'=>array('Msg'=>$this->info['Msg'], 'info'=>$Msg, 'image'=>$image, 'url'=>$url));
		$this->returns();
		return;
	}
	protected function returns(){
		$type = $this->info['type'];
		$array = $this->array;
		if(isset($array['data']['info'])){
			Switch($type){
				case 'text':
				need::send('±img='.$array['data']['image']."±\n".$array['data']['info']."\n更多详情：".$this->url, 'text');
				break;
				default:
				need::send($array, 'json');
				break;
			}
			return;
		}else{
			Switch($type){
				case 'text':
				need::send($array['text'], 'text');
				break;
				default:
				need::send($array, 'json');
				break;
			}
			return;
		}
	}
}