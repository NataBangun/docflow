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
					<input type="file" name="userfile" id="userfile" onchange="cekttd(this);" accept="image/png">
					<?php if($records){?>
					<?php if($records['USERS_SIGNATURE']){?>
					<input type="hidden" name="ttd_file" value="<?php echo $records['USERS_SIGNATURE'];?>" id="ttd">
					<img src="<?php echo base_url()?>uploads/paraf/ttd/<?php echo $records['USERS_SIGNATURE']?>" style="height:100px;">
					<?php }?>
					<?php }?>
				</div>
				
				<label class="control-label">Paraf</label>
				<div class="controls">
					<input type="file" name="paraf" id="paraf" onchange="cekparaf(this);" accept="image/png">
					
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
			<input type="submit" class="btn btn-primary" value="Simpan">
			<button type="reset" id="resetBtn" class="btn">Batal</button>
			</div>
			</div>
</form>
<script type="text/javascript">
   function cekttd(fieldObj)
    {
		var _URL = window.URL || window.webkitURL;
        var FileName  = fieldObj.value;
        var FileExt = FileName.substr(FileName.lastIndexOf('.')+1);

        if (FileExt.toLowerCase() != "png")
        {
            var error = "Tipe file : "+ FileExt+"\n\n";
            error += "Tipe file tanda tangan harus tipe PNG.\n\n";
            alert(error);
			location.reload();
            return false;
        }
        cekdimensi(this);
    }
</script>
<script>
var _URL = window.URL || window.webkitURL;
$("#userfile,#paraf").change(function(e) {
    var file, img;


    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function() {
		if(this.width != this.height){
            alert("ERROR! Dimensi gambar tidak valid.\nLebar: "+this.width + "\nTinggi: " + this.height+"\n\nSilakan pilih gambar dengan tinggi dan lebar yang sama (tinggi = lebar).");
			location.reload();
		}
        };
        img.src = _URL.createObjectURL(file);


    }
});
</script>
<script type="text/javascript">
   function cekparaf(fieldObj)
    {
        var FileName  = fieldObj.value;
        var FileExt = FileName.substr(FileName.lastIndexOf('.')+1);

        if (FileExt.toLowerCase() != "png")
        {
            var error = "Tipe file : "+ FileExt+"\n\n";
            error += "Tipe file paraf harus tipe PNG.\n\n";
            alert(error);
			location.reload();
            return false;
        }
        return true;
    }
</script>
<script type="text/javascript">
$(function() { 	
	
$("#resetBtn").click(function(e){
		var link_ = "<?php echo site_url('usr')?>/";
		location.href=link_;
	});
	
}); 

</script>