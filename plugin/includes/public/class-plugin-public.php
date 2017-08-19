<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin
 * @subpackage Plugin/public
 * @author     Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Public' ) ) {

	class Plugin_Public {

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
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Plugin_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Plugin_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			
			wp_enqueue_style( $this->plugin['id'], $this->plugin['url'] . 'assets/public/css/plugin-public.css', array(), $this->plugin['version'], 'all' );

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Plugin_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Plugin_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			
			wp_enqueue_script( $this->plugin['id'], $this->plugin['url'] . 'assets/public/js/plugin-public.js', array( 'jquery' ), $this->plugin['version'], false );

			wp_localize_script( $this->plugin['id'], 'plugin', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'plugin' )
			) );

		}

	}

}
