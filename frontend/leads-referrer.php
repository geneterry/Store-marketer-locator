<?php
add_shortcode('referrer_leads_page', 'referrer_leads_page_func');
	function referrer_leads_page_func(){
		global $wpdb; 
		?>
		<?php 
		if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
            
           $_SESSION['register_data'] = $_POST;
           do_action('register_service_provider');
           resendEmailVerify();
        }
		 ?>
		 <div class="container  mb-5 pb-5">
		
		<form action="" id="registerServiceProviderForm" method="POST" enctype="multipart/form-data">
			<div class="row">
				<!-- Profile Image-->
					<div class="col-md-12">
						<div class="mx-auto d-block">
							<div class="avatar-upload">
						        <div class="avatar-edit">
						            <input type='file' name="imageUpload" id="imageUpload" accept=".png, .jpg, .jpeg" multiple="false" />
						            <label for="imageUpload"></label>
						        </div>
						        <div class="avatar-preview">
						            <div id="imagePreview" style="background-image: url(https://dummyimage.com/360x360/000/fff);">
						            </div>
						        </div>
						        <h4 class="text-center reg-title pt-3">Register Referrer Account</h4>
						    </div>
						</div>
					</div>
				<!-- /Profile Image -->
				<!-- Create Account Settings -->
					<div class="col-md-12">
						<div class="ap-info-box">

							<!-- Account Information -->
							<div class="row">
								<div class="col-md-12">
									<h4 class="mb-3 reg-title">Account Information</h4>
									<p class="text-danger font-italic mb-2">Please note that registration is for Referrer Leads</p>
								</div>
							</div>

						<div class="form-row">
								<div class="col-md-6">
									<label for="username" class="reg-label">Username<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="username" id="username">
								</div>

								<!-- 	<div class="col-md-6">
									<label for="userPass" class="reg-label">Password<span class="text-danger required">*</span></label>
									<input type="password" class="form-control" name="userPass" id="userPass">
								</div>-->
							</div> 
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEmail" class="reg-label">Email<span class="text-danger required">*</span></label>
									<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email">
								</div>
								<div class="form-group col-md-6">
									<label for="inputPhone">Phone<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="inputPhone" id="inputPhone" value="<?= ( isset($_SESSION['register_data']['inputPhone']) ) ? $_SESSION['register_data']['inputPhone'] : '' ; ?>" placeholder="Phone">
								</div>
							</div>
							
							<!-- /Account Information -->

							<!-- Personal Information -->
							<div class="row">
								<div class="col-md-12">
									<h4 class="mt-4 mb-3 reg-title">Personal Information</h4>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputFirstName" class="reg-label">First Name<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="inputFirstName" id="inputFirstName" value="<?= ( isset($_SESSION['register_data']['inputFirstName']) ) ? $_SESSION['register_data']['inputFirstName'] : '' ; ?>" placeholder="First Name">
								</div>
								<div class="form-group col-md-6">
									<label for="inputLastName" class="reg-label">Last Name<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="inputLastName" id="inputLastName" value="<?= ( isset($_SESSION['register_data']['inputLastName']) ) ? $_SESSION['register_data']['inputLastName'] : '' ; ?>" placeholder="Last Name">
								</div>
							</div>
							
								


								<div class="form-row">
									<div class="form-group col-md-12">
										<label for="store_locator_address" class="reg-label">Address<span class="text-danger required">*</span></label>
										<input type="text" name="store_locator_address" class="form-control" id="store_locator_address" value="<?= ( isset($_SESSION['register_data']['store_locator_address']) ) ? $_SESSION['register_data']['store_locator_address'] : '' ; ?>" placeholder="">
									</div>
								</div>

								<div class="form-row">
                                  <div class="form-group col-md-4">
										<label for="store_locator_city" class="reg-label">City<span class="text-danger required">*</span></label>
										<input type="text" name="store_locator_city" class="form-control" value="<?= ( isset($_SESSION['register_data']['store_locator_city']) ) ? $_SESSION['register_data']['store_locator_city'] : '' ; ?>" id="store_locator_city">
									</div>
                                  
									<div class="form-group col-md-4">
										<label for="store_locator_country" class="reg-label">Country<span class="text-danger required">*</span></label>
										<select id="store_locator_country" name="store_locator_country" class="form-control gmap-select">
											<option value=""></option>
											<?php
					                        $allCountries = $wpdb->get_results("SELECT * FROM store_locator_country");
					                        $selectedCountry = "";
					                        foreach ($allCountries as $country) {
					                            ?>
					                            <option value="<?php echo $country->name; ?>" <?= ( isset($_SESSION['register_data']['store_locator_country']) && $_SESSION['register_data']['store_locator_country'] == $country->name ) ? 'selected' : '' ; ?>><?php echo $country->name; ?></option>
					                            <?php
					                        }
					                        ?>
										</select>
									</div>

									<div class="form-group col-md-4 state_input gmap-select" <?php echo ($selectedCountry != " United States")?"style='display: none;'":""; ?> >
										<label for="store_locator_state" class="reg-label">State<span class="text-danger required">*</span></label>
										<select id="store_locator_state" name="store_locator_state" class="form-control">
											<option value=""></option>
											<?php
					                        $allStates = $wpdb->get_results("SELECT * FROM store_locator_state");
					                        $selectedState = "";
					                        foreach ($allStates as $state) {
					                            ?>
					                            <option value="<?php echo $state->name; ?>" <?= ( isset($_SESSION['register_data']['store_locator_state']) ) ? $_SESSION['register_data']['store_locator_state'] : '' ; ?>><?php echo $state->name; ?></option>
					                            <?php
					                        }?>
										</select>
									</div>
									

									<div class="form-group col-md-4">
										<label for="store_locator_zipcode" class="reg-label">Postal Code<span class="text-danger required">*</span></label>
										<input type="text" class="form-control" name="store_locator_zipcode" value="<?= ( isset($_SESSION['register_data']['store_locator_zipcode']) ) ? $_SESSION['register_data']['store_locator_zipcode'] : '' ; ?>" id="store_locator_zipcode">
									</div>

								</div>
									<!-- <div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputPaypalId">Paypal ID<span class="text-danger required">*</span></label>
										<input type="text" class="form-control" name="inputPaypalId" id="inputPaypalId" value="<?= ( isset($_SESSION['register_data']['inputPaypalId']) ) ? $_SESSION['register_data']['inputPaypalId'] : '' ; ?>" placeholder="Paypal ID">
									</div>
								</div>-->

								<input type="hidden" value="" name="store_locator_lat" value="<?= ( isset($_SESSION['register_data']['store_locator_lat']) ) ? $_SESSION['register_data']['store_locator_lat'] : '' ; ?>" id="store_locator_lat"/>
							    <input type="hidden" value="" name="store_locator_lng" value="<?= ( isset($_SESSION['register_data']['store_locator_lng']) ) ? $_SESSION['register_data']['store_locator_lng'] : '' ; ?>" id="store_locator_lng"/>
							    <div id="map-container" style="position: relative;">
							        <div id="map_loader" style="z-index: 9;width: 100%; height: 200px;position: absolute;background-color: #fff;"><div class="uil-ripple-css" style="transform: scale(0.6); margin-left: auto; margin-right: auto;"><div></div><div></div></div></div>
							        <div id="map-canvas" style="height: 200px;width: 100%;"></div>
							    </div>
							    <script>
							        jQuery(document).ready(function (jQuery) {
							            store_locator_initializeMapBackend();
							        });
							        jQuery(document).ready(function(){
										jQuery('#store_locator_country').on('change', function(){
											var currtCountry = jQuery('#store_locator_country').val();
											if(currtCountry == 'United States'){
												jQuery('.state_input').removeAttr('style');
											}
											
										});
									});
							    </script>
							<!-- /Personal Information -->
							<div class="submit-row text-center pt-2">
								<div class="form-group mt-2">
									<div class="form-check">
										<input class="form-check-input submit-ap" name="acceptTerms" type="checkbox" id="gridCheck" <?= ( isset($_SESSION['register_data']['acceptTerms']) && $_SESSION['register_data']['acceptTerms'] == 'on') ? 'checked' : '' ; ?>>
										<label class="form-check-label" for="gridCheck">
											<strong>
												I agree to <a href="<?php echo get_site_url().'/terms-and-conditions/'; ?>">Terms of Condition</a>
											</strong>
										</label>
									</div>
								</div>

								<button type="submit" class="btn btn-primary btn-lg mb-2">SUBMIT</button>
								<input type="hidden" name="submit" id="submitted" value="true" />
								
								<input type="hidden" class="form-control" name="inputHcpc" id="inputHcpc" value="Referrer">
								<input type="hidden" name="user_role" id="user_role" value="referrer_leads" />
						<input type="hidden" name="post_type_un" id="post_type_un" value="referrer_leads" />
							<input type="hidden" name="post_name_un" id="post_name_un" value="Referrer" />	
							
									
								
									
				    			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
							</div>
						</div>
						<!-- /AP-box -->
							</div>
						<!-- Form Submit Buttons -->
						<!-- /Form Submit Buttons -->

					</div>
				<!-- Create Account Settings -->
			</div>

		</form>
	</div>
		 <?php
	}
?>