<?php 

/**
 * Enqueue scripts and styles.
 */
function physiobrite_scripts() {
	global $post;
	if( is_page() ){
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'leadsDashboard') || has_shortcode( $post->post_content, 'referrer_leads_page') || has_shortcode( $post->post_content, 'referrerDashboard') || has_shortcode( $post->post_content, 'leadsInfo') || has_shortcode( $post->post_content, 'register_leads_page') || has_shortcode( $post->post_content, 'emailtry_shortcode') || has_shortcode( $post->post_content, 'show_physio_category') || has_shortcode( $post->post_content, 'show_contact_form') || has_shortcode( $post->post_content, 'register_leads_page_v2') || has_shortcode( $post->post_content, 'hcpc_form') || has_shortcode( $post->post_content, 'renew_account') ) {
          
          	//wp_enqueue_script( 'select2-leads', plugins_url() . '/physiobrite-leads/js/select2.js', array(), false, true);
		    wp_enqueue_script( 'jquery-latest-leads', 'https://code.jquery.com/jquery-3.3.1.min.js', array(), false, true);
          	
          	wp_enqueue_script( 'backend-script-leads', plugins_url() . '/physiobrite-leads/js/backend_script.js', array(), false, true);
		    wp_enqueue_script( 'popper-leads', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array(), false, true);
		    wp_enqueue_script( 'bootstrap-js-leads', plugins_url() . '/physiobrite-leads/js/bootstrap.bundle.min.js', array(), false, true);
		    wp_enqueue_script( 'timepick-leads', plugins_url() . '/physiobrite-leads/js/jquery.timepicker.js', array(), false, true);
			wp_enqueue_script( 'datajs-leads', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), false, true);
		    wp_enqueue_script( 'leads-js', plugins_url() . '/physiobrite-leads/js/leads.js', array(), false, true);
		    wp_localize_script( 'leads-js', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		    wp_enqueue_script( 'select-js', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js', array(), false, true);
		    wp_enqueue_script( 'slick-script', plugins_url() . '/physiobrite-leads/slick/slick.js', array(), false, true);
          	
		}
	}


}
add_action( 'wp_enqueue_scripts', 'physiobrite_scripts', 100,1 );
function physiobrite_style() {
			wp_enqueue_style( 'bootstrap', plugins_url() . '/physiobrite-leads/css/bootstrap.min.css' );
			wp_enqueue_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.7.1/css/all.css' );

			global $post;
			if( is_page() ){
				if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'leadsDashboard') || has_shortcode( $post->post_content, 'referrerDashboard')  || has_shortcode( $post->post_content, 'referrer_leads_page') ||  has_shortcode( $post->post_content, 'leadsInfo') || has_shortcode( $post->post_content, 'register_leads_page') || has_shortcode( $post->post_content, 'show_featured_physio') || has_shortcode( $post->post_content, 'show_physio_category') || has_shortcode( $post->post_content, 'show_contact_form') || has_shortcode( $post->post_content, 'register_leads_page_v2') || has_shortcode( $post->post_content, 'hcpc_form') || has_shortcode( $post->post_content, 'renew_account') ) {

	              	//wp_enqueue_style( 'bootstrap', plugins_url() . '/physiobrite-leads/css/bootstrap.min.css' );

				    wp_enqueue_style( 'style', plugins_url() . '/physiobrite-leads/css/style.css' );
					
	              	wp_enqueue_style( 'select-css', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css');
				    wp_enqueue_style( 'timepickcss', plugins_url() . '/physiobrite-leads/css/jquery.timepicker.css');
					wp_enqueue_style( 'datacss', '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' );

					wp_enqueue_style( 'slick-css', plugins_url() . '/physiobrite-leads/slick/slick.css' );
					wp_enqueue_style( 'slick-theme-css', plugins_url() . '/physiobrite-leads/slick/slick-theme.css' );
	              
	              	wp_enqueue_script( 'swal-script', plugins_url() . '/physiobrite-leads/js/dist/sweetalert2.all.min.js', array(), false, false);
	              
				}
			}


}
add_action( 'wp_enqueue_scripts', 'physiobrite_style' );

/**
*	Paypal Scripts
*/
add_action('wp_head', 'add_paypal_script_func',5,2);
function add_paypal_script_func(){
  global $post;
	if( is_page() ){
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'leadsDashboard') || has_shortcode( $post->post_content, 'referrer_leads_page') || has_shortcode( $post->post_content, 'referrerDashboard') || has_shortcode( $post->post_content, 'leadsInfo') || has_shortcode( $post->post_content, 'register_leads_page') || has_shortcode( $post->post_content, 'emailtry_shortcode') || has_shortcode( $post->post_content, 'show_physio_category') || has_shortcode( $post->post_content, 'show_contact_form') || has_shortcode( $post->post_content, 'register_leads_page_v2') || has_shortcode( $post->post_content, 'hcpc_form') || has_shortcode( $post->post_content, 'renew_account') ) {
          
    echo '<script src="https://www.paypal.com/sdk/js?client-id=AWDH46fTcQ_a3aYo18i3-tcTbL9Px6vbaNKdwgFbqSRCLnwW8VV6HMJ9SHzknp2J3VSLPHkxzzIAFhX7&currency=GBP&components=buttons"></script>';
  	}
   }
}

 ?>