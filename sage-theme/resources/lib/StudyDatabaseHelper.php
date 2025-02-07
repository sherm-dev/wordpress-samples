<?php
/***********************************************************************************
**
**
*				Study Database helper
**
**
***********************************************************************************/

require_once('StudyConstants.php');

class StudyDatabaseHelper{
	private const SUB_GROUP_NONE = "NONE";
	
	public function __construct(){
		
	}
	
	public function get_overall_group_result($question_id){
		global $wpdb, $table_prefix;
		$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
		$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
		$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
		
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $results_table JOIN $group_table, $sub_group_table ON $results_table.group_id = $group_table.id AND $results_table.sub_group_id = $sub_group_table.id WHERE $sub_group_table.sub_group_name = %s AND $results_table.question_id = %d",
				array(self::SUB_GROUP_NONE, $question_id)
			),
			ARRAY_A
		);
	}
	
	public function get_results_by_group($question_id, $group_id, $option_id = ''){
		global $wpdb, $table_prefix;
		$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
		$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
		$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
		
		if('' == $option_id){
			return $wpdb->get_results(
				$wpdb->prepare(
					"SELECT result_data, group_name, sub_group_name FROM $results_table JOIN $group_table, $sub_group_table ON $results_table.group_id = $group_table.id AND $results_table.sub_group_id = $sub_group_table.id WHERE $results_table.group_id = $group_id AND $results_table.question_id = %d",
					$question_id
				),
				ARRAY_A
			);
		}else{
			return $wpdb->get_results(
				$wpdb->prepare(
					"SELECT result_data, group_name, sub_group_name FROM $results_table JOIN $group_table, $sub_group_table ON $results_table.group_id = $group_table.id AND $results_table.sub_group_id = $sub_group_table.id WHERE $results_table.group_id = $group_id AND $results_table.question_id = %d AND $results_table.option_id = %d",
					array($question_id, $option_id)
				),
				ARRAY_A
			);
		}
	}
	
	public function get_groups_by_question($question_id){
		global $wpdb, $table_prefix;
		$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
		$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
		
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT group_id, group_name FROM $results_table JOIN $group_table ON $results_table.group_id = $group_table.id WHERE $results_table.question_id = %d",
				$question_id
			),
			ARRAY_A
		);
	}
	
	public function retrieve_option_by_question_id($question_id){
		global $wpdb, $table_prefix;
		$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
		$options_table = $table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE;
		
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $results_table JOIN $options_table ON $results_table.option_id = $options_table.id WHERE $results_table.question_id = %d",
				$question_id
			),
			ARRAY_A
		);
	}
	
	public function retrieve_question_text_by_id($question_id){
		global $wpdb, $table_prefix;
		$questions_table = $table_prefix . StudyConstants::QUESTIONS_TABLE;
		
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT question_text FROM $questions_table WHERE $questions_table.id = %d",
				$question_id
			),
			ARRAY_A
		);
	}
	
	public function retrieve_question_ids(){
		global $wpdb, $table_prefix;
		$questions_table = $table_prefix . StudyConstants::QUESTIONS_TABLE;
		
		return $wpdb->get_results(
			"SELECT * FROM $questions_table",
			ARRAY_A
		);
	}
}
?>