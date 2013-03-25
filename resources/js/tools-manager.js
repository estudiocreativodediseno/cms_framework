/**
*
* @date 05.03.13
*
*
*/
$(function(){	
	$.fn.clearInput = function(){
		$(this).focus(function(){
			if($(this).val() == this.defaultValue){ $(this).val('');}
		});
		
		$(this).blur(function(){
			if($(this).val() == ''){ $(this).val(this.defaultValue);}
		});
	}


	$.fn.blankAnchors = function(){
		$("a[href^='http:']:not([href*='" + window.location.host + "'])").attr({'target':'_blank'});
	}


	$.fn.uploader = function(){
		$(this).on('click', function(e){
			$file = $(this).find(':file');
			$text = $(this).find(':text');

			if(!$(e.target).is($file)){
				$file.click();
			}

			$file.on('change', function(e){
				$text.val($(this).val());
			});
		});
	}
});


$().ready(function(){

	// Uploader component
	$('.file-wrapper').uploader();

	// Accordion aside menu
	if($('#accordion').length > 0){	
		$('#accordion').jqcollapse({
			slide: true,
			speed: 500,
			easing: 'easeOutCubic'
		});
	}

	/* current link */
	$('.accordion').on('click', 'a', function(e){
		var $this = $(this);

		$('.accordion').find('.current').removeClass('current');
		$this.addClass('current');

		$node = $this.parent().siblings().children('.jqcNode');
		$nodeList = $node.siblings('ul:visible');

		if($nodeList.length > 0){
			$nodeList.slideToggle();
			$node.removeClass('open-node');
		}
	});


	$().blankAnchors();
	$('input[type=text]').clearInput();
	
	var $this =  $('.accordion').find('.current');

		$('.accordion').find('.current').removeClass('current');
		$this.addClass('current');

		//e.preventDefault();

		$node = $this.parent().siblings().children('.jqcNode');
		$nodeList = $node.siblings('ul:visible');

		if($nodeList.length > 0){
			$nodeList.slideToggle();
			$node.removeClass('open-node');
		}

	
});