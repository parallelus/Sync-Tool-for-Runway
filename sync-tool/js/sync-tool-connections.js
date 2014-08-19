jQuery.noConflict();
(function($) {
  $(function() {
  		$('.check-connection').change(function(e){
			$('div.connection-form').hide();
			$('div#'+$(this).val()).show();
		});

		function clearDialog(){
			$('#server').val('');
			$('#connection-name').val('');
			$('#access-key').val('');
			$('#user-login').val('');
			$('#user-password').val('');
			$('#add-server').data('flag', 'add');
		}

		function getConnectionData(){
			var data = {
				action: 'update_new_connection',
				server_url: $('#server').val(),
				slug: $('#connection-name').val()
			};

			switch($('.check-connection:checked').val()){
				case 'connect-with-key': {					
					data.access_key = $('#access-key').val();
					data.type = 'key_based';
					data.status = 'AVAIBLE';
				} break;
				
				case 'connect-with-login': {
					data.type = 'account_based';
					data.login = $('#user-login').val();
					data.password = $('#user-password').val();
					data.status = 'AVAIBLE';
				} break;
			}
			return data;
		}

		$('#add-new-connection').click(function(e){
			e.preventDefault();
			clearDialog();
			$('#new-connection-dialog').dialog({
                open: function(event, ui) {
                    $('#adminmenuwrap').css({'z-index':0});
                },
                close: function(event, ui) {
                    $('#adminmenuwrap').css({'z-index':'auto'});
                },				
				title: translations_js.add_new_connection,
				modal: true,
				width: 400
			});
		});

		$('#add-server-key, #add-server-login').click(function(){		

			data = getConnectionData();
			if($('#add-server').data('flag') == 'edit'){
				data.old_slug = $('#add-server').data('slug');
			}

			$.post(ajaxurl, data, function(responce){				
				data.title = data.slug;
				data.slug = responce;
				$('#new-connection-dialog').dialog('close');
				location.reload();
			});
		});

		$('#connection-edit').live('click', function(){
			var connectionData = $(this).parent().parent().parent().data();
			clearDialog();
			$('#add-server').data('flag', 'edit');	
			$('#add-server').data('slug', $(this).parent().parent().parent().attr('id'));
			$('#server').val(connectionData.url);
			$('#connection-name').val(connectionData.cn);
			switch(connectionData.type){
				case 'key_based':{
					$('#r-connect-with-key').attr('checked', 'true');
					$('div.connection-form').hide();
					$('div#connect-with-key').show();
					$('#access-key').val(connectionData.ak);
				} break;

				case 'account_based':{
					$('#r-connect-with-login').attr('checked', 'true');
					$('div.connection-form').hide();
					$('div#connect-with-login').show();
					$('#user-login').val(connectionData.login);
					$('#user-password').val(connectionData.psw);
				} break;
			}
			$('#new-connection-dialog').dialog({
                open: function(event, ui) {
                    $('#adminmenuwrap').css({'z-index':0});
                },
                close: function(event, ui) {
                    $('#adminmenuwrap').css({'z-index':'99'});
                },				
				title: translations_js.edit_connection,
				modal: true,
				width: 400
			});			
		});
  });
})(jQuery);