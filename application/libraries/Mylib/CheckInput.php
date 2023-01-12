<?
class CheckInput  { // 處理回傳資料
	public $rq = array (); // 回傳變數
	public $error = ""; // 回傳錯誤訊息
	public $fname = array (); // 傳入檢查的資料
	public $ajax = ""; // 顯示的方式

	public function main($url='') {
		if (! empty ( $this->fname )) {
			foreach ( $this->fname as $key => $val ) { // 0檢查類別1名稱2值3說明
				$Val0=$val[0];
				$err=$this->$Val0($val[1],$val[2]);
				
				if($err!=''){
					$error[]=$err;
				}
			}
			if(!empty($error))
				return  $this->error($error,$url);
		}
	}
	//檢查字串是否輸入
	private function _String($str,$warn){
		$error="";
		if($str==''){
			$error='請輸入'.$warn;	//請輸入
		}
		return $error;
	}
	//檢查select是否選擇
	private function _Select($str,$warn){
		$error="";

		if($str==0){
			$error='請選擇'.$warn;	//請選擇			
		}
		return $error;
	}
	//檢查checkbox & radio
	private function _CheckRadio($str,$warn){
		$error="";
		if($str=='')
			$error='請勾選'.$warn;	//請選擇
		return $error;
	}
	//上傳檢查
	private function _CheckFile($str,$warn){
		$error="";
		if($_FILES[$str]['error']!=0)
			$error='請上傳'.$warn;	
		return $error;
	}
	//檢查手機
	private function _CheckPhone($str,$warn=''){
		$error="";
		if(!preg_match("/09[0-9]{8}/", $str))
	        $error='手機格式錯誤，請重新輸入';	 //手機格式錯誤，請重新輸入
		return $error;
	}
	//檢查信箱
	private function _CheckEmail($str,$warn){
		$error="";
		if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str))
			$error=$warn.'格式錯誤，請重新輸入';	//格式錯誤，請重新輸入
		return $error;
	}
	//檢查身分證
	private function _CheckIDNum($str,$warn=''){
		$data = "";
		$error = false;
		// 建立字母分數陣列
		$head = array (
				'A' => 1,
				'I' => 39,
				'O' => 48,
				'B' => 10,
				'C' => 19,
				'D' => 28,
				'E' => 37,
				'F' => 46,
				'G' => 55,
				'H' => 64,
				'J' => 73,
				'K' => 82,
				'L' => 2,
				'M' => 11,
				'N' => 20,
				'P' => 29,
				'Q' => 38,
				'R' => 47,
				'S' => 56,
				'T' => 65,
				'U' => 74,
				'V' => 83,
				'W' => 21,
				'X' => 3,
				'Y' => 12,
				'Z' => 30 
		);
		$multiply = array (
				8,
				7,
				6,
				5,
				4,
				3,
				2,
				1 
		); // 建立加權基數陣列
		if (preg_match ( "/^[a-zA-Z][1-2][0-9]+$/", $str ) && strlen ( $str ) == 10) { // 檢查身份字格式是否正確
			$len = strlen ( $str ); // 切開字串
			for($i = 0; $i < $len; $i ++) {
				$stringArray [$i] = substr ( $str, $i, 1 );
			}
			$total = $head [array_shift ( $stringArray )]; // 取得字母分數
			$point = array_pop ( $stringArray ); // 取得比對碼
			$len = count ( $stringArray ); // 取得數字分數
			for($j = 0; $j < $len; $j ++) {
				$total += $stringArray [$j] * $multiply [$j];
			}
			if (($total % 10 == 0) ? 0 : 10 - $total % 10 != $point) { // 檢查比對碼
				$error = true;
			}
		} else {
			$error = true;
		}
		if ($error) {
			$data = '身分證格式錯誤';
		}
		return $data;
	}
	//統編
	private function _CheckUBN($str) {
		if (!preg_match('/^[0-9]{8}$/', $str)) {
			return '統編輸入錯誤1';
		}
		$tbNum = array(1,2,1,2,1,2,4,1);
		$intSum = 0;
		for ($i = 0; $i < 8; $i++) {
			$intMultiply = $str[$i] * $tbNum[$i];
			$intAddition = (floor($intMultiply / 10) + ($intMultiply % 10));
			$intSum += $intAddition;
		}

		if(!(($intSum % 10 == 0 ) || ($intSum%10==9 && substr($str,6,1)==7)))
			return '統編輸入錯誤2';
	}
	//兩值是否相同
	private function _CheckSome($str,$error) {
		if($str[0]!=$str[1]){
			return $error;
		}
	}
	private function error($err,$url=''){
		$str='';
		foreach ($err as $value) {
			$str.=$value."\\n";
		}
		$err=header("Cache-control: private");
		if($url!='')
			$err.="<script>alert('".$str."');window.location.href='".$url."';</script>";
		else
			$err.="<script>alert('".$str."');history.go(-1);</script>";
		return $err;
	}
}