<?php
/*
$the_slug = 'my_slug';
$args = array(
  'name'        => $the_slug,
  'post_type'   => 'post',
  'post_status' => 'publish',
  'numberposts' => 1
);
$my_posts = get_posts($args);
*/

function getRemoteSiteOption($user = REMOTE_WP_USER,$pass = REMOTE_WP_PASS){
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/get-option-value/';
	$curlMethod = 'POST';
	
	$data = array("option" => $_GET['option'], "password" => $pass);                                                                    
	$data_string = json_encode($data); 
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token,true); 

	curl_close($ch);
	
	return $token;
}


function getRemoteSiteEvents($user = REMOTE_WP_USER,$pass = REMOTE_WP_PASS){
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/get-posts-with-meta/';
	$curlMethod = 'POST';
	
	$data = array(	"args" => array(	"post_type" => "tribe_events",
										"posts_per_page" => 99999,
										"suppress_filters" => 0,
										'eventDisplay' => 'all',
										'meta_query' => array(	
																array(
													   				'key' => '_EventStartDate',
													   				'value' => date("Y").'-01-01 00:00:00',
																	'type' => 'DATE',
													   				'compare' => '>='
													 				)
									)
				  
	
	));    
	
	$data_string = json_encode($data); 	
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token,true); 

	curl_close($ch);
	
	
	//$token['data'] = $data;
	//$token['data_string'] = $data_string;
	
	return $token;
}

function getRemoteSiteVenues($user = REMOTE_WP_USER,$pass = REMOTE_WP_PASS){
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/get-posts-with-meta/';
	$curlMethod = 'POST';
	
	$data = array(	"args" => array(	"post_type" => "tribe_venue",
										"posts_per_page" => 99999,
										"suppress_filters" => 0,
										
	
	));    
	
	$data_string = json_encode($data); 	
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token,true); 

	curl_close($ch);
	
	return $token;
}

function getRemoteSiteEvent($pID = 0,$user = REMOTE_WP_USER,$pass = REMOTE_WP_PASS){
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/get-posts-with-meta/';
	$curlMethod = 'POST';
	
	$data = array("args" => array("post_type" => "tribe_events",
								  "posts_per_page" => 1,
								  "suppress_filters" => 0,
								  'meta_query' => array(	
																array(
													   				'key' => 'asttq_event_ID',
													   				'value' => (string)$pID,
													   				'compare' => '='
													 				)
								  )));    
	
	$data_string = json_encode($data); 	
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token,true); 

	curl_close($ch);
	
	return $token;
}

function getRemoteSiteVenue($pID = 0,$user = REMOTE_WP_USER,$pass = REMOTE_WP_PASS){
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/get-posts-with-meta/';
	$curlMethod = 'POST';
	
	$data = array("args" => array("post_type" => "tribe_venue",
								  "posts_per_page" => 1,
								  "suppress_filters" => 0,
								  'meta_query' => array(	
																array(
													   				'key' => 'asttq_venue_ID',
													   				'value' => (string)$pID,
													   				'compare' => '='
													 				)
								  )));    
	
	$data_string = json_encode($data); 	
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token,true); 

	curl_close($ch);
	
	return $token;
}

function updateRemotePostMeta($postID, $metaKey, $metaValue, $metaPrevValue = ''){
	
	$user = REMOTE_WP_USER;
	$pass = REMOTE_WP_PASS;
	
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/update-post-meta/';
	$curlMethod = 'POST';
	
	$data = array(	
					"postID" => $postID, 
					"metaKey" => $metaKey,
					"metaValue" => $metaValue,
					"metaPrevValue" => $metaPrevValue
				);
	
	$data_string = json_encode($data); 
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token); 

	curl_close($ch);
	
	return $token;
}


function updateRemoteOption($option, $value){
	
	$user = REMOTE_WP_USER;
	$pass = REMOTE_WP_PASS;
	
	$curlURL = 'https://asttq.net/wp-json/tcc/v1/set-option-value/';
	$curlMethod = 'POST';
	
	$data = array(	
					"option" => $option, 
					"value" => $value
				);
	
	$data_string = json_encode($data); 
	
	$ch = curl_init($curlURL); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POST, count($data));  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	$token = curl_exec($ch);
	$token=  json_decode($token); 

	curl_close($ch);
	
	return $token;
}


function getVenueByRemoteID($remoteID){
	$localVenuesArr = get_posts(array(	"post_type" => "tribe_venue",
										"posts_per_page" => 1,
									  	'post_status' => 'publish',
									  	'suppress_filters' => 0,
										'meta_query' => array(	
																array(
													   				'key' => 'asttq_venue_ID',
													   				'value' => $remoteID,
													   				'compare' => '='
													 				)
									)));
	return $localVenuesArr[0];
}

function compareEventsTable(){
	
	$remoteEvents = getRemoteSiteEvents();
	$localEvents = array();
	
	/*
	$localEventsArr = get_posts(array(	"post_type" => "tribe_events",
										"posts_per_page" => 99999,
									  	'post_status' => 'publish',
									  	'suppress_filters' => 0,
									  	'eventDisplay' => 'all',
									  
										'meta_query' => array(	
																array(
													   				'key' => '_EventStartDate',
													   				'value' => date("Y").'-01-01 00:00:00',
																	'type' => 'DATE',
													   				'compare' => '>='
													 				)
									)
									 
									
									 ));
	*/
	$localEventsArr = tribe_get_events( array(
														'eventDisplay' => 'custom',
														'start_date'   => date("Y").'-01-01 00:01',
														'end_date'     => date('Y').'-'.date('m').'-'.date('d').' 23:59',
														'posts_per_page' => '99999',
														'suppress_filters' => false
													) );
	
	
	
	
	$retVal = '[learn_more caption="Mise à jour des évènements"]<table class="events"><thead><tr>';
	
	$retVal .= ' 
	<!-- '.print_r($remoteEvents['data'],true).' --> 
	';
	$retVal .= ' 
	<!-- '.print_r($remoteEvents['data_string'],true).' --> 
	';
	
	$retVal .= ' 
	<!-- '.print_r($localEventsArr,true).' --> 
	';
	
	$retVal .= '<th>Évènement Local !</th>
	<th>Évènement sur le site distant</th>
	<th>Actions</th>
	</tr></thead><body>';
	
	foreach($localEventsArr as $theEvent){
		$slug = sanitize_title($theEvent->post_title);
		$postMeta = get_post_meta($theEvent->ID);
		
		$rid = get_post_meta($theEvent->ID,'asttq_event_ID');
		$rid = $rid[0];
		
		$localEvents[$rid][] = array(	'postOBJ' => $theEvent,
						 				'postMeta'=> $postMeta);
		
		$remoteEvent = $remoteEvents[$rid][0];
		
		$retVal .= '<tr>';
		$retVal .= '<td class="local_event" data-local-id="'.$theEvent->ID.'">'.$theEvent->post_title.'<div>'.$postMeta['_EventStartDate'][0].'</div></td>';
		$retVal .= '<td class="remote_event" data-remote-id="'.$asttq_event_id = $remoteEvent['postMeta']['asttq_event_ID'][0].'">';
		if(!empty($remoteEvent['postOBJ'])){
			$retVal .= $remoteEvent['postOBJ']['post_title'].'<div>'.$remoteEvent['postMeta']['_EventStartDate'][0].'</div>';
			$asttq_event_id = $remoteEvent['postMeta']['asttq_event_ID'][0];
			if(empty($asttq_event_id)){
				updateRemotePostMeta($remoteEvent['postOBJ']['ID'], 'asttq_event_ID', $remoteEvent['postOBJ']['ID']);
				update_post_meta( $theEvent->ID,'asttq_event_ID',$remoteEvent['postOBJ']['ID'] );
				//$retVal .= '<p>ASTTQ Event ID has been set!</p>';
			}
			
		}
		$retVal .= '</td><td class="actions">';
		
		
		//$retVal .= '<input class="import" value="Importer" />';
		
		$retVal .= '<input type="button" class="sync" value="Synchroniser les infos" />';
		
		$retVal .= '</td></tr>';
		unset($remoteEvents[$rid]);
	}
	
	foreach($remoteEvents as $key => $theEvent){
		$slug = $key;
		
		$retVal .= '<tr>';
		$retVal .= '<td class="local_event">&nbsp;</td>';
		$remoteEvent = $theEvent[0];
		
		
		$retVal .= '<td class="remote_event" data-remote-id="'.$theEvent[0]['postOBJ']['ID'].'" data-slug="'.$slug.'">';
		
		//$retVal .= '<!-- ';
		/*
		foreach($theEvent[0] as $line){
			$retVal .= '
			<pre>'.print_r($line,true).'</pre>';
		}
		$retVal .= '<hr />';
		*/
		//$retVal .= ' --> ';
		if(!empty($theEvent[0]['postOBJ'])){
			$retVal .= $theEvent[0]['postOBJ']['post_title'].'<div>'.$theEvent['postMeta']['_EventStartDate'][0].'</div>';
		}
		
		
		$asttq_event_id = $remoteEvent['postMeta']['asttq_event_ID'][0];
			if(empty($asttq_event_id)){
				updateRemotePostMeta($remoteEvent['postOBJ']['ID'], 'asttq_event_ID', $remoteEvent['postOBJ']['ID']);
			
				$retVal .= '<!-- <p>ASTTQ Event ID has been set!</p> -->';
			}
		
		$retVal .= '</td><td class="actions">';
		
		
		$retVal .= '<input type="button" class="import" value="Importer" />';
		
		$retVal .= '</td></tr>';
	}
	
	$retVal .= '</tbody></table>
	
	<script>
    ajax_url = "'.admin_url('admin-ajax.php').'";
	jQuery(document).ready(function(){
	
		jQuery("table.events input.import").on("click",function(){
			
			jQuery("body").addClass("loading");
			
			var line = jQuery(this).closest("tr");
			var remoteID = line.find(".remote_event").attr("data-remote-id");
			 var my_data = {
                    action: \'ajax_import_event\', // This is required so WordPress knows which func to use
                    remoteID: remoteID // Post any variables you want here
                };
		
			jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			
					var rData = jQuery.parseJSON(response);
					//alert(rData.message);	
			
                    line.find(".actions").html("<div>"+rData.message+"</div>");
					jQuery("body").removeClass("loading");
                });
		
		});
		
		
		jQuery("table.events input.sync").on("click",function(){
			
			jQuery("body").addClass("loading");
			
			var line = jQuery(this).closest("tr");
			var remoteID = line.find(".remote_event").attr("data-remote-id");
			var localID = line.find(".local_event").attr("data-local-id");
			
			 var my_data = {
                    action: \'ajax_update_event\', // This is required so WordPress knows which func to use
                    remoteID: remoteID,
					localID: localID
                };
		
			jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			
					var rData = jQuery.parseJSON(response);
					//alert(rData.message);	
			
                    line.find(".actions").html("<div>"+rData.message+"</div>");
					jQuery("body").removeClass("loading");
                });
		
		});
	
	});
</script>
	[/learn_more]
	';

	return do_shortcode($retVal);
	
}

function compareVenuesTable(){
	
	$remoteEvents = getRemoteSiteVenues();
	$localEvents = array();
	$localEventsArr = get_posts(array(	"post_type" => "tribe_venue",
										"posts_per_page" => 99999,
									  	'post_status' => 'publish',
									  	'suppress_filters' => 0
									));
	
	$remoteEventsSlugs = array();
		foreach($remoteEvents as $currentEvent){
			$newKey = sanitize_title($currentEvent[0]['postOBJ']['post_title']);
			$remoteEventsSlugs[$newKey] = $currentEvent;
		}
		
	$retVal = '';
		$retVal .= '<pre style="display:none;">'.print_r($remoteEvents,true).'</pre>';
		$retVal .= '<pre style="display:none;">'.print_r($remoteEventsSlugs,true).'</pre>';
	$retVal .= '[learn_more caption="Mise à jour des lieux"]<table class="lieux"><thead><tr>
	<th>Lieu Local</th>
	<th>Lieu sur le site distant</th>
	<th>Actions</th>
	</tr></thead><body>';
	
	foreach($localEventsArr as $theEvent){
		$slug = sanitize_title($theEvent->post_title);
		$postMeta = get_post_meta($theEvent->ID);
		
		
		
		$rid = get_post_meta($theEvent->ID,'asttq_venue_ID');
		$rid = $rid[0];
		
		$localEvents[$rid][] = array(	'postOBJ' => $theEvent,
						 				'postMeta'=> $postMeta);
		
		$remoteEvent = $remoteEvents[$rid][0];
		$remoteEventSlug = $remoteEventsSlugs[$slug][0];
		
		$retVal .= '<tr>';
		$retVal .= '<td class="local_event" data-local-id="'.$theEvent->ID.'">'.$theEvent->post_title.'<div>(modifié le '.$theEvent->post_modified.')</div></td>';
		$retVal .= '<td class="remote_event" data-remote-id="'.$remoteEvent['postMeta']['asttq_venue_ID'][0].'">';
		if(!empty($remoteEvent['postOBJ'])){
			$retVal .= $remoteEvent['postOBJ']['post_title'].'<div>(modifié le '.$remoteEvent['postOBJ']['post_modified'].')</div>';
			$asttq_venue_ID = $remoteEvent['postMeta']['asttq_venue_ID'][0];
			
			if(empty($asttq_venue_ID)){
				updateRemotePostMeta($remoteEvent['postOBJ']['ID'], 'asttq_venue_ID', $remoteEvent['postOBJ']['ID']);
				update_post_meta( $theEvent->ID,'asttq_venue_ID',$remoteEvent['postOBJ']['ID'] );
				$retVal .= '<!-- <p>ASTTQ Venue ID has been set!</p> -->';
				//$retVal .= '<p>ASTTQ Event ID has been set!</p>';
			}
			
		}elseif(!empty($remoteEventSlug['postOBJ'])){
			$retVal .= $remoteEventSlug['postOBJ']['post_title'].'<div>(modifié le '.$remoteEventSlug['postOBJ']['post_modified'].')</div>';
			$asttq_venue_ID = $remoteEventSlug['postMeta']['asttq_venue_ID'][0];
			$asttq_venue_ID_local = $postMeta['asttq_venue_ID'][0];
			if(empty($asttq_venue_ID)){
				updateRemotePostMeta($remoteEventSlug['postOBJ']['ID'], 'asttq_venue_ID', $remoteEventSlug['postOBJ']['ID']);
				$retVal .= '<!-- <p>ASTTQ Venue ID has been set! (remote)</p> -->';
				//$retVal .= '<p>ASTTQ Event ID has been set!</p>';
			}
			if(empty($asttq_venue_ID_local)){
				update_post_meta( $theEvent->ID,'asttq_venue_ID',$remoteEventSlug['postOBJ']['ID'] );
				$retVal .= '<!-- <p>ASTTQ Venue ID has been set! (local)</p> -->';
			}
			
		}
		$retVal .= '</td><td class="actions">';
		
		
		//$retVal .= '<input class="import" value="Importer" />';
		
		$retVal .= '<input type="button" class="sync" value="Synchroniser les infos" />';
		
		$retVal .= '</td></tr>';
		unset($remoteEvents[$rid]);
	}
	
	foreach($remoteEvents as $key => $theEvent){
		$slug = $key;
		
		$retVal .= '<tr>';
		$retVal .= '<td class="local_event">&nbsp;</td>';
		$remoteEvent = $theEvent[0];
		
		
		$retVal .= '<td class="remote_event" data-remote-id="'.$theEvent[0]['postOBJ']['ID'].'" data-slug="'.$slug.'">';
		
		//$retVal .= '<!-- ';
		/*
		foreach($theEvent[0] as $line){
			$retVal .= '
			<pre>'.print_r($line,true).'</pre>';
		}
		$retVal .= '<hr />';
		*/
		//$retVal .= ' --> ';
		if(!empty($theEvent[0]['postOBJ'])){
			$retVal .= $theEvent[0]['postOBJ']['post_title'].'<div>(modifié le '.$theEvent[0]['postOBJ']['post_modified'].')</div>';
		}
		
		
		$asttq_event_id = $remoteEvent['postMeta']['asttq_venue_ID'][0];
			if(empty($asttq_event_id)){
				updateRemotePostMeta($remoteEvent['postOBJ']['ID'], 'asttq_venue_ID', $remoteEvent['postOBJ']['ID']);
			
				$retVal .= '<!-- <p>ASTTQ Venue ID has been set!</p> -->';
			}
		
		$retVal .= '</td><td class="actions">';
		
		
		$retVal .= '<input type="button" class="import" value="Importer" />';
		
		$retVal .= '</td></tr>';
	}
	
	$retVal .= '</tbody></table>
	
	<script>
    ajax_url = "'.admin_url('admin-ajax.php').'";
	jQuery(document).ready(function(){
	
		jQuery("table.lieux input.import").on("click",function(){
			
			jQuery("body").addClass("loading");
			
			var line = jQuery(this).closest("tr");
			var remoteID = line.find(".remote_event").attr("data-remote-id");
			 var my_data = {
                    action: \'ajax_import_venue\', // This is required so WordPress knows which func to use
                    remoteID: remoteID // Post any variables you want here
                };
		
			jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			
					var rData = jQuery.parseJSON(response);
					//alert(rData.message);	
			
                    line.find(".actions").html("<div>"+rData.message+"</div>");
					jQuery("body").removeClass("loading");
                });
		
		});
		
		
		jQuery("table.lieux input.sync").on("click",function(){
			
			jQuery("body").addClass("loading");
			
			var line = jQuery(this).closest("tr");
			var remoteID = line.find(".remote_event").attr("data-remote-id");
			var localID = line.find(".local_event").attr("data-local-id");
			
			 var my_data = {
                    action: \'ajax_update_venue\', // This is required so WordPress knows which func to use
                    remoteID: remoteID,
					localID: localID
                };
		
			jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
			
					var rData = jQuery.parseJSON(response);
					//alert(rData.message);	
			
                    line.find(".actions").html("<div>"+rData.message+"</div>");
					jQuery("body").removeClass("loading");
                });
		
		});
	
	});
</script>
	[/learn_more]
	';

	return do_shortcode($retVal);
	
}

function ajax_import_event(){
	$remoteID = $_POST['remoteID'];
	$theRemoteEvent = getRemoteSiteEvent($remoteID);
	$theCurrentEvent = '';
	foreach($theRemoteEvent as $event){
		$theCurrentEvent = $event;
	}
	
	//$theEventPost = print_r($theCurrentEvent,true);
	
	 $defaults = array(
        'post_content' => $theCurrentEvent[0]['postOBJ']['post_content'],
        'post_title' => $theCurrentEvent[0]['postOBJ']['post_title'],
        'post_excerpt' => $theCurrentEvent[0]['postOBJ']['post_excerpt'],
        'post_status' => 'publish',
        'post_type' => 'tribe_events',
        'comment_status' => 'closed'
    );
	
	$newPostID = wp_insert_post( $defaults );
	
	foreach($theCurrentEvent[0]['postMeta'] as $key => $value){
		//update_post_meta( $newPostID, $key, $value[0] );
		
		
		if($key == '_EventVenueID'){
			$venueOBJ = getVenueByRemoteID($value[0]);
			$localVenueID = $venueOBJ->ID;
			update_post_meta( $newPostID, $key, $localVenueID );
		}else{
			update_post_meta( $newPostID, $key, $value[0] );
		}
		
	}
	
	$classesArray = array();
	foreach( $theCurrentEvent[0]['classes'] as $key => $value ){
		$classesArray[] = $value['slug'];
	}
	
	wp_set_object_terms( $newPostID, $classesArray, 'classes' );
	
	echo json_encode(array('message'=>'Le nouvel évènement ('.$theCurrentEvent[0]['postOBJ']['post_title'].') a été créé avec succès. Son numéro d\'identification est '.$newPostID,'objID'=>$newPostID));
	//echo json_encode(array('tireurs'=>$tireurArray,'noms'=>$tireursNoms));
	
	wp_die();
}
add_action( 'wp_ajax_ajax_import_event', 'ajax_import_event' );
add_action( 'wp_ajax_nopriv_ajax_import_event', 'ajax_import_event' );

function ajax_import_venue(){
	$remoteID = $_POST['remoteID'];
	$theRemoteEvent = getRemoteSiteVenue($remoteID);
	$theCurrentEvent = '';
	foreach($theRemoteEvent as $event){
		$theCurrentEvent = $event;
	}
	
	//$theEventPost = print_r($theCurrentEvent,true);
	
	 $defaults = array(
        'post_content' => $theCurrentEvent[0]['postOBJ']['post_content'],
        'post_title' => $theCurrentEvent[0]['postOBJ']['post_title'],
        'post_excerpt' => $theCurrentEvent[0]['postOBJ']['post_excerpt'],
        'post_status' => 'publish',
        'post_type' => 'tribe_venue',
        'comment_status' => 'closed'
    );
	
	$newPostID = wp_insert_post( $defaults );
	
	foreach($theCurrentEvent[0]['postMeta'] as $key => $value){
		update_post_meta( $newPostID, $key, $value[0] );
	}
	
	
	
	echo json_encode(array('message'=>'Le nouveau lieu ('.$theCurrentEvent[0]['postOBJ']['post_title'].') a été créé avec succès. Son numéro d\'identification est '.$newPostID,'objID'=>$newPostID));
	//echo json_encode(array('tireurs'=>$tireurArray,'noms'=>$tireursNoms));
	
	wp_die();
}
add_action( 'wp_ajax_ajax_import_venue', 'ajax_import_venue' );
add_action( 'wp_ajax_nopriv_ajax_import_venue', 'ajax_import_venue' );


function ajax_update_event(){
	
	$remoteID = $_POST['remoteID'];
	$localID = $_POST['localID'];
	
	$theRemoteEvent = getRemoteSiteEvent($remoteID);
	$theCurrentEvent = '';
	
	foreach($theRemoteEvent as $event){
		$theCurrentEvent = $event;
	}
	
	//$theEventPost = print_r($theCurrentEvent,true);
	
	 $defaults = array(
		'ID' => $localID,
        'post_content' => $theCurrentEvent[0]['postOBJ']['post_content'],
        'post_title' => $theCurrentEvent[0]['postOBJ']['post_title'],
        'post_excerpt' => $theCurrentEvent[0]['postOBJ']['post_excerpt'],
        'post_status' => 'publish',
        'post_type' => 'tribe_events',
        'comment_status' => 'closed'
    );
	
	wp_update_post( $defaults );
	
	foreach($theCurrentEvent[0]['postMeta'] as $key => $value){
		
		if($key == '_EventVenueID'){
			$venueOBJ = getVenueByRemoteID($value[0]);
			$localVenueID = $venueOBJ->ID;
			update_post_meta( $localID, $key, $localVenueID );
		}else{
			update_post_meta( $localID, $key, $value[0] );
		}
		
	}
	
	$classesArray = array();
	foreach( $theCurrentEvent[0]['classes'] as $key => $value ){
		$classesArray[] = $value['slug'];
	}
	
	wp_set_object_terms( $localID, $classesArray, 'classes' );
	
	echo json_encode(array('message'=>'L\'évènement ('.$theCurrentEvent[0]['postOBJ']['post_title'].') a été mis à jour avec succès. Son numéro d\'identification est '.$localID,'objID'=>$localID, 'fetched'=>$theRemoteEvent));
	//echo json_encode(array('tireurs'=>$tireurArray,'noms'=>$tireursNoms));
	
	wp_die();
}
add_action( 'wp_ajax_ajax_update_event', 'ajax_update_event' );
add_action( 'wp_ajax_nopriv_ajax_update_event', 'ajax_update_event' );

function ajax_update_venue(){
	
	$remoteID = $_POST['remoteID'];
	$localID = $_POST['localID'];
	
	$theRemoteEvent = getRemoteSiteVenue($remoteID);
	$theCurrentEvent = '';
	
	foreach($theRemoteEvent as $event){
		$theCurrentEvent = $event;
	}
	
	//$theEventPost = print_r($theCurrentEvent,true);
	
	 $defaults = array(
		'ID' => $localID,
        'post_content' => $theCurrentEvent[0]['postOBJ']['post_content'],
        'post_title' => $theCurrentEvent[0]['postOBJ']['post_title'],
        'post_excerpt' => $theCurrentEvent[0]['postOBJ']['post_excerpt'],
        'post_status' => 'publish',
        'post_type' => 'tribe_venue',
        'comment_status' => 'closed'
    );
	
	wp_update_post( $defaults );
	
	foreach($theCurrentEvent[0]['postMeta'] as $key => $value){
		update_post_meta( $localID, $key, $value[0] );
	}
	
	
	wp_set_object_terms( $localID, $classesArray, 'classes' );
	
	echo json_encode(array('message'=>'Le lieu ('.$theCurrentEvent[0]['postOBJ']['post_title'].') a été mis à jour avec succès. Son numéro d\'identification est '.$localID,'objID'=>$localID, 'fetched'=>$theRemoteEvent));
	//echo json_encode(array('tireurs'=>$tireurArray,'noms'=>$tireursNoms));
	
	wp_die();
}
add_action( 'wp_ajax_ajax_update_venue', 'ajax_update_venue' );
add_action( 'wp_ajax_nopriv_ajax_update_venue', 'ajax_update_venue' );
?>