<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the public functionality of the plugin.
 *
 * @package       Wordboot
 * @subpackage    Wordboot/public
 * @author        Abruptive <https://abruptive.com>
 */

if( ! class_exists( 'Wordboot_Public' ) ) {

	class Wordboot_Public {

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
		 * Enqueue the public stylesheets.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style
		 */
		public function enqueue_styles() {

			// Enqueue and localize the public plugin script.
			wp_enqueue_style( $this->plugin['id'], $this->plugin['url'] . 'assets/public/css/public.min.css', array(), $this->plugin['version'], 'all' );

		}

		/**
		 * Enqueue the public scripts.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script
		 * @link https://developer.wordpress.org/reference/functions/wp_localize_script
		 */
		public function enqueue_scripts() {

			// Enqueue and localize the public plugin script.
			wp_enqueue_script( $this->plugin['id'], $this->plugin['url'] . 'assets/public/js/public.min.js', array( 'jquery' ), $this->plugin['version'], true );
			wp_localize_script( $this->plugin['id'], $this->plugin['id'], array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( $this->plugin['id'] )
			) );

		}

	}

}
