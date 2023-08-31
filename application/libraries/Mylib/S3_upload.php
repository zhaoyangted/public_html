<?php

/**
 * Amazon S3 Upload PHP class
 *
 * @version 0.1
 */
class S3_upload {

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('Mylib/S3');

		$this->CI->config->load('s3', TRUE);
		$s3_config = $this->CI->config->item('s3');
		$this->bucket_name = $s3_config['bucket_name'];
		$this->folder_name = $s3_config['folder_name'];
		$this->s3_url = $s3_config['s3_url'];
	}

	function upload_file($imgFile='',$name='',$path='',$Config=array())
	{
		if(!$path)
		{
			$data=array(
				"error" => '沒有上傳路徑'
			);
			return $data;
		}		

		$imagePathDir = $path;
		/*上傳圖片文件類型列表 */
		$uptypes = array (
			'image/jpg',
			'image/jpeg',
			'image/pjpeg',
			'image/gif',
			'image/png'
		);
		
		/*產生唯一的檔案名稱*/
		
		if($name)
			$imgName = $name ;
		else
			$imgName = md5(uniqid(rand())) . '.jpg';
		
		/*檢查檔案大小 5Mb*/
		// if ($imgFile['size'] > 5242880)
		// {
		// 	$data=array(
		// 		"error" => '檔案過大, 檔案限制 : 5Mb'
		// 	);
		// 	return $data;
		// }
		/*檢查文件類型 */
			if(in_array($imgFile['type'], $uptypes))
			{
				
				/*上傳圖片類型為jpg,pjpeg,jpeg */
				if (strstr($imgFile['type'], "jp"))
				{
					if(!($source = @ imageCreatefromjpeg($imgFile['tmp_name'])))
					{
						$data=array(
							"error" => '檔案類型錯誤'
						);
						return $data;
					}
				  /*上傳圖片類型為png */
				}
				elseif(strstr($imgFile['type'], "png"))
				{

					if(!($source = @ imagecreatefrompng($imgFile['tmp_name'])))
					{
						$data=array(
							"error" => '檔案類型錯誤'
						);
						return $data;
					}
					/*上傳圖片類型為gif */
				}
				elseif(strstr($imgFile['type'], "gif"))
				{

					if(!($source = @ imagecreatefromgif($imgFile['tmp_name'])))
					{
						$data=array(
							"error" => '檔案類型錯誤'
						);
						return $data;
					}
				  // 其他例外圖片排除 
				}
				else
				{
					$data=array(
						"error" => '檔案類型錯誤'
					);
					return $data;
				}
				
				
				$w = imagesx($source); /*取得圖片的寬 */
				$h = imagesy($source); /*取得圖片的高 */
				
				$r_width=!(empty($Config['r_width']))?$Config['r_width']:$w;
				$r_height=!(empty($Config['r_height']))?$Config['r_height']:$h;
				$Souce=(!empty($Config['Souce']))?'Y':'';
        // generate unique filename
		//$file = pathinfo($file_path);
		//$s3_file = $file['filename'].'-'.rand(1000,1).'.'.$imgtype;
		//$mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_path);
       
        //print_r($this->s3_url);
		$saved = $this->CI->s3->putObjectFile(
			$imgFile['tmp_name'],
			$this->bucket_name,
			$imagePathDir.$imgName,
			//S3::ACL_PUBLIC_READ,
			array(),
			$imgFile['type']
		);
		if ($saved) {
           // print_r($this->s3_url.$imagePathDir.''.$imgName);
            return $imagePathDir.$imgName;
			//return $saved;
		}
    }else
    {
        $data=array(
            "error" => '檔案類型錯誤'
        );
        return $data;
    }
	}

}