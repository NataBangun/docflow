<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $this->config->item('app_abbr')?> :: <?php echo $this->config->item('app_name')?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="mmediadata.com">
<link href="<?php echo base_url('assets/css/login.css')?>" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="<?php echo base_url('assets/js/respond.min.js')?>"></script>
<![endif]-->

<link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico')?>">

</head>

<body>

<!-- BODY -->
<div class="container">

  <div class="header">
    <img src="<?php echo base_url('assets/img/logo.png')?>" alt="Logo" />
  </div>
  
  <?php echo form_open(site_url('login'), array('class'=>'', 'id'=>'loginForm'))?>

  <?php echo ( (isset($error) && $error!='') ? '<div class="warning" style="text-align:center;">'.$error.'</div>' : '' ) ?>
	
  <h4 class="legend"><?php echo $this->config->item('app_name')?></h4>

  <div id="status"></div>
  <div id="loader"></div>

  <input type="text" name="usr" id="usr" placeholder="Username">
  <?php echo form_error('usr')?>
  
  <input type="password" name="pwd" id="pwd" placeholder="Password">
  <?php echo form_error('pwd')?>
  
  <input type="submit" class="btn" id="submit" value="Masuk">
  
  </form>  
    
  <div class="require">
    <h5>Mohon gunakan browser tercantum.</h5>
    <img src="<?php echo base_url('assets/img/browsers.png')?>" width="100" /><br />
    <span>Chrome, Firefox atau Opera.</span> 
  </div>

  <div class="footer">
  	<hr>
    <small><?php echo $this->config->item('app_abbr') . ' - ' . $this->config->item('app_name') . ' ver.' . $this->config->item('app_ver')?></small>
  </div>

</div><!-- /BODY -->

<script type="text/javascript">
	document.getElementById("usr").focus();
</script>

</body>
</html>