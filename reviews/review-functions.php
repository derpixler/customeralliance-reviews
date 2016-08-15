<?php
/**
 * Functions to hook reviews into wordpress.
 *
 * @package WordPress
 * @subpackage Customer Alliance Reviews
 * @since Customer Alliance Reviews 0.1.0
 */

/**
 * Proper way to enqueue scripts and styles
 */
function addScriptAndStyles()
{
    wp_enqueue_style('review-styles', plugin_dir_url( __FILE__ ) . 'css/reviews.css');
//    wp_enqueue_script( 'review-scripts', plugin_dir_url( __FILE__ ) . '/js/example.js', array(), '1.0.0', true );
}

add_action('wp_enqueue_scripts', 'addScriptAndStyles');

/**
 * add query var for reviews
 */
function add_query_vars($aVars)
{
    $aVars[] = "review-page";
    return $aVars;
}

// hook add_query_vars function into query_vars
add_filter('query_vars', 'add_query_vars');

/**
 * add shortcode for review insertion into content
 */
function add_review_shortcode(){
    //turn on output buffering to capture script output
    ob_start();
    //include the specified file
    include(WP_PLUGIN_DIR . '/customeralliance-reviews/reviews/customer.php');
    //assign the file output to $content variable and clean buffer
    $content = ob_get_clean();
    //return is important for the output to appear at the correct position in the content
    return $content;
}
add_shortcode( 'ca-reviews', 'add_review_shortcode' );
