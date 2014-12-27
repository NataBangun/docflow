<div class="page-header">
	<h4>Dashboard</h4>
</div>
<!-- <?php echo $this->session->userdata('umc_feature'); ?> -->
<div class="clearfix">&nbsp;</div>
<div class="row-fluid">

	<!--
	<?php if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))) { ?>
	
	<div class="span6">
		<h4 style="padding-bottom: 20px;">Dokumen Prosedur</h4>
		<table id="myTable" class="tablesorter order-table table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nomor</th>					
					<th>Judul</th>
					<th>Versi</th>
					<th>Status</th>	
					<th>Tgl.Buat</th>
				</tr>
			</thead>
			<tfoot>
			</tfoot>
			<tbody>
				<?php $i = 1;?>
				<?php if($records):?>
				<?php foreach($records as $val):?>					
				<?php if($i <= 10):?>
				<tr>
					<?php if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))) 
					{ ?>
					<td><a href="<?php echo site_url('documents/detail/'.$val['PK_DOCUMENTS_ID'])?>"><?php echo $val['PK_DOCUMENTS_ID']?></a></td>
					<td><a href="<?php echo site_url('documents/detail/'.$val['PK_DOCUMENTS_ID'])?>"><?php echo $val['DOCUMENTS_NO']?></a></td>					
					<?php }?>
					<td><?php echo $val['DOCUMENTS_TITLE']?></td>
					<?php $ver = $val['VERSION_ID'];?>
					<td><?php echo $ver[0].'.'.$ver[1]?><?php echo ($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2] ;?></td>
					<td><?php echo $config['doc_user'][ $val['PROCESS_STATUS'] ]?></td>					
					<td><?php echo $val['DOCUMENTS_CDT']?></td>
				</tr>
				<?php endif;?>
				<?php $i++;?>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
	</div>	
	<?php }elseif(in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
	|| in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
	|| in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))) 
	{ ?>
	<?php if($records_inbox){?>
	<div class="span6">
		<h4 style="padding-bottom: 20px;">Dokumen Prosedur</h4>
	<table id="myTable3" class="tablesorter order-table table table-bordered">
<thead>
	<tr>
		<th>ID</th>
		<th>Judul</th>
		<th>Versi</th>
		<th>Status</th>
		<th>Penyusun</th>		
		<th>Tgl.Buat</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
<?php foreach($records_inbox as $val):?>
<?php if($val['APPROVAL_STATUS'] ==0 ){?>
<tr>
	<td><a href="<?php echo site_url('inbox/detail/'.$val['PK_DOCUMENTS_ID'])?>" title="detail"><?php echo $val['PK_DOCUMENTS_ID']?></a></td>
	<td>
	<span><a href="<?php echo site_url('inbox/detail/'.$val['PK_DOCUMENTS_ID'])?>" title="detail"><?php echo $val['DOCUMENTS_TITLE']?></a></span><br>
	<span class="font-disabled"><?php echo $val['CATEGORIES_TITLE']?></span>
	</td>
	<?php $ver = $val['VERSION_ID'];?>
	<td><?php echo $ver[0].'.'.$ver[1]?><?php echo ($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2] ;?></td>
	<td><?php echo $config['doc_status'][ $val['CURRENT_LAYER'] ]?></td>		
	
	<td><?php echo $val['EMPLOYEE_NAME']?> <br>
	<span class="font-disabled">(<?php echo $val['E_MAIL_ADDR']?>)</span></td>	
	<td><?php echo $val['DOCUMENTS_CDT']?></td>
</tr>
<?php }?>
<?php endforeach;?>
</tbody>
</table>
	</div>
	<?php }?>
	<?php }?>
	-->
	
	
	<!--
	<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))) { ?>	
	<div class="span6">
		<h4 style="padding-bottom: 20px;">Nota Dinas</h4>
		<table id="myTable2" class="tablesorter order-table table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nomor</th>
					<th>Judul</th>										
					<th>Tgl.Buat</th>
				</tr>
			</thead>
			<tfoot>
			</tfoot>
			<tbody>
				<?php $i = 1;?>				
				<?php if($records_nota):?>
				<?php foreach($records_nota as $val):?>				
				<?php if($i <= 10):?>
				<tr>
					<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))) 
					{ ?>
					<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['PK_NOTA_ID']?></a></td>
					<td><a href="<?php echo site_url('nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['NO_SURAT']?></a></td>					
					<?php }?>
					<td><?php echo $val['HAL']?></td>										
					<td><?php echo $val['CREATE_DATE']?></td>					
				</tr>
				<?php endif;?>
				<?php $i++;?>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
	</div>
	 	
	<?php }elseif(in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
				|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature')))){?>
				<div class="span6">
		<h4 style="padding-bottom: 20px;">Nota Dinas</h4>
		<table id="myTable2" class="tablesorter order-table table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nomor</th>
					<th>Judul</th>										
					<th>Tgl.Buat</th>
				</tr>
			</thead>
			<tfoot>
			</tfoot>
			<tbody>
				<?php $i = 1;?>				
				<?php if($records_in_nota):?>
				<?php foreach($records_in_nota as $val):?>				
				<?php if($i <= 10):?>
				<tr>			
					<td><a href="<?php echo site_url('inbox_nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['PK_NOTA_ID']?></a></td>
					<td><a href="<?php echo site_url('inbox_nota/detail/'.$val['PK_NOTA_ID'])?>"><?php echo $val['NO_SURAT']?></a></td>										
					<td><?php echo $val['HAL']?></td>										
					<td><?php echo $val['CREATE_DATE']?></td>					
				</tr>
				<?php endif;?>
				<?php $i++;?>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
	</div>	
	<?php }?>
	-->
	<?php if ( !in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
			&& !in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
			&& in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
			&& !in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
			&& !in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature'))) 
			) {	?>
	<div class="span12">
	<?php }else{?>
	<div class="span6">
	<?php }?>
		<h4 style="padding-bottom: 20px;">Dokumen Prosedur </h4>
		
		<!--start pagination-->
		<?php echo $doc_dashboard;?>
		<!--end pagination-->
		
	</div>
	<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature'))) 
			|| in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))
			&& !in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
			|| in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))			
			|| in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature')))
			) { ?>
	<div class="span6">
		<h4 style="padding-bottom: 20px;">Nota Dinas</h4>
		
		<!--start pagination-->
		<?php echo $nota_dashboard;?>
		<!--end pagination-->		
	</div>
	<?php }?>
	
</div>
<div class="clearfix">&nbsp;</div>
<script type="text/javascript">
// $('#myTable,#myTable2,#myTable3').dynatable({
  // table: {
    // defaultColumnIdStyle: 'trimDash'
  // }
// });
</script>