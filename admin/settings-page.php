<?php
// admin/settings-page.php

if (!defined('ABSPATH')) {
    exit;
}

// Save settings
if (isset($_POST['fb_save_settings']) && check_admin_referer('fb_settings_nonce')) {
    $whatsapp_number = sanitize_text_field($_POST['fb_whatsapp_number']);
    $notification_email = sanitize_email($_POST['fb_notification_email']);
    $company_name = sanitize_text_field($_POST['fb_company_name']);
    
    update_option('fb_whatsapp_number', $whatsapp_number);
    update_option('fb_notification_email', $notification_email);
    update_option('fb_company_name', $company_name);
    
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'floating-buttons') . '</p></div>';
}

// Get current settings
$whatsapp_number = get_option('fb_whatsapp_number', '');
$notification_email = get_option('fb_notification_email', get_option('admin_email'));
$company_name = get_option('fb_company_name', get_bloginfo('name'));
?>

<div class="wrap">
    <h1><?php _e('Floating Buttons Settings', 'floating-buttons'); ?></h1>
    
    <form method="post" action="" class="fb-settings-page">
        <?php wp_nonce_field('fb_settings_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="fb_company_name"><?php _e('Company Name', 'floating-buttons'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           id="fb_company_name" 
                           name="fb_company_name" 
                           value="<?php echo esc_attr($company_name); ?>" 
                           class="regular-text"
                           placeholder="Your Company Name">
                    <p class="description">
                        <?php _e('This will be used in email notifications', 'floating-buttons'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fb_whatsapp_number"><?php _e('WhatsApp Number', 'floating-buttons'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           id="fb_whatsapp_number" 
                           name="fb_whatsapp_number" 
                           value="<?php echo esc_attr($whatsapp_number); ?>" 
                           class="regular-text"
                           placeholder="e.g., +1234567890">
                    <p class="description">
                        <?php _e('Enter your WhatsApp number with country code (e.g., +1234567890)', 'floating-buttons'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fb_notification_email"><?php _e('Notification Email', 'floating-buttons'); ?></label>
                </th>
                <td>
                    <input type="email" 
                           id="fb_notification_email" 
                           name="fb_notification_email" 
                           value="<?php echo esc_attr($notification_email); ?>" 
                           class="regular-text"
                           placeholder="notifications@yourdomain.com">
                    <p class="description">
                        <?php _e('Email address that will receive notifications when someone starts a WhatsApp chat', 'floating-buttons'); ?>
                    </p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" 
                   name="fb_save_settings" 
                   class="button button-primary" 
                   value="<?php _e('Save Settings', 'floating-buttons'); ?>">
        </p>
    </form>

    <hr class="my-6">

    <h2><?php _e('How to Use', 'floating-buttons'); ?></h2>
    <p><?php _e('Add this shortcode to your footer.php or anywhere you want to display the floating buttons:', 'floating-buttons'); ?></p>
    <code>[floating_buttons]</code>
</div>