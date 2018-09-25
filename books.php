<?php
/**
 * @package  books
 */
/*
Plugin Name:Books
Plugin URI:https://ness.com
Description:Plugin for maintain books information.
Version: 1.0
Author: Pankaj Bansal
Author URI:https://ness.com
License: GPLv2 or later
Text Domain: books
*/

/**
 * Define plugin base url and path
 */
define('books_plugin_URL', plugin_dir_url(__FILE__));
define('books_plugin_Path', plugin_dir_path(__FILE__));


require_once(books_plugin_Path . 'class.books.php');

add_action('init', array(
    'books',
    'init'
));
add_action('admin_init', array(
    'books',
    'add_books_meta_boxes'
));
add_action('save_post', array(
    'books',
    'save_books_custom_fields'
));
add_filter('single_template', array(
    'books',
    'get_custom_post_type_template'
));

/**
 * Plugin activation and deactivation hooks
 */
register_activation_hook(__FILE__, array(
    'books',
    'plugin_activation'
));
register_deactivation_hook(__FILE__, array(
    'books',
    'plugin_deactivation'
));
?>