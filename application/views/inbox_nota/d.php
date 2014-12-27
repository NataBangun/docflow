<div class="page-header">
	<div class="pull-right">	
	
	</div>
	<h4><?php echo $records['HAL'];?></h4>
</div>

<ul class="nav nav-tabs" id="myTab">
	<li><a href="#metadata" data-toggle="tab">Info Dokumen</a></li>
	<li class="active"><a href="#versioning" data-toggle="tab">Versi</a></li>
	<li><a href="#comments" data-toggle="tab">Komentar</a></li>
</ul>
 
<div class="tab-content">
	<div class="tab-pane" id="metadata">
	<a class="btn btn-mini btn-info" href='<?php echo base_url('generate_pdf/nota/'.$records['FK_DOCUMENTS_ID']);?>' target='_blank'><i class="icon-white icon-eye-open"></i> Preview</a>

<table class="table table-condensed alt1 table-bordered" id="xtable" style="border:solid 1px #eee;">
<tbody>
	<tr>
		<th width="120">Kategori Nota</th>
		<td><?php echo $records['CATEGORIES_TITLE'];?></td>
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
		<td><?php echo $records['UPDATE_DATE'];?></td>
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
		<th>&nbsp;</th>
		<td>&nbsp;</td>
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
			
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#desc">Deskripsi/Catatan</a>
				</div>
				<div id="desc" class="accordion-body collapse">
					<div class="accordion-inner notes">
						<div>
						<?php echo $records['DESKRIPSI']->load();?>
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
	foreach($versioning as $key):
	//$version_formatted  = wordwrap($key['VERSION_ID'], 1, '.', true);
	if($version != $key['VERSION_ID']) {
		echo '<tr><th colspan="5" style="background:#eee;">'.$key['VERSION_ID'].'</th></tr>';
	}
	?>
	<tr>
		<td><?php echo $key['STEP_LAYER'] . '. ' . $config['nota_step_layer'][ $key['STEP_LAYER'] ]?></td>
		<td><?php echo $key['EMPLOYEE_NAME']?>
		<?php if($records['CREATE_BY']==$userInfo['uID']):?>
		&nbsp; <a href="javascript:;" id="resendEmailBtn" data-email="<?php echo $key['E_MAIL_ADDR']?>" title="Resend Notification Email"><i class="fam-email-go"></i></a>
		<?php endif;?></td>
		<td><?php echo($key['PROCESS_TYPE']==1)?' <span class="label label-info"> P </span>':' <span class="label label-warning"> S </span>';?></td>
		<td><?php echo '<i class="'.(($key['APPROVAL_STATUS']==ACTION_APPROVE) ? 'fam-accept' : 'fam-error' ).'"></i> '.$config['act_status'][ $key['APPROVAL_STATUS'] ]?>
		<?php
		if( $make_approval && $key['EMPLOYEE_NO'] == $userInfo['uID'] && $key['APPROVAL_STATUS']<=ACTION_READ)
		{
			echo '<a href="javascript:;" id="commentBtn"><i class="fam-comment"></i> Mohon berikan approval</a>';
		}		
		?>		
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
			endforeach; 
			?>
		</div><!--//accordion-group-->
	<?php else:?>
		<p class="font-disabled">Belum ada komentar</p>
	<?php endif;?>
	</div><!--//comments-->
	
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
				<input name="status" type="hidden" value="<?php echo $records['APPROVAL_STATUS']?>">
				<input name="ttl" type="hidden" value="<?php echo count($penandatangan)?>">
				
				
				<div class="control-group">
					<label class="control-label">Respon Anda <span class="important">*</span></label>
					<div class="controls">
						<label class="radio inline" title="Anda akan memberikan approval dokumen ini">
							<input type="radio" name="respon" id="respon" value="<?php echo ACTION_APPROVE?>" checked> <?php echo $config['act_status'][ACTION_APPROVE]?>
						</label> &nbsp; 
						<label class="radio inline" title="Anda akan menolak dokumen ini">
							<input type="radio" name="respon" id="respon" value="<?php echo ACTION_REJECT?>"> <?php echo $config['act_status'][ACTION_REJECT]?>
						</label>
					<?php echo form_error('respon')?>
					</div>
				</div>
				
				
				<div class="control-group">
					<label class="control-label">Isi Pesan</label>
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
		<button class="btn" data-dismiss="modal" id="closeModal" aria-hidden="true">Tutup</button>
		<button class="btn btn-primary" id="submitComment" aria-hidden="true">Kirim komentar</button>
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
			url: '<?php echo site_url('inbox_nota/action/'.$records['FK_DOCUMENTS_ID'].'/0/'.$records['STEP_LAYER'].'/'.$userInfo['uID'])?>',
			type: 'post',
			dataType: 'json'
		};		
		$('#commentForm').ajaxSubmit(options);
	});

	function validate(formData, jqForm, options) 
	{  
		var msg = '';
		var responField = $('input[name=respon]').fieldValue();	
		var statusField = $('input[name=status]').fieldValue();
		var cL = $('input[name=cL]').fieldValue();
		var sL = $('input[name=sL]').fieldValue();
	 
		if ( responField==false && statusField <= <?php echo ACTION_READ;?> && cL == sL) { 
			msg += 'Mohon isikan respon terhadap dokumen';
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