<?php
/*
Plugin Name: Customer Alliance Reviews
Plugin URI:  http://www.customer-alliance.com/
Description: Display customer alliance reviews on your page
Version:     0.2.0
Author:      Thomas Horster
Author URI:  http://thomashorster.de
License:     Apache 2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0
Domain Path: /languages
Text Domain: ca-reviews

copyright 2015 Thomas Horster
all rights reserved

*/
defined('ABSPATH') or die('No script kiddies please!');

//add styles and so on
require_once(plugin_dir_path(__FILE__) . 'reviews/review-functions.php');

class CASettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('init', array($this, 'load_translation'));
    }

    public function load_translation()
    {
        load_plugin_textdomain('ca-reviews', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'CA-Reviews',
            'CA-Reviews',
            'manage_options',
            'ca-settings-admin',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('ca_reviews');
        ?>
        <div class="wrap">
            <h2>CA-Reviews</h2>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('ca_option_group');
                do_settings_sections('ca-settings-admin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'ca_option_group', // Option group
            'ca_reviews', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'CA-Reviews', // Title
            array($this, 'print_section_info'), // Callback
            'ca-settings-admin' // Page
        );

        add_settings_field(
            'id_key', // ID
            'Hotel ID', // Title
            array($this, 'id_key_callback'), // Callback
            'ca-settings-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'access_key',
            'Access Key',
            array($this, 'access_key_callback'),
            'ca-settings-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @return object
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['id_key']))
            $new_input['id_key'] = sanitize_text_field($input['id_key']);

        if (isset($input['access_key']))
            $new_input['access_key'] = sanitize_text_field($input['access_key']);

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Geben Sie bitte die zugesendeten Daten an:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_key_callback()
    {
        printf(
            '<input type="text" id="id_key" name="ca_reviews[id_key]" value="%s" />',
            isset($this->options['id_key']) ? esc_attr($this->options['id_key']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function access_key_callback()
    {
        printf(
            '<input type="text" id="access_key" name="ca_reviews[access_key]" value="%s" />',
            isset($this->options['access_key']) ? esc_attr($this->options['access_key']) : ''
        );
    }
}

if (is_admin()) {
    $my_settings_page = new CASettingsPage();
}
