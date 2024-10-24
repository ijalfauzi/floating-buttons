<?php
// includes/class-floating-buttons.php
class Floating_Buttons {
    private $admin;
    private $db;
    private $whatsapp_number;
    private $notification_email;
    private $company_name;

    public function init() {
        $this->admin = new Floating_Buttons_Admin();
        $this->db = new Floating_Buttons_DB();
        $this->whatsapp_number = get_option('fb_whatsapp_number', '');
        $this->notification_email = get_option('fb_notification_email', get_option('admin_email'));
        $this->company_name = get_option('fb_company_name', get_bloginfo('name'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('floating_buttons', array($this, 'render_floating_buttons'));
        add_action('wp_ajax_submit_whatsapp_form', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_submit_whatsapp_form', array($this, 'handle_form_submission'));
    }

    public function handle_form_submission() {
        check_ajax_referer('fb-nonce', 'nonce');

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $company = sanitize_text_field($_POST['company']);

        $data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'date_created' => current_time('mysql')
        );

        $result = $this->db->insert_submission($data);

        if ($result) {
            // Send email notification
            $this->send_admin_notification($data);

            // Prepare WhatsApp message (excluding phone number)
            $whatsapp_message = sprintf(
                "Name: %s\nEmail: %s\nCompany: %s",
                $name,
                $email,
                $company
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
        } else {
            wp_send_json_error(array(
                'message' => __('Error submitting form', 'floating-buttons')
            ));
        }
    }

    private function send_admin_notification($data) {
        $subject = $this->company_name;
        $message = $this->get_notification_template($data);
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->company_name . ' <' . get_option('admin_email') . '>'
        );

        wp_mail($this->notification_email, $subject, $message, $headers);
    }

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
                        ' . esc_html($data['phone']) . '
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