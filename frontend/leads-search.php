<?php 

	add_shortcode( 'search_for_physio', 'search_for_physio_func' );
	function search_for_physio_func(){

		global $wpdb;

	    if ( isset( $_GET['speciality_id'] )  ):
	        
	        $categoryId = $_GET['speciality_id'];
	        $location = $_GET['physio_location'];

	        $args = array(
				'posts_per_page'   => 10,
				'tax_query' => array(
				    array(
				      'taxonomy' => 'store_locator_category',
				      'field' => 'id',
				      'terms' => $categoryId,
				      'operator' => 'IN',
				    )
				),
				'orderby'          => 'date',
				'order'            => 'DESC',
				'post_type'        => 'store_locator',
			);
			
			$theTerm = get_term( $categoryId, 'store_locator_category' );

			$theTable = $wpdb->prefix . 'postmeta';

			$getData = $wpdb->get_results( "SELECT post_id FROM $theTable WHERE meta_value LIKE '%" . $theTerm->name . "%'" , true);


			//$getData = get_posts( $args );

			
			if ( !empty($getData) ):
				

			?>
			<div class="container pt-2 pb-3">
				<h2>Physiotherapist who specialized in <?= $theTerm->name; ?></h2>
			</div>
			<?php
	        foreach( $getData as $data ):
	        	$theTitle = get_the_title( $data->post_id );
	        	$physio_id = $data->post_id;
	        	$post_status = get_post_status( $data->post_id );
	        	$post_type = get_post_type( $data->post_id );
	        	$physio_url = home_url() . '/leads-info/?physio_id=' . $physio_id;

	        	$store_city = get_post_meta( $physio_id, 'store_locator_city', true );

	        	$media = get_attached_media('image', $physio_id, false);
		        $mediaId = '';
				foreach ($media as $img) {
					$mediaId = $img->ID;
				}
				$mediaImg = wp_get_attachment_url( $mediaId );

				if ( $post_status == 'publish' && $post_type == 'store_locator' && preg_match("/". $location ."/i",$store_city) !== 0 ) :
	        	?>	
					
					<div class="card mb-3">
					  <div class="row no-gutters">
					    <div class="col-md-2">
					      <a href="<?= $physio_url ?>"><img class="card-img-top" src="<?= ( !empty( $mediaImg ) ) ? $mediaImg : 'https://dummyimage.com/360x300/000/fff'; ?>" alt=""></a>
					    </div>
					    <div class="col-md-10">
					      <div class="card-body">
					        <h5 class="card-title"><?= $theTitle; ?></h5>

					        <p class="card-text"><?= get_post_meta( $physio_id, 'store_locator_address', true) . ', ' . get_post_meta($physio_id,'store_locator_city',true). ', ' . get_post_meta($physio_id,'store_locator_state',true) . ' ' . get_post_meta($physio_id,'store_locator_zipcode',true) ?></p>

					        <p class="card-text"><a href="<?= $physio_url ?>">Go to Profile</a></p>
					      </div>
					    </div>
					  </div>
					</div>

	        	<?php
				endif;
	        endforeach;

    	else:
    		?>
    		<div class="container pt-2 pb-3">
				<h2>No Physiotherapist Found.</h2>
			</div>
    		<?php
    	endif;

        endif;
	}

 ?>