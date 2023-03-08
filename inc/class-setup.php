<?php
/**
 * Satus Setup.
 * 
 * @package Satus
 * @author  Tyler Johnson
 * @since   1.0.0
 */
class satusSetup {

    /**
     * Construct.
     */
    public function __construct() {

        // ACF.
        if( class_exists( 'ACF' ) ) {

            // Theme settings.
            add_action( 'acf/init', [ $this, 'theme_settings' ] );

        }

        // Setup.
        add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );

        // Enqueue.
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_private' ] );
        add_action( 'wp_head', [ $this, 'site_head' ] );
        add_action( 'admin_head', [ $this, 'site_head' ] );

        // Clean archive titles.
        add_filter( 'get_the_archive_title', [ $this, 'archive_titles' ] );

        // Remove junk dashboard widgets.
        add_action('wp_dashboard_setup', [ $this, 'dashboard' ], 999);

        // Remove emoji scripts.
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );

        // Update dashboard footer text.
        add_filter( 'admin_footer_text', [ $this, 'dashboard_footer' ] );

    }

    /**
     * Theme settings page.
     */
    public function theme_settings() {

        if( function_exists( 'acf_add_options_page' ) ) {

            // Set the options.
            $args = [
                'page_title'	=> __( 'Theme Settings', 'satus' ),
                'menu_title'	=> __( 'Theme Settings', 'satus' ),
                'captability'	=> 'edit_posts',
                'icon_url'		=> 'dashicons-art',
                'position'		=> 60
            ];
        
            // Add the options page.
            acf_add_options_page( $args );
        
        }

    }

    /**
     * Setup.
     */
    public function setup_theme() {

        // Load text domain.
        load_theme_textdomain( 'satus', SATUS_PATH . 'languages' );

        // Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

        // Register navigation menus.
        register_nav_menus( [ 
            'main-menu' => esc_html__( 'Main Menu', 'satus' ) 
        ] );

        // Switch default core markup to valid HTML5.
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
            ]
		);

        // Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

        // Set content width.
        $GLOBALS['content_width'] = apply_filters( 'satus_content_width', 640 );

    }

    /**
     * Enqueue public files.
     */
    public function enqueue_public() {

        // CSS.
        wp_enqueue_style( 'satus-base-css', SATUS_URL . 'assets/css/base.css', [], SATUS_VERSION, 'all' );
        wp_enqueue_style( 'satus-main-css', SATUS_URL . 'style.css', [], SATUS_VERSION, 'all' );

        // JS.
        wp_enqueue_script( 'satus-main-js', SATUS_URL . 'assets/js/satus.js', [ 'jquery' ], SATUS_VERSION, true );

        // Localize.
        wp_localize_script( 'satus-main-js', 'satus_vars', [ 
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'satus_nonce' ),
        ] );

    }

    /**
     * Enqueue private files (admin).
     */
    public function enqueue_private() {

        // CSS.
        wp_enqueue_style( 'satus-admin', SATUS_URL . 'assets/css/admin.css', [], SATUS_VERSION, 'all' );

    }

    /**
     * Enqueue blocks.
     */
    public function enqueue_blocks() {

        // CSS.
        wp_enqueue_style( 'satus-base-css', SATUS_URL . 'assets/css/base.css', [], SATUS_VERSION, 'all' );
        wp_enqueue_style( 'satus-block-css', SATUS_URL . 'assets/css/blocks.css', [], SATUS_VERSION, 'all' );

        // JS.
        wp_enqueue_script( 'satus-base-js', SATUS_URL . 'assets/js/base.js', [ 'jquery' ], SATUS_VERSION, true );
        wp_enqueue_script( 'satus-main-js', SATUS_URL . 'assets/js/main.js', [ 'jquery' ], SATUS_VERSION, true );

    }

    /**
     * Site head.
     */
    public function site_head() { 

        // Load header code.
    
    }

    /**
     * Remove Category:, Tag:, & Author: from archive titles.
     */
    public function archive_titles() {

        // Checks.
        if( is_category() ) {

            // Set single category.
            $title = single_cat_title( '', false );

        } elseif( is_post_type_archive() ) {

            // Set archive.
            $title = post_type_archive_title( '', false );

        } elseif( is_tag() ) {

            // Set tag.
            $title = single_tag_title( '', false );

        } elseif( is_author() ) {

            // Set author.
            $title = '<span class="vcard">' . get_the_author() . '</span>';
            
        } elseif( is_tax() ) {

            // Set taxonomy.
            $title = single_term_title( '', false );

        }

        // Return the title.
        return $title;

    }

    /**
     * Remove junk dashboard widgets.
     */
    public function dashboard() {

        // Get global.
        global $wp_meta_boxes;

        // WordPress Boxes
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);

        // BBPress
        unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);

        // Yoast SEO
        unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);

        // Gravity Forms
        unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);

        // Apps Creo
        unset($wp_meta_boxes['dashboard']['normal']['core']['appscreo_news']);

        // WP Ematico
        unset($wp_meta_boxes['dashboard']['normal']['core']['wpematico_widget']);

        // WP Engine
        unset($wp_meta_boxes['dashboard']['normal']['core']['wpe_dify_news_feed']);

    }

    /**
     * Set custom dashboard footer.
     */
    public function dashboard_footer() {

        // Compose
        $output = '&copy; Copyright ' . date( 'Y' ) . '. <a href="' . get_bloginfo( 'url' ) . '">' . get_bloginfo( 'name' ) . '</a>. All Rights Reserved. Built by <a href="https://tylerjohnsondesign.com" target="_blank">Satus</a>.';

        // Output
        echo $output;

    }

}
new satusSetup;