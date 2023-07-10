$('.help').hover(function(){if($('.help_zty').hasClass('hide')){
	$('.help_zty').removeClass('hide');}
	else{$('.help_zty').addClass('hide');}
});
var my_modal_bg = $('.my_modal_bg'), scrollWidth ='';
(function div(){
    var div = $('<div id="cde_map" style="width:100px; height:1px; overflow-y: scroll; opacity: 0;"></div>')
    $("body").append(div);
    var lw = document.getElementById('cde_map').offsetWidth - document.getElementById('cde_map').scrollWidth;
    if(lw == 0){
        scrollWidth = 17;
    }else{
        scrollWidth = lw;
    }
    div.remove();
})()
$("#area").click(function (e) {
        my_modal_bg.css({overflowY:'scroll'}).show()
        $('html').css({overflow:'hidden',paddingRight:scrollWidth})
        SelCity(this, e);
});
$("input[name=panmode]").click(function(){
	if($(this).val()==0){
		$('.fppaifa').removeClass('none');
	}else{
		$('.fppaifa').addClass('none');
	}
});

function checkpqm(){
	var birth=$('input[name="gl_birthday"]').val();
	var ct=$('input[name="area"]').val();
	var flag=true;
	if(birth==''){
		alert('请选择出生时间');
		flag=false;
	}
	if($('input[name="zty"]').is(':checked') && ct==''){
		alert('真太阳时需要选择出生地');
		flag=false;
	}
	if(flag==true){
		$('#pqmform').submit();
	}
}
