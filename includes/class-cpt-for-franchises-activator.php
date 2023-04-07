<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cpt_For_Franchises
 * @subpackage Cpt_For_Franchises/includes
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Cpt_For_Franchises_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $title_of_the_page = 'Search Results';
        $objPage = get_page_by_title($title_of_the_page, 'OBJECT', 'page');
        if( ! empty( $objPage ) ){
            $page_id = $objPage->ID;
            update_post_meta( $page_id, 'post_content', '[search_post_form]' );
        }
        else {
            $page_id = wp_insert_post(
                array(
                    'comment_status' => 'close',
                    'ping_status' => 'close',
                    'post_author' => 1,
                    'post_title' => ucwords($title_of_the_page),
                    'post_name' => strtolower(str_replace(' ', '-', trim($title_of_the_page))),
                    'post_status' => 'publish',
                    'post_content' => '[search_post_form]',
                    'post_type' => 'page',
                )
            );
        }
        update_option('redirection_of_verification', $page_id);
	}

}
