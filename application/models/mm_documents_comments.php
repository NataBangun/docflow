<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_documents_comments extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}	
	
	/* 
	*  process = 1-4 [ draft, edit, review, publication ]
	*/
	public function get_all_comments($doc_id, $version_id=FALSE)
	{
		$return = FALSE;
		$sql = "
			SELECT
				documents_comments.comments_title,
				documents_comments.comments_descriptions,
				documents_comments.version_id as comment_version,
				documents_process.process_status,
				documents_process.version_id,
				documents.documents_title,
				documents.documents_description,
				documents_process.documents_id
			FROM
				documents_comments
			INNER JOIN documents_process ON documents_comments.documents_id = documents_process.documents_id
			INNER JOIN documents ON documents_process.documents_id = documents.documents_id
			WHERE
			documents_process.documents_id = $doc_id 
			";
		
		if($version_id)
		{
			$sql .= "AND documents_comments.version_id = $version_id ";
		}
		
		$sql .= " ORDER BY documents_comments.version_id, documents_comments.comments_id DESC";
		
		$query = $this->db->query($sql);
		
		if($query)
		{
			if($version_id)
			{
				$return = $query->row_array();
			}
			else
			{
				$return = $query->result_array();
			}
		}
		
		return $return;
	}
	
}