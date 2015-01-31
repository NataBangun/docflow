<div class="page-header">
	<h4>Daftar Kategori & Workflow</h4>
</div>

<p><a href=":;" id="linkBtn" class="btn btn-info" data-rel="<?php echo site_url('categories/add')?>" title="Buat Kategori Baru"><i class="fam-add"></i> Buat Kategori & Workflow</a></p>

<?php if($records):?>

<table id="myTable" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th width="140">#</th>
		<th>Judul</th>			
		<th>Jenis</th>	
		<th>Status</th>			
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records as $val):?>
<tr>

	<?php if($val['CATEGORIES_STATUS'] == 1):?>
	<td><a href="javascript:;" data-rel="<?php echo site_url('categories/aktif/'.$val['PK_CATEGORIES_ID'])?>" id="aclink" class="btn btn-mini" title="aktifkan Kembali"><i class="fam-cancel"></i></a>
	<a href="<?php echo site_url('categories/edit/'.$val['PK_CATEGORIES_ID'])?>" class="btn btn-mini" title="Edit" id="edit"><i class="fam-pencil"></i></a>
	</td>
	<?php else:?>
	<td><a href="javascript:;" data-rel="<?php echo site_url('categories/delete/'.$val['PK_CATEGORIES_ID'])?>" id="nonlink" class="btn btn-mini" title="non aktifkan"><i class="fam-accept"></i></a>
	<a href="<?php echo site_url('categories/edit/'.$val['PK_CATEGORIES_ID'])?>" class="btn btn-mini" title="Edit" id="edit"><i class="fam-pencil"></i></a>
	</td>
	<?php endif;?>
	

	<td><?php echo $val['CATEGORIES_TITLE']?></td>	
	<td><?php echo $val['TYPE_NAME']?></td>	
	<?php if($val['CATEGORIES_STATUS'] == 1):?>
	<td><span class="label label-important"> Tidak Aktif </span>
	</td>
	<?php else:?>
	<td><span class="label label-info"> Aktif </span>
	</td>
	<?php endif;?>
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