<div class="page-header">
	<h4>Pengaturan Tanda tangan dan Paraf</h4>
</div>
<div class="clearfix">&nbsp;</div>
<div class="row-fluid">	
	<div class="span12">
		<table id="myTable" class="tablesorter order-table table table-bordered">
			<colgroup></colgroup>
			<colgroup></colgroup>
			<colgroup></colgroup>
			<colgroup></colgroup>
			<colgroup></colgroup>
			<thead>
				<tr>
					<th width="150"><strong>Action</strong></th>
					<th class="short"><strong>Nama</strong></th>
					<th class="short"><strong>Ttd</strong></th>
					<th class="short"><strong>Paraf</strong></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($records_user as $key=>$val):?>
				
				<tr>
					<td><a href="<?php echo site_url('usr/add/'.$val['EMPLOYEE_NO'])?>" class="btn btn-primary" role="button">upload ttd & paraf</a></td>
					<td><?php echo $val['EMPLOYEE_NAME']?></td>
					<td><?php echo ($val['USERS_SIGNATURE'])? '<span class="label label-info">Ada</span>' : '<span class="label label-important">Belum Ada</span>';?></td>
					<td><?php echo ($val['USERS_PARAF'])? '<span class="label label-info">Ada</span>' : '<span class="label label-important">Belum Ada</span>';?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>

	</div>
</div>

<script type="text/javascript">
$('#myTable').dynatable({
  table: {
    defaultColumnIdStyle: 'trimDash'
  }
});
</script>