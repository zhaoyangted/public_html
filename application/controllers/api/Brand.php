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
class Brand extends RestController
{
    function __construct()
	{
        /* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
		header("Access-Control-Allow-Credentials: true");
        parent::__construct();
        $this->load->model('MyModel/brands');
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
    public function index_get($bid='')
    {
        $data = array();
        if (!empty($bid)){
            $data = $this->brands->getAllBrands($bid);
        } else {
            $data = $this->brands->getAllBrands();
        }
        //print_r($data);
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->response(
				[
					'msg'=>'no found'
				],500
			);
        }
    }
    
}