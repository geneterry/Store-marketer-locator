<?php 
	
	add_shortcode('leadsDashboard', 'leads_dashboard');

	function leads_dashboard() {
		global $wpdb;


		if( !is_user_logged_in() ){
			redirect_me_to( home_url() . '/login' );
		}

		if(is_user_logged_in()){
			$userId = get_current_user_id();
			//print_r($userId);
			$user_info = get_userdata($userId);

	  		$user_roles = $user_info->roles[0];
			//print_r(wp_get_current_user());

			if($user_roles == 'physiobrite_leads'){

				$leads_user = $user_info->user_login;
				$leads_pass = $user_info->user_pass;
				$first_name = $user_info->first_name;
				$last_name = $user_info->last_name;
				$user_email = $user_info->user_email;
	  			$user_tel = get_user_meta($userId, 'tel', true);
	  			$user_credits = get_user_meta($userId, 'credits', true);

	  			//leads ID
	  			$physioId = (!empty($userId)) ? get_user_meta($userId, 'leads_id', true) : '' ;

			}else{
				swal_message( 'error', 'Error!', 'Please login as a Service Provider to access this page', true, home_url() . '/login' );
			}

        $physio_name = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_name', true) : '' ;

		$physio_id = (!empty($physioId)) ? get_post_meta($physioId, 'physio_id', true) : '' ;

		$physio_email = $user_info->user_email;

		$physio_phone = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_phone', true) : '' ;

		$physio_website = (!empty($user_info)) ? $user_info->user_url : '' ;

		$physio_desc = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_description', true) : '' ;

		$physio_gender = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_gender', true) : '' ;

		$physio_category = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_category', true) : '' ;

		$physio_address = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_address', true) : '' ;

		$physio_country = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_country', true) : '' ;

		$physio_state = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_state', true) : '' ;

		$physio_city = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_city', true) : '' ;

		$physio_code = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_zipcode', true) : '' ;

		$physio_days = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_days', true) : '' ;

		$physio_profile = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_website', true) : '' ;

		$physio_profile = (!empty($physioId)) ? get_post_meta($physioId, 'store_locator_website', true) : '' ;

		$physio_is_promoted = (!empty($userId)) ? get_user_meta($userId, 'is_promoted', true) : '' ;


		$media = get_attached_media('image', $physioId, false);
        $mediaId = '';
		foreach ($media as $img) {
			$mediaId = $img->ID;
		}
		$mediaImg = wp_get_attachment_url( $mediaId );
		}
	?>
	<!-- <script src="https://www.paypal.com/sdk/js?client-id=sb"></script>
<script>paypal.Buttons().render('.dash-section');</script> -->
	<section class="dash-section mb-5 mt-5 pt-4 pb-4">
		<div class="container">
			<div class="row">
				<div class="col-md-2 px-0">
					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link active" id="physio-dashboard-tab" data-toggle="pill" href="#physio-dashboard" role="tab" aria-controls="physio-dashboard" aria-selected="true"><i class="fas fa-tachometer-alt"></i> <p>Dashboard</p></a>
						<a class="nav-link" id="global-physio-enquiry-tab" data-toggle="pill" href="#global-physio-enquiry" role="tab" aria-controls="global-physio-enquiry" aria-selected="false"><i class="far fa-list-alt"></i> <p>Global Enquiry List</p></a>
						<a class="nav-link" id="local-physio-enquiry-tab" data-toggle="pill" href="#local-physio-enquiry" role="tab" aria-controls="local-physio-enquiry" aria-selected="false"><i class="far far fa-star"></i> <p>My Enquiry List</p></a>
						<a class="nav-link" id="physio-account-tab" data-toggle="pill" href="#physio-account" role="tab" aria-controls="physio-account" aria-selected="false"><i class="fas fa-user-circle"></i> <p>Account Details</p></a>
						<a class="nav-link" id="physio-testimonial-tab" data-toggle="pill" href="#physio-testimonial" role="tab" aria-controls="physio-testimonial" aria-selected="false"><i class="fas fa-quote-left"></i> <p>Testimonial</p> </a>
						<?php 
							if (is_user_logged_in()) {
						         echo '<a class="nav-link" href="'. wp_logout_url() .'"><i class="fas fa-sign-out-alt"></i> <p>'. __("Logout") .'</p></a>';
						      }
						 ?>
						
					</div>
				</div>
				<div class="col-md-10">
					<div class="tab-content" id="v-pills-tabContent">
						<div class="tab-pane fade show active" id="physio-dashboard" role="tabpanel" aria-labelledby="physio-dashboard-tab">
							<?php include( plugin_dir_path( __FILE__ ) . 'dashboard-parts/dashboard-part.php'); ?>
						</div>
						<div class="tab-pane fade" id="global-physio-enquiry" role="tabpanel" aria-labelledby="global-physio-enquiry-tab">
							<?php include( plugin_dir_path( __FILE__ ) . 'dashboard-parts/global-enquiry-part.php'); ?>
						</div>
						<div class="tab-pane fade" id="local-physio-enquiry" role="tabpanel" aria-labelledby="local-physio-enquiry-tab">
							<?php include( plugin_dir_path( __FILE__ ) . 'dashboard-parts/enquiry-part.php'); ?>
						</div>
						<div class="tab-pane fade" id="physio-account" role="tabpanel" aria-labelledby="physio-account-tab">
							<?php include( plugin_dir_path( __FILE__ ) . 'dashboard-parts/account-part.php'); ?>
						</div>
						<div class="tab-pane fade" id="physio-testimonial" role="tabpanel" aria-labelledby="physio-testimonial-tab">
							<?php include( plugin_dir_path( __FILE__ ) . 'dashboard-parts/testimonial-part.php'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	}
add_action('wp_footer', 'physio_add_modal_to_footer_func');
function physio_add_modal_to_footer_func(){
	global $post;
		if( is_page() ){

			if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'leadsDashboard') ) {


				echo '
			    <!-- Modal -->
			<div class="modal fade" id="enquiryInfo" tabindex="-1" role="dialog" aria-labelledby="enquiryInfo" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalCenterTitle">Client Details</h5>
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
			        <div class="service-type"><p></p></div>
			        <!--div class="client-problem"><p></p></div-->
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="testimonialInfo" tabindex="-1" role="dialog" aria-labelledby="testimonialInfo" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="testimonialInfos">Testimonial Details</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <div class="testimonial-name"><p></p></div>
			        <div class="testimonial-job-title"><p></p></div>
			        <div class="testimonial-company"><p></p></div>
			        <div class="testimonial-content"><p></p></div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>';
					echo '
					    <!-- Modal -->
					<div class="modal fade" id="upgradeAccountInfo" tabindex="-1" role="dialog" aria-labelledby="upgradeAccountInfo" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title">Upgrade your Account</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					      	<p>Upgrade your account now and become one of <span class="font-weight-bold">the Three Featured Physiotherapist</span> in your City!</p>
					      	<p>You will also enjoy this awesome account add-ons:</p>
					      	<div class="mt-2">
					      		<p class="d-block"><i class="fa fa-check"></i> Lorem Ipsum</p>
					      		<p class="d-block"><i class="fa fa-check"></i> Lorem Ipsum</p>
					      		<p class="d-block"><i class="fa fa-check"></i> Lorem Ipsum</p>
					      		<p class="d-block"><i class="fa fa-check"></i> Lorem Ipsum</p>
					      	</div>
					      	<div class="mt-3"></div>
					      	<div class="row justify-content-center align-items-center">
					      		<div class="col-md-6">
					      			<h3>Pay now with:</h3>
					      		</div>
					      		<div class="col-md-6">
					        		<div id="paypal_button_container">
					      		</div>
					      	</div>
				
							</div>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					      </div>
					    </div>
					  </div>
					</div>';
			}
			
		}
}

 ?>