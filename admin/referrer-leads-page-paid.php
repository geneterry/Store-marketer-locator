<?php 
	
if ( ! class_exists( 'Referrer_Lead_Table' ) ) {
	

	class Referrer_Lead_Table {
		

		/**
		 * Start up
		 */
		public function __construct()
		{

			add_action( 'admin_menu', array( $this, 'pmc_add_plugin_page' ) );
			

		}
		
		public function pmc_define(){
			global $wpdb;
			define('PMC_FS_TABLE', $wpdb->prefix . 'pmc_fs');
		}
		
		public function pmc_add_plugin_page()
		{
		
				/* add pages & menu items */
			add_menu_page( esc_attr__( 'Referrer Lead', 'textdomain' ),esc_html__( 'Referrer Lead', 'textdomain' ),
			'administrator','referrer-leads',array( $this, 'physio_create_referrer_lead_page' ), '', 10);
			
		}
		


		/**
		 * Admin page callback
		 */
		public function physio_create_referrer_lead_page()
		{
			global $wpdb;
			?>
			<div class="wrap pmc-fs">
			<?php
				$pmc_fs_table = new Referrer_Lead_Table_Content();
				echo '<div class="wrap"><h2>Referrer Lead</h2>'; 
				$pmc_fs_table->prepare_items();
				echo '<input type="hidden" name="page" value="" />';
				echo '<input type="hidden" name="section" value="issues" />';

				$pmc_fs_table->views();
				echo '<form method="post">';	
				echo ' <input type="hidden" name="page" value="pmc_fs_search">';
				$pmc_fs_table->search_box( 'search', 'search_id' );
				$pmc_fs_table->display();  
				echo '</form></div>';
					
				echo '</div>'; 

		}		
		
	}
	

	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}


	class Referrer_Lead_Table_Content extends WP_List_Table 
	{
		
		function __construct(){
			parent::__construct( array(
				'ajax'      => false        //does this table support ajax?
		) );
		
		}


		/**
		 * Add columns to grid view
		 */
		function get_columns(){

			$columns = array(		
				
				'client_name' 		=> 'Name of Lead',
				'client_address' 		=> 'Post Code',
			'client_email'      => 'Email',
				'client_tel'      => 'Phone',
				'client_time_call'      => 'Time',
				'service_type'      => 'Service Type',
			'physio_cat'    	=> 'Speciality',
			
				'client_gender'      => 'Gender',
				'client_type'      => 'Type',
				'physio_id'   		=> 'Physio Name',
				'referrer_id'   		=> 'Referrer Name',
				'date_created'      => 'Date Created',
			
			   	'is_viewed'	=> 'Purchased',
				'is_approached'	=> 'Paid',
			//'physio_id'   		=> 'Name of Physiotherapist',
			//'action'			=> 'Action'
			);
			return $columns;
		}	

		function column_default( $item, $column_name ) {

			global $wpdb;

			switch( $column_name ) { 
				
				case 'client_name':
					echo $item['client_name'];
					
				break;
					case 'client_address':
					echo $item['client_address'];
				break;
					case 'client_tel':
					echo $item['client_tel'];
				break;
				case 'client_email':
					echo $item['client_email'];
				break;
					case 'client_time_call':
					echo $item['client_time_call'];
				break;
					case 'service_type':
					echo $item['service_type'];
				break;
				case 'physio_cat':
					echo $item['physio_cat'];
				break;
					
					case 'client_gender':
					echo $item['client_gender'];
				break;
					case 'client_type':
					echo $item['client_type'];
				break;
				case 'physio_id':
					$query = "SELECT post_id FROM $wpdb->postmeta
					    WHERE (meta_value = '". $item['physio_id'] ."')";

					$rows = $wpdb->get_row($query);
					echo get_post_meta( $rows->post_id, 'store_locator_name', true );
				break;
					
					case 'referrer_id':
					$query = "SELECT post_id FROM $wpdb->postmeta
					    WHERE (meta_value = '". $item['referrer_id'] ."')";

					$rows = $wpdb->get_row($query);
					echo get_post_meta( $rows->post_id, 'store_locator_name', true );
				break;
					
					case 'date_created':
					$old_date = Date_create($item['date_created']);
					$new_date = Date_format($old_date, "d/m/Y");
					
					echo($new_date);
					break;
				
				
				case 'is_viewed':
					if($item['is_viewed'] == 1){
						echo 'Yes';
					}else{
						echo 'No';
					}
				break;
					
					case 'is_approached':
					$IDupdate = $item['id'];
					

				
					
					if($item['is_approached'] == 1){
						echo 'Paid';
					}else{
						echo($IDupdate);
						echo '
						  <form action="https://www.physiobrite.com/wp-content/plugins/physiobrite-leads/admin/referrer-leads-page.php" onsubmit="updatepaid()">
						 
  <input type="submit" value="Submit">
						</form>';
						$wpdb->update( $wpdb->prefix . 'physiobrite_client_enquiries', array("is_approached" => "1"), array("ID" => $IDupdate) );
						
						
					}
				break;
				/*case 'physio_id':
					$query = "SELECT post_id FROM $wpdb->postmeta
					    WHERE (meta_value = '". $item['physio_id'] ."')";

					$rows = $wpdb->get_row($query);
					echo get_post_meta( $rows->post_id, 'store_locator_name', true );
				break;*/
				/*case 'action':	
				break;*/
			  //return $item[ $column_name ];
			default:
			  //return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
			}
		}			
		
		protected function get_views() { 
		  $views = array();

		   return $views;
		}
		
		function updatepaid(){
	alert("The form was submitted");
			 $table_name = $wpdb->prefix . 'physiobrite_client_enquiries';

            $wpdb->query("UPDATE $table_name SET is_approached='1' WHERE id=$IDupdate");

			
		}
		
		
		
		function usort_reorder( $a, $b ) {
		  // If no sort, default to title
		  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
		  // If no order, default to asc
		  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
		  // Determine sort order
		  $result = strcmp( $a[$orderby], $b[$orderby] );
		  // Send final sort direction to usort
		  return ( $order === 'asc' ) ? $result : -$result;
		}
		
		function get_sortable_columns() {
			$sortable_columns = array(
			'date'  => array('action',false),
			);
			return $sortable_columns;
		}	

		/**
		 * Prepare admin view
		 */	
		function prepare_items() {
			global $wpdb;

			$per_page = 50;
			$current_page = $this->get_pagenum();
			if ( 1 < $current_page ) {
				$offset = $per_page * ( $current_page - 1 );
			} else {
				$offset = 0;
			}
			
			$search = '';
			//Retrieve $customvar for use in query to get items.
			$enquiryType = ( isset($_REQUEST['enquiry-type']) ? $_REQUEST['enquiry-type'] : '');
			if($enquiryType != '') {

				if( $enquiryType == "global" ){

					$search_custom_vars= " AND physio_id IS NULL ";

				}elseif( $enquiryType == "normal" ){

					$search_custom_vars= " AND physio_id IS NOT NULL ";
				
				}

			} else	{
				$search_custom_vars = '';
			}
			if ( ! empty( $_REQUEST['s'] ) ) {
				$search = "AND ( client_name LIKE '%" . esc_sql( $wpdb->esc_like( $_REQUEST['s'] ) ) . "%'";
				$search .=" OR client_address LIKE '".$requestData['search']['value']."%' ";
				$search .=" OR client_tel LIKE '".$requestData['search']['value']."%' ";
		        $search .=" OR client_email LIKE '".$requestData['search']['value']."%' ";
				$search .=" OR client_time_call LIKE '".$requestData['search']['value']."%' ";
				$search .=" OR service_type LIKE '".$requestData['search']['value']."%' )";
		        $search .=" OR physio_cat LIKE '".$requestData['search']['value']."%' )";
				$search .=" OR client_gender LIKE '".$requestData['search']['value']."%' )";
				$search .=" OR client_type LIKE '".$requestData['search']['value']."%' )";
				$search .=" OR date_created LIKE '".$requestData['search']['value']."%' )";
		        $search .=" OR client_email LIKE '".$requestData['search']['value']."%' ";
		        $search .=" OR physio_cat LIKE '".$requestData['search']['value']."%' )";
				$search .=" OR referrer_id LIKE '".$requestData['search']['value']."%' )";
			}		
			
			$tableEnquiry = $wpdb->prefix . 'physiobrite_client_enquiries';

			$items = $wpdb->get_results( "SELECT * FROM {$tableEnquiry} WHERE 1=1 AND  lead_type = 'referrerLead' {$search} {$search_custom_vars}" . $wpdb->prepare( "ORDER BY id DESC LIMIT %d OFFSET %d;", $per_page, $offset ),ARRAY_A);
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);	
			usort( $items, array( &$this, 'usort_reorder' ) );
			$count = $wpdb->get_var( "SELECT COUNT(id) FROM {$tableEnquiry} WHERE 1 = 1 AND  lead_type = 'referrerLead' {$search} {$search_custom_vars}" );

			$this->items = $items;

			// Set the pagination
			$this->set_pagination_args( array(
				'total_items' => $count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count / $per_page )
			) );
		}
		

	}

}


  $Referrer_Lead_Table = new Referrer_Lead_Table();


 ?>