<h3>Your Global Enquiry / Referrer List</h3>
<div class="row">
  <div class="col-md-8">
    <p class="mt-2">Total Credits: <strong class="user-total-credits"><?= get_user_meta( $userId, 'credits', true ); ?></strong></p>
    <small class="font-weight-bold font-italic">Clicking the "Add To List" button will move the enquiry to your My Enquiry List.</small>
  </div>
  <div class="col-md-4 text-md-center">
    <button class="btn btn-secondary btn-sm recharge_account_dash">Add Credits</button>
	  
	
  </div>
</div>

<div class="table-container mt-3">
	<p class="message-inquiry"></p>
	<table id="globalEnquiryTable" data-id= "<?= $userId; ?>"  class="display">
		<thead>
		    <tr>
		      <th width="20%">Postcode</th>
            	<th width="20%">Gender</th>
				
				<th width="20%">Service Type</th>
				<th width="20%">Insurance</th>
		        <th width="20%">Speciality </th>
				<th width="20%">Lead Type</th>
				
				
		        <th width="20%"></th>
		    </tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>