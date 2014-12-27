<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	var $folder = 'dashboard/';

	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();		
		$this->load->library('pagination_bas', '', 'pg_nota');
		$this->load->library('pagination_bas', '', 'pg_doc');		
		$this->setter();
		
		// Document Procedure 		
        $this->field_doc=array(
            array('field'=>'PK_DOCUMENTS_ID', 'label'=>'ID', 'attribut'=>array('class'=>'form-control', 'style'=>'width:50px')),
            array('field'=>'DOCUMENTS_TITLE', 'label'=>'Judul', 'attribut'=>array('class'=>'form-control', 'style'=>'width:150px')),
            array('field'=>'PROCESS_STATUS_DTL', 'label'=>'Status', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px')),
            array('field'=>'CREATE_BY_NAME', 'label'=>'Penyusun', 'attribut'=>array('class'=>'form-control', 'style'=>'width:150px')),
            array('field'=>'DOCUMENTS_CDT', 'label'=>'Tgl. Buat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px'))
        );
		
		$this->field_doc[0]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == DOC_EDIT) 
	? "<a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['PK_DOCUMENTS_ID']}</a>"
	: "<a href=\"".site_url('inbox/detail/'    .\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['PK_DOCUMENTS_ID']}</a>";
EOD;
		$this->field_doc[1]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == DOC_EDIT) 
	? "<span><a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['DOCUMENTS_TITLE']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>"
	: "<span><a href=\"".site_url('inbox/detail/'    .\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['DOCUMENTS_TITLE']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
		$this->field_doc[3]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

		$this->pg_doc->set_component_id('pg_doc');
        $this->pg_doc->set_field($this->field_doc);

		
		// Nota Dinas
        $this->field_nota=array(
            array('field'=>'PK_NOTA_ID', 'label'=>'No', 'attribut'=>array('class'=>'form-control', 'style'=>'width:50px')),
            array('field'=>'HAL', 'label'=>'Judul', 'attribut'=>array('class'=>'form-control', 'style'=>'width:150px')),
            array('field'=>'PROCESS_STATUS_DTL', 'label'=>'Status', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px')),
            array('field'=>'CREATE_BY_NAME', 'label'=>'Penyusun', 'attribut'=>array('class'=>'form-control', 'style'=>'width:150px')),
            array('field'=>'CREATE_DATE', 'label'=>'Tgl. Buat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px'))
        );
		
		$this->field_nota[0]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == NOTA_EDIT) 
	? "<a href=\"".site_url('nota/detail/'      .\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['PK_NOTA_ID']}</a>" 
	: "<a href=\"".site_url('inbox_nota/detail/'.\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['PK_NOTA_ID']}</a>";
EOD;
		$this->field_nota[1]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == NOTA_EDIT) 		
	? "<span><a href=\"".site_url('nota/detail/'      .\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['HAL']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>"
	: "<span><a href=\"".site_url('inbox_nota/detail/'.\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['HAL']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
		$this->field_nota[3]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

		$this->pg_nota->set_component_id('pg_nota');
        $this->pg_nota->set_field($this->field_nota);
		
	}
	
	// public function __index()
	// {
		// $SQL = "
		// SELECT
		// DEVELOPER.T_USERS.PK_USERS_ID,
		// DEVELOPER.T_USERS.USERS_FNAME,
		// DEVELOPER.T_USERS.USERS_LNAME,
		// DEVELOPER.T_USERS.USERS_AVATAR,
		// DEVELOPER.T_USERS.USERS_SIGNATURE,
		// DEVELOPER.T_USERS.USERS_PASSWORD
		// FROM
		// DEVELOPER.T_USERS
		// WHERE
		// DEVELOPER.T_USERS.USERS_EMAIL = 'yosuacr@gmail.com' AND
		// DEVELOPER.T_USERS.USERS_PASSWORD = 'admin'";
		// $data = $this->db->query($SQL);
		// PRINT_R($data);			
	// }
	
	public function index()
	{
		// $this->load->model('mm_documents');
		// $this->load->model('mm_nota');
		// $this->load->model('mm_inbox');
		// $this->load->model('mm_inbox_nota');
		// $this->data['records'] = $this->mm_documents->get_all_new( $this->session->userdata('uID') );
		// $this->data['records_nota'] = $this->mm_nota->get_all( $this->session->userdata('uID') );
		// $this->data['records_inbox'] = $this->mm_inbox->get_inbox( $this->data['userInfo']['uID'] );
		// $this->data['records_in_nota'] = $this->mm_inbox_nota->get_inbox( $this->data['userInfo']['uID'] );
		
        $this->pg_doc->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_doc->set_ajax_url(site_url().'dashboard/search_doc');
        $this->data['doc_dashboard'] = $this->pg_doc->generate_all();
		
        $this->pg_nota->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_nota->set_ajax_url(site_url().'dashboard/search_nota');
        $this->data['nota_dashboard'] = $this->pg_nota->generate_all();
		
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
    public function search_doc($page){		
        $this->pg_doc->set_table('V_INBOX_DOC');
		if (in_array("Penyusun Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
			|| in_array("Pemeriksa Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Penyetuju Doc Prosedur", explode("|", $this->session->userdata('umc_feature'))) 
			|| in_array("Pengesahan Doc Prosedur", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature'))) 
			) {			
			$this->pg_doc->set_where(array('INBOX_OWNER'=>$this->data['userInfo']['uID']));
		} else {			
			$this->pg_doc->set_where(array('INBOX_OWNER'=>0));
		}
        $this->pg_doc->set_paging($_POST,10,$page);
        $this->pg_doc->generate_table_data();
    }
	
    public function search_nota($page){
        $this->pg_nota->set_table('V_INBOX_NOTA');
		if (in_array("Sekretaris", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Pemeriksa Nota Dinas", explode("|", $this->session->userdata('umc_feature')))
			|| in_array("Pengesahan Nota Dinas", explode("|", $this->session->userdata('umc_feature'))) 
			|| in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))
			) { 			
			$this->pg_nota->set_where(array('INBOX_OWNER'=>$this->data['userInfo']['uID']));
		} else {			
			$this->pg_nota->set_where(array('INBOX_OWNER'=>0));
		}
		
        $this->pg_nota->set_paging($_POST,10,$page);
        $this->pg_nota->generate_table_data();
    }
	
	/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();
		//$this->data['service'] = 0;
		$this->data['service'] = $this->mm_service->main( $this->session->userdata('uID') );
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}
}