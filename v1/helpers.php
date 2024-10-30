<?php

use WPJoli\JoliTOC\Controllers\SettingsController;
use Cocur\Slugify\Slugify;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Returns an instance of the application
 * @return WPJoli\JoliTOC\Application
 */
function JTOC()
{
    return WPJoli\JoliTOC\Application::instance();
}

//Custom toggle icons---
// add_filter('joli_toc_expand_str', function(){ return '<i class="fa fa-angle-down"></i>';});
// add_filter('joli_toc_collapse_str', function(){ return '<i class="fa fa-times"></i>';});

if (!function_exists('pre')) {
    function pre($data)
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
if (!function_exists('apre')) {
    function apre($data)
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
    function jtoc_get_option($name, $section)
    {
        $settings = JTOC()->requestService(SettingsController::class);
        return $settings->getOption($name, $section);
    }
}


if (!function_exists('isset_or_null')) {
    /**
     * Returns $var or null if $var is not set
     * $empty_string = true returns an empty string instead of null
     */
    function isset_or_null(&$var, $empty_string = null)
    {
        return  isset($var) ? $var : ($empty_string ? '' : null);
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

if (!function_exists('saveHTMLNoWrapping')) {
    function saveHTMLNoWrapping($html)
    {
        // $htmlh = '<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">';
        $html_fragment = preg_replace('/<!DOCTYPE.+?>/', '',  trim($html->saveHTML()));

        if (strpos($html_fragment, '<html><body>') === 0) {
            $html_fragment = substr($html_fragment, 12, -14);
        }

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
//         $headings = array_filter($headings, function ($heading) {
//             return ($heading['title'] != 'Subheading to remove' ||
//                 $heading['title'] != 'Other subheading to remove'
//             );
//         });
//     }
//     return $headings;
// }
