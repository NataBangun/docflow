<ul class="breadcrumb">
  <li class="btn-back"><a href="<?php echo site_url('categories')?>" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('categories')?>" class="btn btn-mini">Daftar kategori</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Posting kategori</a></li>
</ul>

<div class="page-header">
	<h4>Posting kategori</h4>
</div>

<?php echo form_open_multipart(site_url('categories/insert'), array('class'=>'form-horizontal alt1', 'id'=>'xform'))?>
<div id="messageWrapper"></div>

<div class="control-group">
	<label class="control-label"> Jenis Dokumen <span class="important">*</span></label>
	<div class="controls" id="type">
		<select name="type" id="doc-type">
		<option value="0"> Pilih </option>
		<?php foreach($type as $row):?>			
			<option value="<?php echo $row['PK_TYPE_ID']?>" <?php echo ($this->input->post('type') == $row['PK_TYPE_ID'])? 'selected="selected"':NULL;?>> <?php echo $row['TYPE_NAME']?> </option>		
		<?php endforeach;?>
		</select>		
		<?php echo '<span style="color:red;"><br>'.form_error('type').'</span>'?>
	</div>
</div>

<div class="control-group" id="close-doc-type">
	<label class="control-label">Stempel</label>
	<div class="controls">
<div class="input-prepend">
		<input type="file" class="span5" name="userfile" id="userfile">
		<?php echo '<span style="color:red;"><br>'.form_error('userfile').'</span>'?>
<p style="color:red;font-style:italic;margin-top:15px;">
			<b>Keterangan :</b><br />
			Type file stempel yang boleh diupload : jpg atau png, maksimal size : 512 KB
		</p>
	</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label">Judul <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="title" id="title" class="span10" placeholder="ketikkan judul" value="<?php echo $this->input->post('title')?>">
		<?php echo '<span style="color:red;"><br>'.form_error('title').'</span>'?>
	</div>
</div>

<div class="clearfix"></div>
<h4 style="border-bottom: 1px solid #EDEDED;">Penandatangan <span class="important">*</span></h4>
<div class="clearfix"></div>

<div class="control-group add-bar">
	<label class="control-label no-border"></label>	
	<?php 
	$count = 1;
	for ($i=1; $i<=$count; $i++) { ?>
	<div class="controls" style="margin-bottom: 10px;" id="e_close">
		<div class="input-append">
			<input type="hidden" value="<?php echo $i?>" name="val">
			<input class="span1" type="hidden" value="<?php echo $i?>" name="order_status<?php echo $i?>" placeholder="urutan <?php echo $i?>">
			<input class="span3"  type="text" placeholder="Masukan Judul Kategori" name="add<?php echo $i?>" id="add<?php echo $i?>"
			value="<?php echo $this->input->post('add'.$i)?>" >
			<input class="span2" id="appendedInputButton" type="text" name="pdf_title<?php echo $i?>" id="pdf_title<?php echo $i?>"
			value="<?php echo $this->input->post('pdf_title'.$i)?>"
			placeholder="Judul Pada Pdf">			
			<input type="radio" name="check_status<?php echo $i?>" value="0" <?php if ($this->input->post('check_status'.$i) == 0) echo "checked" ?>> seri<i> &nbsp &nbsp </i>
			<input type="radio" name="check_status<?php echo $i?>" value="1" <?php if ($this->input->post('check_status'.$i) == 1) echo "checked" ?>> paralel<i> &nbsp &nbsp </i>
			<?php if($i == 1):?>
				<button class="btn btn-info" id="addMore2" type="button" style="margin-left: -5px;"><i class="fam-add"></i></button>
			<?php else:?>
				<button type="button" class="btn btn-danger e_remove" style="margin-left: -5px;"><i class="fam-cancel"></i></button>
			<?php endif;?>
		</div>
    </div>
	<?php 
		if ($this->input->post('order_status'.($i+1)) > $count) {
			$count = $i+1;
		}
	} ?>
	<div id="CP" data-proccess="<?php echo $count;?>"></div>	
	<div class="controls" style="margin-bottom: 10px;">
		<?php echo '<span style="color:red;">'.form_error('add1').'</span>'?>
    </div>
	<div id="e_Form"></div>	
</div>
<div id="targetForm"></div>

<div class="control-group">
	<label class="control-label">Deskripsi/Catatan </label>
	<div class="controls">
		<textarea name="desc" id="desc" class="span10" rows="5"  value="<?php echo set_value('desc'); ?>" placeholder="Deskripsi atau catatan dokumen"><?php echo set_value('desc'); ?></textarea>
		<?php echo '<span style="color:red;"><br>'.form_error('desc').'</span>'?>
	</div>
</div>

<div class="form-actions">
	<button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang menyimpan..." onclick="nicEditors.findEditor('desc').saveContent();">Simpan</button>
	<button type="reset" id="resetBtn" class="btn">Batal</button>
</div>

</form>


<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/nicEdit.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js')?>"></script>
<script type="text/javascript">
$(document).ready(function(){	
var nyut = document.getElementById('doc-type');
  if(nyut.value== '1')
  {
  $('#close-doc-type').show();
  }
 });
</script>

<script type="text/javascript">
    $(function () {

        document.desc = new nicEditor({iconsPath : '<?php echo base_url('assets/js/nicEditIcons-latest.gif')?>'}).panelInstance('desc'); 	
        $("#categories").chosen({disable_search_threshold: 10});
        $("#resetBtn").click(function(e){
		var link_ = "<?php echo site_url('categories')?>/";
		location.href=link_;
	});
        $("#submitBtn").click(function (e) {

            // send nicEditor data - Bug : chrome tidak mengirim data nicEditor - 2015/01/04
            $('#desc').text(document.desc.nicInstances[0].getContent());

            // finally do submit - Bug : jika menggunakan button[type=submit] chrome tidak bisa otomatis submit
            $('#xform').submit();
        });
    });

</script>