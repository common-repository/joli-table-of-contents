<?php

/**
 * @package jolitoc
 */
/*
 * Plugin Name: Joli Table Of Contents
 * Plugin URI: https://wpjoli.com/joli-table-of-contents
 * Description: The most customizable & user friendly Table Of Contents for your website. Works with Gutenberg Block / Shortcode / Auto-insert. 
 * Version: 2.4.0
 * Author: WPJoli
 * Author URI: https://wpjoli.com
 * License: GPLv2 or later
 * Text Domain: joli-table-of-contents
 * Domain Path: /languages 
 * 
 */
defined( 'ABSPATH' ) or die( 'Wrong path bro!' );
$v1_settings = get_option( 'joli_toc_settings', false );
$has_v1 = is_array( $v1_settings ) && $v1_settings !== false;
define( 'JTOC_HAS_V1', $has_v1 );
$use_v1 = false;
if ( $has_v1 ) {
    $use_v1 = get_option( 'joli_toc_use_v1', false );
    //Forces use of v1 the first time if we have the v1 installed
    if ( $has_v1 && $use_v1 === false ) {
        $use_v1 = 1;
        update_option( 'joli_toc_use_v1', 1 );
    } else {
        $use_v1 = intval( $use_v1 );
    }
}
define( 'JTOC_USE_V1', $use_v1 );
$settings_slug = ( $use_v1 === 1 ? 'joli_toc_settings' : 'joli_table_of_contents_settings' );
define( 'JTOC_FS_SETTINGS_SLUG', $settings_slug );
if ( function_exists( 'jtoc_xy' ) ) {
    jtoc_xy()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'jtoc_xy' ) ) {
        function jtoc_xy() {
            global $jtoc_xy;
            if ( !isset( $jtoc_xy ) ) {
                require_once dirname( __FILE__ ) . '/includes/fs/start.php';
                $jtoc_xy = fs_dynamic_init( array(
                    'id'             => '4516',
                    'slug'           => 'joli-table-of-contents',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_e064fd98940b5a52b33eb64b7d517',
                    'is_premium'     => false,
                    'premium_suffix' => '',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                        'slug'    => JTOC_FS_SETTINGS_SLUG,
                        'account' => false,
                        'contact' => false,
                        'support' => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $jtoc_xy;
        }

        jtoc_xy();
        // Signal that SDK was initiated.
        do_action( 'jtoc_xy_loaded' );
    }
    define( 'WPJOLI_JOLI_TOC_BASENAME', plugin_basename( __FILE__ ) );
    // if (strval($use_v1) !== '-1' && $use_v1 !== false){
    if ( $use_v1 !== -1 && $use_v1 !== false ) {
        require_once dirname( __FILE__ ) . '/v1/autoload.php';
        require_once dirname( __FILE__ ) . '/v1/helpers.php';
        require_once dirname( __FILE__ ) . '/v1/fs-helpers.php';
    } else {
        require_once dirname( __FILE__ ) . '/autoload.php';
        require_once dirname( __FILE__ ) . '/helpers.php';
        require_once dirname( __FILE__ ) . '/fs-helpers.php';
    }
    $app = new WPJoli\JoliTOC\Application();
    $app->run();
    register_activation_hook( __FILE__, [$app, 'activate'] );
    register_deactivation_hook( __FILE__, [$app, 'deactivate'] );
}