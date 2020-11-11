/*

Content

-Leads Dashboard
--Inquiry table

-Client Register
-Leads Register Validation


*/


jQuery(document).ready(function($){

	var baseUrlImg = window.location.protocol + "//" + window.location.host +'/wp-content/plugins/physiobrite-leads/img/';

	//Leads Dashboard
	var dataPreloader='<div class="text-center data-preloader"><div class="spinner-border"><span class="sr-only">Loading...</span></div></div>';
	//Inquiry table
	$('#enquiryTable .view-info').on('click', function(){
		$('#enquiryInfo').modal('show')
	});

	//Inquiry table
	var enquiryTable = $('#enquiryTable').DataTable({
		"processing": true,
		"serverSide": true,
		//"order": [[ 0, "desc" ]],
		//"scrollX": true,
		"showNEntries" : false,
		//"bFilter": false,
		"lengthMenu": [ [10, 25, 50, 100, 500, 20000], [10, 25, 50, 100, 500, "All"] ],
		"bInfo": false,
		"lengthChange": true,
		"oLanguage": {"sProcessing": dataPreloader},
		"ajax":{
			url: my_ajax_object.ajax_url,
			type: "post",
			data: function(d) {
				if(d){
					d.action = 'data_enquiry_table';
				}
			}
		},
			
		"drawCallback": function( settings ) {
			if( settings.json.credits == 0 || settings.json.credits < 10 ){
				$( '#enquiryTable tr' ).each( function( index ) {
					var currRow = $(this);
					var inquiryVal = $(this).find('.access-enquiry').data('fieldinfo');

					if ( inquiryVal == 0 ){
						currRow.find('td:not(:last-child)').addClass('blurred-text');
					}
				});
			}
		}
	});

	//Onclick approched button
	$('#enquiryTable').on('click', '.client-approched', function(){
		var leadsId = $(this).data('id');

		var data = {
			'action' 		: 'psyb_approached_client',
			'leadsId'		: leadsId
		}

		$.post(my_ajax_object.ajax_url, data, (response)=> {
			response		=	jQuery.trim(response);
			response		=	jQuery.parseJSON( response);
			if (response.success == true) {
				$(this).prop('disabled', 'disabled');
			}
		});

	});
	
	//Onclick detail button
	$('#enquiryTable').on('click', '.access-enquiry', function(){
		$(this).find('.spinner-border.spinner-border-sm').removeClass('d-none');
		$('#enquiryTable').find('button').attr('disabled', 'disabled');

		var tableInquiry = $(this);
		var dataInfo = $(this).data('fieldinfo');
		var leadsId = $(this).data('id');
		var accessBtn = $(this);
		var currCredit = $(document).find('.user-total-credits');

		var data = {
			'action' 		: 'psyb_deduct_credit',
			'physio_id'		: dataInfo['physio_id'],
			'leadsId'		: leadsId
		}

		$.post(my_ajax_object.ajax_url, data, function(response) {
				response		=	jQuery.trim(response);
				response		=	jQuery.parseJSON( response);
				//console.log(response);
				if (response.success == true) {
					if( response.is_deducted == true ){
						currCredit.text('' + response.remainingCredit + '');
						dataInfo['is_viewed'] = 1;
						if ( response.remainingCredit == 0 || response.remainingCredit < 10 ) {
							//check if a credit is deducted
							$( '#enquiryTable .access-enquiry' ).each( function( index ) {
								var thisBtn = $(this);
								var thisData = $(this).data('fieldinfo');
								var thisIsViewed = thisData['is_viewed'];

								if ( thisIsViewed == 0 ){
									if ( currCredit.text() > 0 || currCredit.text() < 10) {
										thisBtn.parents('tr').find('td:not(:last-child)').text('Not enough credits').addClass('blurred-text');
										thisBtn.removeAttr('data-fieldinfo');
									}
								}
							});
							modalInfo(dataInfo);
							$(tableInquiry).find('.spinner-border.spinner-border-sm').addClass('d-none');
							$('#enquiryTable').find('button').removeAttr('disabled');
							$(document).find('#enquiryInfo').modal('show');
								
						}else{
							if( response.is_deducted == true ){
								currCredit.text('' + response.remainingCredit + '');
								modalInfo(dataInfo);
								$(tableInquiry).find('.spinner-border.spinner-border-sm').addClass('d-none');
								$('#enquiryTable').find('button').removeAttr('disabled');
								$(document).find('#enquiryInfo').modal('show');
								dataInfo['is_viewed'] = 1;
							}
						}

					}else if(response.openModal == true){
						modalInfo(dataInfo);
						$(tableInquiry).find('.spinner-border.spinner-border-sm').addClass('d-none');
						$('#enquiryTable').find('button').removeAttr('disabled');
						$(document).find('#enquiryInfo').modal('show');

					}
					
				} else {
					$(tableInquiry).find('.spinner-border.spinner-border-sm').addClass('d-none');
					$('#enquiryTable').find('button').removeAttr('disabled');
					Swal.fire({
					  title: 'Warning!',
					  text: "You do not have enough credits to view this. Do you want to recharge your account?",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Recharge'
					}).then((result) => {
					  if (result.value) {
					(async function getAmount () {
					    const {value: chargeAmount} = await Swal.fire({
					      title: 'Recharge your account',
					      text: '<p>Enter amount: GBP </p><br><p class="font-weight-lighter font-italic" style="font-size:16px;">Each Lead = 10 Credits and £10 per 10 credits is the fee.</p> ',
					      input: 'number',
					      showCancelButton: true,
					      inputValidator: (value) => {
						    return !value && 'You need to add amount!'
						  }
					    }
					    )
							if (chargeAmount) {
							  Swal.fire({
							  	title: 'Pay using paypal',
							  	html:	'<p>Add ' + chargeAmount + ' credits to your account?</p> <br/> ' + '<div id="paypal_recharge_account"></div>',
							  	showCancelButton: true,
							  	showConfirmButton: false,
							  });

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
						            value: chargeAmount
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
						        var currUserId = $('[name="current_user_id"]').val();
						        
						        var saveData = {
									'action' 			: 'psyb_paypal_transaction',
									'physio_id'			: currUserId,
									'paypal_payerID'	: details.payer.payer_id,
									'physio_name'		: details.payer.name.given_name + ' ' + details.payer.name.surname,
									'physio_email'		: details.payer.email_address,
									'transaction_type'	: 'recharge_account',
									'amount'			: details.purchase_units[0].amount.value,
									'order_id'			: data.orderID
								}

						        // Call your server to save the transaction
						        $.post(my_ajax_object.ajax_url, saveData, function(response) {
									response		=	jQuery.trim(response);
									response		=	jQuery.parseJSON( response);
									//console.log(response);
									if(response.success){
                                      $('#upgradeAccountInfo').modal('hide');
										Swal.fire({
										  type: 'success',
										  title: 'Success!',
										  text: 'Credits has been added successfully.'
										}).then(
										    function(){
								              window.location.href = window.location.href;
								            });
									}

								});
						    });
						  }
						  }).render('#paypal_recharge_account');
						}//if chargeAmount

						})()
					  }
					});
				}//end else
			});
	});

	function modalInfo(obj){
		$(document).find('#enquiryInfo .client-name p').html('<strong>Name: </strong>' + obj.client_name);
		$(document).find('#enquiryInfo .client-address p').html('<strong>Postcode: </strong>' + obj.client_address);
		$(document).find('#enquiryInfo .client-gender p').html('<strong>Gender Wanted: </strong>' + obj.client_gender);
		$(document).find('#enquiryInfo .client-tel p').html('<strong>Telephone Number: </strong><a href="tel:'+ obj.client_tel +'">' + obj.client_tel + '</a>');
		$(document).find('#enquiryInfo .client-email p').html('<strong>Email Adress: </strong><a href="mailto:'+ obj.client_email +'">' + obj.client_email + '</a>');
		$(document).find('#enquiryInfo .client-category p').html('<strong>Speciality Required: </strong>' + obj.physio_cat);
		$(document).find('#enquiryInfo .client-time-call p').html('<strong>Best time to call: </strong>' + obj.client_time_call);
		$(document).find('#enquiryInfo .client-other p').html('<strong>Best person to contact other than above: </strong>' + obj.client_other_contact);
		$(document).find('#enquiryInfo .service-type p').html('<strong>Service Type: </strong>' + obj.service_type);
		//$(document).find('#enquiryInfo .client-problem p').html('<strong>Problem: </strong>' + obj.client_problem);
	}


	//Leads Dashboard
	$('#dashboard-edit-form .leads-info-form').addClass('d-none');

	$('#dashboard-edit-form').on('click', '.edit-form', function(){
		$('#dashboard-edit-form .leads-info-form').removeClass('d-none');
		$('#dashboard-edit-form .leads-info-col').addClass('d-none');
	});

	$('#dashboard-edit-form').on('click', '.cancel-form', function(){
		$('#dashboard-edit-form .leads-info-form').addClass('d-none');
		$('#dashboard-edit-form .leads-info-col').removeClass('d-none');
	});

	$('.inquiry-card').on('click', function(){
		$('#v-pills-profile-tab').tab('show');
	});

	$('.detail-card').on('click', function(){
		$('#v-pills-messages-tab').tab('show');
	});

	function readURL(input) {
	if (input.files && input.files[0]) {
	    var reader = new FileReader();
	    reader.onload = function(e) {
	        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
	        $('#imagePreview').hide();
	        $('#imagePreview').fadeIn(650);
	    }
	    reader.readAsDataURL(input.files[0]);
	}
	}
		$("#imageUpload").change(function() {
		readURL(this);
	});

	function IsEmail(email) {
	  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  if(!regex.test(email)) {
	    return false;
	  }else{
	    return true;
	  }
	}

	$('#store_locator_country').on('change', function(){
		if($(this).val() !== "United States"){
			$('.state_input').attr('style', 'display:none;');
		}
	});

	/*===================Service Provider Update===================*/
	$(document).on('submit', '#dashboard-edit-form', function(e){

		var formError = 0;
		var worktimeError = 0;

		var form = $('#dashboard-edit-form');
		var fname = form.find('[name="inputFirstName"]');
		var lname = form.find('[name="inputLastName"]');
		var email = form.find('[name="inputEmail"]');
		var phone = form.find('[name="inputPhone"]');
		var gender = form.find('[name="inputGender"]');
		var cat = form.find('#inputCategory');
		var username = form.find('[name="username"]');
		var userpass = form.find('[name="userPass"]');
		var locator_address = form.find('[name="store_locator_address"]');
		var locator_country = form.find('[name="store_locator_country"]');
		var locator_state = form.find('[name="store_locator_state"]');
		var locator_city = form.find('[name="store_locator_city"]');
		var locator_zipcode = form.find('[name="store_locator_zipcode"]');
		var locator_days = form.find('[name="store_locator_days"]');

		fname.on('focus', function(){
			fname.removeClass('input-danger');
		});
		lname.on('focus', function(){
			lname.removeClass('input-danger');
		});
		email.on('focus', function(){
			email.removeClass('input-danger');
		});
		phone.on('focus', function(){
			phone.removeClass('input-danger');
		});
		gender.on('focus', function(){
			gender.removeClass('input-danger');
		});
		$('.show-tick .btn.dropdown-toggle').on('focus', function(){
			$('.show-tick .btn.dropdown-toggle').removeClass('input-danger');
		});
		username.on('focus', function(){
			username.removeClass('input-danger');
		});
		userpass.on('focus', function(){
			userpass.removeClass('input-danger');
		});
		locator_address.on('focus', function(){
			locator_address.removeClass('input-danger');
		});
		locator_country.on('focus', function(){
			locator_country.removeClass('input-danger');
		});
		locator_state.on('focus', function(){
			locator_state.removeClass('input-danger');
		});
		locator_city.on('focus', function(){
			locator_city.removeClass('input-danger');
		});
		locator_zipcode.on('focus', function(){
			locator_zipcode.removeClass('input-danger');
		});

		var matchPass = $('#dashboard-edit-form .response_password_check .text-danger ').length;

		if( matchPass == 1 ){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Password does not match.'
				});
				  formError = 1;
			}
		}

		if( fname.val() == "" || lname.val() == "" || email.val() == "" || phone.val() == "" || gender.val() == "Choose..." || cat.val() == "" || username.val() == "" || userpass.val() == "" || locator_address.val() == "" || locator_country.val() == "" || locator_city.val() == "" || locator_zipcode.val() == "" ){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please fill up all of the information in the form'
				});
				  formError = 1;
			}
		}
		if(fname.val() == ""){
			fname.addClass('input-danger');
			formError = 1;
		}
		if(lname.val() == ""){
			lname.addClass('input-danger');
			formError = 1;
		}
		if(email.val() == ""){
			email.addClass('input-danger');
			formError = 1;
		}
		if(phone.val() == ""){
			phone.addClass('input-danger');
			formError = 1;
		}
		if(gender.val() == "Choose..."){
			gender.addClass('input-danger');
			formError = 1;
		}
		if(cat.val() == ""){
			$('.show-tick .btn.dropdown-toggle').addClass('input-danger');
			formError = 1;
		}
		if(username.val() == ""){
			username.addClass('input-danger');
			formError = 1;
		}
		if(userpass.val() == ""){
			userpass.addClass('input-danger');
			formError = 1;
		}
		if(locator_address.val() == ""){
			locator_address.addClass('input-danger');
			formError = 1;
		}
		if(locator_country.val() == ""){
			locator_country.addClass('input-danger');
			formError = 1;
		}
		if(locator_country.val() == 'United States' && locator_state.val() == ""){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please select a state'
				});
				locator_state.addClass('input-danger');
				formError = 1;
			}
		}
		if(locator_city.val() == ""){
			locator_city.addClass('input-danger');
			formError = 1;
		}
		if(locator_zipcode.val() == ""){
			locator_zipcode.addClass('input-danger');
			formError = 1;
		}

		var monday = form.find('#store_locator_days_Monday_1').is(':checked');
		var tuesday = form.find('#store_locator_days_Tuesday_1').is(':checked');
		var wednesday = form.find('#store_locator_days_Wednesday_1').is(':checked');
		var thursday = form.find('#store_locator_days_Thursday_1').is(':checked');
		var friday = form.find('#store_locator_days_Friday_1').is(':checked');
		var saturday = form.find('#store_locator_days_Saturday_1').is(':checked');
		var sunday = form.find('#store_locator_days_Sunday_1').is(':checked');

		var mondayOpen = form.find('[name="store_locator_days[Monday][start]"]');
		var mondayClose = form.find('[name="store_locator_days[Monday][end]"]');

		var tuesdayOpen = form.find('[name="store_locator_days[Tuesday][start]"]');
		var tuesdayClose = form.find('[name="store_locator_days[Tuesday][end]"]');

		var wednesdayOpen = form.find('[name="store_locator_days[Wednesday][start]"]');
		var wednesdayClose = form.find('[name="store_locator_days[Wednesday][end]"]');

		var thursdayOpen = form.find('[name="store_locator_days[Thursday][start]"]');
		var thursdayClose = form.find('[name="store_locator_days[Thursday][end]"]');

		var fridayOpen = form.find('[name="store_locator_days[Friday][start]"]');
		var fridayClose = form.find('[name="store_locator_days[Friday][end]"]');

		var saturdayOpen = form.find('[name="store_locator_days[Saturday][start]"]');
		var saturdayClose = form.find('[name="store_locator_days[Saturday][end]"]');

		var sundayOpen = form.find('[name="store_locator_days[Sunday][start]"]');
		var sundayClose = form.find('[name="store_locator_days[Sunday][end]"]');

		if(monday){
			if(mondayOpen.val() == ""){
				mondayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(mondayClose.val() == ""){
				mondayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(tuesday){
			if(tuesdayOpen.val() == ""){
				tuesdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(tuesdayClose.val() == ""){
				tuesdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(wednesday){
			if(wednesdayOpen.val() == ""){
				wednesdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(wednesdayClose.val() == ""){
				wednesdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(thursday){
			if(thursdayOpen.val() == ""){
				thursdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(thursdayClose.val() == ""){
				thursdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(friday){
			if(fridayOpen.val() == ""){
				fridayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(fridayClose.val() == ""){
				fridayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(saturday){
			if(saturdayOpen.val() == ""){
				saturdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(saturdayClose.val() == ""){
				saturdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(sunday){
			if(sundayOpen.val() == ""){
				sundayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(sundayClose.val() == ""){
				sundayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		mondayOpen.on('focus', function(){
			mondayOpen.removeClass('input-danger');
		});
		mondayClose.on('focus', function(){
			mondayClose.removeClass('input-danger');
		});
		tuesdayOpen.on('focus', function(){
			tuesdayOpen.removeClass('input-danger');
		});
		tuesdayClose.on('focus', function(){
			tuesdayClose.removeClass('input-danger');
		});
		wednesdayOpen.on('focus', function(){
			wednesdayOpen.removeClass('input-danger');
		});
		wednesdayClose.on('focus', function(){
			wednesdayOpen.removeClass('input-danger');
		});
		thursdayOpen.on('focus', function(){
			thursdayOpen.removeClass('input-danger');
		});
		thursdayClose.on('focus', function(){
			thursdayClose.removeClass('input-danger');
		});
		fridayOpen.on('focus', function(){
			fridayOpen.removeClass('input-danger');
		});
		fridayClose.on('focus', function(){
			fridayClose.removeClass('input-danger');
		});
		saturdayOpen.on('focus', function(){
			saturdayOpen.removeClass('input-danger');
		});
		saturdayClose.on('focus', function(){
			saturdayClose.removeClass('input-danger');
		});
		sundayOpen.on('focus', function(){
			sundayOpen.removeClass('input-danger');
		});
		sundayClose.on('focus', function(){
			sundayClose.removeClass('input-danger');
		});

		//var formError = form.find('.input-danger').length;
		if(formError == 1){
			if ( worktimeError == 1){
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'There was an error in creating your account. Please check the form and try again.'
				});
				e.preventDefault();
			}
			e.preventDefault();
		}
	});

	/*==========Book Form===========*/

	$(document).on('submit', '#book_form', function(e){

		var formError = 0;

		var form = $('#book_form');
		var clientName = form.find('[name="clientName"]');
		var clientAddress = form.find('[name="clientAddress"]');
		var clientTel = form.find('[name="clientTel"]');
		var clientEmail = form.find('[name="clientEmail"]');
		var clientTimeCall = form.find('[name="clientTimeCall"]');
		var physioCat = form.find('[name="physioCat"]');
		//var clientProblem = form.find('[name="clientProblem"]');
		var clientGender = form.find('[name="clientGender"]');
		var termsCheck = form.find('[name="termsCheck"]');
		var serviceType = form.find('[name="serviceType"]');

		if( clientName.val() == '' || clientAddress.val() == '' || clientTel.val() == '' || clientEmail.val() == '' || clientTimeCall.val() == '' || physioCat.val() == 'Speciality' || clientGender.val() == 'Gender Required')
		{

			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please fill up all of the information in the form'
				});
				  formError = 1;
			}

		}

		if( clientName.val() == "" ){
			clientName.addClass('input-danger');
			formError = 1;
		}
		if( clientAddress.val() == "" ){
			clientAddress.addClass('input-danger');
			formError = 1;
		}
		if( clientTel.val() == "" ){
			clientTel.addClass('input-danger');
			formError = 1;
		}
		if( clientTimeCall.val() == "" ){
			clientTimeCall.addClass('input-danger');
			formError = 1;
		}
		if( physioCat.val() == "Speciality" ){
			physioCat.addClass('input-danger');
			formError = 1;
		}
		/*if( clientProblem.val() == "" ){
			clientProblem.addClass('input-danger');
			formError = 1;
		}*/
		if( clientEmail.val() == "" ){

			clientEmail.addClass('input-danger');
			formError = 1;

		}
		if ( IsEmail(clientEmail.val() ) == false){
			if (Swal.isVisible() == false) {
              Swal.fire({
                type: 'error',
                title: 'Error!',
                text: 'Please enter a valid email.'
              });
              clientEmail.addClass('input-danger');
              formError = 1;
            }
		}
		if ( clientGender.val() == 'Gender Required' ) {
			clientGender.addClass('input-danger');
			formError = 1
		}

		clientName.on('focus', function(){
			clientName.removeClass('input-danger');
		});
		clientAddress.on('focus', function(){
			clientAddress.removeClass('input-danger');
		});
		clientTel.on('focus', function(){
			clientTel.removeClass('input-danger');
		});
		clientTimeCall.on('focus', function(){
			clientTimeCall.removeClass('input-danger');
		});
		physioCat.on('focus', function(){
			physioCat.removeClass('input-danger');
		});
		clientGender.on('focus', function(){
			clientGender.removeClass('input-danger');
		});
		/*clientProblem.on('focus', function(){
			clientProblem.removeClass('input-danger');
		});*/
		clientEmail.on('focus', function(){
			clientEmail.removeClass('input-danger');
		});

		if( ($('#termsCheck').is(':checked')) == false){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'info',
				  title: 'Good day!',
				  text: 'Agreeing to the Terms and Condition in Required'
				});
			}
			formError = 1;
		}

		if( formError == 1 ){
			e.preventDefault();
		}
	});

	/*==========Telephone consultation in homepage===========*/

	$(document).on('submit', '#book_form_enquiries', function(e){

		var formError = 0;

		var form = $('#book_form_enquiries');
		var clientName = form.find('[name="clientName"]');
		var clientAddress = form.find('[name="clientAddress"]');
		var clientTel = form.find('[name="clientTel"]');
		var clientEmail = form.find('[name="clientEmail"]');
		var clientTimeCall = form.find('[name="clientTimeCall"]');
		var physioCat = form.find('[name="physioCat"]');
		//var clientProblem = form.find('[name="clientProblem"]');
		var clientGender = form.find('[name="clientGender"]');
		var termsCheck = form.find('[name="termsCheck"]');
		var serviceType = form.find('[name="serviceType"]');

		if( clientName.val() == '' || clientAddress.val() == '' || clientTel.val() == '' || clientEmail.val() == '' || clientTimeCall.val() == '' || physioCat.val() == 'Speciality' || clientGender.val() == 'Gender Required')
		{

			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please fill up all of the information in the form'
				});
				  formError = 1;
			}

		}

		if( clientName.val() == "" ){
			clientName.addClass('input-danger');
			formError = 1;
		}
		if( clientAddress.val() == "" ){
			clientAddress.addClass('input-danger');
			formError = 1;
		}
		if( clientTel.val() == "" ){
			clientTel.addClass('input-danger');
			formError = 1;
		}
		if( clientTimeCall.val() == "" ){
			clientTimeCall.addClass('input-danger');
			formError = 1;
		}
		if( physioCat.val() == "Speciality" ){
			physioCat.addClass('input-danger');
			formError = 1;
		}
		/*if( clientProblem.val() == "" ){
			clientProblem.addClass('input-danger');
			formError = 1;
		}*/
		if( clientEmail.val() == "" ){

			clientEmail.addClass('input-danger');
			formError = 1;

		}
		if ( IsEmail(clientEmail.val() ) == false){
			if (Swal.isVisible() == false) {
              Swal.fire({
                type: 'error',
                title: 'Error!',
                text: 'Please enter a valid email.'
              });
              clientEmail.addClass('input-danger');
              formError = 1;
            }
		}
		if ( clientGender.val() == 'Gender Required' ) {
			clientGender.addClass('input-danger');
			formError = 1
		}

		clientName.on('focus', function(){
			clientName.removeClass('input-danger');
		});
		clientAddress.on('focus', function(){
			clientAddress.removeClass('input-danger');
		});
		clientTel.on('focus', function(){
			clientTel.removeClass('input-danger');
		});
		clientTimeCall.on('focus', function(){
			clientTimeCall.removeClass('input-danger');
		});
		physioCat.on('focus', function(){
			physioCat.removeClass('input-danger');
		});
		clientGender.on('focus', function(){
			clientGender.removeClass('input-danger');
		});
		/*clientProblem.on('focus', function(){
			clientProblem.removeClass('input-danger');
		});*/
		clientEmail.on('focus', function(){
			clientEmail.removeClass('input-danger');
		});

		if( ($('#termsCheck').is(':checked')) == false){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'info',
				  title: 'Good day!',
				  text: 'Agreeing to the Terms and Condition in Required'
				});
			}
			formError = 1;
		}

		if( formError == 1 ){
			e.preventDefault();
		}
	});
	
	$('.start_time, .end_time, [name="clientTimeCall"]').timepicker({
        'showDuration': true,
        'timeFormat': 'g:i a'
    });

    function getTwentyFourHourTime(amPmString) { 
	    var d = new Date("1/1/2013 " + amPmString); 
	    var plusThirty = d.getMinutes() + 30;
	    if( plusThirty == 60 ){

	    	var plusOne = d.getHours() + 1;
	    	return currTime = plusOne + ':' + '00'; 

	    }else{
	    	
	    	return currTime = d.getHours() + ':' + plusThirty; 

	    }

	}

	function tConvert (time) {
	  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
	  if (time.length > 1) { // If time format correct
	    time = time.slice (1);  // Remove full string match value
	    time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
	    time[0] = +time[0] % 12 || 12; // Adjust hours
	  }
	  return time.join (''); // return adjusted time or original string
	}


    $('.start_time').on('changeTime', function() {
	    var minTime = $(this).val();
	    minTime = getTwentyFourHourTime(minTime);
	    minTime = tConvert(minTime);

	    $(this).parent('td').find( '.end_time' ).val('').timepicker('remove').timepicker({
	    	//'minTime': minTime,
	    	'disableTimeRanges': [
		        ['12am', minTime],
		    ]
	    });
	});

    /*===================Service Provider===================*/
	$(document).on('submit', '#registerServiceProviderForm', function(e){

		var formError = 0;
		var worktimeError = 0;

		var form = $('#registerServiceProviderForm');
		var fname = form.find('[name="inputFirstName"]');
		var lname = form.find('[name="inputLastName"]');
		var email = form.find('[name="inputEmail"]');
		var hcpc_id = form.find('[name="inputHcpc"]');
		// var phone = form.find('[name="inputPhone"]');
		var gender = form.find('[name="inputGender"]');
		var cat = form.find('#inputCategory');
		var username = form.find('[name="username"]');
		//var userpass = form.find('[name="userPass"]');
		var locator_address = form.find('[name="store_locator_address"]');
		var locator_country = form.find('[name="store_locator_country"]');
		var locator_state = form.find('[name="store_locator_state"]');
		var locator_city = form.find('[name="store_locator_city"]');
		var locator_zipcode = form.find('[name="store_locator_zipcode"]');
		var locator_days = form.find('[name="store_locator_days"]');
		var staff_members_name = form.find('.inputStaffName');
		var staff_members_email = form.find('.inputStaffEmail');
		var staff_members_id = form.find('.inputStaffHcpc');

		fname.on('focus', function(){
			fname.removeClass('input-danger');
		});
		lname.on('focus', function(){
			lname.removeClass('input-danger');
		});
		email.on('focus', function(){
			email.removeClass('input-danger');
		});
		hcpc_id.on('focus', function(){
			hcpc_id.removeClass('input-danger');
		});
		// // phone.on('focus', function(){
		// 	phone.removeClass('input-danger');
		// });
		gender.on('focus', function(){
			gender.removeClass('input-danger');
		});
		$('.show-tick .btn.dropdown-toggle').on('focus', function(){
			$('.show-tick .btn.dropdown-toggle').removeClass('input-danger');
		});
		username.on('focus', function(){
			username.removeClass('input-danger');
		});
		//userpass.on('focus', function(){
			//userpass.removeClass('input-danger');
		//});
		locator_address.on('focus', function(){
			locator_address.removeClass('input-danger');
		});
		locator_country.on('focus', function(){
			locator_country.removeClass('input-danger');
		});
		locator_state.on('focus', function(){
			locator_state.removeClass('input-danger');
		});
		locator_city.on('focus', function(){
			locator_city.removeClass('input-danger');
		});
		locator_zipcode.on('focus', function(){
			locator_zipcode.removeClass('input-danger');
		});

		staff_members_name.on('focus', function(){
			staff_members_name.removeClass('input-danger');
		});
		staff_members_email.on('focus', function(){
			staff_members_email.removeClass('input-danger');
		});
		staff_members_id.on('focus', function(){
			staff_members_id.removeClass('input-danger');
		});
		
		var staff_error = 0;
		staff_members_name.each(function(){
			if( $(this).val() == "" ){
				staff_error = 1;
				$(this).addClass('input-danger');
				formError = 1;
			}
		});
		staff_members_email.each(function(){
			if( $(this).val() == "" ){
				staff_error = 1;
				$(this).addClass('input-danger');
				formError = 1;
			}
		});
		staff_members_id.each(function(){
			if( $(this).val() == "" ){
				staff_error = 1;
				$(this).addClass('input-danger');
				formError = 1;
			}
		});

		if( fname.val() == "" || lname.val() == "" || email.val() == "" || hcpc_id.val() == "" || gender.val() == "Choose..." || cat.val() == "" || username.val() == "" || locator_address.val() == "" || locator_country.val() == "" || locator_city.val() == "" || locator_zipcode.val() == "" || staff_error == 1){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please fill up all of the information in the form'
				});
				  formError = 1;
			}
		}
		if(fname.val() == ""){
			fname.addClass('input-danger');
			formError = 1;
		}
		if(lname.val() == ""){
			lname.addClass('input-danger');
			formError = 1;
		}
		if(email.val() == ""){
			email.addClass('input-danger');
			formError = 1;
		}
		if(hcpc_id.val() == ""){
			hcpc_id.addClass('input-danger');
			formError = 1;
		}
		/*if(phone.val() == ""){
			phone.addClass('input-danger');
			formError = 1;
		}*/
		if(gender.val() == "Choose..."){
			gender.addClass('input-danger');
			formError = 1;
		}
		if(cat.val() == ""){
			$('.show-tick .btn.dropdown-toggle').addClass('input-danger');
			formError = 1;
		}
		if(username.val() == ""){
			username.addClass('input-danger');
			formError = 1;
		}
		if(locator_address.val() == ""){
			locator_address.addClass('input-danger');
			formError = 1;
		}
		if(locator_country.val() == ""){
			locator_country.addClass('input-danger');
			formError = 1;
		}
		if(locator_country.val() == 'United States' && locator_state.val() == ""){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please select a state'
				});
				locator_state.addClass('input-danger');
				formError = 1;
			}
		}
		if(locator_city.val() == ""){
			locator_city.addClass('input-danger');
			formError = 1;
		}
		if(locator_zipcode.val() == ""){
			locator_zipcode.addClass('input-danger');
			formError = 1;
		}
      		if( ($('#gridCheck').is(':checked')) == false){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'info',
				  title: 'Good day!',
				  text: 'Agreeing to the Terms and Condition in Required'
				});
			}
			formError = 1;
		}

		var monday = form.find('#store_locator_days_Monday_1').is(':checked');
		var tuesday = form.find('#store_locator_days_Tuesday_1').is(':checked');
		var wednesday = form.find('#store_locator_days_Wednesday_1').is(':checked');
		var thursday = form.find('#store_locator_days_Thursday_1').is(':checked');
		var friday = form.find('#store_locator_days_Friday_1').is(':checked');
		var saturday = form.find('#store_locator_days_Saturday_1').is(':checked');
		var sunday = form.find('#store_locator_days_Sunday_1').is(':checked');

		var mondayOpen = form.find('[name="store_locator_days[Monday][start]"]');
		var mondayClose = form.find('[name="store_locator_days[Monday][end]"]');

		var tuesdayOpen = form.find('[name="store_locator_days[Tuesday][start]"]');
		var tuesdayClose = form.find('[name="store_locator_days[Tuesday][end]"]');

		var wednesdayOpen = form.find('[name="store_locator_days[Wednesday][start]"]');
		var wednesdayClose = form.find('[name="store_locator_days[Wednesday][end]"]');

		var thursdayOpen = form.find('[name="store_locator_days[Thursday][start]"]');
		var thursdayClose = form.find('[name="store_locator_days[Thursday][end]"]');

		var fridayOpen = form.find('[name="store_locator_days[Friday][start]"]');
		var fridayClose = form.find('[name="store_locator_days[Friday][end]"]');

		var saturdayOpen = form.find('[name="store_locator_days[Saturday][start]"]');
		var saturdayClose = form.find('[name="store_locator_days[Saturday][end]"]');

		var sundayOpen = form.find('[name="store_locator_days[Sunday][start]"]');
		var sundayClose = form.find('[name="store_locator_days[Sunday][end]"]');

		if(monday){
			if(mondayOpen.val() == ""){
				mondayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(mondayClose.val() == ""){
				mondayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(tuesday){
			if(tuesdayOpen.val() == ""){
				tuesdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(tuesdayClose.val() == ""){
				tuesdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(wednesday){
			if(wednesdayOpen.val() == ""){
				wednesdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(wednesdayClose.val() == ""){
				wednesdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(thursday){
			if(thursdayOpen.val() == ""){
				thursdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(thursdayClose.val() == ""){
				thursdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(friday){
			if(fridayOpen.val() == ""){
				fridayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(fridayClose.val() == ""){
				fridayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(saturday){
			if(saturdayOpen.val() == ""){
				saturdayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(saturdayClose.val() == ""){
				saturdayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		if(sunday){
			if(sundayOpen.val() == ""){
				sundayOpen.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			} else if(sundayClose.val() == ""){
				sundayClose.addClass('input-danger');
				formError = 1;
				worktimeError = 1;
			}
		}
		mondayOpen.on('focus', function(){
			mondayOpen.removeClass('input-danger');
		});
		mondayClose.on('focus', function(){
			mondayClose.removeClass('input-danger');
		});
		tuesdayOpen.on('focus', function(){
			tuesdayOpen.removeClass('input-danger');
		});
		tuesdayClose.on('focus', function(){
			tuesdayClose.removeClass('input-danger');
		});
		wednesdayOpen.on('focus', function(){
			wednesdayOpen.removeClass('input-danger');
		});
		wednesdayClose.on('focus', function(){
			wednesdayOpen.removeClass('input-danger');
		});
		thursdayOpen.on('focus', function(){
			thursdayOpen.removeClass('input-danger');
		});
		thursdayClose.on('focus', function(){
			thursdayClose.removeClass('input-danger');
		});
		fridayOpen.on('focus', function(){
			fridayOpen.removeClass('input-danger');
		});
		fridayClose.on('focus', function(){
			fridayClose.removeClass('input-danger');
		});
		saturdayOpen.on('focus', function(){
			saturdayOpen.removeClass('input-danger');
		});
		saturdayClose.on('focus', function(){
			saturdayClose.removeClass('input-danger');
		});
		sundayOpen.on('focus', function(){
			sundayOpen.removeClass('input-danger');
		});
		sundayClose.on('focus', function(){
			sundayClose.removeClass('input-danger');
		});

		//var formError = form.find('.input-danger').length;
		if(formError == 1){
			if ( worktimeError == 1){
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'There was an error in creating your account. Please check the form and try again.'
				});
				e.preventDefault();
			}
			e.preventDefault();
		}
	}); 

	//Add testimonial
	$( '#testimonialAdd' ).one( 'submit', '#add_testimonial', function(e){
		e.preventDefault();

		var testimonialLeadsId 	 =	$('#add_testimonial [name="testimonialLeadsId"]').val();
		var testimonialName 	 =	$('#add_testimonial [name="testimonialName"]').val();
		var testimonialCompany 	 =	$('#add_testimonial [name="testimonialCompany"]').val();
		var testimonialJobTitle  =	$('#add_testimonial [name="testimonialJobTitle"]').val();
		var testimonialContent 	 =	$('#add_testimonial [name="testimonialContent"]').val();

		if( testimonialName == "" || testimonialJobTitle == "" || testimonialContent == "" ){
			Swal.fire({
			  type: 'error',
			  title: 'Errors!',
			  text: 'Please fill up all of the information in the form'
			});
			return;
		}

		var data = {
			'action' 				: 'psyb_add_testimonial',
			'testimonialLeadsId'	: testimonialLeadsId,
			'testimonialName'		: testimonialName,
			'testimonialCompany'	: testimonialCompany,
			'testimonialJobTitle'	: testimonialJobTitle,
			'testimonialContent'	: testimonialContent,
		}


		$.post(my_ajax_object.ajax_url, data, function(response) {
				response		=	jQuery.trim(response);
				response		=	jQuery.parseJSON( response);
				//console.log(response);
				if(response.success){
					Swal.fire({
					  type: 'success',
					  title: 'Success!',
					  text: 'Testimonial added.'
					}).then(
					    function(){
			              window.location.href = window.location.href;
			            });
					$('#testimonialAdd').modal('hide');
					$('#add_testimonial [name="testimonialName"]').val("");
					$('#add_testimonial [name="testimonialCompany"]').val("");
					$('#add_testimonial [name="testimonialJobTitle"]').val("");
					$('#add_testimonial [name="testimonialContent"]').val("");
				}

			});

	});

	$( '#testimonialList' ).on( 'click', '.delete-testimonial', function(e) {
		
		e.preventDefault();
		$('#testimonialList').find('button').attr('disabled', 'disabled');
		var thisButton = $(this);
		var dataid = $(this).data('id');

		var data = {
			'action'		: 'psyb_delete_testimonial',
			'testimonialId'	: dataid
		}
		Swal.fire({
		  title: 'Are you sure to delete this testimonial?',
		  text: "You won't be able to revert this.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.value) {
		  	$.post(my_ajax_object.ajax_url, data, function(response){

				response		=	jQuery.trim(response);
				response		=	jQuery.parseJSON( response);

			if (response.success) {
				    Swal.fire(
				      'Deleted!',
				      'The testimonial has been deleted.',
				      'success'
				    )
					thisButton.parents('tr').remove();
					$('#testimonialList').find('button').removeAttr('disabled');
				  }else{
				  	Swal.fire(
				      'Error!',
				      'Something went wrong. Please refresh the page and try again.',
				      'warning'
				    )
				  }
				});
			}
		});
			
	});

	//Testimonial table
	var testimonialTable = $('#testimonialList').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [[ 0, "desc" ]],
		//"bInfo": false,
		"lengthChange": true,
		"showNEntries" : false,
		//"pageLength": 5,
		"lengthMenu": [ [10, 25, 50, 100, 500, 20000], [10, 25, 50, 100, 500, "All"] ],
		//"bFilter": false,
		"bInfo": false,
		"oLanguage": {"sProcessing": dataPreloader},
		"ajax":{
			url: my_ajax_object.ajax_url,
			type: "post",
			data: function(d) {
				if(d){
					d.action = 'data_testimonial_list';
				}
			}
		},
		"drawCallback": function( settings ) {
			//console.log(settings);
		}
	});

	//Onclick detail button
	$('#testimonialList').on('click', '.access-testimonial', function(){
		$('#testimonialList').find('button').attr('disabled', 'disabled');
		var tableInquiry = $(this);
		var dataInfo = $(this).data('fieldinfo');
		modalInfoTestimonial(dataInfo);
		$(document).find('#testimonialInfo').modal('show');
		$('#testimonialList').find('button').removeAttr('disabled');
	});

	function modalInfoTestimonial(obj){
		$(document).find('#testimonialInfo .testimonial-name p').html('<strong>Name: </strong>' + obj.client_name);
		$(document).find('#testimonialInfo .testimonial-job-title p').html('<strong>Job Title: </strong>' + obj.client_job);
		$(document).find('#testimonialInfo .testimonial-company p').html( '<strong>Company: </strong>' + obj.client_company);
		$(document).find('#testimonialInfo .testimonial-content p').html( '<strong>Content: </strong><br/>' + obj.client_content);
	}

	function htmlspecialchars(str) {
	  return str.replace('&', '&amp;').replace('"', '&quot;').replace("'", '&#039;').replace('<', '&lt;').replace('>', '&gt;');
	}

	$( document ).on('click', '#add_new_testimonial', function(){

		$('#testimonialAdd').modal('show');
		
	});

	$( '#dashboard-edit-form' ).on( 'keyup', '#userPass, #userPassCheck', function(){
		var passCheck = $( '#dashboard-edit-form' ).find('#userPassCheck').val();
		var passVal = $( '#dashboard-edit-form' ).find('#userPass').val();

		if ( passVal !== passCheck ) {
			$('#dashboard-edit-form .response_password_check').html('<p class="text-danger font-weight-bold">Password does not match!</p>');
		}else{
			$('#dashboard-edit-form .response_password_check').html('<p class="text-success font-weight-bold">Password matched!</p>');
		}
	});


	$( '.dashboard_container' ).on( 'click', '#upgrade_account', function(e){
		e.preventDefault();
		var user_id = $( '#upgrade_account' ).data('id');
		//console.log(user_id);
		$('#upgradeAccountInfo').modal('show');
		/*var currUserId = $( this ).data('id');
	        
	        var saveData = {
				'action' 			: 'psyb_paypal_transaction',
				'physio_id'			: currUserId,
				'paypal_payerID'	: 'details.payer.payer_id',
				'physio_name'		: "details.payer.name.given_name + ' ' + details.payer.name.surname",
				'physio_email'		: "details.payer.email_address",
				'transaction_type'	: 'upgrade_account',
				'amount'			: "details.purchase_units[0].amount.value",
				'order_id'			: "data.orderID"
			}

	        // Call your server to save the transaction
	        $.post(my_ajax_object.ajax_url, saveData, function(response) {
				response		=	$.trim(response);
				response		=	$.parseJSON( response);
				console.log(response);
				if(response.success){
					Swal.fire({
					  type: 'success',
					  title: 'Success!',
					  text: 'Payment added.'
					}).then(
					    function(){
			              window.location.href = window.location.href;
			            });
				}

			});*/
	});

if ($('#paypal_button_container').length == 1) {
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
	            value: '0.01'
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
	        var currUserId = jQuery( '#upgrade_account' ).data('id');
            var currCity = jQuery( '#upgrade_account' ).data('city');
            var currEmail = jQuery( '#upgrade_account' ).data('email');
	        
	        var saveData = {
				'action' 			: 'psyb_paypal_transaction',
				'physio_id'			: currUserId,
				'paypal_payerID'	: details.payer.payer_id,
				'physio_name'		: details.payer.name.given_name + ' ' + details.payer.name.surname,
				'physio_email'		: details.payer.email_address,
				'transaction_type'	: 'upgrade_account',
				'amount'			: details.purchase_units[0].amount.value,
				'order_id'			: data.orderID,
              	'user_email'		: currEmail,
              	'physio_city'		: currCity
			}

	        // Call your server to save the transaction
	        $.post(my_ajax_object.ajax_url, saveData, function(response) {
				response		=	jQuery.trim(response);
				response		=	jQuery.parseJSON( response);
				//console.log(response);
				if(response.success){
                  	$('#upgradeAccountInfo').modal('hide');
					Swal.fire({
					  type: 'success',
					  title: 'Congratulations ' + details.payer.name.given_name,
					  html: 'Your account has been upgraded successfully! <br> Your account will be featured until <strong>'+response.expiration_date+'</strong>'
					}).then(
					    function(){
			              window.location.href = window.location.href;
			            });
				}

			});
	    });
	  }
	  }).render('#paypal_button_container');
	
}
//Testing email
/*$( document ).on( 'submit', '#tryemail_form', function(e){
		e.preventDefault();
		//var user_id = $( '#upgrade_account' ).data('id');
		//console.log(user_id);
		//$('#upgradeAccountInfo').modal('show');
		var currUserId = 13;
	        
	        var saveData = {
				'action' 			: 'psyb_paypal_transaction',
				'physio_id'			: currUserId,
				'paypal_payerID'	: 'details.payer.payer_id',
				'physio_name'		: "details.payer.name.given_name + ' ' + details.payer.name.surname",
				'physio_email'		: "details.payer.email_address",
				'transaction_type'	: 'upgrade_account',
				'amount'			: "details.purchase_units[0].amount.value",
				'order_id'			: "data.orderID"
			}
			
	        // Call your server to save the transaction
	        $.post(my_ajax_object.ajax_url, saveData, function(response) {
				response		=	$.trim(response);
				response		=	$.parseJSON( response);
				console.log(response);
				if(response.success){
					Swal.fire({
					  type: 'success',
					  title: 'Success!',
					  text: 'Payment added.'
					}).then(
					    function(){
			              //window.location.href = window.location.href;
			            });
				}

			});
	});*/

/*Recharge Account Button*/
$('.recharge_account_dash').on('click', function(e){
	Swal.fire({
    title: 'Add Credits?',
    text: "Do you want to recharge your account?",
    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Recharge'
  }).then((result) => {
    if (result.value) {
  (async function getAmount () {
      const {value: chargeAmount} = await Swal.fire({
        title: 'Recharge your account',
        html: '<p>Enter amount: GBP </p><br><p class="font-weight-lighter font-italic" style="font-size:16px;">Each Lead = 10 Credits and £10 per 10 credits is the fee.</p>',
        input: 'number',
        showCancelButton: true,
        inputValidator: (value) => {
          return !value && 'You need to add amount!'
        }
      }
      )
          if (chargeAmount) {
            Swal.fire({
              title: 'Pay using paypal',
              html:	'<p>Add ' + chargeAmount + ' credits to your account?</p> <br/> ' + '<div id="paypal_recharge_account"></div>',
              showCancelButton: true,
              showConfirmButton: false,
            });

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
                  value: chargeAmount
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
              var currUserId = $('[name="current_user_id"]').val();

              var saveData = {
                  'action' 			: 'psyb_paypal_transaction',
                  'physio_id'			: currUserId,
                  'paypal_payerID'	: details.payer.payer_id,
                  'physio_name'		: details.payer.name.given_name + ' ' + details.payer.name.surname,
                  'physio_email'		: details.payer.email_address,
                  'transaction_type'	: 'recharge_account',
                  'amount'			: details.purchase_units[0].amount.value,
                  'order_id'			: data.orderID
              }

              // Call your server to save the transaction
              $.post(my_ajax_object.ajax_url, saveData, function(response) {
                  response		=	jQuery.trim(response);
                  response		=	jQuery.parseJSON( response);
                  //console.log(response);
                  if(response.success){
                      Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: 'Credits has been added successfully.'
                      }).then(
                          function(){
                            window.location.href = window.location.href;
                          });
                  }

              });
          });
        }
        }).render('#paypal_recharge_account');
      }//if chargeAmount

      })()
    }
  });
});

$('#store_locator_hours input[type="radio"]').change(function () {
    if ($(this).val() == '1') {
        $(this).parents('.tbl-row-container').find('.copyTimeRegister').removeClass('d-none');
    } 
    else {
        $(this).parents('.tbl-row-container').find('.copyTimeRegister').addClass('d-none');
    }
});

$(document).on('click','.copyTimeRegister',function(e){
	var p = $(this).parent().parent().prev()[0].cells[2].children;
	var start_time = p[0].value;
	var end_time = p[1].value;
	$(this).prev().val(end_time);
	$(this).prev().prev().val(start_time);
});

/*==========Book Form Contact===========*/

	$(document).on('submit', '#book_form_contact', function(e){

		var formError = 0;

		var form = $('#book_form_contact');
		var clientName = form.find('[name="clientName"]');
		var clientAddress = form.find('[name="clientAddress"]');
		var clientTel = form.find('[name="clientTel"]');
		var clientEmail = form.find('[name="clientEmail"]');
		var clientTimeCall = form.find('[name="clientTimeCall"]');
		var clientOtherContact = form.find('[name="clientOtherContact"]');
		var physioCat = form.find('[name="physioCat"]');
		//var clientProblem = form.find('[name="clientProblem"]');
		var clientGender = form.find('[name="clientGender"]');
		var serviceType = form.find('[name="serviceType"]');
		var termsCheck = form.find('[name="termsCheck"]');

		if( clientName.val() == '' || clientAddress.val() == '' || clientTel.val() == '' || clientEmail.val() == '' || clientTimeCall.val() == '' || physioCat.val() == 'Category' || clientGender.val() == 'Gender')
		{

			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'error',
				  title: 'Error!',
				  text: 'Please fill up all of the information in the form'
				});
				  formError = 1;
			}

		}

		if( clientName.val() == "" ){
			clientName.addClass('input-danger');
			formError = 1;
		}
		if( clientAddress.val() == "" ){
			clientAddress.addClass('input-danger');
			formError = 1;
		}
		if( clientTel.val() == "" ){
			clientTel.addClass('input-danger');
			formError = 1;
		}
		if( clientTimeCall.val() == "" ){
			clientTimeCall.addClass('input-danger');
			formError = 1;
		}
		if( physioCat.val() == "Speciality" ){
			physioCat.addClass('input-danger');
			formError = 1;
		}
		/*if( clientProblem.val() == "" ){
			clientProblem.addClass('input-danger');
			formError = 1;
		}*/
		if( clientEmail.val() == "" ){

			clientEmail.addClass('input-danger');
			formError = 1;

		}
		if ( IsEmail(clientEmail.val() ) == false){
			if (Swal.isVisible() == false) {
              Swal.fire({
                type: 'error',
                title: 'Error!',
                text: 'Please enter a valid email.'
              });
              clientEmail.addClass('input-danger');
              formError = 1;
            }
		}
		if ( clientGender.val() == 'Gender' ) {
			clientGender.addClass('input-danger');
			formError = 1
		}

		clientName.on('focus', function(){
			clientName.removeClass('input-danger');
		});
		clientAddress.on('focus', function(){
			clientAddress.removeClass('input-danger');
		});
		clientTel.on('focus', function(){
			clientTel.removeClass('input-danger');
		});
		clientTimeCall.on('focus', function(){
			clientTimeCall.removeClass('input-danger');
		});
		physioCat.on('focus', function(){
			physioCat.removeClass('input-danger');
		});
		clientGender.on('focus', function(){
			clientGender.removeClass('input-danger');
		});
		/*clientProblem.on('focus', function(){
			clientProblem.removeClass('input-danger');
		});*/
		clientEmail.on('focus', function(){
			clientEmail.removeClass('input-danger');
		});

		if( ($('#termsCheck').is(':checked')) == false){
			if (Swal.isVisible() == false) {
				Swal.fire({
				  type: 'info',
				  title: 'Good day!',
				  text: 'Agreeing to the Terms and Condition in Required'
				});
			}
			formError = 1;
		}

		if( formError == 1 ){
			e.preventDefault();
		}else{
			//$('#consultation_payment').modal('show');
			//console.log('testing');
			e.preventDefault();

			Swal.fire({
			  title: 'Good day ' + clientName.val(),
			  text: "The cost for a Telephone Consultation is £25 for 30 minutes. A registered Physiotherapist will call you within 24 hours of payment being made.",
			  type: 'info',
			  showCancelButton: true,
			  confirmButtonColor: '#ffc439',
			  cancelButtonColor: '#d33',
			  confirmButtonText: '<div id="paypal_contact_button_container"></div>'
			});

			$('button.swal2-confirm.swal2-styled').attr('style', 'padding: 0;background-color: #ffc107;');

			 paypal.Buttons({
                style: {
                layout:  'horizontal',
                color:   'gold',
                shape:   'rect',
                label:   'paypal',
                height:  36, 
                tagline: false,
              },
            createOrder: function(data, actions) {
              return actions.order.create({
                purchase_units: [{
                  amount: {
                    value: '0.03'
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
                    'physio_id'          : 0,
                    'paypal_payerID'    : details.payer.payer_id,
                    'physio_name'        : details.payer.name.given_name + ' ' + details.payer.name.surname,
                    'physio_email'      : details.payer.email_address,
                    'transaction_type'  : 'telephone_consultation',
                    'amount'            : details.purchase_units[0].amount.value,
                    'order_id'          : data.orderID,

                    'clientName' 			: clientName.val(),
					'clientAddress' 		: clientAddress.val(),
					'clientTel' 			: clientTel.val(),
					'clientEmail' 			: clientEmail.val(),
					'clientTimeCall' 		: clientTimeCall.val(),
					'clientOtherContact' 	: clientOtherContact.val(),
					'physioCat' 			: physioCat.val(),
					//'clientProblem' 		: clientProblem.val(),
					'clientGender' 			: clientGender.val(),
					'serviceType' 			: serviceType.val()
                }
                //console.log(saveData);

                // Call your server to save the transaction
                $.post(my_ajax_object.ajax_url, saveData, function(response) {
                    response        =   jQuery.trim(response);
                    response        =   jQuery.parseJSON( response);
                    //console.log(response);
                    if(response.success){
                        Swal.fire({
                          type: 'success',
                          title: 'Success ' + details.payer.name.given_name,
                          text: 'Thank you '+ details.payer.name.given_name +' for sending us your Enquiry. We will get back to you as soon as possible!'
                        }).then(
                            function(){
                              window.location.href = window.location.href;
                            });
                    }

                });
            });
          }
          }).render('#paypal_contact_button_container');
	}
});

/*---Slick Slider of categories---*/

$('.physio-category.multiple-items').slick({
  lazyLoad: 'ondemand',
  infinite: true,
  slidesToShow: 5,
  speed: 300,
  autoplay: true,
  autoplaySpeed: 3000,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        infinite: true,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

/*---Slick Slider of categories---*/

$('.featured-slider').slick({
  lazyLoad: 'ondemand',
  infinite: true,
  slidesToShow: 4,
  speed: 300,
  autoplay: true,
  autoplaySpeed: 3000,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});

$('.read-more').each(function(i, v){
	$(this).click(function(e) {
		e.preventDefault()
		$('#testimonialQuotes').appendTo("body").modal()
		$('#testimonialQuotes').find('h5.card-title').text($(this).parent().parent().find('h5').text())
		$('#testimonialQuotes').find('h6').text($(this).parent().parent().find('h6').text())
		$('.modal-qoute').text($(this).data('text'))
	});
});

//Global Enquiry table
	var globalEnquiryTable = $('#globalEnquiryTable').dataTable({
		"processing": true,
		"serverSide": true,
		//"order": [[ 0, "desc" ]],
		//"scrollX": true,
		"showNEntries" : false,
		//"bFilter": false,
		"lengthMenu": [ [10, 25, 50, 100, 500, 20000], [10, 25, 50, 100, 500, "All"] ],
		"bInfo": false,
		"lengthChange": true,
		"oLanguage": {"sProcessing": dataPreloader},
		"ajax":{
			url: my_ajax_object.ajax_url,
			type: "post",
			data: function(d) {
				if(d){
					d.action = 'data_global_enquiry_table';
				}
			}
		},
			
		"drawCallback": function( settings ) {
			if( settings.json.credits == 0 || settings.json.credits < 10 ){

			}
		}
	});

	$('#globalEnquiryTable').on( 'click', '.add-to-list', function(){
		
		var thisButton = $(this);
		var dataid = $(this).data('id');
		var userId = $('table#globalEnquiryTable').data('id');

		var data = {
			'action'			: 'psyb_move_enquiry',
			'globalEnquiryid'	: dataid,
			'userId'			: userId
		}

		$(this).find('.spinner-border.spinner-border-sm').removeClass('d-none');
		$('#globalEnquiryTable').find('button').attr('disabled', 'disabled');
		Swal.fire({
		  title: 'Add Enquiry To My List',
		  html: "Do you want to add this enquiry to your list? <span class='text-danger'>Please note that after 24hrs of not viewing the Enquiry it would be deleted to your list and would be move in the Global Enquiry.</span>",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, move it to my list!',
		  onAfterClose: () => {
		  	//$('#globalEnquiryTable').find('.spinner-border.spinner-border-sm').addClass('d-none');
		  	$('#globalEnquiryTable').find('.spinner-border.spinner-border-sm').addClass('d-none');
			$('#globalEnquiryTable').find('button').removeAttr('disabled');
		    //$('#globalEnquiryTable').find('.add-to-list').prop("disabled", false);
		  }
		}).then((result) => {
          	$(this).find('.spinner-border.spinner-border-sm').removeClass('d-none');
			$('#globalEnquiryTable').find('button').attr('disabled', 'disabled');
		  if (result.value) {
		  	$.post(my_ajax_object.ajax_url, data, function( response ){

				response		=	jQuery.trim(response);
				response		=	jQuery.parseJSON( response);

			if (response.success) {
				    Swal.fire(
				      'Success!',
				      'The enquiry was successfully moved in your list.',
				      'success'
				    )
					thisButton.parents('tr').remove();
					$('#globalEnquiryTable').find('.spinner-border.spinner-border-sm').addClass('d-none');
					$('#globalEnquiryTable').find('button').removeAttr('disabled');
					enquiryTable.ajax.reload();
				  }
				})
			}
		});
	} );

$('.featured-button').on('click', function(e){
  	e.preventDefault();
	$('#homeFeatured').modal('show');
});

/* Map Get Current Location */
var homeSearch = document.getElementById("store_locatore_search_input");
if( $("#store_locatore_search_input").length == 1 ){
	getLocation();

}

/*setTimeout(function(){
  var storeNotEmpty = $('.store-locator-not-found').length;

  if ( storeNotEmpty == 1 ){
    $('#store_locator_search_form #store_locatore_search_btn').click();
  }
}, 4000);*/

function getLocation() {
   if (navigator.geolocation) {
   		navigator.permissions.query({
             name: 'geolocation'
         }).then(function(result) {
            if (result.state == 'granted') {
                navigator.geolocation.getCurrentPosition(showPosition);

             }else if (result.state == 'prompt') {

                 navigator.geolocation.getCurrentPosition(showPosition);
             } else if (result.state == 'denied') {
               setTimeout(function(){
                 var storeNotEmpty = $('.store-locator-not-found').length;

                 if ( storeNotEmpty == 1 ){
                   $('#store_locator_search_form #store_locatore_search_btn').click();
                 }
                }, 1000);
             }
             result.onchange = function() {
                if (result.state == 'granted') {
                  navigator.geolocation.getCurrentPosition(showPosition);
                } else { 
                  setTimeout(function(){
                      var storeNotEmpty = $('.store-locator-not-found').length;

                      if ( storeNotEmpty == 1 ){
                        $('#store_locator_search_form #store_locatore_search_btn').click();
                      }
                  }, 1000);
                }
             }
         });
   }
}

/*setTimeout(function(){
	var storeNotEmpty = $j('.store-locator-not-found').length;
  	
    if ( storeNotEmpty == 1 ){
        $j('#store_locator_search_form #store_locatore_search_btn').click();
    }else{
        return;
    }
}, 4000)*/
    
function showPosition(position) {
   console.log("test");
	var lat = position.coords.latitude;
	var lang = position.coords.longitude;
  	$('#store_locatore_search_lat').val(lat);
  	$('#store_locatore_search_lng').val(lang);
 	var address = '';
  	var country_code = '';
	var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + ',' + lang  + "&sensor=false&key=AIzaSyC2sesQWjloj_1E2jBIrJzAlCbNg4GF8_E";
	console.log(url);
  	$.getJSON(url, function (data) {
      var address = data.results[0].formatted_address;
      var address_components = data.results[0].address_components;
      for(var i=0;i<address_components.length;i++) {
          if( jQuery.inArray("country", address_components[i].types) !== -1 ){
            var country_code = address_components[i].short_name;
          }
      }
      if (country_code !== 'GB'){
      	//return;
      }
      
      homeSearch.value = address;
      setTimeout(function(){
          var storeNotEmpty = $('.store-locator-not-found').length;

          if ( storeNotEmpty == 1 ){
              $('#store_locator_search_form #store_locatore_search_btn').click();
          }else{
              return;
          }
      }, 1000);
	});
}
$('.container.physio-category .card').on('click', function(){
	var link = $(this).find('a').attr('href');
	var modal = $('#category_location');
	var locatorVal = modal.find('#locator_physio').val();
	var id = $(this).find('.physio_id').val();
	console.log(id);
	modal.modal('show');
	modal.find('.physio_id').val(id);
});

$('#category_location input').on('change paste keyup', function(){
	var id = $('#category_location').find('.physio_id').val();
	$('#category_location').find('a').attr('href', '/search-physiotherapist?speciality_id='+id+'&physio_location=' + $(this).val() );
});

$("#delete_my_account").on("click", function(e){
	e.preventDefault();
	//var form = $(this).parents("form");
	
	Swal.fire({
	  title: 'Delete My Account',
	  html: "Do you really want to delete your account? <span class='text-danger'>This action is irreversible.</span>",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete my account!'
	}).then((result) => {
		if (result.value) {
      		var data = {
				'action' 			: 'delete_user_data',
				'userId'			: $(this).val(),
			}

	        // Call your server to save the transaction
	        $.post(my_ajax_object.ajax_url, data, function(response) {
				response		=	jQuery.trim(response);
				response		=	jQuery.parseJSON(response);
				console.log(response);
				if(response.success){
					Swal.fire({
					  type: 'success',
					  title: 'Success!',
					  text: 'Your Account has been deleted Successfully.'
					}).then(
					    function(){
			              window.location.href = window.location.href;
			            });
				}

			});
      	}

	});

});

$(document).on("ajaxComplete", function(){
	var storeNotEmpty = $('.store-locator-not-found').length;
	if( jQuery( document ).width() > 768 && storeNotEmpty == 1 ){
		jQuery(".map-listings.left").attr("style", "display: none;");
		jQuery("#store_locatore_search_map").attr("style", "width: 100% !important;height: 675px;position: absolute;overflow: hidden;");
	}

});

$(".staff-section").on("click", ".register-add-member", function(){
	console.log($(this).text());
	const button = $(this);

	button.text("Add More Member");
	button.parent().append(`<div class="form-row align-items-end">
								<div class="form-group col-md-4">
									<label for="inputStaffName" class="reg-label">Staff Name<span class="text-danger required">*</span></label>
									<input type="text" class="form-control inputStaffName" name="inputStaffName[]" value="" placeholder="Staff Name">
								</div>
								<div class="form-group col-md-4">
									<label for="inputStaffEmail" class="reg-label">Staff Email<span class="text-danger required">*</span></label>
									<input type="email" class="form-control inputStaffEmail" name="inputStaffEmail[]" value="" placeholder="Staff Email">
								</div>
								<div class="form-group col-md-3">
									<label for="inputStaffHcpc" class="reg-label">HCPC ID<span class="text-danger required">*</span></label>
									<input type="text" class="form-control inputStaffHcpc" name="inputStaffHcpc[]" value="" placeholder="HCPC ID">
								</div>
								<div class="form-group col-md-1">
									<button class="btn btn-primary mx-3 remove-staff-row"><i class="fa fa-trash"></i></button>
								</div>
							</div>`);
});

$(".staff-section").on("click", ".remove-staff-row", function(){
	$(this).parents(".form-row").remove();
		let count;
		count = $(".staff-section .form-row").length;
		if (count == 0){
			$(".staff-section .register-add-member").text("Add Member");
		}

});



}); // END of .ready
	function  store_locator_initializeMapBackend() {
        $('#map_loader').hide();
        // Handle google maps
        var oldMarker;
        var updateMapDuration;
        var mapOptions = {
            scrollwheel: false,
            zoom: 10,
            center: new google.maps.LatLng(1, 1)
        };

        //display default address on map
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        setTimeout(function () {

            if ($('#store_locator_lat').val() && $('#store_locator_lng').val()) {
                var currentLatLng = new google.maps.LatLng(parseFloat($('#store_locator_lat').val()), parseFloat($('#store_locator_lng').val()));
                marker = new google.maps.Marker({
                    position: currentLatLng,
                    map: map
                });
                oldMarker = marker;
                map.setCenter(currentLatLng);
            } else {
                var addressString = $('#store_locator_address').val();
                addressString     = ($('#store_locator_city').val()) ?(addressString+" "+ $('#store_locator_city').val()):addressString;
                addressString     = ($('#store_locator_state').val()) ?(addressString+", "+ $('#store_locator_state').val()):addressString;
                addressString     = ($('#store_locator_country').val()) ?(addressString+" "+ $('#store_locator_country').val()):addressString;
                addressString     = ($('#store_locator_zipcode').val()) ?(addressString+" "+ $('#store_locator_zipcode').val()):addressString;
                
                var address = (addressString) ? addressString : "United State";
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        $('#store_locator_lat').val(results[0].geometry.location.lat());
                        $('#store_locator_lng').val(results[0].geometry.location.lng());
                        var currentLatLng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        marker = new google.maps.Marker({
                            position: currentLatLng,
                            map: map
                        });
                        oldMarker = marker;
                        map.setCenter(currentLatLng);
                    }
                });
            }
        }, 1000);
        // move marker when click on map
		
        google.maps.event.addListener(map, "click", function (event) {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map
            });

            if (oldMarker != undefined)
                oldMarker.setMap(null);

            oldMarker = marker;
            $('#store_locator_lat').val(event.latLng.lat());
            $('#store_locator_lng').val(event.latLng.lng());
        });


        $('#store_locator_address, #store_locator_city, #store_locator_zipcode, #store_locator_country, #store_locator_state').change(function () {
            
            clearTimeout(updateMapDuration);
            $('#map_loader').show();
            updateMapDuration = setTimeout(function () {
                var addressString = $('#store_locator_address').val();
                addressString     = ($('#store_locator_city').val()) ?(addressString+" "+ $('#store_locator_city').val()):addressString;
                addressString     = ($('#store_locator_state').val()) ?(addressString+", "+ $('#store_locator_state').val()):addressString;
                addressString     = ($('#store_locator_country').val()) ?(addressString+" "+ $('#store_locator_country').val()):addressString;
                addressString     = ($('#store_locator_zipcode').val()) ?(addressString+" "+ $('#store_locator_zipcode').val()):addressString;
                //console.log(addressString);
                var address = (addressString) ? addressString : "United State";
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        $('#map_loader').hide();
                        $('#store_locator_lat').val(results[0].geometry.location.lat());
                        $('#store_locator_lng').val(results[0].geometry.location.lng());
                        var currentLatLng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        marker = new google.maps.Marker({
                            position: currentLatLng,
                            map: map
                        });
                        if (oldMarker != undefined)
                            oldMarker.setMap(null);

                        oldMarker = marker;
                        map.setCenter(currentLatLng);
                    }
                });
            }, 1000);
        });
    }

$('#store_locator_search_form #store_locatore_search_btn').on("click", function(){
	if( jQuery( document ).width() > 768 ){
		jQuery(".map-listings.left").attr("style", "display: block !important;");
		jQuery("#store_locatore_search_map").attr("style", "width: 50%;");
	}
});

