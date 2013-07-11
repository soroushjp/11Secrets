<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class S3transfer extends CI_Controller {
	
	public function index() {
		
		$this->load->model('s3transfer_model');
		$this->load->library('S3');
		
		if (!defined('awsAccessKey')) define('awsAccessKey', 'AWS_PUBLIC_KEY');  
		if (!defined('awsSecretKey')) define('awsSecretKey', 'AWS_SECRET_KEY');
		
		$bucket_name = "media8.11secrets.com";
		$base_new_url = "http://media8.11secrets.com/";
		$numTransfer = 60;
		
		$local_img_paths = $this->s3transfer_model->getLocalImagePaths($numTransfer);
		
		if(!$local_img_paths) die("Couldn't get local image filepaths<br/>\n<br/>\n");
		
		$s3 = new S3(awsAccessKey, awsSecretKey);
		
		$s3->putBucket($bucket_name, S3::ACL_PUBLIC_READ);
		
		$s = 0;
		$f = 0;
		
		foreach($local_img_paths as $imageid => $path) {
			
			$filepath = $path;
			$filename = basename($filepath);
			
			if ($s3->putObjectFile($filepath, $bucket_name, $filename, S3::ACL_PUBLIC_READ)) {  
			
				$remote_path = $base_new_url . $filename;
			
			    echo "We successfully uploaded your file. <br/>\n ";
				echo "Original file location: " . $path . "<br/>\n";
				echo "New file location: " . $remote_path . "<br/>\n";
				
				if($this->s3transfer_model->setS3URL($imageid, $remote_path)) {
					echo "Image location successfully updated in DB". "<br/>\n";
					
					//Remove temporary local file
					if(unlink($filepath)) echo "Temporary file removed successfully" . "<br/>\n<br/>\n";
					else echo "ERROR: Temporary file couldn't be removed." . "<br/>\n<br/>\n";
					
					$s++;
					
				} else {
					echo "ERROR: Image location unsuccessfully updated in DB". "<br/>\n<br/>\n";
					
					$this->s3transfer_model->setTransferFail($imageid);
					
					$f++;
					continue;
				}
				
				
			} else {  
			    echo "ERROR: Something went wrong while uploading your file... sorry. File: $path <br/>\n<br/>\n";
			
				$this->s3transfer_model->setTransferFail($imageid);
				
				$f++;
				continue;
			}
				
		}
		
		echo "<b>Successfully transferred $s files to S3 bucket '$bucket_name'. $f files unsuccessful.</b>";
	}
	
	public function tmpclean() {
		
		//Function to clean temporary /var/www/tmp/ directory of unreferenced (and hence unused) image files (TO BE BUILT)
		
		//Function to clean Amazon bucket of unreferenced (and hence unused) image files (TO BE BUILT)
		
	}

}

?>
