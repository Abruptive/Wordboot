<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the custom meta boxes.
 *
 * @package       Wordboot
 * @subpackage    Wordboot/admin
 * @author        Alexandru Doda <https://alexandru.co>
 */

if( ! class_exists( 'Wordboot_Meta' ) ) {
	
	class Wordboot_Meta {

		/**
		 * Construct the class.
		 */
		public function __construct() {

			// Load the dependencies.
			$this->load_dependencies();

			// Create an example meta box for the 'item' post type.
			new Metabun( 'item', array(
				array(
					'id'     => 'section',
					'title'  => 'Meta Boxes',
					'fields' => array(
						array(
							'id'          => 'text',
							'title'       => __( 'Text', 'wordboot' ),
							'type'        => 'text',
							'description' => 'This is an example text field.'
						),
					),
					'context'  => 'normal',
					'priority' => 'default'
				)
			) );

		}

		/**
		 * Load the dependencies.
		 */
		private function load_dependencies() {

			/**
			 * Require the Metabun class for custom meta boxes.
			 * 
			 * @link https://github.com/AlexandruDoda/Metabun
			 */
			require_once dirname( __FILE__ ) . '/libraries/metabun/class-metabun.php';

		}

	}

}
