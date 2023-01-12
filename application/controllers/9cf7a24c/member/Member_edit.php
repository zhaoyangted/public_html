<?php
class Member_edit extends CI_Controller {
	public function __construct() {
		parent::__construct();

		// 各專案後臺資料夾
		$AdminName = $this->webmodel->BaseConfig();
		$this->AdminName = $AdminName['d_title'];

		$this->FunctionType = 'edit';
		// 自動頁面設定
		$this->tableful->GetAutoPage($this->FunctionType);
		// 資料庫名稱
		$this->DBname = $this->tableful->MenuidDb['d_dbname'];
		// 後台基本設定
		$this->autoful->backconfig();
	}

	public function index($d_id) {
		if (!empty($d_id)) {
			$this->tableful->TableTreat(2);
			$this->tableful->TableTreat(3);
			$this->tableful->TableTreat(4);
			$this->tableful->TableTreat(38);

			$data['d_id'] = $d_id;
			$dbdata = $this->mymodel->OneSearchSql($this->DBname, $this->tableful->SqlList, array('d_id' => $d_id));

			/*特殊檢查位置*/
			if ($dbdata['d_user_type']==2) { //企業用戶拔除未申請狀態
				unset($this->tableful->Menu[39]['Config'][1]);
			}
			if ($dbdata['d_chked']==4 || $dbdata['d_chked']==1) { //未驗證時不給改狀態
				$this->tableful->Menu[39]['d_type']=7;
			}else{
				unset($this->tableful->Menu[39]['Config'][4]);
			}
			/*特殊檢查位置*/

			// print_r($dbdata['d_operate_service']);
			$dbdata['d_operate_service']=(!empty($dbdata['d_operate_service'])?implode(',',json_decode($dbdata['d_operate_service'],true)):'');
			$this->load->library('encryption');
			$dbdata['d_password'] = $this->encryption->decrypt($dbdata['d_password']);
			$data['dbdata'] = $dbdata;

			// 撈取最後一筆訂單
			$data['LastOrder']=$this->GetLastOrder($d_id);

			// 隱藏欄位
			$DisArray=array();
			for ($i=17; $i <=33 ; $i++) {
				if(!in_array($i,array('28'))){
					$DisArray[]='#'.$this->tableful->Menu[$i]['d_fname'].'_li';
				}
			}
			$data['DisArray']=implode(',',$DisArray);
			// $this->load->view('' . $this->AdminName . '/autopage/_info', $data);
			$this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_info',$data);
		} else {
			$this->useful->AlertPage('', '操作錯誤');
		}

	}
	// 編輯
	public function edit() {
		$this->load->library('mylib/CheckInput');
		$this->load->model('MyModel/Webmodel', 'webmodel');
		$check = new CheckInput;
		$url = $dbname = $msg = '';
		foreach ($this->tableful->Search as $key => $value) {
			if ($value[2] == '_CheckFile') {
				if ((empty($_POST['' . $key . '_ImgHidden']) and $value[0] == 8) or (empty($_POST['' . $key . '_Hidden']) and $value[0] == 14)) {
					$check->fname[] = array($value[2], $key, $value[1]);
				}

			} else {
				$check->fname[] = array($value[2], Comment::SetValue($key), $value[1]);
			}

		}

		/*特殊檢查位置*/
		if ($_POST['d_user_type'] == 1) { //一般用戶時，公司資訊為非必填
			for ($i = 13; $i < 27; $i++) {
				unset($check->fname[$i]);
			}
		} else {
			for ($i = 8; $i <= 11; $i++) { //企業用戶時，個人地址相關為非必填
				unset($check->fname[$i]);
			}
			if ($_POST['d_company_type'] == 2) { //企業用戶且公司類型為個人工作室時，抬頭及統編為非必填
				unset($check->fname[15]);
				unset($check->fname[16]);
			}
		}
		if (isset($_POST['d_chked'])) {
			if ($_POST['d_chked'] != 3) { // 審核狀態不為已審核時，分類非必填
				unset($check->fname[1]);
			}
		}else{
			unset($check->fname[28]); // 審核狀態為單純觀看，改為非必填
		}

		// 已開業，開業日期*預定地址*員工人數非必填
		if ($_POST['d_operate_type'] == 1) {
			unset($check->fname[24]);
			unset($check->fname[25]);
			unset($check->fname[26]);
		}
		/*特殊檢查位置*/

		$Cck = $check->main('');
		if (!empty($Cck)) {
			echo $check->main($url);
			return '';
		}

		$this->load->library('encryption'); //加密
		$_POST['d_password'] = $this->encryption->encrypt($_POST['d_password']);

		$post = (!empty($_POST)) ? $_POST : '';
		$d_id = (!empty($_POST['d_id'])) ? $_POST['d_id'] : '';
		$dbname = (!empty($_POST['dbname'])) ? $_POST['dbname'] : '';

		$dbdata = $this->useful->DB_Array($post, $d_id);

		$UnsetArray = array('d_id', 'dbname', 'BackPageid');
		$dbdata = $this->useful->UnsetArray($dbdata, $UnsetArray);

		/*特殊檢查位置*/
		$dbdata['TID1'] = !empty($dbdata['TID1'])?implode('@#', $dbdata['TID1']):'';
		// if(!empty($dbdata['d_chked'])){
			if ($dbdata['d_user_type']==2 && !isset($dbdata['d_chked'])) {	
				// 如果為企業用戶，審核狀態為未申請，自動改為未審核
				$dbdata['d_chked']=2;
			}
		// }
		// 寫入會員生效期限
		// $dbdata['d_upgrade_date']=
			$this->WriteDeadline($_POST['d_lv'],$d_id,$dbdata['d_chked']);

		/*特殊檢查位置*/

		$msg = $this->mymodel->UpdateData($dbname, $dbdata, ' where d_id=' . $d_id . '');

		$this->useful->AlertPage($this->AdminName . '/' . $dbname . '/' . $dbname, '修改成功');
	}
	// 刪除
	public function deletefile() {
		if ($_POST['deltype'] == 'Y') {
			$dbname = $_POST['dbname'];
			$this->mymodel->DelectData($dbname, ' where d_id=' . $_POST['d_id'] . '');
			$this->useful->AlertPage($this->AdminName . '/' . $dbname . '/' . $dbname, '刪除成功');
		} else {
			$this->useful->AlertPage('', '操作錯誤');
		}

	}
	// 撈取最後一筆訂單
	private function GetLastOrder($id){
		$dbdata=$this->mymodel->WriteSql('select d_id,OID,d_create_time from orders where MID='.$id.' order by d_create_time desc','1');
		if(!empty($dbdata)){
			return $dbdata['d_create_time'].'-----<a href="'.CCODE::DemoPrefix.'/'.$this->autoful->FileName.'/orders/orders_edit/index/'.$dbdata['d_id'].'" target="_BLANK">'.$dbdata['OID'].'</a>';
		}else{
			return '';
		}
	}
	// 寫入會員生效期限
	private function WriteDeadline($lv,$id,$chked){
		$Mdata=$this->mymodel->OneSearchSql('member','d_account,d_lv,d_upgrade_date',array('d_id'=>$id));
		$Ldata=$this->mymodel->OneSearchSql('member_lv','d_title',array('d_id'=>$lv));
		if($chked==3){
			if($lv==1){
				if($Mdata['d_upgrade_date']=='0000-00-00'){
					$this->Sendmail($Mdata['d_account'],$Ldata['d_title']);
					return date('Y-m-d');
				}
			}
			if($Mdata['d_lv'] > $lv){ // 當前等級提升改變
				// 寄信給會員升級信
				$this->Sendmail($Mdata['d_account'],$Ldata['d_title']);
				return date('Y-m-d');
			}
		}
		return $Mdata['d_upgrade_date'];
	}
	// 寄回覆信
    public function Sendmail($mail,$Ltitle){
        $CTitle=$this->webmodel->BaseConfig('6');
        $Subject=$CTitle['d_title'].'-會員升級通知信';
        $Message ='您好，您的會員等級已調整為'.$Ltitle.'，有問題請洽服務人員';
        $this->tableful->Sendmail($mail, $Subject, $Message);
    }

		// 重發驗證信
		public function ReSendVri(){
			$MID = $this->input->post('Mid',true);
			if ($this->input->is_ajax_request() && !empty($MID)) {
					$Mdata=$this->mymodel->OneSearchSql('member','d_account',array('d_id'=>$MID,'d_chked'=>4));
					if (!empty($Mdata)) {
						$url=site_url('/login/Cheackaccount?').$this->encryptStr('acc='.$Mdata['d_account'].'&type=1','jddtshin');
						$Message ="請點選下面連結已完成驗證:<br><a href='".$url."' target='_blank'>" . $url . "</a><br>謝謝！";
						$this->tableful->Sendmail($Mdata['d_account'], '美麗平台會員-會員驗證信', $Message);
						$_SESSION[CCODE::ADMIN]['ReSendVri'.$MID] = true;
						echo "OK";
						exit();
					}
			}
		}

		// 加密
		private function encryptStr($str, $key){
			$block = mcrypt_get_block_size('des', 'ecb');
			$pad = $block - (strlen($str) % $block);
			$str .= str_repeat(chr($pad), $pad);
			$enc_str = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
			return base64_encode($enc_str);
		}
}
