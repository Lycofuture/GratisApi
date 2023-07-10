<!doctype html>
<html> 
    <head> 
        <title>更新日志 - 独角兽-API 一款好用，免费并且没有广告的接口调用平台</title> 
        <meta charset="utf-8"> 
        <style>
        #percentageCounter{position:fixed; left:0; top:0; height:3px; z-index:99999; background-image: linear-gradient(to right, #E8EAF6,#C5CAE9,#9FA8DA,#7986CB,#5C6BC0,#3F51B5,#3949AB,#303F9F,#283593,#1A237E);border-radius:5px;}
    </style> <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />--> 
        <meta name="keywords" content="API,接口,免费调用,免费API,免费接口,独角兽,免费,免费对接,独角兽API,免费API,聚合API,QQAPI,SQ,QR,免费接口调用平台,免费接口"> 
        <meta name="description" content=""> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
        <meta name="generator" content="vue"> 
        <meta name="revisit-after" content="3days"> 
        <meta name="robots" content="all"> 
                <div id="percentageCounter"></div> <!-- <link rel="stylesheet" href="./assets/css/index.css"/> --> 
        <link rel="stylesheet" href="/assets/css/loadingr.css"> 
        <link rel="stylesheet" href="./assets/css/mdui.min.css"> 
        <link rel="stylesheet" href="./assets/css/style.css"> 
        <link rel="stylesheet" href="./assets/css/mdclub.css"> 
        <link rel="stylesheet" href="./assets/css/highlight.min.css"> 
        <script src="./assets/js/highlight.min.js"></script> 
        <style id="style"></style>  
        <!-- <div class="fan-loding"> --> 
            <!-- <div class="la-ball-climbing-dot la-dark la-3x"> --> 
                <!-- <div></div> --> 
                <!-- <div></div> --> 
                <!-- <div></div> --> 
            <!-- </div> --> 
        <!-- </div> --> 
    </head>
    <?php require "header.html"; ?>
    	<nav class="toc mdui-text-color-theme-600" id="catalogue">
    		<li><a :href="'/?action=doc&id='+catalogue.id" v-if="catalogue.status == 1"></a><a href="#" v-if="catalogue.status == 0"></a></li>
    	</nav>
    	<?php require "footer.html";?>
    	<script src="./assets/js/jquery.min.js"></script>
    	<script src="./assets/js/jquery.cookie.min.js"></script>
    	<script src="./assets/js/sweetalert.min.js"></script>
    	<script src="./assets/js/mdui.min.js"></script>
    	<script src="./assets/js/index.js"></script>
    	<script src="./assets/js/vue.js"></script>
    	<script>
    		let catalogue = new Vue({
    			el: '#catalogue',
    			data: {
    				catalogues: []
    			},
    			methods: {
    				
    			},
    			created() {
    				fetch('./Data/api.php?type=getAllApi')
    				.then(res=>json())
    				.then(json=>{
    					this.catalogues = json.data;
    				})
    			}
    		});
    	</script>
    </body>
</html>