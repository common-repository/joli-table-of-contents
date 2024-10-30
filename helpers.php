<?php

use WPJoli\JoliTOC\Controllers\SettingsController;
use Cocur\Slugify\Slugify;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Returns an instance of the applciation
 * @return WPJoli\JoliTOC\Application
 */
function JTOC()
{
    return WPJoli\JoliTOC\Application::instance();
}

//Custom toggle icons---
// add_filter('joli_toc_expand_str', function(){ return '<i class="fa fa-angle-down"></i>';});
// add_filter('joli_toc_collapse_str', function(){ return '<i class="fa fa-times"></i>';});

if (!function_exists('jtocpre')) {
    function jtocpre($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

/**
 * pre only if is super admin
 * @param type $data
 */
if (!function_exists('jtocapre')) {
    function jtocapre($data)
    {
        if (is_super_admin()) {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }
}

if (!function_exists('jtoc_pro_only')) {
    function jtoc_pro_only()
    {
        return '<span class="joli-pro-only">' . __(' (Pro only)', 'joli-table-of-contents') . '</span>';
    }
}


/**
 * Converts a name into a slug friendly 
 * @param type $name
 * @return type
 */
if (!function_exists('jtoc_slugify')) {

    function jtoc_slugify($string, $options)
    {

        // $slugify = new Slugify([
        //         'separator' => $delimiter,
        //         'rulesets' => ['default', 'chinese']
        //         ]);
        // return $slugify->slugify($string); // -> "hello_world"



        $oldLocale = setlocale(LC_ALL, 0);
        // JTOC()->log($oldLocale);

        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $options['delimiter'], $clean);
        $clean = trim($clean, $options['delimiter']);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
}


if (!function_exists('jtoc_url_slug')) {
    function jtoc_url_slug($str, $options = array())
    {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => true,
        );

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',

            // Latin symbols
            '©' => '(c)',

            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',

            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',

            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',

            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
}

if (!function_exists('arrayFind')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     * @param string $key Comparison key
     * @param mixed $value Value to search
     * @param array $array The array to search from
     * @return array
     */
    function arrayFind($value, $key, $array)
    {
        $item = null;
        foreach ($array as $row) {
            if ($row[$key] == $value) {
                $item = $row;
                break;
            }
        }
        return $item;
    }
}


if (!function_exists('jtoc_get_option')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    // function jtoc_get_option($name, $section, $options = null, $controller = null)
    function jtoc_get_option($option_id, $options = null, $global_options = null)
    {
        /** @var SettingsController $settings */
        $settings = JTOC()->requestService(SettingsController::class);
        // }

        if ($global_options !== null) {
            $is_global = $settings->isOptionGlobal($option_id);
            if ($is_global) {
                return $settings->getOption($option_id, false, $global_options);
            }
        }

        if ($options) {
            // error_log('getOption(' . $option_id . ', false, $options))');
            return $settings->getOption($option_id, false, $options);
        }

        // error_log('getOption(' . $option_id . ')');
        return $settings->getOption($option_id);
    }
}


if (!function_exists('jtoc_isset_or_null')) {
    /**
     * Returns $var or null if $var is not set
     * $empty_string = true returns an empty string instead of null
     */
    function jtoc_isset_or_null(&$var, $empty_string = null)
    {
        return  isset($var) ? $var : ($empty_string ? '' : null);
    }
}

if (!function_exists('jtoc_isset_or_zero')) {
    /**
     * Returns $var or null if $var is not set
     * $empty_string = true returns an empty string instead of null
     */
    function jtoc_isset_or_zero(&$var)
    {
        return  isset($var) ? $var : 0;
    }
}

if (!function_exists('joli_minify')) {
    /**
     * Removes line breaks and excessive empty spaces from a string
     */
    function joli_minify($string)
    {
        return  preg_replace('/\v(?:[\v\h]+)/', '', $string);
    }
}

if (!function_exists('jtoc_is_front')) {
    function jtoc_is_front()
    {
        if (function_exists('wp_doing_ajax')) {
            return !is_admin() && !wp_doing_ajax();
        } else {
            return !is_admin();
        }
    }
}

if (!function_exists('jtoc_save_html_no_wrapping')) {
    function jtoc_save_html_no_wrapping($html)
    {
        // $htmlh = '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">';
        $html_fragment = preg_replace('/<!DOCTYPE.+?>/', '',  trim($html->saveHTML()));

        // if (strpos($html_fragment, '<html><body>') === 0) {
        // $html_fragment = substr($html_fragment, 12, -14);
        // }
        $html_fragment = preg_replace('/<html><body>|<\/body><\/html>/', '', $html_fragment);

        return $html_fragment;
    }
}

if (!function_exists('getHostURL')) {
    function getHostURL()
    {

        $_url = parse_url(site_url());
        return $_url ? urlencode($_url['host']) : false;
    }
}

if (!function_exists('jtoc_css_prop')) {
    /**
     * Returns a css string if the value is set or not null
     *
     * @param [type] $prop
     * @param [type] $value
     * @return void 
     */
    function jtoc_css_prop($prop, &$value, $suffix = '')
    {

        if (isset($value) && $value) {
            return sprintf('%s: %s%s;', $prop, $value, $suffix);
        }

        return '';
    }
}

if (!function_exists('jtoc_match_string')) {
    /**
     * Matches a pattern against a string
     * Operator * can be used as a wildcard
     * Comparison is case insensitive
     *
     * @param [type] $pattern
     * @param [type] $str
     * @return bool
     */
    function jtoc_match_string($pattern, $str)
    {
        $pattern = preg_replace_callback('/([^*])/', function ($m) {
            return preg_quote($m[1], "/");
        }, $pattern);
        $pattern = str_replace('*', '.*', $pattern);
        // pre($pattern);
        // var_dump(preg_match('/^.*' . $pattern . '.*$/i', $str));
        return (bool) preg_match('/^.*' . $pattern . '.*$/i', $str);
    }
}



if (!function_exists('jtoc_mustache_key')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jtoc_mustache_key($string)
    {
        return '{{' . $string . '}}';
    }
}

if (!function_exists('jtoc_get_unit_value')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jtoc_get_unit_value($string, $numeric_only = false)
    {
        if (!$string) {
            return false;
        }

        if ($numeric_only === true) {
            if (strpos($string, '|') >= 0) {
                $string_parts = explode('|', $string);
                return $string_parts[0];
            }
        }

        if (strpos($string, '|') >= 0) {
            $string = str_replace('|', '', $string);

            //replaces the % litteral to actual symbol
            $string = str_replace('percent', '%', $string);
        }

        return $string;
    }
}

if (!function_exists('jtoc_get_dimensions_value')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jtoc_get_dimensions_value($array, $type = null)
    {
        $dim = jtoc_isset_or_null($array['dim']);
        $unit = jtoc_isset_or_null($array['unit']);

        if (!$dim || !$unit) {
            return false;
        }

        $offset1 = $type == 'corner' ? 'top-left' : 'top';
        $offset2 = $type == 'corner' ? 'top-right' : 'right';
        $offset3 = $type == 'corner' ? 'bottom-right' : 'bottom';
        $offset4 = $type == 'corner' ? 'bottom-left' : 'left';

        $top = jtoc_isset_or_null($array['dim'][$offset1]);
        $right = jtoc_isset_or_null($array['dim'][$offset2]);
        $bottom = jtoc_isset_or_null($array['dim'][$offset3]);
        $left = jtoc_isset_or_null($array['dim'][$offset4]);
        // JTOC()->log($top);

        //if 4 values are the same
        if (($top === $right) && ($top === $bottom) && ($top === $left)) {

            //any of values are unset, '0' means set by the user
            if (!$top && $top !== '0') {
                // JTOC()->log($top);
                return false;
            }

            return $top ? $array['dim'][$offset1] . $unit : 0; //any of 4
        }

        return sprintf(
            '%s %s %s %s',
            $top ? $array['dim'][$offset1] . $unit : 0,
            $right ? $array['dim'][$offset2] . $unit : 0,
            $bottom ? $array['dim'][$offset3] . $unit : 0,
            $left ? $array['dim'][$offset4] . $unit : 0
        );
    }
}

if (!function_exists('jtoc_decimal_to_roman')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jtoc_decimal_to_roman($decimal)
    {
        $roman = '';
        $matching = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        foreach ($matching as $number => $value) {
            $matches = intval($decimal / $value);
            $roman .= str_repeat($number, $matches);
            $decimal = $decimal % $value;
        }

        return $roman;
    }
}

if (!function_exists('jtoc_textarea_list_to_array')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jtoc_textarea_list_to_array($value)
    {
        $no_empty_lines = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $value);
        $lines = explode("\n", $no_empty_lines);

        return array_map(function ($element) {
            return trim($element);
        }, $lines);
    }
}

if (!function_exists('jtoc_tagify')) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jtoc_tagify($tag, $string, $attrs = null)
    {
        if (!$tag) {
            return $string;
        }

        $attr_str = jtoc_attrify($attrs);

        return sprintf('<%1$s%3$s>%2$s</%1$s>', $tag, $string,  $attr_str);
    }
}

if (!function_exists('jtoc_attrify')) {
    /**
     * Turns an associative array into an html attribute
     * Ex: [ 'class' => 'description' ] will render class="description"
     * Ex: [ 'class' => [ 'description', 'info' ] ] will render class="description info"
     *
     * @param [array] $attrs Associative array of attribute => value 
     * @return void
     */
    function jtoc_attrify($attrs = null)
    {
        if (!$attrs) {
            return "";
        }

        $attr_str = [];

        if ($attrs && is_array($attrs)) {
            $callback = function ($key,  $value) {
                if (gettype($value) === 'array') {
                    $str_value = implode(' ', $value);
                } else {
                    $str_value = $value;
                }
                return sprintf(' %1$s="%2$s"', $key, $str_value);
            };

            $attr_str = array_map($callback, array_keys($attrs), array_values($attrs));
        }

        return implode(' ', $attr_str);
    }
}

if (!function_exists('jtoc_process_attrs')) {

    /**
     * Renders an array of attributes into a linear string
     * Ex:
     * [
     *   [ 'id' => 'my-id' ]
     *   [ 'class' => [ 'description', 'info' ] ]
     *   [ 'style' => 'color: red;' ]
     * ]
     * 
     * Output:
     * id="my-id" class="description info" style="color: red;"
     *
     * @param [type] $attrs
     * @return void
     */
    function jtoc_process_attrs($attrs = null)
    {
        if (!$attrs || !is_array($attrs)) {
            return "";
        }

        $output = [];
        foreach ($attrs as $key => $value) {
            $the_attribute = jtoc_attrify([$key => $value]);

            if (!$the_attribute) {
                continue;
            }

            $output[] = $the_attribute;
        }


        return implode(' ', $output);
    }
}


if (!function_exists('jtoc_is_gutenberg_editor')) {
    function jtoc_is_gutenberg_editor()
    {
        if (!is_admin()) {
            return;
        }

        if (function_exists('is_gutenberg_page') && is_gutenberg_page()) {
            return true;
        }

        if (function_exists('get_current_screen')) {
            $current_screen = get_current_screen();
            
            if (gettype($current_screen) === 'object' && method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('jtoc_get_multipaged_content')) {
    function jtoc_get_multipaged_content()
    {
        global $multipage;

        $paged_content = null;

        //Global var $multipage should be 1 if the post content has multiple pages
        if ($multipage) {
            global $pages;
            $paged_content = $pages ? $pages : null;
        }

        return $paged_content;
    }
}



// add_shortcode('content_from_shortcode', 'myhp_func');


// add_action( 'joli_toc_before_table_of_contents', 'before_toc' );

// function before_toc(){
//     echo '<div style="display:flex;justify-content:center;">';
// }

// add_action( 'joli_toc_after_table_of_contents', 'after_toc' );

// function after_toc(){
//     echo '</div>';
// }
// add_filter('joli_toc_headings', 'filter_headings', 10, 2);
// add_action( 'joli_toc_after_title', 'echo_hr' );

// function echo_hr(){
//     echo '<hr class="joli-div">';
// }
// add_filter('joli_toc_headings', 'filter_headings', 10, 2);

// function filter_headings( $headings ){ 
//     $headings = array_map(function($heading){
//         //for H2 only
//         if ($heading['depth'] == 2){
//             //Capitalizes the first word only
//             $heading['title'] = ucfirst(strtolower($heading['title']));
//         }
//         return $heading;
//     }, $headings);

//     return $headings;
// }

// add_filter('joli_toc_headings', 'filter_headings', 10, 2);

// function filter_headings( $headings ){ 
//     $headings = array_map(function($heading){
//             //Do something to the title here
//             $heading['title'] = preg_replace("/^\d+.\s+/", "", $heading['title']);
//         return $heading;
//     }, $headings);

//     return $headings;
// }
// add_filter('joli_toc_headings', 'filter_headings', 10, 2);

// function filter_headings( $headings ){ 
//     $headings = array_map(function($heading){
//         //for H2 only
//         if ($heading['depth'] == 2){
//             //Capitalizes the first word only
//             $heading['title'] = ucfirst(strtolower($heading['title']));
//         }
//         return $heading;
//     }, $headings);

//     return $headings;
// }

// //
// add_filter('joli_toc_headings', 'filter_headings', 10, 2);

// function filter_headings($headings)
// {
//     global $post;
//     //Target specific post where 100 is the actual post id. Remove the condition for all posts.
//     if ($post->ID == 100) {
//         $headings = array_map(function ($heading) {
//             //Targets specific heading within post id 100
//             if ($heading['id'] == 'the-3-steps') {
//                 //Replaces the title
//                 $heading['title'] = '3 Steps';
//             }
//             //Targets another heading within post id 100
//             else if ($heading['id'] == 'other-id-within-the-same-post') {
//                 //Replaces the title
//                 $heading['title'] = 'Custom heading';
//             }
//             return $heading;
//         }, $headings);
//     }

//     //Use another condition for another post [OPTIONAL]
//     else if ($post->ID == 200) {

//     }

//     //Return the modified headings
//     return $headings;
// }

// add_filter('joli_toc_headings', 'filter_headings', 10, 2);

// function filter_headings($headings)
// {
//     global $post;
//     //Target specific post where 100 is the actual post id. Remove the condition for all posts.
//     if ($post->ID == 100) {
//         $headings = array_filter($headings, function ($heading) {
//             return ($heading['title'] != 'Subheading to remove' ||
//                 $heading['title'] != 'Other subheading to remove'
//             );
//         });
//     }
//     return $headings;
// }

// add_filter('dynamic_sidebar_params', 'filter_dynamic_sidebar_params', 10, 2);

// function filter_dynamic_sidebar_params($params)
// {
//     // pre($params);
//     // JTOC()->log($params);
//     return $params;
// }
// add_filter('joli_toc_the_content_filter_priority', 'joli_toc_content_priority');

// function joli_toc_content_priority($priority)
// {
//     return 1000000;
// }

// add_filter('joli_toc_post_content_preprocessing', function ($content) {
//     $content = str_replace(apply_filters('jolitoc_shortcode_tag', JTOC()::DOMAIN), '', $content);
//     if (version_compare(JTOC()::VERSION, '2.0.4', '<=')) {
//         return apply_filters('the_content', $content);
//     }
//     return $content;
// });


// add_filter('joli_toc_headings', 'filter_headings', 10, 2);
// // Example how to capitalize all the headings
// function filter_headings($headings)
// {
//     if (version_compare(JTOC()::VERSION, '2.0.5', '<=')) {
//         $headings = array_map(function ($heading) {
//             //Capitalizes the first word only
//             $heading['title'] = esc_html($heading['title']);

//             return $heading;
//         }, $headings);
//     }
//     return $headings;
// }

// add_filter('joli_toc_headings', 'edit_headings', 10, 2);

// function edit_headings( $headings ){ 
//     // add new item
//     $headings[] = [
//         'id' => 'my_id',
//         'title' => 'My Title',
//         'depth' => '2', // as h2
//     ];
//     return $headings;
// }

// add_filter('joli_toc_headings', 'edit_headings', 10, 2);

// function edit_headings( $headings ){ 
//     $headings = array_map(function($heading){
//         if ($heading['id'] == 'aut-perferendis-reprehenderit-ut-modi-mollitia'){
//             //edit id
//             $heading['url'] = 'https://customlink.com';
//         }
//         return $heading;
//     }, $headings);

//     return $headings;
// }

// add_filter('joli_toc_headings', 'edit_headings', 10, 2);

// function edit_headings($headings)
// {
//     //change the links from underscore to hyphens
//     $headings = array_map(function ($heading) {
//         $heading['id'] = str_replace('_', '-', $heading['id']);
//         return $heading;
//     }, $headings);

//     return $headings;
// }
// add_filter('jtoc_slide_out_toggle_html', function ($html_icon) {
//     //Returns the selected icon & some custom HTML
//     return $html_icon . '<p style="margin:0;padding:0 10px;font-size:10px;font-weight:bold;">CONTENTS</p>';
// });

// add_filter('joli_toc_toc_title', 'my_custom_title', 10, 1);

// function my_custom_title( $title ){ 
//     return '<h2>' . $title . '</h2>';
// }

// add_filter('joli_toc_disable_toc_custom', 'toc_prevent_duplicate', 10, 3);

// function toc_prevent_duplicate($value, $content, $post)
// {
//     if (strpos($content, 'wpj-jtoc wpj-jtoc--main') !== false) {
//         return true;
//     }

//     //default to false
//     return $value;
// }

// add_filter('joli_toc_disable_toc_custom', 'toc_auto_insert_categories', 10, 3);

// function toc_auto_insert_categories($value, $content, $post)
// {
//     //list of categories allowed
//     $allowed = ['cinema', 'fashion'];
//     $categories = get_the_category($post->ID);

//     $post_cat = array_map(function ($item) {
//         return $item->slug;
//     }, $categories);

//     $has_cat =  array_intersect($allowed, $post_cat) !== [];

//     //limit the function to posts only
//     if ($post->post_type === 'post' && $has_cat) {

//         return $has_cat !== true;
//     }
//     //default to false
//     return $value;
// }
// add_action('joli_toc_before_begin_item_content', function ($args) {
//     // $args['id']
//     // $args['title']
//     // $args['counter']
//     // $args['depth']
//     // $args['url']
//     // pre($args);
//     if ($args['counter'] != 1 && $args['depth'] == 2){
//         echo '<hr>';
//     }
// }, 10, 1);

// add_filter('joli_toc_item_link_attributes', function ($attrs, $args) {

//     $attrs['rel'] =  ['nofollow', 'ugc'];
//     $attrs['style'] = 'color: red;';
//     return $attrs;
// });

// add_filter('joli_toc_item_link_attributes', 'add_toc_link_attributes', 10, 2);

// function add_toc_link_attributes($attrs, $args)
// {
//     global $post;

//     $selected_posts = [123, 124, 125];

//     //Adds nofollow attribute only to specific posts
//     if (in_array($post->ID, $selected_posts)) {
//         $attrs['rel'] =  'nofollow';
//     }

//     return $attrs;
// }

// add_filter('the_content', function ($content) {
//     return do_shortcode('[joli-toc]') . $content;
// }, 1);