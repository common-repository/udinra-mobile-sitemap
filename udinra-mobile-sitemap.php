<?php
/*
Plugin Name: Udinra Mobile Sitemap
Plugin URI: https://udinra.com/downloads/mobile-sitemap-pro-wordpress-plugin
Description: Automatically generates Google Mobile Sitemap and submits it to Google,Bing and Ask.com.
Author: Udinra
Version: 1.7
Author URI: https://udinra.com
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
function Udinra_Mobile() {
	$udinra_sitemap_response = '';
	if(isset($_POST['create_sitemap'])) {
		udinra_mobile_sitemap_loop($udinra_sitemap_response);
	}
	include 'lib/udinra_html_mobile.php';
}

function udinra_mobile_sitemap_loop(&$udinra_sitemap_response) {
	include 'init/udinra_init_mobile.php';
	include 'core/udinra_core.php';
	include 'exit/udinra_ping_mobile.php';
}

function udinra_mobile_sitemap_admin() {
	if (function_exists('add_options_page')) {
		add_options_page('Udinra Mobile Sitemap', 'Udinra Mobile Sitemap', 'manage_options', basename(__FILE__), 'Udinra_Mobile');
	}
}

function UdinraMobileWritable($udinra_filename) {
	if(!is_writable($udinra_filename)) {
		return false;
	}
	return true;
}

function udinra_mobile_post_unpublished( $new_status, $old_status) {
	
    if ( $old_status !== 'publish'  &&  $new_status == 'publish') {
		udinra_mobile_sitemap_loop($udinra_sitemap_response);
    }
	if ( $old_status == 'publish'  &&  $new_status == 'publish') {
		udinra_mobile_sitemap_loop($udinra_sitemap_response);
    }
}

function udinra_mobile_admin_notice() {
	if (get_option('udinra_mobile_decision') == 2) {
		global $current_user ;
		$user_id = $current_user->ID;
		if ( ! get_user_meta($user_id, 'udinra_mobile_admin_notice') ) {
			echo '<div class="notice notice-info"><p>'; 
			printf(__('Must have mobile sitemap plugin if using AMP plugin. <a href="https://udinra.com/downloads/mobile-sitemap-pro-wordpress-plugin">Know More</a> | <a href="%1$s">Hide Notice</a>'), '?udinra_mobile_admin_ignore=0');
			echo "</p></div>";
		}
	}
}

function udinra_mobile_admin_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset($_GET['udinra_mobile_admin_ignore']) && '0' == $_GET['udinra_mobile_admin_ignore'] ) {
		add_user_meta($user_id, 'udinra_mobile_admin_notice', 'true', true);
	}
}

function udinra_mobile_act($networkwide) {
    global $wpdb;
    if (function_exists('is_multisite') && is_multisite()) {
        if ($networkwide) {
            $old_blog = $wpdb->blogid;
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
				add_filter('generate_rewrite_rules', 'udinra_mobile_rewrite');
				global $wp_rewrite;
				$wp_rewrite->flush_rules();
            }
            switch_to_blog($old_blog);
            return;
        }
		else {
			add_filter('generate_rewrite_rules', 'udinra_mobile_rewrite');
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
    }
	else {	

	}
}

function udinra_mobile_deact($networkwide) {
    global $wpdb;
    if (function_exists('is_multisite') && is_multisite()) {
        if ($networkwide) {
            $old_blog = $wpdb->blogid;
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
				remove_action( 'do_feed_sitemap-index-mobile','load_sitemap_index_mobile');
				remove_filter('generate_rewrite_rules', 'udinra_mobile_rewrite');
				remove_action( 'transition_post_status', 'udinra_mobile_post_unpublished', 10, 3 );
				remove_action('admin_menu','udinra_mobile_sitemap_admin');	
				remove_action('admin_notices', 'udinra_mobile_admin_notice');
				remove_action('admin_init', 'udinra_mobile_admin_ignore');
				remove_action( 'wpmu_new_blog', 'udinra_mobile_new_blog', 10, 6);        

				global $wp_rewrite;
				$wp_rewrite->flush_rules();
            }
            switch_to_blog($old_blog);
            return;
        }   
		else {
			remove_action( 'do_feed_sitemap-index-mobile','load_sitemap_index_mobile');
			remove_filter('generate_rewrite_rules', 'udinra_mobile_rewrite');
			remove_action( 'transition_post_status', 'udinra_mobile_post_unpublished', 10, 3 );
			remove_action('admin_menu','udinra_mobile_sitemap_admin');	
			remove_action('admin_notices', 'udinra_mobile_admin_notice');
			remove_action('admin_init', 'udinra_mobile_admin_ignore');
			remove_action( 'wpmu_new_blog', 'udinra_mobile_new_blog', 10, 6);        
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
    }
	else {	
		remove_action( 'transition_post_status', 'udinra_mobile_post_unpublished', 10, 3 );
		remove_action('admin_menu','udinra_mobile_sitemap_admin');	
		remove_action('admin_notices', 'udinra_mobile_admin_notice');
		remove_action('admin_init', 'udinra_mobile_admin_ignore');
	}
}
 
function udinra_mobile_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    global $wpdb;
 
    if (is_plugin_active_for_network('udinra-mobile-sitemap/udinra-mobile-sitemap.php')) {
        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
		add_filter('generate_rewrite_rules', 'udinra_mobile_rewrite');
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
        switch_to_blog($old_blog);
    }
}
function load_sitemap_index_mobile() {
	load_template( dirname( __FILE__ ) . '/feed-sitemap-mobile.php' );
}
function udinra_mobile_rewrite($wp_rewrite) {
	$udinra_mob_feed_rules = array( 'sitemap-index-mobile.xml$' => $wp_rewrite->index . '?feed=sitemap-index-mobile' );
	$wp_rewrite->rules = $udinra_mob_feed_rules + $wp_rewrite->rules;
}

function udinra_mobile_settings_plugin_link( $links, $file ) 
{
    if ( $file == plugin_basename(dirname(__FILE__) . '/udinra-mobile-sitemap.php') ) 
    {
        $in = '<a href="options-general.php?page=udinra-mobile-sitemap">' . __('Settings','udmobile') . '</a>';
        array_unshift($links, $in);
   }
    return $links;
}

function udinra_mobile_admin_style($hook) {
	if($hook == 'settings_page_udinra-mobile-sitemap') {
		wp_enqueue_style( 'udinra_mobile_pure_style', plugins_url('css/udstyle.css', __FILE__) );	
		wp_enqueue_script( 'udinra_mobile_pure_js', plugins_url('js/udinra_slideshow.js', __FILE__),array(), '1.0.0', true );
    }
}
 
register_activation_hook(__FILE__, 'udinra_mobile_act');
register_deactivation_hook(__FILE__, 'udinra_mobile_deact');

add_action( 'transition_post_status', 'udinra_mobile_post_unpublished', 10, 3 );
add_action('admin_menu','udinra_mobile_sitemap_admin');	
add_action('admin_notices', 'udinra_mobile_admin_notice');
add_action('admin_init', 'udinra_mobile_admin_ignore');

add_action( 'do_feed_sitemap-index-mobile','load_sitemap_index_mobile',10,1 );
add_action( 'wpmu_new_blog', 'udinra_mobile_new_blog', 10, 6);        
add_filter( 'plugin_action_links', 'udinra_mobile_settings_plugin_link', 10, 2 );
add_action( 'admin_enqueue_scripts', 'udinra_mobile_admin_style' );

?>
