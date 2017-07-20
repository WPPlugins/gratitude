<?php
if ( !class_exists('GB_Gratitude_Settings')) {
	class GB_Gratitude_Settings {
		public function __construct() {
			//register actions
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array (&$this, 'add_menu' ));
		} // END __construct
		
		/***
		 * hook into WP's admin_init action hook
		 * All settings are set-up here.
		 */
		public function admin_init() {
			//register the settings for this plugin
			register_setting( 'gb_gratitude_settings-group', 'gb_gratitude_settings', array(&$this, 'options_validate'));
			
			//add settings section
			add_settings_section (
				'gb_gratitude_settings-general',
				__('General Settings', 'gb_gratitude'),
				array(&$this, 'settings_section_general_help'),
				'gb_gratitude_settings'
			);
			
			add_settings_section (
				'gb_gratitude_settings-gratitude',
				__('Gratitude Settings', 'gb_gratitude'),
				array(&$this, 'settings_section_gratitude_help'),
				'gb_gratitude_settings'
			);

			add_settings_section (
				'gb_gratitude_settings-accomplished',
				__('Accomplishments Settings', 'gb_gratitude'),
				array(&$this, 'settings_section_accomplishment_help'),
				'gb_gratitude_settings'
			);
			
			//General Settings
			add_settings_field(
				'gb_gratitude_settings-setting_slug',
				__('Slug', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-general',
				array(
					'field' => 'slug',
					'label_for' => 'slug', 
				)
			);
			
			add_settings_field (
				'gb_gratitude_settings-setting_default_visibility',
				__('Default Visibility On New Gratitude Entries', 'gb_gratitude'), 
				array(&$this, 'settings_field_input_dropdown'), 
				'gb_gratitude_settings',
				'gb_gratitude_settings-general',
				array(
					'field' => 'default_visibility',
					'label_for' => 'default_visibility', 
					'options' => array('Public', 'Private'), 
				)
			);
	
			
			add_settings_field (
				'gb_gratitude_settings-settings_uninstall',
				__('Uninstall All Settings and Data on Plugin Deactivate', 'gb_gratitude'), 
				array(&$this, 'settings_field_input_checkbox'), 
				'gb_gratitude_settings',
				'gb_gratitude_settings-general',
				array(
					'field' => 'uninstall_on_deactivate',
					'label_for' => 'uninstall_on_deactivate', 
				)
			);
			
			//add settings fields
			add_settings_field(
				'gb_gratitude_settings-setting_title',
				__('Gratitude Prompt', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-gratitude',
				array(
					'field' => 'gratitude_title',
					'label_for' => 'gratitude_title', 
				)
			);
			
			add_settings_field(
				'gb_gratitude_settings-setting_grat',
				__('Gratitude', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-gratitude',
				array(
					'field' => 'grateful',
					'label_for' => 'grateful', 
				)
			);
			
			add_settings_field(
				'gb_gratitude_settings-setting_opp',
				__('Opportunity', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-gratitude',
				array(
					'field' => 'opportunity',
					'label_for' => 'opportunity', 
				)
			);

			add_settings_field(
				'gb_gratitude_settings-setting_did',
				__('Did', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-gratitude',
				array(
					'field' => 'did',
					'label_for' => 'did', 
				)
			);
			
			add_settings_field(
				'gb_gratitude_settings-setting_do',
				__('Do', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-gratitude',
				array(
					'field' => 'do',
					'label_for' => 'do',
				)
			);
			
			add_settings_field(
				'gb_gratitude_settings-setting_app',
				__('Appreciate', 'gb_gratitude'),
				array(&$this, 'settings_field_input_text'),
				'gb_gratitude_settings',
				'gb_gratitude_settings-gratitude',
				array(
					'field' => 'appreciate',
					'label_for' => 'appreciate', 
				)
			);
			
			//Accomplishments
			add_settings_field (
				'gb_accomplishments_settings-settings_title',
				__('Accomplishments Prompt', 'gb_gratitude'), 
				array(&$this, 'settings_field_input_text'), 
				'gb_gratitude_settings',
				'gb_gratitude_settings-accomplished',
				array(
					'field' => 'accomplished_title',
					'label_for' => 'accomplished_title', 
				)
			);
			
			add_settings_field (
				'gb_accomplishments_settings-settings_nbr_to_write',
				__('Maximum Number of Accomplishments to Record', 'gb_gratitude'), 
				array(&$this, 'settings_field_input_number'), 
				'gb_gratitude_settings',
				'gb_gratitude_settings-accomplished',
				array(
					'field' => 'accomplished_nbr',
					'label_for' => 'accomplished_nbr', 
					'min' => 1,
					'max' => 15,
				)
			);

			//possibly do additional admin_init tasks
		} //END admin_init
			
		/**
		 * explain what the gratitude section does.
		 */
		public function settings_section_gratitude_help() {
			//think of this as help text for the section
			echo __('These allow for easier customization for the Gratitude Plugin.', 'gb_gratitude');
		} // END settings_section_gratitude_help
			
		/**
		 * explain what the accomplishment section does.
		 */
		public function settings_section_accomplishment_help() {
			echo __('Customize the Accomplishments Here.', 'gb_gratitude');
		} // END settings_section_accomplishment_help
		
		/**
		 * explain what the general section does.
		 */
		public function settings_section_general_help() {
			echo __('Customize this plugins common settings.', 'gb_gratitude');
		} // END settings_section_general_help
		
		/**
		 * Sanitize and put the option into an array format.
		 * the ideas here came from pulse press validate options
		 */
		public function options_validate($raw_options) {
			//unset($GLOBALS['gratitude_options']);
			$validated_options = GB_Gratitude::default_options();
		
			$options_name = array(
				'grateful' => 'text', 
				'opportunity' => 'text', 
				'did' => 'text', 
				'do' => 'text', 
				'appreciate' => 'text', 
				'gratitude_title' => 'text', 
				'accomplished_title' => 'text', 
				'accomplished_nbr' => 'integer', 
				'uninstall_on_deactivate' => 'boolean', 
				'slug' => 'slug', 
				'default_visibility' => 'text', 
			);
			$out = 'test<br />';
			if ( isset($raw_options)) {
				foreach ($raw_options as $key => $option):
					$out = sprintf('%soption[%s] = %s<br />', $out, $key, $option);
					switch($options_name[$key]) {
						case 'integer':
							if (is_numeric($option) ) {
								$validated_options[$key] = intval($option);
							}
							else {
								$validated_options[$key] = '';
							}
							break;
							
						case 'text':
							$validated_options[$key] = sanitize_text_field($option);
							break;
							
						case 'boolean':
							$var = is_integer($option)?false:$option == 1;
							if (is_bool($var))
								$validated_options[$key] = $var;
							else
								$validated_options[$key] = false;
							break;
							
						case 'title':
							$var = sanitize_title($option);
							$validated_options[$key] = $var;
							break;

						case 'slug':
							$var = sanitize_title($option);
							$validated_options[$key] = $var;
							
							//if (GB_GRATITUDE::gratitude_get_option($key) != $var) {
								flush_rewrite_rules();
								do_action( 'rri_flush_rules' );
							//}
							break;
							
						/*
						case 'numeric':
							if (is_numeric($option) )
								$validated_options[$key] = floatval($option);
							else
								$validated_options[$key] = '';
							break;
						
						case 'date':
							if(strtotime($option))
								$validated_options[$key] = $option;
							else
								$validated_options[$key] = '';
							break;
						*/
					}
				endforeach;
			} 
			$validated_options = wp_parse_args($validated_options, get_option('gb_gratitude_settings', GB_Gratitude::default_options()));
			
			
			/*
			echo "<b>Raw Options:</b><br />";
			var_dump($raw_options);
			echo "<br /><br /><b>Validated Options:</b><br />";
			var_dump($validated_options);
			wp_die(sprintf("<br />%s", $out));
			*/
			
			return $validated_options;
		} // END options_validate

		/**
		 * this function provides text inputs for settings fields
		 */
		public function settings_field_input_text($args) {
			//get the field name from the $arg array
			$field = $args['field'];
			
			//get the value of this setting
			//wp_die(sprintf('arg: %s', $field));
			$value = GB_Gratitude::gratitude_get_option($field);
			
			//acho a proper input type="text"
			echo sprintf( '<input type="text" name="gb_gratitude_settings[%s]" id="%s" value="%s" />', $field, $field, $value);
		} // END settings_field_input_text
		
		/**
		 * this provides an input for numbers.
		 */
		public function settings_field_input_number ($args) {
			//get the field name from the $arg array
			$field = $args['field'];
			
			//get the value of this setting
			$value = GB_Gratitude::gratitude_get_option($field);
			
			$min = 0;
			if ( isset($args['min']) ) {
				$min = $args['min'];
			}
			
			$max = 100;
			if ( isset($args['min']) ) {
				$max = $args['max'];
			}
			
			//echo a proper input type=number, value has to be set to 1, HTML thing
			echo sprintf( '<input type="number" value="%s" name="gb_gratitude_settings[%s]" id="%s" min="%s" max="%s" />', $value, $field, $field, $min, $max );
		}

		/**
		 * this function provides checkbox inputs for settings fields
		 */
		public function settings_field_input_checkbox ($args) {
			//get the field name from the $arg array
			$field = $args['field'];
			
			//get the value of this setting
			$value = GB_Gratitude::gratitude_get_option($field);
			
			$checked = checked(1, $value, false);
		
			//echo a proper input type=checkbox, value has to be set to 1, HTML thing
			echo sprintf( '<input type="checkbox" %s value="1" name="gb_gratitude_settings[%s]" id="%s" />', $checked, $field, $field );
		}

		/**
		 * this function provides drop-downs inputs for settings fields
		 */
		public function settings_field_input_dropdown ($args) {
			//get the field name from the $arg array
			$field = $args['field'];
			
			//get the value of this setting
			$value = GB_Gratitude::gratitude_get_option($field);
			$items = $args['options'];
			
			echo sprintf('<select id="%s" name="gb_gratitude_settings[%s]">', $field, $field);
			foreach($items as $item) {
				$selected = ($value == $item) ? 'selected="selected"' : '';
				echo sprintf('<option value="%s" %s>%s</option>', $item, $selected, $item);
			}
			echo "</select>";
		}
		
		/**
		 * add a menu
		 */
		public function add_menu() {
			//add a page to manage this plugin's settings
			add_options_page(
				__('Gratitude Settings', 'gb_gratitude'), 
				__('Gratitude', 'gb_gratitude'), 
				'manage_options', 
				'gb_gratitude', 
				array ( &$this, 'plugin_settings_page' )
			);
		} // END add_menu
		
		/**
		 * menu callback, set-up the page to show the options.
		 */
		public function plugin_settings_page () {
			if ( !current_user_can('manage_options' )) {
				wp_die ( __('You do not have sufficient permissions to access this page.', 'gb_gratitude'));
			}
			
			//render the settings template
			include (sprintf("%s/templates/settings.php", dirname(__FILE__)));
		} //END plugin_settings_page 
		
	}
}
?>