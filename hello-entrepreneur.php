<?php
/*
Plugin Name: Hello Entrepreneur
Plugin URI: https://www.brandkover.com/
Description: A simple plugin that displays random entrepreneurial quotes in the WordPress admin dashboard.
Author: Brandkover
Version: 1.0.0
Author URI: https://www.brandkover.com/wordpress
Text Domain: hello-entrepreneur
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load text domain for translations (if supporting older WordPress versions)
function hello_entrepreneur_load_textdomain() {
    load_plugin_textdomain('hello-entrepreneur', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'hello_entrepreneur_load_textdomain');

// Enqueue styles for the admin dashboard
function hello_entrepreneur_enqueue_styles() {
    wp_register_style(
        'hello-entrepreneur-style', 
        plugins_url('css/hello-entrepreneur.css', __FILE__), 
        [], 
        '1.0' // Version number
    );
    wp_enqueue_style('hello-entrepreneur-style');
}
add_action('admin_enqueue_scripts', 'hello_entrepreneur_enqueue_styles');

// Array of entrepreneurial quotes
function hello_entrepreneur_get_quotes() {
    $quotes = [
        __("The best way to predict the future is to create it. - Peter Drucker", "hello-entrepreneur"),
        __("Success usually comes to those who are too busy to be looking for it. - Henry David Thoreau", "hello-entrepreneur"),
        __("Don't be afraid to give up the good to go for the great. - John D. Rockefeller", "hello-entrepreneur"),
        __("I find that the harder I work, the more luck I seem to have. - Thomas Jefferson", "hello-entrepreneur"),
        __("Opportunities don't happen. You create them. - Chris Grosser", "hello-entrepreneur"),
        // Add more quotes here
    ];
    return $quotes[array_rand($quotes)];
}

// Function to display the quote
function hello_entrepreneur_display_quote() {
    $quote = hello_entrepreneur_get_quotes();
    echo "<p id='hello-entrepreneur-quote'>" . esc_html($quote) . "</p>";
}

// Hook the function to the admin_notices action
add_action('admin_notices', 'hello_entrepreneur_display_quote');
