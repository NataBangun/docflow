<ul class="breadcrumb">    <li class="btn-back"><a href="javascript:history.go(-1);" class="btn btn-mini btn-info">Kembali</a></li>    <li><a href="<?php echo site_url() ?>" class="btn btn-mini"><i class="icon-home"></i></a></li>    <li><a href="<?php echo site_url('documents') ?>" class="btn btn-mini">Daftar Dokumen</a></li>    <li><a href="javascript:;" class="btn btn-mini disabled">Edit Dokumen</a></li></ul><div class="page-header">    <div class="pull-right">	        <?php        if ($records['PROCESS_STATUS'] == DOC_DRAFT) {            echo form_open('', array('id' => 'distForm'))            ?>            <input type="hidden" name="dI" value="<?php echo $records['PK_DOCUMENTS_ID'] ?>">            <input type="hidden" name="dS" value="<?php echo $records['PROCESS_STATUS'] ?>">            <input type="hidden" name="vI" value="<?php echo $records['VERSION_ID'] ?>">            <a id="distBtn" class="btn btn-primary data-load" href="javascript:;" title="Sosialisasikan Dokumen" data-confirm="Anda yakin akan melakukan submit Dokumen ?" data-loading="Loading..."><i class="fam-arrow-switch"></i> Submit</a>            </form>            <?php        }        if ($records['PROCESS_STATUS'] == DOC_EDIT) {            echo form_open('', array('id' => 'commitForm'))            ?>            <input type="hidden" name="dI" value="<?php echo $records['PK_DOCUMENTS_ID'] ?>">            <input type="hidden" name="dS" value="<?php echo $records['PROCESS_STATUS'] ?>">            <input type="hidden" name="vI" value="<?php echo $records['VERSION_ID'] ?>">            <a id="commitBtn" href=":;"  class="btn btn-primary" title="Sosialisasikan Dokumen" data-confirm="Anda yakin akan melakukan submit Dokumen ?"><i class="fam-arrow-switch"></i> Submit</a>            </form>            <?php        }        ?>    </div>    <h4>        <?php echo $records['DOCUMENTS_NO'] ?>         <a title="view merge" class="btn btn-mini btn-info" target="_blank" href="<?php echo site_url('documents/view/' . $records['PK_DOCUMENTS_ID'] . '/' . $records['PK_DOCUMENTS_PROCESS_ID']) ?>">            <i class="icon-white icon-eye-open"></i> Preview        </a>    </h4>		    <div class="clearfix" style="height: 10px;"></div></div><div id="messageWrapper"></div><ul class="nav nav-tabs" id="myTab">    <li class="active"><a href="#metadata" data-toggle="tab">Info Dokumen</a></li>    <li class=""><a href="#files" data-toggle="tab">Lampiran Dokumen Sistem</a></li>    <?php if ($records['PROCESS_STATUS'] == DOC_DRAFT || $records['PROCESS_STATUS'] == DOC_EDIT) { ?>        <li class="pull-right"><div class="alert"><strong style="color: #A67E39;">Jika ingin melakukan submit / sosialisasi silakan klik tombol submit diatas </strong></div></li>    <?php } ?></ul><div class="tab-content">    <div class="tab-pane active" id="metadata">        <link href="<?php echo base_url('assets/css/datepicker.css') ?>" rel="stylesheet">        <link href="<?php echo base_url('assets/css/chosen.min.css') ?>" rel="stylesheet">        <link href="<?php echo base_url('assets/css/magicsuggest.css') ?>" rel="stylesheet">        <?php echo form_open_multipart(site_url('documents/update_revisi/' . $records['PK_DOCUMENTS_ID']), array('class' => 'form-horizontal alt1', 'id' => 'xform')) ?>        <div class="control-group">            <label class="control-label">Nomor Dokumen <span class="important">*</span></label>            <div class="controls">                <input type="text" name="no" id="no" class="span10" placeholder="Ketikkan Nomor Dokumen" value="<?php echo set_value('no', $records['DOCUMENTS_NO']) ?>">                <?php echo form_error('no'); ?>            </div>        </div>        <div class="control-group">            <label class="control-label">Judul Dokumen <span class="important">*</span></label>            <div class="controls">                <input type="text" name="title" id="title" class="span10" placeholder="Ketikkan Judul Dokumen" value="<?php echo set_value('title', $records['DOCUMENTS_TITLE']) ?>">                <?php echo form_error('title'); ?>            </div>        </div>        <div class="control-group">            <label class="control-label">Versi</label>            <div class="controls">                <?php $versi = $records['VERSION_ID']; ?>                <input type="text" name="versi[0]" class="span1" id="versi1" readonly value="<?php echo (isset($versi[0])) ? $versi[0] : NULL; ?>" style="width:20px;">                <input type="text" name="versi[1]" class="span1" id="versi2" readonly value="<?php echo (isset($versi[1])) ? $versi[1] : NULL; ?>" style="width:20px;">                <input type="hidden" name="versi[2]" class="span1" id="versi3" readonly value="<?php echo (isset($versi[2])) ? $versi[2] : NULL; ?>" style="width:20px;">            </div>        </div>        <div class="control-group">            <label class="control-label">Kategori Prosedur</label>            <div class="controls">                <?php if ($categories) { ?>                    <select name="categories" id="categories">                        <?php                        foreach ($categories as $key => $val) {                            if ($val['FK_TYPE_ID'] == 1 && $val['CATEGORIES_STATUS'] != 1) {                                ?>                                <option value="<?php echo $val['PK_CATEGORIES_ID'] ?>" <?php echo ($records['PK_CATEGORIES_ID'] == $val['PK_CATEGORIES_ID']) ? 'selected' : 'disabled'; ?> >                                    <?php echo $val['CATEGORIES_TITLE'] ?>                                </option>                                <?php                            }                        }                        ?>                    </select>                <?php } else { ?>                    <span class="important">                        Hubungi Admin untuk mengisi data Kategori Prosedur.                    </span>                <?php } ?>            </div>        </div>        <div class="control-group">            <label class="control-label">Tanggal Terbit</label>            <div class="controls">                <div class="input-prepend">                    <span class="add-on btn disabled"><i class="fam-date"></i></span>                    <input type="text" name="datepub" id="datepub" placeholder="yyyy-mm-dd" class="input-small" value="<?php echo set_value('datepub', $records['DOCUMENTS_DATEPUB']); ?>" readonly>                    <?php echo form_error('datepub') ?>                </div>            </div>        </div>        <div class="control-group">            <label class="control-label">Lampiran</label>            <div class="controls">                <div id="wraper-atch">                    <div class="input-prepend">                        <?php                        if ($records['DOCUMENTS_ATC_NAME']) {                            $ex_name = explode(',', $records['DOCUMENTS_ATC_NAME']);                            ?>		                            <ul class="no-bulets" style="margin-left: 0;">                                <?php                                foreach ($ex_name as $key => $val) {                                    if ($val == '') {                                        continue;                                    }                                    ?>                                    <li>                                        <span class="label label-info"><?php echo $val ?></span>                                         &nbsp                                         <a class="btn btn-mini btn-danger" href="<?php echo site_url('documents/d_lampiran/' . $records['PK_DOCUMENTS_ID'] . '/' . $val) ?>">                                            <i class="fam-cancel"></i>                                        </a>                                         &nbsp                                         <a class="btn btn-mini btn-info" href="<?php echo base_url(UPLOAD_DOKPRO_LAMPIRAN . $val) ?>" target="_blank">                                             <i class="fam-zoom"></i>                                        </a>                                     </li>                                <?php } ?>		                            </ul>                        <?php } ?>                        <input type="file" accept="application/pdf" class="span3" name="files[]">                        <a class="btn btn-info" id="atch"><i class="fam-add"></i></a> &nbsp &nbsp <span class="label label-info"><?php echo "Jenis File: " . UPLOAD_DOKPRO_FILE_TYPE . ";  Ukuran Maks: " . UPLOAD_DOKPRO_SIZE_MB . "MB"; ?></span>                        <input type="hidden" name="file_name" value="<?php echo $records['DOCUMENTS_ATC_NAME'] ?>">                    </div>                </div>                <?php                if (isset($files_error)) {                    echo "<div  style=\"color:red;\">" . $files_error . "</div>";                }                ?>            </div>        </div>                <div class="control-group">            <label class="control-label">Histori Perubahan</label>            <div class="controls">                <textarea name="descrip" id="descrip" class="span10" rows="5" placeholder="Histori Perubahan"><?php echo set_value('descrip', $records['DOCUMENTS_DESCRIPTION']); ?></textarea>                <?php echo form_error('descrip') ?>            </div>        </div>        <div class="control-group add-bar">            <label class="control-label">Distribusi Kepada <span class="important">*</span></label>	            <div class="controls">                <div class="input-append">                    <ul id="targetDist" style="margin-left:0;" class="no-bulets">                        <li>                            <?php                            $arr_distribution = explode(",", $records['DOCUMENTS_DISTRIBUTION']);                            ?>                            <input class="span5" id="appendedInputButton" type="text" placeholder="Ketikkan Distribusi Kepada" name="distribution[]" value="<?php echo set_value('distribution[]', $arr_distribution[0]) ?>"                                   ><button class="btn btn-info" id="addDist" type="button"><i class="fam-add"></i></button>                        </li>                        <?php                        $distribution = array();                        if (is_array($this->input->post('distribution'))) {                            $distribution = $this->input->post('distribution');                        } else {                            foreach ($arr_distribution as $k => $v) {                                if ($v != '') {                                    $distribution[$k] = $v;                                }                            }                        }                        for ($i = 1; $i < count($distribution); $i++) {                            ?>                            <li id="close-dist">                                <input class="span5" id="appendedInputButton" type="text" placeholder="Ketikkan Distribusi Kepada" name="distribution[]" value="<?php echo $distribution[$i] ?>"                                       ><button class="btn btn-danger remove" type="button"><i class="fam-cancel"></i></button>                            </li>                            <?php                        }                        ?>                    </ul>                </div>                <?php echo form_error('distribution[]'); ?>            </div>        </div>        <div class="clearfix"></div>        <input type="hidden" name="documents_id" id="documents_id" value="<?php echo $records['PK_DOCUMENTS_ID'] ?>">        <?php//        echo validation_errors(); // for debuging        ?>                <?php if ($records['PROCESS_STATUS'] == DOC_DRAFT || $records['PROCESS_STATUS'] == DOC_EDIT) { ?>            <div class="form-actions">                <button type="submit" id="submitBtn" class="btn btn-primary data-load" title="Simpan" data-loading="Sedang Menyimpan...">Simpan</button>                <button type="reset" id="resetBtn" class="btn">Batal</button>            </div>        <?php } ?>        </form>    </div><!--//metadata-->    <div class="tab-pane" id="files">        <table class="table table-condensed" id="xtable">            <thead>                <tr>                    <th>No.</th>                    <th>Nama File</th>                    <th>Versi</th>				                    <th>#</th>                </tr>            </thead>            <tbody>		                <?php                $name_img = '';                if ($records['DOCUMENTS_ATC_SYSTEM']) {                    $num = 1;                    $doc_version = $records['VERSION_ID'];                    ?>                    <tr>                        <td><?php echo $num ?>.</td>	                        <td><?php echo $records['DOCUMENTS_ATC_SYSTEM'] ?></td>                        <td><?php echo $doc_version[0] . '.' . $doc_version[1] ?><?php echo ($doc_version[2] == 0) ? NULL : ' Revisi Ke - ' . $doc_version[2]; ?></td>					                        <td><a href="<?php echo base_url(UPLOAD_DOKPRO . $records['PK_DOCUMENTS_ID'] . '/' . $records['DOCUMENTS_ATC_SYSTEM']) ?>" target="_blank">View</a></td>	                    </tr>                    <?php                    $name_img .= $records['DOCUMENTS_ATC_SYSTEM'];                    $num++;                }                ?>            </tbody>        </table>        <?php echo form_open_multipart(site_url('documents/upload'), array('class' => 'form-horizontal', 'id' => 'xformUpload')); ?>        <div class="well well-small">            <input type="hidden" name="documents_id" id="documents_id" value="<?php echo $records['PK_DOCUMENTS_ID'] ?>">            <input type="hidden" name="img_name" id="img_name" value="<?php echo $name_img; ?>">            <input type="hidden" name="version_id" id="version_id" value="<?php echo $records['VERSION_ID'] ?>">            <input type="hidden" name="uid" id="uid" value="<?php echo $userInfo['uID'] ?>">            <input type="hidden" name="process_status" id="process_status" value="<?php echo $records['PROCESS_STATUS'] ?>">            <input type="file" accept="application/pdf" name="userfile" id="userfile">            <div class="help help-block">                <h5 style="text-decoration:underline;">CATATAN</h5>                <span><?php echo 'Jenis File yang diperbolehkan : ' . UPLOAD_DOKPRO_FILE_TYPE; ?></span><br>                <span><?php echo 'Maksimum file : ' . UPLOAD_DOKPRO_SIZE_MB . 'MB'; ?></span>	            </div>        </div>        <div class="form-actions">            <button type="submit" class="btn btn-primary data-load" id="uploadBtn" data-loading="Sedang Menyimpan...">Upload</button>            <button type="reset" class="btn" id="resetUploadBtn">Batal</button>        </div>        <?php echo form_close(); ?>	    </div><!--//files--></div><!--//tab-content--><script type="text/javascript" src="<?php echo base_url('assets/js/datepicker.js') ?>"></script><script type="text/javascript" src="<?php echo base_url('assets/js/chosen.min.js') ?>"></script><script type="text/javascript" src="<?php echo base_url('assets/js/form.js') ?>"></script><script type="text/javascript" src="<?php echo base_url('assets/js/nicEdit.js') ?>"></script><script type="text/javascript" src="<?php echo base_url('assets/js/jquery.chained.min.js') ?>"></script><script type="text/javascript">    $(function () {        document.descrip = new nicEditor({iconsPath: '<?php echo base_url('assets/js/nicEditIcons-latest.gif') ?>'}).panelInstance('descrip');        $("#datepub").datepicker({format: 'yyyy-mm-dd', weekStart: 1, noDefault: true});        $("#categories").chosen({disable_search_threshold: 10});//        var pS = '<?php echo $records['PROCESS_STATUS'] ?>';//        if (pS == '<?php echo DOC_DRAFT ?>')//        {//            $("#xform > input, #xform > textarea").attr('readonly');//        }        $("#resetBtn,#resetUploadBtn").click(function (e) {            var link_ = "<?php echo site_url('inbox') ?>/";            location.href = link_;        });        $("#submitBtn").click(function (e) {            // send nicEditor data - Bug : chrome tidak mengirim data nicEditor - 2015/01/04            $('#descrip').text(document.descrip.nicInstances[0].getContent());            // finally do submit - Bug : jika menggunakan button[type=submit] chrome tidak bisa otomatis submit            $('#xform').submit();        });        $("#uploadBtn").click(function (e) {            // finally do submit            $('#xformUpload').submit();        });        //        var e_url = "<?php echo current_url() ?>";//        var d_url = "<?php echo site_url('documents/edit_revisi/' . $records['PK_DOCUMENTS_ID']) ?>";//        var statusVal = false;        /* $("#distBtn").click(function(e){         $('#distForm').submit();         e.preventDefault();         }); */        $("#distBtn").click(function (e) {            var data_c = $(this).attr('data-confirm');            var cnf = confirm(data_c);            if (cnf) {                $('#distForm').submit();            } else {                return false;            }            e.preventDefault();        });        $("#commitBtn").click(function (e) {            var data_c = $(this).attr('data-confirm');            var cnf = confirm(data_c);            if (cnf) {                $('#commitForm').submit();            } else {                return false;            }            e.preventDefault();        });        var optionsDist = {            target: '#messageWrapper',            beforeSubmit: validateDist,            success: showResponseDist,            url: '<?php echo site_url('documents/dist') ?>',            type: 'post',            dataType: 'json'        };        $('#distForm').ajaxForm(optionsDist);        var optionsDist = {            target: '#messageWrapper',            beforeSubmit: validateDist,            success: showResponseDist,            url: '<?php echo site_url('documents/commit') ?>',            type: 'post',            dataType: 'json'        };        $('#commitForm').ajaxForm(optionsDist);        function validateDist(formData, jqForm, options)        {            $("#preloader").show();            $('#formProcess').append('<p class="alert alert-info">Mohon sabar menunggu selagi sistem sedang menyelesaikan tugasnya.</p>');            return true;        }        function showResponseDist(responseText, statusText, xhr, $form)        {            if (responseText.error == '1')            {                $('#messageWrapper').html('<div class="alert alert-error">' + responseText.response + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');            }            else            {                setInterval(function () {                    location.href = "<?php echo site_url('documents/detail/' . $records['PK_DOCUMENTS_ID']) ?>";                }, 2000);                $('#messageWrapper').html('<div class="alert alert-success">' + responseText.response + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');            }            return responseText.error;        }        function validate(formData, jqForm, options)        {            var msg = '';            var form = jqForm[0];            if (!form.title.value) {                msg += 'Mohon isikan judul pada dokumen.';                $('#messageWrapper').html('<div class="alert alert-error">' + msg + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');                return false;            } else if (!form.desc.value) {                msg += 'Mohon isikan deskripsi.';                $('#messageWrapper').html('<div class="alert alert-error">' + msg + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');                return false;            } else {                $("#preloader").show();                $('#formProcess').append('<p class="alert alert-info">Mohon sabar menunggu selagi sistem sedang menyelesaikan tugasnya.</p>');                return true;            }        }        function showResponse(responseText, statusText, xhr, $form)        {            if (responseText.error == '1')            {                $('#messageWrapper').html('<div class="alert alert-error">' + responseText.response + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');            }            else            {                setInterval(function () {                    location.href = "<?php echo site_url('documents/edit_revisi/' . $records['PK_DOCUMENTS_ID']) ?>";                }, 2000);                $('#messageWrapper').html('<div class="alert alert-success">Berhasil memperbaharui informasi dokumen.<a class="close" data-dismiss="alert" href="#">&times;</a></div>');            }            return responseText.error;        }        function create_field_file(fileId, fileName, versionId, fileSize, fileCdt)        {            var rowCount = $('#xtable > tbody > tr').length;            if (rowCount != 'undifined') {                rowCount++;            } else {                rowCount = 0;            }            var viewLink = "<?php echo site_url('attachment/view') ?>/" + fileId;            var outputStr = '<tr>';            outputStr += '<td>' + rowCount + '.</td>';            outputStr += '<td>' + fileName + '</td>';            outputStr += '<td>' + versionId + '</td>';            outputStr += '<td>' + fileSize + ' kb</td>';            outputStr += '<td>' + fileCdt + '</td>';            outputStr += '<td><a href=":;" id="viewBtn" data-rel="' + viewLink + '">View</a></td>';            outputStr += '</tr>';            return outputStr;        }        function validateFiles(formData, jqForm, options)        {            var msg = '';            var fileType = '<?php echo UPLOADFILETYPE ?>';            fileType = fileType.split("|");            var form = jqForm[0];            if (!form.documents_id.value) {                msg += 'Mohon isikan Dokumen ID.';                $('#messageWrapper').html('<div class="alert alert-error">' + msg + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');                return false;            }            if (!form.version_id.value) {                msg += 'Mohon isikan Rev ID.';                $('#messageWrapper').html('<div class="alert alert-error">' + msg + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');                return false;            }            for (var i = 0; i < form.length; i = i + 1)            {                var str = form[ i ].name;                var fieldValue = form[ i ].value;                var strFileType = fieldValue.substr(-3);                if (str.search('userfile') == 0 && $.inArray(strFileType.toLowerCase(), fileType) < 0)                {                    msg += 'Pastikan jenis lampiran diperbolehkan.';                    $('#messageWrapper').html('<div class="alert alert-error">' + msg + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');                    return false;                }            }        }        function showResponseFiles(responseText, statusText, xhr, $form)        {            if (responseText.error == '1')            {                $('#messageWrapper').html('<div class="alert alert-error">' + responseText.response + '.<a class="close" data-dismiss="alert" href="#">&times;</a></div>');            }            else            {                var output = responseText.response;                var atcCount = $("#atcCount").text();                $("#atcCount").text(parseInt(atcCount) + 1);                //var responseText = JSON.parse(responseText);                $("#xtable tbody").append(create_field_file(output.fileId, output.fileName, output.versionId, output.fileSize, output.fileCdt));                $('#messageWrapper').html('<div class="alert alert-success">Berhasil menambahkan ' + output.fileName + '.<a class="close" data-dismiss="alert" href="#">&times;</a></div>');            }        }    });</script>