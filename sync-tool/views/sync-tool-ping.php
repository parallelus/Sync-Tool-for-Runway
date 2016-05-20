<h3>
	<?php foreach( $ping_response_list as $ping_response_alone ) {
		 echo stripslashes( $ping_response_alone['connection'] ) ?>
		[<?php echo isset($ping_response_alone['state']) && in_array('success', $ping_response_alone['state']) ?
			'<span class="connection-status-success">' . rf__($ping_response_alone['message']) :
			'<span class="connection-status-failed">'.__('Failed', 'runway') ?></span>]<br>
	<?php } ?>
</h3>