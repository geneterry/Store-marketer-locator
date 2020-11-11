<?php 

	add_role(
	    'physiobrite_leads',
	    __( 'Physiobrite Leads' ),
	    array(
	        'read'         => true,  // true allows this capability
	        'edit_posts'   => false,
	        'delete_posts' => false // Use false to explicitly deny
	    )
	);
	add_role(
	    'physiobrite_user',
	    __( 'Physiobrite User' ),
	    array(
	        'read'         => true,  // true allows this capability
	        'edit_posts'   => false,
	        'delete_posts' => false // Use false to explicitly deny
	    )
	);
add_role(
	    'referrer_leads',
	    __( 'Referrer' ),
	    array(
	        'read'         => true,  // true allows this capability
	        'edit_posts'   => false,
	        'delete_posts' => false // Use false to explicitly deny
	    )
	);
 ?>