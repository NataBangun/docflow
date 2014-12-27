<div class="page-header">
	<h4>Inbox Dokumen</h4>
</div>

<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))):?>

	<?php if($revisi):?>

<table id="myTable" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th>ID</th>
		<th>Nomor Nota Dinas</th>
		<th>Hal</th>
		<th>Dari</th>
		<th>Kepada</th>
		<th>Status</th>
		<th>Tgl.Publikasi</th>
		<th>Tgl.Buat</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
	<?php foreach($revisi as $val):?>
	<?php if( $val['PROCESS_STATUS'] == NOTA_EDIT):?>
<tr>
	<td><a href="<?php echo site_url('nota/edit_revisi/'.$val['PK_NOTA_ID'])?>"><?php echo $val['PK_NOTA_ID']?></a></td>
	<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['NO_SURAT']?></a></td>
	<td><?php echo $val['HAL']?></td>
	<td><?php echo $val['DARI']?></td>
	<td><?php echo $val['KEPADA']?></td>
	<td><?php echo $config['nota_status'][ $val['PROCESS_STATUS'] ]?></td>
	<td><?php echo $val['TANGGAL_NOTA']?></td>
	<td><?php echo $val['CREATE_DATE']?></td>
</tr>
	<?php endif;?>
	    <?php endforeach;?>
</tbody>
</table>

	    <?php else:?>

	<p>Tidak ada data</p>

	        <?php endif;?>

<?php else:?>

<?php if($records):?>

<table id="myTable" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th>No</th>
		<th>Judul</th>	
		<th>Status</th>
		<th>Proses Berjalan</th>
		<th>Respon</th>
		<th>Penyusun</th>		
		<th>Tgl.Buat</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records as $val):?>
<?php if($val['PROCESS_STATUS'] == 3):?>
<tr>
	<td><a href="<?php echo site_url('inbox_nota/detail/'.$val['PK_NOTA_ID'])?>" title="detail"><?php echo $val['PK_NOTA_ID']?></a></td>
	<td>
	<span><a href="<?php echo site_url('inbox_nota/detail/'.$val['PK_NOTA_ID'])?>" title="detail"><?php echo $val['HAL']?></a></span><br>
	<span class="font-disabled"><?php echo $val['CATEGORIES_TITLE']?></span>
	</td>
	<?php $ver = $val['VERSION_ID'];?>	
	<td><?php echo $config['nota_status'][ $val['PROCESS_STATUS'] ]?></td>	
	<td><?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $val['CURRENT_LAYER'] .'. ('. $config['step_layer'][$val['CURRENT_LAYER']].')' : DOC_CLOSED;?></td>
	<td>
	<?php if($val['CURRENT_LAYER']==ACTION_FINAL):?>
	<?php echo $config['act_status_icon'][ 2 ][1].' '.$config['act_status_icon'][ 2 ][0]?>
	<?php else:?>
	<?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $config['act_status_icon'][ 0 ][1].' '.$config['act_status_icon'][ 0 ][0] : $config['act_status_icon'][ $val['APPROVAL_STATUS'] ][1].' '.$config['act_status_icon'][ $val['APPROVAL_STATUS'] ][0];?>		
	<?php endif;?>
	</td>
	
	
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
<?php endif;?>
<script type="text/javascript">
$('#myTable').dynatable({
  table: {
    defaultColumnIdStyle: 'trimDash'
  }
});
</script>