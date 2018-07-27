<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the internationalization functionality.
 * 
 * @package       Wordboot
 * @subpackage    Wordboot/setup
 * @author        Abruptive <https://abruptive.com>
 */

if( ! class_exists( 'Wordboot_Translation' ) ) {

	class Wordboot_Translation {

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
		 * Load the plugin text domain for translation.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/load_plugin_textdomain
		 */
		public function load_textdomain() {

			load_plugin_textdomain(
				$this->plugin['id'],
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}

	}

}
