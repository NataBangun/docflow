<html>
<head>
    <!-- ExtJS -->
    <link rel="stylesheet" type="text/css" href="/extjs/resources/css/ext-all.css" />
    <script type="text/javascript" src="/extjs/bootstrap.js"></script>
	
	<script type="text/javascript">
	
		Ext.define('KitchenSink.view.tree.Reorder', {
			extend: 'Ext.tree.Panel',
			
			requires: [
				'Ext.tree.*',
				'Ext.data.*'
			],
			xtype: 'tree-reorder',			
			useArrows: true,
			
			initComponent: function() {
				var data = Ext.JSON.decode(Ext.getDom('dataConfig').value);
				var array_children = new Array();
				
				for (var i=0; i<data.length; i++) {
					array_children.push({ text: data[i], leaf: true });
				}				
			
				Ext.apply(this, {
					store: new Ext.data.TreeStore({
						root: {
							text: 'Drilldown',
							expanded: true,
							children: array_children
							// children: [{ 
								// text: 'PERIODE', leaf: true 
							// },{
								// text: 'AKTIVITY', leaf: true 
							// },{
								// text: 'DIVISI', leaf: true 
							// },{
								// text: 'BAGIAN', leaf: true 
							// },{
								// text: 'SALES', leaf: true 
							// }]
						}
					}),
					viewConfig: {
						plugins: {
							ptype: 'treeviewdragdrop',
							containerScroll: true
						}
					}
				});
				this.callParent();
			}
		});
			
		function showConfig() 
		{
			if (Ext.getCmp('winConfig')) return;
					
			Ext.create('Ext.window.Window', {
				title: 'Drilldown Configuration',
				id: 'winConfig',
				height: 275,
				width: 400,
				layout: 'fit',
				items: {  
					xtype: 'tree-reorder',
					id: 'treeDrilldown'
				},
				buttons: [{
					text: 'Simpan',
					handler: function() {
						var rows = Ext.getCmp('treeDrilldown').getRootNode().childNodes;
						var data = new Array();
						for (var i=0; i< rows.length; i++) {
							data.push(rows[i].raw.text);
						}
						Ext.getDom('dataConfig').value = Ext.JSON.encode(data);
						Ext.getCmp('winConfig').close();
					}
				},{
					text: 'Cancel',
					handler: function() {
						Ext.getCmp('winConfig').close();
					}
				}]
			}).show();		
		}
	</script>
</head>
<body>
<input type="hidden" id="dataConfig" value="['PERIODE','AKTIVITY','DIVISI','BAGIAN','SALES']">
<input type="button" value="Config" onclick="showConfig()"
</body>
</html>