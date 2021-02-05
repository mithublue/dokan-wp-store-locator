<?php
/**
 * Plugin Name: WP Store Locator for Dokan
 * Plugin URI:
 * Description: Enables store locator feature for vendor by WP Store Locator
 * Version: 1.0
 * Author: CyberCraft
 * Author URI: http://cybercraftit.com/
 * Text Domain: wpsl-dokan
 * WC requires at least: 3.0
 * WC tested up to: 5.6
 * Domain Path: /languages/
 * License: GPL2
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !function_exists( 'pri' ) ) {
    function pri( $data ) {
        echo '<pre>';print_r( $data );echo '</pre>';
    }
}

//check if dokan is active
if( !class_exists( 'WeDevs_Dokan' ) || !class_exists( 'WP_Store_locator' ) ) {
    add_action('admin_notices', function () {
        ?>
        <div class="notice notice-success is-dismissible">
            <?php _e( 'You need to have Dokan and WP Store Locator activated to get WP Store Locator for Dokan working', 'domain' ); ?>
        </div>
<?php
    });
}

/**
 * WPSL_Dokan_Init class
 *
 * @class WPSL_Dokan_Init The class that holds the entire WPSL_Dokan_Init plugin
 */
class WPSL_Dokan_Init {

    /**
     * Instance of self
     *
     * @var WPSL_Dokan_Init
     */
    private static $instance = null;

    /**
     * Initializes the WPSL_Dokan_Init class
     *
     * Checks for an existing WeDevs_Classname() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor for the Classname class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    private function __construct() {
        $this->includes();
    }

    public function includes() {
        include_once 'inc/class-store-creator.php';
    }

}

WPSL_Dokan_Init::init();