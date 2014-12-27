<ul class="breadcrumb">
  <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('documents')?>" class="btn btn-mini">Daftar Dokumen</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Edit Dokumen</a></li>
</ul>

<div class="page-header">
	<div class="pull-right">	
	<?php if($records['PROCESS_STATUS'] == DOC_DRAFT) :
	// Jika dokumen baru, gunakan fungsi dist() untuk submit form
	echo form_open('', array('id'=>'distForm'))?>
	<input type="hidden" name="dI" value="<?php echo $records['PK_DOCUMENTS_ID']?>">
	<input type="hidden" name="dS" value="<?php echo $records['PROCESS_STATUS']?>">
	<input type="hidden" name="vI" value="<?php echo $records['VERSION_ID']?>">
	<a id="distBtn" class="btn btn-primary" href="javascript:;" title="Sosialisasikan Dokumen" data-confirm="Anda yakin akan mensosialisasikan Dokumen"><i class="fam-arrow-switch"></i> Submit</a>
	</form>
	<?php endif;
	// If revisi dokumen, gunakan fungsi commit() untuk submit form
	if($records['PROCESS_STATUS'] == DOC_EDIT) :
	echo form_open('', array('id'=>'commitForm'))?>
	<input type="hidden" name="dI" value="<?php echo $records['PK_DOCUMENTS_ID']?>">
	<input type="hidden" name="dS" value="<?php echo $records['PROCESS_STATUS']?>">
	<input type="hidden" name="vI" value="<?php echo $records['VERSION_ID']?>">
	<a id="commitBtn" href=":;" title="Sosialisasikan Dokumen" data-confirm="Anda yakin akan mensosialisasikan Dokumen"><i class="fam-arrow-switch"></i> Submit</a>
	</form>
	<?php endif;?>
	</div>
	<h4><?php echo $records['DOCUMENTS_NO']?> <a title="view merge" class="btn btn-mini btn-info" target="_blank" href="<?php echo site_url('documents/view/'.$records['PK_DOCUMENTS_ID'].'/'.$records['PK_DOCUMENTS_PROCESS_ID'])?>"><i class="icon-white icon-eye-open"></i> Preview</a></h4>
	<div class="clearfix" style="height: 10px;"></div>
</div>

<div id="messageWrapper">
</div>

<ul class="nav nav-tabs" id="myTab">
	<li class="active"><a href="#metadata" data-toggle="tab">Info Dokumen</a></li>
	<li class=""><a href="#files" data-toggle="tab">Dokumen Prosedur</a></li>
	<?php if($records['PROCESS_STATUS']==DOC_DRAFT || $records['PROCESS_STATUS']==DOC_EDIT):?>
	<li class="pull-right"><div class="alert"><strong style="color: #A67E39;">Jika ingin melakukan submit / sosialisasi silakan klik tombol submit diatas </strong></div></li>
	<?php endif;?>
</ul>
 
<div class="tab-content">
	<div class="tab-pane active" id="metadata">
	
<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/magicsuggest.css')?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('assets/js/magicsuggest.js')?>"></script>
<?php echo form_open_multipart(site_url('documents/update/'.$records['PK_DOCUMENTS_ID']), array('class'=>'form-horizontal alt1'))?>

<div class="control-group">
	<label class="control-label">Nomor Dokumen <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="no" id="no" class="span10" placeholder="ketikkan nomor dokumen" value="<?php echo $records['DOCUMENTS_NO']?>">
		<br><?php echo '<span style="color:red;">'.form_error('no').'</span>'?>
	</div>
</div>


<div class="control-group">
	<label class="control-label">Judul <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="title" id="title" class="span10" placeholder="ketikkan judul dokumen" value="<?php echo $records['DOCUMENTS_TITLE']?>">
		<br><?php echo '<span style="color:red;">'.form_error('title').'</span>'?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Versi</label>
	<div class="controls">
		<?php $versi = $records['VERSION_ID'];?>
		<input type="text" name="versi[]" class="span1" id="versi1" readonly value="<?php echo (isset($versi[0])) ? $versi[0]: NULL;?>" style="width:20px;">
		<input type="text" name="versi[]" class="span1" id="versi2" readonly value="<?php echo (isset($versi[1])) ? $versi[1]: NULL;?>" style="width:20px;">
		<input type="hidden" name="versi[]" class="span1" id="versi3" readonly value="<?php echo (isset($versi[2])) ? $versi[2]: NULL;?>" style="width:20px;">
		<br><?php echo '<span style="color:red;">'.form_error('versi[]').'</span>'?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Kategori prosedur</label>
	<div class="controls">
		<?php if($categories):?>
		<select name="categories" id="categories" data-placeholder="Pilih kategori">
		<option value=""></option>
		<?php foreach($categories as $key=>$val):?>
		<?php if($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1):?>
		<div id="close" data-id="<?php echo '.'.$val['PK_CATEGORIES_ID'].', '?>"></div>
		<option class="<?php echo $val['PK_CATEGORIES_ID']?>" value="<?php echo $val['PK_CATEGORIES_ID']?>"<?php echo ($records['PK_CATEGORIES_ID']==$val['PK_CATEGORIES_ID']) ? ' selected=selected' : NULL;?> data-select="<?php echo($records['PK_CATEGORIES_ID']==$val['PK_CATEGORIES_ID'])?'selected':'else_selected'; ?>"><?php echo $val['CATEGORIES_TITLE']?></option>
		<?php endif;?>
		<?php endforeach;?>
		</select>
		<?php else:?>
		<span class="important">Please tell administrators to fill some categories.</span>
		<?php endif;?>
		<br><?php echo '<span style="color:red;">'.form_error('categories').'</span>'?>
	</div>
</div>

<?php $data =''; foreach($categories as $key=>$val):$data .= '.'.$val['PK_CATEGORIES_ID'].', ';endforeach;?>
<div id="close" data-id="<?php echo trim($data, ", ");?>"></div>

<div class="control-group">
	<label class="control-label">Tanggal Terbit</label>
	<div class="controls">
	<div class="input-prepend">
		<span class="add-on btn disabled"><i class="fam-date"></i></span>
		<input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo ($records['DOCUMENTS_DATEPUB']) ? date('Y-m-d', strtotime($records['DOCUMENTS_DATEPUB'])):NULL?>" readonly> 
		<?php echo form_error('datepub')?>
	</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Lampiran</label>
	<div class="controls">
	<div class="input-prepend">
	<?php if($records['DOCUMENTS_ATC_NAME']):?>
		<?php $clear = substr($records['DOCUMENTS_ATC_NAME'], 0, -1);?>
		<?php $ex_name = explode(',', $clear);?>		
		<ul class="no-bulets" style="margin-left: 0;">
		
		<?php foreach($ex_name as $key=>$val):?>
			<li><span class="label label-info"><?php echo $val?></span> <a class="btn btn-mini btn-danger" href="<?php echo site_url('documents/d_lampiran/'.$records['PK_DOCUMENTS_ID'].'/'.$val)?>"><i class="fam-cancel"></i></a> &nbsp <a class="btn btn-mini btn-info" href="<?php echo base_url('uploads/lampiran_dokpro/'.$val)?>" target="_blank"> <i class="fam-zoom"></i></a> </li>
		<?php endforeach;?>		
		</ul>
		<?php endif;?>
		<input type="file" class="span3" name="files[]" id="files[]" accept="application/pdf" onchange="checkFile(this)">
		<a href="#" class="btn btn-info" id="atch"><i class="fam-add"></i></a> &nbsp &nbsp <span class="label label-info"> file harus Pdf</span>
		<input type="hidden" name="file_name" value="<?php echo $records['DOCUMENTS_ATC_NAME']?>">
	</div>
	</div>
</div>
<div id="wraper-atch"></div>
<div class="control-group">
	<label class="control-label">Histori Perubahan</label>
	<div class="controls">
		<textarea name="desc" id="desc" class="span10" rows="5" placeholder="Deskripsi atau catatan dokumen"><?php echo $records['DOCUMENTS_DESCRIPTION']?></textarea>
		<?php echo form_error('desc')?>
	</div>
</div>
<?php foreach($process as $key=>$val):?>
	<?php if($records['PK_CATEGORIES_ID']==$val['FK_CATEGORIES_ID']):?>
		<div class="control-group <?php echo $val['FK_CATEGORIES_ID']?>" <?php 
			echo ($records['PK_CATEGORIES_ID']==$val['FK_CATEGORIES_ID']) ? 'style="display:block"' : 'style="display:none"';
			?> id="close">
			<label class="control-label"><?php echo $val['PROCESS_NAME']?> <span class="important">*</span></label>
			<div class="controls">
				<input style="width:400px;" type="text" placeholder="ketikkan nama Penandatangan" id="ms<?php echo $key?>" 
					name="penandatangan<?php echo $val['PROCESS_SORT']?>[]" 
					value='<?php 					
					// $temp = array();
					// foreach($penandatangan as $a=>$b):		
						// if( $val['PROCESS_SORT'] == $b['STEP_LAYER']){
							// $temp[] = $b['EMPLOYEE_NAME'].' ('.$b['EMPLOYEE_NO'].')';
						// }
					// endforeach;	
					//echo json_encode($temp);
					?>'/>
			</div>
		</div>
	<?php endif;?>
<?php endforeach;?>

<div class="edt" style="display: none;">
	<div class="control-group">
		<label class="control-label">Pemeriksaan <span class="important">*</span></label>
		<div class="controls">
			<input style="width:400px;" type="text" placeholder="ketikkan nama Penandatangan" id="ms_e1" name="penandatangan1[]"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Menyetujui <span class="important">*</span></label>
		<div class="controls">
			<input style="width:400px;" type="text" placeholder="ketikkan nama Penandatangan" id="ms_e2" name="penandatangan2[]"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Pengesahan <span class="important">*</span></label>
		<div class="controls">
			<input style="width:400px;" type="text" placeholder="ketikkan nama Penandatangan" id="ms_e3" name="penandatangan3[]"/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Legalisir <span class="important">*</span></label>
		<div class="controls">
			<input style="width:400px;" type="text" placeholder="ketikkan nama Penandatangan" id="ms_e4" name="penandatangan4[]" />
		</div>
	</div>
</div>

<div class="clearfix"></div>
<h4 style="border-bottom: 1px solid #EDEDED; color: #000;">Didistribusikan kepada <span class="important">*</span></h4>
<div class="clearfix"></div>
<div class="control-group add-bar">
	<label class="control-label no-border"></label>	
	<div class="span11">
		<?php if($records['DOCUMENTS_DISTRIBUTION']):?>
			<?php $clears = rtrim($records['DOCUMENTS_DISTRIBUTION'], ', ');?>
			<?php $ex_dis = explode(',', $clears);?>
			<ul class="no-bulets" style="margin-left: 0;">		
				<?php foreach($ex_dis as $key=>$val):?>
					<li><span class="label label-info"><?php echo $val?></span> <a class="btn btn-mini btn-danger" href="<?php echo site_url('documents/d_dist/'.$records['PK_DOCUMENTS_ID'].'/'.$key)?>"><i class="fam-cancel"></i></a></li>
				<?php endforeach;?>			
			</ul>
		<?php endif;?>		
	</div>		
</div>

<input type="hidden" name="dist_name" value="<?php echo $records['DOCUMENTS_DISTRIBUTION']?>">
<div class="control-group add-bar">
	<label class="control-label no-border"></label>	
	<div class="span11">
		<div class="input-append">
		<ul id="targetDist" style="margin-left:0;" class="no-bulets">
			<li>
			<input class="span5" id="appendedInputButton" style="float: left;" type="text" placeholder="distribusikan kepada" name="distribution[]">	
			<button class="btn btn-info" id="addDist" type="button"><i class="fam-add"></i></button>

			</li>
		</ul>
		<br><?php echo '<span style="color:red;">'.form_error('distribution[]').'</span>'?>	
		</div>
    </div>
</div>

<div class="clearfix"></div>

<input type="hidden" name="documents_id" id="documents_id" value="<?php echo $records['PK_DOCUMENTS_ID']?>">

<?php if($records['PROCESS_STATUS']==DOC_DRAFT || $records['PROCESS_STATUS'] == DOC_EDIT):?>
<div class="form-actions">
	<button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang Menyimpan...">Simpan</button>
	<button type="reset" id="resetBtn" class="btn">Batal</button>
</div>
<?php endif;?>
</form>


	</div><!--//metadata-->

	<div class="tab-pane" id="files">

		<table class="table table-condensed" id="xtable">
		<thead>
			<tr>
				<th>No.</th>
				<th>Nama File</th>
				<th>Versi</th>				
				<th>#</th>
			</tr>
		</thead>
		<tbody>		
		<?php $name_img = '';?>
		<?php if($records['DOCUMENTS_ATC_SYSTEM']):$num=1;?>		
		<?php $doc_version = $records['VERSION_ID'];?>
			<tr>
				<td><?php echo $num?>.</td>	
				<td><?php echo $records['DOCUMENTS_ATC_SYSTEM']?></td>
				<td><?php echo $doc_version[0].'.'.$doc_version[1]?><?php echo ($doc_version[2] == 0)? NULL : ' Revisi Ke - '. $doc_version[2] ;?></td>					
				<td><a href="<?php echo base_url('uploads/'.$records['DOCUMENTS_CBY'].'/'.$records['DOCUMENTS_ATC_SYSTEM'])?>" target="_blank">View</a></td>	
			</tr>
			<?php $name_img .= $records['DOCUMENTS_ATC_SYSTEM']?>
		<?php $num++;?>
		<?php endif;?>
		</tbody>
		</table>
		
		<?php echo form_open_multipart(site_url('documents/upload'), array('class'=>'form-horizontal'));?>
		<div class="well well-small">
			<input type="hidden" name="documents_id" id="documents_id" value="<?php echo $records['PK_DOCUMENTS_ID']?>">
			<input type="hidden" name="img_name" id="img_name" value="<?php echo $name_img;?>">
			<input type="hidden" name="version_id" id="version_id" value="<?php echo $records['VERSION_ID']?>">
			<input type="hidden" name="uid" id="uid" value="<?php echo $userInfo['uID']?>">
			<input type="hidden" name="process_status" id="process_status" value="<?php echo $records['PROCESS_STATUS']?>">
			
			<input type="file" name="userfile" id="userfile">
			<div class="help help-block">
			<h5 style="text-decoration:underline;">CATATAN</h5>
			<span><?php echo 'File yang diperbolehkan : ' . str_replace('|', ' | ', UPLOADFILETYPE)?></span><br>
			<span><?php echo 'Maksimum file : ' . ini_get('upload_max_filesize')?></span>	
			</div>
		</div>
		
			<div class="form-actions">
				<button type="submit" class="btn btn-primary data-load" id="uploadBtn" data-loading="Sedang Menyimpan...">Upload</button>
				<button type="reset" class="btn" id="resetBtn">Batal</button>
			</div>
			
		<?php echo form_close();?>	
	
	</div><!--//files-->
	
</div><!--//tab-content-->

<!-- Modal -->
<div id="modal-process-draft" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
	<h3 id="modalLabel" class="important">Perhatian!</h3>
	</div>
	<div class="modal-body">
	<p>Dokumen akan didistribusikan kepada penandatangan, anda dapat <strong>mengunci</strong> dokumen dari proses verifikasi dengan cara mengunci dokumen.</p>
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Ya, Saya mengerti</button>
	</div>
</div>

<?php 
$pndtn = array(); 
foreach($penandatangan as $a=>$b): 
	$pndtn[] = $b['EMPLOYEE_NAME'].' ('.$b['EMPLOYEE_NO'].')';
endforeach;
$pndtn = implode(',', $pndtn);

$datas = array(); 
foreach($categories as $key=>$val): 
	if($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1): 
		$datas[] = '#ms'.$val['PK_CATEGORIES_ID'];
	endif; 
endforeach;
$datas = implode(',', $datas);

$data3 = array(); 
foreach($process as $key=>$val): 
	$data3[] = '#ms'.$key; 
endforeach;
$data3 = implode(',', $data3);
?>

<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/nicEdit.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.chained.min.js')?>"></script>
<script type="text/javascript">
   function checkFile(fieldObj)
    {
        var FileName  = fieldObj.value;
        var FileExt = FileName.substr(FileName.lastIndexOf('.')+1);
        var FileSize = fieldObj.files[0].size;
        var FileSizeMB = (FileSize/125485760).toFixed(2);

        if ( (FileExt != "pdf") || FileSize>125485760)
        {
            var error = "Tipe file : "+ FileExt+"\n\n";
            error += "Ukuran file: " + FileSizeMB + " MB \n\n";
            error += "Tipe file lampiran harus PDF dan tidak boleh lebih dari 125 MB.\n\n";
            alert(error);
            return false;
        }
        return true;
    }
</script>
<script type="text/javascript">
$(function() { 

	var dataSelection = <?php echo json_encode(explode(',', $name)); ?>;

	<?php
	foreach($process as $key=>$val) {
		if($records['PK_CATEGORIES_ID']==$val['FK_CATEGORIES_ID']) {
			?>

			$.ms<?php echo $key?> = $('#ms<?php echo $key?>').magicSuggest({
				width: 700,
				data: dataSelection
			});
			<?php
			foreach($penandatangan as $a=>$b):		
				if( $val['PROCESS_SORT'] == $b['STEP_LAYER']){
					echo "$.ms".$key.".setValue([\"".$b['EMPLOYEE_NAME'].' ('.$b['EMPLOYEE_NO'].')'."\"]);\r\n";
				}
			endforeach;	
		}
	}
	?>
	// var ms1 = $('<?php echo $datas.','.$data3;?>').magicSuggest({
		// width: 700,
		// data: dataSelection
	// });	
	
	var ms = '#ms_e1,#ms_e2,#ms_e3,#ms_e4';
	var mse = $(ms).magicSuggest({
		width: 700,
		data: dataSelection
	});	
	
	new nicEditor({iconsPath : '<?php echo base_url('assets/js/nicEditIcons-latest.gif')?>'}).panelInstance('desc'); 	

	$("#datepub").datepicker({format: 'yyyy-mm-dd', weekStart: 1, noDefault: true});
	$("#categories").chosen({disable_search_threshold:10});
	//$('#myTab a[href="#files"]').tab('show');
	 var ttd = $('#data-penandatangan').attr('data-id'); 
	 //$("'"+ttd+"'").chosen({width:"95%"}); 
	 $("#penandatangan20,#penandatangan21,#penandatangan22, #penandatangan23, #penandatangan24, #penandatangan25, #penandatangan26, #penandatangan27, #penandatangan28, #penandatangan29, #penandatangan30, #penandatangan31").chosen({width:"95%"}); 
	var curr_doc_status = '<?php echo $records['PROCESS_STATUS']?>';
	var doc_status = $("input[name=doc_status]").val();
	var pS = '<?php echo $records['PROCESS_STATUS']?>';
	
	if( pS == '<?php echo DOC_DRAFT?>')
	{
		$("#xform > input, #xform > textarea").attr('readonly');
	}

	$("#resetBtn").click(function(e){
		var link_ = "<?php echo site_url('documents')?>/";
		location.href=link_;
	});	
	
	var e_url = "<?php echo current_url()?>";
	var d_url = "<?php echo site_url('documents/edit/'.$records['PK_DOCUMENTS_ID'])?>";
	var statusVal = false;
		
	
	$("#distBtn").click(function(e){
		var data_c = $(this).attr('data-confirm');
		var cnf = confirm(data_c);
		if (cnf){
		$('#distForm').submit();
		}else{
			return false;
		}
		e.preventDefault();
	});
	
	/* $(".data-loader").click(function(e){
		var load = $(this).attr('data-loading');
		$(this).text(load);
		$("button[type=submit]").attr("disabled", "disabled");
		$('#distForm').submit();
		e.preventDefault();
	}); */
	
	$("#commitBtn").click(function(e){
		$('#commitForm').submit();
		e.preventDefault();
	});

	var optionsDist = { 
		target: '#messageWrapper',
		beforeSubmit: validateDist,
		success: showResponseDist,
		url: '<?php echo site_url('documents/dist')?>',
		type: 'post',
		dataType: 'json'
	}; 

	$('#distForm').ajaxForm(optionsDist); 

	var optionsDist = { 
		target: '#messageWrapper',
		beforeSubmit: validateDist,
		success: showResponseDist,
		url: '<?php echo site_url('documents/commit')?>',
		type: 'post',
		dataType: 'json'
	}; 

	$('#commitForm').ajaxForm(optionsDist); 

	var optionsUpload = { 
		target: '#messageWrapper',
		beforeSubmit: validateFiles,
		success: showResponseFiles,
		url: '<?php echo site_url('documents/upload')?>',
		type: 'post',
		dataType: 'json',
		clearForm: true
	};
	
	$('#uploadform').ajaxForm(optionsUpload); 	

	function validateDist(formData, jqForm, options) 
	{  
		$("#preloader").show();
		$('#formProcess').append('<p class="alert alert-info">Mohon sabar menunggu selagi sistem sedang menyelesaikan tugasnya.</p>');
		return true;
	}

	function showResponseDist(responseText, statusText, xhr, $form)  
	{ 
		if(responseText.error=='1')
		{
			$('#messageWrapper').html( '<div class="alert alert-error">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' );
		}
		else
		{
			setInterval(function(){
				location.href = "<?php echo site_url('documents/detail/'.$records['PK_DOCUMENTS_ID'])?>";
			}, 2000);
			$('#messageWrapper').html( '<div class="alert alert-success">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
		}
		
		return responseText.error;
	}

	function validate(formData, jqForm, options) 
	{  
		var msg = '';
		var form = jqForm[0];
	 
			if (! form.title.value ) { 
			msg += 'Mohon isikan judul pada dokumen.';
					$('#messageWrapper').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
					return false; 			
			} else {
			$("#preloader").show();
			$('#formProcess').append('<p class="alert alert-info">Mohon sabar menunggu selagi sistem sedang menyelesaikan tugasnya.</p>');
			return true;
		}
	}
		
	function create_field_file(fileId, fileName, versionId, fileSize, fileCdt)
	{
		var rowCount = $('#xtable > tbody > tr').length;
		if(rowCount!='undifined') {
			rowCount++;
		} else {
			rowCount = 0;
		}
		
		var viewLink = "<?php echo site_url('attachment/view')?>/"+fileId;
		var outputStr = '<tr>';
		outputStr += '<td>'+rowCount+'.</td>';
		outputStr += '<td>'+fileName+'</td>';
		outputStr += '<td>'+versionId+'</td>';
		outputStr += '<td>'+fileSize+' kb</td>';
		outputStr += '<td>'+fileCdt+'</td>';
		outputStr += '<td><a href=":;" id="viewBtn" data-rel="'+viewLink+'">View</a></td>';
		outputStr += '</tr>';
		
		return outputStr;
	}

	function validateFiles(formData, jqForm, options) 
	{  
		var msg = '';
		var fileType = '<?php echo UPLOADFILETYPE?>';
		fileType = fileType.split("|");
		var form = jqForm[0];
	 
			if (! form.documents_id.value ) { 
			msg += 'Mohon isikan Dokumen ID.';
					$('#messageWrapper').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
					return false; 
			}
	 
			if (! form.version_id.value ) { 
			msg += 'Mohon isikan Rev ID.';
					$('#messageWrapper').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
					return false; 
			}
		
		for ( var i = 0; i < form.length; i = i + 1 ) 
		{
			var str = form[ i ].name;
			var fieldValue = form[ i ].value;
			var strFileType = fieldValue.substr(-3);
			if( str.search('userfile')==0 && $.inArray(strFileType.toLowerCase(), fileType) < 0)
			{
				msg += 'Pastikan jenis lampiran diperbolehkan.';
				$('#messageWrapper').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
						return false; 
			}		
		}
	}

	function showResponseFiles(responseText, statusText, xhr, $form)  
	{
		if(responseText.error=='1')
		{
			$('#messageWrapper').html( '<div class="alert alert-error">'+responseText.response+'.<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
		}
		else
		{
			var output = responseText.response;
			var atcCount = $("#atcCount").text();
			$("#atcCount").text( parseInt(atcCount)+1 );
			//var responseText = JSON.parse(responseText);
			$("#xtable tbody").append( create_field_file(output.fileId, output.fileName, output.versionId, output.fileSize, output.fileCdt) );
			$('#messageWrapper').html( '<div class="alert alert-success">Berhasil menambahkan '+output.fileName+'.<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 		
		}
	}
	
}); 	

</script>