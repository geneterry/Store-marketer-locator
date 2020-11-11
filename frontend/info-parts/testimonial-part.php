<section class="testimonial">
	<div class="container">
		<h2>TESTIMONIALS</h2>
		<div class="card-deck pt-3">
		<?php 
			$tbl_name = $wpdb->prefix . 'physiobrite_testimonial';
	        $testimonials = $wpdb->get_results($wpdb->prepare(
	                    "SELECT * FROM ". $tbl_name ." WHERE physio_id = %d ORDER BY id DESC LIMIT 4", 
	                    $store_id
	                  ));
	        if ($testimonials) :
	        	$testimonialsData = $testimonials;

		        foreach ( $testimonialsData as $dataTestimonials ):
		 	?>

		 	<div class="card col-md-3">
				<div class="card-body">
					<h5 class="card-title text-center"><?= $dataTestimonials->client_name ?></h5>
					<h6 class="card-subtitle mb-2 text-muted text-center"><?= $dataTestimonials->client_job ?><?= (!empty($dataTestimonials->client_company) ? ', ' . $dataTestimonials->client_company : '' ); ?></h6>
					<div class="text-center">
						<i class="fa fa-quote-left testimonial-icon"></i>
					</div>
					<p class="card-text font-italic quote">
						<?php
						// strip tags to avoid breaking any html
						$string = strip_tags($dataTestimonials->client_content);
						if (strlen($string) > 200) {

						    // truncate string
						    $stringCut = substr($string, 0, 200);
						    $endPoint = strrpos($stringCut, ' ');

						    //if the string doesn't contain any space then it will cut without word basis.
						    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
						    $string .= '...<br><a href="javascript:void(0);" style="display:block;" data-text="'. $dataTestimonials->client_content .'" class="btn btn-primary btn-sm read-more mt-3">Read More</a>';
						}
						echo $string;
												
						?>
					</p>
				</div>
			</div>
		    
		    <?php    	
		        endforeach;
	        endif;
	        ?>
		</div>
		<div class="text-center">
			<button id="add_new_testimonial" class="btn btn-primary mb-2 mt-5<?= (!empty($userCurrentRole) && $userCurrentRole == 'physiobrite_leads') ? ' d-none' : ''; ?>" >Tell us what you think</button>
		</div>
	</div>
</section>
