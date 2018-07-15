<?php
/*
 * Plugin Name: Event Manager
 * Author: Eliasu Abraman
 * Version: 1.0.0
 * Description: A plugin to manage events.
 * Author_uri: www.eliasuabraman.com
 **/
 if(!function_exists('add_action')){
 	die('Sorry we can\'t help!!' );
 }
define( 'EM_BASE', plugin_dir_path( __FILE__ ));
define( 'EM_BASE_URL', plugin_dir_url( __FILE__ ));

//define( 'EM_BASE', __FILE__ );

add_action( 'init', 'em_register_event_post_type' );
function em_register_event_post_type(){
	
	
	$labels = array(
		'name'               => __( 'Events', 'event-manager' ),
		'singular_name'      => __( 'Event', 'event-manager' ),
		'add_new'            => _x( 'Add New Event', 'event-manager', 'event-manager' ),
		'add_new_item'       => __( 'Add New Event', 'event-manager' ),
		'edit_item'          => __( 'Edit Event', 'event-manager' ),
		'new_item'           => __( 'New Event', 'event-manager' ),
		'view_item'          => __( 'View Event', 'event-manager' ),
		'search_items'       => __( 'Search Events', 'event-manager' ),
		'not_found'          => __( 'No Events found', 'event-manager' ),
		'not_found_in_trash' => __( 'No Events found in Trash', 'event-manager' ),
		'parent_item_colon'  => __( 'Parent Event:', 'event-manager' ),
		'menu_name'          => __( 'Events', 'event-manager' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'thumbnail',
			'custom-fields',
			'trackbacks',
			'comments',
			'revisions',
			'page-attributes',
			'post-formats',
		),
	);

	register_post_type( 'event-manager', $args );
	
	
}
add_action( 'do_meta_boxes', 'em_add_event_meta_box' );
function em_add_event_meta_box(){
	add_meta_box( 'em-event-meta', __('Event Options', 'event-manager'), 'show_event_meta_box', 'event-manager', 'normal', '' );
}
function show_event_meta_box($post){

	include EM_BASE .'meta/event.php';
}
add_action( 'save_post_event-manager', 'em_save_events_meta_fields' );
function em_save_events_meta_fields($post_id){
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE  ) {         
    	return;
    }
    foreach (array( 'event_date', 'event_url', 'event_location') as $key) {
    	if(isset($_POST[$key])){
    		$value = ($key == 'url ') ? sanitize_url( $_POST[$key] ) : sanitize_text_field($_POST[$key]);
    		update_post_meta( $post_id, sprintf( 'em_%s', $key ), $value );
    	}
    }
}
add_action( 'admin_print_scripts', 'em_enqueue_admin_scripts' );
function em_enqueue_admin_scripts($page){
	global $post_type;
	if($post_type == 'event-manager'){
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'em-map-js', 'http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyAoju93rH8MhWnAsnchGO2JB9kqte0b7K4');
		wp_enqueue_script( 'em-jquery-location-js', EM_BASE_URL.'js/jquery-location.js', array( 'em-map-js'), '', true );
		wp_enqueue_script( 'em-admin-js', EM_BASE_URL.'js/admin.js', array( 'jquery', 'em-jquery-location-js', 'em-map-js' ), '', true );
		
		wp_enqueue_style( 'em-admin-css', EM_BASE_URL . 'css/admin.css' );

	}
	
}
add_filter( 'template_include', 'em_event_list_redirect' );
function em_event_list_redirect($file){
	//is_tax( $taxonomy = '', $term = '' )
	if(is_singular( 'event-manager' )){

		return EM_BASE .'templates/tax-events-manager.php';
	}
	if( !is_post_type_archive( 'event-manager') ){
		return $file;
	}
	require EM_BASE.'vendor/autoload.php';

	return EM_BASE .'templates/archive.php';
	
}
add_action( 'wp_enqueue_scripts', 'em_add_style' );
function em_add_style(){
	if (is_post_type_archive( 'event-manager' ) || is_singular( 'event-manager' )) {
		wp_enqueue_style( 'em-main-css', EM_BASE_URL .'css/style.css' );
	}
}
function em_google_calendar_client(){

	$client = new Google_Client();
	$client->setClientId('828192286022-s5n98q3fqsn4quoflnckn9gfgl9fh78u.apps.googleusercontent.com');
    $client->setClientSecret('ek_yWLschOcLlATDNoRDTb2j');
    $client->setApplicationName('Event Manager');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    //$client->setAuthConfig('client_secret.json');
    $client->setRedirectUri( 'http://localhost:8888/dev/testing-site/event-manager/first-event/' );
    $client->setAccessType('offline');
    $authUrl = $client->createAuthUrl();
    return $client;
}
//add_action( 'init', 'bb_update_email' );
function bb_update_email(){
	update_option('admin_email', 'b@benjaminboone.com');
}