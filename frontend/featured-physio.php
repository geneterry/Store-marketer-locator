<?php 

add_shortcode('show_featured_physio', 'show_featured_physio_func');
function show_featured_physio_func()
{
	global $wpdb;
	$query = array(
				'post_type'		=> 'store_locator',
				'post_status'	=> 'publish',
				'order_by'		=>	'DESC'
			);

	$physio_items = get_posts( $query );
  	$hasPromoted = array();
  	$notPromoted = array();
  	$mergePromoted = array();
  	for($i = 0; $i < count($physio_items); $i++){
      $physio_id = get_post_meta( $physio_items[$i]->ID, 'physio_id', true );
      $is_promoted = get_user_meta( $physio_id, 'is_promoted', true );

      if($is_promoted == 1){
        $hasPromoted[] = $physio_items[$i];
      }else{
        $notPromoted[] = $physio_items[$i];
      }

    }

  	$mergePromoted[] = $hasPromoted;
  	$mergePromoted[] = $notPromoted;
?>
	<section class="featured-physio">
		<div class="text-right mb-3"><a href="#" class="btn btn-primary btn-sm py-2 px-2 featured-button">Be a featured physiotherapist now</a></div>
		<div class="featured-slider">
			
<?php

	if( $physio_items ):
		foreach ($mergePromoted[0] as $physio):
			
			$id = $physio->ID;
			$title =  $physio->post_title;
			$link = get_post_meta( $id, 'store_locator_website', true );

			$media = get_attached_media('image', $id, false);
	        $mediaId = '';
			foreach ($media as $img) {
				$mediaId = $img->ID;
			}
			$mediaImg = wp_get_attachment_url( $mediaId );
			$physio_id = get_post_meta( $id, 'physio_id', true );
			$is_promoted = get_user_meta( $physio_id, 'is_promoted', true );
			?>
			<?php if ( $is_promoted == 1 ): ?>
				<div class="text-center mt-2 pb-3 position-relative">
					<div class="ribbon ribbon-top-right"><span>featured</span></div>
					<div class="card mx-3">
					  <a href="<?= $link ?>"><img class="card-img-top" src="<?= ( !empty( $mediaImg ) ) ? $mediaImg : 'https://dummyimage.com/360x360/000/fff'; ?>" alt=""></a>
					  <div class="card-body">
					    <h3 class="mb-2 pt-2 card-text"><a href="<?= $link ?>"><?php echo $title ?></a></h3>				  
					  </div>
					</div>
				</div>
			<?php endif ?>
	<?php			
		endforeach;
		foreach ($mergePromoted[1] as $physio):
			
			$id = $physio->ID;
			$title =  $physio->post_title;
			$link = get_post_meta( $id, 'store_locator_website', true );

			$media = get_attached_media('image', $id, false);
	        $mediaId = '';
			foreach ($media as $img) {
				$mediaId = $img->ID;
			}
			$mediaImg = wp_get_attachment_url( $mediaId );
			$physio_id = get_post_meta( $id, 'physio_id', true );
			$is_promoted = get_user_meta( $physio_id, 'is_promoted', true );
			?>
			<?php if ( $is_promoted !== 1 ): ?>
				<div class="text-center mt-2 pb-3 position-relative">
					<div class="card mx-3">
					  <a href="<?= $link ?>"><img class="card-img-top" src="<?= ( !empty( $mediaImg ) ) ? $mediaImg : 'https://dummyimage.com/360x360/000/fff'; ?>" alt=""></a>
					  <div class="card-body">
					    <h3 class="mb-2 pt-2 card-text"><a href="<?= $link ?>"><?php echo $title ?></a></h3>				  
					  </div>
					</div>
				</div>
			<?php endif ?>
	<?php			
		endforeach;
  		else:
  		echo '<h3 class="text-center">No Result Found.</h3>';
	endif;

 	?>
 		</div>
	</section>

<?php  } 
add_action('wp_footer', 'physio_add_modal_to_featured_func');
function physio_add_modal_to_featured_func(){
	global $post;
		if( is_page() ){

			if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'show_featured_physio') ) {


				echo '
			    <!-- Modal -->
			<div class="modal fade" id="homeFeatured" tabindex="-1" role="dialog" aria-labelledby="enquiryInfo" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h3 class="modal-title text-center">Become A Featured Physiotherapist Now!</h3>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body text-center">
			        <p><a href="/register" title="Create Your Account" class="font-weight-bold">Create your account</a> now, and become one of the growing list of our Professional Physiotherapist.</p><br/>
                    <p>Already have an account? <a href="/login" class="font-weight-bold">Login to your dashboard</a> and upgrade your account and become one of the Physiobrite Featured Physiotherapist.</p>
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