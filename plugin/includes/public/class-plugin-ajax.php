<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the public AJAX functionality.
 *
 * @package       Plugin
 * @subpackage    Plugin/public
 * @author        Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Ajax' ) ) {

	class Plugin_Ajax {

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
		 * Define an example AJAX request.
		 */
		public function callback() {

			// Check the nonce for permission.
			if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], $this->plugin['id'] ) ) {
				die( 'Permission Denied' );
			}

			// Get the request data.
			$request = array(
				'example' => isset( $_REQUEST['example'] ) ? $_REQUEST['example'] : 'default'
			);

			// Define an empty response array.
			$response = array(
				'content' => 'This is a successful response with an example request for `' . $request['example'] . '`.'
			);

			// Terminate the callback and return a proper response.
			wp_die( json_encode( $response ) );

		}

	}

}
