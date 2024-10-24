<?php
// includes/class-floating-buttons-db.php
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

    public function insert_submission($data) {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            $data,
            array('%s', '%s', '%s', '%s', '%s')
        );
    }

    public function get_submissions($per_page = 10, $page = 1) {
        global $wpdb;

        $offset = ($page - 1) * $per_page;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $this->table_name ORDER BY date_created DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );
    }
}