<?php
/**
 * A Lot of Credit to the theme: Coraline
 */
get_header(); ?>

		<div id="content-container">
			<div id="content" role="main">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<h2 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="wp-plugin">
					<h3><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option('gratitude_title')); ?></h3>
					<table>
						<tr valign="top">
							<th class="metabox_label_column">
								<?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('grateful')); ?>
							</th>
							<td>
								<?php echo @wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_grat', true), wp_kses_allowed_html('post')); ?>
							</td>
						</tr>
						<tr valign="top">
							<th class="metabox_label_column">
								<?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('opportunity')); ?>
							</th>
							<td>
								<?php echo @wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_opp', true), wp_kses_allowed_html('post')); ?>
							</td>
						</tr>
						<tr valign="top">
							<th class="metabox_label_column">
								<?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('did')); ?>
							</th>
							<td>
								<?php echo @wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_did', true), wp_kses_allowed_html('post')); ?>
							</td>
						</tr>
						<tr valign="top">
							<th class="metabox_label_column">
								<?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('do')); ?>
							</th>
							<td>
								<?php echo @wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_do', true), wp_kses_allowed_html('post')); ?>
							</td>
						</tr>
						<tr valign="top">
							<th class="metabox_label_column">
								<?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('appreciate')); ?>
							</th>
							<td>
								<?php echo @wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_app', true), wp_kses_allowed_html('post')); ?>
							</td>
						</tr>
					</table>
					<?php $i = 1;
						$achievements = get_post_meta($post->ID, 'gb_gratitude_meta_appreciate', true);
						if ( !empty($achievements)):
					?>
					<h3><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option('accomplished_title')); ?></h3>
					<table>
					<?php $i = 1;
							foreach ($achievements as $value):
					?>
					<tr valign="top">
						<th><?php echo sprintf("%s)", $i); ?></th>
						<td><?php $value = wp_kses($value, wp_kses_allowed_html('post'));
											echo $value; 
								?>
						</td>
					</tr>
					<?php $i++;
					endforeach;
					?>
					</table>
					<?php else: ?>
					<h3><?php _e('No Achievements were recorded for the day.', 'gb_gratitude'); ?></h3>
					<?php endif; ?>
				</div>
						
					<div class="entry-content">
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gb_gratitude' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'gb_gratitude' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-## -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>