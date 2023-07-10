<?php
header('Content-type: application/json; charset=utf-8');
require_once __DIR__ . '/../../need.php';
require ("../function.php"); // 引入函数文件
addApiAccess(177); // 调用统计函数
addAccess();//调用统计函数
$request = need::request();
$city = isset($request['city']) ? $request['city'] : false;
$type = isset($request['type']) ? $request['type'] : false;

new weather(['city'=>$city, 'type'=>$type]);
class weather
{
	public $array = [];
	public $message;
	public function __construct(public $info = [])
	{
		$this->parametersException();
	}
	public function parametersException()
	{
		if(!isset($this->info['city']) || !$this->info['city'])
		{
			return $this->exec(['code'=>-1, 'text'=>'请输入地区']);
		} else {
			$this->info['city'] = preg_replace('/(省|县|区|市|(壮|回|维吾尔|延边朝鲜|恩施土家苗|湘西土家苗|阿坝藏羌|甘孜藏|凉山彝|黔东南苗侗|黔南布依苗|黔西南布依苗|楚雄彝|红河哈尼彝|文山壮苗|西双版纳傣|大理白|德宏傣景颇|怒江傈僳|迪庆藏|临夏回|甘南藏|海南藏|海北藏|海西蒙古藏|黄南藏|果洛藏|玉树藏|伊犁哈萨克|博尔塔拉蒙古|昌吉回|巴音郭楞蒙古|克孜勒苏柯尔克孜)*(族)*自治(区|州|市|县)*)*/', '', $this->info['city']);
		}
		return $this->weather();
	}
	public function weather()
	{
		if($city = $this->is_city($this->info['city']))
		{
			$url = "http://d1.weather.com.cn/weather_index/${city}.html";
			$data = preg_split('/[;]*var .*?=/', need::teacher_curl($url, [
				'refer'=>'http://www.weather.com.cn/'
			]));
			if(!$data[0]) array_shift($data);
			if($data)
			{
				$weather = json_decode($data[0])->weatherinfo;
				// print_r($data);exit;
				$weather2 = json_decode($data[1]);
				$weather2 = isset($weather2->w[0]) ? $weather2->w[0] : $weather2->w;
				$weather3 = json_decode($data[2]);
				$weather4 = array_values(json_decode($data[3], true)['zs']);
				/*
				print_r($weather);
				print_r($weather2);
				print_r($weather3);
				*/
				array_shift($weather4);
				$array = [
					'code'=>1,
					'text'=>'获取成功',
					'data'=>[
						'city'=>$weather->city,
						'cityEnglish'=>$weather->cityname,
						'temp'=>$weather->temp,
						'tempn'=>$weather->tempn,
						'weather'=>$weather->weather,
						'wind'=>$weather->wd,
						'windSpeed'=>$weather->ws,
						'time'=>date('Y-m-d') . ' 08:00',
						'warning'=>(Object) [],
						'current'=>[
							'city'=>$weather3->cityname,
							'cityEnglish'=>$weather3->nameen,
							'humidity'=>$weather3->sd,
							'wind'=>$weather3->WD,
							'windSpeed'=>$weather3->WS,
							'visibility'=>$weather3->njd,
							'weather'=>$weather3->weather,
							'weatherEnglish'=>$weather3->weathere,
							'temp'=>$weather3->temp,
							'fahrenheit'=>$weather3->tempf,
							// 'tempn'=>$weather->tempn,
							'air'=>$weather3->aqi,
							'air_pm25'=>$weather3->aqi_pm25,
							'date'=>$weather3->date,
							'time'=>$weather3->time,
							'image'=>$this->get_Image($weather3->weather)
						],
						'living'=>[
						]
					]
				];
				if($weather2)
				{
					$array['data']['warning'] = [
						'windSpeed'=>isset($weather2->w4) ? $weather2->w4 : null,
						'wind'=>isset($weather2->w5) ? $weather2->w5 : null,
						'color'=>isset($weather2->w7) ? $weather2->w7 : null,
						'warning'=>isset($weather2->w9) ? $weather2->w9 : null,
						'time'=>isset($weather2->w15) ? $weather2->w15 : null
					];
				}
				foreach(range(0, 89, 3) as $k=>$v)
				{
					$array['data']['living'][$k] = [
						'name'=>$weather4[$v],
						'index'=>$weather4[($v + 1)],
						'tips'=>$weather4[$v + 2]
					];
				}
				$message = "地区：{$weather3->cityname}\n天气：{$weather3->weather}";
				if(isset($weather3->WD) && $weather3->WD)
				{
					$message .= "\n风向：{$weather3->WD}\n风速：{$weather3->WS}";
				}
				$message .= "\n能见度：{$weather3->njd}\n空气指数：{$weather3->aqi}\n更新时间：{$weather3->date} {$weather3->time}";
				return $this->exec($array, $message);
			} else {
				return $this->exec(['code'=>-3, 'text'=>"不支持该地区查询"]);
			}
		} else {
			return $this->exec(['code'=>-2, 'text'=>"`{$this->info['city']}` 可能不是一个地区或不支持该地区查询"]);
		}
	}
	public function is_city($city, $switch = 'region')
	{
		$city_region = $city;
		$data = json_decode(file_get_contents(__DIR__ . '/cache/city.json'), true);
		switch($switch) {
			case 'region':
				if($data)
				{
					$keys = (array_keys($data));
					foreach($keys as $key)
					{
						if(str_starts_with($city, $key))
						{
							$region = str_replace($key, '', $city);
							if($region)
							{
								$city = $this->is_city($region, 'city');
								break;
							} else {
								// print_r($keys);exit;
								$city_key = array_keys($data[$key]);
								// print_r($key);exit;
								$city = $data[$key][$city_key[0]][$city_key[0]]['AREAID'];
								// print_r($city);exit;
								break;
							}
						}
					}
					if(!$city || $city == $city_region) $city = $this->is_city($city, 'city');
				} else {
					return false;
				}
			break;
			case 'city':
			// echo '很难不支持';
				foreach(array_keys($data) as $v)
				{
					foreach($data[$v] as $val)
					{
						$keys = array_keys($val);
						foreach($keys as $key)
						{
							// print_r($key);exit;
							if(str_starts_with($city, $key))
							{
								$region = str_replace($key, '', $city);
								if($region)
								{
									$city = $this->is_city($region, 'city');
									break;
								} else {
									$city = $val[$city]['AREAID'];
									
									break;
								}
							}
						}
						unset($keys, $key);
					}
				}
			break;
		}
		return is_numEric($city) ? $city : false;
	}
	public function get_Image($weather = '晴')
	{
		if($weather == '晴' && (date('H') < 5 || date('H') > 19))
		{
			$weather = '晚上晴';
		}
		if(file_exists(__DIR__ . "/cache/image/${weather}.png"))
		{
			return "http://ovooa.com/API/weather/cache/image/${weather}.png";
		} else {
			return "http://ovooa.com/API/weather/cache/image/云.png";
		}
	}
	public function exec($array, $message = null)
	{
		$message = $message ? $message : $array['text'];
		switch($this->info['type'])
		{
			case 'text':
				need::send($message, 'text');
			break;
			default:
				need::send($array, 'json');
			break;
		}
		return true;
	}
}