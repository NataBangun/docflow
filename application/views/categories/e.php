<ul class="breadcrumb">
  <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('categories')?>" class="btn btn-mini">Daftar Kategori</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Edit Kategori</a></li>
</ul>

<div class="page-header">
	<h4>Edit Kategori & Workflow</h4>
</div>
<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css')?>" rel="stylesheet">
<?php echo form_open_multipart(site_url('categories/update/'.$records['PK_CATEGORIES_ID']), array('class'=>'form-horizontal alt1', 'id'=>'xform'))?>
<div id="messageWrapper"></div>

<input type="hidden" name="id" id="id" class="span10" value="<?php echo $records['PK_CATEGORIES_ID'];?>">
<input type="hidden" name="fname" id="fname" class="span10" value="<?php echo $records['CATEGORIES_IMAGE'];?>">

<div class="control-group">
	<label class="control-label"> Jenis Dokumen <span class="important">*</span></label>
	<div class="controls" id="type">
		<select name="type" id="doc-type" placeholder="pilih">		
		<?php foreach($type as $row):?>			
			<option value="<?php echo $row['PK_TYPE_ID']?>" <?php echo ($row['PK_TYPE_ID'] == $records['FK_TYPE_ID'])?'selected':NULL;?>> <?php echo $row['TYPE_NAME']?> </option>		
		<?php endforeach;?>
		</select>
		<?php echo form_error('type')?>
	</div>
</div>

<div class="control-group" id="close-doc-type">
	<label class="control-label">Stempel</label>
	<div class="controls">
	<div class="input-prepend">
		<input type="file" class="span5" name="userfile">		
	</div>
	</div>
</div>
<?php if($records['CATEGORIES_IMAGE']){?>
<div class="control-group">
	<label class="control-label">image</label>
	<div class="controls">
	<div class="input-prepend">	
		<img src="<?php echo base_url()?>uploads/category/<?php echo $records['CATEGORIES_IMAGE']?>" style="width:20%;">
		<a href="<?php echo site_url('categories/delete_img/'.$records['PK_CATEGORIES_ID'])?>" style="margin-left: -5px;"><i class="fam-cancel" style="width: 17px; margin-left: -16px; margin-top: -42px;"></i></a>
	</div>
	</div>
</div>
<?php }?>

<div class="control-group">
	<label class="control-label">Judul <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="title" id="title" class="span10" placeholder="ketikkan judul" value="<?php echo $records['CATEGORIES_TITLE'];?>">
		<?php echo '<span style="color:red;"><br>'.form_error('title').'</span>'?>
	</div>
</div>

<div class="clearfix"></div>
<h4 style="border-bottom: 1px solid #EDEDED;">Penandatangan <span class="important">*</span></h4>
<div class="clearfix"></div>
<!--<?php print_r($proccess)?>-->
<div class="control-group add-bar">
	<label class="control-label no-border"></label>	
	<?php $x=1;?>
	<div id="CP" data-proccess="<?php echo count($proccess);?>"></div>	
	<?php foreach($proccess as $key=>$val):?>
	<div class="controls" style="margin-bottom: 10px;" id="e_close">
		<div class="input-append">			
			<input class="span1" type="hidden" name="order_status<?php echo $x?>" placeholder="urutan 1" value="<?php echo $val['PROCESS_SORT']?>">
			<input class="span3"  type="text" placeholder="Masukan Judul Kategori" name="add<?php echo $x?>" value="<?php echo $val['PROCESS_NAME']?>">
			<input class="span2" id="appendedInputButton" type="text" name="pdf_title<?php echo $x?>" placeholder="Judul Pada Pdf" value="<?php echo ($val['PROCESS_PDF_NAME'])? $val['PROCESS_PDF_NAME']:'Tidak Ada';?>">
			<input type="radio" name="check_status<?php echo $x?>" value="0" <?php echo ($val['PROCESS_TYPE'] == 0)? 'checked':NULL;?>> seri<i> &nbsp &nbsp </i>
			<input type="radio" name="check_status<?php echo $x?>" value="1" <?php echo ($val['PROCESS_TYPE'] == 1)? 'checked':NULL;?>> pararel<i> &nbsp &nbsp </i>
			<?php if($key == 0):?>
			<button type="button" id="addMore2" class="btn btn-info" style="margin-left: -5px;"><i class="fam-add"></i></button>
			<?php else:?>
			<button type="button" class="btn btn-danger e_remove" style="margin-left: -5px;"><i class="fam-cancel"></i></button>
			<?php endif;?>
		</div>
    </div>	
		<?php $x++;?>
	<?php endforeach;?>
	<div class="controls" style="margin-bottom: 10px;">
		<?php echo '<span style="color:red;">'.form_error('add1').'</span>'?>
    </div>	
	<div id="e_Form"></div>	
</div>
<!-- 
<div class="control-group add-bar">
	<label class="control-label no-border"></label>	
	<div class="btn btn-primary">Tambah</div>
</div>
 -->

<div class="control-group">
	<label class="control-label">Deskripsi/Catatan </label>
	<div class="controls">
		<textarea name="desc" id="desc" class="span10" rows="5" placeholder="Deskripsi atau catatan dokumen"><?php echo $records['CATEGORIES_DESCRIPTION'];?></textarea>
		<?php echo '<span style="color:red;"><br>'.form_error('desc').'</span>'?>
	</div>
</div>

<input type="hidden" name="categories_val" value="<?php echo count($proccess)?>">
<div class="form-actions">
	<button type="submit" id="submitBtn" data-loading-text="Loading..." class="btn btn-primary" title="Save as a draft">Simpan</button>
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
		var link_ = "<?php echo site_url('categories')?>/";
		location.href=link_;
	});
	
}); 

</script>