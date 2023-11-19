<?php

defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/RestController.php';
//require_once 'Format.php';

use Restserver\Libraries\RestController;

/**
 * Description of RestGetController
 *
 * @author https://roytuts.com
 */
class Auth extends RestController
{

	function __construct()
	{
        /* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
		header("Access-Control-Allow-Credentials: true");
		parent::__construct();
        $this->autoful->FrontConfig();
        $this->load->library('form_validation');
		$this->load->model('MyModel/user');
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
    public function join_get() {
		if (!empty($_SESSION[CCODE::MEMBER]['IsLogin'])) {
			//$this->useful->AlertPage('member', '');
			$this->response(['msg'=>'請先登出目前用戶。'],404);
			exit();
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
		if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->response(NULL,404);
        }
		//$this->load->view('front/join', $data);
	}
    public function login_post()
    {
        $post = array(
		'd_account'=>$this->post('d_account'),
		'd_password'=>$this->post('d_password'),
		'd_captcha'=>$this->post('d_captcha')
		);
        //print_r($post);
		// 登入會員檢查
/* 		if ($this->form_validation->run('login') == true) {
			$this->_chk_Captcha($post['d_captcha'],'login'); */
		if(!empty($post)){
			$this->_chk_Captcha($post['d_captcha'],'login');
			// $dbdata = $this->mymodel->WriteSQL('select m.d_id,m.d_account,m.d_phone,m.d_pname,m.d_lv,m.d_password,m.d_chked,m.TID,m.TID1,m.d_user_type,m.d_enable,m.d_chked,lv.d_title from member as m left join member_lv as lv on m.d_lv=lv.d_id where m.d_account="' . trim($post['d_account']) . '"', '1');
			//$this->user->getUserByAccount(trim($post['d_account']));
			//print_r($post);
			$this->db->select('member.d_id,member.d_account,member.d_phone,member.d_pname,member.d_lv,member.d_password,member.d_chked,member.TID,member.TID1,member.d_user_type,member.d_enable,member.d_chked,member_lv.d_title');
			$this->db->from('member');
			$this->db->join('member_lv','member.d_lv=member_lv.d_id','left');
			$this->db->where('d_account',trim($post['d_account']));
			$query=$this->db->get();
			$dbdata=$query->result_array()[0];
			// 導回正確連結
			$BackUrl='member';
			$ErrorUrl='login';
			if(!empty($this->post('BackUrl'))){
				$BackUrl='cart/cart_payment';
				$ErrorUrl='cart/cart_login';
			}

			if (!empty($dbdata)) {
				$this->load->library('encryption');
				$password = $this->encryption->decrypt($dbdata['d_password']);

				if ($password != $post['d_password']) {
					$this->response(
						[
							'msg'=>'帳號或密碼錯誤'
						]
						,404);
					exit();
				} else if ($dbdata['d_enable'] == 'N') {
					$this->response(
						[
							'msg'=>'您的帳號停權中，請洽管理員'
						]
						,404);
					exit();
				}
				if ($dbdata['d_chked']==4) {
					$this->response(
						[
							'msg'=>'此帳號尚未驗證，請先到設定的信箱點選驗證網址'
						]
						,404);
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
			   //print_r($_SESSION);
                $this->response(
					[
					'status'=>'Online',
					'msg'=>'Success',
					'data'=>$dbdata,
					'isLoggedIn'=>true,
					'redirectTo'=>$BackUrl
					],
					200);
				/*$_SESSION[CCODE::MEMBER]['IsLogin'] = 'Y';
				$this->useful->AlertPage($BackUrl, '登入成功');
				exit(); */

			} else {
                $this->response(['msg'=>'帳號未註冊！'],404);
				/* $this->useful->AlertPage($ErrorUrl, '帳號或密碼錯誤');*/
				exit(); 
			}
			}
			else {
				$this->response(['msg'=>'沒有此會員'],401);
				exit();
			}
		/* } else {
			$this->form_validation->set_error_delimiters('', '\n');
			$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
			exit();
		}
 */
        /* if ($data) {
            $this->response($data,200);
        } else {
            $this->response(NULL,404);
        } */

    }
    public function registration_post(){
		$post = $this->input->post(null, true);
		print_r($post);
		/* if (empty($post['chkok'])) {
			//$this->useful->AlertPage('', '請勾選我已詳細閱讀<會員條款>');
			$this->response(['msg'=>'請勾選我已詳細閱讀<會員條款>。'],401);
			exit();
		} */
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
				$this->response(['msg'=>'註冊成功，請至註冊信箱進行驗證。'],200);
				$this->useful->AlertPage('index', '註冊成功，請至註冊信箱進行驗證');
			} else {
				$this->response(['msg'=>'註冊失敗，請重新註冊。'],401);
				//$this->useful->AlertPage('login/join', '註冊失敗，請重新註冊');
			}
		} else {
			$this->form_validation->set_error_delimiters('', '\n');
			$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
			exit();
		}
	}
	public function user_get(){
		//$user = array ();
		//$user = $this->user->getRows($id);
		//$this->mymodel->OneSearchSql('member','*',array('d_id'=>$id));
		//if(!empty($user)){
			if(isset($_SESSION[CCODE::MEMBER]['IsLogin']) &&$_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y'){
			//print_r($_SESSION[CCODE::MEMBER]['LID']);
			$this->db->select('member.d_id,member.d_account,member.d_phone,member.d_pname,member.d_lv,member.d_password,member.d_chked,member.d_county,member.d_district,member.d_address,member.d_zipcode,member.TID,member.TID1,member.d_user_type,member.d_enable,member.d_chked,member_lv.d_title');
			$this->db->from('member');
			$this->db->join('member_lv','member.d_lv=member_lv.d_id','left');
			$this->db->where('member.d_id',$_SESSION[CCODE::MEMBER]['LID']);
			$query=$this->db->get();
			$dbdata=$query->result_array()[0];
			//	$user = array ();
			//	$user = $this->user->getRows($_SESSION[CCODE::MEMBER]['LID']);
			$this->response([
				'data'=>$dbdata,
				'status'=>'Success',
				'isLoggedIn'=>true
			],200);
		}else{
			$this->response(
				[
					//'data'=>$user,
					'msg'=>'no online',
					'isLoggedIn'=>false
				],200
			);
		}
	}
	
	public function user_put(){
		
	}
	public function logout_put(){
		// session_start();
		if (isset($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y') {
			unset($_SESSION[CCODE::MEMBER]);
			// remove session datas
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
				// 最后，销毁会话
				}
			// user logout ok
            $this->response(['Logout success!'], 200);
			
		} else {
            $this->response(['Not found.'], 500);	
		}
	}
	// 忘記帳號
	/* public function forgetpwd() {
		$data = array();
		if (!empty($_SESSION[CCODE::MEMBER]['IsLogin'])) {
			$this->useful->AlertPage('member', '');
			exit();
		}
		$this->NetTitle = '忘記密碼';
		$this->load->view('front/forgetpwd', $data);
	} */

	// 忘記帳密驗證
	public function forget_post() {
		$post = $_POST;
		// 忘記帳密檢查
		if ($this->form_validation->run('forget') == true) {
			$this->_chk_Captcha($post['d_captcha']);
			$dbdata = $this->mymodel->OneSearchSql('member', 'd_password', array('d_account' => $post['d_account']));
			if (empty($dbdata)) {
				$this->response(['msg'=>'change failed!'], 404);
				//$this->useful->AlertPage('', '無此會員');
				exit();
			}
			$this->load->library('encryption');
			$this->tableful->Sendmail($post['d_account'], '美麗平台-忘記密碼通知信', '您好，您的密碼是' . $this->encryption->decrypt($dbdata['d_password']));
			$this->useful->AlertPage('index', '已將資料寄到您當初所填的信箱。');
			$this->response(['msg'=>'已將資料寄到您當初所填的信箱。'], 200);
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
			$this->response(['msg'=>'驗證碼輸入錯誤'],404);
			//$this->useful->AlertPage($url, '驗證碼輸入錯誤');
			exit();
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
	// 解密
	private function decryptStr($str, $key){
		$str = base64_decode($str);
		$str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
		$block = mcrypt_get_block_size('des', 'ecb');
		$pad = ord($str[($len = strlen($str)) - 1]);
		return substr($str, 0, strlen($str) - $pad);
	}
}