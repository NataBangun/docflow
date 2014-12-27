<div class="page-header">
	<h4>Monitoring Nota</h4>
</div>

<?php if($records):?>

<table id="myTable" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th>No</th>
		<th>Judul</th>	
		<th>Status</th>
		<th>Proses Berjalan</th>
		<th>Penyusun</th>		
		<th>Tgl.Buat</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records as $val):?>
<?php if($val['APPROVAL_STATUS'] != 1):?>
<tr>
	<td><a href="<?php echo site_url('inbox_nota/detail/'.$val['PK_NOTA_ID'])?>" title="detail"><?php echo $val['PK_NOTA_ID']?></a></td>
	<td>
	<span><a href="<?php echo site_url('inbox_nota/detail/'.$val['PK_NOTA_ID'])?>" title="detail"><?php echo $val['HAL']?></a></span><br>
	<span class="font-disabled"><?php echo $val['CATEGORIES_TITLE']?></span>
	</td>
	<?php $ver = $val['VERSION_ID'];?>	
	<td><?php echo $config['nota_status'][ $val['PROCESS_STATUS'] ]?></td>	
	<td><?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $val['CURRENT_LAYER'] .'. ('. $config['step_layer'][$val['CURRENT_LAYER']].')' : DOC_CLOSED;?></td>
	
	
	<td><?php echo $val['EMPLOYEE_NAME']?> <br>
	<span class="font-disabled">(<?php echo $val['E_MAIL_ADDR']?>)</span></td>	
	<td><?php echo $val['CREATE_DATE']?></td>
</tr>
<?php endif;?>
<?php endforeach;?>
</tbody>
</table>

<?php else:?>

	<p>Tidak ada data</p>

<?php endif;?>
<script type="text/javascript">
$('#myTable').dynatable({
  table: {
    defaultColumnIdStyle: 'trimDash'
  }
});
</script>