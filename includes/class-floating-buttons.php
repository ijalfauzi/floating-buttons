<?php
/**
 * Main plugin class
 * 
 * @package Floating_Buttons
 */

if (!defined('ABSPATH')) {
    exit;
}

class Floating_Buttons {
    /**
     * @var Floating_Buttons_Admin
     */
    private $admin;

    /**
     * @var Floating_Buttons_DB
     */
    private $db;

    /**
     * @var string
     */
    private $whatsapp_number;

    /**
     * @var string
     */
    private $notification_email;

    /**
     * @var string
     */
    private $company_name;

    /**
     * Initialize the plugin
     */
    public function init() {
        $this->admin = new Floating_Buttons_Admin();
        $this->db = new Floating_Buttons_DB();
        $this->whatsapp_number = get_option('fb_whatsapp_number', '');
        $this->notification_email = get_option('fb_notification_email', get_option('admin_email'));
        $this->company_name = get_option('fb_company_name', get_bloginfo('name'));

        // Register hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('floating_buttons', array($this, 'render_floating_buttons'));
        add_action('wp_ajax_submit_whatsapp_form', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_submit_whatsapp_form', array($this, 'handle_form_submission'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'floating-buttons-style',
            FB_PLUGIN_URL . 'assets/css/floating-buttons.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'floating-buttons-script',
            FB_PLUGIN_URL . 'assets/js/floating-buttons.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('floating-buttons-script', 'fbAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fb-nonce')
        ));
    }

    /**
     * Render the floating buttons
     * 
     * @return string
     */
    public function render_floating_buttons() {
        ob_start();
        include FB_PLUGIN_DIR . 'templates/floating-buttons.php';
        return ob_get_clean();
    }

    /**
     * Handle form submission via AJAX
     */
    public function handle_form_submission() {
        try {
            // Verify nonce
            check_ajax_referer('fb-nonce', 'nonce');

            // Verify required fields
            $required_fields = array('name', 'email', 'phone', 'company');
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Missing required field: {$field}");
                }
            }

            // Sanitize input data
            $data = array(
                'name' => sanitize_text_field($_POST['name']),
                'email' => sanitize_email($_POST['email']),
                'phone' => sanitize_text_field($_POST['phone']),
                'company' => sanitize_text_field($_POST['company']),
                'date_created' => current_time('mysql')
            );

            // Insert into database
            $result = $this->db->insert_submission($data);

            if ($result === false) {
                throw new Exception("Database insertion failed");
            }

            // Send email notification
            $this->send_admin_notification($data);

            // Prepare WhatsApp message (excluding phone number)
            $whatsapp_message = sprintf(
                "Name: %s\nEmail: %s\nCompany: %s",
                $data['name'],
                $data['email'],
                $data['company']
            );
            
            $whatsapp_url = sprintf(
                'https://wa.me/%s?text=%s',
                preg_replace('/[^0-9]/', '', $this->whatsapp_number),
                urlencode($whatsapp_message)
            );

            wp_send_json_success(array(
                'message' => __('Form submitted successfully', 'floating-buttons'),
                'whatsapp_url' => $whatsapp_url
            ));

        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }

        wp_die();
    }

    /**
     * Send email notification to admin
     * 
     * @param array $data Form submission data
     */
    private function send_admin_notification($data) {
        $subject = $this->company_name;
        $message = $this->get_notification_template($data);
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->company_name . ' <' . get_option('admin_email') . '>'
        );

        wp_mail($this->notification_email, $subject, $message, $headers);
    }

    /**
     * Get HTML template for email notification
     * 
     * @param array $data Form submission data
     * @return string HTML template
     */
    private function get_notification_template($data) {
        $template = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . esc_html($this->company_name) . '</title>
        </head>
        <body style="margin: 0; padding: 20px; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif;">
            <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h1 style="color: #1e40af; margin: 0 0 20px 0; font-size: 24px;">
                    ' . esc_html($this->company_name) . '
                </h1>

                <p style="color: #374151; font-size: 16px; margin: 0 0 20px 0;">Hello,</p>
                <p style="color: #374151; font-size: 16px; margin: 0 0 20px 0;">Someone has just contacted through WhatsApp Chat. Here are their details:</p>

                <div style="background-color: #f9fafb; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                    <p style="margin: 0 0 10px 0; color: #374151;">
                        <strong style="display: inline-block; width: 100px;">Name:</strong> 
                        ' . esc_html($data['name']) . '
                    </p>
                    <p style="margin: 0 0 10px 0; color: #374151;">
                        <strong style="display: inline-block; width: 100px;">Email:</strong> 
                        <a href="mailto:' . esc_attr($data['email']) . '" style="color: #2563eb; text-decoration: none;">
                            ' . esc_html($data['email']) . '
                        </a>
                    </p>
                    <p style="margin: 0 0 10px 0; color: #374151;">
                        <strong style="display: inline-block; width: 100px;">Phone:</strong> 
                        <a href="tel:' . esc_attr($data['phone']) . '" style="color: #2563eb; text-decoration: none;">
                            ' . esc_html($data['phone']) . '
                        </a>
                    </p>
                    <p style="margin: 0 0 10px 0; color: #374151;">
                        <strong style="display: inline-block; width: 100px;">Company:</strong> 
                        ' . esc_html($data['company']) . '
                    </p>
                </div>

                <p style="color: #6b7280; font-size: 14px; margin: 20px 0 0 0; text-align: center;">
                    Time: ' . wp_date('Y-m-d H:i:s', strtotime($data['date_created'])) . '
                </p>

                <div style="border-top: 1px solid #e5e7eb; margin-top: 20px; padding-top: 20px; text-align: center;">
                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                        This is an automated message from ' . esc_html($this->company_name) . '
                    </p>
                </div>
            </div>
        </body>
        </html>';

        return $template;
    }
}