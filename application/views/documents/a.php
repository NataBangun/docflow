<ul class="breadcrumb">
  <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('documents')?>" class="btn btn-mini">Daftar Dokumen</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Posting Dokumen</a></li>
</ul>

<div class="page-header">
	<h4>Posting Dokumen</h4>
</div>

<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/magicsuggest.css')?>" rel="stylesheet">

<?php echo form_open_multipart(site_url('documents/insert'), array('class'=>'form-horizontal alt1', 'id'=>'xform'))?>
<div id="messageWrapper"></div>

<div class="control-group">
	<label class="control-label">Nomor Dokumen <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="no" id="no" class="span10" placeholder="ketikkan nomor dokumen" onkeypress="return check(event)" value="<?php echo $this->input->post('no')?>">		
		<br><?php echo '<span style="color:red;">'.form_error('no').'</span>'?>
	</div>	
</div>

<div class="control-group">
	<label class="control-label">Judul <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="title" id="title" class="span10" placeholder="ketikkan judul dokumen" value="<?php echo $this->input->post('title')?>">		
		<br><?php echo '<span style="color:red;">'.form_error('title').'</span>'?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Versi</label>
	<div class="controls">
		<input type="text" name="versi[]" class="span1" id="versi1" placeholder="" value="1" style="width:20px;">
		<input type="text" name="versi[]" class="span1" id="versi2" placeholder="" value="0" style="width:20px;">
		<input type="hidden" name="versi[]" class="span1" id="versi3" placeholder="" value="0" style="width:20px;">		
		<br><?php echo '<span style="color:red;">'.form_error('versi[]').'</span>'?>
	</div>	
</div>

<div class="control-group">
	<label class="control-label">Kategori Prosedur <span class="important">*</span></label>
	<div class="controls">
		<?php if($categories):?>
		<select name="categories" id="categories" data-placeholder="Pilih kategori">
		<option value="NULL">Pilih</option>
		<?php foreach($categories as $key=>$val):?>
		<?php if($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1):?>
		<div id="close" data-id="<?php echo '.'.$val['PK_CATEGORIES_ID'].', '?>"></div>
		<option value="<?php echo $val['PK_CATEGORIES_ID']?>"><?php echo $val['CATEGORIES_TITLE']?></option>
		<?php endif;?>
		<?php endforeach;?>
		</select>
		<?php else:?>
		<span class="important">Please tell administrators to fill some categories.</span>
		<?php endif;?>
		<?php echo form_error('categories')?>
	</div>
</div>

<?php $data =''; foreach($categories as $key=>$val): if($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1): $data .= '.'.$val['PK_CATEGORIES_ID'].',';endif; endforeach;?>
<div id="close" data-id="<?php echo trim($data, ", ");?>"></div>

<?php $datas =''; foreach($categories as $key=>$val): if($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1): $datas .= '#ms'.$val['PK_CATEGORIES_ID'].',';endif; endforeach;?>
<div id="close2" data-id="<?php echo trim($datas, ", ");?>"></div>

<div class="control-group">
	<label class="control-label">Tanggal Terbit <span class="important">*</span></label>
	<div class="controls">
	<div class="input-prepend">
		<span class="add-on btn disabled"><i class="fam-date"></i></span>
		<input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo ($this->input->post('datepub'))?$this->input->post('datepub'):NULL?>" readonly>
		<br><?php echo '<span style="color:red;">'.form_error('datepub').'</span>'?>		
	</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Lampiran</label>
	<div class="controls">
	<div class="input-prepend">
		<input type="file" accept="application/pdf" onchange="checkFile(this)"class="span3" name="files[]">
		<a href="#" class="btn btn-info" id="atch"><i class="fam-add"></i></a> &nbsp &nbsp <span class="label label-info"> file harus Pdf</span>
	</div>
	</div>
</div>
<div id="wraper-atch"></div>

<div class="control-group">
	<label class="control-label">Histori Perubahan</label>
	<div class="controls">
		<textarea name="descrip" id="desc" class="span10" rows="5" placeholder="Deskripsi atau catatan dokumen"></textarea>
		<br><?php echo '<span style="color:red;">'.form_error('desc').'</span>'?>
	</div>
</div>

<div class="clearfix"></div>
<h4 style="border-bottom: 1px solid #EDEDED; color: #000;">Didistribusikan kepada <span class="important">*</span></h4>
<div class="clearfix"></div>

<div class="control-group add-bar">
	<label class="control-label no-border"></label>	
	<div class="span11">
		<div class="input-append">
		<ul id="targetDist" style="margin-left:0;" class="no-bulets">
			<li>
			<input class="span5" id="appendedInputButton" style="float: left;" type="text" placeholder="distribusikan kepada" name="distribution[]">	
			<button class="btn btn-info" id="addDist" type="button"><i class="fam-add"></i></button>
			<br><?php echo '<span style="color:red;">'.form_error('distribution[]').'</span>'?>	
			</li>
		</ul>		
		</div>		
    </div>
</div>
<div class="clearfix"></div>

<?php foreach($process as $key=>$val):?>

<div class="control-group <?php echo $val['FK_CATEGORIES_ID']?>" style="display:none" id="close">
	<label class="control-label"><?php echo $val['PROCESS_NAME']?> <span class="important">*</span></label>
		<div class="controls">
		<input style="width:400px;" type="text" placeholder="ketikkan nama Penandatangan" id="ms<?php echo $key?>" name="penandatangan<?php echo $val['PROCESS_SORT']?>[]"/>
		
	</div>
</div>

<?php echo form_error('penandatangan'.$key)?>
<?php endforeach?>
<div id="form_P"></div>



<?php $data3 =''; foreach($process as $key=>$val): $data3 .= '#ms'.$key.','; endforeach;?>
<div id="close3" data-id="<?php echo trim($data3, ", ");?>"></div>
<div class="form-actions">
	<button type="submit" id="submitBtn" class="btn btn-primary data-loader" title="Simpan" data-loading="Sedang Menyimpan..."  >Simpan</button>
	<button type="reset" id="resetBtn" class="btn">Batal</button>
</div>

</form>
<?php $a = count($process)?>
<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/nicEdit.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.chained.min.js')?>"></script>
<script type="text/javascript">
   function checkFile(fieldObj)
    {
        var FileName  = fieldObj.value;
        var FileExt = FileName.substr(FileName.lastIndexOf('.')+1);
        var FileSize = fieldObj.files[0].size;
        var FileSizeMB = (FileSize/125485760).toFixed(2);

        if ( (FileExt.toLowerCase() != "pdf") || FileSize>125485760)
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
<script language="javascript" type="text/javascript">
function checkchar(entry){
	// Fungsi ini dipindahkan ke Code Igniter agar lebih mengikuti standar (20140704)
	return true; 
	
	// validChar='*\/?\'<>:'; //ok chars
	invalidChar = '\\/:*?"<>|';
	strlen = entry.length; //test string length
	if (strlen < 1) {
		alert('No dokumen belum diinput!');
		return false;
	}
	entry = entry.toUpperCase(); //case insensitive
	//Now scan for invalid characters
	for (idx=0; idx<strlen; idx++) {
		if (invalidChar.indexOf(entry.charAt(idx)) > -1) {
			alert("No dokumen berisi karakter yang tidak valid: " + invalidChar);
			return false;
		}
	}
	return true;
	// document.getElementById('no').value = '';
}  //end scan
</script>
<script language="javascript" type="text/javascript">
function check(e) {
	// Fungsi ini dipindahkan ke Code Igniter agar lebih mengikuti standar (20140704)
	return true;
	
	var keynum
	var keychar
	var numcheck
	// For Internet Explorer
	if (window.event)
	{
		keynum = e.keyCode
	}
	// For Netscape/Firefox/Opera
	else if (e.which)
	{
		keynum = e.which
	}
	keychar = String.fromCharCode(keynum)
	//List of special characters you want to restrict
	invalidChar = '\\/:*?"<>|';
	if (invalidChar.indexOf(keychar) > -1) 
	// if (keychar == "'" || keychar == "`" || keychar == "/" || keychar == "*" || keychar == ":" || keychar == "?"||keychar == ">" || keychar == "<"||keychar == "\\")
	{
		return false;
	}
	else {
		return true;
	}
}
</script>

<script type="text/javascript">
$(function() {
	
	var cat_pro = $('#close2').attr('data-id');
	var cat_pro2 = $('#close3').attr('data-id');
	console.log(cat_pro +','+ cat_pro2);
	//var val = '#ms1,#ms2,#ms3,#ms4,#ms5,#ms6,#ms7,#ms8,#ms9,#ms10,#ms11,#ms12,#ms13,#ms14,#ms15,#ms16,#ms17,#ms18,#ms19,#ms20,#ms21,#ms22,#ms23,#ms24,#ms25,#ms26,#ms27,#ms28,#ms29,#ms30';
	var ms1 = $(cat_pro +','+ cat_pro2).magicSuggest({
		width: 590,
		data: '<?php echo $name;?>'
	});	

	new nicEditor({iconsPath : '<?php echo base_url('assets/js/nicEditIcons-latest.gif')?>'}).panelInstance('desc'); 
	
	$("#datepub").datepicker({format: 'yyyy-mm-dd', weekStart: 1, noDefault: true});
	$("#categories").chosen({disable_search_threshold:10});
	$("#penandatangan20,#penandatangan21,#penandatangan22, #penandatangan23, #penandatangan24, #penandatangan25, #penandatangan26, #penandatangan27, #penandatangan28, #penandatangan29, #penandatangan30, #penandatangan31").chosen({width:"57%"}); 
	
	$("#resetBtn").click(function(e){
		$('#title, #desc, [id=userfile]').reset();
		e.preventDefault();
	});	
}); 

	$(".data-loader").click(function(e){
		if (checkchar($('#no').val())) {
			var load = $(this).attr('data-loading');
			$(this).text(load);
			//$('.data-loader').submit();
			//$("button[type=submit]").attr("disabled", "disabled");
			//$(this).prop('disabled',true);
			$('#xform').submit();
			$(this).enable(false);
		} else {
			e.preventDefault(); // cancel submit
		}
	});

function validate(formData, jqForm, options) 
{  
	var msg = '';
	var form = jqForm[0];
 
    if (! form.title.value ) { 
		msg += 'Mohon isikan judul pada dokumen.';
        $('#messageWrapper').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
        return false; 
    } else {
		return true;
	}
}

function showResponse(responseText, statusText, xhr, $form)  
{ 
	if(responseText.error=='1')
	{
		$('#messageWrapper').html( '<div class="alert alert-error">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
	}
	else
	{
		setInterval(function() {
			var link_ = "<?php echo site_url('documents/edit')?>/"+responseText.id;
			location.href=link_;
		}, 2000);	
		$('#messageWrapper').html( '<div class="alert alert-success">Berhasil membuat posting dokumen, lanjutkan dengan upload file.<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
	}
	
	return responseText.error;
}
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/magicsuggest.js')?>"></script>