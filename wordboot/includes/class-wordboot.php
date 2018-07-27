<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Class.
 *
 * Loads dependencies, handles translation, registers admin and public hooks.
 * 
 * @package       Wordboot
 * @subpackage    Wordboot/includes
 * @author        Abruptive <https://abruptive.com>
 */

if( ! class_exists( 'Wordboot_Main' ) ) {

	class Wordboot_Main {

		/**
		 * The plugin variables container.
		 * 
		 * @var    object    $plugin
		 */
		private $plugin;
	
		/**
		 * The plugin loader that registers actions and filters.
		 *
		 * @var Wordboot_Loader
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
			 * @see    Wordboot_Loader         Orchestrates the hooks of the plugin.
			 * @see    Wordboot_Translation    Defines internationalization functionality.
			 */
			require_once $this->plugin['path'] . 'includes/setup/class-wordboot-loader.php';
			require_once $this->plugin['path'] . 'includes/setup/class-wordboot-translation.php';
	
			/**
			 * Require the admin classes.
			 * 
			 * @see    Wordboot_Admin         Defines all hooks for the admin area.
			 * @see    Wordboot_Settings      Defines the settings page and fields.
			 * @see    Wordboot_Post_Types    Defines the custom post types.
			 * @see    Wordboot_Meta          Defines the custom meta boxes.
			 */
			require_once $this->plugin['path'] . 'includes/admin/class-wordboot-admin.php';
			require_once $this->plugin['path'] . 'includes/admin/class-wordboot-settings.php';
			require_once $this->plugin['path'] . 'includes/admin/class-wordboot-types.php';
			require_once $this->plugin['path'] . 'includes/admin/class-wordboot-meta.php';
	
			/**
			 * Require the public classes.
			 * 
			 * @see    Wordboot_Public       Defines all hooks for the public side of the site.
			 * @see    Wordboot_Ajax         Defines the public ajax functionality.
			 * @see    Wordboot_Templates    Defines the public templates.
			 */
			require_once $this->plugin['path'] . 'includes/public/class-wordboot-public.php';
			require_once $this->plugin['path'] . 'includes/public/class-wordboot-ajax.php';
			require_once $this->plugin['path'] . 'includes/public/class-wordboot-templates.php';
	
			/**
			 * Initialize the action & filter loader.
			 * 
			 * @see    Wordboot_Loader    Defines the loader used to register actions and hooks.
			 */
			$this->loader = new Wordboot_Loader();
	
		}
	
		/**
		 * The plugin translation.
		 * 
		 * @see    Wordboot_Translation::load_textdomain()    Load the plugin text domain for translation.
		 */
		private function define_translation() {
			$translation = new Wordboot_Translation( $this->plugin );
			$this->loader->add_action( 'plugins_loaded', $translation, 'load_textdomain' );
		}
	
		/**
		 * The admin actions and filters.
		 */
		private function define_admin_hooks() {
	
			/**
			 * Admin
			 * 
			 * @see    Wordboot_Admin::enqueue_styles()     Enqueue the admin stylesheets.
			 * @see    Wordboot_Admin::enqueue_scripts()    Enqueue the admin scripts.
			 * @see    Wordboot_Admin::action_links()       Extend the default action links.
			 */
			$admin = new Wordboot_Admin( $this->plugin );
			$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
			$this->loader->add_filter( 'plugin_action_links_' . $this->plugin['basename'], $admin, 'action_links' );

			/**
			 * Settings
			 * 
			 * @see    Wordboot_Settings::add_settings_page()    Register the settings menu page.
			 * @see    Wordboot_Settings::add_settings()         Register the settings.
			 */
			$settings = new Wordboot_Settings( $this->plugin );
			$this->loader->add_action( 'admin_menu', $settings, 'add_settings_page' );
			$this->loader->add_action( 'admin_init', $settings, 'add_settings' );

			/**
			 * Custom Post Types
			 * 
			 * @see    Wordboot_Post_Types::register_type_item()                 Register the "item" post type.
			 * @see    Wordboot_Post_Types::register_taxonomy_item_category()    Register the "item_category" taxonomy.
			 */
			$post_types = new Wordboot_Post_Types( $this->plugin );
			$this->loader->add_action( 'init', $post_types, 'register_type_item' );
			$this->loader->add_action( 'init', $post_types, 'register_taxonomy_item_category' );

			/**
			 * Meta Boxes
			 */
			$meta_boxes = new Wordboot_Meta();

		}
	
		/**
		 * The public actions and filters.
		 */
		private function define_public_hooks() {
	
			/**
			 * Public
			 * 
			 * @see    Wordboot_Public::enqueue_styles()     Enqueue the public stylesheets.
			 * @see    Wordboot_Public::enqueue_scripts()    Enqueue the public scripts.
			 */
			$public = new Wordboot_Public( $this->plugin );
			$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );

			/**
			 * Templates
			 * 
			 * @see    Wordboot_Templates::single_template_item()     Setup the single template for the 'item' custom post type.
			 * @see    Wordboot_Templates::archive_template_item()    Setup the archive template for the 'item' custom post type.
			 */
			$templates = new Wordboot_Templates( $this->plugin );
			$this->loader->add_filter( 'single_template', $templates, 'single_template_item' );
			$this->loader->add_filter( 'archive_template', $templates, 'archive_template_item' );

			/**
			 * AJAX
			 * 
			 * @see    Wordboot_Ajax::wp_ajax_nopriv_callback()    Setup an example AJAX callback.
			 * @see    Wordboot_Ajax::wp_ajax_callback()
			 */
			$ajax = new Wordboot_Ajax( $this->plugin );
			$this->loader->add_action( 'wp_ajax_nopriv_callback', $ajax, 'callback' );
			$this->loader->add_action( 'wp_ajax_callback', $ajax, 'callback' );
	
		}
	
		/**
		 * The plugin hook loader.
		 * 
		 * @see    Wordboot_Loader::run()    Register the filters and actions with WordPress.
		 */
		public function run() {
			$this->loader->run();
		}
	
	}		

}
