<?php

// PPM theme supports
if ( ! function_exists( 'ppm_theme_supports' ) ) :
function ppm_theme_supports(){
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );

    register_nav_menus( array(
        'main-menu'   => __( 'Primary menu', 'ppm-quickstart' ),
    ) );
}
endif;
add_action('after_setup_theme', 'ppm_theme_supports');


// Calling Theme resource files
function ppm_theme_resource_files(){
    /* wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', '5.1.3', 'all' ); */
    /* wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', '4.7.0', 'all' ); */
    wp_enqueue_style( 'ppm-quickstart-theme-style', get_stylesheet_uri() );

    wp_enqueue_script( 'bootstrap-bundle', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array(), '5.1.3', true );
    /* wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), '1.8.1', true ); */
}
add_action('wp_enqueue_scripts', 'ppm_theme_resource_files'); 


// Includes
//include_once('inc/custom-posts.php');
include_once('inc/shortcodes.php');
include_once('inc/elementor/elementor.php');
include_once('inc/metabox-and-options.php');


// WooCommerce Activate
if(class_exists('WooCommerce')) {
   include_once('inc/woocommerce.php');
}


require_once get_theme_file_path() .'/inc/cs-framework/codestar-framework.php';
include_once('functions-csf.php');


// Register Custom-Posts for Puppies
register_post_type( 'puppy', array(
    "labels" => array(
        "name"          => __( "Puppies" ),
        "singular_name" => __( "Puppy" ),
        "add_new_item"  => __( "Add New Puppy" )
    ),
    "public" => true,
    "supports" => array( "title", "editor", "thumbnail", "page-attributes" )
) );


// Register Taxonomy for Puppies
register_taxonomy(
    "puppy_cat",
    "puppy",
    array(
        "hierarchical"      => true,
        "label"             => "Puppy Category",
        "query_var"         => true,
        "show_admin_column" => true,
        "rewrite"           => array(
            "slug"          => "puppy-category",
            "with_front"    => true
        )
    )
);





