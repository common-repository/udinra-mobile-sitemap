<?php

if ( is_multisite()) {
	$udinra_sitemap_response = '<a href='.get_bloginfo('url'). '/sitemap-index-mobile.xml'.' target="_blank" title="Mobile Sitemap URL">View Mobile Sitemap</a> <br />Submit this sitemap to Google Search Console (Google Webmasters) and others Bing Webmasters.';
	$udinra_xml_mobile .= "</urlset>"; 
}
else {
$udinra_index_xml .= "</sitemapindex>";	
if (UdinraMobileWritable(ABSPATH) || UdinraMobileWritable($udinra_mobile_sitemap_url)) {
	file_put_contents ($udinra_index_sitemap_url, $udinra_index_xml);
	$udinra_sitemap_response = '<a href='.get_bloginfo('url'). '/sitemap-index-mobile.xml'.' target="_blank" title="Mobile Sitemap URL">View Mobile Sitemap</a> <br />Submit this sitemap to Google Search Console (Google Webmasters) and others Bing Webmasters.';
}
else {
	$udinra_sitemap_response = "The file sitemap-index-mobile.xml is not writable please check permission of the file for more details visit https://udinra.com/docs/category/mobile-sitemap-pro";
}
if (is_wp_error(wp_remote_get( "http://www.google.com/webmasters/tools/ping?sitemap=" . urlencode($udinra_index_sitemap_url) ))) {
}
if (is_wp_error(wp_remote_get( "http://www.bing.com/webmaster/ping.aspx?sitemap=" . urlencode($udinra_index_sitemap_url) ))) {
}

return $udinra_sitemap_response;
}
?>