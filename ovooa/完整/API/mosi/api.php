<?php
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(1); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

/* End */

/**
 * morse                     
 * @author     binwei <fubinwei_2008@163.com>
 * @copyright  (c) 2017 binwei <fubinwei_2008@163.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class morse
{
	//加密代码
	protected static $option = array(
		'space'	=> '/',
		'short'	=> '.',
		'long'	=> '-'
	);
	protected static $standard = array(
		/* Letters                               */
		'A' => '01',      /* A                   */
		'B' => '1000',    /* B                   */
		'C' => '1010',    /* C                   */
		'D' => '100',     /* D                   */
		'E' => '0',       /* E                   */
		'F' => '0010',    /* F                   */
		'G' => '110',     /* G                   */
		'H' => '0000',    /* H                   */
		'I' => '00',      /* I                   */
		'J' => '0111',    /* J                   */
		'K' => '101',     /* K                   */
		'L' => '0100',    /* L                   */
		'M' => '11',      /* M                   */
		'N' => '10',      /* N                   */
		'O' => '111',     /* O                   */
		'P' => '0110',    /* P                   */
		'Q' => '1101',    /* Q                   */
		'R' => '010',     /* R                   */
		'S' => '000',     /* S                   */
		'T' => '1',       /* T                   */
		'U' => '001',     /* U                   */
		'V' => '0001',    /* V                   */
		'W' => '011',     /* W                   */
		'X' => '1001',    /* X                   */
		'Y' => '1011',    /* Y                   */
		'Z' => '1100',    /* Z                   */
		/* Numbers                               */
		'0' => '11111',   /* 0                   */
		'1' => '01111',   /* 1                   */
		'2' => '00111',   /* 2                   */
		'3' => '00011',   /* 3                   */
		'4' => '00001',   /* 4                   */
		'5' => '00000',   /* 5                   */
		'6' => '10000',   /* 6                   */
		'7' => '11000',   /* 7                   */
		'8' => '11100',   /* 8                   */
		'9' => '11110',   /* 9                   */
		/* Punctuation                           */
		'.' => '010101',  /* Full stop           */
		',' => '110011',  /* Comma               */
		'?' => '001100',  /* Question mark       */
		'\'' => '011110', /* Apostrophe          */
		'!' => '101011',  /* Exclamation mark    */
		'/' => '10010',   /* Slash               */
		'(' => '10110',   /* Left parenthesis    */
		')' => '101101',  /* Right parenthesis   */
		'&' => '01000',   /* Ampersand           */
		' =>' => '111000',  /* Colon               */
		';' => '101010',  /* Semicolon           */
		'=' => '10001',   /* Equal sign          */
		'+' => '01010',   /* Plus sign           */
		'-' => '100001',  /* Hyphen1minus        */
		'_' => '001101',  /* Low line            */
		'"' => '010010',  /* Quotation mark      */
		'$' => '0001001', /* Dollar sign         */
		'@' => '011010',  /* At sign             */
	);
	protected static $standardReverse = null;

	protected static function defaultOption( $option = null )
	{
		$option = $option || [];
		return [
			isset($option['space']) ? $option['space'] : self::$option['space'],
			isset($option['short']) ? $option['short'] : self::$option['short'],
			isset($option['long']) ? $option['long'] : self::$option['long']
		];
	}

	//按utf分割字符串
	protected static function mbStrSplit($str )
	{
		/*
		字符串切割方式有多种
		function utf8_str_split($str, $split_len = 1)
		{
			if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1)
				return FALSE;
		 
			$len = mb_strlen($str, 'UTF-8');
			if ($len <= $split_len)
				return array($str);
		 
			preg_match_all('/.{'.$split_len.'}|[^\x00]{1,'.$split_len.'}$/us', $str, $ar);
		 
			return $ar[0];
		}
		function utf8_str_split($str )
		{
			$split = 1;
			$array = array();
			for( $i = 0; $i < strlen( $str ); )
			{
				$value = ord( $str[ $i ] );
				if( $value > 127 )
				{
					if( $value >= 192 && $value <= 223 )
						$split = 2;
					elseif( $value >=224 && $value <= 239 )
						$split = 3;
					elseif($value >= 240 && $value <= 247)
						$split = 4;
				}
				else
				{
					$split = 1;
				}
				$key = NULL;
				for( $j=0; $j<$split; $j++,$i++ )
				{
					$key .= $str[$i];
				}
				array_push( $array, $key );
			}
			return $array;
		}
		*/
		$len = 1;
		$start = 0;
		$strlen = mb_strlen($str);
		while ($strlen) 
		{
			$array[] = mb_substr($str,$start,$len,"utf8");
			$str = mb_substr($str, $len, $strlen,"utf8");
			$strlen = mb_strlen($str);
		}
		return $array;
	}

	/**
	 * Unicode字符转换成utf8字符
	 * @param  [type] $unicode_str Unicode字符
	 * @return [type]              Utf-8字符
	 */
	protected static function unicodeToUtf8( $unicode_str )
	{
		$utf8_str = '';
		$code = intval(hexdec($unicode_str));
		//这里注意转换出来的code一定得是整形，这样才会正确的按位操作
		$ord_1 = decbin(0xe0 | ($code >> 12));
		$ord_2 = decbin(0x80 | (($code >> 6) & 0x3f));
		$ord_3 = decbin(0x80 | ($code & 0x3f));
		$utf8_str = chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
		return $utf8_str;
	}

	protected static function charCodeAt($str, $index)
	{
		$char = mb_substr($str, $index, 1, 'UTF-8');
		if (mb_check_encoding($char, 'UTF-8'))
		{
			$ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
			return hexdec(bin2hex($ret));
		}
		else
		{
			return null;
		}
	}

	//摩斯码转文本
	protected static function morseHexUnicode( $mor ) 
	{
		$mor = bindec( $mor );
		if( !$mor )
		{
			return '';
		}
		else
		{
			$mor = dechex ( $mor );
		}
		return self::unicodeToUtf8( $mor );
	}

	//unicode转二进制
	public static function unicodeHexMorse( $ch )
	{
		$r = [];
		$length = mb_strlen( $ch, 'UTF8' );
		for( $i = 0; $i < $length; $i++ )
		{
			$r[$i] = substr(('00'. dechex( self::charCodeAt($ch,$i) )), -4 );
		}
		$r = join('',$r);
		return base_convert ( hexdec($r), 10, 2 );
	}

	//加密摩斯电码
	public static function encode( $text, $option = null )
	{
		$option = self::defaultOption($option); // 默认参数
		$morse = []; // 最终的 morse 结果
		// 删除空格，转大写，分割为数组
		$text = self::mbStrSplit( strtoupper( str_replace(' ', '', $text) ) );
		foreach( $text as $key => $ch )
		{
			$r = @self::$standard[ $ch ];
			if( !$r && $r != '0')
			{
				$r = self::unicodeHexMorse($ch); // 找不到，说明是非标准的字符，使用 unicode。
			}
			$morse[] = str_replace('1', $option[2], str_replace('0', $option[1], $r));
		}
		return join( $option[0], $morse );
	}

	//解密摩斯电码
	public static function decode( $morse, $option = null )
	{
		if( self::$standardReverse === null )
		{
			foreach( self::$standard as $key => $value )
			{
				self::$standardReverse[$value] = $key;
			}
		}

		$option = self::defaultOption( $option );
		$msg = [];
		$morse = explode( $option[0], $morse ); // 分割为数组
		foreach( $morse as $key => $mor )
		{
			$mor = str_replace(' ', '', $mor );
			$mor = str_replace( $option[2], '1', str_replace( $option[1], '0', $mor ) );
			
			$r = @self::$standardReverse[ $mor ];
			if( !$r )
			{
				$r = self::morseHexUnicode($mor); // 找不到，说明是非标准字符的 morse，使用 unicode 解析方式。
			}
			$msg[] = $r;
		}
		return join( '', $msg );
	}
}

//+------------------------------------------------------------------------------
//|代码参考源自:https://gitee.com/hustcc/xmorse
//|关于摩尔密码 | Morse Code

//|	摩尔斯电码（Morse alphabet）（又译为摩斯电码）是一种时通时断的信号代码，这种信号代码通过不同的排列顺序来表达不同的英文字母、数字和标点符号等。
//|	由美国人摩尔斯（Samuel Finley Breese Morse）于1837年发明，为摩尔斯电报机的发明（1835年）提供了条件。
//|	摩尔密码加密的字符只有字符，数字，标点，不区分大小写，支持中文汉字
//|	中文摩斯加密解密：本工具摩尔密码加密是互联网上唯一一个可以对中文进行摩斯编码的工具。

//以下参考资料源自:http://www.atool.org/morse.php
//@http://www.atool.org/morse.php网址里面 里面 & 电码符号是错误的,和H的一样了，应该是作者写错了 & 电码符号应该是 .-...
//|莫尔斯电码加密列表 | Morse Code List
//|一、26个字母的莫尔斯电码加密(最多4个符号)
//| +------+----------+------+----------+------+----------+------+----------+
//| | 字符 | 电码符号 | 字符 | 电码符号 | 字符 | 电码符号 | 字符 | 电码符号 |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  A   |   . -    |  B   |   -...   |  C   |   -.-.   |  D   |   -..    |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  E   |   .      |  F   |   ..-.   |  G   |   --.    |  H   |   ....   |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  I   |   ..     |  J   |   .---   |  K   |   -.-    |  L   |   -..    |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  M   |   --     |  N   |   -.     |  O   |   ---    |  P   |   .--.   |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  Q   |   --.-   |  R   |   .-.    |  S   |   ...    |  T   |   -      |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  U   |   ..-    |  V   |   ...-   |  W   |   .--    |  X   |   -..-   |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  Y   |   -.--   |  Z   |   --..   |      |          |      |          |
//| +------+----------+------+----------+------+----------+------+----------+
//|二、数字的莫尔斯电码加密(5个符号)
//| +------+----------+------+----------+------+----------+------+----------+
//| | 字符 | 电码符号 | 字符 | 电码符号 | 字符 | 电码符号 | 字符 | 电码符号 |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  0   |   -----  |  1   |   .----  |  2   |   ..---  |  3   |   ...--  |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  4   |   ....-  |  5   |   .....  |  6   |   -....  |  7   |   --...  |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  8   |   ---..  |  9   |   ----.  |      |          |      |          |
//| +------+----------+------+----------+------+----------+------+----------+
//|三、标点符号的莫尔斯电码加密(6个符号)
//| +------+----------+------+----------+------+----------+------+----------+
//| | 字符 | 电码符号 | 字符 | 电码符号 | 字符 | 电码符号 | 字符 | 电码符号 |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  .   |  .-.-.-  |  :   |  ---...  |  ,   |  --..--  |  ;   |  -.-.-.  |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  ?   |  ..--..  |  =   |  -...-   |  '   |  .----.  |  /   |  -..-.   |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  !   |  -.-.--  |  -   |  -....-  |  _   |  ..--.-  |  "   |  .-..-.  |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  (   |  -.--.   |  )   |  -.--.-  |  $   |  ...-..- |  &   |  .-...   |
//| +------+----------+------+----------+------+----------+------+----------+
//| |  @   |  .--.-.  |      |          |      |          |      |          |
//| +------+----------+------+----------+------+----------+------+----------+
//|摩斯密码灯光求救 | Morse Code

//|	1.摩斯密码编码简单清晰，二义性小，编码主要是由两个字符表示："."、"-"，一长一短，这在很多情况下应用很多，比如发送求救信号。电影《风声》中就是采用在衣服上缝出摩尔密码，将消息传播出去。
//|	2.在利用摩尔密码灯光求救的时候，定义：灯光长亮为"-"，灯光短量为"."，那么就可以通过手电筒的开关来发送各种信息，例如求救信息。
//|	3.如果灯光是按照“短亮 暗 短亮 暗 短亮 暗 长亮 暗 长亮 暗 长亮 暗 短亮 暗 短亮 暗 短亮”这个规律来显示的话那么它就意味是求救信号SOS。
//|	4.因为SOS的摩尔编码为：··· －－－ ··· ，按照上面的规定即可进行灯光编码。这个编码其实非常简单，三短、三长、三短。
//|	5.除了灯光之外，利用声音（两种区别的声音）也可以发出求救信号。这种求救方式是我们都应该进行了解的，也许在必要的时候就可以派上用场。
//+------------------------------------------------------------------------------
//我这里用php代码实现了一遍
//测试代码

$msg = @$_REQUEST["msg"];

$encode = @$_REQUEST["code"];

$type = @$_REQUEST["type"];

if($msg == '' ){

if($type == 'text'){

exit('缺少必要参数');

}else{

exit(need::json(array('code'=>-1,'text'=>'缺少必要参数')));

}

}

if($encode == 'decode'){

if($type == 'text'){

exit(morse::decode($msg));

}else{

exit(need::json(array('code'=>1,'text'=>morse::decode($msg))));

}

}else{

if($type == 'text'){

exit(morse::encode($msg));

}else{

exit(need::json(array('code'=>1,'text'=>morse::encode($msg))));

}

}





