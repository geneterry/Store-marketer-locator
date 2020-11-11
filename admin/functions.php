<?php 

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

 ?>