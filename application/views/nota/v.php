<div class="page-header">
	<h4>Daftar Nota Dinas</h4>
</div>
<p>
	<a href=":;" id="linkBtn" class="btn btn-info" data-rel="<?php echo site_url('nota/add')?>" title="Buat Nota Dinas Baru">
		<i class="fam-add"></i> Buat Nota Dinas
	</a>	
	<div class="clearfix"></div>
</p>
<ul class="nav nav-tabs" id="myTab">
	<li class="active">
		<a data-toggle="tab" href="#s1">Dalam Proses</a>
	</li>
	<li class="">
		<a data-toggle="tab" href="#s2">Selesai</a>
	</li>
</ul>
<div class="tab-content" id="myTabContent">
	<div id="s1" class="tab-pane fade active in">
		<!--
		<?php if($records):?>
			<table id="myTable" class="tablesorter order-table table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nomor Nota Dinas</th>
					<th>Hal</th>
					<th>Dari</th>		
					<th>Status</th>
					<th>Tgl. Publikasi</th>
					<th>Tgl. Buat</th>
				</tr>
			</thead>
			<tfoot>
			</tfoot>
			<tbody>
				<?php foreach($records as $val):?>
					<?php if($val['PROCESS_STATUS'] != NOTA_FINAL):?>
						<tr>
							<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['PK_NOTA_ID']?></a></td>
							<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['NO_SURAT']?></a></td>
							<td><?php echo $val['HAL']?></td>
							<td><?php echo $val['DARI']?></td>	
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
		-->
		
		<!--start pagination-->
		<?php echo $daftar_nota1;?>
		<!--end pagination-->
		
	</div>
	<div id="s2" class="tab-pane fade">
		<!--
		<?php if($records):?>
			<table id="myTable2" class="tablesorter order-table table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nomor Nota Dinas</th>
					<th>Hal</th>
					<th>Dari</th>
					<th>Status</th>
					<th>Tgl. Publikasi</th>
					<th>Tgl. Buat</th>
				</tr>
			</thead>
			<tfoot>
			</tfoot>
			<tbody>
				<?php foreach($records as $val):?>
					<?php if($val['PROCESS_STATUS'] == NOTA_FINAL):?>
						<tr>
							<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['PK_NOTA_ID']?></a></td>
							<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['NO_SURAT']?></a></td>
							<td><?php echo $val['HAL']?></td>
							<td><?php echo $val['DARI']?></td>
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
		-->
		
		<!--start pagination-->
		<?php echo $daftar_nota2;?>
		<!--end pagination-->
		
	</div>
</div>
<script type="text/javascript">
// $('#myTable, #myTable2').dynatable({
	// table: {
		// defaultColumnIdStyle: 'trimDash'
	// }
// });
</script>