<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Controllers\SettingsController;
use WPJoli\JoliTOC\Controllers\PostTypeSettingController;
use WPJoli\JoliTOC\Engine\CustomThemes;

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
        //Registers the menu afters functions.php has been processed to allow custom filter hooks for joli_toc_settings_capability
        add_action('after_setup_theme', [$this, 'setup']);
    }

    public function setup()
    {
        // $this->option_group = Application::SLUG . '_settings';
        $this->option_group = Application::SETTINGS_V2_SLUG;

        $this->setPages();
        // $this->setSubpages();

        $this->addPages($this->pages)->withSubPage('Settings')->addSubPages($this->subpages);

        // $this->logo_url = JTOC()->url('assets/admin/img/wpjoli-logo-new-small.png');
        $this->logo_url = JTOC()->url('assets/admin/img/wpjoli-logo-2023.svg');
    }

    /**
     * Array of menu pages
     * To be defined manually
     */
    public function setPages()
    {
        $capability = apply_filters('joli_toc_settings_capability', 'manage_options');

        $allowed_cap = ['manage_options', 'edit_pages'];
        //allow custom capability only if current user is allowed
        if (!in_array($capability, $allowed_cap)) {
            $capability = 'manage_options'; // default value
        }

        //Wordpress filter that allows saving settings
        add_filter(
            'option_page_capability_' . Application::SETTINGS_V2_SLUG,
            function ($cap) use ($capability) {
                return $capability;
            }
        );

        $this->pages = [
            [
                'page_title' => Application::NAME,
                'menu_title' => Application::NAME,
                'capability' => $capability,
                // 'capability' => 'edit_pages',
                'menu_slug' => $this->option_group,
                'callback' => [$this, 'displaySettingsPage'],
                // 'icon_url' => Application::instance()->url('/assets/admin/img/' . 'joli-toc-wp-dashicon-white-alt.png'),
                // 'icon_url' => 'data:image/svg+xml;base64,' . base64_encode('<svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.cls-1,.cls-2{fill:#fff;}.cls-1{opacity:0.5;}</style></defs><g id="Flame"><path class="cls-1" d="M161.2,196.71C201.19,108,166,39.32,173,32.58c30.49-29.45,157.86,79.6,197.09,159.76,51.1,104.2,56.8,175.15,34.67,217-19.09,36.28-76,86.24-178.57,77C155.12,480,47.69,448.71,161.2,196.71Z"/><path class="cls-1" d="M179.34,180.28c52-68,50.34-149.13,57.75-153.5,32-18.71,105,118.26,122.81,196.53,23.18,101.82,13.87,164.7-13.48,196.05-23.65,27.16-82.54,57.75-169,26.78C117.5,424.68,31.35,373.48,179.34,180.28Z"/><path class="cls-2" d="M188.08,226c33.24-70.86,16.24-141.72,22-147.13,24.89-23.37,115.41,81,146,146,39.79,84.34,43.5,141.52,25.26,174.86-15.77,29-62.12,68.49-144.47,59.94C179.81,453.74,93.76,427.33,188.08,226Z"/></g></svg>'),
                'icon_url' => 'data:image/svg+xml;base64,' . 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48ZGVmcz48c3R5bGU+LmNscy0xLC5jbHMtMntmaWxsOiNmZmY7fS5jbHMtMXtvcGFjaXR5OjAuNTt9PC9zdHlsZT48L2RlZnM+PGcgaWQ9IkZsYW1lIj48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xNjEuMiwxOTYuNzFDMjAxLjE5LDEwOCwxNjYsMzkuMzIsMTczLDMyLjU4YzMwLjQ5LTI5LjQ1LDE1Ny44Niw3OS42LDE5Ny4wOSwxNTkuNzYsNTEuMSwxMDQuMiw1Ni44LDE3NS4xNSwzNC42NywyMTctMTkuMDksMzYuMjgtNzYsODYuMjQtMTc4LjU3LDc3QzE1NS4xMiw0ODAsNDcuNjksNDQ4LjcxLDE2MS4yLDE5Ni43MVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xNzkuMzQsMTgwLjI4YzUyLTY4LDUwLjM0LTE0OS4xMyw1Ny43NS0xNTMuNSwzMi0xOC43MSwxMDUsMTE4LjI2LDEyMi44MSwxOTYuNTMsMjMuMTgsMTAxLjgyLDEzLjg3LDE2NC43LTEzLjQ4LDE5Ni4wNS0yMy42NSwyNy4xNi04Mi41NCw1Ny43NS0xNjksMjYuNzhDMTE3LjUsNDI0LjY4LDMxLjM1LDM3My40OCwxNzkuMzQsMTgwLjI4WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTE4OC4wOCwyMjZjMzMuMjQtNzAuODYsMTYuMjQtMTQxLjcyLDIyLTE0Ny4xMywyNC44OS0yMy4zNywxMTUuNDEsODEsMTQ2LDE0NiwzOS43OSw4NC4zNCw0My41LDE0MS41MiwyNS4yNiwxNzQuODYtMTUuNzcsMjktNjIuMTIsNjguNDktMTQ0LjQ3LDU5Ljk0QzE3OS44MSw0NTMuNzQsOTMuNzYsNDI3LjMzLDE4OC4wOCwyMjZaIi8+PC9nPjwvc3ZnPg==',
                'position' => 110
            ]
        ];
    }

    /**
     * Array of submenu pages
     * To be defined manually
     */
    // public function setSubpages()
    // {
    //     $this->subpages = [
    //         [
    //             'parent_slug' => $this->option_group,
    //             'page_title' => 'User guide',
    //             'menu_title' => 'User guide',
    //             'capability' => 'manage_options',
    //             'menu_slug' => Application::SLUG . '_user_guide',
    //             'callback' => [$this, 'displayUserGuidePage']
    //         ],
    //     ];
    // }

    public function addPages(array $pages)
    {
        $this->admin_pages = $pages;
        return $this;
    }

    public function withSubPage(string $title = null)
    {
        if (empty($this->admin_pages)) {
            return $this;
        }
        $admin_page = $this->admin_pages[0];
        $subpage = [
            [
                'parent_slug' => $admin_page['menu_slug'],
                'page_title' => $admin_page['page_title'],
                'menu_title' => ($title) ? $title : $admin_page['menu_title'],
                'capability' => $admin_page['capability'],
                'menu_slug' => $admin_page['menu_slug'],
                'callback' => $admin_page['callback']
            ]
        ];
        $this->admin_subpages = $subpage;
        return $this;
    }

    public function addSubPages(array $pages)
    {
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);
        return $this;
    }

    public function addAdminMenu()
    {
        foreach ($this->admin_pages as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
        }
        foreach ($this->admin_subpages as $page) {
            add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
        }
    }

    public function displayMainPage()
    {
        JTOC()->render(['admin' => 'main']);
    }

    // public function displayUserGuidePage()
    // {

    //     $tabs = [
    //         'quick-start' => __('Quick start', 'joli-table-of-contents'),
    //         'quick-setup' => __('Quick setup', 'joli-table-of-contents'),
    //         'shortcode' => __('Shortcode', 'joli-table-of-contents'),
    //         'documentation' => __('Documentation', 'joli-table-of-contents'),
    //         'hooks' => __('Hooks (for developers)', 'joli-table-of-contents'),
    //     ];

    //     $data = [
    //         'tabs' => $tabs,
    //         'logo_url' => $this->logo_url,
    //     ];

    //     JTOC()->render(['admin/user-guide' => 'user-guide'], $data);
    // }


    public function displaySettingsPage()
    {
        $settings = JTOC()->requestService(SettingsController::class);
        $groups = $settings->getGroups();

        $tabs = [];
        foreach ($groups as $group) {
            $tabs[$group['id']] = [
                'label' => $group['label'],
                'args' => $group['args'],
            ];
        }

        $plugin_info = get_plugin_data(JTOC()->path('joli-table-of-contents.php'));

        $wpjoli_url = 'https://wpjoli.com/';
        $base_url = 'https://wpjoli.com/joli-table-of-contents/';
        $params = '?utm_source=' . getHostURL() . '&utm_medium=admin-settings&utm_campaign=joli-table-of-contents-settings';

        /**
         * Since 2.0.0
         */
        $post_types = get_post_types(
            ['public' => true],
            'objects'
        );

        $admin_url = get_admin_url();

        /** @var PostTypeSettingController $oc */
        $ptsc = JTOC()->requestService(PostTypeSettingController::class);
        $activated_post_type = $ptsc->getActivatedPostType();

        /** @var CustomThemes $custom_themes */
        $themes_controller = JTOC()->requestService(CustomThemes::class);
        $custom_themes = $themes_controller->getThemes();
        // JTOC()->log($custom_themes);

        $data = [
            'option_group' => $this->option_group,
            'tabs' => $tabs,
            'logo_url' => $this->logo_url,
            'version' => isset($plugin_info['Version']) ? $plugin_info['Version'] : '',
            'pro_url' => $base_url . $params,
            'pro_url_v' => $base_url . '#visibilities' . $params,
            'pro_features' => [
                __("Custom settings per post type", "joli-table-of-contents"),
                __("Customize individual TOC block", "joli-table-of-contents"),
                __("Sidebar sticky TOC", "joli-table-of-contents"),
                __("Floating widget", "joli-table-of-contents"),
                __("Slide-out widget", "joli-table-of-contents"),
                __("Progress bar", "joli-table-of-contents"),
                __("Advanced auto-insert rules", "joli-table-of-contents"),
                __("Dynamic unfold", "joli-table-of-contents"),
                __("Skip headings by ascending class", "joli-table-of-contents"),
                __("Columns mode", "joli-table-of-contents"),
                __("Additional themes", "joli-table-of-contents"),
                __("Premium support", "joli-table-of-contents"),
            ],

            'plugins' => [
                [
                    'name' => "Smart Auto \nFeatured Image",
                    'highlight' => "NEW PLUGIN !",
                    'url' => $wpjoli_url . 'smart-auto-featured-image/' . $params,
                    'desc' => "Generate Featured Images automatically based on your post content (title, etc).\nCustomize your featured image with the built in template editor.",
                    'thumb' => JTOC()->url('assets/admin/img/plugins/wpjoli-smart-auto-featured-image.png'),
                ],
                [
                    'name' => 'Joli FAQ SEO',
                    'url' => $wpjoli_url . 'joli-faq-seo/' . $params,
                    'desc' => "WordPress FAQ plugin:\nEasy & fast single page drag-n-drop editor, lightweight, no jQuery, block-enabled, schema.org, optimized for SEO.",
                    'thumb' => JTOC()->url('assets/admin/img/plugins/wpjoli-joli-faq-seo.png'),
                ],
                [
                    'name' => 'Joli CLEAR Lightbox',
                    'url' => $wpjoli_url . 'joli-clear-lightbox/' . $params,
                    'desc' => "Ultralight Lightbox for WordPress.\nDesigned for Speed. No jQuery. Responsive with gestures. Simple, Elegant & Powerful.",
                    'thumb' => JTOC()->url('assets/admin/img/plugins/wpjoli-joli-clear-lightbox.png'),
                ],
            ],
            // 'joli_faq_seo_url' => $wpjoli_url . 'joli-faq-seo/' . $params,
            // 'joli_clear_lightbox_url' => $wpjoli_url . 'joli-clear-lightbox/' . $params,
            'joli_toc_review_url' => 'https://wordpress.org/support/plugin/' . JTOC()::WP_ORG_SLUG . '/reviews/?rate=5#new-post',
            'joli_toc_doc_url' => $wpjoli_url . 'docs/joli-table-of-contents/' . $params,
            'joli_toc_doc_post_type_settings_url' => $wpjoli_url . 'docs/joli-table-of-contents/settings/post-type-settings/' . $params,
            //since 2.0.0
            'post_types' => $post_types, //label, name, menu_icon
            'admin_url' => $admin_url,
            'jtoc_settings_url' => sprintf('%sadmin.php?page=' . JTOC()::SETTINGS_V2_SLUG, $admin_url),
            'active_post_type' => jtoc_isset_or_null($_GET['jtoc_post_type'], true),
            'activated_post_type' => $activated_post_type,
            'js_vars' => ['jtoc_custom_themes' => $custom_themes],

            // 'block_json' => $settings->getJSONAttributes(),
            // 'block_template' => $settings->getBlockTemplate(),
        ];

        JTOC()->render(['admin' => 'settings'], $data);
    }
}
