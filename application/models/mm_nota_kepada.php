<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mm_Nota_kepada extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_kepada() {
        $sql = "SELECT * FROM V_NOTA_KEPADA T1";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

    public function get_dari() {
        $sql = "SELECT * FROM V_NOTA_DARI T1";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

    public function get_tembusan() {
        $sql = "SELECT * FROM V_NOTA_TEMBUSAN";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

    public function get_klasifikasi() {
        $sql = "SELECT * FROM P_KLASIFIKASI";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

    public function get_pengesahan() {
        $sql = "SELECT * FROM V_NOTA_PENGESAHAN";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

    public function get_pembuat_konsep() {
        $sql = "SELECT * FROM V_NOTA_PEMBUAT_KONSEP";
        $query = $this->db->query($sql);
        if ($query) {
            $query = $query->result_array();
        } else {
            $query = FALSE;
        }
        return $query;
    }

}
