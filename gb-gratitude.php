<?php
/*
Plugin Name: Gratitude Plugin
Plugin URI: http://gratitudeplugin.comuv.com
Description: Makes doing a gratitude journal online, a little easier.
Version: 1.0.0.0
Author: greenberrie
License: GPL2
*/

/*  Copyright 2013  greenberrie (email : greenberrie@gratitudeplugin.comuv.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('GB_Gratitude'))
{
	class GB_Gratitude
	{
		/**
		 * Construct the plugin object, and all hooks needed.
		 */
		public function __construct() {
			//initialize the settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Gratitude_settings = new GB_Gratitude_Settings();
			
			require_once(sprintf("%s/post-types/gratitude_post.php", dirname(__FILE__)));
			$GB_Gratitude_Post = new GB_Gratitude_Post();
			
			//load the localized strings
			load_plugin_textdomain('gb_gratitude', false, basename( dirname( __FILE__) ) . '/languages');
		} // END __construct
		
		/**
		 * Anything that needs activating for this plugin
		 */
		public static function activate() {
			//set defaults for the settings
			
			//accomplishments
			update_option('gb_gratitude_settings', GB_Gratitude::default_options());
			flush_rewrite_rules();
			
			
			/* i dont know if i need this yet, it may be nessaccary to do this, but holding off for the moment.
			//need a table to hold the information, i feel it is better to be wide than it is to be long.

			//any data should be inserted this way.
			*/
		} // END activate

		/**
		 * Anything that needs to be done to deactivate the plugin
		 */
		public static function deactivate() {
			//if set, remove all the options, it is basically a way to reset the plugin
			if ( 1 == self::gratitude_get_option('uninstall_on_deactivate') ) {
				delete_option('gb_gratitude_settings');
			}
		} //END deactivate
		
		/**
		 * This will return all the defaults for this plugin.
		 */
		public static function default_options() {
			return array(
				'grateful' => 'Grateful', 
				'opportunity' => 'Opportunity', 
				'did' => 'Did', 
				'do' => 'Do', 
				'appreciate' => 'Appreciate', 
				'gratitude_title' => 'Today I Am Grateful For...', 
				'accomplished_title' => 'Today I Accomplished...', 
				'accomplished_nbr' => '5', 
				'slug' => 'gratitude', 
				'uninstall_on_deactivate' => '0', 
				'default_visibility' => 'Public', 
			);
		} // END default_options
		
		/*
		 * This will retrieve the option from the array, making it slightly easier for consumption
		 * Came from Pulse Press db helper, and then modified to make more sense to me.
		 */
		public static function gratitude_get_option($name) {
			global $gratitude_options;
			$out = '';
			if (! isset($gratitude_options)) {
				$gratitude_options = get_option('gb_gratitude_settings', GB_Gratitude::default_options());
			}
			
			if (isset($gratitude_options[$name])) {
				$out = $gratitude_options[$name];
			} else {
				 // $out = get_option('gb_gratitude_settings', GB_Gratitude::default_options());
				 $new_field = GB_Gratitude::default_options();
				 $out = $new_field[$name];
			}
			
			
			return $out;
		} // END gratitude_get_option
		
	} // END GB_Gratitude
} // END if (!class_exists('GB_Gratitude'))

if (class_exists('GB_Gratitude')) {
	global $gratitude_options;
	//install and uninstall hooks
	register_activation_hook(__FILE__, array('GB_Gratitude', 'activate'));
	register_deactivation_hook(__FILE__, array('GB_Gratitude', 'deactivate'));

	//instaniate the plugin class
	$gb_gratitude_template = new GB_Gratitude();
	
	//add a link to the settings page onto the plugin page
	if (isset($gb_gratitude_template)) {
		//add the settings link to the plugins page
		function gb_gratitude_settings_link ($links) {
			$settings_link = '<a href="options-general.php?page=gb_gratitude">' . __('Settings', 'gb_gratitude') . '</a>';
			array_unshift($links, $settings_link);
			return $links;
		} // END gb_gratitude_settings_link
		
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", 'gb_gratitude_settings_link');
	} // END if (isset($gb_gratitude_template))
} // END if (class_exists('GB_Gratitude'))

?>