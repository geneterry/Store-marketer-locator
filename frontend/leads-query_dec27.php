<?php 

add_action( 'registerLeads', 'register_leads_func');
function register_leads_func(){
    global $wpdb;

    $isError = 0;

    if (isset($_POST['username']))
        $username = sanitize_text_field($_POST['username']);

    if (isset($_POST['inputFirstName']))
        $firstname = sanitize_text_field($_POST['inputFirstName']);

    if (isset($_POST['inputLastName']))
        $lastname = sanitize_text_field($_POST['inputLastName']);

    if (isset($_POST['inputEmail']))
        $user_email = sanitize_text_field($_POST['inputEmail']);

    if (isset($_POST['userPass']))
        $user_pass = sanitize_text_field($_POST['userPass']);

    if (isset($_POST['store_locator_website']))
        $user_website = sanitize_text_field($_POST['store_locator_website']);

    if ( email_exists( $user_email) )  {

        $isError = 1;
        swal_message('info', 'Email exist!', 'The email that you have entered is already taken.');

    }
    else if(username_exists( $username ))

    {
        $isError = 1;
        swal_message('info', 'Username exist!', 'The username that you have entered is already taken.');

    }

    else

    {
        if( $isError == 1 ){
            swal_message('error', 'Error!', 'Something went wrong in creating your account.');
        }
        $url = get_bloginfo('url');

        $rand_passwod=wp_generate_password(20);

        $rand_passwod=preg_replace("/[^a-zA-Z]/", "", $rand_passwod);

        $userdata = array(
            'user_login'            => $username,   //(string) The user's login username.
            'user_pass'             => $user_pass,   //(string) The plain-text user password.
            'user_url'              => $user_website,   //(string) The user URL.
            'user_email'            => $user_email,   //(string) The user email address.
            'first_name'            => $firstname,   //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
            'last_name'             => $lastname,   //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
            'show_admin_bar_front'  => 'false',   //(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
            'role'                  => 'physiobrite_leads',   //(string) User's role.
         
        );

        $user_id = wp_insert_user( $userdata ) ;

        
        if($user_id){


            $table_name = $wpdb->prefix . 'users';

            $wpdb->query("UPDATE $table_name SET user_activation_key='$rand_passwod' WHERE ID=$user_id");


            update_user_meta($user_id,'credits', '30'); //Update the credits of the user

            update_user_meta($user_id,'is_promoted', '0'); //Update the promoted status of user
            
            /*==========Create Post============*/

            $lead_information = array(
                'post_title' => wp_strip_all_tags($_POST['inputFirstName'] . ' ' . $_POST['inputLastName']),
                'post_type' => 'store_locator',
                'post_status' => 'publish'
            );

            // update post meta
            $post_id = wp_insert_post($lead_information);

            update_post_meta($post_id, 'physio_id', $user_id);
            update_user_meta($user_id,'leads_id',$post_id);

            $attachment_id = '';
            if( $_FILES['imageUpload']['name'] !== '' ){
                $attachment_id = media_handle_upload( 'imageUpload', $post_id );
                if ( is_wp_error( $attachment_id ) ) {
                    swal_message('error', 'Error!', 'There was an error in uploading the image.');
                }
            }
               
            /*if (isset($_POST['inputCategory']))
                wp_set_post_terms( $post_id, array( $_POST['inputCategory'] ), 'store_locator_category');*/

            if (isset($_POST['inputCategory'])){
                $categoryInput = $_POST['inputCategory'];
                $arrCats = array();

                foreach ($categoryInput as $inputCat) {
                    $intCatId = (int)$inputCat;
                    wp_set_post_terms( $post_id, $intCatId, 'store_locator_category');
                    $arrCat = get_term_by( 'id', $intCatId, 'store_locator_category' );
                    $arrCats[] .= $arrCat->name;
                }
                update_post_meta($post_id, 'store_locator_category', $arrCats);
            }

            $leadsSiteUrl = site_url() . '/leads-info/?physio_id='.$post_id;



            if (isset($_POST['inputFirstName']) || isset($_POST['inputLastName'])){
                $firstLastName = sanitize_text_field($_POST['inputFirstName'] . ' ' . $_POST['inputLastName']);
                update_post_meta($post_id, 'store_locator_name', $firstLastName);
            }

            if (isset($_POST['inputGender'])){
                $postGender = sanitize_text_field($_POST['inputGender']);
                update_post_meta($post_id, 'store_locator_gender', $postGender);
            }

            if (isset($_POST['store_locator_address'])){
                $postLocatorAddress = sanitize_text_field($_POST['store_locator_address']);
                update_post_meta($post_id, 'store_locator_address', $postLocatorAddress);
            }

            if (isset($_POST['store_locator_lat'])){
                $postLocatorLat = sanitize_text_field($_POST['store_locator_lat']);
                update_post_meta($post_id, 'store_locator_lat',  $postLocatorLat);
            }

            if (isset($_POST['store_locator_lng'])){
                $postLocatorLng = sanitize_text_field($_POST['store_locator_lng']);
                update_post_meta($post_id, 'store_locator_lng', $postLocatorLng);
            }

            if (isset($_POST['store_locator_country'])){
                $postLocatorCountry = sanitize_text_field($_POST['store_locator_country']);
                update_post_meta($post_id, 'store_locator_country', $postLocatorCountry);
            }

            if (isset($_POST['store_locator_state'])){
                $postLocatorState = sanitize_text_field($_POST['store_locator_state']);
                update_post_meta($post_id, 'store_locator_state', $postLocatorState);
            }

            if (isset($_POST['store_locator_city'])){
                $postLocatorCity = sanitize_text_field($_POST['store_locator_city']);
                update_post_meta($post_id, 'store_locator_city', $postLocatorCity);
            }

            if (isset($_POST['inputPhone'])){
                $postInputPhone = sanitize_text_field($_POST['inputPhone']);
                update_post_meta($post_id, 'store_locator_phone',  $postInputPhone);
            }

            if (isset($_POST['store_locator_website']))
                update_post_meta($post_id, 'store_locator_website', $leadsSiteUrl);

            if (isset($_POST['store_locator_zipcode'])){
                $postLocatorZipcode = sanitize_text_field($_POST['store_locator_zipcode']);
                update_post_meta($post_id, 'store_locator_zipcode', $postLocatorZipcode);
            }

            if (isset($_POST['store_locator_days']))
                update_post_meta($post_id, 'store_locator_days', $_POST['store_locator_days']);

            if (isset($_POST['store_locator_description'])){
                $postLocatorZipcode = sanitize_textarea_field($_POST['store_locator_zipcode']);
                update_post_meta($post_id, 'store_locator_description', $_POST['store_locator_description']);
            }

            if (isset($_POST['store_locator_gform']))
                update_post_meta($post_id, 'store_locator_gform', $_POST['store_locator_gform']);
        }

        if ( !is_wp_error( $post_id ) AND !is_wp_error( $user_id ) ) {
                swal_message('success', 'Success!', 'Your account has been created successfully. You can now <a href="'. home_url() . '/login"><strong><u>login</u></strong></a> to your account.');
                echo '<p class="text-success"><strong><a href="' . home_url() . '/login">Login</a> to your account</strong></p>';
                unset($_SESSION['register_data']);
        }
        else{
            swal_message('error', 'Error!', 'There is an error in creating your account.');
        }
    }
}


add_action('sendEnquiries', 'physiobrite_send_enquiries_func');
function physiobrite_send_enquiries_func() {

    global $wpdb;

    if (isset($_POST['physioId']))
            $physioId = $_POST['physioId']; //Physio ID

    if (isset($_POST['physioCat']))
            $physioCat = $_POST['physioCat']; //Physio Cat

    if (isset($_POST['clientName']))
            $clientName = $_POST['clientName']; //Client Name

    if (isset($_POST['clientAddress']))
            $clientAddress = $_POST['clientAddress']; //Client Address

    if (isset($_POST['clientTel']))
            $clientTel = $_POST['clientTel']; //Client Tel

    if (isset($_POST['clientEmail']))
            $clientEmail = $_POST['clientEmail']; //Client Email

    if (isset($_POST['clientTimeCall']))
            $clientTimeCall = $_POST['clientTimeCall']; //Client Time Call

    if (isset($_POST['clientOtherContact']))
            $clientOtherContact = $_POST['clientOtherContact']; //Client Other Contact

    if (isset($_POST['clientGender']))
            $clientGender = $_POST['clientGender']; //Client Gender

    if (isset($_POST['serviceType']))
            $serviceType = $_POST['serviceType']; //Client Gender

    /*if (isset($_POST['clientProblem']))
            $clientProblem = $_POST['clientProblem'];*/ //Client Problem

    //Save the Inquiry
    $table = $wpdb->prefix.'physiobrite_client_enquiries';

    $data = array(
                'physio_id' => $physioId,
                'physio_cat' => $physioCat,
                'client_name' => $clientName,
                'client_address' => $clientAddress,
                'client_email' => $clientEmail,
                'client_tel' => $clientTel,
                'client_time_call' => $clientTimeCall,
                'client_other_contact' => $clientOtherContact,
                'service_type' => $serviceType,
                'client_gender' => $clientGender,
                'client_problem' => '',
                'date_created' => current_time('mysql', 1)
            );

    $format = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');

    $insertId = $wpdb->insert($table,$data,$format);

    if ( $insertId && sendEnquiryEmail($data)) {
        global $wp;
        swal_message('success', 'Success!', 'Thank you for sending us your Enquiry. We will get back to you as soon as possible.');
    }else{
        swal_message('error', 'Error!', 'There was an error in sending your Enquiry. Please refresh the page and try again.');
    }

}

add_action('wp_ajax_psyb_approached_client', 'psyb_approached_client');
add_action('wp_ajax_nopriv_psyb_approached_client', 'psyb_approached_client');
function psyb_approached_client(){
    
    global $wpdb;
    $result = array();

    if ( isset($_POST['leadsId']) ) {
        $leadsId = $_POST['leadsId'];
        $tableName = $wpdb->prefix . 'physiobrite_client_enquiries';

        $data = array( 'is_approached' => 1 );
        $where = array( 'ID' => $leadsId );
        $approachedClient = $wpdb->update( $tableName, $data, $where );

        if($approachedClient){
            $result = array(
                'success'       => true
            );
        }
    }else{
        $result = array(
            'success'       => false
        );
    }

    echo wp_json_encode($result); 
    wp_die();
}

add_action('wp_ajax_psyb_deduct_credit', 'psyb_deduct_credit');
add_action('wp_ajax_nopriv_psyb_deduct_credit', 'psyb_deduct_credit');
function psyb_deduct_credit() {
    include_once plugin_dir_path( __FILE__ ).'function.php';
    ob_start();
    global $wpdb;

    $result = '';
    $isSuccess = true;

    $openModal = false;
    $is_deducted = false;

    if (isset($_POST['physio_id']))
            $physio_id = $_POST['physio_id'];

    if (isset($_POST['leadsId']))
        $leadsId = $_POST['leadsId'];

    $tableName = $wpdb->prefix . 'physiobrite_client_enquiries';

    $viewLeads = $wpdb->get_row($wpdb->prepare(
                    "SELECT is_viewed FROM ". $tableName ." WHERE ID = %d", 
                    $leadsId
                  ));
    
    //If the leads is not yet viewed by the Service Provider, deduct credits.
    if ( $viewLeads->is_viewed == 0 ){

        $currentLeadsCredit = (float) get_user_meta( $physio_id, 'credits', true );
        if( $currentLeadsCredit > 0 && $currentLeadsCredit >= 10 ){

            $deductTen = $currentLeadsCredit - 10;
            $deductTen = number_format((float)$deductTen, 2, '.', '');

            $updateCredit = update_user_meta( $physio_id, 'credits', $deductTen );
            if($updateCredit){

                $message = 'You currently have ' . $deductTen .' credits';
                $data = array( 'is_viewed' => 1 );
                $where = array( 'ID' => $leadsId );
                $viewedLeads = $wpdb->update( $tableName, $data, $where );

                    if( $viewedLeads ){

                        $openModal = true;
                        $is_deducted = true;
                        
                    }
                $isSuccess = true;
            }

        }elseif( $currentLeadsCredit == 0 && $currentLeadsCredit < 10 ){
            $message = 'You do not enough credits to view this leads';
            $openModal = false;
            $deductTen = $currentLeadsCredit;
            $isSuccess = false;
        }


        $result = array(
                        'success'   => $isSuccess,
                        'physio_id' => $physio_id,
                        'remainingCredit'   => $deductTen,
                        'message'   => $message,
                        'openModal' => $openModal,
                        'is_deducted' => $is_deducted
                    );
    //Else do not deduct credits
    }else{

        $result = array(
                'success'       => true,
                'openModal'     => true,
                'is_deducted'   => false,
            );
    }

    echo wp_json_encode($result); 
    wp_die();
}


add_action('wp_ajax_data_enquiry_table', 'data_enquiry_table');
add_action('wp_ajax_nopriv_data_enquiry_table', 'data_enquiry_table');
function data_enquiry_table() {
    ob_start();
    global $wpdb;
    $userId = get_current_user_id();
    $requestData= $_REQUEST;

    $columns = array( 
        0  =>    'id',
        1  =>    'physio_id', 
        2  =>    'physio_cat',
        3  =>    'client_name',
        4  =>    'client_address',
        5  =>    'client_email',
        6  =>    'client_tel',
        7  =>    'client_time_call',
        8  =>    'client_other_contact',
        9  =>    'client_gender',
        10 =>    'client_problem',
        11 =>    'service_type',
        12 =>    'date_created',
        13 =>    'is_viewed',
        13 =>    'is_approached',
    );

    $currentLeadsCredit = get_user_meta( $userId, 'credits', true );

    $tbl_name = $wpdb->prefix . 'physiobrite_client_enquiries';
        $enquiryList = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM ". $tbl_name ." WHERE physio_id = %d", 
                    $userId
                  ));
    $currentDateTime = current_time('mysql', 1);
    //Only get data that have date diff less than 31
    $sql = "SELECT * FROM ". $tbl_name ." WHERE physio_id = '" . $userId . "' AND DATEDIFF(date_created, '" . $currentDateTime . "') < '31'";
    if( !empty($requestData['search']['value']) ) 
     {

        $sql.=" AND ( physio_cat LIKE '".$requestData['search']['value']."%' "; 
        //$sql.=" OR  client_name LIKE '".$requestData['search']['value']."%' "; 
        $sql.=" OR  client_address LIKE '".$requestData['search']['value']."%' "; 
        $sql.=" OR  client_gender LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR  service_type LIKE '".$requestData['search']['value']."%' )";
     }

        $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

        $listEnquiry = $wpdb->get_results($sql);

        $totalData = count($enquiryList);
        $totalFiltered = $totalData;

        if( count( $listEnquiry ) > 0 ){
            $dataEnquiry = $listEnquiry;


            foreach ($dataEnquiry as $enquiryData) {

                if( $currentLeadsCredit > 0 && $currentLeadsCredit >= 10 || $enquiryData->is_viewed == 1 ){
                    
                    $isApproached = "";
                    if( $enquiryData->is_approached == 1 ){
                        $isApproached = " disabled";
                    }
                    $enquiry_view[]= array(
                    $enquiryData->client_address,
                    $enquiryData->client_gender,
                    $enquiryData->physio_cat,
                    '<button class="btn btn-secondary btn-sm access-enquiry" data-fieldinfo=\''.htmlspecialchars(wp_json_encode($enquiryData), ENT_QUOTES, "UTF-8").'\'" data-id="'.$enquiryData->id.'" data-expanded="false" data-info-loaded="false"><span class="spinner-border spinner-border-sm d-none"></span> Detail</button> <button class="btn btn-success btn-sm client-approched" data-id="'.$enquiryData->id.'"'. $isApproached .'>Approched</button>'
                    );


                }else{

                    $enquiry_view[]= array(
                    'Not enough credits',
                    'Not enough credits',
                    'Not enough credits',
                    '<button class="btn btn-secondary btn-sm access-enquiry" data-fieldinfo="' . $enquiryData->is_viewed .'" data-id="'.$enquiryData->id.'" data-expanded="false" data-info-loaded="false"><span class="spinner-border spinner-border-sm d-none"></span> Detail</button>'
                    );

                }

            }
        }
        else {
            $enquiry_view[]= array('','No Result Founds','','');
        }

    $json_data = array(
        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), 
        "data"            => $enquiry_view,   // total data array
        "credits"         => $currentLeadsCredit
    );

    echo wp_json_encode($json_data); 
    wp_die();
}

add_action( 'updateLeads2', 'update_leads2_func');
function update_leads2_func(){
    global $wpdb;

    $userUpdate = 0;

    //Get Current leads post id
    if ( isset($_POST['leadsId']) )
        $leadsId = $_POST['leadsId'];

    //Get current user id
    if ( isset($_POST['userId']) )
            $userId = $_POST['userId'];

    //Get current email
    $currUser = get_userdata( $userId );
    $currEmail = $currUser->user_email;

    $currUserpass = $currUser->user_pass;

    $currFirstName = get_user_meta( $userId, 'first_name', true );

    $currLastName = get_user_meta( $userId, 'last_name', true );

    $currUserNicename = $currUser->user_nicename;

    if (isset($_POST['inputFirstName']))
        $firstname = $_POST['inputFirstName'];

    if (isset($_POST['inputLastName']))
        $lastname = $_POST['inputLastName'];

    if (isset($_POST['inputEmail']))
        $user_email = $_POST['inputEmail'];

    if (isset($_POST['userPass']))
        $user_pass = $_POST['userPass'];

    if (isset($_POST['userPassCheck']))
        $user_pass_check = $_POST['userPassCheck'];

    $staffInfo = array();
    if ( isset( $_POST['inputStaffName'] ) && isset( $_POST['inputStaffHcpc'] ) && isset( $_POST['inputStaffEmail'] ) ){
       foreach ($_POST["inputStaffName"] as $key => $value) {
            $item = $value . ',' . $_POST["inputStaffEmail"][$key] . ',' . $_POST["inputStaffHcpc"][$key];
            array_push($staffInfo, $item);
       }
    }else{
        $staffInfo = '';
    }
  
	$table_unverified = $wpdb->prefix.'physiobrite_unverified_user_data';
    
    $email_exist = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM ". $table_unverified ." WHERE user_email = %s", 
        $user_email
      ));
  
    if( $user_pass !== $user_pass_check ){

        swal_message('error', 'Error!', 'The submitted Password and the Re-type Password does not match.');

    }elseif( $currEmail !== $user_email && email_exists( $user_email) || count( $email_exist ) > 0 ){

        swal_message('info', 'Email exist!', 'The email that you have entered is already taken.');


    }else{

        if( $firstname !== $currFirstName || $lastname !== $currLastName || $user_email !== $currEmail || $user_pass !== $currUserpass || $user_website !== $currWebsite )

        {
            $userdata = array(
                'ID'                    => $userId,    //(int) User ID.
                'user_pass'             => $user_pass,   //(string) The plain-text user password.
                'user_nicename'         => $currUserNicename,   //(string) The URL-friendly user name.
                'user_url'              => $user_website,   //(string) The user URL.
                'user_email'            => $user_email,   //(string) The user email address.
                'first_name'            => $firstname,   //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
                'last_name'             => $lastname,   //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
                'display_name'          => $firstname . ' ' . $lastname
             
            );

            $user_id = wp_update_user( $userdata ) ;

            $userUpdate = 1;

        }
            /*==========Update Post============*/

        $lead_information = array(
            'ID' => $leadsId,
            'post_title' => wp_strip_all_tags($_POST['inputFirstName'] . ' ' . $_POST['inputLastName']),
            'post_type' => 'store_locator',
            'post_status' => 'publish'
        );

        // update post meta
        $post_id = wp_update_post($lead_information);

        if ( $post_id ){
            
        $attachment_id = '';
        
        if( $_FILES['imageUpload']['name'] !== '' ){
            $attachment_id = media_handle_upload( 'imageUpload', $post_id );
            if ( isset($_POST['currImg']) ){
                wp_delete_attachment( $_POST['currImg'] );
            }else{

                if ( is_wp_error( $attachment_id ) ) {
                    swal_message('error', 'Error!', 'There was an error uploading the image.');
                }
                
            }
        }

        if (isset($_POST['inputCategory'])){
            $categoryInput = $_POST['inputCategory'];
            $arrCats = array();

            foreach ($categoryInput as $inputCat) {
                $intCatId = (int)$inputCat;
                wp_set_post_terms( $post_id, $intCatId, 'store_locator_category');
                $arrCat = get_term_by( 'id', $intCatId, 'store_locator_category' );
                $arrCats[] .= $arrCat->name;
            }
            update_post_meta($post_id, 'store_locator_category', $arrCats);
        }

        if (isset($_POST['inputFirstName']) || isset($_POST['inputLastName'])){
                $firstLastName = sanitize_text_field($_POST['inputFirstName'] . ' ' . $_POST['inputLastName']);
                update_post_meta($post_id, 'store_locator_name', $firstLastName);
            }

            if (isset($_POST['inputGender'])){
                $postGender = sanitize_text_field($_POST['inputGender']);
                update_post_meta($post_id, 'store_locator_gender', $postGender);
            }

            if (isset($_POST['store_locator_address'])){
                $postLocatorAddress = sanitize_text_field($_POST['store_locator_address']);
                update_post_meta($post_id, 'store_locator_address', $postLocatorAddress);
            }

            if (isset($_POST['store_locator_lat'])){
                $postLocatorLat = sanitize_text_field($_POST['store_locator_lat']);
                update_post_meta($post_id, 'store_locator_lat',  $postLocatorLat);
            }

            if (isset($_POST['store_locator_lng'])){
                $postLocatorLng = sanitize_text_field($_POST['store_locator_lng']);
                update_post_meta($post_id, 'store_locator_lng', $postLocatorLng);
            }

            if (isset($_POST['store_locator_country'])){
                $postLocatorCountry = sanitize_text_field($_POST['store_locator_country']);
                update_post_meta($post_id, 'store_locator_country', $postLocatorCountry);
            }

            if (isset($_POST['store_locator_state'])){
                $postLocatorState = sanitize_text_field($_POST['store_locator_state']);
                update_post_meta($post_id, 'store_locator_state', $postLocatorState);
            }

            if (isset($_POST['store_locator_city'])){
                $postLocatorCity = sanitize_text_field($_POST['store_locator_city']);
                update_post_meta($post_id, 'store_locator_city', $postLocatorCity);
            }

            if (isset($_POST['inputPhone'])){
                $postInputPhone = sanitize_text_field($_POST['inputPhone']);
                update_post_meta($post_id, 'store_locator_phone',  $postInputPhone);
            }

            if (isset($_POST['store_locator_zipcode'])){
                $postLocatorZipcode = sanitize_text_field($_POST['store_locator_zipcode']);
                update_post_meta($post_id, 'store_locator_zipcode', $postLocatorZipcode);
            }

            if (isset($_POST['store_locator_days']))
                update_post_meta($post_id, 'store_locator_days', $_POST['store_locator_days']);

            if (isset($_POST['store_locator_description'])){
                update_post_meta($post_id, 'store_locator_description', $_POST['store_locator_description']);
            }

            //if ( isset( $_POST['inputStaffName'] ) && isset( $_POST['inputStaffHcpc'] ) ){
                update_post_meta($post_id, 'store_locator_staff_members', $staffInfo);
            //}

            if (isset($_POST['store_locator_gform']))
                update_post_meta($post_id, 'store_locator_gform', $_POST['store_locator_gform']);
        
        }else{
            swal_message('error', 'Error!', 'There was an error in updating your account. Please refresh the page and try again.');
        }

    }

    if ( !is_wp_error( $post_id ) ) {


            if( $firstname !== $currFirstName || $lastname !== $currLastName || $user_email !== $currEmail || $user_pass !== $currUserpass ) {

                if ( !is_wp_error( $user_id ) && $userUpdate == 1 ) {

                    swal_message('info', 'Hello ' . $firstname . ' ' . $lastname, 'You have updated your account login information. You have to login again.', true, home_url() . '/login');

                }

            }

            swal_message('success', 'Success!', 'Your account has been updated successfully.', true, home_url() . '/leads-dashboard');
    }
    else{
        swal_message('error', 'Error!', 'There was an error in updating your account. Please refresh the page and try again.', true, home_url() . '/leads-dashboard');
    }
}

/*=======Delete Testimonial========*/
add_action( 'wp_ajax_psyb_delete_testimonial', 'psyb_delete_testimonial' );
add_action( 'wp_ajax_nopriv_psyb_delete_testimonial', 'psyb_delete_testimonial' );

function psyb_delete_testimonial() {
    global $wpdb;

    $result = '';
    
    if (isset($_POST['testimonialId']))
            $testimonialId = sanitize_text_field($_POST['testimonialId']);

    $table = $wpdb->prefix.'physiobrite_testimonial';
    $isDeleted = $wpdb->delete( $table, array( 'id' => $testimonialId ) );

    if( $isDeleted ){
        $result = array( 'success' => true );
    }else{
        $result = array( 'success' => false );
    }
    echo wp_json_encode($result);
    wp_die();
}

/*-------Add testimonial-----------*/
add_action( 'wp_ajax_psyb_add_testimonial', 'psyb_add_testimonial' );
add_action( 'wp_ajax_nopriv_psyb_add_testimonial', 'psyb_add_testimonial' );

function psyb_add_testimonial() {
    global $wpdb;

    $result = '';

    if (isset($_POST['testimonialLeadsId']))
            $testimonialLeadsId = $_POST['testimonialLeadsId'];

    if (isset($_POST['testimonialName']))
            $testimonialName = sanitize_text_field($_POST['testimonialName']);

    if (isset($_POST['testimonialCompany']))
            $testimonialCompany = sanitize_text_field($_POST['testimonialCompany']);

    if (isset($_POST['testimonialJobTitle']))
            $testimonialJobTitle = sanitize_text_field($_POST['testimonialJobTitle']);

    if (isset($_POST['testimonialContent']))
            $testimonialContent = sanitize_text_field(stripslashes (htmlspecialchars($_POST['testimonialContent'])));
    //Save the Inquiry
    $table = $wpdb->prefix.'physiobrite_testimonial';

    $data = array(
                'physio_id'       => $testimonialLeadsId,
                'client_name'     => $testimonialName,
                'client_company'  => $testimonialCompany,
                'client_job'      => $testimonialJobTitle,
                'client_content'  => $testimonialContent
            );
    $format = array('%d','%s','%s','%s','%s');

    $insertId = $wpdb->insert($table,$data,$format);
    if ($insertId) {
        $result =  array('success' => true );
    }else{
        $result =  array('success' => false );
    }
    

    echo wp_json_encode($result);
    wp_die();
}

add_action('wp_ajax_data_testimonial_list', 'data_testimonial_list');
add_action('wp_ajax_nopriv_data_testimonial_list', 'data_testimonial_list');
function data_testimonial_list() {
    ob_start();
    global $wpdb;
    $userId = get_current_user_id();
    $leadsId = get_user_meta($userId, 'leads_id', true);

    $requestData= $_REQUEST;

    $columns = array( 
        0 =>    'id',
        1 =>    'client_name', 
        2 =>    'client_company',
        3 =>    'client_job',
        4 =>    'client_content',
    );


    $currentLeadsCredit = get_user_meta( $userId, 'credits', true );

    $tbl_name = $wpdb->prefix . 'physiobrite_testimonial';
        $testimonialList = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM ". $tbl_name ." WHERE physio_id = %d", 
                    $leadsId
                  ));
        $sql = "SELECT * FROM ". $tbl_name ." WHERE physio_id = '" . $leadsId . "' ";

    if( !empty($requestData['search']['value']) ) 
     {
         $sql.=" AND ( client_name LIKE '".$requestData['search']['value']."%' "; 
         $sql.=" OR  client_company LIKE '".$requestData['search']['value']."%' "; 
         $sql.=" OR  client_job LIKE '".$requestData['search']['value']."%' "; 
         $sql.=" OR  client_content LIKE '".$requestData['search']['value']."%') ";
     }

        $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

        $listTestimonial = $wpdb->get_results($sql);
        
        $totalData = count($testimonialList);
        $totalFiltered = $totalData; 

        if(count($listTestimonial) > 0){
            $dataTestimonial = $listTestimonial;
            $i = 1;
            foreach ($dataTestimonial as $testimonialData) {

                $testimonial_view[]= array(
                $i,
                $testimonialData->client_name,
                $testimonialData->client_company,
                $testimonialData->client_job,
                '<button class="access-testimonial" data-fieldinfo=\''.htmlspecialchars(wp_json_encode($testimonialData), ENT_QUOTES, "UTF-8").'\'" data-id="'.$testimonialData->id.'" data-expanded="false" data-info-loaded="false"><i class="fa fa-eye"></i></button><button class="delete-testimonial" data-id="'.$testimonialData->id.'" data-expanded="false" data-info-loaded="false"><i class="fa fa-trash"></i></button>'
                );
                $i++;
            }
        }
        else {
            $testimonial_view[]= array('','No Result Found','','','');
        }

    $json_data = array(
        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), 
        "data"            => $testimonial_view,   // total data array
    );

    echo wp_json_encode($json_data); 
 
    wp_die();
    }

/*Save paypal payment details*/
add_action( 'wp_ajax_psyb_paypal_transaction', 'psyb_paypal_transaction' );
add_action( 'wp_ajax_nopriv_psyb_paypal_transaction', 'psyb_paypal_transaction' );

function psyb_paypal_transaction(){
    global $wpdb;

    $result = '';

    if (isset($_POST['physio_id']))
            $physio_id = $_POST['physio_id'];

    if (isset($_POST['paypal_payerID']))
            $paypal_payerID = $_POST['paypal_payerID'];

    if (isset($_POST['physio_name']))
            $physio_name = $_POST['physio_name'];

    if (isset($_POST['physio_email']))
            $physio_email = $_POST['physio_email'];

    if (isset($_POST['transaction_type']))
            $transaction_type = $_POST['transaction_type'];

    if (isset($_POST['amount']))
            $amount = $_POST['amount'];

    if (isset($_POST['order_id']))
            $order_id = $_POST['order_id'];
  
  	if (isset($_POST['physio_city']))
            $physio_city = $_POST['physio_city'];
  
  	if (isset($_POST['user_email']))
            $user_email = $_POST['user_email'];
  
  	$currUser = get_userdata( $physio_id );
    $currEmail = $currUser->user_email;
	$data = array();

    $table = $wpdb->prefix.'physiobrite_payment_trasaction';

    if( $transaction_type == 'upgrade_account' ){
        $data = array(
                    'physio_id'         => $physio_id,
                    'paypal_payerID'    => $paypal_payerID,
                    'physio_name'       => $physio_name,
                    'physio_email'      => $physio_email,
                    'transaction_type'  => $transaction_type,
                    'amount'            => $amount,
                    'date_created'      => current_time('mysql', 1),
                    'order_id'          => $order_id
                );

        $format = array('%d','%s','%s','%s','%s', '%s', '%s', '%s');

        $insertId = $wpdb->insert($table,$data,$format);
      
      	$tablePromoted = $wpdb->prefix.'physiobrite_featured_physio';
      	$dataPromoted = array(
                    	'physio_city'       => $physio_city,
                    	'physio_name'       => $physio_name,
          				'physio_id'         => $physio_id,
                    	'physio_email'      => $user_email,
                    	'date_created'  	=> current_time('mysql', 1),
                		);

        $formatPromoted = array('%s','%s','%d','%s','%s');

        $insertIdPromoted = $wpdb->insert($tablePromoted,$dataPromoted,$formatPromoted);
      	$tbl_featured = $wpdb->prefix .'physiobrite_featured_physio';
      	$getExpiration = $wpdb->get_row($wpdb->prepare(
          'SELECT *, DATE_ADD(date_created, INTERVAL 1 MONTH) as date_expired FROM '. $tbl_featured . ' WHERE physio_id = %d',
          $physio_id
        ));
		$expiration_date = date("F, j Y",strtotime($getExpiration->date_expired));
        if( $insertId ){
            $result = array( 
                        'success'           => true,
                        'transaction_type'  => $transaction_type,
              			'expiration_date'	=> $expiration_date
                        );
            update_user_meta($physio_id, 'is_promoted', '1');
        }

    }elseif( $transaction_type == 'recharge_account' ){
        $data = array(
                    'physio_id'         => $physio_id,
                    'paypal_payerID'    => $paypal_payerID,
                    'physio_name'       => $physio_name,
                    'physio_email'      => $physio_email,
                    'transaction_type'  => $transaction_type,
                    'amount'            => $amount,
                    'order_id'          => $order_id,
                );

        $format = array('%d','%s','%s','%s','%s', '%s', '%s');

        $insertId = $wpdb->insert($table,$data,$format);
      
        if( $insertId ){

            $getCurrentCredit = (float) get_user_meta($physio_id,'credits', true);
            $amount = (float) $amount;

            $totalCredit = ($getCurrentCredit + $amount);

            $totalCredit = number_format((float)$totalCredit, 2, '.', '');


            update_user_meta($physio_id,'credits', $totalCredit);

            $result = array( 
                        'success'           => true,
                        'transaction_type'  => $transaction_type
                        );
          	sendPaypalEmailConfirm( $data, $currEmail );
        }
			
    }else{

        $result = array( 'success' => false );

    }
    echo wp_json_encode($result);
    wp_die();
}

add_action('sendGlobalEnquiries', 'physiobrite_send_global_enquiries_func');
function physiobrite_send_global_enquiries_func() {

    global $wpdb;

    if (isset($_POST['clientName']))
        $clientName = $_POST['clientName'];

    if (isset($_POST['clientAddress']))
        $clientAddress = $_POST['clientAddress'];

    if (isset($_POST['clientTel']))
        $clientTel = $_POST['clientTel'];

    if (isset($_POST['clientEmail']))
        $clientEmail = $_POST['clientEmail'];

    if (isset($_POST['clientTimeCall']))
        $clientTimeCall = $_POST['clientTimeCall'];

    if (isset($_POST['clientOtherContact']))
        $clientOtherContact = $_POST['clientOtherContact'];

    if (isset($_POST['physioCat']))
        $physioCat = $_POST['physioCat'];

    /*if (isset($_POST['clientProblem']))
        $clientProblem = $_POST['clientProblem'];*/

    if (isset($_POST['clientGender']))
        $clientGender = $_POST['clientGender'];

    if (isset($_POST['serviceType']))
        $serviceType = $_POST['serviceType'];


    $table = $wpdb->prefix.'physiobrite_global_client_enquiries';

    $data = array(
                'client_name'           => $clientName,
                'client_address'        => $clientAddress,
                'client_email'          => $clientEmail,
                'client_tel'            => $clientTel,
                'client_time_call'      => $clientTimeCall,
                'client_other_contact'  => $clientOtherContact,
                'physio_cat'            => $physioCat,
                'client_problem'        => '',
                'client_gender'         => $clientGender,
                'service_type'          => $serviceType,
                'date_created'           => current_time('mysql', 1)
            );

    //Save the Inquiry
    $format = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');

    $insertId = $wpdb->insert($table,$data,$format);

    if ( $insertId && sendEnquiryEmail($data) ) {
        swal_message('success', 'Success!', 'Thank you for sending us your Enquiry. We will get back to you as soon as possible.', true, home_url());
        /*$result = array( 
                'success'               => true,
                'sendGlobalEnquiries'   => true
                );*/
    }else{
        swal_message('error', 'Error!', 'There was an error in sending your Enquiry. Please refresh the page and try again.', true, home_url());
        /*$result = array( 
                'success'               => true,
                'sendGlobalEnquiries'   => false
                );*/
    }
    /*echo wp_json_encode($result);*/
    //exit;
}

add_action( 'wp_ajax_psyb_enquiry_paypal_transaction', 'psyb_enquiry_paypal_transaction' );
add_action( 'wp_ajax_nopriv_psyb_enquiry_paypal_transaction', 'psyb_enquiry_paypal_transaction' );

function psyb_enquiry_paypal_transaction(){
    global $wpdb;

    if (isset($_POST['physio_id']))
            $physio_id = $_POST['physio_id'];

    if (isset($_POST['paypal_payerID']))
            $paypal_payerID = $_POST['paypal_payerID'];

    if (isset($_POST['physio_name']))
            $physio_name = $_POST['physio_name'];

    if (isset($_POST['physio_email']))
            $physio_email = $_POST['physio_email'];

    if (isset($_POST['transaction_type']))
            $transaction_type = $_POST['transaction_type'];

    if (isset($_POST['amount']))
            $amount = $_POST['amount'];

    if (isset($_POST['order_id']))
            $order_id = $_POST['order_id'];

    if( $transaction_type == "telephone_consultation" ){

        $table = $wpdb->prefix.'physiobrite_payment_trasaction';

        $data = array(
                    'physio_id'         => $physio_id,
                    'paypal_payerID'    => $paypal_payerID,
                    'physio_name'       => $physio_name,
                    'physio_email'      => $physio_email,
                    'transaction_type'  => $transaction_type,
                    'amount'            => $amount,
                    'date_created'      => current_time('mysql', 1),
                    'order_id'          => $order_id
                );


        $format = array('%d','%s','%s','%s','%s', '%s', '%s', '%s');

        $insertId = $wpdb->insert($table,$data,$format);


        if (isset($_POST['clientName']))
            $clientName = $_POST['clientName'];

        if (isset($_POST['clientAddress']))
            $clientAddress = $_POST['clientAddress'];

        if (isset($_POST['clientTel']))
            $clientTel = $_POST['clientTel'];

        if (isset($_POST['clientEmail']))
            $clientEmail = $_POST['clientEmail'];

        if (isset($_POST['clientTimeCall']))
            $clientTimeCall = $_POST['clientTimeCall'];

        if (isset($_POST['clientOtherContact']))
            $clientOtherContact = $_POST['clientOtherContact'];

        if (isset($_POST['physioCat']))
            $physioCat = $_POST['physioCat'];

        if (isset($_POST['clientProblem']))
            $clientProblem = $_POST['clientProblem'];

        if (isset($_POST['clientGender']))
            $clientGender = $_POST['clientGender'];

        if (isset($_POST['serviceType']))
            $serviceType = $_POST['serviceType'];

        $enquiryData = array(
                    'clientName'            => $clientName,
                    'clientAddress'         => $clientAddress,
                    'clientTel'             => $clientTel,
                    'clientEmail'           => $clientEmail,
                    'clientTimeCall'        => $clientTimeCall,
                    'clientOtherContact'    => $clientOtherContact,
                    'physioCat'             => $physioCat,
                    'clientProblem'         => $clientProblem,
                    'clientGender'          => $clientGender,
                    'serviceType'           => $serviceType
                );
        
        if ( $insertId ) {
            do_action('sendGlobalEnquiries' , $enquiryData);
            $result = array( 
                            'success'               => true,
                            'transaction_type'      => $transaction_type
                            );
        }
        

    }else{

        $result = array( 'success' => false );

    }
    sendPaypalEmailConfirm( $data, $clientEmail );
    echo wp_json_encode($result);
    wp_die();
}

add_action('wp_ajax_data_global_enquiry_table', 'data_global_enquiry_table');
add_action('wp_ajax_nopriv_data_global_enquiry_table', 'data_global_enquiry_table');
function data_global_enquiry_table() {
    ob_start();
    global $wpdb;
    $userId = get_current_user_id();
    $requestData= $_REQUEST;

    $columns = array( 
        0  =>    'id',
        1  =>    'physio_id', 
        2  =>    'physio_cat',
        3  =>    'client_name',
        4  =>    'client_address',
        5  =>    'client_email',
        6  =>    'client_tel',
        7  =>    'client_time_call',
        8  =>    'client_other_contact',
        9  =>    'client_gender',
        10 =>    'client_problem',
        11 =>    'service_type',
        12 =>    'date_created',
        13 =>    'is_viewed',
    );

    $currentLeadsCredit = get_user_meta( $userId, 'credits', true );

    $tbl_name = $wpdb->prefix . 'physiobrite_global_client_enquiries';
    $currentDateTime = current_time('mysql', 1);
    //Only get data that have time diff less than "24:00:00"
    //TIMEDIFF('2019-06-26 08:53:52',date_created) < "24:00:00"
    $sql = "SELECT * FROM ". $tbl_name ." WHERE physio_id IS NULL AND TIMEDIFF('". $currentDateTime ."', date_created) < '23:59:59'";

    if( !empty($requestData['search']['value']) ) 
     {

        $sql.=" AND ( physio_cat LIKE '".$requestData['search']['value']."%' "; 
        //$sql.=" OR  client_name LIKE '".$requestData['search']['value']."%' "; 
        $sql.=" OR  client_address LIKE '".$requestData['search']['value']."%' "; 
        $sql.=" OR  client_gender LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR  service_type LIKE '".$requestData['search']['value']."%' )";
     }

        $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

        $listEnquiry = $wpdb->get_results($sql);

        $totalData = count($sql);
        $totalFiltered = $totalData;

        if( count( $listEnquiry ) > 0 ){
            $dataEnquiry = $listEnquiry;


            foreach ($dataEnquiry as $enquiryData) {
                if( $currentLeadsCredit > 0 && $currentLeadsCredit >= 10 || $enquiryData->is_viewed == 1){

                    $enquiry_view[]= array(
                    //$enquiryData->client_name,
                    $enquiryData->client_address,
                    $enquiryData->client_gender,
                    $enquiryData->physio_cat,
                    '<button class="btn btn-secondary btn-sm add-to-list" data-id="'.$enquiryData->id.'" data-expanded="false" data-info-loaded="false"><span class="spinner-border spinner-border-sm d-none"></span> Add To List</button>'
                    );

                }else{

                    $enquiry_view[]= array(
                    'Not enough credits',
                    'Not enough credits',
                    'Not enough credits',
                    '<button class="btn btn-secondary btn-sm access-enquiry" data-fieldinfo="' . $enquiryData->is_viewed .'" data-id="'.$enquiryData->id.'" data-expanded="false" data-info-loaded="false"><span class="spinner-border spinner-border-sm d-none"></span> Add To List</button>'
                    );

                }

            }
        }
        else {
            $enquiry_view[]= array('','No Result Founds','','','');
        }

    $json_data = array(
        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), 
        "data"            => $enquiry_view,   // total data array
        "credits"         => $currentLeadsCredit
    );

    echo wp_json_encode($json_data); 
    wp_die();
}

/*=======Move Enquiry to Physio List========*/
add_action( 'wp_ajax_psyb_move_enquiry', 'psyb_move_enquiry' );
add_action( 'wp_ajax_nopriv_psyb_move_enquiry', 'psyb_move_enquiry' );

function psyb_move_enquiry() {
    global $wpdb;

    $result = '';
    
    if (isset($_POST['globalEnquiryid']))
            $global_enquiry_id = sanitize_text_field($_POST['globalEnquiryid']);

    if (isset($_POST['userId']))
            $userId = sanitize_text_field($_POST['userId']);

    $globalEnquiryTable = $wpdb->prefix.'physiobrite_global_client_enquiries';
    $localEnquiryTable = $wpdb->prefix.'physiobrite_client_enquiries';

    $globalEnquiryInfo = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM ". $globalEnquiryTable ." WHERE ID = %d", 
                    $global_enquiry_id
                  ));

    //$wpdb->query("UPDATE $globalEnquiryTable SET physio_id='$userId' WHERE ID='$global_enquiry_id'");
    $wpdb->delete( $globalEnquiryTable, array( 'id' => $global_enquiry_id ) );

    $data = array(
                'physio_id'             => $userId,
                'physio_cat'            => $globalEnquiryInfo->physio_cat,
                'client_name'           => $globalEnquiryInfo->client_name,
                'client_address'        => $globalEnquiryInfo->client_address,
                'client_email'          => $globalEnquiryInfo->client_email,
                'client_tel'            => $globalEnquiryInfo->client_tel,
                'client_time_call'      => $globalEnquiryInfo->client_time_call,
                'client_other_contact'  => $globalEnquiryInfo->client_other_contact,
                'service_type'          => $globalEnquiryInfo->service_type,
                'client_gender'         => $globalEnquiryInfo->client_gender,
                'client_problem'        => $globalEnquiryInfo->client_problem,
                'date_created'         => $globalEnquiryInfo->date_created,
                'date_modified'         => current_time('mysql', 1)
            );
    $format = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');

    $insertId = $wpdb->insert($localEnquiryTable,$data,$format);

    if( $insertId ){
        $result = array( 'success' => true );
    }else{
        $result = array( 'success' => false );
    }
    echo wp_json_encode($result);
    wp_die();
}

function lookForDuplicateHCPC( $hcpc_id = '' ){
    global $wpdb;
    if ($hcpc_id !== '') {
        $table_unverified = $wpdb->prefix.'physiobrite_unverified_user_data';
        $table_usermeta = $wpdb->prefix.'usermeta';
        $data = $wpdb->get_results( $wpdb->prepare( "SELECT hcpc_id FROM $table_unverified WHERE hcpc_id = %s", $hcpc_id ), true );
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT hcpc_id FROM $table_usermeta WHERE meta_value = %s", $hcpc_id ), true );

        if( count($data) > 0 || count($result) > 0 ) {
            return true;
        }
    } 
}

add_action( 'register_service_provider', 'register_service_provider_func');
function register_service_provider_func(){
    global $wpdb;

    $isError        = 0;
    $post_id        = '';
    $user_email     = '';
    $rand_passwod   = '';

    if (isset($_POST['username']))
        $username = sanitize_text_field($_POST['username']);

    if (isset($_POST['inputFirstName']))
        $firstname = sanitize_text_field($_POST['inputFirstName']);

    if (isset($_POST['inputLastName']))
        $lastname = sanitize_text_field($_POST['inputLastName']);

    if (isset($_POST['inputEmail']))
        $user_email = sanitize_text_field($_POST['inputEmail']);

    if (isset($_POST['inputHcpc']))
        $hcpc_id = sanitize_text_field($_POST['inputHcpc']);

    if (isset($_POST['store_locator_website']))
        $user_website = sanitize_text_field($_POST['store_locator_website']);

    $staffInfo = array();
    if ( isset( $_POST['inputStaffName'] ) && isset( $_POST['inputStaffEmail'] ) && isset( $_POST['inputStaffHcpc'] ) ){
       foreach ($_POST["inputStaffName"] as $key => $value) {
            $item = $value . ',' . $_POST["inputStaffEmail"][$key] . ',' . $_POST["inputStaffHcpc"][$key];
            array_push($staffInfo, $item);
       }
    }else{
        $staffInfo = '';
    }

    /**
    *   Check if the email or username exist
    */
    $table_unverified = $wpdb->prefix.'physiobrite_unverified_user_data';
    
    $email_exist = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM ". $table_unverified ." WHERE user_email = %s", 
        $user_email
      ));

    $user_exist = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM ". $table_unverified ." WHERE user_login = %s", 
        $username
      ));

    if( count( $email_exist ) > 0 || email_exists( $user_email) ) 
    {
		//count( $email_exist ) > 0 ||
        $isError = 1;
        swal_message('info', 'Email exist!', 'The email that you have entered is already taken.');
    
    }


    else if( count( $user_exist ) > 0 || username_exists( $username ) )

    {
      	//count( $user_exist ) > 0 ||
        $isError = 1;
        swal_message('info', 'Username exist!', 'The username that you have entered is already taken.');

    }

    else

    {

        if( $isError == 1 ){
            swal_message('error', 'Error!', 'Something went wrong in creating your account.');
        }
        $url = get_bloginfo('url');

        $rand_passwod=wp_generate_password(20);

        $rand_passwod=preg_replace("/[^a-zA-Z]/", "", $rand_passwod);

        $table = $wpdb->prefix.'physiobrite_unverified_user_data';

        //Generate password
        $generated_pass = wp_generate_password();

        $data = array(
                    'user_login'    => $username,
                    'user_pass'     => $generated_pass,
                    'user_url'      => $user_website,
                    'user_email'    => $user_email,
                    'first_name'    => $firstname,
                    'last_name'     => $lastname,
                    'rand_password' => $rand_passwod,
                    'hcpc_id'       => $hcpc_id,
                    'role'          => 'physiobrite_leads',
                    'date_created'  => current_time('mysql', 1) 
                );

        $format = array('%s','%s','%s','%s','%s','%s','%s','%s');

        $user_id = $wpdb->insert($table,$data,$format);
        $currUserId = $wpdb->insert_id;

        //$user_id = wp_insert_user( $userdata ) ;

        
        if($user_id){


            $table_name = $wpdb->prefix . 'users';

            //$wpdb->query("UPDATE $table_name SET user_activation_key='$rand_passwod' WHERE ID=$user_id");


            //update_user_meta($user_id,'credits', '30'); //Update the credits of the user

            //update_user_meta($user_id,'is_promoted', '0'); //Update the promoted status of user
            
            /*==========Create Post============*/

            $lead_information = array(
                'post_title' => wp_strip_all_tags($_POST['inputFirstName'] . ' ' . $_POST['inputLastName']),
                'post_type' => 'unverified_users',
                'post_status' => 'publish'
            );

            // update post meta
            $post_id = wp_insert_post($lead_information);

            update_post_meta($post_id, 'physio_id', $currUserId);
            //update_user_meta($user_id,'store_id',$post_id);
            update_post_meta($post_id, 'is_verification_sent', 0);

            $data = array( 'store_id' => $post_id );

            $where = array( 'id' => $currUserId );

            $updated = $wpdb->update( $table, $data, $where );

            $attachment_id = '';
            if( $_FILES['imageUpload']['name'] !== '' ){
                $attachment_id = media_handle_upload( 'imageUpload', $post_id );
                if ( is_wp_error( $attachment_id ) ) {
                    swal_message('error', 'Error!', 'There was an error in uploading the image.');
                }
            }
               
            /*if (isset($_POST['inputCategory']))
                wp_set_post_terms( $post_id, array( $_POST['inputCategory'] ), 'store_locator_category');*/

            if (isset($_POST['inputCategory'])){
                $categoryInput = $_POST['inputCategory'];
                $arrCats = array();

                foreach ($categoryInput as $inputCat) {
                    $intCatId = (int)$inputCat;
                    wp_set_post_terms( $post_id, $intCatId, 'store_locator_category');
                    $arrCat = get_term_by( 'id', $intCatId, 'store_locator_category' );
                    $arrCats[] .= $arrCat->name;
                }
                update_post_meta($post_id, 'store_locator_category', $arrCats);
            }

            $leadsSiteUrl = site_url() . '/leads-info/?physio_id='.$post_id;



            if (isset($_POST['inputFirstName']) || isset($_POST['inputLastName'])){
                $firstLastName = sanitize_text_field($_POST['inputFirstName'] . ' ' . $_POST['inputLastName']);
                update_post_meta($post_id, 'store_locator_name', $firstLastName);
            }

            if (isset($_POST['inputGender'])){
                $postGender = sanitize_text_field($_POST['inputGender']);
                update_post_meta($post_id, 'store_locator_gender', $postGender);
            }

            if (isset($_POST['store_locator_address'])){
                $postLocatorAddress = sanitize_text_field($_POST['store_locator_address']);
                update_post_meta($post_id, 'store_locator_address', $postLocatorAddress);
            }

            if (isset($_POST['store_locator_lat'])){
                $postLocatorLat = sanitize_text_field($_POST['store_locator_lat']);
                update_post_meta($post_id, 'store_locator_lat',  $postLocatorLat);
            }

            if (isset($_POST['store_locator_lng'])){
                $postLocatorLng = sanitize_text_field($_POST['store_locator_lng']);
                update_post_meta($post_id, 'store_locator_lng', $postLocatorLng);
            }

            if (isset($_POST['store_locator_country'])){
                $postLocatorCountry = sanitize_text_field($_POST['store_locator_country']);
                update_post_meta($post_id, 'store_locator_country', $postLocatorCountry);
            }

            if (isset($_POST['store_locator_state'])){
                $postLocatorState = sanitize_text_field($_POST['store_locator_state']);
                update_post_meta($post_id, 'store_locator_state', $postLocatorState);
            }

            if (isset($_POST['store_locator_city'])){
                $postLocatorCity = sanitize_text_field($_POST['store_locator_city']);
                update_post_meta($post_id, 'store_locator_city', $postLocatorCity);
            }

            if (isset($_POST['inputPhone'])){
                $postInputPhone = sanitize_text_field($_POST['inputPhone']);
                update_post_meta($post_id, 'store_locator_phone',  $postInputPhone);
            }

            //if (isset($_POST['store_locator_website']))
                update_post_meta($post_id, 'store_locator_website', $leadsSiteUrl);

            if (isset($_POST['store_locator_zipcode'])){
                $postLocatorZipcode = sanitize_text_field($_POST['store_locator_zipcode']);
                update_post_meta($post_id, 'store_locator_zipcode', $postLocatorZipcode);
            }

            if (isset($_POST['store_locator_days']))
                update_post_meta($post_id, 'store_locator_days', $_POST['store_locator_days']);

            if (isset($_POST['store_locator_description'])){
                $postLocatorZipcode = sanitize_textarea_field($_POST['store_locator_zipcode']);
                update_post_meta($post_id, 'store_locator_description', $_POST['store_locator_description']);
            }

            if (isset($_POST['store_locator_gform']))
                update_post_meta($post_id, 'store_locator_gform', $_POST['store_locator_gform']);

            //if ( isset( $_POST['inputStaffName'] ) && isset( $_POST['inputStaffHcpc'] ) ){
                update_post_meta($post_id, 'store_locator_staff_members', $staffInfo);
            //}
        }

        if ( !is_wp_error( $post_id ) AND !is_wp_error( $user_id ) ) {
                unset($_SESSION['register_data']);
                sendEmailAccountVerification( $post_id, $user_email, $rand_passwod );
                //sendRegisterEmail( $post_id, $user_email, $rand_passwod );
                if (lookForDuplicateHCPC($hcpc_id)) {
                    echo '<p class="text-center text-danger"><strong>A duplicate HCPC ID is found. Please <a href="#">contact us</a>.</strong></p>';
                }
                
        }
        else{
            swal_message('error', 'Error!', 'There is an error in creating your account.');
        }
    }
}

add_action( 'insert_hcpc_id', 'insert_hcpc_id_func' );
function insert_hcpc_id_func(){
    global $wpdb;
    if ( isset($_POST['store_id']) ):

       // $hcpc_id = $_POST['hcpc_id'];
        $store_id = $_POST['store_id'];

        $table = $wpdb->prefix.'physiobrite_unverified_user_data';
        
        $currentUserData = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM ". $table ." WHERE store_id = %d", 
            $store_id
          ));
        if ( $currentUserData->is_verified == "0" ) :
            
            $data = array( 'is_verified' => 1 );

            $where = array( 'store_id' => $store_id );

            $updated = $wpdb->update( $table, $data, $where );

            if( $updated ):
                swal_message('success', 'Success!', 'Thank you verifying your account, once our admin staff verified your account an email will be sent to you.' );
                echo "<p class='text-center pt-3'>Thank you verifying your account, once our admin staff verified your account an email will be sent to you.</p>";
            endif;
        else:
            swal_message('warning', 'Warning!', 'This account has already been verified!' );
        endif;
    else: 
        swal_message('error', 'Something went wrong!', 'Please refresh the page and try again.');
        wp_die();
    endif;
}

add_action( 'renew_account_submit', 'renew_account_submit_func' );
function renew_account_submit_func(){
    global $wpdb;
    if ( isset($_POST['store_id']) ):

        $store_id = $_POST['store_id'];

        $table = $wpdb->prefix.'physiobrite_expired_user_data';
        
        $currentUserData = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM ". $table ." WHERE store_id = %d", 
            $store_id
          ));
        if ( $currentUserData->is_verified == 0 ) :
            
            $data = array( 'is_verified' => 1 );

            $where = array( 'store_id' => $store_id );

            $updated = $wpdb->update( $table, $data, $where );

            if( $updated ):
                swal_message('success', 'Success!', 'Thank you for renewing your Physiobrite Account once our admin staff reviewed your account an email will be sent to you.' );
                echo "Thank you for renewing your Physiobrite Account once our admin staff reviewed your account an email will be sent to you.";
            endif;
        else:
            swal_message('warning', 'Warning!', 'This account has already been verified.' );
        endif;
    else: 
        swal_message('error', 'Something went wrong!', 'Please refresh the page and try again.');
        wp_die();
    endif;
}


add_action( 'admin_post_verify_user_account', 'verify_user_account_admin_action' );
function verify_user_account_admin_action(){
    global $wpdb;
    
    $store_id = '';
  	$currentUser = '';
  	$userdata = array();
    if ( isset($_GET['store_id']) ):
        $store_id = $_GET['store_id'];

        $update_post = array(

              'ID'          => $store_id,
              'post_type'   => 'store_locator',

          );

        $updatePost = wp_update_post( $update_post );
        
        $table_unverified = $wpdb->prefix.'physiobrite_unverified_user_data';

        if( $updatePost ):

            
            $currentUser = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM ". $table_unverified ." WHERE store_id = %d", 
                $store_id
              ));

            //Generate New Password
            $generatedPass = wp_generate_password();

            $userdata = array(
                        'user_login'            => $currentUser->user_login,
                        'user_pass'             => $generatedPass,
                        'user_url'              => $currentUser->user_url,
                        'user_email'            => $currentUser->user_email,
                        'first_name'            => $currentUser->first_name,
                        'last_name'             => $currentUser->last_name,
                        'show_admin_bar_front'  => 'false',
                        'role'                  => 'physiobrite_leads',
                    );


            $user_id = wp_insert_user( $userdata ) ;

            if( $user_id ):

                //$randPass = $currentUser->rand_password;
  				$randPass = $generatedPass;
                $table_users = $wpdb->prefix . 'users';

                $wpdb->query("UPDATE $table_users SET user_activation_key='$randPass' WHERE ID=$user_id");

                update_post_meta($store_id, 'physio_id', $user_id);
                
                if( lookForDuplicateHCPC($hcpc_id) ){
                    
                    update_user_meta($user_id,'credits', '0.00');

                }else{
                    
                    update_user_meta($user_id,'credits', '30.00');

                }
                update_user_meta($user_id,'is_promoted', '0');
                update_user_meta($user_id,'leads_id',$store_id);
                update_user_meta($user_id,'hcpc_id', $currentUser->hcpc_id);

                $data = array( 'is_verified' => 1 );
                $where = array( 'store_id' => $store_id );
                $verifiedUpdate = $wpdb->update( $table_unverified, $data, $where );

                sendAccountVerificationEmail( $currentUser->user_email, $userdata );
                update_post_meta($store_id, 'is_verification_sent', 1);

                $wpdb->delete( $table_unverified, array( 'store_id' => $store_id ) );

                physio_admin_notice('success');
                
                /*print_r($userdata['user_pass']);
                wp_die();*/
            endif;
        endif;
        physio_admin_notice('error');
    endif;
    redirect_me_to($_SERVER['HTTP_REFERER']);
    exit();
}

add_action('wp_ajax_psyb_resend_email', 'psyb_resend_email');
add_action('wp_ajax_nopriv_psyb_resend_email', 'psyb_resend_email');
function psyb_resend_email() {
    if (isset($_POST['dataEmail']))
        $dataEmail = $_POST['dataEmail'];
    if (isset($_POST['dataTxt']))
        $dataTxt = $_POST['dataTxt'];

    if ( $dataTxt !== '' && $dataEmail !== '' ) {
        
        resendRegisterVerification($dataTxt, $dataEmail);
    
    }
    wp_die();
}


add_action( 'admin_post_renew_user_account', 'renew_user_account_admin_action' );
function renew_user_account_admin_action(){
    global $wpdb;

    $store_id = '';
    if ( isset($_GET['the_user_id']) ):
        $store_id = $_GET['the_user_id'];

        $update_post = array(

              'ID'          => $store_id,
              'post_type'   => 'store_locator',

          );

        $updatePost = wp_update_post( $update_post );
        
        $table_expired = $wpdb->prefix.'physiobrite_expired_user_data';

        if( $updatePost ):

            
            $currentUser = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM ". $table_expired ." WHERE store_id = %d", 
                $store_id
              ));

            //Generate New Password
            $generatedPass = wp_generate_password();

            $userdata = array(
                        'user_login'            => $currentUser->user_login,
                        'user_pass'             => $generatedPass,
                        'user_url'              => $currentUser->user_url,
                        'user_email'            => $currentUser->user_email,
                        'first_name'            => $currentUser->first_name,
                        'last_name'             => $currentUser->last_name,
                        'show_admin_bar_front'  => 'false',
                        'role'                  => 'physiobrite_leads',
                    );

            $user_id = wp_insert_user( $userdata ) ;

            if( $user_id ):

                $rand_passwod=wp_generate_password(20);
                $rand_passwod=preg_replace("/[^a-zA-Z]/", "", $rand_passwod);

                $randPass = $rand_passwod;
                $table_users = $wpdb->prefix . 'users';
  				$table_p_enquiries = $wpdb->prefix . 'physiobrite_client_enquiries';
  				$oldId = get_post_meta($store_id, 'physio_id', true);

                $wpdb->query("UPDATE $table_users SET user_activation_key='$randPass' WHERE ID=$user_id");
  				
  				$dataEnquiry = array( 'physio_id' => $user_id );
                $whereEnquiry = array( 'physio_id' => $oldId );
                $updateEnquiryId = $wpdb->update( $table_p_enquiries, $dataEnquiry, $whereEnquiry );
  				
  				$table_featured = $wpdb->prefix . 'physiobrite_featured_physio';
  				$dataFeatured = array( 'physio_id' => $user_id );
                $whereFeatured = array( 'physio_id' => $oldId );
                $updateFeaturedId = $wpdb->update( $table_featured, $dataFeatured, $whereFeatured );

                update_post_meta($store_id, 'physio_id', $user_id);

                update_user_meta($user_id,'credits', $currentUser->credits);
                update_user_meta($user_id,'is_promoted', $currentUser->is_promoted);
                update_user_meta($user_id,'leads_id', $currentUser->leads_id);
                update_user_meta($user_id,'hcpc_id', $currentUser->hcpc_id);

                $data = array( 'is_verified' => 1 );
                $where = array( 'store_id' => $store_id );
                $verifiedUpdate = $wpdb->update( $table_expired, $data, $where );

                sendRenewAccountVerificationEmail( $currentUser->user_email, $userdata );
                update_post_meta($store_id, 'is_verification_sent', 1);

                $wpdb->delete( $table_expired, array( 'store_id' => $store_id ) );

                physio_admin_notice('success');
                
                /*print_r($userdata['user_pass']);
                wp_die();*/
                
            endif;
        endif;
        physio_admin_notice('error');
    endif;
    redirect_me_to($_SERVER['HTTP_REFERER']);
    exit();
}

add_action( 'admin_post_resend_verify_email', 'resend_verify_email_admin_action' );
function resend_verify_email_admin_action(){
    global $wpdb;

    $store_id = '';
    if ( isset($_GET['store_id']) ):
        $store_id = $_GET['store_id'];

        $theTable = $wpdb->prefix . 'physiobrite_unverified_user_data';
        $data = $wpdb->get_row($wpdb->prepare(
                "SELECT user_email, rand_password FROM ". $theTable ." WHERE store_id = %d", 
                $store_id
              ));

        if( $data ):
            sendRegisterEmailAgain( $store_id, $data->user_email, $data->rand_password );
            physio_admin_notice('success');
        else:
            physio_admin_notice('error');
        endif;

    endif;
    redirect_me_to($_SERVER['HTTP_REFERER']);
    exit();
}

/*add_action( 'admin_post_resend_account_info_email', 'resend_account_info_email_admin_action' );
function resend_account_info_email_admin_action(){
    global $wpdb;

    $store_id = '';
    if ( isset($_GET['user_id']) ):
        $user_id = $_GET['user_id'];

        $user_meta=get_userdata( $user_id );
        $first_name = get_user_meta( $user_id, 'first_name', true );


        if( $user_meta ):
            
            $userdata = array(
                'user_login'            => $user_meta->data->user_login,
                'user_pass'             => $user_meta->data->user_activation_key,
                'first_name'            => $first_name,
            );

            sendAccountVerificationEmail( $user_meta->data->user_email, $userdata);

            physio_admin_notice('success');
        else:
            physio_admin_notice('error');
        endif;

    endif;
    redirect_me_to($_SERVER['HTTP_REFERER']);
    exit();
}*/

//add_action( "delete_user_data", "delete_user_data_func" );
add_action( 'wp_ajax_delete_user_data', 'delete_user_data_func' );
add_action( 'wp_ajax_nopriv_delete_user_data', 'delete_user_data_func' );
function delete_user_data_func(){
    global $wpdb;

    $tbl_users = $wpdb->prefix . 'users';
    $tbl_usermeta = $wpdb->prefix . 'usermeta';
    $tbl_deletedTable = $wpdb->prefix . 'physiobrite_deleted_account_data';

    if ( isset($_POST['userId'] ) ):

        $userId = $_POST['userId'];

        $getSingleLeadsInfo = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM ". $tbl_users ." WHERE ID = %d", 
                        $userId
                      ));

        $getUserInfoById = get_user_by('ID', $userId);

        $first_name = get_user_meta( $userId, 'first_name', true );
        $last_name = get_user_meta( $userId, 'last_name', true );
        $hcpc_id = get_user_meta( $userId, 'hcpc_id', true );
        $role = $getUserInfoById->roles[0];
        $credits = get_user_meta( $userId, 'credits', true );
        $credits = get_user_meta( $userId, 'credits', true );
        $leads_id = get_user_meta( $userId, 'leads_id', true );
        $is_promoted = get_user_meta( $userId, 'is_promoted', true );

        if ($getSingleLeadsInfo) :
            
            $rand_passwod=wp_generate_password(20);

            $rand_passwod=preg_replace("/[^a-zA-Z]/", "", $rand_passwod);
            
            //Insert to expired user table
            $data = array(
                'store_id'      => $leads_id,
                'user_login'    => $getSingleLeadsInfo->user_login,
                'user_pass'     => $getSingleLeadsInfo->user_pass,
                'user_url'      => $getSingleLeadsInfo->user_url,
                'user_email'    => $getSingleLeadsInfo->user_email,
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'rand_password' => $rand_passwod,
                'hcpc_id'       => $hcpc_id,
                'role'          => $role,
                'date_deleted'  => current_time('mysql', 1),
                'credits'       => $credits,
                'leads_id'      => $leads_id,
                'is_promoted'   => $is_promoted,
                'is_verified'   => 0,
            );

            $format = array('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%d','%d');

            //$insertId = $wpdb->insert($tbl_expiredUsers,$data,$format);
            $insertId = $wpdb->insert($tbl_deletedTable,$data,$format);

            if ($insertId) :

                $update_post = array(
                      'ID'          => $leads_id,
                      'post_type'   => 'deleted_accounts',
                  );

                require_once(ABSPATH.'wp-admin/includes/user.php' );
                wp_delete_user( $userId );
                
                $updatePost = wp_update_post( $update_post );

                //swal_message('success', 'Success!', 'Account Deleted', true, home_url());
                $result = array(
                    'success'   => true,
                );

            else:

                $result = array(
                    'success'   => false,
                );

            endif; // ($insertId)
            
        endif; // ($getSingleLeadsInfo)

    endif; //isset $_POST['delete_data']

    
    echo wp_json_encode($result);
    wp_die();
    
}

?>
