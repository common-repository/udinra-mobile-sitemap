<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

udinra_uninstall_mobile_plugin();

function udinra_uninstall_mobile_plugin () {
    if ( function_exists( 'is_multisite' ) && is_multisite() ) {
        if ( false == is_super_admin() ) {
            return;
        }
        $blogs = wp_get_sites();
        foreach ( $blogs as $blog ) {
            switch_to_blog( $blog[ 'blog_id' ] );
            udinra_delete_mobile_options();
            restore_current_blog();
        }
    } else {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
		udinra_delete_mobile_options();
	}
}

function udinra_delete_mobile_options () {
}

?>