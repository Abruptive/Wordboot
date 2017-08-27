<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ALXWP: Meta Boxes
 * 
 * This class adds custom meta boxes for post types.
 *
 * @version    1.0.0
 * @link       https://github.com/AlexandruDoda/ALXWP-Meta
 * @author     Alexandru Doda <https://alexandru.co>
 */

if( ! class_exists( 'ALXWP_Meta' ) ) {
	
	class ALXWP_Meta {

		/**
		 * The meta boxes to be registered.
		 *
		 * @var    object    $meta_boxes
		 */
		private $meta_boxes;

		/**
		 * The post type to have meta boxes registered for.
		 *
		 * @var    object    $post_type
		 */
		private $post_type;

		/**
		 * Counter that keeps track of the meta boxes.
		 *
		 * @var    int    $counter
		 */
		private $counter = 0;

		/**
		 * Construct the class.
		 */
		public function __construct( $post_type, $meta_boxes ) {

			// Initialize the meta boxes.
			$this->meta_boxes = $meta_boxes;

			// Initialize the post type.
			$this->post_type = $post_type;

			// Add the meta boxes to the post admin page.
			add_action( 'add_meta_boxes_' . $post_type, array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post_' . $post_type, array( $this, 'save_meta_boxes' ) );

		}

		/**
		 * Register the plugin meta boxes.
		 *
		 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
		 */
		public function add_meta_boxes() {
		
			foreach( $this->meta_boxes as $meta_box ) {
				add_meta_box( 
					$meta_box['id'] . '_meta_box', 
					$meta_box['title'], 
					array( $this, 'meta_box' ), 
					$this->post_type, 
					$meta_box['context'], 
					$meta_box['priority'] 
				);
			}

		}

		/**
		 * Generate the markup for a meta box.
		 *
		 * @param     int    $post    The post id to register the meta box for.
		 * @return    void
		 */
		public function meta_box( $post ) {

			// Retrieve the current values.
			$meta_box = $this->meta_boxes[ $this->counter ];
			
			// Make sure the form request comes from WordPress.
			wp_nonce_field( basename( __FILE__ ), $meta_box['id'] . '_meta_box_nonce' );

			// Get the current data from the database.
			foreach( $meta_box['fields'] as $field ) { 
				
				// Retrieve the current field value.
				$value = get_post_meta( $post->ID, '_' . $field['id'], true );

				// If none is set, fallback to default.
				if( empty( $value ) && isset( $field['default'] ) && !in_array( $field['type'], array( 'checkbox', 'toggle' ) ) ) {
					update_post_meta( $post->ID, '_' . $field['id'], $field['default'] );
					$value = get_post_meta( $post->ID, '_' . $field['id'], true );
				}
				
				// Format the field for checkboxes and toggles.
				if( $field['type'] == 'checkbox' || $field['type'] == 'toggle' ) {
					$value = ( $value ) ? $value : array();
				} ?>

				<div id="<?php echo $meta_box['id'] ?>_<?php echo $field['id'] ?>" class="field">

					<p class="label">
						<label for="<?php echo $field['id'] ?>">
							<strong><?php echo $field['title'] ?></strong>
						</label>
					</p>

					<span class="value">
						<?php echo $this->field( $field, $value ); ?>
					</span>

				</div>

			<?php }

			// Increment the class meta counter.
			$this->counter += 1;

		}

		/**
		 * Store custom field meta box data
		 *
		 * @link     https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
		 * @param    int    $post_id    The post ID.
		*/
		public function save_meta_boxes( $post_id ) {
			
			// Do not run if it's an autosave.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
				return;
			}
			
			// Check if the user is allowed to edit the post.
			if ( ! current_user_can( 'edit_post', $post_id ) ){
				return;
			}

			// Process each meta box.
			foreach( $this->meta_boxes as $meta_box ) {

				// Verify the meta box nonce.
				if ( !isset( $_POST[ $meta_box['id'] . '_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ $meta_box['id'] . '_meta_box_nonce' ], basename( __FILE__ ) ) ){
					return;
				}

				// Save the data based on field type.
				foreach( $meta_box['fields'] as $field ) {

					if( $field['type'] == 'checkbox' || $field['type'] == 'toggle' ) {

						/**
						 * Checkbox & Toggle
						 */
						
						if( isset( $_POST[ $field['id'] ] ) ){
							update_post_meta( $post_id, '_' . $field['id'], array_map( 'sanitize_text_field', (array) $_POST[ $field['id'] ] ) );
						} else {
							delete_post_meta( $post_id, '_' . $field['id'] );
						}

					} else {

						/**
						 * Default
						 */
						
						if ( isset( $_REQUEST[ $field['id'] ] ) ) {
							update_post_meta( $post_id, '_' . $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );
						}

					}

				}

			}

		}

		/**
		 * Field
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The field.
		 */
		public function field( $field, $value ) {
			
			switch( $field['type'] ) {

				case 'textarea':
					return $this->textarea( $field, $value );
				break;

				case 'readonly':
					return $this->readonly( $field, $value );
				break;

				case 'select':
					return $this->select( $field, $value );
				break;

				case 'toggle':
					return $this->toggle( $field, $value );
				break;

				case 'checkbox':
					return $this->checkbox( $field, $value );
				break;

				case 'radio':
					return $this->radio( $field, $value );
				break;

				default:
					return $this->text( $field, $value );
				break;
				
			}

		}

		/**
		 * Field: Single Line Text
		 * 
		 * This field is used for text, phone, email and url inputs.
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The single line text field.
		 */
		public function text( $field, $value ) {

			// Format the field type if necessary.
			if( $field['type'] == 'phone' ) {
				$field['type'] = 'tel';
			}

			// Return the input.
			return '<input type="' . $field['type'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat" value="' . $value . '">';

		}

		/**
		 * Field: Multi Line Text
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The multi line text field.
		 */
		public function textarea( $field, $value ) {
			
			return '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat">' . esc_attr( $value ) . '</textarea>';

		}

		/**
		 * Field: Read-only
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The read-only field.
		 */
		public function readonly( $field, $value ) {

			return esc_attr( $value );

		}

		/**
		 * Field: Select Box
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The select field.
		 */
		public function select( $field, $value ) {
			
			$markup = '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
			foreach( $field['options'] as $option ) {
				$markup .= '<option value="' . $option['id'] . '"' . selected( $value, $option['id'], false ) . '>' . $option['title'] . '</option>';
			}
			$markup .= '</select>';
			return $markup;

		}

		/**
		 * Field: Toggle
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The toggle field.
		 */
		public function toggle( $field, $value ) {

			$markup  = '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . $field['id'] . '"' . checked( ( in_array( $field['id'], $value ) ) ? $field['id'] : '', $field['id'], false ) . '>';
			$markup .= '<label for="' . $field['id'] . '">' . $field['description'] . '</label>';

			return $markup;

		}

		/**
		 * Field: Checkbox
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The checkbox field.
		 */
		public function checkbox( $field, $value ) {

			$markup = '';

			foreach ( $field['options'] as $option ) {

				$markup .= '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( ( in_array( $option['id'], $value ) ) ? $option['id'] : '', $option['id'], false ) . '>';
				$markup .= $option['title'];
				$markup .= '<br />';

			}

			return $markup;

		}

		/**
		 * Field: Radio
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The radio field.
		 */
		public function radio( $field, $value ) {

			$markup = '';

			foreach( $field['options'] as $option ) {
				$markup .= '<input type="radio" name="' . $field['id'] . '" id="' . $field['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( $value, $option['id'], false ) . '>';
				$markup .= $option['title'] . '<br />';
			}

			return $markup;

		}

	}

}
