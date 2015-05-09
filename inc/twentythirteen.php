<?php

remove_action( 'after_setup_theme', 'twentythirteen_infinite_scroll_init', 15 );

function spacedmonkey_overide(){
	remove_action( 'wp_enqueue_scripts', 'twentythirteen_scripts_styles');
	remove_action( 'wp_enqueue_scripts', 'twentythirteen_infinite_scroll_enqueue_styles', 25 );
	remove_filter( 'wp_title', 'twentythirteen_wp_title', 10, 2 );
	
}
add_action('after_setup_theme','spacedmonkey_overide');


function twentythirteen_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'twentythirteen' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date published updated" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'twentythirteen' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}

/**
 * Display navigation to next/previous post when applicable.
*
* @since Twenty Thirteen 1.0
*
* @return void
*/
function twentythirteen_post_nav() {
	global $post;
	
	if(in_array(get_post_type(),array('page','attachment')))
		return;
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text sr-only"><?php _e( 'Post navigation', 'twentythirteen' ); ?></h1>
		<ul class="nav-links pager">
			<?php if(get_previous_post_link()):?>
			<li class="nav-previous previous"><?php previous_post_link( '%link', _x( '<span class="meta-nav glyphicon glyphicon-chevron-left"></span> %title', 'Previous post link', 'twentythirteen' ) ); ?></li>		<?php endif;?>
			<?php if(get_next_post_link()):?>
			<li class="nav-next next"><?php next_post_link( '%link', _x( '%title <span class="meta-nav glyphicon glyphicon-chevron-right"></span>', 'Next post link', 'twentythirteen' ) ); ?></li>
<?php endif;?>
		</ul><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}

function twentythirteen_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text sr-only"><?php _e( 'Posts navigation', 'twentythirteen' ); ?></h1>
		<ul class="nav-links pager">

			<?php if ( get_next_posts_link() ) : ?>
			<li class="nav-previous previous"><?php next_posts_link( __( '<span class="meta-nav glyphicon glyphicon-chevron-left"></span> Older posts', 'twentythirteen' ) ); ?></li>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<li class="nav-next next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav glyphicon glyphicon-chevron-right"></span>', 'twentythirteen' ) ); ?></li>
			<?php endif; ?>

		</ul><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}


function twentythirteen_entry_meta() {
	global $post;
	echo '<ul class="list-inline text-muted">';
	

	if ( ! has_post_format( 'link' ) && 'page' != get_post_type() ){
		printf(__('<li><span class="genericon genericon-month"></span>%s</li>'),twentythirteen_entry_date(false));
	}
	
	if ( is_sticky()  && ! is_paged() )
		printf(__('<li class="featured-post"><span class="glyphicon glyphicon-star"></span>%s</li>'), __( 'Featured', 'twentythirteen' ));	

	
	if ( 'attachment' == get_post_type() ) {
		
		$parents = get_post_ancestors( $post->ID );
		$parent = end($parents);
		$parent_link = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_permalink($parent),get_the_title($parent));
		if(!empty($parents))
			printf(__('<li><span class="genericon genericon-attachment"></span>Attached to %s</li>'), $parent_link);
	}

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentythirteen' ) );
	if ( $categories_list ) {
		printf(__('<li class="categories-links"><span class="genericon genericon-category"></span>%s</li>'),$categories_list);
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentythirteen' ) );
	if ( $tag_list ) {
		printf(__('<li class="tags-links"><span class="genericon genericon-tag"></span>%s</li>'),$tag_list);
	}
	$format = get_post_format();
	if(false !== $format){
		
		$format_link = get_post_format_link($format);
		$format_up = get_post_format_string( get_post_format() );
		printf(__('<li class="format-links"><span class="genericon genericon-%s"></span> <a href="%s" title="%s">%s</a></li>'),$format,$format_link,$format_up,$format_up);
	}

	// Post author
	if ( 'page' != get_post_type() ) {
		printf( '<li class="author vcard">&nbsp;<span class="genericon genericon-user"></span> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></li>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentythirteen' ), get_the_author() ) ),
			get_the_author()
		);
	}
	echo "</ul>";
}