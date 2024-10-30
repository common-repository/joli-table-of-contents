<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;

use WP_REST_Response;
use WPJoli\JoliTOC\Engine\ContentProcessing;
use WPJoli\JoliTOC\Engine\TOCBuilder;

class RestApi
{

    protected $namespace;

    public function __construct()
    {
        $this->namespace   = 'jolitoc/v1';
    }

    public function registerRestRoutes()
    {

        //Featured Image Generation from JSON Template
        register_rest_route($this->namespace, '/' . 'headings', [
            'methods' => 'POST',
            'callback' => [$this, 'getTableOfContentsHeadings'],
            'permission_callback' => function () {
                return current_user_can('edit_posts');
            }
            //            'args' => array(
            //              'id' => array(
            //                'validate_callback' => function($param, $request, $key) {
            //                  return is_numeric( $param );
            //                }
            //              ))
        ]);
    }

    public function getTableOfContentsHeadings($request)
    { 
        //Check headers and license
        $headers = $request->get_headers();

        //Headers should be returned as key => value but are returned as key => array[0] =>value
        //Then we modify the array accordingly
        if (is_array($headers['content_type'])) {
            $headers = array_map(function ($header) {
                return $header[0];
            }, $headers);
        }

        $params = $request->get_params();
        if (!$params) {
            //Return error
        }

        $content = json_decode($params['content']);
        $attr = ($params['attributes']);


        //Generate headings from content
        /** @var ContentProcessing $cp */
        $cp = JTOC()->requestService(ContentProcessing::class);

        $toc_builder = new TOCBuilder(null, null, $attr);

        $filtered_content = apply_filters('the_content', $content);
        $processed = $cp::Process($filtered_content, true, $toc_builder);


        //Send the generated featured image URL OR the featured image
        return new WP_REST_Response([
            'headings' => $processed['headings'],
        ]);
    }
}
