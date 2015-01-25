
<ul class="breadcrumb">
  <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('nota')?>" class="btn btn-mini">Daftar Nota Dinas</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Posting Nota Dinas</a></li>
</ul>

<div class="page-header">
	<h4>Posting Nota Dinas.</h4>
</div>

<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/magicsuggest.css')?>" rel="stylesheet">

<!-- start form -->
<?php echo form_open_multipart(site_url('nota/insert'), array('class'=>'form-horizontal form-inline alt1', 'id'=>'xform'))?>
	<div id="messageWrapper"></div>
	<?php if($categories):?>
		<?php $idCat = '';?>
		<select name="categories" data-placeholder="Pilih kategori" style="display:none;">
			<option value="0">Pilih</option>
			<?php foreach($categories as $key=>$val):?>
				<?php if($val['FK_TYPE_ID'] == 2):?>
					<?php $idCat = $val['PK_CATEGORIES_ID'];?>
					<option value="<?php echo $val['PK_CATEGORIES_ID']?>" selected>
						<?php echo $val['CATEGORIES_TITLE']?>
					</option>
				<?php endif;?>
			<?php endforeach;?>
		</select>
	<?php else:?>
		<span class="important">Please tell administrators to fill some categories.</span>
	<?php endif;?>

	<div class="control-group">
		<label class="control-label">Klasifikasi Informasi</label>
		<div class="controls">
			<?php if($users_nota_klasifikasi):?>
				<select name="klasifikasi" id="klasifikasi" data-placeholder="Pilih klasifikasi">
					<option value=""></option>
					<?php foreach($users_nota_klasifikasi as $key=>$val):?>
						<option value="<?php echo $val['PK_KLASIFIKASI_ID']?>">
							<?php echo $val['DESKRIPSI']?>
						</option>
					<?php endforeach;?>
				</select>
			<?php else:?>
				<span class="important">Please tell administrators to fill some categories.</span>
			<?php endif;?>
			<?php echo form_error('klasifikasi')?>
		</div>
	</div>

	<div class="control-group">		
		<label class="control-label">Kepada <span class="important">*</span></label>
		<div class="controls">
		<span style="color:grey;font-style:italic;margin-top:15px;">
					Redaksional tujuan/sasaran Nota Dinas, misal : Tim Pengembangan Aplikasi, Procurement Manager, dsb...
				</span>
		<ul id="targetKpd" style="margin-left:0;" class="no-bulets">
			<li>				
				<input class="span5" id="appendedInputButton" style="float: left;" type="text" placeholder="Ketikkan Kepada" name="kepada[]">	
				<button class="btn btn-info" id="addKpd" type="button"><i class="fam-add"></i></button>		
				<br><?php echo '<span style="color:red;">'.form_error('kepada[]').'</span>'?>	
			</li>
		</ul>		
		</div>		    
	</div>
	<div class="clearfix"></div>
		
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">        
			<select name="kepada1[]" id="kepada1" multiple="multiple" data-placeholder="Pilih Kepada" >
				<?php if($users_nota_kepada):?>
					<?php foreach($users_nota_kepada as $key=>$val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>">
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['KEPADA'].')' ?>
						</option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<br><?php echo '<span style="color:red;">'.form_error('kepada[]').'</span>'?>		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Dari<span class="important">*</span></label>
		<div class="controls">
			<select name="dari" id="dari" data-placeholder="Pilih Dari" >
				<option value="0">Pilih Dari</option>
				<?php if($users_nota_dari):?>
					<?php foreach($users_nota_dari as $val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>">
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['DARI'].')' ?>
						</option>
					<?php endforeach;?>
				<?php endif;?>
			</select>		
			<br><?php echo '<span style="color:red;">'.form_error('dari').'</span>'?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Hal<span class="important">*</span></label>
		<div class="controls">
			<input type="text" name="hal" id="hal" class="span10" placeholder="ketikkan Hal" value="<?php echo $this->input->post('hal')?>">
			<br><?php echo '<span style="color:red;">'.form_error('hal').'</span>'?>		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Isi<span class="important">*</span></label>
		<div class="controls">
			<textarea name="desc" id="desc" class="span10" rows="5" placeholder="isi Nota Dinas"><?php echo set_value('desc'); ?></textarea>		
			<br><?php echo '<span style="color:red;">'.form_error('desc').'</span>'?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Tempat<span class="important">*</span></label>
		<div class="controls">
			<input type="text" name="tempat" id="tempat" class="span3" placeholder="cth.Jakarta" value="<?php echo $this->input->post('tempat')?>">
			<br><?php echo '<span style="color:red;">'.form_error('tempat').'</span>'?>		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Tanggal Nota Dinas</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on btn disabled"><i class="fam-date"></i></span>
				<input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo ($this->input->post('date_publish'))?$this->input->post('datepub'):NULL?>" readonly>
				<br><?php echo '<span style="color:red;">'.form_error('datepub').'</span>'?>
			</div>
		</div>
	</div>

	<?php foreach($process as $key=>$val):?>
		<?php 
			if (!($val['FK_CATEGORIES_ID'] == $idCat)) {
				continue;
			}
		?>
		<div class="control-group <?php echo $val['FK_CATEGORIES_ID']?>" <?php echo ($val['FK_CATEGORIES_ID'] == $idCat) ? 'style="display:block"' : 'style="display:none"';?> id="close">
			<label class="control-label"><?php echo $val['PROCESS_NAME']?></label>
			<div class="controls">
				<select name="pengesahan_<?php echo $val['PROCESS_SORT']?>" class="pengesahan_<?php echo $val['PROCESS_SORT']?>" id="pengesahan_<?php echo $val['FK_CATEGORIES_ID']?>" data-placeholder="Pilih <?php echo $val['PROCESS_NAME']?>" multiple>
					<option value=""></option>
					<?php if($users_nota_pengesahan):?>
						<?php foreach($users_nota_pengesahan as $key=>$val):?>
							<option value="<?php echo $val['EMPLOYEE_NO']?>"><?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['PENGESAHAN'].')'?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('pengesahan_'.$key)?>		
				<?php echo form_error('posisi_'.$key)?>
			</div>
		</div>
	<?php endforeach?>

	<div class="control-group">		
		<label class="control-label"></label>
		<div class="controls">
			<br><?php echo '<span style="color:red;">'.form_error('pengesahan_1').'</span>'?>
		</div>
	</div>

	<div class="control-group">		
	<label class="control-label">Tembusan</label>
		<div class="controls">
		<ul id="targetTmb" style="margin-left:0;" class="no-bulets">
			<li>
				<input class="span5" id="appendedInputButton" style="float: left;" type="text" placeholder="Ketikkan Tembusan" name="tembusan1[]">	
				<button class="btn btn-info" id="addTmb" type="button"><i class="fam-add"></i></button>
				<br><?php echo '<span style="color:red;">'.form_error('tembusan1[]').'</span>'?>	
			</li>
		</ul>		
		</div>		    
	</div>
	
	<div class="clearfix"></div>

	<div class="control-group">
		<label class="control-label"><span class="important">*</span></label>
		<div class="controls">
			<select name="tembusan[]" id="tembusan" multiple="multiple" data-placeholder="Pilih Tembusan" >
				<?php if($users_nota_tembusan):?>
					<?php foreach($users_nota_tembusan as $key=>$val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>">
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['TEMBUSAN'].')' ?>
						</option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<br><?php echo '<span style="color:red;">'.form_error('tembusan1[]').'</span>'?>		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Pembuat Konsep</label>
		<div class="controls">
			<select name="pembuat_konsep[]" id="pembuat_konsep" multiple="multiple" data-placeholder="Pilih Inisial" >
				<?php if($users_nota_pengesahan):?>
					<?php foreach($users_nota_pengesahan as $key=>$val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>">
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['PENGESAHAN'].')' ?>
						</option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<?php echo form_error('initial')?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Lampiran</label>
		<div class="controls">
			<input type="file"  accept=".pdf" onchange="checkFile(this)"name="lampiran" id="lampiran"/>
		</div>
	</div>

	<div class="form-actions">
		<button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang Menyimpan...">Simpan</button>
		<button type="reset" id="resetBtn" class="btn">Batal</button>
	</div>

</form>
<!-- end form -->

<?php 
	$data =''; 
	if($users_nota_pengesahan): 
		foreach($users_nota_pengesahan as $key=>$val): 
			$data .= $val['EMPLOYEE_NAME'].' ('.$val['PENGESAHAN'].'),'; 
		endforeach; 
	endif;
?>
<div id="DP" data-pengesahan="<?php echo trim($data, ", ");?>"></div>
<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/tinymce.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
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
            error += "Tipe file lampiran harus PDF dan tidak boleh lebih dari 125 MB.\n.";
			document.getElementById('lampiran').value=''
            alert(error);
            return false;
        }
        return true;
    }
</script>
<!-- mencegah user menginput special character di field
<script type="text/javascript">
$('input:text').keydown(function(e){
  if(e.keyCode>=65 && e.keyCode<=90) { return true; } else if(e.keyCode>=48 && e.keyCode<=57) { return true; } else if(e.keyCode>=97 && e.keyCode<=122) { return true; } else if(e.keyCode == 8) { return true; }else if(e.keyCode == 9) { return true; }else  { return false;
}})
    </script>
-->
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		element: "#desc",
		theme: "modern",
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table contextmenu directionality responsivefilemanager",
			"emoticons template paste textcolor"
		],
		relative_urls: false,
		remove_script_host: false,
		toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent link image",
		image_advtab: true,
	//    templates: [
	//        {title: 'Test template 1', content: 'Test 1'},
	//        {title: 'Test template 2', content: 'Test 2'},
	//    ],
		external_filemanager_path:"<?php echo base_url('assets/filemanager').'/'?>",
		filemanager_title:"Pilih Gambar" ,
		filemanager_nik:"<?php echo $userInfo['uID'];?>",
		external_plugins: { "filemanager" : "<?php echo base_url('assets/filemanager/plugin.js')?>"}		
	});
</script>
<script type="text/javascript">

	$(function() 
	{       
		var DP = $('#DP').attr('data-pengesahan');
		var ms1 = $('#ms_e1,#ms_e2,#ms_e3').magicSuggest({
			maxSelection: 1,
			width: 590,
			data: DP
		});
	
		$(".pengesahan_1,.pengesahan_2,.pengesahan_3").chosen({
			max_selected_options : 1,
			width:"60%"
		});
		
		$("#klasifikasi").chosen({disable_search_threshold:10}); 
		$("#datepub").datepicker({format: 'yyyy-mm-dd', weekStart: 1, noDefault: true});
		$("#posisi_1,#posisi_2,#posisi_3").chosen({disable_search_threshold:10,width:"10%"});
		$("#categories,#pilihan_lembar").chosen({disable_search_threshold:10});
		$("#dari").chosen({width:"60%"});
		$("#tembusan").chosen({width:"80%"});
		$("#paraf").chosen({width:"80%"});
		$("#pembuat_konsep").chosen({width:"80%"});
		$("#kepada1").chosen({width:"60%"});
		
		$("#resetBtn").click(function(e){
			var link_ = "<?php echo site_url('nota')?>/";
			location.href=link_;
		});	
		$("#desc").val(<?php $this->input->post('desc')?>);
	});

	function cek_no_surat()
	{
		/*
		* cek nosurat berdasarkan no surat
		*/

		var url_send ='<?php echo site_url('nota/cek_no_surat')?>';
		var n1 = $('#no_surat1');
		var n2 = $('#no_surat2');
		var n3 = $('#no_surat3');
		var n = $('#no_surat');
		var jav = false;


		//function
		if(
			(n1.val().length == 3)&&
			(n2.val().length == 5)&&
			(n3.val().length == 4))
		{
			no_surat = (n1.val()+'/ND/'+n2.val()+'/'+n3.val());
			n.val(no_surat);
			$.ajax( {
				type:"POST",
				url: url_send,
				data:{
					"no_surat" : no_surat
				},
				complete:function(response, statusText){
					var res = response.responseText;
						if (res>=1) {
							$('#preview_no_surat').html( 'No Nota Sudah Terdaftar.' );
							$('#preview_no_surat').attr({
								'class':'label label-important'
							});
						} else {
							$('#preview_no_surat').html( res );
							$('#preview_no_surat').attr({
								'class':'label label-info'
							});
						}
					}
			});
		} else {
			n.val('');
			n.attr({
				placeholder:'Silakan melengkapi No Surat'
			});
		}
	
		$( document ).ajaxStart(function() {
			$( "#submitBtn" ).prop({
				disabled: true,
				value:'Loading...'
			});
		});
	}

</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/magicsuggest.js')?>"></script>