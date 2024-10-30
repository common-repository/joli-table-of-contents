<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC;

use WPJoli\JoliTOC\Controllers\Blocks;
use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Controllers\AdminController;
use WPJoli\JoliTOC\Controllers\AdminNotices;
// use WPJoli\JoliTOC\Controllers\DocumentSettingPanel;
use WPJoli\JoliTOC\Controllers\MenuController;
use WPJoli\JoliTOC\Controllers\PublicAppController;
use WPJoli\JoliTOC\Controllers\SettingsController;
use WPJoli\JoliTOC\Controllers\ShortcodesController;
use WPJoli\JoliTOC\Controllers\NoticesFreeController;
use WPJoli\JoliTOC\Controllers\PostTypeSettingController;
use WPJoli\JoliTOC\Controllers\RestApi;
use WPJoli\JoliTOC\Engine\CustomThemes;
class Hooks {
    protected $app;

    protected $admin;

    protected $menu;

    protected $public_app;

    protected $settings;

    protected $shortcodes;

    protected $notices_free;

    protected $notices;

    protected $pt_setting;

    // protected $dsp;
    protected $themes;

    protected $blocks;

    protected $rest;

    public function __construct( Application &$app ) {
        $this->app = $app;
        $this->admin = $app->requestService( AdminController::class );
        $this->menu = $app->requestService( MenuController::class );
        $this->public_app = $app->requestService( PublicAppController::class );
        $this->settings = $app->requestService( SettingsController::class );
        $this->shortcodes = $app->requestService( ShortcodesController::class );
        $this->pt_setting = $app->requestService( PostTypeSettingController::class );
        // $this->dsp = $app->requestService(DocumentSettingPanel::class);
        $this->themes = $app->requestService( CustomThemes::class );
        $this->rest = $app->requestService( RestApi::class );
        $this->notices = $app->requestService( AdminNotices::class );
        if ( version_compare( $GLOBALS['wp_version'], '5.0', '>=' ) ) {
            $this->blocks = $app->requestService( Blocks::class );
        }
        if ( jtoc_xy()->is_free_plan() ) {
            $this->notices_free = $app->requestService( NoticesFreeController::class );
        }
    }

    public function run() {
        $this->registerAdminHooks();
        $this->registerPublicHooks();
        $this->registerIntegrations();
    }

    private function registerAdminHooks() {
        // add_action( 'init',                                 [ $this->notices,           'initNotices' ] );
        add_action( 'wp_ajax_joli_toc_handle_v2_notice', [$this->notices, 'jtocHandleV2Notice'] );
        //actions
        if ( jtoc_xy()->is_free_plan() ) {
            add_action( 'init', [$this->notices_free, 'initNotices'] );
            add_action( 'wp_ajax_joli_toc_handle_notice', [$this->notices_free, 'jtocHandleNotice'] );
        }
        add_action( 'wp_ajax_joli_toc_update_active_post_type_setting', [$this->pt_setting, 'updatePostTypeSetting'] );
        // add_action('wp_ajax_joli_toc_save_user_settings',               [$this->settings, 'saveUserSetting']);
        add_action( 'wp_ajax_joli_toc_export_user_settings', [$this->settings, 'exportUserSetting'] );
        add_action( 'wp_ajax_joli_toc_import_user_settings', [$this->settings, 'importUserSetting'] );
        add_action( 'init', [$this->settings, 'handleResetSettings'] );
        // add_action( 'plugins_loaded',                       [ $this->app,           'registerLanguages' ] );
        add_action( 'admin_enqueue_scripts', [$this->admin, 'enqueueAssets'] );
        add_action( 'admin_menu', [$this->menu, 'addAdminMenu'] );
        add_action( 'admin_init', [$this->settings, 'registerSettings'] );
        //Registers the block for WP version above 5.0
        if ( version_compare( $GLOBALS['wp_version'], '5.0', '>=' ) ) {
            add_action( 'init', [$this->blocks, 'registerBlocks'] );
        }
        //filters
        add_filter( 'plugin_action_links_' . plugin_basename( JTOC()->path( 'joli-table-of-contents.php' ) ), [$this->admin, 'addSettingsLink'] );
        /**
         * Since 2.0.0
         */
        // add_filter('init',                                      [$this->dsp, 'registerMetas']);
        // add_filter('enqueue_block_editor_assets',               [$this->dsp, 'enqueueAssets']);
        add_action( 'rest_api_init', [$this->rest, 'registerRestRoutes'] );
    }

    private function registerPublicHooks() {
        //only for front end, avoid interferences with the editor
        if ( jtoc_is_front() ) {
            //actions
            add_action( 'init', [$this->shortcodes, 'registerShortcodes'] );
            //since v2.0.6
            add_action( 'wp_enqueue_scripts', [$this->public_app, 'enqueueResources'] );
            //filters
            // add_filter('the_content',            [ $this->public_app,    'joliTocFilterTheContent'],    1000);
            add_action( 'init', function () {
                $priority = apply_filters( 'joli_toc_the_content_filter_priority', 10001 );
                add_filter( 'the_content', [$this->shortcodes, 'beforeTheContent'], -100000 );
                add_filter( 'the_content', [$this->public_app, 'joliTocFilterTheContent'], $priority );
                add_filter( 'the_content', [$this->shortcodes, 'filterTheContentShortcode'], $priority + 1 );
            } );
            // add_filter('the_content', [ $this->shortcodes,    'theContent'], 9999);
        }
    }

    //Integrations - since 1.3.8
    private function registerIntegrations() {
        add_action( 'plugins_loaded', function () {
            if ( class_exists( '\\RankMath' ) ) {
                $rm = \WPJoli\JoliTOC\Integrations\RankMath::class;
                new $rm();
            }
        } );
    }

}
