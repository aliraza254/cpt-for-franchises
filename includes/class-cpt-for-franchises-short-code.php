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
class Cpt_For_Franchises_Short_Code {

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

    }

    public function single_post_btn_function(){
        if( is_singular('post') ) {
            $current_post_id = get_the_ID();
            ?>
            <div>
                <button class="btn btn--simple btn--single-post" data-id="<?php echo $current_post_id;?>" > Request INFO </button>
            </div>
            <?php
        }
    }

    public function search_post_form_function(){
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $investment = isset($_GET['investment']) ? $_GET['investment'] : '';
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(),
            'meta_query' => array()
        );
        if (!empty($category)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $category
            );
        }
        if (!empty($investment)) {
            $args['meta_query'][] = array(
                'key' => '_custom_field',
                'value' => $investment,
                'compare' => '>=',
                'type' => 'NUMERIC'
            );
        }
        $results = new WP_Query( $args );
        $html = '';
        if ( $results->have_posts() ) {
            $post_ids = explode(',', $_COOKIE['post_ids']);
            $html .= '<div class="cards" style="max-width: 100% !important;">';
            while ( $results->have_posts() ) {
                $results->the_post();
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
                $custom_field_value = get_post_meta(get_the_ID(), '_custom_field', true);
                $html .= '<div class="card">
            <div class="card__body">
                <a href="#" target="_self">
                    <div class="card__image">
                        <a href="' . esc_url(get_permalink()) . '">
                            <img width="180" height="120" src="' . ($thumbnail_url ? esc_url($thumbnail_url) : '') . '" class="">
                        </a>
                    </div>
                </a>
                <div class="card__entry">
                    <div class="card__entry-inner" style="height: 258.6px;">
                        <h3>
                            <a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>
                        </h3>
                        <p>' . get_the_excerpt() . '</p>
                    </div>
                    <div class="card__actions">
                        <p>Cash Required: $' . esc_html($custom_field_value) . '</p>
                        <label for="post-' . get_the_ID() . '" class="btn btn--blue btn--special">
                            <input type="checkbox" id="post-' . get_the_ID() . '" class="request-info-checkbox"> Request FREE Info
                        </label>
                    </div>
                </div>
            </div>
        </div>';
            }
            wp_reset_postdata();
            $html .= '</div> 
        <div class="bar-request isFixed" id="show-shell" style="display: none">
            <div class="shell">
                <div class="bar__body">
                    <div class="bar__body-inner">
                        <div class="bar__number">
                            <span>0</span>
                        </div>
                        <h6>Pending Request</h6>
                    </div>
                </div>
                <div class="bar__actions">
                    <button class="btn btn--simple btn--loog " target="_blank">
                        Complete Request >
                    </button>
                </div>
            </div>
        </div>';
        } else {
            $html .= '<p>Sorry, no posts found!</p>';
        }
        return $html;

    }

    public function request_search_form_function(){
        ?>
        <section class="section-home">
            <div class="section__form">
                <div class="form form-search">
                    <form method="get" action="<?php echo esc_url( home_url( '/search-results/' ) ); ?>" id="companies-filter-form">
                        <div class="form__head">
                            <h4>Search America's Best Franchises Directory</h4>
                        </div>
                        <div class="form__body">
                            <div class="form__row">
                                <div class="form__controls">
                                    <?php
                                    $categories = get_categories(array(
                                        'type'         => 'post',
                                        'orderby'      => 'name',
                                        'order'        => 'ASC',
                                        'hide_empty'   => 0,
                                        'hierarchical' => 1,
//                                        'exclude'      => 1,
                                    ));
                                    ?>
                                    <select name="category" required>
                                        <option value="">Please select Industry</option>
                                        <?php
                                        foreach ($categories as $category) {
                                            ?>
                                            <option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html($category->name)?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form__row">
                                <div class="form__controls">
                                    <select name="investment" required>
                                        <option value="">Please select Investment</option>
                                        <option value="10000">Up To $10k</option>
                                        <option value="20000">Up To $20k</option>
                                        <option value="50000">Up To $50k</option>
                                        <option value="100000">Up To $100k</option>
                                        <option value="200000">Up To $200k</option>
                                        <option value="300000">Up To $300k</option>
                                        <option value="400000">Up To $400k</option>
                                        <option value="500000">Up To $500k</option>
                                        <option value="500000+">All Over $500k</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form__row invalid-form-message" style="display: none">
                                <p>Please select a filter</p>
                            </div>
                        </div>
                        <div class="form__foot">
                            <div class="form__actions">
                                <button type="submit" id="search_post_form_btn" class="form__btn btn--red btn--xl">Find Your Match</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    }

    public function all_products_list_function(){
        ?>
        <section class="section-cards">

            <div class="shell shell--offset">

                <div class="section__head">

                    <h2>Best Franchises To Start 2023</h2>

                    <p>We've connected thousands of aspiring entrepreneurs to their perfect franchise opportunity. Now,
                        let's
                        find your perfect franchise match.</p>
                </div>

                <div class="tabs">

                    <div class="tabs__head">
                        <nav class="tabs__nav">
                            <ul>
                                <li class="current">
                                    <a href="#tab-1">Best Franchises</a>
                                </li>

                                <li>
                                    <a href="#tab-2">New Arrivals</a>
                                </li>

                                <li>
                                    <a href="#tab-3">Low-Cost</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="tabs__body">

                        <div class="tab current" id="tab-1">
                            <div class="cards">
                                <?php
                                $args = array(
                                    'post_type'      => 'post',
                                    'orderby'        => 'ID',
                                    'post_status'    => 'publish',
                                    'order'          => 'DESC',
                                    'posts_per_page' => 6,
                                    'tax_query'      => array(
                                        array(
                                            'taxonomy' => 'post_tag',
                                            'field'    => 'slug',
                                            'terms'    => 'new-franchises', // replace with the tag slug you want to filter by
                                        ),
                                    ),
                                );
                                $results = new WP_Query( $args );
                                    if ( $results->have_posts() ) :
                                        while ( $results->have_posts() ) : $results->the_post();
                                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
                                            $custom_field_value = get_post_meta(get_the_ID(), '_custom_field', true);
                                ?>
                                <div class="card">
                                    <div class="card__body">
                                        <a href="#" target="_self">
                                            <div class="card__image">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img width="180" height="120" src="<?php if( $thumbnail_url){ echo $thumbnail_url;}?>" class="">
                                                </a>
                                            </div>
                                        </a>
                                        <div class="card__entry">
                                            <div class="card__entry-inner" style="height: 258.6px;">
                                                <h3>
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                <p><?php the_excerpt(); ?></p>
                                            </div>
                                            <div class="card__actions">
                                                <p>Cash Required: $<?php echo $custom_field_value;?></p>
                                                <label for="post-<?php the_ID(); ?>" class="btn btn--blue btn--special">
                                                    <input type="checkbox" id="post-<?php the_ID(); ?>" class="request-info-checkbox"> Request FREE Info
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                        endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        </div>

                        <div class="tab" id="tab-2">
                            <div class="cards">
                                <?php
                                $args = array(
                                    'post_type'      => 'post',
                                    'orderby'        => 'ID',
                                    'post_status'    => 'publish',
                                    'order'          => 'DESC',
                                    'posts_per_page' => 6,
                                    'tax_query'      => array(
                                        array(
                                            'taxonomy' => 'post_tag',
                                            'field'    => 'slug',
                                            'terms'    => 'new-arrivals', // replace with the tag slug you want to filter by
                                        ),
                                    ),
                                );
                                $results = new WP_Query( $args );
                                    if ( $results->have_posts() ) :
                                        while ( $results->have_posts() ) : $results->the_post();
                                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
                                            $custom_field_value = get_post_meta(get_the_ID(), '_custom_field', true);
                                ?>
                                <div class="card">
                                    <div class="card__body">
                                        <a href="#" target="_self">
                                            <div class="card__image">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img width="180" height="120" src="<?php if( $thumbnail_url){ echo $thumbnail_url;}?>" class="">
                                                </a>
                                            </div>
                                        </a>
                                        <div class="card__entry">
                                            <div class="card__entry-inner" style="height: 258.6px;">
                                                <h3>
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                <p><?php the_excerpt(); ?></p>
                                            </div>
                                            <div class="card__actions">
                                                <p>Cash Required: $<?php echo $custom_field_value;?></p>
                                                <label for="post-<?php the_ID(); ?>" class="btn btn--blue btn--special">
                                                    <input type="checkbox" id="post-<?php the_ID(); ?>" class="request-info-checkbox"> Request FREE Info
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                        endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        </div>

                        <div class="tab" id="tab-3">
                            <div class="cards">
                                <?php
                                $args = array(
                                    'post_type'      => 'post',
                                    'orderby'        => 'ID',
                                    'post_status'    => 'publish',
                                    'order'          => 'DESC',
                                    'posts_per_page' => 6,
                                    'tax_query'      => array(
                                        array(
                                            'taxonomy' => 'post_tag',
                                            'field'    => 'slug',
                                            'terms'    => 'low-cost', // replace with the tag slug you want to filter by
                                        ),
                                    ),
                                );
                                $results = new WP_Query( $args );
                                    if ( $results->have_posts() ) :
                                        while ( $results->have_posts() ) : $results->the_post();
                                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
                                            $custom_field_value = get_post_meta(get_the_ID(), '_custom_field', true);
                                    ?>
                                <div class="card">
                                    <div class="card__body">
                                        <a href="#" target="_self">
                                            <div class="card__image">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img width="180" height="120" src="<?php if( $thumbnail_url){ echo $thumbnail_url;}?>" class="">
                                                </a>
                                            </div>
                                        </a>
                                        <div class="card__entry">
                                            <div class="card__entry-inner" style="height: 258.6px;">
                                                <h3>
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                <p><?php the_excerpt(); ?></p>
                                            </div>
                                            <div class="card__actions">
                                                <p>Cash Required: $<?php echo $custom_field_value;?></p>
                                                <label for="post-<?php the_ID(); ?>" class="btn btn--blue btn--special">
                                                    <input type="checkbox" id="post-<?php the_ID(); ?>" class="request-info-checkbox"> Request FREE Info
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                        endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        </div>

                        <div class="tab__actions" style="text-align: center;">
                            <form method="get" action="<?php echo esc_url( home_url( '/search-results/' ) ); ?>">
                                <button type="submit" class="btn btn--white--alt">See More</button>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
            <input type="hidden" value="1" id="sb_ajax_details">
            <div class="bar-request isFixed" id="show-shell" style="display: none">
                <div class="shell">
                    <div class="bar__body">
                        <div class="bar__body-inner">
                            <div class="bar__number">
                                <span>0</span>
                            </div>
                            <h6>Pending Request</h6>
                        </div>
                    </div>
                    <div class="bar__actions">
                        <button class="btn btn--simple btn--loog">
                            Complete Request >
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    public function request_information_function(){
        if(empty($_COOKIE['post_ids'])){
            wp_safe_redirect(get_home_url());
        }
        ?>
        <section class="wts_section-intro">
            <div class="wts_shell">
                <p class="section__title">Congratulations! You've made some excellent choices. </p>
                <h1>Request Franchise Information</h1>
            </div>
        </section>

        <section style="padding-top: 50px">
            <div class="wts_container">
                <div class="main_body">
                    <div class="wts_form">
                        <div class="form__head">
                            <h2>Make a Smart Choice... in 3 Easy Steps!</h2>
                        </div>

                        <aside class="form__aside">

                            <div class="wts_widget_requests">
                                <div class="wts_mar_bo">
                                    <div class="widget__entry-left">
                                        <span class="widget__num">1</span>
                                    </div>
                                    <div class="widget__entry-right">
                                        <p>Please inspect your franchise choices. You will receive information from all of them
                                            listed below. If you changed your
                                            mind, simply remove any you are no longer interested in.</p>
                                    </div>
                                </div>

                                <div class="wts_widget__head" style="background: #1b384d;">
                                    <h2 class="wts_widget__title">YOUR SELECTED FRANCHISES</h2>
                                </div>

                                <div class="widget__body">
                                    <div class="widget__body-half">
                                        <div class="form__row">
                                            <ul class="list-checkboxes list-checkboxes--remove">
                                                <?php


                                                if(!empty($_COOKIE['post_ids'])){
                                                    $post_ids = explode(',', $_COOKIE['post_ids']);
                                                    $args = array(
                                                        'post_type' => 'post',
                                                        'post__in' => $post_ids,
                                                        'orderby' => 'post__in'
                                                    );
                                                    $posts = get_posts($args);
                                                    foreach ($posts as $post) {
                                                        $value = get_post_meta($post->ID, '_custom_field', true);
                                                        ?>
                                                        <li>
                                                            <div class="checkbox checkbox--selected" style="width: 254px;">
                                                                <label for="">
                                                                    <strong><?php echo $post->post_title?></strong>
                                                                    <span>[$<?php echo $value;?>] </span>
                                                                    <a href="javascript:void(0)" class="remove_post" data-post-id="<?php echo $post->ID;?>">X | Remove</a>
                                                                </label>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wts_widget_requests">
                                <div style="margin-bottom: 20px;">
                                    <div class="widget__entry-left">
                                        <span class="widget__num">2</span>
                                    </div>
                                    <div class="widget__entry-right">
                                        <p style="margin: 0">Comparing franchises helps in making a smarter choice. Based on your selection (s) our algorithm has matched you to the franchises listed below. Simply, check the box if you have an interest in learning more.</p>
                                    </div>
                                </div>

                                <div class="wts_widget__head" style="background: #d74a29;">
                                    <h2 class="wts_widget__title">RECOMMENDED FRANCHISES</h2>
                                </div>
                                <div class="widget__body">
                                    <div class="widget__body-half">
                                        <div class="form__row">
                                            <ul class="list-checkboxes">
                                                <?php
                                                $args = array(
                                                    'post_type'=> 'post',
                                                    'orderby'    => 'ID',
                                                    'post_status' => 'publish',
                                                    'order'    => 'DESC',
                                                    'posts_per_page' => 5
                                                );
                                                $posts = get_posts($args);
                                                foreach ($posts as $post) {
                                                    $thumbnail_url = get_the_post_thumbnail_url($post->ID);
                                                    $value = get_post_meta($post->ID, '_custom_field', true);
                                                    ?>
                                                    <li>
                                                        <div class="checkbox checkbox--selected">
                                                            <label for="">
                                                                <input type="checkbox" name="recommended_franchise" data-id="<?php echo $post->ID; ?>" class="select_posts">
                                                                <strong><?php echo $post->post_title?></strong>
                                                                <span>[$<?php echo $value;?>] </span>
                                                                <a class="popup-open" data-target="target_<?php echo $post->ID; ?>" style="cursor: pointer">Read Summary</a>
                                                            </label>
                                                        </div>
                                                        <div class="popup visible" style="display: none" id="target_<?php echo $post->ID; ?>">
                                                            <div class="popup__content">
                                                                <a href="#" class="popup__close"></a>
                                                                <div class="popup__image">
                                                                    <img width="180" height="120" src="<?php if( $thumbnail_url){ echo $thumbnail_url;}?>" class="attachment-large size-large entered lazyloaded">
                                                                </div>

                                                                <h5><?php echo $post->post_title?></h5>

                                                                <div class="popup__entry">
                                                                    <p><?php echo $post->post_content;?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                <div class="form__row">
                                                    <p class="uncheck_selected">clear recommendations</p>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </aside>

                        <div class="form__inner">
                            <div style="margin-bottom: 30px;">
                                <div class="widget__entry-left">
                                    <span class="widget__num">3</span>
                                </div>
                                <div class="widget__entry-right">
                                    <p style="margin: 0">Complete the form below and we'll send your information to all of your franchise selections. You'll have taken your official first step toward business ownership. Plus, it's all free!</p>
                                </div>
                            </div>
                            <form  method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="information_form">
                                <?php
                                $franchise = $_COOKIE['post_ids'];
                                ?>
                                <input type="hidden" name="franchise" value="<?php echo $franchise; ?>">
                                <input type="hidden" name="recommended_franchise" value="">
                                <input type="hidden" value="send_email_to_admin" name="action">
                                <div class="wts_two_field_main">
                                    <div class="wts_two_field" style="margin-right: 10px;">
                                        <input type="text" name="first_name" placeholder="Enter Your First Name" required>
                                    </div>
                                    <div class="wts_two_field" style="margin-left: 10px;">
                                        <input type="text" name="last_name" placeholder="Enter Your Last Name" required>
                                    </div>
                                </div>

                                <div class="wts_two_field_main">
                                    <div class="wts_two_field" style="margin-right: 10px;">
                                        <input type="text" name="mobile_number" placeholder="Mobile Number" required>
                                    </div>
                                    <div class="wts_two_field" style="margin-left: 10px;">
                                        <input type="text" name="phone_number" placeholder="Phone Number" required>
                                    </div>
                                </div>

                                <div class="wts_two_field_main">
                                    <div class="wts_two_field" style="margin-right: 10px;">
                                        <select name="preferred_contact_method" required>
                                            <option>Preferred Method of Contact</option>
                                            <option>Mobile Phone and Text</option>
                                            <option>Mobile Phone</option>
                                            <option>Alternate Phone</option>
                                        </select>
                                    </div>
                                    <div class="wts_two_field" style="margin-left: 10px;">
                                        <select name="best_time_to_call" required>
                                            <option>Best Time to Call</option>
                                            <option>Before 9AM</option>
                                            <option>Before Noon</option>
                                            <option>After Noon</option>
                                            <option>After 6PM</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="wts_two_field_main">
                                    <div class="wts_two_field" style="margin-right: 10px;">
                                        <input type="text" name="city" placeholder="City" required>
                                    </div>
                                    <div class="wts_two_field" style="margin-left: 10px;">
                                        <input type="text" name="state" placeholder="State" required>
                                    </div>
                                </div>

                                <div class="wts_two_field_main">
                                    <div class="wts_two_field" style="margin-right: 10px;">
                                        <input type="text" name="zip" placeholder="Zip">
                                    </div>
                                    <div class="wts_two_field" style="margin-left: 10px;">
                                        <select name="cash_on_hand_required" required>
                                            <option>Cash on Hand Required</option>
                                            <option>$25,000</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="wts_two_field_main">
                                    <div class="wts_two_field">
                                        <select name="time_frame_to_invest" required>
                                            <option>Time Frame to Invest</option>
                                            <option>Immediately</option>
                                            <option>Within 3 Months</option>
                                            <option>3 - 6 Months</option>
                                            <option>6 - 12 Months</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="margin-top: 20px">
                                    <div style="margin-bottom: 20px; background: #e7eef3; padding: 22px 25px!important;">
                                        <div class="widget__entry-left" style="margin-right: 25px;">
                                            <input type="checkbox" name="consultation" required>
                                        </div>
                                        <div class="widget__entry-right">
                                            <p style="margin: 0">Yes, I would like a Free Franchise Consultation with a Certified Franchise Consultant to explore some new and exciting franchise opportunities that are rapidly growing.</p>
                                        </div>
                                    </div>

                                    <div style="margin-bottom: 20px; padding: 22px 25px!important;">
                                        <div class="widget__entry-left" style="margin-right: 25px;">
                                            <input type="checkbox" name="marketing" required>
                                        </div>
                                        <div class="widget__entry-right">
                                            <p style="margin: 0">By pressing Complete Request, you agree that America’s Best Franchises and the businesses you selected may call, text, and/or email you at the number you provided above, including for marketing purposes related to your inquiry. This contact may be made using automated or pre-recorded/artificial voice technology. Data and message rates may apply. You are not consenting to any purchase. You also agree to our Privacy Policy & Terms of Use.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="wts_form_btn">
                                    <button type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }

    public function send_email_to_admin(){
        $franchise = $_POST['franchise'];
        $recommended_franchise = $_POST['recommended_franchise'];
        $combined_values = array_merge(explode(',', $franchise), explode(',', $recommended_franchise));
        $unique_values = array_unique($combined_values);
        foreach ($unique_values as $single){
            $titles .= get_the_title( $single ) . "<br>";
        }
        $email = get_option('admin_email');
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $mobile_number = $_POST['mobile_number'];
        $phone_number = $_POST['phone_number'];
        $preferred_contact_method = $_POST['preferred_contact_method'];
        $best_time_to_call = $_POST['best_time_to_call'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $cash_on_hand_required = $_POST['cash_on_hand_required'];
        $time_frame_to_invest = $_POST['time_frame_to_invest'];
        $consultation = $_POST['consultation'];
        $marketing = $_POST['marketing'];

        $subject = 'Customer Request information';
        $headers  = "From: " . strip_tags($email) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($email) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = '<table style="width:100%;"><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">First Name</td>';
        $message .= '<td style="border: 1px solid #000000;"> '.$first_name.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Last Name</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$last_name.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Mobile Number</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$mobile_number.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Phone Number</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$phone_number.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Preferred Contact Method</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$preferred_contact_method.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Best Time To Call</td>';
        $message .= '<td style="border: 1px solid #000000;" >'.$best_time_to_call.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">City</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$city.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">State</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$state.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Zip</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$zip.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Cash On Hand Required</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$cash_on_hand_required.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Time Frame To Invest</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$time_frame_to_invest.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Consultation</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$consultation.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Marketing</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$marketing.'</td></tr><tr>';
        $message .= '<td style="border: 1px solid #000000; text-align: center">Franchise Title</td>';
        $message .= '<td  style="border: 1px solid #000000;">'.$titles.'</td></tr></table>';

        if( mail ( $email, $subject, $message, $headers ) ){
            setcookie('post_ids', '', time() + (86400 * 30), "/");
            ?>
            <script>
                window.location.href = "<?php echo get_home_url(); ?>/thank-you";
            </script>
            <?php
        } else{
            ?>
            <script>
                window.location.href = "<?php echo get_home_url(); ?>";
            </script>
            <?php
        }

    }

    public function this_meathod_works_on_live_site(){
        $to = get_option('admin_email');
        $to = 'ranarazaali08@gmail.com';
        $subject = 'Customer Request Information';
        $franchise = sanitize_text_field($_POST['franchise']);
        $recommended_franchise = sanitize_text_field($_POST['recommended_franchise']);
        $combined_values = array_merge(explode(',', $franchise), explode(',', $recommended_franchise));
        $unique_values = array_unique($combined_values);
        foreach ($unique_values as $single) {
            $titles .= get_the_title($single) . "<br>";
        }
        $body = '<table style="width:100%;">';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Franchise Titles:</td><td>' . $titles . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">First Name:</td><td>' . sanitize_text_field($_POST['first_name']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Last Name:</td><td>' . sanitize_text_field($_POST['last_name']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Mobile Number:</td><td>' . sanitize_text_field($_POST['mobile_number']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Phone Number:</td><td>' . sanitize_text_field($_POST['phone_number']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Preferred Contact Method:</td><td>' . sanitize_text_field($_POST['preferred_contact_method']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Best Time To Call:</td><td>' . sanitize_text_field($_POST['best_time_to_call']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">City:</td><td>' . sanitize_text_field($_POST['city']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">State:</td><td>' . sanitize_text_field($_POST['state']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Zip:</td><td>' . sanitize_text_field($_POST['zip']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Cash On Hand Required:</td><td>' . sanitize_text_field($_POST['cash_on_hand_required']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Time Frame To Invest:</td><td>' . sanitize_text_field($_POST['time_frame_to_invest']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Consultation:</td><td>' . sanitize_text_field($_POST['consultation']) . '</td></tr>';
        $body .= '<tr><td style="border: 1px solid #000000; text-align: center">Marketing:</td><td>' . sanitize_text_field($_POST['marketing']) . '</td></tr>';
        $body .= '</table>';

        $headers = array('Content-Type: text/html; charset=UTF-8');
        // mail send by hosting without html format & wp_mail send by WordPress with html format
        if( mail($to, $subject, $body, $headers) ){
            setcookie('post_ids', '', time() + (86400 * 30), "/");
            ?>
            <script>
                window.location.href = "<?php echo get_home_url(); ?>/thank-you";
            </script>
            <?php
        } else{
            ?>
            <script>
                window.location.href = "<?php echo get_home_url(); ?>";
            </script>
            <?php
        }

    }



}
