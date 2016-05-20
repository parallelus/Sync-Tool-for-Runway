<h3>
	<?php echo stripslashes( $connection['connection_name'] ) ?>
	[<?php echo isset($ping_response['state']) && in_array('success', $ping_response['state']) ?
		'<span class="connection-status-success">' . $ping_response['message'] :
		'<span class="connection-status-failed">'.__('Failed', 'runway') ?></span>]
</h3>
<div class='server-response-information'></div>
<?php if(isset($ping_response['state']) && in_array('success', $ping_response['state'])) : ?>
<div id="data-from-server" class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="Click to toggle"><br></div>
		<h3 class="hndle"><span><?php echo __('Contribution Extensions', 'runway'); ?></span></h3>
		<div class="inside" id="data-from-server-inside">
			<table>
				<tr>
					<td><?php echo __('Extensions count on server:', 'runway'); ?></td>
					<td><b><?php echo $data_count_server['extensions']; ?></b></td>
				</tr>
				<tr>
					<td><?php echo __('Extensions count on local:', 'runway'); ?></td>
					<td><b><?php echo $data_count_local['extensions']; ?></b></td>
				</tr>
			</table>
		<a href="<?php echo $this->self_url('merge-extensions'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=extensions" class="button-primary" style="float:right;"><?php echo __('Go to merging', 'runway'); ?></a><br><br>
		<div id="error-from-server"></div>
		</div>
	</div>
</div>

<div id="data-from-server" class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Posts', 'runway'); ?></span></h3>
		<div class="inside" id="data-from-server-inside">
		<table>
			<tr>
				<td><?php echo __('Posts count on server:', 'runway'); ?></td>
				<td><b><?php echo $data_count_server['posts']; ?></b></td>
			</tr>
			<tr>
				<td><?php echo __('Posts count on local:', 'runway'); ?></td>
				<td><b><?php echo $data_count_local['posts']; ?></b></td>
			</tr>
		</table>
		<a href="<?php echo $this->self_url('import-posts'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=posts" class="button-primary" style="float:right;"><?php echo __('Go to merging', 'runway'); ?></a><br><br>
		<div id="error-from-server"></div>
		</div>
	</div>
</div>

<div id="data-from-server" class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Categories', 'runway'); ?></span></h3>
		<div class="inside" id="data-from-server-inside">
		<table>
			<tr>
				<td><?php echo __('Categories count on server:', 'runway'); ?></td>
				<td><b><?php echo $data_count_server['categories']; ?></b></td>
			</tr>
			<tr>
				<td><?php echo __('Categories count on local:', 'runway'); ?></td>
				<td><b><?php echo $data_count_local['categories']; ?></b></td>
			</tr>
		</table>
		<a href="<?php echo $this->self_url('import-categories'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=categories" class="button-primary" style="float:right;"><?php echo __('Go to merging', 'runway'); ?></a><br><br>
		<div id="error-from-server"></div>
		</div>
	</div>
</div>

<div id="data-from-server" class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Tags', 'runway'); ?></span></h3>
		<div class="inside" id="data-from-server-inside">
		<table>
			<tr>
				<td><?php echo __('Tags count on server:', 'runway'); ?></td>
				<td><b><?php echo $data_count_server['tags']; ?></b></td>
			</tr>
			<tr>
				<td><?php echo __('Tags count on local:', 'runway'); ?></td>
				<td><b><?php echo $data_count_local['tags']; ?></b></td>
			</tr>
		</table>
		<a href="<?php echo $this->self_url('import-tags'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=tags" class="button-primary" style="float:right;"><?php echo __('Go to merging', 'runway'); ?></a><br><br>
		<div id="error-from-server"></div>
		</div>
	</div>
</div>

<div id="data-from-server" class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Plugins', 'runway'); ?></span></h3>
		<div class="inside" id="data-from-server-inside">
		<table>
			<tr>
				<td><?php echo __('Plugins count on server:', 'runway'); ?></td>
				<td><b><?php echo $data_count_server['plugins']; ?></b></td>
			</tr>
			<tr>
				<td><?php echo __('Plugins count on local:', 'runway'); ?></td>
				<td><b><?php echo $data_count_local['plugins']; ?></b></td>
			</tr>
		</table>
		<a href="<?php echo $this->self_url('import-plugins'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=plugins" class="button-primary" style="float:right;"><?php echo __('Go to merging', 'runway'); ?></a><br><br>
		<div id="error-from-server"></div>
		</div>
	</div>
</div>

<div id="data-from-server" class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Users', 'runway'); ?></span></h3>
		<div class="inside" id="data-from-server-inside">
		<table>
			<tr>
				<td><?php echo __('Users count on server:', 'runway'); ?></td>
				<td><b><?php echo $data_count_server['users']; ?></b></td>
			</tr>
			<tr>
				<td><?php echo __('Users count on local:', 'runway'); ?></td>
				<td><b><?php echo $data_count_local['users']; ?></b></td>
			</tr>
		</table>
		<a href="<?php echo $this->self_url('import-users'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=users" class="button-primary" style="float:right;"><?php echo __('Go to merging', 'runway'); ?></a><br><br>
		<div id="error-from-server"></div>
		</div>
	</div>
</div>
<?php endif; ?>