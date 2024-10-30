<?php

namespace WPJoli\JoliTOC;

use WPJoli\JoliTOC\JoliApplication;
use WPJoli\JoliTOC\Activator;
use WPJoli\JoliTOC\Hooks;

class Application extends JoliApplication
{

    const NAME = 'Joli TOC';
    const SLUG = 'joli_toc';
    const WP_ORG_SLUG = 'joli-table-of-contents';
    const VERSION = '2.4.0';
    const SETTINGS_SLUG = 'joli_toc_settings';
    const SETTINGS_V2_SLUG = 'joli_table_of_contents_settings';
    const DOMAIN = 'joli-toc';
    const ID = 'jolitoc';
    const USE_MINIFIED_ASSETS = true;
    // const SHORTCODE_TEMP_TAG = '<!--wpjoli-joli-table-of-contents:toc[#]-->';
    // const SHORTCODE_TEMP_TAG_REGEX_PATTERN = '/<!--wpjoli-joli-table-of-contents:toc\[(\d)\]-->/';
    const SHORTCODE_TEMP_TAG = '<!--wpjoli-joli-table-of-contents:toc:#-->';
    const SHORTCODE_TEMP_TAG_REGEX_PATTERN = '/<!--wpjoli-joli-table-of-contents:toc:(\d)-->/';

    const CUSTOM_THEMES_PATH = 'joli-table-of-contents/themes';

    protected $hooks;
    public $isProcessingShortcode = false;
    public $isBuildingShortcode = false;
    public $isProcessingMultipage = false;
    public $the_content = null;
    public $the_ID = null;

    public $options; 

    public function __construct()
    {
        // static::$instance = $this;
        parent::__construct();

        load_plugin_textdomain(
            'joli-table-of-contents',
            false,
            trailingslashit(plugin_basename($this->path()) . '/languages')
        );
        // add_action(
        //     'plugins_loaded',function () {
        //         $this->log('load languages');
        //         load_plugin_textdomain('joli-toc',false,
        //             trailingslashit(plugin_basename($this->path()) . '/languages')
        //         );
        //     }
        // );
        
        $this->log = new Log($this);
    }

    public function setTheContent($content)
    {
        $this->the_content = $content;
    }

    public function getTheContent()
    {
        return $this->the_content;
    }

    public function run()
    {
        $this->hooks = new Hooks($this);
        $this->hooks->run();
    }
    // public function run()
    // {
    //     add_action('after_setup_theme', [$this, 'rungo']);
    // }

    public function activate()
    {
        $activator = new Activator();
        $activator->activate();
    }

    public function deactivate()
    {
    }
}
