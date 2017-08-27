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
			new ALXWP_Meta( 'item', array(
				array(
					'id' => 'section',
					'title' => 'Meta Boxes',
					'fields' => array(
						array(
							'id'          => 'text',
							'title'       => 'Text',
							'type'        => 'text',
							'description' => 'This is an example text setting.',
							'default'     => 'Default Value'
						),
						array(
							'id'          => 'textarea',
							'title'       => 'Textarea',
							'type'        => 'textarea',
							'description' => 'This is an example textarea setting.',
							'default'     => 'Default Value'
						),
						array(
							'id'          => 'toggle',
							'title'       => 'Toggle',
							'type'        => 'toggle',
							'description' => 'This is an example toggle setting.',
						),
						array(
							'id'          => 'select',
							'title'       => 'Select',
							'type'        => 'select',
							'description' => 'This is an example select setting.',
							'options'     => array(
								array(
									'id'    => 'option_1',
									'title' => 'Option 1'
								),
								array(
									'id'    => 'option_2',
									'title' => 'Option 2'
								),
								array(
									'id'    => 'option_3',
									'title' => 'Option 3'
								)
							),
							'default' => 'option_2'
						),
						array(
							'id'          => 'checkbox',
							'title'       => 'Checkboxes',
							'type'        => 'checkbox',
							'description' => 'This is an example checkboxes setting.',
							'options'     => array(
								array(
									'id'    => 'option_1',
									'title' => 'Option 1'
								),
								array(
									'id'    => 'option_2',
									'title' => 'Option 2'
								),
								array(
									'id'    => 'option_3',
									'title' => 'Option 3'
								)
							),
							'default' => 'option_2'
						),
						array(
							'id'          => 'radio',
							'title'       => 'Radio',
							'type'        => 'radio',
							'description' => 'This is an example radio setting.',
							'options'     => array(
								array(
									'id'    => 'option_1',
									'title' => 'Option 1'
								),
								array(
									'id'    => 'option_2',
									'title' => 'Option 2'
								),
							),
							'default' => 'option_2'
						)
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

			require_once dirname( __FILE__ ) . '/libraries/class-alxwp-meta.php';

		}

	}

}
