<h3>My Enquiry List</h3>
<div class="row">
  <div class="col-md-8">
    <p class="mt-2">Total Credits: <strong class="user-total-credits"><?= get_user_meta( $userId, 'credits', true ); ?></strong></p>
	  <small class="text-danger font-weight-bold font-italic">Credits will be deducted upon clicking the <u>BUY IT NOW</u> button.</small>
  </div>
  <div class="col-md-4 text-md-center">
    <button class="btn btn-secondary btn-sm recharge_account_dash">Add Credits</button>
  </div>
	
</div>
  <div class="col-md-8">
<small class="text-danger font-weight-bold font-italic">*If you do not click the <u>ACCEPT LEAD </u> button within 24 hour the lead will be added to the Global list for others to purchase*</small>
    
  </div>
<div class="table-container mt-3">
	<p class="message-inquiry"></p>
	<table id="enquiryTable" class="display">
		<thead>
		    <tr>
				
 <th width="20%">Postcode</th>
            	<th width="20%">Gender Wanted</th>
				<th width="20%">Service Type</th>
				<th width="20%">Insurance</th>
		       <th width="20%">Speciality Required</th>
				<th width="20%">Type of Lead</th>
		       <th width="20%"></th>
				
		    </tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>