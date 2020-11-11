<?php 

	add_shortcode('register_leads_button', 'register_leads_button');
	function register_leads_button(){
		global $wpdb;
		?>
		<div class="container  mb-5 pb-5" style="text-align:center;">
			
			<button type="button" class="btn btn-primary btn-lg mb-2" onClick="redirect_link('referrer');">Register Referrer Account</button>
		</div>
		<script>
		function redirect_link(link){
			if(link == 'physio'){
				window.location.href = "<?php echo get_site_url().'/register/';?>";
				}else{
					window.location.href = "<?php echo get_site_url().'/referrer/';?>";
				}
		}
		</script>
		<?php
	}

?>