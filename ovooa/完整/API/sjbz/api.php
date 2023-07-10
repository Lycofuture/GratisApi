<?php
     $counter = intval(file_get_contents("counter.dat"));  
     $_SESSION['#'] = true;  
     $counter++;  
     $fp = fopen("counter.dat","w");  
     fwrite($fp, $counter);  
     fclose($fp); 
 ?>
<?php 
/* Start */
require ("../function.php"); // 引入函数文件

addApiAccess(30); // 调用统计函数

addAccess();//调用统计函数

require ('../../curl.php');//引进封装好的curl文件

require ('../../need.php');//引用封装好的函数文件

require ('./get.php');

/* End */

$type=@$_GET["type"];

$method=@$_GET["method"];

$lx=@$_GET["lx"]?:"dongman";

if(@$_GET["method"] == "mobile"){	
	if($_GET["lx"] == 'dongman'){
		$imgurl = 'https://api.btstu.cn/sjbz/api.php?method=mobile&lx=dongman';
	}	
	else if(@$_GET["lx"] == 'meizi'){
		$imgurl = 'https://api.btstu.cn/sjbz/api.php?method=mobile&lx=meizi';
	}
    else if(@$_GET["lx"] == 'fengjing'){
        $imgurl = 'https://api.btstu.cn/sjbz/api.php?method=mobile&lx=fengjing';
    }
	else if(@$_GET["lx"] == 'suiji'){
		$imgurl = 'https://api.btstu.cn/sjbz/api.php?method=mobile&lx=suiji';
	}
	else{
	    $imgurl = 'https://api.btstu.cn/sjbz/api.php?method=mobile&lx=dongman';
	}
}else{
	if(@$_GET["lx"] == 'dongman'){
		$imgurl = 'https://api.btstu.cn/sjbz/api.php?lx=dongman';
	}
	else if(@$_GET["lx"] == 'meizi'){
		$imgurl = 'https://api.btstu.cn/sjbz/api.php?lx=meizi';
	}
    else if@($_GET["lx"] == 'fengjing'){
         $imgurl = 'https://api.btstu.cn/sjbz/api.php?lx=fengjing';
    }
	else if(@$_GET["lx"] == 'suiji'){
		$imgurl = 'https://api.btstu.cn/sjbz/api.php?lx=suiji';
	}
	else{
	    $imgurl = 'https://api.btstu.cn/sjbz/api.php?lx=dongman';
	}
}

    
    
    if (!$type){
    
    header("Location:".$imgurl);//跳转输出图片    
    
    }else{
    
    $s = need::teacher_curl($imgurl,[
    'loadurl'=>1]);
    
        img_get($s);
        
            echo $s;
            
               img_get($s);
    exit;
 //   echo file_get_contents('image.txt');
    
}

