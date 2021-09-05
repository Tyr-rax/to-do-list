<?php

global $todo_db_version;
$todo_db_version = '1.0';

function todo_install() {
	global $wpdb;
	global $todo_db_version;

	$table_name = $wpdb->prefix . 'to_do_list';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		task text NOT NULL,
		status int(11) NOT NULL DEFAULT 7,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'todo_db_version', $todo_db_version );
}



