<?php

add_shortcode( 'leads_promoted_count', 'map_test_func' );
function map_test_func(){
  $store_locator_API_KEY = get_option('store_locator_API_KEY');
?>
		 <button onclick="getLocation()">Try It</button>

<p id="demo"></p>

<!--script>
jQuery(document).ready(function($){
	getLocation();
});  

var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}
    
function showPosition(position) {
	var lat = position.coords.latitude;
	var lang = position.coords.longitude;
    x.innerHTML="Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;
	var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + ',' + lang  + "&sensor=false&key=<?= $store_locator_API_KEY ?>";
	jQuery.getJSON(url, function (data) {
	    for(var i=0;i<data.results.length;i++) {

	        var adress = data.results[0].formatted_address;
          var alladdress = data.results[0];
	    }
      	console.log(adress);
      	console.log(alladdress);
	});
}
</script-->

<?php
}


//add_shortcode( 'leads_promoted_count', 'leads_promoted_count_func' );
//function leads_promoted_count_func() {
  
  	//global $wpdb;
  	/*$timestampNow = strtotime( current_time('mysql', 1) );
  
  	$tbl_featured = $wpdb->prefix . 'physiobrite_featured_physio';
	$getAllPromoted = $wpdb->get_results($wpdb->prepare(
        'SELECT *, DATE_ADD(date_created, INTERVAL 1 MONTH) as date_expired FROM '. $tbl_featured
      ));
  
  	if($getAllPromoted):
  
  		foreach($getAllPromoted as $dataGet):
  
  			$dateCreated = strtotime($dataGet->date_created);
  			$dateExpired = strtotime($dataGet->date_expired);

            $diffCreated = ($timestampNow - $dateCreated);

            if ( $diffCreated >= 1800 && $dateModified == 0 ):
  				$sendNotif = sendPromotedEmailExpired($dataGet);
  
  				if( $sendNotif == true ):
  		
  					update_user_meta($dataGet->physio_id, 'is_promoted', 0);

  				endif;
            endif;
  
  		endforeach;
  
  	endif;*/
  //echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';
  /*$tbl_featured = $wpdb->prefix . 'physiobrite_featured_physio';
  $getExpiration = $wpdb->get_row($wpdb->prepare(
          'SELECT *, DATE_ADD(date_created, INTERVAL 1 MONTH) as date_expired FROM ' . $tbl_featured . ' WHERE physio_id = %d',
          15
        ));
	$expiration_date = date("F, j Y",strtotime($getExpiration->date_expired));
  dd($wpdb->prefix);
  dd($tbl_featured);
  dd($getExpiration);
  dd($expiration_date);*/
//}

/*add_shortcode( 'leads_promoted_count', 'leads_promoted_count_func' );
function leads_promoted_count_func() {
  	global $wpdb;
	$getPromotedPerCity = $wpdb->get_results($wpdb->prepare(
        'SELECT COUNT(*) as featured_count, physio_city FROM '. $wpdb->prefix .'physiobrite_featured_physio WHERE physio_city = %s',
      	'England'
      ));
  	return (empty($getPromotedPerCity[0]->featured_count) ? 0 : $getPromotedPerCity[0]->featured_count);
}*/


/*function get_featured_physio_count($city='') {
  	global $wpdb;
  	$storeArray = array();
	$getAllPromotedId = $wpdb->get_results($wpdb->prepare(
        'SELECT user_id FROM '. $wpdb->prefix .'usermeta where meta_key = %s', 
        'is_promoted'
      ));
      if( $getAllPromotedId ):
      	$getDataPromoted = $getAllPromotedId;
        foreach( $getDataPromoted as $dataGetPromoted ):
      		$userId = $dataGetPromoted->user_id;
  			$storeId = get_user_meta( $userId, 'leads_id', true );
			$storeArray[] = $storeId;
      	endforeach;
  		$storeString = implode(",",$storeArray);
    $getStoreCount = $wpdb->get_results($wpdb->prepare(
                "SELECT COUNT(*) as featured_count, meta_value FROM " . $wpdb->prefix ."postmeta WHERE post_id IN (". $storeString .") AND meta_value = %s AND meta_key=%s GROUP BY meta_value",
              $city, 'store_locator_city'
              ));
  return (empty($getStoreCount[0]->featured_count) ? 0 : $getStoreCount[0]->featured_count);
      endif;
}*/

?>