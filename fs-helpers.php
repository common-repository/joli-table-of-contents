<?php

function jtoc_xy_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
) {
    return sprintf(
        __( 'Hey %1$s', 'joli-table-of-contents' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'joli-table-of-contents' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

jtoc_xy()->add_filter(
    'connect_message_on_update',
    'jtoc_xy_custom_connect_message_on_update',
    10,
    6
);
function jtoc_fs_uninstall_cleanup() {
    // delete_option( 'joli_toc_settings' );
    delete_option( 'joli_toc_rating_time' );
    delete_option( 'joli_toc_gopro_time' );
}

jtoc_xy()->add_action( 'after_uninstall', 'jtoc_fs_uninstall_cleanup' );
if ( !function_exists( 'jtoc_fs_file' ) ) {
    function jtoc_fs_file(  $file  ) {
        return $file;
    }

}
if ( !function_exists( 'jtoc_fs_custom_icon' ) ) {
    function jtoc_fs_custom_icon() {
        return dirname( __FILE__ ) . '/assets/icon-256x256.png';
    }

    jtoc_xy()->add_filter( 'plugin_icon', 'jtoc_fs_custom_icon' );
}