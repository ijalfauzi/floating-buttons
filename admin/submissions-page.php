<?php
if (!defined('ABSPATH')) {
    exit;
}

// Handle bulk actions and single deletions
if (isset($_REQUEST['action']) && isset($_REQUEST['submission'])) {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = $_REQUEST['action'];
    $submissions = (array) $_REQUEST['submission'];

    if ($action === 'delete') {
        if (is_array($submissions)) {
            // Bulk delete
            check_admin_referer('bulk-submissions');
            $db = new Floating_Buttons_DB();
            $db->delete_submissions($submissions);
            $message = __('Submissions deleted successfully.', 'floating-buttons');
        } else {
            // Single delete
            check_admin_referer('delete_submission_' . $submissions);
            $db = new Floating_Buttons_DB();
            $db->delete_submission($submissions);
            $message = __('Submission deleted successfully.', 'floating-buttons');
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
    }
}

// Display the list table
require_once FB_PLUGIN_DIR . 'includes/class-submissions-list-table.php';
$submissions_table = new FB_Submissions_List_Table();
$submissions_table->prepare_items();
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('WhatsApp Chat Submissions', 'floating-buttons'); ?></h1>
    
    <form method="post">
        <?php
        $submissions_table->search_box(__('Search Submissions', 'floating-buttons'), 'submission-search');
        $submissions_table->display();
        ?>
    </form>
</div>