<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing templates of the plugin.
 *
 * @package       Plugin
 * @subpackage    Plugin/public
 * @author        Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Templates' ) ) {

	class Plugin_Templates {

		/**
		 * The plugin variables container.
		 * 
		 * @var    object    $plugin
		 */
		private $plugin;

		/**
		 * Construct the class.
		 * 
		 * @param    object    $plugin    The plugin variables.
		 */
		public function __construct( $plugin ) {

			$this->plugin = $plugin;

		}

		/**
		 * Assign the single item template to the post type.
		 * 
		 * @param     string    The default single template.
		 * @return    string    The proper single template.
		 */
		public function single_template_item( $template ) {

			if ( is_singular( 'item' ) ) {
				$name = 'single-item.php';
				if( locate_template( $name ) == '' ) {
					$template = $this->plugin['path'] . '/templates/' . $name;
				}	
			}
	
			return $template;
			
		}

		/**
		 * Assign the archive item template to the post type.
		 * 
		 * @param     string    The default archive template.
		 * @return    string    The proper archive template.
		 */
		public function archive_template_item( $template ) {

			if ( is_post_type_archive( 'item' ) ) {
				$name = 'archive-item.php';
				if( locate_template( array( $name ) ) == '' ) {
					$template = $this->plugin['path'] . '/templates/' . $name;
				}
			}
	
			return $template;
			
		}

	}
	
}
