<?php

// Memory page.
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

// Subscription plan.
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

// Promocode.
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

// Memory.
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

// Production.
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
	'public'				=> false,
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

// Expanded page.
$labels = [
	'name'			=> __( 'Замовлення', 'inheart' ),
	'singular_name'	=> __( 'Замовлення', 'inheart' ),
	'add_new'		=> __( 'Додати замовлення', 'inheart' ),
	'add_new_item'	=> __( 'Додати нове замовлення', 'inheart' ),
	'edit_item'		=> __( 'Редагувати', 'inheart' ),
	'new_item'		=> __( 'Нове замовлення', 'inheart' ),
	'all_item'		=> __( 'Усі замовлення', 'inheart' ),
	'view_item'		=> __( 'Дивитись', 'inheart' ),
	'search_item'	=> __( 'Пошук', 'inheart' ),
	'menu_item'		=> __( 'Замовлення', 'inheart' )
];
$args = [
	'labels'				=> $labels,
	'public'				=> true,
	'publicly_queryable'	=> false,
	'show_ui'				=> true,
	'hierarchical'			=> false,
	'menu_icon'				=> 'dashicons-products',
	'menu_position'			=> 6,
	'has_archive'			=> false,
	'show_in_rest'			=> true,
	'supports'				=> ['title'],
	'rewrite'				=> ['slug' => 'expanded-page']
];
register_post_type( 'expanded-page', $args );

// Rewards.
register_taxonomy( 'rewards', ['reward'], [
	'label'                 => '',
	'labels'                => [
		'name'              => __( 'Види нагород', 'inheart' ),
		'singular_name'     => __( 'Вид нагороди', 'inheart' ),
		'search_items'      => __( 'Пошук видів нагород', 'inheart' ),
		'all_items'         => __( 'Всі види нагород', 'inheart' ),
		'view_item '        => __( 'Дивитись вид нагород', 'inheart' ),
		'parent_item'       => __( 'Батьківський вид нагород', 'inheart' ),
		'parent_item_colon' => __( 'Батьківський вид нагород:', 'inheart' ),
		'edit_item'         => __( 'Редагувати вид нагород', 'inheart' ),
		'update_item'       => __( 'Оновити вид нагород', 'inheart' ),
		'add_new_item'      => __( 'Додати новий вид нагород', 'inheart' ),
		'new_item_name'     => __( 'Назва нового виду нагород', 'inheart' ),
		'menu_name'         => __( 'Види нагород', 'inheart' ),
		'back_to_items'     => __( 'Повернутись до видів нагород', 'inheart' )
	],
	'public'                => true,
	'hierarchical'          => true,
	'rewrite'               => true,
	'capabilities'          => [],
	'show_admin_column'     => true,
	'show_in_rest'          => true
] );
$labels = [
	'name'			=> __( 'Нагороди', 'inheart' ),
	'singular_name'	=> __( 'Нагорода', 'inheart' ),
	'add_new'		=> __( 'Додати нагороду', 'inheart' ),
	'add_new_item'	=> __( 'Додати нову нагороду', 'inheart' ),
	'edit_item'		=> __( 'Редагувати', 'inheart' ),
	'new_item'		=> __( 'Нова нагорода', 'inheart' ),
	'all_item'		=> __( 'Усі нагороди', 'inheart' ),
	'view_item'		=> __( 'Дивитись', 'inheart' ),
	'search_item'	=> __( 'Пошук', 'inheart' ),
	'menu_item'		=> __( 'Нагорода', 'inheart' )
];
$args = [
	'labels'				=> $labels,
	'public'				=> true,
	'publicly_queryable'	=> false,
	'exclude_from_search'	=> true,
	'show_ui'				=> true,
	'hierarchical'			=> false,
	'menu_icon'				=> 'dashicons-awards',
	'menu_position'			=> 6,
	'has_archive'			=> false,
	'show_in_rest'			=> true,
	'supports'				=> ['title', 'thumbnail'],
	'rewrite'				=> ['slug' => 'reward']
];
register_post_type( 'reward', $args );

// Army types.
$labels = [
	'name'			=> __( 'Роди військ', 'inheart' ),
	'singular_name'	=> __( 'Рід військ', 'inheart' ),
	'add_new'		=> __( 'Додати рід військ', 'inheart' ),
	'add_new_item'	=> __( 'Додати новий рід військ', 'inheart' ),
	'edit_item'		=> __( 'Редагувати', 'inheart' ),
	'new_item'		=> __( 'Новий рід військ', 'inheart' ),
	'all_item'		=> __( 'Усі роди військ', 'inheart' ),
	'view_item'		=> __( 'Дивитись', 'inheart' ),
	'search_item'	=> __( 'Пошук', 'inheart' ),
	'menu_item'		=> __( 'Роди військ', 'inheart' )
];
$args = [
	'labels'				=> $labels,
	'public'				=> true,
	'publicly_queryable'	=> false,
	'exclude_from_search'	=> true,
	'show_ui'				=> true,
	'hierarchical'			=> false,
	'menu_icon'				=> 'dashicons-superhero-alt',
	'menu_position'			=> 6,
	'has_archive'			=> false,
	'show_in_rest'			=> true,
	'supports'				=> ['title', 'thumbnail'],
	'rewrite'				=> ['slug' => 'army']
];
register_post_type( 'army', $args );

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

