<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function dwm_post_controller_constructor() {
    $CI =& get_instance();
    $CI->db->reconnect();
}
