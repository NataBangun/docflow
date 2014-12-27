<div class="page-header">
	<div class="pull-right">
		<?php if($records['PROCESS_STATUS']==DOC_DRAFT || $records['PROCESS_STATUS']==DOC_EDIT):?>
		<a href="<?php echo site_url('documents/edit/'.$records['PK_DOCUMENTS_ID'])?>" class="btn btn-primary"><i class="fam-pencil"></i> Edit</a>
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
	<h4><?php echo $records['DOCUMENTS_TITLE'];?></h4>
	<div class="clearfix"></div>
</div>

<ul class="nav nav-tabs" id="myTab">
	<li><a href="#metadata" data-toggle="tab">Info Dokumen</a></li>
	<li class="active"><a href="#versioning" data-toggle="tab">Versi</a></li>
	<li><a href="#comments" data-toggle="tab">Komentar</a></li>
	<?php if($records['PROCESS_STATUS']==DOC_DRAFT || $records['PROCESS_STATUS']==DOC_EDIT):?>
	<li class="pull-right"><div class="alert"><strong style="color: #A67E39;">Jika ingin melakukan submit / sosialisasi silakan klik tombol edit diatas </strong></div></li>
	<?php endif;?>
</ul>
 
<div class="tab-content">
	<div class="tab-pane" id="metadata">
	<?php if($records['PROCESS_STATUS']==DOC_FINAL):?>
	<a title="view merge" class="btn btn-mini btn-info" target="_blank" href="<?php echo site_url('documents/preview/'.$records['PK_DOCUMENTS_ID'].'/'.$records['PK_DOCUMENTS_PROCESS_ID'])?>"><i class="icon-white icon-eye-open"></i> Preview</a>
	<?php else:?>
	<a title="view merge" class="btn btn-mini btn-info" target="_blank" href="<?php echo site_url('documents/view/'.$records['PK_DOCUMENTS_ID'].'/'.$records['PK_DOCUMENTS_PROCESS_ID'])?>"><i class="icon-white icon-eye-open"></i> Preview</a>
	<?php endif;?>
	<div class="clearfix"></div>
<table class="table table-condensed alt1" id="xtable" style="border:solid 1px #eee;">
<tbody>
	<tr>
		<th width="120">Kategori Prosedur</th>
		<td><?php echo $records['CATEGORIES_TITLE'];?></td>
		<th width="100">Tgl. Terbit</th>
		<td><?php echo $records['DOCUMENTS_DATEPUB'];?></td>
		<th width="200" style="border-left:solid 1px #eee;">Status</th>
	</tr>
	<tr>
		<th>Penyusun</th>
		<td><?php echo $records['EMPLOYEE_NAME'].' ('.$records['E_MAIL_ADDR'].')';?></td>
		<th>Tgl. Buat</th>
		<td><?php echo $records['DOCUMENTS_CDT'];?></td>
		<td rowspan="3" style="border-left:solid 1px #eee;">
			<h2 style="text-align:center;margin-top:10px;">
			<?php 
				if ($records['PROCESS_STATUS'] == DOC_REVIEW) {
					echo $config['doc_status'][$records['CURRENT_LAYER']];
				} else {
					echo $config['doc_user'][$records['PROCESS_STATUS']];
				}
			?>
			</h2>
		</td>
	</tr>
	<tr>
		<th>Versi</th>
		<?php $doc_version = $records['VERSION_ID'];?>
		<td><span class="label label-info"><?php echo $doc_version[0].'.'.$doc_version[1]?><?php echo ($doc_version[2] == 0)? NULL : ' Revisi Ke - '. $doc_version[2] ;?></span></td>
		<th>Tgl. Update</th>
		<td><?php echo $records['DOCUMENTS_UDT'];?></td>
	</tr>
	<tr>
		<th>Proses Berjalan</th>
		<td>
			<span class="label label-success">
				<?php 
				if ($records['PROCESS_STATUS'] == DOC_REVIEW) {
					echo $records['CURRENT_LAYER'] .'. ('. $config['step_layer'][$records['CURRENT_LAYER']].')';
				} else {
					echo $config['doc_user'][$records['PROCESS_STATUS']];
				}
				?>
			</span>
		</td>
		<th>Tgl. Upload Webinfo</th>
		<td><?php echo $records['UPLOAD_DATE'];?></td>
	</tr>
</tbody>
</table>
	
	<table class="table table-condensed table-bordered">
	<tbody>
	<?php 		
	echo '<tr><th colspan="5" style="background:#eee;"> Dokumen Prosedur </th></tr>';	
	?>
	<?php if($records['DOCUMENTS_ATC_SYSTEM']):?>
	<tr>
		<td><?php echo '1'?>.</td>
		<td><a href="<?php echo base_url()?>uploads/<?php echo $records['DOCUMENTS_CBY']?>/<?php echo $records['DOCUMENTS_ATC_SYSTEM']?>" target="_blank"><?php echo $records['DOCUMENTS_ATC_SYSTEM']?></a></td>				
	</tr>
	<?php else:?>
	<tr>
		<td colspan="3">Tidak Ada</td>
	</tr>
	<?php endif;?>	
	
	
	</tbody>
	</table>		

	<table class="table table-condensed table-bordered">
	<tbody>
		<tr><th style="background:#eee;"colspan="3">Lampiran</th>			
		</tr>
		<tr>
		<?php $i=1;?>
		<?php $clear = substr($records['DOCUMENTS_ATC_NAME'], 0, -1);?>
		<?php $ex_name = explode(',', $clear);?>
			<?php if($records['DOCUMENTS_ATC_NAME']):?>
			<?php foreach($ex_name as $key=>$val):?>			
			<tr>
			<td><?php echo $i?></td>
			<td><?php echo $val?></td>
			<td><a target="_blank" href="<?php echo base_url(); ?>uploads/lampiran_dokpro/<?php echo $val;?>"> View </a></td>
			</tr>
			<?php $i++;?>
			<?php endforeach;?>	
			<?php else:?>
			<tr>
			<td colspan="3">
			Tidak Ada
			</td>
			</tr>
			<?php endif;?>										
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
					
						<?php if($penandatangan):?>
						<table class="table table-condensed" id="xtable" style="">
						<tbody>
						<?php foreach($process as $key=>$val):?>
						<?php $key = $key+1;?>
							<tr>
								<th width="200"><?php echo $key . '. ' . $val['PROCESS_NAME']?></th>
								<td>
								<?php 
								$num = 1;
								foreach($penandatangan as $level=>$user):
								if($user['STEP_LAYER']==$key) {
									$class = ( ($user['EMPLOYEE_NO']==$userInfo['uID']) ? ' ':'' );
									echo '<p>
									<span>'. $num . '. ' . $user['EMPLOYEE_NAME'] . ' (' . $user['E_MAIL_ADDR'] . ')</span>
									'.(($key==$records['CURRENT_LAYER'])?'<span><i class="fam-arrow-left"></i>':'').$class.'
									</p>';
									$num++;
								}
								endforeach;
								?>
								</td>
							</tr>
						<?php endforeach?>
						</tbody>
						</table>
						<?php else:?>
						<p class="important">Belum ada penandatangan</p>
						<?php endif;?>			
					
					</div>
				</div>
			</div><!--//accordion-group-->					
			
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#desc">Deskripsi/Catatan</a>
				</div>
				<div id="desc" class="accordion-body collapse">
					<div class="accordion-inner notes">
						<div>
						<?php echo $records['DOCUMENTS_DESCRIPTION'];?><?php //print_r($penandatangan);?>
						</div>
					</div>
				</div>
			</div><!--//accordion-group-->
			
		</div><!--//accordion-->

	</div><!--//metadata-->
	
	<div class="tab-pane active" id="versioning">
	<?php if($versioning):?>
	<table class="table table-condensed">
	<tbody>
	<?php 
	$version = 0;
	foreach($versioning as $key):	
	if($version != $key['VERSION_ID']) {
		$ver = $key['VERSION_ID'];
		 echo '<tr><th colspan="5" style="background:#eee;">'.$ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2]).'</th></tr>';
	 
	}
	?>
	<tr>
		<td><?php echo $key['STEP_LAYER'] . '. ' . $config['step_layer'][ $key['STEP_LAYER'] ]?></td>
		<td><?php echo $key['EMPLOYEE_NAME']?></td>
		<td><?php echo($key['PROCESS_TYPE']==1)?' <span class="label label-info"> P </span>':' <span class="label label-warning"> S </span>';?></td>
		<td><?php echo '<i class="'.(($key['APPROVAL_STATUS']==ACTION_APPROVE) ? 'fam-accept' : 'fam-error' ).'"></i> '.$config['act_status'][ $key['APPROVAL_STATUS'] ]?></td>		
		<td><?php echo $key['APPROVAL_UDT']?></td>
	</tr>
	<?php 
	$version = $key['VERSION_ID'];
	endforeach;?>
	</tbody>
	</table>
	<?php else:?>
	<p class="font-disabled">Belum ada versi</p>
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
			echo '<h4 class="">'.$ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2]).'</h4>';
		}
		?>
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#cmt<?php echo $key['PK_DOCUMENTS_COMMENTS_ID']?>">
					<span class="pull-right font-disabled"><em><?php echo ' mengomentari ver.'.$ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2]).', pada: ' . $key['COMMENTS_CDT']?></em></span>
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
<script type="text/javascript">
$(function() { 

	$("[id=commentBtn]").click(function(e){
		$('#commentModal').modal({
			show:'show'
		});
		e.preventDefault();
	});
	
	var redirect_url = "<?php echo current_url()?>";
	var posted = false;
	
	$('#commentModal').on('hidden', function () {
		$('input[name=respon], textarea[name=comment]').val();
		if(posted) {
			location.href = redirect_url;
			posted = false;
		}
    })

});

</script>