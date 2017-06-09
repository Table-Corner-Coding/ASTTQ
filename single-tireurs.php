<?php

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
				if(isset($_POST['acf'])){
					//unset($_POST);
					echo '<!-- Post just saved  -->';
					echo do_shortcode('[edition_competiteurs]');
				}else{
					echo '<!-- No post data! -->';
					echo do_shortcode('[edition_competiteurs id="'.$post->ID.'"]');
				}

				
				the_content();
				
			}else{
				the_content();
			}
			
			
		?>
		</div> <!-- .entry-content -->

	</article> <!-- .et_pb_post -->

<?php endwhile; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>