<div class="page-header">
    <h4>Dashboard</h4>
</div>
<!-- 
<?php echo $this->session->userdata('umc_feature'); ?>
-->
<div class="clearfix">&nbsp;</div>
<div class="row-fluid">
    <?php
    if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) || in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) || in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) || in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) || in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature')))
    ) {
        ?>
        <div class="span6">
            <h4 style="padding-bottom: 20px;">Dokumen Prosedur </h4>
            <!--start pagination-->
            <?php echo $doc_dashboard; ?>
            <!--end pagination-->
        </div>
        <?php
    }
    if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature'))) || in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature'))) || in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature'))) || in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))
    ) {
        ?>
        <div class="span6">
            <h4 style="padding-bottom: 20px;">Nota Dinas</h4>
            <!--start pagination-->
            <?php echo $nota_dashboard; ?>
            <!--end pagination-->		
        </div>
    <?php } ?>
</div>
<div class="clearfix">&nbsp;</div>
