<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing templates of the plugin.
 *
 * @package    Plugin
 * @subpackage Plugin/public
 * @author     Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Templates' ) ) {

	class Plugin_Templates {

		/**
		 * The plugin variables container.
		 * 
		 * @var object $plugin
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
		 */
		public function single_template_item( $template ) {

			global $post;
	
			if ( $post->post_type == 'item' ) {
				$path = $this->plugin['path'] . '/templates/';
				$name = 'single-item.php';
				if( locate_template( $name ) == '' ) {
					return $path . $name;
				}	
			}
	
			return $template;
			
		}

		/**
		 * Assign the archive item template to the post type.
		 */
		public function archive_template_item( $template ) {
			
			if ( is_post_type_archive( 'item' ) ) {
				$path = $this->plugin['path'] . '/templates/';
				$name = 'archive-item.php';
				if( locate_template( array( $name ) ) == '' ) {
					return $path . $name;
				}	
			}
	
			return $template;
			
		}

	}
	
}
