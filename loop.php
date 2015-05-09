<?php  
  global $i; 
  
  if(have_posts()):
		$paged = get_query_var('paged');
		$posts_per_page = get_option('posts_per_page');
		$i = ($paged * $posts_per_page) + 1;
	  while ( have_posts() ) : the_post(); 
		get_template_part( 'templates/item', spacedmonkey_template() );
		if(($i % 2) == 0){
			echo '<div class="visible-sm clear"></div>';
		}
		if(($i % 3) == 0){
			echo '<div class="visible-md clear"></div>';
		}
		if(($i % 4) == 0){
			echo '<div class="visible-lg clear"></div>';
		}
		$i++;

	   endwhile; 
    endif;
?>
