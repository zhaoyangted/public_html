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
class Menus extends RestController
{
    function __construct()
	{
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
        $this->autoful->FrontConfig();
		// $this->load->database();
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
        $data = array();
        $data = $this->autoful->SideMenu;
        //print($data);
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('menu', '操作錯誤');
            exit();
        }
    }
    public function config_get()
    {
        $data = array();
        $data = $this->webmodel->GetWebData();
        //print($data);
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->useful->AlertPage('config', '操作錯誤');
            exit();
        }
    }
}