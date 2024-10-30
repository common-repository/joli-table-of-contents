<?php

namespace WPJoli\JoliTOC\Integrations;

class RankMath
{

    public function __construct()
    {

        add_filter('rank_math/researches/toc_plugins', function ($plugins) {
            $plugins[WPJOLI_JOLI_TOC_BASENAME] = JTOC()::NAME;
            
            return $plugins;
        });
    }
}
