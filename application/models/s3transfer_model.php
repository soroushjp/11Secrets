<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class S3transfer_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }

	public function getLocalImagePaths($numGet=0) {
		$this->db->select('imageid, img_local_path')->from('news_images')->where('location', 'tmp')->where('s3transfer_fail <', '2');
		
		if ($numGet > 0) {
			$this->db->limit($numGet);
		}
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			
			$results = $query->result_array();
			 
			foreach($results as $result) {
				$img_paths[$result['imageid']] = $result['img_local_path'];
			}
			
			return $img_paths;
		}
		
		return false;
	}
	
	public function setS3URL($imageid="", $imageURL="") {
		
		if(!is_numeric($imageid) || $imageURL=="") return false;
		
		$data = array(
		               'img_local' => $imageURL,
		               'location' => "s3"
		            );

		$this->db->where('imageid', $imageid);
		$success = $this->db->update('news_images', $data);
		
		if(!$success || $this->db->affected_rows() <= 0) return false;
		
		return true;
		
	}
	
	public function setTransferFail($imageid) {
		
		if(!is_numeric($imageid)) return false;
		
		$this->db->set('s3transfer_fail', 's3transfer_fail + 1', FALSE);
		$this->db->where('imageid', $imageid);
		$this->db->update('news_images');
		
	}

}

?>