<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* PARAMETER */

$config['app_abbr']		= 'LDW';
$config['app_name']		= 'Lintasarta Document Workflow';
$config['app_ver']		= '1.0.0';
$config['salt'] 		= 'MMEDIADATA';
$config['email_form'] 	= 'info@mmediadata.com';

$config['auth_array'] = array(0=>'Sembunyikan', 1=>'Lihat', 2=>'Tulis', 3=>'Edit', 4=>'Full Akses');
$config['auth_pages'] = array('usr'=>'Halaman User', 
							'rol'=>'Halaman Roles', 
							'grp'=>'Halaman Group',
							'sys'=>'Konfigurasi Sistem',
							'log_srv'=>'Halaman Riwayat Server', 
							'util'=>'Sistem', 
							'masterdata'=>'Masterdata', 
							'doc'=>'Dokumen Kontrol', 
							'nota'=>'Nota Dinas'
							);

$config['doc_status'] = array(1=>'Pemeriksaan', 2=>'Persetujuan', 3=>'Pengesahan', 4=>'Legalisasi', 99=>'Publikasi/Final');

$config['doc_user'] = array(1=>'draft',2=>'Revisi', 3=>'Sedang Berjalan', 4=>'Publikasi/Final');

$config['nota_status'] = array(1=>'draft',2=>'Revisi', 3=>'Pengesahan', 4=>'Publikasi/Final');

$config['act_status'] = array(0=>'Belum Dibaca', 1=>'Sudah Dibaca', 2=>'Approve', 3=>'Reject');

$config['act_status_icon'] = array(0=>array('Belum Dibaca','<i class="fam-book"></i>'), 1=>array('Sudah Dibaca','<i class="fam-book-open"></i>'), 2=>array('Approve','<i class="fam-accept"></i>'), 3=>array('Reject','<i class="fam-error"></i>'));

$config['step_layer'] = array(1=>'Pemeriksa', 2=>'Menyetujui', 3=>'Pengesahan', 4=>'Legalisir');

$config['nota_step_layer'] = array(1=>'Pengesahan',2=>'Pengesahan',3=>'Pengesahan',99=>'Publikasi/Final');

$config['dayArray'] = array('Sunday'=>'Minggu', 'Monday'=>'Senin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Kamis', 'Friday'=>'Juma\'at', 'Saturday'=>'Sabtu');
$config['monthArray'] = array('January ',' February ',' March ',' April ',' May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$config['shortDayArray'] = array('M', 'S', 'S', 'R', 'K', 'J', 'S');

/* Error reporting */
$config['msg_login_fail1'] = 'I cannot find your account, are you registered user in this system?, if yes please contact Administrator';
$config['msg_conf_account1'] = 'Your account is not configured well, please contact administrator.';
$config['msg_conf_account2'] = 'Your account is not configured well or you dont have permission to login, please contact administrator.';
$config['msg_forbid_page'] = 'Mohon maaf Anda tidak diperbolehkan mengakses halaman tersebut.';
$config['msg_no_data'] = 'Mohon maaf kami tidak menemukan yang Anda cari/akses.';
$config['msg_active'] = 'Data berhasil diaktifkan Kembali.';
$config['msg_non_active'] = 'Data berhasil dinon-aktifkan.';
$config['msg_doc_on_track'] = 'Dokumen tidak bisa diedit karena masih dalam masa sosialisasi.';
$config['msg_delete'] = 'Data berhasil dihapus.';
$config['msg_doc_final'] = 'Dokumen tidak bisa diedit karena sudah selesai dan harus dipublikasi.';

/* Email Configuration */
$config['mail_conf']['document_init_title'] = 'Mohon verifikasi dokumen';
$config['mail_conf']['document_complete_phase'] = 'Dokumen telah melewati fase verifikasi dan siap diterbitkan.';
$config['mail_conf']['document_author_reminder'] = 'Dokumen anda mendapat balasan mohon cek dokumen tersebut.';
$config['mail_conf']['mail_footer'] = "===========================================================================
***** This message may contain confidential and/or privileged information. If you are not the addressee or authorized to receive this for the addressee, you must not use, copy, disclose or take any action based on this message or any information herein. If you have received this communication in error, please notify us immediately by responding to this email and then delete it from your system. PT Lintasarta (Persero) is neither liable for the proper and complete transmission of the information contained in this communication nor for any delay in its receipt. ***** 

";
