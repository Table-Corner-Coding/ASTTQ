// JavaScript Document

jQuery(document).ready(function(){
	
	jQuery('select[data-selection]').each(function(){
		jQuery(this).val(jQuery(this).attr('data-selection'));
		jQuery(this).prop('disabled', true);
	});
	
	jQuery('.editable_table.comp_table').on('change','.mfield_container select.distance_type',function(){
		
		if(jQuery(this).val() == 'FP'){
			if(jQuery(this).parent().is(':last-child')){
				var theCell = jQuery(this).parent().parent();
				var theClone = theCell.find('.mfield_container.mfieldClone').clone().appendTo(theCell);
				theClone.removeClass('mfieldClone');
			}
			
		}else{
			if(jQuery(this).parent().is(':last-child')){
				
			}else{
				jQuery(this).parent().nextAll('.mfield_container').remove();
			}
		}
		
	});
	
	//jQuery('.editable_table.comp_table input').prop('disabled', true);
	
	jQuery('.actions').append("<span class='dashicons dashicons-yes save' title='Enregistrer les modifications'></span><span class='dashicons dashicons-trash delete' title='Supprimer le tireur'></span>");
	
	jQuery(".editable_table").on("click",'span.dashicons-welcome-write-blog',function(){

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


	jQuery(".editable_table.table_tireurs").on("click",".conducteur span.dashicons-plus-alt",function(){
		jQuery(this).before("<div data-content=''><input name='conducteur[]' type='text' /></div>");
	});
	
	jQuery(".editable_table.comp_table").on("click",".conducteur span.dashicons-plus-alt",function(){
		jQuery(this).before("<div data-content=''><input name='conducteur[]' type='text' /></div>");
		jQuery(this).parent().find('select.conducteur').hide();
		
		jQuery(this).after('<span class="dashicons dashicons-yes save-conducteur" title="Enregistrer les modifications"></span>');
		
		jQuery(this).removeClass('dashicons-plus-alt').addClass('dashicons-undo');
	});
	
	jQuery(".editable_table.comp_table").on("click",".conducteur span.dashicons-undo",function(){
		jQuery(this).parent().find('select.conducteur').show();
		jQuery(this).parent().find('div').remove();
		jQuery(this).removeClass('dashicons-undo').addClass('dashicons-plus-alt');
		jQuery(this).parent().find('.dashicons.dashicons-yes.save-conducteur').remove();
	});
	
	jQuery(".editable_table.comp_table").on("click",".dashicons.dashicons-yes.save-conducteur",function(){
		var theSelect = jQuery(this).parent().find('select');
		var theNewOne = jQuery(this).parent().find('input').val();
		var theLine = jQuery(this).closest('tr.tireur_line');
		var vehicule = theLine.find('select.tireur option:selected').text();
		
		if(theNewOne != ''){
			
		
		var theTable = theLine.parent().parent();
		var term_id = theTable.attr('data-term-id');
		var objID = theLine.attr('data-tireur-id');
		
		var nom_profil = theLine.find('.nom_profil > input').val();
		//var conducteur = [];
		var nom = [];
		
			
			theSelect.prepend('<option value="'+theNewOne+'">'+theNewOne+'</option>').val(theNewOne);
			jQuery(this).parent().find('div').remove();
		
			jQuery(this).parent().find('dashicons-undo').addClass('dashicons-plus-alt').removeClass('dashicons-undo');
			theSelect.show();
			
			theLine.find('.conducteur > option').each(function(){
				nom.push(jQuery(this).val());
			});
		
			//alert(nom);
			updateProfil(nom,false,false,objID,term_id,false);				
			
			jQuery(this).remove();
		}
		
		
		
	});
	
	jQuery(".editable_table").on('click','.edit_line .save',function(){
		//alert('Going to save!');
		
		var theLine = jQuery(this).parent().parent();
		var theTable = theLine.parent().parent();
		var term_id = theTable.attr('data-term-id');
		var objID = theLine.attr('data-tireur-id');
		var vehicule = theLine.find('.vehicule > input').val();
		var nom_profil = theLine.find('.nom_profil > input').val();
		//var conducteur = [];
		var nom = [];
		
		
		theLine.find('.conducteur > div > input').each(function(){
			nom.push(jQuery(this).val());
		});
		
		
		updateProfil(nom,vehicule,nom_profil,objID,term_id,theLine);
		
	});
	
	
	jQuery('.editable_table').on('click','td.actions > .dashicons-trash.delete',function(){
		var theLine = jQuery(this).parent().parent();
		var objID = theLine.attr('data-tireur-id');
		var nom_profil = '';
		if(theLine.hasClass('edit_line')){
			nom_profil = theLine.find('.nom_profil > input').val();
		}else{
			nom_profil = theLine.find('.nom_profil').attr('data-content');
		}
		
		//alert('!!!');
		jQuery.confirm({
			title: 'Confirmation requise!',
			content: 'Êtes-vous certain de vouloir supprimer '+nom_profil+'?',
			buttons: {
				confirm: {
					text: "Oui",
					action: function () {
						
						var my_data = {
						action: 'ajax_delete_post', // This is required so WordPress knows which func to use
						objID: objID
						};

						jQuery.post(adminAjax, my_data, function(response) { // This will make an AJAX request upon page load
								//jQuery("#response").html("<div>"+response+"</div>");
								//jQuery("body").removeClass("loading");

							var rData = jQuery.parseJSON(response);
							//alert(rData.message);
							theLine.fadeOut(300,function(){
								theLine.remove();
							});
							
						});
						
						
					}
				},
				cancel: { 
					text: "Non, c'est une erreur...",
					action: function(){
								//$.alert('Canceled!');
							}
				}
			}
		});
		
		
		
		
	});
	
	jQuery('.add_tireur_line').on('click','.dashicons-plus-alt',function(){
		jQuery(this).parent().parent().before('<tr class="tireur_line edit_line" data-tireur-id="0"><td data-content="" class="vehicule"><input name="vehicule" type="text" value=""></td><td class="nom_profil" data-content=""><input name="nom_profil" type="text" value=""></td><td class="conducteur multi_field"><div data-content=""><input name="conducteur[]" type="text" value=""></div><span class="dashicons dashicons-plus-alt"></span></td><td class="actions"><span title="Éditer" class="dashicons dashicons-welcome-write-blog edit"></span><span class="dashicons dashicons-yes save" title="Enregistrer les modifications"></span><span class="dashicons dashicons-trash delete" title="Supprimer le tireur"></span></td></tr>');
	});
	
});

function editionDone(theLine){
	theLine.removeClass('edit_line');
		var vehicule = theLine.find(".vehicule").attr("data-content",theLine.find(".vehicule input").val());
		theLine.find(".vehicule").html(theLine.find(".vehicule").attr("data-content"));
	
		var nom_profil = theLine.find(".nom_profil").attr("data-content",theLine.find(".nom_profil input").val());
		theLine.find(".nom_profil").html(theLine.find(".nom_profil").attr("data-content"));
	

		theLine.find(".conducteur > div").each(function(){
			jQuery(this).attr("data-content",jQuery(this).find('input').val());
			
			if(jQuery(this).attr("data-content") != ""){
				jQuery(this).html(jQuery(this).attr("data-content"));
			}else{
				jQuery(this).remove();
			}
		});

		theLine.find(".conducteur .dashicons.dashicons-plus-alt").remove();
	
}


function updateProfil(nom,vehicule,nom_profil,objID,term_id,theLine){
	
	var conducteur = {nom: nom};
	var dataString = {conducteur: conducteur};
	
	alert('Nom: '+nom);
	alert('Conducteur: '+conducteur);
	
	if(vehicule != false){
		dataString['nom_du_vehicule'] = vehicule;
	}
	
	if(nom_profil != false){
		dataString['nom_du_profil'] = nom_profil;
	}
	
		dataString.test = 'test value';
		dataString['conducteur'] = nom;
	
	alert(dataString);
	var jsonString = JSON.stringify(dataString);

	alert(jsonString);
	
	var my_data = {
				action: 'update_post_fields', // This is required so WordPress knows which func to use
				objID: objID,
				term_id : term_id,
				data: jsonString // Post any variables you want here
			};

		jQuery.post(adminAjax, my_data, function(response) { // This will make an AJAX request upon page load
				//jQuery("#response").html("<div>"+response+"</div>");
				//jQuery("body").removeClass("loading");

			var rData = jQuery.parseJSON(response);
			alert(rData.message);
			if(theLine != false){
				theLine.attr('data-tireur-id',rData.objID);
				editionDone(theLine);
			}
			
		});
}
