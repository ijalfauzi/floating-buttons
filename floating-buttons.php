<?php
/**
 * Plugin Name: Floating Buttons
 * Description: Adds floating Back to Top and WhatsApp Chat buttons with form submission functionality
 * Version: 1.0.0
 * Author: Ijal Fauzi
 * Text Domain: floating-buttons
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin Constants
define('FB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once FB_PLUGIN_DIR . 'includes/class-floating-buttons.php';
require_once FB_PLUGIN_DIR . 'includes/class-floating-buttons-admin.php';
require_once FB_PLUGIN_DIR . 'includes/class-floating-buttons-db.php';
require_once FB_PLUGIN_DIR . 'includes/class-submissions-list-table.php';

// Initialize the plugin
function floating_buttons_init() {
    $plugin = new Floating_Buttons();
    $plugin->init();
}
add_action('plugins_loaded', 'floating_buttons_init');

// Activation Hook
register_activation_hook(__FILE__, 'floating_buttons_activate');
function floating_buttons_activate() {
    global $wpdb;
    
    $db = new Floating_Buttons_DB();
    $db->create_tables();
    
    // Check if phone column exists and add if it doesn't
    $table_name = $wpdb->prefix . 'fb_whatsapp';
    $column = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = 'phone'",
        DB_NAME,
        $table_name
    ));
    
    if (empty($column)) {
        $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN phone varchar(20) NOT NULL AFTER email");
    }
   
    // Set default options if not set
    if (!get_option('fb_whatsapp_number')) {
        update_option('fb_whatsapp_number', '');
    }
    
    if (!get_option('fb_notification_email')) {
        update_option('fb_notification_email', get_option('admin_email'));
    }
    
    if (!get_option('fb_company_name')) {
        update_option('fb_company_name', get_bloginfo('name'));
    }

    // Clear any cached permalinks
    flush_rewrite_rules();
}

// Deactivation Hook
register_deactivation_hook(__FILE__, 'floating_buttons_deactivate');
function floating_buttons_deactivate() {
    // Clean up if needed
    
    // Clear any cached permalinks
    flush_rewrite_rules();
}

// Add plugin action links
function floating_buttons_action_links($links) {
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=floating-buttons') . '">' . __('Settings', 'floating-buttons') . '</a>'
    );
    return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'floating_buttons_action_links');