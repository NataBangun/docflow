<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once dirname(__FILE__) . '/tcpdf2/tcpdf.php';
 
class Pdf2 extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}
 
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */