<?php
	add_shortcode('emailtry_shortcode', 'emailtry_shortcode_func');

	function emailtry_shortcode_func(){
      	if(isset( $_POST['submitEmail']) ){
        	do_action('sendmailtry');
        }
?>
		<form action="" id="tryemail_form" method="POST">
        	<input type="submit" name="submitEmail" value="Submit" class="btn btn-primary"/>  
        </form>
<?php
    }
?>