<?php
$debug_mode = false;

class Sync_Tool_Admin_Object extends Runway_Admin_Object {
	public $server_settings, $settings;
	public $data, $webserv_path, $response;

	// remote call methods list
	private $remote_actions = array( 'method_test', 'get_extensions_data', 'ping', 'get_all_data', 
	'get_posts',	'get_categories', 'get_tags', 'get_plugins', 'get_users', 'get_themes', 
	'export_options', 'create_categories', 'create_posts', 'create_tags', 'create_users', 'get_items_count',
	'get_permissions');

	public function __construct($settings) {

		parent::__construct($settings);

		$this->settings = $settings;
		$this->navigation = (isset($_REQUEST['navigation'])) ? $_REQUEST['navigation'] : '';
		$this->action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';


		// You can get all data in json by this URL
		$this->webserv_path = admin_url('admin-ajax.php?action=');

		$this->key_option_id = $this->settings['option_key'] . '_sync_outh_key';

		$this->server_settings = get_option( $this->settings['option_key'] );

		add_action( 'init', array( $this, 'append_hooks' ) );		
	}

	public function append_hooks() {

		add_action( 'wp_ajax_nopriv_sync', array( $this, 'accept_sync_connection' ) );
		add_action( 'wp_ajax_sync', array( $this, 'accept_sync_connection' ) );

	}
	// debug
	public function method_test( $params = array() ) {

		$this->response->test = $params['message'];

	}

	public function ping() {

		$this->response->message = __('Connected', 'framework');

	} 

	public function prepare_authentication($connection = array()) {		
		switch($connection['type']) {			
			case 'account_based': {
				return array('type' => $connection['type'], 'login' => $connection['login'], 'password' => $connection['password']);
			} break;
			
			case 'key_based': {
				return array('type' => $connection['type'], 'key' => $connection['access_key']);
			} break;

			default: {
				return false;				
			} break;
		}
	}

	// execute reqeust
	public function request( $url = '', $authentication = array(), $request = array() ) {
		global $debug_mode;

		// check required params
		if ( empty( $url ) || empty( $authentication ) || empty( $request ) ) {
			return false;
		}

		// build post
		$postdata = http_build_query(
			array(
				'authentication' => $authentication,
				'request' => $request,
			)
		);

		// set request options
		$opts = array( 
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata,
			)
		);

		// get context
		$context  = stream_context_create( $opts );

		$request_url = $url . '/wp-admin/admin-ajax.php?action=sync';

		// execute query convert/result
		if(isset( $debug_mode ) && $debug_mode) {
			$response = wp_remote_post($url . '/wp-admin/admin-ajax.php?action=sync&'.$postdata);
			return $response['body'];
			//return file_get_contents( $url . '/wp-admin/admin-ajax.php?action=sync', false, $context );
		} else {
			$file = $url . '/wp-admin/admin-ajax.php?action=sync';
			$file_headers = @get_headers($file);
			$exists = ( isset($file_headers[0] ) )? true : false;
			
			/*if( $exists )
				return json_decode( file_get_contents( $url . '/wp-admin/admin-ajax.php?action=sync', false, $context ), true );
			else
				return false;*/
			
			if( $exists ) {
				$response = wp_remote_post($url . '/wp-admin/admin-ajax.php?action=sync&'.$postdata);
				return json_decode($response['body'], true);
			}
			else {
				return false;
			}
		}

	}

	// run requested method
	public function process_request( $request ) {
		// check is method allowd for remote call
		if ( in_array( $request['action'], $this->remote_actions ) ) {
			// check permisiions on operation
			if(($request['resource'] == 'service' && $request['operation'] == 'service') || 
				in_array($request['resource'], $this->server_settings['permissions'][$request['operation']])
				){
				// run method		
				$this->$request['action']( isset($request['params']) ? $request['params'] : array() );
			}
			else{
				$this->response->state[] = 'refused';
				$this->response->state[] = 'access-denied';	
			}
		} else {
			$this->response->state[] = 'refused';
			$this->response->state[] = 'wrong-action';
		}
	}

	// accept connections
	public function accept_sync_connection() {

		extract( $_REQUEST );	

		if(!isset($this->response))
			$this->response = new stdClass();
		// check authentication type
		switch ( $authentication['type'] ) {
			// secret key based authentication
			case 'key_based': {
				// check key
				if ( $authentication['key'] != $this->get_key() ) {
					$this->response->state[] = 'refused';
					$this->response->state[] = 'wrong-authentication-key';
					$this->response->errors->incorect_key = 'ERROR: Incorect access key!';
				}
			} break;

			// account based authentication
			case 'account_based': {
				// try to signin user
				$result = wp_signon( array(
					'user_login' => $authentication['login'],
					'user_password' => $authentication['password'],
				) );

				// check if user exist
				if ( get_class( $result ) == 'WP_Error' ) {
					$this->response->state[] = 'refused';

					// remove links to server
					foreach ( $result->errors as $key => $value ) {
						$result->errors[$key] = strip_tags( $value[0] );
					}

					// attach errors to responce
					$this->response->errors = $result->errors;
				} else {
					// check if user has admin permissions
					if ( !$result->caps['administrator'] ) {
						$this->response->state[] = 'refused';
						$this->response->state[] = 'wrong-user-access-level';
						$this->response->errors->wrong_user_access_level = 'ERROR: Wrong user access level!';
					}
				}
			} break;

			default: {
				$this->response->state[] = 'refused';
				$this->response->state[] = 'wrong-authentication-type';
			} break;
		}
		
		if(!isset($this->response->state))
			$this->response->state = array();

		if ( !in_array( 'refused', (array)$this->response->state ) ) {
			$this->response->state[] = 'success';
			// process request
			$this->process_request( $request );
		}

		echo json_encode( $this->response );

		exit;
	}

	// Geting data from extensions
	public function get_extensions_data($params = array()) {		
		global $extm;
		$themeInfo = rw_get_theme_data();
		$data = array();

		// Data to active extensions
		foreach ( $extm->get_active_extensions_list( THEME_NAME ) as $key => $value ) {
			$data['active_exts'][$value] = $extm->get_extension_data( $extm->extensions_dir.$value, false, false );
			$option_key = explode( '/', $value );
			$option_key = $themeInfo['Folder'].'_'.$option_key[0];
			$data['active_exts'][$value]['option_key'] = $option_key;
			$data['active_exts'][$value]['data'] = get_option( $option_key );
		}

		// Getting desible extensions list
		$data['desible_exts'] = $extm->get_desible_extensions_list(THEME_NAME);
		foreach ( $data['desible_exts'] as $extension => $extension_info ) {
			$option_key = explode( '/', $extension );
			$option_key = $themeInfo['Folder'].'_'.$option_key[0];
			$data['desible_exts'][$value]['option_key'] = $option_key;
			$data['desible_exts'][$extension]['data'] = get_option( $option_key );
		}

		// Getting core extensions list and data
		$data['core_exts'] = $extm->get_extensions_list( $extm->core_extensions );
		if ( isset( $data['core_extensions'] ) && $data['core_extensions'] ) {
			foreach ( $data['core_extensions'] as $key => $value ) {
				$option_key = explode( '/', $key );
				$option_key = $option_key[0];
				switch ( $option_key ) {
				case 'extensions-manager':{
						$option_key = $themeInfo['Folder'].'_'.$option_key;
						$data['core_extensions'][$value]['option_key'] = $option_key;
						$data['core_extensions'][$key]['data'] = get_option( $option_key );
					} break;

				case 'options-builder':{
						global $apm;
						$data['core_extensions'][$value]['option_key'] = $option_key;						
						$data['core_extensions'][$key]['data'] = $apm->get_pages_list();
					} break;

				default:{
						$data['core_extensions'][$key]['data'] = array();
					} break;
				}
			}
		}
		if( isset($this->response) )
			$this->response->extensions_data = $data;
		return $data;
	}

	public function get_posts($params = array()){ 
		$args = array(
			'posts_per_page' => -1
		);
		$posts = get_posts($args);
		$this->response->posts = $posts;
		return $posts;
	}

	public function get_categories($params = array()){
		$categories = get_categories();
		$this->response->categories = get_categories();
		return $categories;
	}

	public function get_tags(){
		$tags = get_tags();
		$this->response->tags = $tags;
		return $tags;
	}

	public function get_plugins(){
		$plugins = get_plugins();
		$this->response->plugins = $plugins;
		return $plugins;
	}

	public function get_users(){
		$users = get_users();
		$this->response->users = $users;
		return $users;
	}

	public function get_themes(){
		// TODO: get all themes from server
	}

	public function get_all_data(){		
		$all_data->extensions_data = $this->get_extensions_data();
		$all_data->posts = $this->get_posts();
		$all_data->categories = $this->get_categories();
		$all_data->tags = $this->get_tags();
		$all_data->plugins = $this->get_plugins();
		$all_data->users = $this->get_users();

		return $all_data;
	}

	public function set_permissions($permissions_list = array(), $operation = 'import'){
		if(empty($this->server_settings)){
			$this->server_settings = get_option( $this->settings['option_key'] );
		}

		$this->server_settings['permissions'][$operation] = $permissions_list;
		update_option( $this->settings['option_key'], $this->server_settings );
	}

	public function get_permissions(){
		$this->response->permissions = $this->server_settings['permissions'];
		return $this->server_settings['permissions'];
	}

	public function generate_key() {		
		$key = md5( uniqid() );
		update_option( $this->key_option_id, $key );
		return $key;

	}

	public function get_key() {
		$key = get_option( $this->key_option_id );

		if ( !$key ) {
			return $this->generate_key();
		} else {
			return $key;
		}

	}

	public function get_extensions_differencies($local_extensions = array(), $server_extensions = array()){
		global $extm;
		// $local_extensions = $this->get_extensions_data();
		$differencies = array(
			'same_extensions' => array(),
			'must_be_active' => array(),
			'must_be_installed' => array(),
			'desible_on_server' => (array)$server_extensions['desible_exts'],
		);

		$differencies['same_extensions'] = array_intersect_key(
			(array)$server_extensions['active_exts'], 
			(array)$local_extensions['active_exts']);

		$diff_exts = array_diff_key(
			(array)$server_extensions['active_exts'], 
			(array)$local_extensions['active_exts']);
		
		foreach ($diff_exts as $key => $value) {
			if(file_exists($extm->extensions_dir.$key)){
				$differencies['must_be_active'][$key] = $value;
			}
			else{
				$differencies['must_be_installed'][$key] = $value;
			}
		}

		return $differencies;
	}

	public function export_options($params = array()){
		$themeInfo = rw_get_theme_data();
		$option_key = explode( '/', $params['extension'] );
		$option_key = $themeInfo['Folder'].'_'.$option_key[0];
		
		update_option($option_key, $params['export_data']['data']);
	
		$this->response->export['option_key'] = $option_key;
		$this->response->export['data'] = $params['export_data']['data'];
	}

	public function create_categories($params = array()){		
		$result = wp_create_category($params['name']);
		$response = array();
		if($result != 0){
			$this->response['new_categoty_id'] = $result;
			$this->response['import_export_state'] = $response['import_export_state'] = 'success';
			$this->response['result_message'] = $response['result_message'] = 'Category "'.$params['name'].'" created';
		}
		else{
			$this->response['import_export_state'] = $response['import_export_state'] = 'fail';
			$this->response['result_message'] = $response['result_message'] = 'Creation category "'.$params['name'].'" failed';
		}
		return $response;
	}		

	public function create_posts($params = array()){
		unset($params['ID'], $params['post_name'], $params['guid']);
		$response = array();
		$params['post_parent'] = 0;
		$result = wp_insert_post($params);
		if($result != 0){
			$this->response['new_post_id'] = $response['new_post_id'] = $result;
			$this->response['import_export_state'] = $response['import_export_state'] = 'success';
			$this->response['result_message'] = $response['result_message'] = 'Post "'.$params['post_title'].'" created';
		}
		else{
			$this->response['import_export_state'] = $response['import_export_state'] = 'fail';
			$this->response['result_message'] = $response['result_message'] = 'Creation post "'.$params['post_title'].'" failed';
		}
		return $response;
	}

	public function create_tags($params = array()){		
		$result = wp_create_tag($params['name']);
		$response = array();
		if($result != 0){
			$this->response['new_tag_id'] = $result;
			$this->response['import_export_state'] = $response['import_export_state'] = 'success';
			$this->response['result_message'] = $response['result_message'] = 'Tag "'.$params['name'].'" created';
		}
		else{
			$this->response['import_export_state'] = $response['import_export_state'] = 'fail';
			$this->response['result_message'] = $response['result_message'] = 'Creation tag "'.$params['name'].'" failed';
		}

		return $response;
	}

	public function create_users($params = array()){
		global $wpdb;
		$response = array();

		if(!email_exists($params['data']['user_email']) && username_exists($params['data']['user_login']) == null){
			$wpdb->insert(
				$wpdb->prefix.'users',
				array(
					'user_login' => $params['data']['user_login'], 
					'user_pass' => $params['data']['user_pass'],
					'user_nicename' => $params['data']['user_nicename'],
					'user_email' => $params['data']['user_email'],
					'user_url' => $params['data']['user_url'],
					'user_registered' => $params['data']['user_registered'],
					'user_activation_key' => $params['data']['user_activation_key'],
					'user_status' => $params['data']['user_status'],
					'display_name' => $params['data']['display_name']
				)
			);

			$user_id = $wpdb->insert_id;
			$user = new WP_User($user_id);

			if(isset($params['caps'])){
				// TODO: add user caps
			}

			if(isset($params['roles'])){
				$user->set_role($params['roles'][0]);
			}

			if(isset($params['allcaps'])){
				foreach ($params['allcaps'] as $capability => $value) {
					$user->add_cap($capability);
				}
			}

			$this->response['new_user_id'] = $user_id;
			$this->response['import_export_state'] = $response['import_export_state'] = 'success';
			$this->response['result_message'] = $response['result_message'] = 'User "'.$params['data']['user_login'].'" created';
		}
		else{
			$this->response['import_export_state'] = $response['import_export_state'] = 'fail';
			$this->response['result_message'] = $response['result_message'] = 'Creation user "'.$params['data']['user_login'].'" failed';
		}

		return $response;
	}

	public function get_items_count(){
		$extensions_data = $this->get_extensions_data();
		$extensions_count = count($extensions_data['active_exts']);
		$posts_count = wp_count_posts();
		$categories_count = count(get_categories());
		$tags_count = count(get_tags());
		$plugins_count = count(get_plugins());
		$users_count = count(get_users());

		$count = array(
			'extensions' => $extensions_count,
			'posts' => $posts_count->publish,
			'categories' => $categories_count,
			'tags' => $tags_count,
			'plugins' => $plugins_count,
			'users' => $users_count
		);
		$this->response = $count;
		return $count;
	}

	function add_actions() {
		add_action( 'wp_ajax_update_new_connection', array( $this, 'update_connection' ) );
		add_action( 'wp_ajax_del_connection', array( $this, 'delete_connection' ) );
	}

	public function load_objects() {

		/* *** */

	}

	public function update_connection($slug = null, $new_connection = array()){
		// if ajax request
		if($slug == null && empty($new_connection)){
			if(!isset($_POST['old_slug'])){
				$slug = sanitize_title($_POST['slug']);
			}
			else{
				$slug = $_POST['old_slug'];
			}
			$new_connection['type'] = $_POST['type'];
			$new_connection['server_url'] = $_POST['server_url'];
			$new_connection['connection_name'] = $_POST['slug'];
		}

		switch ($new_connection['type']) {
			case 'key_based':{
				$new_connection['access_key'] = $_POST['access_key'];
				$this->server_settings['connections'][$slug] = $new_connection;
			} break;			

			case 'account_based':{
				$new_connection['login'] = $_POST['login'];
				$new_connection['password'] = $_POST['password'];
				$this->server_settings['connections'][$slug] = $new_connection;
			} break;			
		}
		update_option($this->option_key, $this->server_settings);
	}

	public function delete_connection($slug = null){
		if($slug == null){
			$slug = $_POST['slug'];
		}
		unset($this->server_settings['connections'][$slug]);
		update_option($this->option_key, $this->server_settings);
	}	
}

?>