<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area col-lg-8 col-md-8 col-sm-12 col-xs-12">
		<div id="content" class="site-content" role="main">


			
			
			<?php if ( have_posts() ) : ?>


				<?php /* The loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', spacedmonkey_template() ); ?>
					<?php twentythirteen_post_nav(); ?>
					<?php comments_template(); ?>
				<?php endwhile; ?>
	
	
	
			<?php else : ?>
				<?php get_template_part( 'content', spacedmonkey_template() ); ?>
			<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>