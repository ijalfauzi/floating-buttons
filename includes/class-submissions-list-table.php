<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class FB_Submissions_List_Table extends WP_List_Table {
    
    private $db;
    private $items_per_page = 10;

    public function __construct() {
        parent::__construct(array(
            'singular' => 'submission',
            'plural'   => 'submissions',
            'ajax'     => false
        ));

        $this->db = new Floating_Buttons_DB();
    }

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $current_page = $this->get_pagenum();
        $total_items = $this->db->get_submissions_count();

        $this->items = $this->db->get_submissions(
            $this->items_per_page, 
            $current_page,
            isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'date_created',
            isset($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC'
        );

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $this->items_per_page,
            'total_pages' => ceil($total_items / $this->items_per_page)
        ));
    }

    public function get_columns() {
        return array(
            'cb'           => '<input type="checkbox" />',
            'name'         => __('Name', 'floating-buttons'),
            'email'        => __('Email', 'floating-buttons'),
            'phone'        => __('Phone', 'floating-buttons'),
            'company'      => __('Company', 'floating-buttons'),
            'date_created' => __('Date', 'floating-buttons')
        );
    }

    public function get_sortable_columns() {
        return array(
            'name'         => array('name', false),
            'email'        => array('email', false),
            'company'      => array('company', false),
            'date_created' => array('date_created', true)
        );
    }

    protected function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="submission[]" value="%s" />', 
            $item->id
        );
    }

    public function column_name($item) {
        // Build actions
        $actions = array(
            'delete' => sprintf(
                '<a href="%s" onclick="return confirm(\'%s\');">%s</a>',
                wp_nonce_url(
                    admin_url(sprintf('admin.php?page=floating-buttons-submissions&action=delete&submission=%s', $item->id)),
                    'delete_submission_' . $item->id
                ),
                __('Are you sure you want to delete this submission?', 'floating-buttons'),
                __('Delete', 'floating-buttons')
            )
        );

        return sprintf(
            '%1$s %2$s',
            esc_html($item->name),
            $this->row_actions($actions)
        );
    }

    public function column_email($item) {
        return '<a href="mailto:' . esc_attr($item->email) . '">' . esc_html($item->email) . '</a>';
    }

    public function column_phone($item) {
        return '<a href="tel:' . esc_attr($item->phone) . '">' . esc_html($item->phone) . '</a>';
    }

    public function column_date_created($item) {
        return wp_date(
            get_option('date_format') . ' ' . get_option('time_format'),
            strtotime($item->date_created)
        );
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'company':
                return esc_html($item->$column_name);
            default:
                return print_r($item, true);
        }
    }

    public function get_bulk_actions() {
        return array(
            'delete' => __('Delete', 'floating-buttons')
        );
    }
}