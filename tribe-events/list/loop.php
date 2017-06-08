<?php
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @version 4.4
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<?php
global $post;
global $more;
$more = false;
?>

<div class="tribe-events-loop">
<table class="liste-evenements">
<thead>
	<th style="min-width: 105px;"><?php _e('Date','asttq'); ?></th>
	<th><?php _e('Heure','asttq'); ?></th>
	<th style="min-width: 225px;"><?php _e('Endroit','asttq'); ?></th>
	<th><?php _e('Classes','asttq'); ?></th>
	<th><?php _e('Évènement','asttq'); ?></th>
</thead>
<tbody>
	<?php 
	
	$today = date('Y-m-d');
	$thisYear = date('Y');
	$thisMonth = date('m');
	
	if($thisMonth >= 10){
		$theYear = $thisYear+1;
	}else{
		$theYear = $thisYear;
	}
	
	$posts = tribe_get_events ( array(
										'start_date' => $theYear.'-06-01',
										'end_date' => $theYear.'-09-30',
										'posts_per_page' => 999
								),false );
	//var_dump($posts);
	foreach ( $posts as $post ){ ?>
		<?php do_action( 'tribe_events_inside_before_loop' ); ?>

		<!-- Month / Year Headers -->
		<?php //tribe_events_list_the_date_headers(); ?>

		<!-- Event  -->
		<?php
		$post_parent = '';
		if ( $post->post_parent ) {
			$post_parent = ' data-parent-post-id="' . absint( $post->post_parent ) . '"';
		}
		?>
		<tr id="post-<?php the_ID() ?>" class="<?php //tribe_events_event_classes() ?>" <?php echo $post_parent; ?>>
			<?php
			$event_type = tribe( 'tec.featured_events' )->is_featured( $post->ID ) ? 'featured' : 'event';
			
			$start_date = get_post_meta($post->ID,'_EventStartDate',true);
			
			$when = explode(' ',$start_date);
			
			$fmt = datefmt_create(
						'fr_FR',
						IntlDateFormatter::FULL,
						IntlDateFormatter::FULL,
						'America/Toronto',
						IntlDateFormatter::GREGORIAN,
						'd MMMM'
					);
			
		


			$date = $fmt->format(strtotime($when[0]));
			$heure = substr($when[1],0,5);
			
			//$date = $start_date;
			//$heure = $start_date;
			
			$venue_details = tribe_get_venue_details($post->ID);
			
			$url = get_post_meta($post->ID,'_EventURL',true);
			$EventVenueID = get_post_meta($post->ID,'_EventVenueID',true);
			$terms = get_the_terms( $post->ID, 'classes' );
			$classes = '';
			if(!empty($terms)){
				foreach($terms as $term){
					$classes .= $term->name.', ';
				}
			}
			
			$classes = trim($classes,', ');
			$nom = $post->post_title;
			
			
			//echo '<pre>';
			//var_dump(get_post_meta($post->ID));
			//echo '</pre>';
			
			
			/**
			 * Filters the event type used when selecting a template to render
			 *
			 * @param $event_type
			 */
			$event_type = apply_filters( 'tribe_events_list_view_event_type', $event_type );

			//tribe_get_template_part( 'list/single', $event_type );
			?>
			<td><?php echo $date; ?></td>
			<td><?php echo $heure; ?></td>
			<td><?php echo $venue_details['linked_name']; ?><?php
				if ( tribe_get_map_link() ) {
					echo '<p>'.tribe_get_map_link_html().'</p>';
				}
				?></td>
			<td><?php echo $classes.'<p style="font-style:italic;">'.$post->post_content.'</p>'; ?></td>
			<td><a href="<?php echo $url; ?>"><?php echo $nom; ?></a></td>
		</tr>


		<?php do_action( 'tribe_events_inside_after_loop' ); ?>
	<?php } ?>
	</tbody>
</table>
</div><!-- .tribe-events-loop -->
