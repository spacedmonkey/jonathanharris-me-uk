<?php

add_filter('img_caption_shortcode', 'orangegnome_img_caption_shortcode_filter',10,3);

/**
* Removes the extra 10px of width from wp-caption and changes to HTML5 figure/figcaption
*
**/
function orangegnome_img_caption_shortcode_filter($val, $attr, $content = null) {
  extract(shortcode_atts(array(
  	'id'	=> '',
  	'align'	=> '',
  	'width'	=> '',
  	'caption' => ''
  ), $attr));

  if ( 1 > (int) $width || empty($caption) )
  	return $val;

  return '<figure id="' . $id . '" class="wp-caption thumbnail ' . esc_attr($align) . '" >'
  . do_shortcode( $content ) . '<figcaption class="wp-caption-text caption">' . $caption . '</figcaption></figure>';
}