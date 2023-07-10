<?
header('content-type: application/json');
require __DIR__.'/../../need.php';
$request = need::request();
// print_r($request);
$msg = isset($request['msg']) ? $request['msg'] : false;
$translate = isset($request['translate']) ? $request['translate'] : 'EN';
$type = isset($request['type']) ? $request['type'] : 10;
// print_r(json_decode('{"jsonrpc":"2.0","method": "LMT_handle_jobs","params":{"jobs":[{"kind":"default","sentences":[{"text":"身材丰满的少女","id":0,"prefix":""}],"raw_en_context_before":[],"raw_en_context_after":[],"preferred_num_beams":4}],"lang":{"preference":{"weight":{},"default":"default"},"source_lang_computed":"ZH","target_lang":"EN"},"priority":1,"commonJobParams":{"regionalVariant":"en-US","mode":"translate","browserType":1},"timestamp":1670075612611},"id":0}', true));exit;
new deepl(['msg'=>$msg, 'translate'=>$translate, 'type'=>$type]);
class deepl
{
	private $info = [];
	public $array = [];
	public $message;
	public $translate = [
		'DA'=>[
			'name'=>'丹麦语',
			'var'=>false
		],
		'UK'=>[
			'name'=>'乌克兰语',
			'var'=>false
		],
		'RU'=>[
			'name'=>'俄语',
			'var'=>false
		],
		'BG'=>[
			'name'=>'保加利亚语',
			'var'=>false
		],
		'HU'=>[
			'name'=>'匈牙利语',
			'var'=>false
		],
		'ID'=>[
			'name'=>'印尼语',
			'var'=>false
		],
		'TR'=>[
			'name'=>'土耳其语',
			'var'=>false
		],
		'EL'=>[
			'name'=>'希腊语',
			'var'=>false
		],
		'DE'=>[
			'name'=>'德语',
			'var'=>false
		],
		'IT'=>[
			'name'=>'意大利语',
			'var'=>false
		],
		'LV'=>[
			'name'=>'拉脱维亚语',
			'var'=>false
		],
		'CS'=>[
			'name'=>'捷克语',
			'var'=>false
		],
		'SK'=>[
			'name'=>'斯洛伐克语',
			'var'=>false
		],
		'SL'=>[
			'name'=>'斯洛文尼亚语',
			'var'=>false
		],
		'JA'=>[
			'name'=>'日语',
			'var'=>false
		],
		'FR'=>[
			'name'=>'法语',
			'var'=>false
		],
		'PL'=>[
			'name'=>'波兰语',
			'var'=>false
		],
		'ET'=>[
			'name'=>'爱沙尼亚语',
			'var'=>false
		],
		'SV'=>[
			'name'=>'瑞典语',
			'var'=>false
		],
		'LT'=>[
			'name'=>'立陶宛语',
			'var'=>false
		],
		'RO'=>[
			'name'=>'罗马尼亚语',
			'var'=>false
		],
		'FI'=>[
			'name'=>'芬兰语',
			'var'=>false
		],
		'EN'=>[
			'name'=>'美式英语',
			'var'=>'en-US'
		],
		'EN_GB'=>[
			'name'=>'英式英语',
			'var'=>'en-GB'
		],
		'NL'=>[
			'name'=>'荷兰语',
			'var'=>false
		],
		'PT'=>[
			'name'=>'葡萄牙语',
			'var'=>'pt-PT'
		],
		'PT_BR'=>[
			'name'=>'巴西葡萄牙语',
			'var'=>'pt-BR'
		],
		'ES'=>[
			'name'=>'西班牙语',
			'var'=>false
		]
	];
	public function __construct($array)
	{
		foreach($array as $k=>$v)
		{
			$this->info[$k] = $v;
		}
		$this->parametersException();
	}
	private function parametersException()
	{
		if(!isset($this->info['msg']) || !$this->info['msg']) return $this->exec(['code'=>-1, 'text'=>'请输入需要翻译的内容']);
		$array = $this->translate;
		$keys = array_keys($array);
		// print_r($keys);
		$msg = mb_strtoupper($this->info['translate']);
		// echo $msg;
		if(!in_array($msg, $keys))
		{
			foreach($array as $k=>$v)
			{
				if(strstr($v['name'], $msg))
				{
					$msg = $k;
					break;
				}
			}
			if(!in_array($msg, $keys)) $msg = 'EN';
		}
		$this->info['translate'] = $msg;
		return $this->start();
	}
	public function start()
	{
		$info = $this->info;
		// print_r($info);
		$url = 'https://www2.deepl.com/jsonrpc?method=LMT_handle_jobs';
		$translate = $info['translate'];
		switch($info['translate'])
		{
			case 'EN_GB':
				$translate = 'EN';
			break;
			case 'PT_BR':
				$translate = 'PT';
			break;
		}
		$post = [
			'jsonrpc'=>'2.0',
			'method'=>'LMT_handle_jobs',
			'params'=>[
				'jobs'=>[
					[
						'kind'=>'default',
						'sentences'=>[
							[
								'text'=>'身材丰满的少女',
								'id'=>0,
								'prefix'=>''
							]
						],
						'raw_en_context_before'=>[],
						'raw_en_context_after'=>[],
						'preferred_num_beams'=>4
					]
				],
				'lang'=>[
					'preference'=>[
						'weight'=>[''=>''],
						'default'=>'default'
					],
					'source_lang_computed'=>'ZH',
					'target_lang'=>$translate
				],
				'priority'=>1,
				'commonJobParams'=>[
					'regionalVariant'=>($this->translate[$translate] ? $this->translate[$translate]['var'] : false),
					'mode'=>'translate',
					'browserType'=>1
				],
				'timestamp'=>need::getmillisecond()
			],
			'id'=>0
		];
		$post = '{"jsonrpc":"2.0","method": "LMT_handle_jobs","params":{"jobs":[{"kind":"default","sentences":[{"text":"'.$info['msg'].'","id":0,"prefix":""}],"raw_en_context_before":[],"raw_en_context_after":[],"preferred_num_beams":4}],"lang":{"preference":{"weight":{},"default":"default"},"source_lang_computed":"ZH","target_lang":"'.$translate.'"},"priority":1,"commonJobParams":{'.($this->translate[$translate]['var'] ? '"regionalVariant":"'.$this->translate[$translate]['var'].'",' : '').'"mode":"translate","browserType":1},"timestamp":'.need::getmillisecond().'},"id":'.rand(100000, 10000000).'}';
		// echo $post;
		$data = json_decode(need::teacher_curl($url, [
			'post'=>($post),
			'Header'=>[
				'Host: www2.deepl.com',
				'Connection: keep-alive',
				'Content-Length: '.strlen(($post)),
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
				'Content-type: application/json',
				'Accept: */*',
				'Origin: https://www.deepl.com',
				'X-Requested-With: mark.via',
				'Sec-Fetch-Site: same-site',
				'Sec-Fetch-Mode: cors',
				'Sec-Fetch-Dest: empty',
				'Referer: https://www.deepl.com/translator',
				'Accept-Encoding: gzip, deflate',
				'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
			],
			'refer'=>'https://www.deepl.com/translator',
			'ua'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
		]), true);
		if(isset($data['error']['message'])) return $this->exec(['code'=>-2, 'text'=>$data['error']['message']]);
		$string = false;
		foreach($data['result']['translations'][0]['beams'] as $v)
		{
			if(isset($v['sentences'][0]['text']) && $v['sentences'][0]['text'])
			{
				$string = $v['sentences'][0]['text'];
				break;
			}
		}
		return ($string === false ? $this->exec(['code'=>-3, 'text'=>'获取失败，内部错误']) : $this->exec(['code'=>1, 'text'=>$string]));
	}
	public function exec($array, $message = null)
	{
		$this->message = $message ? $message : $array['text'];
		$this->array = $array;
		return $this->result();
	}
	public function result()
	{
		$info = $this->info;
		$type = isset($info['type']) ? $info['type'] : 'json';
		Switch($type)
		{
			case 'text':
			need::send($this->message, 'text');
			break;
			default:
			$help = [];
			foreach($this->translate as $k=>$v)
			{
				$help[$v['name']] = $k;
			}
			$this->array['data']['help'] = $help;
			need::send($this->array, 'json');
			break;
		}
		// print_r($this->array);
		return true;
	}
}