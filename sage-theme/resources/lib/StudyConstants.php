<?php
/***********************************************************************************
**
**
*				Study of Jewish LA Constants
**
**
***********************************************************************************/
class StudyConstants{
	public const RESPONSE_GROUP_TABLE = "response_group";
	public const RESPONSE_SUB_GROUP_TABLE = "response_sub_group";
	public const QUESTIONS_TABLE = "questions";
	public const QUESTIONS_OPTIONS_TABLE = "questions_options";
	public const RESULTS_TABLE = "results";
	public const STUDY_TABLES = array(
		self::RESPONSE_GROUP_TABLE,
		self::RESPONSE_SUB_GROUP_TABLE,
		self::QUESTIONS_TABLE,
		self::QUESTIONS_OPTIONS_TABLE,
		self::RESULTS_TABLE
	);
	
	public const QUESTION_LABEL = "QUESTION_LABEL";
	public const QUESTION_CHOICE = "QUESTION_CHOICE";
	public const RESULT_DATA = "RESULT_DATA";
	public const PARENT_GROUP = "PARENT_GROUP";
	public const SUB_GROUP = "SUB_GROUP";
	
	public const STUDY_CSV_HEADERS = array(
		self::QUESTION_LABEL, 
		self::QUESTION_CHOICE, 
		self::RESULT_DATA, 
		self::PARENT_GROUP, 
		self::SUB_GROUP
	);
	
	public const CHART_COLORS = array('#FCE291', '#FCE291', '#FCE291', '#FCE291', '#FCE291', '#FCE291', '#FCE291', '#FCE291', '#FCE291', '#FCE291');
	public const FADE_COLOR = "#e6e6e6";
	
}
?>