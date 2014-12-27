<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/* 
*  Documents Process 
*  1=draft; 2=edit; 3=review/approval; 4=publication;
*  see documents_process
*/
define('DOC_DRAFT', 1);
define('DOC_EDIT', 2);
define('DOC_REVIEW', 3);
define('DOC_FINAL', 4);
define('DOC_CLOSED', 'Publikasi/Final');
/* 
*  Documents Actions 
*  see documents_step
*/
define('ACTION_UNREAD', 0);
define('ACTION_READ', 1);
define('ACTION_REJECT', 3);
define('ACTION_APPROVE', 2);
define('ACTION_FINAL', 99);

define('NOTA_DRAFT', 1);
define('NOTA_EDIT', 2);
define('NOTA_REVIEW', 3);
define('NOTA_FINAL', 4);
define('NOTA_CLOSED', 'Publikasi/Final');

/* Directory */
define('UPLOADDIR', './uploads/');
define('UPLOADSIZE', '8024');//8MB
define('UPLOADFILETYPE', 'pdf');

/* SYS */
define('CACHE_MIN_EXP', 120);
define('CACHE_MID_EXP', 300);//5min
define('CACHE_MAX_EXP', 1800);

/* WEBSERVICE & WSDL */
define('WEBSERVICE_SSO', 'https://10.24.14.51/sso/');
//define('WEBSERVICE_SSO', 'https://portal/sso2/');
define('WEBSERVICE_SSO_WSDL', 'http://10.24.14.51/sso/ValidateTicket.asmx?WSDL');
//define('WEBSERVICE_SSO_WSDL', 'http://portal/sso2/ValidateTicket.asmx?WSDL');
//define('WEBSERVICE_UMC_WSDL', 'http://10.24.14.51/umcservice/service.asmx?WSDL');
define('WEBSERVICE_UMC_WSDL', 'http://10.24.14.51/umcservice/service.asmx?WSDL');
define('JAVA_DIR', 'C:\Program Files (x86)\Java\jdk1.6.0_21\bin');

/* End of file constants.php */
/* Location: ./application/config/constants.php */