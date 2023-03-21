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
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
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
   
}