<div class="wrap">
	<h2>Gratitude</h2>
	<form method="post" action="options.php">
		<?php @settings_fields('gb_gratitude_settings-group'); ?>
		<?php do_settings_sections('gb_gratitude_settings'); ?>
		<?php @submit_button(); ?>
	</form>
</div>