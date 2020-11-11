<div class="container dashboard_container mt-2">
	<?php 
		$tbl_enquiry = $wpdb->prefix . 'physiobrite_client_enquiries';
		$tbl_globalEnquiry = $wpdb->prefix . 'physiobrite_global_client_enquiries';
  		$featuredInTheCity = get_featured_physio_count($physio_city);
  		$userId = get_current_user_id();
  		$user_info = get_userdata($userId);
	  	$user_roles = $user_info->roles[0];
	 ?>

	<h3 class="text-blue mb-2 mt-2 text-center">Welcome Back <strong><?= (!empty($first_name) || !empty($last_name)) ? $first_name . ' ' . $last_name : '' ;?></strong></h3>


	<?php 
        $myEnquiryCount = count($wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM ". $tbl_enquiry ." WHERE physio_id = %d", 
                    $userId
                  )));
        $currentDateTime = current_time('mysql', 1);
        $globalEnquiryCount = count($wpdb->get_results(
                    "SELECT * FROM ". $tbl_globalEnquiry ." WHERE physio_id IS NULL " //TIMEDIFF('". $currentDateTime ."', date_created) < '23:59:59'"
                  ));
	 ?>


	<div class="container mt-3 dashboard-btn">
		<div class="row justify-content-center">
			<div class="card col-md-5 col-sm-6 mb-2 px-0 mx-1">
				<i class="fas <?= ( $physio_is_promoted == 0 ) ? 'fa-user' : 'fa-user-md' ?>"></i>
				<div class="card-body">
					<h3 class="card-text">
						<input type="hidden" name="current_user_id" value="<?= $userId; ?>">
						<?= ( $physio_is_promoted == 1) ? '<p>Featured User</p>' : '<p class="">Normal User</p>'; ?>
					</h3>
					<?php if ( $physio_is_promoted == 0 && $featuredInTheCity < 3 && $user_roles != 'referrer_leads'  ) : ?>
						<div class="text-center">
							<button class="btn btn-primary btn-sm mt-3" data-city="<?= $physio_city ?>" data-email="<?= $physio_email ?>" data-id="<?= $userId ?>" id="upgrade_account">Upgrade My account</button>
						</div>
                  		<?php elseif( $physio_is_promoted !== '1' ): ?>
                  			<p>There are no slot available to become the Featured Physiotherapist in your city.</p>
					<?php endif; ?>
				</div>
			</div>
			<?php
				if($user_roles != 'referrer_leads'){
			?>
			<div class="card detail-card col-md-5 col-sm-6 mb-2 px-0 mx-1">
				<i class="fas fa-wallet"></i>
				<div class="card-body">
					<p class="mt-2 card-title"><strong class="user-total-credits"><?= get_user_meta( $userId, 'credits', true ); ?></strong></p>
					<h3 class="card-text">Credits</h3>
				</div>
			</div>
			<div class="card dash-card col-md-5 col-sm-6 mb-2 px-0 mx-1">
				<i class="far fa-star"></i>
				<div class="card-body">
					<h2 class="card-title"><?= $myEnquiryCount; ?></h2>
					<h3 class="card-text">Enquiry in my List</h3>
				</div>
			</div>
			<div class="card inquiry-card col-md-5 col-sm-6 mb-2 px-0 mx-1">
				<i class="far fa-list-alt"></i>
				<div class="card-body">
					<h2 class="card-title"><?= $globalEnquiryCount; ?></h2>
					<h3 class="card-text">Total Enquries in Global / Referrer List</h3>
				</div>
			</div>
			<?php
		}
			?>
		</div>
	</div>
</div>