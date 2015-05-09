<ul class="media-list">
	<?php /* The loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'templates/item', spacedmonkey_template() ); ?>
	<?php endwhile; ?>
</ul>