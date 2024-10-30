<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Application;
class AdminController {
    public function enqueueAssets( $hook_suffix ) {
        //enqueues scripts/styles only for admin page than contain "joli_toc" in the hook suffix or in posts
        if ( $hook_suffix == 'post.php' || stripos( $hook_suffix, JTOC()::SLUG ) !== false ) {
            wp_enqueue_style(
                'wpjoli-joli-toc-admin-styles',
                JTOC()->url( 'assets/admin/css/joli-toc-admin.css', JTOC()::USE_MINIFIED_ASSETS ),
                [],
                '1.3.9'
            );
            wp_enqueue_style(
                'wpjoli-joli-toc-admin-gg-icons',
                JTOC()->url( 'assets/public/css/' . jtoc_fs_file( 'gg-icons' ) . '.css', JTOC()::USE_MINIFIED_ASSETS ),
                [],
                '1.3.9'
            );
            wp_enqueue_script(
                'wpjoli-joli-toc-admin-scripts',
                JTOC()->url( 'assets/admin/js/joli-toc-admin.js', JTOC()::USE_MINIFIED_ASSETS ),
                ['jquery', 'wp-color-picker'],
                '1.3.9',
                true
            );
            wp_localize_script( 'wpjoli-joli-toc-admin-scripts', 'jtocAdmin', [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            ] );
            //        wp_enqueue_script( 'accordion', $this->plugin_url . '../../wp-content/wp-admin/js/accordion.min.js' );
            wp_enqueue_style( 'wp-color-picker' );
            // wp_enqueue_script('wpjoli-wp-color-picker-alpha', JTOC()->url('vendor/wp-color-picker-alpha/wp-color-picker-alpha.min.js'), ['wp-color-picker'], '1.0.0', true);
        }
        // if ( jtoc_xy()->is_free_plan() ){
        wp_enqueue_script(
            'wpjoli-joli-toc-admin-notice-scripts',
            JTOC()->url( 'assets/admin/js/joli-toc-admin-notices.js', JTOC()::USE_MINIFIED_ASSETS ),
            ['jquery'],
            '1.3.9',
            true
        );
        wp_localize_script( 'wpjoli-joli-toc-admin-notice-scripts', 'jtocAdminNotice', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'jtoc_admin_notices' ),
        ] );
        // }
    }

    public function addSettingsLink( $links ) {
        $joli_link = '<a href="' . admin_url( 'admin.php?page=' . JTOC()::SETTINGS_SLUG ) . '">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $joli_link );
        return $links;
    }

}
