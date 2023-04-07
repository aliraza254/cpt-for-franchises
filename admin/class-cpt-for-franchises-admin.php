<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/admin
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Cpt_For_Franchises_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cpt-for-franchises-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cpt-for-franchises-admin.js', array( 'jquery' ), $this->version, false );

	}

    function add_custom_meta_box() {
        add_meta_box(
            'custom-meta-box',
            'Add Franchise Price',
            array( $this, 'custom_meta_box_callback'),
            'post',
            'normal',
            'default'
        );
    }

    function custom_meta_box_callback($post) {
        wp_nonce_field(basename(__FILE__), 'custom_meta_box_nonce');
        $value = get_post_meta($post->ID, '_custom_field', true);
        ?>
        <input type="number" id="custom-field" style="width: 100%; margin-top: 10px;" name="custom_field" placeholder="Add Franchise Price" value="<?php echo esc_attr($value) ?>">
        <?php
    }

    function save_custom_meta_box_data($post_id) {
        if (!isset($_POST['custom_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (!isset($_POST['custom_field'])) {
            return;
        }

        $value = sanitize_text_field($_POST['custom_field']);

        update_post_meta($post_id, '_custom_field', $value);
    }

}
