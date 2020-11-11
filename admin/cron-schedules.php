<?php 

add_action('local_enquiry_time_check', 'local_enquiry_time_check_func');
function local_enquiry_time_check_func(){
	global $wpdb;

	$timestampNow = strtotime( current_time('mysql', 1) );

	$tbl_enquiry = $wpdb->prefix . 'physiobrite_client_enquiries';
	$tbl_globalEnquiry = $wpdb->prefix . 'physiobrite_global_client_enquiries';

	$getNotViewed = $wpdb->get_results($wpdb->prepare(
	                    "SELECT * FROM ". $tbl_enquiry ." WHERE is_viewed = '0'", 
	                    0
	                  ));

	if ( $getNotViewed ) :

		foreach ($getNotViewed as $dataGet) :
			
			$dateCreated = strtotime($dataGet->date_created);
			$dateModified = strtotime($dataGet->date_modified);

			$diffCreated = ($timestampNow - $dateCreated);
			$diffModified = ($timestampNow - $dateModified);

			if ( $diffCreated >= 86400 && $dateModified == 0  ):
				//Delete from local enquiry
				$enquiryId = $dataGet->id;
				$wpdb->delete( $tbl_enquiry, array( 'id' => $enquiryId ) );

				//Insert to global enquiry
				$data = array(
	                'physio_id'             => null,
					'referrer_id'            => $dataGet->referrer_id,
	                'physio_cat'            => $dataGet->physio_cat,
					'physio_cat_male'            => $dataGet->physio_cat_male,
					'physio_cat_female'            => $dataGet->physio_cat_female,
					'physio_cat_2'            => $dataGet->physio_cat_2,
					'preferred_gender'            => $dataGet->preferred_gender,
	                'client_name'           => $dataGet->client_name,
	                'client_address'        => $dataGet->client_address,
	                'client_email'          => $dataGet->client_email,
	                'client_tel'            => $dataGet->client_tel,
	                'client_time_call'      => $dataGet->client_time_call,
	                'client_other_contact'  => $dataGet->client_other_contact,
	                'service_type'          => $dataGet->service_type,
	                'client_gender'         => $dataGet->client_gender,
					'client_type'         => $dataGet->client_type,
					 'other_Name'         => $dataGet->other_Name,
					'service_type'         => $dataGet->service_type,
	                'client_problem'        => $dataGet->client_problem,
	                'date_created'        	=> $dataGet->date_created,
	                'date_modified'        	=> current_time('mysql', 1),
					 'lead_type'        => $dataGet->lead_type,
	        	);

			    $format = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');

			    $insertId = $wpdb->insert($tbl_globalEnquiry,$data,$format);

			elseif ( $dateModified !== 0 && $diffModified >= 86400 ):

				$enquiryId = $dataGet->id;
				$wpdb->delete( $tbl_enquiry, array( 'id' => $enquiryId ) );

				//Insert to global enquiry
				$data = array(
	                   'physio_id'             => null,
					'referrer_id'            => $dataGet->referrer_id,
	                'physio_cat'            => $dataGet->physio_cat,
					'physio_cat_male'            => $dataGet->physio_cat_male,
					'physio_cat_female'            => $dataGet->physio_cat_female,
					'physio_cat_2'            => $dataGet->physio_cat_2,
					'preferred_gender'            => $dataGet->preferred_gender,
	                'client_name'           => $dataGet->client_name,
	                'client_address'        => $dataGet->client_address,
	                'client_email'          => $dataGet->client_email,
	                'client_tel'            => $dataGet->client_tel,
	                'client_time_call'      => $dataGet->client_time_call,
	                'client_other_contact'  => $dataGet->client_other_contact,
	                'service_type'          => $dataGet->service_type,
	                'client_gender'         => $dataGet->client_gender,
					'client_type'         => $dataGet->client_type,
					 'other_Name'         => $dataGet->other_Name,
					'service_type'         => $dataGet->service_type,
	                'client_problem'        => $dataGet->client_problem,
	                'date_created'        	=> $dataGet->date_created,
	                'date_modified'        	=> current_time('mysql', 1),
					 'lead_type'        => $dataGet->lead_type,
	        	);

			    $format = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');

			    $insertId = $wpdb->insert($tbl_globalEnquiry,$data,$format);

			endif;

		endforeach;

	endif;
}

add_action('member_expiry_check', 'member_expiry_check_func');
function member_expiry_check_func(){
	global $wpdb;

	$timestampNow = strtotime( current_time('mysql', 1) );

	$tbl_users = $wpdb->prefix . 'users';
	$tbl_usermeta = $wpdb->prefix . 'usermeta';
	$tbl_expiredUsers = $wpdb->prefix . 'physiobrite_expired_user_data';

	$getAllLeadsUserInfo = $wpdb->get_results($wpdb->prepare(
	                    "SELECT * FROM ". $tbl_usermeta ." WHERE user_id != %d AND meta_value LIKE  %s", 
	                    1, '%physiobrite_leads%'
	                  ));

	if ( $getAllLeadsUserInfo ) :

		foreach ( $getAllLeadsUserInfo as $getUserMeta ) :

			$userId = $getUserMeta->user_id;

			$getSingleLeadsInfo = $wpdb->get_row($wpdb->prepare(
	                    "SELECT * FROM ". $tbl_users ." WHERE ID = %d", 
	                    $userId
	                  ));
			
			$getUserInfoById = get_user_by('ID', $userId);

			$first_name = get_user_meta( $userId, 'first_name', true );
			$last_name = get_user_meta( $userId, 'last_name', true );
			$hcpc_id = get_user_meta( $userId, 'hcpc_id', true );
			$role = $getUserInfoById->roles[0];
			$credits = get_user_meta( $userId, 'credits', true );
			$credits = get_user_meta( $userId, 'credits', true );
			$leads_id = get_user_meta( $userId, 'leads_id', true );
			$is_promoted = get_user_meta( $userId, 'is_promoted', true );

			if ($getSingleLeadsInfo) :

				$userRegistered = strtotime($getSingleLeadsInfo->user_registered);
				$diffUserRegistered = abs($timestampNow - $userRegistered);

				$datediffdays = round($diffUserRegistered / (60 * 60 * 24));
				
				if ( $datediffdays >= 730 ):


					$rand_passwod=wp_generate_password(20);

        			$rand_passwod=preg_replace("/[^a-zA-Z]/", "", $rand_passwod);
					
					//Insert to expired user table
					$data = array(
		                'store_id'      => $leads_id,
		                'user_login'    => $getSingleLeadsInfo->user_login,
		                'user_pass'		=> $getSingleLeadsInfo->user_pass,
		                'user_url'      => $getSingleLeadsInfo->user_url,
		                'user_email'    => $getSingleLeadsInfo->user_email,
		                'first_name'    => $first_name,
		                'last_name'     => $last_name,
		                'rand_password' => $rand_passwod,
		                'hcpc_id'       => $hcpc_id,
		                'role'         	=> $role,
		                'date_created'  => $getSingleLeadsInfo->user_registered,
		                'date_modified' => current_time('mysql', 1),
		                'credits'       => $credits,
		                'leads_id'      => $leads_id,
		                'is_promoted'   => $is_promoted,
		                'is_verified'   => 0,
		        	);

				    $format = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%d','%d');

				    //$insertId = $wpdb->insert($tbl_expiredUsers,$data,$format);
				    $insertId = $wpdb->insert($tbl_expiredUsers,$data,$format);

				    if ($insertId) :

				    	$update_post = array(
				              'ID'          => $leads_id,
				              'post_type'   => 'expired_users',
				          );

						require_once(ABSPATH.'wp-admin/includes/user.php' );
						wp_delete_user( $userId );
				        
				        $updatePost = wp_update_post( $update_post );
				        sendExpiredAccountNotification( $data );

				    endif;

				endif;
			
			endif;

		endforeach;

	endif;
}

add_action( 'leads_promoted_expiry_check', 'leads_promoted_expiry_check_func' );
function leads_promoted_expiry_check_func() {
  
  	global $wpdb;
  	$timestampNow = strtotime( current_time('mysql', 1) );
  
  	$tbl_featured = $wpdb->prefix . 'physiobrite_featured_physio';
	$getAllPromoted = $wpdb->get_results($wpdb->prepare(
        'SELECT *, DATE_ADD(date_created, INTERVAL 1 MONTH) as date_expired FROM '. $tbl_featured
      ));
  
  	if($getAllPromoted):
  
  		foreach($getAllPromoted as $dataGet):
  			$dataId = $dataGet->id;
  			
  			$dateCreated = strtotime($dataGet->date_created);
  			$dateExpired = strtotime($dataGet->date_expired);

            $diffCreated = abs($timestampNow - $dateCreated);
			$datediff = round($diffCreated / (60 * 60 * 24));

            if ( $datediff >= 30 ):
  				$sendNotif = sendPromotedEmailExpired($dataGet);
  
  				if( $sendNotif == true ):
  		
  					update_user_meta($dataGet->physio_id, 'is_promoted', 0);
					$wpdb->delete( $tbl_featured, array( 'id' => $dataId ) );
  
  				endif;
            endif;
  
  		endforeach;
  
  	endif;
}


 ?>