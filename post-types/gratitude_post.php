<?php

if ( !class_exists('GB_Gratitude_Post')) {
	/**
	 * A PostTypeTemplate class that provides 3 additional meta fields
	 */
	class GB_Gratitude_Post {
		const POST_TYPE = "gb-gratitude-post";
		private $_meta = array (
			'gb_gratitude_meta_grat',
			'gb_gratitude_meta_opp', 
			'gb_gratitude_meta_did', 
			'gb_gratitude_meta_do', 
			'gb_gratitude_meta_app', 
		);
		
		/**
		 * The constructor
		 */
		public function __construct() {
			//register actions 
			add_action ('init', array ( &$this, 'init'));
			add_action ('admin_init', array (&$this, 'admin_init'));
		} //END __construct
		
		/**
		 * hook into WP's init action hook
		 */
		public function init() {
			//initialize post type
			$this->create_post_type();
			
			//add a custom template if none exists, don't expect any to start off with
			add_filter( 'template_include', array(&$this, 'include_template_function'), 1);
			
			//get the home page to show these
			add_filter('pre_get_posts', array(&$this, 'include_post_template'));
			
			//set a default title
			add_filter('default_title', array(&$this, 'default_title'));
			
			add_action( 'save_post', array(&$this, 'save_post'));
			
			//This will change the default visibility of this post to what was set in the settings.
			add_action( 'post_submitbox_misc_actions' , array(&$this, 'default_post_visibility' ));
		} //END init
		
		/**
		 * to help default a title, whenever it loads up.
		 */
		public function default_title($title) {
			$title = '';
			$screen = get_current_screen();

			if ( isset($screen) && ($screen->post_type == self::POST_TYPE)) {
				$title = date('F j, Y');
			}
			return $title;
		}
		
		/**
		 * to help make sure this post type will show up elsewhere.
		 */
		public function add_to_feed($qv) { 
			if ( !isset($qv['post_type']) ) {
				$qv['post_type'] = array('post', self::POST_TYPE);
			}
			return $qv;
		}
		
		/**
		 * should add these to the home
		 */
		public function include_post_template($query) {
			//wp_die(sprintf('testing, is Home? %s', is_home()));
			if ( is_home() && $query->is_main_query() || is_feed() ) {
				$query->set ('post_type', array('post', 'page', self::POST_TYPE));
			}
			return $query;
		}

		/**
		 * this shows the template and all of that
		 */
		public function include_template_function( $template_path ) {
			/*
			to deal with: 
				is_post_type_archive for http://localhost/development_site/gb-gratitude-post/
				is_search for http://localhost/development_site/?s=2013
			*/
			if (get_post_type() == self::POST_TYPE) {
				if (is_single()) {
					//check if the file exists in the theme first,
					//otherwise serve the file from the plugin
					if ($theme_file = locate_template(array("single-%s.php", self::POST_TYPE))) {
						$template_path = $theme_file;
					} else {
						$template_path = sprintf("%s/../templates/single-%s.php", dirname(__FILE__), self::POST_TYPE);
					}
				}
				if (is_post_type_archive()) {
					if ($theme_file = locate_template(array("archive-%s.php", self::POST_TYPE))) {
						$template_path = $theme_file;
					} else {
						$template_path = sprintf("%s/../templates/archive-%s.php", dirname(__FILE__), self::POST_TYPE);
					}
				}
			}
			return $template_path;
		}
		
		/**
		 * Create the post type
		 */
		public function create_post_type() {
			$slug = GB_Gratitude::gratitude_get_option('slug', self::POST_TYPE);
			register_post_type (self::POST_TYPE,
				array(
					'labels' => array(
						'name' => __('Gratitude Posts', 'gb_gratitude'),
						'singular_name' => __('Gratitude Post', 'gb_gratitude')
					),
					'public' => true,
					'has_archive' => true,
					'description' => __('This is to help make journaling about your gratitude a little easier, and to help improve your life.', 'gb_gratitude'),
					'supports' => array(
						 'title'
					 ),
					 'rewrite' => array( 
						'slug' => $slug, 
						'with_front' => true
					), 
				)
			);
			
		} //END create_post_type
		
		/**
		 * save the metaboxes for this custom post type
		 */
		public function save_post($post_id) {
			//verify if this is an auto save routine.
			//if it is our form has not been submitted, so we dont want to do anything
			if ( defined ('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return ;
			}

			//verify this came from the our screen and with proper authorization,
			//because save_post can be triggered at other times
			if (!isset( $_POST['gb_gratitudenonce']) ) {
				return ;
			}
			if ( !wp_verify_nonce( $_POST['gb_gratitudenonce'], 'gb_gratitude_post' ) ) {
				return ;
			}

			$post_type = !empty($_POST['post_type']) ? $_POST['post_type'] : 'post';
			
			if ('page' == $post_type) {
				if  ( !current_user_can ('edit_page', $post_id)) {
					return ;
				}
			}
			
			if($post_type == self::POST_TYPE && current_user_can('edit_post', $post_id)) {
				
				//accomplishments.
				$nbr = GB_Gratitude::gratitude_get_option('accomplished_nbr', 3);
				for ($i = 1; $i <= $nbr; $i++) {
					$field_name = "gb_gratitude_meta_appreciate_$i";
					if ($_POST[$field_name] != "") {
						$appreciate[] = wp_kses($_POST[$field_name], wp_kses_allowed_html('post'));
					}
				}
				update_post_meta($post_id, 'gb_gratitude_meta_appreciate', $appreciate);
				
				//gratitude
				foreach ($this->_meta as $field_name) {
					//update the post's meta field
					update_post_meta($post_id, $field_name, wp_kses($_POST[$field_name], wp_kses_allowed_html('post')));
				}
			}
			else {
				return ;
			} // END if ($_POST['post_type'] == self::POST_TYPE && current_user_can ('edit_post', $post_id) ) {
		} //END save_post
		
		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init() {
			//add metaboxes
			add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
		} //END admin_init
		
		/**
		 * hook into WP's add_meta_boxes action hook
		 */
		public function add_meta_boxes() {

			$meta_title = GB_Gratitude::gratitude_get_option('gratitude_title');
			//Add this metabox to every selected post
			add_meta_box(
				sprintf('gb_gratitude_post_%s_section', self::POST_TYPE), 
				sprintf('%s' , $meta_title), 
				array(&$this, 'add_inner_meta_boxes'),
				self::POST_TYPE, 'normal', 'high'
			);

			$achievement_title = GB_Gratitude::gratitude_get_option('accomplished_title');
			//Add this metabox to every selected post
			add_meta_box(
				'gb_gratitude_post_achievement_section', 
				sprintf('%s' , $achievement_title), 
				array(&$this, 'add_achieved_meta_boxes'),
				self::POST_TYPE, 'normal', 'high'
			);
		} // END add_meta_boxes
		
		/**
		 * called off of the add meta box
		 */
		public function add_inner_meta_boxes($post) {
			//render the metabox
			include(sprintf("%s/../templates/%s_metabox.php", dirname(__FILE__), self::POST_TYPE));
		} //END add_inner_meta_boxes
		
		/**
		 * called off of the add meta box
		 */
		public function add_achieved_meta_boxes($post) {
			include( sprintf( "%s/../templates/achievements_metabox.php", dirname(__FILE__)));
		} // END add_achieved_meta_boxes
	


		/**
		 * This will update all new posts default visibility based on the default visiblity setting.
		 */
		function default_post_visibility($post_info){
			if (get_post_type() == self::POST_TYPE) {
				global $post;

				if ( $post->post_status == 'auto-draft' ) {
					$visibility_setting = GB_Gratitude::gratitude_get_option('default_visibility', self::POST_TYPE);
					$post->post_password = '';
					$visibility = strtolower($visibility_setting);
					$visibility_trans = __($visibility_setting);
					?>

					<script type="text/javascript">
						(function($){
							try {
								$('#post-visibility-display').text('<?php echo $visibility_trans; ?>');
								$('#hidden-post-visibility').val('<?php echo $visibility; ?>');
								$('#visibility-radio-<?php echo $visibility; ?>').attr('checked', true);
							} catch(err){}
						}) (jQuery);
					</script>
					<?php
				}
		}
		} // END default_post_visibility
	} //END CLASS GB_Gratitude_Post
		
} //END if ( !class_exists('GB_Gratitude_Post'))

?>