<?
class Up_image {
	/*
	r_width=>圖片比例寬度 (可空值為原圖)
	r_height=>圖片比例高度 (可空值為原圖)
	Souce=> 設定上面寬高這就為空
	tmp=>是否要產生縮圖
	*/	
	//上傳一般圖檔 以plupload取代了
	// public function uploadimage($imgFile='',$name='',$path='', $r_width='', $r_height='',$Souce='',$tmp=''){
	public function uploadimage($imgFile='',$name='',$path='',$Config=array()){
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

				if($imgFile['type']=='image/png')
				{
					/* 儲存到檔案目錄(JPG) */
					/*$jpg = $this->imagetranstowhite($source,$r_width,$r_height);*/	//PNG透明變黑色的解決函式
					//imagepng($newImage, $imagePathDir . $imgName);
					copy($imgFile['tmp_name'], $imagePathDir . $imgName);
					/* 檔案resize */
					//$newImage=$this->resizeImage($imagePathDir . $imgName, $r_width, $r_height,1,$Souce);
					/* 檔案resize存檔 */
					//imagepng($newImage, $imagePathDir.''.$imgName);

					if(!empty($Config['tmp'])){
						$TmpPath=$imagePathDir.'tmp/';
						$this->create_dir($TmpPath);
						$TmpimgName='tmp_'.$imgName;
						
						$tmp_width=$r_width*0.5;
						$tmp_height=$r_height*0.5;
						copy($imgFile['tmp_name'], $TmpPath . $TmpimgName);
						/* 檔案resize */
						$newImage=$this->resizeImage($TmpPath . $TmpimgName, $tmp_width, $tmp_height,1,$Souce);
						/* 檔案resize存檔 */
						imagepng($newImage, $TmpPath.''.$TmpimgName);
					}

					//刪除原始圖檔
					//unlink($imagePathDir . $imgName);
				}
				else if($imgFile['type']=='image/gif'){
					// /* 儲存到檔案目錄(JPG) */
					// imagegif($source, $imagePathDir . $imgName);
					// /* 檔案resize */
					// $newImage=$this->resizeImage($imagePathDir . $imgName, $r_width, $r_height,2,$Souce);
					// /* 檔案resize存檔 */
					// imagegif($newImage, $imagePathDir.''.$imgName);
					$lastdot = strrpos($imgFile['name'], "."); //取出.最後出現的位置 
					$extended = substr($imgFile['name'], $lastdot); //取出副檔名 

					/*產生唯一的檔案名稱*/
					// $docName = md5(uniqid(rand())) . $extended;
					$imgName = $imgFile['name'];

					move_uploaded_file($imgFile["tmp_name"], $imagePathDir.$imgName);
					
				}else{
					
					/* 儲存到檔案目錄(JPG) */
					//imagejpeg($source, $imagePathDir . $imgName);
					copy($imgFile['tmp_name'], $imagePathDir . $imgName);
					/* 檔案resize */
					//$newImage=$this->resizeImage($imagePathDir . $imgName, $r_width, $r_height,'',$Souce);
					/* 檔案resize存檔 */
					//imagejpeg($newImage, $imagePathDir.''.$imgName);

					if(!empty($Config['tmp'])){
						$TmpPath=$imagePathDir.'tmp/';
						$this->create_dir($TmpPath);
						$TmpimgName='tmp_'.$imgName;
						$tmp_width=$r_width*0.5;
						$tmp_height=$r_height*0.5;
					
						copy($imgFile['tmp_name'], $TmpPath . $TmpimgName);
						/* 檔案resize */
						$newImage=$this->resizeImage($TmpPath . $TmpimgName, $tmp_width, $tmp_height,'',$Souce);
						/* 檔案resize存檔 */
						imagejpeg($newImage, $TmpPath.''.$TmpimgName);
					}
					
					//刪除原始圖檔
					//unlink($imagePathDir . $imgName);
				}			
	
				//圖檔資訊回傳
				$data=array(
					"path"	=>  $imagePathDir.''.$imgName,
					"size" 	=>	filesize($imagePathDir.''.$imgName),
					"error" => 	''
				);
				
	
				return $data;
				
			}
			else
			{
				$data=array(
					"error" => '檔案類型錯誤'
				);
				return $data;
			}

	}

	public function resizeImage($filename, $max_width, $max_height,$type='',$Souce='')
	{
	    list($orig_width, $orig_height) = getimagesize($filename);

	    $width = $orig_width;
	    $height = $orig_height;

	    # taller
	    if ($height > $max_height) {
	        $width = ($max_height / $height) * $width;
	    	$height = $max_height;
	    }
		
	    # wider
	    if ($width > $max_width) {
	        $height = ($max_width / $width) * $height;
	        $width = $max_width;
	    }
	    if(empty($Souce)){
			$width = $max_width;
	    	$height = $max_height;
		}

	    $image_p = imagecreatetruecolor($width, $height);
		imagealphablending($image_p, false);
		imagesavealpha($image_p, true);
		if($type==1)
		{
			$image = imagecreatefrompng($filename);
		}else if($type==2){
			$image = imagecreatefromgif($filename);
		}else{
			$image = imagecreatefromjpeg($filename);
		}
		
		


	    imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
	                                     $width, $height, $orig_width, $orig_height);

	    return $image_p;
	}

	public function uploadDoc($docFile)
	{
		//允許的副檔名
		$allowedExts = array("pdf", "doc", "docx", "ppt", "pptx", "xls", "xlsx");

		//檢查檔名合法
		$chk_file_ext= $this->CheckExtendName($docFile['name'], $allowedExts);

		if($chk_file_ext == 1)
		{
			$lastdot = strrpos($docFile['name'], "."); //取出.最後出現的位置 
			$extended = substr($docFile['name'], $lastdot); //取出副檔名 

			/*產生唯一的檔案名稱*/
			// $docName = md5(uniqid(rand())) . $extended;
			$docName = $docFile['name'];
			
			
			move_uploaded_file($docFile["tmp_name"], $docFile["path"].$docName);

			$data=array(
				"path"	=>  $docFile["path"].$docName,
				"size" 	=>	filesize($docFile["path"].$docName),
				"error" => 	''
			);

			return $data;
		}
		else
		{
			$data=array(
				"error" => '檔案類型錯誤'
			);
			return $data;
		}
	}

	//----------------------------------------------------------------------------------- 
	// 函數名：CheckExtendName($C_filename,$A_extend) 
	// 作 用：上傳文件的副檔名判斷 
	// 參 數：$C_filename 上傳的檔案名 
	// $A_extend 要求的副檔名 
	// 返回值：布林值 
	// 備 注：無 
	//----------------------------------------------------------------------------------- 
	public function CheckExtendName($C_filename, $A_extend) 
	{ 
		if(strlen(trim($C_filename)) < 5) 
		{ 
			return 0; //返回0表示沒上傳圖片 
		} 
		$alarm=$flag=0;
		$lastdot = strrpos($C_filename, "."); //取出.最後出現的位置 
		$extended = substr($C_filename, $lastdot+1); //取出副檔名 

		for($i=0;$i<count($A_extend);$i++) //進行檢測 
		{ 
			if (trim(strtolower($extended)) == trim(strtolower($A_extend[$i]))) //轉換大小寫並檢測 
			{ 
				$flag=1; //加成功標誌 
				$i=count($A_extend); //檢測到了便停止檢測 
			} 
		} 

		if($flag<>1) 
		{ 
			for($j=0;$j<count($A_extend);$j++) //列出允許上傳的副檔名種類 
			{ 
				$alarm .= $A_extend[$j]." "; 
			}
			return -1; //返回-1表示上傳圖片的類型不符 
		} 
		return 1; //返回1表示圖片的類型符合要求 
	} 

	// ------------------------------------------------
	// upload_ios_element
	// ------------------------------------------------
	// $file      : 上傳檔案
	// $file_dir  : 上傳路徑
	// $file_type : 上傳類型
	// $file_name : 檔案名稱
	// ------------------------------------------------
	public function upload_ios_element($file, $file_dir, $file_type, $file_name)
	{
		//允許的副檔名
		$allowedExts = array($file_type);

		//檢查檔名合法
		$chk_file_ext= $this->CheckExtendName($file['name'], $allowedExts);

		if($chk_file_ext == 1)
		{
			$lastdot = strrpos($file['name'], "."); //取出.最後出現的位置 
			$extended = substr($file['name'], $lastdot); //取出副檔名 

			// 產生唯一的檔案名稱
			if($file_name == '')
			{
				$fileName = md5(uniqid(rand())) . $extended;
			}
			else
				$fileName = $file_name . $extended;
			move_uploaded_file($file["tmp_name"], $file_dir.$fileName);

			$data=array(
				"path"	=>  $file_dir.$fileName,
				"size" 	=>	filesize($file_dir.$fileName),
				"error" => 	''
			);

			return $data;
		}
		else
		{
			$data=array(
				"error" => '檔案類型錯誤'
			);
			return $data;
		}
	}
	//創建目錄	
	public function create_dir($dir){
		if (!is_dir($dir))
		{
			
			$temp = explode('/',$dir);
			$cur_dir = '';
			for($i=0;$i<count($temp);$i++)
			{
				$cur_dir .= $temp[$i].'/';
				if (!is_dir($cur_dir))
				{
					mkdir($cur_dir,0777);
				}
			}
		}
	}
}
?>