<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the admin functionality.
 *
 * @package    Plugin
 * @subpackage Plugin/admin
 * @author     Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Admin' ) ) {

	class Plugin_Admin {

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
		 * Enqueue the admin stylesheets.
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin['id'], $this->plugin['url'] . 'assets/admin/css/plugin-admin.css', array(), $this->plugin['version'], 'all' );

		}

		/**
		 * Enqueue the admin scripts.
		 * 
		 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin['id'], $this->plugin['url'] . 'assets/admin/js/plugin-admin.js', array( 'jquery' ), $this->plugin['version'], true );

		}

		/**
		 * Extend the default action links.
		 *
		 * @param     array    $actions       Associative array of action names to anchor tags.
		 * @return    array    Associative array of plugin action links,
		 */
		public function action_links( $actions ) {

			return array_merge( array( 
				'<a href="' . admin_url( 'admin.php?page=' . $this->plugin['id'] ) . '">' . 
					__( 'Settings', 'public' ) . 
				'</a>',
			), $actions );
			
		}

	}

}
