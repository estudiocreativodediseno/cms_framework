/* 06.06.12 | app-chrome.js */

$().ready(function(){

	/* Maximize Main Window */

	$resize = $('.resize');
	$mainH = $resize.innerHeight() - $resize.height();

	function maximize(){
		$windowH = $(window).height();
		// $windowH = $('.wrap').height();

		/* Check if aside is talles than main */

		$headerBarH = $('.header-bar').height();
		$footerBarH = $('.footer-bar').height();
		$padding = parseInt($resize.parent().css('padding-top')) + parseInt($resize.parent().css('padding-bottom'));
		// $border = 80;
		$border = 2;

		$totalHeight = (($windowH - ($headerBarH + $footerBarH + $padding + $border)) - $mainH) + 'px';

		$resize.css({'height':$totalHeight});
	}

	$(window).resize(function(){
		maximize();
	});

	maximize();
});