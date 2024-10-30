<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC\Controllers;

use ErrorException;
use WPJoli\JoliTOC\Application;
use WPJoli\JoliTOC\Controllers\Callbacks\SettingsCallbacks;
use WPJoli\JoliTOC\Controllers\PostTypeSettingController;
use WPJoli\JoliTOC\Controllers\V1ToV2Settings;
use WPJoli\JoliTOC\Config\Settings;
class SettingsController {
    protected $prefix;

    protected $page;

    protected $page_name;

    protected $settings = [];

    //default settings
    public $groups = [];

    public $sections = [];

    public $fields = [];

    protected $settings_cb;

    //will contain the options array stored in db
    protected $cached_settings;

    //post type settings
    protected $active_post_type;

    protected $page_name_post_type = [];

    protected $post_type_settings = [];

    /**
     * Undocumented function
     *
     * @param [type] $post_type specify post_type to fetech specific options 
     */
    public function __construct( $post_type = null ) {
        // set_error_handler(function($severity, $message, $file, $line) {
        //     if (error_reporting() & $severity) {
        //         throw new ErrorException($message, 0, $severity, $file, $line);
        //     }
        // });
        $this->settings_cb = new SettingsCallbacks();
        //loads the default settings array
        $this->settings = $this->defaultSettings();
        $this->prefix = Application::SLUG . '_';
        // $this->page_name = Application::SETTINGS_V2_SLUG . $active_post_type_suffix;
        $this->page_name = Application::SETTINGS_V2_SLUG;
        $this->initSettings();
        //global settings
        $db_options = get_option( $this->page_name );
        if ( $db_options === false ) {
            $this->setupSettings();
            $db_options = get_option( $this->page_name );
            //V1 to v2 settings
            $v1_options = get_option( Application::SETTINGS_SLUG );
            if ( is_array( $v1_options ) ) {
                $v2_controller = new V1ToV2Settings($v1_options, $db_options);
                $updated_options = $v2_controller->convertV1toV2();
                // pre($updated_options);
                if ( is_array( $updated_options ) ) {
                    update_option( $this->page_name, $updated_options );
                    $db_options = get_option( $this->page_name );
                }
            }
            // JTOC()->log('CONVERTED V1 to V2');
        }
        // if (!true) {
        //     $db_options = get_option($this->page_name);
        //     //V1 to v2 settings
        //     $v1_options = get_option(Application::SETTINGS_SLUG);
        //     $v2_controller = new V1ToV2Settings($v1_options, $db_options);
        //     $updated_options = $v2_controller->convertV1toV2();
        //     echo '<div style="margin-left:200px;">';
        //     pre($updated_options);
        //     echo '</div>';
        //     JTOC()->log('CONVERTED V1 to V2');
        // }
        $this->cached_settings = $db_options;
        // $this->cached_settings = $current_options;
        // var_dump($this->cached_settings);
    }

    public function handleResetSettings() {
        //Reset settings button clicked
        if ( isset( $_POST['jtoc_reset_settings'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'jtoc_reset' ) ) {
            $this->resetSettings();
        }
    }

    public function getPageName( $post_type = null ) {
        $active_post_type_suffix = '';
        $page_name = Application::SETTINGS_V2_SLUG . $active_post_type_suffix;
        return $page_name;
    }

    private function defaultSettings() {
        $settings = (include JTOC()->path( 'config/defaults_v2.php' ));
        return $settings;
    }

    public function initSettings() {
        $page_name = $this->getPageName();
        $settings = $this->settings;
        $this->groups = [];
        $this->sections = [];
        $this->fields = [];
        $cpt = 0;
        //Init Groups-----------------
        foreach ( $settings as $group ) {
            $_group = [
                'id'    => $group['group'],
                'name'  => $group['group'],
                'label' => $group['label'],
                'args'  => jtoc_isset_or_null( $group['args'] ),
            ];
            $this->groups[] = $_group;
            //Init Sections-------------------
            foreach ( $group['sections'] as $section ) {
                $_section = [
                    'name'  => $section['name'],
                    'group' => $group['group'],
                    'title' => $section['title'],
                    'desc'  => jtoc_isset_or_null( $section['desc'] ),
                ];
                $this->sections[] = $_section;
                //Init Fields-------------------
                foreach ( $section['fields'] as $field ) {
                    $_args = $field['args'];
                    $active_post_type = ( is_admin() ? jtoc_isset_or_null( $_GET['jtoc_post_type'], true ) : null );
                    $is_global = jtoc_isset_or_null( $_args['is_global'] );
                    //adds some args automatically
                    // $option_id = $section['name'] . '.' . $field['id'];
                    $option_id = str_replace( '-', '_', $field['id'] );
                    // $_args['name'] = $page_name . '[' . $section['name'] . '.' . $field['id'] . ']';
                    $_args['name'] = $page_name . '[' . $option_id . ']';
                    $_args['id'] = $option_id;
                    $pro_class = ( jtoc_isset_or_null( $_args['pro'] ) === true ? ' joli-pro' : '' );
                    $new_class = ( jtoc_isset_or_null( $_args['new'] ) === true ? ' joli-new' : '' );
                    $is_global_class = ( $is_global && $active_post_type ? ' joli-is-global' : '' );
                    $_args['class'] = 'tab-' . $group['group'] . $pro_class . $new_class . $is_global_class . ' joli-field--' . $field['id'];
                    $_args['type'] = $field['type'];
                    $info_html = '<span class="joli-field-info dashicons dashicons-info-outline"></span>';
                    $info = ( jtoc_isset_or_null( $_args['desc'] ) ? $info_html . '<div class="joli-info-bubble">' . $_args['desc'] . '</div>' : '' );
                    $_field = [
                        'id'            => $field['id'],
                        'option_id'     => $option_id,
                        'section'       => $section['name'],
                        'group'         => $group['group'],
                        'label'         => $field['title'] . $info,
                        'type'          => $field['type'],
                        'default'       => jtoc_isset_or_null( $field['default'] ),
                        'initial_value' => jtoc_isset_or_null( $field['initial_value'] ),
                        'args'          => $_args,
                        'name'          => $page_name . '[' . $option_id . ']',
                        'sanitize'      => jtoc_isset_or_null( $field['sanitize'] ),
                        'sanitize_args' => jtoc_isset_or_null( $field['sanitize_args'] ),
                        'global'        => $is_global,
                    ];
                    $this->fields[] = $_field;
                    $cpt++;
                    // 'fields' => [
                    //     [
                    //         'id' => 'min-width',
                    //         'title' => esc_html__('Minimum width', 'joli-table-of-contents'),
                    //         'type' => 'text',
                    //         'args' => [
                    //             'class' => 'ui-toggle'
                    //         ],
                    //         'default' => '300px',
                    //     ],
                    // ],
                }
            }
        }
        // pre($this->fields);
    }

    public function registerSettings() {
        $post_types = get_post_types( [
            'public' => true,
        ], 'objects' );
        //Global settings
        $this->registerSettingsGroup();
        //--Register groups-----
        // $_group = [
        //     'id' => $this->prefix . $group['id'],
        //     'name' => $group['id'],
        //     'label' => $group['label'],
        //     'callback' => $group['label'],
        // ];
        // foreach ($this->groups as $group) {
        //     register_setting(
        //         $group[ 'id' ],
        //         $group[ 'id' ],
        //         [
        //             'sanitize_callback' => [ $this->settings_cb, 'sanitizeCallback']
        //         ]
        //     );
        // }
    }

    public function registerSettingsGroup( $group = null ) {
        $setting_name = $this->page_name;
        //--Register Sections-----
        // $_section = [
        //     'name' => $this->prefix . $section['name'],
        //     'group' => $this->prefix . $group['id'],
        //     'title' => $section['title'],
        //     'callback' => [ $this->settings_cb, 'sectionCallback'],
        //     // 'desc' => $section['desc'],
        // ];
        foreach ( $this->sections as $section ) {
            add_settings_section(
                $section['name'],
                $section['title'],
                [$this, 'sectionCallback'],
                $setting_name,
                [
                    'before_section' => '<div class="joli-section joli-section--' . $section['name'] . '">',
                    'after_section'  => '</div>',
                ]
            );
        }
        //--Register Fields-----
        // $_field = [
        //     'id' => $field['id'],
        //     'group' => $this->prefix . $group['id'],
        //     'section' => $this->prefix . $section['name'],
        //     'label' => $field['title'],
        //     // 'desc' => ArrayHelper::getValue($config, 'desc'),
        //     'type' => $field['type'],
        //     'default' => $field['default'],
        //     'args' => $field['args'],
        //     'callback' => $field['callback'],
        //     'name' => $this->prefix . $group['id'] . '[' . $field['id'] . ']',
        // ];
        // $setting_group = $group ?  $setting_name . '_' . $group : $setting_name;
        // JTOC()->log($setting_name);
        register_setting( $setting_name, $setting_name, [
            'type'              => 'array',
            'sanitize_callback' => [$this, 'sanitizeCallback'],
        ] );
        foreach ( $this->fields as $field ) {
            add_settings_field(
                $field['section'] . '.' . $field['id'],
                $field['label'],
                [$this->settings_cb, 'inputField'],
                // $this->assignFieldCallback( $field[ 'type' ] ),
                $setting_name,
                // $field[ 'group' ],
                $field['section'],
                $field['args']
            );
        }
    }

    public function sanitizeCallback( $input ) {
        // [
        //     "general.show-title" => "Title",
        //     ...
        // ]
        foreach ( $input as $option => $value ) {
            $field_item = arrayFind( $option, 'option_id', $this->fields );
            $key = 'sanitize';
            $sanitization = jtoc_isset_or_null( $field_item[$key] );
            //Since 2.0.0
            $sanitize_args = jtoc_isset_or_null( $field_item['sanitize_args'] );
            //If no specific sanitation is passed, use the type as default sanitation
            if ( !$sanitization ) {
                $sanitization = jtoc_isset_or_null( $field_item['type'] );
            }
            //Calls the corresponding sanitization function
            if ( $sanitization ) {
                //builds the corresponding method name: ex: sanitizeText found in the SettingsCallbacks class
                $method_name = 'sanitize' . ucfirst( $sanitization );
                if ( method_exists( $this->settings_cb, $method_name ) ) {
                    $input[$option] = call_user_func( [$this->settings_cb, $method_name], $value, $sanitize_args );
                }
            }
        }
        return $input;
    }

    /**
     * Displays the section description if any
     */
    public function sectionCallback( $args ) {
        // pre($args);
        foreach ( $this->sections as $section ) {
            if ( $section['name'] === $args['id'] ) {
                if ( isset( $section['desc'] ) && $section['desc'] ) {
                    echo '<div class="joli-section-desc" style="display:none">' . $section['desc'] . '</div>';
                }
                break;
            }
        }
    }

    /**
     * Setup settings on plugin activation
     *
     * @return void
     */
    public function setupSettings() {
        $page_name = $this->getPageName();
        if ( !$this->fields ) {
            $this->initSettings();
        }
        $options = [];
        //runs through each "prepared" option
        foreach ( $this->fields as $field ) {
            $option_item[$field['option_id']] = ( isset( $field['initial_value'] ) ? $field['initial_value'] : $field['default'] );
            $options += $option_item;
            // $option_item[ $field[ 'option_id' ] ] = $field[ 'default' ];
            // $options[] = $option_item;
        }
        //add the option to the database if none
        if ( get_option( $page_name ) === false ) {
            add_option( $page_name, $options );
        }
        $this->cached_settings = $options;
    }

    public function resetSettings() {
        delete_option( $this->getPageName() );
        $this->setupSettings();
    }

    /**
     * Gets the global option from the database or from the local cache
     * Ex: getOption( 'general', 'prefix' );
     *
     * @param [type] identifier
     * @param [type] section (parent of identifier)
     * @param [type] default returns only the default value if true
     * @return mixed option value, default value, or null
     */
    // public function getOption($name, $section, $default = false, $options_override = null)
    // {
    //     $option_selector = $section . '.' . $name;
    //     $field_item = arrayFind($name, 'id', $this->fields);
    //     // error_log($name);
    //     $default_val = $field_item['default'];
    //     if ($default === true) {
    //         return $default_val;
    //     }
    //     $cached_settings = $this->getCachedSettings();
    //     if ($options_override) {
    //         $options = $options_override;
    //     } else if ($cached_settings) {
    //         $options = $cached_settings; //get option from cache
    //     } else {
    //         $options = get_option($this->getPageName()); //get option from database
    //     }
    //     $value = null;
    //     if ($options && is_array($options)) {
    //         $value = $this->fetchOption($option_selector, $options, $default_val);
    //     }
    //     if ($value !== null) {
    //         return $value;
    //     }
    //     return;
    // }
    public function getFieldsIDs( $additional_options = null ) {
        $id_list = array_map( function ( $option ) {
            return $option['option_id'];
        }, $this->fields );
        if ( $additional_options && is_array( $additional_options ) ) {
            $keys = array_keys( $additional_options );
            // pre($keys);
            // pre($id_list);
            // $id_list = array_merge($keys, ['ppede', 'cucu']);
            $id_list = array_merge( $keys, $id_list );
        }
        return $id_list;
    }

    public function isOptionGlobal( $option_id ) {
        $field_item = arrayFind( $option_id, 'option_id', $this->fields );
        return jtoc_isset_or_null( $field_item['global'] ) === true;
    }

    /**
     * Gets the global option from the database or from the local cache
     * Ex: getOption( 'general', 'prefix' );
     *
     * @param [type] identifier
     * @param [type] section (parent of identifier)
     * @param [type] default returns only the default value if true
     * @return mixed option value, default value, or null
     */
    public function getOption( $option_id, $default = false, $options_override = null ) {
        $field_item = arrayFind( $option_id, 'option_id', $this->fields );
        $default_val = jtoc_isset_or_null( $field_item['default'] );
        if ( $default === true ) {
            return $default_val;
        }
        $cached_settings = $this->getCachedSettings();
        if ( $options_override ) {
            $options = $options_override;
        } else {
            if ( $cached_settings ) {
                $options = $cached_settings;
                //get option from cache
            } else {
                $options = get_option( $this->getPageName() );
                //get option from database
            }
        }
        $value = null;
        if ( $options && is_array( $options ) ) {
            $value = $this->fetchOption( $option_id, $options, $default_val );
        }
        if ( $value !== null ) {
            return $value;
        }
        return;
    }

    /**
     * Get the cached settings, either global or the post type settings (auto-detection)
     * Set $global to true to force the global settings
     *
     * @param boolean $global
     * @return void
     */
    private function getCachedSettings( $global = false ) {
        $cached_settings = null;
        if ( $global ) {
            $cached_settings = $this->cached_settings;
        } else {
            if ( jtoc_xy()->can_use_premium_code__premium_only() && $this->active_post_type !== null && jtoc_isset_or_null( $this->post_type_settings[$this->active_post_type] ) !== null ) {
                $cached_settings = $this->post_type_settings[$this->active_post_type];
            } else {
                $cached_settings = $this->cached_settings;
            }
        }
        return $cached_settings;
    }

    /**
     * Returns an array of all the options
     *
     * @param [type] $post_type if post_type is set to false, we will retrieve the global options
     * @return void
     */
    public function getOptions( $post_type = null ) {
        if ( $post_type ) {
            $option_selector = Application::SETTINGS_V2_SLUG . '_' . $post_type;
            $options = get_option( $option_selector );
            //get option from database
            return $options;
        }
        $global = $post_type === false;
        $cached_settings = $this->getCachedSettings( $global );
        if ( $cached_settings ) {
            $options = $cached_settings;
            //get option from cache
        } else {
            $options = get_option( $this->getPageName() );
            //get option from database
        }
        if ( $options !== null ) {
            return $options;
        }
        return;
    }

    /**
     * Fetches the option from the unserialized array
     */
    public function fetchOption( $option_selector, $options, $default = null ) {
        $value = null;
        if ( is_array( $options ) && array_key_exists( $option_selector, $options ) ) {
            $value = $options[$option_selector];
        }
        if ( $value === null ) {
            return $default;
        }
        return $value;
    }

    public function getGroups() {
        // (
        //     [id] => general
        //     [name] => general
        //     [label] => General
        // )
        return $this->groups;
    }

    public function exportUserSetting() {
        check_ajax_referer( JTOC()::SLUG, 'nonce' );
        $apt = jtoc_isset_or_null( $_POST['active_post_type'] );
        $current_settings = $this->getOptions( $apt );
        if ( $current_settings ) {
            $hash = hash( 'sha256', json_encode( $current_settings ) );
            $current_settings['_jtoc_hash'] = $hash;
            wp_send_json_success( [
                'settings' => json_encode( $current_settings ),
            ] );
        }
        wp_send_json_error( [
            'message' => 'Invalid value',
        ] );
        die;
    }

    public function importUserSetting() {
        check_ajax_referer( JTOC()::SLUG, 'nonce' );
        $apt = $_POST['active_post_type'];
        $file = $_POST['file'];
        $settings = json_decode( stripslashes( $file ), true );
        $hash = jtoc_isset_or_null( $settings['_jtoc_hash'] );
        if ( !$hash ) {
            wp_send_json_error( [
                'message' => 'File cannot be verified',
            ] );
        }
        unset($settings['_jtoc_hash']);
        $actual_hash = hash( 'sha256', json_encode( $settings ) );
        if ( $hash !== $actual_hash ) {
            wp_send_json_error( [
                'message' => 'File is corrupted, aborting.',
            ] );
        }
        $post_type = ( $apt ? $apt : null );
        $page_name = $this->getPageName( $post_type );
        if ( !$page_name ) {
            wp_send_json_error( [
                'message' => 'An error occured. Please reload the page and try again.',
            ] );
        }
        $updated = update_option( $page_name, $settings );
        wp_send_json_success( [
            'message' => 'File OK',
            'updated' => $updated,
        ] );
        die;
    }

}
