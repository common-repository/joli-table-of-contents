<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC\Engine;

use WPJoli\JoliTOC\Engine\ContentProcessing;
use WPJoli\JoliTOC\Controllers\SettingsController;
use WPJoli\JoliTOC\Controllers\PostTypeSettingController;
class TOCBuilder {
    protected $sc;

    //SettingsController
    protected $headings;

    protected $content;

    protected $options;

    protected $is_in_the_content;

    public function __construct( $headings = null, $content = null, $options = null ) {
        $this->headings = $headings;
        $this->content = $content;
        $this->setOptions( $options );
        $this->is_in_the_content = current_filter() === 'the_content';
    }

    public function setHeadings( $headings ) {
        $this->headings = $headings;
    }

    public function setContent( $content ) {
        $this->content = $content;
    }

    public function getOptions() {
        return $this->options;
    }

    /**
     * Undocumented function
     *
     * @param [array] $options Options that will override default settings
     * @return void
     */
    public function setOptions( $options = null, $additional_options = null ) {
        $pt = get_post_type();
        /** @var PostTypeSettingController $ptsc */
        $ptsc = JTOC()->requestService( PostTypeSettingController::class );
        $is_post_type_activated = $ptsc->isPostTypeSettingActivated( $pt );
        // pre($is_post_type_activated);
        /** @var SettingsController $sc */
        $this->sc = JTOC()->requestService( SettingsController::class );
        $global_options = $this->sc->getOptions( false );
        //Get global user settings
        $option_fields = null;
        if ( $additional_options ) {
            $all_options = $this->sc->getOptions();
        }
        if ( $additional_options && $all_options ) {
            $all_options = array_merge( $additional_options, $all_options );
            $global_options = array_merge( $additional_options, $global_options );
            $option_fields = $this->sc->getFieldsIDs( $additional_options );
        } else {
            $option_fields = $this->sc->getFieldsIDs();
            // pre($option_fields);
        }
        if ( $options !== null ) {
            // pre($option_fields);
        }
        foreach ( $option_fields as $option_id ) {
            $this->options[$option_id] = jtoc_get_option( $option_id, $options, $global_options );
            // if($option_id === 'headings_depth'){
            //     // JTOC()->log($all_options);
            //     JTOC()->log($options[$option_id]);
            //     JTOC()->log($global_options[$option_id]);
            // }
        }
        // JTOC()->log($this->options['headings_depth']);
    }

    public function getOption( $option ) {
        return jtoc_isset_or_null( $this->options[$option] );
    }

    public function makeTOC( $headings_processed = null ) {
        $args = null;
        $content = $this->content;
        $headings = $this->headings;
        $options = $this->options;
        // pre($options);
        $data = [];
        // content processing: get the headings and returns content with idfied headings
        if ( $headings_processed ) {
            $headings = $headings_processed;
        } else {
            if ( $content ) {
                $processed_content = ContentProcessing::Process(
                    $content,
                    $args,
                    $this,
                    jtoc_get_multipaged_content()
                );
                $headings = $processed_content['headings'];
            }
        }
        //Hide certain headings from the TOC block
        $hidden_headings = jtoc_isset_or_null( $options['hidden_headings'] );
        if ( $hidden_headings && is_array( $hidden_headings ) && count( $hidden_headings ) > 0 ) {
            $headings = array_filter( $headings, function ( $item ) use($hidden_headings) {
                //skips headings in the list of hidden_headings
                return !in_array( $item['id'], $hidden_headings );
            } );
            //reset the array indexes
            $headings = array_values( $headings );
        }
        //Edit certain headings from the TOC block
        $edited_headings = jtoc_isset_or_null( $options['edited_headings'] );
        if ( $edited_headings && is_array( $edited_headings ) && count( $edited_headings ) > 0 ) {
            $headings = array_map( function ( $item ) use($edited_headings) {
                $edited_heading_title = jtoc_isset_or_null( $edited_headings[$item['id']] );
                //updates title if changed
                if ( $edited_heading_title ) {
                    $item['title'] = $edited_heading_title;
                }
                return $item;
            }, $headings );
        }
        $toc_is_sticky = null;
        $headings = apply_filters( 'joli_toc_headings', $headings );
        global $post;
        $post_settings = get_post_meta( $post->ID, 'joli_toc_post_settings', true );
        $force = is_array( $post_settings ) && key_exists( 'enable_toc', $post_settings ) && $post_settings['enable_toc'] == 'on';
        // do not check the headings counts if force is on
        if ( !$force ) {
            //min-number of headings
            $min_headings = (int) $options['min_headings'];
            if ( $min_headings !== null && is_int( $min_headings ) && $min_headings > 0 ) {
                if ( is_array( $headings ) && count( $headings ) < $min_headings ) {
                    return;
                }
            }
            //max-number of headings
            $max_headings = (int) $options['max_headings'];
            if ( $max_headings !== null && is_int( $max_headings ) && $max_headings > 0 ) {
                if ( is_array( $headings ) && count( $headings ) > $max_headings ) {
                    return;
                }
            }
        }
        //headings parsing
        $headings_count = count( $headings );
        // pre($headings);
        $_headings = $this->prepareHeadings( $headings );
        // pre($_headings);
        $_headings = $this->parseHeadings( $_headings );
        $theme = $options['theme'];
        $has_custom_theme = ( $theme ? strpos( $theme, 'custom-' ) === 0 : false );
        if ( $has_custom_theme ) {
            $theme_id = substr( $theme, strlen( 'custom-' ) );
            /** @var CustomThemes $custom_themes */
            $themes_controller = JTOC()->requestService( CustomThemes::class );
            $custom_theme = $themes_controller->getTheme( $theme_id );
            $stylesheet_path = $custom_theme['styles'];
            $function_path = $custom_theme['functions'];
            $theme_version = jtoc_isset_or_null( $custom_theme['info']['version'] );
            if ( $stylesheet_path ) {
                wp_enqueue_style(
                    'wpjoli-joli-tocv2-theme-' . $theme_id,
                    $stylesheet_path,
                    [],
                    ( $theme_version ? $theme_version : JTOC()::VERSION )
                );
            }
            if ( $function_path ) {
                include_once $function_path;
            }
        } else {
            if ( $theme && $theme !== 'none' ) {
                $stylesheet_path = JTOC()->url( 'assets/public/css/themes/' . $theme . '.css', JTOC()::USE_MINIFIED_ASSETS );
                wp_enqueue_style(
                    'wpjoli-joli-tocv2-theme-' . $theme,
                    $stylesheet_path,
                    [],
                    JTOC()::VERSION
                );
                //Includes additional php theme specific file
                $theme_functions = JTOC()->path( 'assets/public/themes/' . $theme . '.php' );
                if ( is_file( $theme_functions ) ) {
                    include_once $theme_functions;
                }
            }
        }
        //since 1.3.8
        if ( !apply_filters( 'joli_toc_disable_styles', false ) ) {
            wp_enqueue_style(
                'wpjoli-joli-tocv2-styles',
                JTOC()->url( 'assets/public/css/' . 'wpjoli-joli-table-of-contents' . '.css', JTOC()::USE_MINIFIED_ASSETS ),
                [],
                JTOC()::VERSION
            );
        }
        if ( !apply_filters( 'joli_toc_disable_js', false ) ) {
            wp_enqueue_script(
                'wpjoli-joli-tocv2-scripts',
                JTOC()->url( 'assets/public/js/' . 'wpjoli-joli-table-of-contents' . '.js', JTOC()::USE_MINIFIED_ASSETS ),
                [],
                JTOC()::VERSION,
                true
            );
            $logo_url = JTOC()->url( 'assets/public/img/' . 'wpjoli-logo-linear-small-bw-24px.png' );
            // $has_credits = $options['show_credits'];
            // $data['logo'] = $has_credits ? $logo_url : null;
            $widget_support_post_types = $options['widget_support_post_types'];
            $widget_support = false;
            if ( is_array( $widget_support_post_types ) && in_array( $post->post_type, $widget_support_post_types ) ) {
                $widget_support = true;
            }
            $front_data = [
                'scroll_update_interval'      => (int) apply_filters( 'jtoc_floating_widget_scroll_update_interval', 200 ),
                'header_as_toggle'            => (bool) $options['header_as_toggle'],
                'headings_full_row_clickable' => (bool) $options['headings_full_row_clickable'],
                'logo'                        => $logo_url,
                'jump_to_offset'              => (int) jtoc_get_unit_value( $options['jump_to_offset'], true ),
                'jump_to_offset_mobile'       => (int) jtoc_get_unit_value( $options['jump_to_offset_mobile'], true ),
                'smooth_scroll'               => (bool) $options['smooth_scroll'],
                'hash_in_url'                 => (bool) $options['hash_in_url'],
                'is_admin'                    => is_super_admin(),
                'wp_widget_support'           => $widget_support,
                'in_the_loop'                 => in_the_loop(),
                'post_class'                  => get_post_class(),
            ];
            $front_strings = [
                'wp_widget_support_message' => __( 'Widget support for this post type', 'joli-table-of-contents' ) . ' (<strong>' . $post->post_type . '</strong>) ' . __( 'is currently not enabled, to make the TOC links work, please enable support in the settings under WIDGET SUPPORT > Enable widget support > Post type. This message is only visible by admins.', 'joli-table-of-contents' ),
            ];
            wp_localize_script( 'wpjoli-joli-tocv2-scripts', 'JTOC', [
                'options' => $front_data,
                'strings' => $front_strings,
            ] );
        }
        // Processes the TOC inner after the theme is loaded to make sure the custom hooks will work
        $output = $this->renderTOC( $_headings, true );
        $return_args = [];
        $data = [
            'title'                      => apply_filters( 'joli_toc_toc_title', $options['toc_title'] ),
            'show_header'                => $options['show_header'],
            'show_toggle'                => (bool) $options['show_toggle'],
            'preserve_theme_styles'      => (bool) $options['preserve_theme_styles'],
            'toggle_type'                => $options['toggle_type'],
            'toggle_button_text_opened'  => $options['toggle_button_text_opened'],
            'toggle_button_text_closed'  => $options['toggle_button_text_closed'],
            'in_the_loop'                => in_the_loop(),
            'is_in_the_content'          => $this->is_in_the_content,
            'custom_css'                 => $options['css_code'],
            'toc_styles_general'         => $this->getTOCStylesGeneral(),
            'toc_styles'                 => $this->getTOCStyles(),
            'toc_styles_root'            => $this->getTOCStylesRoot(),
            'toc_wrapper_main_classes'   => $this->getTOCMainClasses( $headings_count, $return_args ),
            'toc_wrapper_shared_classes' => $this->getTOCWrapperClasses( $headings_count, $return_args ),
            'theme_class'                => jtoc_isset_or_null( $return_args['theme_class'], true ),
            'toc_classes'                => $this->getTOCClasses(),
            'toc'                        => $output,
            'toc_style'                  => '',
        ];
        if ( $options['toggle_type'] === 'icon-std' ) {
            $data['toggle_button_icon_opened'] = apply_filters( 'joli_toc_toggle_button_icon_opened', sprintf( '<i class="%s"></i>', $options['toggle_button_icon_opened'] ) );
            $data['toggle_button_icon_closed'] = apply_filters( 'joli_toc_toggle_button_icon_closed', sprintf( '<i class="%s"></i>', $options['toggle_button_icon_closed'] ) );
        }
        $toc = JTOC()->render( [
            'public' => 'joli-toc-template',
        ], $data, true );
        return $toc;
    }

    private function getTOCStylesGeneral() {
        $options = $this->options;
        $sc = $this->sc;
        $styles = [];
        //Adds numeration option only if numeration is set
        $option_master = $options['numeration_type'];
        if ( jtoc_isset_or_null( $option_master ) !== null && $option_master !== 'none' ) {
            //Toggle color
            $option = $options['numeration_suffix'];
            if ( jtoc_isset_or_null( $option ) ) {
                $processed_value = trim( $option );
                //Add the style only if different from the default value
                // if ($processed_value !== '.') {
                $styles['--jtoc-numeration-suffix'] = '"' . $processed_value . ' "';
                // }
            }
        }
        //Builds the final css string
        $output = '';
        foreach ( $styles as $prop => $value ) {
            $output .= sprintf( '%s: %s;', $prop, $value ) . "\n";
            // $output .= sprintf('%s: %s !important;', $prop, $value) . "\n";
        }
        return $output;
    }

    private function getTOCStyles() {
        $options = $this->options;
        $sc = $this->sc;
        $styles = [];
        $option = $options['hierarchy_offset'];
        $default_option = $sc->getOption( 'hierarchy_offset', true );
        if ( jtoc_isset_or_null( $option ) !== null && $option !== $default_option ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-hierarchy-offset'] = $processed_value;
            }
        }
        // STYLES --------------------------
        //TOC Width
        $option = $options['toc_width_incontent'];
        if ( jtoc_isset_or_null( $option ) && $option === 'width-100' ) {
            $styles['--jtoc-width'] = '100%';
        }
        //TOC Margin
        $option = $options['toc_margin'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-toc-margin'] = $processed_value;
            }
        }
        //TOC Padding
        $option = $options['toc_padding'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-toc-padding'] = $processed_value;
            }
        }
        //TOC Border radius
        $option = $options['toc_border_radius'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option, 'corner' );
            if ( $processed_value !== false ) {
                $styles['--jtoc-toc-border-radius'] = $processed_value;
            }
        }
        //TOC Border
        $option = $options['toc_border'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-toc-border'] = $processed_value . ' solid';
            }
        }
        //TOC Border color
        $option = $options['toc_border_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-toc-border-color'] = $option;
        }
        //TOC background color
        $option = $options['toc_background_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-background-color'] = $option;
        }
        $option = (bool) $options['toc_shadow'];
        if ( jtoc_isset_or_null( $option ) !== null && $option === true ) {
            $shadow_color = jtoc_isset_or_null( $options['toc_shadow_color'] );
            $shadow_color = ( $shadow_color ? $shadow_color : '#c2c2c280' );
            $styles['--jtoc-toc-box-shadow'] = '0 0 16px ' . $shadow_color;
        }
        //TOC min-width
        $option = $options['toc_min_width'];
        $default_option = $sc->getOption( 'toc_min_width', true );
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-min-width'] = $processed_value;
            }
        }
        //TOC max-width
        $option = $options['toc_max_width'];
        $default_option = $sc->getOption( 'toc_max_width', true );
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-max-width'] = $processed_value;
            }
        }
        //TOC Header Height
        $option = $options['toc_header_height'];
        $default_option = $sc->getOption( 'toc_header_height', true );
        if ( jtoc_isset_or_null( $option ) !== null && $option !== $default_option ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-header-height'] = $processed_value;
            }
        }
        //TOC Header Margin
        $option = $options['toc_header_margin'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-header-margin'] = $processed_value;
            }
        }
        //TOC Header Padding
        $option = $options['toc_header_padding'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-header-padding'] = $processed_value;
            }
        }
        //TOC Header background color
        $option = $options['toc_header_background_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-header-background-color'] = $option;
        }
        //TOC Header background color
        $option = $options['toc_title_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-title-color'] = $option;
        }
        //TOC Title font size
        $option = $options['toc_title_font_size'];
        // $default_option = $sc->getOption('toc-title-font-size', 'table-of-contents-header-styles', true);
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-title-font-size'] = $processed_value;
            }
        }
        //TOC Title font weight
        $option = $options['toc_title_font_weight'];
        // $default_option = $sc->getOption('toc-title-font-size', 'table-of-contents-header-styles', true);
        if ( jtoc_isset_or_null( $option ) && $option !== 'none' ) {
            $styles['--jtoc-title-label-font-weight'] = $option;
        }
        //TOC Title font weight
        $option = $options['toc_title_font_style'];
        // $default_option = $sc->getOption('toc-title-font-size', 'table-of-contents-header-styles', true);
        if ( jtoc_isset_or_null( $option ) && $option !== 'none' ) {
            $styles['--jtoc-title-label-font-style'] = $option;
        }
        //Toggle color
        $option = $options['toc_toggle_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-toggle-color'] = $option;
        }
        //TOC Body Margin
        $option = $options['toc_body_margin'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            // JTOC()->log($processed_value);
            if ( $processed_value !== false ) {
                $styles['--jtoc-body-margin'] = $processed_value;
            }
        }
        //TOC Body Padding
        $option = $options['toc_body_padding'];
        if ( jtoc_isset_or_null( $option ) ) {
            // JTOC()->log($option);
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-body-padding'] = $processed_value;
            }
        }
        //TOC Body background color
        $option = $options['toc_body_background_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-body-background-color'] = $option;
        }
        //TOC Body background color
        // $option = $options['headings_group_background_color'];
        // if (jtoc_isset_or_null($option) !== null) {
        //     $styles['--jtoc-headings-group-background-color'] = $option;
        // }
        //Headings Margin
        $option = $options['headings_margin'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            // JTOC()->log($processed_value);
            if ( $processed_value !== false ) {
                $styles['--jtoc-headings-margin'] = $processed_value;
            }
        }
        //Headings Padding
        $option = $options['headings_padding'];
        if ( jtoc_isset_or_null( $option ) ) {
            // JTOC()->log($option);
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-headings-padding'] = $processed_value;
            }
        }
        //Headings border radius
        $option = $options['headings_border_radius'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option, 'corner' );
            // JTOC()->log($processed_value);
            if ( $processed_value !== false ) {
                $styles['--jtoc-headings-border-radius'] = $processed_value;
            }
        }
        //Headings font size
        // $option = $options['headings_font_size'];
        // // $default_option = $sc->getOption('toc-title-font-size', 'table-of-contents-header-styles', true);
        // if (jtoc_isset_or_null($option)) {
        //     $processed_value = jtoc_get_unit_value($option);
        //     if ($processed_value !== false) {
        //         $styles['--jtoc-headings-font-size'] = $processed_value;
        //     }
        // }
        //Headings line height
        $option = $options['headings_line_height'];
        // $default_option = $sc->getOption('toc-title-font-size', 'table-of-contents-header-styles', true);
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-headings-line-height'] = $processed_value;
            }
        }
        //TOC headings color
        // $option = $options['headings_color'];
        // if (jtoc_isset_or_null($option) !== null) {
        //     $styles['--jtoc-headings-color'] = $option;
        // }
        //TOC headings background color
        $option = $options['headings_background_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-headings-background-color'] = $option;
        }
        //TOC headings color
        // $option = $options['headings_color_hover'];
        // if (jtoc_isset_or_null($option) !== null) {
        //     $styles['--jtoc-headings-color-hover'] = $option;
        // }
        //TOC headings background color
        $option = $options['headings_background_color_hover'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-headings-background-color-hover'] = $option;
        }
        //TOC headings color
        // $option = $options['headings_color_active'];
        // if (jtoc_isset_or_null($option) !== null) {
        //     $styles['--jtoc-headings-color-active'] = $option;
        // }
        //TOC headings background color
        $option = $options['headings_background_color_active'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-headings-background-color-active'] = $option;
        }
        //Adds numeration option only if numeration is set
        $option_master = $options['numeration_type'];
        if ( jtoc_isset_or_null( $option_master ) !== null && $option_master !== 'none' ) {
            // //Toggle color
            // $option = $options['numeration_suffix'];
            // if (jtoc_isset_or_null($option)) {
            //     $processed_value = trim($option);
            //     //Add the style only if different from the default value
            //     if ($processed_value !== '.') {
            //         $styles['--jtoc-numeration-suffix'] = '"' . $processed_value . ' "';
            //     }
            // }
            //TOC numeration color
            $option = $options['numeration_color'];
            if ( jtoc_isset_or_null( $option ) !== null ) {
                $styles['--jtoc-numeration-color'] = $option;
            }
            $option = $options['numeration_color_hover'];
            if ( jtoc_isset_or_null( $option ) !== null ) {
                $styles['--jtoc-numeration-color-hover'] = $option;
            }
            $option = $options['numeration_color_active'];
            if ( jtoc_isset_or_null( $option ) !== null ) {
                $styles['--jtoc-numeration-color-active'] = $option;
            }
        }
        //Headings Margin
        $option = $options['headings_link_margin'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_dimensions_value( $option );
            // JTOC()->log($processed_value);
            if ( $processed_value !== false ) {
                $styles['--jtoc-link-margin'] = $processed_value;
            }
        }
        //Headings Padding
        $option = $options['headings_link_padding'];
        if ( jtoc_isset_or_null( $option ) ) {
            // JTOC()->log($option);
            $processed_value = jtoc_get_dimensions_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-link-padding'] = $processed_value;
            }
        }
        //TOC link font size
        $option = $options['headings_link_font_size'];
        if ( jtoc_isset_or_null( $option ) ) {
            $processed_value = jtoc_get_unit_value( $option );
            if ( $processed_value !== false ) {
                $styles['--jtoc-link-font-size'] = $processed_value;
            }
        }
        //TOC Title font weight
        $option = $options['headings_link_font_weight'];
        // $default_option = $sc->getOption('toc-title-font-size', 'table-of-contents-header-styles', true);
        if ( jtoc_isset_or_null( $option ) && $option !== 'none' ) {
            $styles['--jtoc-link-font-weight'] = $option;
        }
        //TOC link color
        $option = $options['headings_link_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-link-color'] = $option;
        }
        //TOC link background color
        $option = $options['headings_link_background_color'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-link-background-color'] = $option;
        }
        //TOC link color
        $option = $options['headings_link_color_hover'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-link-color-hover'] = $option;
        }
        //TOC link background color
        $option = $options['headings_link_background_color_hover'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-link-background-color-hover'] = $option;
        }
        //TOC link color
        $option = $options['headings_link_color_active'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-link-color-active'] = $option;
        }
        //TOC link background color
        $option = $options['headings_link_background_color_active'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $styles['--jtoc-link-background-color-active'] = $option;
        }
        //Builds the final css string
        $output = '';
        foreach ( $styles as $prop => $value ) {
            $output .= sprintf( '%s: %s;', $prop, $value ) . "\n";
            // $output .= sprintf('%s: %s !important;', $prop, $value) . "\n";
        }
        return $output;
    }

    private function getTOCStylesRoot() {
        $options = $this->options;
        $sc = $this->sc;
        $styles = [];
        if ( (bool) $options['activate_bullet_points'] === true ) {
            //s, m, l
            $bullet_size = jtoc_isset_or_null( $options['bullet_points_size'] );
            if ( !$bullet_size ) {
                $bullet_size = $sc->getOption( 'bullet_points_size', true );
            }
            if ( $bullet_size == 's' ) {
                $bullet_size_px = 6;
            } else {
                if ( $bullet_size == 'm' ) {
                    $bullet_size_px = 8;
                } else {
                    if ( $bullet_size == 'l' ) {
                        $bullet_size_px = 10;
                    }
                }
            }
            $option = jtoc_isset_or_null( $options['bullet_points_type'] );
            if ( $option == 'disc' ) {
                $styles['--jtoc-bullet-border-radius'] = $bullet_size_px . 'px';
                $styles['--jtoc-bullet-width'] = $bullet_size_px . 'px';
                $styles['--jtoc-bullet-height'] = $bullet_size_px . 'px';
            } else {
                if ( $option == 'square' ) {
                    $styles['--jtoc-bullet-width'] = $bullet_size_px . 'px';
                    $styles['--jtoc-bullet-height'] = $bullet_size_px . 'px';
                } else {
                    if ( $option == 'pill' ) {
                        $styles['--jtoc-bullet-border-radius'] = $bullet_size_px / 2 . 'px';
                        $styles['--jtoc-bullet-width'] = $bullet_size_px * 2.25 . 'px';
                        $styles['--jtoc-bullet-height'] = $bullet_size_px . 'px';
                    }
                }
            }
            $option = jtoc_isset_or_null( $options['bullet_points_color'] );
            if ( jtoc_isset_or_null( $option ) !== null ) {
                $styles['--jtoc-bullet-background-color'] = $option;
            }
        }
        //Builds the final css string
        $output = '';
        foreach ( $styles as $prop => $value ) {
            // $output .= sprintf('%s: %s !important;', $prop, $value) . "\n";
            $output .= sprintf( '%s: %s;', $prop, $value ) . "\n";
        }
        return $output;
    }

    private function getTOCMainClasses( $headings_count, &$return_args ) {
        $options = $this->options;
        $sc = $this->sc;
        $classes = [];
        // $is_folded = false;
        $fold_if_headings_count = (int) $options['fold_if_headings_count'];
        // $option = $fold_if_headings_count;
        // if (jtoc_isset_or_null($option) !== null && $option > 0 && jtoc_isset_or_zero($headings_count) > $option) {
        //     $classes[] = '--jtoc-is-folded';
        //     // $is_folded = true;
        // }
        // if (!$is_folded) {
        $option = $options['fold_on_load'];
        if ( jtoc_isset_or_null( $option ) !== null && $option === 'yes' ) {
            $classes[] = '--jtoc-is-folded';
            // $classes[] = '--jtoc-is-unfolded';
        } elseif ( jtoc_isset_or_null( $option ) !== null && $option === 'no' ) {
            if ( $fold_if_headings_count > 0 && jtoc_isset_or_zero( $headings_count ) > $fold_if_headings_count ) {
                $classes[] = '--jtoc-is-folded';
            } else {
                $classes[] = '--jtoc-is-unfolded';
            }
        } elseif ( jtoc_isset_or_null( $option ) !== null && $option === 'partial' ) {
            if ( $fold_if_headings_count > 0 && jtoc_isset_or_zero( $headings_count ) > $fold_if_headings_count ) {
                $classes[] = '--jtoc-partial-fold';
            } else {
                $classes[] = '--jtoc-is-unfolded';
            }
        } elseif ( jtoc_isset_or_null( $option ) !== null && $option === 'responsive' ) {
            if ( wp_is_mobile() ) {
                $classes[] = '--jtoc-is-folded';
            } else {
                $classes[] = '--jtoc-is-unfolded';
            }
        }
        // }
        $option = (int) $options['animate_on_fold'];
        if ( jtoc_isset_or_null( $option ) !== null && $option > 0 && jtoc_isset_or_zero( $headings_count ) > $option ) {
            $classes[] = '--jtoc-animate';
        }
        //Builds the final css string
        $output = implode( ' ', $classes );
        return ' ' . $output;
    }

    private function getTOCWrapperClasses( $headings_count, &$return_args ) {
        /*
         --jtoc-theme-basic-light 
         --has-custom-css 
         --jtoc-toggle-1 
         --jtoc-expand-active-only 
         --jtoc-unfolded-incontent 
         --jtoc-is-unfolded  
         --jtoc-overflow-text-hidden 
         --jtoc-widget-floating
        */
        $options = $this->options;
        $sc = $this->sc;
        $classes = [];
        $option = $options['theme'];
        if ( jtoc_isset_or_null( $option ) ) {
            $return_args['theme_class'] = '--jtoc-theme-' . $option;
            $classes[] = $return_args['theme_class'];
        }
        $option = $options['headings_overflow'];
        if ( jtoc_isset_or_null( $option ) !== null && $option === 'hidden-ellipsis' ) {
            $classes[] = '--jtoc-headings-overflow-hidden-ellipsis';
        } elseif ( jtoc_isset_or_null( $option ) !== null && $option === 'hidden' ) {
            $classes[] = '--jtoc-headings-overflow-hidden';
        }
        // $is_folded = false;
        // $option = (int) $options['fold_if_headings_count'];
        // if (jtoc_isset_or_null($option) !== null && $option > 0 && jtoc_isset_or_zero($headings_count) > $option) {
        //     $classes[] = '--jtoc-is-folded';
        //     $is_folded = true;
        // }
        // if (!$is_folded) {
        //     $option = $options['fold_on_load'];
        //     if (jtoc_isset_or_null($option) !== null && $option === 'yes') {
        //         $classes[] = '--jtoc-is-folded';
        //     } elseif (jtoc_isset_or_null($option) !== null && $option === 'no') {
        //         $classes[] = '--jtoc-is-unfolded';
        //     } elseif (jtoc_isset_or_null($option) !== null && $option === 'responsive') {
        //         if (wp_is_mobile()) {
        //             $classes[] = '--jtoc-is-folded';
        //         } else {
        //             $classes[] = '--jtoc-is-unfolded';
        //         }
        //     }
        // }
        $option = $options['toc_title_alignment'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $classes[] = '--jtoc-title-align-' . $option;
        }
        $option = $options['toggle_type'];
        $toggle_type = $option;
        if ( jtoc_isset_or_null( $option ) !== null && $option === 'text' ) {
            $classes[] = '--jtoc-toggle-text';
        } else {
            if ( jtoc_isset_or_null( $option ) !== null && $option === 'icon' ) {
                $classes[] = '--jtoc-toggle-icon';
            } else {
                if ( jtoc_isset_or_null( $option ) !== null && $option === 'icon-std' ) {
                    $classes[] = '--jtoc-toggle-icon-std';
                }
            }
        }
        $option = $options['toggle_position'];
        if ( jtoc_isset_or_null( $option ) !== null ) {
            $classes[] = '--jtoc-toggle-position-' . $option;
        }
        if ( $toggle_type === 'icon' ) {
            $option = $options['toggle_button_icon'];
            if ( jtoc_isset_or_null( $option ) !== null ) {
                $classes[] = '--jtoc-' . $option;
            }
        }
        $option = $options['numeration_type'];
        // JTOC()->log($option);
        if ( jtoc_isset_or_null( $option ) !== null && $option !== 'none' ) {
            $classes[] = '--jtoc-has-numeration';
        }
        $option = (bool) $options['header_as_toggle'];
        // JTOC()->log($option);
        if ( jtoc_isset_or_null( $option ) !== null && $option === true ) {
            $classes[] = '--jtoc-header-as-toggle';
        }
        $option = (bool) $options['headings_full_row_clickable'];
        // JTOC()->log($option);
        if ( jtoc_isset_or_null( $option ) !== null && $option === true ) {
            $classes[] = '--jtoc-headings-full-row-clickable';
        }
        // JTOC()->log(count($classes));
        if ( count( $classes ) > 0 ) {
            $classes[] = '--jtoc-has-custom-styles';
        }
        //Builds the final css string
        $output = implode( ' ', $classes );
        return ' ' . $output;
    }

    private function getTOCClasses() {
        $options = $this->options;
        $sc = $this->sc;
        $classes = [];
        //Builds the final css string
        $output = implode( ' ', $classes );
        return ' ' . $output;
    }

    /**
     * Sanitizes the tree indexes if it does not start with H2 depth
     */
    private function prepareHeadings( $headings ) {
        //if it starts with h2, we don't need to process
        if ( $headings[0]['depth'] == 2 ) {
            return $headings;
        }
        $items = [];
        $i = 0;
        $h2_found = false;
        $delta_from_h2 = $headings[0]['depth'] - 2;
        $previous_depth = null;
        $closest_parent = $headings[0];
        //Brings all items upper in the headings rank if they don't start from h2
        // if ($delta_from_h2 > 0) {
        //     for ($i = 0; $i < count($headings); $i++) {
        //         $headings[$i]['depth'] -= $delta_from_h2;
        //         if ($headings[$i]['depth'] < 2) {
        //             $headings[$i]['depth'] = 2;
        //         }
        //     }
        // }
        for ($i = 0; $i < count( $headings ); $i++) {
            $item = $headings[$i];
            $current_depth = $item['depth'];
            // $h2_found = false;
            if ( $item['depth'] == 2 ) {
                $h2_found = true;
            }
            // pre($headings[$i]);
            if ( !$h2_found === true ) {
                // pre($item['depth']);
                // pre($previous_depth);
                if ( $item['depth'] < $previous_depth ) {
                    // $delta_from_h2 = $delta_from_h2 + ($previous_depth - $item['depth'] - 1);
                    $delta_from_h2 = $closest_parent - 2 + ($item['depth'] - $closest_parent);
                }
                if ( $item['depth'] > $previous_depth ) {
                    // $item['depth'] = $previous_depth + 1;
                    $closest_parent = $previous_depth;
                    $delta_from_previous = $item['depth'] - $closest_parent;
                    if ( $delta_from_previous > 1 ) {
                        $item['depth'] -= $delta_from_previous - 1;
                        //1
                    }
                }
                $previous_depth = $current_depth;
                //3
                $item['depth'] -= $delta_from_h2;
                //1
                if ( $item['depth'] < 2 ) {
                    $item['depth'] = 2;
                }
            }
            // $previous_item = $item;
            $items[] = $item;
        }
        return $items;
    }

    /**
     * Transforms a linear list of headings into a hierarchical array
     */
    private function parseHeadings( &$headings, $first_run = true ) {
        $items = [];
        if ( !$headings ) {
            return;
        }
        $i = 0;
        // $firstH2 = false;
        if ( $headings[0]['depth'] !== 2 && $first_run === true ) {
            $first_heading = $headings[0]['depth'];
            // pre($headings);
            // pre($headings[0]['depth']);
            $headings[0]['depth'] = 2;
            for ($h = 1; $h < count( $headings ); $h++) {
                if ( $headings[$h]['depth'] === $first_heading ) {
                    $headings[$h]['depth'] = 2;
                } else {
                    break;
                }
            }
        }
        do {
            $children = null;
            $depth = $headings[0]['depth'];
            $item = [
                'id'    => $headings[0]['id'],
                'title' => $headings[0]['title'],
                'icon'  => $headings[0]['icon'],
                'depth' => $depth,
                'smart' => $headings[0]['smart'],
                'url'   => jtoc_isset_or_null( $headings[0]['url'] ),
            ];
            //removes the first element
            array_shift( $headings );
            // pre ($headings[0]);
            // pre ($depth);
            if ( isset( $headings[0] ) && $headings[0]['depth'] > $depth ) {
                // Corrects any hierarchy anomaly, makes the depth delta to 1 in any case.
                $h_delta = $headings[0]['depth'] - $depth;
                // pre($h_delta);
                if ( $h_delta > 1 ) {
                    $reference_depth = $headings[0]['depth'];
                    // $headings[0]['depth'] = $headings[0]['depth'] - ($h_delta - 1);
                    $z = 0;
                    //realigns all direct following items of the same depth
                    do {
                        $headings[$z]['depth'] = $headings[$z]['depth'] - ($h_delta - 1);
                        $z++;
                    } while ( isset( $headings[$z] ) && $headings[$z]['depth'] === $reference_depth );
                }
                $children = $this->parseHeadings( $headings, false );
            }
            $items[] = [
                'data'     => $item,
                'children' => $children,
            ];
            $i++;
        } while ( isset( $headings[0] ) && $headings[0]['depth'] >= $depth );
        // } while (isset($headings[0]) && $headings[0]['depth'] >= $depth);
        return $items;
    }

    /**
     * Turns a recursive array into HTML
     */
    private function renderTOC(
        &$headings,
        $root = false,
        $options = null,
        $level = ''
    ) {
        if ( !$headings ) {
            return;
        }
        $collapsible = '';
        $dynamic_mode = jtoc_isset_or_null( $this->options['activate_dynamic_unfold'] );
        if ( $dynamic_mode && jtoc_xy()->can_use_premium_code__premium_only() ) {
            $collapsible = ( !$root ? ' is-expandable' : '' );
        } else {
            $collapsible = '';
        }
        $list_tag = apply_filters( 'joli_toc_list_tag', 'ol' );
        $output = '<' . $list_tag . ' class="wpj-jtoc--items' . $collapsible . '">';
        // $output = sprintf(
        //     '<ul%s>',
        //     $root == true ? ' class="joli-nav"' . $init_style : ''
        // );
        $has_bullets = (bool) jtoc_isset_or_null( $this->options['activate_bullet_points'] );
        $bullets = [];
        if ( $has_bullets ) {
            $bullets_depth = jtoc_isset_or_null( $this->options['bullet_points_headings_depth'] );
            if ( !$bullets_depth || !is_string( $bullets_depth ) ) {
                $bullets_depth = '2,3,4,5,6';
            }
            //Array of depth where bullets are active
            $bullets = explode( ',', $bullets_depth );
        }
        $cpt = 0;
        $numeration_type = jtoc_isset_or_null( $this->options['numeration_type'] );
        do {
            $cpt++;
            $id = $headings[0]['data']['id'];
            $title = $headings[0]['data']['title'];
            $icon = $headings[0]['data']['icon'];
            $depth = $headings[0]['data']['depth'];
            $children = $headings[0]['children'];
            //since 2.0.0
            $smart = $headings[0]['data']['smart'];
            $url = $headings[0]['data']['url'];
            // //Renders a single item
            // $output .= sprintf(
            //     '<li class="%sitem"><a href="#%s" title="%s" class="joli-h%s">%s</a>',
            //     $depth > 2 ? 'sub' : '',
            //     $id,
            //     $title,
            //     $depth,
            //     $title
            // );
            $cpt_processed = $cpt;
            if ( $numeration_type === 'roman' ) {
                $cpt_processed = jtoc_decimal_to_roman( $cpt );
            } else {
                if ( $numeration_type === 'hexadecimal' ) {
                    $cpt_processed = base_convert( $cpt, 10, 16 );
                } else {
                    if ( $numeration_type === 'binary' ) {
                        $cpt_processed = base_convert( $cpt, 10, 2 );
                    }
                }
            }
            $suffix = ( $numeration_type !== 'numbers' ? $cpt_processed : $cpt );
            $display_type = jtoc_isset_or_null( $this->options['numeration_display'] );
            $attrs = null;
            if ( jtoc_isset_or_null( $this->options['seo_rel_nofollow'] ) ) {
                $attrs = [
                    'rel' => 'nofollow',
                ];
            }
            $output .= JTOC()->render( [
                'public' => 'joli-toc-template-item',
            ], [
                'args' => [
                    'id'      => $id,
                    'title'   => esc_html( $title ),
                    'icon'    => $icon,
                    'depth'   => $depth,
                    'counter' => ( $display_type === 'full' || $display_type == null ? $level . $suffix : $suffix ),
                    'smart'   => $smart,
                    'url'     => $url,
                    'attrs'   => $attrs,
                    'options' => $this->options,
                    'bullet'  => $has_bullets && in_array( $depth, $bullets ),
                ],
            ], true );
            //Renders the children if any
            if ( $children !== null ) {
                // $output .= sprintf(
                //     '<li class="%sitem">',
                //     $depth > 2 ? 'sub' : ''
                // );
                $numeration_separator = jtoc_isset_or_null( $this->options['numeration_separator'] );
                $separator = ( $numeration_separator ? $numeration_separator : '.' );
                //fallback to default value if unset
                $output .= $this->renderTOC(
                    $children,
                    false,
                    null,
                    $level . $suffix . $separator
                );
                // $output .= '</li>';
            }
            $output .= '</li>';
            //removes the first element and go on
            array_shift( $headings );
        } while ( count( $headings ) > 0 );
        $output .= '</' . $list_tag . '>';
        return $output;
    }

}
