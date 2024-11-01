<?php
if ( is_multisite()) {
	$udinra_url_count = 0;
}
else{
$udinra_xml_mobile .= "</urlset>"; 
$udinra_sitemap_count = $udinra_sitemap_count + 1;
$udinra_mobile_sitemap_url = ABSPATH . '/sitemap-mobile-'.$udinra_sitemap_count.'.xml'; 

if (get_option('udinra_mobile_sitemap_key')) {
	if (file_put_contents ($udinra_mobile_sitemap_url, $udinra_xml_mobile)) {
		$udinra_tempurl = get_bloginfo('url').'/sitemap-mobile-'.$udinra_sitemap_count.'.xml'; 
		$udinra_index_xml .="\t"."<sitemap>".PHP_EOL."\t\t"."<loc>".htmlspecialchars($udinra_tempurl)."</loc>".PHP_EOL.
							  "\t\t"."<lastmod>".$udinra_date."</lastmod>".PHP_EOL.	"\t"."</sitemap>".PHP_EOL;
	}	
} 	

$udinra_url_count = 0;
$udinra_xml_mobile = '';
}
?>