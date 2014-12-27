<div class="page-header">
	<div class="pull-right">	
	<a id="commentBtn" href=":;" title="Berikan saran"><i class="fam-comment"></i> Berikan <?php echo (($make_approval)?'Approval &amp; ':'')?>Saran</a>
	</div>
	<?php $doc_version = $records['VERSION_ID'];?>
	<h4><?php echo $records['DOCUMENTS_TITLE'] .' <sup>Ver. '.$doc_version[0].'.'.$doc_version[1].(($doc_version[2] == 0)? NULL : ' Revisi Ke - '. $doc_version[2]).'</sup>';?></h4>
</div>

<ul class="nav nav-tabs" id="myTab">
	<li><a href="#metadata" data-toggle="tab">Metadata</a></li>
	<li class="active"><a href="#versioning" data-toggle="tab">Versioning</a></li>
	<li><a href="#comments" data-toggle="tab">Comments</a></li>
	<li><a href="#files" data-toggle="tab">Files</a></li>
</ul>
 
<div class="tab-content">
	<div class="tab-pane" id="metadata">

	<a title="view merge" class="btn btn-mini btn-info" target="_blank" href="<?php echo site_url('generate_doc_pro2/create/'.$records['FK_DOCUMENTS_ID'])?>"><i class="icon-white icon-eye-open"></i> Preview</a>
	
<table class="table table-condensed alt1 table-bordered" id="xtable" style="border:solid 1px #eee;">
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
		<td rowspan="3" style="border-left:solid 1px #eee;"><h2 style="text-align:center;margin-top:10px;"><?php echo $config['doc_status'][ $records['CURRENT_LAYER'] ];?></h2></td>
	</tr>
	<tr>
		<th>Versi</th>		
		<td><span class="label label-info"><?php echo $doc_version[0].'.'.$doc_version[1]?><?php echo ($doc_version[2] == 0)? NULL : ' Revisi Ke - '. $doc_version[2] ;?></span></td>
		<th>Tgl. Update</th>
		<td><?php echo $records['DOCUMENTS_UDT'];?></td>
	</tr>
	<tr>
		<th>Proses Berjalan</th>
		<td><span class="label label-success"><?php echo ($is_step_final) ? $records['CURRENT_LAYER'] .'. ('. $config['step_layer'][$records['CURRENT_LAYER']].')' : DOC_CLOSED;?></label></td>
		<th>Tgl. Upload webinfo</th>
		<td><?php echo ($records['PROCESS_STATUS']==DOC_FINAL) ? 'Tgl Pengesahan' : '---';?></td>
	</tr>
</tbody>
</table>


<table class="table table-condensed table-bordered">
<tbody>
	<tr><th style="background:#eee;">Lampiran</th>
		<th style="background:#eee;">Action</th>
	</tr>
	<tr>						
	<?php $clear = substr($records['DOCUMENTS_ATC_NAME'], 0, -1);?>
	<?php $ex_name = explode(',', $clear);?>
		<?php foreach($ex_name as $key=>$val):?>
		<tr>
		<td><?php echo $val?></td>
		<td><a target="_blank" href="<?php echo base_url(); ?>uploads/lampiran_dokpro/<?php echo $val;?>"> View </a></td>
		</tr>
		<?php endforeach;?>	
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
						<table class="table table-condensed table-bordered" id="xtable" style="">
						<tbody>
						<?php foreach($config['step_layer'] as $key=>$val):?>
							<tr>
								<th width="200"><?php echo $key . '. ' . $val?></th>
								<td>
								<?php 
								$num = 1;
								foreach($penandatangan as $level=>$user):
								if($user['STEP_LAYER']==$key) {
									$class = ( ($user['EMPLOYEE_NO']==$userInfo['uID']) ? ' alt1':'' );
									echo '
									<span class="label'.(($key==$records['CURRENT_LAYER'])?' label-success':'').$class.'">'. $num . '. ' . $user['EMPLOYEE_NAME'] . ' (' . $user['E_MAIL_ADDR'] . ')</span></p>';
									$num++;
								}
								endforeach;
								?>
								</td>
							</tr>
						<?php endforeach?>
						</tbody>
						</table>
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
						<?php echo $records['DOCUMENTS_DESCRIPTION'];?>
						</div>
					</div>
				</div>
			</div><!--//accordion-group-->
			
		</div><!--//accordion-->

	</div><!--//metadata-->
	
	<div class="tab-pane active" id="versioning">
	<?php if($versioning):?>
	<table class="table table-condensed table-bordered">
	<tbody>
	<?php 
	$version = 0;	
	$i=count($versioning) - count($vers_min);	
	foreach($versioning as $key):
	if($version != $key['VERSION_ID']) {
		$ver = $key['VERSION_ID'];
		echo '<tr><th colspan="6" style="background:#eee;">'.$ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2]).'</th></tr>';
	}
	?>
	<tr>
		<td><?php echo $key['STEP_LAYER'] . '. ' . $config['step_layer'][ $key['STEP_LAYER'] ]?></td>
		<td><?php echo $key['EMPLOYEE_NAME']?>
		<?php if($records['DOCUMENTS_CBY']==$userInfo['uID']):?>
		&nbsp; <a href=":;" id="resendEmailBtn" data-email="<?php echo $key['E_MAIL_ADDR']?>" title="Resend Notification Email"><i class="fam-email-go"></i></a>
		<?php endif;?></td><td><?php echo($key['PROCESS_TYPE']==1)?' <span class="label label-info"> P </span>':' <span class="label label-warning"> S </span>';?></td>
		<td><?php echo '<i class="'.(($key['APPROVAL_STATUS']==ACTION_APPROVE) ? 'fam-accept' : 'fam-error' ).'"></i> '.$config['act_status'][ $key['APPROVAL_STATUS'] ]?>
		<?php						
		//$num = $key['PK_DOCUMENTS_STEP_ID'] != ;
		if($key['PROCESS_TYPE'] == 0){
		$total = count($vers_row) - $i;
		}else{
		$total = 1;		
		}		
	
		if($total < 0 || $total == 0 && $key['PROCESS_TYPE'] == 0)
		{		
		if( $key['PROCESS_TYPE'] == 0 && $key['APPROVAL_STATUS']!=ACTION_APPROVE)
		{			
			if( $make_approval && $key['EMPLOYEE_NO'] == $userInfo['uID'])		
			{																		
				echo '<a href=":;" id="commentBtn"><i class="fam-comment"></i> Mohon berikan approval</a>';
				$pro_type = 0;
			}						
		}		
		else
		{	
			if($key['APPROVAL_STATUS']!=ACTION_APPROVE && $key['APPROVAL_STATUS']!=ACTION_REJECT){			
				echo '<a><i class="fam-exclamation"></i> Belum bisa memberikan approval</a>';
			}elseif($key['APPROVAL_STATUS']==ACTION_APPROVE){
				echo '<a><i class="fam-bullet-go"></i>Telah Disetujui</a>';
			}else{
				echo '<a><i class="fam-cross"></i>Mengajukan Revisi</a>';
			}
		}
		if($make_approval)
		{
			$i--;
		}
		
		}
		elseif($key['PROCESS_TYPE'] == 1 && $key['APPROVAL_STATUS']!=ACTION_APPROVE && $key['EMPLOYEE_NO'] == $userInfo['uID'])
		{
			if($make_approval && $key['APPROVAL_STATUS']!=ACTION_REJECT)
			{
				echo '<a href=":;" id="commentBtn"><i class="fam-comment"></i> Mohon berikan approval</a>';		
				$pro_type = 1;
			}
			else
			{
				echo '<a><i class="fam-cross"></i>Mengajukan Revisi</a>';
			}
		}
		elseif($key['PROCESS_TYPE'] == 1 && $key['APPROVAL_STATUS']!=ACTION_APPROVE)
		{
			if($key['APPROVAL_STATUS']!=ACTION_REJECT)
			{
				echo '<a><i class="fam-exclamation"></i>Belum memberikan approval</a>';
			}else{
				echo '<a><i class="fam-cross"></i>Mengajukan Revisi</a>';
			}
		}
		elseif($key['PROCESS_TYPE'] == 1 && $key['APPROVAL_STATUS']==ACTION_APPROVE)
		{
			
			echo '<a><i class="fam-bullet-go"></i>Telah Disetujui</a>';

		}
		else
		{		
			echo '<a><i class="fam-exclamation"></i> Belum bisa memberikan approval</a>';
		}
		?>		
		</td>
		<td><?php echo ($key['APPROVAL_MAILED']) ? '<i class="fam-email"></i> ' . $key['APPROVAL_MAILED'] .'x' : '<i class="fam-comment"></i> Belum terkirim'?>
		</td>
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
	<?php echo '<p class=""><a id="commentBtn" href=":;" title="Berikan saran"><i class="fam-comment"></i> mau memberikan saran?</a></p>';?>
	<div class="accordion" id="accordion_comments">
		<?php 
		$version = 0;
		foreach($comments as $key):
		if($version != $key['VERSION_ID']) {
			$ver = $key['VERSION_ID'];
			echo '<h4 class="rev">'.$ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2]).'</h4>';
		}
		?>
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#cmt<?php echo $key['PK_DOCUMENTS_COMMENTS_ID']?>">
					<span class="pull-right font-disabled"><em><?php echo ' mengomentari ver.'. $ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : 'Revisi Ke - '. $ver[2]).', pada: ' . $key['COMMENTS_CDT']?></em></span>
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
	<p class="font-disabled">Belum ada komentar, <?php echo '<a id="commentBtn" href=":;" title="Berikan saran"><i class="fam-comment"></i> mau memberikan saran?</a>';?></p>
	<?php endif;?>
	</div><!--//comments-->
	
	<div class="tab-pane" id="files">
	<?php if($files):?>
	<table class="table table-condensed table-bordered">
	<tbody>
	<?php 
	$version = 0;
	foreach($files as $key):
	$version_formatted  = wordwrap($key['VERSION_ID'], 1, '.', true);
	if($version != $key['VERSION_ID']) {
		$ver = $key['VERSION_ID'];
		echo '<tr><th colspan="5" style="background:#eee;">'.$ver[0].'.'.$ver[1].(($ver[2] == 0)? NULL : ' Revisi Ke - '. $ver[2]).'</th></tr>';
		$num=1;
	}
	?>
	<tr>
		<td><?php echo $num?>.</td>
		<td><a href="<?php echo base_url()?>uploads/<?php echo $key['ATC_CBY']?>/<?php echo $key['ATC_RAWNAME']?>" target="_blank"><?php echo $key['ATC_ORIGNAME']?></a></td>
		<td><?php echo substr($key['ATC_SIZE']/1024, 0, 4);?> mb</td>
		<td><?php echo $key['ATC_CDT']?></td>
	</tr>
	<?php 	
	$version = $key['VERSION_ID'];
	$num++;
	endforeach;?>
	</tbody>
	</table>	
	<?php else:?>
	<p class="font-disabled">Belum ada file</p>
	<?php endif;?>
	</div><!--//files-->
	
</div><!--//tab-content-->


<div id="commentModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
	<h3 id="modalLabel"><i class="fam-comment"></i> Berikan Saran Anda</h3>
	</div>
	<div class="modal-body">
				
				<div id="formProcess"><img src="<?php echo base_url('assets/img/loader.gif')?>" id="preloader" style="display:none;margin:0 auto;text-align:center;"></div>
				<?php echo form_open('', array('class'=>'form-horizontal alt1', 'id'=>'commentForm'));?>
				
				<input name="dI" type="hidden" value="<?php echo $records['FK_DOCUMENTS_ID']?>">
				<input name="sL" type="hidden" value="<?php echo $records['STEP_LAYER']?>">
				<input name="vI" type="hidden" value="<?php echo $records['VERSION_ID']?>">
				<input name="cL" type="hidden" value="<?php echo $records['CURRENT_LAYER']?>">
				<input name="pT" type="hidden" value="<?php echo $pro_type?>">
				<input name="status" type="hidden" value="<?php echo $records['APPROVAL_STATUS']?>">
				
				
				<div class="control-group">
					<label class="control-label">Respon Anda <span class="important">*</span></label>
					<div class="controls">
						<label class="radio inline" title="Anda akan memberikan approval dokumen ini">
							<input type="radio" name="respon" id="respon" data-respon="Anda akan memberikan approval dokumen ini?" value="<?php echo ACTION_APPROVE?>" checked> <?php echo $config['act_status'][ACTION_APPROVE]?>
						</label> &nbsp; 
						<label class="radio inline" title="Anda akan menolak dokumen ini">
							<input type="radio" name="respon" id="respon" data-respon="Anda akan menolak dokumen ini?" value="<?php echo ACTION_REJECT?>"> <?php echo $config['act_status'][ACTION_REJECT]?>
						</label>
					<?php echo form_error('respon')?>
					</div>
				</div>
				
				
				<div class="control-group">
					<label class="control-label">Isi Pesan </label>
					<div class="controls">
					<textarea name="comment" id="comment" class="input-large" rows="8" placeholder="Isi dari saran Anda"></textarea>
					<?php echo form_error('comment')?>
					</div>
				</div>			
				
				<?php 
				echo form_close();
				?>	
	</div>
	<div class="modal-footer"> 
		<button class="btn btn-primary" id="submitComment" aria-hidden="true">Kirim komentar</button>
		<button class="btn" data-dismiss="modal" id="closeModal" aria-hidden="true">Tutup</button>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
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
	
	$("#submitComment").click(function(){
		var options = { 
			target: '#formProcess',
			beforeSubmit: validate,
			success: showResponse,
			url: '<?php echo site_url('inbox/action/'.$records['FK_DOCUMENTS_ID'].'/'.$records['VERSION_ID'].'/'.$records['STEP_LAYER'].'/'.$userInfo['uID'])?>',
			type: 'post',
			dataType: 'json'
		};		
		$('#commentForm').ajaxSubmit(options);
	});

	function validate(formData, jqForm, options) 
	{  
		var msg = '';
		var responField = $('input[name=respon]').fieldValue();
		var commentField = $('textarea[name=comment]').fieldValue();
		var statusField = $('input[name=status]').fieldValue();
		var cL = $('input[name=cL]').fieldValue();
		var sL = $('input[name=sL]').fieldValue();
	 
		if ( responField==false && statusField <= <?php echo ACTION_READ;?> && cL == sL) { 
			msg += 'Mohon isikan respon terhadap dokumen';
			$('#formProcess').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
			return false; 		
		} else if ( commentField==false && responField==3) { 
			msg += 'Mohon isikan pesan komentar Anda';
			$('#formProcess').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
			return false; 
		} else {
			$("#preloader").show();
			$('#formProcess').append('<p class="alert alert-info">Mohon sabar menunggu selagi sistem sedang menyelesaikan tugasnya.</p>');
			return true;
		}
	}
	
	function showResponse(responseText, statusText, xhr, $form)  
	{ 
		if( responseText.error > 0 )
		{
			$('#formProcess').html( '<div class="alert alert-error">' + responseText.message + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
			posted = false;
		}
		else
		{
			$("#commentForm, #submitComment").hide();
			$('#formProcess').html( '<div class="alert alert-success">' + responseText.message + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
			posted = true;
		}
		return responseText.error;
	}
	
});

</script>