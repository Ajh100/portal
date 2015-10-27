$(function(){

	$('.header .city').hover(function(){
		$(this).addClass('city_on');
		$(this).find('.down').show();
                $(this).find('i').css("backgroundImage","url(/public/ui/images/topico2.png)"); 
	},function(){
		$(this).removeClass('city_on');
		$(this).find('.down').hide();
                $(this).find('i').css("backgroundImage","url(/public/ui/images/topico1.png)"); 
	});
	
});