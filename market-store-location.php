<?php 
	/*
		Plugin Name: Market Store Location
		Description: Extension for wp multi store locator.
		Version: 1.0.0
	*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
	date_default_timezone_set('Europe/London'); // CDT

	include( plugin_dir_path( __FILE__ ) . 'admin/leads-enqueue-scripts.php');

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	include plugin_dir_path( __FILE__ ).'frontend/function.php';
	include( plugin_dir_path( __FILE__ ) . 'admin/leads-user.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-query.php');

	include( plugin_dir_path( __FILE__ ) . 'frontend/featured-physio.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-contact.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-consultation.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-category.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-register.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-referrer.php');
	 include( plugin_dir_path( __FILE__ ) . 'frontend/leads-info.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-dashboard.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/referrer-dashboard.php');
	
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-register-v2.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-promoted-count.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-register-button.php');

	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-search.php');

	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-email.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-hcpc.php');
	include( plugin_dir_path( __FILE__ ) . 'frontend/leads-renew-account.php');

	include( plugin_dir_path( __FILE__ ) . 'admin/plugin-activation.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/leads-custom-posttype.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/unverified-user-page.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/expired-users-page.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/deleted-account-page.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/enquiry-list-page.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/global-enquiry-page.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/referrer-leads-page.php');
	include( plugin_dir_path( __FILE__ ) . 'admin/paypal-transactions.php');

	include( plugin_dir_path( __FILE__ ) . 'admin/cron-schedules.php' );
	
	register_activation_hook(__FILE__, 'physiobrite_leads_plugin_activation');
	register_activation_hook( __FILE__, 'physiobrite_page_register_leads_create' );
	register_activation_hook( __FILE__, 'physiobrite_page_consultation_create' );
	register_activation_hook( __FILE__, 'physiobrite_page_leads_dashboard_create' );
	register_activation_hook( __FILE__, 'physiobrite_page_leads_info_create' );

	/**
	*	Actions and Filters
	*/
	add_action( 'wp_head', 'hide_show_dashboard_link_func' );
	function hide_show_dashboard_link_func(){
		if(is_user_logged_in()){
	      $userId = get_current_user_id();
	      $user_info = get_userdata($userId);
	      $user_roles = $user_info->roles[0];

			if($user_roles !== 'physiobrite_leads'){
	        	echo '<style>.menu-leads-dashboard{display:none !important;}</style>';
	        }
	    }else{
	    	echo '<style>.menu-leads-dashboard{display:none !important;}</style>';
	    }
	}

	function register_session(){
	    if( !session_id() )
	        session_start();
	}
	add_action('init','register_session');


	


	/**
	*	The Custom login page
	*/
	function custom_login_redirect( $redirect_to, $request, $user )
	{
		global $user;
		$url = '';
		if( isset( $user->roles ) && is_array( $user->roles ) ) {
			
			if( in_array( 'physiobrite_leads', $user->roles ) ) {
				$url = home_url('/leads-dashboard');
			}
			else {
				$url = admin_url();
			}
		}
		return $url;
	}
	add_filter("login_redirect", "custom_login_redirect", 10, 3);



	function the_custom_login()
	{
		echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url( __FILE__ ) . 'css/custom-login-style.css" />';
	}
	add_action('login_head', 'the_custom_login');

	function custom_login_logo_url() {
		return get_bloginfo( 'url' );
	}
	add_filter( 'login_headerurl', 'custom_login_logo_url' );

	function custom_login_logo_url_title() {
		return 'Physiobrite';
	}
	add_filter( 'login_headertitle', 'custom_login_logo_url_title' );

	// Add a new interval of 1800 seconds
    // See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
    add_filter( 'cron_schedules', 'check_every_thirty_minutes' );
    function check_every_thirty_minutes( $schedules ) {
    $schedules['every_three_minutes'] = array(
    'interval' => 1800,
    'display' => __( 'Every 30 Minutes', 'textdomain' )
    );
    return $schedules;
    }

    // Schedule an action if it's not already scheduled
    if ( ! wp_next_scheduled( 'check_every_thirty_minutes' ) ) {
    wp_schedule_event( time(), 'every_three_minutes', 'check_every_thirty_minutes' );
    }

    // Hook into that action that'll fire every thirty minutes
    add_action( 'check_every_thirty_minutes', 'check_every_thirty_minutes_func' );
    function check_every_thirty_minutes_func() {

        do_action('member_expiry_check');
        do_action('local_enquiry_time_check');
        do_action('leads_promoted_expiry_check');

    }

/**
*	Add Google Ads scripts to the header of the site
*/
add_action('wp_head', 'google_ads_scripts_func', 1);
function google_ads_scripts_func(){
  	global $post;
  /*if( is_front_page() ){
	/*if( is_page() ){
        if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'hcpc_form') || has_shortcode( $post->post_content, 'renew_account') || is_page('All Categories') ) {*/
            //echo '';
        /*}else{*/
            echo '
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<script>
			  (adsbygoogle = window.adsbygoogle || []).push({
			    google_ad_client: "ca-pub-4613477108088593",
			    enable_page_level_ads: true
			  });
			</script>
            ';
       // }
    /*}*/
}

/**
*	Duplicate Store Locator category on create
*/

add_action('create_store_locator_category', 'duplicate_to_post_category_func');
function duplicate_to_post_category_func(){

	$cat_name = sanitize_text_field($_POST['tag-name']);
	$cat_slug = sanitize_text_field($_POST['slug']);
	$cat_desc = sanitize_text_field($_POST['description']);
	//$cat_parent = sanitize_text_field($_POST['parent']);
	$cat_ID = get_cat_ID( sanitize_title_for_query($cat_name) );

    $my_cat = array(
        'cat_name' => $cat_name, 
        'category_description' => $cat_desc, 
        'category_nicename' => $cat_slug, 
        //'category_parent' => $cat_parent,
        'taxonomy' => 'category'
    );
    wp_insert_category( $my_cat );
}

add_action( 'init', 'blockusers_init' );

function blockusers_init() {

if ( is_admin() && ! current_user_can( 'administrator' ) &&

! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

wp_redirect( home_url() . '/leads-dashboard', 301 );

exit;

}

}

function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
  $earth_radius = 6371;

  $dLat = deg2rad($latitude2 - $latitude1);  
  $dLon = deg2rad($longitude2 - $longitude1);  

  $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
  $c = 2 * asin(sqrt($a));  
  $d = $earth_radius * $c;  

  return $d;  
}



add_action("wp_ajax_show_featured_physio", "show_featured_physio_search");
add_action("wp_ajax_nopriv_show_featured_physio", "show_featured_physio_search");

function show_featured_physio_search() {
	header("Content-type:application/json");
	
	
	global $wpdb;
	

	//print_r(get_post_meta(1544, 'store_locator_lat'));
	//print_r(get_post_meta(1544, 'store_locator_lng'));
   
	
	$tableName =  $wpdb->prefix .'physiobrite_featured_physio';
	$origLat = $_POST['store_locatore_search_lat'];  // Iba zamabales
$category = (empty($_POST['store_locator_category']))?"NULL" : $_POST['store_locator_category'];  
$origLon = $_POST['store_locatore_search_lng']; // Iba zamabales
$dist = $_POST['store_locatore_search_radius'] * 0.621371; // This is the maximum distance (in KM) away from $origLat, $origLon in which to search
// $dist = 37; // This is the maximum distance (in KM) away from $origLat, $origLon in which to search
$query = "SELECT * , 3956 * 2 * 
          ASIN(SQRT( POWER(SIN(($origLat - abs(store_locator_lat))*pi()/180/2),2)
          +COS($origLat*pi()/180 )*COS(abs(store_locator_lat)*pi()/180)
          *POWER(SIN(($origLon-abs(store_locator_lng))*pi()/180/2),2))) 
          as km_distance FROM $tableName WHERE 
          store_locator_lng between ($origLon-$dist/cos(radians($origLat))*69) 
          and ($origLon+$dist/cos(radians($origLat))*69) 
          and store_locator_lat between ($origLat-($dist/69)) 
          and ($origLat+($dist/69)) 
		  and (CASE
                 WHEN $category IS NULL THEN service_type LIKE '%%'
                 ELSE  service_type = $category
            END)
          having km_distance < $dist ORDER BY km_distance limit 3";  
		  //echo $query;
	/* $query = "SELECT 
			(ATAN(
				SQRT(
					POW(COS(RADIANS($tableName.store_locator_lat)) * SIN(RADIANS($tableName.store_locator_lng) - RADIANS($origLon)), 2) +
					POW(COS(RADIANS($origLat)) * SIN(RADIANS($tableName.store_locator_lat)) - 
				   SIN(RADIANS($origLat)) * cos(RADIANS($tableName.store_locator_lat)) * cos(RADIANS($tableName.store_locator_lng) - RADIANS($origLon)), 2)
				)
				,
				SIN(RADIANS($origLat)) * 
				SIN(RADIANS($tableName.store_locator_lat)) + 
				COS(RADIANS($origLat)) * 
				COS(RADIANS($tableName.store_locator_lat)) * 
				COS(RADIANS($tableName.store_locator_lng) - RADIANS($origLon))
			 ) * $dist) as distance
			FROM $tableName
			ORDER BY distance ASC";  */
	

	
	 $wp_physiobrite_featured_physio = $wpdb->get_results($wpdb->prepare(
       $query
      ));
	
	//print_r($wp_physiobrite_featured_physio);
	
	
	
	/* $distance = getDistance(15.3329773,119.975756, $wp_physiobrite_featured_physio[0]->store_locator_lat,$wp_physiobrite_featured_physio[0]->store_locator_lng);
	if ($distance < 80) {
	  echo "Within  kilometer radius";
	} else {
	  echo "Outside  kilometer radius";
	} */
	
	ob_start();
?>
<section class="featured-physio">
		<div class="text-right mb-3"><a href="#" class="btn btn-primary btn-sm py-2 px-2 featured-button">Be a featured physiotherapist now</a></div>
		<div class="featured-slider">
<?php
foreach($wp_physiobrite_featured_physio as $key => $value){
	
		?>
			<div class="text-center mt-2 pb-3 position-relative" style="width: 100%; display: inline-block;">
				<div class="ribbon ribbon-top-right"><span>featured</span></div>
				<div class="card mx-3">
				  <a href="http://physiobrite.test/leads-info/?physio_id=1544" tabindex="0"><img class="card-img-top" src="https://dummyimage.com/360x360/000/fff" alt=""></a>
				  <div class="card-body">
					<h3 class="mb-2 pt-2 card-text"><a href="http://physiobrite.test/leads-info/?physio_id=<?=$value->leadsId?>" tabindex="0"><?=$value->physio_name?></a></h3>		
					<p><small><?=sprintf('%0.1f', ($value->km_distance * 1.60934))?> km away to <?=$value->physio_city?></small></p>
				  </div>
				</div>
			</div>
	
		
		<?php
	}

?>
			
		</div>
	</section>
	<?php
	
		
			
			
	$output = ob_get_clean();
	
	
	if(!empty($wp_physiobrite_featured_physio)){
		echo json_encode( array( "html" => $output , "success" => true) );
	}
	else{
		echo json_encode( array( "success" => false ,  "html" => "") );
	}
	
   die();


}


add_action( 'init', 'search_feature_resuly_ajax_queue' );

function search_feature_resuly_ajax_queue() {
	
   wp_register_script( "search_feature_resuly",plugin_dir_url( __FILE__ ).'/js/search_feature_result.js', array('jquery') );
   
   wp_localize_script( 'search_feature_resuly', 'searchFeature', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'search_feature_resuly' );

}


 ?>