<?php
/**
* @package   The_Grid
* @author    Themeone <themeone.master@gmail.com>
* @copyright 2015 Themeone
*
* Skin Name: TCC3
* Skin Slug: tg-tcc3
* Date: 05/16/17 - 09:48:03PM
*
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

// Init The Grid Elements instance
$tg_el = The_Grid_Elements();

$the_post = get_post($tg_el->get_item_ID());
$post_type = $the_post->post_type;
$excerpt = $the_post->post_excerpt;


$post_categories = wp_get_post_categories( $the_post->ID );
$post_link = get_permalink($the_post->ID);
$cats = array();



foreach($post_categories as $c){
    $cat = get_category( $c );
    $cats[] = 'category-'.$cat->slug;
}

$the_class = implode(' ',$cats);

// Prepare main data for futur conditions
$image  = $tg_el->get_attachment_url();
$format = $tg_el->get_item_format();

$output = null;

$media = $tg_el->get_media();

// if there is a media
if ($media) {

	// Media wrapper start
	$output .= $tg_el->get_media_wrapper_start();
$output .= '<div class="'.$post_type.' '.$the_class.'">';
	// Media content (image, gallery, audio, video)
	$output .= $media;

	// if there is an image
	if ($image || in_array($format, array('gallery', 'video'))) {

		// Media content holder start
		$output .= $tg_el->get_media_content_start();



		
		// Absolute element(s) in Media wrapper
		$output .= $tg_el->get_html_element(array('html' => '<div class=&quot;tg-button-overlay&quot; style=&quot;background-color:#color:overlay-background#&quot;></div>
'), 'tg-element-10');
		
		$output .= '<a class="tcc-media-button tg-media-button" href="'.$post_link.'"></a>';
		
		//$output .= $tg_el->get_media_button(array('icons' => array('image' => '<i class="tg-icon-arrows-out"></i>', 'audio' => '<i class="tg-icon-play"></i>', 'video' => '<i class="tg-icon-play"></i>', 'pause' => ''), 'action' => array('type' => 'lightbox')), 'tg-element-9');
		
		
		
		// Media content holder end
		$output .= $tg_el->get_media_content_end();
		

	}
$output .= '</div>';
	$output .= $tg_el->get_media_wrapper_end();
	// Media wrapper end

}

// Bottom content wrapper start
$output .= $tg_el->get_content_wrapper_start('', 'bottom');
	$output .= $tg_el->get_the_date(array('format' => 'j F, Y'), 'tg-element-3');
	$output .= $tg_el->get_the_title(array('link' => false, 'action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'post_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-4');
	$output .= $tg_el->get_content_clear().'<div class="excerpt">'.$excerpt.'</div>';

	

$output .= $tg_el->get_content_wrapper_end();
// Bottom content wrapper end

return $output;


/*

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$the_post = get_post($tg_el->get_item_ID());
$post_type = $the_post->post_type;

$format = $tg_el->get_item_format();
$colors = $tg_el->get_colors();
$image  = $tg_el->get_attachment_url();

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$media_args = array(
	'icons' => array(
		'image' => '<i class="tg-icon-add"></i>'
	)
);

if ($format == 'quote' || $format == 'link') {
	
	$output  = ($image) ? '<div class="tg-item-image" style="background-image: url('.esc_url($image).')"></div>' : null;
	$output .= $tg_el->get_content_wrapper_start();
		$output .= $tg_el->get_the_date();
		$output .= ($format == 'quote') ? $tg_el->get_the_quote_format() : $tg_el->get_the_link_format();
		$output .= '<div class="tg-item-footer">';
			$output .= '<i class="tg-'.$format.'-icon tg-icon-'.$format.'" style="color:'.$colors['content']['title'].'"></i>';
			$output .= $tg_el->get_the_likes_number();
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();
	
	return $output;
		
} else {
	
	$output = null;
	$media_content = $tg_el->get_media();
	$social = $tg_el->get_social_share_links();
	
	$social_button  = null;
	if (!empty($social)) {
		//$social_button = '<div class="tg-item-share-holder">';
			//$social_button .= '<div class="triangle-up-left" style="border-color:'.$colors['overlay']['background'].'"></div>';
			//$social_button .= '<i class="tg-icon-reply" style="color:'.$colors['overlay']['title'].'"></i>';
			//$social_button .= '<div class="tg-share-icons">';
				foreach ($social as $url) {
					//$social_button .= $url;
				}
			//$social_button .= '</div>';
		//$social_button .= '</div>';
	}
	
	
	$decoration  = '<div class="tg-item-decoration">';
		$decoration .= '<div class="triangle-down-right" style="border-color:'.$colors['content']['background'].'"></div>';
	$decoration .= '</div>';
	
	
	if ($media_content) {
		$link_button = $tg_el->get_link_button();
		$output .= $tg_el->get_media_wrapper_start();
		$output .= '<div class="'.$post_type.'">';
			$output .= $media_content;
			if ($image || in_array($format, array('gallery', 'video'))) {
				$output .= $tg_el->get_center_wrapper_start();
				$output .= (in_array($format, array('video', 'audio'))) ? $tg_el->get_overlay().$tg_el->get_media_button($media_args) : null;
				$output .= (!in_array($format, array('video', 'audio')) && $link_button) ? $tg_el->get_overlay().$link_button : null;
				$output .= $tg_el->get_center_wrapper_end();
				$output .= $decoration;
				$output .= $social_button;
			}
		$output .= '</div>';
		$output .= $tg_el->get_media_wrapper_end();
	}
	
	$output .= $tg_el->get_content_wrapper_start();
		$output .= $tg_el->get_the_date();
		$output .= $tg_el->get_the_title();
		//$output .= $tg_el->get_the_terms($terms_args);
		$output .= $tg_el->get_the_excerpt();
		$output .= '<div class="tg-item-footer '.$post_type.'">';
			$output .= $tg_el->get_the_comments_number();
			$output .= $tg_el->get_the_likes_number();
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();

	return $output;

}
*/
