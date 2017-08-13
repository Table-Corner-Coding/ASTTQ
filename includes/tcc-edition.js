// JavaScript Document

(function(jQuery){
    //Shuffle all rows, while keeping the first column
    //Requires: Shuffle
 jQuery.fn.shuffleRows = function(){
     return this.each(function(){
        var main = jQuery(/table/i.test(this.tagName) ? this.tBodies[0] : this);
        var firstElem = [], counter=0;
        main.children().each(function(){
             firstElem.push(this.firstChild);
        });
        main.shuffle();
        main.children().each(function(){
           this.insertBefore(firstElem[counter++], this.firstChild);
        });
     });
   }
  /* Shuffle is required */
  jQuery.fn.shuffle = function() {
    return this.each(function(){
      var items = jQuery(this).children();
      return (items.length)
        ? jQuery(this).html(jQuery.shuffle(items))
        : this;
    });
  }

  jQuery.shuffle = function(arr) {
    for(
      var j, x, i = arr.length; i;
      j = parseInt(Math.random() * i),
      x = arr[--i], arr[i] = arr[j], arr[j] = x
    );
    return arr;
  }
})(jQuery)

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
	
	
	jQuery('.editable_table.table_tireurs').on('click','td.actions > .dashicons-trash.delete',function(){
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
	
	jQuery('.editable_table.comp_table').on('click','.dashicons-randomize',function(){
		var theTable = jQuery(this).parent().parent().parent().parent();
		jQuery.confirm({
			title: 'Confirmation requise!',
			content: 'Êtes-vous certain de vouloir mélanger toutes les lignes?',
			buttons: {
				confirm: {
					text: "Oui",
					action: function () {

							theTable.shuffleRows();
			
						
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
	
	jQuery('.editable_table.comp_table').on('click','td.actions > .dashicons-trash.delete',function(){
		
		var theLine = jQuery(this).parent().parent();
		var objID = theLine.attr('data-tireur-id');
		var nom_profil = '';
		nom_profil = theLine.find('.tireur option:selected').text();
		var tbody = theLine.closest('tbody');
		
		jQuery.confirm({
			title: 'Confirmation requise!',
			content: 'Êtes-vous certain de vouloir supprimer '+nom_profil+'?',
			buttons: {
				confirm: {
					text: "Oui",
					action: function (){
							
							/*
							tbody.children('tr').each(function(){
								jQuery(this).first('td').html(parseInt(jQuery(this).first('td').html())-1);
							});
						*/
							theLine.fadeOut(300,function(){
								theLine.remove();
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
		
		//setTimeout(function(){reorderRows(tbody.parent());},200);
		
	});
	
	jQuery('.editable_table.comp_table').on('change','td.membre input[type=checkbox]',function(){
		var theLine = jQuery(this).closest('tr');
		if(jQuery(this).is(':checked')){
			theLine.find('select.tireur').show();
			theLine.find('select.conducteurs_select, .addConducteur').show();
			theLine.find('input.tireur').remove();
			theLine.find('input.conducteur').remove();
			
		}else{
			theLine.find('select.tireur').hide();
			theLine.find('select.conducteurs_select, .addConducteur').hide();
			theLine.find('select.conducteurs_select').after('<input type="text" class="conducteur" />');
			theLine.find('select.tireur').after('<input type="text" class="tireur" />');
		}
		
	});
	
	jQuery('.editable_table.comp_table').on('change','select.tireur',function(){
		var theLine = jQuery(this).closest('tr');
		var objID = jQuery(this).val();
		//var hiddenField = sender;
		var my_data = {
			action: 'ajax_get_conducteurs', // This is required so WordPress knows which func to use
			objID: objID
		};
		
		jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			var rData = jQuery.parseJSON(response);

			//alert(rData.message);
			var theSelect = jQuery(rData.message);
			theLine.find('select.conducteurs_select').replaceWith(theSelect);

		});
	});
	
	
	jQuery('.editable_table.table_tireurs .add_tireur_line').on('click','.dashicons-plus-alt',function(){
		jQuery(this).parent().parent().before('<tr class="tireur_line edit_line" data-tireur-id="0"><td data-content="" class="vehicule"><input name="vehicule" type="text" value=""></td><td class="nom_profil" data-content=""><input name="nom_profil" type="text" value=""></td><td class="conducteur multi_field"><div data-content=""><input name="conducteur[]" type="text" value=""></div><span class="dashicons dashicons-plus-alt"></span></td><td class="actions"><span title="Éditer" class="dashicons dashicons-welcome-write-blog edit"></span><span class="dashicons dashicons-yes save" title="Enregistrer les modifications"></span><span class="dashicons dashicons-trash delete" title="Supprimer le tireur"></span></td></tr>');
	});
	
	jQuery('.comp_table .add_tireur_line').on('click','.dashicons-plus-alt',function(){
		var theNewLine = jQuery('<tr class="tireur_line" data-tireur-id="0"><td class="pos"></td><td data-content="" class="vehicule"><select class="tireur" data-selection="0" disabled="" style="display:none;"></select><input type="text" class="tireur" /></td><td class="conducteur multi_field"><select style="display:none;" class="conducteurs_select"></select><input type="text" class="conducteur" /><span class="dashicons dashicons-plus-alt addConducteur"></span></td><td class="distances multi_field"><div class="mfield_container mfieldClone"><select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0"><option value="Normal" class="">Normal</option><option value="FP">FP</option><option value="DNS">DNS</option><option value="DQ">DQ</option><option value="BR">BR</option></select><input type="number" id="" class="" name="" value="" min="" max="" step="any" placeholder=""></div><div class="mfield_container"><select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0"><option value="Normal" class="">Normal</option><option value="FP">FP</option><option value="DNS">DNS</option><option value="DQ">DQ</option><option value="BR">BR</option></select><input type="number" id="" class="" name="" value="" min="" max="" step="any" placeholder=""></div></td><td class="membre"><label class="switch"><input type="checkbox" value="1" class="" autocomplete="off"><div class="slider round"></div></label></td><td class="actions"><span class="dashicons dashicons-yes save" title="Enregistrer les modifications"></span><span class="dashicons dashicons-trash delete" title="Supprimer le tireur"></span></td></tr>');
		
		var tbody = jQuery(this).closest('table').find('tbody');
		
		tbody.append(theNewLine);
		
		//updateLine(theNewLine);
		updateLine(tbody.find('tr:last-child'));
	});
	
	jQuery('body').on('click','a.sButton',function(){
		saveCompetition(jQuery(this));
	});
	
});


function updateLine(theLine){
	//var theLine = hiddenField.parent().parent().parent().parent();
	var objID = theLine.closest('table').attr('data-term-id');
		//var hiddenField = sender;
		var my_data = {
			action: 'ajax_get_tireurs_select', // This is required so WordPress knows which func to use
			objID: objID
		};
		
		jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			var rData = jQuery.parseJSON(response);

			//alert(rData.message);
			var theSelect = jQuery(rData.message);
			var newSelect = theSelect.css('display','none');
			theLine.find('select.tireur').replaceWith(newSelect);
			
			//theSelect.trigger('change');
		});
}

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
	
	//alert('Nom: '+nom);
	//alert('Conducteur: '+conducteur);
	
	if(vehicule != false){
		dataString['nom_du_vehicule'] = vehicule;
	}
	
	if(nom_profil != false){
		dataString['nom_du_profil'] = nom_profil;
	}
	
		//dataString.test = 'test value';
		//dataString['conducteur'] = nom;
	
	//alert(dataString);
	var jsonString = JSON.stringify(dataString);

	//alert(jsonString);
	
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

function saveCompetition(eventSender){
	var theTable = eventSender.parent().find('table.comp_table');
	var classeID = theTable.attr('data-term-id');
	var eventID = theTable.attr('data-event-id');
	
	var pos = 0;
	
	var membres = [];
	var nonMembres = [];
	
	theTable.find('tbody').find('tr').each(function(){
		pos+=1;
		var theLine = jQuery(this);
		var distances = [];
		var distancesTypes = [];
		var conducteur = '';
		
		if(jQuery(this).find('input[type=checkbox]').is(':checked')){
			
			
			var membreID = theLine.find('select.tireur').val();
			conducteur = theLine.find('select.conducteurs_select').val();
			
			
			theLine.find('.mfield_container:not(.mfieldClone) select.distance_type').each(function(){
				distancesTypes.push(jQuery(this).val());
			});
			
			theLine.find('.mfield_container:not(.mfieldClone) input[type=number]').each(function(){
				distances.push(jQuery(this).val());
			});
			
			membres.push({ID: membreID, conducteur: conducteur, distances: distances, distancesTypes: distancesTypes, pos: pos});
		}else{
			var vehicule = jQuery(this).find('input.tireur').val();
			conducteur = jQuery(this).find('input.conducteur').val();
			
			theLine.find('.mfield_container:not(.mfieldClone) select.distance_type').each(function(){
				distancesTypes.push(jQuery(this).val());
			});
			
			theLine.find('.mfield_container:not(.mfieldClone) input[type=number]').each(function(){
				distances.push(jQuery(this).val());
			});
			
			nonMembres.push({vehicule: vehicule, conducteur: conducteur, distances: distances, distancesTypes: distancesTypes,pos: pos});
		}
	});
	
	var jsonStringMembres = JSON.stringify(membres);
	var jsonStringNonMembres = JSON.stringify(nonMembres);
	
	
	
	var my_data = {
				action: 'update_competition_results', // This is required so WordPress knows which func to use
				eventID: eventID,
				classeID : classeID,
				dataMembres: jsonStringMembres, // Post any variables you want here
				dataNonMembres: jsonStringNonMembres
			};

		jQuery.post(adminAjax, my_data, function(response) { // This will make an AJAX request upon page load
				//jQuery("#response").html("<div>"+response+"</div>");
				//jQuery("body").removeClass("loading");

			var rData = jQuery.parseJSON(response);
			alert(rData.message);				
		});
	
	theTable.parent().find('.save_data').html(jsonStringMembres+'\r\n\r\n'+jsonStringNonMembres);
	
	//alert('Membres: '+jsonStringMembres);
	//alert('Non-Membres: '+jsonStringNonMembres);
	
}
