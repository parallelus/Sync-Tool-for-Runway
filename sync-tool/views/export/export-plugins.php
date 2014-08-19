<!--<br><div class="nav-tabs nav-tabs-second">
	<a href="<?php echo $this->self_url('connection'); ?>&connection_id=<?php echo $connection_id; ?>" class="nav-tab">Differencies Page</a>
	<a href="<?php echo $this->self_url('import-export-plugins'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import" class="nav-tab">Import</a>
	<a href="<?php echo $this->self_url('import-export-plugins'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=export" class="nav-tab nav-tab-active">Export</a>	
</div>

<?php 
if(isset($message) && $message != '') : ?>
	<div id="message" class="updated">
		<p>
			<?php echo $message; ?>
		</p>
	</div>
<?php endif; ?>

<div class="meta-box-sortables metabox-holder">
<div class="postbox">
	<div class="handlediv" title="Click to toggle"><br></div>
	<h3 class="hndle"><span>Local Plugins</span></h3>
	<div class="inside" >
		<a href="<?php echo $this->self_url('import-export-plugins'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=export-all&what=plugins" id="export-all-plugins" class="add-new-h2">Export All</a><br/><br/>		
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th id="name" class="manage-column column-name">Name</th>
					<th id="description" class="manage-column column-description">Description</th>
					<th id="action" class="manage-column column-name">Action</th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php foreach ((array)$local_plugins as $key => $value) : ?>
					<tr class="active">					
						<td class="plugin-title" style="text-align:left;">
							<?php echo $value['Name']; ?>
						</td>	
						<td class="column-description desc">
							<?php echo $value['Description']; ?>
						</td>	
						<td class="column-description desc">
							Import/Export
						</td>	
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
</div>-->