<?php  
/**
 * Theme Functions
 * 
 * @package Nandan.Global
 */ 

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if(!defined('NANDAN_DIR_PATH')){
	define('NANDAN_DIR_PATH', untrailingslashit(get_template_directory()));
}

if( !defined('NANDAN_DIR_URI')){
	define('NANDAN_DIR_URI', untrailingslashit(get_template_directory_uri()));
}

function nandanglobal_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on nandanglobal, use a find and replace
		* to change 'nandanglobal' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'nandanglobal', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'nandanglobal' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'nandanglobal_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'nandanglobal_setup' );

function nandanglobal_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nandanglobal_content_width', 640 );
}
add_action( 'after_setup_theme', 'nandanglobal_content_width', 0 );

/**
 * Register widget area.
 */
function nandanglobal_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'nandanglobal' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'nandanglobal' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'nandanglobal_widgets_init' );


function theme_styles_scripts(){
	// enqueue style
	wp_enqueue_style('style-css', get_stylesheet_uri(), [], filemtime(NANDAN_DIR_PATH . '/style.css'),'all');
	wp_enqueue_style('bootstrap-css', NANDAN_DIR_URI . '/assets/src/lib/css/bootstrap.min.css', [], false,'all');

	// enqueue script

	wp_enqueue_script('main-js', NANDAN_DIR_URI . '/assets/main.js', [], filemtime(NANDAN_DIR_PATH . '/assets/main.js'), true);
	wp_enqueue_script('bootstrap-js', NANDAN_DIR_URI . '/assets/src/lib/js/bootstrap.min.js', ['jquery'], false, true);
	wp_enqueue_script( 'navigation', get_template_directory_uri() . '/assets/navigation.js', array(), _S_VERSION, true );
}
add_action('wp_enqueue_scripts', 'theme_styles_scripts');


// add custom class to post navigation
function add_custom_class_to_posts_navigation($attributes) {
    $attributes = 'class="btn btn-outline-warning"';
    return $attributes;
}

add_filter('previous_posts_link_attributes', 'add_custom_class_to_posts_navigation');
add_filter('next_posts_link_attributes', 'add_custom_class_to_posts_navigation');

// Buddypress default profile image functionality

// disable default gravatar
add_filter('bp_core_fetch_avatar_no_grav', '__return_true');

// default member avatar
function change_default_profile_avatar(){
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$roles = (array) $current_user->roles;
	$upload_dir = wp_upload_dir();

	if(is_user_logged_in()){
    	if(in_array('administrator', (array)$roles)){
			return $upload_dir['baseurl'] . '/2023/10/default-user.png';
    	}
    }
}
add_filter('bp_core_default_avatar_user', 'change_default_profile_avatar');
