<ul class="breadcrumb">
    <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>
    <li><a href="<?php echo site_url() ?>" class="btn btn-mini"><i class="icon-home"></i></a></li>
    <li><a href="<?php echo site_url('documents') ?>" class="btn btn-mini">Daftar Dokumen</a></li>
    <li><a href="javascript:;" class="btn btn-mini disabled">Posting Dokumen</a></li>
</ul>

<div class="page-header">
    <h4>Posting Dokumen</h4>
</div>

<link href="<?php echo base_url('assets/css/datepicker.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/chosen.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/magicsuggest.css') ?>" rel="stylesheet">

<?php echo form_open_multipart(site_url('documents/insert'), array('class' => 'form-horizontal alt1', 'id' => 'xform')) ?>
<div id="messageWrapper"></div>

<div class="control-group">
    <label class="control-label">Nomor Dokumen <span class="important">*</span></label>
    <div class="controls">
        <input type="text" name="no" id="no" class="span10" placeholder="Ketikkan Nomor Dokumen" value="<?php echo set_value('no') ?>">		
        <?php echo form_error('no'); ?>
    </div>	
</div>

<div class="control-group">
    <label class="control-label">Judul Dokumen <span class="important">*</span></label>
    <div class="controls">
        <input type="text" name="title" id="title" class="span10" placeholder="Ketikkan Judul Dokumen" value="<?php echo set_value('title') ?>">		
        <?php echo form_error('title'); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">Versi <span class="important">*</span></label>
    <div class="controls">
        <input type="text" name="versi[0]" class="span1" id="versi1" value="<?php echo set_value('versi[0]', '1') ?>" style="width:20px;">
        <input type="text" name="versi[1]" class="span1" id="versi2" value="<?php echo set_value('versi[1]', '0') ?>" style="width:20px;">
        <input type="hidden" name="versi[2]" class="span1" id="versi3" value="<?php echo set_value('versi[2]', '0') ?>" style="width:20px;">		
        <?php echo form_error('versi[0]'); ?>
        <?php echo form_error('versi[1]'); ?>
        <?php echo form_error('versi[2]'); ?>
    </div>	
</div>

<div class="control-group">
    <label class="control-label">Kategori Prosedur <span class="important">*</span></label>
    <div class="controls">
        <?php if ($categories) { ?>
            <select name="categories" id="categories">
                <option value="">Pilih Kategori Prosedur</option>
                <?php
                $arr_pk_categories_id = array();
                foreach ($categories as $key => $val) {
                    if ($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1) {
                        $arr_pk_categories_id[] = $val['PK_CATEGORIES_ID'];
                        ?>
                        <option value="<?php echo $val['PK_CATEGORIES_ID'] ?>" <?php echo set_select('categories', $val['PK_CATEGORIES_ID']) ?>>
                            <?php echo $val['CATEGORIES_TITLE'] ?>
                        </option>
                        <?php
                    }
                }
                ?>
            </select>
        <?php } else { ?>
            <span class="important">
                Hubungi Admin untuk mengisi data Kategori Prosedur.
            </span>
        <?php } ?>
        <?php echo form_error('categories') ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label">Tanggal Terbit <span class="important">*</span></label>
    <div class="controls">
        <div class="input-prepend">
            <span class="add-on btn disabled"><i class="fam-date"></i></span>
            <input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo set_value('datepub'); ?>" readonly>
            <?php echo form_error('datepub'); ?>		
        </div>
    </div>
</div>

<div class="control-group">
    <label class="control-label">Lampiran</label>
    <div class="controls">
        <div id="wraper-atch">
            <div class="input-prepend">
                <input type="file" accept="application/pdf" class="span3" name="files[]">
                <a class="btn btn-info" id="atch"><i class="fam-add"></i></a> &nbsp &nbsp <span class="label label-info"> Jenis File: pdf; Ukuran Maks: 5MB</span>
            </div>
        </div>
        <?php
        if (isset($files_error)) {
            echo "<div  style=\"color:red;\">" . $files_error . "</div>";
        }
        ?>
    </div>
</div>


<div class="control-group">
    <label class="control-label">Histori Perubahan</label>
    <div class="controls">
        <textarea name="descrip" id="descrip" class="span10" rows="5" placeholder="Histori Perubahan"><?php echo set_value('descrip'); ?></textarea>
        <?php echo form_error('descrip'); ?>
    </div>
</div>

<div class="control-group add-bar">
    <label class="control-label">Distribusi Kepada <span class="important">*</span></label>	
    <div class="controls">
        <div class="input-append">
            <ul id="targetDist" style="margin-left:0;" class="no-bulets">
                <li>
                    <input class="span5" id="appendedInputButton" type="text" placeholder="Ketikkan Distribusi Kepada" name="distribution[]" value="<?php echo set_value('distribution[]') ?>"
                           ><button class="btn btn-info" id="addDist" type="button"><i class="fam-add"></i></button>
                </li>
                <?php
                if (is_array($this->input->post('distribution'))) {
                    $distribution = $this->input->post('distribution');
                    for ($i = 1; $i < count($distribution); $i++) {
                        ?>
                        <li id="close-dist">
                            <input class="span5" id="appendedInputButton" type="text" placeholder="Ketikkan Distribusi Kepada" name="distribution[]" value="<?php echo $distribution[$i] ?>"
                                   ><button class="btn btn-danger remove" type="button"><i class="fam-cancel"></i></button>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <?php echo form_error('distribution[]'); ?>
    </div>
</div>
<div class="clearfix"></div>

<?php
$arr_control_group_key = array();
$arr_magic_suggest_key = array();
foreach ($process as $key => $val) {
    if (in_array($val['FK_CATEGORIES_ID'], $arr_pk_categories_id)) {
        $arr_control_group_key[] = '.cg' . $key;
        $arr_magic_suggest_key[] = '#ms' . $key;
        $penandatangan_key = 'penandatangan_' . $val['FK_CATEGORIES_ID'] . '_' . $val['PROCESS_SORT'];
        ?>
        <div class="control-group <?php echo $val['FK_CATEGORIES_ID'] . ' cg' . $key ?>"
        <?php
        if (set_value('categories') != $val['FK_CATEGORIES_ID']) {
            echo "style=\"display: none\"";
        }
        ?> >
            <label class="control-label"><?php echo $val['PROCESS_NAME'] ?> <span class="important">*</span></label>
            <div class="controls">
                <input style="width:560px;" type="text" placeholder="Ketikkan <?php echo $val['PROCESS_NAME'] ?>" 
                       id="ms<?php echo $key ?>" name="<?php echo $penandatangan_key; ?>"
                       <?php
                       if (set_value('categories') == $val['FK_CATEGORIES_ID']) {
                           if (is_array($this->input->post($penandatangan_key))) {
                               echo "value='" . json_encode($this->input->post($penandatangan_key)) . "'";
                           }
                       }
                       ?> />
                       <?php
                       if (set_value('categories') == $val['FK_CATEGORIES_ID']) {
                           echo form_error($penandatangan_key);
                       }
                       ?>
            </div>
        </div>
        <?php
    }
}
?>
<div id="data_cg_key" data-id="<?php echo implode(',', $arr_control_group_key); ?>"></div>
<div id="data_ms_key" data-id="<?php echo implode(',', $arr_magic_suggest_key); ?>"></div>

<?php
//    echo validation_errors(); // for debuging
?>

<div class="form-actions">
    <button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang Menyimpan..."  >Simpan</button>
    <button type="reset" id="resetBtn" class="btn">Batal</button>
</div>

</form>
<script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/nicEdit.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/form.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.chained.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/magicsuggest.js') ?>"></script>
<script type="text/javascript">
    $(function () {
        var ms_keys = $('#data_ms_key').attr('data-id');
        $(ms_keys).magicSuggest({
            allowFreeEntries: false,
            data: <?php echo json_encode($name); ?>
        });

        document.descrip = new nicEditor({iconsPath: '<?php echo base_url('assets/js/nicEditIcons-latest.gif') ?>'}).panelInstance('descrip');

        $("#datepub").datepicker({format: 'yyyy-mm-dd', weekStart: 1, noDefault: true});
        $("#categories").chosen({disable_search_threshold: 10});
        $("#resetBtn").click(function (e) {
            location.href = "<?php echo site_url('documents') ?>/";
        });
        $("#submitBtn").click(function (e) {

            // send nicEditor data - Bug : chrome tidak mengirim data nicEditor - 2015/01/04
            $('#descrip').text(document.descrip.nicInstances[0].getContent());

            // finally do submit
            $('#xform').submit();
        });
    });

</script>