<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package jolitoc
 * @author     Michael ANDRE <mxlaxe@gmail.com>
 */

namespace WPJoli\JoliTOC;

use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Controllers\OptionsController;
use WPJoli\JoliTOC\Controllers\SettingsController;

class Activator
{

    public function activate()
    {
        //app settings
        $settings = JTOC()->requestService(SettingsController::class);
        $settings->setupSettings();
    }
}
?>
