<?php
header('Content-type:application/json');
require_once '../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(167); // 调用统计函数
addAccess();//调用统计函数
$url = $_REQUEST['url'];
$password = @$_REQUEST['password'];
$type = @$_REQUEST['type'];
new lanz(array('url'=>$url, 'password'=>$password, 'type'=>$type));
class lanz
{
	private $info = array();
	private $array = array();
	private $Message;
	private $host;
	public function __construct(array $array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->parameterException();
	}
	private function parameterException()
	{
		$info = $this->info;
		$url = $info['url'];
		if(!stristr($url, 'lanzou') && $info['type'] !== 'highlight')
		{
			$this->evaluation(array('code'=>-1, 'text'=>'错误的链接'));
			return false;
		}
		preg_match('/https:\/\/(.*?)\//', $url, $host);
		$this->host = $host;
		if(stristr($url, '/tp/'))
		{
			$url = str_replace('/tp/', '/', $url);
			$this->info['url'] = $url;
		}
		$info['type'] === 'highlight' ? $this->evaluation(array()) : $this->getfile();
		return true;
	}
	private function getfile()
	{
		$url = $this->info['url'];
		$password = $this->info['password'];
		$data = $this->curlhtml($url);
		preg_match('/var link = \'(.*)\';/', $data, $thisurl);
		$fileurl = 'https://lanzouw.com/'.$thisurl[1];
		// echo $fileurl;
		$data = $this->curlhtml($fileurl);
		if(strstr($data, '输入密码'))
		{
			if(!$password)
			{
				$this->evaluation(array('code'=>-2, 'text'=>'文件需要密码'));
				return false;
			}
			// echo $data;
			$preg_sign = ['posign', 'postsign'];
			foreach($preg_sign as $v)
			{
				preg_match('/'.$v.' = \'(.*?)\'/', $data, $sign);
				if(isset($sign[1]) && $sign[1] && $sign[1] != '?') break;
			}
			// print_r($sign);
			if(!isset($sign[1]) || !$sign[1])
			{
				$this->evaluation(array('code'=>-3, 'text'=>'遇到了解决不了的问题，你可以重新试试'));
				return false;
			}
			$post = 'action=downprocess&sign='.$sign[1].'&p='.$password;
			$data = json_decode(need::teacher_curl('https://'.$this->host[1].'/ajaxm.php', [
				'post'=>$post,
					'Header'=>[
					'Host: '.$this->host[1]?:'lanzouw.com',
					'Connection: keep-alive',
					'Content-Length: '.(Int) (strlen($post)),
					'Accept: application/json, text/javascript, */*',
					'X-Requested-With: XMLHttpRequest',
					'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
					'Content-Type: application/x-www-form-urlencoded',
					'Origin: https://wwu.lanzouw.com',
					'Sec-Fetch-Site: same-origin',
					'Sec-Fetch-Mode: cors',
					'Sec-Fetch-Dest: empty',
					'Referer: '.$fileurl,
					'Accept-Encoding: gzip, deflate',
					'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
				],
				'refer'=>$fileurl,
				'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
				'cookie'=>'codelen=1; STDATA83=czst_eid%3D1587512080-3821-%26ntime%3D3821; UM_distinctid=18058b3c9dab7-0ee65841016f1e-625a4b3e-46500-18058b3c9dbc0; Hm_lvt_1cba67fc6b903abd7314b5f70beef01a=1650755620; Hm_lpvt_1cba67fc6b903abd7314b5f70beef01a=1650755620; BAIDU_SSP_lcr=https://mzf.fateqqq.com/work.html; CNZZDATA1253610888=193320452-1651238965-%7C1651781004; CNZZDATA1253610883=358924570-1651239030-%7C1651779730; Hm_lvt_fb7e760e987871d56396999d288238a4=1654218662; m_adb1=1; uz_distinctid=1813951adc1211-0c15e9887925af-7375305a-46500-1813951adc2244; m_ad2=2; Hm_lpvt_fb7e760e987871d56396999d288238a4='.Time()
			]), true);
			if($data['zt'] !== 1)
			{
				$this->evaluation(array('code'=>-4, 'text'=>$data['inf']));
				return false;
			}
			$url = $data['dom'].'/file/'.$data['url'];
			$filename = $data['inf'];
		}else{
			$url = null;
			$preg = [['pototo', 'spototo'], ['tedomain', 'domianload']];
			foreach($preg as $v)
			{
				preg_match('/'.$v[0].' = \'(.*?)\';/', $data, $download);
				preg_match('/'.$v[1].' = \'(.*?)\';/', $data, $fileurl);
				$url = $download[1].$fileurl[1];
				if($url) break;
			}
			preg_match('/<div class="md">(.*?)<span class="mtt">/', $data, $filename);
			$filename = $filename[1];
		}
		// print_r($data);
		$url = need::loadurl($url,[
			'Header'=>[
				'Host: developer.lanzoug.com',
				'Connection: keep-alive',
				'Upgrade-Insecure-Requests: 1',
				'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
				'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
				'dnt: 1',
				'X-Requested-With: mark.via',
				'Sec-Fetch-Site: cross-site',
				'Sec-Fetch-Mode: navigate',
				'Sec-Fetch-User: ?1',
				'Sec-Fetch-Dest: document',
				'Accept-Encoding: gzip, deflate',
				'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
			],
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'GetCookie'=>false,
			'refer'=>$fileurl
		]);
		if(!$url)
		{
			preg_match('/cppat = \'(.*?)\';/', $data, $caap);
			preg_match('/cppat \+ \'(.*?)\'/', $data, $href);
			// print_r($data);
			if(!$caap || !$href)
			{
				$this->evaluation(array('code'=>-5, 'text'=>'文件有密码'));
				return false;
			} else {
				$url = $caap[1].$href[1];
				$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>array('url'=>$url,'name'=>$filename)), '文件名：'.$filename."\n文件链接：{$url}");
				return true;
			}
		}
		$this->evaluation(array('code'=>1, 'text'=>'获取成功', 'data'=>array('url'=>$url,'name'=>$filename)), '文件名：'.$filename."\n文件链接：{$url}");
		return true;
	}
	private function curlhtml($url)
	{
		$data = need::teacher_curl($url, [
			'Header'=>[
				'Host: '.$this->host[1]?:'lanzouw.com',
				'Connection: keep-alive',
				'Upgrade-Insecure-Requests: 1',
				'User-Agent: Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
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
			'ua'=>'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36',
			'GetCookie'=>false
		]);
		return $data;
	}
	private function delete()
	{
		/* 删除可能存在的变量 */
		unset($this->array, $this->Message);
		return true;
	}
	private function evaluation(array $array, $string = false)
	{
		/* 统一回复 */
		$this->delete();
		$this->array = $array;
		$this->Message = $string ? $string : $array['text'];
		$this->result();
		return;
	}
	private function highlight()
	{
		Header('content-type: text/html; charset=utf-8');
		echo (highlight_file(__FILE__));
		return;
	}
	private function result()
	{
		/*发送 */
		Switch($this->info['type'])
		{
			case 'text':
			need::send($this->Message, 'text');
			break;
			case 'highlight':
			$this->highlight();
			break;
			default:
			need::send($this->array);
			break;
		}
		return;
	}
}

