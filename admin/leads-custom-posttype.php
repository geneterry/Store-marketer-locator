<?php 

/*
* Create unverified_users post type
*/
 
function unverified_users_post_type_func() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Unverified Users', 'Post Type General Name', 'physiobrite' ),
        'singular_name'       => _x( 'Unverified User', 'Post Type Singular Name', 'physiobrite' ),
        'menu_name'           => __( 'Unverified Users', 'physiobrite' ),
        'parent_item_colon'   => __( 'Parent Unverified Users', 'physiobrite' ),
        'all_items'           => __( 'All Unverified Users', 'physiobrite' ),
        'view_item'           => __( 'View Unverified User', 'physiobrite' ),
        'add_new_item'        => __( 'Add New Unverified User', 'physiobrite' ),
        'add_new'             => __( 'Add New', 'physiobrite' ),
        'edit_item'           => __( 'Edit Unverified User', 'physiobrite' ),
        'update_item'         => __( 'Update Unverified User', 'physiobrite' ),
        'search_items'        => __( 'Search Unverified User', 'physiobrite' ),
        'not_found'           => __( 'Not Found', 'physiobrite' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'physiobrite' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Unverified Users', 'physiobrite' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title' ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'capabilities' => array(
		    'create_posts' => 'do_not_allow',
		  ),
        'map_meta_cap' => true,
    );
     
    // Registering your Custom Post Type
    register_post_type( 'unverified_users', $args );
 
}
 

//Register Expired user post type 
add_action( 'init', 'unverified_users_post_type_func', 0 );



function expired_users_post_type_func() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Expired Users', 'Post Type General Name', 'physiobrite' ),
        'singular_name'       => _x( 'Expired User', 'Post Type Singular Name', 'physiobrite' ),
        'menu_name'           => __( 'Expired Users', 'physiobrite' ),
        'parent_item_colon'   => __( 'Parent Expired Users', 'physiobrite' ),
        'all_items'           => __( 'All Expired Users', 'physiobrite' ),
        'view_item'           => __( 'View Expired User', 'physiobrite' ),
        'add_new_item'        => __( 'Add New Expired User', 'physiobrite' ),
        'add_new'             => __( 'Add New', 'physiobrite' ),
        'edit_item'           => __( 'Edit Expired User', 'physiobrite' ),
        'update_item'         => __( 'Update Expired User', 'physiobrite' ),
        'search_items'        => __( 'Search Expired User', 'physiobrite' ),
        'not_found'           => __( 'Not Found', 'physiobrite' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'physiobrite' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Expired Users', 'physiobrite' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title' ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
          ),
        'map_meta_cap' => true,
    );
     
    // Registering your Custom Post Type
    register_post_type( 'expired_users', $args );
 
}
 
 
add_action( 'init', 'expired_users_post_type_func', 0 );

function deleted_accounts_post_type_func() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Deleted Accounts', 'Post Type General Name', 'physiobrite' ),
        'singular_name'       => _x( 'Deleted Account', 'Post Type Singular Name', 'physiobrite' ),
        'menu_name'           => __( 'Deleted Accounts', 'physiobrite' ),
        'parent_item_colon'   => __( 'Parent Deleted Accounts', 'physiobrite' ),
        'all_items'           => __( 'All Deleted Accounts', 'physiobrite' ),
        'view_item'           => __( 'View Deleted Account', 'physiobrite' ),
        'add_new_item'        => __( 'Add New Deleted Account', 'physiobrite' ),
        'add_new'             => __( 'Add New', 'physiobrite' ),
        'edit_item'           => __( 'Edit Deleted Account', 'physiobrite' ),
        'update_item'         => __( 'Update Deleted Account', 'physiobrite' ),
        'search_items'        => __( 'Search Deleted Account', 'physiobrite' ),
        'not_found'           => __( 'Not Found', 'physiobrite' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'physiobrite' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Deleted Accounts', 'physiobrite' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title' ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
          ),
        'map_meta_cap' => true,
    );
     
    // Registering your Custom Post Type
    register_post_type( 'deleted_accounts', $args );
 
}
 
 
add_action( 'init', 'deleted_accounts_post_type_func', 0 );

 ?>