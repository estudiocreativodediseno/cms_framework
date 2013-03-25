/**
*
* @date 06.03.13
*
*
*/
$().ready(function(){
	$('.login-form fieldset').on({
		click: function(){
			$this = $(this);

			$active = $('.login-form .active').removeClass('active');

			$label = $this.find('label');
			$text = $this.find('input');

			$text.focus();

			if($text.is(":focus")){ $label.addClass('active')}
		}
	});

	$('.login-form fieldset').on({

		focus: function(e) {
			$(e.target).parent().find('label').addClass('active');
		},

		blur: function() {
			$active = $('.login-form .active').removeClass('active');
		}
	}, "input");
});