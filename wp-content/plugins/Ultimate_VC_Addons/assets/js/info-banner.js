(function($) {
	$(document).ready(function(){
		// CSS3 Transitions.
		jQuery('.ultb3-box .ultb3-info').each(function(){
			if(jQuery(this).attr('data-animation')) {
				jQuery(this).css('opacity','0');
				var animationName = jQuery(this).attr('data-animation'),
					animationDelay = "delay-"+jQuery(this).attr('data-animation-delay');
				jQuery(this).bsf_appear(function() {
					var $this = jQuery(this);
					//$this.css('opacity','0');
					//setTimeout(function(){
						$this.addClass('animated').addClass(animationName);
						$this.addClass('animated').addClass(animationDelay);
						$this.css('opacity','1');
					//},1000);
				},{accY: -70});
			} 
		});
	});
}( jQuery ));