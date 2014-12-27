<div class="page-header"><h4>Daftar Dokumen Prosedur</h4></div>

<p>
	<a href=":;" id="linkBtn" class="btn btn-info" data-rel="<?php echo site_url('documents/add')?>" title="Buat Dokumen Baru">
		<i class="fam-add"></i> Buat Dokumen Prosedur</a>
	<div class="clearfix"></div>
</p>

<ul class="nav nav-tabs" id="myTab">
	<li class="active"><a data-toggle="tab" href="#s1">Dalam Proses</a></li>
	<li class=""><a data-toggle="tab" href="#s2">Selesai</a></li>
</ul>
<div class="tab-content" id="myTabContent">
	<div id="s1" class="tab-pane fade active in">
	<!--
	<?php if($records):?>

<table id="myTable" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th>ID</th>
		<th>Nomor</th>
		<th>Judul</th>
		<th>Versi</th>
		<th>Status Dokumen</th>
		<th>Proses</th>
		<th>Tgl.Buat</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records as $val):?>
<?php if($val['PROCESS_STATUS'] != DOC_FINAL && $val['PROCESS_STATUS'] != DOC_EDIT):?>
<tr>
	<td><a href="<?php echo site_url('documents/edit/'.$val['PK_DOCUMENTS_ID'])?>"><?php echo $val['PK_DOCUMENTS_ID']?></a></td>
	<td><a href="<?php echo site_url('documents/detail/'.$val['PK_DOCUMENTS_ID'])?>"><?php echo $val['DOCUMENTS_NO']?></a></td>
	<td><?php echo $val['DOCUMENTS_TITLE']?></td>
	<?php $ver = $val['VERSION_ID'];?>
	<?php if($ver != 0):?>
	<td><?php echo $ver[0].'.'.$ver[1]?><?php echo ($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2] ;?></td>
	<?php else:?>
	<td><?php echo 0 ;?></td>
	<?php endif;?>	
	<td><?php echo $config['doc_user'][ $val['PROCESS_STATUS'] ]?></td>
	<td><?php echo ($val['PROCESS_STATUS'] > 1)? $config['doc_status'][ $val['CURRENT_LAYER'] ] : 'Proses Belum dimulai';?></td>
	<td><?php echo $val['DOCUMENTS_CDT']?></td>
</tr>
<?php endif;?>
<?php endforeach;?>
</tbody>
</table>

<?php else:?>

	<p>Tidak ada data</p>

<?php endif;?>
	-->

		<!--start pagination-->
		<?php echo $daftar_doc1;?>
		<!--end pagination-->
		
	</div>
	<div id="s2" class="tab-pane fade">
	<!--
	<?php if($records):?>

<table id="myTable2" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th>ID</th>
		<th>Nomor</th>
		<th>Judul</th>
		<th>Versi</th>
		<th>Status</th>		
		<th>Tgl.Buat</th>
		<th>Tgl.Selesai</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records as $val):?>
<?php if($val['PROCESS_STATUS'] == DOC_FINAL):?>
<tr>
	<td><a href="<?php echo site_url('documents/edit/'.$val['PK_DOCUMENTS_ID'])?>"><?php echo $val['PK_DOCUMENTS_ID']?></a></td>
	<td><a href="<?php echo site_url('documents/detail/'.$val['PK_DOCUMENTS_ID'])?>"><?php echo $val['DOCUMENTS_NO']?></a></td>
	<td><?php echo $val['DOCUMENTS_TITLE']?></td>
	<?php $ver = $val['VERSION_ID'];?>
	<td><?php echo $ver[0].'.'.$ver[1]?><?php echo ($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2] ;?></td>
	<td><?php echo 'Selesai'?></td>
	<td><?php echo $val['DOCUMENTS_CDT']?></td>
	<td><?php echo $val['UDT']?></td>
</tr>
<?php endif;?>
<?php endforeach;?>
</tbody>
</table>

<?php else:?>

	<p>Tidak ada data</p>

<?php endif;?>
	-->
	
		<!--start pagination-->
		<?php echo $daftar_doc2;?>
		<!--end pagination-->
	</div>
</div>
<script type="text/javascript">
$('#myTable, #myTable2').dynatable({
  table: {
    defaultColumnIdStyle: 'trimDash'
  }
});
</script>