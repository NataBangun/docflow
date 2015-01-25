<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mm_users extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get($key = "get_all_users", $expired = 500) {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
        $hashed_key = md5($key);

        if (!$query = $this->cache->get($hashed_key)) {
            $sql = "SELECT T_USERS.PK_USERS_ID
			FROM T_USERS";
            $query = $this->db->query($sql);
            if ($query) {
                $query = $query->result_array();
            } else {
                $query = FALSE;
            }

            $this->cache->save($hashed_key, $query, $expired);
        }

        return $query;
    }

    public function get_sign($key = "get_all_users", $expired = 500) {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
        $hashed_key = md5($key);

        if (!$query = $this->cache->get($hashed_key)) {
            $sql = "SELECT V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO,V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME,V_EMPLOYEE_DOKUMEN_PROSEDUR.E_MAIL_ADDR
			FROM V_EMPLOYEE_DOKUMEN_PROSEDUR";
            $query = $this->db->query($sql);
            if ($query) {
                $query = $query->result_array();
            } else {
                $query = FALSE;
            }

            $this->cache->save($hashed_key, $query, $expired);
        }

        return $query;
    }

    public function get_list_sign() {
        $sql = "SELECT
			DBDOC.V_EMPLOYEE.EMPLOYEE_NO,
			DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,
			DBDOC.V_EMPLOYEE.E_MAIL_ADDR,
			DBDOC.T_USERS.PK_USERS_ID,
			DBDOC.T_USERS.USERS_PARAF,
			DBDOC.T_USERS.USERS_SIGNATURE
			FROM
			DBDOC.V_EMPLOYEE
			LEFT JOIN DBDOC.T_USERS ON DBDOC.V_EMPLOYEE.EMPLOYEE_NO = DBDOC.T_USERS.EMPLOYEE_NO";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

    public function uploads() {
        $id = $this->uri->segment(3);
        $this->load->library('upload');
        $this->load->helper('date');
        $timestamp = date('Y-m-d H:i:s');

        $config['upload_path'] = UPLOAD_TTD;
        $config['allowed_types'] = UPLOAD_TTD_PARAF_FILE_TYPE;
        $config['file_name'] = $id;
        $config['overwrite'] = true;
        $config['max_size'] = UPLOAD_TTD_PARAF_SIZE_KB;

        //$this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') {
            if (!$this->upload->do_upload()) {
                return False;
            }
            $ttd_data = $this->upload->data();
            $tanda = $ttd_data['file_name'];
        } else {
            $tanda = $this->input->post('ttd_file');
        }

        $config['upload_path'] = UPLOAD_PARAF;
        $config['allowed_types'] = UPLOAD_TTD_PARAF_FILE_TYPE;
        $config['file_name'] = $id;
        $config['overwrite'] = true;
        $config['max_size'] = UPLOAD_TTD_PARAF_SIZE_KB;

        //$this->load->library('upload', $config);
        $this->upload->initialize($config);
        //$this->upload->do_upload('paraf');

        if (isset($_FILES['paraf']['name']) && $_FILES['paraf']['name'] != '') {
            if (!$this->upload->do_upload('paraf')) {
                return False;
            }

            $paraf_data = $this->upload->data('paraf');
            $paraf = $paraf_data['file_name'];
        } else {
            $paraf = $this->input->post('paraf_file');
        }
        $this->db->where('EMPLOYEE_NO', $id);
        $row = $this->db->get('T_USERS')->row_array();
        if ($row) {
            $documents = array(
                'USERS_SIGNATURE' => $tanda,
                'USERS_PARAF' => $paraf,
                'EMPLOYEE_NO' => $id,
                'USERS_CBY' => $this->session->userdata('uID'),
                'USERS_CDT' => $timestamp
            );
            $this->db->where('EMPLOYEE_NO', $id);
            $db = $this->db->update('T_USERS', $documents);
        } else {
            //$ttd_data = $this->upload->data();
            //$paraf_data = $this->upload->data('paraf');
            $documents = array(
                'USERS_SIGNATURE' => $tanda,
                'USERS_PARAF' => $paraf,
                'EMPLOYEE_NO' => $id,
                'USERS_CBY' => $this->session->userdata('uID'),
                'USERS_CDT' => $timestamp
            );
            $db = $this->db->insert('T_USERS', $documents);
        }

        if ($db) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
