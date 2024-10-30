<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Engine;

use Cocur\Slugify\RuleProvider\DefaultRuleProvider;
use Cocur\Slugify\Slugify;
use DOMDocument;
use DOMXPath;
use DOMElement;
use Exception;

class ContentProcessing
{

    /**
     * Reads the actual HTML content from a post and processes the titles
     * @param $content HTML content from 'the_content' hook
     */
    public static function Process($content, $headings_only = false)
    {
        //Parses the content
        $html = new DOMDocument('1.0', "UTF-8");
        // @$html->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_use_internal_errors(true);
        @$html->loadHTML('<html><body>' . mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8') . '</body></html>', LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);
        
        $headings = [];

        if ($html) {

            //$depth_option = jtoc_get_option('title-depth', 'general');
			$depth_option = apply_filters( 'joli-toc-title-depth', jtoc_get_option('title-depth', 'general'));

            if (!$depth_option) {
                $depth_option = 6;
            }

            $depth = [];
            for ($i = 2; $i <= $depth_option; $i++) {
                $depth[] = '//h' . $i;
            }

            $depth_str = implode('|', $depth); //'h2,h3,h4,h5,h6'

            //skipping vars
            $skip_by_class = jtoc_get_option('skip-h-by-class', 'headings-processing');
            $skip_by_text = jtoc_get_option('skip-h-by-text', 'headings-processing');

            $skip_classes = [];
            if ( $skip_by_class ){
                $skip_classes = explode(' ', $skip_by_class);
            }
            
            $skip_texts = [];
            if ( $skip_by_text ){
                $skip_texts = explode("\n", $skip_by_text);
            }


            //xpath queriable html
            $xhtml = new DOMXPath($html);

            if ($xhtml) {   

                $empty_h_count = 0;
                
                $delimiter = '-';

                //hash format
                $hash_format = jtoc_get_option('hash-format', 'headings-hash');

                $args = [
                    'delimiter' => $delimiter,
                ];

                if ($hash_format == 'all-translit'){
                    //site locale
                    $locale_slug = self::findLocaleSlug();

                    if ($locale_slug !== 'english'){
                        $rules = (new DefaultRuleProvider())->getRules($locale_slug);
                        if ($rules){
                            // $locale_slug = 'russian';

                            //loads only one instance of the transliterator before the loop if needed
                            $slugify = new Slugify([
                                            'separator' => $args['delimiter'],
                                            'rulesets' => ['default', $locale_slug],
                                        ]);
                        }
                    }
                }
                
                // $heads = $xtree->query('//h2|//h3');
                foreach ($xhtml->query($depth_str) as $heading) {
                    
                    //check the skipping classes
                    $current_classes = $heading->getAttribute('class');
                    if ($skip_classes && !empty(array_intersect($skip_classes, explode(' ', $current_classes)))){
                        continue;
                    }
                    
                    //check the skipping textes
                    $current_title = $heading->textContent;
                    if ($skip_texts ){
                        foreach ($skip_texts as $needle){
                            if ( jtoc_match_string(trim($needle), $current_title) == true){
                                continue 2;
                            }
                        }
                    }


                    //latin and non latin
                    if ($hash_format == 'all'){
                        $args['transliterate'] = false;
                        $heading_id = jtoc_url_slug($current_title, $args);
                    }

                    //all translit
                    else if ($hash_format == 'all-translit'){
                        if (isset($slugify)){
                            $heading_id = $slugify->slugify($current_title);
                        }
                        else{
                            $args['transliterate'] = true;
                            $heading_id = jtoc_url_slug($current_title, $args);
                        }
                    }

                    //counter
                    else if ($hash_format == 'counter'){
                        $heading_id = '';
                    }
                    
                    //latin
                    else{
                        $heading_id = jtoc_slugify($current_title, $args);
                        // $heading_id = jtoc_slugify($current_title, $args);
                    }


                    //if heading id could not be processed, we generate an automatic ID
                    if ( $heading_id == ''){
                        $empty_h_count++;
                        $heading_prefix = jtoc_get_option('hash-counter-prefix', 'headings-hash');
                        $heading_id = $heading_prefix . $empty_h_count;
        
                    }else{
                        //checks if the id is already in use to avoid duplicate ids
                        $current_titles = array_column($headings, 'id');
                        $similar_index = 0;
                        $look_for = $heading_id;

                        //adds numeric suffix if same heading found
                        while (in_array($look_for, $current_titles)) {
                            $similar_index++;
                            $look_for = $heading_id . '_' . $similar_index;
                        }
                        $heading_id = $look_for;
                    }

   
                        //if any ID was here before, it will take over generated ID
                        $heading_id = ContentProcessing::appendID($html, $heading, $heading_id);
                        // ContentProcessing::appendClass($html, $heading, 'joli-heading' );

                    if (!$headings_only) {
                        //appends a class
                        $current_classes = $heading->getAttribute('class');
                        $new_classes = $current_classes == '' ? 'joli-heading' : $current_classes . ' ' . 'joli-heading';
                        // $heading->removeAttribute('class');
                        $heading->setAttribute('class', $new_classes);
                    }
                    //adds the current heading to the output 
                    $headings[] = [
                        'id' => $heading_id,
                        'title' => $heading->textContent,
                        'icon' => null,
                        'depth' => (int) substr($heading->tagName, -1),
                    ];

                    // pre( $heading->tag . $heading->plaintext );
                }

                // JTOC()->log($headings);
                if (!$headings_only) {
                    // $output = $html->saveHTML();
                    $output = saveHTMLNoWrapping($html);

                    return [
                        'content' => $output,
                        'headings' => $headings,
                    ];
                }

                //headings onlu
                return [
                    'content' => $content,
                    'headings' => $headings,
                ];
            }
        }
        return [
            'content' => $content,
            'headings' => null,
        ];
    }


    /**
     * Appends an ID to the current not if not exists
     */
    public static function appendID(DOMDocument &$document, DOMElement &$node, $id)
    {

        $node_id = $node->getAttribute('id');

        //returns the current node if it already contains an ID
        if ($node_id !== '') {
            // return html_entity_decode($node_id, ENT_COMPAT | ENT_HTML401, 'UTF-8');
            return $node_id;
        }

        $domAttribute = $document->createAttribute('id');

        // Value for the created attribute
        $domAttribute->value = $id;

        // Don't forget to append it to the element
        $node->appendChild($domAttribute);

        return $id;
    }

    /**
     * Appends an ID to the current not if not exists
     */
    public static function appendClass(DOMDocument &$document, DOMElement &$node, $class)
    {

        $classAttr = $node->getAttribute('class');

        //returns the current node if it already contains an ID
        if (!$classAttr) {
            $classAttr = $document->createAttribute('class');
            $classAttr->value = $class;
            $node->appendChild($classAttr);
            return;
        }


        // Adds a new class if the attribute already exists
        $classAttr->value .= ' ' . $class;
    }

    private static function findLocaleSlug(){
        $curr_locale = get_locale();
        $locale_list = include JTOC()->path('config/locales.php');

        foreach ($locale_list as $locale_item => $details){
            if ($curr_locale == $details['wp_locale']){
                $full_name = $details['name'];
                $name = explode(' ', $full_name);
                return strtolower($name[0]);
            }
        }
        
    }
}
