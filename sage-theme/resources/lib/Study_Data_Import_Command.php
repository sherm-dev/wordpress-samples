<?php
/***********************************************************************************
**
**
*				Study of Jewish LA Data Importer
**
**
***********************************************************************************/
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '/wp-load.php');
require_once('StudyDataImporter.php');
require_once('StudyDatabaseTableCreator.php');




class Study_Data_Import_Command{
	
	/**
	 * Switch image by post type
	 *
	 *
	 * ## OPTIONS
	 *	<file_path>
	 * 
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp study-import <file_path>
	 *
	 *
	 *	@when wp
	 *
	 *
	 */
	public function __invoke($args){
		list($file_path) = $args;
			
		$data_importer = new StudyDataImporter($file_path);
		
		if(!get_option('sojla_tables_created')){
			$table_creator = new StudyDatabaseTableCreator();
			$table_creator->create_initial_tables();
			add_option('sojla_tables_created', TRUE);
			WP_CLI::line("TABLES CREATED");
		}
		
		$count = $data_importer->import_on_command();
		WP_CLI::success("Inserted: " . json_encode($count));
	}
}


WP_CLI::add_command( 'study-import', 'Study_Data_Import_Command');
?>