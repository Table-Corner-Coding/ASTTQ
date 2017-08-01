<?php
/*
Template Name: Édition des tireurs
*/

get_header();



if(isset($_REQUEST['acf'])){
	
	/* Si la variable existe, on ajoute le post à la liste des mises à jour à faire */
	
	$option_name = 'tireurs-to-update';
	$current_array = get_option($option_name);
	$current_array[$post->ID] = mktime();
	update_option( $option_name, $current_array );
	
	

}

?>

<div id="main-content">

<?php 
	
				
	
	while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
		<?php
			
			
			if(is_user_logged_in() && current_user_can('manage_options')){
				
				$terms = get_the_terms( get_the_ID(), 'classes' );
				
				$tabs = '
				[tabs slidertype="left tabs"] 
					[tabcontainer]';
				foreach($terms as $term){
					$tabs .= '[tabtext]'.$term->name.'[/tabtext]';
				}
				$tabs .= '[/tabcontainer]';			
				$tabs .= '[tabcontent]'; 
				foreach($terms as $term){
					$tabs .= '[tab]';
					
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
					
					
					
					$classement .= '</tbody></table>';
					
					$tabs .= '[/tab] ';
				}
				$tabs .= '[/tabcontent]
				[/tabs]'; 
				
				echo do_shortcode($tabs);
				
			}else{
				the_content();
			}
			
			
		?>
		</div> <!-- .entry-content -->

	</article> <!-- .et_pb_post -->

<?php endwhile; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>