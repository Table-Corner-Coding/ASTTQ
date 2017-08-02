// JavaScript Document

jQuery(document).ready(function(){
	
	jQuery('.actions').append("<span class='dashicons dashicons-yes save' title='Enregistrer les modifications'></span><span class='dashicons dashicons-trash delete' title='Supprimer le tireur'></span>");
	
	jQuery("span.dashicons-welcome-write-blog").on("click",function(){

		var theLine = jQuery(this).parent().parent();
		theLine.addClass('edit_line');
		var vehicule = theLine.find(".vehicule").attr("data-content");
		theLine.find(".vehicule").html("<input name='vehicule' type='text' value='"+vehicule+"' />");

		var nom_profil = theLine.find(".nom_profil").attr("data-content");
		theLine.find(".nom_profil").html("<input name='nom_profil' type='text' value='"+nom_profil+"' />");

		theLine.find(".conducteur > div").each(function(){
			var theValue = jQuery(this).attr("data-content");
			jQuery(this).html("<input name='conducteur[]' type='text' value='"+theValue+"' />");

		});

		theLine.append("<span class='dashicons dashicons-plus-alt'></span>");

		theLine.find("actions").html("");
	});


	jQuery(".conducteur span.dashicons-plus-alt").on("click",function(){
		jQuery(this).before("<div data-content=''><input name='conducteur[]' type='text' /></div>");
	});

});