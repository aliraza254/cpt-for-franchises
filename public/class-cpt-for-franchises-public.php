<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/public
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Cpt_For_Franchises_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cpt_For_Franchises_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cpt_For_Franchises_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cpt-for-franchises-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cpt_For_Franchises_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cpt_For_Franchises_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cpt-for-franchises-public.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'URL',array('ajax_url' => admin_url('admin-ajax.php'), 'redirect' => get_home_url()), array( 'jquery' ), $this->version, false );

	}
	public function add_post_in_session(){
	    $post_id = $_POST['post_ids'];
        $post_ids = (!empty($_COOKIE['post_ids'])) ? $_COOKIE['post_ids'] : '';
        $posts = [];
        if(!empty($post_ids)){
            $posts = explode(',', $post_ids);
            $posts[] = $post_id;
            $posts = array_unique($posts);
            setcookie('post_ids', implode(',', $posts), time() + (86400 * 30), "/");
        }
        else{
            $posts[] = $post_id;
            setcookie('post_ids', implode(',', $posts), time() + (86400 * 30), "/");
        }
        echo 100;
	    die();
    }
	public function remove_post_in_session(){
        $post_id = $_POST['post_id'];
        $coockies = explode(',', $_COOKIE['post_ids']);
        foreach ($coockies as $key => $single){
            if($post_id == $single){
                unset($coockies[$key]);
            }
        }
//        $post_ids = implode(',', $coockies);
        setcookie('post_ids', implode(',', $coockies), time() + (86400 * 30), "/");
	    echo 100;
	    die();
    }

}
