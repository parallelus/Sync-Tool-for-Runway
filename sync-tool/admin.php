<div class="nav-tabs">

	<h2 class="nav-tab-wrapper tab-controlls" style="padding-top: 9px;">
		<a href="<?php echo $this->self_url(); ?>" class="nav-tab <?php if($this->navigation == '') {echo "nav-tab-active";} ?>"><?php _e('Dashboard', 'framework') ?></a>
		<a href="<?php echo $this->self_url('connections'); ?>" class="nav-tab <?php if($this->navigation == 'connections') {echo "nav-tab-active";} ?>"><?php _e('Connection tool', 'framework') ?></a>
		<a href="<?php echo $this->self_url('server-settings'); ?>" class="nav-tab <?php if($this->navigation == 'server-settings') {echo "nav-tab-active";} ?>"><?php _e('Server settings', 'framework') ?></a>
		<!-- <a href="<?php echo $this->self_url('test-connection'); ?>" class="nav-tab <?php if($this->navigation == 'server-settings') {echo "nav-tab-active";} ?>"><?php _e('Test connection', 'framework'); ?></a> -->
	</h2>

</div>

<div class="tab-layout">

	<?php

		global $sync_tool_admin, $extm;

		if(empty($this->action)) {
			$this->action = $this->navigation;
		}
		
		if( isset($_GET['connection_id']) ) {					
			$connection_id = $_GET['connection_id'];
			$connection = $this->server_settings['connections'][$connection_id];

		}
		switch($this->action) {			
			case 'ping': {
				$connection_id = $_GET['connection_id'];
				$connection = $this->server_settings['connections'][$connection_id];
				$ping_response = request($connection, 'ping');
				$ping_response_list[] = array( 'connection' => $connection['connection_name'],
											   'state' => isset($ping_response)? $ping_response['state'] : '',
											   'message' => isset($ping_response)? $ping_response['message'] : '');
				include 'views/sync-tool-connections.php';
				} break;
			case 'ping-all': {
				foreach($this->server_settings['connections'] as $key => $server) {
					$connection = $server;
					$ping_response = request($connection, 'ping');
					$ping_response_list[] = array( 'connection' => $server['connection_name'],
											   'state' => isset($ping_response)? $ping_response['state'] : '',
											   'message' => isset($ping_response)? $ping_response['message'] : '');				
					}
				include 'views/sync-tool-connections.php';
				} break;				
			case 'connection': {
				// TODO: Need to be added checking if connection does not exist
				if( !isset($connection) ) {
					$this->navigation = '404';
				} else {
					$connection_id = $_GET['connection_id'];
					$connection = $this->server_settings['connections'][$connection_id];

					$permissions_on_server = request($connection, 'get_permissions');
					if($permissions_on_server['state'][0] == 'success'){
						$permissions_on_server = $permissions_on_server['permissions'];
					}
					else $permissions_on_server = array();
					// out($permissions_on_server);

					if($this->navigation == 'connection'){
						// Ping request
						$ping_response = request($connection, 'ping');
						$data_count_local = $sync_tool_admin->get_items_count();
						$data_count_server = request($connection, 'get_items_count');
					}					

					// import-export START
					$operation = (isset($_REQUEST['operation'])) ? $_REQUEST['operation'] : 'import';
					if( isset($operation)  && isset($_REQUEST['what'])) {
						$what = $_REQUEST['what'];
						switch ($operation) {
							case 'import': {	
								if(isset($_REQUEST['item-id'])){		
									$item_id = $_REQUEST['item-id'];
									$data = request($connection, 'get_'.$what);
									if(isset($data['state'][0]) && $data['state'][0] == 'success'){
										$data = $data[$what][$item_id];
									}
									$func = 'create_'.$what;
								    $response = $sync_tool_admin->$func($data);
								    $message = rf__($response['result_message']) . ' ' . __('on your site', 'framework');
								}
							} break;

							case 'export': {
								if(isset($_REQUEST['item-id'])){
									$item_id = $_REQUEST['item-id'];
									$func = 'get_'.$what;
									$data = $sync_tool_admin->$func();
									$data = (array)$data[$item_id];
									$func = 'create_'.$what;

									$response = request($connection, $func, $what, $operation, $data);
									$message = rf__($response['result_message']) . ' ' . __('on server', 'framework');
								}
							} break;

							case 'import-all': {
								$operation = str_replace('-all', '', $operation);
								$data = request($connection, 'get_'.$what, $what, $operation);
								if(isset($data['state'][0]) && $data['state'][0] == 'success'){
									$data = $data[$what];
								}

								$func = 'create_'.$what;
								$success = 0;
								$fail = 0;

							    foreach ($data as $key => $value) {
								    $sync_tool_admin->$func($value);

								    if($sync_tool_admin->response['import_export_state'] == 'success'){
								    	$success++;
								    }
								    elseif($sync_tool_admin->response['import_export_state'] == 'fail'){
								    	$fail++;
								    }
							    }
							    
							    $message = '';

							    if($success != 0){
							    	$message .= __("Imported", 'framework').' '.rf__($success).' '.rf__($what).'.';
							    }								    
							    if($fail != 0){
							    	$message .= __("Failed import", 'framework').' '.rf__($fail).' '.rf__($what).'.';
							    }
							} break;

							case 'export-all': {
								$func = 'get_'.$what;
								$data = $sync_tool_admin->$func();
								
								$func = 'create_'.$what;
								$success = 0;
								$fail = 0;

								foreach ($data as $key => $value) {
									$operation = str_replace('-all', '', $operation);
									$response = request($connection, $func, $what, $operation, (array) $value);
									if($response['import_export_state'] == 'success'){
								    	$success++;
								    }
								    elseif($response['import_export_state'] == 'fail'){
								    	$fail++;
								    }
							    }
							    
							    $message = '';

							    if($success != 0){
							    	$message .= __("Exported", 'framework').' '.rf__($success).' '.rf__($what).'.';
							    }								    

							    if($fail != 0){
							    	$message .= __("Failed export", 'framework').' '.rf__($fail).' '.rf__($what).'.';							    	
							    }
							} break;
						}
					}
					$operation = str_replace('-all', '', $operation);
					// import-export END		
				}
			} break;

			case 'delete-connection':{
				if(isset($_GET['connection_id'])){
					$this->delete_connection($_GET['connection_id']);
					include 'views/sync-tool-connections.php';

					$link = admin_url('options-general.php?page=sync-tool&navigation=connections');
    				$redirect = '<script type="text/javascript">window.location = "'.$link.'";</script>';
    				echo $redirect;
				}
			} break;

			case 'create-new-key': {
				$sync_tool_admin->generate_key();
			} break;

			case 'set-permissions-on-import': {
				// $sync_tool_admin->generate_key();
				if(isset($_POST['import-permissions-list']) && !empty($_POST['import-permissions-list'])){
					$sync_tool_admin->set_permissions($_POST['import-permissions-list'], 'import');
				}
			} break;

			case 'set-permissions-on-export': {
				// $sync_tool_admin->generate_key();
				if(isset($_POST['export-permissions-list']) && !empty($_POST['export-permissions-list'])){
					$sync_tool_admin->set_permissions($_POST['export-permissions-list'], 'export');
				}
			} break;

			default: {} break;
		}

		switch($this->navigation) {
			case '404': {
				include 'views/sync-tool-404.php';
			} break;

			case 'ping': {
				include 'views/sync-tool-ping.php';
			} break;

			case "connection": {
				include 'views/sync-tool-connection.php';
			} break;

			case "connections": {
				include 'views/sync-tool-connections.php';
			} break;

			case "server-settings": {
				include 'views/sync-tool-server-settings.php';
			} break;

			case 'merge-extensions':{
				$operation = explode('-', $operation);
				$operation = $operation[0];

				// Getting data from server
				$server_data = request($connection, 'get_extensions_data', $what, $operation);

				if(isset($server_data['state'][0]) && $server_data['state'][0] = 'success'){
					$server_data = isset( $server_data['extensions_data'] )? $server_data['extensions_data'] : '';
					$server_data['extensions_data'] = $server_data;
				}

				$local_data = $sync_tool_admin->get_extensions_data();

				$diff_extensions = $sync_tool_admin->get_extensions_differencies(
					$local_data, 
					$server_data['extensions_data']
				);

				$extension = isset( $_REQUEST['extension'] )? $_REQUEST['extension'] : false;
				$option_key = isset( $_REQUEST['option_key'] )? $_REQUEST['option_key'] : false;

				if( $extension && $option_key )
					switch ($operation) {
						case 'import':{
							$data = $server_data['extensions_data']['active_exts'][$extension];
							update_option($option_key, $data['data']);

						} break;

						case 'export':{										
							$data['export_data'] = $local_data['active_exts'][$extension];	
							$data['extension'] = $extension;
							$response = request($connection, 'export_options', 'extensions', 'export', $data);

						} break;
					}

				include_once 'views/sync-tool-merge-extensions.php';
			} break;			

			case 'import-posts':{
				$server_posts = request($connection, 'get_posts', 'posts', $operation);
				if(isset($server_posts['state'][0]) && $server_posts['state'][0] == 'success'){
					$server_posts = $server_posts['posts'];
				}
				
				include_once 'views/'.$operation.'/'.$operation.'-posts.php';
			} break;

			case 'export-posts':{
				$local_posts = $sync_tool_admin->get_posts();

				include_once 'views/'.$operation.'/'.$operation.'-posts.php';
			} break;

			case 'import-categories':{
				$server_categories = request($connection, 'get_categories', $what, $operation);
				if(isset($server_categories['state'][0]) && $server_categories['state'][0] == 'success'){
					$server_categories = $server_categories['categories'];
				}
				
				include_once 'views/'.$operation.'/'.$operation.'-categories.php';
			} break;

			case 'export-categories':{
				$local_categories = $sync_tool_admin->get_categories();
				
				include_once 'views/'.$operation.'/'.$operation.'-categories.php';
			} break;

			case 'import-tags':{
				$server_tags = request($connection, 'get_tags', $what, $operation);
				if(isset($server_tags['state'][0]) && $server_tags['state'][0] == 'success'){
					$server_tags = $server_tags['tags'];
				}

				include_once 'views/'.$operation.'/'.$operation.'-tags.php';
			} break;

			case 'export-tags':{
				$local_tags = $sync_tool_admin->get_tags();
				
				include_once 'views/'.$operation.'/'.$operation.'-tags.php';
			} break;

			case 'import-plugins':{				
				$server_plugins = request($connection, 'get_plugins', $what, $operation);
				if(isset($server_plugins['state'][0]) && $server_plugins['state'][0] == 'success'){
					$server_plugins = $server_plugins['plugins'];
				}
				
				include_once 'views/'.$operation.'/'.$operation.'-plugins.php';
			} break;			

			case 'import-users':{
				$server_users = request($connection, 'get_users', $what, $operation);
				if(isset($server_users['state'][0]) && $server_users['state'][0] == 'success'){
					$server_users = $server_users['users'];
				}
				
				include_once 'views/'.$operation.'/'.$operation.'-users.php';
			} break;

			case 'export-users':{
				$local_users = $sync_tool_admin->get_users();

				include_once 'views/'.$operation.'/'.$operation.'-users.php';
			} break;

			// case 'test':{
			// 	out('!!!!!!!!!!!!');
			// 	$args = array(
	  //           	'action' => 'ping',
	  //           	'params' => array(),
	  //           	'resource' => 'service',
	  //           	'operation' => 'service',
		 //    	);
				
			// 	$sync_tool_admin->process_request($args);

			// 	include_once 'views/'.$operation.'/'.$operation.'-users.php';
			// } break;			

			default: {
				include 'views/sync-tool-dashboard.php';
			} break;
		}

		function request($connection, $func = 'ping', $resource = 'service', $operation = 'service',  $params = array()){
			global $sync_tool_admin;
			$response = $sync_tool_admin->request(
				$connection['server_url'],
				$sync_tool_admin->prepare_authentication($connection),
				array(
	            	'action' => $func,
	            	'params' => $params,
	            	'resource' => $resource,
	            	'operation' => $operation,
		    	)
			);
			return $response;
		}

	?>

</div>