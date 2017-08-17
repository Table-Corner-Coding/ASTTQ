<?php
//error_reporting(E_ALL);

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' ); 

function my_enqueue_assets() { 

    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' ); 
	
	if(isset($_GET['in_iframe'])){
		wp_enqueue_script( 'iframe-resizer', get_stylesheet_directory_uri().'/includes/iframe-resizer-master/js/iframeResizer.contentWindow.min.js', array('jquery') );
	}else{
		wp_enqueue_script( 'iframe-resizer', get_stylesheet_directory_uri().'/includes/iframe-resizer-master/js/iframeResizer.min.js', array('jquery') );
	}
	
	wp_enqueue_script( 'tcc-scripts', get_stylesheet_directory_uri().'/includes/tcc.js', array('jquery','iframe-resizer') );
	
	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'tcc-scripts', 'ajax_url', admin_url( 'admin-ajax.php' ) );
	

} 

add_filter('body_class', 'multisite_body_classes');

function multisite_body_classes($classes) {

        if(isset($_GET['in_iframe'])){
			$classes[] = 'iframe';
			
		}
	
	if(is_user_logged_in() || is_page(3214)){
		//$classes[] = 'et_header_style_fullscreen';
	}

        return $classes;
}


add_filter( 'wp_insert_post_data' , 'modify_post_title' , '99', 2 ); // Grabs the inserted post data so you can modify it.

function modify_post_title( $data,  $postarr )
{
	$post_id = $postarr['ID'];
  if($data['post_type'] == 'tireurs') { // If the actual field name of the rating date is different, you'll have to update this.
	
	  
	  $option_name = 'tireurs-to-update';
	  	$current_array = get_option($option_name);
		$current_array[$post_id] = mktime();
		update_option( $option_name, $current_array );
	  
	  if(isset($_POST['acf'])){
		  $nomProfil = $_POST['acf']['field_59348bdad3bcc'];
		  $vehicule = $_POST['acf']['field_591d0b5cbeb04'];
	  }
	  else{
		  $nomProfil = get_field('field_59348bdad3bcc', $post_id);
		  $vehicule = get_field('field_591d0b5cbeb04',$post_id);
	  }
	  
	  $vehicule = get_field('field_591d0b5cbeb04',$post_id);
	  
	  if(empty($nomProfil)){
		  $post_obj = get_post($post_id);
		  if(!empty($vehicule)){
			  $nomProfil = $vehicule;
		  }else{
			  $nomProfil = ucwords(str_replace('-',' ',$post_obj->post_name));
		  }
		  
		  
	  }
	
	$classes = wp_get_post_terms( $post_id, $taxonomy = 'classes' );
	  
	if(!empty($classes[0])){
		$classe = $classes[0];
		$title = '['.$classe->name.'] - '.$nomProfil;
	}else{
		  $title = '[] - '.$nomProfil;
	  }
	
	if($title != '[] - '){
		$data['post_title'] =  $title ; //Updates the post title to your new title.
	}
    
  }elseif($data['post_type'] == 'tribe_events'){
	  	
	  $option_name = 'events-to-update';
	  	$current_array = get_option($option_name);
		$current_array[$post_id] = mktime();
		update_option( $option_name, $current_array );
  }
  return $data; // Returns the modified data.
}




function my_et_builder_post_types( $post_types ) {
    $post_types[] = 'tireurs';

     
    return $post_types;
}
add_filter( 'et_builder_post_types', 'my_et_builder_post_types' );



// Register Custom Post Type
function tireurs_function() {

	$labels = array(
		'name'                  => _x( 'Tireurs', 'Post Type General Name', 'asttq' ),
		'singular_name'         => _x( 'Tireur', 'Post Type Singular Name', 'asttq' ),
		'menu_name'             => __( 'Tireurs', 'asttq' ),
		'name_admin_bar'        => __( 'Tireur', 'asttq' ),
		'archives'              => __( 'Archives', 'asttq' ),
		'attributes'            => __( 'Attributs', 'asttq' ),
		'parent_item_colon'     => __( 'Tireur parent:', 'asttq' ),
		'all_items'             => __( 'Tous les tireurs', 'asttq' ),
		'add_new_item'          => __( 'Nouveau tireur', 'asttq' ),
		'add_new'               => __( 'Nouveau tireur', 'asttq' ),
		'new_item'              => __( 'Nouveau tireur', 'asttq' ),
		'edit_item'             => __( 'Modifier le tireur', 'asttq' ),
		'update_item'           => __( 'Mettre à jour le tireur', 'asttq' ),
		'view_item'             => __( 'Voir le tireur', 'asttq' ),
		'view_items'            => __( 'Voir les tireurs', 'asttq' ),
		'search_items'          => __( 'Chercher un tireur', 'asttq' ),
		'not_found'             => __( 'Non trouvé', 'asttq' ),
		'not_found_in_trash'    => __( 'Rien dans la corbeille', 'asttq' ),
		'featured_image'        => __( 'Photo', 'asttq' ),
		'set_featured_image'    => __( 'Mettre une photo', 'asttq' ),
		'remove_featured_image' => __( 'Enlever la photo', 'asttq' ),
		'use_featured_image'    => __( 'Utiliser comme photo', 'asttq' ),
		'insert_into_item'      => __( 'Insérer', 'asttq' ),
		'uploaded_to_this_item' => __( 'Mis en ligne sur ce tireur', 'asttq' ),
		'items_list'            => __( 'Liste', 'asttq' ),
		'items_list_navigation' => __( 'Liste de navigation', 'asttq' ),
		'filter_items_list'     => __( 'Filter items list', 'asttq' ),
	);
	$args = array(
		'label'                 => __( 'Tireur', 'asttq' ),
		'description'           => __( 'Post Type Description', 'asttq' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-universal-access',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'tireurs', $args );

}
add_action( 'init', 'tireurs_function', 0 );



add_filter('tg_register_item_skin', function($skins) {
	
    // just push your skin slugs (file name) inside the registered skin array
    $skins = array_merge($skins,
        array(
            'tg-tcc1' => array(
                'filter'   => 'TCC3', // filter name used in slider skin preview
                'name'     => 'TCC3', // Skin name used in skin preview label
                'col'      => 1, // col number in preview skin mode
                'row'      => 1  // row number in preview skin mode
            )
        )
    );
    
    // return all skins + the new one we added (my-skin1)
    return $skins;
	
});



// add a skin in a plugin/theme
add_filter('tg_add_item_skin', function($skins) {

    $PATH = get_stylesheet_directory();
	
    // register a skin and add it to the main skins array
    $skins['tg-tcc3'] = array(
        'type'   => 'masonry',
        'filter' => 'TCC3',
        'slug'   => 'tg-tcc3',
        'name'   => 'TCC3',
        'php'    => $PATH . '/the-grid/masonry/tcc1/tcc1.php',
        'css'    => $PATH . '/the-grid/masonry/tcc1/tcc1.css',
        'col'    => 1, // col number in preview skin mode
        'row'    => 1  // row number in preview skin mode
    );
    
    // return the skins array + the new one you added (in this example 2 new skins was added)
    return $skins;
    
});


function my_custom_transient_expiration() {
	return 0; // 6 months
}

add_filter( 'tg_transient_expiration', 'my_custom_transient_expiration' );




/*
 * Hide end time in list, map, photo, and single event view
 * NOTE: This will only hide the end time for events that end on the same day
 */
function tribe_remove_end_time_single( $formatting_details ) {
	$formatting_details['show_end_time'] = 0;

	return $formatting_details;
}
add_filter( 'tribe_events_event_schedule_details_formatting', 'tribe_remove_end_time_single', 10, 2);

/*
 * Hide end time in Week and Month View Tooltips
 * NOTE: This will hide the end time in tooltips for ALL events, not just events that end on the same day
 */
function tribe_remove_end_time_tooltips( $json_array, $event, $additional ) {
	$json_array['endTime'] = '';

	return $json_array;
}
add_filter( 'tribe_events_template_data_array', 'tribe_remove_end_time_tooltips', 10, 3 );

/*
 * Hide endtime for multiday events
 * Note: You will need to uncomment this for it to work
 */
function tribe_remove_endtime_multiday ( $inner, $event ) {

	if ( tribe_event_is_multiday( $event ) && ! tribe_event_is_all_day( $event ) ) {

		$format                   = tribe_get_date_format( true );
		$time_format              = get_option( 'time_format' );
		$format2ndday             = apply_filters( 'tribe_format_second_date_in_range', $format, $event );
		$datetime_separator       = tribe_get_option( 'dateTimeSeparator', ' @ ' );
		$time_range_separator     = tribe_get_option( 'timeRangeSeparator', ' - ' );
		$microformatStartFormat   = tribe_get_start_date( $event, false, 'Y-m-dTh:i' );
		$microformatEndFormat     = tribe_get_end_date( $event, false, 'Y-m-dTh:i' );

		$inner = '<span class="date-start dtstart">';
		$inner .= tribe_get_start_date( $event, false, $format ) . $datetime_separator . tribe_get_start_date( $event, false, $time_format );
		$inner .= '<span class="value-title" title="' . $microformatStartFormat . '"></span>';
		$inner .= '</span>' . $time_range_separator;
		$inner .= '<span class="date-end dtend">';
		$inner .= tribe_get_end_date( $event, false, $format2ndday );
		$inner .= '<span class="value-title" title="' . $microformatEndFormat . '"></span>';
		$inner .= '</span>';

	}

	return $inner;
}
//add_filter( 'tribe_events_event_schedule_details_inner', 'tribe_remove_endtime_multiday', 10, 3 );
/*
$post_type = 'tribe_venue';
set_post_type( 3157, 'tribe_venue' );
set_post_type( 3121, $post_type );
set_post_type( 3113, $post_type );
set_post_type( 3109, $post_type );
set_post_type( 3095, $post_type );
set_post_type( 3041, $post_type );
*/
/* Admin CSS */
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
  .post-php #postcustom,
  .post-php #the_grid_item_formats,
    td[data-name=rang],
	td[data-name=pointage],
	td[data-name=points],
	th[data-name=rang],
	th[data-name=pointage],
	th[data-name=points]{
      display:none;
    } 
	
	
  </style>';
}


function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

// Add Shortcode
function edition_positions_shortcode($att) {
	global $post;
	
	// Attributes
	$atts = '';
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	
	wp_deregister_style( 'wp-admin' );
	ob_start();
	acf_form_head();
	
	$event_id = $atts['id'];
	
	if(empty($event_id)){
		$event_id = $post->ID;
	}
	
	$new_post = array(
		'post_id'            => $event_id, // Create a new post
		// PUT IN YOUR OWN FIELD GROUP ID(s)
		'field_groups'       => array(3181), // Create post field group ID(s)
		'form'               => true,
		'return'             => '', // Redirect to new post url
		'html_before_fields' => '',
		'html_after_fields'  => '',
		'submit_value'       => 'Mettre à jour le classement',
		'updated_message'    => 'Saved!'
	);
	acf_form( $new_post );
		
	$retVal = ob_get_clean();
	
	return($retVal);
}
add_shortcode( 'edition_positions', 'edition_positions_shortcode' );


// Add Shortcode
function sommaire_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'annee' => '',
		),
		$atts,
		'sommaire'
	);

	$theYear = $atts['annee'];
	
	if(empty($theYear)){
	/* Si l'année n'a pas été spécifiée, on prend l'année en cours */
	$theYear = date('Y');
	}
	
	/* On ramasse tout les événement de l'année voulue */
	
	$events = tribe_get_events( array(
    'eventDisplay' => 'custom',
    'start_date'   => $theYear.'-01-01 00:01',
    'end_date'     => date('Y').'-'.date('m').'-'.date('d').' 23:59',
	'posts_per_page' => '99999'
	) );
	
	
	
	$termine = 0;
	
	
	$content = '';
	
	$args = array(
			'taxonomy' => 'classes',
			'hide_empty' => false
		);
		
	$terms = get_terms( $args );
	
	$classes_events = array();
	$last_location = '';
	$first  = true;
	$new_place = false;	
	
	foreach($events as $current_event){
		
		$term_list = wp_get_post_terms($current_event->ID, 'classes', array("fields" => "ids"));
		foreach($term_list as $current_classe){
			$classes_events[$current_classe][] = $current_event;
		}
		
		$termine = get_field('field_5939ced2dcd39',$current_event->ID);
		if($termine){
			
		$place = tribe_get_venue ( $current_event->ID );
		if($place != $last_location){
			$last_location = $place;
			$new_place = true;
		}else{
			$new_place = false;
		}	
		
			if($first || $new_place){
				
				if(!$first){
					$content .= '<div><a href="#_top_">[ [wpml__ context=asttq]Retour en haut[/wpml__] ]</a></div>[/learn_more]<hr />';
				}
				
				$content .= '[learn_more caption="'.$place.'"]<a name="'.str_replace(' ','_',$place).'"></a>';
			}
			$content .= '<h2 class="comp_title">'.$current_event->post_title.'</h2>'.get_points_table_for_event($current_event->ID);
		}
		
		$first = false;
	}
	
	$content .= '<div><a href="#_top_">[ [wpml__ context=asttq]Retour en haut[/wpml__] ]</a></div>[/learn_more]<hr />';
	
	$sommaire_transient_name = 'asttq_sommaire_'.$theYear;
	$sommaire_table = get_transient($sommaire_transient_name);
	
	$content .= '[learn_more caption="'.__('Sommaire').'"]';
		
	
	
	
	foreach($terms as $classe){
		
		$eventsFromClasse = $classes_events[$classe->term_id];
		
		$content .= '<h3>'.$classe->name.'</h3>
					<div class="scrollable">
					<table><thead><tr>
					<th>[wpml__ context=asttq]Rang[/wpml__]</th>
					<th>[wpml__ context=asttq]Vehicule[/wpml__]</th>
					<th>[wpml__ context=asttq]Total[/wpml__]</th>
		';
		
		$usedNames = array();
		
		$totals = array();
		
		foreach($eventsFromClasse as $theEvent){
			$place = tribe_get_venue ( $theEvent->ID );
			$name = substr($place,0,4);
			
			
			if(empty($usedNames[$name])){
				$usedNames[$name] = 1;
				$name = $name."1";
			}else{
				$nb = $usedNames[$name];
				$nb++;
				$usedNames[$name] = $nb;
				$name = $name.$nb;
			}
			
			foreach($sommaire_table[$theEvent->ID] as $key => $eventPoints){
				if(empty($totals[$key])){
					$totals[$key] = 0;
				}
				$totals[$key] = $totals[$key]+$eventPoints;
			}
			
			$content .= '<th>'.$name.'</th>';
		}
		
		$content .= '</tr></thead><tbody>';
		
		arsort($totals);
		
		$rg = 0;
		foreach($totals as $key => $value){
			$rg++;
			
			$tireur = get_post($key);
			$tID = $tireur->ID;
			$nom_du_vehicule = get_field('nom_du_vehicule',$tID);
			
			$content .= '<tr><td>'.$rg.'</td><td>'.$nom_du_vehicule.'</td><td>'.$value.'</td>';
			
			foreach($eventsFromClasse as $theEvent){
				$eID = $theEvent->ID;
				$content .= '<td>';
				if(empty($sommaire_table[$eID][$tID])){
					$content .= '-';
				}else{
					$content .= $sommaire_table[$eID][$tID];
				}
				$content .= '</td>';
			}
		}
		
		
		$content .= '</tbody></table></div>';
		
		/* Fetch events for current classe for current Year */
		
	}
	
	
	$content .= '<div><a href="#_top_">[ [wpml__ context=asttq]Retour en haut[/wpml__] ]</a></div>[/learn_more]<hr />
		';
	
	
	return do_shortcode('<div id="pointages">'.$content.'</div>');
	
	
}
add_shortcode( 'sommaire', 'sommaire_shortcode' );




function edition_positions_page_shortcode($att) {
	global $post;
	
	// Attributes
	$atts = '';
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	
	$thisYear = date('Y');
	
	$events = tribe_get_events( array(
    'eventDisplay' => 'custom',
    'start_date'   => $thisYear.'-01-01 00:01',
    'end_date'     => $thisYear.'-12-31 23:59',
		'posts_per_page' => '99999'
	) );
	
	
	
	$retVal = '<div><select id="evenements">';
	
	//$retVal .= '<option value="">Sélection de l\'évènement</option>';
	
	$itt = 0;
	$firstUrl = '';
	
	foreach($events as $event){
		if($itt == 0){ 
			$retVal .= '<option value="">Sélection de l\'évènement</option>';
			//$firstUrl = get_permalink($event->ID).'?in_iframe=1'; 
		}
		$retVal .= '<option value="'.get_permalink($event->ID).'?in_iframe=1">'.$event->post_title.'</option>';
		$itt++;
	}
	
	$retVal .= '</select></div>';
	
	$firstUrl = '/empty-page?in_iframe=1';
	$retVal .= '<div class="iframe_container"><iframe scrolling="no" onLoad="" src="'.$firstUrl.'" class="evenement_frame" id="evenement_frame"></iframe></div>';
	
	wp_deregister_style( 'wp-admin' );

	
	return($retVal);
}
add_shortcode( 'edition_positions_page', 'edition_positions_page_shortcode' );



function edition_competiteurs_page_shortcode($att) {
	global $post;
	
	// Attributes
	$atts = '';
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	
	$thisYear = date('Y');
	
	$events = get_posts( array(
    	'post_type'		=> 'tireurs',
		'posts_per_page' => '99999'
	) );
	
	
	
	$retVal = '<div><select id="evenements">';
	
	//$retVal .= '<option value="">Sélection de l\'évènement</option>';
	
	$itt = 0;
	$firstUrl = '';
	
	foreach($events as $event){
		if($itt == 0){ 
			$retVal .= '<option value="">Sélection de l\'évènement</option>';
			$firstUrl = get_permalink($event->ID).'?in_iframe=1'; 
		}
		$retVal .= '<option value="'.get_permalink($event->ID).'?in_iframe=1">'.$event->post_title.'</option>';
		$itt++;
	}
	
	$retVal .= '</select></div>';
	
	$firstUrl = '/empty-page?in_iframe=1';
	$retVal .= '<div class="iframe_container"><iframe scrolling="no" onLoad="" src="'.$firstUrl.'" class="evenement_frame" id="evenement_frame"></iframe></div>';
	
	wp_deregister_style( 'wp-admin' );

	
	return($retVal);
}
add_shortcode( 'edition_competiteurs_page', 'edition_competiteurs_page_shortcode' );


// Add Shortcode
function edition_competiteurs_shortcode($att) {
	global $post;
	
	// Attributes
	$atts = '';
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	
	wp_deregister_style( 'wp-admin' );
	ob_start();
	acf_form_head();
	
	$event_id = $atts['id'];
	
	if(empty($event_id)){
		$event_id = $post->ID;
	}
	
	$new_post = array(
		'post_id'            => $event_id, // Create a new post
		// PUT IN YOUR OWN FIELD GROUP ID(s)
		'field_groups'       => array(3024), // Create post field group ID(s)
		'form'               => true,
		'return'             => '', // Redirect to new post url
		'html_before_fields' => '',
		'html_after_fields'  => '',
		'submit_value'       => 'Mettre à jour le compétiteur',
		'updated_message'    => 'Modifications enregistrées'
	);
	acf_form( $new_post );
		
	$retVal = ob_get_clean();
	
	return($retVal);
}
add_shortcode( 'edition_competiteurs', 'edition_competiteurs_shortcode' );


// Add Shortcode
function TCC_LOGIN_shortcode() {
	$retVal = '<div class="et_pb_section  et_pb_section_0 et_section_regular et_pb_section_first" data-fix-page-container="on" style="padding-top: 168px;"><div class=" et_pb_row et_pb_row_0 et_pb_row_empty">'.do_shortcode('[LOGIN_FORM]').'</div> <!-- .et_pb_row -->
	</div>';
}
add_shortcode( 'TCC_LOGIN', 'TCC_LOGIN_shortcode' );



function acf_load_tireur_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();


	$tireurs = get_posts(array(
								  'post_type' => 'tireurs',
								  'numberposts' => -1,
								  
								)); 
	
    // if has rows

        
        // while has rows
        foreach( $tireurs as $tireur ) {
            
            // instantiate row
            the_row();
            
            
			$vehicule = get_field('nom_du_vehicule', $tireur->ID);
			$nom = $tireur->post_title;
			
            // vars
            $value = $tireur->ID;
            $label = $nom.' ('.$vehicule.')';

            
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
        
    


    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=tireur', 'acf_load_tireur_field_choices');




function foreignDbAction(){
	global $wpdb;
	
	$option_name = 'tireurs-to-update';
	$tireurs_array = get_option($option_name);
	
	$option_name = 'events-to-update';
	$events_array = get_option($option_name);
	
	$posts_to_update=array();
	
	foreach($tireurs_array as $key=>$value){
		$postOBJ = get_post($key);
		$postMeta = get_post_meta($postOBJ->ID);
		
		$posts_to_update[] = array(	'postOBJ' => $postOBJ,
										'postMeta'=> $postMeta
								   );
	}
	
	foreach($events_array as $key=>$value){
		$postOBJ = get_post($key);
		$postMeta = get_post_meta($postOBJ->ID);
		
		$posts_to_update[] = array(	'postOBJ' => $postOBJ,
										'postMeta'=> $postMeta
								   );
	}
	
	$dbPass = DB_PASSWORD;
	$dbUser = DB_USER;
	$dbName = DB_NAME;
	$dbHost = 'tccdev.net';
	
	$retVal = '';
	
	/* Si nous avons une adresse courriel, nous mettons à jour l'historique de peiements dans la base de données des membres */
	
	
	$wpdb_old = wp_clone($GLOBALS['wpdb']);
	$wpdb_new = &$GLOBALS['wpdb'];	
	
		
	//$oldWPDB = $wpdb;
	
	$wpdb_new = new wpdb($dbUser,$dbPass,$dbName,$dbHost);
	$wpdb_new->set_prefix('wrdp_2017_');

	$tempVar = $wpdb_new;

	$retVal .= '<h4>Mises à jour effectuées: </h4><table><thead><tr><th>Type</th><th>Post</th><th>Time</th></tr></thead><tbody>';
	
		foreach($posts_to_update as $current_post){
			$the_post_obj = $current_post['postOBJ'];
			$the_post_meta = $current_post['postMeta'];
			
			foreach($the_post_meta as $key=>$value){
				if(is_array($value))
				{
					update_post_meta($the_post_obj->ID,$key,$value[0]);
				}else{
					update_post_meta($the_post_obj->ID,$key,$value);
				}
				
			}
			wp_update_post($the_post_obj);
			$retVal .= '<tr><td>'.$the_post_obj->post_type.'</td><td>'.$the_post_obj->post_title.'</td><td>'.strftime('%d/%m/%y - %H:%M').'</td></tr>';
		}
		$retVal .= '</tbody></table>';
	
	// On retourne sur le site local
	$wpdb_new = $wpdb_old;	
	
	$option_name = 'tireurs-to-update';
	delete_option( $option_name );
	
	$option_name = 'events-to-update';
	delete_option( $option_name );
	
	/*
	ob_start();
		
	var_dump($tempVar);
	
	$retVal = ob_get_clean();
*/
	return $retVal;
	//return $retVal;
	
	
	//return $retVal.'<pre>'.print_r($oldWPDB, true).'</pre><pre>'.print_r($mydb, true).'</pre><pre>'.print_r($wpdb, true).'</pre>';
}

// Add Shortcode
function update_pointages_shortcode() {
	
	$retVal = '';
	
	if(is_user_logged_in()){
	$retVal .= '
	
	<div class="update_btn_holder">
		<h3>Mise à jour de la base de données en ligne</h3>
		<input type="button" value="Mettre à jour sur le serveur distant" id="update_btn" />
	</div>
	<br /><br />
	<div id="response">
	
	<h4>Tireurs à mettre à jour:</h4>
	
	<table><tbody>
	';
	
	$option_name = 'tireurs-to-update';
	$current_array = get_option($option_name);
	if(is_array($current_array)){			
	foreach($current_array as $key=>$value){
		
		if(!empty($key)){
			$postOBJ = get_post($key);
			
			$title = $postOBJ->post_title;
		}else{
			$title = '';
		}
		
		$retVal .= '<tr><td>('.$key.') '.$title.'</td><td>'.$value.'</td></tr>';
	}
	}else{
		$retVal .= '<tr><td>Aucun tireur n\'a été mis à jour depuis la dernière syncronisation...</td></tr>';
	}
	$retVal .= '
	</tbody></table>
	
	<br /><br />
	
	<h4>Évènements à mettre à jour:</h4>
	
	<table><tbody>
	';
	
	$option_name = 'events-to-update';
	$current_array = get_option($option_name);
	if(is_array($current_array)){		
	foreach($current_array as $key=>$value){
		if(!empty($key)){
			$postOBJ = get_post($key);
			
			$title = $postOBJ->post_title;
		}else{
			$title = '';
		}
		
		$retVal .= '<tr><td>('.$key.') '.$title.'</td><td>'.$value.'</td></tr>';
	}
	}else{
		$retVal .= '<tr><td>Aucun évènement n\'a été mis à jour depuis la dernière syncronisation...</td></tr>';
	}		
	$retVal .= '
	</tbody></table>
	
	</div>
	
	<script>
    ajax_url = "'.admin_url('admin-ajax.php').'";
	jQuery(document).ready(function(){
	
		jQuery("#update_btn").on("click",function(){
		
			jQuery("body").addClass("loading");
		
			 var my_data = {
                    action: \'update_point\', // This is required so WordPress knows which func to use
                    whatever: "yes it is" // Post any variables you want here
                };
		
			jQuery.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
                    jQuery("#response").html("<div>"+response+"</div>");
					jQuery("body").removeClass("loading");
                });
		
		});
	
	});
</script>';
	}
	return $retVal;
}
add_shortcode( 'update_pointages', 'update_pointages_shortcode' );


function update_point() {
	global $wpdb;
	echo foreignDbAction();
	wp_die();
}
add_action( 'wp_ajax_update_point', 'update_point' );
add_action( 'wp_ajax_nopriv_update_point', 'update_point' );


function get_points_table_for_event($event_id, $refresh = false){
	
	
	
	$theYear = tribe_get_start_date ( $event_id, false, 'Y' );
	
	$done = array();
	$transient_name = 'asttq_p_table_'.$event_id;
	$sommaire_transient_name = 'asttq_sommaire_'.$theYear;
	
	$current_table = get_transient($transient_name);
	
	$current_points_t_name = 'asttq_points_'.$event_id;
	
	$competitions = get_field('field_592da5f526f1e', $event_id);
			
	$bonus_position = array(0,15,12,10,9,8,7,6,5,4,3,2,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); 
	
	$pointsTable = get_transient($current_points_t_name);
	if(empty($points))
	{
		$points = array();
		$pointsTable = true;
	}
	
	
	$sommaire = get_transient($sommaire_transient_name);
	if(empty($sommaire)){
		$sommaire = array();
	}

//print_r($bonus_inscription);

	//$classement = '';		

	$classement = '';
	
	if(empty($current_table) || $refresh == true)
	{
		$grille = array();
		$grille_finale = array();
		foreach($competitions as $competition){
			
			//$grille = array();
			
			$bonus_inscription = $competition['bonus_inscription'];	
			
			$term = get_term( $competition['classe'], 'classes' );
			$classement .= '<!-- '.print_r($grille,true).' --> <h3>'.$term->name.'</h3> <!-- Grille finale: '.print_r($grille_finale,true).' -->';

			$grille = array();
			$grille_finale = array();
			
			$fullPull = array();
			$fullPullNM = array();
			
			foreach($competition['competiteur'] as $competiteur){
				
				
				$tireur_id = $competiteur['tireur'];
				$tireur = get_post($tireur_id);
				$vehicule = get_field('nom_du_vehicule', $tireur_id);
				
				//$nom_tireur = get_field('nom_du_profil', $tireur_id);
				$nom_tireur = $competiteur['nom_du_tireur'];
				
				$distances = $competiteur['distances'];
				
				$highest = 0;
				$fp = false;
				
				foreach($distances as $current_distance){
					//if($current_distance['distance']>$highest){
						$highest = $current_distance['distance'];
					//}
					if($current_distance['statut'] == 'FP'){
						$fp = true;
					}elseif($current_distance['statut'] == 'DNS'){
						$highest = -1;
					}elseif($current_distance['statut'] == 'DQ'){
						$highest = -2;
					}
				}
				
				if($fp){
					$fullPull[] = array(	'nom_tireur'=>$nom_tireur,
											'vehicule'=>$vehicule,
											'distance'=>$highest,
									   		'non-membre' => false,
									   		'ID' => $tireur->ID);
				}
				else{
					$grille[] = array(	'nom_tireur'=>$nom_tireur,
										'vehicule'=>$vehicule,
										'distance'=>$highest,
									  	'non-membre' => false,
									  	'ID' => $tireur->ID
									 );
				}
				
			}

			foreach($competition['non-membre'] as $nonmembre){
				$distance = $nonmembre['distance'];
				$vehicule = $nonmembre['vehicule'];
				$nom_tireur = $nonmembre['nom_du_tireur'];
				$distances = $nonmembre['distances'];
				
				$highest = 0;
				$fp = false;
				
				foreach($distances as $current_distance){
					//if($current_distance['distance']>$highest){
						$highest = $current_distance['distance'];
					//}
					if($current_distance['statut'] == 'FP'){
						$fp = true;
					}elseif($current_distance['statut'] == 'DNS'){
						$highest = -1;
					}elseif($current_distance['statut'] == 'DQ'){
						$highest = -2;
					}
				}
				
				if($fp){
					$fullPull[] = array(	'nom_tireur'=>$nom_tireur,
											'vehicule'=>$vehicule,
											'distance'=>$highest,
									   		'non-membre' => true,
									   		'ID' => 0);
				}
				else{
					$grille[] = array(	'nom_tireur'=>$nom_tireur,
										'vehicule'=>$vehicule,
										'distance'=>$highest,
									   	'non-membre' => true,
									 	'ID' => 0);
				}
				
			}
			
			
			
			$fullPull = array_orderby($fullPull, 'distance', SORT_DESC);
			$grille = array_orderby($grille, 'distance', SORT_DESC);

			$classement .= '<table><thead><tr><th>'.__('Rang','asttq').'</th><th>'.__('Véhicule','asttq').'</th><th>'.__('Compétiteur','asttq').'</th><th>'.__('Distance','asttq').'</th><th>'.__('Points','asttq').'</th></tr></thead><tbody>';

			$itt = 0;
			$itt2 = 0;
			
			
			
			foreach($fullPull as $tireur){
				$itt2++;
				
				if($tireur['non-membre']){
					$points = '*';				
				}else{
					$itt++;
					
					$tid = $tireur['ID'];
					
					$points = 5+$bonus_position[$itt]+$bonus_inscription;	
					$pointsTable[$tid] = $points;
					
					//$sommaire[$event_id][$tid] = $points;
					
					
				}
				
				$theDist = $tireur['distance'];
				
				$leMembre = array();
				
				$leMembre['ID'] = $tireur['ID'];
				$leMembre['points'] = $points;
				
				$leMembre['theDist'] = $theDist.' (FP)';
				$leMembre['itt2'] = $itt2;
				$leMembre['vehicule'] = $tireur['vehicule'];
				$leMembre['nom_tireur'] = $tireur['nom_tireur'];
				$leMembre['non-membre'] = $tireur['non-membre'];
				
				$grille_finale[$tireur['distance']][] = $leMembre;
				
				
				//$classement .=  '<tr><td> '.$itt2.' </td><td>'.$tireur['vehicule'].'</td><td>'.$tireur['nom_tireur'].'</td><td> '.$tireur['distance'].' (FP)</td><td> '.$points.' </td></tr>';				

			}
			
			$last_distance = '';
			$last_tireur = '';
			
			$grille_arr = array();
			$i = 0;
			
			$cumul = 0;
			$cumul_i = array();
			
			foreach($grille as $tireur){
				
				$itt2++;
				$leMembre = array();
				if($tireur['non-membre']){
					$points = '*';
					$leMembre['points'] = '*';
					$leMembre['ID'] = 0;
				}else{
					$itt++;
					$tid = $tireur['ID'];
					if($tireur['distance'] == -1){
						$points = 5+$bonus_inscription;
					}elseif($tireur['distance'] == -2){
						$points = 5+$bonus_position[$itt]+$bonus_inscription;
					}else{
						$points = 5+$bonus_position[$itt]+$bonus_inscription;
					}
					
					$leMembre['ID'] = $tid;
					$leMembre['points'] = $points;
					
					$grille[$i]['points'] = $points;
					
				}
				
				if($tireur['distance'] == -1){
					$theDist = 'DNS';
				}elseif($tireur['distance'] == -2){
					$theDist = 'DQ';
				}else{
					$theDist = $tireur['distance'];
					
				}
				
	
				
				$leMembre['theDist'] = $theDist;
				$leMembre['itt2'] = $itt2;
				$leMembre['vehicule'] = $tireur['vehicule'];
				$leMembre['nom_tireur'] = $tireur['nom_tireur'];
				$leMembre['non-membre'] = $tireur['non-membre'];
				
				$grille_finale[$tireur['distance']][] = $leMembre;
					
				
				$i++;
			}
			
			
			$grille = array();
			foreach($grille_finale as $key=>$value){
				
				$count = count($value);
				$cumul = 0;
				$cumul_i = array();
				
				
				foreach($value as $tireur){
					$cumul += $tireur['points'];
				}

				$laPos = $value[0]['itt2'];
				if($count > 1){
					$laPos = $laPos.' (=)';
				}
				$lesPoints = $cumul/$count;

				foreach($value as $leTireur){
					$grille[] = array(	'itt2' => $laPos,
										'points' => $lesPoints,
										'theDist' => $leTireur['theDist'],
										'vehicule' => $leTireur['vehicule'],
										'nom_tireur' => $leTireur['nom_tireur'],
									 	'ID' => $leTireur['ID'],
									 	'non-membre' => $leTireur['non-membre']);
				}
				
				
				
			}
			
		
			foreach($grille as $tireur){
				
				
				$tid = $tireur['ID'];
				$theDist = $tireur['theDist'];
				$points = $tireur['points'];
				$itt2 = $tireur['itt2'];
				
				if($points == 0){
					$points = '*';
				}
				
				$classement .=  '<tr><td> '.$itt2.' </td><td>'.$tireur['vehicule'].'</td><td>'.$tireur['nom_tireur'].'</td><td> '.$theDist.' </td><td> '.$points.' </td></tr>';	
				
				if($points != '*' && $tid != 0){
					$sommaire[$event_id][$tid] = $points;
				}
				
			}
			
			$classement .= '</tbody></table><br />';
		
		
		}
		
		$classement .= '<div class="last_updated"> '.strftime('%d/%m/%y - %H:%M').'</div>';
		
		delete_transient($transient_name);
		
		set_transient( $current_points_t_name,$pointsTable,YEAR_IN_SECONDS);
		set_transient( $transient_name, $classement, YEAR_IN_SECONDS );
		set_transient( $sommaire_transient_name,$sommaire,YEAR_IN_SECONDS);
	}else{
		$classement = $current_table;
	}
	
	return $classement;
}



function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			
			return home_url().'/gestion';
			//return $redirect_to;
		} else {
			return home_url().'/gestion';
		}
	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


function translate_date_format($format) {
	if (function_exists('icl_translate'))
	  $format = icl_translate('Formats', $format, $format);
return $format;
}
add_filter('option_date_format', 'translate_date_format');


add_filter('body_class', 'append_language_class');
function append_language_class($classes){
  $classes[] = ICL_LANGUAGE_CODE;  //or however you want to name your class based on the language code
  return $classes;
}


// Add Shortcode
function edition_tireurs_shortcode() {

	$tabs = '';
	
	if(is_user_logged_in() && current_user_can('manage_options')){
		wp_enqueue_script( 'tcc-edition', get_stylesheet_directory_uri().'/includes/tcc-edition.js', array('jquery') );
		wp_localize_script( 'tcc-edition', 'adminAjax', admin_url( 'admin-ajax.php' ) );
		
		wp_enqueue_script( 'jquery-confirm', get_stylesheet_directory_uri().'/includes/jquery-confirm/js/jquery-confirm.js', array('jquery') );
		wp_enqueue_style( 'jquery-confirm-style', get_stylesheet_directory_uri().'/includes/jquery-confirm/css/jquery-confirm.css' );
		
		
		$args = array(
			'taxonomy' => 'classes',
			'hide_empty' => false
		);
		
					$terms = get_terms( $args );
					
					$tabs = '
					[tabs slidertype="left tabs"] 
						[tabcontainer]';
					foreach($terms as $term){
						$tabs .= '[tabtext]'.$term->name.'[/tabtext]';
					}
					$tabs .= '[/tabcontainer]';			
					$tabs .= '[tabcontent]'; 
					foreach($terms as $term){
						$tabs .= '[tab]
						
						<h2>'.$term->name.'</h2>
						
						';
						
						$tireurs = get_posts(array(
									  'post_type' => 'tireurs',
									  'numberposts' => -1,
									  'tax_query' => array(
										array(
										  'taxonomy' => 'classes',
										  'field' => 'id',
										  'terms' => $term->term_id, // Where term_id of Term 1 is "1".
										  'include_children' => false
										)
									  )
									));
						
						$tabs .= '<form id="form_edition_'.$term->term_id.'">';
						$tabs .= '<table data-term-id="'.$term->term_id.'" class="editable_table table_tireurs"><thead><tr><th>'.__('Véhicule','asttq').'</th><th>'.__('Nom du profil','asttq').'</th><th>'.__('Conducteurs','asttq').'</th><th>Actions</th></tr></thead><tbody>';
						
						foreach($tireurs as $tireur){
							
							$tireur_id = $tireur->ID;
							$vehicule = get_field('nom_du_vehicule', $tireur_id);
							$nom_profil = get_field('nom_du_profil', $tireur_id);
							$conducteurs = get_field('conducteur', $tireur_id);
	
							
							$tabs .=  '<tr class="tireur_line" data-Tireur-ID="'.$tireur_id.'"><td data-content="'.$vehicule.'" class="vehicule">'.$vehicule.'</td><td class="nom_profil" data-content="'.$nom_profil.'">'.$nom_profil.'</td><td class="conducteur multi_field">';
							
							if(count($conducteurs)){
								foreach($conducteurs as $conducteur){
									$tabs .= '<div data-content="'.$conducteur['nom'].'">'.$conducteur['nom'].'</div>';
								}
							}else{
								$tabs .= '<div data-content=""></div>';
							}
							
							
							$tabs .= '</td><td class="actions"><span title="Éditer" class="dashicons dashicons-welcome-write-blog edit"></span></td></tr>';
						}
						
						
						
						$tabs .= '<tr class="add_tireur_line"><td colspan="4"><span class=\'dashicons dashicons-plus-alt\'></span></td></tr></tbody></table> </form>';
						
						$tabs .= '[/tab] ';
					}
					$tabs .= '[/tabcontent]
					[/tabs]'; 
					
					$scripts = '<script>
					
					
					
					</script>';
					
				}
	
	return do_shortcode($tabs).$scripts;

}
add_shortcode( 'edition_tireurs', 'edition_tireurs_shortcode' );


// Add Shortcode
function edition_competitions_shortcode() {

	$distValues = array('Normal','FP','DNS','DQ',"BR");
	
	
	$tabs = '';
	
	if(is_user_logged_in() && current_user_can('manage_options')){
		wp_enqueue_script( 'tcc-edition', get_stylesheet_directory_uri().'/includes/tcc-edition.js', array('jquery') );
		wp_localize_script( 'tcc-edition', 'adminAjax', admin_url( 'admin-ajax.php' ) );
		
		wp_enqueue_script( 'jquery-confirm', get_stylesheet_directory_uri().'/includes/jquery-confirm/js/jquery-confirm.js', array('jquery') );
		wp_enqueue_style( 'jquery-confirm-style', get_stylesheet_directory_uri().'/includes/jquery-confirm/css/jquery-confirm.css' );
		
		
		$theYear = $_REQUEST['annee'];
	
	if(empty($theYear)){
	/* Si l'année n'a pas été spécifiée, on prend l'année en cours */
	$theYear = date('Y');
	}
	
	/* On ramasse tout les événement de l'année voulue */
	
	$events = tribe_get_events( array(
    'eventDisplay' => 'custom',
    'start_date'   => $theYear.'-01-01 00:01',
    'end_date'     => $theYear.'-12-31 23:59',
	'posts_per_page' => '99999'
	) );
	
	$termine = 0;
			
			if(is_user_logged_in() && current_user_can('manage_options')){
				
				$terms = get_the_terms( get_the_ID(), 'classes' );
				
				$tabs = '
				[tabs slidertype="left tabs"] 
					[tabcontainer]';
				foreach($events as $event){
					$tabs .= '[tabtext]'.$event->post_title.'[/tabtext]';
				}
				$tabs .= '[/tabcontainer]';			
				$tabs .= '[tabcontent]'; 
				
				
				
				foreach($events as $event){
					$tabs .= '[tab]';
					
					$tabs .= '<h3>'.tribe_get_venue($event->ID).'</h3>';
					
					$tabs .= '[et_pb_accordion admin_label="Accordion" use_border_color="off" border_color="#ffffff" border_style="solid"]';
					$classes = get_the_terms( $event->ID, 'classes' );
					
					$termine = get_field('termine',$event->ID);
					$competitions = get_field('competition',$event->ID);
						$compArr = array();
						
						foreach($competitions as $competition){
							$compArr[$competition['classe']] = $competition;
						}
					
					
					foreach($classes as $classe){
						$objID = $classe->term_id;										
						
						$tabs .= '[et_pb_accordion_item title="'.$classe->name.'"]';
						
						$tabs .= '<form id="form_edition_'.$classe->term_id.'">';
						$tabs .= '<a class="sButton" data-icon="">Sauvegarder</a>';
						if(!empty($compArr[$objID])){
							$bonus = $compArr[$objID]['bonus_inscription'];
						}else{
							$bonus = 0;
						}
						
						
						
						$tabs .= '<div class="bonus"><strong>Bonus de points</strong><input type="number" class="bonus_points" value="'.$bonus.'" /></div>';
							$tabs .= '<table data-event-id="'.$event->ID.'" data-term-id="'.$classe->term_id.'" class="editable_table comp_table"><thead><tr><th><span title="Mélanger le tableau" class="dashicons dashicons-randomize"></span></th><th>'.__('Véhicule','asttq').'</th><th>'.__('Conducteur','asttq').'</th><th>'.__('Distances','asttq').'</th><th>'.__('Membre','asttq').'</th><th>Actions</th></tr></thead><tbody>';
						
						if(!empty($compArr[$objID])){
							
							/* La compétition existe déjà! */
							
							$allTireurs = $compArr[$objID]['competiteur'];
							$allNonMembres = $compArr[$objID]['non-membre'];
							
							
							
							$ordered_table = array();
							
							$itt = 1;
							foreach($allTireurs as $current_tireur){
								if(!empty($current_tireur['rang'])){
									$pos = (int)$current_tireur['rang'];
								}else{
									$pos = $itt;
								}
								
								$ordered_table[$pos] = $current_tireur;
								$itt++;
							}
							
							foreach($allNonMembres as $current_tireur){
								
								if(!empty($current_tireur['rang'])){
									$pos = (int)$current_tireur['rang'];
								}else{
									$pos = $itt;
								}

								$ordered_table[$pos] = $current_tireur;
								$itt++;
							}
							
							sort($ordered_table);
							
							//$tabs .= ' <!-- $ordered_table: '.print_r($ordered_table,true).' --> ';
							
							$itt = 0;
							foreach($ordered_table as $tireur){
								
								$itt++;
								
								$tireurOBJ = $tireur['tireur'];
								$nom_du_tireur = $tireur['nom_du_tireur'];
								$distances = $tireur['distances'];
								
								if(empty($tireurOBJ)){
									// C'est un non-membre!
									$vehicule = $tireur['vehicule'];
									
									$tabs .= '<tr class="tireur_line" data-tireur-id="0"><td class="pos"></td>';
									
									/* Véhicule */
									$tabs .= '<td data-content="" class="vehicule"><select class="tireur" data-selection="" style="display: none;"><option value="">Choix du véhicule</option></select><input type="text" class="tireur" value="'.$tireur['vehicule'].'"></td>';
									
									/* Conducteur */
									$tabs .= '<td class="conducteur multi_field"><select style="display:none;" class="conducteurs_select"></select><input value="'.$nom_du_tireur.'" type="text" class="conducteur"><span class="dashicons dashicons-plus-alt addConducteur"></span></td>';
									
									/* Distances */
									$tabs .= '<td class="distances multi_field"><div class="mfield_container mfieldClone"><select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0"><option value="Normal" class="">Normal</option><option value="FP">FP</option><option value="DNS">DNS</option><option value="DQ">DQ</option><option value="BR">BR</option></select><input type="number" id="" class="" name="" value="" min="" max="" step="any" placeholder=""></div>';
									foreach($distances as $current_distance){
										$tabs .= '<div class="mfield_container"><select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0">';
										foreach($distValues as $theValue){
											if($current_distance['statut'] == $theValue){
												$selection = ' selected="selected"';
											}else{
												$selection = '';
											}
											
											$tabs .= '<option value="'.$theValue.'"'.$selection.'>'.$theValue.'</option>';
										}
										$tabs .= '</select><input type="number" id="" class="" name="" value="'.$current_distance['distance'].'" min="" max="" step="any" placeholder=""></div>';
									}					
									$tabs .= '</td>';
									
										
									$tabs .= '<td class="membre"><label class="switch"><input type="checkbox" value="1" class="" autocomplete="off"><div class="slider round"></div></label></td><td class="actions"></td></tr>';
									
								}else{
									// C'est un membre
									
									
									$vehicule = $tireur['vehicule'];
									
									$tabs .= '<tr class="tireur_line" data-tireur-id="'.$tireurOBJ->ID.'"><td class="pos"></td>';
									
									/* Véhicule */
									$tabs .= '<td data-content="" class="vehicule">'.get_tireurs_select($classe->term_id,$tireurOBJ ->ID).'</td>';
									
									/* Conducteur */
									$tabs .= '<td class="conducteur multi_field">';
									
									$conducteurs = get_field('conducteur', $tireurOBJ->ID);
									if(count($conducteurs)){
										$tabs .= '<select class="conducteurs_select">';
										foreach($conducteurs as $conducteur){
											if($conducteur == $nom_du_tireur){
												$selection = ' selected="selected"';
											}else{
												$selection = '';
											}
											
											$tabs .= '<option'.$selection.' data-content="'.$conducteur['nom'].'">'.$conducteur['nom'].'</option>';
										}
											$tabs .= '</select><span class="dashicons dashicons-plus-alt addConducteur"></span>';
									}
									
									$tabs .='</td>';
									
									/* Distances */
									$tabs .= '<td class="distances multi_field"><div class="mfield_container mfieldClone"><select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0"><option value="Normal" class="">Normal</option><option value="FP">FP</option><option value="DNS">DNS</option><option value="DQ">DQ</option><option value="BR">BR</option></select><input type="number" id="" class="" name="" value="" min="" max="" step="any" placeholder=""></div>';
									foreach($distances as $current_distance){
										$tabs .= '<div class="mfield_container"><select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0">';
										foreach($distValues as $theValue){
											if($current_distance['statut'] == $theValue){
												$selection = ' selected="selected"';
											}else{
												$selection = '';
											}
											
											$tabs .= '<option value="'.$theValue.'"'.$selection.'>'.$theValue.'</option>';
										}
										$tabs .= '</select><input type="number" id="" class="" name="" value="'.$current_distance['distance'].'" min="" max="" step="any" placeholder=""></div>';
									}					
									$tabs .= '</td>';
										
									$tabs .= '<td class="membre"><label class="switch"><input type="checkbox" checked="checked" value="1" class="" autocomplete="off"><div class="slider round"></div></label></td><td class="actions"></td></tr>';
								}
								

							}
							
						}else{
							
							// Si la compétition n'existe pas, on peuple le tableau avec tous les tireurs de la classe.
							
							$tireurs = get_posts(array(	'post_type' => 'tireurs',
									'numberposts' => -1,
									'tax_query' => array(
									array(
										  'taxonomy' => 'classes',
										  'field' => 'id',
										  'terms' => $objID, // Where term_id of Term 1 is "1".
										  'include_children' => false
										)
									  )
									));
							
							
							
							
							$itt = 0;
							
							foreach($tireurs as $tireur){
								$itt++;
								$tireur_id = $tireur->ID;
								$vehicule = get_field('nom_du_vehicule', $tireur_id);
								$nom_profil = get_field('nom_du_profil', $tireur_id);
								$conducteurs = get_field('conducteur', $tireur_id);


								$tabs .=  '<tr class="tireur_line" data-Tireur-ID="'.$tireur_id.'"><td class="pos"></td><td data-content="'.$vehicule.'" class="vehicule">';
								
								$tabs .= get_tireurs_select($classe->term_id,$tireur_id);
									
								$tabs .= '</td><td class="conducteur multi_field">';

								if(count($conducteurs)){
										$tabs .= '<select class="conducteurs_select">';
									foreach($conducteurs as $conducteur){
										$tabs .= '<option data-content="'.$conducteur['nom'].'">'.$conducteur['nom'].'</option>';
									}
										$tabs .= '</select><span class="dashicons dashicons-plus-alt addConducteur"></span>';
								}else{
									$tabs .= '<div data-content=""></div>';
								}
								
								$tabs .= '</td><td class="distances multi_field">
								<div class="mfield_container mfieldClone">
								<select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0">
								<option value="Normal" class="">Normal</option>
								<option value="FP">FP</option>
								<option value="DNS">DNS</option>
								<option value="DQ">DQ</option>
								<option value="BR">BR</option>
								</select>
								<input type="number" id="" class="" name="" value="" min="" max="" step="any" placeholder="">
								</div>
								<div class="mfield_container">
								<select id="" class="distance_type" name="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Choisir" data-allow_null="0">
								<option value="Normal" class="">Normal</option>
								<option value="FP">FP</option>
								<option value="DNS">DNS</option>
								<option value="DQ">DQ</option>
								<option value="BR">BR</option>
								</select>
								<input type="number" id="" class="" name="" value="" min="" max="" step="any" placeholder="">
								</div>';


								$tabs .= '</td><td class="membre">
								<label class="switch">
								<input type="checkbox" value="1"  checked="checked" class="" autocomplete="off">
								<div class="slider round"></div>
								</label>
								</td><td class="actions"><!-- <span title="Éditer" class="dashicons dashicons-welcome-write-blog edit"></span> --></td></tr>';
							}



							
							
							$tireurArray = array();
							$tireursNoms = array();

							foreach($tireurs as $tireur){
								$profil = get_field('nom_du_profil',$tireur->ID);
								if(empty($profil)){
									$profil = $tireur->post_title;
								}
								$tireursNoms[] = $profil;
								$tireurArray[] = $tireur->ID;
							}
							
							
							
						}
						$tabs .= '</tbody><tfoot><tr class="add_tireur_line"><td colspan="5"><span class=\'dashicons dashicons-plus-alt\'></span></td></tr></tfoot></table>';
						
						$tabs .= '<a class="sButton" data-icon="">Sauvegarder</a>';
						$tabs .= '</form>';
						
						
						
						$tabs .= '[/et_pb_accordion_item]';
					}
					
					$tabs .= '[/et_pb_accordion]';
					
					
					if($termine == 1){
						$checked = ' checked="checked"';
					}
					else{
						$checked = '';
					}
					$tabs .= '<div class="termine"><label class="switch"><input data-event="'.$event->ID.'" class="event_termine" type="checkbox"'.$checked.' value="1" class="" autocomplete="off"><div class="slider round"></div></label></div>';
					/*
					$tireurs = get_posts(array(
								  'post_type' => 'tireurs',
								  'numberposts' => -1,
								  'tax_query' => array(
									array(
									  'taxonomy' => 'classes',
									  'field' => 'id',
									  'terms' => $term->term_id, // Where term_id of Term 1 is "1".
									  'include_children' => false
									)
								  )
								));
					$tabs .= '<table><thead><tr><th>'.__('Véhicule','asttq').'</th><th>'.__('Nom du profil','asttq').'</th><th>'.__('Conducteurs','asttq').'</th></tr></thead><tbody>';
					
					foreach($tireurs as $tireur){
						
						$tireur_id = $tireur->ID;
						$vehicule = get_field('nom_du_vehicule', $tireur_id);
						$nom_profil = get_field('nom_du_profil', $tireur_id);
						$conducteurs = get_field('conducteur', $tireur_id);

						
						$tabs .=  '<tr><td>'.$vehicule.'</td><td>'.$nom_profil.'</td><td>';
						'</td><td>';
						
						foreach($conducteurs as $conducteur){
							$tabs .= '<div>'.$conducteur['nom'].'</div>';
						}
						
						$tabs .= '</td></tr>';
					}
					
					
					
					$tabs .= '</tbody></table>';
					*/
					$tabs .= '[/tab] ';
				}
				$tabs .= '[/tabcontent]
				[/tabs]'; 
				
			
	
	return do_shortcode($tabs).$scripts;
			}
}
}
add_shortcode( 'edition_competitions', 'edition_competitions_shortcode' );

function get_tireurs_select($classeID = 0,$selection = ''){
	
	$transient_name = 'tireurs_options_'.$classeID;
	//delete_transient($transient_name);
	$options_array = get_transient($transient_name);
	//$options_array = '';
	
	$retVal = '<select class="tireur" data-selection="'.$selection.'"><option value="">Choix du véhicule</option>';
	
	if(empty($options_array)){
		
		$tireurs = get_posts(array(	'post_type' => 'tireurs',
									'numberposts' => -1,
									'tax_query' => array(
									array(
										  'taxonomy' => 'classes',
										  'field' => 'id',
										  'terms' => $classeID, // Where term_id of Term 1 is "1".
										  'include_children' => false
										)
									  )
									));
		foreach($tireurs as $tireur){
			$vehicule = get_field('nom_du_vehicule',$tireur->ID);
			$theSelection = '';
			if($selection == $tireur->ID){
				$theSelection = ' selected="selected"';
			}
			$line = '<option'.$theSelection.' value="'.$tireur->ID.'">'.$vehicule.'</option>';
			$options_array .= str_replace($theSelection,'',$line);
			$retVal .= $line;
		}
		set_transient($transient_name, $options_array);
	}else{

		$retVal .= $options_array;
	}
	
	

	$retVal .= '</select>';
	
	return $retVal;
} 

function ajax_get_tireurs_select(){
	
	$objID = $_POST['objID'];
	$selection = '';
	
	$retVal = get_tireurs_select($objID,$selection);
	
	echo json_encode(array('message'=>$retVal));
	wp_die();
}
add_action( 'wp_ajax_ajax_get_tireurs_select', 'ajax_get_tireurs_select' );
add_action( 'wp_ajax_nopriv_ajax_get_tireurs_select', 'ajax_get_tireurs_select' );


function ajax_event_termine(){
	$eventID = $_POST['eventID'];
	$theValue = $_POST['theValue'];
	
	
	$retVal = update_field('termine', $theValue, $eventID);
	
	echo json_encode(array('message'=>$retVal));
	wp_die();
}
add_action( 'wp_ajax_ajax_event_termine', 'ajax_event_termine' );
add_action( 'wp_ajax_nopriv_ajax_event_termine', 'ajax_event_termine' );


function update_competition_results(){
	global $wpdb;
	
	$eventID = $_POST['eventID'];
	
	$transient_name = 'asttq_p_table_'.$eventID;
	delete_transient($transient_name);
	
	$classeID = $_POST['classeID'];
	$dataMembres = json_decode(stripslashes($_POST['dataMembres']),true);
	$dataNonMembres = json_decode(stripslashes($_POST['dataNonMembres']),true);
	$bonus = $_POST['bonus'];
	
	$currentClasses = array();
	
	$allCompetitions = get_field('competition', $eventID);
	
	$classFound = false;
	
	
	/* Setting values from ajast Post */
	
	$lineData = array();
	
	$lineData['classe'] = $classeID;
	$lineData['classe_id'] = $classeID;
	$lineData['bonus_inscription'] = $bonus;
	
	$rowIndex = -1;
	
	$lineData['competiteur'] = array();
	/* Infos sur les membres */
	foreach($dataMembres as $membre){
		$membreOBJ = get_post($membre['ID']);
		$distancesMembres = array();

		$i = 0;
		$distancesTypes = $membre['distancesTypes'];
		
		foreach($membre['distances'] as $distance){
			
			$distancesMembres[] = array('statut'=>$distancesTypes[$i],
										'distance'=>$distance);
		}

		$lineData['competiteur'][] = array(	'rang' => $membre['pos'],
											'tireur' => $membreOBJ,
											'nom_du_tireur' => $membre['conducteur'],
											'distances' => $distancesMembres);
	}	

	$lineData['non-membre'] = array();
	/* Infos sur les non-membres */
	foreach($dataNonMembres as $nonMembre){

		$distancesNonMembres = array();
		$distancesTypes = $nonMembre['distancesTypes'];
		$i = 0;
		foreach($nonMembre['distances'] as $distance){
			$distancesNonMembres[] = array(	'statut'=>$distancesTypes[$i],
											'distance'=>$distance);
		}

		$lineData['non-membre'][] = array(	'rang' => $nonMembre['pos'],
											'vehicule' => $nonMembre['vehicule'],
											'nom_du_tireur' => $nonMembre['conducteur'],
											'distances' => $distancesNonMembres);
	}


	
	
	$ri = 0;
	foreach($allCompetitions as $key => $competition){
	$ri++;	
		if($competition['classe'] == $classeID){
			$classFound = true;
			$rowIndex = $ri;
			$updatedComp = $competition;
			
			$updatedComp['competiteur'] = $lineData['comptetiteur'];
			$updatedComp['non-membre'] = $lineData['non-membre'];
			
			$allCompetitions[$key] = $updatedComp;
			delete_row( 'competition', $ri, $eventID);
			$classFound = false;
			
		} 
	}
	
	if($classFound){

		update_field('competition', $allCompetitions, $eventID);
	}else{
		add_row('competition', $lineData, $eventID);
	}
	
	
	echo json_encode(array('message'=>'Les changement ont été sauvegardés','eventID'=>$eventID));
	wp_die();
	
}
add_action( 'wp_ajax_update_competition_results', 'update_competition_results' );
add_action( 'wp_ajax_nopriv_update_competition_results', 'update_competition_results' );


function update_post_fields() {
	global $wpdb;
	
	$objID = $_POST['objID'];
	$term_id = $_POST['term_id'];
	$allData = json_decode(stripslashes($_POST['data']),true);
	
	$transient_name = 'tireurs_options_'.$term_id;
	delete_transient($transient_name);
	
	$worker = '';
	
	if($objID == 0){
		$my_post = array(
		'post_title'	=> $allData['nom_du_profil'],
		'post_type'		=> 'tireurs',
		'post_status'	=> 'publish'
	);


	// insert the post into the database
		$objID = wp_insert_post( $my_post );
		wp_set_post_terms( $objID, $term_id, 'classes', false );
		update_field( 'classe', $term_id, $objID );
	}	// vars

	
	
	if($objID != 0){
		foreach($allData as $key => $value){
			if(!is_array($value)){
				update_field($key, $value, $objID);
				$worker .= '<br>
'.'Updating field: '.$key.' to: '.$value.' in post: '.$objID;
			}else{
				$worker .= '<br>
'.'Updating field: '.$key.' to: '.print_r($value,true).' in post: '.$objID;
				$field_value = array();
				foreach($value as $subkey=>$subvalue){
					if(is_array($subvalue)){
						foreach($subvalue as $val){
							$cleanValue = str_replace(array('"','[',']'),array('','',''),$val);
							
							if(!empty($cleanValue)){
								$field_value[] = array($subkey => $cleanValue);
							}
							
						}
					}
					
				}
				update_field( $key, $field_value, $objID );
			}
		}
		
	}
	/**/
	
	//echo $worker;
	
	echo json_encode(array('message'=>'Les changement ont été sauvegardés','objID'=>$objID));
	
	
	
	wp_die();
}
add_action( 'wp_ajax_update_post_fields', 'update_post_fields' );
add_action( 'wp_ajax_nopriv_update_post_fields', 'update_post_fields' );

function ajax_delete_post(){
	$objID = $_POST['objID'];
	
	if(!empty($objID)){
		$retVal = wp_delete_post($objID);
	}
	//var_dump($allData);
	//echo foreignDbAction();
	
	if(!$retVal){
		echo json_encode(array('message'=>'Il y a eu une erreur...','objID'=>$objID));
		
	}else{
		echo json_encode(array('message'=>$retVal->post_title.' a été supprimé avec succès.','objID'=>$objID));
	}
	
	wp_die();
}
add_action( 'wp_ajax_ajax_delete_post', 'ajax_delete_post' );
add_action( 'wp_ajax_nopriv_ajax_delete_post', 'ajax_delete_post' );

function ajax_get_conducteurs(){
	$objID = $_POST['objID'];
	
	$conducteurs = get_field('conducteur', $objID);
	
	$retVal = '<select class="conducteurs_select">';
	
	foreach($conducteurs as $conducteur){
		$retVal .= '<option value="'.$conducteur['nom'].'">'.$conducteur['nom'].'</option>';
	}
	
	$retVal .= '</select>';
	
	//var_dump($allData);
	//echo foreignDbAction();
	

	echo json_encode(array('message'=>$retVal,'objID'=>$objID));
	
	
	wp_die();
}
add_action( 'wp_ajax_ajax_get_conducteurs', 'ajax_get_conducteurs' );
add_action( 'wp_ajax_nopriv_ajax_get_conducteurs', 'ajax_get_conducteurs' );


function ajax_load_tireurs_from_class(){
	$objID = $_POST['objID'];
	
	$tireurs = get_posts(array(
									  'post_type' => 'tireurs',
									  'numberposts' => -1,
									  'tax_query' => array(
										array(
										  'taxonomy' => 'classes',
										  'field' => 'id',
										  'terms' => $objID, // Where term_id of Term 1 is "1".
										  'include_children' => false
										)
									  )
									));
	$tireurArray = array();
	$tireursNoms = array();
	
	foreach($tireurs as $tireur){
		$profil = get_field('nom_du_profil',$tireur->ID);
		if(empty($profil)){
			$profil = $tireur->post_title;
		}
		$tireursNoms[] = $profil;
		$tireurArray[] = $tireur->ID;
	}

	echo json_encode(array('tireurs'=>$tireurArray,'noms'=>$tireursNoms));
	
	wp_die();
}
add_action( 'wp_ajax_ajax_load_tireurs_from_class', 'ajax_load_tireurs_from_class' );
add_action( 'wp_ajax_nopriv_ajax_load_tireurs_from_class', 'ajax_load_tireurs_from_class' );


//include_once('includes/dynamic-repeater-on-category.php');

?>