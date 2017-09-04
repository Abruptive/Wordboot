<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the custom post type(s).
 *
 * @package       Wordboot
 * @subpackage    Wordboot/admin
 * @author        Alexandru Doda <https://alexandru.co>
 */

if( ! class_exists( 'Wordboot_Post_Types' ) ) {

	class Wordboot_Post_Types {

		/**
		 * Register the "item" custom post type.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/register_post_type
		 */
		public function register_type_item() {

			$labels = array(
				'name'                  => _x( 'Items', 'Post Type General Name', 'wordboot' ),
				'singular_name'         => _x( 'Item', 'Post Type Singular Name', 'wordboot' ),
				'menu_name'             => __( 'Items', 'wordboot' ),
				'name_admin_bar'        => __( 'Item', 'wordboot' ),
				'archives'              => __( 'Item Archives', 'wordboot' ),
				'attributes'            => __( 'Item Attributes', 'wordboot' ),
				'parent_item_colon'     => __( 'Parent Item:', 'wordboot' ),
				'all_items'             => __( 'All Items', 'wordboot' ),
				'add_new_item'          => __( 'Add New Item', 'wordboot' ),
				'add_new'               => __( 'Add New', 'wordboot' ),
				'new_item'              => __( 'New Item', 'wordboot' ),
				'edit_item'             => __( 'Edit Item', 'wordboot' ),
				'update_item'           => __( 'Update Item', 'wordboot' ),
				'view_item'             => __( 'View Item', 'wordboot' ),
				'view_items'            => __( 'View Items', 'wordboot' ),
				'search_items'          => __( 'Search Item', 'wordboot' ),
				'not_found'             => __( 'Not found', 'wordboot' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'wordboot' ),
				'featured_image'        => __( 'Featured Image', 'wordboot' ),
				'set_featured_image'    => __( 'Set featured image', 'wordboot' ),
				'remove_featured_image' => __( 'Remove featured image', 'wordboot' ),
				'use_featured_image'    => __( 'Use as featured image', 'wordboot' ),
				'insert_into_item'      => __( 'Insert into item', 'wordboot' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'wordboot' ),
				'items_list'            => __( 'Items list', 'wordboot' ),
				'items_list_navigation' => __( 'Items list navigation', 'wordboot' ),
				'filter_items_list'     => __( 'Filter items list', 'wordboot' )
			);

			$supports = array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'custom-fields',
				'comments',
				'revisions',
				'post-formats',
			);
			
			$args = array(
				'labels'                => $labels,
				'description'           => __( 'Item Description', 'wordboot' ),
				'public'                => true,
				'hierarchical'          => false,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'show_in_nav_menus'     => true,
				'show_in_admin_bar'     => true,
				'show_in_rest'          => false,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-tag',
				'capability_type'       => 'post',
				'supports'              => $supports,
				'taxonomies'            => array(),
				'has_archive'           => 'items',
				'can_export'            => true,
				'rewrite'               => array( 
					'slug'       => 'item', 
					'with_front' => false,
					'pages'      => true,
				),
			);

			register_post_type( 'item', $args );

		}

		/**
		 * Register the "item_category" taxonomy.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/register_taxonomy
		 */
		public function register_taxonomy_item_category() {

			$labels = array(
				'name'                       => _x( 'Categories', 'Taxonomy General Name', 'wordboot' ),
				'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'wordboot' ),
				'menu_name'                  => __( 'Categories', 'wordboot' ),
				'all_items'                  => __( 'All Categories', 'wordboot' ),
				'edit_item'                  => __( 'Edit Category', 'wordboot' ),
				'view_item'                  => __( 'View Tag' ),
				'update_item'                => __( 'Update Category', 'wordboot' ),
				'add_new_item'               => __( 'Add New Category', 'wordboot' ),
				'new_item_name'              => __( 'New Category Name', 'wordboot' ),
				'parent_item'                => __( 'Parent Category', 'wordboot' ),
				'parent_item_colon'          => __( 'Parent Category:', 'wordboot' ),
				'search_items'               => __( 'Search Categories', 'wordboot' ),
				'popular_items'              => __( 'Popular Categories', 'wordboot' ),
				'separate_items_with_commas' => __( 'Separate Categories with commas', 'wordboot' ),
				'add_or_remove_items'        => __( 'Add or remove Categories', 'wordboot' ),
				'choose_from_most_used'      => __( 'Choose from the most used Categories', 'wordboot' ),
				'not_found'                  => __( 'No Categories found.', 'wordboot' )
			);

			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_rest'       => false,
				'show_tagcloud'      => true,
				'show_in_quick_edit' => true,
				'show_admin_column'  => false,
				'description'        => __( 'Category Description', 'wordboot' ),
				'hierarchical'       => true,
				'query_var'          => 'item_category',
				'rewrite'            => array( 
					'slug'       => 'item-category',
					'with_front' => true
				)
			);
			
			register_taxonomy( 'item_category', array( 'item' ), $args );

		}

	}

}
