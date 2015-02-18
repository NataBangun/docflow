<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Aplikasi Workflow Pengelolaan Dokumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="mmediadata.com">
    <meta name="robots" content="noindex, nofollow">
    <link href="<?php echo base_url('assets/css/jquery.dynatable.css')?>" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/main.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/responsive.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/jquery-ui.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/datepicker.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/fam-icons.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/fam-icons.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/pace-mini.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url('assets/js/respond.min.js')?>"></script>
    <![endif]-->

    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico')?>" type="image/x-icon">
    <link rel="icon" href="<?php echo base_url('assets/img/favicon.ico')?>" type="image/x-icon">
    <script src="<?php echo base_url('assets/js/jquery.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-ui.custom.js')?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.js')?>"></script>
    <script src="<?php echo base_url('assets/js/datepicker.js')?>"></script>
    <script src="<?php echo base_url('assets/js/timeago.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.dynatable.js')?>"></script>
	<script text="text/javascript">
		paceOptions = {
			restartOnRequestAfter: 100,
			elements: false,
			eventLag: false,
			ajax: {
				trackMethods: ['GET', 'POST'],
				trackWebSockets: false
			}
		}

		function paceBeforeUnload() {
			Pace.restart();
			Pace.sources.push({ progress: 50 }); // force Pace to show progress while unload
		}
		
		$(document).ajaxStop(function() {
			Pace.stop(); // fix bug for IE8 - http://theie8countdown.com/
		});
	</script>
	<script src="<?php echo base_url('assets/js/pace.min.js')?>"></script>
	<script text="text/javascript">
		Pace.on('restart', function() {
			$('#tawaf').removeClass('hide');
		});
		Pace.on('hide', function() {
			$('#tawaf').addClass('hide');
		});
	</script>
  </head>

  <body onbeforeunload="paceBeforeUnload();">
	<div id="tawaf" style="z-index: 2000; position: fixed; height: 90px; width: 90px; margin: auto; top: 0; left: 0; right: 0; bottom: 0;">
		<img src="<?php echo base_url('assets/img/loader_tawaf.gif')?>">
	</div>
    <div class="navbar navbar-fixed-top">		
		<div class="top-header">
			<div class="container-fluid">
				<h3>Aplikasi Workflow Pengelolaan Dokumen</h3>
			</div>
		</div>

		<span style="clear:both"></span>
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo site_url('dashboard')?>">Home</a>
          <div class="nav-collapse collapse">
		  
			<ul class="nav">	
				<!--<?php echo $this->session->userdata('umc_feature');?>-->
				<?php if (in_array("Setting Tandatangan", explode("|", $this->session->userdata('umc_feature')))
				|| in_array("Setting Jenis Dokumen", explode("|", $this->session->userdata('umc_feature')))
				|| in_array("Setting Workflow", explode("|", $this->session->userdata('umc_feature')))
				) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle <?php echo ($this->uri->segment(1)=='usr' || $this->uri->segment(1)=='type' || $this->uri->segment(1)=='categories')?'here':NULL?>" data-toggle="dropdown" title="Masterdata"><i class="fam-cog"></i> Konfigurasi <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php if (in_array("Setting Tandatangan", explode("|", $this->session->userdata('umc_feature')))) { ?>
						<li><a href="<?php echo site_url('usr')?>" onclick="localStorage.clear();">Paraf & Tanda Tangan</a></li>	
						<?php }?>
						<?php if (in_array("Setting Jenis Dokumen", explode("|", $this->session->userdata('umc_feature')))) { ?>
						<li><a href="<?php echo site_url('type')?>" onclick="localStorage.clear();">Jenis Dokumen</a></li>	
						<?php }?>
						<?php if (in_array("Setting Workflow", explode("|", $this->session->userdata('umc_feature')))) { ?>
						<li><a href="<?php echo site_url('categories')?>" onclick="localStorage.clear();">Pengaturan Kategori & Workflow</a></li>	
						<?php }?>					
					</ul>
				</li>
				<?php }?>
						
									
				
				
				<?php if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature')))) 
					{ ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle <?php echo ($this->uri->segment(1)=='documents' || $this->uri->segment(1)=='documents' && $this->uri->segment(2)=='add')?'here':NULL?>" data-toggle="dropdown" title="Dokumen Prosedur"><i class="fam-folder-page-white"></i> Dokumen Prosedur <b class="caret"></b></a>
					<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('documents')?>" onclick="localStorage.clear();">Daftar Dokumen Prosedur</a></li>
					<li><a href="<?php echo site_url('documents/add')?>" onclick="localStorage.clear();">Posting Dokumen Prosedur</a></li>
					</ul>
				</li>
				<?php } ?>
				
				<?php if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))) 
					{ ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle <?php echo ($this->uri->segment(1)=='nota' || $this->uri->segment(1)=='nota' && $this->uri->segment(2)=='add')?'here':NULL?>" data-toggle="dropdown" title="Nota Dinas"><i class="fam-note"></i> Nota Dinas<b class="caret"></b></a>
					<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('nota')?>" onclick="localStorage.clear();">Daftar Nota Dinas</a></li>
					<li><a href="<?php echo site_url('nota/add')?>" onclick="localStorage.clear();">Posting Nota Dinas</a></li>
					</ul>
				</li>
				
				<?php } ?>

				<?php if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
					|| in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
					|| in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature'))) 
					|| in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature'))) 
					|| in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))) 
					{ ?>
				<li><a href="<?php echo site_url('inbox')?>" class="<?php echo ($this->uri->segment(1)=='inbox')?'here':NULL?>" onclick="localStorage.clear();"><i class="fam-folder-database"></i> Inbox Dokumen<?php echo ($service['myInbox']['total']>0)?' <sup>'.$service['myInbox']['total'].'</sup>':'';?></a></li>
				<?php } ?>
				<?php if (in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
					|| in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature'))) 
					|| in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature'))) 
					) 
					{ ?>				
				<li><a href="<?php echo site_url('monitoring/document')?>" onclick="localStorage.clear();" class="<?php echo ($this->uri->segment(1)=='monitoring' && $this->uri->segment(2)=='document')?'here':NULL?>"><i class="fam-folder-explore"></i> Monitoring Dokumen</a></li>				
				<?php } ?>
								
				
				<?php /*  if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
				|| in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
				|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
				) { ?>
				<li><a href="<?php echo site_url('inbox_nota')?>" class="<?php echo ($this->uri->segment(1)=='inbox_nota')?'here':NULL?>"><i class="fam-folder-page"></i> Inbox Nota<?php echo ($service['myInbox']['total']>0)?' <sup>'.$service['myInbox']['total'].'</sup>':'';?></a></li>
				<?php } */?>
				

				<?php /*if (in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
					|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature')))) 
					{ ?>
				
				<li><a href="<?php echo site_url('monitoring/nota')?>" class="<?php echo ($this->uri->segment(1)=='monitoring' && $this->uri->segment(2)=='nota')?'here':NULL?>"><i class="fam-folder-magnify"></i> Monitoring Nota</a></li>
				<?php } */?>
				
			</ul>					
			
            <ul class="nav pull-right">
              <li class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fam-user-suit"></i> 
				  <small><?php echo ($userInfo['fullname'])?$userInfo['fullname']:'No Login';?></small> <b class="caret"></b></a>
                  <ul class="dropdown-menu">

					<li><a id="linkBtn" href=":;" data-rel="<?php echo site_url('help')?>"><i class="fam-book"></i> Panduan Pemakai</a></li>
					<li><a id="loadModal" href=":;" data-rel="<?php echo site_url('info')?>"><i class="fam-information"></i> Info Aplikasi</a></li>
                   
                    <li><a href="<?php echo site_url('logout')?>" title="Keluar Sistem"><i class="fam-disconnect"></i> Logout</a></li>
                  </ul>
              </li>
            </ul>			
			
		  </div><!--//nav-collapse-->
		</div><!--//container-fluid-->
	  </div><!--//navbar-inner-->
	  
	</div><!--//navbar-->
			
	<div class="clearfix" style="height:45px;">&nbsp;</div>

    <div class="container-fluid">
		
		<?php
		echo ( ( $this->session->flashdata('error') ) ? '<div class="alert alert-error">'.$this->session->flashdata('error').'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' : NULL );
		echo ( ( $this->session->flashdata('success') ) ? '<div class="alert alert-success">'.$this->session->flashdata('success').'<a class="close" data-dismiss="alert" href="#">&times;</a></div>' : NULL );
		?>