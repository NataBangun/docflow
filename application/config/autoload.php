<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$autoload['packages'] = array();

$autoload['libraries'] = array('database', 'session', 'form_validation');

$autoload['helper'] = array('url', 'form', 'mm_util');

$autoload['config'] = array('mm_config');

$autoload['language'] = array();

$autoload['model'] = array('mm_session','mm_service');

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */