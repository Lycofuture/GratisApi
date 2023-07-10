<?php
require '../../curl.php';
$url = @$_REQUEST['url'];
$host = @$_SERVER["HTTP_HOST"];
if($url){
    $String = need::teacher_curl($url);
    if($String){
        file_put_contents(md5($url),$String);
        /*
        $image = imagecreatefromString($String);
        imagepng($image,md5($url));
        */
        try{
            $imagick = new imagick(md5($url));
        } catch (\Exception $e){
            unlink(md5($url));
            die('图片错误');
        }
        $format = $imagick->getimageformat();
        if($format == 'GIF'){
            unlink(md5($url));
            die('不可以是动图');
        }
        $size = $imagick->getimagegeometry();//获取宽高数据
        $width = $size['width'];//宽度
        $height = $size['height'];//高度
        if($width > 100 || $height > 100){
            if($width == $height){
                $width = 100;
                $height = 100;
            }else
            if($width > $height){
                $zhi = (100 / $width);
                $width = 100;
                $height = ($height * $zhi);
            }else{
                $zhi = (100 / $height);
                $height = 100;
                $width = ($width * $zhi);
            }
            $imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);//修改头像框大小与头像一样
        }
        $imagick->writeImages(md5($url),true);
        $file_name = md5($url);
    }else{
        die('图片错误');
    }
}else{
    die('链接错误');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,hight=device-hight,minimum-scale=1.0,maximum-scale=1.0,ser-scalable=none"/>
<title><?php echo $_REQUEST['name']?:'忍の术'; ?></title>

<style type="text/css">
        body {
            margin: 0;
            padding: 0;
            position: relative;
            //background-image: url(<?php if($file_name && is_file($file_name)){ echo './'.$file_name; }else{echo './fadou/skin/images/feel.png' ;} ?>);
            //background-position: center;
            /*background-repeat: no-repeat;*/
            ///width: 100%; height: 100%;
            //background-size: 100% 100%;
        }

#jk {
    width: 30%;
    position: fixed;
    right: 10px;
    bottom: 40px;
}


</style>

</head>
<body id="body" onLoad="init()">
    
<script type="text/javascript" src="http://ovooa.com/API/renzhe/skin/js/threecanvas.js"></script>
<script type="text/javascript" src="http://ovooa.com/API/renzhe/skin/js/snow.js"></script>

<script type="text/javascript">
	var SCREEN_WIDTH = window.innerWidth;//
	var SCREEN_HEIGHT = window.innerHeight;
	var container;
	var particle;//粒子

	var camera;
	var scene;
	var renderer;

	var starSnow = 1;

	var particles = []; 

	var particleImage = new Image();
	//THREE.ImageUtils.loadTexture( "img/wj.png" );
	particleImage.src = '<?php if($file_name){ echo "http://{$host}/API/renzhe/".md5($url); }else{ echo "http://{$host}/API/renzhe/fadou/skin/images/feel.png" ;} ?>'; 
	

	function init() {
		//alert("message3");
		container = document.createElement('div');//container：画布实例;
		document.body.appendChild(container);

		camera = new THREE.PerspectiveCamera( 60, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000 );
		camera.position.z = 1000;
		//camera.position.y = 50;

		scene = new THREE.Scene();
		scene.add(camera);
			
		renderer = new THREE.CanvasRenderer();
		renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
		var material = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(particleImage) } );
			//alert("message2");
		for (var i = 0; i < 500; i++) {
			//alert("message");
			particle = new Particle3D( material);
			particle.position.x = Math.random() * 2000-1000;
			
			particle.position.z = Math.random() * 2000-1000;
			particle.position.y = Math.random() * 2000-1000;
			//particle.position.y = Math.random() * (1600-particle.position.z)-1000;
			particle.scale.x = particle.scale.y =  1;
			scene.add( particle );
			
			particles.push(particle); 
		}

		container.appendChild( renderer.domElement );


		//document.addEventListener( 'mousemove', onDocumentMouseMove, false );
		document.addEventListener( 'touchstart', onDocumentTouchStart, false );
		document.addEventListener( 'touchmove', onDocumentTouchMove, false );
		document.addEventListener( 'touchend', onDocumentTouchEnd, false );
		
		setInterval( loop, 1000 / 60 );
		
	}

	var touchStartX;
	var touchFlag = 0;//储存当前是否滑动的状态;
	var touchSensitive = 80;//检测滑动的灵敏度;
	//var touchStartY;
	//var touchEndX;
	//var touchEndY;
	function onDocumentTouchStart( event ) {

		if ( event.touches.length == 1 ) {

			//event.preventDefault();//取消默认关联动作;
			touchStartX = 0;
			touchStartX = event.touches[ 0 ].pageX ;
			//touchStartY = event.touches[ 0 ].pageY ;
		}
	}


	function onDocumentTouchMove( event ) {

		if ( event.touches.length == 1 ) {
			//event.preventDefault();
			var direction = event.touches[ 0 ].pageX - touchStartX;
			if (Math.abs(direction) > touchSensitive) {
				if (direction>0) {touchFlag = 1;}
				else if (direction<0) {touchFlag = -1;};
				//changeAndBack(touchFlag);
			}	
		}
	}

	function onDocumentTouchEnd (event) {
		// if ( event.touches.length == 0 ) {
		// 	event.preventDefault();
		// 	touchEndX = event.touches[ 0 ].pageX ;
		// 	touchEndY = event.touches[ 0 ].pageY ;
	
		// }这里存在程序
		var direction = event.changedTouches[ 0 ].pageX - touchStartX;

		changeAndBack(touchFlag);
	}


	function changeAndBack (touchFlag) {
		var speedX = 25*touchFlag;
		touchFlag = 0;
		for (var i = 0; i < particles.length; i++) {
			particles[i].velocity=new THREE.Vector3(speedX,-10,0);
		}
		var timeOut = setTimeout(";", 800);
		clearTimeout(timeOut);

		var clearI = setInterval(function () {
			if (touchFlag) {
				clearInterval(clearI);
				return;
			};
			speedX*=0.8;

			if (Math.abs(speedX)<=1.5) {
				speedX=0;
				clearInterval(clearI);
			};
			
			for (var i = 0; i < particles.length; i++) {
				particles[i].velocity=new THREE.Vector3(speedX,-10,0);
			}
		},100);


	}


	function loop() {
		for(var i = 0; i<particles.length; i++){
			var particle = particles[i]; 
			particle.updatePhysics(); 

			with(particle.position)
			{
				if((y<-1000)&&starSnow) {y+=2000;}

				if(x>1000) x-=2000; 
				else if(x<-1000) x+=2000;
				if(z>1000) z-=2000; 
				else if(z<-1000) z+=2000;
			}			
		}

		camera.lookAt(scene.position); 

		renderer.render( scene, camera );
	}
</script>



</body>
</html>
<?php
fastcgi_finish_request();//先返回上面的内容
time_sleep_until(time()+10);//延迟10秒后执行下面的命令
unlink('./'.md5($url));
?>