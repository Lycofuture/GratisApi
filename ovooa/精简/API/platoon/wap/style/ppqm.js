/*弹出定位*/
var scT='';
function scTop(){
    var top=$(window).scrollTop()
    return {top:top}
}
function bodyStyle(){
    scT = scTop().top;
    $('body').css({position:'fixed',top:-scT,left:0,right:0});
}
function bodyRemovStyle(){
    $('body').removeAttr('style');
    $('html,body').scrollTop(scT);
    
}

/*单选美化label,隐藏radio靠label点击*/
$('.checked label').click(function(){
      if($(this).find('input').is(':checked')){
          $(this).addClass('on').siblings().removeClass('on'); 
      }
});
/*复选美化label,隐藏checkbox靠label点击*/
$('.checkbox label').click(function(){
        if ($(this).find('input').is(':checked')) {
            $(this).find('input').attr("checked",true).prop("checked","checked").parent().siblings().removeClass('on').find('input').removeAttr("checked");
            $(this).parent().addClass('on')
        } else {
            $(this).parent().removeClass('on')
        }
    });
/*城市选择*/
$(".cs").click(function (e) {
     SelCity(this, e);
});
/*自定义SELECT*/
$('.select select').change(function(){
        var text = $(this).children('option:selected').text(), val = $(this).children('option:selected').val();
        $(this).prev().find('span').text(text)
})
$('.morechecked label').click(function(){
      if($(this).find('input').is(':checked')){
          $(this).addClass('on').siblings().removeClass('on'); 
      }
});
$('input[name="panmode"]').change(function(){
	var n=$(this).val();
	var obj=$('.fppaifa');
	if(n==0){
		obj.addClass('dis_flex');
	}else{
		if(obj.hasClass('dis_flex')) obj.addClass('none').removeClass('dis_flex');
	}
});
function checkpqm(){
	var birth=$('input[name="gl_birthday"]').val();
	var ct=$('input[name="area"]').val();
	var flag=true;
	if(birth==''){
		point('请选择起局时间');
		flag=false;
	}
	if($('input[name="zty"]').is(':checked') && ct==''){
		point('真太阳时需要选择出生地');
		flag=false;
	}
	if(flag==true){
		$('#pqmform').submit();
	}
}
function point(a){
        var point = $('<div class="point">'+a+'</div>');
        $("body").append(point);
        var left = ($(window).width()-point.outerWidth())/2,
            top = ($(window).height()-point.outerHeight())/2;
        point.css({left:left,top:top}).addClass('show');
        setTimeout(function(){
            point.removeClass('show');
            setTimeout(function(){
                point.remove()
            },200)
        },2000)
 }