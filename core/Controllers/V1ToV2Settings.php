<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;


class V1ToV2Settings
{

    protected $v1;
    protected $v2;

    public function __construct($v1, $v2)
    {
        $this->v1 = $v1;
        $this->v2 = $v2;
    }

    public function convertV1toV2()
    {
        $mapping = [
            'general.toc-title' => ['option' => 'toc_title'],
            'general.title-depth' => [
                'option' => 'headings_depth',
                'transform' => [$this, 'convertTitleDepth'],
            ], // add hx before],
            'general.min-headings' => ['option' => 'min_headings'],
            'general.hierarchy-offset' => [
                'option' => 'hierarchy_offset',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'px'],
            ], //|px],
            'general.prefix' => ['option' => 'numeration_type'],
            'general.prefix-separator' => ['option' => 'numeration_separator'],
            'general.prefix-suffix' => ['option' => 'numeration_suffix'],
            'headings-processing.skip-h-by-text' => ['option' => 'skip_h_by_text'],
            'headings-processing.skip-h-by-class' => ['option' => 'skip_h_by_class'],
            'headings-hash.hash-format' => ['option' => 'hash_format'],
            'headings-hash.hash-counter-prefix' => ['option' => 'hash_counter_prefix'],
            // 'support-us.show-credits' => null,
            'auto-insert.position-auto' => ['option' => 'position_auto'],
            'auto-insert.post-types' => ['option' => 'auto_insert_post_types'],
            // 'behaviour.visibility' => null, //complicado!!!],
            'behaviour.smooth-scrolling' => ['option' => 'smooth_scroll'],
            'behaviour.jump-to-offset' => [
                'option' => 'jump_to_offset',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'px'],
            ], //|px],
            'behaviour.headings-overflow' => ['option' => 'headings_overflow'],
            'incontent-behaviour.toggle-position' => ['option' => 'toggle_position'],
            'floating-behaviour.floating-position' => ['option' => 'floating_position'],
            'floating-behaviour.floating-offset-y' => [
                'option' => 'floating_offset_y',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'px'],
            ], //|px],
            'floating-behaviour.floating-offset-y-mobile' => [
                'option' => 'floating_offset_y_mobile',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'px'],
            ], //|px],
            'floating-behaviour.floating-offset-x' => [
                'option' => 'floating_offset_x',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'px'],
            ], //|px],
            'floating-behaviour.expands-on' => ['option' => 'expands_on'],
            'floating-behaviour.collapses-on' => ['option' => 'collapses_on'],
            'floating-behaviour.expanding-animation' => null,
            'columns.columns-mode' => ['option' => 'columns_mode'],
            'columns.columns-min-headings' => ['option' => 'columns_min_headings'],
            'columns.columns-breakpoint' => ['option' => 'columns_breakpoint'],
            'themes.theme' => ['option' => 'theme'],
            'buttons.expand-button-icon' => ['option' => 'toggle_button_icon_closed'],
            'buttons.collapse-button-icon' => ['option' => 'toggle_button_icon_opened'],
            'column-style.columns-separator-style' => ['option' => 'columns_separator_style'],
            'column-style.columns-separator-width' => [
                'option' => 'columns_separator_width',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'px'],
            ],
            'column-style.columns-separator-color' => ['option' => 'columns_separator_color'],
            'table-of-contents.toc-background-color' => ['option' => 'toc_background_color'],
            'table-of-contents.toc-padding' => ['option' => 'toc_padding'], //to array],
            'table-of-contents.min-width' => ['option' => 'toc_min_width'],
            'table-of-contents.max-width' => ['option' => 'toc_max_width'],
            'table-of-contents.width-incontent' => ['option' => 'toc_width_incontent'],
            'table-of-contents.toc-shadow' => ['option' => 'toc_shadow'],
            'table-of-contents.toc-shadow-color' => ['option' => 'toc_shadow_color'],
            'title.title-alignment' => ['option' => 'toc_title_alignment'],
            'title.title-color' => ['option' => 'toc_title_color'],
            'title.title-font-size' => [
                'option' => 'toc_title_font_size',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'em'],
            ], //|em],
            'title.title-font-weight' => ['option' => 'toc_title_font_weight'],
            'prefix.prefix-color' => ['option' => 'numeration_color'],
            'prefix.prefix-hover-color' => ['option' => 'numeration_color_hover'],
            'prefix.prefix-active-color' => ['option' => 'numeration_color_active'],
            'headings.headings-font-size' => [
                'option' => 'headings_link_font_size',
                'transform' => [$this, 'convertValue'],
                'args' => ['unit' => 'em'],
            ], //|em],
            // 'headings.headings-height' => null,
            'headings.headings-color' => ['option' => 'headings_link_color'],
            'headings.headings-hover-color' => ['option' => 'headings_link_color_hover'],
            'headings.headings-active-color' => ['option' => 'headings_link_color_active'],
            'headings.headings-hover-background-color' => ['option' => 'headings_link_background_color_hover'],
            'headings.headings-active-background-color' => ['option' => 'headings_link_background_color_active'],
            'custom-css.css-code' => ['option' => 'css_code'],
        ];

        $v2 = $this->v2;

        if (!is_array($this->v1)) {
            return $v2;
        }

        foreach ($this->v1 as $option => $value) {

            //special op
            if ($option === 'table-of-contents.toc-padding') {
                $v2['toc_padding'] = [
                    'dim' => [
                        'top' => $value,
                        'right' => $value,
                        'bottom' => $value,
                        'left' => $value,
                    ],
                    'unit' => 'px',
                ];
                continue;
            } else if ($option === 'themes.theme') {
                switch ($value) {
                    case 'default':
                        $v2['theme'] = 'original';
                        break;
                    case 'dark':
                        $v2['theme'] = 'original-dark';
                        break;
                    case 'classic':
                        $v2['theme'] = 'basic-light';
                        break;
                    case 'classic-dark':
                        $v2['theme'] = 'basic-dark';
                        break;
                }
                continue;
            } else if ($option === 'buttons.expand-button-icon') {
                $v2['toggle_type'] = 'icon-std';
            } else if ($option === 'behaviour.visibility') {
                switch ($value) {
                    case 'invisible':
                        // hide_main_toc: 1
                        $v2['hide_main_toc'] = '1';
                        break;

                    case 'unfolded-incontent':
                        // fold_on_load: no
                        $v2['fold_on_load'] = 'no';
                        break;

                    case 'unfolded-floating':
                        // fold_on_load: no
                        $v2['fold_on_load'] = 'no';
                        // activate_floating_table_of_contents: 1
                        $v2['activate_floating_table_of_contents'] = '1';
                        break;

                    case 'folded-incontent':
                        // fold_on_load: yes
                        $v2['fold_on_load'] = 'yes';
                        break;

                    case 'folded-floating':
                        // fold_on_load: yes
                        $v2['fold_on_load'] = 'yes';
                        // activate_floating_table_of_contents: 1
                        $v2['activate_floating_table_of_contents'] = '1';
                        break;

                    case 'responsive-incontent':
                        // fold_on_load: responsive
                        $v2['fold_on_load'] = 'responsive';
                        break;

                    case 'responsive-floating':
                        // fold_on_load: responsive
                        $v2['fold_on_load'] = 'responsive';
                        // activate_floating_table_of_contents: 1
                        $v2['activate_floating_table_of_contents'] = '1';
                        break;
                }
                continue;
            }

            if (!isset($mapping[$option]['option'])) {
                // error_log($option . ': ' . $value);
                continue;
            }

            $new_key = $mapping[$option]['option'];

            $op = jtoc_isset_or_null($mapping[$option]['transform']);
            $args = jtoc_isset_or_null($mapping[$option]['args']);

            $new_value = $value;

            if ($op) {
                $parameters = [$value];
                if ($args) {
                    $parameters[] = $args;
                }
                $new_value = call_user_func_array($op, $parameters);
            }

            $v2[$new_key] = $new_value;
        }
        return $v2;
    }

    protected function convertTitleDepth($value)
    {
        if (!is_numeric($value)) {
            return ($value);
        }

        // JTOC()->log('convertTitleDepth: ' . $value);
        $values = [];
        for ($i = 2; $i <= $value; $i++) {
            $values[] = 'h' . $i;
        }

        return implode(',', $values);
    }

    protected function convertValue($value, $args)
    {
        if (!$value) {
            return $value;
        }

        $suffix = jtoc_isset_or_null($args['unit']);

        if ($suffix) {
            // JTOC()->log('convertValue: ' . $value);
            return $value . '|' . $suffix;
        }
        return $value;
    }
}
