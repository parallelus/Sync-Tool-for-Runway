<?php 
	$request_uri = $this->self_url('merge-extensions').'&action=connection&connection_id='.$connection_id.'&operation=export';
	$count_items = count($local_data->extensions_data['active_exts']);
	$items_per_page = 10;
	$mid_size = 5; // How many numbers to either side of current page, but not including current page.
	$end_size = 5; // How many numbers on either the start and the end list edges
	$page_num = (isset($_GET['paginator'])) ? $_GET['paginator'] : 0;
	$start = ($page_num == 0) ? 0 : ($page_num*$items_per_page)-$items_per_page;
	$end = ($page_num == 0) ? $items_per_page : ($page_num*$items_per_page);

	// out($local_data->extensions_data['active_exts']);
?>

<br><div class="nav-tabs nav-tabs-second">
	<a href="<?php echo $this->self_url('connection'); ?>&connection_id=<?php echo $connection_id; ?>" class="nav-tab"><?php echo __('Differencies Page', 'framework'); ?></a>
	<a href="<?php echo $this->self_url('merge-extensions'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import" class="nav-tab"><?php echo __('Import', 'framework'); ?></a>
	<a href="<?php echo $this->self_url('merge-extensions'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=export" class="nav-tab nav-tab-active"><?php echo __('Export', 'framework'); ?></a>	
</div>

<?php 
if(isset($message) && $message != '') : ?>
	<div id="message" class="updated">
		<p>
			<?php rf_e($message); ?>
		</p>
	</div>
<?php endif; ?>

<div class="meta-box-sortables metabox-holder">
<div class="postbox">
	<div class="handlediv" title="<?php echo __('Click to toggle', 'framework'); ?>"><br></div>
	<h3 class="hndle"><span><?php echo __('Local Extensions', 'framework'); ?></span></h3>
	<div class="inside" >	
		<table class="wp-list-table widefat">
			<thead>
				<tr>		
					<th id="name" class="manage-column column-name"><?php echo __('Name', 'framework'); ?></th>
					<th id="description" class="manage-column column-description"><?php echo __('Description', 'framework'); ?></th>
					<th id="action" class="manage-column column-name"><?php echo __('Action', 'framework'); ?></th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php
					$i = $start;	
					if(count($local_data->extensions_data['active_exts']) == 0){
						?>
						<tr class="active">
							<td class="plugin-title" style="text-align:left;" colspan=3>
								<?php echo __("No items to display.", 'framework'); ?>
							</td>										
						</tr>
						<?php								
					}
					else{
						foreach ($local_data->extensions_data['active_exts'] as $key => $value) {
							if(!empty($value)){
							?>
								<tr class="active">
									<td class="plugin-title" style="text-align:left;">
										<?php echo $value['Name']; ?>
									</td>	
									<td class="column-description desc">
										<?php echo $value['Description']; ?>
									</td>	
									<td class="column-description desc">
										<?php if(in_array('extensions',$permissions_on_server['export'])) : ?>
										<a href="<?php echo $this->self_url('merge-extensions'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&option_key=<?php echo $value['option_key']; ?>&extension=<?php echo $key; ?>&operation=export-options&what=extensions">
											<?php echo __('Export option', 'framework'); ?>
										</a>
										<?php else: ?>
										<?php echo __('Export denied on server', 'framework'); ?>
										<?php endif; ?>
									</td>	
								</tr>
							<?php
							}
							else break;						

							$i++;
							if($i >= $end) break;
						}
					}		
				 ?>				
			</tbody>
		</table>
	</div>
</div>
</div>

<div class='paginator'>
<?php  
	$args = array(
		'base'         => $request_uri.'%_%',
		'format'       => '&paginator=%#%',
		'total'        => ($count_items/$items_per_page)+1,
		'current'      => (isset($_GET['page-n'])) ? $_GET['page-n'] : 0,
		'show_all'     => false,
		'end_size'     => $end_size,
		'mid_size'     => $mid_size,
		'prev_next'    => true,
		'prev_text'    => __('&laquo; Previous', 'framework'),
		'next_text'    => __('Next &raquo;', 'framework'),
		'type'         => 'plain',
	);
	echo paginate_links($args); 
?>
</div>