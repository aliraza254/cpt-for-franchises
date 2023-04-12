<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/includes
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
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/includes
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Cpt_For_Franchises {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cpt_For_Franchises_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'CPT_FOR_FRANCHISES_VERSION' ) ) {
			$this->version = CPT_FOR_FRANCHISES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cpt-for-franchises';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
        $this->wts_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cpt_For_Franchises_Loader. Orchestrates the hooks of the plugin.
	 * - Cpt_For_Franchises_i18n. Defines internationalization functionality.
	 * - Cpt_For_Franchises_Admin. Defines all hooks for the admin area.
	 * - Cpt_For_Franchises_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpt-for-franchises-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpt-for-franchises-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cpt-for-franchises-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cpt-for-franchises-public.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cpt-for-franchises-short-code.php';

		$this->loader = new Cpt_For_Franchises_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cpt_For_Franchises_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cpt_For_Franchises_i18n();

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

		$plugin_admin = new Cpt_For_Franchises_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_custom_meta_box_data' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_function' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Cpt_For_Franchises_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_add_post_in_session', $plugin_public, 'add_post_in_session', 99 );
		$this->loader->add_action( 'wp_ajax_nopriv_add_post_in_session', $plugin_public, 'add_post_in_session', 99 );
		$this->loader->add_action( 'wp_ajax_remove_post_in_session', $plugin_public, 'remove_post_in_session', 99 );
		$this->loader->add_action( 'wp_ajax_nopriv_remove_post_in_session', $plugin_public, 'remove_post_in_session', 99 );

	}

    /**
     * Register all of the Short Code related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function wts_shortcodes() {

        $shortcode_admin = new Cpt_For_Franchises_Short_Code( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $shortcode_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $shortcode_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_post_send_email_to_admin', $shortcode_admin, 'send_email_to_admin', 99 );
        $this->loader->add_action( 'admin_post_nopriv_send_email_to_admin', $shortcode_admin, 'send_email_to_admin', 99 );
        add_shortcode( 'all_products_list', array($shortcode_admin, 'all_products_list_function') );
        add_shortcode( 'request_information', array($shortcode_admin, 'request_information_function') );
        add_shortcode( 'request_search_form', array($shortcode_admin, 'request_search_form_function') );
        add_shortcode( 'search_post_form', array($shortcode_admin, 'search_post_form_function') );
        add_shortcode( 'single_post_btn', array($shortcode_admin, 'single_post_btn_function') );
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
	 * @return    Cpt_For_Franchises_Loader    Orchestrates the hooks of the plugin.
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
