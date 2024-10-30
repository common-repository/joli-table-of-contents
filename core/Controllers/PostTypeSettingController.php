<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;


class PostTypeSettingController
{
    private $options;
    private $post_types;

    public function __construct()
    {
        $this->options = JTOC()->requestService(OptionsController::class);
        $this->post_types = $this->options->get('active_setting_post_types');
    }

    public function getActivatedPostType()
    {
        return $this->post_types ? $this->post_types : [];
    }


    public function updatePostTypeSetting()
    {
        check_ajax_referer(JTOC()::SLUG, 'nonce');

        $value = jtoc_isset_or_null($_POST['active_post_type']);

        if ($value !== null || $value !== false) {
            $update = $this->options->set('active_setting_post_types', $value);

            wp_send_json_success([
                'return' => $update,
            ]);
        }

        wp_send_json_error([
            'message' => 'Invalid value',
        ]);

        die;
    }

    public function isPostTypeSettingActivated($post_type)
    {
        if (!is_string($post_type)){
            return false;
        }

        if ( !is_array($this->post_types)){
            return false;
        }
        
        return in_array($post_type, $this->post_types);
    }
}
