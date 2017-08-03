
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
	
	jQuery('#menu-gestion > li > a').on('click',function(){
		load();
	});
	
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
	   /*
		jQuery('.et_header_style_fullscreen #et-top-navigation').prepend(jQuery('<span class="mobile_menu_bar et_pb_header_toggle et_toggle_fullscreen_menu"></span>'));

		jQuery('<div class="et_slide_in_menu_container" style="padding-top:20px;"><span class="mobile_menu_bar et_toggle_fullscreen_menu"></span><div class="et_pb_fullscreen_nav_container"></div></div>').prependTo('.et_header_style_fullscreen #page-container');

		jQuery(document).ready(function(){
			jQuery('.et_header_style_fullscreen #mobile_menu').attr('id','mobile_menu_slide');
			jQuery('.et_header_style_fullscreen #mobile_menu_slide').appendTo('.et_pb_fullscreen_nav_container');
		});
	   */
	}
	
	
	
	/*

	setInterval(function(){

    if (iframe.document.body.scrollheight != lastheight) {
        // adjust the iframe's size

        lastheight = iframe.document.body.scrollheight;
    }
		
	}, 200);
	*/
	jQuery('.acf-taxonomy-field.acf-soh > input[type=hidden]').each(function(){
		
		jQuery(this).parent().append('<a class="acf-button button button-primary load-members">Charger les membres de cette classe</a>');
	});
	
	// on Select change:
	
	jQuery('#acf-form').on('change','.acf-taxonomy-field.acf-soh > input[type=hidden]', function(){
		//alert('La classe a été changée! Elle est maintenant: '+jQuery(this).val());
		var classeID = jQuery(this).val();
		jQuery(this).parent().parent().parent().parent().find('div[data-name=classe_id]').find('input').val(classeID);
		jQuery(this).parent().find('.load-members').remove();
		jQuery(this).parent().append('<a class="acf-button button button-primary load-members">Charger les membres de cette classe</a>');
		
		jQuery(this).parent().find('.load-members').trigger('click');
	});
	
	jQuery('#acf-form').on('change','td[data-name=tireur] input[type=hidden]', function(){
		//alert('La classe a été changée! Elle est maintenant: '+jQuery(this).val());
		//alert('tireur changé!');
		var sender = jQuery(this).parent().parent().parent().find('td[data-name=nom_du_tireur] input[type=text]');
		//jQuery(this).parent().parent().parent().parent().find('div[data-name=classe_id]').find('input').val(classeID);
		
		updateConducteurs(sender,0);
	});
	
	jQuery('#acf-form').on('change','.conducteurs_select',function(){
		jQuery(this).next('input[type=text]').val(jQuery(this).val());
	});
	
	
	jQuery('#acf-form div[data-name=competiteur] td[data-name=nom_du_tireur] .acf-input input[type=text]').each(function(){
		
		updateConducteurs(jQuery(this),1);
	});
	
	jQuery('#acf-form').on('click','.load-members',function(){
		
		var objID = jQuery(this).parent().find('input[type=hidden]').val();
		var wholeList = jQuery(this).parent().parent().parent().parent().find('div[data-name=competiteur]');
		var table = jQuery(this).parent().parent().parent().parent().find('div[data-name=competiteur] > .acf-input > .acf-repeater > .acf-table');
		var addButton = jQuery(this).parent().parent().parent().parent().find('div[data-name=competiteur] > .acf-input > .acf-repeater > .acf-actions > li > a.acf-button');

		
		table.find('tbody > tr+tr').each(function(){
			//acf.fields.repeater.remove(jQuery(this));
			
		});
		
		//var hiddenField = sender;
		var my_data = {
			action: 'ajax_load_tireurs_from_class', // This is required so WordPress knows which func to use
			objID: objID
		};
		//alert(objID);
		jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			var rData = jQuery.parseJSON(response);
			//alert(rData.message);
			var tireursArray = rData.tireurs;
			var tireursNom = rData.noms;
			
			tireursArray.forEach(function(item, index){
				//alert(item);
				//table.find('tbody tr:last-child td[data-name=tireur] input[type=hidden]').val(item);
				//table.find('tbody tr:last-child td[data-name=tireur] span.select2-chosen').html(tireursNom[index]);
				
				//alert('Tireur: '+tireursNom[index]+' ('+item+')');	
				
				//addButton.trigger('click');
				
				acf.fields.repeater.add(table);
				
				var nomTireur = tireursNom[index];
				var trueIndex = index;
				setTimeout(function(){
					
						table.find('tbody tr:nth-child('+trueIndex+') td[data-name=tireur] input[type=hidden]').val(item);
						table.find('tbody tr:nth-child('+trueIndex+') td[data-name=tireur] span.select2-chosen').html(nomTireur);
						table.find('tbody tr:nth-child('+trueIndex+') td[data-name=tireur] input[type=hidden]').trigger('change');
						table.find('tbody tr:nth-child('+trueIndex+') td[data-name=tireur] input[type=hidden]').val(item);
						table.find('tbody tr:nth-child('+trueIndex+') td[data-name=tireur] span.select2-chosen').html(nomTireur);
						table.find('tbody tr:nth-child('+trueIndex+')').attr('title',item);
   					}, 200);
			});
			
			
			
			tireursArray.forEach(function(item, index){
				/*
				table.find('tbody tr:nth-child('+index+') td[data-name=tireur] input[type=hidden]').val(item);
				table.find('tbody tr:nth-child('+index+') td[data-name=tireur] span.select2-chosen').html(tireursNom[index]);
				table.find('tbody tr:nth-child('+index+') td[data-name=tireur] input[type=hidden]').trigger('change');
				*/
			});
			

		});
		
	});

	function updateConducteurs(hiddenField,triggerChange){
		
		var theLine = hiddenField.parent().parent().parent().parent();
		var objID = theLine.find('td[data-name=tireur] input[type=hidden]').val();
		//var hiddenField = sender;
		var my_data = {
			action: 'ajax_get_conducteurs', // This is required so WordPress knows which func to use
			objID: objID
		};
		
		jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			var rData = jQuery.parseJSON(response);

			//alert(rData.message);
			var theSelect = jQuery(rData.message);
			hiddenField.parent().find('select').remove();
			hiddenField.before(theSelect);
			if(triggerChange == 1){
				theSelect.trigger('change');
			}

		});
	}
	
	
	/*
	jQuery('#acf-form div[data-name=classe_id]').each(function(){
		var theGroup = jQuery(this).parent().parent().parent().parent();
		var theSelect = theGroup.find('div[data-name=classe]').find('input[type=hidden]').select2('data', {id:103});
	});
	*/
});



/*
window.addEventListener('DOMContentLoaded', function(e) {

    var iFrame = document.getElementById( 'evenement_frame' );
    resizeIFrameToFitContent( iFrame );

    // or, to resize all iframes:

} );
*/