<?php

/**
 *
 */

if( ! $url = $args['url'] ?? null ) return;

if( ! $svg_file = file_get_contents( $url ) ) return;

$start_string	= '<svg';
$start_position	= strpos( $svg_file, $start_string );
$svg_structure	= substr( $svg_file, $start_position );

echo $svg_structure;

