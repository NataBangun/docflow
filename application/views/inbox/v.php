
<ul class="nav nav-tabs" id="myTab">
	<li class="<?php  
	if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
		|| in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
		|| in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
		|| in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
		|| in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature'))) 
		) { 
		echo "";
	} else {
		echo "hide";
	}
	?>"><a data-toggle="tab" href="#s1">Inbox Dokumen Prosedur</a></li>
	<li class="<?php  
	if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
		|| in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
		|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature'))) 
		|| in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))
		) { 
		echo "";
	} else {
		echo "hide";
	}
	?>"><a data-toggle="tab" href="#s2">Inbox Nota Dinas</a></li>
</ul>

<div class="tab-content" id="myTabContent">
	<div id="s1" class="tab-pane fade">
		<div class="page-header">
			<h4>Inbox Dokumen Prosedur</h4>
		</div>
		
		<!--
		<?php if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))):?>			

		<table id="myTable" class="tablesorter order-table table table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>Judul</th>
				<th>Versi</th>
				<th>Status</th>
				<th>Proses Berjalan</th>
				<th>Respon</th>
				<th>Penyusun</th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
		<?php foreach($records as $val):?>
		<?php if($val['APPROVAL_STATUS'] != 2):?>
		<tr>
			<td><a href="<?php echo site_url('inbox/detail/'.$val['PK_DOCUMENTS_ID'])?>" title="detail"><?php echo $val['PK_DOCUMENTS_ID']?></a></td>
			<td>
			<span><a href="<?php echo site_url('inbox/detail/'.$val['PK_DOCUMENTS_ID'])?>" title="detail"><?php echo $val['DOCUMENTS_TITLE']?></a></span><br>
			<span class="font-disabled"><?php echo $val['CATEGORIES_TITLE']?></span>
			</td>
			<?php $ver = $val['VERSION_ID'];?>
			<td><?php echo $ver[0].'.'.$ver[1]?><?php echo ($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2] ;?></td>
			<td><?php echo $config['doc_status'][ $val['CURRENT_LAYER'] ]?></td>	
			<td><?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $val['CURRENT_LAYER'] .'. ('. $config['step_layer'][$val['CURRENT_LAYER']].')' : DOC_CLOSED;?></td>
			<td>
			<?php if($val['CURRENT_LAYER']==ACTION_FINAL):?>
			<?php echo $config['act_status_icon'][ 2 ][1].' '.$config['act_status_icon'][ 2 ][0]?>
			<?php else:?>
			<?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $config['act_status_icon'][ $val['APPROVAL_STATUS'] ][1].' '.$config['act_status'][ $val['APPROVAL_STATUS'] ] : $config['act_status_icon'][ $val['APPROVAL_STATUS'] ][1].' '.$config['act_status_icon'][ $val['APPROVAL_STATUS'] ][0];?>		
			<?php endif;?>
			</td>
			
			<td><?php echo $val['EMPLOYEE_NAME']?> <br>
			<span class="font-disabled">(<?php echo $val['E_MAIL_ADDR']?>)</span></td>

		</tr>
		<?php endif;?>
		<?php endforeach;?>
		
		<?php foreach($revisi as $val):?>
		<?php if($val['PROCESS_STATUS'] == DOC_EDIT):?>
		<?php if($val['DOCUMENTS_CBY'] == $this->session->userdata('uID')):?>
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
		<?php endif;?>
		<?php endforeach;?>

		</tbody>
		</table>

		<?php else:?>

		<?php if($records):?>

			<table id="myTable" class="tablesorter order-table table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Judul</th>
					<th>Versi</th>
					<th>Status</th>
					<th>Proses Berjalan</th>
					<th>Respon</th>
					<th>Penyusun</th>
					<th>Tgl.Publikasi</th>
					<th>Tgl.Buat</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($records as $val):?>
					<?php if($val['CURRENT_LAYER'] == $val['STEP_LAYER']):?>
						<?php if($val['APPROVAL_STATUS'] < 2 || $val['APPROVAL_STATUS'] == 99 ):?>
						<tr>
							<td>
								<a href="<?php echo site_url('inbox/detail/'.$val['PK_DOCUMENTS_ID'])?>" title="detail">
									<?php echo $val['PK_DOCUMENTS_ID']?>
								</a>
							</td>
							<td>
								<span>
									<a href="<?php echo site_url('inbox/detail/'.$val['PK_DOCUMENTS_ID'])?>" title="detail">
										<?php echo $val['DOCUMENTS_TITLE']?>
									</a>
								</span><br>
								<span class="font-disabled">
									<?php echo $val['CATEGORIES_TITLE']?><?php echo $val['CATEGORIES_TITLE']?></span>
							</td>
							<?php $ver = $val['VERSION_ID'];?>
							<td><?php echo $ver[0].'.'.$ver[1]?><?php echo ($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2] ;?></td>
							<td><?php echo $config['doc_status'][ $val['CURRENT_LAYER'] ]?></td>	
							<td><?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $val['CURRENT_LAYER'] .'. ('. $config['step_layer'][$val['CURRENT_LAYER']].')' : DOC_CLOSED;?></td>
							<td>
							<?php if($val['CURRENT_LAYER']==ACTION_FINAL):?>
							<?php echo $config['act_status_icon'][ 2 ][1].' '.$config['act_status_icon'][ 2 ][0]?>
							<?php else:?>
							<?php echo ($val['CURRENT_LAYER']!=ACTION_FINAL) ? $config['act_status_icon'][ $val['APPROVAL_STATUS'] ][1].' '.$config['act_status'][ $val['APPROVAL_STATUS'] ] : $config['act_status_icon'][ $val['APPROVAL_STATUS'] ][1].' '.$config['act_status_icon'][ $val['APPROVAL_STATUS'] ][0];?>		
							<?php endif;?>
							</td>
							<td>
								<?php echo $val['EMPLOYEE_NAME']?><br>
								<span class="font-disabled">
									(<?php echo $val['E_MAIL_ADDR']?>)
								</span>
							</td>
							<td>
								<?php echo $val['DOCUMENTS_DATEPUB']?>
							</td>
							<td>
								<?php echo $val['DOCUMENTS_CDT']?>
							</td>
						</tr>
						<?php endif;?>
					<?php endif;?>
				<?php endforeach;?>
			</tbody>
			</table>

		<?php else:?>
			<p>Tidak ada data</p>
		<?php endif;?>

		<?php endif;?>
		-->
		
		<!--start pagination-->
		<?php echo $doc_inbox;?>
		<!--end pagination-->
		
	</div>

	<div id="s2" class="tab-pane fade">
		<div class="page-header">
			<h4>Inbox Nota Dinas</h4>
		</div>
		
		<!--start pagination-->
		<?php echo $nota_inbox;?>
		<!--end pagination-->

	</div>

</div>
<script type="text/javascript">
// $('#myTable').dynatable({
  // table: {
    // defaultColumnIdStyle: 'trimDash'
  // }
// });

// $('#myTable_nota').dynatable({
  // table: {
    // defaultColumnIdStyle: 'trimDash'
  // }
// });
$(document).ready(function() {
	var arr_li = $('#myTab').find('li');
	var arr_div = $('#myTabContent').find('div.tab-pane');
	for (var i=0; i<arr_li.size(); i++) {
		if ($(arr_li[i]).hasClass('hide') == false) {
			$(arr_li[i]).addClass('active');
			$(arr_div[i]).addClass('active in');
			return;
		}
	}
});
</script>