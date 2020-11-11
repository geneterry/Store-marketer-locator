<?php 

/**
*	Create database tables
*/
function physiobrite_leads_plugin_activation() {
	require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    global $wpdb;
	date_default_timezone_set('Europe/London'); // CDT
	$inquiries_table = $wpdb->prefix . 'physiobrite_client_enquiries';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$inquiries_table'") != $inquiries_table) {
	    $sql = "CREATE TABLE " . $inquiries_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `physio_id` bigint(20) NULL,
	    `physio_cat` varchar(255) NULL,
	    `client_name` varchar(255) NULL,
	    `client_address` varchar(255) NULL,
	    `client_email` varchar(255) NULL,
	    `client_tel` varchar(255) NULL,
	    `client_time_call` varchar(255) NULL,
	    `client_other_contact` text NULL,
	    `client_gender` varchar(255) NULL,
		`client_type` varchar(255) NULL,
	    `client_problem` text NULL,
	    `service_type`	varchar(255) NULL,
	    `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `date_modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `is_viewed` int(1) NOT NULL DEFAULT '0',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sql);
	}

	$testimonial_table = $wpdb->prefix . 'physiobrite_testimonial';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$testimonial_table'") != $testimonial_table) {
	    $sqlTestimonial = "CREATE TABLE " . $testimonial_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `physio_id` bigint(20) NULL,
	    `client_name` varchar(255) NULL,
	    `client_company` varchar(255) NULL,
	    `client_job` varchar(255) NULL,
	    `client_content` text NULL,
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sqlTestimonial);
	}
  
  $featured_table = $wpdb->prefix . 'physiobrite_featured_physio';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$featured_table'") != $featured_table) {
	    $sqlFeatured = "CREATE TABLE " . $featured_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `physio_city` varchar(255) NULL,
	    `physio_name` varchar(255) NULL,
	    `physio_id` bigint(20) NULL,
	    `physio_email` varchar(255) NULL,
        `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sqlFeatured);
	}
  
  $payment_table = $wpdb->prefix . 'physiobrite_payment_trasaction';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$payment_table'") != $payment_table) {
	    $sqlPayment = "CREATE TABLE " . $payment_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `physio_id` bigint(20) NULL,
	    `paypal_payerID` varchar(100) NULL,
	    `physio_name` varchar(255) NULL,
	    `physio_email` varchar(255) NULL,
	    `transaction_type` varchar(255) NULL,
	    `amount` varchar(255) NULL,
	    `order_id` varchar(255) NULL,
	    `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sqlPayment);
	}


	$unverified_user_table = $wpdb->prefix . 'physiobrite_unverified_user_data';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$unverified_user_table'") != $unverified_user_table) {
	    $sqlUnverified = "CREATE TABLE " . $unverified_user_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `store_id` bigint(20) NULL,
	    `user_login` varchar(255) NULL,
	    `user_pass` varchar(255) NULL,
	    `user_url` varchar(255) NULL,
	    `user_email` varchar(255) NULL,
	    `first_name` varchar(255) NULL,
	    `last_name` varchar(255) NULL,
	    `rand_password` varchar(255) NULL,
	    `hcpc_id` varchar(255) NULL,
	    `role` varchar(255) NULL,
	    `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `is_verified` int(1) NOT NULL DEFAULT '0',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sqlUnverified);
	}

	$expired_user_table = $wpdb->prefix . 'physiobrite_expired_user_data';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$expired_user_table'") != $expired_user_table) {
	    $sqlExpired = "CREATE TABLE " . $expired_user_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `store_id` bigint(20) NULL,
	    `user_login` varchar(255) NULL,
	    `user_pass` varchar(255) NULL,
	    `user_url` varchar(255) NULL,
	    `user_email` varchar(255) NULL,
	    `first_name` varchar(255) NULL,
	    `last_name` varchar(255) NULL,
	    `rand_password` varchar(255) NULL,
	    `hcpc_id` varchar(255) NULL,
	    `role` varchar(255) NULL,
	    `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `date_modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `credits` varchar(255) NOT NULL DEFAULT '0',
	    `leads_id` bigint(20),
	    `is_promoted` int(1) NOT NULL DEFAULT '0',
	    `is_verified` int(1) NOT NULL DEFAULT '0',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sqlExpired);
	}

	$deleted_account_table = $wpdb->prefix . 'physiobrite_deleted_account_data';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$deleted_account_table'") != $deleted_account_table) {
	    $sqlDeleted = "CREATE TABLE " . $deleted_account_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `store_id` bigint(20) NULL,
	    `user_login` varchar(255) NULL,
	    `user_pass` varchar(255) NULL,
	    `user_url` varchar(255) NULL,
	    `user_email` varchar(255) NULL,
	    `first_name` varchar(255) NULL,
	    `last_name` varchar(255) NULL,
	    `rand_password` varchar(255) NULL,
	    `hcpc_id` varchar(255) NULL,
	    `role` varchar(255) NULL,
	    `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `credits` varchar(255) NOT NULL DEFAULT '0',
	    `leads_id` bigint(20),
	    `is_promoted` int(1) NOT NULL DEFAULT '0',
	    `is_verified` int(1) NOT NULL DEFAULT '0',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sqlDeleted);
	}

	$global_leads_table = $wpdb->prefix . 'physiobrite_global_client_enquiries';
	  if ($wpdb->get_var("SHOW TABLES LIKE '$global_leads_table'") != $global_leads_table) {
	    $sql = "CREATE TABLE " . $global_leads_table . " (
	    `id` bigint(20) NOT NULL AUTO_INCREMENT,
	    `physio_id` bigint(20) NULL,
	    `physio_cat` varchar(255) NULL,
	    `client_name` varchar(255) NULL,
	    `client_address` varchar(255) NULL,
	    `client_email` varchar(255) NULL,
	    `client_tel` varchar(255) NULL,
	    `client_time_call` varchar(255) NULL,
	    `client_other_contact` text NULL,
	    `client_gender` varchar(255) NULL,
		 `client_type` varchar(255) NULL,
	    `client_problem` text NULL,
	    `service_type`	varchar(255) NULL,
	    `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `date_modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	    `is_viewed` int(1) NOT NULL DEFAULT '0',
	    PRIMARY KEY (`id`)
	) $charset_collate;";
	dbDelta($sql);
	}

}

/**
*	Create Pages
*/


function physiobrite_page_register_leads_create() {
    global $wpdb;
    $pageTitle = 'Register Leads';
    $pageContent = '[show_contact_form]';

    $tbl_name = $wpdb->prefix . 'posts';
    $inquiryList = $wpdb->get_results($wpdb->prepare(
                'SELECT ID FROM '. $tbl_name .' where post_title="%s" and post_status="%s"', 
                $pageTitle,'publish'
              ));

    if ( !empty($inquiryList) ) {
        // Title already exists
    }else{
        $pageInfo = array(
            'post_type' => 'page',
            'post_title' => $pageTitle,
            'post_content' => $pageContent,
            'post_status' => 'publish',
            'post_author' => 1,
        );
        $pageCreate = wp_insert_post($pageInfo);
    }
}


function physiobrite_page_consultation_create() {
    global $wpdb;
    $pageTitle = 'Telephone Consultation';
    $pageContent = '[register_leads_page]';

    $tbl_name = $wpdb->prefix . 'posts';
    $inquiryList = $wpdb->get_results($wpdb->prepare(
                'SELECT ID FROM '. $tbl_name .' where post_title="%s" and post_status="%s"', 
                $pageTitle,'publish'
              ));

    if ( !empty($inquiryList) ) {
        // Title already exists
    }else{
        $pageInfo = array(
            'post_type' => 'page',
            'post_title' => $pageTitle,
            'post_content' => $pageContent,
            'post_status' => 'publish',
            'post_author' => 1,
        );
        $pageCreate = wp_insert_post($pageInfo);
    }
}



function physiobrite_page_leads_dashboard_create() {
    global $wpdb;
    $pageTitle = 'Leads Dashboard';
    $pageContent = '[leadsDashboard]';

    $tbl_name = $wpdb->prefix . 'posts';
    $inquiryList = $wpdb->get_results($wpdb->prepare(
                'SELECT ID FROM '. $tbl_name .' where post_title="%s" and post_status="%s"', 
                $pageTitle,'publish'
              ));

    if ( !empty($inquiryList) ) {
        // Title already exists
    }else{
        $pageInfo = array(
            'post_type' => 'page',
            'post_title' => $pageTitle,
            'post_content' => $pageContent,
            'post_status' => 'publish',
            'post_author' => 1,
        );
        $pageCreate = wp_insert_post($pageInfo);
    }
}


function physiobrite_page_leads_info_create() {
    global $wpdb;
    $pageTitle = 'Leads Info';
    $pageContent = '[leadsInfo]';

    $tbl_name = $wpdb->prefix . 'posts';
    $inquiryList = $wpdb->get_results($wpdb->prepare(
                'SELECT ID FROM '. $tbl_name .' where post_title="%s" and post_status="%s"', 
                $pageTitle,'publish'
              ));

    if ( !empty($inquiryList) ) {
        // Title already exists
    }else{
        $pageInfo = array(
            'post_type' => 'page',
            'post_title' => $pageTitle,
            'post_content' => $pageContent,
            'post_status' => 'publish',
            'post_author' => 1,
        );
        $pageCreate = wp_insert_post($pageInfo);
    }
}
 ?>