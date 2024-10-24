<?php
// admin/submissions-page.php
if (!defined('ABSPATH')) {
    exit;
}

$fb_db = new Floating_Buttons_DB();
$submissions = $fb_db->get_submissions(20, 1);
?>

<div class="wrap">
    <h1><?php _e('WhatsApp Chat Submissions', 'floating-buttons'); ?></h1>
    
    <div class="fb-submissions-table">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'floating-buttons'); ?></th>
                    <th><?php _e('Email', 'floating-buttons'); ?></th>
                    <th><?php _e('Company', 'floating-buttons'); ?></th>
                    <th><?php _e('Date', 'floating-buttons'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($submissions)) : ?>
                    <?php foreach ($submissions as $submission) : ?>
                        <tr>
                            <td><?php echo esc_html($submission->name); ?></td>
                            <td><?php echo esc_html($submission->email); ?></td>
                            <td><?php echo esc_html($submission->company); ?></td>
                            <td><?php echo wp_date(
                                get_option('date_format') . ' ' . get_option('time_format'),
                                strtotime($submission->date_created)
                            ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4"><?php _e('No submissions found.', 'floating-buttons'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>