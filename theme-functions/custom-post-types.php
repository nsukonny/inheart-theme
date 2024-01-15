<?php

$labels = [
	'name'			=> __( 'Сторінка пам`яті', 'inheart' ),
	'singular_name'	=> __( 'Сторінки пам`яті', 'inheart' ),
	'add_new'		=> __( 'Додати сторінку', 'inheart' ),
	'add_new_item'	=> __( 'Додати нову сторінку', 'inheart' ),
	'edit_item'		=> __( 'Редагувати', 'inheart' ),
	'new_item'		=> __( 'Нова сторінка', 'inheart' ),
	'all_item'		=> __( 'Усі сторінкі пам`яті', 'inheart' ),
	'view_item'		=> __( 'Дивитись', 'inheart' ),
	'search_item'	=> __( 'Пошук', 'inheart' ),
	'menu_item'		=> __( 'Сторінки пам`яті', 'inheart' )
];
$args = [
	'labels'				=> $labels,
	'public'				=> true,
	'publicly_queryable'	=> true,
	'show_ui'				=> true,
	'hierarchical'			=> false,
	'menu_icon'				=> 'dashicons-image-filter',
	'menu_position'			=> 6,
	'has_archive'			=> true,
	'show_in_rest'			=> true,
	'supports'				=> ['title', 'author', 'thumbnail'],
	'rewrite'				=> ['slug' => 'memory']
];
register_post_type( 'memory_page', $args );

$labels = [
	'name'          => __( 'Плани підписки', 'inheart' ),
	'singular_name' => __( 'План підписки', 'inheart' ),
	'add_new'       => __( 'Додати план', 'inheart' ),
	'add_new_item'  => __( 'Додати новий план', 'inheart' ),
	'edit_item'     => __( 'Редагувати', 'inheart' ),
	'new_item'      => __( 'Новий план', 'inheart' ),
	'all_item'      => __( 'Усі плани', 'inheart' ),
	'view_item'     => __( 'Дивитись', 'inheart' ),
	'search_item'   => __( 'Пошук', 'inheart' ),
	'menu_item'     => __( 'Плани підписки', 'inheart' ),
];
$args = [
	'labels'             => $labels,
	'public'             => true,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'hierarchical'       => false,
	'menu_icon'          => 'dashicons-money-alt',
	'menu_position'      => 7,
	'has_archive'        => true,
	'show_in_rest'       => true,
	'supports'           => ['title']
];
register_post_type( 'subscription_plan', $args );

$labels = [
	'name'          => __( 'Промокоди', 'inheart' ),
	'singular_name' => __( 'Промокоди', 'inheart' ),
	'add_new'       => __( 'Додати промокод', 'inheart' ),
	'add_new_item'  => __( 'Додати новий промокод', 'inheart' ),
	'edit_item'     => __( 'Редагувати', 'inheart' ),
	'new_item'      => __( 'Новий промокод', 'inheart' ),
	'all_item'      => __( 'Усі промокоди', 'inheart' ),
	'view_item'     => __( 'Дивитись', 'inheart' ),
	'search_item'   => __( 'Пошук', 'inheart' ),
	'menu_item'     => __( 'Промокоди', 'inheart' ),
];
$args = [
	'labels'             => $labels,
	'public'             => true,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'hierarchical'       => false,
	'menu_icon'          => 'dashicons-tickets',
	'menu_position'      => 8,
	'has_archive'        => true,
	'show_in_rest'       => true,
	'supports'           => ['title']
];
register_post_type( 'promocode', $args );

$labels = [
	'name'			=> __( 'Спогади', 'inheart' ),
	'singular_name'	=> __( 'Спогади', 'inheart' ),
	'add_new'		=> __( 'Додати спогад', 'inheart' ),
	'add_new_item'	=> __( 'Додати новий спогад', 'inheart' ),
	'edit_item'		=> __( 'Редагувати', 'inheart' ),
	'new_item'		=> __( 'Новий спогад', 'inheart' ),
	'all_item'		=> __( 'Усі спогади', 'inheart' ),
	'view_item'		=> __( 'Дивитись', 'inheart' ),
	'search_item'	=> __( 'Пошук', 'inheart' ),
	'menu_item'		=> __( 'Спогади', 'inheart' )
];
$args = [
	'labels'			=> $labels,
	'public'			=> true,
	'publicly_queryable'=> false,
	'show_ui'			=> true,
	'hierarchical'		=> false,
	'menu_icon'			=> 'dashicons-format-status',
	'menu_position'		=> 6,
	'has_archive'		=> false,
	'show_in_rest'		=> true,
	'supports'			=> ['title', 'thumbnail'],
	'rewrite'			=> ['slug' => 'memories']
];
register_post_type( 'memory', $args );

$labels = [
	'name'			=> __( 'Товари', 'inheart' ),
	'singular_name'	=> __( 'Товар', 'inheart' ),
	'add_new'		=> __( 'Додати товар', 'inheart' ),
	'add_new_item'	=> __( 'Додати новий товар', 'inheart' ),
	'edit_item'		=> __( 'Редагувати', 'inheart' ),
	'new_item'		=> __( 'Новий товар', 'inheart' ),
	'all_item'		=> __( 'Усі товари', 'inheart' ),
	'view_item'		=> __( 'Дивитись', 'inheart' ),
	'search_item'	=> __( 'Пошук', 'inheart' ),
	'menu_item'		=> __( 'Товари', 'inheart' )
];
$args = [
	'labels'				=> $labels,
	'public'				=> true,
	'publicly_queryable'	=> true,
	'show_ui'				=> true,
	'hierarchical'			=> false,
	'menu_icon'				=> 'dashicons-cart',
	'menu_position'			=> 6,
	'has_archive'			=> false,
	'show_in_rest'			=> true,
	'supports'				=> ['title', 'thumbnail'],
	'rewrite'				=> ['slug' => 'production']
];
register_post_type( 'production', $args );

/*
$labels = array(
	'name'          => __( 'Підписки', 'inheart' ),
	'singular_name' => __( 'Підписки', 'inheart' ),
	'add_new'       => __( 'Додати підписку', 'inheart' ),
	'add_new_item'  => __( 'Додати нову підписку', 'inheart' ),
	'edit_item'     => __( 'Редагувати', 'inheart' ),
	'new_item'      => __( 'Нова підписка', 'inheart' ),
	'all_item'      => __( 'Усі підписки', 'inheart' ),
	'view_item'     => __( 'Дивитись', 'inheart' ),
	'search_item'   => __( 'Пошук', 'inheart' ),
	'menu_item'     => __( 'Підписки', 'inheart' ),
);

$args = array(
	'labels'             => $labels,
	'public'             => true,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'hierarchical'       => false,
	'menu_icon'          => 'dashicons-editor-unlink',
	'menu_position'      => 8,
	'has_archive'        => true,
	'show_in_rest'       => true,
	'supports'           => array( 'title', 'page-attributes', 'custom-fields' ),
);

register_post_type( 'subscription', $args );
*/

