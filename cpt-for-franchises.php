<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://webtechsofts.co.uk/
 * @since             1.0.0
 * @package           Cpt_For_Franchises
 *
 * @wordpress-plugin
 * Plugin Name:       CPT For Franchises
 * Plugin URI:        https://https://webtechsofts.co.uk/
 * Description:       Transform your website and improve your user experience with our new plugin! With just one shortcode, [all_products_list], your visitors can easily browse through all your available listings and add them to a custom form. We've even created a dedicated page, "request-information," where visitors can submit their inquiries. And for even more convenience, use the [request_search_form] shortcode to add a category and investment filter to your listings.
 * Version:           1.0.0
 * Author:            Web Tech Softs
 * Author URI:        https://https://webtechsofts.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cpt-for-franchises
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CPT_FOR_FRANCHISES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cpt-for-franchises-activator.php
 */
function activate_cpt_for_franchises() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cpt-for-franchises-activator.php';
	Cpt_For_Franchises_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cpt-for-franchises-deactivator.php
 */
function deactivate_cpt_for_franchises() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cpt-for-franchises-deactivator.php';
	Cpt_For_Franchises_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cpt_for_franchises' );
register_deactivation_hook( __FILE__, 'deactivate_cpt_for_franchises' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cpt-for-franchises.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cpt_for_franchises() {

	$plugin = new Cpt_For_Franchises();
	$plugin->run();

}
run_cpt_for_franchises();
