<?php 

	add_shortcode('renew_account', 'renew_account_func');
	function renew_account_func(){
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

		$tbl_name = $wpdb->prefix . 'physiobrite_expired_user_data';
        $currentUser = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM ". $tbl_name ." WHERE store_id = %d AND rand_password = %s", 
                    $store_id, $rand_password
                  ));

	    if ($currentUser) :

        	$userData = $currentUser;
        	if ( $userData->is_verified == 1 ):
        		swal_message('error', 'Error!', 'A request for account renewal has already been sent for this account', true, home_url() );
        	endif;


	        if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) :
				
				do_action('renew_account_submit');
		    
		    endif;
?>

			<form action="" id="renew_account_submit" class="pt-5 mb-5 pb-5" method="POST">
				<h2 class="text-center">Do you want to renew your account?</h2>
				
				<div class="text-center mt-3 pt-3">
			    	
			    	<button class="btn btn-primary btn-lg" type="submit">Yes Renew My Account</button>
					
				</div>

				<input type="hidden" name="submit" id="submitted" value="true" />
				<input type="hidden" name="store_id" id="store_id" value="<?= $userData->store_id; ?>" />
				<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
			</form>

<?php
        elseif ( count($currentUser) == 0 ):

        		swal_message('error', 'Error!', 'The URL is not valid! Please check your email and try again.', true, home_url() );

	    endif;
	}

 ?>