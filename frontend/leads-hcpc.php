<?php 

	add_shortcode('hcpc_form', 'hcpc_form_func');
	function hcpc_form_func(){
		global $wpdb;
		$store_id = '';
		$rand_password = '';

		if ( isset( $_GET['id'] ) AND isset( $_GET['verification_code'] ) ):

	            $store_id = $_GET['id'];
	            $rand_password = $_GET['verification_code'];

        else:
        	redirect_me_to(home_url());
        endif;

        $userCurrentRole = '';
        
        if( is_user_logged_in() ):
        	$user = wp_get_current_user();
			if ( in_array( 'administrator', (array) $user->roles ) ) :
    			swal_message('error', 'Error!', 'You are logged in as Administrator. You cannot access this page.', true, home_url() );
			endif;
        endif;

		$tbl_name = $wpdb->prefix . 'physiobrite_unverified_user_data';
        $currentUser = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM ". $tbl_name ." WHERE store_id = %d AND rand_password = %s", 
                    $store_id, $rand_password
                  ));

	    if ($currentUser) :

        	$userData = $currentUser;
        	if ( $userData->is_verified !== "0" ):
        		swal_message('error', 'Error!', 'This account is already verified!', true, home_url() );

        	endif;


	        if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) :
			
				do_action('insert_hcpc_id');
		    
		    endif;
?>

			<form action="" id="hcpc_form_submit" class="pt-5 mb-5 pb-5" method="POST">
				<div class="row pt-3 text-center">
					<div class="col-md-8 offset-md-2">
						<!-- <div class="input-group input-group-lg"> -->
							<!--input type="text" class="form-control" id="hcpc_id" name="hcpc_id" value="" placeholder=""-->
							<!--div class="input-group-append"-->
							    <button class="btn btn-primary btn-lg" type="submit">Verify Your Account</button>
							  <!-- </div> -->
						<!-- </div> -->
						<div class="claim-info my-3">
							*This information has been provided by the individual practice/physio and has not been independently checked.
						</div>
					</div>
						<input type="hidden" name="submit" id="submitted" value="true" />
						<input type="hidden" name="store_id" id="store_id" value="<?= $userData->store_id; ?>" />
						<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
				</div>
			</form>

<?php
        elseif ( empty($currentUser) ):

    		swal_message('error', 'Error!', 'The URL is not valid! Please check your email and try again.', true, home_url() );

		endif;   	
	}

 ?>