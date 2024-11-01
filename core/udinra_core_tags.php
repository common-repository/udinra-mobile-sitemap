<?php

$udinra_xml_mobile .= "\t"."<url>".PHP_EOL;
$udinra_xml_mobile .= "\t\t"."<loc>".htmlspecialchars(get_permalink($udinra_post->ID)).$udinra_sitemap_amp."</loc>".PHP_EOL;
$udinra_xml_mobile .= "\t\t"."<lastmod>".get_post_modified_time('c',false,$udinra_post->ID)."</lastmod>".PHP_EOL;
if ( get_post_type($udinra_post->ID) == 'page') {
	$udinra_xml_mobile .= "\t\t"."<priority>"."0.8"."</priority>".PHP_EOL;
}
elseif (get_post_type($udinra_post->ID) == 'post') {
	$udinra_xml_mobile .= "\t\t"."<priority>"."0.6"."</priority>".PHP_EOL;
}
$udinra_xml_mobile .= "\t\t"."<mobile:mobile/>".PHP_EOL;
$udinra_xml_mobile .= "\t"."</url>".PHP_EOL;
$udinra_url_count = $udinra_url_count + 1;
?>
