<li id="post-<?php the_ID(); ?>" <?php post_class("media"); ?>>
	<?php if ( has_spacedmonkey_image('thumbnail') ):?>

		<a class="thumbnail hidden-xs pull-left" href="<?php the_permalink();?>">
			<?php get_spacedmonkey_image('thumbnail', array('class' => "media-object")); ?>
	    </a>

    <?php endif;?>
    <div class="media-body">
      <h4 class="media-heading entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a> <small><?php echo ucfirst(get_post_type());?></small></h4>
      
      <?php the_excerpt(); ?> 
    </div>
	
</li>