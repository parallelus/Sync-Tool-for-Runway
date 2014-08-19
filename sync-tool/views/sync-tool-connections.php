<br>
	<a href="options-general.php?page=sync-tool&navigation=connections" id="add-new-connection" class="add-new-h2"><?php echo __('Add New Item', 'framework'); ?></a>
	<a href="options-general.php?page=sync-tool&navigation=ping&action=ping-all" id="ping-all" class="add-new-h2"><?php echo __('Ping All', 'framework'); ?></a>
<br><br>
<!-- Add connection dialog -->
<div id="new-connection-dialog" style="display:none;">		
	<table> 
		<tr>
			<td><?php echo __('Connect with:', 'framework'); ?></td>
			<td>
				<label><input id="r-connect-with-key" type="radio" class="check-connection" name="check-connection" checked="true" value="connect-with-key"><?php echo __('Access Key', 'framework'); ?></label>
				<label><input id="r-connect-with-login" type="radio" class="check-connection" name="check-connection" value="connect-with-login"><?php echo __('Login and password', 'framework'); ?></label>
			</td>
		</tr>
	</table>	
	<table>
		<tr>
			<td><?php echo __('URL:', 'framework'); ?></td>
			<td><input type="text" id="server" value=""><br></td>
		</tr>
		<tr>
			<td><?php echo __('Name:', 'framework'); ?></td>
			<td><input type="text" id="connection-name" value=""><br></td>
		</tr>
	</table><hr>
	<div id="connect-with-key" class="connection-form">
		<table>					
			<tr>
				<td><?php echo __('Access key:', 'framework'); ?></td>
				<td><input type="text" id="access-key" value=""><br></td>
			</tr>
			<tr>
				<td class="connect-to-serv"><a href="#" id="add-server-key" data-flag="add" class="button-primary"><?php echo __('Save settings', 'framework'); ?></a></td>
			</tr>
		</table>							
	</div>			
	<div id="connect-with-login" style="display:none;" class="connection-form">
		<table>
			<tr>
				<td><?php echo __('Login:', 'framework'); ?></td>
				<td><input type="text" id="user-login"><br></td>
			</tr>
			<tr>
				<td><?php echo __('Password:', 'framework'); ?></td>
				<td><input type="password" id="user-password"><br></td>
			</tr>
			<tr>
				<td class="connect-to-serv"><a href="#" id="add-server-login" data-flag="add" class="button-primary"><?php echo __('Save settings', 'framework'); ?></a></td>
			</tr>
		</table>		
	</div>
</div>
<div id="responce"></div>

<!-- Connections list -->
<table class="wp-list-table widefat" id="connections">
<thead>
	<tr>
		<th scope="col" style="width:0px;" id="cb" class="manage-column column-cb check-column" style="width: 0px;"><input type="checkbox" name="ext_chk[]" /></th>
		<th id="name" class="manage-column column-name"><?php echo __('URL', 'frmework'); ?></th>
		<th id="description" class="manage-column column-description"><?php echo __('Description', 'framework'); ?></th>
		<th id="description" class="manage-column column-description"><?php echo __('Action', 'framework'); ?></th>
		<th id="description" class="manage-column column-description"><?php echo __('Status', 'framework'); ?></th>
	</tr>
</thead>
<tbody id="connections-list">
	<?php if ( isset ( $this->server_settings['connections'] ) ): ?>
		<?php foreach ((array)$this->server_settings['connections'] as $key => $value) :?>
			<tr class="active" id="<?php echo $key; ?>" 
				data-cn="<?php echo stripslashes( $value['connection_name'] ); ?>"
				data-url="<?php echo $value['server_url']; ?>"
				data-type="<?php echo $value['type']; ?>"
				data-ak="<?php if(isset($value['access_key'])) echo $value['access_key']; ?>"
				data-login="<?php if(isset($value['login'])) echo $value['login']; ?>"
				data-psw="<?php if(isset($value['password'])) echo $value['password']; ?>">
				<td class="plugin-title">
					<input type="checkbox">
				</td>
				<td class="plugin-title" style="text-align:left;">
					<?php echo stripslashes( $value['connection_name'] ) . ' ('.$value['server_url'].')'; ?>
				</td>	
				<td class="column-description desc">
					<?php echo $value['type']; ?>
				</td>	
				<td class="column-description actions">
					<span class="connect"><a href="options-general.php?page=sync-tool&navigation=connection&connection_id=<?php echo $key; ?>" title="<?php echo __('Connect', 'framework'); ?>" class="connect"><?php echo __('Connect', 'framework'); ?></a> | </span>
					<span class="edit"><a href="#" title="<?php echo __('Edit', 'framework'); ?>" id="connection-edit" class="edit"><?php echo __('Edit', 'framework'); ?></a> | </span>
					<span class="ping"><a href="options-general.php?page=sync-tool&navigation=ping&action=ping&connection_id=<?php echo $key; ?>" title="<?php echo __('Ping', 'framework'); ?>" class="ping"><?php echo __('Ping', 'framework'); ?></a> | </span>
					<span class="delete"><a href="options-general.php?page=sync-tool&navigation=connection&action=delete-connection&connection_id=<?php echo $key; ?>" class="delete"><?php echo __('Delete', 'framework') ;?></a></span>
				</td>	
				<td class="column-description status">
					<?php
						$ping_response = request($value);
						
						if($ping_response['state'][0] == 'success'){
							$state = __("AVAILABLE", 'framework');
						}
						else {
							$state = __("DISABLE", 'framework');
						}
					?>
					<p style="color:<?php echo ($state == __('AVAILABLE', 'framework')) ? 'Green' : 'Red'	; ?>"><?php echo $state; ?></p>
				</td>	
			</tr>
		<?php endforeach; ?>
	<?php endif ?>
</tbody>
</table>		