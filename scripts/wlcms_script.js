    jQuery(document).ready(function(){
    
    	jQuery('#footer-left').remove();
    
		jQuery('.wlcms_options').slideUp();
		
		jQuery('.video-h').hover(function() {
			jQuery(this).addClass('pretty-hover');
		}, function() {
			jQuery(this).removeClass('pretty-hover');
		});

		var showHideWelcome;
		showHideWelcome = jQuery('.wlcms_opts form #form-show-welcome input:radio:checked').val();
		if(showHideWelcome == 0) {
			jQuery('.video-h').hide();
		}
		
		 jQuery('.wlcms_opts form #form-show-welcome input:radio').click(function() {
		 	showHideWelcome = jQuery('.wlcms_opts form #form-show-welcome input:radio:checked').val();
			if(showHideWelcome == 0) {
				jQuery('.video-h').hide();
			} else {
				jQuery('.video-h').show();
			}
		 });
		
  
		jQuery('.wlcms_section h3').click(function(){		
			if(jQuery(this).parent().next('.wlcms_options').css('display')=='none')
				{	jQuery(this).removeClass('inactive');
					jQuery(this).addClass('active');
					jQuery(this).children('img').removeClass('inactive');
					jQuery(this).children('img').addClass('active');
					
				}
			else
				{	jQuery(this).removeClass('active');
					jQuery(this).addClass('inactive');		
					jQuery(this).children('img').removeClass('active');			
					jQuery(this).children('img').addClass('inactive');
				}
				
			jQuery(this).parent().next('.wlcms_options').slideToggle('slow');	
		});
});