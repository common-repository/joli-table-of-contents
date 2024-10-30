<?php

namespace WPJoli\JoliTOC;

use WPJoli\JoliTOC\JoliApplication;
use WPJoli\JoliTOC\Activator;
use WPJoli\JoliTOC\Hooks;

class Application extends JoliApplication
{

    const NAME = 'Joli TOC';
    const SLUG = 'joli_toc';
    const SETTINGS_SLUG = 'joli_toc_settings';
    const DOMAIN = 'joli-toc';
    const ID = 'jolitoc';
    const USE_MINIFIED_ASSETS = true;

    protected $hooks;

    public $options;

    public function __construct()
    {
        // static::$instance = $this;
        parent::__construct();
        
        load_plugin_textdomain('joli-toc',false,
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

    public function run()
    {
        $this->hooks = new Hooks( $this );
        $this->hooks->run();
    }

    public function activate()
    {
        $activator = new Activator();
        $activator->activate();
    }

    public function deactivate()
    { }

}
