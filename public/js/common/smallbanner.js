$.fn.smallbanner = function(cla){
	//点击小图切换大图
	var $obj = $(cla);
	$obj.find(".sliderbox li a").click(function(){
		$obj.find(".zoompic img").hide().attr({ "src": $(this).attr("rel"), "title": $("> img", this).attr("title") }).fadeIn();
		$obj.find(".zoompic p").html($(this).find('img').attr("alt"));
		$obj.find(".zoompic a").attr({"href" : $(this).attr("vrc")});
		$obj.find(".sliderbox li.current").removeClass("current");
		$(this).parents("li").addClass("current");
		return false;
	});
	$obj.find(".zoompic>img").load(function(){
		$obj.find(".zoompic>img:hidden").show();
	});
	
	//小图片左右滚动
	var $slider = $obj.find('.sliderbox ul');
	var $slider_child_l = $obj.find('.sliderbox ul li').length;
	var $slider_width = $obj.find('.sliderbox ul li').width();
	$slider.width($slider_child_l * $slider_width);
	
	var slider_count = 0;
	
	if ($slider_child_l < 5) {
		$obj.find('.btn-right').css({cursor: 'auto'});
		$obj.find('.btn-right').removeClass("dasabled");
	}
	
	$obj.find('.btn-right').click(function() {
		if ($slider_child_l < 3 || slider_count >= $slider_child_l - 3) {
			return false;
		}
		
		slider_count++;
		$slider.animate({left: '-=' + $slider_width + 'px'}, 'fast');
		slider_pic();
	});
	
	$obj.find('.btn-left').click(function() {
		if (slider_count <= 0) {
			return false;
		}
		slider_count--;
		$slider.animate({left: '+=' + $slider_width + 'px'}, 'fast');
		slider_pic();
	});
	
	function slider_pic() {
		if (slider_count >= $slider_child_l - 3) {
			$obj.find('.btn-right').css({cursor: 'auto'});
			$obj.find('.btn-right').addClass("dasabled");
		}
		else if (slider_count > 0 && slider_count <= $slider_child_l - 3) {
			$obj.find('.btn-left').css({cursor: 'pointer'});
			$obj.find('.btn-left').removeClass("dasabled");
			$obj.find('.btn-right').css({cursor: 'pointer'});
			$obj.find('.btn-right').removeClass("dasabled");
		}
		else if (slider_count <= 0) {
			$obj.find('.btn-left').css({cursor: 'auto'});
			$obj.find('.btn-left').addClass("dasabled");
		}
	}
}