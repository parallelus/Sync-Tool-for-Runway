<br><div class="nav-tabs nav-tabs-second">
	<a href="<?php echo $this->self_url('connection'); ?>&connection_id=<?php echo $connection_id; ?>" class="nav-tab"><?php echo __('Differencies Page', 'runway'); ?></a>
	<a href="<?php echo $this->self_url('import-plugins'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=plugins" class="nav-tab nav-tab-active"><?php echo __('Import', 'runway'); ?></a>
</div>

<?php
if(in_array('plugins',$permissions_on_server['import'])):
if(isset($message) && $message != '') : ?>
	<div id="message" class="updated">
		<p>
			<?php rf_e($message); ?>
		</p>
	</div>
<?php endif; ?>

<div class="meta-box-sortables metabox-holder">
<div class="postbox">
	<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
	<h3 class="hndle"><span><?php echo __('Server Plugins', 'runway'); ?></span></h3>
	<div class="inside" >
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th id="name" class="manage-column column-name"><?php echo __('Name', 'runway'); ?></th>
					<th id="description" class="manage-column column-description"><?php echo __('Description', 'runway'); ?></th>
					<th id="action" class="manage-column column-name" width="70"><?php echo __('Action', 'runway'); ?></th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php foreach ((array)$server_plugins as $key => $value) : ?>
					<tr class="active">
						<td class="plugin-title" style="text-align:left;">
							<?php rf_e($value['Name']); ?>
						</td>
						<td class="column-description desc">
							<?php rf_e($value['Description']); ?>
						</td>
						<td class="column-description desc">
							<?php
								if(isset($local_plugins[$key])){
									echo __("Installed", 'runway');
								}
								else{
									$plugin = explode('/', $key);
									$plugin = str_replace('.php', '', $plugin[0]);
									?>
									<a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin='.$plugin.'&TB_iframe=true&width=640&height=359'); ?>" class="thickbox" title="More information about Akismet 2.5.7"><?php echo __('Details', 'runway'); ?></a><br>
									<?php
									if ( current_user_can('install_plugins') ){
										$install_now_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin), 'install-plugin_' . $plugin);
										?>
										<a href="<?php echo "$install_now_url"; ?>" target="_blank"><?php echo __('Install now', 'runway'); ?></a><br>
										<a href="<?php echo "http://wordpress.org/extend/plugins/$plugin"; ?>" target="_blank"><?php echo __('Download', 'runway'); ?></a>
										<?php
									}
								}
							 ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
</div>
<?php else: ?>
	<?php echo __('Access denied', 'runway'); ?>
<?php endif; ?>