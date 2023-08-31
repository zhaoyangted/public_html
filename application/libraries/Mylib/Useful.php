<?
class Useful {
	//分頁專用
	public function SetPage($sql,$Topage,$pagsize='20',$cond='',$total=''){
		
		$page=new page();
		$page->SetPagSize($pagsize);
		$page->SetMySQL($this->db);
		if(!empty($total)){
			$page->SetTotal($total);
		}
		$qpage=$page->PageStar($sql,$Topage,$cond);
		return $qpage;
	}

	public function get_page($qpage){
		$data=$this->load->view('mypage/page',$qpage,true);
		return $data;
	}

	//寫入LOG資料表
	public function write_log($d_type,$d_text){
		$logdata=array(
			'd_type'=>$d_type,
			'd_text'=>$d_text,
			'create_time'=>$this->useful->get_now_time(),
			'last_ip'=>$this->useful->get_ip(),
		);
		$this->load->model('jur_model','jmodel');
		$this->jmodel->insert_into('event_log',$logdata);
	}
	//產生寫入資料庫的陣列
	public function DB_Array($POST,$Create="",$NoDate='',$Isdata=''){
		$Array=array();
		foreach ($POST as $key => $value) {
			if(isset($value)){
				if(empty($Isdata))
		   			$val=Comment::SetValue($key);
		   		else
		   			$val=trim($POST[$key]);
				$Array+=array($key =>$val);
			}

		}

		if(empty($NoDate)){
			if(empty($Create)){
				$Array+=array('d_create_time'=>$this->get_now_time());
			}
			$Array+=array('d_update_time'=>$this->get_now_time(),'d_edit_ip'=>$this->get_ip());
		}
		return $Array;
	}
	//把圖文編輯器上傳的圖片變更為絕對路徑
	public function CKediterImg($Message){
        $body_arr=explode("/",$Message);
        foreach ($body_arr as $bk => &$bv) {
            if($bv=='demo'){
                unset($body_arr[$bk]);
            }
            if ($bv=='uploads') {
                $bv=site_url().$bv;
            }else{
                $bv=$bv;
            }
        }
        $Message=str_replace("/http:","http:",implode("/",$body_arr));

        return $Message;
    }
	//取得現在時間
	public function get_now_time(){
		date_default_timezone_set("Asia/Taipei");
		return date('Y-m-d H:i:s');
	}
	//取得來源IP
	public function get_ip(){			
		if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		{
		  $cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
		  $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif(!empty($_SERVER["REMOTE_ADDR"]))
		{
		  $cip = $_SERVER["REMOTE_ADDR"];
		}
		else{
		  $cip = "無法取得IP位址！";
		}
		 return $cip;
	}

	// 文字編輯器
	public function CKediter($push_path){
		// ckeditor 文字編輯器
		$this -> create_dir('../'.CCODE::DemoPrefix.'/uploads/'.$push_path.'/ckfinder_image/');
		$this -> start_session(3600);
		$_SESSION['ckeditor_url']=CCODE::DemoPrefix.'/uploads/'.str_replace(".", "", $push_path).'/ckfinder_image';
		session_write_close();
		// ckeditor 文字編輯器
	}

	//亂數密碼產生
	public function generatorPassword($number){
		$password_len = $number;
		$password = '';
		// remove o,0,1,l
		$word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$len = strlen($word);
		for ($i = 0; $i < $password_len; $i++) {
			$password .= $word[rand() % $len];
		}
		return $password;
	}
	//亂數+時間產生亂數
	public function RandTimeNumber($number,$num){
		$password_len = $number;
		$password = '';
		// remove o,0,1,l
		$word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$len = strlen($word);
		for ($i = 0; $i < $password_len; $i++) {
			$password .= $word[rand() % $len];
			$rand=substr(md5(date('Ymdhis').microtime('true').$password.$num),0,14);
		}
		return $rand;
	}

	//權限判斷
	public function CheckComp($comp){
		@session_start();
	
		// if(!isset($_SESSION['AT']['action_list'])){
		// 	echo '<script>alert("連線逾時，請重新登入");window.location.href="/index/logout";</script>';
		// 	return '';
		// }
		$action_list=isset($_SESSION['AT']['action_list'])?$_SESSION['AT']['action_list']:"";
		// !strpos(',' .$action_list. ',', ',' . $comp . ',') 用法錯誤
		if($action_list==''){
			echo '<script>alert("連線逾時，請重新登入");top.window.location.href="/index/logout"; </script>';
			return '';
		}elseif(strpos(',' .$action_list. ',', ',' . $comp . ',') === false){
			
			echo '<script>alert("沒有此權限");window.location.href="/admin/panel"; </script>';
			return '';
		}
	}

	//權限設定
	public function set_jur($type=''){
		
		$this->load->model('jur_model','jmodel');
		$data=$this->jmodel->get_jur();
		$_SESSION['AT']['action_list']=empty($_SESSION['AT']['action_list'])?"":$_SESSION['AT']['action_list'];
		$action_code_all="";
		foreach ($data as $key => $value) {
			$cdata=$this->jmodel->get_jur($value['d_id']);
			foreach ($cdata as $ckey => $cvalue) {
				// 20160524
				if (strpos(',' .$_SESSION['AT']['action_list'].',',','.$cvalue['d_code'].',') !== false or $type!='')
				{
					$sdata[$value['d_name']][]=array(
						'd_code'=>$cvalue['d_code'],
						'd_name'=>$cvalue['d_name'],
						'd_link'=>$cvalue['d_link'],
					);				
					$action_code_all.=$cvalue['d_code'].',';
				}
			}
			$sdata[$value['d_name']]['action_code_all']=$action_code_all;
			$sdata[$value['d_name']]['menu_action']=$value['d_code'];
			$action_code_all='';

			// 20160524
			if(empty($sdata[$value['d_name']]['action_code_all']))
				unset($sdata[$value['d_name']]);
		}
		return $sdata;
	}

	//刪除陣列Key值
	public function UnsetArray($data,$array){
		foreach ($array as $value) {
			unset($data["$value"]);
		}

		return $data;
	}

	//判斷CheckBox值 因不勾選為空值 所以空值就給N
	public function ChkCheck($data,$array){
		foreach ($array as $value) {
			$data[$value]=(!empty($data[$value]))?'Y':'N';
		}
		return $data;
	}

	//去除字串最後一碼
	public function del_string_last($str){
		return substr($str,0,strlen($str)-1);
	}

	//跳頁
	public function AlertPage($url='',$msg=''){
		$NetPrefix=CCODE::DemoPrefix;
		if($url!=''){ 
			$url=(!empty($NetPrefix))?$NetPrefix.'/'.$url:site_url($url);
			if($msg!=''){
				echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
			}else{
				echo '<script>window.location.href="'.$url.'";</script>';
			}
		}else{
			echo '<script>alert("'.$msg.'");history.go(-1);</script>';
		}
	}

	
	//檢視錯誤訊息
	public function ViewError(){
		ini_set("display_errors", "On"); // 顯示錯誤是否打開( On=開, Off=關 )
		error_reporting(E_ALL & ~E_NOTICE);
	}

	//開啟關閉判斷
	public function ChkOC($Enum){
		$Result=($Enum=='Y')?"<div class='dot-enable'></div>":"<div class='dot-disable'></div>";
		return $Result;
	}

	//擷取youtube代碼
	public function GetYoutube($url='',$rurl=''){
		//去除首尾空白
		$url=trim($url);

		if(!empty($url)){
			//擷取id
			if($pos = strpos($url, '?v=') !== false){
				//後綴參數檢查
				$pos=strpos($url, '?v=');
				$and_mark=strpos($url, '&');
				if($and_mark != false)
				{
					$id=substr($url, $pos+3, ($and_mark-$pos-3));
				}
				else
				{
					$id=substr($url, $pos+3);
				}
			}
			else{
				//youtu.be檢查
				if($pos = strpos($url, 'youtu.be') !== false)
				{
					$pos=strrpos($url, '/');
					$and_mark=strpos($url, '&');
					if($and_mark != false)
					{
						$id=substr($url, $pos+1, ($and_mark-$pos-1));
					}
					else
					{
						$id=substr($url, $pos+1);
					}
				}
				else
				{
					$id='';
				}
			}
			return $id;
		}
		if(!empty($rurl)){
			$youurl='https://www.youtube.com/watch?v=';
			return $youurl.$rurl;
		}
	}

	//取得每月最後一天
	public function getCurMonthLastDay($date) {
	    $ldata=date('Y-m-t',strtotime(' +1 month '));
	    return $ldata;
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
	//刪除資料夾(連同下面的檔案和資料夾)
	public function remove_dir($dir, $del_root_dir=''){
		if(!$dh = @opendir($dir)) return;
		
		while (false !== ($obj = readdir($dh)))
		{
			if($obj=='.' || $obj=='..') continue;
			if (!@unlink($dir.'/'.$obj)) $this->remove_dir($dir.'/'.$obj, true);
		}
		if ($del_root_dir)
		{
			closedir($dh);
			rmdir($dir);
		}
	}
	//session
	private function start_session($expire = 0)
	{
	   if(!isset($_SESSION)) {
		if ($expire == 0) {
	        $expire = ini_get('session.gc_maxlifetime');
	    } else {
	        ini_set('session.gc_maxlifetime', $expire);
	     }
	 
	    if (isset($_COOKIE["PHPSESSID"])) {
	        session_set_cookie_params($expire);
			//header('Set-Cookie: PHPSESSID='.$_COOKIE["PHPSESSID"].'; SameSite=None');
	        @session_start();
	    } else {
	        @session_start();
	        setcookie('PHPSESSID', session_id(), time() + $expire);
	     }
		} 
	}
	
	//短網址
	public function getTinyUrl($url) { 
		return file_get_contents("http://tinyurl.com/api-create.php?url=".$url); 
	}

	// 複製檔案 Copy
	public function CopyFile($source,$destination){
		/* copy(source,destination) 
			source =>來源
			destination=>目的地
		*/
		copy($source,$destination);
	}
	// 下載檔案
	public function DownloadFile($File,$FileName,$Filels){
	   	// $File='201803230951442372.jpeg'; 檔案名
	   	// $FileName='Test'; 下載後的檔名
	   	// $Filels='down'; 下載路徑資料夾
		
		$file_path = "./uploads/".$Filels."/" . $File;
		if(!is_file($file_path)){
			echo $file_path;
			exit();
		}
		$file_size = filesize($file_path);
		$file_Sname = substr($File, strrpos($File , ".")+1);

		header('Pragma: public');
		header('Expires: 0');
		header('Last-Modified: ' . gmdate('D, d M Y H:i ') . ' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		//header('Content-Type: application/octet-stream');
		header("Content-Type: application/download");
		header('Content-Length: ' . $file_size);
		header('Content-Disposition: attachment; filename="' . iconv("UTF-8","Big5",$FileName). '";');
		header('Content-Transfer-Encoding: binary');
		readfile($file_path);
	}
	

	//----------------------------------------------------------------------------------- 
	// 編號	 ：17
	// 函數名：random_vcode($len)
	// 作 用 ：產生隨機n碼作為驗證碼
	// 參 數 ：$len 驗證碼長度
	// 返回值：隨機n碼陣列
	// 備 注 ：session_vcode清空 
	//----------------------------------------------------------------------------------- 
	public function random_vcode($len){
		unset($_SESSION['vo']['VCODE']);
		srand((double)microtime()*1000000);
		for($i = 0; $i < $len; $i++)
		{
			$authnum=rand(1,9);
			$vcodes[$i]=$authnum;
		}
		return $vcodes;
	}
	// 亂數英數
	public function GetRandEngNum($random='10'){
        $randoma='';
        //FOR回圈以$random為判斷執行次數
        for ($i=1;$i<=$random;$i=$i+1)
        {
            //亂數$c設定三種亂數資料格式大寫、小寫、數字，隨機產生
            $c=rand(1,3);
            //在$c==1的情況下，設定$a亂數取值為97-122之間，並用chr()將數值轉變為對應英文，儲存在$b
            if($c==1){$a=rand(97,122);$b=chr($a);}
            //在$c==2的情況下，設定$a亂數取值為65-90之間，並用chr()將數值轉變為對應英文，儲存在$b
            if($c==2){$a=rand(65,90);$b=chr($a);}
            //在$c==3的情況下，設定$b亂數取值為0-9之間的數字
            if($c==3){$b=rand(0,9);}
            //使用$randoma連接$b
            $randoma=$randoma.$b;
        }
        //輸出$randoma每次更新網頁你會發現，亂數重新產生了
        return $randoma;
    }
	/**
	 *
	 * 修改:mcrypt对称加密代码在PHP7.1已经被抛弃了，所以使用下面的openssl来代替
	 * Encrypts data.
	 *
	 * @param string $data
	 *        	data to be encrypted.
	 * @param string $key
	 *        	the decryption key. This defaults to null, meaning using {@link getEncryptionKey EncryptionKey}.
	 * @return string the encrypted data
	 * @throws CException if PHP Mcrypt extension is not loaded or key is invalid
	 */
	public function encrypt($data, $key = 'jddtshin') {
		//$key = 'jddtshin';
		if ($key === null)
			$key = $this->getEncryptionKey ();
		//$this->validateEncryptionKey ( $key );
		$text = $data;
		$iv = substr ( $key, 0, 16 );
		
		$block_size = 32;
		$text_length = strlen ( $text );
		$amount_to_pad = $block_size - ($text_length % $block_size);
		if ($amount_to_pad == 0) {
			$amount_to_pad = $block_size;
		}
		$pad_chr = chr ( $amount_to_pad );
		$tmp = '';
		for($index = 0; $index < $amount_to_pad; $index ++) {
			$tmp .= $pad_chr;
		}
		$text = $text . $tmp;
		/*
		 * mcrypt对称加密代码在PHP7.1已经被抛弃了，所以使用下面的openssl来代替
		 * $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		 * $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		 * mcrypt_generic_init($module, $key, $iv);
		 * $encrypted = mcrypt_generic($module, $text);
		 * mcrypt_generic_deinit($module);
		 * mcrypt_module_close($module);
		 */
		$encrypted = openssl_encrypt ( $text, 'des-ede3', $key, /* OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv */0 );
		$encrypt_msg = base64_encode ( $encrypted );
		return $encrypt_msg;
	}
	
	/**
	 *
	 * 修改:mcrypt对称加密代码在PHP7.1已经被抛弃了，所以使用下面的openssl来代替
	 * Decrypts data
	 *
	 * @param string $data
	 *        	data to be decrypted.
	 * @param string $key
	 *        	the decryption key. This defaults to null, meaning using {@link getEncryptionKey EncryptionKey}.
	 * @return string the decrypted data
	 * @throws CException if PHP Mcrypt extension is not loaded or key is invalid
	 */
	public function decrypt($data, $key = 'jddtshin', $appid = '') {
		if ($key === null)
			$key = $this->getEncryptionKey ();
		//$this->validateEncryptionKey ( $key );
		$ciphertext_dec = base64_decode ( $data );
		$iv = substr ( $key, 0, 16 );
		/*
		 * mcrypt对称解密代码在PHP7.1已经被抛弃了，所以使用下面的openssl来代替
		 * $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		 * mcrypt_generic_init($module, $key, $iv);
		 * $decrypted = mdecrypt_generic($module, $ciphertext_dec);
		 * mcrypt_generic_deinit($module);
		 * mcrypt_module_close($module);
		 */
		$decrypted = openssl_decrypt ( $ciphertext_dec, 'des-ede3', $key); /*OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING , $iv */ 
		return $decrypted;
	}
}