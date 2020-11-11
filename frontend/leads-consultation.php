<?php 
add_shortcode( 'show_consultation_form', 'show_consultation_form_func' );

	function show_consultation_form_func(){
		global $wpdb;
		ob_start();
	?>
		<?php 

			if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
				do_action('sendGlobalEnquiries');
	        }
		 ?>
		<section class="book-form-enquiries">
			<div class="container">
				<!--h2>BOOK AN APPOINTMENT WITH US.</h2>
				<p>Please arrange your telephone consultation by completing the form below. The cost for a Telephone Consultation is £25 for 30	minutes. A registered Physiotherapist will call you within 24 hours of payment being made.
				</p-->
				<form action="" id="book_form_enquiries" method="POST">
					<div class="row pt-3">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control" id="clientName" name="clientName" value="" placeholder="Name">
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
								<input type="hidden" name="lead_type" id="lead_type" value="Lead" />
								<input type="hidden" name="lead_type1" id="lead_type1" value="Global" />
								<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
	<?php
		return ob_get_clean();
	}
?>