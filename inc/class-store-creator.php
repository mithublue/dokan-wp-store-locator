<?php

/**
 * WPSLD_Store_Creator class
 *
 * @class WPSLD_Store_Creator The class that holds the entire WPSLD_Store_Creator plugin
 */
class WPSLD_Store_Creator {

    /**
     * Instance of self
     *
     * @var WPSLD_Store_Creator
     */
    private static $instance = null;

    /**
     * Initializes the WPSLD_Store_Creator class
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
        add_action( 'dokan_new_vendor', [$this, 'update_store']);
        add_action( 'dokan_update_vendor', [$this, 'update_store']);

        add_action( 'user_register', [$this, 'update_store']);
        add_action( 'profile_update', [$this, 'update_store']);
    }

    /**
     * @param $vendor_id
     */
    public function update_store( $vendor_id ) {
        $this->process_store( $vendor_id, get_user_meta( $vendor_id, 'wpsld_store', true ) );
    }

    /**
     * @param $vendor_id
     * @param null $store_id
     */
    public function process_store( $vendor_id, $store_id = null ) {
        if( !dokan_is_user_seller( $vendor_id ) ) return;

        $profile = get_user_meta($vendor_id, 'dokan_profile_settings',true);
        $userdata = get_userdata( $vendor_id );
        !is_array( $profile ) ? $profile = [] : '';

        $args = [
            'post_title' => $profile['store_name'] ? $profile['store_name'] : 'Store for '.$userdata->user_login,
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'wpsl_stores',
            'meta_input' => [
                //attach vendor/user id to store
                'wpsld_vendor' => $vendor_id,

                'wpsl_address' => $profile['address']['street_1'],
                'wpsl_address2' => $profile['address']['street_2'],
                'wpsl_city' => $profile['address']['city'],
                'wpsl_zip' => $profile['address']['zip'],
                'wpsl_country' => $profile['address']['country'],
                'wpsl_state' => $profile['address']['state'],
                'wpsl_lng' => '',
                'wpsl_lat' => '',
            ]
        ];

        if( $store_id ) {
            $args['ID'] = $store_id;
        }

        $store_id = wp_insert_post($args);

        if( $store_id ) {
            //attach store_id to user/vendor
            update_user_meta( $vendor_id, 'wpsld_store', $store_id );
        }
    }

    /**
     * Update store when user is updated
     * as vendor
     * create new store if no store is found
     *
     * @param $user_id
     */
    public function update_for_store( $user_id ) {
        if( dokan_is_user_seller( $user_id ) ) {
            $store_id = get_user_meta( $user_id, 'wpsld_store', true );
            $this->process_store( $user_id, $store_id );
        }
    }

}

function WPSLD_Store_Creator() {
    return WPSLD_Store_Creator::init();
}

WPSLD_Store_Creator();