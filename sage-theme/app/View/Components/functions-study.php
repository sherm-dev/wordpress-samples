<?php
/****************************************************************************************************
**
**
*			Functions - Study
**
**
********************************************************************************************************/

require_once('resources/lib/StudyDatabaseHelper.php');
set_time_limit(0);
$field_render = 0;



function get_question_text($id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
		$questions_table = $table_prefix . StudyConstants::QUESTIONS_TABLE;
		
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT question_text FROM $questions_table WHERE $questions_table.id = %d",
				$id
			),
			ARRAY_A
		);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('question_text' == strtolower($key))
					return $value;
			}
		}
	}
		
	return "";
}

function get_option_text($option_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$options_table = $table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE;

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT option_text FROM $options_table WHERE $options_table.id = %d",
			$option_id
		),
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('option_text' == strtolower($key))
					return $value;
			}
		}
	}
		
	return "";
}

function get_question_option_ids($question_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
	$ids = array();
	
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT option_id FROM $results_table WHERE $results_table.question_id = %d",
			$question_id
		),
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('option_id' == strtolower($key) && !in_array($value, $ids))
					$ids[] = $value;
			}
		}
	}
		
	return $ids;
}



function get_group_name_by_id($question_id, $group_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;

	if(empty($group_id)){
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT group_name FROM $group_table WHERE $group_table.id = %d",
				get_overall_group_by_question_id($question_id)
			),
			ARRAY_A
		);
	}else{
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT group_name FROM $group_table WHERE $group_table.id = %d",
				$group_id
			),
			ARRAY_A
		);
	}
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('group_name' == strtolower($key))
					return $value;
			}
		}
	}
		
	return "";
}

function get_sub_group_ids_by_group($question_id, $group_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
	$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
	$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
	$ids = array();
	
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT $results_table.sub_group_id FROM $results_table WHERE $results_table.question_id = %d AND $results_table.group_id = %d",
			array($question_id, $group_id)
		),
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				$ids[] = $value;
			}
		}
	}
	
	return $ids;
}

function get_sub_group_name_by_id($sub_group_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT sub_group_name FROM $sub_group_table WHERE $sub_group_table.id = %d",
			$sub_group_id
		),
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('sub_group_name' == strtolower($key))
					return $value;
			}
		}
	}
		
	return "";
}

function get_overall_group_by_question_id($question_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
	$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
	$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
	
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT group_id FROM $results_table JOIN $sub_group_table ON $results_table.sub_group_id = $sub_group_table.id WHERE $results_table.question_id = %d AND $sub_group_table.sub_group_name = %s",
			array($question_id, "NONE")
		),
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('group_id' == $key)
					return $value;
			}
		}
	}
		
	return 0;
}

function get_groups_by_question_id($question_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$ids = array();
	$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
	
	
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT group_id FROM $results_table WHERE $results_table.question_id = %d",
			$question_id
		),
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('group_id' == $key && !in_array($value, $ids))
					$ids[] = $value;
			}
		}
	}
	
	return $ids;
}

function get_question_ids(){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	global $wpdb, $table_prefix;
	$ids = array();
	$questions_table = $table_prefix . StudyConstants::QUESTIONS_TABLE;
	
	$results = $wpdb->get_results(
		"SELECT id FROM $questions_table",
		ARRAY_A
	);
	
	if(!empty($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('id' == $key && !in_array($value, $ids))
					$ids[] = $value;
			}
		}
	}
	
	return $ids;
}

function get_results_for_charts($question_id, $option_id, $group_id, $sub_group_id){
	global $wpdb, $table_prefix;
	$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
	$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
	$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
	$options_table = $table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE;
	
	//echo json_encode(array('question_id' => $question_id, 'option_id'	=>	$option_id, 'group_id' => $group_id, 'sub_group_id' => $sub_group_id));
	
	if('' == $option_id && '' == $sub_group_id && '' != $group_id){
		$search_key = 'option_text';
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT $options_table.option_text, $results_table.result_data FROM $results_table JOIN $options_table ON $results_table.option_id = $options_table.id WHERE $results_table.question_id = %d AND $results_table.group_id = %d",
				array($question_id, $group_id)
			),
			ARRAY_A
		);
	}else if('' == $option_id && '' != $sub_group_id && '' != $group_id){
		$search_key = 'option_text';
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT $options_table.option_text, $results_table.result_data FROM $results_table JOIN $options_table ON $results_table.option_id = $options_table.id WHERE $results_table.question_id = %d AND $results_table.group_id = %d AND $results_table.sub_group_id = %d",
				array($question_id, $group_id, $sub_group_id)
			),
			ARRAY_A
		);
	}else if('' == $sub_group_id && '' != $option_id && '' != $group_id){
		$search_key = 'group_name';
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT $options_table.option_text, $results_table.result_data FROM $results_table JOIN $options_table ON $results_table.option_id = $options_table.id WHERE $results_table.question_id = %d AND $results_table.group_id = %d AND $results_table.option_id = %d",
				array($question_id, $group_id, $option_id)
			),
			ARRAY_A
		);
	}else{
		$search_key = 'group_name';
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT $group_table.group_name, $results_table.result_data FROM $results_table JOIN $group_table ON $results_table.group_id = $group_table.id WHERE $results_table.question_id = %d AND $results_table.group_id = %d AND $results_table.option_id = %d AND $results_table.sub_group_id = %d",
				array($question_id, $group_id, $option_id, $sub_group_id)
			),
			ARRAY_A
		);
	}
	
	return array('results'	=>	$results, 'search_key'	=> $search_key);
}

function query_options_for_sub_group($question_id, $option_id, $group_id, $sub_group_id){
	global $wpdb, $table_prefix;
	$results_table = $table_prefix . StudyConstants::RESULTS_TABLE;
	$group_table = $table_prefix . StudyConstants::RESPONSE_GROUP_TABLE;
	$sub_group_table = $table_prefix . StudyConstants::RESPONSE_SUB_GROUP_TABLE;
	$options_table = $table_prefix . StudyConstants::QUESTIONS_OPTIONS_TABLE;
	
	return $wpdb->get_results(
		$wpdb->prepare(
			"SELECT $options_table.id, $results_table.result_data FROM $options_table JOIN $results_table ON $results_table.option_id = $options_table.id WHERE $results_table.question_id = %d AND $results_table.group_id = %d AND $results_table.sub_group_id = %d",
			array($question_id, $group_id, $sub_group_id)
		),
		ARRAY_A
	);
}

function retrieve_option_count($question_id, $option_id, $group_id, $sub_group_id){
	$results = query_options_for_sub_group($question_id, $option_id, $group_id, $sub_group_id);
	return count($results);
}

function retrieve_option_index($question_id, $option_id, $group_id, $sub_group_id){
	$results = query_options_for_sub_group($question_id, $option_id, $group_id, $sub_group_id);
	$index = 0;
	$counter = 0;
	
	if(!empty($results) && !is_wp_error($results)){
		foreach($results as $result){
			foreach($result as $key => $value){
				if('option_id' == $key && $value == $option_id)
					$index = $counter;
			}
			
			$counter++;
		}
	}
	
	return $index;
}

function clean_chart_data($chart_row){
	$chart_row['value'] = ltrim(ltrim($chart_row['value'], '<'), '>');
	return $chart_row;
}

function retrieve_chart_data($question_id, $option_id, $group_id, $sub_group_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	
	$chart_data = array();
	$chart_results = get_results_for_charts($question_id, $option_id, $group_id, $sub_group_id);
	
	if(!empty($chart_results['results']) && !empty($chart_results['search_key'])):
		foreach($chart_results['results'] as $result){
			$chart_row = array();

			foreach($result as $key => $value){
				if($chart_results['search_key'] == $key)
					$chart_row['label'] = $value;

				if('result_data' == $key)
					$chart_row['value'] = $value; //TODO: Deal with <1
			}

			$chart_row['label'] = $chart_row['label'] . " – " . $chart_row['value'] . "%";
			$chart_data[] = clean_chart_data($chart_row);
		}
	
	endif;

	return $chart_data;
}

function bar_graph_file($question_id, $option_id, $group_id, $sub_group_id){
	$file = "https://" . rtrim($_SERVER['HTTP_HOST'], "/") . "/wp-content/themes/sage/resources/lib/tsv-generate.php?";
	
	if('' != $question_id)
		$file .= "question_id=$question_id";
	
	if('' != $option_id)
		$file .= "&option_id=$option_id";
	
	if('' == $group_id):
		$file .= "&group_id=" . get_overall_group_by_question_id($question_id);
	else:
		$file .= "&group_id=$group_id";
	endif;
	
	if('' != $sub_group_id)
		$file .= "&sub_group_id=$sub_group_id";
	
	return $file;
}

function bar_graph_render($question_id, $option_id, $group_id, $sub_group_id){
	require_once('/code/wp-content/themes/sage/resources/lib/PhpD3/autoloader.php');
	
	 $data = array(
        'data_file'		=>	bar_graph_file($question_id, $option_id, $group_id, $sub_group_id),
		 'dimensions'		=>	array(
				'height'	=>	360,
				'width'		=>	360
		),
        'render_element'=>array(
            'value'=>'graph' . $question_id . $group_id . $sub_group_id,
            'type'=>'id'
        ),
        'axis_data'=>array(
            'x_axis_label'=>'group',
            'y_axis_label'=>'percent',
        ),
        'file_type'=>'tsv',
        'autosize'=>false
    );

    $chart = new PhpD3\Draw('simple_bar_graph', $data);
	echo '<div class="chart-data" data-results='  . "'"  . esc_attr(json_encode(retrieve_chart_data($question_id, $option_id, ('' == $group_id ? get_overall_group_by_question_id($question_id) : $group_id), $sub_group_id), JSON_PRETTY_PRINT)) . "'" . '></div>';
    echo $chart->render();
}

function pie_chart_colors($question_id, $option_id, $group_id, $sub_group_id, $index){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	$count = retrieve_option_count($question_id, $option_id, $group_id, $sub_group_id);
	$colors = array();
	
	for($i = 0; $i < $count; $i++){
		if($i < count(StudyConstants::CHART_COLORS)){
			if($i == $index){
				$colors[] = StudyConstants::FADE_COLOR;
			}else{
				$colors[] = StudyConstants::CHART_COLORS[$i];
			}
		}
	}
}

function pie_chart_render($question_id, $option_id, $group_id, $sub_group_id){
	require_once('/code/wp-content/themes/sage/resources/lib/StudyConstants.php');
	require_once('/code/wp-content/themes/sage/resources/lib/PhpD3/autoloader.php');
	$index = retrieve_option_index($question_id, $option_id, $group_id, $sub_group_id);
	$chart_data = array(
		'render_element'	=>	array(
				'value'		=>	'chart' . $question_id . $group_id . $sub_group_id,
				'type'		=>	'id'
		),
		'chart_data'		=>	retrieve_chart_data($question_id, $option_id, ('' == $group_id ? get_overall_group_by_question_id($question_id) : $group_id), $sub_group_id),	
		'dimensions'		=>	array(
				'height'	=>	220,
				'width'		=>	220,
				'radius'	=>	110
		),
		'colors'			=>	StudyConstants::CHART_COLORS,//pie_chart_colors($question_id, $option_id, $group_id, $sub_group_id, $index),
		'autosize'			=>  false
	); 
		
	//$chart = new PhpD3\Draw('simple_pie_chart', $chart_data);
	//echo '<div class="chart-data" data-results='  . "'"  . esc_attr(json_encode($chart_data['chart_data'], JSON_PRETTY_PRINT)) . "'" . '></div>';
	//echo $chart->render();
}

function get_chart_background($type){
	if('pie' == $type)
		return 'background: linear-gradient(90deg, #ff4802, #ff823f); background-color: linear-gradient(90deg, #ff4802, #ff823f);';
	
	if('bar' == $type)
		return 'background: linear-gradient(90deg, #25245e, #5f338a); background-color: linear-gradient(90deg, #25245e, #5f338a);';
	
	return '';
}

add_action('wp_enqueue_scripts', function(){
	global $post;
	
	wp_enqueue_script( 'wp-util' );
	wp_enqueue_script('jquery', "https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js", array(), '3.5.1', TRUE);
	wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.js', array('jquery'), '4.6.1', TRUE);
	
	
	
	if(get_field('show_study', $post->ID)):
	 	
		wp_enqueue_script('d3', 'https://d3js.org/d3.v7.min.js', array(), '7.0', FALSE);
		wp_enqueue_script('study', "https://" . $_SERVER['HTTP_HOST'] . '/wp-content/themes/sage/public/study.js', array('jquery', 'bootstrap'), '1.0', TRUE);	
	endif;
	
	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', array(), '4.6.1', 'all');
},99);



add_action('acf/init', 'studyjla_acf_init');

function studyjla_acf_init(){
	// Check function exists.
    if( function_exists('acf_add_options_page') ):

        // Register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Theme General Settings'),
            'menu_title'    => __('Theme Settings'),
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
	
		// Register options page.
        $sponsors_page = acf_add_options_page(array(
            'page_title'    => __('Study Sponsors'),
            'menu_title'    => __('Study Sponsors'),
            'menu_slug'     => 'sponsors-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
	
    endif;
	
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_62045de44a717',
			'title' => 'Study Questions',
			'fields' => array(
				array(
					'key' => 'field_62045df1083d3',
					'label' => 'Questions',
					'name' => 'questions',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Question',
					'sub_fields' => array(
						array(
							'key' => 'field_62045e1d083d4',
							'label' => 'Question',
							'name' => 'question',
							'type' => 'select',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'choices' => array(
							),
							'default_value' => array(
							),
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'value',
							'ajax' => 0,
							'placeholder' => '',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

		acf_add_local_field_group(array(
			'key' => 'group_620addbe3a6cf',
			'title' => 'Show Study',
			'fields' => array(
				array(
					'key' => 'field_620addcf37883',
					'label' => 'Show Study?',
					'name' => 'show_study',
					'type' => 'true_false',
					'instructions' => 'Show the study explorer?',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 1,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

	acf_add_local_field_group(array(
		'key' => 'group_61f466f26565c',
		'title' => 'Theme Settings',
		'fields' => array(
			array(
				'key' => 'field_61f466fa4f62d',
				'label' => 'Main Logo',
				'name' => 'main_logo',
				'type' => 'image',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_61f4672e4f62e',
				'label' => 'Favicon',
				'name' => 'favicon',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_620d2cf5d1b06',
				'label' => 'Mobile Logo',
				'name' => 'mobile_logo',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_627c2154dbeed',
				'label' => 'Facebook Page',
				'name' => 'facebook',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
			),
			array(
				'key' => 'field_628d2154dbeed',
				'label' => 'Twitter Page',
				'name' => 'twitter',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'theme-general-settings',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));


acf_add_local_field_group(array(
	'key' => 'group_627003c28b966',
	'title' => 'Study LA Content',
	'fields' => array(
		array(
			'key' => 'field_627003d52ace7',
			'label' => 'Study Content',
			'name' => 'study_content',
			'type' => 'flexible_content',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layouts' => array(
				'layout_627003e948963' => array(
					'key' => 'layout_627003e948963',
					'name' => 'open_content',
					'label' => 'Open Content',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_627004162ace8',
							'label' => 'Content',
							'name' => 'content',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 0,
						),
						array(
							'key' => 'field_627010edf064e',
							'label' => 'Wrapper Class',
							'name' => 'wrapper',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'col-12 col-sm-10 offset-sm-1',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
					),
					'min' => '',
					'max' => '',
				),
				'layout_6270111af064f' => array(
					'key' => 'layout_6270111af064f',
					'name' => 'sections',
					'label' => 'Sections',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_627004702ace9',
							'label' => 'Study Section',
							'name' => 'sections',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'row',
							'button_label' => 'Add Section',
							'sub_fields' => array(
								array(
									'key' => 'field_627004c72acea',
									'label' => 'Anchor',
									'name' => 'anchor',
									'type' => 'text',
									'instructions' => 'Anchor Link (do not include \'#\')',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_627004e22aceb',
									'label' => 'Image',
									'name' => 'image',
									'type' => 'image',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'preview_size' => 'full',
									'library' => 'all',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => '',
								),
								array(
									'key' => 'field_627004fe2acec',
									'label' => 'Title',
									'name' => 'title',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_6270050e2aced',
									'label' => 'Text',
									'name' => 'text',
									'type' => 'textarea',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'maxlength' => '',
									'rows' => '',
									'new_lines' => '',
								),
								array(
									'key' => 'field_323006b02acf5',
									'label' => 'Link',
									'name' => 'link',
									'type' => 'url',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
								),
								array(
									'key' => 'field_32306463e67e5',
									'label' => 'Background',
									'name' => 'background',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'enable_opacity' => 0,
									'return_format' => 'string',
								),
							),
						),
					),
					'min' => '',
					'max' => '',
				),
				'layout_62701166f0651' => array(
					'key' => 'layout_62701166f0651',
					'name' => 'studies',
					'label' => 'Studies',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_6270062f2acf1',
							'label' => 'Studies',
							'name' => 'studies',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'row',
							'button_label' => 'Add Study',
							'sub_fields' => array(
								array(
									'key' => 'field_627006562acf2',
									'label' => 'Image',
									'name' => 'image',
									'type' => 'image',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'preview_size' => 'full',
									'library' => 'all',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => '',
								),
								array(
									'key' => 'field_6270068b2acf3',
									'label' => 'Title',
									'name' => 'title',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_6270069a2acf4',
									'label' => 'Content',
									'name' => 'content',
									'type' => 'textarea',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'maxlength' => '',
									'rows' => '',
									'new_lines' => '',
								),
								array(
									'key' => 'field_627006b02acf5',
									'label' => 'Link',
									'name' => 'link',
									'type' => 'url',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
								),
								array(
									'key' => 'field_627019e94e28a',
									'label' => 'Wrapper',
									'name' => 'wrapper',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => 'col-4',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_62706463e67e5',
									'label' => 'Background',
									'name' => 'background',
									'type' => 'color_picker',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'enable_opacity' => 0,
									'return_format' => 'string',
								),
							),
						),
					),
					'min' => '',
					'max' => '',
				),
				/*'layout_62701152f0650' => array(
					'key' => 'layout_62701152f0650',
					'name' => 'sponsors',
					'label' => 'Sponsors',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_627005472acee',
							'label' => 'Study Sponsors',
							'name' => 'sponsors',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'row',
							'button_label' => 'Add Sponsor',
							'sub_fields' => array(
								array(
									'key' => 'field_627005a92acef',
									'label' => 'Image',
									'name' => 'image',
									'type' => 'image',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'return_format' => 'url',
									'preview_size' => 'full',
									'library' => 'all',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => '',
								),
								array(
									'key' => 'field_627005cf2acf0',
									'label' => 'Link',
									'name' => 'link',
									'type' => 'url',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
								),
							),
						),
					),
					'min' => '',
					'max' => '',
				),*/
			),
			'button_label' => 'Add Content',
			'min' => '',
			'max' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));


acf_add_local_field_group(array(
	'key' => 'group_626c210c93fe6',
	'title' => 'StudyLA Sponsors',
	'fields' => array(
		array(
			'key' => 'field_626c2129cbeeb',
			'label' => 'Sponsors',
			'name' => 'sponsors',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'row',
			'button_label' => 'Add Sponsor',
			'sub_fields' => array(
				array(
					'key' => 'field_626c214bcbeec',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'full',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_626c2168cbeed',
					'label' => 'Link',
					'name' => 'link',
					'type' => 'url',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
				),
				array(
					'key' => 'field_626029e84e28a',
					'label' => 'Wrapper',
					'name' => 'wrapper',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'col-4',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'sponsors-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));

endif;			
}



add_action('acf/render_field/type=select', function($field){
	if(!is_null($field) && !empty($field['value']) && (!isset($_REQUEST['post_type']) || 'acf-field-group' != $_REQUEST['post_type'])){
		?>
		<script type="text/javascript">
			(function($){
				$(function(){
					var name = "<?php echo isset($field['name']) ? $field['name'] : ''; ?>";

					if(name !== '' && wp.ajax !== undefined){
						var params = {
							action: 'retrieve_question_ids'
						};

						$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqXHR){
							if(data !== undefined && data !== [] && data !== {}){
								console.log(JSON.parse(data));
								var res = JSON.parse(data);
								for(var i = 0; i < res.length; i++){
									$('select[name*="' + name + '"]').append(
										$(document.createElement('OPTION')).val(res[i].id).text(res[i].question_text)
									);
								}
							}
						});
					}	
				});
			})(jQuery);
		</script>
		<?php
	}
}, 99, 1);



add_action('wp_ajax_groups_by_question', 'list_groups_by_question');
add_action('wp_ajax_nopriv_groups_by_question', 'list_groups_by_question');

function list_groups_by_question(){
	if(isset($_POST['question_id'])):
		$db_helper = new StudyDatabaseHelper();
		echo json_encode($db_helper->get_groups_by_question($_POST['question_id']));
	endif;
	wp_die();
}

add_action('wp_ajax_results_by_group', 'list_results_by_group');
add_action('wp_ajax_nopriv_results_by_group', 'list_results_by_group');
function list_results_by_group(){
	if(isset($_POST['question_id']) && isset($_POST['group_id'])):
		$db_helper = new StudyDatabaseHelper();
		echo json_encode($db_helper->get_results_by_group($_POST['question_id'], $_POST['group_id'], (isset($_POST['option_id']) ? $_POST['option_id'] : '')));
	endif;
	wp_die();
}

add_action('wp_ajax_retrieve_question_ids', 'retrieve_question_ids');
add_action('wp_ajax_nopriv_retrieve_question_ids', 'retrieve_question_ids');
function retrieve_question_ids(){
	echo json_encode(get_question_ids());
	wp_die();
}

add_action('wp_ajax_load_question_action', 'load_study_question');
add_action('wp_ajax_nopriv_load_question_action', 'load_study_question');
function load_study_question(){
	if(isset($_POST['action']) && isset($_POST['question_id'])):
		$question_item = new \App\View\Components\QuestionItem($_POST['question_id']);
		$result = $question_item->ajaxRender($_POST['question_id']);
		echo htmlspecialchars_decode($result['view']);
	endif;
	
	wp_die();
}

add_action('wp_ajax_load_group_action', 'load_study_group');
add_action('wp_ajax_nopriv_load_group_action', 'load_study_group');
function load_study_group(){
	if(isset($_POST['action']) && isset($_POST['question_id']) && isset($_POST['group_id']) && isset($_POST['option_id'])):
		$group_view = new \App\View\Components\GroupResults($_POST['question_id'], $_POST['group_id'], $_POST['option_id']);
		$result = $group_view->ajaxRender($_POST['question_id'], $_POST['group_id'], $_POST['option_id']);
		echo htmlspecialchars_decode($result['view']);
	endif;
	
	wp_die();
}

add_action('wp_ajax_load_sub_group_action', 'load_study_sub_group');
add_action('wp_ajax_nopriv_load_sub_group_action', 'load_study_sub_group');
function load_study_sub_group(){
	if(isset($_POST['action']) && isset($_POST['question_id']) && isset($_POST['group_id']) && isset($_POST['sub_group_id']) && isset($_POST['option_id'])):
		$sub_group_view = new \App\View\Components\SubGroupResults($_POST['question_id'], $_POST['group_id'], $_POST['sub_group_id']);
		$result = $sub_group_view->ajaxRender($_POST['question_id'], $_POST['group_id'], $_POST['sub_group_id']);
		echo htmlspecialchars_decode($result['view']);
	endif;
	
	wp_die();
}

add_action('wp_ajax_pie_chart_data_action', 'pie_chart_data_request');
add_action('wp_ajax_nopriv_pie_chart_data_action', 'pie_chart_data_request');
function pie_chart_data_request(){
	if(isset($_POST['action']) && isset($_POST['question_id']) && isset($_POST['group_id']) && isset($_POST['sub_group_id']) && isset($_POST['option_id'])):
		$question_id = $_POST['question_id'];
		$option_id = $_POST['option_id'];
		$group_id = $_POST['group_id'];
		$sub_group_id = $_POST['sub_group_id'];
	
		$index = retrieve_option_index($question_id, $option_id, $group_id, $sub_group_id);
		$chart_data = array(
			'render_element'	=>	array(
					'value'		=>	'chart' . $question_id . $group_id . $sub_group_id,
					'type'		=>	'id'
			),
			'chart_data'		=>	retrieve_chart_data($question_id, $option_id, ('' == $group_id ? get_overall_group_by_question_id($question_id) : $group_id), $sub_group_id),	
			'dimensions'		=>	array(
					'height'	=>	440,
					'width'		=>	440,
					'radius'	=>	220
			),
			'colors'			=>	pie_chart_colors($question_id, $option_id, $group_id, $sub_group_id, $index),
			'autosize'			=>  true
		);
		echo json_encode($chart_data);
	endif;
	wp_die();
}

add_action('wp_ajax_bar_chart_data_action', 'bar_chart_data_request');
add_action('wp_ajax_nopriv_bar_chart_data_action', 'bar_chart_data_request');
function bar_chart_data_request(){
	if(isset($_POST['action']) && isset($_POST['question_id']) && isset($_POST['group_id']) && isset($_POST['sub_group_id']) && isset($_POST['option_id'])):
		$index = retrieve_option_index($question_id, $option_id, $group_id, $sub_group_id);
		$chart_data = array(
			'render_element'	=>	array(
					'value'		=>	'chart' . $question_id . $option_id . $group_id . $sub_group_id,
					'type'		=>	'id'
			),
			'chart_data'		=>	retrieve_chart_data($question_id, $option_id, ('' == $group_id ? get_overall_group_by_question_id($question_id) : $group_id), $sub_group_id),	
			'dimensions'		=>	array(
					'height'	=>	360,
					'width'		=>	360
			),
			'colors'			=>	pie_chart_colors($question_id, $option_id, $group_id, $sub_group_id, $index),
			'autosize'			=>  false
		); 
		echo json_encode($chart_data);
	endif;
	wp_die();
}

if ( defined( 'WP_CLI' ) && WP_CLI ) 
    require_once(rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '/wp-content/themes/sage/resources/lib/Study_Data_Import_Command.php');

?>