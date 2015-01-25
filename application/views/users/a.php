<ul class="breadcrumb">
  <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>
  <li><a href="<?php echo site_url()?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
  <li><a href="<?php echo site_url('usr')?>" class="btn btn-mini">Daftar Paraf & Tanda Tangan</a></li>
  <li><a href="javascript:;" class="btn btn-mini disabled">Posting Paraf & Tanda Tangan</a></li>
</ul>

<div class="page-header">
	<h4>Posting Paraf & Tanda Tangan</h4>
</div>

<?php echo form_open_multipart(site_url('usr/uploads/'.$this->uri->segment(3)), array('class'=>'form-horizontal alt1'))?>
			<div class="control-group">
				<label class="control-label">Tanda tangan</label>
				<div class="controls">
					<input type="file" name="userfile" id="userfile"  accept="image/png">
					<?php if($records){?>
					<?php if($records['USERS_SIGNATURE']){?>
					<input type="hidden" name="ttd_file" value="<?php echo $records['USERS_SIGNATURE'];?>" id="ttd">
					<img src="<?php echo base_url()?>uploads/paraf/ttd/<?php echo $records['USERS_SIGNATURE']?>" style="height:100px;">
					<?php }?>
					<?php }?>
				</div>
				
				<label class="control-label">Paraf</label>
				<div class="controls">
					<input type="file" name="paraf" id="paraf"  accept="image/png">
					
					<?php if($records){?>
					<?php if($records['USERS_PARAF']){?>
					<input type="hidden" name="paraf_file" value="<?php echo $records['USERS_PARAF'];?>" id="paraf">
					<img src="<?php echo base_url()?>uploads/paraf/prf/<?php echo $records['USERS_PARAF']?>" style="height:100px;">
					<?php }?>
					<?php }?>
					
				</div>
				
			</div>
			<div class="control-group">
			<div class="controls">
			<button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang Menyimpan...">Simpan</button>
			<button type="reset" id="resetBtn" class="btn">Batal</button>
			</div>
			<?php echo "<font color='red'>Keterangan:<br>Jenis File ".UPLOAD_TTD_PARAF_FILE_TYPE." dan ukuran ".UPLOAD_TTD_PARAF_SIZE_KB." KB</font>"; ?>
			</div>
</form>



<script type="text/javascript">
$(function() { 	
	
$("#resetBtn").click(function(e){
		var link_ = "<?php echo site_url('usr')?>/";
		location.href=link_;
	});
	
}); 

</script>