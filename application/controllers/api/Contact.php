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
class Contact extends RestController
{
    function __construct()
	{
        /* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
		header("Access-Control-Allow-Credentials: true");
        parent::__construct();
        $this->autoful->FrontConfig();
		// 網頁標題
		$this->NetTitle = '聯絡我們';
		// $this->load->database();
		// $this->load->model('MyModel/Webmodel', 'webmodel');
		// $this->load->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
    public function index_get($id='') {
		$data['Contact_type'] = $this->mymodel->SelectSearch('contact_type', '', 'd_id,d_title', 'where d_enable="Y"', 'd_sort');
		$Pdata=!empty($id)?$this->mymodel->OneSearchSql('products','d_model,d_title',array('d_id'=>$id)):'';
		$data['PID'] = !empty($Pdata)?$Pdata['d_model'].'：'.$Pdata['d_title']:'';
		$this->load->view('front/contact', $data);
	}

	// 聯絡我們寫入
	public function addcontact_post() {
		$this->load->library('form_validation');
		$post = $this->input->post(null, true);
		if ($this->form_validation->run('contact') == true) {
			if ($_SESSION[CCODE::MEMBER]['VcodeNum'] != $post['d_captcha']) {
				$this->useful->AlertPage('', '驗證碼輸入錯誤');
				exit();
			}
			$dbdata = $this->useful->DB_Array($post, '', '', '1');
			$dbdata = $this->useful->UnsetArray($dbdata, array('d_captcha', 'd_type_Hide'));
			$msg = $this->mymodel->InsertData('contact', $dbdata);
			if (!empty($msg)) {
				$this->Sendmail($post);
				//$this->useful->AlertPage('index', '已提交相關人員，我們將盡快回覆您');
                $this->response(['msg'=>'已提交相關人員，我們將盡快回覆您'],200);
			} else {
				//$this->useful->AlertPage('contact', '提交失敗，請重新輸入');
                $this->response(['msg'=>'提交失敗，請重新輸入'],404);
			}
		}
		$this->form_validation->set_error_delimiters('', '\n');
		$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
		exit();
	}

	// 寄回覆信
	private function Sendmail($dbdata='') {
		$CTitle = $this->webmodel->BaseConfig('6');
		$Subject = $CTitle['d_title'] . "-聯絡我們通知信";

		$Message = "
	        公司名稱 : " . $dbdata['d_cname'] . "<br><br>
	        姓名 : " . $dbdata['d_name'] . "<br><br>
	        電話 : " . $dbdata['d_mobile'] . "<br><br>
	        E-mail : " . $dbdata['d_mail'] . "<br><br>
	        地址 : " . $dbdata['d_zipcode'] . $dbdata['d_county'] . $dbdata['d_district'] . $dbdata['d_address'] . "<br><br>
	        詢問類型 : " . $dbdata['d_type_Hide'] . "<br><br>
	        內容 : <br><br>" . $dbdata['d_content'] . "<br>
        ";

		$Address = $this->webmodel->BaseConfig('12');

		$this->tableful->Sendmail($Address, $Subject, $Message);
	}
    
}