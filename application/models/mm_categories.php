<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mm_categories extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get() {
        $sql = "SELECT DBDOC.P_CATEGORIES.PK_CATEGORIES_ID, DBDOC.P_CATEGORIES.CATEGORIES_STATUS, DBDOC.P_CATEGORIES.CATEGORIES_TITLE, DBDOC.P_CATEGORIES.CATEGORIES_CDT, DBDOC.P_CATEGORIES.CATEGORIES_CBY, DBDOC.P_CATEGORIES.FK_TYPE_ID,DBDOC.P_CATEGORIES.CATEGORIES_STATUS, DBDOC.P_TYPE.PK_TYPE_ID, DBDOC.P_TYPE.TYPE_NAME FROM DBDOC.P_CATEGORIES INNER JOIN DBDOC.P_TYPE ON DBDOC.P_CATEGORIES.FK_TYPE_ID = DBDOC.P_TYPE.PK_TYPE_ID ORDER BY DBDOC.P_CATEGORIES.CATEGORIES_CDT DESC";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_type() {
        $sql = "SELECT
		DBDOC.P_TYPE.PK_TYPE_ID,
		DBDOC.P_TYPE.TYPE_NAME,
		DBDOC.P_TYPE.TYPE_DESC,
		DBDOC.P_TYPE.TYPE_CDT,
		DBDOC.P_TYPE.TYPE_CBY,
		DBDOC.P_TYPE.TYPE_STATUS
		FROM
		DBDOC.P_TYPE
		WHERE
		DBDOC.P_TYPE.TYPE_STATUS IS NULL OR
		DBDOC.P_TYPE.TYPE_STATUS = 0";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_num() {
        $sql = "SELECT
		Count(DBDOC.P_CATEGORIES.PK_CATEGORIES_ID)
		FROM
		DBDOC.P_CATEGORIES";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function get_process($categories_id = '') {
        $sql = "
            SELECT
                DBDOC.P_CATEGORY_PROCESS.PROCESS_NAME,
                DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
                DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
                DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID
            FROM
            DBDOC.P_CATEGORY_PROCESS ";
        if (is_numeric($categories_id)) {
            $sql .= "WHERE DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID = $categories_id ";
        }
        $sql .= "ORDER BY DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT ASC";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_detail() {
        $id = $this->uri->segment(3);
        $sql = "SELECT P_CATEGORIES.PK_CATEGORIES_ID, P_CATEGORIES.CATEGORIES_TITLE, P_CATEGORIES.FK_TYPE_ID, P_CATEGORIES.CATEGORIES_DESCRIPTION,P_CATEGORIES.CATEGORIES_IMAGE FROM P_CATEGORIES WHERE P_CATEGORIES.PK_CATEGORIES_ID = $id";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function get_process_by_id() {
        $id = $this->uri->segment(3);
        $sql = "
		SELECT
		DBDOC.P_CATEGORY_PROCESS.PROCESS_NAME,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_PDF_NAME,
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID
		FROM
		DBDOC.P_CATEGORY_PROCESS
		WHERE
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID = $id
		ORDER BY
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT ASC";
        $query = $this->db->query($sql);
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function insert_categories() {
        $config['upload_path'] = './uploads/category/';
        $config['remove_spaces'] = true;
        $config['allowed_types'] = 'png|jpg';
        $config['max_size'] = '51200';
        $this->load->library('upload', $config);
        $this->upload->do_upload();

        if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') {
            if (!$this->upload->do_upload()) {
                return False;
            }
        }

        $image_data = $this->upload->data();

        $timestamp = date('Y-m-d H:i:s');
        $desc = $this->input->post('desc');
        $data = array(
            'CATEGORIES_TITLE' => sanitize_filename($this->input->post('title')),
            'CATEGORIES_DESCRIPTION' => $desc,
            'CATEGORIES_IMAGE' => $image_data['file_name'],
            'FK_TYPE_ID' => $this->input->post('type'),
            'CATEGORIES_STATUS' => 0,
            'CATEGORIES_CBY' => $this->session->userdata('uID'),
            'CATEGORIES_CDT' => $timestamp
        );
        $cat = $this->db->insert('P_CATEGORIES', $data);

        $sql = 'SELECT
		Max(DBDOC.P_CATEGORIES.PK_CATEGORIES_ID)
		FROM
		DBDOC.P_CATEGORIES';
        $row = $this->db->query($sql)->row();

        foreach ($row as $key => $val) {
            $appID = $val;
        }

        $val = $this->input->post('val');

        /* tambahan */
        if ($val == null) {
            $val = 1;
        }
        /* -------------- */

        $data_val = '';
        for ($x = 1; $x <= $val; $x++) {
            $records = ($this->input->post('check_status' . $x) != 0) ? '1' : '0';
            $data = array(
                'FK_CATEGORIES_ID' => $appID,
                'PROCESS_PDF_NAME' => sanitize_filename($this->input->post('pdf_title' . $x)),
                'PROCESS_NAME' => sanitize_filename($this->input->post('add' . $x)),
                'PROCESS_TYPE' => $records,
                'PROCESS_SORT' => $x
            );
            $this->db->insert('P_CATEGORY_PROCESS', $data);
        }

        return TRUE;
    }

    public function update_categories() {
        $id = $this->input->post('id');
        $config['upload_path'] = './uploads/category/';
        $config['remove_spaces'] = true;
        $config['file_name'] = $this->input->post('fname');
        $config['overwrite'] = true;
        $config['allowed_types'] = 'png|jpg';
        $config['max_size'] = '51200';
        $this->load->library('upload', $config);
        $this->upload->do_upload();

        $image_data = $this->upload->data();

        if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') {
            if (!$this->upload->do_upload()) {
                return False;
            }
        }

        $timestamp = date('Y-m-d H:i:s');
        $desc = $this->input->post('desc');
        $data = array(
            'CATEGORIES_TITLE' => sanitize_filename($this->input->post('title')),
            'CATEGORIES_DESCRIPTION' => $desc,
            'CATEGORIES_IMAGE' => $image_data['file_name'],
            'FK_TYPE_ID' => $this->input->post('type'),
            'CATEGORIES_UBY' => $this->session->userdata('uID'),
            'CATEGORIES_UDT' => $timestamp
        );
        $this->db->where('PK_CATEGORIES_ID', $id);
        $cat = $this->db->update("P_CATEGORIES", $data);

        $this->db->where('FK_CATEGORIES_ID', $id);
        $this->db->delete('P_CATEGORY_PROCESS');

        if ($this->input->post('val')) {
            $val = $this->input->post('val');
        } else {
            $val = $this->input->post('categories_val');
        }

        $data_val = '';
        for ($x = 1; $x <= $val; $x++) {
            $records = (sanitize_filename($this->input->post('check_status' . $x) != 0)) ? '1' : '0';
            $add = $this->input->post('add' . $x);
            if ($add != 0 || $add != '') {
                $data = array(
                    'FK_CATEGORIES_ID' => $id,
                    'PROCESS_PDF_NAME' => sanitize_filename($this->input->post('pdf_title' . $x)),
                    'PROCESS_NAME' => sanitize_filename($this->input->post('add' . $x)),
                    'PROCESS_TYPE' => $records,
                    'PROCESS_SORT' => $x
                );
                $this->db->insert('P_CATEGORY_PROCESS', $data);
            }
        }

        return TRUE;
    }

    public function delete_image() {
        $id = $this->uri->segment(3);
        $this->db->where('PK_CATEGORIES_ID', $id);
        $cat = $this->db->get("P_CATEGORIES")->row();

        $dir = @unlink('./uploads/category/' . $cat->CATEGORIES_IMAGE);

        $this->db->set('CATEGORIES_IMAGE', '');
        $this->db->where('PK_CATEGORIES_ID', $id);
        $cat = $this->db->UPDATE("P_CATEGORIES");
        return TRUE;
    }

    public function aktif() {
        $id = $this->uri->segment(3);
        $this->db->set('CATEGORIES_STATUS', 0);
        $this->db->where('PK_CATEGORIES_ID', $id);
        $cat = $this->db->UPDATE("P_CATEGORIES");
        return TRUE;
    }

    public function non_aktif() {
        $id = $this->uri->segment(3);
        $this->db->set('CATEGORIES_STATUS', 1);
        $this->db->where('PK_CATEGORIES_ID', $id);
        $cat = $this->db->UPDATE("P_CATEGORIES");
        return TRUE;
    }

}
