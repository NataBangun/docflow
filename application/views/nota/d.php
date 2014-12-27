<div class="page-header">
	<div class="pull-right">
		<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))): ?>
			<?php if($records['PROCESS_STATUS']==DOC_DRAFT):?>
				<a href="<?php echo site_url('nota/edit/'.$records['PK_NOTA_ID'])?>"><i class="fam-pencil"></i> Edit</a>		
			<?php endif;?>
			<?php if($records['PROCESS_STATUS']==DOC_EDIT):?>
				<a href="<?php echo site_url('nota/edit_revisi/'.$records['PK_NOTA_ID'])?>"><i class="fam-pencil"></i> Edit</a>		
			<?php endif;?>
		<?php endif;?>
		
		<?php if($records['PROCESS_STATUS']==DOC_FINAL):?>
			<?php if (in_array("Webinfo", explode("|", $this->session->userdata('umc_feature')))): ?>
				<script type="text/javascript">
					function upload_webinfo()
					{
						window.open('<?php echo site_url('webinfo/tree/'.$records['PK_DOCUMENTS_PROCESS_ID'])?>', 'webinfo', 'width=630, height=430');
					}
				</script>
				<a href="javascript:upload_webinfo()" class="btn btn-primary"><i class="fam-arrow-up"></i> Upload Ke Webinfo</a>
			<?php endif;?>
		<?php endif;?>
	</div>
	<h4><?php echo $records['HAL'];?></h4>
</div>

<form id="frmSendEmail">
	<input type="hidden" name="nota_id" value="<?php echo $records['PK_NOTA_ID'] ?>">
	<div id="messageWrapper"></div>
</form>

<ul class="nav nav-tabs" id="myTab">
	<li><a href="#metadata" data-toggle="tab">Info Dokumen</a></li>
	<li class="active"><a href="#versioning" data-toggle="tab">Versi</a></li>
	<li><a href="#comments" data-toggle="tab">Komentar</a></li>
	<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))): ?>
		<?php if($records['PROCESS_STATUS']==NOTA_DRAFT || $records['PROCESS_STATUS']==NOTA_EDIT):?>
			<li class="pull-right">
				<div class="alert">
					<strong style="color: #A67E39;">Jika ingin melakukan submit / sosialisasi silakan klik tombol edit diatas </strong>
				</div>
			</li>
		<?php endif;?>
	<?php endif;?>
</ul>
 
<div class="tab-content">
	<div class="tab-pane" id="metadata">
	<a href='<?php echo base_url('generate_pdf/nota/'.$records['PK_NOTA_ID']);?>' class="btn btn-mini btn-info" target='_blank'>
		<i class="icon-white icon-eye-open"></i> Preview
	</a>
	<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))) { ?>
		<?php if ($records['CURRENT_LAYER'] == ACTION_FINAL) { ?>
			<button type="button" id="btnSendEmail" class="btn btn-mini btn-info" data-confirm="Email akan dikirim kepada penerima Nota Dinas. Anda Yakin?">
				<i class="icon-white icon-envelope"></i> Kirim E-mail
			</button>
		<?php }?>
	<?php }?>
<table class="table table-condensed alt1" id="xtable" style="border:solid 1px #eee;">
<tbody>
	<tr>
		<th width="120">No. Surat</th>
		<td>
			<?php echo ($records['NO_SURAT'] == 0) ? 'Belum Mendapat nomor' : $records['NO_SURAT_LONG'];?>
			
			<?php if ($records['NO_SURAT'] != 0) { ?>
				<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))) { ?>
					&nbsp;&nbsp;
					<a href='<?php echo base_url('nota/edit_no/'.$records['PK_NOTA_ID']);?>' class="btn btn-mini btn-info">
						Edit
					</a>		
				<?php } ?>
			<?php } ?>
			
		</td>
		<th width="140">Tgl. Nota Dinas</th>
		<td><?php echo $records['TANGGAL_NOTA'];?></td>
		<th width="200" style="border-left:solid 1px #eee;">Status</th>
	</tr>
	<tr>
		<th>Penyusun</th>
		<td><?php echo $records['EMPLOYEE_NAME'].' ('.$records['E_MAIL_ADDR'].')';?></td>
		<th>Tgl. Buat</th>
		<td><?php echo $records['CREATE_DATE'];?></td>
		<td rowspan="3" style="border-left:solid 1px #eee;">
			<h2 style="text-align:center;margin-top:10px;">
			<?php echo $config['nota_status'][$records['PROCESS_STATUS']]; ?>
			</h2>
		</td>
	</tr>	
	<tr>
		<th>Versi</th>
		<td><?php echo $records['VERSION_ID'] ;?></td>
		<th>Tgl. Update</th>
		<td>
			<?php echo $records['UPDATE_DATE'];?>
		</td>
	</tr>
	<tr>
		<th>Proses Berjalan</th>
		<td>
			<span class="label label-success">
			<?php 
				if ($records['PROCESS_STATUS'] == NOTA_REVIEW) {
					echo $records['CURRENT_LAYER'] .'. ('. $config['nota_step_layer'][$records['CURRENT_LAYER']].')';
				} else {
					echo $config['nota_status'][$records['PROCESS_STATUS']];
				}
			?>
			</span>
		</td>
		<th>Tgl. Upload Webinfo</th>
		<td>
			<?php echo $records['UPLOAD_DATE'];?>
		</td>
	</tr>
</tbody>
</table>

		<div class="accordion" id="accordion_meta">
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#penandatangan">Penandatangan</a>
				</div>
				<div id="penandatangan" class="accordion-body collapse in">
					<div class="accordion-inner">										
						<table class="table table-condensed" id="xtable" style="">
						<tbody>						
							<tr>
								<th width="200"><?php echo 1 . '. ' . 'Pengesahan Kanan'?></th>
								<td>
									<p> 
									<?php if($pnota1):?>
									<span><?php echo $pnota1['EMPLOYEE_NAME'].' ('.$pnota1['E_MAIL_ADDR'].')'?></span>
									<?php else:?>
									<span>Tidak ada</span>
									<?php endif;?>
									</p>
								</td>
							</tr>
							<tr>
								<th width="200"><?php echo 2 . '. ' . 'Pengesahan Tengah'?></th>
								<td>
									<p> 
									<?php if($pnota2):?>
									<span><?php echo $pnota2['EMPLOYEE_NAME'].' ('.$pnota2['E_MAIL_ADDR'].')'?></span>
									<?php else:?>
									<span>Tidak ada</span>
									<?php endif;?>
									</p>
								</td>
							</tr>
							<tr>
								<th width="200"><?php echo 3 . '. ' . 'Pengesahan Kiri'?></th>
								<td>
									<p> 
									<?php if($pnota3):?>
									<span><?php echo $pnota3['EMPLOYEE_NAME'].' ('.$pnota3['E_MAIL_ADDR'].')';?></span>
									<?php else:?>
									<span>Tidak ada</span>
									<?php endif;?>
									</p>
								</td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>
			</div><!--//accordion-group-->
			
		</div><!--//accordion-->

	</div><!--//metadata-->
	
	<div class="tab-pane active" id="versioning">
	<?php if($versioning):?>
	<?php //print_r($versioning);?>
	<table class="table table-condensed">
	<tbody>
	<?php 
	$version = 0;
	foreach($versioning as $key):	
	//$version_formatted  = wordwrap($key['VERSION_ID'], 1, '.', true);
	if($version != $key['VERSION_ID']) {
	echo '<tr><th colspan="6" style="background:#eee;">'.$key['VERSION_ID'].'</th></tr>';
	}

	?>
	<tr>
		<td><?php echo $key['STEP_LAYER'] . '. ' . $config['nota_step_layer'][ $key['STEP_LAYER'] ]?></td>
		<td><?php echo $key['EMPLOYEE_NAME']?></td>
		<td><?php echo($key['PROCESS_TYPE']==1)?' <span class="label label-info"> P </span>':' <span class="label label-warning"> S </span>';?></td>
		<td><?php echo '<i class="'.(($key['APPROVAL_STATUS']==ACTION_APPROVE) ? 'fam-accept' : 'fam-error' ).'"></i> '.$config['act_status'][ $key['APPROVAL_STATUS'] ]?></td>
		<td></td>
		<td><?php echo $key['APPROVAL_UDT']?></td>
	</tr>
	<?php 
	$version = $key['VERSION_ID'];
	endforeach;?>
	</tbody>
	</table>
	<?php else:?>
	<p class="font-disabled">Belum ada versioning</p>
	<?php endif;?>
	</div><!--//versioning-->
	
	<div class="tab-pane" id="comments">
	<?php if($comments):?>
	<div class="accordion" id="accordion_comments">
		<?php 
		$version = 0;
		foreach($comments as $key):
			if($version != $key['VERSION_ID']) {
				$ver = $key['VERSION_ID'];
				echo '<h5 class="rev">Versi : '. $ver.'</h5>';
			}
			?>
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#cmt<?php echo $key['PK_DOCUMENTS_COMMENTS_ID']?>">
					<span class="pull-right font-disabled"><em><?php echo ' mengomentari pada: ' . $key['COMMENTS_CDT']?></em></span>
					<?php echo $key['EMPLOYEE_NAME']?>
					</a>
				</div>
				<div id="cmt<?php echo $key['PK_DOCUMENTS_COMMENTS_ID']?>" class="accordion-body collapse">
					<div class="accordion-inner notes">
						<div>
						<?php echo ($key['COMMENTS_DESC'])?$key['COMMENTS_DESC']:'<i> Tidak ada komentar </i>';?>
						</div>
					</div>
				</div>
			</div><!--//accordion-group-->
		<?php 
		$version = $key['VERSION_ID'];		
		endforeach?>
	</div><!--//accordion-group-->
	<?php else:?>
	<p class="font-disabled">Belum ada komentar</p>
	<?php endif;?>
	</div><!--//comments-->
	
	
</div><!--//tab-content-->
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
<script type="text/javascript">
	$(function() {   
		$("#btnSendEmail").click(function(e){
			var data_confirm = $(this).attr('data-confirm');
			if (confirm(data_confirm)){
				var options = { 
					target: '#messageWrapper',
					success: showResponse,
					url: '<?php echo site_url('nota/send_email')?>',
					type: 'post',
					dataType: 'json'
				}; 

				$('#frmSendEmail').ajaxSubmit(options); 
			}
		});
	});
	function showResponse(responseText, statusText, xhr, $form)
	{
		if (responseText.error=='1')
		{
			$('#messageWrapper').html( '<div class="alert alert-error">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' );
		}
		else
		{
			$('#messageWrapper').html( '<div class="alert alert-success">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' );
					
		}
	}
</script>