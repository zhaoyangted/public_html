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
class Homepage extends RestController
{
    function __construct()
	{
        /* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
		header("Access-Control-Allow-Credentials: true");
        parent::__construct();
        $this->autoful->FrontConfig();
		 $this->load->database();
		// $this->load->model('MyModel/Webmodel', 'webmodel');
		// $this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
    public function index_get()
    {
        $data  = array();
        // 大圖會員權限可看
        $Lv_where = !empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y' ? ' and (d_lv like "' . $_SESSION[CCODE::MEMBER]['Mlv'] . '@#%" or d_lv like "%@#' . $_SESSION[CCODE::MEMBER]['Mlv'] . '" or d_lv like "%@#' . $_SESSION[CCODE::MEMBER]['Mlv'] . '@#%" or d_lv=' . $_SESSION[CCODE::MEMBER]['Mlv'] . ' or d_lv = "" )' : ' and d_lv="" ';
    // Banner
    $BannerData = $this->mymodel->WriteSql('
        SELECT d_img,d_link FROM `banner`
            where if(d_start!="",d_start<=now(),1) and (if(d_end!="",d_end>=now(),1) or d_end="0000-00-00 00:00") and d_enable="Y"' . $Lv_where . '
            ORDER BY d_sort
        ');
    $data['BannerData'] = $BannerData;
    $data['ActionData'] = $this->mymodel->SelectSearch('action_list', '', 'd_title,d_img,d_link', 'where d_enable="Y"', 'd_sort');
    // Action
    
        // print_r($NewsData);
        // 紀錄瀏覽人數
        //$this->AddVisit();
        
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('home', '操作錯誤');
            exit();
        }
    }
    public function about_get(){
        $data=array();
        $data['AboutData']=$this->mymodel->GetCkediter('1');
				$data['AboutMap']=$this->mymodel->SelectSearch('about_map','','d_title,d_address,d_tel,d_fax,d_time,d_link','where d_enable="Y"','d_sort');
        //$this->load->view('front/about',$data);
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('home', '操作錯誤');
            exit();
        }
    }
    public function clause_get(){
        $data=array();
        $data['ClauseData']=$this->mymodel->GetCkediter('2');
        
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('home', '操作錯誤');
            exit();
        }
        //$this->load->view('front/clause',$data);
    }
    public function contact_get($id='') {
		$data['Contact_type'] = $this->mymodel->SelectSearch('contact_type', '', 'd_id,d_title', 'where d_enable="Y"', 'd_sort');
		$Pdata=!empty($id)?$this->mymodel->OneSearchSql('products','d_model,d_title',array('d_id'=>$id)):'';
		$data['PID'] = !empty($Pdata)?$Pdata['d_model'].'：'.$Pdata['d_title']:'';
		if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('home', '操作錯誤');
            exit();
        }
        //$this->load->view('front/contact', $data);
	}

	// 聯絡我們寫入
	public function AddContact_post() {
		$this->load->library('form_validation');
		$post = $this->input->post(null, true);
		if ($this->form_validation->run('contact') == true) {
			if ($_SESSION['contact']['VcodeNum'] != $post['d_captcha']) {
				$this->useful->AlertPage('', '驗證碼輸入錯誤');
				exit();
			}
			$dbdata = $this->useful->DB_Array($post, '', '', '1');
			$dbdata = $this->useful->UnsetArray($dbdata, array('d_captcha', 'd_type_Hide'));
			$msg = $this->mymodel->InsertData('contact', $dbdata);
			if (!empty($msg)) {
				$this->Sendmail($post);
				$this->useful->AlertPage('index', '已提交相關人員，我們將盡快回覆您');
			} else {
				$this->useful->AlertPage('contact', '提交失敗，請重新輸入');
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

	//驗證碼網址
	public function make_vcode_img() {

		$len = 5;
		unset($_SESSION['contact']['VcodeNum']);
		$Vcode = $this->useful->random_vcode($len);

		$_SESSION['contact']['VcodeNum'] = implode('', $Vcode);

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
    public function sitemap_get() {
		$data = array(
			'qa' => $this->mymodel->SelectSearch('qa', '', 'd_id,d_title', 'where d_hot="Y"', 'd_sort'),
			'products_type' => $this->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where d_enable="Y" and TID is null', 'd_sort'),
		);
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('home', '操作錯誤');
            exit();
        }
		//$this->load->view('front/sitemap', $data);
	}
}