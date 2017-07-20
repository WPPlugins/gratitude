<?php wp_nonce_field('gb_gratitude_post', 'gb_gratitudenonce'); ?>
<table>
	<?php 
	$nbr = absint(GB_Gratitude::gratitude_get_option('accomplished_nbr'));
	$achievements = get_post_meta($post->ID, 'gb_gratitude_meta_appreciate', true);
	for ($i = 1; $i <= $nbr; $i++):
	?>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="gb_gratitude_meta_appreciate_$i"><?php echo $i; ?>)</label>
		</th>
		<td>
			<?php 
			$j = $i - 1;
			$achieve = "";
			if (isset($achievements[$j])) {
				$achieve = $achievements[$j];
			}
			$achieve = wp_kses($achieve, wp_kses_allowed_html('post'));
			wp_editor($achieve, "gb_gratitude_meta_appreciate_$i", 
				array(
					'textarea_rows' => 4,
				)
			); 
			?>
		</td>
	</tr>
	<?php endfor; ?>
</table>
