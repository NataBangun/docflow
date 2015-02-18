<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mm_documents_email extends CI_Model {

	var $email_penyusun;

	function __construct()
	{
		parent::__construct();
	}
	
	function get_email_to($doc_id)
	{
		$sql = "
			SELECT 
				T3.EMPLOYEE_NO,
				T3.EMPLOYEE_NAME,
				T3.E_MAIL_ADDR 
			FROM H_DOCUMENTS_PROCESS T1, H_DOCUMENTS_APPROVAL T2, V_EMPLOYEE T3
			WHERE T1.FK_DOCUMENTS_ID = T2.FK_DOCUMENTS_ID
				AND T1.FK_TYPE_ID = T2.FK_TYPE_ID
				AND T1.CURRENT_LAYER = T2.STEP_LAYER
				AND T1.VERSION_ID = T2.VERSION_ID
				AND T1.FK_DOCUMENTS_ID = ".$doc_id."
				AND T1.FK_TYPE_ID = 1 -- doc prosedur
				AND T2.APPROVAL_STATUS = 0 -- ACTION_UNREAD
				AND T2.EMPLOYEE_NO = T3.EMPLOYEE_NO";
		$result = $this->db->query($sql)->result();
		return $result;
	}
	
	function get_data($doc_id)
	{
		$sql = "
            SELECT
                T1.DOCUMENTS_NO,
                T1.DOCUMENTS_TITLE,
                T2.CATEGORIES_TITLE,
                T3.EMPLOYEE_NO,
                T3.EMPLOYEE_NAME,
				T1.DOCUMENTS_CDT,
                T3.E_MAIL_ADDR,
                F_GET_DOCUMENTS_VERSION(T4.VERSION_ID) as VERSION_ID
            FROM T_DOCUMENTS T1, P_CATEGORIES T2, V_EMPLOYEE T3, H_DOCUMENTS_PROCESS T4
            WHERE T1.FK_CATEGORIES_ID=T2.PK_CATEGORIES_ID
                AND T1.DOCUMENTS_CBY=T3.EMPLOYEE_NO
                AND T1.PK_DOCUMENTS_ID=T4.FK_DOCUMENTS_ID
                AND T4.FK_TYPE_ID=1
                AND T1.PK_DOCUMENTS_ID = ".$doc_id;
		$row = $this->db->query($sql)->row();
		$this->email_penyusun = $row->E_MAIL_ADDR;
		return $row;
	}
	
	function get_approval($doc_id)
	{
		$row = $this->get_data($doc_id);
		$no_dokumen = $row->DOCUMENTS_NO;
		$judul_dokumen = $row->DOCUMENTS_TITLE;
		$kategori_prosedur = $row->CATEGORIES_TITLE;
		$penyusun = $row->EMPLOYEE_NAME.' ('.$row->E_MAIL_ADDR.')';
		$versi = $row->VERSION_ID;
		$tanggalpembuatan = $row->DOCUMENTS_CDT;
		$base_url = base_url();
	
		$message = <<<EOD
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Dengan hormat,</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Berikut permohonan Dokumen Prosedur :</span></p>
<table border="0" cellpadding="0" cellspacing="0" style="padding: 0px; border-collapse: collapse; word-break: break-word; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 13px; background-color: rgb(255, 255, 255);">
	<tbody style="width: 575px;">
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: solid none solid solid; border-top-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-left-color: rgb(79, 129, 189); border-top-width: 1pt; border-bottom-width: 1pt; border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Nomor Dokumen</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: solid solid solid none; border-top-color: rgb(79, 129, 189); border-right-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-top-width: 1pt; border-right-width: 1pt; border-bottom-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$no_dokumen</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Judul Dokumen</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$judul_dokumen</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none none solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Kategori Prosedur</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid none none; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$kategori_prosedur</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: solid none solid solid; border-top-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-left-color: rgb(79, 129, 189); border-top-width: 1pt; border-bottom-width: 1pt; border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Penyusun</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: solid solid solid none; border-top-color: rgb(79, 129, 189); border-right-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-top-width: 1pt; border-right-width: 1pt; border-bottom-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$penyusun</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Versi</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$versi</span></p>
			</td>
		</tr>
				<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Tanggal Pembuatan</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$tanggalpembuatan</span></p>
			</td>
		</tr>
	</tbody>
</table>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">yang memerlukan persetujuan anda.</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Silahkan klik link berikut untuk melihat detail Dokumen Prosedur &amp; melakukan persetujuan :</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<b><span style="font-size: 10pt;"><a href="$base_url" rel="nofollow" shape="rect" style="margin: 0px; padding: 0px; background-color: rgba(0, 0, 0, 0); color: purple; outline: none;" target="_blank">link aplikasi</a></span></b></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<b><span style="font-size: 10pt;">&nbsp;</span></b></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Atas perhatiannya kami ucapkan terimakasih.</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<i><span style="font-size: 8pt;">*Email ini digenerate otomatis oleh system</span></i></p>
EOD;
		
		return $message;
	}

	function get_revisi($doc_id)
	{
		$row = $this->get_data($doc_id);
		$no_dokumen = $row->DOCUMENTS_NO;
		$judul_dokumen = $row->DOCUMENTS_TITLE;
		$kategori_prosedur = $row->CATEGORIES_TITLE;
		$penyusun = $row->EMPLOYEE_NAME.' ('.$row->E_MAIL_ADDR.')';
		$versi = $row->VERSION_ID;
		$base_url = base_url();
		
		$message = <<<EOD
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Dengan hormat,</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Berikut permohonan revisi Dokumen Prosedur :</span></p>
<table border="0" cellpadding="0" cellspacing="0" style="padding: 0px; border-collapse: collapse; word-break: break-word; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 13px; background-color: rgb(255, 255, 255);">
	<tbody style="width: 575px;">
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: solid none solid solid; border-top-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-left-color: rgb(79, 129, 189); border-top-width: 1pt; border-bottom-width: 1pt; border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Nomor Dokumen</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: solid solid solid none; border-top-color: rgb(79, 129, 189); border-right-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-top-width: 1pt; border-right-width: 1pt; border-bottom-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$no_dokumen</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Judul Dokumen</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$judul_dokumen</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none none solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Kategori Prosedur</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid none none; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$kategori_prosedur</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: solid none solid solid; border-top-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-left-color: rgb(79, 129, 189); border-top-width: 1pt; border-bottom-width: 1pt; border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Penyusun</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: solid solid solid none; border-top-color: rgb(79, 129, 189); border-right-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-top-width: 1pt; border-right-width: 1pt; border-bottom-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$penyusun</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Versi</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$versi</span></p>
			</td>
		</tr>
						<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Tanggal Pembuatan</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$tanggalpembuatan</span></p>
			</td>
		</tr>
	</tbody>
</table>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Silahkan klik link berikut untuk melihat detail revisi :</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<b><span style="font-size: 10pt;"><a href="$base_url" rel="nofollow" shape="rect" style="margin: 0px; padding: 0px; background-color: rgba(0, 0, 0, 0); color: purple; outline: none;" target="_blank">link aplikasi</a></span></b></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<b><span style="font-size: 10pt;">&nbsp;</span></b></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Atas perhatiannya kami ucapkan terimakasih.</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	&nbsp;</p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<i><span style="font-size: 8pt;">*Email ini digenerate otomatis oleh system</span></i></p>
EOD;
		
		return $message;
	}
	
	function get_selesai($doc_id)
	{
		$row = $this->get_data($doc_id);
		$no_dokumen = $row->DOCUMENTS_NO;
		$judul_dokumen = $row->DOCUMENTS_TITLE;
		$kategori_prosedur = $row->CATEGORIES_TITLE;
		$penyusun = $row->EMPLOYEE_NAME.' ('.$row->E_MAIL_ADDR.')';
		$versi = $row->VERSION_ID;
		$base_url = base_url();
		
		$message = <<<EOD
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Dengan hormat,</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Dengan ini diberitahukan, bahwa Dokumen Prosedur :</span></p>
<table border="0" cellpadding="0" cellspacing="0" style="padding: 0px; border-collapse: collapse; word-break: break-word; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 13px; background-color: rgb(255, 255, 255);">
	<tbody style="width: 575px;">
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: solid none solid solid; border-top-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-left-color: rgb(79, 129, 189); border-top-width: 1pt; border-bottom-width: 1pt; border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Nomor Dokumen</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: solid solid solid none; border-top-color: rgb(79, 129, 189); border-right-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-top-width: 1pt; border-right-width: 1pt; border-bottom-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$no_dokumen</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Judul Dokumen</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$judul_dokumen</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none none solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Kategori Prosedur</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid none none; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$kategori_prosedur</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: solid none solid solid; border-top-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-left-color: rgb(79, 129, 189); border-top-width: 1pt; border-bottom-width: 1pt; border-left-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Penyusun</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: solid solid solid none; border-top-color: rgb(79, 129, 189); border-right-color: rgb(79, 129, 189); border-bottom-color: rgb(79, 129, 189); border-top-width: 1pt; border-right-width: 1pt; border-bottom-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$penyusun</span></p>
			</td>
		</tr>
		<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Versi</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$versi</span></p>
			</td>
		</tr>
						<tr style="min-height: 0.2in;">
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 99pt; border-style: none none solid solid; border-left-color: rgb(79, 129, 189); border-left-width: 1pt; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; background-color: rgb(198, 217, 241); padding: 0in 5.4pt; min-height: 0.2in;" width="132">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<b><span style="font-size: 8pt;">Tanggal Pembuatan</span></b></p>
			</td>
			<td colspan="1" rowspan="1" style="border-spacing: 2px; width: 310.1pt; border-style: none solid solid none; border-bottom-color: rgb(79, 129, 189); border-bottom-width: 1pt; border-right-color: rgb(79, 129, 189); border-right-width: 1pt; padding: 0in 5.4pt; min-height: 0.2in;" width="413">
				<p style="padding: 0px; margin: 0px 0in; font-size: 12pt;">
					<span style="font-size: 8pt;">$tanggalpembuatan</span></p>
			</td>
		</tr>
	</tbody>
</table>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">telah selesai proses approval.</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	&nbsp;</p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Silahkan klik link berikut untuk melihat detail Dokumen Prosedur :</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<b><span style="font-size: 10pt;"><a href="$base_url" rel="nofollow" shape="rect" style="margin: 0px; padding: 0px; background-color: rgba(0, 0, 0, 0); color: purple; outline: none;" target="_blank">link aplikasi</a></span></b></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<b><span style="font-size: 10pt;">&nbsp;</span></b></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">Atas perhatiannya kami ucapkan terimakasih.</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	&nbsp;</p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<span style="font-size: 10pt;">&nbsp;</span></p>
<p style="padding: 0px; margin: 0px 0in; font-size: 12pt; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, 'Lucida Grande', sans-serif; background-color: rgb(255, 255, 255);">
	<i><span style="font-size: 8pt;">*Email ini digenerate otomatis oleh system</span></i></p>
EOD;
		
		return $message;
	}
	
}