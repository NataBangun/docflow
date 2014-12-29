	<!--/footer-->
    <div class="footer">
      <small><?php echo $config['app_abbr'].' :: '.$config['app_name'].' @'.date('Y').'<br>(Best view at Google Chrome 26 or Firefox 3-0 above on 1024x768 resolution)'?></small>
    </div>

    </div><!--/container-fluid-->
	
	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="modalLabel">Tentang Aplikasi</h3>
		</div>
		<div class="modal-body"><img src="<?php echo base_url('assets/img/loader.gif')?>" id="preloaderModal" style="display:none;margin:0 auto;text-align:center;"></div>
		<div class="modal-footer"></div>
	</div>	
	
	<script src="<?php echo base_url('assets/js/plugins.js')?>"></script>

  </body>

</html>