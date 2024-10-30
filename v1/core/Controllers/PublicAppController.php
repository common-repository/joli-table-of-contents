<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Engine\ContentProcessing;
use WPJoli\JoliTOC\Engine\TableOfContents;
use DOMDocument;
use DOMXPath;

class PublicAppController
{

    public function joliTocFilterTheContent($content)
    {

        global $post;

        //getting individual post settings
        $post_settings = get_post_meta( $post->ID, 'joli_toc_post_settings', true );

        // $shortcode_tag = apply_filters('jolitoc_shortcode_tag', Application::DOMAIN);

        if (!jtoc_is_front()){
            return $content;
        }

        //post check
        if (!is_single($post) && !is_page($post)) {
            return $content;
        }

        //manual interruption
        if (apply_filters('joli_toc_disable_autoinsert', false)) {
            // JTOC()->log('4');
            return $content;
        }

        //individual post settings override
        if ( is_array($post_settings) && key_exists('disable_toc', $post_settings)  ){
            if ( $post_settings['disable_toc'] == 'on' ){
                return $content;
            }
        }

        $processed = ContentProcessing::Process( $content );
        // JTOC()->log(json_encode($processed['headings']));

        //forced ids generation option check
        // $forced_ids = jtoc_get_option('force-id-gen', 'headings-options');

        // if shortcode used or post not eligible, return content with anchored headings
        if (has_shortcode($post->post_content, apply_filters('jolitoc_shortcode_tag', Application::DOMAIN)) ) {
        // if ( strpos( $content, '#joli-toc-wrapper' ) || ! $is_eligible ) {
            // pre('2');
            // $this->enqueueAssets();
            return $processed['content'];
        }

        //auto insert post type check
        $allowed_post_types = jtoc_get_option('post-types', 'auto-insert');
        $current_post_type = get_post_type();
        
        //force enable toc check over post type
        if ( is_array($post_settings) && key_exists('enable_toc', $post_settings) && $post_settings['enable_toc'] == 'on' ){
            //does nothing and skips the next else if
        }
        //allowed post types
        else if (!is_array($allowed_post_types) || 
            (is_array($allowed_post_types) && !in_array($current_post_type, $allowed_post_types) )
            ) {
            return $processed['content'];
        } 

        //builds the actual toc
        if ( $processed['headings']){
            $rendered_toc = TableOfContents::makeTOC( $processed['headings'] );
        }

        $placement = jtoc_get_option('position-auto', 'auto-insert');
        
        if ( isset($rendered_toc)){
            switch ($placement) {
                case 'after-content':
                    return  $processed['content'] . $rendered_toc;

                case 'before-h1':
                    return $this->insertIntoHTML($processed['content'], $rendered_toc, 'h1');

                case 'after-h1':
                    return $this->insertIntoHTML($processed['content'], $rendered_toc, 'h1', true);

                case 'before-h2-1':
                    return $this->insertIntoHTML($processed['content'], $rendered_toc, 'h2');

                case 'after-p-1':
                    return $this->insertIntoHTML($processed['content'], $rendered_toc, 'p', true);
                    
                case 'before-content':
                default:
                    return $rendered_toc . $processed['content'];
            }
        }

        //fallback
        return $processed['content'];
    }

    /**
     * Alters HTML to insert some content into an HTML string
     * $html = source to modify
     * $content = content to add to the HTML
     * $tag = markup to find
     * $before = insert before the tag. inserts after if false
     */
    public function insertIntoHTML($html, $content, $tag, $after = false)
    {
        $parsed_html = new DOMDocument('1.0', "UTF-8");
        @$parsed_html->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        if (!$parsed_html) {
            return $html;
        }
        
        $xhtml = new DOMXPath($parsed_html);
        // $tag_search = $parsed_html->getElementsByTagName($tag);
        $tag_search = $xhtml->query( sprintf( '(//%s)[1]', $tag ) );

        if (! $tag_search){ 
            $parsed_html = null;
            return $html;
        }

        $tag_to_find = $tag_search[0];

        if ($tag_to_find) {

            // Creates a chunk of HTML portion
            $toc = new DOMDocument();
            // @$toc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8')); 
            @$toc->loadHTML('<html><body>' . mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8') . '</body></html>', LIBXML_HTML_NODEFDTD);
        
            // $tag_text = new DOMText( $tag_to_find->textContent );

            if ($after === false) {
                
                //inserts content before the tag
                $tag_to_find->parentNode->insertBefore( $parsed_html->importNode($toc->documentElement, true), $tag_to_find);

            } else {

                //inserts content after the tag
                $tag_to_find->parentNode->insertBefore( $parsed_html->importNode($toc->documentElement, true), $tag_to_find->nextSibling);
                // $inserted = $tag_to_find->outertext . $content;
            }

            // $output = $parsed_html->saveHTML();
            $output = saveHTMLNoWrapping($parsed_html);
            return $output;
        }

        //fallback
        return $html;
    }

}
