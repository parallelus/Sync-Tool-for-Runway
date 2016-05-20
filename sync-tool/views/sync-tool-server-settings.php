<div class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Access key', 'runway'); ?></span></h3>
		<div class="inside">
			<span><?php echo __('Access key', 'runway'); ?>: <?php echo $sync_tool_admin->get_key(); ?></span>
			<form method="post">
				<input type="hidden" name="action" value="create-new-key" />
				<input type="submit" class="button" value="<?php echo __('Create new key', 'runway'); ?>">
			</form>
		</div>
	</div>
</div>
<?php
	$is_import = isset( $sync_tool_admin->server_settings['permissions']['import'] );
	$is_export = isset( $sync_tool_admin->server_settings['permissions']['export'] );
?>
<div class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Permissions on Export', 'runway'); ?></span></h3>
			<form method="post">
				<input type="checkbox" name="import-permissions-list[]" <?php if( $is_import && in_array('extensions', $sync_tool_admin->server_settings['permissions']['import'])) { echo 'checked="true"'; } ?> value="extensions" id="import-extensions"><label for="import-extensions"><?php echo __('Export extensions', 'runway'); ?></label><br>
				<input type="checkbox" name="import-permissions-list[]" <?php if( $is_import && in_array('posts', $sync_tool_admin->server_settings['permissions']['import'])) { echo 'checked="true"'; } ?> value="posts" id="import-posts"><label for="import-posts"><?php echo __('Export posts', 'runway'); ?></label><br>
				<input type="checkbox" name="import-permissions-list[]" <?php if( $is_import && in_array('categories', $sync_tool_admin->server_settings['permissions']['import'])) { echo 'checked="true"'; } ?> value="categories" id="import-categories"><label for="import-categories"><?php echo __('Export categories', 'runway'); ?></label><br>
				<input type="checkbox" name="import-permissions-list[]" <?php if( $is_import && in_array('tags', $sync_tool_admin->server_settings['permissions']['import'])) { echo 'checked="true"'; } ?> value="tags" id="import-tags"><label for="import-tags"><?php echo __('Export tags', 'runway'); ?></label><br>
				<input type="checkbox" name="import-permissions-list[]" <?php if( $is_import && in_array('users', $sync_tool_admin->server_settings['permissions']['import'])) { echo 'checked="true"'; } ?> value="users" id="import-users"><label for="import-users"><?php echo __('Export users', 'runway'); ?></label><br>
				<input type="checkbox" name="import-permissions-list[]" <?php if( $is_import && in_array('plugins', $sync_tool_admin->server_settings['permissions']['import'])) { echo 'checked="true"'; } ?> value="plugins" id="import-plugins"><label for="import-plugins"><?php echo __('Export plugins', 'runway'); ?></label><br><br>
				<input type="hidden" name="action" value="set-permissions-on-import" />
				<input type="submit" class="button" value="<?php echo __('Set Permissions', 'runway'); ?>">
			</form>
		</div>
	</div>
</div>

<div class="meta-box-sortables metabox-holder">
	<div class="postbox">
		<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
		<h3 class="hndle"><span><?php echo __('Permissions on Import', 'runway'); ?></span></h3>
			<form method="post">
				<input type="checkbox" name="export-permissions-list[]" <?php if( $is_export && in_array('extensions', $sync_tool_admin->server_settings['permissions']['export'])) { echo 'checked="true"'; } ?> value="extensions" id="export-extensions"><label for="export-extensions"><?php echo __('Import extensions', 'runway'); ?></label><br>
				<input type="checkbox" name="export-permissions-list[]" <?php if( $is_export && in_array('posts', $sync_tool_admin->server_settings['permissions']['export'])) { echo 'checked="true"'; } ?> value="posts" id="export-posts"><label for="export-posts"><?php echo __('Import posts', 'runway'); ?></label><br>
				<input type="checkbox" name="export-permissions-list[]" <?php if( $is_export && in_array('categories', $sync_tool_admin->server_settings['permissions']['export'])) { echo 'checked="true"'; } ?> value="categories" id="export-categories"><label for="export-categories"><?php echo __('Import categories', 'runway'); ?></label><br>
				<input type="checkbox" name="export-permissions-list[]" <?php if( $is_export && in_array('tags', $sync_tool_admin->server_settings['permissions']['export'])) { echo 'checked="true"'; } ?> value="tags" id="export-tags"><label for="export-tags"><?php echo __('Import tags', 'runway'); ?></label><br>
				<input type="checkbox" name="export-permissions-list[]" <?php if( $is_export && in_array('users', $sync_tool_admin->server_settings['permissions']['export'])) { echo 'checked="true"'; } ?> value="users" id="export-users"><label for="export-users"><?php echo __('Import users', 'runway'); ?></label><br><br>
				<input type="hidden" name="action" value="set-permissions-on-export" />
				<input type="submit" class="button" value="<?php echo __('Set Permissions', 'runway'); ?>">
			</form>
		</div>
	</div>
</div>