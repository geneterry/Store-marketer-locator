<?php 

	add_shortcode('register_leads_page', 'register_leads_page');
	function register_leads_page(){
		global $wpdb;
		?>
		<?php 
		if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
            
           $_SESSION['register_data'] = $_POST;
           do_action('registerLeads');
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
						        <h4 class="text-center reg-title pt-3">Register an accounts</h4>
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
									<p class="text-danger font-italic mb-2">Please note that registration is for	
									physiotherpists	registered with HCPC.</p>
								</div>
							</div>

							<div class="form-row">
								<div class="col-md-6">
									<label for="username" class="reg-label">Username<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="username" id="username">
								</div>

								<div class="col-md-6">
									<label for="userPass" class="reg-label">Password<span class="text-danger required">*</span></label>
									<input type="password" class="form-control" name="userPass" id="userPass">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEmail" class="reg-label">Email<span class="text-danger required">*</span></label>
									<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email">
								</div>
								
							</div>
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="store_locator_website" class="reg-label">Website</label>
									<input type="text" name="store_locator_website" class="form-control" id="store_locator_website" value="<?= ( isset($_SESSION['register_data']['store_locator_website']) ) ? $_SESSION['register_data']['store_locator_website'] : '' ; ?>" placeholder="Website (optional)">
								</div>
							</div>
							<!-- /Account Information -->

							<!-- Working Hours Box -->
							<div class="row">
								<div class="col-md-12">
								<h4 class="text-left mb-3 mt-4 reg-title"><?php echo __("Store Hours", 'wpmsl'); ?><span class="text-danger required">*</span></h4>
				                <?php
				                $days = array(
				                    __("Monday","wpmsl"), 
				                    __("Tuesday","wpmsl"), 
				                    __("Wednesday","wpmsl"), 
				                    __("Thursday","wpmsl"), 
				                    __("Friday","wpmsl"), 
				                    __("Saturday","wpmsl"), 
				                    __("Sunday","wpmsl"));
				                    $days_meta = "";?>
				                <td>
				                    <table id="store_locator_hours">
				                        <?php foreach ($days as $day): ?>
				                    <tr class="tbl-row-container">
				                        <td class="tbl_store_days"><?php echo $day; ?></td>
				                        <td class="tbl_store_status">
											<div class="store-status-container">
				                            <input <?php echo ( isset($_SESSION['register_data']['store_locator_days'][$day]['status'] ) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == '1')?'checked':''; ?> type="radio" value="1" id="store_locator_days_<?php echo $day; ?>_1" name="store_locator_days[<?php echo $day; ?>][status]" > <label for="store_locator_days_<?php echo $day; ?>_1"> <?php _e('Opened','wpmsl'); ?> </label>
											</div>
											<div class="store-status-container">
				                            <input <?php echo ( !isset($_SESSION['register_data']['store_locator_days'][$day]['status']) || $_SESSION['register_data']['store_locator_days'][$day]['status'] == '0')?'checked':''; ?> type="radio" value="0" id="store_locator_days_<?php echo $day; ?>_0" name="store_locator_days[<?php echo $day; ?>][status]" /> <label for="store_locator_days_<?php echo $day; ?>_0"><?php _e('Closed','wpmsl'); ?> </label>
											</div>
				                        </td>
				                        <td class="tbl_store_hours">
				                            <input <?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status'] ) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Open Time" type="text" value="<?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status']) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == 1)? $_SESSION['register_data']['store_locator_days'][$day]['start'] : ''; ?>" name="store_locator_days[<?php echo $day; ?>][start]" class="start_time"/>
				                            <input <?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status']) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Close Time" type="text" value="<?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status']) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == 1)? $_SESSION['register_data']['store_locator_days'][$day]['end'] : ''; ?>" name="store_locator_days[<?php echo $day; ?>][end]" class="end_time" />
                                          <?php if ($day !== 'Monday'): ?>
                                            	<a class="btn btn-sm btn-primary mx-3 copyTimeRegister text-white d-none" id="copyTime<?= $day ?>" title="Copy Time Above"><i class="fa fa-copy"></i></a>
                                          <?php endif; ?>
				                        </td>
				                    </tr>
				                            <?php endforeach; ?>
				                        </table>
				                    </td>	
								</div>
							</div>
							<!-- /Working Hours Box -->


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
								<div class="form-group col-md-6">
									<label for="inputGender">Gender	of	Physiotherapists that you provide <span class="text-danger required">*</span></label>
									<select id="inputGender" name="inputGender" class="form-control">
										<option>Choose...</option>
										<?php
					                        $allGender = $wpdb->get_results("SELECT * FROM store_locator_gender");
					                        $selectedGender = "";
					                        foreach ($allGender as $gender) {
					                            ?>
					                            <option value="<?php echo $gender->gender; ?>" <?= ( isset($_SESSION['register_data']['inputGender']) && $_SESSION['register_data']['inputGender'] == $gender->gender ) ? 'selected' : '' ; ?>><?php echo $gender->gender; ?></option>
					                            <?php
					                        }
					                    ?>
									</select>
								</div>
							
							</div>
							<div class="form-group">
								<label for="store_locator_website">About us</label>
								<textarea name="store_locator_description" id="store_locator_description" class="form-control" rows="3"><?= ( isset($_SESSION['register_data']['store_locator_description']) ) ? $_SESSION['register_data']['store_locator_description'] : '' ; ?></textarea>
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
							
								<div class="form-group col-md-6">
									<?php 
										$tbl_name = $wpdb->prefix . 'term_taxonomy';

										$catIDs = $wpdb->get_results($wpdb->prepare(
										  	"SELECT * FROM ". $tbl_name ." WHERE taxonomy = %s", 
										  	'store_locator_category'
										  ));
									 ?>
									<label for="inputCategory">Category<span class="text-danger required">*</span></label>

									<select id="inputCategory" name="inputCategory[]" class="form-control selectpicker" multiple data-live-search="true">
										<?php
											foreach ( $catIDs as $catID ) {
												$termID = $catID->term_id;
												$termName = $wpdb->get_row($wpdb->prepare(
												  	"SELECT * FROM "  . $wpdb->prefix . 'terms' . " WHERE term_id = %d",
												  	 $termID
												  ));
												?>
												<option value="<?php echo $termName->term_id; ?>" <?= ( isset($_SESSION['register_data']['inputCategory']) && $_SESSION['register_data']['inputCategory'] == $termName->term_id ) ? 'selected' : '' ; ?>><?php echo $termName->name; ?></option>
										    <?php
											}
										?>
									</select>
								</div>
							<!-- /Personal Information -->
							<div class="submit-row text-center pt-2">
								<div class="form-group mt-2">
									<div class="form-check">
										<input class="form-check-input submit-ap" name="acceptTerms" type="checkbox" id="gridCheck" <?= ( isset($_SESSION['register_data']['acceptTerms']) && $_SESSION['register_data']['acceptTerms'] == 'on') ? 'checked' : '' ; ?>>
										<label class="form-check-label" for="gridCheck">
											<strong>
												I agree to <a href="/terms-and-conditions/">Terms of Condition</a>
											</strong>
										</label>
									</div>
								</div>

								<button type="submit" class="btn btn-primary btn-lg mb-2">SUBMIT</button>
								<input type="hidden" name="submit" id="submitted" value="true" />
				    			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
							</div>
						</div>
						<!-- /AP-box -->

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
