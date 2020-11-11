<?php 


		if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
            
            /*echo '<pre>';
            print_r($_POST);
            print_r($_FILES['imageUpload']);
            echo '</pre>';*/
            do_action('updateLeads2');
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
 	<form action="" id="dashboard-edit-form" method="POST" enctype="multipart/form-data">
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
									
	 								<h4 class="reg-title">Personal Information</h4>
					
	 							</div>
 							</div>
 							<!-- /Title Text -->

							
 							<!-- Personal Information -->
 							<div class="row">
 							 <div class="col-md-12 mb-4">
								
 							 	<p class="leads-info"><strong>Name: </strong><?= ( !empty($physio_name) ) ? $physio_name : '' ; ?></p>
 							 	<p class="leads-info"><strong>Gender: </strong><?php 

									$physio_gender = get_post_meta( $physioId, "store_locator_gender_main", true );
									if ( $physio_gender !== "" ); 
										echo  ($physio_gender) ;
									 ?></p> 							 		
 							 	
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
							
 							 </div>
 							</div>

 							<!-- Edit Forms -->
 							
 							<!-- /Edit Forms -->

 							<!-- /Personal Information -->
						</div>
							<hr>
						
						<div class="leads-info-col">
							<div class="col-md-12">
							<div class="staff pt-4 pb-2">
								<h4 class="reg-title pb-2"><?php echo __("Service Type", 'wpmsl'); ?></h4>
								
									
								<p class="leads-info">
									
									<?php 
										if(!empty($store_locator_category))echo $store_locator_category->name;
									?>
								</p>
							</div>
						</div>
							</div>
						<hr>
						
						<div class="leads-info-col">
							<div class="col-md-12">
							<div class="staff pt-4 pb-2">
								<h4 class="reg-title pb-2"><?php echo __("Staff Information", 'wpmsl'); ?></h4>
								<?php 

									$staff_data = get_post_meta( $physioId, "store_locator_staff_members", true );
									if ( $staff_data !== "" ): 
										foreach ($staff_data as $value) :
									 
									 		$item = explode(",", $value);
								
								if ($item[3] == '1') {
									
									$itemgender = 'Male';
								}
								else if ($item[3] == '2') {
									
									$itemgender = 'Female';
								}
									 	 ?>
										<div class="form-row">
											<div class="form-group col-md-6">
												<strong>Name: </strong><?= $item[0] ?><br>
											
												<strong>Email: </strong><?= $item[1] ?>
											</div>
											<div class="form-group col-md-6" >
												<strong>HCPC ID: </strong><?= $item[2] ?><br>
											
												<strong>Gender: </strong><?php echo $itemgender ?>
											</div>
										</div>
								<hr>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
							</div>
						
						
							
 						
						
						<div class="leads-info-col">
							
									<div class="col-md-12">
							<div class="working-hours pt-4 pb-2">
								<h4 class="reg-title pb-2"><?php echo __("Working Hours", 'wpmsl'); ?></h4>
								<td>
									<table id="store_locator_hours">
										<?php foreach ($physio_days as $day => $value): 
											if($value['status'] == 1):
											?>
												<tr>
													<td><?php echo $day; ?></td>
													<td>
													    <strong><?php echo $value['start']; ?> - <?php echo $value['end'] ?></strong>
													</td>
												</tr>
											<?php 
											endif;
										endforeach; ?>
									</table>
								</td>	
							</div>
						</div>

</div>
						<hr>
 				<!-- Title Text -->
 							<div class="leads-info-col">
									<div class="col-md-12">
							<div class="working-hours pt-4 pb-2">
								<h4 class="reg-title pb-2"><?php echo __("Speciality", 'wpmsl'); ?></h4>
							  <div   style='width:50%; text-align:left; float:left;padding-bottom:10px;'>	
										<p class="leads-info"><strong>Speciality Of The Physio Available</strong></p>
									<?php 	
										

						
									$specialitynew_65= get_post_meta( $physioId, "store_locator_gender_category", false); 
									
								$needle    = ',';
								  
								  foreach ( $specialitynew_65 as $item) :
								  
				$catonaccount =  strstr( $item, $needle, true );  	
								  
		$catonaccountgender =	  $specialitynew_65a_selected =  strstr( $item, $needle, true );   
$catonaccount =   substr(strrchr( $item, $needle), 1 );  // think i need to make these arrays so that I can output it later
								
								
  if ($catonaccountgender !=='none') {
										  echo $catonaccount;
								  echo "<br>";
									  }
		
endforeach; 
								  
							   
								  
								  		
								  
								 

									
	?>
								
									
									 </div> 

								
								<div   style='width:50%; text-align:left; float:left;padding-bottom:10px;'>	<p class="leads-info"><strong>Gender Of Physio Available To Give Treatments</strong></p>
									
									
												<?php
										

						
									$specialitynew_65= get_post_meta( $physioId, "store_locator_gender_category", false); 
									
								$needle    = ',';
								  
								  foreach ( $specialitynew_65 as $item) :
								  
								
								 
$catonaccount =  strstr( $item, $needle, true );   
									  if ($catonaccount !=='none') {
										  echo $catonaccount;
								  echo "<br>";
									  }
endforeach; 
		
	?>
								
								
									
								
									
									<div style="width: 100%; padding-bottom: 50px"></div>
										
										
												</div>
								
								
									
	 							</div>
								</div></div>	<!-- Information Column -->
  
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
									
								</div>

								<div class="form-row">
								<div class="form-group col-md-12">
											<label for="inputGendermain">Gender<span class="text-danger required">*</span>	- <em>please select the gender you identify with or that most represents you </em></label>
										<select id="inputGendermain" name="inputGendermain" class="form-control">
											
											
						                            <option value="male" <?=($inputGendermain== 'male')?'selected="true"':''?> >Male</option>
						                            <option value="female" <?=($inputGendermain== 'female')?'selected="true"':''?>>Female</option>
						                           
						                    
										</select>
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
									
										 <select id="store_locator_country" name="store_locator_country" class="form-control">
										<option value="" ></option>
										<?php
										
										$allCountries = $wpdb->get_results("SELECT * FROM store_locator_country");
										
										foreach ($allCountries as $country) { ?>
											<option value="<?php echo $country->name; ?>" <?php echo ($physio_country == $country->name) ? "selected" : ""; ?>><?php echo $country->name; ?></option>
										<?php } ?>
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

							<input type="hidden" value="" name="store_locator_lat" id="store_locator_lat"/>
						    <input type="hidden" value="" name="store_locator_lng" id="store_locator_lng"/>
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
							

							

							<!--Staff Information-->
							<br>
							<hr>
							

						<!-- Working Hours Box -->
							
							<div class="row mb-5">
								<div class="col-md-12">
								<h4 class="text-left mb-3 mt-4 reg-title"><?php echo __("Service Type", 'wpmsl'); ?><span class="text-danger required">*</span></h4>
									
										<?php 
										  $terms = get_terms( 'store_locator_category', array('hide_empty' => 0));
										  
										  //print_r($terms);
										?>
										<select id="inputservicetypemain" name="inputservicetypemain" class="form-control">
											<option hidden>Choose...</option>
											    <?php foreach ( $terms as $term ) : 
												
												
												if(!empty($store_locator_category) && $term->name == $store_locator_category->name):
													?>
													<option value="<?php echo $term->term_id; ?>" selected="true"> <?php echo $term->name; ?></option>
													<?php
												else:
													?>
													<option value="<?php echo $term->term_id; ?>"> <?php echo $term->name; ?></option>
													<?php
												endif;
												?>
												
												
											<?php endforeach; ?>
										</select>
								</div>
							</div>
					<hr>
						
							<div class="row mb-5">
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
				                        <?php foreach ($physio_days as $day => $value): ?>
				                    <tr>
				                        <td class="tbl_store_days"><?php echo $day; ?></td>
				                        <td>
					                    <input <?php echo ($value['status'] == '1')?'checked':''; ?> type="radio" value="1" id="store_locator_days_<?php echo $day; ?>_1" name="store_locator_days[<?php echo $day; ?>][status]" > <label for="store_locator_days_<?php echo $day; ?>_1"> <?php _e('Opened','wpmsl'); ?> </label>
					                    <input <?php echo ($value['status'] == '0')?'checked':''; ?> type="radio" value="0" id="store_locator_days_<?php echo $day; ?>_0" name="store_locator_days[<?php echo $day; ?>][status]" /> <label for="store_locator_days_<?php echo $day; ?>_0"><?php _e('Closed','wpmsl'); ?> </label>
					                </td>
					                <td>
					                    <input <?php echo ($value['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Open Time" type="text" value="<?= ( !empty($value['start']) ) ? $value['start'] : '' ; ?>" name="store_locator_days[<?php echo $day; ?>][start]" class="start_time"/>
					                    <input <?php echo ($value['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Close Time" type="text" value="<?= ( !empty($value['end']) ) ? $value['end'] : '' ; ?>" name="store_locator_days[<?php echo $day; ?>][end]" class="end_time" />
					                </td>
				                    </tr>
				                            <?php endforeach; ?>
				                        </table>
				                    </td>	
								</div>
							</div>
							<!-- /Working Hours Box -->
							<hr>
							
							<div class="row mb-4">
								<div class="col-md-12">
									<h4 class="text-left mb-3 mt-4 reg-title"><?php echo __("Staff Information", 'wpmsl'); ?></h4>
									<div class="staff-section">
										<button type="button" class="btn btn-primary btn-sm mb-2 register-add-member">Add Member</button>

										<?php 
$staff_data = get_post_meta( $physioId, "store_locator_staff_members", true );
											if ( $staff_data !== "" ): 
												foreach ($staff_data as $value) :
											
											 		$item = explode(",", $value);
										
											 		$item = explode(",", $value);
								
								if ($item[3] == 1) {
									
									$itemgender = 'Male';
								}
								else if ($item[3] == 2) {
									
									$itemgender = 'Female';
								}

								// second letter of the word
											 	 ?>
										
										
										
												<div class="form-row align-items-end" style="border-bottom: #818181 dotted 1px">
								<div class="form-group col-md-4">
									<label for="inputStaffName" class="reg-label">Staff Name<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="inputStaffName[]" id="name="inputStaffName[]"" value="<?php echo $item[0]; ?>" placeholder="Staff Name" >
								</div>
								<div class="form-group col-md-4">
									<label for="inputStaffEmail" class="reg-label">Staff Email<span class="text-danger required">*</span></label>
									<input type="email" class="form-control"  name="inputStaffEmail[]"   id="name="inputStaffEmail[]"" value="<?php echo $item[1]; ?>" placeholder="Staff Email" disabled>
								<input type="hidden"  name="inputStaffEmail[]"   id="name="inputStaffEmail[]"" value="<?php echo $item[1]; ?>">
									
								</div>
								<div class="form-group col-md-3">
									<label for="inputStaffHcpc" class="reg-label">HCPC ID<span class="text-danger required">*</span></label>
									<input type="text" class="form-control" name="inputStaffHcpc[]"  id="name="inputStaffHcpc[]""  value="<?php echo $item[2]; ?>" placeholder="HCPC ID" disabled>
									<input type="hidden"  name="inputStaffHcpc[]"  id="name="inputStaffHcpc[]""  value="<?php echo $item[2]; ?>">
								</div>
<div class="form-group col-md-10">
									<label for="inputStaffgender" class="reg-label">Gender Of Physio<span class="text-danger required">*</span> <em>- please select the gender that the physio identify's with or that most represents the physio</em></label>
	
	<input type="text" class="form-control" name="inputStaffgender[]"  id="name="inputStaffgender[]" value="<?php echo $itemgender ?>" placeholder="Gender Of Physio" disabled>
	
	<input type="hidden"  name="inputStaffgender[]"  id="name="inputStaffgender[]" value="<?php echo $item[3] ?>">
									
				
								</div>
								<div class="form-group col-md-1">
									<button class="btn btn-primary mx-3 remove-staff-row">X</i>
</button>

								</div>

							</div>
											<?php endforeach; ?>
										<?php endif; ?>
										
									</div>
								</div>
							</div>
							

							<!--/Staff Information-->
							
							<hr>
							<div class="row">
							<div class="form-group col-md-12">
									
						<?php	 $tbl_name = $wpdb->prefix . 'term_taxonomy';

										$catIDs = $wpdb->get_results($wpdb->prepare(
										  	"SELECT * FROM ". $tbl_name ." WHERE taxonomy = %s", 
										  	'store_locator_category'
										  ));
		
								$specialitynew_65a= get_post_meta( $physioId, "store_locator_gender_category", false); 
								$needle    = ',';
   
									 ?>
								
									
								<p ><strong>Please Select at least one speciality<span class="text-danger required">*</span></strong><br><em>  - please select the gender that the physio identify's with or that most represents the physio</em></p><br>

									
										
										<?php
		
		echo "
		<div style='width:100%;'>
<div   style='width:50%; text-align:left; float:left; padding-bottom:20px;'><strong>Speciality</strong></div>

<div   style='width:50%; text-align:left; float:left; padding-bottom:20px;'><strong>Gender of the Physio available for this Speciality</strong></div></div>";
			
									//		foreach ( $catIDs as $catID ) {
										//		$termID = $catID->term_id;
										//		$termName = $wpdb->get_row($wpdb->prepare(
									//			  	"SELECT * FROM "  . $wpdb->prefix . 'terms' . " WHERE term_id = %d  ",
									//			  	 $termID
									//			  ));
												
?>
										
									 <?php
								
									
						
										
								
								
								foreach ( $catIDs as $catID ) :
											$catInfo = get_term_by( 'id', $catID->term_id, 'store_locator_category' );
								
								
									
								
								  
								  foreach ( $specialitynew_65a as $item) :	 
$specialitynew_65a_selected =  strstr( $item, $needle, true );   
$specialitynew_65a_selected_cat =   substr(strrchr( $item, $needle), 1 );  // think i need to make these arrays so that I can output it later
								
								if ($specialitynew_65a_selected_cat == $catInfo->name) { 
											?>
								<div   style='width:50%; text-align:left; float:left; padding-bottom:0px;'>	
									
								
									
									
											<option class="form-control" id="inputGenderCategory" name="inputGenderCategory"  readonly="readonly" value="<?php echo $catInfo->name; ?>"><?php echo $catInfo->name; ?></option>
									 
								</div>
											
											<div   style='width:50%; text-align:left; float:left;padding-bottom:0px;'>
												
									<?php 
	
	// only outputting last gender need to make an array from above ?>
									<select id="inputGender" name="inputGender[]" class="form-control"> 
										
										<option value="none,<?php echo $catInfo->term_id; ?>,<?php echo $catInfo->name; ?>" >Choose...</option>
									<?php

							
					              $allGender = $wpdb->get_results("SELECT gender FROM store_locator_gender WHERE id=1 OR id=2 OR id=6");
					                   $selectedGender = "";
													
					                     foreach ($allGender as $gender ) {
											
					                            ?>
					                           <option value="<?php echo $gender->gender; ?>,<?php echo $catInfo->term_id; ?>,<?php echo $catInfo->name; ?>" <?= ($specialitynew_65a_selected == $gender->gender) ? 'selected' : '' ;  ?>><?php echo $gender->gender; ?></option>

					                            <?php

										 }
									
									
									if ($specialitynew_65a_selected !== 'none') { 
					                    ?>
											<option value="none,<?php echo $catInfo->term_id; ?>,<?php echo $catInfo->name; ?>" >Remove</option>
											 <?php
								} ?>
									</select>
							    		
									
								</div>
							
								
								
								  <?php
								}
							
					
						endforeach;
									endforeach;
									?>
									
							
								
									
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