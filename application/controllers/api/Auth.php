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
						,500);
					exit();
				} else if ($dbdata['d_enable'] == 'N') {
					$this->response(
						[
							'msg'=>'您的帳號停權中，請洽管理員'
						]
						,500);
					exit();
				}
				if ($dbdata['d_chked']==4) {
					$this->response(
						[
							'msg'=>'此帳號尚未驗證，請先到設定的信箱點選驗證網址'
						]
						,500);
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
                $this->response(['msg'=>'no registration'],404);
				/* $this->useful->AlertPage($ErrorUrl, '帳號或密碼錯誤');*/
				exit(); 
			}
			}
			else {
				$this->response(['msg'=>'No post data'],500);
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

	}
	public function user_get(){
		//$user = array ();
		//$user = $this->user->getRows($id);
		//$this->mymodel->OneSearchSql('member','*',array('d_id'=>$id));
		//if(!empty($user)){
			if($_SESSION[CCODE::MEMBER]['IsLogin'] = 'Y'){
				/* $this->db->select('member.d_id,member.d_account,member.d_phone,member.d_pname,member.d_lv,member.d_password,member.d_chked,member.TID,member.TID1,member.d_user_type,member.d_enable,member.d_chked,member_lv.d_title');
			$this->db->from('member');
			$this->db->join('member_lv','member.d_lv=member_lv.d_id','left');
			$this->db->where('m.d_id',$_SESSION[CCODE::MEMBER]['LID']);
			$query=$this->db->get();
			$dbdata=$query->result_array()[0]; */
			//	$user = array ();
			//	$user = $this->user->getRows($_SESSION[CCODE::MEMBER]['LID']);
			$this->response([
				//'data'=>$dbdata,
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
				$this->response(['change failed!'], 400);
				//$this->useful->AlertPage('', '無此會員');
				exit();
			}
			$this->load->library('encryption');
			$this->tableful->Sendmail($post['d_account'], '美麗平台-忘記密碼通知信', '您好，您的密碼是' . $this->encryption->decrypt($dbdata['d_password']));
			$this->useful->AlertPage('index', '已將資料寄到您當初所填的信箱。');
			$this->response(['change success!'], 200);
			exit();
		} else {
			$this->form_validation->set_error_delimiters('', '\n');
			$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
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