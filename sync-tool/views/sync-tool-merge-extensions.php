<?php
	if(in_array('extensions', $permissions_on_server['import']) && in_array('extensions', $permissions_on_server['export'])):
	// out($server_data->extensions_data);
	// out($diff_exten1sions);
?>
<script type="text/javascript">
jQuery.noConflict();
(function($) {
  $(function() {

  });
})(jQuery);
</script>
<?php if(!empty($diff_extensions['same_extensions'])): ?>
<div id="data-from-server" class="meta-box-sortables metabox-holder">
<div class="postbox">
	<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
	<h3 class="hndle"><span><?php echo __('Same Extensions', 'runway'); ?></span></h3>
	<div class="inside" id="data-from-server-inside">
	<table class="wp-list-table widefat" id="data-list">
		<thead>
			<tr>
				<th id="name" class="manage-column column-name"><?php echo __('Local Extensions', 'runway'); ?></th>
				<th id="name" class="manage-column column-name" style="text-align:center;"><?php echo __('Import/Export All Data', 'runway'); ?></th>
				<th id="description" class="manage-column column-description"><?php echo __('Server Extensions', 'runway'); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach ((array)$diff_extensions['same_extensions'] as $extension => $extension_info) : ?>
				<tr class="active">
					<td class="plugin-title" style="text-align:left;">
						<?php
							$local_extension = $local_data['active_exts'][$extension];
						?>
						<div id="data-from-server" class="meta-box-sortables metabox-holder">
						<div class="postbox">
							<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
							<h3 class="hndle"><span><?php rf_e($local_extension['Name']); ?></span></h3>
							<div class="inside" id="data-from-server-inside" style="display:none;">
								<?php  out($local_extension['data']); ?>
							</div>
						</div>
					</div>
					</td>
					<td class="plugin-title" style="text-align:center;">
						<br>
						<a href="<?php echo $this->self_url('merge-extensions'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&option_key=<?php echo $local_extension['option_key']; ?>&extension=<?php echo $extension; ?>&operation=import-options&what=<?php echo $what; ?>">
							<?php echo __('Import options', 'runway'); ?>
						</a> |
						<a href="<?php echo $this->self_url('merge-extensions'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&option_key=<?php echo $local_extension['option_key']; ?>&extension=<?php echo $extension; ?>&operation=export-options&what=<?php echo $what; ?>">
							<?php echo __('Export option', 'runway'); ?>
						</a>
					</td>
					<td class="column-description desc">
						<div id="data-from-server" class="meta-box-sortables metabox-holder">
						<div class="postbox">
							<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
							<h3 class="hndle"><span><?php rf_e($extension_info['Name']); ?></span></h3>
							<div class="inside" id="data-from-server-inside" style="display:none;">
								<?php  out($extension_info['data']); ?>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
</div>
<?php endif; ?>
<!-- ************************************************************************* -->
<?php if(!empty($diff_extensions['must_be_active'])): ?>
<div id="data-from-server" class="meta-box-sortables metabox-holder">
<div class="postbox">
	<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
	<h3 class="hndle"><span><?php echo __('Must be active', 'runway'); ?></span></h3>
	<div class="inside" id="data-from-server-inside">
	<table class="wp-list-table widefat" id="data-list">
		<thead>
			<tr>
				<th id="name" class="manage-column column-name"><?php echo __('Name', 'runway'); ?></th>
				<th id="name" class="manage-column column-name"><?php echo __('Description', 'runway'); ?></th>
				<th id="name" class="manage-column column-name"><?php echo __('Active', 'runway'); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach ((array)$diff_extensions['must_be_active'] as $extension => $extension_info) : ?>
				<tr class="active">
					<td class="plugin-title" style="text-align:left;">
						<?php
							$local_extension = $local_data['desible_exts'][$extension];
							rf_e($local_extension['Name']);
						?>
					</td>
					<td class="column-description desc">
						<?php rf_e($local_extension['Description']); ?>
					</td>
					<td class="plugin-title" style="text-align:left;">
						<a href="<?php echo admin_url('themes.php?page=extensions&navigation=extension-activate&ext='.$extension); ?>" title="<?php echo __('Connect', 'runway'); ?>" class="connect"><?php echo __('Activate', 'runway'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
</div>
<?php endif; ?>
<!-- ************************************************************************* -->
<?php if(!empty($diff_extensions['must_be_installed'])): ?>
<div id="data-from-server" class="meta-box-sortables metabox-holder">
<div class="postbox">
	<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
	<h3 class="hndle"><span><?php echo __('Must be installed', 'runway'); ?></span></h3>
	<div class="inside" id="data-from-server-inside">
	<table class="wp-list-table widefat" id="data-list">
		<thead>
			<tr>
				<th id="name" class="manage-column column-name"><?php echo __('Local Extensions', 'runway'); ?></th>
				<th id="description" class="manage-column column-description"><?php echo __('Description', 'runway'); ?></th>
				<th id="name" class="manage-column column-name"><?php echo __('Install', 'runway'); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach ((array)$diff_extensions['must_be_installed'] as $extension => $extension_info) : ?>
				<tr class="active">
					<td class="plugin-title" style="text-align:left;">
						<?php
							rf_e($extension_info->Name);
						?>
					</td>
					<td class="column-description desc">
						<?php rf_e($extension_info->Description); ?>
					</td>
					<td class="plugin-title">
						<span>
							<a href="<?php echo $extension_info->AuthorURI; ?>" title="<?php echo __('Connect', 'runway'); ?>" class="connect"><?php echo __('Install', 'runway'); ?></a>
						</span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
</div>
<?php endif; ?>
<?php
elseif(in_array('extensions', $permissions_on_server['import']) && !in_array('extensions', $permissions_on_server['export'])):
	include 'import/import-extensions.php';
elseif(!in_array('extensions', $permissions_on_server['import']) && in_array('extensions', $permissions_on_server['export'])):
	include ('export/export-extensions.php');
endif;
?>