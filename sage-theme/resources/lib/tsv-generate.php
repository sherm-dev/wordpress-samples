<?php
/********************************************
**
*		.tsv file creator for bar chart
**
*********************************************/

require_once('StudyTsvGenerator.php');

if(isset($_GET['question_id']) && isset($_GET['group_id'])){
	header('Content-Type: text/tsv');
	$generator = new StudyTsvGenerator();
	echo $generator->generate_tsv($_GET['question_id'], $_GET['group_id'], (isset($_GET['sub_group_id']) ? $_GET['sub_group_id'] : ''), (isset($_GET['option_id']) ? $_GET['option_id'] : ''));
}
?>