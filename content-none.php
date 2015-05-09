<article id="error-404" <?php post_class();?>>
	<header class="page-header">
		<h1 class="page-title"><?php _e( 'Not Found', 'twentythirteen' ); ?></h1>
	</header>
		<?php if(is_active_sidebar('sidebar-1')):
					spacedmonkey_dynamic_sidebar( 'sidebar-1' );
			  else:
		?>

		<div class="entry-content">
			<h2><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'twentythirteen' ); ?></h2>
			<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentythirteen' ); ?></p>

			<?php get_search_form(); ?>
		</div><!-- .page-content -->
		<?php endif;?>
		<?php wp_login_form( $args ); ?> 
		
</article>
