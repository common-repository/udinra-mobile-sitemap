<?php

status_header( '200' ); 
header( 'Content-Type: text/xml; charset=' . get_bloginfo( 'charset' ), true );

echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">' . PHP_EOL;

include 'init/udinra_init_mobile.php';
include 'core/udinra_core.php';
include 'exit/udinra_ping_mobile.php';
echo $udinra_xml_mobile;

?>