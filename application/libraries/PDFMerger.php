<?php
/**
 *  PDFMerger created by Jarrod Nettles December 2009
 *  jarrod@squarecrow.com
 *  
 *  v1.0
 * 
 * Class for easily merging PDFs (or specific pages of PDFs) together into one. Output to a file, browser, download, or return as a string.
 * Unfortunately, this class does not preserve many of the enhancements your original PDF might contain. It treats
 * your PDF page as an image and then concatenates them all together.
 * 
 * Note that your PDFs are merged in the order that you provide them using the addPDF function, same as the pages.
 * If you put pages 12-14 before 1-5 then 12-15 will be placed first in the output.
 * 
 * 
 * Uses FPDI 1.3.1 from Setasign
 * Uses FPDF 1.6 by Olivier Plathey with FPDF_TPL extension 1.1.3 by Setasign
 * 
 * Both of these packages are free and open source software, bundled with this class for ease of use. 
 * They are not modified in any way. PDFMerger has all the limitations of the FPDI package - essentially, it cannot import dynamic content
 * such as form fields, links or page annotations (anything not a part of the page content stream).
 * 
 */
class PDFMerger
{
	private $_files;	//['form.pdf']  ["1,2,4, 5-19"]
	private $_fpdi;
	
	private $_header_footer_array;
	
	/**
	 * Merge PDFs.
	 * @return void
	 */
	public function __construct()
	{
		require_once(dirname(__FILE__) . '/fpdf/fpdf.php');
		require_once(dirname(__FILE__) . '/fpdi/fpdi.php');
	}
	
	/**
	 * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16. 
	 * @param $filepath
	 * @param $pages
	 * @return void
	 */
	public function addPDF($filepath, $pages = 'all')
	{
		if(file_exists($filepath))
		{
			if(strtolower($pages) != 'all')
			{
				$pages = $this->_rewritepages($pages);
			}
			
			$this->_files[] = array($filepath, $pages);
		}
		else
		{
			throw new exception("Could not locate PDF on '$filepath'");
		}
		
		return $this;
	}
	
	public function addPDF_header_footer($filepath, $pages = 'all', $header_footer = '')
	{
		$this->addPDF($filepath, $pages);
		
		if(file_exists($header_footer))
		{
			$this->_header_footer_array[pathinfo($filepath, PATHINFO_BASENAME)] = $header_footer;
		}
		else 
		{
			throw new exception("Could not locate Header Footer PDF on '$header_footer'");
		}
		
		return $this;
	}
	
	/**
	 * Merges your provided PDFs and outputs to specified location.
	 * @param $outputmode
	 * @param $outputname
	 * @return PDF
	 */
	public function merge($outputmode = 'browser', $outputpath = 'newfile.pdf')
	{
		if(!isset($this->_files) || !is_array($this->_files)): throw new exception("No PDFs to merge."); endif;
		
		$fpdi = new FPDI;
		
		//merger operations
		foreach($this->_files as $k=>$file)
		{
			$filename  = $file[0];
			$filepages = $file[1];
			
			if (isset($this->_header_footer_array[pathinfo($filename, PATHINFO_BASENAME)])) {
				$count_header_footer = $fpdi->setSourceFile($this->_header_footer_array[pathinfo($filename, PATHINFO_BASENAME)]);
				$template_header_footer = array();
				for ($i=1; $i<=$count_header_footer; $i++) {
					$template_header_footer[$i] = $fpdi->importPage($i);
				}
				// if ($k == 0) {
					// $template_header_footer = $fpdi->importPage(2); //halaman 2 dengan no halaman
				// } else {
					// $template_header_footer = $fpdi->importPage(1); //halaman 1 tanpa no halaman
				// }
			} else {
				$template_header_footer = null;
			}
					
			$count = $fpdi->setSourceFile($filename);
			
			//add the pages
			if($filepages == 'all')
			{
				for($i=1; $i<=$count; $i++)
				{
					$template 	= $fpdi->importPage($i);
					$size 		= $fpdi->getTemplateSize($template);
					
					$fpdi->AddPage('P', array($size['w'], $size['h']));
					$fpdi->useTemplate($template);			
					
					// if ($template_header_footer != null) {
						// if ($k == 0) {
							// if ($i == 2) { // hanya sampai halaman 2
								// $fpdi->useTemplate($template_header_footer);
							// }
						// } else {
							// $fpdi->useTemplate($template_header_footer);
						// }
					// }
					if ($k == 0) {
						if ($template_header_footer != null && isset($template_header_footer[$i])) {
							// halaman 1: kutipan halaman 2 jika ada. 
							// halaman 2: nomor surat nota dinas dengan no halaman
							$fpdi->useTemplate($template_header_footer[$i]); 
						}
					} else {
						// halaman 3: nomor surat nota dinas tanpa no halaman
						$fpdi->useTemplate($template_header_footer[3]); 
					}
				}
			}
			else
			{
				foreach($filepages as $page)
				{
					if(!$template = $fpdi->importPage($page)): throw new exception("Could not load page '$page' in PDF '$filename'. Check that the page exists."); endif;
					$size = $fpdi->getTemplateSize($template);
					
					$fpdi->AddPage('P', array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}	
		}
		
		//output operations
		$mode = $this->_switchmode($outputmode);
		
		if($mode == 'S')
		{
			return $fpdi->Output($outputpath, 'S');
		}
		else
		{
			$out = $fpdi->Output($outputpath, $mode);
			// var_dump($out);
			if($out == true)
			{
				// echo "<script>alert('Dokumen berhasil dibuat dan disatukan dengan lampiran')</script>";
				// echo "<script>window.location='$_SERVER[PHP_SELF]assets/pdf/TEST2.pdf';</script>";
				//echo "Sukses: " . $outputpath;
				return true;
			}
			else
			{
				throw new exception("Masih ada yang error! '$outputmode'.");
				return false;
			}
		}
		
		
	}
	
	/**
	 * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
	 * @param $mode
	 * @return Character
	 */
	private function _switchmode($mode)
	{
		switch(strtolower($mode))
		{
			case 'download':
				return 'D';
				break;
			case 'browser':
				return 'I';
				break;
			case 'file':
				return 'F';
				break;
			case 'string':
				return 'S';
				break;
			default:
				return 'I';
				break;
		}
	}
	
	/**
	 * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
	 * @param $pages
	 * @return unknown_type
	 */
	private function _rewritepages($pages)
	{
		$pages = str_replace(' ', '', $pages);
		$part = explode(',', $pages);
		
		//parse hyphens
		foreach($part as $i)
		{
			$ind = explode('-', $i);

			if(count($ind) == 2)
			{
				$x = $ind[0]; //start page
				$y = $ind[1]; //end page
				
				if($x > $y): throw new exception("Starting page, '$x' is greater than ending page '$y'."); return false; endif;	
				
				//add middle pages
				while($x <= $y): $newpages[] = (int) $x; $x++; endwhile;
			}
			else
			{
				$newpages[] = (int) $ind[0];
			}
		}
		
		return $newpages;
	}
	
}