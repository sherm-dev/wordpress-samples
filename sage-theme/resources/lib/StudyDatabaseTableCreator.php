<?php
/***********************************************************************************
**
**
*				Study of Jewish LA Table Creator
**
**
***********************************************************************************/
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once('StudyConstants.php');

class StudyDatabaseTableCreator{
	public function __construct(){
	}
	
	public function create_initial_tables(){
		$this->response_group_table_sql();
		$this->response_sub_group_table_sql();
		$this->questions_table_sql();
		$this->questions_options_table_sql();
		$this->results_table_sql();
	}
	
	public function insert_table_row($table_name, $data, $format = ''){
		global $wpdb;
		return $wpdb->insert($table_name, $data, $format);
	}
	
	private function response_group_table_sql(){
		global $wpdb, $table_prefix;
		$table_name = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
		$sql =  "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			group_name varchar(55) NOT NULL UNIQUE,
			PRIMARY KEY  (id)
		) {$wpdb->get_charset_collate()};";
		dbDelta( $sql );
	}
	
	private function response_sub_group_table_sql(){
		global $wpdb, $table_prefix;
		$table_name = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			sub_group_name varchar(55) NOT NULL UNIQUE,
			PRIMARY KEY  (id)
		) {$wpdb->get_charset_collate()};";
		dbDelta( $sql );
	}
	
	private function questions_table_sql(){
		global $wpdb, $table_prefix;
		$table_name = $table_prefix . StudyConstants::QUESTIONS_TABLE;
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			question_text varchar(255) NOT NULL UNIQUE,
			PRIMARY KEY  (id)
		) {$wpdb->get_charset_collate()};";
		dbDelta( $sql );
	}
	
	private function questions_options_table_sql(){
		global $wpdb, $table_prefix;
		$table_name = $table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE;
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			option_text varchar(55) NOT NULL UNIQUE,
			PRIMARY KEY  (id)
		) {$wpdb->get_charset_collate()};";
		dbDelta( $sql );
	}
	
	private function results_table_sql(){
		global $wpdb, $table_prefix;
		$table_name = $table_prefix . StudyConstants::RESULTS_TABLE;
		//dbDelta doesn't support Foreign Keys
		/*$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			result_data varchar(55) NOT NULL,
			question_id mediumint(9) NOT NULL,
			option_id mediumint(9) NOT NULL,
			group_id mediumint(9) NOT NULL,
			sub_group_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (question_id)
				REFERENCES " . ($table_prefix . StudyConstants::QUESTIONS_TABLE) . " (id),
			FOREIGN KEY (option_id)
				REFERENCES " . ($table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE) . " (id),	
			FOREIGN KEY (group_id)
				REFERENCES " . ($table_prefix . StudyConstants::RESPONSE_GROUP_TABLE) . " (id),
			FOREIGN KEY (sub_group_id)
				REFERENCES " . ($table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE) . " (id)
		) {$wpdb->get_charset_collate()};";*/
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			result_data varchar(55) NOT NULL,
			question_id mediumint(9) NOT NULL,
			option_id mediumint(9) NOT NULL,
			group_id mediumint(9) NOT NULL,
			sub_group_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id)
		) {$wpdb->get_charset_collate()};";
		
		dbDelta($sql);
	}
}
?>