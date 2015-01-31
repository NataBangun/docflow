<ul class="breadcrumb">
  <li class="btn-back"><a href="<?php echo site_url('type')?>" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('type')?>" class="btn btn-mini">Daftar Jenis Dokumen</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Edit Jenis Dokumen</a></li>
</ul>

<div class="page-header">
	<h4>Edit Jenis Dokumen</h4>
</div>

<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css')?>" rel="stylesheet">
<?php echo form_open(site_url('type/update/'.$records['PK_TYPE_ID']) , array('class'=>'form-horizontal alt1', 'id'=>'xform'))?>
<div id="messageWrapper"></div>

<div class="control-group">
	<label class="control-label">Judul <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="title" id="title" class="span10" placeholder="ketikkan Tipe dokumen" value="<?php echo set_value('title',$records['TYPE_NAME']); ?>">
		<?php echo '<span style="color:red;"><br>'.form_error('title').'</span>'?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Deskripsi/Catatan</label>
	<div class="controls">
		<textarea name="desc" id="desc" class="span10" rows="5" placeholder="Deskripsi atau catatan dokumen">
		<?php echo set_value('desc',$records['TYPE_DESC']); ?>
		</textarea>
		<?php echo '<span style="color:red;"><br>'.form_error('desc').'</span>'?>
	</div>
</div>
<input type="hidden" name="id" value="<?php echo $records['PK_TYPE_ID']?>">
<div class="form-actions">
	<button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="sedang menyimpan...">Simpan</button>
	<button type="reset" id="resetBtn" class="btn">Batal</button>
</div>

</form>


<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/nicEdit.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
<script type="text/javascript">
$(function() { 
	
	new nicEditor({iconsPath : '<?php echo base_url('assets/js/nicEditIcons-latest.gif')?>'}).panelInstance('desc'); 	
	
	$("#resetBtn").click(function(e){
		var link_ = "<?php echo site_url('type')?>/";
		location.href=link_;
	});
	
}); 
</script>