<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->autoful->FrontConfig();
		$this->load->library('form_validation');
	}

	// 登入
	public function index() {
		if (!empty($_SESSION[CCODE::MEMBER]['IsLogin'])) {
			$this->useful->AlertPage('member', '');
		}
		$data = array();

		$this->NetTitle = '會員登入';
		$this->load->view('front/login', $data);
	}

	// 登入驗證
	public function login_ok() {
		$post = $_POST;
		// 登入會員檢查
		if ($this->form_validation->run('login') == true) {
			$this->_chk_Captcha($post['d_captcha'],'login');
			$dbdata = $this->mymodel->WriteSQL('select m.d_id,m.d_account,m.d_phone,m.d_pname,m.d_lv,m.d_password,m.d_chked,m.TID,m.TID1,m.d_user_type,m.d_enable,m.d_chked,lv.d_title from member as m left join member_lv as lv on m.d_lv=lv.d_id where m.d_account="' . trim($post['d_account']) . '"', '1');
			// 導回正確連結
			$BackUrl='member';
			$ErrorUrl='login';
			if(!empty($_POST['BackUrl'])){
				$BackUrl='cart/cart_payment';
				$ErrorUrl='cart/cart_login';
			}

			if (!empty($dbdata)) {
				$this->load->library('encryption');
				$password = $this->encryption->decrypt($dbdata['d_password']);

				if ($password != $post['d_password']) {
					$this->useful->AlertPage('login', '帳號或密碼錯誤');
					exit();
				} else if ($dbdata['d_enable'] == 'N') {
					$this->useful->AlertPage('login', '您的帳號停權中，請洽管理員');
					exit();
				}
				if ($dbdata['d_chked']==4) {
					$this->useful->AlertPage('login', '此帳號尚未驗證，請先到設定的信箱點選驗證網址');
					exit();
				}

				unset($_SESSION[CCODE::MEMBER]['VcodeNum']);
				$_SESSION[CCODE::MEMBER]['LName'] = $dbdata['d_pname'];
				$_SESSION[CCODE::MEMBER]['LEmail'] = $dbdata['d_account'];
				$_SESSION[CCODE::MEMBER]['LPhone'] = $dbdata['d_phone'];
				$_SESSION[CCODE::MEMBER]['LID'] = $dbdata['d_id'];
				$_SESSION[CCODE::MEMBER]['Mtype'] = $dbdata['TID'];
				$_SESSION[CCODE::MEMBER]['UserType'] = $dbdata['d_user_type'];
				$_SESSION[CCODE::MEMBER]['Mtype1'] = $dbdata['TID1'];
				$_SESSION[CCODE::MEMBER]['Mlv'] = $dbdata['d_lv'];
				$_SESSION[CCODE::MEMBER]['Mlv_title'] = $dbdata['d_title'];
				$_SESSION[CCODE::MEMBER]['IsLogin'] = 'Y';
				$this->useful->AlertPage($BackUrl, '登入成功');
				exit();

			} else {
				$this->useful->AlertPage($ErrorUrl, '帳號或密碼錯誤');
				exit();
			}
		} else {
			$this->form_validation->set_error_delimiters('', '\n');
			$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
			exit();
		}

	}
	// 登出
	public function logout() {
		unset($_SESSION[CCODE::MEMBER]);
		$this->useful->AlertPage('index', '登出成功');
	}
	// 加入會員
	public function join() {
		if (!empty($_SESSION[CCODE::MEMBER]['IsLogin'])) {
			$this->useful->AlertPage('member', '');
		}
		$data = array(
			'Member_rules' => $this->mymodel->GetCkediter(3),
			'Member_types' => $this->mymodel->SelectSearch('member_type', '', 'd_id,d_title', 'where d_enable="Y"', 'd_sort'),
			'Member_user_types' => $this->mymodel->GetConfig('6', 'and d_enable="Y"'),
			'Member_company_types' => $this->mymodel->GetConfig('4', 'and d_enable="Y"'),
			'Member_operate_types' => $this->mymodel->GetConfig('5', 'and d_enable="Y"'),
			'Member_Sales' => $this->mymodel->SelectSearch('salesman', '', 'd_id,d_title', 'where d_enable="Y"', 'd_sort'),
		);
		if (!empty($_GET['F'])) {
			$dbdata = $this->mymodel->OneSearchSql('member_friend', 'MID', array('d_Fcode' => $_GET['F'], 'd_enable' => "Y"));
			if (!empty($dbdata)) {
				$data['FID'] = $dbdata['MID'];
			}
		}

		$this->NetTitle = '會員註冊';
		$this->load->view('front/join', $data);
	}
	// 註冊寫入
	public function register() {

		$post = $_POST;

		if (empty($post['chkok'])) {
			$this->useful->AlertPage('', '請勾選我已詳細閱讀<會員條款>');
			exit();
		}
		if ($this->form_validation->run('register') == true) {
			$this->_chk_Captcha($post['d_captcha']);
			$post['d_chked'] = 4; // 會員審核
			if ($post['d_user_type'] == 2) {
				$post['d_chked'] = 4; // 會員審核
				if(!empty($post['d_operate_service']))
					$post['d_operate_service'] = (!empty($post['d_operate_service'])?json_encode($post['d_operate_service'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES):''); // 服務項目
				$post['TID1'] = (!empty($post['TID1'])?implode('@#', $post['TID1']):''); // 會員分類
			}

			if (!empty($post['FID'])) {
				$chkFID = $this->mymodel->OneSearchSql('member_friend', 'd_id', array('d_id' => $post['FID'], 'd_enable' => "Y"));
				empty($chkFID)?$post['FID'] = '':'';
			}
			$dbdata = $this->useful->DB_Array($post, '', '', '1');
			//加密
			$this->load->library('encryption');
			$dbdata['d_password'] = $this->encryption->encrypt($post['d_password']);
			$dbdata['d_newsletter'] = (!empty($post['d_newsletter']) ? 'Y' : 'N'); // 電子信
			$dbdata['d_lv'] = 1; // 會員等級

			// 會員代碼
	        $Mdata=$this->mymodel->WriteSql('select substr(d_mcode,-6) as d_mcode from member order by d_id desc limit 0,1','1');

	        if(!empty($Mdata))
	            $Mcode='BG'.substr('000000'.($Mdata['d_mcode']+1),-6);
	        else
	            $Mcode='BG000001';
	        $dbdata['d_mcode']=$Mcode;

			$dbdata = $this->useful->UnsetArray($dbdata, array('d_repassword', 'chkok', 'd_captcha'));

			if (!empty($this->mymodel->InsertData('member', $dbdata))) {
				if (!empty($dbdata['FID'])) {
					$this->mymodel->UpdateData('member_friend', array('d_enable' => "N", 'd_upadte_time' => date('Y-m-d H:i:s')), 'where d_id =' . $dbdata['FID']);
				}
				// 寄驗證信給帳號人員
				$this->SendVri($dbdata['d_account']);
				$this->useful->AlertPage('index', '註冊成功，請至註冊信箱進行驗證');
			} else {
				$this->useful->AlertPage('login/join', '註冊失敗，請重新註冊');
			}
		} else {
			$this->form_validation->set_error_delimiters('', '\n');
			$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
			exit();
		}
	}
	// 忘記帳號
	public function forgetpwd() {
		$data = array();
		if (!empty($_SESSION[CCODE::MEMBER]['IsLogin'])) {
			$this->useful->AlertPage('member', '');
			exit();
		}
		$this->NetTitle = '忘記密碼';
		$this->load->view('front/forgetpwd', $data);
	}

	// 忘記帳密驗證
	public function forget_ok() {
		$post = $_POST;
		// 忘記帳密檢查
		if ($this->form_validation->run('forget') == true) {
			$this->_chk_Captcha($post['d_captcha']);
			$dbdata = $this->mymodel->OneSearchSql('member', 'd_password', array('d_account' => $post['d_account']));
			if (empty($dbdata)) {
				$this->useful->AlertPage('', '無此會員');
				exit();
			}
			$this->load->library('encryption');
			$this->tableful->Sendmail($post['d_account'], '美麗平台-忘記密碼通知信', '您好，您的密碼是' . $this->encryption->decrypt($dbdata['d_password']));
			$this->useful->AlertPage('index', '已將資料寄到您當初所填的信箱。');
			exit();
		} else {
			$this->form_validation->set_error_delimiters('', '\n');
			$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
			exit();
		}

	}

	//檢查驗證碼
	private function _chk_Captcha($code,$url='') {
		if ($_SESSION[CCODE::MEMBER]['VcodeNum'] != $code) {
			$this->useful->AlertPage($url, '驗證碼輸入錯誤');
			exit();
		}
	}

	// 寄驗證信給帳號人員
	public function SendVri($Account=''){
		if(!empty($Account)){
			$url=site_url('/login/Cheackaccount?').$this->encryptStr('acc='.$Account.'&type=1','jddtshin');
			$Message ="請點選下面連結已完成驗證:<br><a href='".$url."' target='_blank'>" . $url . "</a><br>謝謝！";
			$this->tableful->Sendmail($Account, '美麗平台會員-會員驗證信', $Message);
		}
	}
	// 驗證函式
	public function Cheackaccount(){
		$Key=$_SERVER['QUERY_STRING'];
		$data=$this->decryptStr($Key,'jddtshin');
		$edata=explode('&',$data);
		foreach ($edata as $key => $value) {
			$eidata=explode('=',$value);
			$adata[$eidata[0]]=$eidata[1];
		}
		$Mdata=$this->mymodel->OneSearchSql('member','d_id,d_chked,d_user_type',array('d_account'=>$adata['acc']));
		if(!empty($Mdata)){
			if($Mdata['d_chked']==4){
				$Chked=($Mdata['d_user_type']==1)?'1':'2';
				// 首次加入會員成功紅利點數
				$Bonus=$this->webmodel->BaseConfig('17');
				$BonusNum=0;
				if(isset($Bonus)){
					$BonusNum=$Bonus['d_title'];
					$this->GetBonus($BonusNum,$Mdata['d_id']);
				}

				$this->mymodel->UpdateData('member', array('d_chked' => $Chked,'d_bonus'=>$BonusNum), 'where d_id =' . $Mdata['d_id']);
				$this->useful->AlertPage('login', '此帳號已驗證完成，請重新登入');
				exit();
			}else{
				$this->useful->AlertPage('index', '此帳號已驗證過');
				exit();
			}
		}else{
			$this->useful->AlertPage('', '無此會員');
			exit();
		}
	}
	// 新增首次加入會員成功紅利點數
    private function GetBonus($Bonus,$MID){
        // 點數備註
        $Sdata=array(
            'MID'=>$MID,
            'd_type'=>'1',
            'd_num'=>$Bonus,
            'd_total'=>$Bonus,
            'd_content'=>'首次加入會員成功紅利點數',
						'd_create_date' => date('Y-m-d'),
        );

        $dbdata=$this->useful->DB_Array($Sdata,'','','1');
        $this->mymodel->InsertData('member_point',$dbdata);
    }

	//驗證碼網址
	public function make_vcode_img() {
		$len = 5;
		unset($_SESSION[CCODE::MEMBER]['VcodeNum']);
		$Vcode = $this->useful->random_vcode($len);

		$_SESSION[CCODE::MEMBER]['VcodeNum'] = implode('', $Vcode);

		Header("Content-type: image/PNG");
		$im = imagecreate($len * 11, 18);
		$back = ImageColorAllocate($im, 245, 245, 245);
		imagefill($im, 0, 0, $back); //背景

		for ($i = 0; $i < $len; $i++) {
			$font = ImageColorAllocate($im, rand(100, 255), rand(0, 100), rand(100, 255));
			imagestring($im, 5, 2 + $i * 10, 1, $Vcode[$i], $font);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}
	// 加密
	private function encryptStr($str, $key){
		$block = mcrypt_get_block_size('des', 'ecb');
		$pad = $block - (strlen($str) % $block);
		$str .= str_repeat(chr($pad), $pad);
		$enc_str = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
		return base64_encode($enc_str);
	}
	// 解密
	private function decryptStr($str, $key){
		$str = base64_decode($str);
		$str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
		$block = mcrypt_get_block_size('des', 'ecb');
		$pad = ord($str[($len = strlen($str)) - 1]);
		return substr($str, 0, strlen($str) - $pad);
	}

}
