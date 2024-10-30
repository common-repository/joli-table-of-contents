<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Application;
class AdminController {
    public function enqueueAssets( $hook_suffix ) {
        // JTOC()->log($hook_suffix);
        //enqueues scripts/styles only for admin page than contain "joli_toc" in the hook suffix or in posts
        // if ( $hook_suffix == 'post.php' || stripos( $hook_suffix, JTOC()::SLUG ) !== false ) {
        $jtoc = JTOC();
        if ( stripos( $hook_suffix, $jtoc::SLUG ) !== false || stripos( $hook_suffix, $jtoc::SETTINGS_V2_SLUG ) !== false ) {
            wp_enqueue_style(
                'wpjoli-joli-toc-admin-styles',
                JTOC()->url( 'assets/admin/css/joli-toc-admin.css', $jtoc::USE_MINIFIED_ASSETS ),
                [],
                $jtoc::VERSION
            );
            wp_enqueue_style(
                'wpjoli-joli-toc-public-styles',
                JTOC()->url( 'assets/public/css/wpjoli-joli-table-of-contents.css', $jtoc::USE_MINIFIED_ASSETS ),
                [],
                $jtoc::VERSION
            );
            // wp_enqueue_style('wpjoli-joli-toc-admin-gg-icons', JTOC()->url('assets/public/css/' . jtoc_fs_file('gg-icons') . '.css', $jtoc::USE_MINIFIED_ASSETS), [], $jtoc::VERSION);
            wp_enqueue_script(
                'wpjoli-joli-toc-admin-scripts',
                JTOC()->url( 'assets/admin/js/joli-toc-admin.js', $jtoc::USE_MINIFIED_ASSETS ),
                ['jquery', 'wp-color-picker'],
                $jtoc::VERSION,
                true
            );
            wp_localize_script( 'wpjoli-joli-toc-admin-scripts', 'jtocAdmin', [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( $jtoc::SLUG ),
            ] );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script(
                'wpjoli-joli-toc-admin-wp-color-picker-alpha',
                JTOC()->url( 'vendor/wp-color-picker-alpha/wp-color-picker-alpha.min.js' ),
                ['wp-color-picker'],
                '3.0.2',
                true
            );
        }
        wp_enqueue_script(
            'wpjoli-joli-toc-admin-notice-scripts',
            JTOC()->url( 'assets/admin/js/joli-toc-admin-notices.js', $jtoc::USE_MINIFIED_ASSETS ),
            ['jquery'],
            $jtoc::VERSION,
            true
        );
        wp_localize_script( 'wpjoli-joli-toc-admin-notice-scripts', 'jtocAdminNotice', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'jtoc_admin_notices' ),
        ] );
    }

    public function addSettingsLink( $links ) {
        $jtoc = JTOC();
        $joli_link = '<a href="' . admin_url( 'admin.php?page=' . $jtoc::SETTINGS_V2_SLUG ) . '">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $joli_link );
        return $links;
    }

}
