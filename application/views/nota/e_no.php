<ul class="breadcrumb">
  <li class="btn-back"><a href="<?php echo site_url('nota')?>" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('nota')?>" class="btn btn-mini">Daftar Nota Dinas</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Edit Nomor Nota Dinas</a></li>
</ul>

<div class="page-header">
	<h4>
		<?php echo $records['HAL']?>
		<a href='<?php echo base_url('generate_pdf/nota/'.$records['PK_NOTA_ID']);?>' class="btn btn-mini btn-info" target='_blank'>
			<i class="icon-white icon-eye-open"></i> Preview
		</a>
	</h4>
</div>

<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css')?>" rel="stylesheet">

<?php echo form_open_multipart(site_url('nota/edit_no/'.$this->uri->segment(3)), array('class'=>'form-horizontal form-inline alt1', 'id'=>'xform'))?>
	<div id="messageWrapper"></div>

	<div class="control-group">
		<label class="control-label">No Surat<span class="important">*</span></label>
		<div class="controls">
			<input type="text" name="no_surat" id="no_surat" class="span4" placeholder="ketikkan no surat" value="<?php echo $records['NO_SURAT']?>">
			<?php echo form_error('no_surat', '<br><span style="color:red;">', '</span>')?>
		</div>
	</div>

	<?php if($categories):?>
		<select name="categories"  data-placeholder="Pilih kategori" style="display:none;" disabled>
			<option value="0">Pilih</option>
			<?php foreach($categories as $key=>$val):?>
				<?php if($val['FK_TYPE_ID'] == 2):?>
					<option value="<?php echo $val['PK_CATEGORIES_ID']?>"<?php echo ($records['FK_CATEGORIES_ID']==$val['PK_CATEGORIES_ID']) ? ' selected' : NULL;?>><?php echo $val['CATEGORIES_TITLE']?></option>
				<?php endif;?>
			<?php endforeach;?>
		</select>
	<?php else:?>
		<span class="important">Please tell administrators to fill some categories.</span>
	<?php endif;?>
	<?php echo form_error('categories')?>

	<div class="control-group">
		<label class="control-label">Klasifikasi Informasi</label>
		<div class="controls">
			<?php if($users_nota_klasifikasi):?>
				<select name="klasifikasi" id="klasifikasi" data-placeholder="Pilih klasifikasi" disabled>
					<option value=""></option>
					<?php foreach($users_nota_klasifikasi as $key=>$val):?>
						<option value="<?php echo $val['PK_KLASIFIKASI_ID']?>" <?php echo ($val['PK_KLASIFIKASI_ID']== $records['FK_KLASIFIKASI_ID'])?'selected="selected"':NULL;?>><?php echo $val['DESKRIPSI']?></option>
					<?php endforeach;?>
				</select>
			<?php else:?>
				<span class="important">Please tell administrators to fill some categories.</span>
			<?php endif;?>
			<?php echo form_error('klasifikasi')?>
		</div>
	</div>

	<?php 
	$str = $records['KEPADA'];
	$rec = explode(",",$str);
	?>
	
	<?php if($records['KEPADA_TEXT']):?>
		<div class="clearfix"></div>
		<div class="control-group add-bar">
			<label class="control-label no-border"></label>	
			<div class="span11">	
				<?php $clears = rtrim($records['KEPADA_TEXT'], ', ');?>
				<?php $ex_dis = explode(',', $clears);?>
				<ul class="no-bulets" style="margin-left: 0;">		
				<?php foreach($ex_dis as $key=>$val):?>
					<li>
						<span class="label label-info"><?php echo $val?></span>
					</li>
				<?php endforeach;?>			
				</ul>
			</div>		
		</div>
	<?php endif;?>
	<div class="control-group" style="display:none">
		<label class="control-label">Kepada<span class="important">*</span></label>
		<div class="controls">
			<input type="text" name="kepada" id="kepada" class="span4" placeholder="ketikkan kepada" value="" disabled>
			<?php echo form_error('kepada')?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label">Kepada<span class="important">*</span></label>
		<div class="controls">        
			<select name="kepada1[]" id="kepada1" multiple="multiple" data-placeholder="Pilih Kepada" disabled>
				<?php if($users_nota_kepada):?>							
					<?php foreach($users_nota_kepada as $key=>$val):?>						
						<option value="<?php echo $val['EMPLOYEE_NO']?>" 
							<?php foreach($rec as $k=>$v):?>
								<?php echo ($val['EMPLOYEE_NO'] == $v)?'selected':NULL;?>
							<?php endforeach;?>>
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['KEPADA'].')' ?>
						</option>							
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<?php echo form_error('kepada')?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Dari<span class="important">*</span></label>
		<div class="controls">
			<select name="dari" id="dari" data-placeholder="Pilih Dari" disabled>
				<?php if($users_nota_dari):?>
					<?php foreach($users_nota_dari as $val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>" <?php echo ($val['EMPLOYEE_NO'] == $records['DARI'])? 'selected="selected"':NULL;?>>
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['DARI'] ?>
						</option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<?php echo form_error('dari')?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Hal<span class="important">*</span></label>
		<div class="controls">
			<input type="text" name="hal" id="hal" class="span10" placeholder="ketikkan Hal" value="<?php echo $records['HAL']?>" disabled>
			<?php echo form_error('hal')?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Isi<span class="important">*</span></label>
		<div class="controls">
			<textarea name="desc" id="desc" class="span10" rows="5" placeholder="isi Nota Dinas" disabled>
				<?php echo $records['DESKRIPSI']->load()?>
			</textarea>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Tempat<span class="important">*</span></label>
		<div class="controls">
			<input type="text" name="tempat" id="tempat" class="span3" placeholder="ketikkan Hal" value="<?php print_r($records['TEMPAT'])?>" disabled>
			<?php echo form_error('tempat')?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Tanggal Nota Dinas</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on btn disabled"><i class="fam-date"></i></span>
				<input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo ($records['TANGGAL_NOTA']) ? date('Y-m-d', strtotime($records['TANGGAL_NOTA'])):NULL?>" readonly>
				<?php echo form_error('datepub')?>
			</div>
		</div>
	</div>

	<?php foreach($process as $key=>$val):?>
		<?php 
			if (!($records['FK_CATEGORIES_ID']==$val['FK_CATEGORIES_ID'])) { 
				continue;
			}
		?>
		<div class="control-group <?php echo $val['FK_CATEGORIES_ID']?>" <?php echo ($records['FK_CATEGORIES_ID']==$val['FK_CATEGORIES_ID']) ? 'style="display:block"' : 'style="display:none"';?> id="close">
			<label class="control-label"><?php echo $val['PROCESS_NAME']?></label>
			<div class="controls">
				<select name="pengesahan_<?php echo $val['PROCESS_SORT']?>" class="pengesahan_<?php echo $val['PROCESS_SORT']?>"  id="pengesahan_<?php echo $val['FK_CATEGORIES_ID']?>" data-placeholder="Pilih <?php echo $val['PROCESS_NAME']?>" multiple disabled>
					<option value=""></option>
					<?php 						
					if($users_nota_pengesahan):		
						foreach($users_nota_pengesahan as $a=>$b):
							$selected = '';
							if( search_penandatangan_nota($val['PROCESS_SORT'], $b['EMPLOYEE_NO'], $penandatangan)==TRUE ) {
								$selected = ' selected';
							}?>
							<option value="<?php echo $b['EMPLOYEE_NO']?>"<?php echo $selected?>>
								<?php echo $b['EMPLOYEE_NO'].' - '.$b['EMPLOYEE_NAME'].' ('.$b['PENGESAHAN'].')'?>
							</option>
						<?php endforeach;
					endif;?>
				</select>			
				<?php echo form_error('pengesahan_'.$key)?>						
			</div>
		</div>
	<?php endforeach?>

	<?php 
	$str = $records['TEMBUSAN'];
	$rec_t = explode(",",$str);
	?>

	<?php if($records['TEMBUSAN_TEXT']):?>
		<div class="clearfix"></div>
		<div class="control-group add-bar">
			<label class="control-label no-border"></label>	
			<div class="span11">	
				<?php $clears = rtrim($records['TEMBUSAN_TEXT'], ', ');?>
				<?php $ex_dis = explode(',', $clears);?>
				<ul class="no-bulets" style="margin-left: 0;">		
					<?php foreach($ex_dis as $key=>$val):?>
						<li>
							<span class="label label-info"><?php echo $val?></span> 
						</li>
					<?php endforeach;?>			
				</ul>
			</div>		
		</div>
	<?php endif;?>
	
	<div class="control-group">
		<label class="control-label">Tembusan<span class="important">*</span></label>
		<div class="controls">
			<select name="tembusan[]" id="tembusan" multiple="multiple" data-placeholder="Pilih Tembusan" disabled>
				<?php if($users_nota_tembusan):?>
					<?php foreach($users_nota_tembusan as $key=>$val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>"
							<?php foreach($rec_t as $k=>$v):?>
								<?php echo ($val['EMPLOYEE_NO'] == $v)?'selected':NULL;?>
							<?php endforeach;?>>
							<?php echo $val['EMPLOYEE_NO'].' - '.$val['EMPLOYEE_NAME'].' ('.$val['TEMBUSAN'].')' ?>
						</option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<?php echo form_error('tembusan')?>
		</div>
	</div>

	<?php 
	$str = $records['PEMBUAT_KONSEP'];
	$rec_k = explode(",",$str);
	?>

	<div class="control-group">
		<label class="control-label">Pembuat Konsep</label>
		<div class="controls">
			<select name="pembuat_konsep[]" id="pembuat_konsep" multiple="multiple" data-placeholder="Pilih Pembuat Konsep" disabled>
				<?php if($users_nota_pembuat_konsep):?>
					<?php foreach($users_nota_pembuat_konsep as $key=>$val):?>
						<option value="<?php echo $val['EMPLOYEE_NO']?>" 
							<?php foreach($rec_k as $k=>$v):?>
								<?php echo ($val['EMPLOYEE_NO'] == $v)?'selected':NULL;?>
							<?php endforeach;?>> 
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
			<span class="label label-info"><?php echo $records['LAMPIRAN_NAME']?></span>
		</div>
	</div>

	<input type="hidden" name="nota_id" id="nota_id" value="<?php echo $records['PK_NOTA_ID']?>">

	<div class="form-actions">
		<button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang Menyimpan...">Simpan</button>
		<button type="reset" id="resetBtn" class="btn">Batal</button>
	</div>

</form>

<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/tinymce.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
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
$(function() {   
    
	$(".pengesahan_1,.pengesahan_2,.pengesahan_3").chosen({
		max_selected_options : 1,
		width:"60%"
	});
	
    $("#klasifikasi").chosen({disable_search_threshold:10}); 
    $("#datepub").datepicker({format: 'yyyy-mm-dd', weekStart: 1, noDefault: true});
    $("#posisi_1,#posisi_2,#posisi_3").chosen({disable_search_threshold:10,width:"10%"});
    $("#categories,#pilihan_lembar").chosen({disable_search_threshold:10});
    $("#dari,.pengesahan_1,.pengesahan_2,.pengesahan_3").chosen({width:"60%"});
    $("#tembusan").chosen({width:"80%"});
    $("#paraf").chosen({width:"80%"});
    $("#pembuat_konsep").chosen({width:"80%"});
    $("#kepada1").chosen({width:"60%"});
    
	var e_url = "<?php echo current_url()?>";
	var d_url = "<?php echo site_url('nota/detail/'.$records['PK_NOTA_ID'])?>";
	var statusVal = false;
	
	$("#resetBtn").click(function(e){
		var link_ = "<?php echo site_url('nota/detail/'.$records['PK_NOTA_ID'])?>/";
		location.href=link_;
	});	
		
	$("#submitBtn").click(function(e){
		$('#xform').submit();
	});		
	
	// var options = {
		// target: '#messageWrapper',
		// beforeSubmit: validate,
		// complete: showResponse,
		// url: '<?php echo site_url('nota/edit_no/'.$this->uri->segment(3))?>',
		// type: 'post',
		// dataType: 'json'
	// };

	// $('#xform').ajaxForm(options);	
	
});

function validate(formData, jqForm, options)
{
    var msg = '';
    var form = jqForm[0];

    if (! form.no_surat.value )       msg += '<br>Mohon isikan field Kepada pada Nota Dinas.';
    
    if(msg!='') {
        $('#messageWrapper').html( '<div class="alert alert-error">'+msg+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' );
        return false;
    }       
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
			location.href = "<?php echo site_url('nota/detail/'.$records['PK_NOTA_ID'])?>";
		}, 2000);
		$('#messageWrapper').html( '<div class="alert alert-success">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' ); 
	}
	
	return responseText.error;
}

function showResponse(responseText, statusText, xhr, $form)
{
	if(responseText.error=='1')
	{
		$('#messageWrapper').html( '<div class="alert alert-error">'+responseText.response+'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' );
	}
	else
	{
		setInterval(function(){
				location.href = "<?php echo site_url('nota/detail/'.$records['PK_NOTA_ID'])?>";
			}, 2000);
		$('#messageWrapper').html( '<div class="alert alert-success">Berhasil memperbaharui nota dinas.<a class="close" data-dismiss="alert" href="#">&times;</a></div>' );
                
	}

	return responseText.error;
}


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
        (n3.val().length == 4)
    ){
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
                    }else{
                        $('#preview_no_surat').html( res );
                        $('#preview_no_surat').attr({
                            'class':'label label-info'
                        });
                    }
                }

        });

    }else{
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