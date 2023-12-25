<?php

/**
 * Main menu.
 *
 * @see Appearance -> Menus -> Header Menu.
 */

$type	= $args['type'] ?? 'header_menu';
$wrap	= $args['wrap'] ?? 'nav';
$class	= $args['class'] ?? 'main-menu';
$id		= $args['id'] ?? 'main-menu';

wp_nav_menu( [
	'theme_location'	=> $type,
	'container'			=> $wrap,
	'container_class'	=> $class,
	'container_id'		=> $id
] );

