<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WPJoli\JoliTOC\Engine\ContentProcessing;
use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Engine\TOCBuilder;

class ShortcodesController
{

    public $shortcode_contents;
    public $page_contents;
    public $page_headings;

    protected $additional_options = [];
    protected $isProcessing = false;
    protected $tocBuilder = [];
    protected $toc;


    protected $the_content;
    protected $the_content_processed = [];
    protected $headings_processed = [];

    public function registerShortcodes()
    {
        add_shortcode(
            apply_filters('jolitoc_shortcode_tag', Application::DOMAIN),
            [$this, 'joliTOCShortcode']
        );
    }

    public function theContent($content)
    {
        if ($this->the_content_processed) {
            return $this->the_content_processed;
        }

        return $content;
    }

    public function joliTOCShortcodeFromBlock($atts = [])
    {
        $shortcode_index = count($this->tocBuilder);

        $this->additional_options[$shortcode_index] = [
            'hidden_headings' => null,
            'edited_headings' => null,
            'toc_is_sticky' => null,
        ];

        $html = $this->joliTOCShortcode($atts);

        $this->additional_options[$shortcode_index] = null;

        return $html;
    }

    /**
     * Processes 'joli_toc' shortcode
     * @param type $atts
     * @return type
     */
    public function joliTOCShortcode($atts = [])
    {
        if (!jtoc_is_front() || JTOC()->isBuildingShortcode === true) {
            return;
        }

        $shortcode_index = count($this->tocBuilder);
        $this->toc = null;
        $this->the_content_processed = null;
        $this->headings_processed[$shortcode_index] = null;

        $options = null;
        $this->tocBuilder[$shortcode_index] = new TOCBuilder(null, null, $options);
        $tocBuilder = $this->tocBuilder[$shortcode_index];
        $shortcode_defaults = $tocBuilder->getOptions();

        $additional_options = jtoc_isset_or_null($this->additional_options[$shortcode_index]);

        //if additionnal block options
        if ($additional_options) {
            $shortcode_defaults = array_merge($additional_options, $shortcode_defaults);
        }

        $atts = shortcode_atts(
            $shortcode_defaults, //default values
            $atts, //user custom attr ex :[joli-toc attr='1' attr1='asc']
            apply_filters('jolitoc_shortcode_tag', Application::DOMAIN)
        );

        $tocBuilder->setOptions($atts, $additional_options);


        if ($this->isProcessing || JTOC()->isProcessingShortcode) {
            // return str_replace('[#]', '[' . $shortcode_index . ']', JTOC()::SHORTCODE_TEMP_TAG);
            return str_replace('#',  $shortcode_index, JTOC()::SHORTCODE_TEMP_TAG);
        }

        $shortcode = $this->buildShortcodeContents(get_post(), $shortcode_index, false);
        return $shortcode;
    }

    public function buildShortcodeContents($post, $shortcode_index = 0, $in_the_content = true)
    {
        //shortcode or block inside the_content
        if (JTOC()->getTheContent()) {
            $content = JTOC()->getTheContent();
        }
        //shortcode or block inside a widget
        else {
            JTOC()->isBuildingShortcode = true;
            $the_content = is_object($post) ? $post->post_content : '';
            if ($in_the_content === false && jtoc_is_front()) {
                $content = apply_filters('joli_toc_post_content_preprocessing', apply_filters('the_content', $the_content));
            } else {
                $content = apply_filters('joli_toc_post_content_preprocessing', $the_content);
            }
            JTOC()->isBuildingShortcode = false;
        }

        $tocBuilder = $this->tocBuilder[$shortcode_index];

        $processed_content = ContentProcessing::Process($content, false, $tocBuilder, jtoc_get_multipaged_content());

        if ($processed_content) {
            $this->headings_processed[$shortcode_index] = $processed_content['headings'];
            $this->the_content_processed = $processed_content['content'];
        }

        if ($this->headings_processed[$shortcode_index]) {
            $tocBuilder->setHeadings($this->headings_processed[$shortcode_index]);
            $tocBuilder->setContent($this->the_content_processed);

            $this->toc = $tocBuilder->makeTOC($this->headings_processed[$shortcode_index]);
            return $this->toc;
        }
        return;
    }

    public function beforeTheContent($content)
    {
        // set the current post ID
        if (JTOC()->the_ID === null) {
            JTOC()->the_ID = get_the_ID();
        }

        $has_shortcode = has_shortcode($content, apply_filters('jolitoc_shortcode_tag', Application::DOMAIN));

        $has_block = false;
        if (version_compare($GLOBALS['wp_version'], '5.0', '>=')) {
            $has_block = has_block('wpjoli/joli-table-of-contents');
        }

        $has_shortcode_or_block = $has_shortcode || $has_block;
        if (JTOC()->the_ID === get_the_ID() && $has_shortcode_or_block) {


            JTOC()->isProcessingShortcode = true;
            return $content;
        }

        return $content;
    }

    public function filterTheContentShortcode($content)
    {

        if (is_feed() || is_search() || is_archive()) {
            return $content;
        }

        if (JTOC()->isBuildingShortcode) {
            return $content;
        }

        //JTOC()->the_ID === get_the_ID() checks the initial post ID, because it could cause confusion if w WPQuery was executed in between
        if (JTOC()->the_ID === get_the_ID() && JTOC()->isProcessingShortcode) {
            JTOC()->setTheContent($content);

            $shortcode_match = preg_match_all(JTOC()::SHORTCODE_TEMP_TAG_REGEX_PATTERN, $content, $matches);

            // JTOC()->log($shortcode_match);
            if ($shortcode_match === false || !$matches) {
                // if ($shortcode_match === false || !$matches ||  $shortcode_match === 0) {
                return $content;
            }

            // //auto insert but shortcode in sidebar
            // if ( $shortcode_match === 0) {
            //     return $this->the_content_processed;
            // }

            $content_after_shortcodes = "";

            $the_post = get_post();
            if (!is_object($the_post)) {
                return $content;
            }

            if ($shortcode_match === 0) {
                return $content;
            }

            $i = 0;
            foreach ($matches[1] as $shortcode_index) {
                $toc = $this->buildShortcodeContents($the_post, $shortcode_index);
                // JTOC()->log($toc);
                // if ($shortcode_index == 0) {
                if ($i == 0) { //first of shortcode or block
                    $content_after_shortcodes = $this->the_content_processed;
                }
                // $current_tag = str_replace('[#]', '[' . $shortcode_index . ']', JTOC()::SHORTCODE_TEMP_TAG);
                $current_tag = str_replace('#', $shortcode_index, JTOC()::SHORTCODE_TEMP_TAG);
                $content_after_shortcodes =  str_replace($current_tag, (string) $toc, $content_after_shortcodes);

                $i++;
            }

            // resets everything
            JTOC()->isProcessingShortcode = false;
            JTOC()->the_ID = null;

            return $content_after_shortcodes;
        }

        return $content;
    }
}
