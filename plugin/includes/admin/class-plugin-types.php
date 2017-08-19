<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the custom post type(s).
 *
 * @package       Plugin
 * @subpackage    Plugin/admin
 * @author        Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Post_Types' ) ) {

	class Plugin_Post_Types {

		/**
		 * Register the "item" custom post type.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/register_post_type
		 */
		public function register_type_item() {

			$labels = array(
				'name'                  => _x( 'Items', 'Post Type General Name', 'plugin' ),
				'singular_name'         => _x( 'Item', 'Post Type Singular Name', 'plugin' ),
				'menu_name'             => __( 'Items', 'plugin' ),
				'name_admin_bar'        => __( 'Item', 'plugin' ),
				'archives'              => __( 'Item Archives', 'plugin' ),
				'attributes'            => __( 'Item Attributes', 'plugin' ),
				'parent_item_colon'     => __( 'Parent Item:', 'plugin' ),
				'all_items'             => __( 'All Items', 'plugin' ),
				'add_new_item'          => __( 'Add New Item', 'plugin' ),
				'add_new'               => __( 'Add New', 'plugin' ),
				'new_item'              => __( 'New Item', 'plugin' ),
				'edit_item'             => __( 'Edit Item', 'plugin' ),
				'update_item'           => __( 'Update Item', 'plugin' ),
				'view_item'             => __( 'View Item', 'plugin' ),
				'view_items'            => __( 'View Items', 'plugin' ),
				'search_items'          => __( 'Search Item', 'plugin' ),
				'not_found'             => __( 'Not found', 'plugin' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'plugin' ),
				'featured_image'        => __( 'Featured Image', 'plugin' ),
				'set_featured_image'    => __( 'Set featured image', 'plugin' ),
				'remove_featured_image' => __( 'Remove featured image', 'plugin' ),
				'use_featured_image'    => __( 'Use as featured image', 'plugin' ),
				'insert_into_item'      => __( 'Insert into item', 'plugin' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'plugin' ),
				'items_list'            => __( 'Items list', 'plugin' ),
				'items_list_navigation' => __( 'Items list navigation', 'plugin' ),
				'filter_items_list'     => __( 'Filter items list', 'plugin' )
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
				'description'           => __( 'Item Description', 'plugin' ),
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
				'name'                       => _x( 'Categories', 'Taxonomy General Name', 'plugin' ),
				'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'plugin' ),
				'menu_name'                  => __( 'Categories', 'plugin' ),
				'all_items'                  => __( 'All Categories', 'plugin' ),
				'edit_item'                  => __( 'Edit Category', 'plugin' ),
				'view_item'                  => __( 'View Tag' ),
				'update_item'                => __( 'Update Category', 'plugin' ),
				'add_new_item'               => __( 'Add New Category', 'plugin' ),
				'new_item_name'              => __( 'New Category Name', 'plugin' ),
				'parent_item'                => __( 'Parent Category', 'plugin' ),
				'parent_item_colon'          => __( 'Parent Category:', 'plugin' ),
				'search_items'               => __( 'Search Categories', 'plugin' ),
				'popular_items'              => __( 'Popular Categories', 'plugin' ),
				'separate_items_with_commas' => __( 'Separate Categories with commas', 'plugin' ),
				'add_or_remove_items'        => __( 'Add or remove Categories', 'plugin' ),
				'choose_from_most_used'      => __( 'Choose from the most used Categories', 'plugin' ),
				'not_found'                  => __( 'No Categories found.', 'plugin' )
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
				'description'        => __( 'Category Description', 'plugin' ),
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
