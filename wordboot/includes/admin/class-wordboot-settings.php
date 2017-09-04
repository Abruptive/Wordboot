<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the settings page.
 *
 * @package       Wordboot
 * @subpackage    Wordboot/admin
 * @author        Alexandru Doda <https://alexandru.co>
 */

if( ! class_exists( 'Wordboot_Settings' ) ) {

	class Wordboot_Settings {

		/**
		 * The plugin variables container.
		 * 
		 * @var    object    $plugin
		 */
		private $plugin;

		/**
		 * The settings to be registered.
		 *
		 * @var    array    $settings
		 */
		private $settings;

		/**
		 * Construct the class.
		 * 
		 * @param    object    $plugin    The plugin variables.
		 */
		public function __construct( $plugin ) {

			$this->plugin = $plugin;

			$this->settings = array(
				array(
					'id'          => 'example_option_text',
					'name'        => 'Text',
					'type'        => 'text',
					'description' => 'This is an example text setting.',
					'default'     => 'Default Value'
				),
				array(
					'id'          => 'example_option_textarea',
					'name'        => 'Textarea',
					'type'        => 'textarea',
					'description' => 'This is an example textarea setting.',
					'default'     => 'Default Value'
				),
				array(
					'id'          => 'example_option_toggle',
					'name'        => 'Toggle',
					'type'        => 'toggle',
					'description' => 'This is an example toggle setting.',
					'default'     => 1
				),
				array(
					'id'          => 'example_option_select',
					'name'        => 'Select',
					'type'        => 'select',
					'description' => 'This is an example select setting.',
					'options'     => array(
						array(
							'id'   => 'option_1',
							'name' => 'Option 1'
						),
						array(
							'id'   => 'option_2',
							'name' => 'Option 2'
						),
						array(
							'id'   => 'option_3',
							'name' => 'Option 3'
						)
					),
					'default' => 'option_2'
				),
				array(
					'id'          => 'example_option_repeater',
					'name'        => 'Repeater',
					'type'        => 'repeater',
					'description' => 'This is an example repeater setting.'
				)
			);

		}

		/**
		 * Register the settings menu page.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/add_menu_page
		 */
		public function add_settings_page() {

			add_menu_page( 
				sprintf( __( '%s Settings', 'wordboot' ), $this->plugin['name'] ), 
				$this->plugin['name'], 
				'administrator', 
				$this->plugin['id'], 
				array( $this, 'settings_page' ), 
				'dashicons-marker' 
			);

		}

		/**
		 * Create the plugin settings page.
		 */ 
		public function settings_page() { ?>

			<div class="wrap">
				<h1>
					<?php printf( __( '%s Settings', 'wordboot' ), $this->plugin['name'] ) ?>
				</h1>

				<form method="post" action="options.php">

					<?php settings_fields( $this->plugin['id'] . '-settings-group' ); ?>
					<?php do_settings_sections( $this->plugin['id'] . '-settings-group' ); ?>

					<table class="form-table">
						<?php foreach( $this->settings as $setting ) { ?>
							<tr valign="top">
								<th scope="row"><?php echo $setting['name']; ?></th>
								<td>
									<?php echo $this->settings_field( $setting ); ?>
									<?php if( isset( $setting['description'] ) ) { ?>
										<p class="description"><?php echo $setting['description']; ?></p>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</table>
					
					<?php submit_button( __( 'Save Changes', 'wordboot' ) ); ?> &nbsp;

				</form>
			</div>

		<?php }

		/**
		 * Generate the field for the settings page.
		 */
		public function settings_field( $setting ) {

			switch( $setting['type'] ) {

				case 'text':
					return '<input type="text" name="' . $setting['id'] . '" class="regular-text" value="' . esc_attr( get_option( $setting['id'] ) ) . '" />';
				break;

				case 'textarea':
					return '<textarea name="' . $setting['id'] . '" class="regular-text" rows="3">' . esc_attr( get_option( $setting['id'] ) ) . '</textarea>';
				break;

				case 'toggle':
					return '<input type="checkbox" name="' . $setting['id'] . '" value="1"' . checked( '1', get_option( $setting['id'] ), false ) . ' />';
				break;

				case 'select':
					$markup = '<select name="' . $setting['id'] . '">';
					foreach( $setting['options'] as $option ) {
						$markup .= '<option value="' . $option['id'] . '"' . selected( get_option( $setting['id'] ), $option['id'], false ) . '>' . $option['name'] . '</option>';
					}
					$markup .= '</select>';
					return $markup;
				break;

				case 'repeater':
					return '
					<table class="repeater wp-list-table">
						<tbody>
							<tr class="repeater-template hidden">
								<td>
									<input type="text" data-name="' . $setting['id'] . '[]" class="regular-text" value="">
								</td>
								<td>
									<button class="button" data-repeater="remove" tabindex="-1">
										' . __( 'Remove', 'wordboot' ) . '
									</button>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2">
									<button class="button" data-repeater="add">
										' . __( 'Add Item', 'wordboot' ) . '
									</button>
								</td>
							</tr>
							<input type="hidden" class="repeater-data" value=' . "'" . json_encode( get_option( $setting['id'] ) ) . "'" . '>
						</tfoot>
					</table>';
				break;

				default: 
					return '<input type="text" name="' . $setting['id'] . '" class="regular-text" value="' . esc_attr( get_option( $setting['id'] ) ) . '">';
				break;

			}

		}

		/**
		 * Register the settings.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/register_setting
		 */ 
		public function add_settings() {
			
			foreach( $this->settings as $setting ) {

				$args = array();

				if( isset( $setting['default'] ) ) {
					$args['default'] = $setting['default'];
				}

				register_setting( $this->plugin['id'] . '-settings-group', $setting['id'], $args );

			}

		}

	}

}
