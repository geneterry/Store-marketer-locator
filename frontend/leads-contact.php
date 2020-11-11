<?php 

add_shortcode( 'show_contact_form', 'show_contact_form_func' );

	function show_contact_form_func(){
		global $wpdb;
		ob_start();
	?>
		<?php 

			if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
				//do_action('sendGlobalEnquiries');
	        }
		 ?>
		<section class="book-form-contact">
			<div class="container">
				<h2>BOOK AN APPOINTMENT WITH US.</h2>
				<p>Please arrange your telephone consultation by completing the form below. The cost for a Telephone Consultation is £25 for 30	minutes. A registered Physiotherapist will call you within 24 hours of payment being made.
				</p>
				<form action="" id="book_form_contact" method="POST">
					<div class="row pt-3">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control" id="clientName" name="clientName" value="" placeholder="Name">
							</div>
							<div class="form-group">
								<textarea class="form-control" name="clientAddress" id="clientAddress" rows="2" placeholder="City"></textarea>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="clientTel" name="clientTel" value="" placeholder="Tel">
							</div>
							<div class="form-group">
								<input type="email" class="form-control" id="clientEmail" value="" name="clientEmail" placeholder="Email">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="clientTimeCall" name="clientTimeCall" placeholder="Best time to call">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<textarea class="form-control" name="clientOtherContact" id="clientOtherContact" rows="2" placeholder="Best person to contact other than above (optional)"></textarea>
							</div>
							<div class="form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="serviceType" id="serviceType0" value="Attending Clinic" checked>
									<label class="form-check-label" for="serviceType0">
										Attending Clinic
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="serviceType" id="serviceType1" value="Home Visit">
									<label class="form-check-label" for="serviceType1">
										Home Visit
									</label>
								</div>
							</div>
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
											<option value="<?php echo $catInfo->name; ?>"><?php echo $catInfo->name; ?></option>
									    <?php
										endforeach;
									?>
								</select>
							</div>
							<div class="form-group">
								<select id="clientGender" name="clientGender" class="form-control">
									<option>Gender</option>
									<option>Male</option>
									<option>Female</option>
									<option>Both</option>
								</select>
							</div>	
							<!-- <div class="form-group">
								<textarea class="form-control" name="clientProblem" id="clientProblem" rows="4" placeholder="Present your problem ..."></textarea>
							</div> -->
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

add_action('wp_footer', 'physio_consultation_add_modal_to_footer_func');
function physio_consultation_add_modal_to_footer_func(){
	global $post;
		if( is_page() ){
			
			if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'show_contact_form') ) {


				echo '
			    <!-- Modal -->
			<div class="modal fade" id="consultation_payment" tabindex="-1" role="dialog" aria-labelledby="consultation_payment" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="consultation_text">Client Details</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <div class="client-name"><p></p></div>
			        <div class="client-address"><p></p></div>
			        <div class="client-email"><p></p></div>
			        <div class="client-tel"><p></p></div>
			        <div class="client-gender"><p></p></div>
			        <div class="client-category"><p></p></div>
			        <div class="client-time-call"><p></p></div>
			        <div class="client-other"><p></p></div>
			        <div class="client-problem"><p></p></div>
			      </div>
			      <div class="modal-footer">
				  
			        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:window.location.reload()">Close</button>
			      </div>
			    </div>
			  </div>
			</div>
			';
		}
	}
}

 ?>