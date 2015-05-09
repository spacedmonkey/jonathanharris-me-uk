<?php
function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';
	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}
add_filter('body_class','browser_body_class');

function tax_add_post_classes($classes) {
	global $post;
		
	$taxs = get_post_taxonomies($post->ID);
	 foreach ($taxs as $tax){
		$terms = get_the_terms( $post->ID, $tax);
		if(!empty($terms)){
			foreach ( $terms as $term ) {
				$classes [] = $tax . "-".$term->slug;
			
			}
		}
	 }

	return $classes;
}
add_filter('post_class', 'tax_add_post_classes');