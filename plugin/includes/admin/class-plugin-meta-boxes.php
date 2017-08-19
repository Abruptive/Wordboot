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

if( ! class_exists( 'Plugin_Meta_Boxes' ) ) {
	
	class Plugin_Meta_Boxes {

		/**
		 * The meta boxes to be registered.
		 *
		 * @var    object    $meta_boxes
		 */
		private $meta_boxes;

		/**
		 * Counter that keeps track of the meta boxes.
		 *
		 * @var    int    $counter
		 */
		private $counter = -1;

		/**
		 * Construct the class.
		 */
		public function __construct() {

			// Initialize the meta boxes.
			$this->meta_boxes = array(
				array(
					'id'       => 'box',
					'title'    => __( 'Plugin Meta Box', 'plugin' ),
					'fields'   => array(
						array(
							'id'      => 'text_field',
							'name'    => __( 'Text Field', 'plugin' ),
							'type'    => 'text',
						),
						array(
							'id'      => 'readonly_field',
							'name'    => __( 'Readonly Field', 'plugin' ),
							'type'    => 'readonly',
							'default' => 0
						)
					),
					'screen'   => array( 'item' ),
					'context'  => 'normal',
					'priority' => 'default',
				)
			);

		}

		/**
		 * Register the plugin meta boxes.
		 *
		 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
		 */
		public function add_meta_boxes() {
		
			foreach( $this->meta_boxes as $meta_box ) {

				// Increment the class meta counter.
				$this->counter += 1;
				
				// Add the meta box.
				add_meta_box( 
					$meta_box['id'] . '_meta_box', 
					$meta_box['title'], 
					array( $this, 'meta_box' ), 
					$meta_box['screen'], 
					$meta_box['context'], 
					$meta_box['priority'] 
				);

			}

		}

		/**
		 * Register the meta box callbacks.
		 * 
		 * @param    int    $post    The post id to register the meta box for.
		 */
		public function meta_box( $post ) {

			// Retrieve the current values.
			$meta_box = $this->meta_boxes[ $this->counter ];

			// Make sure the form request comes from WordPress
			wp_nonce_field( basename( __FILE__ ), $meta_box['id'] . '_meta_box_nonce' );

			// Get the current data from the database.
			foreach( $meta_box['fields'] as $field ) { 

				// Attempt to get the current field value.
				$value = get_post_meta( $post->ID, '_' . $meta_box['id'] . '_' . $field['id'], true ); 
				
				// If none is set, fallback to default for readonly fields.
				if( empty( $value ) && isset( $field['default'] ) ) {
					update_post_meta( $post->ID, '_' . $meta_box['id'] . '_' . $field['id'], $field['default'] );
					$value = get_post_meta( $post->ID, '_' . $meta_box['id'] . '_' . $field['id'], true );
				} ?>

				<div class="field">
					<p class="label">
						<label for="<?php echo $field['id'] ?>">
							<strong><?php echo $field['name'] ?></strong>
						</label>
					</p>	
					<?php echo $this->meta_box_field( $field, $value ); ?>
				</div>

			<?php }

		}

		/**
		 * Render an individual meta box field.
		 * 
		 * @param    object    $field    The meta box field.
		 * @param    string    $value    The current field value.
		 */
		public function meta_box_field( $field, $value ) {

			switch( $field['type'] ) {

				case 'text':
					return '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat" value="' . esc_attr( $value ) . '">';
				break;

				case 'readonly':
					return esc_attr( $value );
				break;

				default:
					return '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat" value="' . esc_attr( $value ) . '">';
				break;
				
			}

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

			// Check user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ){
				return;
			}

			// Check if there's a request for the custom meta boxes and update.
			foreach( $this->meta_boxes as $meta_box ) {

				if ( !isset( $_POST[ $meta_box['id'] . '_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ $meta_box['id'] . '_meta_box_nonce' ], basename( __FILE__ ) ) ){
					return;
				}

				foreach( $meta_box['fields'] as $field ) {
					if ( isset( $_REQUEST[ $field['id'] ] ) ) {	
						update_post_meta( $post_id, '_' . $meta_box['id'] . '_' . $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );
					}
				}
				
			}

		}

	}

}
