<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Metabun
 * 
 * This class adds custom meta boxes for post types.
 *
 * @version    0.5.0
 * @link       https://github.com/AlexandruDoda/Metabun
 * @author     Alexandru Doda <https://alexandru.co>
 */

if( ! class_exists( 'Metabun' ) ) {
	
	class Metabun {

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
		 * A field container used as a temporary variable.
		 *
		 * @var    object    $field
		 */
		private $field;

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

			// Enqueue the scripts and stylesheets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Add the meta boxes to the post admin page.
			add_action( 'add_meta_boxes_' . $post_type, array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post_' . $post_type, array( $this, 'save_meta_boxes' ) );

		}

		/**
		 * Enqueue the admin stylesheets.
		 *
		 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style
		 */
		public function enqueue_styles() {

			// Enqueue the color picker.
			wp_enqueue_style( 'wp-color-picker');
			
			// Enqueue and localize the admin plugin stylesheet.
			wp_enqueue_style( 'metabun', plugin_dir_url( __FILE__ ) . 'assets/css/metabun.css', array(), null, 'all' );

		}

		/**
		 * Enqueue the admin scripts.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script
		 */
		public function enqueue_scripts() {

			// Enqueue the media library.
			if ( !did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			// Enqueue the color picker.
			wp_enqueue_script( 'wp-color-picker');

			// Enqueue and localize the admin plugin script.
			wp_enqueue_script( 'metabun', plugin_dir_url( __FILE__ ) . 'assets/js/metabun.js', array( 'jquery' ), null, true );

		}

		/**
		 * Register the plugin meta boxes.
		 *
		 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
		 */
		public function add_meta_boxes() {
		
			foreach( $this->meta_boxes as $meta_box ) {

				if( isset( $meta_box['description'] ) ) {
					$meta_box['title'] .= ' <small>' . $meta_box['description'] . '</small>';
				}

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
		 */
		public function meta_box( $post ) {

			// Retrieve the current values.
			$meta_box = $this->meta_boxes[ $this->counter ];
			
			// Make sure the form request comes from WordPress.
			wp_nonce_field( basename( __FILE__ ), $meta_box['id'] . '_meta_box_nonce' );

			// Get the current data from the database.
			foreach( $meta_box['fields'] as $field ) { 

				// Extend the field ID with the meta box.
				$field['id'] = $meta_box['id'] . '_' . $field['id'];
				
				// Retrieve the current field value.
				$field['value'] = get_post_meta( $post->ID, '_' . $field['id'], true );

				// If none is set, fallback to default.
				if( empty( $field['value'] ) && isset( $field['default'] ) && !in_array( $field['type'], array( 'checkbox', 'toggle' ) ) ) {
					update_post_meta( $post->ID, '_' . $field['id'], $field['default'] );
					$field['value'] = get_post_meta( $post->ID, '_' . $field['id'], true );
				}
				
				// Format the field for checkboxes and toggles.
				if( in_array( $field['type'], array( 'checkbox', 'toggle', 'repeater' ) ) ) {
					$field['value'] = ( $field['value'] ) ? $field['value'] : array();
				} 
				
				// Store the field to the class instance.
				$this->field = $field; ?>

				<div id="<?php echo $meta_box['id'] ?>_<?php echo $field['id'] ?>" class="field field-<?php echo $field['type']; ?>">

					<p class="label">
						<label for="<?php echo $field['id'] ?>">
							<strong><?php echo $field['title'] ?></strong>
						</label>
					</p>

					<?php if( !in_array( $field['type'], array( 'toggle' ) ) && isset( $field['description'] ) ): ?>
						<p class="description">
							<?php echo $field['description']; ?>
						</p>
					<?php endif; ?>

					<span class="value">
						<?php echo $this->field(); ?>
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

					// Extend the field ID with the meta box.
					$field['id'] = $meta_box['id'] . '_' . $field['id'];

					// Check if the meta is set and process it.
					if( isset( $_POST[ $field['id'] ] ) ) {
							
						if( $field['type'] == 'checkbox' || $field['type'] == 'toggle' || $field['type'] == 'repeater' ) {
							update_post_meta( $post_id, '_' . $field['id'], array_map( 'sanitize_text_field', (array) $_POST[ $field['id'] ] ) );
						} else {
							update_post_meta( $post_id, '_' . $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );
						}

					} else {
						
						delete_post_meta( $post_id, '_' . $field['id'] );

					}

				}

			}

		}

		/**
		 * Field
		 *
		 * @return    string    The field.
		 */
		private function field() {
			
			switch( $this->field['type'] ) {

				case 'textarea':
					return $this->textarea();
				break;

				case 'readonly':
					return $this->readonly();
				break;

				case 'select':
					return $this->select();
				break;

				case 'toggle':
					return $this->toggle();
				break;

				case 'checkbox':
					return $this->checkbox();
				break;

				case 'radio':
					return $this->radio();
				break;

				case 'editor':
					return $this->editor();
				break;

				case 'image':
					return $this->upload();
				break;

				case 'file':
					return $this->upload();
				break;

				case 'color':
					return $this->color();
				break;

				case 'repeater':
					return $this->repeater();
				break;

				case 'post':
					return $this->post();
				break;

				case 'taxonomy':
					return $this->taxonomy();
				break;

				default:
					return $this->text();
				break;
				
			}

		}

		/**
		 * Field: Single Line Text
		 *
		 * @return    string    The single line text field.
		 */
		private function text() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Format the field type if necessary.
			if( $field['type'] == 'phone' ) {
				$field['type'] = 'tel';
			}

			// Return the input.
			return '<input type="' . $field['type'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat" value="' . $field['value'] . '">';

		}

		/**
		 * Field: Multi Line Text
		 *
		 * @return    string    The multi line text field.
		 */
		private function textarea() {

			// Fetch the field from the class instance.
			$field = $this->field;
			
			// Return the input.
			return '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat">' . esc_attr( $field['value'] ) . '</textarea>';

		}

		/**
		 * Field: Read-only
		 *
		 * @return    string    The read-only field.
		 */
		private function readonly() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Return the input.
			return esc_attr( $field['value'] );

		}

		/**
		 * Field: Select Box
		 *
		 * @return    string    The select field.
		 */
		private function select() {
			
			// Fetch the field from the class instance.
			$field = $this->field;
			
			// Compose the markup.
			$markup = '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat">';
			foreach( $field['options'] as $option ) {
				$markup .= '<option value="' . $option['id'] . '"' . selected( $field['value'], $option['id'], false ) . '>' . $option['title'] . '</option>';
			}
			$markup .= '</select>';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Toggle
		 *
		 * @return    string    The toggle field.
		 */
		private function toggle() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Compose the markup.
			$markup  = '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . $field['id'] . '"' . checked( ( in_array( $field['id'], $field['value'] ) ) ? $field['id'] : '', $field['id'], false ) . '>';
			$markup .= '<label for="' . $field['id'] . '">' . $field['description'] . '</label>';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Checkbox
		 *
		 * @return    string    The checkbox field.
		 */
		private function checkbox() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Compose the markup.
			$markup = '';
			foreach ( $field['options'] as $option ) {
				$markup .= '<p>';
					$markup .= '<label>';
						$markup .= '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( ( in_array( $option['id'], $field['value'] ) ) ? $option['id'] : '', $option['id'], false ) . '>';
						$markup .= $option['title'];
					$markup .= '</label>';
				$markup .= '</p>';
			}

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Radio
		 *
		 * @return    string    The radio field.
		 */
		private function radio() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Compose the markup.
			$markup = '';
			foreach( $field['options'] as $option ) {
				$markup .= '<p>';
					$markup .= '<label>';
						$markup .= '<input type="radio" name="' . $field['id'] . '" id="' . $field['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( $field['value'], $option['id'], false ) . '>';
						$markup .= $option['title'];
					$markup .= '</label>';
				$markup .= '</p>';
			}

			// Return the input.
			return $markup;

		}

		/**
		 * Field: WYSIWYG Editor
		 *
		 * @return    string    The editor field.
		 */
		private function editor() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Set a default arguments array.
			$field['args'] = isset( $field['args'] ) ? $field['args'] : array();

			// Return the input.
			return wp_editor( $field['value'], $field['id'], $field['args'] );

		}

		/**
		 * Field: Upload
		 *
		 * @return    string    The upload field.
		 */
		private function upload() {
			
			// Fetch the field from the class instance.
			$field = $this->field;

			// Setup the default instance.
			$instance = array(
				'class'   => 'upload',
				'display' => 'inline-block'
			);

			// Configure the upload field based on type.
			if( $field['type'] == 'file' ) {

				// Initialize the labels array.
				$labels = array(
					'select' => __( 'Select File', 'metabun' ),
					'remove' => __( 'Remove File', 'metabun' )
				);

				// Check if a file already exists and setup the instance.
				if( $attachment = wp_prepare_attachment_for_js( $field['value'] ) ) {
					$instance['content'] = '<span class="selected">' . $attachment['title'] . ' (' . $attachment['filesizeHumanReadable'] . ')' . '</span>';
				}

			} elseif( $field['type'] == 'image' ) {

				// Add the extended field argument defaults.
				$field['args']['image_size'] = isset( $field['args']['image_size'] ) ? $field['args']['image_size'] : 'large';

				// Check if an image already exists and setup the instance.
				if( wp_attachment_is_image( $field['value'] ) ) {
					$instance['content'] = wp_get_attachment_image( $field['value'], $field['args']['image_size'], false );
				}

				// Initialize the labels array.
				$labels = array(
					'select' => __( 'Select Image', 'metabun' ),
					'remove' => __( 'Remove Image', 'metabun' )
				);

			}

			// If no current file, setup the defaults.
			if( !isset( $instance['content'] ) ) {
				$instance = array(
					'class'   => 'upload button',
					'content' => $labels['select'],
					'display' => 'none'
				);
			}

			// Setup the upload settings array.
			$instance['settings'] = json_encode( array( 
				'type'  => $field['type'], 
				'title' => $labels['select'],
				'size'  => isset( $field['args']['image_size'] ) ? $field['args']['image_size'] : null
			) );
			
			// Compose the markup.
			$markup  = '<a href="#" class="remove button" style="display:' . $instance['display'] . '">';
			$markup .= 		$labels['remove'];
			$markup .= '</a>';
			$markup .= "<a href='#' class='" . $instance['class'] . "' data-settings='" . $instance['settings'] . "'>";
			$markup .= 		$instance['content'];
			$markup .= '</a>';
			$markup .= '<input type="hidden" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $field['value'] . '" />';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Color Picker
		 *
		 * @return    string    The color picker field.
		 */
		private function color() {
			
			// Fetch the field from the class instance.
			$field = $this->field;

			// Compose the markup.
			$markup = '<input type="hidden" class="input-color" name="' . $field['id'] . '" value="' . $field['value'] . '">';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Repeater
		 *
		 * @return    string    The repeater field.
		 */
		private function repeater() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Compose the markup.
			$markup = '
			<table class="repeater wp-list-table">
				<tbody>
					<tr class="repeater-template hidden">
						<td>
							<input type="text" data-name="' . $field['id'] . '[]" class="regular-text" value="">
						</td>
						<td>
							<button class="button" data-repeater="remove" tabindex="-1">
								' . __( 'Remove', 'metabun' ) . '
							</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2">
							<button class="button" data-repeater="add">
								' . __( 'Add Item', 'metabun' ) . '
							</button>
						</td>
					</tr>
					<input type="hidden" class="repeater-data" value=' . "'" . json_encode( $field['value'] ) . "'" . '>
				</tfoot>
			</table>';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Post
		 * 
		 * @return    string    The post field.
		 */
		private function post() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Setup the query attributes.
			$args = array(
				'posts_per_page' => -1
			);

			// Extend the query.
			$args['post_type'] = isset( $field['args']['post_type'] ) ? $field['args']['post_type'] : 'post';

			// Check if any posts are found and process the field.
			if( !empty( $posts = get_posts( $args ) ) ) {
				
				// Compose the markup.
				$markup = '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat">';
				foreach( $posts as $post ) {
					$markup .= '<option value="' . $post->ID . '"' . selected( $field['value'], $post->ID, false ) . '>' . $post->post_title . '</option>';
				}
				$markup .= '</select>';

				// Return the input.
				return $markup;

			} else {

				// Get the post type label.
				$labels = get_post_type_labels( get_post_type_object( $args['post_type'] ) );

				// Return an error.
				return sprintf( __( 'No %s could be found in the database.', 'metabun' ), strtolower( $labels->name ) );

			}

		}

		/**
		 * Field: Taxonomy
		 * 
		 * @return    string    The taxonomy field.
		 */
		private function taxonomy() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Extend the query.
			$args['taxonomy']   = isset( $field['args']['taxonomy'] ) ? $field['args']['taxonomy'] : 'category';

			// Check if the taxonomy exists.
			if( taxonomy_exists( $args['taxonomy'] ) ) {

				// Check if any terms are found and process the field.
				if( !empty( $terms = get_terms( $args['taxonomy'] ) ) ) {

					// Compose the markup.
					$markup = '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat">';
					foreach( $terms as $term ) {
						$markup .= '<option value="' . $term->term_id . '"' . selected( $field['value'], $term->term_id, false ) . '>' . $term->name . '</option>';
					}
					$markup .= '</select>';

					// Return the input.
					return $markup;

				} else {

					// Get the post type label.
					$labels = get_taxonomy_labels( get_taxonomy( $args['taxonomy'] ) );

					// Return an error if no terms could be found.
					return sprintf( __( 'No %s could be found in the database.', 'metabun' ), strtolower( $labels->name ) );

				}

			} else {

				// Return an error if the taxonomy does not exist.
				return sprintf( __( 'A taxonomy identified with "%s" does not exist.', 'metabun' ), strtolower( $args['taxonomy'] ) );

			}

		}

	}

}
