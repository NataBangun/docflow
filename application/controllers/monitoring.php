<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    var $folder = 'monitoring/';
    var $data = array();
    protected $field_doc;
    protected $field_nota;

    function __construct() {
        parent::__construct();
        $this->mm_session->islogin();
        $this->load->model('mm_inbox');
        $this->load->model('mm_inbox_nota');
        $this->load->library('pagination_bas', '', 'pg_nota');
        $this->load->library('pagination_bas', '', 'pg_doc');
        $this->setter();

        // Document Procedure 		
        $this->field_doc = array(
            array('field' => 'PK_DOCUMENTS_ID', 'label' => 'ID', 'attribut' => array('class' => 'form-control', 'style' => 'width:40px')),
            array('field' => 'DOCUMENTS_TITLE', 'label' => 'Judul', 'attribut' => array('class' => 'form-control', 'style' => 'width:200px')),
            array('field' => 'VERSION_DTL', 'label' => 'Versi', 'attribut' => array('class' => 'form-control', 'style' => 'width:100px')),
            array('field' => 'PROCESS_STATUS_DTL', 'label' => 'Status', 'attribut' => array('class' => 'form-control', 'style' => 'width:100px')),
            array('field' => 'CURRENT_LAYER_DTL', 'label' => 'Proses Berjalan', 'attribut' => array('class' => 'form-control', 'style' => 'width:110px')),
            array('field' => 'APPROVAL_STATUS_DTL', 'label' => 'Respon', 'attribut' => array('class' => 'form-control', 'style' => 'width:100px')),
            array('field' => 'CREATE_BY_NAME', 'label' => 'Penyusun', 'attribut' => array('class' => 'form-control', 'style' => 'width:170px')),
            array('field' => 'DOCUMENTS_DATEPUB', 'label' => 'Tgl. Publikasi', 'attribut' => array('class' => 'form-control', 'style' => 'width:70px')),
            array('field' => 'DOCUMENTS_CDT', 'label' => 'Tgl. Buat', 'attribut' => array('class' => 'form-control', 'style' => 'width:110px'))
        );

        $this->field_doc[0]['script'] = <<<EOD
"<a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['PK_DOCUMENTS_ID']}</a>";
EOD;
        $this->field_doc[1]['script'] = <<<EOD
"<span><a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['DOCUMENTS_TITLE']}</a></span><br>
<span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
        $this->field_doc[5]['script'] = <<<EOD
\$this->ci->data['config']['act_status_icon'][ \$value['APPROVAL_STATUS'] ][1].' '.\$value['APPROVAL_STATUS_DTL'] ;
EOD;
        $this->field_doc[6]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

        $this->pg_doc->set_component_id('pg_doc');
        $this->pg_doc->set_field($this->field_doc);


        // Nota Dinas
        $this->field_nota = array(
            array('field' => 'PK_NOTA_ID', 'label' => 'No', 'attribut' => array('class' => 'form-control', 'style' => 'width:70px')),
            array('field' => 'HAL', 'label' => 'Judul', 'attribut' => array('class' => 'form-control', 'style' => 'width:200px')),
            array('field' => 'PROCESS_STATUS_DTL', 'label' => 'Status', 'attribut' => array('class' => 'form-control', 'style' => 'width:100px')),
            array('field' => 'CURRENT_LAYER_DTL', 'label' => 'Proses Berjalan', 'attribut' => array('class' => 'form-control', 'style' => 'width:150px')),
            array('field' => 'APPROVAL_STATUS_DTL', 'label' => 'Respon', 'attribut' => array('class' => 'form-control', 'style' => 'width:100px')),
            array('field' => 'CREATE_BY_NAME', 'label' => 'Penyusun', 'attribut' => array('class' => 'form-control', 'style' => 'width:200px')),
            array('field' => 'CREATE_DATE', 'label' => 'Tgl. Buat', 'attribut' => array('class' => 'form-control', 'style' => 'width:120px'))
        );

        $this->field_nota[0]['script'] = <<<EOD
"<a href=\"".site_url('nota/detail/'.\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['PK_NOTA_ID']}</a>";
EOD;
        $this->field_nota[1]['script'] = <<<EOD
"<span><a href=\"".site_url('nota/detail/'.\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['HAL']}</a></span><br>
<span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
        $this->field_nota[4]['script'] = <<<EOD
\$this->ci->data['config']['act_status_icon'][ \$value['APPROVAL_STATUS'] ][1].' '.\$value['APPROVAL_STATUS_DTL'] ;
EOD;
        $this->field_nota[5]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

        $this->pg_nota->set_component_id('pg_nota');
        $this->pg_nota->set_field($this->field_nota);
    }

    public function document() {
        $this->pg_doc->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_doc->set_ajax_url(site_url() . 'monitoring/search_doc');
        $this->data['doc_monitoring'] = $this->pg_doc->generate_all();

        $this->pg_nota->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_nota->set_ajax_url(site_url() . 'monitoring/search_nota');
        $this->data['nota_monitoring'] = $this->pg_nota->generate_all();

        // $this->data['records'] = $this->mm_inbox->get_inbox_monitoring( $this->data['userInfo']['uID'] );

        $this->data['layout'] = $this->folder . 'v_doc';

        $this->load->view('layout', $this->data);
    }

    public function nota() {
        // $this->data['records'] = $this->mm_inbox_nota->get_inbox( $this->data['userInfo']['uID'] );
        // //$this->data['records'] = 0;
        // $this->data['layout'] = $this->folder.'v_nota';
        // $this->load->view('layout', $this->data);
    }

    public function search_doc($page) {
        if (in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature')))) {
            $this->pg_doc->set_table('V_MONITORING_DOC_ADMIN');
        } else {
            $this->pg_doc->set_table('V_MONITORING_DOC');
            $this->pg_doc->set_where(array('MONITORING_OWNER' => $this->data['userInfo']['uID']));
        }
        $this->pg_doc->set_paging($_POST, 10, $page);
        $this->pg_doc->generate_table_data();
    }

    public function search_nota($page) {
        if (in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))) {
            $this->pg_nota->set_table('V_MONITORING_NOTA_ADMIN');
        } else {
            $this->pg_nota->set_table('V_MONITORING_NOTA');
            $this->pg_nota->set_where(array('MONITORING_OWNER' => $this->data['userInfo']['uID']));
        }
        $this->pg_nota->set_paging($_POST, 10, $page);
        $this->pg_nota->generate_table_data();
    }

    /* Setter */

    private function setter() {
        $this->data['config'] = mm_cache_config();
        $this->data['userInfo'] = $this->session->all_userdata();
        $this->data['service'] = 0;
        $this->data['header'] = 'header';
        $this->data['footer'] = 'footer';
        return $this->data;
    }

}
