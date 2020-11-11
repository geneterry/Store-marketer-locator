<?php 
$physio_id = get_current_user_id();

					if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
						do_action('sendGlobalEnquiries');
			        }
				 ?>
				<section class="book-form">
					<div class="container">
						<h2 class="pb-2">BOOK AN APPOINTMENT FOR YOUR CLIENT</h2>
						<span>Please arrange your referral by completing the form below for your client.</span>
						<form action="" id="book_form" method="POST">
							<div class="row pt-3">
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control" id="clientName" name="clientName" value="" placeholder="Name of Client">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="clientAddress" id="clientAddress" rows="2" placeholder="Postcode"></textarea>
									</div>
									<div class="form-group">
										<input type="text" class="form-control" id="clientTel" name="clientTel" value="" placeholder="Telephone Number">
									</div>
									<div class="form-group">
										<input type="email" class="form-control" id="clientEmail" value="" name="clientEmail" placeholder="Email">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" id="clientTimeCall" name="clientTimeCall" placeholder="Best time to call">
									</div>
								
									<div class="form-group">
										<textarea class="form-control" name="clientOtherContact" id="clientOtherContact" rows="2" placeholder="Best person to contact other than above (optional)"></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											$tbl_name = $wpdb->prefix . 'term_taxonomy';

											$catIDs = $wpdb->get_results($wpdb->prepare(
											  	"SELECT * FROM ". $tbl_name ." WHERE taxonomy = %s", 
											  	'store_locator_category'
											  ));
										 ?> 
										<select id="physioCat" name="physioCat" class="form-control">
											<option>Speciality</option>
											<?php

										foreach ( $catIDs as $catID ) :
											$catInfo = get_term_by( 'id', $catID->term_id, 'store_locator_category' );
											?>
										<option value=",<?php echo $catInfo->term_id; ?>,<?php echo $catInfo->name; ?>"><?php echo $catInfo->name; ?></option>
									    <?php
										endforeach;
									?>ndforeach;
											?>
										</select>
									</div>
									<div class="form-group">
								<select id="clientGender" name="clientGender" class="form-control">
									<option>Gender Of Physio Required</option>
									<option>Male</option>
									<option>Female</option>
								<option value="Both">Either</option>
								</select>
							</div>
							
											<div class="form-group">
							
								
								
								


<select id="clientype" name="clientype" class="form-control">
    <option hidden>Type Of Treatment Payment</option>
    <option value="Self Funded">Self Funded</option>
    <option value="Private Insurance">Private Insurance</option>
</select>
								
<div id="otherName"  />
    <input type="text" id="otherName" name="otherName" class="form-control" placeholder="Enter the name of your Insurance Provider"/>
</div>
										<div class="form-group">
								<div style="padding-left: 25px; padding-top:30px">
											
											<input class="form-check-input" type="radio" name="serviceType" id="serviceType1" value="Clinic Appointments and Home Visit Appointments" checked>
											<label class="form-check-label" for="serviceType1">
												Clinic Appointments or Home Visit Appointments
											</label>
										<br>
											
											
											
											<input class="form-check-input" type="radio" name="serviceType" id="serviceType0" value="Clinic Appointments Only" >
											<label class="form-check-label" for="serviceType0">
												Clinic Appointments
											</label>
									<br>
										
											<input class="form-check-input" type="radio" name="serviceType" id="serviceType1" value="Home Visit Appointments Only">
											<label class="form-check-label" for="serviceType1">
												Home Visit Appointments 
											</label>
										
											
											
									</div>
							</div>
									<!--div class="form-group">
										<textarea class="form-control" name="clientProblem" id="clientProblem" rows="4" placeholder="Present your problem (Issue in 100 words) ..."></textarea>
									</div-->
									<div class="button-container text-right">
										<div class="form-group mt-1">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck">
												<label class="form-check-label" for="gridCheck">
												<strong>Agree to <a href="/terms-and-conditions/">Terms of Condition</a></strong>
												</label>
											</div>
										</div>
										<button type="submit" class="btn btn-primary mb-2">SUBMIT</button>
										<input type="hidden" name="submit" id="submitted" value="true" />
										<input type="hidden" name="physioId" id="physioId" value="<?= $physio_id; ?>" />
										<input type="hidden" name="lead_type" id="lead_type" value="referrerLead" />
										<input type="hidden" name="lead_type1" id="lead_type1" value="Referrer" />
										<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
									</div>
								</div>
							</div>
						</form>
					</div>
				</section>

				<section class="map-address pt-2">
					<input type="hidden" value="" name="store_locator_lat" id="store_locator_lat"/>
				    <input type="hidden" value="" name="store_locator_lng" id="store_locator_lng"/>
				    <div id="map-container" style="position: relative;">
				        <div id="map_loader" style="z-index: 9;width: 100%; height: 250px;position: absolute;background-color: #fff;"><div class="uil-ripple-css" style="transform: scale(0.6); margin-left: auto; margin-right: auto;"><div></div><div></div></div></div>
				        <div id="map-canvas" style="height: 250px;width: 100%;"></div>
				    </div>
				</section>
				    <script>
				        jQuery(document).ready(function (jQuery) {
				            store_locator_initializeMapBackend();
				        });
				    </script>