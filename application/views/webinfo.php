<html>
<head>
    <!-- ExtJS -->
	
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/extjs/resources/css/ext-all.css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/extjs/bootstrap.js"></script>
	
	<script type="text/javascript">
	
		Ext.define('KitchenSink.view.tree.Webinfo', {
			extend: 'Ext.tree.Panel',
			
			requires: [
				'Ext.tree.*',
				'Ext.data.*'
			],
			xtype: 'tree-webinfo',			
			useArrows: true,
			
			initComponent: function() {
			
				Ext.apply(this, {
					store: new Ext.data.TreeStore({
						autoLoad: true,
						root: {
							text: 'WEBINFO'
						},
						proxy: {
							type: 'ajax',
							url: '../get_treenode'
						}
					}),
					viewConfig: {
					}
				});
				this.callParent();
			}
		});
			
		function showConfig() 
		{
			if (Ext.getCmp('winWebinfo')) return;

			function do_upload(overwrite)
			{
				var process_id = <?php echo $process_id; ?>;
				var uID = '<?php echo $uID; ?>'; // used by document procedure
				if (typeof(Ext.getCmp('treeWebinfo').getSelectionModel().getSelection()[0]) != "undefined") {
					var node_id = Ext.getCmp('treeWebinfo').getSelectionModel().getSelection()[0].raw.id;
					var link_upload = '../link_upload?node=' + node_id + '&process_id=' + process_id + '&uID=' + uID;
					if (overwrite) {
						link_upload += '&overwrite=1';
					}
					// window.open(link_upload);
					myMask.show();
					Ext.Ajax.request({
						url: link_upload,
						// params: {
							// id: 1
						// },
						success: function(response){
							myMask.hide();
							var text = response.responseText;
							if (text == 'File already exists.') {
								if (confirm('You will overwrite existing file. Are you sure?')) {
									do_upload(true);
								}
							} else {
								alert(text);
							}
							// process server response here
						}
					});
				} else {
					alert('Please select folder to Upload!');
				}
			}
		
			Ext.create('Ext.window.Window', {
				title: 'Choose Folder to upload...',
				id: 'winWebinfo',
				height: 400,
				width: 600,
				maximized: true,
				layout: 'fit',
				closable: false,
				items: {  
					xtype: 'tree-webinfo',
					id: 'treeWebinfo'
				},
				buttons: [{
					text: 'Browse',
					id: 'btnBrowse',
					handler: function() {
						var process_id = <?php echo $process_id; ?>;
						if (typeof(Ext.getCmp('treeWebinfo').getSelectionModel().getSelection()[0]) != "undefined") {
							var node_id = Ext.getCmp('treeWebinfo').getSelectionModel().getSelection()[0].raw.id;
							var link_upload = '../link_upload?browse=1&node=' + node_id + '&process_id=' + process_id;
							window.open(link_upload);
						} else {
							alert('Please select folder to Browse!');
						}
					}
				},{
					text: 'Upload',
					handler: function() {
						do_upload(false);
					}
				},{
					text: 'Close',
					handler: function() {
						//alert('Close');
						window.opener.window.location.reload();
						window.close();
						
					}
				}]
			}).show();		
			
			Ext.getCmp('btnBrowse').setVisible(true);
			
			var myMask = new Ext.LoadMask(Ext.getCmp('winWebinfo'), {msg:"Uploading, please wait..."});
			
			var winLogin = Ext.create('Ext.window.Window', {
				title: 'Login Webinfo',
				id: 'winLogin',
				height: 150,
				width: 300,
				//maximized: false,
				layout: 'fit',
				closable: false,
				modal: true,
				items: {
					xtype: 'form',
					id: 'frmLogin',
					bodyPadding: 8,
					items: [{  
						xtype: 'textfield',
						fieldLabel: 'Username',
						name: 'username',
						value: '<?php echo $user_name; ?>',
						allowBlank: false
					},{
						xtype: 'textfield',
						fieldLabel: 'Password',
						name: 'password',
						inputType: 'password',
						allowBlank: false
						
					}]
				},
				buttons: [{
					text: 'Login',
					id: 'btnLogin',
					handler: function() {
						Ext.getCmp('frmLogin').getForm().submit({
							url: '../login',
							method: 'POST',
							params: {
								username: Ext.getCmp('frmLogin').getForm().findField('username').getValue(),
								password: Ext.getCmp('frmLogin').getForm().findField('password').getValue()
							},
							success: function(form, action){
								if (action.result.success == true) {
									// Ext.Msg.alert('Success', action.result.msg);
									winLogin.hide();
									Ext.getCmp('treeWebinfo').getStore().reload();
								}
							},
							failure: function(form, action){
								switch (action.failureType) {
									case Ext.form.action.Action.CLIENT_INVALID:
										alert('Form fields may not be submitted with invalid values');
										break;
									case Ext.form.action.Action.CONNECT_FAILURE:
										alert('Ajax communication failed');
										break;
									case Ext.form.action.Action.SERVER_INVALID:
										alert(action.result.msg);
								}
							}
						});
					}
				},{
					text: 'Cancel',
					handler: function() {
						//alert('Close');
						window.close();
					}
				}]
			});
			
			if ('<?php echo $showLogin; ?>' == '1') {
				winLogin.show();
				Ext.getCmp('frmLogin').getForm().findField('password').focus();
			}
		}
	</script>
</head>
<body onload="showConfig()">
</body>
</html>