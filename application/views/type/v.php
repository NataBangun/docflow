<div class="page-header">
	<h4>Daftar Jenis Dokumen</h4>
</div>

<p><a href=":;" id="linkBtn" class="btn btn-info" data-rel="<?php echo site_url('type/add')?>" title="Buat Kategori Baru"><i class="fam-add"></i> Buat Jenis Dokumen</a></p>

<?php if($records):?>

<table id="myTable" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th width="140">#</th>
		<th>Judul</th>			
		<th>Status</th>			
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records as $val):?>
<tr>
	<?php if($val['TYPE_STATUS'] == 1):?>
	<td><a href="javascript:;" data-rel="<?php echo site_url('type/active/'.$val['PK_TYPE_ID'])?>" id="aclink" class="btn btn-mini" title="aktifkan Kembali"><i class="fam-cancel"></i></a>
	<a href="<?php echo site_url('type/edit/'.$val['PK_TYPE_ID'])?>" class="btn btn-mini" title="Edit" id="edit"><i class="fam-pencil"></i></a>
	</td>
	<?php else:?>
	<td><a href="javascript:;" data-rel="<?php echo site_url('type/delete/'.$val['PK_TYPE_ID'])?>" id="nonlink" class="btn btn-mini" title="non aktifkan"><i class="fam-accept"></i></a>
	<a href="<?php echo site_url('type/edit/'.$val['PK_TYPE_ID'])?>" class="btn btn-mini" title="Edit" id="edit"><i class="fam-pencil"></i></a>
	</td>
	<?php endif;?>
	<td><?php echo $val['TYPE_NAME']?></td>	
	<td><?php echo ($val['TYPE_STATUS'] == 1)?'<span class="label label-important"> Tidak Aktif </span>':'<span class="label label-info"> Aktif </span>'?></td>	
</tr>
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