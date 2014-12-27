<div class="page-header">
	<div class="pull-right">
		<?php if($records['process_status']==DOC_DRAFT || $records['process_status']==DOC_EDIT):?>
		<a href="<?php echo site_url('documents/e/'.$records['documents_id'])?>"><i class="fam-pencil"></i> Edit</a>
		<?php endif;?>
	</div>
	<h4><?php echo $records['documents_title'];?></h4>
</div>

<ul class="nav nav-tabs" id="myTab">
	<li><a href="#metadata" data-toggle="tab">Metadata</a></li>
	<li class="active"><a href="#versioning" data-toggle="tab">Versioning</a></li>
	<li><a href="#comments" data-toggle="tab">Comments</a></li>
	<li><a href="#files" data-toggle="tab">Files</a></li>
</ul>
 
<div class="tab-content">
	<div class="tab-pane" id="metadata">


<table class="table table-condensed alt1" id="xtable" style="border:solid 1px #eee;">
<tbody>
	<tr>
		<th width="120">Kategori Prosedur</th>
		<td><?php echo $records['categories_title'];?></td>
		<th width="100">Tgl. Terbit</th>
		<td><?php echo $records['documents_datepub'];?></td>
		<th width="200" style="border-left:solid 1px #eee;">Status</th>
	</tr>
	<tr>
		<th>User</th>
		<td><?php echo $records['fullname'].' ('.$records['users_email'].')';?></td>
		<th>Tgl. Buat</th>
		<td><?php echo $records['documents_cdt'];?></td>
		<td rowspan="3" style="border-left:solid 1px #eee;"><h2 style="text-align:center;margin-top:10px;"><?php echo $config['doc_status'][ $records['process_status'] ];?></h2></td>
	</tr>
	<tr>
		<th>Versi</th>
		<td><span class="label label-info"><?php echo wordwrap($records['version_id'],1,".",true);?></span></td>
		<th>Tgl. Update</th>
		<td><?php echo $records['documents_udt'];?></td>
	</tr>
	<tr>
		<th>Proses</th>
		<td><span class="label label-success"><?php echo ($records['current_layer'] && $is_step_final) ? $records['current_layer'] .'. ('. $config['step_layer'][$records['current_layer']].')' : 'Selesai';?></span></td>
		<th>Tgl. Pengesahan</th>
		<td><?php echo ($records['process_status']==DOC_CLOSED) ? 'Tgl Pengesahan' : '---';?></td>
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
						<?php foreach($config['step_layer'] as $key=>$val):?>
							<tr>
								<th width="200"><?php echo $key . '. ' . $val?></th>
								<td>
								<?php 
								$num = 1;
								foreach($penandatangan as $level=>$user):
								if($user['step_layer']==$key) {
									$class = ( ($user['users_id']==$userInfo['uID']) ? ' alt1':'' );
									echo '<p><img src="'.base_url('uploads/avatars/'.$user['users_avatar']).'" style="width:20px;height:20px;"> 
									<span class="label'.(($key==$records['current_layer'])?' label-success':'').$class.'">'. $num . '. ' . $user['fullname'] . ' (' . $user['users_email'] . ')</span>
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
						<?php echo $records['documents_description'];?>
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
	$version_formatted  = wordwrap($key['version_id'], 1, '.', true);
	if($version != $key['version_id']) {
		echo '<tr><th colspan="5" style="background:#eee;">'.$version_formatted.'</th></tr>';
	}
	?>
	<tr>
		<td><?php echo $key['step_layer'] . '. ' . $config['step_layer'][ $key['step_layer'] ]?></td>
		<td><?php echo $key['fullname']?></td>
		<td><?php echo '<i class="'.(($key['approval_status']==ACTION_APPROVE) ? 'fam-accept' : 'fam-error' ).'"></i> '.$config['act_status'][ $key['approval_status'] ]?></td>
		<td><?php echo ($key['approval_mailed']) ? '<i class="fam-email"></i> ' . $key['approval_mailed'] .'x' : '<i class="fam-comment"></i> Belum terkirim'?></td>
		<td><?php echo $key['approval_udt']?></td>
	</tr>
	<?php 
	$version = $key['version_id'];
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
		$version_formatted  = wordwrap($key['version_id'], 1, '.', true);
		if($version != $key['version_id']) {
			echo '<h4 class="">'.$version_formatted.'</h4>';
		}
		?>
			<div class="accordion-group">
				<div class="accordion-heading alt1">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_meta" href="#cmt<?php echo $key['comments_id']?>">
					<span class="pull-right font-disabled"><em><?php echo $key['fullname'] . ' mengomentari ver.'. $key['version_id'].', pada: ' . $key['comments_cdt']?></em></span>
					<?php echo $key['comments_title']?>
					</a>
				</div>
				<div id="cmt<?php echo $key['comments_id']?>" class="accordion-body collapse">
					<div class="accordion-inner notes">
						<div>
						<?php echo $key['comments_descriptions'];?>
						</div>
					</div>
				</div>
			</div><!--//accordion-group-->
		<?php 
		$version = $key['version_id'];
		endforeach?>
	</div><!--//accordion-group-->
	<?php else:?>
	<p class="font-disabled">Belum ada komentar<?php if($records['process_status']==DOC_DRAFT || $records['process_status']==DOC_EDIT):
	echo ', <a id="commentBtn" href=":;" title="Berikan saran"><i class="fam-comment"></i> mau memberikan saran?</a>';
	endif;?></p>
	<?php endif;?>
	</div><!--//comments-->
	
	<div class="tab-pane" id="files">
	<?php if($files):?>
	<table class="table table-condensed">
	<tbody>
	<?php 
	$version = 0;
	foreach($files as $key):
	$version_formatted  = wordwrap($key['version_id'], 1, '.', true);
	if($version != $key['version_id']) {
		echo '<tr><th colspan="5" style="background:#eee;">'.$version_formatted.'</th></tr>';
		$num=1;
	}
	?>
	<tr>
		<td><?php echo $num?>.</td>
		<td><a href="#"><?php echo $key['atc_origname']?></a></td>
		<td><?php echo $key['atc_size']?> mb</td>
		<td><?php echo $key['atc_cdt']?></td>
	</tr>
	<?php 	
	$version = $key['version_id'];
	$num++;
	endforeach;?>
	</tbody>
	</table>	
	<?php else:?>
	<p class="font-disabled">Belum ada file</p>
	<?php endif;?>
	</div><!--//files-->
	
</div><!--//tab-content-->