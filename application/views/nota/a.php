
<ul class="breadcrumb">
  <li class="btn-back"><a href="<?php echo site_url('nota')?>" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('nota')?>" class="btn btn-mini" onclick="localStorage.clear();">Daftar Nota Dinas</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled" onclick="localStorage.clear();">Posting Nota Dinas</a></li>
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
		<input type=hidden id ="selection" name="selection" value="">
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
		<label class="control-label">Kepada</label>
		<div class="controls">
			<ul id="kpdlist" style="margin-left:0;" class="no-bulets">
			<li>
				<input class="span5 listkepada" id="appendedInputButton" style="float: left;" type="text" maxlength="100" placeholder="Ketikkan Kepada" value="<?php echo set_value('kepada[]');?>" name="kepada[]" >	
				<button class="btn btn-info" id="addKpd" type="button" onclick="k++"><i class="fam-add"></i></button>
				<br><?php echo '<span style="color:red;">'.form_error('kepada[]').'</span>'?>	
			</li>
		</ul>	
		<ul id="targetKpd" style="margin-left:0;" class="no-bulets">
		
		</ul>		
			<span class="alert alert-info" style="font-size:11px;">
				<i class="fam-information"></i> Redaksional tujuan/sasaran Nota Dinas, misal : Tim Pengembangan Aplikasi, Procurement Manager, dsb...
			</span>	
			<br /><br />
		</div>		    
	</div>
	<div class="clearfix"></div>

    <div>
    </div>
		
	<div class="control-group">
		<label class="control-label"><span class="important">*</span></label>
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
<br><?php echo '<span style="color:red;">'.form_error('kepada[]').'</span>'?><br />
			<span class="alert alert-info" style="font-size:11px">
				<i class="fam-information"></i> Daftar penerima Nota Dinas (orang-orang yg menerima Nota Dinas)
			</span>	
			<br /><br />
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
			<input type="text" name="hal" id="hal" class="span10" maxlength="100" placeholder="ketikkan Hal" value="<?php echo set_value('hal');?>">
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
			<input type="text" name="tempat" id="tempat" class="span3" placeholder="cth.Jakarta" value="<?php echo set_value('tempat'); ?>">
			<br><?php echo '<span style="color:red;">'.form_error('tempat').'</span>'?>		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Tanggal Nota Dinas</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on btn disabled"><i class="fam-date"></i></span>
				<input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo ($this->input->post('datepub'))?$this->input->post('datepub'):NULL?>" readonly>
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
			
			<?php 
				if ($val['PROCESS_NAME'] == 'Pengesahan Kanan') {
				echo '<label class="control-label">'.$val['PROCESS_NAME'].'<span class="important">*</span></label>';
			}else{
							echo '<label class="control-label">'.$val['PROCESS_NAME'].'</label>';
						}
			?>
			

			
	<?php 
				if ($val['PROCESS_NAME'] == 'Pengesahan Kanan') {
?>				
<div class="controls">
				<select name="pengesahan_<?php echo $val['PROCESS_SORT']?>" class="pengesahan_<?php echo $val['PROCESS_SORT']?> pengesahankanan" id="pengesahan_<?php echo $val['FK_CATEGORIES_ID']?>" data-placeholder="Pilih <?php echo $val['PROCESS_NAME']?>" multiple>
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

				<?php
			}else if ($val['PROCESS_NAME'] == 'Pengesahan Tengah') {
			?>
<div class="controls">
				<select name="pengesahan_<?php echo $val['PROCESS_SORT']?>" class="pengesahan_<?php echo $val['PROCESS_SORT']?> pengesahantengah" id="pengesahan_<?php echo $val['FK_CATEGORIES_ID']?>" data-placeholder="Pilih <?php echo $val['PROCESS_NAME']?>" multiple>
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
		<?php
			}else if ($val['PROCESS_NAME'] == 'Pengesahan Kiri') {
			?>
<div class="controls">
				<select name="pengesahan_<?php echo $val['PROCESS_SORT']?>" class="pengesahan_<?php echo $val['PROCESS_SORT']?> pengesahankiri" id="pengesahan_<?php echo $val['FK_CATEGORIES_ID']?>" data-placeholder="Pilih <?php echo $val['PROCESS_NAME']?>" multiple>
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
			<?php		}
			?>

		</div>
	<?php endforeach?>

	<div class="control-group">		
		<div class="controls">
			<br><?php echo '<span style="color:red;">'.form_error('pengesahan_1').'</span>'?>
		</div>
	</div>

	<div class="control-group">		
	<label class="control-label">Tembusan <span class="important">*</span></label>
		<div class="controls">
		<ul id="targetTmb" style="margin-left:0;" class="no-bulets">
			<li>
				<input class="span5" id="appendedInputButton" style="float: left;" type="text" maxlength="100" placeholder="Ketikkan Tembusan" name="tembusan1[]" value="<?php echo set_value('tembusan1[]'); ?>" >	
				<button class="btn btn-info" id="addTmb" type="button"><i class="fam-add"></i></button>
				<br><?php echo '<span style="color:red;">'.form_error('tembusan1[]').'</span>'?>	
			</li>
		</ul>
		<span class="alert alert-info" style="font-size:11px;">
			<i class="fam-information"></i> Redaksional tembusan Nota Dinas (yang tercetak di Nota Dinas)
		</span>	
		<br /><br />
		</div>		    
	</div>
	
	<div class="clearfix"></div>

	<div class="control-group">
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
			<?php echo '<br /><span style="color:red;">'.form_error('tembusan1[]').'</span>'?>	
			<br />
			<span class="alert alert-info" style="font-size:11px; margin-top:15px">
				<i class="fam-information"></i> Daftar tembusan Nota Dinas (orang-orang yg menerima tembusan Nota Dinas)
			</span>
			<br /><br />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Pembuat Konsep</label>
		<div class="controls">
			<select name="pembuat_konsep[]" id="pembuat_konsep" multiple="multiple" data-placeholder="Pilih Pembuat Konsep" >
				<?php if($users_nota_pembuat_konsep):?>
					<?php foreach($users_nota_pembuat_konsep as $key=>$val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>">
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['PEMBUAT_KONSEP'].')' ?>
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
			<p style="color:red;font-style:italic;margin-top:15px;">
			<b>Keterangan :</b><br />
			Type file yang boleh diupload : *.pdf, maksimal size : 8 MB
		</p>
		</div>
		
	</div>

	<div class="form-actions">
		<button type="submit" id="submitBtn" class="btn btn-primary data-load" onclick="simpankuki()" title="Simpan" >Simpan</button>
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
<script>
var k = 0;
$('#kepada1').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('#kepada1 option').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('kepada', JSON.stringify(selected));
});
 
// check for stored motivations
var kepada = JSON.parse(localStorage.getItem('kepada'));
if (kepada !== null) {
	$('#kepada1 option').each(function() {
		for (var i = 0; i < kepada.length; i++) {
			if (this.value == kepada[i]) {
				this.selected = true;
			}
		}
	});
}


$('#close-kpd').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('#close-kpd input').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('kpdnya', JSON.stringify(selected));
});
 
// check for stored motivations
var kepadanya = JSON.parse(localStorage.getItem('kpdnya'));
if (kepadanya !== null) {
	$('#close-kpd input').each(function() {
		for (var i = 0; i < kepadanya.length; i++) {
			if (this.value == kepadanya[i]) {
				this.selected = true;
			}
		}
	});
}




$('#tembusan').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('#tembusan option').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('tembusan', JSON.stringify(selected));
});
 
// check for stored motivations
var tembusan = JSON.parse(localStorage.getItem('tembusan'));
if (tembusan !== null) {
	$('#tembusan option').each(function() {
		for (var i = 0; i < tembusan.length; i++) {
			if (this.value == tembusan[i]) {
				this.selected = true;
			}
		}
	});
}

$('#pembuat_konsep').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('#pembuat_konsep option').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('pembuat_konsep', JSON.stringify(selected));
});
 
// check for stored motivations
var pembuat_konsep = JSON.parse(localStorage.getItem('pembuat_konsep'));
if (pembuat_konsep !== null) {
	$('#pembuat_konsep option').each(function() {
		for (var i = 0; i < pembuat_konsep.length; i++) {
			if (this.value == pembuat_konsep[i]) {
				this.selected = true;
			}
		}
	});
}



$('.pengesahankanan').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('.pengesahankanan option').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('pengesahankanan', JSON.stringify(selected));
});
 
// check for stored motivations
var pengkanan = JSON.parse(localStorage.getItem('pengesahankanan'));
if (pengkanan !== null) {
	$('.pengesahankanan option').each(function() {
		for (var i = 0; i < pengkanan.length; i++) {
			if (this.value == pengkanan[i]) {
				this.selected = true;
			}
		}
	});
}

$('.pengesahantengah').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('.pengesahantengah option').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('pengesahanktengah', JSON.stringify(selected));
});
 
// check for stored motivations
var pengtengah = JSON.parse(localStorage.getItem('pengesahanktengah'));
if (pengtengah !== null) {
	$('.pengesahantengah option').each(function() {
		for (var i = 0; i < pengtengah.length; i++) {
			if (this.value == pengtengah[i]) {
				this.selected = true;
			}
		}
	});
}

$('.pengesahankiri').change(function() {
	var selected = []; // create an array to hold all currently selected motivations
 
	// loop through each available motivation
	$('.pengesahankiri option').each(function() {
		// if it's selected, add it to the array above
		if (this.selected) {
			selected.push(this.value);
		}
	});
 
	// store the array of selected options
	localStorage.setItem('pengesahankiri', JSON.stringify(selected));
});
 
// check for stored motivations
var pengkiri = JSON.parse(localStorage.getItem('pengesahankiri'));
if (pengkiri !== null) {
	$('.pengesahankiri option').each(function() {
		for (var i = 0; i < pengkiri.length; i++) {
			if (this.value == pengkiri[i]) {
				this.selected = true;
			}
		}
	});
}

var r;
 $('body').on('click', '#addKpd', function () {
        $('#targetKpd').append('<li id="close-kpd" style="margin-top: 5px;"><input class="span5" id="appendedInputButton" type="text" name="kepada[]"  placeholder="Ketikan Kepada"  onchange="simpanlistdaftar(this);"><button class="btn btn-danger remove"  onclick="k--" type="button"><i class="fam-cancel"></i></button></li>');
    });
	
//$(document).on('change','.listkepada',function(){
//    alert('Id:'+this.id+'\nName:'+this.name+'\nValue:'+this.value);
//});
	



	    $('body').on('click', '.remove', function (e) {
            $(this).closest("#close-kpd").remove();
            e.preventDefault();
        });
		


</script>

<script type="text/javascript">
    function simpanlistdaftar(sel) {
	sel.setAttribute('value', sel.value);
    }
</script>

<script type="text/javascript">
document.getElementById("klasifikasi").value = localStorage.getItem("kelasin");
document.getElementById("dari").value = localStorage.getItem("darimana");
document.getElementById("targetKpd").innerHTML = localStorage.getItem("listkepada");

function simpankuki() {
    var kelas = document.getElementById("klasifikasi").value;
	var dari = document.getElementById("dari").value;
	var listkepada = document.getElementById("targetKpd").innerHTML;
    localStorage.setItem("kelasin", kelas);
    localStorage.setItem("darimana", dari);
	localStorage.setItem("listkepada", listkepada);
    return true;
}
</script>


<script type="text/javascript">
   function checkFile(fieldObj)
    {
        var FileName  = fieldObj.value;
        var FileExt = FileName.substr(FileName.lastIndexOf('.')+1);
        var FileSize = fieldObj.files[0].size;
        var FileSizeMB = (FileSize/8000001).toFixed(2);

        if ( (FileExt != "pdf") || FileSize>8000001)
        {
            var error = "Tipe file lampiran harus PDF dan tidak boleh lebih dari 8 MB.\n.";
			fieldObj.value='';
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
	
			var len = 1000;    
$(".mce-content-body").keydown(function () {
    if($(".mce-content-body").html().length>len){
        var string = $('.mce-content-body').html();
        $('.mce-content-body').html(string.substring(0, len));
        placeCaretAtEnd($('.mce-content-body').get(0));
    }
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
				disabled: false,
				value:'Loading...'
			});
		});
	}

</script>
	

<script type="text/javascript" src="<?php echo base_url('assets/js/magicsuggest.js')?>"></script>
