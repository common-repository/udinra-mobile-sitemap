<?php

$udinra_mob_pluginurl = plugins_url();

if ( preg_match( '/^https/', $udinra_mob_pluginurl ) && !preg_match( '/^https/', get_bloginfo('url') ) )
	$udinra_mob_pluginurl = preg_replace( '/^https/', 'http', $udinra_mob_pluginurl );

define( 'UDINRA_MOB_FRONT_URL', $udinra_mob_pluginurl.'/' );
global $wpdb;

$udinra_index_xml   = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$udinra_index_xml  .= '<?xml-stylesheet type="text/xsl" href='.'"'. UDINRA_MOB_FRONT_URL . 'udinra-mobile-sitemap/xsl/xml-index-sitemap.xsl'. '"'.'?>' .PHP_EOL;
$udinra_index_xml  .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

$udinra_index_sitemap_url = ABSPATH . '/sitemap-index-mobile.xml'; 
$udinra_date = Date(DATE_W3C);
$udinra_sitemap_response = '';
$udinra_sitemap_length = 1000;
$udinra_sitemap_count = 0;

?>