<br><div class="nav-tabs nav-tabs-second">
	<a href="<?php echo $this->self_url('connection'); ?>&connection_id=<?php echo $connection_id; ?>" class="nav-tab"><?php echo __('Differencies Page', 'runway'); ?></a>
	<a href="<?php echo $this->self_url('import-posts'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&what=posts" class="nav-tab nav-tab-active"><?php echo __('Import', 'runway'); ?></a>
	<a href="<?php echo $this->self_url('export-posts'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=export&what=posts" class="nav-tab"><?php echo __('Export', 'runway'); ?></a>
</div>
<?php
	if(in_array('tags',$permissions_on_server['import'])):

	$request_uri = $this->self_url('import-posts').'&action=connection&connection_id='.$connection_id.'&operation=import';
	$count_items = count($server_posts);
	$items_per_page = 10;
	$mid_size = 5; // How many numbers to either side of current page, but not including current page.
	$end_size = 5; // How many numbers on either the start and the end list edges
	$page_num = (isset($_GET['paginator'])) ? $_GET['paginator'] : 0;
	$start = ($page_num == 0) ? 0 : ($page_num*$items_per_page)-$items_per_page;
	$end = ($page_num == 0) ? $items_per_page : ($page_num*$items_per_page);
?>


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
	<div class="handlediv" title="<?php echo __('Click to toggle', 'runway'); ?>"><br></div>
	<h3 class="hndle"><span><?php echo __('Server Posts', 'runway'); ?></span></h3>
	<div class="inside" >
		<a href="<?php echo $this->self_url('import-posts'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import-all&what=posts" id="import-all-posts" class="add-new-h2"><?php echo __('Import All', 'runway'); ?></a><br/><br/>
		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th id="name" class="manage-column column-name"><?php echo __('Name', 'runway'); ?></th>
					<th id="description" class="manage-column column-description"><?php echo __('Description', 'runway'); ?></th>
					<th id="action" class="manage-column column-name"><?php echo __('Action', 'runway'); ?></th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php
					$server_posts = array_slice($server_posts, $start);
					$i = $start;
					if(count($server_posts) == 0){
						?>
						<tr class="active">
							<td class="plugin-title" style="text-align:left;" colspan=3>
								<?php echo __("No items to display.", 'runway'); ?>
							</td>
						</tr>
						<?php
					}
					else{
						foreach ($server_posts as $key => $value) {
							if(!empty($value)){
							?>
								<tr class="active">
									<td class="plugin-title" style="text-align:left;">
										<?php rf_e($value['post_title']); ?>
									</td>
									<td class="column-description desc">
										<?php rf_e($value['post_content']); ?>
									</td>
									<td class="column-description desc">
										<a href="<?php echo $this->self_url('import-posts'); ?>&action=connection&connection_id=<?php echo $connection_id; ?>&operation=import&item-id=<?php echo $i; ?>&what=posts" class=""><?php echo __('Import', 'runway'); ?></a>
									</td>
								</tr>
							<?php
							}
							else break;

							$i++;
							if($i == $end) break;
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
		'prev_text'    => __('&laquo; Previous', 'runway'),
		'next_text'    => __('Next &raquo;', 'runway'),
		'type'         => 'plain',
	);
	echo paginate_links($args);
?>
</div>
<?php else: ?>
	<?php echo __('Access denied', 'runway'); ?>
<?php endif; ?>