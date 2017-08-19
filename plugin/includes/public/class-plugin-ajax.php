<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing AJAX functionality.
 *
 * Creates the various functions used for AJAX on the front-end.
 *
 * @package    Plugin
 * @subpackage Plugin/public
 * @author     Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Ajax' ) ) {

	class Plugin_Ajax {

		/**
		 * An example AJAX callback.
		 *
		 * @return void
		 */
		public function callback() {

			// Check the nonce for permission.
			if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'plugin' ) ) {
				die( 'Permission Denied' );
			}

			// Define an empty response array.
			$response = array(
				'status'  => 200,
				'content' => 'This is an AJAX response.'
			);

			// Terminate the callback and return a proper response.
			wp_die( json_encode( $response ) );

		}

	}

}
