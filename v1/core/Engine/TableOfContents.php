<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC\Engine;

use WPJoli\JoliTOC\Engine\ContentProcessing;
class TableOfContents {
    public static function makeTOC( $headings = null, $content = null ) {
        $args = null;
        // content processing: get the headings and returns content with idfied headings
        if ( $content ) {
            $processed_content = ContentProcessing::Process( $content, $args );
            $headings = $processed_content['headings'];
        }
        // JTOC()->log('-----------------makeTOC-----------------');
        // JTOC()->log($headings);
        //Allow custom filters function
        $headings = apply_filters( 'joli_toc_headings', $headings );
        // Array
        // (
        //     [0] => Array
        //         (
        //             [id] => my-title-1
        //             [title] => My title 1
        //             [depth] => 2
        //         )
        //     [1] => Array
        //         (
        //             [id] => my-title-2
        //             [title] => My title 2
        //             [depth] => 3
        //         )
        //         ...
        // )
        global $post;
        $post_settings = get_post_meta( $post->ID, 'joli_toc_post_settings', true );
        $force = is_array( $post_settings ) && key_exists( 'enable_toc', $post_settings ) && $post_settings['enable_toc'] == 'on';
        // do not check the headings counts if force is on
        if ( !$force ) {
            //min-number of headings
            $min_headings = (int) jtoc_get_option( 'min-headings', 'general' );
            if ( $min_headings !== null && is_int( $min_headings ) && $min_headings > 0 ) {
                if ( is_array( $headings ) && count( $headings ) < $min_headings ) {
                    return;
                }
            }
        }
        //headings parsing
        // pre($headings);
        $prepared_headings = self::prepareHeadings( $headings );
        // pre($prepared_headings);
        $_headings = self::parseHeadings( $prepared_headings );
        // pre($_headings);
        $visibility = jtoc_get_option( 'visibility', 'behaviour' );
        $data['visibility'] = $visibility;
        $data['visibility_classes'] = self::getVisibilityClasses( $visibility );
        $hide_at_load = '';
        if ( $visibility == 'invisible' ) {
            $hide_at_load = ' style="display:none";';
        }
        $data['hide_at_load'] = $hide_at_load;
        //generating the items within the TOC
        $output = self::renderTOC( $_headings, true, [
            'visibility' => $visibility,
        ] );
        // pre($output);
        // custom style
        // $data['css'] = self::renderStyle()
        //enqueue style & script only if TOC is running
        //since 1.3.8
        if ( !apply_filters( 'joli_toc_disable_styles', false ) ) {
            wp_enqueue_style(
                'wpjoli-joli-toc-styles',
                JTOC()->url( 'assets/public/css/' . jtoc_fs_file( 'wpjoli-joli-toc' ) . '.css', JTOC()::USE_MINIFIED_ASSETS ),
                [],
                '1.3.9'
            );
        }
        if ( !apply_filters( 'joli_toc_disable_js', false ) ) {
            // JTOC()->log('4');
            wp_enqueue_script(
                'wpjoli-joli-toc-scripts',
                JTOC()->url( 'assets/public/js/' . jtoc_fs_file( 'wpjoli-joli-toc' ) . '.js', JTOC()::USE_MINIFIED_ASSETS ),
                ['jquery'],
                '1.3.9',
                true
            );
        }
        //since 1.3.8
        $data['custom_css'] = '';
        if ( !apply_filters( 'joli_toc_disable_inline_styles', false ) ) {
            //high priority to render after the css file    W
            $inline_styles = self::renderStyle( $headings );
            $data['custom_css'] = $inline_styles;
        }
        // add_action( 'wp_footer', function() use ($inline_styles) { echo $inline_styles; }, 999);
        // add_action( 'wp_footer', 'WPJoli\JoliTOC\Engine\TableOfContents::renderStyle', 999, 1 );
        $data['toc'] = $output;
        $toc_title = jtoc_get_option( 'toc-title', 'general' );
        $data['title'] = apply_filters( 'joli_toc_toc_title', $toc_title );
        // pre(jtoc_get_option('smooth-scrolling', 'behaviour'));
        $data['smoothscroll'] = ( jtoc_get_option( 'smooth-scrolling', 'behaviour' ) == 1 ? ' joli-smoothscroll' : '' );
        $has_credits = jtoc_get_option( 'show-credits', 'support-us' );
        $logo_url = JTOC()->url( 'assets/public/img/' . 'wpjoli-logo-linear-small-bw-24px.png' );
        $data['logo'] = ( $has_credits ? $logo_url : null );
        $data['domain'] = getHostURL();
        //javascript vars
        $front_data['expands_on'] = jtoc_get_option( 'expands-on', 'floating-behaviour' );
        $front_data['collapses_on'] = jtoc_get_option( 'collapses-on', 'floating-behaviour' );
        // $front_data['logo'] = $data['logo'];
        $front_data['logo'] = $logo_url;
        $front_data['jumpto_offset'] = jtoc_get_option( 'jump-to-offset', 'behaviour' );
        wp_localize_script( 'wpjoli-joli-toc-scripts', 'joli_toc_vars', $front_data );
        // $data['expand'] = apply_filters( 'joli_toc_expand_str', '<span class="joli-expcol">+</span>' );
        // $data['collapse'] = apply_filters( 'joli_toc_collapse_str', '<span class="joli-expcol">-</span>' );
        $data['expand'] = apply_filters( 'joli_toc_expand_str', sprintf( '<i class="%s"></i>', jtoc_get_option( 'expand-button-icon', 'buttons' ) ) );
        $data['collapse'] = apply_filters( 'joli_toc_collapse_str', sprintf( '<i class="%s"></i>', jtoc_get_option( 'collapse-button-icon', 'buttons' ) ) );
        $data['togglealign'] = jtoc_get_option( 'toggle-position', 'incontent-behaviour' );
        //Full HTML
        $toc = JTOC()->render( [
            'public' => 'jolitoc',
        ], $data, true );
        return $toc;
    }

    public static function getVisibilityClasses( $visibility ) {
        switch ( $visibility ) {
            case 'invisible':
                return 'joli-floating ';
            case 'unfolded-incontent':
                return 'joli-unfolded-incontent joli-unfolded joli-incontent ';
            case 'unfolded-floating':
                return 'joli-unfolded-floating joli-unfolded joli-incontent ';
            case 'unfolded-ufloating':
                return 'joli-unfolded-ufloating joli-unfolded joli-incontent ';
            case 'folded-incontent':
                return 'joli-folded-incontent joli-folded joli-incontent ';
            case 'folded-floating':
                return 'joli-folded-floating joli-folded joli-incontent ';
            case 'responsive-incontent':
                return 'joli-folded-incontent ' . (( !wp_is_mobile() ? 'joli-unfolded ' : 'joli-folded' )) . ' joli-incontent ';
            case 'responsive-floating':
                return 'joli-folded-floating ' . (( !wp_is_mobile() ? 'joli-unfolded ' : 'joli-folded' )) . ' joli-incontent ';
        }
        return 'joli-floating ';
        //default
    }

    /**
     * Sanitizes the tree indexes if it does not start with H2 depth
     */
    public static function prepareHeadings( $headings ) {
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
        for ($i = 0; $i < count( $headings ); $i++) {
            $item = $headings[$i];
            if ( $item['depth'] == 2 ) {
                $h2_found = true;
            }
            if ( !$h2_found === true ) {
                // JTOC()->log($previous_item['depth']);
                if ( $item['depth'] < $previous_depth ) {
                    // $delta_from_h2 = $delta_from_h2 + ($previous_depth - $item['depth'] - 1);
                    $delta_from_h2 = $closest_parent - 2 + ($item['depth'] - $closest_parent);
                }
                if ( $item['depth'] > $previous_depth ) {
                    // $item['depth'] = $previous_depth + 1;
                    $closest_parent = $previous_depth;
                }
                $previous_depth = $item['depth'];
                $item['depth'] -= $delta_from_h2;
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
    public static function parseHeadings( &$headings ) {
        $items = [];
        // pre($headings);
        if ( !$headings ) {
            return;
        }
        $i = 0;
        // $firstH2 = false;
        do {
            $children = null;
            $depth = $headings[0]['depth'];
            // if ( $depth == 2){
            //     $firstH2 = true;
            // }
            $item = [
                'id'    => $headings[0]['id'],
                'title' => $headings[0]['title'],
                'icon'  => $headings[0]['icon'],
                'depth' => $depth,
            ];
            //removes the first element
            array_shift( $headings );
            if ( isset( $headings[0] ) && $headings[0]['depth'] > $depth ) {
                // if (isset($headings[0])) {
                $children = TableOfContents::parseHeadings( $headings );
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
     * Renders CSS from options
     * ---
     * colors.text-color
     * colors.text-hover-color
     * colors.text-active-color
     * colors.text-hover-background-color
     * colors.text-text-active-background-color
     * colors.toc-background-color
     */
    public static function renderStyle( $headings = null ) {
        $css = '<style>';
        //themes
        $theme = jtoc_get_option( 'theme', 'themes' );
        if ( $theme && $theme !== 'default' ) {
            $theme_path = JTOC()->path( '/assets/public/css/themes/' . jtoc_fs_file( $theme ) . '.min.css' );
            $theme_path_fallback = JTOC()->path( '/assets/public/css/themes/' . $theme . '.min.css' );
            $theme_path_unmin = JTOC()->path( '/assets/public/css/themes/' . jtoc_fs_file( $theme ) . '.css' );
            $theme_path_fallback_unmin = JTOC()->path( '/assets/public/css/themes/' . $theme . '.css' );
            if ( file_exists( $theme_path ) ) {
                $theme_css = file_get_contents( $theme_path );
                $css .= $theme_css;
            } else {
                if ( file_exists( $theme_path_fallback ) ) {
                    $theme_css = file_get_contents( $theme_path_fallback );
                    $css .= $theme_css;
                } else {
                    if ( file_exists( $theme_path_unmin ) ) {
                        $theme_css = file_get_contents( $theme_path_unmin );
                        $css .= $theme_css;
                    } else {
                        if ( file_exists( $theme_path_fallback_unmin ) ) {
                            $theme_css = file_get_contents( $theme_path_fallback_unmin );
                            $css .= $theme_css;
                        }
                    }
                }
            }
        }
        //TOC padding
        $toc_padding = jtoc_get_option( 'toc-padding', 'table-of-contents' );
        if ( $toc_padding ) {
            $css .= sprintf( '                    
                #joli-toc-wrapper nav#joli-toc.joli-expanded, 
                #joli-toc-wrapper.joli-folded nav#joli-toc.joli-expanded, 
                #joli-toc-wrapper.joli-unfolded nav#joli-toc{
                    %s
                }
                ', ( $toc_padding ? "padding: {$toc_padding}px !important;" : '' ) );
        }
        //headings height
        $headings_height = jtoc_get_option( 'headings-height', 'headings' );
        if ( $headings_height ) {
            $css .= sprintf( '                    
                #joli-toc-wrapper nav#joli-toc li a{
                    %s
                }
                ', ( $headings_height ? "line-height: {$headings_height}px !important;" : '' ) );
        }
        //headings font-size
        $headings_font_size = jtoc_get_option( 'headings-font-size', 'headings' );
        if ( $headings_font_size ) {
            $css .= sprintf( '                    
                #joli-toc-wrapper nav#joli-toc.joli-collapsed ul.joli-nav, 
                #joli-toc-wrapper nav#joli-toc ul.joli-nav{
                    %s
                }
                ', ( $headings_font_size ? "font-size: {$headings_font_size}em !important;" : '' ) );
        }
        //headings overflow
        $headings_overflow = jtoc_get_option( 'headings-overflow', 'behaviour' );
        $toc_background_color = jtoc_get_option( 'toc-background-color', 'table-of-contents' );
        // #joli-toc-wrapper nav#joli-toc li a,
        // #joli-toc-wrapper nav#joli-toc.joli-collapsed li a {
        //     white-space: normal !important;
        // }
        // pre($headings_overflow);
        $css .= sprintf( '                    
        #joli-toc-wrapper nav#joli-toc.joli-expanded li a, 
        #joli-toc-wrapper nav#joli-toc.joli-collapsed li a,
        #joli-toc-wrapper.joli-folded nav#joli-toc.joli-expanded li a, 
        #joli-toc-wrapper.joli-unfolded nav#joli-toc li a{
            text-overflow: %s !important;
            overflow: hidden !important;
            white-space: %s !important;
            }
            ', ( $headings_overflow == 'hidden-ellipsis' ? 'ellipsis' : 'unset' ), ( $headings_overflow == 'wrap' ? 'normal' : 'nowrap' ) );
        if ( $headings_overflow == 'hidden-gradient' ) {
            $css .= '                    
            #joli-toc-wrapper nav#joli-toc.joli-expanded li a:after, 
            #joli-toc-wrapper.joli-folded nav#joli-toc.joli-expanded li a:after, 
            #joli-toc-wrapper.joli-unfolded nav#joli-toc li a:after {
                content: \'\' !important;
                display: inline-block !important;
                position: absolute !important;
                right: 0 !important;
                width: 40px !important;
                height: 100% !important;
                background: linear-gradient(90deg, #ffffff00 0%, ' . (( $toc_background_color ? $toc_background_color : '#ffffff' )) . 'ff 100%) !important;
            }
            ';
        }
        //min & max width
        $min_width = jtoc_get_option( 'min-width', 'table-of-contents' );
        $max_width = jtoc_get_option( 'max-width', 'table-of-contents' );
        if ( $min_width || $max_width ) {
            $_min_width = ( $min_width ? 'min-width: ' . $min_width . 'px !important;' : '' );
            $_max_width = ( $max_width ? 'max-width: ' . $max_width . 'px !important;' : '' );
            $css .= sprintf( '                    
            #joli-toc-wrapper nav#joli-toc{
                %s%s
            }
            ', $_min_width, $_max_width );
        }
        //width-incontent
        $width_incontent = jtoc_get_option( 'width-incontent', 'table-of-contents' );
        if ( $width_incontent ) {
            $_width_incontent = '';
            if ( $width_incontent == 'width-auto' ) {
                $_width_incontent = 'auto';
            } else {
                if ( $width_incontent == 'width-100' ) {
                    $_width_incontent = '100%';
                }
            }
            $css .= sprintf( '                    
            #joli-toc-wrapper nav#joli-toc{
                width: %s !important;
            }
            #joli-toc-wrapper nav#joli-toc.joli-collapsed{
                width: initial !important;
            }
            ', ( $_width_incontent ? $_width_incontent : '' ) );
        }
        //TOC title alignement
        $title_alignment = jtoc_get_option( 'title-alignment', 'title' );
        if ( $title_alignment ) {
            $css .= sprintf( '                    
                #joli-toc-wrapper nav#joli-toc .title #title-label{
                    %s
                }
                ', ( $title_alignment ? "text-align: {$title_alignment} !important;" : '' ) );
        }
        //TOC title
        $title_color = jtoc_get_option( 'title-color', 'title' );
        $title_font_size = jtoc_get_option( 'title-font-size', 'title' );
        $title_font_weight = jtoc_get_option( 'title-font-weight', 'title' );
        $css .= sprintf(
            '                    
            #joli-toc-wrapper nav#joli-toc.joli-collapsed .title, 
            #joli-toc-wrapper nav#joli-toc .title{
                %s
                %s
                %s
            }
            ',
            ( $title_color ? "color: {$title_color} !important;" : '' ),
            ( $title_font_size ? "font-size: {$title_font_size}em !important;" : '' ),
            ( $title_font_weight !== 'none' ? "font-weight: {$title_font_weight} !important;" : '' )
        );
        //expanding animation
        $exp_animation = jtoc_get_option( 'expanding-animation', 'floating-behaviour' );
        // $css .= sprintf('
        //     #joli-toc-wrapper nav#joli-toc.joli-collapsed,
        //     #joli-toc-wrapper nav#joli-toc{
        //         transition: %s;
        //     }
        //     ',
        //     $exp_animation ? 'all 0.2s cubic-bezier(0.47, 0, 0.49, 0.9)' : 'none'
        // );
        //shadow
        $toc_shadow = jtoc_get_option( 'toc-shadow', 'table-of-contents' );
        $toc_shadow_color = jtoc_get_option( 'toc-shadow-color', 'table-of-contents' );
        $css_shadow = sprintf( '-webkit-box-shadow: 0 0 10px %s !important;
                         box-shadow: 0 0 10px %s !important;
                        ', $toc_shadow_color, $toc_shadow_color );
        //titles & text colors
        if ( $exp_animation || $toc_background_color || $toc_shadow ) {
            $css .= sprintf(
                '#joli-toc-wrapper nav#joli-toc.joli-collapsed, 
                            #joli-toc-wrapper nav#joli-toc {
                                %s
                                %s
                                %s
                            }',
                ( $exp_animation ? 'transition: all 0.2s cubic-bezier(0.47, 0, 0.49, 0.9) !important;' : '' ),
                ( $toc_background_color ? 'background-color: ' . $toc_background_color . ' !important;' : '' ),
                ( $toc_shadow ? $css_shadow : '' )
            );
        }
        //headings color
        $headings_color = jtoc_get_option( 'headings-color', 'headings' );
        if ( $headings_color ) {
            $css .= sprintf( '#joli-toc-wrapper nav#joli-toc.joli-collapsed li a, 
                #joli-toc-wrapper nav#joli-toc li a{
                    color: %s !important;
                }', $headings_color );
        }
        //headings hover color
        $headings_hover_color = jtoc_get_option( 'headings-hover-color', 'headings' );
        $headings_hover_background_color = jtoc_get_option( 'headings-hover-background-color', 'headings' );
        if ( $headings_hover_color || $headings_hover_background_color ) {
            $css .= sprintf( '#joli-toc-wrapper nav#joli-toc.joli-collapsed li a:hover,
                #joli-toc-wrapper nav#joli-toc li a:hover {
                    %s
                    %s;
                }', ( $headings_hover_color ? 'color: ' . $headings_hover_color . ' !important;' : '' ), ( $headings_hover_background_color ? 'background-color: ' . $headings_hover_background_color . ' !important;' : '' ) );
        }
        //headings active color
        $headings_active_color = jtoc_get_option( 'headings-active-color', 'headings' );
        $headings_active_background_color = jtoc_get_option( 'headings-active-background-color', 'headings' );
        if ( $headings_active_color || $headings_active_background_color ) {
            $css .= sprintf( '#joli-toc-wrapper nav#joli-toc.joli-collapsed li a.active, 
            #joli-toc-wrapper nav#joli-toc li a.active {
                %s
                %s
            }', ( $headings_active_color ? 'color: ' . $headings_active_color . ' !important;' : '' ), ( $headings_active_background_color ? 'background-color: ' . $headings_active_background_color . ' !important;' : '' ) );
        }
        //Prefix counter
        $numbering = jtoc_get_option( 'prefix', 'general' );
        $prefix_separator = jtoc_get_option( 'prefix-separator', 'general' );
        $prefix_suffix = jtoc_get_option( 'prefix-suffix', 'general' );
        $prefix_color = jtoc_get_option( 'prefix-color', 'prefix' );
        if ( $numbering != 'none' ) {
            $css .= sprintf(
                '                    
                            #joli-toc-wrapper #joli-toc ul {
                                counter-reset: jolicpt !important;
                            } 
                            #joli-toc-wrapper nav#joli-toc.joli-collapsed li a:before, 
                            #joli-toc-wrapper nav#joli-toc li a:before {
                                counter-increment: jolicpt !important;
                                content: counters(jolicpt,"%s"%s) "%s " !important;
                                %s
                            }
                            ',
                ( $prefix_separator ? addslashes( $prefix_separator ) : '.' ),
                ( $numbering == 'roman' ? ',upper-roman' : '' ),
                ( $prefix_suffix ? addslashes( $prefix_suffix ) : '.' ),
                ( $prefix_color ? "color: {$prefix_color} !important;" : '' )
            );
            $prefix_color_active = jtoc_get_option( 'prefix-active-color', 'prefix' );
            if ( $prefix_color_active ) {
                $css .= sprintf( '                    
                    #joli-toc-wrapper nav#joli-toc.joli-collapsed li a.active:before, 
                    #joli-toc-wrapper nav#joli-toc li a.active:before {
                        %s
                    }
                    ', ( $prefix_color_active ? "color: {$prefix_color_active} !important;" : '' ) );
            }
            $prefix_color_hover = jtoc_get_option( 'prefix-hover-color', 'prefix' );
            if ( $prefix_color_hover ) {
                $css .= sprintf( '                    
                #joli-toc-wrapper nav#joli-toc.joli-collapsed li a:hover:before, 
                #joli-toc-wrapper nav#joli-toc li a:hover:before {
                        %s
                    }
                    ', ( $prefix_color_hover ? "color: {$prefix_color_hover} !important;" : '' ) );
            }
        }
        //indentation in pixels
        $indentation = jtoc_get_option( 'hierarchy-offset', 'general' );
        // pre($indentation);
        $css .= sprintf( '                    
            #joli-toc-wrapper.joli-unfolded-ufloating nav#joli-toc ul.joli-nav ul,
            #joli-toc-wrapper.joli-incontent nav#joli-toc ul.joli-nav ul,
            #joli-toc-wrapper nav#joli-toc.joli-expanded ul.joli-nav ul {
                margin: 0 0 0 %dpx !important;
            }
            ', $indentation );
        //Floating position
        $floating_position = jtoc_get_option( 'floating-position', 'floating-behaviour' );
        $floating_offset_x = jtoc_get_option( 'floating-offset-x', 'floating-behaviour' );
        $floating_offset_y = jtoc_get_option( 'floating-offset-y', 'floating-behaviour' );
        // $margin = '10';
        $admin_bar_height = 32;
        $css .= sprintf(
            '
            #joli-toc-wrapper {
                top: %s !important;
                bottom: %s !important;
                margin-left: %s !important;
            }
            body.admin-bar #joli-toc-wrapper {
                top: %s !important;
                bottom: %s !important;
            }
            ',
            ( $floating_position == 'top' ? $floating_offset_y . 'px' : 'initial' ),
            ( $floating_position == 'bottom' ? $floating_offset_y . 'px' : 'initial' ),
            ( $floating_offset_x ? $floating_offset_x . 'px' : 'initial' ),
            ( $floating_position == 'top' ? (int) $floating_offset_y + $admin_bar_height . 'px' : 'initial' ),
            ( $floating_position == 'bottom' ? $floating_offset_y . 'px' : 'initial' )
        );
        //offset y mobile
        $floating_offset_y_mobile = jtoc_get_option( 'floating-offset-y-mobile', 'floating-behaviour' );
        if ( $floating_offset_y_mobile ) {
            $css .= sprintf( '
            @media (max-width: 767.98px) {
                #joli-toc-wrapper {
                    %s1
                    %s2
                }
            }
            ', ( $floating_position == 'top' ? 'top: ' . $floating_offset_y_mobile . 'px !important;' : '' ), ( $floating_position == 'bottom' ? 'bottom: ' . $floating_offset_y_mobile . 'px !important;' : '' ) );
        }
        //Toggle position
        $toggle_position = jtoc_get_option( 'toggle-position', 'incontent-behaviour' );
        if ( $toggle_position == 'right' ) {
            $css .= '   
            #joli-toc-wrapper nav#joli-toc.joli-collapsed #joli-toc-header, 
            #joli-toc-wrapper nav#joli-toc #joli-toc-header {
                flex-direction: row-reverse !important;
            }';
        }
        //Custom CSS [MUST BE LAST]
        $custom_css = jtoc_get_option( 'css-code', 'custom-css' );
        $css .= $custom_css;
        $css .= '</style>';
        $css_min = joli_minify( $css );
        // echo $css_min;
        return $css_min;
    }

    /**
     * Turns a recursive array into HTML
     */
    public static function renderTOC( &$headings, $root = false, $options = null ) {
        // pre($headings);
        if ( !$headings ) {
            return;
        }
        //force display none for folded visibility
        $init_style = '';
        if ( isset( $options['visibility'] ) && strpos( $options['visibility'], 'folded' ) === 0 ) {
            $init_style = ' style="display:none;"';
        }
        $output = sprintf( '<ul%s>', ( $root == true ? ' class="joli-nav"' . $init_style : '' ) );
        do {
            $id = $headings[0]['data']['id'];
            $title = $headings[0]['data']['title'];
            $icon = $headings[0]['data']['icon'];
            $depth = $headings[0]['data']['depth'];
            $children = $headings[0]['children'];
            //Renders a single item
            $output .= sprintf(
                '<li class="%sitem"><a href="#%s" title="%s" class="joli-h%s">%s</a>',
                ( $depth > 2 ? 'sub' : '' ),
                $id,
                $title,
                $depth,
                $title
            );
            //Renders the children if any
            if ( $children !== null ) {
                // $output .= sprintf(
                //     '<li class="%sitem">',
                //     $depth > 2 ? 'sub' : ''
                // );
                $output .= TableOfContents::renderTOC( $children );
                // $output .= '</li>';
            }
            $output .= '</li>';
            //removes the first element and go on
            array_shift( $headings );
        } while ( count( $headings ) > 0 );
        $output .= '</ul>';
        return $output;
    }

}
