<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Class.
 *
 * Loads dependencies, handles translation, registers admin and public hooks.
 * 
 * @package    Plugin
 * @subpackage Plugin/includes
 * @author     Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Main' ) ) {

	class Plugin_Main {

		/**
		 * The plugin variables container.
		 * 
		 * @var object $plugin
		 */
		private $plugin;
	
		/**
		 * The plugin loader that registers actions and filters.
		 *
		 * @var Plugin_Loader
		 */
		protected $loader;
	
		/**
		 * Construct the class.
		 * 
		 * @param    object    $plugin    The plugin variables.
		 */
		public function __construct( $plugin ) {
	
			$this->plugin = $plugin;
	
			$this->load_dependencies();
			$this->define_translation();
			$this->define_admin_hooks();
			$this->define_public_hooks();
	
		}
	
		/**
		 * Load the dependencies.
		 *
		 * Require all the classes and create an instance of the loader 
		 * which will be used to register the hooks with WordPress.
		 */
		private function load_dependencies() {
	
			/**
			 * Require the plugin loader and internationalization setup classes.
			 * 
			 * @see    Plugin_Loader    Orchestrates the hooks of the plugin.
			 * @see    Plugin_i18n      Defines internationalization functionality.
			 */
			require_once $this->plugin['path'] . 'includes/setup/class-plugin-loader.php';
			require_once $this->plugin['path'] . 'includes/setup/class-plugin-i18n.php';
	
			/**
			 * Require the admin classes.
			 * 
			 * @see    Plugin_Admin         Defines all hooks for the admin area.
			 * @see    Plugin_Settings      Defines the settings page and fields.
			 * @see    Plugin_Post_Types    Defines the custom post types.
			 * @see    Plugin_Meta_Boxes    Defines the custom meta boxes.
			 */
			require_once $this->plugin['path'] . 'includes/admin/class-plugin-admin.php';
			require_once $this->plugin['path'] . 'includes/admin/class-plugin-settings.php';
			require_once $this->plugin['path'] . 'includes/admin/class-plugin-types.php';
			require_once $this->plugin['path'] . 'includes/admin/class-plugin-meta-boxes.php';
	
			/**
			 * Require the public classes.
			 * 
			 * @see    Plugin_Public       Defines all hooks for the public side of the site.
			 * @see    Plugin_Ajax         Defines the public ajax functionality.
			 * @see    Plugin_Templates    Defines the public templates.
			 */
			require_once $this->plugin['path'] . 'includes/public/class-plugin-public.php';
			require_once $this->plugin['path'] . 'includes/public/class-plugin-ajax.php';
			require_once $this->plugin['path'] . 'includes/public/class-plugin-templates.php';
	
			/**
			 * Initialize the action & filter loader.
			 * 
			 * @see    Plugin_Loader    Defines the loader used to register actions and hooks.
			 */
			$this->loader = new Plugin_Loader();
	
		}
	
		/**
		 * The plugin translation.
		 * 
		 * @see    Plugin_i18n::load_plugin_textdomain()    Load the plugin text domain for translation.
		 */
		private function define_translation() {
			$i18n = new Plugin_i18n();
			$this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );
		}
	
		/**
		 * The admin actions and filters.
		 */
		private function define_admin_hooks() {
	
			/**
			 * Admin
			 * 
			 * @see    Plugin_Admin::enqueue_styles()     Enqueue the admin stylesheets.
			 * @see    Plugin_Admin::enqueue_scripts()    Enqueue the admin scripts.
			 * @see    Plugin_Admin::action_links()       Extend the default action links.
			 */
			$admin = new Plugin_Admin( $this->plugin );
			$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
			$this->loader->add_filter( 'plugin_action_links_' . $this->plugin['basename'], $admin, 'action_links' );

			/**
			 * Settings
			 * 
			 * @see    Plugin_Settings::add_settings_page()    Register the settings menu page.
			 * @see    Plugin_Settings::add_settings()         Register the settings.
			 */
			$settings = new Plugin_Settings( $this->plugin );
			$this->loader->add_action( 'admin_menu', $settings, 'add_settings_page' );
			$this->loader->add_action( 'admin_init', $settings, 'add_settings' );

			/**
			 * Custom Post Types
			 * 
			 * @see    Plugin_Post_Types::register_type_item()                 Register the "item" post type.
			 * @see    Plugin_Post_Types::register_taxonomy_item_category()    Register the "item_category" taxonomy.
			 */
			$post_types = new Plugin_Post_Types( $this->plugin );
			$this->loader->add_action( 'init', $post_types, 'register_type_item' );
			$this->loader->add_action( 'init', $post_types, 'register_taxonomy_item_category' );

			/**
			 * Meta Boxes
			 * 
			 * @see    Plugin_Meta_Boxes::add_meta_boxes()     Register the meta boxes.
			 * @see    Plugin_Meta_Boxes::save_meta_boxes()    Save the meta boxes.
			 */
			$meta_boxes = new Plugin_Meta_Boxes();
			$this->loader->add_action( 'add_meta_boxes', $meta_boxes, 'add_meta_boxes' );
			$this->loader->add_action( 'save_post', $meta_boxes, 'save_meta_boxes' );

		}
	
		/**
		 * The public actions and filters.
		 */
		private function define_public_hooks() {
	
			/**
			 * Public
			 * 
			 * @see    Plugin_Public::enqueue_styles()     Enqueue the public stylesheets.
			 * @see    Plugin_Public::enqueue_scripts()    Enqueue the public scripts.
			 */
			$public = new Plugin_Public( $this->plugin );
			$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );

			/**
			 * Templates
			 * 
			 * @see    Plugin_Templates::single_template_item()     Setup the single template for the 'item' custom post type.
			 * @see    Plugin_Templates::archive_template_item()    Setup the archive template for the 'item' custom post type.
			 */
			$templates = new Plugin_Templates( $this->plugin );
			$this->loader->add_filter( 'single_template', $templates, 'single_template_item' );
			$this->loader->add_filter( 'archive_template', $templates, 'archive_template_item' );

			/**
			 * AJAX
			 * 
			 * @see    Plugin_Ajax::wp_ajax_nopriv_callback()    Setup an example AJAX callback.
			 * @see    Plugin_Ajax::wp_ajax_callback()
			 */
			$ajax = new Plugin_Ajax( $this->plugin );
			$this->loader->add_action( 'wp_ajax_nopriv_callback', $ajax, 'callback' );
			$this->loader->add_action( 'wp_ajax_callback', $ajax, 'callback' );
	
		}
	
		/**
		 * The plugin hook loader.
		 * 
		 * @see    Plugin_Loader::run()    Register the filters and actions with WordPress.
		 */
		public function run() {
			$this->loader->run();
		}
	
	}		

}
