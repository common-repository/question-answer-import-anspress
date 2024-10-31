<?php
/*
Plugin Name: Question Answer - Import Anspress
Plugin URI: http://pickplugins.com
Description: Import Question & Answer form Anspress plugin.
Version: 1.0.0
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class QuestionAnswerAnspressImport{
	
	public function __construct(){
	
		$this->qa_ans2qa_define_constants();

		add_action( 'admin_enqueue_scripts', array( $this, 'qa_ans2qa_front_scripts' ) );
		
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
	}
	
	public function qa_ans2qa_front_scripts(){
		
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('qa_ans2qa_front_js', plugins_url( '/assets/front/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'qa_ans2qa_front_js', 'qa_ans2qa_ajax', array( 'qa_ans2qa_ajaxurl' => admin_url( 'admin-ajax.php')));	

		wp_enqueue_style('qa_ans2qa_style', QA_DW2QA_PLUGIN_URL.'assets/front/css/style.css');
	}

	public function qa_ans2qa_define_constants() {

		$this->define('QA_DW2QA_PLUGIN_URL', plugins_url('/', __FILE__)  );
		$this->define('QA_DW2QA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		$this->define('QA_DW2QA_TEXTDOMAIN', 'question-answer' );
		$this->define('QA_DW2QA_PLUGIN_NAME', __('Question Answer',QA_DW2QA_TEXTDOMAIN) );
		$this->define('QA_DW2QA_PLUGIN_SUPPORT', 'http://www.pickplugins.com/questions/'  );
	}
	
	private function define( $name, $value ) {
		if( $name && $value )
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	
} 

new QuestionAnswerAnspressImport();