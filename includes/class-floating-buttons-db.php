<?php
class Floating_Buttons_DB {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'fb_whatsapp';
    }

    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            company varchar(100) NOT NULL,
            date_created datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function get_submissions_count() {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name");
    }

    public function get_submissions($per_page = 10, $page = 1, $orderby = 'date_created', $order = 'DESC') {
        global $wpdb;

        // Validate order and orderby parameters
        $allowed_orderby = array('name', 'email', 'phone', 'company', 'date_created');
        $orderby = in_array($orderby, $allowed_orderby) ? $orderby : 'date_created';
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $offset = ($page - 1) * $per_page;

        $sql = $wpdb->prepare(
            "SELECT * FROM $this->table_name ORDER BY %i $order LIMIT %d OFFSET %d",
            $orderby,
            $per_page,
            $offset
        );

        return $wpdb->get_results($sql);
    }

    public function insert_submission($data) {
        global $wpdb;
        return $wpdb->insert(
            $this->table_name,
            $data,
            array('%s', '%s', '%s', '%s', '%s')
        );
    }

    public function delete_submission($id) {
        global $wpdb;
        return $wpdb->delete(
            $this->table_name,
            array('id' => $id),
            array('%d')
        );
    }

    public function delete_submissions($ids) {
        global $wpdb;
        $ids = array_map('intval', $ids);
        $ids_string = implode(',', $ids);
        return $wpdb->query("DELETE FROM $this->table_name WHERE id IN ($ids_string)");
    }
}