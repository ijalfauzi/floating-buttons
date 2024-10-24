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

// Initialize the plugin
function floating_buttons_init() {
    $plugin = new Floating_Buttons();
    $plugin->init();
}
add_action('plugins_loaded', 'floating_buttons_init');

// Activation Hook
register_activation_hook(__FILE__, 'floating_buttons_activate');
function floating_buttons_activate() {
    $db = new Floating_Buttons_DB();
    $db->create_tables();
    
    // Set default WhatsApp number if not set
    if (!get_option('fb_whatsapp_number')) {
        update_option('fb_whatsapp_number', '');
    }
}

// Deactivation Hook
register_deactivation_hook(__FILE__, 'floating_buttons_deactivate');
function floating_buttons_deactivate() {
    // Clean up if needed
}