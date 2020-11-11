jQuery(document).ready(function(){
	
	var store_locator_search_form = '';
	var store_locator_id = jQuery("div#store-locator-id").attr('style');
	var map_listings_left = jQuery("#store-locator-id .map-listings.left").attr('style');
	var map_container = jQuery("div#map-container").attr('style');
	
	
	jQuery("input#store_locatore_search_btn").bind("click",function(){
		
	var store_locatore_search_lat = jQuery("input#store_locatore_search_lat").val();
	var store_locatore_search_lng = jQuery("input#store_locatore_search_lng").val();
	var store_locatore_search_radius = jQuery("select#store_locatore_search_radius").val();;
	var store_locator_category = jQuery("select#wpsl_store_locator_category").val();;
	
		
    jQuery.ajax({
         type : "post",
         dataType : "json",
         url : searchFeature.ajaxurl,
         data : {	
					'action' 					: "show_featured_physio" ,
					'store_locatore_search_lat' : store_locatore_search_lat, 
					'store_locatore_search_lng' : store_locatore_search_lng, 
					'store_locatore_search_radius' : store_locatore_search_radius,
					'store_locator_category' : store_locator_category
					
				},
         success: function(response) {
           console.log(response);
           
			if(response.success){
				if(jQuery(".store-search-fields section.featured-physio").length == 0){
					 jQuery(".store-search-fields").prepend(response.html);
				}
				else{
					 jQuery(".store-search-fields section.featured-physio").remove();
					 jQuery(".store-search-fields").prepend(response.html);
				}
				
				
				
				
				
				jQuery("#store_locator_search_form").attr('style' , '   display: inline-block;width: 100%;');
				jQuery("div#store-locator-id").attr('style' , 'height: 900px;width: 100%;');
				jQuery("#store-locator-id .map-listings.left").attr('style' , 'position: initial;display: block !important;padding-top:0px;');
				
				jQuery("div#map-container").attr('style' , 'position: relative;width: 70%;right: 0%;left: 30%;padding-top: 35%;');
				
				
				 jQuery(".store-search-fields section.featured-physio .featured-slider").slick({
				  infinite: true,
				  slidesToShow: 3,
				  slidesToScroll: 3
				}); 
			}
			else{
				jQuery(".store-search-fields section.featured-physio").remove();
				
				
	
				
				jQuery("#store_locator_search_form").attr('style' , store_locator_search_form);
				jQuery("div#store-locator-id").attr('style' , store_locator_id);
				jQuery("#store-locator-id .map-listings.left").attr('style' ,map_listings_left);
				
				jQuery("div#map-container").attr('style' , map_container);
			}
         }
      }) 
})

/* 
jQuery.ajaxComplete(function(event, xhr, options)
{
    var data = jQuery.httpData(xhr,options.dataType);

    console.log(data);
}); */
	
})

