<?php wp_nonce_field('gb_gratitude_post', 'gb_gratitudenonce'); ?>
<table>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="gb_gratitude_meta_grat"><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('grateful')); ?></label>
		</th>
		<td>
			<?php wp_editor(@wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_grat', true), wp_kses_allowed_html('post')), 'gb_gratitude_meta_grat', 
				array(
					'textarea_rows' => 4,
				)
			); ?>
		</td>
	</tr>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="gb_gratitude_meta_opp"><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('opportunity')); ?></label>
		</th>
		<td>
			<?php wp_editor(@wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_opp', true), wp_kses_allowed_html('post')), 'gb_gratitude_meta_opp', 
				array(
					'textarea_rows' => 4,
				)
			); ?>
		</td>
	</tr>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="gb_gratitude_meta_did"><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('did')); ?></label>
		</th>
		<td>
			<?php wp_editor(@wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_did', true), wp_kses_allowed_html('post')), 'gb_gratitude_meta_did', 
				array(
					'textarea_rows' => 4,
				)
			); ?>
		</td>
	</tr>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="gb_gratitude_meta_do"><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('do')); ?></label>
		</th>
		<td>
			<?php wp_editor(@wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_do', true), wp_kses_allowed_html('post')), 'gb_gratitude_meta_do', 
				array(
					'textarea_rows' => 4,
				)
			); ?>
		</td>
	</tr>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="gb_gratitude_meta_app"><?php echo sanitize_text_field(GB_Gratitude::gratitude_get_option ('appreciate')); ?></label>
		</th>
		<td>
			<?php wp_editor(@wp_kses(get_post_meta($post->ID, 'gb_gratitude_meta_app', true), wp_kses_allowed_html('post')), 'gb_gratitude_meta_app', 
				array(
					'textarea_rows' => 4,
				)
			); ?>
		</td>
	</tr>
</table>