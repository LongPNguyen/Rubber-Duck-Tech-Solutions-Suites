<?php
global $rdts_addon_settings;

/**
 * Plugin Name: Rubber Duck Tech Solutions
 * Plugin URI:  https://rubberducktech.com/products/rubber-duck-tech-solutions
 * Description: A suite of plugins for managing your business.
 * Version:     1.0
 * Author:      Long Nguyen
 * Author URI:  https://rubberducktech.com
 * Text Domain: rubber-duck-inventory-solutions
 */

function rdts_enqueue_styles()
{
    wp_enqueue_style('rdts-styles', plugin_dir_url(__FILE__) . 'assets/css/main-page.css');
}

add_action('admin_enqueue_scripts', 'rdts_enqueue_styles');
add_action('admin_menu', 'rdts_add_main_menu');


function rdts_add_main_menu()
{
    $user = wp_get_current_user();

    add_menu_page(
        'Rubber Duck Tech Solutions', // Page title
        'Rubber Duck Tech Solutions',         // Menu title
        'manage_options',            // Capability
        'rdts_main_menu',            // Menu slug
        'rdts_main_page',            // Function to display the page
        'dashicons-products',        // Icon URL (optional)
        6                            // Position (optional)
    );

    // Optionally add the Inventory submenu here if you want to check for the plugin's existence
    // This is useful if you want to manage the submenu from the parent plugin
    if (is_plugin_active('Rubber-Duck-Inventory-Solutions/rdis.php') && !in_array('employee_hourly', $user->roles)) {
        add_submenu_page(
            'rdts_main_menu',           // Parent slug
            'Inventory Management',     // Page title
            'Inventory',                // Menu title
            'manage_options',           // Capability
            'edit.php?post_type=rd_inventory' // Menu slug - Points to the inventory CPT
        );
        add_submenu_page(
            'rdts_main_menu', // The slug of your main plugin menu
            'Add/Sell', // Page title
            'Add/Sell', // Menu title
            'manage_options', // Capability
            'rdis-item-scanner', // Menu slug
            'rdis_load_scanner_template' // Function to display the OCR page content
        );
    }

    if (is_plugin_active('rubber-duck-team-management-solutions/rdtms.php')) {
        add_submenu_page(
            'rdts_main_menu', // The slug of your main plugin menu
            'Team Portal', // Page title
            'Team Portal', // Menu title
            'manage_options', // Capability
            'rdtms-team-portal', // Menu slug
            'rdtms_load_team_portal_template' // Function to display the team portal
        );
    }

    //settings page
    // add_submenu_page(
    //     'rdts_main_menu',                  // Parent slug
    //     'RDTS Settings',                   // Page title
    //     'Settings',                        // Menu title
    //     'manage_options',                  // Capability
    //     'rdts_settings',                   // Menu slug
    //     'rdts_settings_page_callback'      // Function to display the settings page
    // );
}

function rdts_main_page()
{
    // Code to display the main page content goes here.
    include(plugin_dir_path(__FILE__) . 'templates/main-page.php');
}

function rdis_load_scanner_template()
{
    include_once WP_PLUGIN_DIR . '/Rubber-Duck-Inventory-Solutions/templates/rdis-item-scanner-template.php';
}

function rdtms_load_team_portal_template()
{
    include_once WP_PLUGIN_DIR . '/rubber-duck-team-management-solutions/templates/rdtms-team-portal-template.php';
}



function rdts_settings_page_callback()
{
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Include the settings page partial
    include_once plugin_dir_path(__FILE__) . '/templates/settings-page.php';
}

function rdts_register_addon_settings($addon_slug, $settings_callback)
{
    global $rdts_addon_settings;

    if (!isset($rdts_addon_settings)) {
        $rdts_addon_settings = array();
    }
    $rdts_addon_settings[$addon_slug] = $settings_callback;
}

function rdts_display_addon_settings()
{
    global $rdts_addon_settings;

    if (!empty($rdts_addon_settings)) {
        foreach ($rdts_addon_settings as $addon => $callback) {
            if (is_callable($callback)) {
                call_user_func($callback);
            }
        }
    }
}

