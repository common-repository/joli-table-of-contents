<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;


class AdminNotices
{

    private $can_display_v2;
    private $options;


    public function __construct()
    {
        $this->options = JTOC()->requestService(OptionsController::class);
    }

    public function initNotices()
    {
        if ($this->canDisplayV2Notice()) {
            $this->showV2Notice();
        }
    }

    public function showV2Notice()
    {

        add_action('admin_notices', [$this, 'makeV2Notice']);
    }


    public function makeV2Notice()
    {
        
        $data = [
            'v2_what_new_url' => 'https://wpjoli.com/joli-table-of-contents-v2-what-is-new/',
        ];
        return JTOC()->render(['notices' => 'v2-warning'], $data);
    }

    public function makeGoProNotice()
    {
        $base_url = 'https://wpjoli.com/joli-table-of-contents/';
        $params = '?utm_source=' . getHostURL() . '&utm_medium=admin-notice';

        $data = [
            'pro_url' => $base_url . $params,
            'pro_url_v' => $base_url . '#visibilities' . $params,
        ];
        return JTOC()->render(['notices' => 'go-pro'], $data);
    }

    public function canDisplayV2Notice()
    {

        if ($this->can_display_v2 === null) {

            //first time
            if (JTOC_USE_V1 === 1) {
                $this->can_display_v2 = true;
            } 
            else {
                $time = $this->options->get('use_v1');
                if ($time > 1) {
                    $this->can_display_v2 = time() > $time;
                } else {
                    $this->can_display_v2 = false;
                }
                // $this->options->set('joli_toc_use_v1', time() + WEEK_IN_SECONDS * 4);
            }

            // $this->can_display_v2 = JTOC_USE_V1 === '' ? true : false;
        }
        // JTOC()->log(JTOC_USE_V1);
        return $this->can_display_v2;
    }


    public function jtocHandleV2Notice()
    {
        if (isset($_POST['handler'])) {
             if ($_POST['handler'] == 'v2') {

                if ($_POST['method'] == 'go') {
                    $this->goV2();
                    wp_send_json_success([
                        'gov2' => admin_url('admin.php?page=' . 'joli_table_of_contents_settings'),
                    ]);
                } else if ($_POST['method'] == 'remind') {
                    $this->remindLater();
                    wp_send_json_success([
                        'gov2' => false,
                    ]);
                } 
            }
        }
        die;
    }

    public function goV2()
    {
        $this->options->set('use_v1', -1);
    }

    public function remindLater()
    {
        $this->options->set('use_v1', time() + WEEK_IN_SECONDS * 4);
    }
}
