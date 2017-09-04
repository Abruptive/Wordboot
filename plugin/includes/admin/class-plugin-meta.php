<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the custom meta boxes.
 *
 * @package       Plugin
 * @subpackage    Plugin/admin
 * @author        Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Meta' ) ) {
	
	class Plugin_Meta {

		/**
		 * Construct the class.
		 */
		public function __construct() {

			// Load the dependencies.
			$this->load_dependencies();

			// Create an example meta box for the 'item' post type.
			new Metabun( 'item', array(
				array(
					'id' => 'section',
					'title' => 'Meta Boxes',
					'fields' => array(
						array(
							'id'          => 'text',
							'title'       => 'Text',
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

			require_once dirname( __FILE__ ) . '/libraries/metabun/class-metabun.php';

		}

	}

}
