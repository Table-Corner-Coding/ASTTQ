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
	wp_localize_script( 'tcc-scripts', 'ajax_object',
    array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
	

} 

add_filter('body_class', 'multisite_body_classes');

function multisite_body_classes($classes) {

        if(isset($_GET['in_iframe'])){
			$classes[] = 'iframe';
			
		}
	
	if(is_user_logged_in() || is_page(3214)){
		$classes[] = 'et_header_style_fullscreen';
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
	  }
	  else{
		  $nomProfil = get_field('field_59348bdad3bcc', $post_id);
	  }
	  
	  if(empty($nomProfil)){
		  $post_obj = get_post($post_id);
		  $nomProfil = ucwords(str_replace('-',' ',$post_obj->post_name));
	  }
	
	$classes = wp_get_post_terms( $post_id, $taxonomy = 'classes' );
	  
	if(!empty($classes[0])){
		$classe = $classes[0];
		$title = '['.$classe->name.'] - '.$nomProfil;
	}else{
		  $title = '[] - '.$nomProfil;
	  }

    $data['post_title'] =  $title ; //Updates the post title to your new title.
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
				$firstUrl = get_permalink($event->ID).'?in_iframe=1'; 
		}
		$retVal .= '<option value="'.get_permalink($event->ID).'?in_iframe=1">'.$event->post_title.'</option>';
		$itt++;
	}
	
	$retVal .= '</select></div>';
	
	$firstUrl = '';
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
	
	$firstUrl = '';
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
	
	$done = array();
	$transient_name = 'asttq_p_table_'.$event_id;
	$current_table = get_transient($transient_name);
	
	
	
	$competitions = get_field('field_592da5f526f1e', $event_id);
			
	$bonus_position = array(0,15,12,10,9,8,7,6,5,4,3,2,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); 
	$bonus_inscription = get_field('field_592e44df6e8c7', $event_id);	

//print_r($bonus_inscription);

	//$classement = '';		

	$classement = '';
	
	if(empty($current_table) || $refresh == true)
	{
	
		foreach($competitions as $competition){
			$term = get_term( $competition['classe'], 'classes' );
			$classement .= '<h3>'.$term->name.'</h3>';

			$grille = array();

			foreach($competition['competiteur'] as $competiteur){
				$tireur_id = $competiteur['tireur'];
				$distance = $competiteur['distance'];
				$tireur = get_post($tireur_id);
				$vehicule = get_field('nom_du_vehicule', $tireur_id);
				$nom_tireur = $tireur->post_title;

				$grille[] = array(	'nom_tireur'=>$nom_tireur,
									'vehicule'=>$vehicule,
									'distance'=>$distance);
			}

			foreach($competition['non-membre'] as $nonmembre){
				$distance = $nonmembre['distance'];
				$vehicule = $nonmembre['vehicule'];
				$nom_tireur = $nonmembre['nom_du_tireur'];

				$grille[] = array(	'nom_tireur'=>$nom_tireur,
									'vehicule'=>$vehicule,
									'distance'=>$distance);
			}

			$grille = array_orderby($grille, 'distance', SORT_DESC);

			$classement .= '<table><thead><tr><th>'.__('Rang','asttq').'</th><th>'.__('Véhicule','asttq').'</th><th>'.__('Compétiteur','asttq').'</th><th>'.__('Distance','asttq').'</th><th>'.__('Points','asttq').'</th></tr></thead><tbody>';

			$itt = 0;
			foreach($grille as $tireur){
				$itt++;
				$points = 5+$bonus_position[$itt]+$bonus_inscription;
				$classement .=  '<tr><td> '.$itt.' </td><td>'.$tireur['vehicule'].'</td><td>'.$tireur['nom_tireur'].'</td><td> '.$tireur['distance'].' </td><td> '.$points.' </td></tr>';
			}
		
			
			$classement .= '</tbody></table><br /><div class="last_updated">'.strftime('%d/%m/%y - %H:%M').'</div>';
		
		
		}
		
		delete_transient($transient_name);
		set_transient( $transient_name, $classement, YEAR_IN_SECONDS );
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



?>