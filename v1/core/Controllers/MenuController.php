<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Controllers\SettingsController;

class MenuController
{

    public $admin_pages = [];
    public $admin_subpages = [];
    public $pages = [];
    public $subpages = [];

    protected $option_group;
    protected $logo_url;

    public function __construct()
    {
        $this->option_group = Application::SLUG . '_settings';

        $this->setPages();
        $this->setSubpages();
        
        $this->addPages( $this->pages )->withSubPage('Settings')->addSubPages( $this->subpages );

        $this->logo_url = JTOC()->url('assets/admin/img/wpjoli-logo-new-small.png');
    }

    /**
     * Array of menu pages
     * To be defined manually
     */
    public function setPages()
    {
        $this->pages = [
            [
                'page_title' => Application::NAME,
                'menu_title' => Application::NAME,
                'capability' => 'manage_options',
                'menu_slug' => $this->option_group,
                'callback' => [$this, 'displaySettingsPage'],
                'icon_url' => Application::instance()->url('/assets/admin/img/' . 'wpjoli-joli-wp-dashicon-white.png'),
                'position' => 110
            ]
        ];
    }

     /**
     * Array of submenu pages
     * To be defined manually
     */
    public function setSubpages()
    {
        $this->subpages = [
            [
                'parent_slug' => $this->option_group,
                'page_title' => 'User guide',
                'menu_title' => 'User guide',
                'capability' => 'manage_options',
                'menu_slug' => Application::SLUG . '_user_guide',
                'callback' => [$this, 'displayUserGuidePage']
            ],
        ];
    }

    public function addPages( array $pages )
    {
        $this->admin_pages = $pages;
        return $this;
    }

    public function withSubPage( string $title = null )
    {
        if ( empty( $this->admin_pages ) ) {
            return $this;
        }
        $admin_page = $this->admin_pages[ 0 ];
        $subpage = [
            [
                    'parent_slug' => $admin_page[ 'menu_slug' ],
                    'page_title' => $admin_page[ 'page_title' ],
                    'menu_title' => ($title) ? $title : $admin_page[ 'menu_title' ],
                    'capability' => $admin_page[ 'capability' ],
                    'menu_slug' => $admin_page[ 'menu_slug' ],
                    'callback' => $admin_page[ 'callback' ]
            ]
        ];
        $this->admin_subpages = $subpage;
        return $this;
    }

    public function addSubPages( array $pages )
    {
        $this->admin_subpages = array_merge( $this->admin_subpages, $pages );
        return $this;
    }

    public function addAdminMenu()
    {
        foreach ( $this->admin_pages as $page ) {
            add_menu_page( $page[ 'page_title' ], $page[ 'menu_title' ], $page[ 'capability' ], $page[ 'menu_slug' ], $page[ 'callback' ], $page[ 'icon_url' ], $page[ 'position' ] );
        }
        foreach ( $this->admin_subpages as $page ) {
            add_submenu_page( $page[ 'parent_slug' ], $page[ 'page_title' ], $page[ 'menu_title' ], $page[ 'capability' ], $page[ 'menu_slug' ], $page[ 'callback' ] );
        }
    }
    
    public function displayMainPage()
    {
        JTOC()->render( [ 'admin' => 'main' ] );
    }
    
    public function displayUserGuidePage()
    {

        $tabs = [
            'quick-start' => __( 'Quick start', 'joli-table-of-contents'),
            'quick-setup' => __( 'Quick setup', 'joli-table-of-contents'),
			'shortcode' => __( 'Shortcode', 'joli-table-of-contents'),
			'documentation' => __( 'Documentation', 'joli-table-of-contents'),
			'hooks' => __( 'Hooks (for developers)', 'joli-table-of-contents'),
        ];

        $data = [
            'tabs' => $tabs,
            'logo_url' => $this->logo_url,
        ];

        JTOC()->render( [ 'admin/user-guide' => 'user-guide' ], $data );
    }
       
    
    public function displaySettingsPage()
    {
        $tabs = [
			'general' => __( 'General', 'joli-table-of-contents'), //id must match tab-general in the "class" args
			'appearance' => __( 'Appearance', 'joli-table-of-contents'),
            'dimensions' => __( 'Dimensions', 'joli-table-of-contents'),
            
        ];

        $settings = JTOC()->requestService(SettingsController::class);
        $groups = $settings->getGroups();
        
        $tabs = [];
        foreach($groups as $group){
            $tabs[ $group[ 'id' ] ] = $group[ 'label' ];
        }

        // $plugin_info = get_plugin_data( JTOC()->path('joli-table-of-contents.php') );

        $base_url = 'https://wpjoli.com/joli-table-of-contents/';
        $params = '?utm_source=' . getHostURL() . '&utm_medium=admin-settings';

        $data = [
            'option_group' => $this->option_group,
            'tabs' => $tabs,
            'logo_url' => $this->logo_url,
            'version' => '1.3.9',
            'pro_url' => $base_url . $params,
            'pro_url_v' => $base_url . '#visibilities' . $params,
        ];

        JTOC()->render( [ 'admin' => 'settings' ], $data );
    }

}
