<?php


	class Bullshit
	{

		/**
		 * @param string $title  标题
		 * @param int    $length 长度
		 * @param string $data   语料位置
		 * @return string
		 * @author     :  Wangqs  2021/4/7
		 * @description:  主要生成方法
		 */
		public static function generator ( $title = 'hello world' , $length = 200 , $data = 'data.json' ) {
			$body = '';
			//$data = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . $data;
			if($length > 50000 || $length < 10) $length = 200;
			$data = json_decode( file_get_contents( $data ) , true );
			while ( strlen( $body ) / 3 < $length ) {
				$num = rand( 0 , 100 );
				if ( $num < 10 ) {
					$body .= "\r\n";
				}
				else if ( $num < 20 ) {
					$body .= $data["famous"][array_rand( $data["famous"] , 1 )];
					$replace = [ $data["before"][array_rand( $data["before"] , 1 )] , $data['after'][array_rand( $data['after'] , 1 )] ];
					$find = [ 'a' , 'b' ];
					$body = str_replace( $find , $replace , $body );
				}
				else {
					$body .= $data["bosh"][array_rand( $data["bosh"] , 1 )];
				}
				$body = str_replace( "x" , $title , $body );
			}
			return $body;
		}

	}