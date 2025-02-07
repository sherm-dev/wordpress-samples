<?php
/**************************************
**
*	.tsv file generator Bar Graph
**
****************************************/

require_once(rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '/wp-load.php');
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '/wp-content/themes/sage/functions-study.php');

class StudyTsvGenerator{
	private const Y_DATA_HEADER = "percent";
	private const X_DATA_HEADER = "group";
	private const TSV_HEADERS = array(self::X_DATA_HEADER, self::Y_DATA_HEADER);
	
	public function __construct(){}
	
	public function generate_tsv($question_id, $group_id, $sub_group_id, $option_id){
		$chart_results = get_results_for_charts($question_id, $option_id, $group_id, $sub_group_id);
		$tsv = "";
		
		foreach(self::TSV_HEADERS as $header){
			$tsv .= $header . chr(9);
		}
		
		$tsv .= PHP_EOL;
		
		
	

		if(!empty($chart_results['results']) && !empty($chart_results['search_key'])):
				foreach($chart_results['results'] as $result){
					foreach($result as $key => $value){
						if($chart_results['search_key'] == $key)
							$sub_group = $value;

						if('result_data' == $key)
							$data = ltrim(ltrim($value, '<'), '>');
					}

					$tsv .= $sub_group . chr(9);
					$tsv .= $data . chr(9);
					$tsv .= PHP_EOL;
				}
		endif;
		
		return $tsv;
	}
}