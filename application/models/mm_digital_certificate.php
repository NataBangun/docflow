<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_digital_certificate extends CI_Model {

	private $java_exe;
	private $keytool_exe;
	
	private $process_id;
	private $work_dir;
	private $src_pdf;
	private $dest_pdf;
	

	function __construct()
	{
		parent::__construct();
		$this->java_exe = JAVA_DIR . '\java.exe';
		$this->keytool_exe = JAVA_DIR . '\keytool.exe';
	}

	function set_process_id($process_id)
	{
		$this->process_id = $process_id;
	}
	
	function set_folder($folder)
	{
		$this->work_dir = dirname(__FILE__) . '/../../assets/digital_certificate/' . $folder;
		if (!is_dir($this->work_dir)) mkdir($this->work_dir, 0777, true);
		file_put_contents($this->work_dir . '/log.txt', "LOG DATE : " . date('Y-m-d H:i:s') . PHP_EOL);
	}
	
	function set_pdf($pdf)
	{
		$this->src_pdf = $pdf;
		$this->dest_pdf = $this->work_dir . '/temp.pdf';
		copy($pdf, $this->dest_pdf);
	}
	
	function get_signed_pdf()
	{
		// $signed_pdf = dirname($this->src_pdf) . '/' . basename($this->src_pdf, '.pdf') . '_signed.pdf';
		$signed_pdf = dirname($this->src_pdf) . '/' . basename($this->src_pdf, '.pdf') . '.pdf';
		copy($this->dest_pdf, $signed_pdf);
		
		$sql = "select * from t_digital_certificate where fk_documents_process_id = ?";
		$query = $this->db->query($sql, array($this->process_id));
		$row = $query->row();
		
		if ($query->num_rows == 0) {
			$sql = "INSERT INTO t_digital_certificate ( FK_DOCUMENTS_PROCESS_ID, FILE_PDF, FILE_PDF_SIGNED, SIGNED_DATE)
				VALUES (?, ?, ?, SYSDATE)";
			$this->db->query($sql, array($this->process_id, $this->src_pdf, $signed_pdf));
		} else {
			$sql = "UPDATE t_digital_certificate SET FILE_PDF=?, FILE_PDF_SIGNED=?, SIGNED_DATE=SYSDATE
				WHERE  FK_DOCUMENTS_PROCESS_ID = ?";
			$this->db->query($sql, array($this->src_pdf, $signed_pdf, $this->process_id));
		}
		
		return $signed_pdf;
	}
	
	function fix_path($value)
	{
		return str_replace("/", "\\", $value);
	}
	
	function log($output)
	{
		file_put_contents($this->work_dir . '/log.txt', implode(PHP_EOL, $output) . PHP_EOL, FILE_APPEND);
	}
	
	function sign($alias, $email, $nama, $unit)
	{
		$this->generate_key($alias, $email, $nama, $unit);
	
		$jsignpdf_jar = dirname(__FILE__) . '/../libraries/JSignPdf.jar';
		$keystore = $this->work_dir . '/../keystore';
		$jsignpdf_bat = $this->work_dir . '/jsignpdf.bat';
		$jsignpdf_bat_content = <<<EOD
		
"$this->java_exe" -jar "$jsignpdf_jar" ^
	"$this->work_dir/temp.pdf" ^
	--out-directory $this->work_dir/ ^
	--out-suffix _signed ^
	--append ^
	--keystore-type JKS ^
	--keystore-file $keystore ^
	--key-password P4ssword ^
	--key-alias $alias ^
	--key-password P4ssword ^
	--reason "This Document has been signed" ^
	--location "Jakarta" ^
	--contact "$email"
	
del "$this->work_dir/temp.pdf"
rename "$this->work_dir/temp_signed.pdf" "temp.pdf"
EOD;
		file_put_contents($jsignpdf_bat, $this->fix_path($jsignpdf_bat_content));
		
		$phpexec_jar = dirname(__FILE__) . '/../libraries/phpexec.jar';
		$phpexec_bat = $this->work_dir . '/phpexec.bat';
		$phpexec_bat_content = <<<EOD
		
"$this->java_exe" -jar "$phpexec_jar" "$jsignpdf_bat"
EOD;
		file_put_contents($phpexec_bat, $this->fix_path($phpexec_bat_content));
		exec ($phpexec_bat, $output);
		
		$this->log($output);
	}
	
	function generate_key($alias, $email, $nama, $unit)
	{
		$keystore = $this->work_dir . '/../keystore';
		$keytool_bat = $this->work_dir . '/keytool.bat';
		$keytool_bat_content = <<<EOD
		
"$this->keytool_exe" -genkeypair ^
	-dname "email=$email, cn=$nama, ou=$unit, o=APLIKANUSA LINTASARTA, l=JAKARTA, st=DKI JAKARTA, c=ID" ^
	-alias $alias ^
	-keypass P4ssword ^
	-keystore $keystore ^
	-storepass P4ssword ^
	-validity 1825
EOD;
		file_put_contents($keytool_bat, $this->fix_path($keytool_bat_content));
		
		$phpexec_jar = dirname(__FILE__) . '/../libraries/phpexec.jar';
		$phpexec_bat = $this->work_dir . '/phpexec.bat';
		$phpexec_bat_content = <<<EOD
		
"$this->java_exe" -jar "$phpexec_jar" "$keytool_bat"
EOD;
		file_put_contents($phpexec_bat, $this->fix_path($phpexec_bat_content));
		exec ($phpexec_bat, $output);
		
		$this->log($output);
	}
}