<?php
	/*******************************************************
	**
	**
	*	Data importer used by WP Command
	**
	**
	*********************************************************/

require_once('StudyConstants.php');
require_once('StudyDatabaseTableCreator.php');

class StudyDataImporter{
	private $file_path;
	private $row_count;
	
	public function __construct($file_path){
		$this->file_path = $file_path;
		$this->row_count = array();
	}
	
	public function import_on_command(){
		echo "Rows Inserted: " . json_encode($this->import_data($this->import_csv())); //echo for importer command
	}
	
	private function insert_result_rows($result_rows){
		global $wpdb, $table_prefix;
		$table_creator = new StudyDatabaseTableCreator();
		$inserted = 0;
		$format = array();
		$row_insert = array();
		
		if(!empty($result_rows)){
			foreach($result_rows as $row){
				
				foreach($row as $row_val){
					
					foreach($row_val as $val_key => $val){
						
						if(is_array($val) && 'format' != $val_key){
							$results = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT id FROM " . $val['table_name'] .  " WHERE " . $val['column_name'] . " = %s",
									$val['value']
								),
								ARRAY_A
							);

							
							if(!empty($results)){
								foreach($results as $result){
									if(isset($result['id']))
										$row_insert[$val_key] = $result['id'];
								}
							}
						}else{
							//echo "KEY: $key VALKEY: $val_key Value: $val";
							$row_insert[$val_key] = $val;
						}
					}
					
				}
				
				unset($row_insert['format']);
				$table_creator->insert_table_row($table_prefix . StudyConstants::RESULTS_TABLE, $row_insert, $row_val['format']);
			}
		}
	}
	
	private function get_result_data($insert_id, $column_name, $data, $table_name){
		global $wpdb;
		
		if(0 == $insert_id)
			return array('id' => $insert_id, 'column_name'	=> $column_name, 'value' => $data, 'table_name'	=>	$table_name);
			
		return $insert_id;
	}
	
	private function import_data($csv_array){
		global $table_prefix, $wpdb;
		$table_creator = new StudyDatabaseTableCreator();
		$result_rows = array();
		$inserted_count = 0;
		
		
		if(!empty($csv_array) && isset($csv_array['rows'])){
			foreach($csv_array['rows'] as $row){
				$result_insert = array();
				$format = array();
				foreach($row as $key => $row_val){
					//echo json_encode($row_val);
					switch($key){
						case StudyConstants::QUESTION_LABEL:
							$table_creator->insert_table_row($table_prefix . StudyConstants::QUESTIONS_TABLE, array('question_text' => $row_val), '%s');
							//$result_insert['question_id'] = $this->insert_row_with_check($table_prefix . StudyConstants::QUESTIONS_TABLE, 'question_text', $row_val);
							$result_insert['question_id'] = $this->get_result_data($wpdb->insert_id, 'question_text', $row_val, $table_prefix . StudyConstants::QUESTIONS_TABLE);
							$format[] = '%d';
							break;
						case StudyConstants::QUESTION_CHOICE:
							$table_creator->insert_table_row($table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE, array('option_text' => $row_val), '%s');
							$result_insert['option_id'] = $this->get_result_data($wpdb->insert_id, 'option_text', $row_val, $table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE);
							//$result_insert['option_id'] = $this->insert_row_with_check($table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE, 'option_text', $row_val);
							$format[] = '%d';
							break;
						case StudyConstants::RESULT_DATA:
							$result_insert['result_data'] = $row_val;
							$format[] = '%s';
							break;
						case StudyConstants::PARENT_GROUP:
							$table_creator->insert_table_row($table_prefix . StudyConstants::RESPONSE_GROUP_TABLE, array('group_name' => $row_val), '%s');
							$result_insert['group_id'] = $this->get_result_data($wpdb->insert_id, 'group_name', $row_val, $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE);
							//$result_insert['group_id'] = $this->insert_row_with_check($table_prefix . StudyConstants::RESPONSE_GROUP_TABLE, 'group_name', $row_val);
							$format[] = '%d';
							break;
						case StudyConstants::SUB_GROUP:
							$table_creator->insert_table_row($table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE, array('sub_group_name' => $row_val), '%s');
							$result_insert['sub_group_id'] = $this->get_result_data($wpdb->insert_id, 'sub_group_name', $row_val, $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE);//$this->insert_row_with_check($table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE, 'sub_group_name', $row_val);
							$format[] = '%d';
							break;
						default:
							break;
					}
				}
				
				$result_insert['format'] = $format;
				$result_rows[] = array($result_insert);
				
				
			
			}
			
			$inserted_count = $this->insert_result_rows($result_rows);
		}
		
		return $inserted_count;
	}
	

	
	private function import_csv(){
		$row_counter = 0;
		$csv_formatted = array('header' => array(), 'rows' => array());
		$header_field_count = 0;
		
		if(($csv = fopen($this->file_path, "r")) !== FALSE){
			while (!feof($csv) && ($data = fgetcsv($csv)) !== FALSE) {
				if(0 == $row_counter){
					for($x = 0; $x < count($data); $x++){
						
							$csv_formatted['header'][] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[$x]);
						
					}
				}else{
					if(count($csv_formatted['header']) == count($data)){

						for($x = 0; $x < count($data); $x++){
							$row[trim($csv_formatted['header'][$x])] = trim($data[$x]);
						}

						$csv_formatted['rows'][] = $row;
					}
				}

				$row_counter++;
			  }

			fclose($csv);
		}
		
		return $csv_formatted;
	}
}
?>