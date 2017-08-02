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

		theLine.find(".conducteur").append("<span class='dashicons dashicons-plus-alt'></span>");

		//theLine.find("actions").html("");
	});


	jQuery(".conducteur").on("click","span.dashicons-plus-alt",function(){
		jQuery(this).before("<div data-content=''><input name='conducteur[]' type='text' /></div>");
	});
	
	jQuery(".editable_table").on('click','.edit_line .save',function(){
		alert('Going to save!');
		
		var theLine = jQuery(this).parent().parent();
		var objID = theLine.attr('data-tireur-id');
		var vehicule = theLine.find('.vehicule > input').val();
		var nom_profil = theLine.find('.nom_profil > input').val();
		//var conducteur = [];
		var nom = [];
		
		
		theLine.find('.conducteur > div > input').each(function(){
			nom.push(jQuery(this).val());
		});
		
		var conducteur = {nom: nom};
		
		var dataString = {vehicule: vehicule, nom_profil: nom_profil, conducteur: conducteur};
		var jsonString = JSON.stringify(dataString);
		
		
		var my_data = {
                    action: 'update_post_fields', // This is required so WordPress knows which func to use
					objID: objID,
                    data: jsonString // Post any variables you want here
                };
		
			jQuery.post(adminAjax, my_data, function(response) { // This will make an AJAX request upon page load
                    //jQuery("#response").html("<div>"+response+"</div>");
					//jQuery("body").removeClass("loading");
					alert(response);
                });
		
	});
	

});