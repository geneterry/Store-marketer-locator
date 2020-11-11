<?php 
		if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
            
            /*echo '<pre>';
            print_r($_POST);
            print_r($_FILES['imageUpload']);
            echo '</pre>';*/
            do_action('updateLeads2kel');
        }


        if ( isset( $_POST['delete_data'] ) ) {

        	//echo "string";
            //var_dump( $_POST['delete_data'] );
            do_action('delete_user_data');
        }
 ?>
	<style>
		
		span.required{
			position: relative;
			font-size: 25px;
			top:5px;
		}
		
	</style>


 	<!-- Form profile-->
 	<form action="" id="dashboard-edit-form1" method="POST" enctype="multipart/form-data">
 	<div class="row">
 		<div class="col-md-12">
 				<h4 class="text-center reg-title">Account Details</h4>
 			 	<!-- Centered Profile Picture -->
 					<div class="mx-auto d-block info-col-dash">
						<div class="avatar-upload">
							<div class="leads-info-form">
						        <div class="avatar-edit">
						            <input type='file' name="imageUpload" id="imageUpload" accept=".png, .jpg, .jpeg" multiple="false" />
						            <label for="imageUpload"></label>
						        </div>
					    	</div>
					        <div class="avatar-preview avatar-dash">
					            <div id="imagePreview" style="background-image: url(<?= (!empty($mediaImg)) ? $mediaImg : 'https://dummyimage.com/360x360/000/fff' ; ?>);">
					            </div>
					        </div>
					    </div>
					</div>
 				<!-- /Profile Picture -->

 			<div class="ap-dashboard-box">
 				<!-- Intro to Column -->
 				<div class="row">
 					<div class="col-md-12">
 						<!-- Information Column --> 	
 						<div class="leads-info-col leads-info-col-top">
 							<!-- Title Text -->
 							<div class="row ">
	 							<div class="col-md-12">
									<div style="padding-top: 70px"></div>
	 								<h4 class="reg-title">Personal Information</h4>
	 							</div>
 							</div>
 							<!-- /Title Text -->

 							<!-- Personal Information -->
 							<div class="row">
 							 <div class="col-md-12 mb-4">
 							 	<p class="leads-info"><strong>Name: </strong><?= ( !empty($physio_name) ) ? $physio_name : '' ; ?></p>
 												 		
 							 	
 							 </div>
 							</div>
 							<!-- /Personal Information -->

							<!-- Title Text -->
 							<div class="row">
	 							<div class="col-md-12">
	 								<h4 class="reg-title">Contact Information</h4>
	 							</div>
 							</div>
 							<!-- /Title Text -->

 							<!-- Personal Information -->
 							<div class="row">
 							 <div class="col-md-12">
 							 	<p class="leads-info"><strong>Address: </strong><?= ( !empty($physio_address) ) ? $physio_address : '' ; ?>, <?= ( !empty($physio_city) ) ? $physio_city : '' ; ?> <?= ( !empty($physio_country) ) ? $physio_country : '' ; ?>, <?= ( !empty($physio_state) ) ? $physio_state : '' ; ?> <?= ( !empty($physio_code) ) ? $physio_code : '' ; ?></p>
 							 	<p class="leads-info"><strong>Email: </strong><?= ( !empty($physio_email) ) ? $physio_email : '' ; ?></p>
								 <p class="leads-info"><strong>Phone: </strong><?= ( !empty($physio_phone) ) ? $physio_phone : '' ; ?></p>
 							 </div>
 							</div>

 							<!-- Edit Forms -->
 							
 							<!-- /Edit Forms -->

 							<!-- /Personal Information -->
						</div>
						
						
 						<!-- Information Column -->
  
 						<!-- Edit Form -->
 						<div class="leads-info-form leads-top-edit">

							<div class="account-info">
								<h3 class="reg-title mb-4 pt-4">Account Information</h3>
								<div class="form-row mb-4">
									<div class="col-md-6">
										<label for="username">Username</label>
										<input type="text" class="form-control" name="username" value="<?= ( !empty($leads_user) ) ? $leads_user : '' ; ?>" id="username" disabled>
									</div>
										<div class="col-md-6">
										<div class="form-group">
											<label for="inputEmail">Email<span class="text-danger required">*</span></label>
											<input type="email" class="form-control" name="inputEmail" id="inputEmail" value="<?= ( !empty($physio_email) ) ? $physio_email : '' ; ?>" placeholder="Email">
											
										</div>
									</div>
									<div class="col-md-6">
										<label for="userPass">Password <span class="text-danger required">*</span></label>
										<input type="password" class="form-control" name="userPass" value="<?= ( !empty($leads_pass) ) ? $leads_pass : '' ; ?>" id="userPass">
									</div>
										<div class="col-md-6">
										<label for="userPass pt-2">Re-type password <span class="text-danger required">*</span></label>
										<input type="password" class="form-control" name="userPassCheck" value="<?= ( !empty($leads_pass) ) ? $leads_pass : '' ; ?>" id="userPassCheck">
										<div class="response_password_check"></div>
									</div>
								</div>
							</div>

						

							<!-- Personal Information -->
							<div class="personal-info">
								<h3 class="reg-title mb-4">Personal Information</h3>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputFirstName">First Name<span class="text-danger required">*</span></label>
										<input type="text" class="form-control" name="inputFirstName" id="inputFirstName" value="<?= ( !empty($first_name) ) ? $first_name : '' ; ?>" placeholder="First Name">
									</div>
									<div class="form-group col-md-6">
										<label for="inputLastName">Last Name<span class="text-danger required">*</span></label>
										<input type="text" class="form-control" name="inputLastName" id="inputLastName" value="<?= ( !empty($last_name) ) ? $last_name : '' ; ?>" placeholder="Last Name">
									</div>
									<div class="form-group col-md-6">
										<label for="inputPhone">Phone<span class="text-danger required">*</span></label>
										<input type="text" class="form-control" name="inputPhone" id="inputPhone" value="<?= ( !empty($physio_phone) ) ? $physio_phone : '' ; ?>" placeholder="Phone">
									</div>
								</div>

								<div class="form-row">
								<div class="form-group col-md-6">
									<label for="store_locator_address">Address<span class="text-danger required">*</span></label>
									<input type="text" name="store_locator_address" class="form-control" value="<?= ( !empty($physio_address) ) ? $physio_address : '' ; ?>" id="store_locator_address" placeholder="">
								</div>
									<div class="form-group col-md-6">
										<label for="store_locator_city">City<span class="text-danger required">*</span></label>
										<input type="text" name="store_locator_city" class="form-control" value="<?= ( !empty($physio_city) ) ? $physio_city : '' ; ?>" id="store_locator_city">
									</div>
</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="store_locator_zipcode">Postal Code<span class="text-danger required">*</span></label>
										<input type="text" class="form-control" name="store_locator_zipcode" value="<?= ( !empty($physio_code) ) ? $physio_code : '' ; ?>" id="store_locator_zipcode">
									</div>

									<div class="form-group col-md-6">
										
										
										<label for="store_locator_country">Country<span class="text-danger required">*</span></label>
										<select id="store_locator_country" name="store_locator_country" class="form-control" disabled>
											<option value="United Kingdom">United Kingdom</option>
										</select>
									</div>

									<div class="form-group col-md-6 state_input" <?php echo ($selectedCountry != " United States")?"style='display: none;'":""; ?> >
										<label for="store_locator_state">State<span class="text-danger required">*</span></label>
										<select id="store_locator_state" name="store_locator_state" class="form-control">
											<option value=""></option>
											<?php
						                    $allStates = $wpdb->get_results("SELECT * FROM store_locator_state");
						                    $selectedState = "";
						                    foreach ($allStates as $state) {
						                        ?>
						                        <option value="<?php echo $state->name; ?>" <?php echo ($physio_state == $state->name) ? "selected" : ""; ?>><?php echo $state->name; ?></option>
						                        <?php
						                    }?>
										</select>
									</div>
									
									

								</div>
							</div>

								
						
						</div>
 						<!-- /Edit Form -->

 						<!-- Edit Form -->
					 	<div class="edit-buttons-column pt-4">
							<div class="leads-info-col leads-info-col-submit text-center">
								<button type="button" class="btn btn-default mb-2 edit-form">EDIT PROFILE</button>
							</div>
							<div class="leads-info-form text-center">
								<button type="submit" class="btn btn-primary mb-2">SUBMIT</button>
								<button type="button" class="btn btn-default mb-2 cancel-form">CANCEL</button>
							</div>
							<input type="hidden" name="submit" id="submitted" value="true" />
							<input type="hidden" name="leadsId" id="leadsId" value="<?= ( !empty($physioId) ) ? $physioId : '' ; ?>" />
							<input type="hidden" name="userId" id="userId" value="<?= ( !empty($userId) ) ? $userId : '' ; ?>" />
							<input type="hidden" name="currImg" id="currImg" value="<?= ( !empty($mediaId) ) ? $mediaId : '' ; ?>" />
							<input type="hidden" name="lead_type1" id="lead_type1" value="" />
							<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
						</div>
						<!-- Edit Form -->

 					</div>
 				</div>
 				<!-- /Intro to Column -->
 			</div>

 		</div>		
 	</div>
 	</form>
 	<form method="post" action="" id="delete_my_account_form">
	 	<div class="text-right">
			<button type="button" name="delete_data" id="delete_my_account" class="text-danger bg-none" value="<?= $userId ?>"><strong class="text-decoration-underline"><u>Delete My Account</u></strong></button>
	 	</div>
 	</form>
 	<!-- /Form Profile -->