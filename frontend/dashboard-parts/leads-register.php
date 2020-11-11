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
		<div class="container">
			<form action="" id="registerServiceProviderForm" method="POST" enctype="multipart/form-data">
			<div class="row">
			<div class="col-md-5">
				<div class="mx-auto d-block">
					<div class="avatar-upload">
						<label>Profile Image</label>
				        <div class="avatar-edit">
				            <input type='file' name="imageUpload" id="imageUpload" accept=".png, .jpg, .jpeg" multiple="false" />
				            <label for="imageUpload"></label>
				        </div>
				        <div class="avatar-preview">
				            <div id="imagePreview" style="background-image: url(https://dummyimage.com/360x360/000/fff);">
				            </div>
				        </div>
				    </div>
				</div>

					 <h3 class="text-center pb-3 pt-2"><?php echo __("Working Hours", 'wpmsl'); ?></h3>
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
	                                <tr>
	                                    <td><?php echo $day; ?></td>
	                                    <td>

	                                        <input <?php echo ( isset($_SESSION['register_data']['store_locator_days'][$day]['status'] ) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == '1')?'checked':''; ?> type="radio" value="1" id="store_locator_days_<?php echo $day; ?>_1" name="store_locator_days[<?php echo $day; ?>][status]" > <label for="store_locator_days_<?php echo $day; ?>_1"> <?php _e('Opened','wpmsl'); ?> </label>

	                                        <input <?php echo ( !isset($_SESSION['register_data']['store_locator_days'][$day]['status']) || $_SESSION['register_data']['store_locator_days'][$day]['status'] == '0')?'checked':''; ?> type="radio" value="0" id="store_locator_days_<?php echo $day; ?>_0" name="store_locator_days[<?php echo $day; ?>][status]" /> <label for="store_locator_days_<?php echo $day; ?>_0"><?php _e('Closed','wpmsl'); ?> </label>
	                                    </td>
	                                    <td>
	                                        <input <?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status'] ) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Open Time" type="text" value="<?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status']) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == 1)? $_SESSION['register_data']['store_locator_days'][$day]['start'] : ''; ?>" name="store_locator_days[<?php echo $day; ?>][start]" class="start_time"/>

	                                        <input <?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status']) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Close Time" type="text" value="<?php echo (isset($_SESSION['register_data']['store_locator_days'][$day]['status']) && $_SESSION['register_data']['store_locator_days'][$day]['status'] == 1)? $_SESSION['register_data']['store_locator_days'][$day]['end'] : ''; ?>" name="store_locator_days[<?php echo $day; ?>][end]" class="end_time" />

	                                       
	                                    </td>
	                                </tr>
	                            <?php endforeach; ?>
	                        </table>
	                    </td>	
			</div>
				<div class="col-md-7">

					<div class="account-info mb-4">
						<h3 class="mb-3 pt-2">Account Information</h3>
						<div class="form-row">
							<div class="col-md-6">
								<label for="username">Username</label>
								<input type="text" class="form-control" name="username" value="<?= ( isset($_SESSION['register_data']['username']) ) ? $_SESSION['register_data']['username'] : '' ; ?>" id="username">
							</div>
							<div class="col-md-6">
								<label for="userPass">Password</label>
								<input type="password" class="form-control" name="userPass" value="<?= ( isset($_SESSION['register_data']['userPass']) ) ? $_SESSION['register_data']['userPass'] : '' ; ?>" id="userPass">
							</div>
						</div>
					</div>


					<h3 class="mb-4">Personal Information</h3>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputFirstName">First Name</label>
							<input type="text" class="form-control" name="inputFirstName" id="inputFirstName" value="<?= ( isset($_SESSION['register_data']['inputFirstName']) ) ? $_SESSION['register_data']['inputFirstName'] : '' ; ?>" placeholder="First Name">
						</div>
						<div class="form-group col-md-6">
							<label for="inputLastName">Last Name</label>
							<input type="text" class="form-control" name="inputLastName" id="inputLastName" value="<?= ( isset($_SESSION['register_data']['inputLastName']) ) ? $_SESSION['register_data']['inputLastName'] : '' ; ?>" placeholder="Last Name">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputEmail">Email</label>
							<input type="email" class="form-control" name="inputEmail" id="inputEmail" value="<?= ( isset($_SESSION['register_data']['inputEmail']) ) ? $_SESSION['register_data']['inputEmail'] : '' ; ?>" placeholder="Email">
						</div>
						<div class="form-group col-md-6">
							<label for="inputPhone">Phone</label>
							<input type="text" class="form-control" name="inputPhone" id="inputPhone" value="<?= ( isset($_SESSION['register_data']['inputPhone']) ) ? $_SESSION['register_data']['inputPhone'] : '' ; ?>" placeholder="Phone">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="inputGender">Gender</label>
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
						<div class="form-group col-md-6">
							<?php 
								$tbl_name = $wpdb->prefix . 'term_taxonomy';

								$catIDs = $wpdb->get_results($wpdb->prepare(
								  	"SELECT * FROM ". $tbl_name ." WHERE taxonomy = %s", 
								  	'store_locator_category'
								  ));
							 ?>
							<label for="inputCategory">Category</label>
							<select id="inputCategory" name="inputCategory" class="form-control">
								<option>Choose...</option>
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
					</div>

					<div class="form-group">
						<label for="store_locator_website">Website</label>
						<input type="text" name="store_locator_website" class="form-control" id="store_locator_website" value="<?= ( isset($_SESSION['register_data']['store_locator_website']) ) ? $_SESSION['register_data']['store_locator_website'] : '' ; ?>" placeholder="Website (optional)">
					</div>

					<div class="form-group">
						<label for="store_locator_website">Description</label>
						<textarea name="store_locator_description" id="store_locator_description" class="form-control" rows="3"><?= ( isset($_SESSION['register_data']['store_locator_description']) ) ? $_SESSION['register_data']['store_locator_description'] : '' ; ?></textarea>
					</div>

					<div class="form-group">
						<label for="store_locator_address">Address</label>
						<input type="text" name="store_locator_address" class="form-control" id="store_locator_address" value="<?= ( isset($_SESSION['register_data']['store_locator_address']) ) ? $_SESSION['register_data']['store_locator_address'] : '' ; ?>" placeholder="1234 Main St">
					</div>

					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="store_locator_country">Country</label>
							<select id="store_locator_country" name="store_locator_country" class="form-control">
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

						<div class="form-group col-md-4 state_input" <?php echo ($selectedCountry != " United States")?"style='display: none;'":""; ?> >
							<label for="store_locator_state">State</label>
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
							<label for="store_locator_city">City</label>
							<input type="text" name="store_locator_city" class="form-control" value="<?= ( isset($_SESSION['register_data']['store_locator_city']) ) ? $_SESSION['register_data']['store_locator_city'] : '' ; ?>" id="store_locator_city">
						</div>

						<div class="form-group col-md-4">
							<label for="store_locator_zipcode">Postal Code</label>
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
					
				</div>


			</div>
			<div class="submit-row text-right pt-2">
				<div class="form-group mt-2">
					<div class="form-check">
						<input class="form-check-input" name="acceptTerms" type="checkbox" id="gridCheck" <?= ( isset($_SESSION['register_data']['acceptTerms']) && $_SESSION['register_data']['acceptTerms'] == 'on') ? 'checked' : '' ; ?>>
						<label class="form-check-label" for="gridCheck">
							<strong>
								I agree to <a href="#">Terms of Condition</a>
							</strong>
						</label>
					</div>
				</div>

				<button type="submit" class="btn btn-primary mb-2">SUBMIT</button>
				<input type="hidden" name="submit" id="submitted" value="true" />
    			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
			</div>
		</form>

	</div>
<?php
}
 ?>
