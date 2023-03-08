<?php
/**
 * Satus.
 * 
 * @package Satus
 * @author  Tyler Johnson
 * @link    https://github.com/tylerjohnsondesign/satus
 * @license GPL-2.0+
 */

/**
 * Constants.
 */
define( 'SATUS_VERSION', '1.0.0' );
define( 'SATUS_URL', trailingslashit( get_template_directory_uri() ) );
define( 'SATUS_PATH', trailingslashit( get_template_directory() ) );

/**
 * Theme.
 */
class satusTheme {

    /**
     * Construct.
     */
    public function __construct() {

        // Load.
        $this->load();

    }

    /**
     * Load.
     */
    public function load() {

        // Load classes.
        require SATUS_PATH . 'inc/class-setup.php';

    }

}
new satusTheme;