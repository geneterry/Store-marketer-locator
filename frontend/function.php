<?php 

//add_action( 'redirect_me', 'redirect_non_logged_users_to_specific_page' );
function redirect_me_to( $link ='' ) {
	echo "<script>window.location.href = '" . $link . "';</script>";
    exit;
}


function swal_message($type='', $title='', $text='', $redirect=false, $redirect_link='', $showCancel=false){
	
	if( $redirect ){
		echo "<script type='text/javascript'>
    		if (Swal.isVisible() == false) {
		    	Swal.fire({
			      type: '". $type ."',
			      title: '". $title ."',
			      html: '". $text ."'
			    }).then(
			    function(){
	              window.location.href = '" . $redirect_link . "';
	            });
			}
	    </script>";
	}elseif( $showCancel ){
		if( $redirect !== '' ){
			echo "<script type='text/javascript'>
	    		if (Swal.isVisible() == false) {
			    	Swal.fire({
				      type: '". $type ."',
				      title: '". $title ."',
				      html: '". $text ."',
				      showCancelButton: true
				    }).then(
				    function(){
		              window.location.href = '" . $redirect_link . "';
		            });
				}
		    </script>";
		}else{
			echo "<script type='text/javascript'>
	    		if (Swal.isVisible() == false) {
			    	Swal.fire({
				      type: '". $type ."',
				      title: '". $title ."',
				      html: '". $text ."',
				      showCancelButton: true
				    });
				}
		    </script>";
		}
	}else{
	    echo "<script type='text/javascript'>
	    		if (Swal.isVisible() == false) {
			    	Swal.fire({
				      type: '". $type ."',
				      title: '". $title ."',
				      html: '". $text ."'
				    });
				}
		    </script>";
	}
}

/*add_action('sendmailtry', 'sendmailtry_func');
function sendmailtry_func(){
  $data = array(
                    'physio_id'         => '123',
                    'paypal_payerID'    => '1234',
                    'physio_name'       => 'john doe',
                    'physio_email'      => 'email@email.com',
                    'transaction_type'  => 'upgrade_account',
                    'amount'            => '100',
                    'order_id'          => '321'
                );
  sendPaypalEmailConfirm($data);
$txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;">
                    <h1 style="color: #fff;line-height: 1.5;letter-spacing: 1px;">Thank You for Upgrading Your Account!</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <p class="email-receiver" style="font-size: 16px;font-weight: bold;color: #1781bc;line-height: 3;">Dear John.</p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        We would like to inform you that your account has been upgraded successfully! Thank you so much for upgrading your account, the payment of 100GBP was sent on February 28, 2019 and your Paypal Order No. 1234.
                    </p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        Thanks again for upgrading your account.
                    </p>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <p>All the best,</p>
                        <p>Physiobrite team</p>
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin-top: 10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = 'trainingjuan509@gmail.com';

    $subject = "Testing Email";

    //$headers = array('Content-Type: text/html; charset=UTF-8');
  
  	$headers = "From:  testing.com <receipts@testing.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    //$sendmailtry = wp_mail($to,$subject,$txt,$headers);
  	//if($sendmailtry){
    //	echo 'success';
    //}
  echo 'send';
  $testing = 'testingsample';
  
}*/

function sendPaypalEmailConfirm( $orderDetails='', $userEmail='' ){
	
    $physio_name = $orderDetails['physio_name'];
    $physio_email = $orderDetails['physio_email'];
    $transaction_type = $orderDetails['transaction_type'];
    $amount = $orderDetails['amount'];
    $order_id = $orderDetails['order_id'];
    $date = date('F d, Y');
    $txt = "";
    $subject = "";
  	$result = "";

    if( $transaction_type == 'upgrade_account' ):

    	$subject = "Physiobrite Account Upgrade";

    	$txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;line-height: 1.5;letter-spacing: 1px;">Thank You for Upgrading Your Account!</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <p class="email-receiver" style="font-size: 16px;font-weight: bold;color: #1781bc;line-height: 3;">Dear '. $physio_name .',</p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        We would like to inform you that your account has been upgraded successfully! Thank you so much for upgrading your account, the payment of '. $amount .'GBP was sent on '. $date .' and your Paypal Order No. '. $order_id .'.
                    </p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        Thanks again for upgrading your account.
                    </p>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <p>All the best,</p>
                        <p>Physiobrite team</p>
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin-top: 10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    elseif( $transaction_type == 'recharge_account' ):

    	$subject = "Physiobrite Account Credits";

    	$txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;line-height: 1.5;letter-spacing: 1px;">Thank You for Adding Credits To Your Account!</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <p class="email-receiver" style="font-size: 16px;font-weight: bold;color: #1781bc;line-height: 3;">Dear '. $physio_name .',</p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        We would like to inform you that your account credits has been added successfully! Thank you so much for adding credits to your account, the payment of '. $amount .'GBP was sent on '. $date .' and your Paypal Order No. '. $order_id .'.
                    </p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        Thanks again for adding credits to your account.
                    </p>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <p>All the best,</p>
                        <p>Physiobrite team</p>
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin-top: 10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    elseif( $transaction_type == 'telephone_consultation' ):

        $subject = "Physiobrite Telephone Consultation";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;line-height: 1.5;letter-spacing: 1px;">Thank You for submitting your consultation to us!</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <p class="email-receiver" style="font-size: 16px;font-weight: bold;color: #1781bc;line-height: 3;">Dear '. $physio_name .',</p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        We would like to inform you that consultation has been received successfully! Thank you so much for submitting your consultation with to us, the payment of '. $amount .'GBP was sent on '. $date .' and your Paypal Order No. '. $order_id .'.
                    </p>
                    <p class="email-message" style="margin-bottom: 15px;color: #333;">
                        Thanks again for your consultation a registered Physiotherapist will contact you within 24 hours upon the time of payment.
                    </p>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <p>All the best,</p>
                        <p>Physiobrite team</p>
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin-top: 10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    endif;

    $to = $physio_email ;
  
  	$headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoPaypalEmail = wp_mail($to,$subject,$txt,$headers);
  	$sendmailtoPhysioEmail = wp_mail($userEmail,$subject,$txt,$headers);
  
    if($sendmailtoPhysioEmail == true && $sendmailtoPaypalEmail == true){
      	$result = array('success' => true);
    }else{
      	$result = array('success' => false);
    }

}

function paypalButton( $container='', $amount=0  ){
?>
<script type="text/javascript">
    jQuery( document ).ready( function (){

        /*$('.button-testing').on('click', function(){
            var saveData = {
                'action'            : 'psyb_enquiry_paypal_transaction',
                //'pysio_id'          : currUserId,
                'paypal_payerID'    : 'details.payer.payer_id',
                'pysio_name'        : 'details.payer.name.given_name + ' ' + details.payer.name.surname',
                'physio_email'      : 'details.payer.email_address',
                'transaction_type'  : 'telephone_consultation',
                'amount'            : 'details.purchase_units[0].amount.value',
                'order_id'          : 'data.orderID'
            }

            // Call your server to save the transaction
            $.post(my_ajax_object.ajax_url, saveData, function(response) {
                response        =   jQuery.trim(response);
                response        =   jQuery.parseJSON( response);
                //console.log(response);
                if(response.success){
                    Swal.fire({
                      type: 'success',
                      title: 'Success' + details.payer.name.given_name,
                      text: 'Thank you '+ + details.payer.name.given_name +' for sending us your Enquiry. We will get back to you as soon as possible!'
                    }).then(
                        function(){
                          window.location.href = window.location.href;
                        });
                }

            });
        });*/

        if ($('<?= $container; ?>').length == 1) {
        paypal.Buttons({
                style: {
                layout:  'horizontal',
                color:   'gold',
                shape:   'rect',
                label:   'paypal',
                height:  40, 
                tagline: false,
              },
            createOrder: function(data, actions) {
              return actions.order.create({
                purchase_units: [{
                  amount: {
                    value: '<?= $amount; ?>'
                  }
                }]
              });
            },
            onCancel: function (data) {
                Swal.fire({
                          type: 'warning',
                          title: 'Cancelled!',
                          text: 'The transaction was cancelled.'
                });
                $('#upgradeAccountInfo').modal('hide');
              },
            onError: function (err) {
                Swal.fire({
                          type: 'error',
                          title: 'Error!',
                          text: 'Something went wrong in processing your payment. Please refresh the page and try again.'
                });
                $('#upgradeAccountInfo').modal('hide');
              },
            onApprove: function(data, actions) {
              return actions.order.capture().then(function(details) {
                //alert('Transaction completed by ' + details.payer.name.given_name);
                //var currUserId = jQuery( '#upgrade_account' ).data('id');
                
                var saveData = {
                    'action'            : 'psyb_enquiry_paypal_transaction',
                    //'pysio_id'          : currUserId,
                    'paypal_payerID'    : details.payer.payer_id,
                    'pysio_name'        : details.payer.name.given_name + ' ' + details.payer.name.surname,
                    'physio_email'      : details.payer.email_address,
                    'transaction_type'  : 'telephone_consultation',
                    'amount'            : details.purchase_units[0].amount.value,
                    'order_id'          : data.orderID
                }

                // Call your server to save the transaction
                $.post(my_ajax_object.ajax_url, saveData, function(response) {
                    response        =   jQuery.trim(response);
                    response        =   jQuery.parseJSON( response);
                    //console.log(response);
                    if(response.success){
                        Swal.fire({
                          type: 'success',
                          title: 'Success' + details.payer.name.given_name,
                          text: 'Thank you '+ + details.payer.name.given_name +' for sending us your Enquiry. We will get back to you as soon as possible!'
                        }).then(
                            function(){
                              window.location.href = window.location.href;
                            });
                    }

                });
            });
          }
          }).render('<?= $container; ?>');
        
    }
        
});
</script>
<?php
}

function sendRegisterEmail( $storeId = '', $userEmail='', $userRand='' ){
    

        $subject = "Please Verify Your Account";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;font-size: 32px;letter-spacing: 1px;">Before we get started...</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <h3 class="email-message" style="margin-bottom: 40px;color: #333 !important; font-size: 20px; text-align: center;">
                        Thank you for creating an account, but you need to have a HCPC ID to activate your account. Please click the button below to verify your account now.
                    </h3>
                    <div style="text-align:center; margin-bottom:20px">
                        <a href="'. home_url() . '/account-verification/?id='. $storeId .'&verification_code=' . $userRand . '" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;text-decoration:none;">Verify Your Account</a>
                    </div>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; padding-top:10px; display:block;" width="357px" height="140px" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = $userEmail ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  
    if( $sendmailtoUserEmail ){

        swal_message('success', 'Please activate your account', 'Click the button in the email we sent to <span class="font-weight-bold">' . $userEmail . '</span>' );

        //swal_message('success', 'Please activate your account', 'Click the button in the email we sent to <span class="font-weight-bold">' . $userEmail . '</span><a href="'. home_url() . '/verify-your-account/?id='. $storeId .'&verification_code=' . $userRand . '" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;">Confirm Your Email</a>' );

        echo '<div class="container mt-3 text-center style=background-color:#00A0D2"><h3 class="font-italic">Please check your email to verify your account. Did not received any email? <a href="#" id="resend_email" data-txt="'.htmlspecialchars(wp_json_encode($txt), ENT_QUOTES, "UTF-8").'" data-email="' . $userEmail . '"><u>resend</u></a></p><br></div>';
    }
}

function sendEmailAccountVerification( $storeId = '', $userEmail='', $userRand='' ){
    

        $subject = "Please Verify Your Account";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;font-size: 32px;letter-spacing: 1px;">Before we get started...</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <h3 class="email-message" style="margin-bottom: 40px;color: #333 !important; font-size: 20px; text-align: center;">
                        Thank you for creating an account. Please click the button below to verify your account now.
                    </h3>
                    <div style="text-align:center; margin-bottom:20px">
                        <a href="'. home_url() . '/account-verification/?id='. $storeId .'&verification_code=' . $userRand . '" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;text-decoration:none;">Verify Your Account</a>
                    </div>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="'. home_url() . '/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; padding-top:10px; display:block;" width="357px" height="140px"  title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = $userEmail ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  
    if( $sendmailtoUserEmail ){

        swal_message('success', 'Please activate your account', 'Click the button in the email we sent to <span class="font-weight-bold">' . $userEmail . '</span>' );

        //swal_message('success', 'Please activate your account', 'Click the button in the email we sent to <span class="font-weight-bold">' . $userEmail . '</span><a href="'. home_url() . '/verify-your-account/?id='. $storeId .'&verification_code=' . $userRand . '" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;">Confirm Your Email</a>' );

        echo '<div class="container mt-3 text-center"><p class="font-italic">Please check your email to verify your account. Did not received any email? <a href="#" id="resend_email" data-txt="'.htmlspecialchars(wp_json_encode($txt), ENT_QUOTES, "UTF-8").'" data-email="' . $userEmail . '"><u>resend</u></a></p><br></div>';
    }
}

function sendAccountVerificationEmail( $userEmail='', $userdata='' ){


    $user_name = $userdata['first_name'];

    $subject = "Congratulations, Your Account Was Successfully Verified!";

    $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
        <div class="email-container">
            <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                <h1 style="color: #fff;line-height: 1.5;letter-spacing: 1px;">Your Account Was Successfully Verified!</h1>
            </div>
            <div class="email-body" style="padding: 20px 30px;">
            <p class="email-receiver" style="font-size: 16px;font-weight: bold;color: #1781bc;line-height: 3;">Dear '. $user_name .',</p>
                <p class="email-message" style="margin-bottom: 40px;color: #333; text-align:center;">
                    Congratulations your account has been verified! <br/>
					Your account username is <strong>'. $userdata["user_login"] .'</strong> <br/>
					Your temporary password has been set to <strong>'. $userdata["user_pass"] .'</strong><br/>
					You can change this password by accessing your User Dashboard, in the Account Details Tab.<br/>
                    Your account is now ready and you can now Login - Please click the link below to get started!
                </p>
                <div style="text-align:center;">
                    <a href="'. home_url() . '/login" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;">Log me in!</a>
                </div>
                <div class="email-footer" style="font-weight: 500;color: #1781bc; padding-top: 30px;">
                    <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                </div>
            </div>
        </div>
    </section>';

    $to = $userEmail ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  
}

function sendRenewAccountVerificationEmail( $userEmail='', $userdata='' ){


    $user_name = $userdata['first_name'];

    $subject = "Congratulations, Your Account Was Successfully Renewed!";

    $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
        <div class="email-container">
            <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                <h1 style="color: #fff;line-height: 1.5;letter-spacing: 1px;">Your Account Was Successfully Renewed!</h1>
            </div>
            <div class="email-body" style="padding: 20px 30px;">
            <p class="email-receiver" style="font-size: 16px;color: #1781bc;line-height: 3;">Dear '. $user_name .',</p>
                <p class="email-message" style="margin-bottom: 40px;color: #333; text-align: center;">
                    Congratulations on having your account renewed! Your account username is <strong>'. $userdata["user_login"] .'</strong> and your temporary password has been set to <strong>'. $userdata["user_pass"] .'</strong>. You can change this by accessing your User Dashboard, in the Account Details Tab.<br/>
                    Your account is now ready and you can now Login in to your account. Please click the link below and acccess your account now!
                </p>
                <div style="text-align:center;">
                    <a href="'. home_url() . '/login" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;">Log me in!</a>
                </div>
                <div class="email-footer" style="font-weight: 500;color: #1781bc; padding-top: 30px;">
                    <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                </div>
            </div>
        </div>
    </section>';

    $to = $userEmail ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  
}

function resendEmailVerify(){
    echo '<script type="text/javascript">
                jQuery(document).ready(function($){
                    var dataTxt = "";
                    var dataEmail = "";
                    if (Swal.isVisible()) {

                        dataTxt = $(document).find("#resend_email").data("txt");
                        dataEmail = $(document).find("#resend_email").data("email");

                        dataTxt  =   $.trim(dataTxt);
                        dataTxt  =   $.parseJSON(dataTxt);
                        
                    }
                    $("#resend_email").on("click", function(e){
                        e.preventDefault();
                        var data = {
                            "action"        : "psyb_resend_email",
                            "dataTxt"       : dataTxt,
                            "dataEmail"     : dataEmail
                        }
                        //console.log(data);
                        //return;

                        $.post(my_ajax_object.ajax_url, data, function(response) {
                            response        =   jQuery.trim(response);
                            response        =   jQuery.parseJSON( response);
                            if (response.success == true) {
                                Swal.fire({
                                  type: "success",
                                  title: "Success!",
                                  html: "An email has been resent to <strong>" + dataEmail + "</strong>"
                                });
                            }else{
                                Swal.fire({
                                  type: "error",
                                  title: "Error!",
                                  text: "Something went wrong in resending the email. Please try again."
                                });
                            }
                        });
                    });

                });
            </script>';
}

function resendRegisterVerification($dataTxt, $dataEmail){
  
  	$subject = "Please Verify Your Account";

    $txt = stripslashes($dataTxt);

    $to = $dataEmail ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  
    if( $sendmailtoUserEmail ){

        $result = array(
            'success'       => true,
        );
    }else{
    	$result = array(
            'success'       => false
        );
    }
  echo json_encode($result);
}

function sendExpiredAccountNotification( $data='' ){
    

        $subject = "Your Physiobrite Account has Expired";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;font-size: 32px;letter-spacing: 1px;">Your Physiobrite Account has expired</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <h3 class="email-message" style="margin-bottom: 40px;color: #333 !important; font-size: 20px; text-align: center;">
                        We would like to inform you that your account in www.physiobrite.com has expired. You have created the account on '. date("F, j Y",strtotime($data['date_modified'])) .'
                        If you want to renew your account, please click the button below.
                    </h3>
                    <div style="text-align:center; margin-bottom:20px">
                        <a href="'. home_url() . '/renew-your-account/?id='. $data['store_id'] .'&verification_code=' . $data['rand_password'] . '" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;text-decoration:none;">Renew My Account</a>
                    </div>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; padding-top:10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = $data['user_email'] ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
}

/**
* Admin Notice
*/

function physio_admin_notice($type='') {
    if ($type == 'success') :
        set_transient( 'physio-notice-success-admin', true, 5 );

    elseif($type == 'error'):
        set_transient( 'physio-notice-success-admin', true, 5 );

    endif;
}

add_action( 'admin_notices', 'physio_notice_in_admin' );

function physio_notice_in_admin($class='', $message=''){

    /* Check transient, if available display notice */
    if( get_transient( 'physio-notice-success-admin' ) ):
        ?>
        <div class="notice is-dismissible notice-success">
            <p>The user was updated successfully!</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'physio-notice-success-admin' );

    elseif( get_transient( 'physio-notice-error-admin' ) ):
        ?>
        <div class="notice is-dismissible notice-error">
            <p>Something went wrong while updating the user.</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'physio-notice-error-admin' );
    
    endif;
}

/**
*	Check the number of featured physio in a city
*/
function get_featured_physio_count($city='') {
    global $wpdb;
    $getPromotedPerCity = $wpdb->get_results($wpdb->prepare(
        'SELECT COUNT(*) as featured_count, physio_city FROM '. $wpdb->prefix .'physiobrite_featured_physio WHERE physio_city = %s',
      	$city
      ));
  	return (empty($getPromotedPerCity[0]->featured_count) ? 0 : $getPromotedPerCity[0]->featured_count);
}

/**
*	Var_dump
*/
function dd( $val = '' ){
	echo '<pre>';
  	var_dump($val);
  	echo '</pre>';
}

/**
*	Send email to expired promoted
*/
function sendPromotedEmailExpired($data = ''){
		$subject = "Featured Account has Expired";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;font-size: 32px;letter-spacing: 1px;">Your Featured Account has Expired</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <p class="email-message" style="margin-bottom: 40px;color: #333 !important; font-size: 16px; text-align: center;">
                        We would like to inform you that your 30 days of being a Featured Physiotherapist in www.physiobrite.com has expired. Your account was promoted on '. date("F, j Y",strtotime($data->date_created)) .'
                        If you want to renew your account, please click the button below to go to your dashboard and <strong>Be One of The Three Featured Physiotherapist in Your City</strong>.
                    </p>
                    <div style="text-align:center; margin-bottom:20px">
                        <a href="'. home_url() . '/leads-dashboard/" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;text-decoration:none;">Be The Featured Physio Now!</a>
                    </div>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; padding-top:10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = $data->physio_email;

    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .
		
      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  	if ($sendmailtoUserEmail):
  		return true;
  	else: 
  		return false;
  	endif;
}

function sendRegisterEmailAgain( $storeId = '', $userEmail='', $userRand='' ){
    

        $subject = "Please Verify Your Account";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;font-size: 32px;letter-spacing: 1px;">Before we get started...</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <h3 class="email-message" style="margin-bottom: 40px;color: #333 !important; font-size: 20px; text-align: center;">
                        Thank you for creating an account, but you need to have a HCPC ID to activate your account. Please click the button below to verify your account now.
                    </h3>
                    <div style="text-align:center; margin-bottom:20px">
                        <a href="'. home_url() . '/account-verification/?id='. $storeId .'&verification_code=' . $userRand . '" style="border-radius:3px; padding:10px 15px;background-color: #1781bc; color:#fff; font-weight: 600; font-size: 18px;text-decoration:none;">Verify Your Account</a>
                    </div>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; padding-top:10px; display:block;" width="357px" height="140px" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = $userEmail ;
  
    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .

      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";

    $sendmailtoUserEmail = wp_mail($to,$subject,$txt,$headers);
  
}

/**
*   Send email on enquiry submit
*/
function sendEnquiryEmail($data = ''){
        $subject = "Thank you for your enquiry!";

        $txt = '<section class="email-section" style="max-width: 600px;border: 2px solid #004774;margin: auto;border-radius: 10px;background-color: #fff;">
            <div class="email-container">
                <div class="email-head" style="background: #004774;text-align: center;padding: 30px 20px;margin-bottom: 20px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <h1 style="color: #fff;font-size: 32px;letter-spacing: 1px;">Thank you for sending us your Enquiry!</h1>
                </div>
                <div class="email-body" style="padding: 20px 30px;">
                    <p class="email-message" style="margin-bottom: 40px;color: #333 !important; font-size: 16px;">You have sent us the following information below:</p>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Name: </strong>'. $data['client_name'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Postcode: </strong>'. $data['client_address'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Telephone Number: </strong>'. $data['client_tel'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Email: </strong>'. $data['client_email'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Best time to call: </strong>'. $data['client_time_call'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Best person to contact other than above: </strong>'. $data['client_other_contact'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Service Type: </strong>'. $data['service_type'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Speciality: </strong>'. $data['physio_cat_2'] .'</div>
                        <div style="color: #333 !important; font-size: 16px;"><strong>Gender Of Physio Required: </strong>'. $data['client_gender'] .'</div>
						 <div style="color: #333 !important; font-size: 16px;"><strong>Type Of Treatment Payment: </strong>'. $data['client_type'] .'</div>
						 <div style="color: #333 !important; font-size: 16px;"><strong>Date Created: </strong>'. $data['date_created'] .'</div>
                    <div style="margin-bottom:20px">
                        <p>We are currently finding you a Physiotherapist to respond to your request. Thank you for using our service!</p>
                    </div>
                    <div class="email-footer" style="font-weight: 500;color: #1781bc;">
                        <a href="www.physiobrite.com" title="Visit Physiobrite"><img src="https://www.physiobrite.com/wp-content/uploads/2019/01/PhysioBriteCom.png" style="margin: auto; padding-top:10px; display:block;" width="35%" height="100%" title="Physiobrite Logo" alt="Physiobrite Logo"></a>
                    </div>
                </div>
            </div>
        </section>';

    $to = $data['client_email'];

    $headers = "From:  Physiobrite <info@physiobrite.com>" . "\r\n" .
        
      "MIME-Version: 1.0" . "\r\n".

      "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	

    $sendmailtoEnquiryEmail = wp_mail($to,$subject,$txt,$headers);
    if ($sendmailtoEnquiryEmail):
        return true;
    else: 
        return false;
    endif;
}

 ?>