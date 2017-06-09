
var iframe = '';
var frame = '';

var unload = function(){
	jQuery('.gestion-content > div:first-child').addClass('loading');
};
	
var load = function(){
	jQuery('.gestion-content > div:first-child').removeClass('loading');
	
	iframe.contentWindow.onbeforeunload = unload;
	iframe.onload = load;
};

	

//iframe.src = "";

function refreshStyles(){
	jQuery('.acf-true-false > label').each(function(){
		
		jQuery(this).addClass('switch');
		jQuery(this).append(jQuery('<div class="slider round"></div>'));
		
	});
}

jQuery(document).ready(function(){
	
	frame = jQuery('iframe#evenement_frame');
	iframe = document.getElementById('evenement_frame');
	
	
	unload();
	
	
	if(iframe != null){		
		iframe.onload = load;
	}else{
		jQuery(document).ready(function(){
			//jQuery('.gestion-content > div:first-child').removeClass('loading');
		});
	}
	
	
		jQuery(document).ready(function(){
			if(!jQuery('iframe').length){
				jQuery('.gestion-content > div:first-child').removeClass('loading');
			}else{
				iframe.onload = load;
			}
			//
		});
	
	
	jQuery('#evenements').on('change',function(){
		
		if(jQuery(this).val() !== ""){
			jQuery('#evenement_frame').attr('src',jQuery(this).val());
		}
		
		
	});
	
	jQuery('td[data-key=field_592da79d26f22] select').on('change', function(){
		
		alert(jQuery(this).val());
		
	});
	
	//refreshStyles();
	
});



var iframe = document.getElementById('evenement_frame'),
    lastheight;

jQuery(document).ready(function(){

    
	frame = jQuery('iframe#evenement_frame');
	
    //hide document default scroll-bar
    jQuery('iframe#evenement_frame body').css('overflow','hidden');
	
	
	if(typeof iFrameResize !== 'undefined'){
		iFrameResize({log:false}, '#evenement_frame');
	}
	

    frame.load(resizeIframe);   //wait for the frame to load
    jQuery(window).resize(resizeIframe); 

    function resizeIframe() {
        var w, h;       

        //detect browser dimensions
            if(jQuery.browser.mozilla){
            h = jQuery(window).height();
            w = jQuery(window).width();                  
        }else{
            h = jQuery(document).height();
            w = jQuery(document).width();
        }

        //set new dimensions for the iframe
            frame.height(h);
        frame.width(w);
    }
	
	if(jQuery('body').hasClass('iframe')){
	   
	   }else{
	   
		jQuery('.et_header_style_fullscreen #et-top-navigation').prepend(jQuery('<span class="mobile_menu_bar et_pb_header_toggle et_toggle_fullscreen_menu"></span>'));

		jQuery('<div class="et_slide_in_menu_container" style="padding-top:20px;"><span class="mobile_menu_bar et_toggle_fullscreen_menu"></span><div class="et_pb_fullscreen_nav_container"></div></div>').prependTo('.et_header_style_fullscreen #page-container');

		jQuery(document).ready(function(){
			jQuery('.et_header_style_fullscreen #mobile_menu').attr('id','mobile_menu_slide');
			jQuery('.et_header_style_fullscreen #mobile_menu_slide').appendTo('.et_pb_fullscreen_nav_container');
		});
	   
	}
	
	
	
	/*

	setInterval(function(){

    if (iframe.document.body.scrollheight != lastheight) {
        // adjust the iframe's size

        lastheight = iframe.document.body.scrollheight;
    }
		
	}, 200);
	*/
	
});



/*
window.addEventListener('DOMContentLoaded', function(e) {

    var iFrame = document.getElementById( 'evenement_frame' );
    resizeIFrameToFitContent( iFrame );

    // or, to resize all iframes:

} );
*/