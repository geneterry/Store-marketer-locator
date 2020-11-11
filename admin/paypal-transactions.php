<?php 
	
if ( ! class_exists( 'Paypal_Transaction_Table' ) ) {
	

	class Paypal_Transaction_Table {
		

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
			add_menu_page( esc_attr__( 'Paypal Transactions', 'textdomain' ),esc_html__( 'Paypal Transactions', 'textdomain' ),
			'administrator','physio-paypal-transaction',array( $this, 'physio_create_enquiry_page' ), '', 10);
			
		}
		


		/**
		 * Admin page callback
		 */
		public function physio_create_enquiry_page()
		{
			global $wpdb;
			?>
			<div class="wrap pmc-fs">
			<?php
				$pmc_fs_table = new Paypal_Transaction_Table_Content();
				echo '<div class="wrap"><h2>Paypal Transactions</h2>'; 
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


	class Paypal_Transaction_Table_Content extends WP_List_Table 
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
			'paypal_payerID' 	=> 'Paypal ID',
			'physio_name'      	=> 'Name',
			'physio_email'    	=> 'Email',
			'transaction_type'  => 'Type',
			'amount'    		=> 'Amount',
			'order_id'    		=> 'Order ID',
			'date_created'    	=> 'Date',
			//'physio_id'   		=> 'Name of Physiotherapist',
			//'action'			=> 'Action'
			);
			return $columns;
		}	

		function column_default( $item, $column_name ) {

			global $wpdb;

			switch( $column_name ) { 
				case 'paypal_payerID':
					echo $item['paypal_payerID'];
				break;
				case 'physio_name':
					echo $item['physio_name'];
				break;
				case 'physio_email':
					echo $item['physio_email'];
				break;
				case 'transaction_type':
					echo $item['transaction_type'];
				break;
				case 'amount':
					echo $item['amount'];
				break;
				case 'order_id':
					echo $item['order_id'];
				break;
				case 'date_created':
					echo $item['date_created'];
				break;

			default:
			  //return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
			}
		}			
		
		protected function get_views() { 
		  $views = array();
		   $current = ( !empty($_REQUEST['transaction_type']) ? $_REQUEST['transaction_type'] : 'all');

		   //All link
		   $class = ($current == 'all' ? ' class="current"' :'');
		   $all_url = remove_query_arg('transaction_type');
		   $views['all'] = "<a href='{$all_url }' {$class} >All</a>";

		   //upgrade_account link
		   $foo_url = add_query_arg('transaction_type','upgrade_account');
		   $class = ($current == 'upgrade_account' ? ' class="current"' :'');
		   $views['upgrade_account'] = "<a href='{$foo_url}' {$class} >Account Upgrade</a>";

		   //recharge_account
		   $bar_url = add_query_arg('transaction_type','recharge_account');
		   $class = ($current == 'recharge_account' ? ' class="current"' :'');
		   $views['recharge_account'] = "<a href='{$bar_url}' {$class} >Account Recharge</a>";

		   return $views;
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
			$transaction_type = ( isset($_REQUEST['transaction_type']) ? $_REQUEST['transaction_type'] : '');
			if($transaction_type != '') {

					$search_custom_vars= " AND transaction_type = '" . $transaction_type . "' ";

			} else	{
				$search_custom_vars = '';
			}

			if ( ! empty( $_REQUEST['s'] ) ) {
				$search = "AND ( paypal_payerID LIKE '%" . esc_sql( $wpdb->esc_like( $_REQUEST['s'] ) ) . "%'";
		        $search .=" OR physio_name LIKE '".$requestData['search']['value']."%' ";
		        $search .=" OR physio_email LIKE '".$requestData['search']['value']."%' ";
		        $search .=" OR order_id LIKE '".$requestData['search']['value']."%' )";
			}		
			
			$tablePaypal = $wpdb->prefix . 'physiobrite_payment_trasaction';

			$items = $wpdb->get_results( "SELECT * FROM {$tablePaypal} WHERE 1=1 {$search} {$search_custom_vars}" . $wpdb->prepare( "ORDER BY id DESC LIMIT %d OFFSET %d;", $per_page, $offset ),ARRAY_A);
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);	
			usort( $items, array( &$this, 'usort_reorder' ) );
			$count = $wpdb->get_var( "SELECT COUNT(id) FROM {$tablePaypal} WHERE 1 = 1 {$search} {$search_custom_vars}" );

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


  $Paypal_Transaction_Table = new Paypal_Transaction_Table();


 ?>