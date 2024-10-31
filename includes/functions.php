<?php
/*
* @Author 		pickplugins
* Copyright: 	pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	

	function qa_ans2qa_action_admin_menus(){
		// add_submenu_page( 'edit.php?post_type=question', __( 'Anspress Migration', QA_TEXTDOMAIN ), __( 'Anspress Migration', QA_TEXTDOMAIN ), 'manage_options', 'anspress_migration', 'qa_ans2qa__migration' );	
		
		add_submenu_page( 'anspress', __( 'Anspress to QA', QA_TEXTDOMAIN ), __( 'Anspress to QA', QA_TEXTDOMAIN ), 'manage_options', 'anspress_migration', 'qa_ans2qa__migration' );
		//add_submenu_page( 'edit.php?post_type=question', __( 'Anspress Migration', QA_TEXTDOMAIN ), __( 'Anspress Migration', QA_TEXTDOMAIN ), 'manage_options', 'anspress_migration', 'qa_ans2qa__migration' );		
			
	}
	add_action('qa_action_admin_menus','qa_ans2qa_action_admin_menus');	
	
	

	
	
	
	function qa_ans2qa__migration(){
		include( QA_DW2QA_PLUGIN_DIR. 'includes/menus/migration.php' );
	}
	
	function qa_ans2qa_ajax_migration() {
		
		$paged 			= (int)$_POST['paged'];
		$ppp 			= (int)$_POST['ppp'];
		
		
		$delete_dw_question	= get_option( 'qa_options_ans2qa_delete_question', 'no' );
		$delete_dw_answer 	= get_option( 'qa_options_ans2qa_delete_answer', 'no' );
		

		$wp_query = new WP_Query(
			array (
				'post_type' => 'question',
				'post_status' => 'publish',
				'orderby' => 'Date',
				'order' => 'DESC',
				'posts_per_page' => $ppp,
				'paged' => $paged,
		) );
				
		if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();	

			$_ap_status = get_post_status( get_the_ID() );
			if( $_ap_status == 'closed' || $_ap_status == 'resolved' ) {
				update_post_meta( get_the_ID() , 'qa_question_status', 'solved' );
			} else {
				update_post_meta( get_the_ID() , 'qa_question_status', 'processing' );
			}

			$_ap_selected = get_post_meta( get_the_ID(), '_ap_selected', true );
			update_post_meta( get_the_ID(), 'qa_meta_best_answer', $_ap_selected );
			
			
			$Answers_Query = new Answers_Query(
				array(
					'question_id' => $main_questio_ID
				)
			);
			if ( $Answers_Query->have_posts() ) : while ( $Answers_Query->have_posts() ) : $Answers_Query->the_post();

				
				update_post_meta( get_the_ID(), 'qa_answer_question_id', $main_questio_ID);

				
			endwhile; endif;
			
		endwhile; endif;
		
		
		die();
	}

	add_action('wp_ajax_qa_ans2qa_ajax_migration', 'qa_ans2qa_ajax_migration');
	add_action('wp_ajax_nopriv_qa_ans2qa_ajax_migration', 'qa_ans2qa_ajax_migration');
	

	function qa_filter_settings_options_function_anspress( $section_options ){
		
		return array_merge( $section_options, array(
			
			'qa_options_ans2qa_delete_question'=>array(
				'css_class'=>'qa_options_ans2qa_delete_question',					
				'title'=>__('Delete Orginal DW Questions?', QA_TEXTDOMAIN),
				'option_details'=>__('Do you want to delete all orginal questions from of DW Database.<br>Default: No.',QA_TEXTDOMAIN),					
				'input_type'=>'select',
				'input_values'=> 'no',
				'input_args'=> array( 'no'=>__('No',QA_TEXTDOMAIN), 'yes'=>__('Yes',QA_TEXTDOMAIN),),
			),
			
			'qa_options_ans2qa_delete_answer'=>array(
				'css_class'=>'qa_options_ans2qa_delete_answer',					
				'title'=>__('Delete Orginal DW Answers?', QA_TEXTDOMAIN),
				'option_details'=>__('Do you want to delete all orginal answers from of DW Database.<br>Default: No.',QA_TEXTDOMAIN),					
				'input_type'=>'select',
				'input_values'=> 'no',
				'input_args'=> array( 'no'=>__('No',QA_TEXTDOMAIN), 'yes'=>__('Yes',QA_TEXTDOMAIN),),
			),
			
		) );
	}
	add_filter( 'qa_settings_section_import', 'qa_filter_settings_options_function_anspress',10,1 );

	function generate_question_meta_keys( $post_type = '' ){
	
		if( empty( $post_type ) ) return;
		
		global $wpdb;
		$query = "
			SELECT DISTINCT($wpdb->postmeta.meta_key) 
			FROM $wpdb->posts 
			LEFT JOIN $wpdb->postmeta 
			ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
			WHERE $wpdb->posts.post_type = '%s' 
			AND $wpdb->postmeta.meta_key != '' 
		";
		$meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));
		return $meta_keys;
	}

	
	
		
	// function sample_admin_notice__error() {
		
		// $class = 'notice notice-error';
		// $message = __( 'Irks! An error has occurred.', 'sample-text-domain' );
		
		// printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	// }
	
	// global $pagenow;
	// if ( ! class_exists( 'AnsPress' ) && $pagenow == 'admin.php' & isset( $_GET['page'] ) && $_GET['page'] == 'anspress_migration' ) { 
		// add_action( 'admin_notices', 'sample_admin_notice__error' );
	// }
	
	
	
	
	
	
	
	
	
	