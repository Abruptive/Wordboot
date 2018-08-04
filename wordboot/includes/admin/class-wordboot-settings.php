<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the settings page.
 *
 * @package       Wordboot
 * @subpackage    Wordboot/admin
 * @author        Abruptive <https://abruptive.com>
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
		private $settings = array();

		/**
		 * Construct the class.
		 * 
		 * @param    object    $plugin    The plugin variables.
		 */
		public function __construct( $plugin ) {

			$this->plugin = $plugin;

			// General
			$this->settings[] = array(
				'title'    => 'General',
				'id'       => 'general',
				'settings' => array(
					array(
						'id'          => 'example_option_text',
						'name'        => 'Text',
						'type'        => 'text',
						'description' => 'This is an example text setting.',
						'default'     => 'Default Value'
					),
					array(
						'id'          => 'example_option_number',
						'name'        => 'Number',
						'type'        => 'number',
						'description' => 'This is an example text setting.',
					),
					array(
						'id'          => 'example_option_url',
						'name'        => 'Link',
						'type'        => 'url',
						'description' => 'This is an example URL setting.',
					),
					array(
						'id'          => 'example_option_email',
						'name'        => 'Email Address',
						'type'        => 'email',
						'description' => 'This is an example email setting.',
					),
					array(
						'id'          => 'example_option_tel',
						'name'        => 'Phone Number',
						'type'        => 'tel',
						'description' => 'This is an example phone setting.',
					),
					array(
						'id'          => 'example_option_textarea',
						'name'        => 'Textarea',
						'type'        => 'textarea',
						'description' => 'This is an example textarea setting.',
					)
				)
			);

			// Advanced
			$this->settings[] = array(
				'title'    => 'Advanced',
				'id'       => 'advanced',
				'settings' => array(
					array(
						'id'          => 'example_option_toggle',
						'name'        => 'Toggle',
						'type'        => 'toggle',
						'description' => 'This is an example toggle setting.',
						'default'     => 1
					),
					array(
						'id'          => 'example_option_checkbox',
						'name'        => 'Checkbox',
						'type'        => 'checkbox',
						'description' => 'This is an example checkbox field.',
						'options'     => array(
							array(
								'id'    => 'option_1',
								'title' => 'Option 1',
							),
							array(
								'id'    => 'option_2',
								'title' => 'Option 2'
							),
							array(
								'id'    => 'option_3',
								'title' => 'Option 3'
							)
						)
					),
					array(
						'id'          => 'example_option_radio',
						'name'        => 'Radio',
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
						'default' => 'option_1'
					),
					array(
						'id'          => 'example_option_message',
						'name'        => 'Message',
						'type'        => 'message',
						'description' => 'This is an example message.'
					),
					array(
						'id'          => 'example_option_repeater',
						'name'        => 'Repeater',
						'type'        => 'repeater',
						'description' => 'This is an example repeater setting.'
					)
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
		 * Markup for tabs.
		 */
		public function display_tabs( $active ) { ?>

			<nav class="nav-tab-wrapper">
				<?php foreach( $this->settings as $tab ){ ?>
					<a href="<?php echo admin_url( 'admin.php?page=wordboot&tab=' . $tab['id'] ); ?>" class="nav-tab <?php echo $tab['id'] !== $active ?: 'nav-tab-active'; ?> nav-tab--<?php echo $tab['id']; ?>">
						<?php echo $tab['title']; ?>
					</a>
				<?php } ?>
			</nav>

		<?php }

		/**
		 * Markup for an individual setting.
		 */
		public function display_setting( $setting ) { ?>

			<tr valign="top">
				<th scope="row"><?php echo $setting['name']; ?></th>
				<td>
					<?php echo $this->settings_field( $setting ); ?>
					<?php if( isset( $setting['description'] ) && !in_array( $setting['type'], array( 'message', 'toggle' ) ) ) { ?>
						<p class="description"><?php echo $setting['description']; ?></p>
					<?php } ?>
				</td>
			</tr>
				
		<?php }

		/**
		 * Create the plugin settings page.
		 */ 
		public function settings_page() { ?>

			<div class="wrap">
				<h1>
					<?php printf( __( '%s Settings', 'wordboot' ), $this->plugin['name'] ) ?>
				</h1>

				<form method="post" action="options.php">

					<?php foreach( $this->settings as $tab ) {
						settings_fields( $this->plugin['id'] . '_' . $tab['id'] );
						do_settings_sections( $this->plugin['id'] . '_' . $tab['id'] );
					} ?>

					<table class="form-table">

						<?php if( isset( $_GET['tab'] ) ) {
							$active = $_GET['tab'];
						} else {
							$active = $this->settings[0]['id'];
						}

						if( count( $this->settings ) > 1 ) {
							$this->display_tabs( $active );
						}

						foreach( $this->settings as $tab ){
							if( $tab['id'] === $active ) {
								foreach( $tab['settings'] as $setting ) {
									$this->display_setting( $setting );
								}
							}
						} ?>

					</table>
					
					<?php submit_button( __( 'Save Changes', 'wordboot' ) ); ?> &nbsp;

				</form>
			</div>

		<?php }

		/**
		 * Generate the field for the settings page.
		 */
		public function settings_field( $setting ) {

			$type = $setting['type'];

			if( in_array( $type, array( 'text', 'number', 'url', 'tel', 'email' ) ) ){

				return '<input type="' . $type . '" name="' . $setting['id'] . '" class="regular-text" value="' . esc_attr( get_option( $setting['id'] ) ) . '" />';

			} else {

				switch( $setting['type'] ) {

					case 'textarea':
						return '<textarea name="' . $setting['id'] . '" class="regular-text" rows="3">' . esc_attr( get_option( $setting['id'] ) ) . '</textarea>';
					break;

					case 'toggle':
						$markup  = '<label>';
							$markup .= '<input type="checkbox" name="' . $setting['id'] . '" value="1"' . checked( '1', get_option( $setting['id'] ), false ) . ' />';
							$markup .= $setting['description'];
						$markup .= '</label>';
						return $markup;
					break;

					case 'checkbox':
						$markup = '<div>';
						foreach ( $setting['options'] as $option ) {
							$markup .= '<p>';
								$markup .= '<label>';
									$markup .= '<input type="checkbox" name="' . $setting['id'] . '[]" id="' . $setting['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( ( in_array( $option['id'], (array)get_option( $setting['id'] ) ) ) ? $option['id'] : '', $option['id'], false ) . '>';
									$markup .= $option['title'];
								$markup .= '</label>';
							$markup .= '</p>';
						}
						$markup .= '</div>';
						return $markup;
					break;

					case 'radio':
						$markup = '<div>';
						foreach( $setting['options'] as $option ) {
							$markup .= '<p>';
								$markup .= '<label>';
									$markup .= '<input type="radio" name="' . $setting['id'] . '" id="' . $setting['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( get_option( $setting['id'] ), $option['id'], false ) . '>';
									$markup .= $option['title'];
								$markup .= '</label>';
							$markup .= '</p>';
						}
						$markup .= '</div>';
						return $markup;
					break;

					case 'select':
						$markup = '<select name="' . $setting['id'] . '">';
						foreach( $setting['options'] as $option ) {
							$markup .= '<option value="' . $option['id'] . '"' . selected( get_option( $setting['id'] ), $option['id'], false ) . '>' . $option['name'] . '</option>';
						}
						$markup .= '</select>';
						return $markup;
					break;

					case 'message':
						return '<div class="message">' . esc_html__( $setting['description'] ) . '</div>';
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

		}

		/**
		 * Register the settings.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/register_setting
		 */ 
		public function add_settings() {

			foreach( $this->settings as $tab ) {

				foreach( $tab['settings'] as $setting ) {

					$args = array();

					if( isset( $setting['default'] ) ) {
						$args['default'] = $setting['default'];
					}

					register_setting( $this->plugin['id'] . '_' . $tab['id'], $setting['id'], $args );

				}

			}

		}

	}

}
