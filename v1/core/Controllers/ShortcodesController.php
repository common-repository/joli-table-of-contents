<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Engine\ContentProcessing;
use WPJoli\JoliTOC\Engine\TableOfContents;
use WPJoli\JoliTOC\Application;

class ShortcodesController
{

    public $shortcode_contents;
    public $page_contents;
    public $page_headings;
    public $shortcode_ran = false;
    public $user_shortcode_ran = false;
    public $user_shortcode_running = false;
    public $content_processed = false;

    public function registerShortcodes()
    {

        add_shortcode(apply_filters('jolitoc_shortcode_tag', Application::DOMAIN), [$this, 'joliTOCShortcode']);
    }

    /**
     * Processes 'joli_toc' shortcode
     * @param type $atts
     * @return type
     */
    public function joliTOCShortcode($atts = [])
    {
        $post = get_post(get_the_ID());
        $shortcode = $this->buildShortcodeContents($post);

        return $shortcode;

    }

    public function buildShortcodeContents($post)
    {
        // JTOC($post->post_content);
        $content = apply_filters( 'joli_toc_post_content_preprocessing', $post->post_content );

        $processed_content = ContentProcessing::Process($content, true);

        if ( $processed_content ){
            $headings = $processed_content['headings'];
        }
        
        if ( $headings){
            $toc = TableOfContents::makeTOC($headings);
            return $toc;
        }
        return;

        // global $post;
        // $current_post = $post;

        // if ( !$current_post && !is_single($post) && !is_page($post)) {
        //     return;
        // }

        $headings = $this->page_headings;
        $content = $this->page_contents;

        $toc = TableOfContents::makeTOC($headings);

        return $toc;

    }

}
