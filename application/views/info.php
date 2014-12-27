<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Tentang <?php echo $config['app_abbr'].' - '.$config['app_name']?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="mmediadata.com">
</head>

<body>

<p>
  <h4><?php echo $config['app_abbr'].' - '.$config['app_name']?></h4>
  <small>Aplikasi workflow dan nota dinas PT. Lintasarta.</small>
</p>

<p>
  <span><strong>Versi:</strong> &nbsp;<?php echo $config['app_ver']?></span><br>
  <span><strong>Terbit:</strong> &nbsp;September 2013</span><br>
  <span><strong>Developer:</strong> &nbsp; PT. Napta<span><br>
  <span><strong>Update:</strong> &nbsp;<span id="updater"><?php echo ($updates)?></span></span>
</p>

</body>
</html>