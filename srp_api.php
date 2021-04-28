<?php

if ( ! defined( 'ABSPATH' ) ) {
	require_once ("../../../wp-load.php");

	if (isset($_POST['form_data_json']) && !empty($_POST['form_data_json'])) {

		// echo 'este cerere';
		// print_r($formDataJSON);
		// echo gettype($formDataJSON);

		$formDataJSON = $_POST['form_data_json'];
		$formDataJSON = json_decode(stripslashes($formDataJSON));

		//	'main_site_address' => 'https://localhost/hapigood-prod/',

		// construct array for creating post func
		$formDataArr = array(
				'post_content'  => wp_strip_all_tags( $formDataJSON->post_content ),
				'post_title'    => wp_strip_all_tags( $formDataJSON->post_title ),
				// 'post_date'  => wp_strip_all_tags( $formDataJSON->post_date ),
				'post_type'     => 'srp_review_posts',
				'post_status'   => 'publish',
				'meta_input' => array(
						'srp_author_name_meta' => wp_strip_all_tags( $formDataJSON->author_full_name ),
						'srp_author_description_meta' => wp_strip_all_tags( $formDataJSON->profession_title )
				)
		);

		// insert post

		// TODO: security check
//		$site_address_val = get_option( 'srp_sync_site_address' );
//
//		if ($site_address_val == $formDataJSON['main_site_address']){
//			echo 'corect';
//		} else {
//			echo 'incorect';
//		}


		$resultPostInsert = wp_insert_post( $formDataArr, false );
//		$resultPostInsert = 1;
		if ( isset($resultPostInsert) && $resultPostInsert != 0 ) {
			$responseJSON = array(
					'postInsert' => '1',
			);
			$responseJSON = json_encode($responseJSON);
			echo $responseJSON;
		} else {
			$responseJSON = array(
					'postInsert' => '0',
			);
			$responseJSON = json_encode($responseJSON);
			echo $responseJSON;
		}
	}
}
