<?php 

add_action( 'admin_footer', 'expired_users_scripts_func' );
function expired_users_scripts_func(){
	if ( get_post_type() === 'expired_users' ) {
		
		echo "<script type='text/javascript'>jQuery('.edit_unverified a').text('View');</script>";
		
	}
}

add_filter( 'post_row_actions', 'remove_row_actions_expired', 10, 1 );
function remove_row_actions_expired( $actions )
{
    if( get_post_type() === 'expired_users' ){
        unset( $actions['view'] );
        $editAction = '<span class="edit_unverified">' . $actions['edit'] . '</span>';
        $actions['edit'] = $editAction ;
        unset( $actions['inline hide-if-no-js'] );
    }
    return $actions;
}

//manage custom coulmns display for stores
add_filter('manage_edit-expired_users_columns', 'expired_users_columns');
function expired_users_columns($columns) {
  	echo '<style>.padmin-verify-button{
  background-color: #222;
    font-weight: 600;
    border: 0;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -webkit-box-shadow: none;
    box-shadow: none;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-size: 0.875rem;
    line-height: 1;
    font-weight: normal;
    padding: 1em 2em;
    text-shadow: none;
    -webkit-transition: background 0.2s;
    transition: background 0.2s;
    font-family: inherit;
}.padmin-verify-button:hover {
    color: #fff;
}</style>';
    unset(
        $columns['date']
    );
    $new_columns = array(
        'title' => __('Name', 'wpmsl'),
        'address' => __('Address', 'wpmsl'),
      	'status' => __('Status', 'wpmsl'),
      	'activate' => __('Activate', 'wpmsl'),
      	'date' => __('Date', 'wpmsl'),
    );
    return array_merge($columns, $new_columns);
}

//manage custom coulmns content display for Stores
add_filter('manage_expired_users_posts_custom_column', 'manage_expired_users_columns', 10, 2);
function manage_expired_users_columns($column, $post_id) {
    global $post;
    global $wpdb;
    $tbl_name = $wpdb->prefix . 'physiobrite_expired_user_data';
    $currentUser = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM ". $tbl_name ." WHERE store_id = %d", 
                    $post_id
                  ));
    switch ($column) {

        case 'address':
        $meta = get_post_meta($post_id);
        echo $meta['store_locator_address'][0] . " " . $meta['store_locator_city'][0] . " " . $meta['store_locator_state'][0] . " " . $meta['store_locator_country'][0] . " " . $meta['store_locator_zipcode'][0];
        break;

        case 'status':
        	echo ( $currentUser->is_verified == 0 ) ? '<p style="padding: 10px 15px;text-align: center;width: 115px;color: #fff !important;font-weight: 600;background-color: #00a0d2;">User Notified</p>' : '<p style="padding: 10px 15px;text-align: center;width: 115px;color: #fff !important;font-weight: 600;background-color: #46b450;">Renew my Account</p>';
        break;

        case 'activate':

	        ?>
			<a class="padmin-verify-button" <?= ( $currentUser->is_verified == 0 ) ? 'style="background-color: #ababab;"' : 'href="' . home_url() . '/wp-admin/admin-post.php?action=renew_user_account&the_user_id=' . $post_id . '"'; ?>>
						Renew Account
					</a>

        <?php

        break;
        case 'date':
        	echo $columns['date'];
        break;

        default :
        break;
    }
}

add_action('add_meta_boxes', 'add_expired_users_meta');
function add_expired_users_meta() {
    add_meta_box('store-info',__('User Info','wpmsl'), 'expired_users_meta_box_callback_store_info', 'expired_users');
    add_meta_box('user-address',__('User Address','wpmsl'), 'expired_users_meta_box_callback_address_info', 'expired_users');
  
}

function expired_users_meta_box_callback_store_info($post) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field('expired_users_save_meta_box_data', 'expired_users_meta_box_nonce');
    global $wpdb;
    $post_id = $post->ID;

    $media = get_attached_media('image', $post_id, false);
	        $mediaId = '';
			foreach ($media as $img) {
				$mediaId = $img->ID;
			}
			$mediaImg = wp_get_attachment_url( $mediaId );

	$tbl_name = $wpdb->prefix . 'physiobrite_expired_user_data';
    $currentUser = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM ". $tbl_name ." WHERE store_id = %d", 
        $post_id
      ));

    ?>
    <style type="text/css">
    	#publishing-action, #preview-action{
    		display: none;
    	}
    </style>
    <table class="widefat" style="border: 0px;">
    		<tr>
                <td><?php echo __("Image", 'wpmsl'); ?></td>
                <td>
                    <?php $physioId = get_post_meta($post_id, 'physio_id', true); ?>
                    <img style="width: 150px; height: 150px; border-radius: 10px; border:3px solid #054874;" src="<?= ( !empty($mediaImg) ) ? $mediaImg : 'https://dummyimage.com/360x360/000/fff' ;  ?>">
                </td>
            </tr>
            <tr>
                <td><?php echo __("Name", 'wpmsl'); ?></td>
                <td>
                    <input type="text" value="<?php echo get_post_meta($post_id, 'store_locator_name', true) ? get_post_meta($post_id, 'store_locator_name', true) : ''; ?>" name="store_locator_name"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Credit", 'wpmsl'); ?></td>
                <td>
                    <input type="text" value="<?= (!empty( $currentUser->credits )) ? $currentUser->credits : ''; ?>" name="store_locator_credit"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Promoted", 'wpmsl'); ?></td>
                <td>
                    <input type="text" value="<?= ( $currentUser->is_promoted == 1 ) ? 'Yes' : 'No'; ?>" name="store_locator_promoted"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("HCPC ID", 'wpmsl'); ?></td>
                <td>
                    <input type="text" value="<?= (!empty( $currentUser->hcpc_id )) ? $currentUser->hcpc_id : 'Not Yet Submitted'; ?>" name="store_locator_name"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Gender", 'wpmsl'); ?></td>
                <td>
                    <!--<input type="text" value="<?php echo get_post_meta($post_id, 'store_locator_gender', true) ? get_post_meta($post_id, 'store_locator_gender', true) : ''; ?>" name="store_locator_gender"/>-->
                	<select style="width: 155px;" name="store_locator_gender" id="store_locator_gender">
                        <option value="" ></option>
                      <?php
                        global $wpdb;
                        $allGender = $wpdb->get_results("SELECT * FROM store_locator_gender");
                        $selectedGender = get_post_meta($post_id, 'store_locator_gender', true);
                        foreach ($allGender as $gender) {
                            ?>
                            <option value="<?php echo $gender->gender; ?>" <?php echo ($selectedGender == $gender->gender) ? "selected" : ""; ?>><?php echo $gender->gender; ?></option>
                            <?php
                        }
                        ?>
                      <!--<option value="Male">Male</option>
                      <option value="Female">Female</option>-->
                    </select>
              </td>
            </tr>
            <tr style="display:none;">
                <td><?php echo __("Description", 'wpmsl'); ?></td>
                <td>
                    <?php
                    $content = get_post_meta( $post_id, 'store_locator_description', true );
                    wp_editor( $content, "store_locator_description" );?>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Specialities", 'wpmsl'); ?></td>
                <td>
                    <?php $physio_category = get_post_meta($post_id, 'store_locator_category', true); ?>
                     <?php foreach ($physio_category as $value): ?>
                         <div class="items-category">
                             <strong><?= $value; ?></strong>
                         </div>
                     <?php endforeach ?>
                </td>
            </tr>
            <tr style="display: none;">
                <td><?php echo __("Website", 'wpmsl'); ?></td>
                <td>
                    <input type="text" value="<?= ($currentUser->user_url) ? $currentUser->user_url : ''; ?>" name="store_locator_website"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Working Hours", 'wpmsl'); ?></td>
                <?php
                $days = array(
                    __("Monday","wpmsl"), 
                    __("Tuesday","wpmsl"), 
                    __("Wednesday","wpmsl"), 
                    __("Thursday","wpmsl"), 
                    __("Friday","wpmsl"), 
                    __("Saturday","wpmsl"), 
                    __("Sunday","wpmsl"));
                    $days_meta = get_post_meta($post_id, 'store_locator_days', true);?>
                    <td>
                        <table id="store_locator_hours" style="background-color: rgb(241, 241, 241); border-radius: 5px;">
                            <?php foreach ($days as $day): ?>
                                <tr>
                                    <td style="border-bottom: 1px solid #dbdbdb;"><?php echo $day; ?></td>
                                    <td style="border-bottom: 1px solid #dbdbdb;">
                                        <input <?php echo (isset($days_meta[$day]) && $days_meta[$day]['status'] == '1')?'checked':''; ?> type="radio" value="1" id="store_locator_days_<?php echo $day; ?>_1" name="store_locator_days[<?php echo $day; ?>][status]" > <label for="store_locator_days_<?php echo $day; ?>_1"> <?php _e('Opened','wpmsl'); ?> </label>
                                        <input <?php echo (!isset($days_meta[$day]) || $days_meta[$day]['status'] == '0')?'checked':''; ?> type="radio" value="0" id="store_locator_days_<?php echo $day; ?>_0" name="store_locator_days[<?php echo $day; ?>][status]" /> <label for="store_locator_days_<?php echo $day; ?>_0"><?php _e('Closed','wpmsl'); ?> </label>
                                    </td>
                                    <td style="border-bottom: 1px solid #dbdbdb;">
                                        <input <?php echo (isset($days_meta[$day]) && $days_meta[$day]['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Open Time" type="text" value="<?php echo (isset($days_meta[$day]))?$days_meta[$day]['start']:''; ?>" name="store_locator_days[<?php echo $day; ?>][start]" class="start_time"/>
                                        <input <?php echo (isset($days_meta[$day]) && $days_meta[$day]['status'] == '1')?'':'style="display: none;"'; ?> size="9" placeholder="Close Time" type="text" value="<?php echo (isset($days_meta[$day]))?$days_meta[$day]['end']:''; ?>" name="store_locator_days[<?php echo $day; ?>][end]" class="end_time" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?php echo __("Staff Information", 'wpmsl'); ?></td>
                    <td>
                        <?php 
                          $staff_array = get_post_meta( $post_id, "store_locator_staff_members", true );
                          if (!empty( $staff_array )) {
                              foreach ($staff_array as $key => $value) {
                                  $item = explode(",", $value);
                            ?>
                                    <div class="staff-info" style="border-bottom: 1px solid #eee;">
                                        <p><strong>Name: </strong><?= $item[0] ?></p>
                                        <p><strong>Email: </strong><?= $item[1] ?></p>
                                        <p><strong>HCPC ID: </strong><?= $item[2] ?></p>
                                    </div>
                            <?php
                              }
                          }else{
                            echo "-";
                          }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php
}


function expired_users_meta_box_callback_address_info($post) {
    $post_id = $post->ID;?>
    <table class="widefat" style="border: 0px;">
        <tbody>
            <tr>
                <td><?php echo __("Address", 'wpmsl'); ?></td>
                <td>
                    <input id="store_locator_address" type="text" value="<?php echo get_post_meta($post_id, 'store_locator_address', true); ?>" name="store_locator_address"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Country", 'wpmsl'); ?></td>
                <td>
                    <select style="width: 186px;" name="store_locator_country" id="store_locator_country">
                        <option value="" ></option>
                        <?php
                        global $wpdb;
                        $allCountries = $wpdb->get_results("SELECT * FROM store_locator_country");
                        $selectedCountry = get_post_meta($post_id, 'store_locator_country', true);
                        foreach ($allCountries as $country) {
                            ?>
                            <option value="<?php echo $country->name; ?>" <?php echo ($selectedCountry == $country->name) ? "selected" : ""; ?>><?php echo $country->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr <?php echo ($selectedCountry != "United States")?"style='display: none;'":""; ?> >
                <td><?php echo __("State", 'wpmsl'); ?></td>
                <td>
                    <select style="width: 186px;" name="store_locator_state" id="store_locator_state">
                        <option value="" ></option>
                        <?php
                        global $wpdb;
                        $allStates = $wpdb->get_results("SELECT * FROM store_locator_state");
                        $selectedState = get_post_meta($post_id, 'store_locator_state', true);
                        foreach ($allStates as $state) {
                            ?>
                            <option value="<?php echo $state->name; ?>" <?php echo ($selectedState == $state->name) ? "selected" : ""; ?>><?php echo $state->name; ?></option>
                            <?php
                        }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo __("City", 'wpmsl'); ?></td>
                <td>
                    <input id="store_locator_city" type="text" value="<?php echo get_post_meta($post_id, 'store_locator_city', true) ? get_post_meta($post_id, 'store_locator_city', true) : ''; ?>" name="store_locator_city"/>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Postal Code", 'wpmsl'); ?></td>
                <td>
                    <input id="store_locator_zipcode" type="text" value="<?php echo get_post_meta($post_id, 'store_locator_zipcode', true) ? get_post_meta($post_id, 'store_locator_zipcode', true) : ''; ?>" name="store_locator_zipcode"/>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" value="<?php echo get_post_meta($post_id, 'store_locator_lat', true) ? get_post_meta($post_id, 'store_locator_lat', true) : ''; ?>" name="store_locator_lat" id="store_locator_lat"/>
    <input type="hidden" value="<?php echo get_post_meta($post_id, 'store_locator_lng', true) ? get_post_meta($post_id, 'store_locator_lng', true) : ''; ?>" name="store_locator_lng" id="store_locator_lng"/>
    <div id="map-container" style="position: relative;">
        <div id="map_loader" style="z-index: 9;width: 100%; height: 200px;position: absolute;background-color: #fff;"><div class="uil-ripple-css" style="transform: scale(0.6); margin-left: auto; margin-right: auto;"><div></div><div></div></div></div>
        <div id="map-canvas" style="height: 200px;width: 100%;"></div>
    </div>
    <script>
        jQuery(document).ready(function (jQuery) {
            store_locator_initializeMapBackend();
        });

    </script>
    <?php
   }
?>