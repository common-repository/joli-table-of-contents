<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Engine;

class CustomThemes
{

    protected $themes = [];

    public function __construct()
    {
        $this->loadThemes();
    }

    public function loadThemes()
    {
        //Scan for custo themes
        $custom_themes_path = get_stylesheet_directory()  . '/' . JTOC()::CUSTOM_THEMES_PATH;
        $custom_themes_url = get_stylesheet_directory_uri()  . '/' . JTOC()::CUSTOM_THEMES_PATH;

        if (!is_dir($custom_themes_path)) {
            return false;
        }

        $files = scandir($custom_themes_path);

        if (!count($files) > 0) {
            return false;
        }

        $themes = [];

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $theme_styles_path = $custom_themes_path . '/' . $file . '/' . 'jtoc-styles.css';
            $theme_functions_path = $custom_themes_path . '/' . $file . '/' . 'jtoc-functions.php';
            $theme_json_path = $custom_themes_path . '/' . $file . '/' . $file . '.json';
            
            $theme_styles_url = $custom_themes_url . '/' . $file . '/' . 'jtoc-styles.css';
            // $theme_functions_url = $custom_themes_url . '/' . $file . '/' . 'jtoc-functions.php';
            // $theme_json_url = $custom_themes_url . '/' . $file . '/' . $file . '.json';

            //only css is madatory
            if (!is_file($theme_styles_path)) {
                continue;
            }

            $theme = [
                'id' => $file,
                'styles' => is_file($theme_styles_path) ? $theme_styles_url : null,
                'functions' => is_file($theme_functions_path) ? $theme_functions_path : null,
                'info' => is_file($theme_json_path) ? json_decode(file_get_contents($theme_json_path), true) : null,
            ];

            //Adds the current theme to the csutom themes array
            $themes[] = $theme;
        }

        $this->themes = $themes;
    }

    public function getThemes()
    {
        return $this->themes;
    }

    public function getTheme($id)
    {
        if (!$id) {
            return false;
        }

        $theme = array_filter($this->themes, function ($theme) use ($id) {
            return $theme['id'] === $id;
        });


        return $theme[0];
    }
}
