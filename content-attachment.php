<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-meta">
						<?php twentythirteen_entry_meta(); ?>
						<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-meta -->

				</header><!-- .entry-header -->

				<div class="entry-content">
					

					<div class="entry-attachment">
						<div class="attachment">
							
							<?php 
								$type = explode("/",get_post_mime_type()); 

								switch($type[0]){
									case 'audio':
										echo wp_audio_shortcode( array('src' => wp_get_attachment_url() ) );
										break;
									case 'video':
										echo wp_video_shortcode( array('src' => wp_get_attachment_url() ) );
										break;
									case 'image':
										the_attachment_link(get_the_ID(),true);
										$parents = get_post_ancestors( get_the_ID() );
										$parent = end($parents);
										$gallery = do_shortcode("[gallery id=$parent]");
										break;
									default:
										the_attachment_link(get_the_ID(),false, false, true);
										break;
								}
								
								
								
							?>
							<?php if ( has_excerpt() ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div>
							<?php endif; ?>
						</div><!-- .attachment -->
					</div><!-- .entry-attachment -->
					<ul id="image-navigation" class="navigation image-navigation pager" role="navigation">
						<li class="nav-previous previous"><?php previous_image_link( false, __( '<span class="meta-nav glyphicon glyphicon-chevron-left"></span> Previous', 'twentythirteen' ) ); ?></li>
						<li class="nav-next next"><?php next_image_link( false, __( 'Next <span class="meta-nav glyphicon glyphicon-chevron-right"></span>', 'twentythirteen' ) ); ?></li>
					</ul><!-- #image-navigation -->
					<?php echo $gallery;?>
					
					<div class="entry-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentythirteen' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-description -->
					
					
				</div><!-- .entry-content -->
			</article><!-- #post -->