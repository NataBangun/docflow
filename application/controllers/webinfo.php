<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// require_once dirname(__FILE__) . '/../libraries/HttpClient/HttpClient.class.php';
require_once dirname(__FILE__) . '/../libraries/simple_html_dom.php';

class webinfo extends CI_Controller 
{
	var $status;
	var $cookie = "";
	var $userpwd = "";
	var $line_delimiter = "\r\n";
	var $newline_html = " <br>\r\n";
	var $return_header = true;
	var $WEBINFO = array();
	
	function __construct()
	{
		parent::__construct();
		$this->load_webinfo();
		if (isset($this->WEBINFO['userpwd'])) {
			$this->userpwd = $this->WEBINFO['userpwd'];
		}
		// $this->userpwd = "LADOMAIN\SHT:Pr1ngg0d4n1";
	}
	
	public function exec($url, $post = "")
	{
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY ); 
		curl_setopt($ch, CURLOPT_USERPWD, $this->userpwd);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		if ($this->cookie != "") {
			curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
		}
		
		if (isset($post) && $post != "") {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		
		curl_setopt($ch, CURLOPT_HEADER, $this->return_header);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Host: webinfo',
			'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0'
			));

		$response = curl_exec($ch);
		$this->status = curl_error($ch);

		curl_close($ch);		
		
		$this->set_cookie($response);
		
		return $response;		
	}
	
	public function set_cookie($response)
	{
		$arr_response = explode("<HTML", $response);			
		$arr_header = explode($this->line_delimiter, $arr_response[0]);
		foreach ($arr_header as $value) {
			if (substr($value, 0, 12) == "Set-Cookie: ") {
				$set_cookie = str_replace("Set-Cookie: ", "", $value);
				$arr_set_cookie = explode(";", $set_cookie);
				if ($this->cookie == "") {
					$this->cookie = $arr_set_cookie[0];
				} else {
					$this->cookie .= "; " . $arr_set_cookie[0];
				}
				// echo "proses_curl got new cookie: " . $this->cookie . $this->newline_html;
				break;
			}
		}
	}

	/* for development purpose */
	public function proxy_get($value, $file="") 
	{
		$this->return_header = false;
		if ($value == 1) {
			header('Content-Type: image/'.pathinfo($file, PATHINFO_EXTENSION));
			readfile("http://webinfo/_layouts/images/{$file}");
		}
		if ($value == 31) {
			header('Content-Type: image/'.pathinfo($file, PATHINFO_EXTENSION));
			$html = $this->exec("http://webinfo/_layouts/1033/images/{$file}");
			echo $html;
		}
		if ($value == 2) {
			$html = $this->exec("http://webinfo/_layouts/1033/styles/{$file}?{$_SERVER['QUERY_STRING']}");
			header('Content-Type: text/css');
			$html = $this->replace($html);
			echo "/* http://webinfo/_layouts/1033/styles/{$file}?{$_SERVER['QUERY_STRING']} */ \n";
			echo $html;
		}
		if ($value == 3) {
			$html = $this->exec("http://webinfo/_layouts/1033/{$file}?{$_SERVER['QUERY_STRING']}");
			header('application/x-javascript');
			echo "/* http://webinfo/_layouts/1033/{$file}?{$_SERVER['QUERY_STRING']} */ \n";
			echo $html;
		}
		if ($value == 4) {
			$html = $this->exec("http://webinfo/{$file}?{$_SERVER['QUERY_STRING']}");
			header('application/x-javascript');
			echo "/* http://webinfo/{$file}?{$_SERVER['QUERY_STRING']} */ \n";
			echo $html;
		}
	}
	
	/* for development purpose */
	public function replace($html)
	{
		$html = str_replace("/_layouts/images/", base_url()."webinfo/proxy_get/1/", $html);
		
		$html = str_replace("/_layouts/1033/styles/", base_url()."webinfo/proxy_get/2/", $html);
		
		$html = str_replace("/_layouts/1033/images/", base_url()."webinfo/proxy_get/31/", $html);
		
		$html = str_replace("/_layouts/1033/", base_url()."webinfo/proxy_get/3/", $html);
		
		$html = str_replace("/WebResource.axd", base_url()."webinfo/proxy_get/4/WebResource.axd", $html);
		
		return $html;
	}
	
	public function home($return=0)
	{
		$this->return_header = false;
		$html = $this->exec("http://webinfo/default.aspx");
		$html = $this->replace($html);
		if ($return == 1) {
			$dom_html = str_get_html($html);
			$i = 1;
			$result = array();
			$result["dom_html"] = is_object($dom_html);
			$result["children"] = array();
			while (true) {
				$node = $dom_html->find('td[id=zz1_TopNavigationMenun'.$i.']', 0);
				// var_dump(is_object($node));
				if (!is_object($node)) break;
				$link = $node->childNodes(0)->childNodes(0)->childNodes(0)->childNodes(0);
				$result["children"][] = array('id'=>$i, 'text'=>$link->innertext);
				$this->WEBINFO['webinfo|'.$i] = $link->href;
				$i++;
			}
			return $result;
		} else {
			echo $html;
		}
	}

	/* for development purpose */
	public function laporan()
	{
		$this->return_header = false;
		// $html = $this->exec("http://webinfo/Laporan");
		$html = $this->exec("http://webinfo/Laporan/Forms/AllItems.aspx");
		$html = $this->replace($html);
		echo $html;
	}

	/* for development purpose */
	public function it_dev()
	{
		$this->return_header = false;
		$html = $this->exec("http://10.24.8.61/Laporan/Forms/AllItems.aspx?RootFolder=%2fLaporan%2fInformation%20Technology%20Division&FolderCTID=&View=%7bF900070E%2d820E%2d49DB%2d927F%2d6898DAD3B1DE%7d");
		$html = $this->replace($html);
		echo $html;
	}

	/* for development purpose */
	public function testing()
	{
		$this->return_header = false;
		$html = $this->exec("http://webinfo/Laporan/Forms/AllItems.aspx?RootFolder=%2fLaporan%2fInformation%20Technology%20Division%2fTesting&FolderCTID=&View=%7bF900070E%2d820E%2d49DB%2d927F%2d6898DAD3B1DE%7d");
		$html = $this->replace($html);
		echo $html;
	}

	/* for development purpose */
	public function form_upload()
	{
		$this->return_header = false;
		$html = $this->exec("http://webinfo/_layouts/Upload.aspx?List=%7BCB046157%2D827C%2D43C2%2DB705%2DC15E5A0DC8B5%7D&RootFolder=%2FLaporan%2FInformation%20Technology%20Division%2FTesting&Source=http%3A%2F%2Fwebinfo%2FLaporan%2FForms%2FAllItems%2Easpx%3FRootFolder%3D%252fLaporan%252fInformation%2520Technology%2520Division%252fTesting%26FolderCTID%3D%26View%3D%257bF900070E%252d820E%252d49DB%252d927F%252d6898DAD3B1DE%257d");
		$html = $this->replace($html);
		echo $html;
	}

	/* for development purpose */
	public function form_edit()
	{
		$this->return_header = false;
		$html = $this->exec("http://webinfo/Laporan/Forms/EditForm.aspx?Mode=Upload&CheckInComment=&ID=9919&RootFolder=%2FLaporan%2FInformation%20Technology%20Division%2FTesting&Source=http%3A%2F%2Fwebinfo%2FLaporan%2FForms%2FAllItems%2Easpx%3FRootFolder%3D%252fLaporan%252fInformation%2520Technology%2520Division%252fTesting%26FolderCTID%3D%26View%3D%257bF900070E%252d820E%252d49DB%252d927F%252d6898DAD3B1DE%257d");
		$html = $this->replace($html);
		echo $html;
	}

	public function index($process_id = "")
	{
	// Step 0 - get file
		$sql = "select * from t_digital_certificate where fk_documents_process_id = ?";
		$query = $this->db->query($sql, array($process_id));
		$row = $query->row();
		$file_pdf_signed = $row->FILE_PDF_SIGNED;		
		// echo $file_pdf_signed;
	
	// Step 1 - browse form upload
		$this->return_header = false;
		$url = "http://webinfo/_layouts/Upload.aspx?List=%7BCB046157%2D827C%2D43C2%2DB705%2DC15E5A0DC8B5%7D&RootFolder=%2FLaporan%2FInformation%20Technology%20Division%2FTesting&Source=http%3A%2F%2Fwebinfo%2FLaporan%2FForms%2FAllItems%2Easpx%3FRootFolder%3D%252fLaporan%252fInformation%2520Technology%2520Division%252fTesting%26FolderCTID%3D%26View%3D%257bF900070E%252d820E%252d49DB%252d927F%252d6898DAD3B1DE%257d";
		$html = $this->exec($url);
		echo $html;
		// die;
		$dom_html = str_get_html($html);

		$__EVENTARGUMENT = $dom_html->find('input[id=__EVENTARGUMENT]', 0);
		$__REQUESTDIGEST = $dom_html->find('input[id=__REQUESTDIGEST]', 0);
		$__VIEWSTATE = $dom_html->find('input[id=__VIEWSTATE]', 0);
		$destination = $dom_html->find('input[id=destination]', 0);
		$__EVENTVALIDATION = $dom_html->find('input[id=__EVENTVALIDATION]', 0);
		
	// Step 2 - post form upload
		// if ($pdf == "") {
			// $file = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/pdf/lampiran.pdf";
		// } else {
			// $file = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/{$pdf}";
		// }
		$file = $file_pdf_signed;
		$file = str_replace('/', '\\', $file);
		$cfile = new CURLFile($file,'application/pdf',pathinfo($file, PATHINFO_BASENAME)); // PHP 5 >= 5.5.0
		$post = array(
			'__EVENTTARGET' => 'ctl00$PlaceHolderMain$ctl00$RptControls$btnOK',
			'__EVENTARGUMENT' => $__EVENTARGUMENT->value,
			'__REQUESTDIGEST' => $__REQUESTDIGEST->value,
			'__VIEWSTATE' => $__VIEWSTATE->value,
			'destination' => $destination->value,
			//'ctl00$PlaceHolderMain$ctl01$ctl02$InputFile' => '@'.$file,
			'ctl00$PlaceHolderMain$ctl01$ctl02$InputFile' => $cfile, // PHP 5 >= 5.5.0
			'ctl00$PlaceHolderMain$ctl01$ctl02$OverwriteSingle' => 'on',
			'__spDummyText1' => '',
			'__spDummyText2' => '',
			'__EVENTVALIDATION' => $__EVENTVALIDATION->value
		);
		
		$this->return_header = true;
		$html_post = $this->exec($url, $post);
		print_r($post);
		echo $html_post;
		
	// Step 3 - browse form edit
		if (strpos($html_post, "Location: http://webinfo/") === false) {
			die('Gagal upload');
		} else {
			$temp = substr($html_post, strpos($html_post, "Location: http://webinfo/"));
			$temp = explode($this->line_delimiter, $temp);
			$url = str_replace("Location: ", "", $temp[0]);
		}
		$this->return_header = true;
		$html = $this->exec($url);
		echo $html;
		
		$dom_html = str_get_html($html);

		$MSO_PageHashCode = $dom_html->find('input[id=MSO_PageHashCode]', 0);
		$MSOWebPartPage_PostbackSource = $dom_html->find('input[id=MSOWebPartPage_PostbackSource]', 0);
		$MSOTlPn_SelectedWpId = $dom_html->find('input[id=MSOTlPn_SelectedWpId]', 0);
		$MSOTlPn_View = $dom_html->find('input[id=MSOTlPn_View]', 0);
		$MSOTlPn_ShowSettings = $dom_html->find('input[id=MSOTlPn_ShowSettings]', 0);
		$MSOGallery_SelectedLibrary = $dom_html->find('input[id=MSOGallery_SelectedLibrary]', 0);
		$MSOGallery_FilterString = $dom_html->find('input[id=MSOGallery_FilterString]', 0);
		$MSOTlPn_Button = $dom_html->find('input[id=MSOTlPn_Button]', 0);
		$_ListSchemaVersion = $dom_html->find('input[id=_ListSchemaVersion_{cb046157-827c-43c2-b705-c15e5a0dc8b5}]', 0);
		$MSOSPWebPartManager_DisplayModeName = $dom_html->find('input[id=MSOSPWebPartManager_DisplayModeName]', 0);
		$MSOWebPartPage_Shared = $dom_html->find('input[id=MSOWebPartPage_Shared]', 0);
		$MSOLayout_LayoutChanges = $dom_html->find('input[id=MSOLayout_LayoutChanges]', 0);
		$MSOLayout_InDesignMode = $dom_html->find('input[id=MSOLayout_InDesignMode]', 0);
		$MSOSPWebPartManager_OldDisplayModeName = $dom_html->find('input[id=MSOSPWebPartManager_OldDisplayModeName]', 0);
		$MSOSPWebPartManager_StartWebPartEditingName = $dom_html->find('input[id=MSOSPWebPartManager_StartWebPartEditingName]', 0);
		
		$__EVENTARGUMENT = $dom_html->find('input[id=__EVENTARGUMENT]', 0);
		$__REQUESTDIGEST = $dom_html->find('input[id=__REQUESTDIGEST]', 0);
		$__LASTFOCUS = $dom_html->find('input[id=__LASTFOCUS]', 0);
		$__VIEWSTATE = $dom_html->find('input[id=__VIEWSTATE]', 0);
		$__EVENTVALIDATION = $dom_html->find('input[id=__EVENTVALIDATION]', 0);
		
		$ctl00_PlaceHolderSearchArea_ctl01_ctl00 = $dom_html->find('input[id=ctl00_PlaceHolderSearchArea_ctl01_ctl00]', 0);
		$ctl00_PlaceHolderSearchArea_ctl01_SBScopesDDL = $dom_html->find('select[id=ctl00_PlaceHolderSearchArea_ctl01_SBScopesDDL]', 0);
		
		$ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl00_ctl00_ctl00_ctl04_ctl00_ctl00_onetidIOFile = $dom_html->find('input[id=ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl00_ctl00_ctl00_ctl04_ctl00_ctl00_onetidIOFile]', 0);
		$ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl01_ctl00_ctl00_ctl04_ctl00_ctl00_TextField = $dom_html->find('input[id=ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl01_ctl00_ctl00_ctl04_ctl00_ctl00_TextField]', 0);
		$ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl02_ctl00_ctl00_ctl04_ctl00_Lookup = $dom_html->find('select[id=ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl02_ctl00_ctl00_ctl04_ctl00_Lookup]', 0);
		$ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl05_ctl00_owshiddenversion = $dom_html->find('input[id=ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl05_ctl00_owshiddenversion]', 0);
		
	// Step 4 - post form edit
		$post = array(
			'InputKeywords' => '',
			'MSO_PageHashCode' => $MSO_PageHashCode->value,
			'MSOWebPartPage_PostbackSource' => $MSOWebPartPage_PostbackSource->value,
			'MSOTlPn_SelectedWpId' => $MSOTlPn_SelectedWpId->value,
			'MSOTlPn_View' => $MSOTlPn_View->value,
			'MSOTlPn_ShowSettings' => $MSOTlPn_ShowSettings->value,
			'MSOGallery_SelectedLibrary' => $MSOGallery_SelectedLibrary->value,
			'MSOGallery_FilterString' => $MSOGallery_FilterString->value,
			'MSOTlPn_Button' => $MSOTlPn_Button->value,
			'_ListSchemaVersion_{cb046157-827c-43c2-b705-c15e5a0dc8b5}' => $_ListSchemaVersion->value,
			'MSOSPWebPartManager_DisplayModeName' => $MSOSPWebPartManager_DisplayModeName->value,
			'MSOWebPartPage_Shared' => $MSOWebPartPage_Shared->value,
			'MSOLayout_LayoutChanges' => $MSOLayout_LayoutChanges->value,
			'MSOLayout_InDesignMode' => $MSOLayout_InDesignMode->value,
			'MSOSPWebPartManager_OldDisplayModeName' => $MSOSPWebPartManager_OldDisplayModeName->value,
			'MSOSPWebPartManager_StartWebPartEditingName' => $MSOSPWebPartManager_StartWebPartEditingName->value,
			'__EVENTTARGET' => 'ctl00$m$g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef$ctl00$ctl02$ctl00$toolBarTbl$RightRptControls$ctl00$ctl00$diidIOSaveItem',
			'__EVENTARGUMENT' => $__EVENTARGUMENT->value,
			'__REQUESTDIGEST' => $__REQUESTDIGEST->value,
			'__LASTFOCUS' => $__LASTFOCUS->value,
			'__VIEWSTATE' => $__VIEWSTATE->value,
			'__spDummyText1' => '',
			'__spDummyText2' => '',
			'ctl00$PlaceHolderSearchArea$ctl01$ctl00' => $ctl00_PlaceHolderSearchArea_ctl01_ctl00->value,
			'ctl00$PlaceHolderSearchArea$ctl01$SBScopesDDL' => $ctl00_PlaceHolderSearchArea_ctl01_SBScopesDDL->children(0)->value,
			'ctl00$m$g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef$ctl00$ctl02$ctl00$ctl01$ctl00$ctl00$ctl00$ctl00$ctl00$ctl04$ctl00$ctl00$onetidIOFile' => $ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl00_ctl00_ctl00_ctl04_ctl00_ctl00_onetidIOFile->value,
			'ctl00$m$g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef$ctl00$ctl02$ctl00$ctl01$ctl00$ctl00$ctl01$ctl00$ctl00$ctl04$ctl00$ctl00$TextField' => $ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl01_ctl00_ctl00_ctl04_ctl00_ctl00_TextField->value,
			'ctl00$m$g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef$ctl00$ctl02$ctl00$ctl01$ctl00$ctl00$ctl02$ctl00$ctl00$ctl04$ctl00$Lookup' => $ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl01_ctl00_ctl00_ctl02_ctl00_ctl00_ctl04_ctl00_Lookup->children(0)->value,
			'ctl00$m$g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef$ctl00$ctl02$ctl00$ctl05$ctl00$owshiddenversion' => $ctl00_m_g_8e9291e8_9067_4ec8_8a0b_5e71deade5ef_ctl00_ctl02_ctl00_ctl05_ctl00_owshiddenversion->value,
			'__EVENTVALIDATION' => $__EVENTVALIDATION->value
		);
		
		$this->return_header = true;
		$html_post = $this->exec($url, $post);
		print_r($post);
		echo $html_post;
	}


	function tree($process_id)
	{
		$data['process_id'] = $process_id;
		$data['uID'] = $this->session->userdata('uID');
		if ($this->userpwd == "") {
			$data['showLogin'] = "1";
			$sql = "select * from v_employee where employee_no = ?";
			$query = $this->db->query($sql, array($data['uID']));
			$row = $query->row_array();
			$data['user_name'] = $row['USER_NAME'];
		} else {
			$data['showLogin'] = "0";
			$data['user_name'] = "";
		}
		$this->load->view('webinfo', $data);
	}
	
	function get_treenode()
	{
		// echo "hello " . $_SERVER['QUERY_STRING'];
		parse_str($_SERVER['QUERY_STRING']);
		if ($node == "root") {
			$result = $this->home(true);
		} else {
			// $url = $this->session->userdata('webinfo|'.$node);
			$url = $this->WEBINFO['webinfo|'.$node];
			$this->return_header = true;
			$html = $this->exec($url);
			$html = $this->replace($html);
			
			if (strpos($html, "HTTP/1.1 302 Object Moved") !== false) {
				$new_url = substr($html, strpos($html, "Location: "));
				$new_url = substr($new_url, 0, strpos($new_url, PHP_EOL));
				$new_url = str_replace("Location: ", "", $new_url);
				// echo $new_url;
				$html = $this->exec($new_url);
				$html = $this->replace($html);
			}
			// echo $html;
			
			$dom_html = str_get_html($html);
			$result = array();
			$result["dom_html"] = is_object($dom_html);
			$result["children"] = array();
			if (is_object($dom_html)) {
				$dom_table = $dom_html->find('table[id=onetidDoclibViewTbl0]', 0);
				$result["dom_table"] = is_object($dom_table);
				if (is_object($dom_table)) {
					$i = 1;
					while (true) {
						$childNode = $dom_table->childNodes($i);
						// echo $childNode;
						// var_dump(is_object($childNode));
						if (!is_object($childNode)) break;
						$link = $childNode->childNodes(0)->childNodes(0);
						// echo $link->href;
						// echo $link->onclick;
						if (strpos($link->onclick, "EnterFolder") !== false) {
							$result["children"][] = array('id'=>$node.'|'.$i, 'text'=>str_replace("Folder: ", "", $link->childNodes(0)->title));
							// $this->session->set_userdata('webinfo|'.$node.'|'.$i, $link->href);
							$this->WEBINFO['webinfo|'.$node.'|'.$i] = $link->href;
							// echo $this->session->userdata('webinfo|'.$node.'|'.$i);
						}
						$i++;
					}
				}
			}
			
		}
		$this->save_webinfo();
		echo json_encode($result);
	}
	
	function load_webinfo()
	{
		if (is_file(session_save_path()."/webinfo_".$this->session->userdata('session_id'))) {
			$this->WEBINFO = unserialize(file_get_contents(session_save_path()."/webinfo_".$this->session->userdata('session_id')));
		}
	}
	
	function save_webinfo()
	{
		file_put_contents(session_save_path()."/webinfo_".$this->session->userdata('session_id'), serialize($this->WEBINFO));
	}

	function link_upload()
	{		
		parse_str($_SERVER['QUERY_STRING']);
		if (isset($this->WEBINFO['webinfo|'.$node])) {
			$url = $this->WEBINFO['webinfo|'.$node];
		} else {
			$url = "http://webinfo/default.aspx";
		}
		$this->return_header = true;
		$html = $this->exec($url);
		$html = $this->replace($html);
		
		if (strpos($html, "HTTP/1.1 302 Object Moved") !== false) {
			$new_url = substr($html, strpos($html, "Location: "));
			$new_url = substr($new_url, 0, strpos($new_url, PHP_EOL));
			$new_url = str_replace("Location: ", "", $new_url);
			// echo $new_url;
			$html = $this->exec($new_url);
			$html = $this->replace($html);
		}
			
		if (isset($browse) && $browse==1) {
			$html = "<!--".str_replace("<HTML", "--><HTML", $html);
			echo $html;
			return;
		}
		
		$dom_html = str_get_html($html);
		if (is_object($dom_html)) {
			$dom_table = $dom_html->find('table[id=zz14_UploadMenu_t]', 0);
			if (is_object($dom_table)) {
				$node = $dom_table->childNodes(0)->childNodes(0);
				$link_upload = str_replace("STSNavigate('", "", $node->onclick);
				$link_upload = str_replace("')", "", $link_upload);
				$link_upload = "http://webinfo".json_decode("\"".$link_upload."\"");
				// echo $link_upload;
				
				if (isset($overwrite) && $overwrite=="1") {
					$overwrite = true;
				} else {
					$overwrite = false;
				}
				$this->do_upload($process_id, $link_upload, $uID, $overwrite);
			} else {
				echo "Error: Access Denied.";
			}
		} else {
			echo "Error: Unknown Error";
		}
		
		// echo $html;
		
		
	}
	
	function do_upload($process_id, $url, $uID, $overwrite = false)
	{
	// Step 0 - get file - first attempt
		$sql = "select * from t_digital_certificate where fk_documents_process_id = ?";
		$query = $this->db->query($sql, array($process_id));
		$row = $query->row();
		if (is_object($row)) {
			$file_pdf_signed = $row->FILE_PDF_SIGNED;		
		} else {
			// call proses sign pdf
			$sql = "select * from h_documents_process where PK_DOCUMENTS_PROCESS_ID=?";
			$query = $this->db->query($sql, array($process_id));
			$row = $query->row_array();	
			if (isset($row['FK_TYPE_ID']) && $row['FK_TYPE_ID']=="2") {
				// echo base_url("generate_pdf/sign/{$row['FK_DOCUMENTS_ID']}/{$row['PK_DOCUMENTS_PROCESS_ID']}");
				file_get_contents(base_url("generate_pdf/sign/{$row['FK_DOCUMENTS_ID']}/{$row['PK_DOCUMENTS_PROCESS_ID']}"));
			} else {
				// echo base_url("generate_doc_pro/sign/{$row['FK_DOCUMENTS_ID']}/{$row['PK_DOCUMENTS_PROCESS_ID']}/$uID");
				file_get_contents(base_url("generate_doc_pro/sign/{$row['FK_DOCUMENTS_ID']}/{$row['PK_DOCUMENTS_PROCESS_ID']}/$uID"));
			}
			// get file - second attempt
			$sql = "select * from t_digital_certificate where fk_documents_process_id = ?";
			$query = $this->db->query($sql, array($process_id));
			$row = $query->row();
			if (is_object($row)) {
				$file_pdf_signed = $row->FILE_PDF_SIGNED;		
			} else {
				$file_pdf_signed = ".";
			}
		}
		// echo $file_pdf_signed;
	
		if (!is_file($file_pdf_signed)) {
			echo "Error: File '".pathinfo($file_pdf_signed, PATHINFO_BASENAME)."' does not exists.";
			return;
		}
	
	// Step 1 - browse form upload
		$this->return_header = false;
		// $url = "http://webinfo/_layouts/Upload.aspx?List=%7BCB046157%2D827C%2D43C2%2DB705%2DC15E5A0DC8B5%7D&RootFolder=%2FLaporan%2FInformation%20Technology%20Division%2FTesting&Source=http%3A%2F%2Fwebinfo%2FLaporan%2FForms%2FAllItems%2Easpx%3FRootFolder%3D%252fLaporan%252fInformation%2520Technology%2520Division%252fTesting%26FolderCTID%3D%26View%3D%257bF900070E%252d820E%252d49DB%252d927F%252d6898DAD3B1DE%257d";
		$html = $this->exec($url);
		// echo $html;
		// die;
		$dom_html = str_get_html($html);
		if (!is_object($dom_html)) {
			echo "Error: Failed to access upload page.";
			return;
		}

		$__EVENTARGUMENT = $dom_html->find('input[id=__EVENTARGUMENT]', 0);
		$__REQUESTDIGEST = $dom_html->find('input[id=__REQUESTDIGEST]', 0);
		$__VIEWSTATE = $dom_html->find('input[id=__VIEWSTATE]', 0);
		$destination = $dom_html->find('input[id=destination]', 0);
		$__EVENTVALIDATION = $dom_html->find('input[id=__EVENTVALIDATION]', 0);
		
		if (!is_object($destination)) {
			echo "Error: Failed to extract data from upload page.";
			return;
		}
		
	// Step 2 - post form upload
		$file = $file_pdf_signed;
		$file = str_replace('/', '\\', $file);
		$cfile = new CURLFile($file,'application/pdf',pathinfo($file, PATHINFO_BASENAME)); // PHP 5 >= 5.5.0
		$post = array(
			'__EVENTTARGET' => 'ctl00$PlaceHolderMain$ctl00$RptControls$btnOK',
			'__EVENTARGUMENT' => $__EVENTARGUMENT->value,
			'__REQUESTDIGEST' => $__REQUESTDIGEST->value,
			'__VIEWSTATE' => $__VIEWSTATE->value,
			'destination' => $destination->value,
			//'ctl00$PlaceHolderMain$ctl01$ctl02$InputFile' => '@'.$file,
			'ctl00$PlaceHolderMain$ctl01$ctl02$InputFile' => $cfile, // PHP 5 >= 5.5.0
			// 'ctl00$PlaceHolderMain$ctl01$ctl02$OverwriteSingle' => 'on',
			'__spDummyText1' => '',
			'__spDummyText2' => '',
			'__EVENTVALIDATION' => $__EVENTVALIDATION->value
		);
		
		if ($overwrite) {
			$post['ctl00$PlaceHolderMain$ctl01$ctl02$OverwriteSingle'] = 'on';
		}
		
		$this->return_header = true;
		$html_post = $this->exec($url, $post);
		// print_r($post);
		// echo $html_post;		
		
		$dom_html_post = str_get_html($html_post);
		$ctl00_PlaceHolderMain_LabelMessage = $dom_html_post->find('span[id=ctl00_PlaceHolderMain_LabelMessage]', 0);
		if (is_object($ctl00_PlaceHolderMain_LabelMessage) && strpos($ctl00_PlaceHolderMain_LabelMessage->innertext, "already exists") !== false) {
			echo "File already exists.";
			return;
		}
		
		if (strpos($html_post, "HTTP/1.1 302 Found") === false) {
			echo "Error: Unexpected HTTP HEADER response.";
			// echo $html_post;
			return;
		}
		
		$sql = "select * from t_webinfo where fk_documents_process_id = ?";
		$query = $this->db->query($sql, array($process_id));
		$row = $query->row();
		
		if ($query->num_rows == 0) {
			$sql = "INSERT INTO t_webinfo ( FK_DOCUMENTS_PROCESS_ID, FOLDER, URL, UPLOAD_DATE)
				VALUES (?, ?, ?, SYSDATE)";
			$this->db->query($sql, array($process_id, $destination->value, $url));
		} else {
			$sql = "UPDATE t_webinfo SET FOLDER=?, URL=?, UPLOAD_DATE=SYSDATE
				WHERE  FK_DOCUMENTS_PROCESS_ID = ?";
			$this->db->query($sql, array($destination->value, $url, $process_id));
		}

		// echo $html_post;
		echo "File has been successfully uploaded.";
		return;
	}
	
	function login()
	{
		$username = "LADOMAIN\\" . $this->input->post('username').":".$this->input->post('password');
		$this->userpwd = $username;
		$result = $this->home(true);
		// $result['children'] = array(1,2,3); // test sukses
		if (count($result['children']) > 0) {
			$result['success'] = true;
			$result['msg'] = "Login Success";
			$this->WEBINFO['userpwd'] = $username;
			$this->save_webinfo();
		} else {
			$result['success'] = false;
			$result['msg'] = "Login Failed";
		}
		echo json_encode($result);
	}
}