$(function () {

    $('#dp1,#dp2,#dp3,#dp4').datepicker({format: 'dd-mm-yyyy', weekStart: 1, noDefault: true});

    $('[class="timeago"]').timeago();

    /* hide links */
    $('[id="linkBtn"]').click(function () {
        var txt = $(this).attr('data-rel');
        location.href = txt;
        return false;
    });

    $('[id="deletelink"]').click(function (event) {
        xconfirm = confirm("Anda yakin akan menghapus data?");
        if (xconfirm)
        {
            var txt = $(this).attr('data-rel');
            location.href = txt;
        }
        event.preventDefault();
        return false;
    });

    $('[id="nonlink"]').click(function (event) {
        xconfirm = confirm("Anda yakin akan non-aktifkan data?");
        if (xconfirm)
        {
            var txt = $(this).attr('data-rel');
            location.href = txt;
        }
        event.preventDefault();
        return false;
    });

    $('[id="aclink"]').click(function (event) {
        xconfirm = confirm("Anda yakin akan aktifkan data?");
        if (xconfirm)
        {
            var txt = $(this).attr('data-rel');
            location.href = txt;
        }
        event.preventDefault();
        return false;
    });

    $('#xtable').on("click", '[id="linkBtn"]', function (event) {
        var txt = $(this).attr('data-rel');
        location.href = txt;
        event.preventDefault();

    });

    $('#xtable').on("click", '[id="deletelink"]', function (event) {
        xconfirm = confirm("Anda yakin akan menghapus data?");
        if (xconfirm)
        {
            var txt = $(this).attr('data-rel');
            location.href = txt;
        }
        event.preventDefault();
        return false;
    });

    $(".collapse").collapse({
        toggle: false
    });

    /* jumpmenus */
    $("#jumpmenu").change(function () {
        $("option:selected").each(function () {
            var url = $(this).val();
            location.href = url;
        });
    });

    /* checkboxes */
    $("input[id=checkall]").each(function () {
        var trigger = $(this);
        trigger.click(function () {
            var checked_status = this.checked;
            $("input:checkbox").each(function () {
                this.checked = checked_status;
            });
        });
    });

    $("input:checkbox").click(function () {
        if ($("input:checked").length > 0) {
            $("#delete_all").removeAttr("disabled");
        } else {
            $("#delete_all").attr("disabled", "disabled");
        }
    });

    /* multiple delete */
    $("[id=delete_all]").click(function () {
        return confirm('Yakin akan menghapus data yang dipilih?');
    });

    /* modal */
    $("#loadModal").click(function (event) {
        $("#preloaderModal").show();
        var href_ = $(this).attr('data-rel');
        $('#myModal').modal({
            show: 'show',
            remote: href_
        });
        event.preventDefault();
    });

    /* multiple form */
    var count_1 = count_2 = count_3 = count_4 = count_5 = count_6 = 1;
    $('body').on('click', '#addMore', function () {
        $('#targetForm').append('<div id="close-bar"><div class="control-group child-bar"><label class="control-label no-border"></label><div class="controls"><div class="input-append"><input type="hidden" name="val" value="' + (++count_4) + '"><input type="hidden" value="' + (++count_6) + '" name="order_status' + (++count_3) + '" class="span1" style="margin-right:4px;" placeholder="urutan ' + (++count_6) + '"><input class="span3" id="appendedInputButton" type="text" name="add' + (++count_1) + '" placeholder="Masukan Judul Kategori"><input type="text" name="pdf_title' + (++count_5) + '" class="span2" style="margin-left: 5px;" placeholder="Judul Pada Pdf"><input type="radio" name="check_status' + (++count_2) + '" value="0" checked style="margin-left: 3px;" > seri<i> &nbsp &nbsp </i><input type="radio" name="check_status' + (count_2) + '" value="1"> pararel<i> &nbsp &nbsp </i><button class="btn btn-danger remove" type="button" style="margin-left: -8px;"><i class="fam-cancel"></i></button></div></div></div></div>');

        $(".remove").click(function (e) {
            $(this).closest("#close-bar").remove();
            e.preventDefault();
        });
    });
    $('body').on('click', '#atch', function () {
        $('#wraper-atch').append('<div id="atch-container"><input type="file" class="span3" name="files[]"><a class="btn btn-danger atch-close" style="margin-left: 3px;"><i class="fam-cancel"></i></a></div>');

        $(".atch-close").click(function (e) {
            $(this).closest("#atch-container").remove();
            e.preventDefault();
        });
    });
    var dist = 1;
    $('body').on('click', '#addDist', function () {
        $('#targetDist').append('<li id="close-dist"><input class="span5" id="appendedInputButton" type="text" name="distribution[]" placeholder="Ketikkan Distribusi Kepada"><button class="btn btn-danger remove" type="button"><i class="fam-cancel"></i></button></li>');
        $(".remove").click(function (e) {
            $(this).closest("#close-dist").remove();
            e.preventDefault();
        });
    });
    if ($('#addDist').hasClass('btn')) {
        $(".remove").click(function (e) {
            $(this).closest("#close-dist").remove();
            e.preventDefault();
        });
    }

    $('body').on('click', '#addTmb', function () {
        $('#targetTmb').append('<li id="close-tmb" style="margin-top: 5px;"><input class="span5" id="appendedInputButton" type="text" name="tembusan1[]" placeholder="Ketikan Tembusan"><button class="btn btn-danger remove" type="button"><i class="fam-cancel"></i></button></li>');
        $(".remove").click(function (e) {
            $(this).closest("#close-tmb").remove();
            e.preventDefault();
        });
    });

    $('body').on('click', '#addKpd', function () {
        $('#targetKpd').append('<li id="close-kpd" style="margin-top: 5px;"><input class="span5" id="appendedInputButton" type="text" name="kepada[]" placeholder="Ketikan Kepada"><button class="btn btn-danger remove" type="button"><i class="fam-cancel"></i></button></li>');
        $(".remove").click(function (e) {
            $(this).closest("#close-kpd").remove();
            e.preventDefault();
        });
    });

    /* e_close (multiple form) */
    var CP = $('#CP').attr('data-proccess');
    var count = count2 = count3 = count4 = count5 = count6 = CP;
    $('body').on('click', '#addMore2', function () {

        $('#e_Form').append('<div id="e_close" style="margin-bottom: 10px;" class="controls"><div class="input-append"><input type="hidden" value="' + (++count6) + '" name="val"><input type="text" value="" name="add' + (++count5) + '" placeholder="Masukan Judul Kategori" class="span3"><input type="text" value="" placeholder="Judul Pada Pdf" name="pdf_title' + (++count) + '" id="appendedInputButton" class="span2" style="margin-left: 4px;"><input type="radio" checked="" value="0" name="check_status' + (++count2) + '" style="margin-left: 4px;"> seri<i> &nbsp; &nbsp; </i><input type="radio" value="1" name="check_status' + (++count3) + '"> pararel<i> &nbsp; &nbsp; </i><input type="hidden" value="' + (++count4) + '" placeholder="urutan 1" name="order_status' + (count4) + '" class="span1"><button class="btn btn-danger e_remove" type="button" style="margin-left: -5px;"><i class="fam-cancel"></i></button></div></div>');

        $(".e_remove").click(function (e) {
            $(this).closest("#e_close").remove();
            e.preventDefault();
        });
    });
    $(".e_remove").click(function (e) {
        $(this).closest("#e_close").remove();
        e.preventDefault();
    });

    /* chosen */
    $('#categories').on("change", function () {
        var data_cg_key = $('#data_cg_key').attr('data-id');
        var list_value = $(this).val();

        if (list_value > 0) {
            $(data_cg_key).hide();
            $('.' + list_value).show();
        } else {
            $(data_cg_key).hide();
        }
    });

    /* doc-type */
    $('#doc-type').on("change", function () {
        var list_value = $(this).val();
        var srt = $('#close-doc-type');

        if (list_value == '1') {
            $(srt).show();
        } else {
            $(srt).hide();
        }
    });
    
    /* loading */
    $('.data-load').click(function () {
        $('.data-load').submit();
        var load = $(this).attr('data-loading');
        $(this).text(load);
        $(this).enable(false);
    });

});