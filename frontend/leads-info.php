<?php 

	add_shortcode('leadsInfo', 'leads_info');
	function leads_info(){
		include_once plugin_dir_path( __FILE__ ).'function.php';
		global $wpdb;

	  	$store_id = '';$physio_name = '';$physio_id = '';$physio_user_data = '';$physio_email = '';$physio_phone = '';$physio_website = '';$physio_gender = '';$physio_category = '';$physio_address = '';$physio_country = '';$physio_state = '';$physio_city = '';$physio_code = '';$physio_days = '';$physio_content = '';$media = '';$mediaId = '';


			if ( isset( $_GET['physio_id'] ) ) {

	            $store_id = $_GET['physio_id'];

	        }else{
	        	redirect_me_to(home_url());
	        }
	        $userCurrentRole = '';
	        if( is_user_logged_in() ){

		        $userCurrent = get_current_user_id();
		        $userCurrentRole = get_userdata($userCurrent);
		        $userCurrentRole = $userCurrentRole->roles[0];
	        	
	        }

	        $physio_name = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_name', true) : '' ;

			$physio_id = (!empty($store_id)) ? get_post_meta($store_id, 'physio_id', true) : '' ;

			$physio_user_data = get_userdata($physio_id);

			$physio_email = (!empty( $physio_user_data->user_email ) ) ? $physio_user_data->user_email : '' ;

			$physio_phone = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_phone', true) : '' ;

			$physio_website = (!empty($physio_user_data)) ? $physio_user_data->user_url : '' ;

			$physio_gender = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_gender_main', true) : '' ;

$physio_service= (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_service_type', true) : '' ;
	
		
		
		$physio_category_65 = get_post_meta($store_id, 'store_locator_gender_category') ;
	

			$physio_address = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_address', true) : '' ;
      

			$physio_country = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_country', true) : '' ;

			$physio_state = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_state', true) : '' ;

			$physio_city = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_city', true) : '' ;

			$physio_code = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_zipcode', true) : '' ;

			$physio_days = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_days', true) : '' ;

			$physio_content = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_description', true ) : '' ;

			$physio_staff_info = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_staff_members', true ) : '' ;

			$media = get_attached_media('image', $store_id, false);
	        $mediaId = '';
			foreach ($media as $img) {
				$mediaId = $img->ID;
			}
			$mediaImg = wp_get_attachment_url( $mediaId );
		 ?>

<?php 

			if ( isset( $_POST['submit'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
				do_action('sendEnquiries');
	        }
		 ?>
		<div class="container pt-3 mt-3">
			<div class="row">
				<div class="col-md-12 col-lg-3">
				<div class="avatar-upload">
				        <div class="avatar-preview w-100">
				            <div id="imagePreview" style="background-image: url(<?= ( !empty( $mediaImg ) ) ? $mediaImg : 'https://dummyimage.com/360x360/000/fff'; ?>);">
				            </div>
				        </div>
				    </div>
				</div>
				</div>
				<div class="row">
			
				<div class="col-md-12 col-lg-9 profile-info-column">
					<div class="form-row">
						<div class="form-group col-md-6">
							<div class="borderblue">
							<h2 class="mb-2">PROFILE INFO</h2>
							<label><strong>Name: </strong><?= $physio_name; ?></label><br/>
							
								
							
							<div class="gender-icons">
					<label><strong>Gender: </strong></label><br/>
					<i class="fas fa-male position-relative<?= ( $physio_gender == 'Male') ? ' active' : '' ?>"></i>
					<i class="fas fa-female position-relative<?= ( $physio_gender == 'Female' ) ? ' active' : '' ?>"></i><br/><br/>
				</div>
							<label><strong>Address: </strong><?= $physio_address . ', ' . $physio_city . ', ' . $physio_country . ', ' . $physio_code; ?></label><br/>
								
							
										<hr>
								
									<div class="staff-info mb-3">
							
										<label><strong>Service Type: </strong><?= $physio_service; ?></label><br/>
									
									
							</div>
						<hr>
								
									<div class="staff-info mb-3">
								<label><strong>Working Hours: </strong>
									<td>
										<table id="store_locator_hours">
											<?php if ( !empty($physio_days) ): ?>
												<?php foreach ($physio_days as $day => $value): 
													if($value['status'] == 1):
													?>
														<tr>
															<td class="profile-days " style="text-align: left; padding: 0;"><?php echo $day; ?></td>
															<td class="profile-times" style="text-align: left; padding: 0 10px;">
															    <strong><?php echo $value['start']; ?> - <?php echo $value['end'] ?></strong>
															</td>
														</tr>
													<?php 
													endif;
												endforeach; ?>
											<?php endif ?>
										</table>
									</td>
									
									
							</div>
						<hr>
							

							<div class="staff-info mb-6">
								<label><strong>Staff Members: </strong></label><br>
									<?php 
									if ( $physio_staff_info !== "" ) {
										foreach( $physio_staff_info as $value ){
											$staff_data = explode(',', $value);
											if ( isset( $staff_data[0] ) ){
						if ($staff_data[3] == 1){
													$staff_name_gender_cat = '1';
												}
											
											else if ($staff_data[3] == 2){
													$staff_name_gender_cat = '2';
												}
													?>
													
													<div style="width: 50%; float: left"><p class="leads-info"><?php	echo $staff_data[0] ?> </p></div>
					<div class="gender-icons"><i class="fas fa-male position-relative<?= ( $staff_name_gender_cat == '1')   ? ' active' : '' ?>"></i>
					<i class="fas fa-female position-relative<?= ( $staff_name_gender_cat == '2')  ? ' active' : '' ?>"></i><br/><br/>
				</div>
											
										
								
									 
							
							
												
											<?php	
												
												
											}
										}
									}
									 ?>
							</div>
							

					<!--<div class="social-share">
								<label><strong>Share: </strong>
									<br/>
								<a href="http://www.facebook.com/sharer.php?u=<?//= home_url().'/leads-info/?physio_id='. $store_id ?>" target="_blank" class="fa fa-facebook">
								</a>
								<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?//= home_url().'/leads-info/?physio_id='. $store_id ?>" target="_blank" class="fa fa-linkedin"></a>

								<a href="http://vkontakte.ru/share.php?url=<?//= home_url().'/leads-info/?physio_id='. $store_id ?>" target="_blank" class="fa fa-twitter"></a>
							</div>!-->

	</div> 
						</div> 
						<div class="form-group col-md-6">
							<div class="borderblue">
								<h2 class="mb-2"><?php echo __("Specialities", 'wpmsl'); ?></h2>
									<label><strong>Specialities and gender of the physio available for that speciality: </strong></label><br/><br/>
								<?php $needle    = ',';
		
		foreach ($physio_category_65 as $physio_category_65_array) :
		
		$specialitygenderarray =  strstr( $physio_category_65_array, $needle, true );   
$specialitycatarray =   substr(strrchr( $physio_category_65_array, $needle), 1 ); 				
		
		if ( $specialitygenderarray !== 'none') {?><div style="width: 50%; float: left"><p class="leads-info" style="color: #2d6287"><?php	echo $specialitycatarray; ?> <?php 
											if  ($specialitygenderarray == 'Male') 
											{$gendericoncategory_65 = '1';}
												else if ($specialitygenderarray == 'Female') 
											{$gendericoncategory_65 = '2';}
											else if ($specialitygenderarray == 'Both') 
											{$gendericoncategory_65 = '3';} ?></p></div>
					<div class="gender-icons"><i class="fas fa-male position-relative<?= ( $gendericoncategory_65 == '1') || ( $gendericoncategory_65 == '3')  ? ' active' : '' ?>"></i>
					<i class="fas fa-female position-relative<?= ( $gendericoncategory_65 == '2') || ( $gendericoncategory_65 == '3') ? ' active' : '' ?>"></i><br/><br/>
				</div><?php;}
		
		endforeach;
		?>
							
						</div>
							</div>
							
					</div>
						<div class="claim-info">
							*This information has been provided by the individual practice/physio and has not been independently checked.
						</div>	
</div>
					<div class="form-row">
							<input type="hidden" name="store_locator_address" class="form-control" id="store_locator_address" value="<?= $physio_address ?>"/>

							<select id="store_locator_country" name="store_locator_country"  class="form-control d-none">
								<option value="<?= $physio_country; ?>" selected><?= $physio_country; ?></option>
							</select>

							<select id="store_locator_state" name="store_locator_state" class="form-control d-none">
								<option value="<?= $physio_state; ?>" selected><?= $physio_state; ?></option>
							</select>

							<input type="hidden" name="store_locator_city" value="<?= $physio_city; ?>" class="form-control" id="store_locator_city"/>

							<input type="hidden" class="form-control" name="store_locator_zipcode" value="<?= $physio_code; ?>" id="store_locator_zipcode"/>
						
					</div>
				</div>
			</div>
		</div>
			
 
			
				<section class="book-form">
					<div class="container">
						<h2 class="pb-2">BOOK AN APPOINTMENT WITH US</h2>
						<span>Please arrange your telephone consultation by completing the form below.</span>
						<form action="" id="book_form" method="POST">
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
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<textarea class="form-control" name="clientOtherContact" id="clientOtherContact" rows="2" placeholder="Best person to contact other than above (optional)"></textarea>
									</div>
								
									<div class="form-group">
											<select id="physioCat" name="physioCat" class="form-control">
											<option>Speciality And Gender Of The Physio Available</option>
										<?php 
					$needle    = ',';
										

			
									$catIDs65_test = get_post_meta( $store_id, "store_locator_gender_category" ); 
								
		
		
		foreach ($catIDs65_test as $catIDs65gender_test_array) :
			
				$catIDs65gender_test = strstr($catIDs65gender_test_array, $needle, true); 
				$catIDs65cat_test = substr(strrchr($catIDs65gender_test_array, $needle), 1 ) ;
		
		if ($catIDs65gender_test == 'Both'){
			
			$catIDs65both = $catIDs65cat_test . ' - Both' ;
		}
			
		else if ($catIDs65gender_test == 'Male'){
			
			$catIDs65both = $catIDs65cat_test . ' - Male' ;
		}
		
		else if ($catIDs65gender_test == 'Female'){
			
			$catIDs65both = $catIDs65cat_test . ' - Female' ;
		}
		
		else if ($catIDs65gender_test == 'none'){
			
			$catIDs65both = "";
		}
		
		$catIDs65both_array = array($catIDs65both);
		
	foreach ($catIDs65both_array as $catIDs65both_value) :
		
		if ($catIDs65both_value !== ""){
			
	?> 
		
         <option value="<?php echo $catIDs65gender_test_array; ?>" ><?php echo $catIDs65both; ?></option>
   <?php }
		endforeach;				
		endforeach;	?> 	</select>
										
										<div id="otherNameinfo"  />
    <select id="otherNameinfo" name="otherNameinfo" class="form-control">
    <option value="none" hidden>Gender of Physio Preferred</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
		 <option value="Both">Either</option>
</select>
</div>
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
										
											<div style="padding-left: 25px; padding-top:30px;">
											<?php 
											if ($physio_service == 'Clinic Appointments Only') {
											
											?>
											<input class="form-check-input" type="radio" name="serviceType" id="serviceType0" value="Clinic Appointments Only" checked>
											<label class="form-check-label" for="serviceType0">
												Clinic Appointments Only
											</label>
										<?php ; }?>
											<?php 
											if ($physio_service == 'Home Visit Appointments Only') {
											
											?>
										
											<input class="form-check-input" type="radio" name="serviceType" id="serviceType1" value="Home Visit Appointments Only" checked>
											<label class="form-check-label" for="serviceType1">
												Home Visit Appointments Only
											</label>
										<?php ; }?>
											<?php 
											if ($physio_service == 'Clinic Appointments and Home Visit Appointments') {
												?>

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
										
											<?php ; }?>	
											
									</div></div>
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
										<input type="hidden" name="lead_type" id="lead_type" value="Lead" />
										<input type="hidden" name="lead_type1" id="lead_type1" value="Enquiry" />
										<input type="hidden" name="is_viewed" id="is_viewed" value="1" />
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

<?php
	}
	
add_filter('the_title', 'info_title_func');
function info_title_func($title) {
   	$store_id = '';
	$physio_name = '';
    if (is_page('leads-info') && !in_the_loop()) {
        if ( isset( $_GET['physio_id'] ) ) {

			$store_id = $_GET['physio_id'];
			$physio_name = (!empty($store_id)) ? get_post_meta($store_id, 'store_locator_name', true) : '' ;
	    	return $physio_name;

		}
    }
    return $title;
}
    function wpse309151_remove_title_filter_nav_menu( $nav_menu, $args ) {
        // we are working with menu, so remove the title filter
        remove_filter( 'the_title', 'info_title_func', 10, 2 );
        return $nav_menu;
    }
    // this filter fires just before the nav menu item creation process
    add_filter( 'pre_wp_nav_menu', 'wpse309151_remove_title_filter_nav_menu', 10, 2 );

    function wpse309151_add_title_filter_non_menu( $items, $args ) {
        // we are done working with menu, so add the title filter back
        add_filter( 'the_title', 'info_title_func', 10, 2 );
        return $items;
    }
    // this filter fires after nav menu item creation is done
    add_filter( 'wp_nav_menu_items', 'wpse309151_add_title_filter_non_menu', 10, 2 );

	add_action('wp_footer', 'profile_info_modal_in_footer_func');
	function profile_info_modal_in_footer_func(){
      	global $post;
		
		if ( isset( $_GET['physio_id'] ) ) {

			$store_id = $_GET['physio_id'];

		}
   
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'leadsInfo') ) {

	    	echo '
	        	<!-- Modal -->
	            <div class="modal fade" id="testimonialAdd" tabindex="-1" role="dialog" aria-labelledby="testimonialAdd" aria-hidden="true">
	                <div class="modal-dialog modal-dialog-centered" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title">Add Testimonial</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                            <span aria-hidden="true">&times;</span>
	                        </button>
	                    </div>
	                    <form action="" id="add_testimonial">
	                        <div class="modal-body">
	                            <div class="mt-3 form-group">
	                                <label for="testimonialName">Name</label>
	                                <input type="text" class="form-control" name="testimonialName" id="testimonialName">
	                            </div>
	                            <div class="form-group">
	                                <label for="testimonialCompany">Company (optional)</label>
	                                <input type="text" class="form-control" name="testimonialCompany" id="testimonialCompany">
	                            </div>
	                            <div class="form-group">
	                                <label for="testimonialJobTitle">Job Title</label>
	                                <input type="text" class="form-control" name="testimonialJobTitle" id="testimonialJobTitle">
	                            </div>
	                            <div class="form-group">
	                                <label for="testimonialContent">Comment</label>
	                                <textarea class="form-control" name="testimonialContent" id="testimonialContent" rows="4"></textarea>
	                            </div>
	                        </div>
	                        <div class="modal-footer">
	                            <button type="submit" class="btn btn-primary">Submit</button>
	                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            <input type="hidden" name="testimonialLeadsId" id="testimonialLeadsId" value="'. $store_id . '" />
	                            <input type="hidden" name="submitTestimonial" id="submitTestimonial" value="true" />
	                        </div>
	                    </form>
	                </div>
	                </div>
	            </div>
	            <!-- Modal -->
	            <div class="modal fade" id="testimonialQuote" tabindex="-1" role="dialog" aria-labelledby="testimonialAdd" aria-hidden="true">
	                <div class="modal-dialog modal-dialog-centered" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title">Add Testimonial</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                            <span aria-hidden="true">&times;</span>
	                        </button>
	                    </div>
	                    <form action="" id="add_testimonial">
	                        <div class="modal-body">
	                            <div class="mt-3 form-group">
	                                <label for="testimonialName">Name</label>
	                                <input type="text" class="form-control" name="testimonialName" id="testimonialName">
	                            </div>
	                            <div class="form-group">
	                                <label for="testimonialCompany">Company (optional)</label>
	                                <input type="text" class="form-control" name="testimonialCompany" id="testimonialCompany">
	                            </div>
	                            <div class="form-group">
	                                <label for="testimonialJobTitle">Job Title</label>
	                                <input type="text" class="form-control" name="testimonialJobTitle" id="testimonialJobTitle">
	                            </div>
	                            <div class="form-group">
	                                <label for="testimonialContent">Comment</label>
	                                <textarea class="form-control" name="testimonialContent" id="testimonialContent" rows="4"></textarea>
	                            </div>
	                        </div>
	                        <div class="modal-footer">
	                            <button type="submit" class="btn btn-primary">Submit</button>
	                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            <input type="hidden" name="testimonialLeadsId" id="testimonialLeadsId" value="'. $store_id . '" />
	                            <input type="hidden" name="submitTestimonial" id="submitTestimonial" value="true" />
	                        </div>
	                    </form>
	                </div>
	                </div>
	            </div>
	            <!-- Modal -->
	            <div class="modal fade" id="testimonialQuotes" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-scrollable" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalScrollableTitle"></h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        <h5 class="card-title text-center"></h5>
							<h6 class="card-subtitle mb-2 text-muted text-center"></h6>
							<div class="text-center">
								<i class="fa fa-quote-left testimonial-icon"></i>
							</div>
							<p class="card-text font-italic modal-qoute">
								
							</p>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					      </div>
					    </div>
					  </div>
					</div>
	        ';
            }
    }
?>
