<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Application;

class DocumentSettingPanel
{

    public function enqueueAssets()
    {


        $asset_file = include(JTOC()->path('gutenberg/admin/wpjoli-joli-toc-sidebar/index.asset.php'));
        wp_enqueue_script(
            'wpjoli-joli-toc-sidebar-scripts',
            JTOC()->url('gutenberg/admin/wpjoli-joli-toc-sidebar/index.js', JTOC()::USE_MINIFIED_ASSETS),
            $asset_file['dependencies'],
            $asset_file['version']
            // ['wp-edit-post', 'wp-data', 'wp-components'], 
            // time()
        );

        // wp_localize_script( 
        //     'wpjoli-joli-toc-sidebar-scripts',
        //     'jtocAdminData',
        //     []
        // );
    }

    public function registerMetas()
    {

        register_post_meta('', '_jtoc_post_settings', [
            'single' => true,
            'type' => 'object',
            'show_in_rest' => [
                'schema' => [
                    'type'       => 'object',
                    'properties' => [
                        'show_credits'    => [
                            'type' => 'number',
                        ],
                        'show_logo'    => [
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }
}
