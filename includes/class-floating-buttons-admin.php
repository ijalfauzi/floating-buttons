<?php
// includes/class-floating-buttons-admin.php
class Floating_Buttons_Admin {
    private $db;

    public function __construct() {
        $this->db = new Floating_Buttons_DB();
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Floating Buttons', 'floating-buttons'),
            __('Floating Buttons', 'floating-buttons'),
            'manage_options',
            'floating-buttons',
            array($this, 'render_settings_page'),
            'dashicons-share',
            30
        );

        add_submenu_page(
            'floating-buttons',
            __('WhatsApp Submissions', 'floating-buttons'),
            __('WhatsApp Submissions', 'floating-buttons'),
            'manage_options',
            'floating-buttons-submissions',
            array($this, 'render_submissions_page')
        );
    }

    public function render_settings_page() {
        include FB_PLUGIN_DIR . 'admin/settings-page.php';
    }

    public function render_submissions_page() {
        include FB_PLUGIN_DIR . 'admin/submissions-page.php';
    }

    public function enqueue_admin_scripts($hook) {
        if (!strpos($hook, 'floating-buttons')) {
            return;
        }

        wp_enqueue_style(
            'floating-buttons-admin-style',
            FB_PLUGIN_URL . 'assets/css/admin-style.css',
            array(),
            '1.0.0'
        );
    }
}