<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://huttartsites.co.nz
 * @since      1.0.0
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/includes
 * @author     Owen McCarthy <onmccarthy@gmail.com>
 */
class Hacc_Exhibition_Manager {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hacc_Exhibition_Manager_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
        
        

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'hacc-exhibition-manager';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hacc_Exhibition_Manager_Loader. Orchestrates the hooks of the plugin.
	 * - Hacc_Exhibition_Manager_i18n. Defines internationalization functionality.
	 * - Hacc_Exhibition_Manager_Admin. Defines all hooks for the admin area.
	 * - Hacc_Exhibition_Manager_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hacc-exhibition-manager-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hacc-exhibition-manager-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hacc-exhibition-manager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hacc-exhibition-manager-public.php';

		$this->loader = new Hacc_Exhibition_Manager_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hacc_Exhibition_Manager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hacc_Exhibition_Manager_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
     
		$plugin_admin = new Hacc_Exhibition_Manager_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
                $this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widgets' );
                
                $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
                $this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );
                
                // Custom post type hooks
                // Venue Post Type
                $venue = new Hacc_Exhibition_Manager_Venue( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $venue, 'get_template',11,1);
                $this->loader->add_action( 'init', $venue, 'create_post_type' );
                $this->loader->add_action('init', $venue, 'create_venue_type_taxonomy');
                
                // Group Post Type
                $group = new Hacc_Exhibition_Manager_Group( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $group, 'get_template',11,1);
                $this->loader->add_action( 'init', $group, 'create_post_type' );
                $this->loader->add_action( 'add_meta_boxes', $group, 'create_metabox' );
                $this->loader->add_action( 'save_post_hacc_group', $group, 'save_meta_data',20 );

                // Exhibition Post type
                $exhibition = new Hacc_Exhibition_Manager_Exhibition( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $exhibition, 'get_template',11,1 );
                $this->loader->add_action( 'init', $exhibition, 'create_post_type' );
                $this->loader->add_action( 'add_meta_boxes', $exhibition, 'create_metabox' );
                $this->loader->add_action( 'save_post', $exhibition, 'save_meta_data',20 );
                
                // Tutor Post type
                $tutor = new Hacc_Exhibition_Manager_Tutor( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $tutor, 'get_template',11,1);
                $this->loader->add_action( 'init', $tutor, 'create_post_type' );
                
                // Class Post type
                $class = new Hacc_Exhibition_Manager_Workshop( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $class, 'get_template',11,1 );
                $this->loader->add_action( 'init', $class, 'create_post_type' );
                $this->loader->add_action( 'add_meta_boxes', $class, 'create_metabox' );
                $this->loader->add_action( 'save_post_hacc_workshop', $class, 'save_meta_data',20 );
                
                // Artist Post type
                $artist = new Hacc_Exhibition_Manager_Artist( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $artist, 'get_template',11,1);
                $this->loader->add_action( 'init', $artist, 'create_post_type' );
                
                // Programme Post Type
                $programme = new Hacc_Exhibition_Manager_Programme( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $programme, 'get_template',11,1);
                $this->loader->add_action( 'init', $programme, 'create_post_type' );
                
                                // Exhibition Post type
                $exhibition = new Hacc_Exhibition_Manager_News( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_filter( 'template_include', $exhibition, 'get_template',11,1 );
                $this->loader->add_action( 'init', $exhibition, 'create_post_type' );
                $this->loader->add_action( 'add_meta_boxes', $exhibition, 'create_metabox' );
                $this->loader->add_action( 'save_post', $exhibition, 'save_meta_data',20 );
                
       }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hacc_Exhibition_Manager_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles',99);
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
                
                

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hacc_Exhibition_Manager_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
