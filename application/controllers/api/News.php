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
class News extends RestController
{

	function __construct()
	{
        /* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
        header("Access-Control-Allow-Credentials: true");
		parent::__construct();
		$this->load->database();
		$this->load->model('MyModel/Webmodel', 'webmodel');
		$this->autoful->FrontConfig();
		// 網頁標題
		$this->NetTitle = '產品介紹';
		//$this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
    public function catList_get($TID = '0')
    {   
        $Pages=$this->get('page');
		$Limit=$this->get('limit');
		$Order=$this->get('order');
        $data = array();
        if (!empty($TID)) {
            $dbdata = $this->mymodel->OneSearchSql('news_type', '*', array('d_id' => $TID, 'd_enable' => "Y"));
            // 各頁面的SEO
            $this->NetTitle = (!empty($dbdata['d_stitle']) ? $dbdata['d_stitle'] : $this->NetTitle);
            $this->Seokeywords = (!empty($dbdata['d_skeywords']) ? $dbdata['d_skeywords'] : '');
            $this->Seodescription = (!empty($dbdata['d_sdescription']) ? $dbdata['d_sdescription'] : '');
            if (empty($dbdata)) {
                $this->useful->AlertPage('', '操作錯誤');
                return '';
            }
        }
        $data['News_type'] = $this->mymodel->SelectSearch('news_type', '', 'd_id,d_title,d_color', 'where d_enable="Y"', 'd_sort');

        // 20191021-多時間判斷
        $Where = ' and d_date<=now() ';

        $data['NewsData'] = !empty($data['News_type']) ? $this->mymodel->APISelectPage('news', '*,SUBSTR(d_date, 1,10) as d_date', 'where d_enable="Y" and TID IN (' . implode(",", array_column($data['News_type'], 'd_id')) . ')' . (!empty($TID) ? ' and TID="' . $TID . '"' : '') . $Where, 'd_sort, d_date desc',$Pages,$Limit) : array();
        $data['TID'] = !empty($dbdata) ? $dbdata['d_title'] : '';
        if ($data) {
            $this->response($data,200);
        } else {
            $this->response(NULL,404);
        }
        //$this->load->view('front/news', $data);
    }
    public function list_get($TID = '0')
    {
        $data = array();
        if (!empty($TID)) {
            $dbdata = $this->mymodel->OneSearchSql('news_type', '*', array('d_id' => $TID, 'd_enable' => "Y"));
            // 各頁面的SEO
            $this->NetTitle = (!empty($dbdata['d_stitle']) ? $dbdata['d_stitle'] : $this->NetTitle);
            $this->Seokeywords = (!empty($dbdata['d_skeywords']) ? $dbdata['d_skeywords'] : '');
            $this->Seodescription = (!empty($dbdata['d_sdescription']) ? $dbdata['d_sdescription'] : '');
            if (empty($dbdata)) {
                $this->useful->AlertPage('', '操作錯誤');
                return '';
            }
        }
        $data['News_type'] = $this->mymodel->SelectSearch('news_type', '', 'd_id,d_title,d_color', 'where d_enable="Y"', 'd_sort');

        // 20191021-多時間判斷
        $Where = ' and d_date<=now() ';

        $data['NewsData'] = !empty($data['News_type']) ? $this->mymodel->FrontSelectPage('news', '*,SUBSTR(d_date, 1,10) as d_date', 'where d_enable="Y" and TID IN (' . implode(",", array_column($data['News_type'], 'd_id')) . ')' . (!empty($TID) ? ' and TID="' . $TID . '"' : '') . $Where, 'd_sort, d_date desc', 6) : array();
        $data['TID'] = !empty($dbdata) ? $dbdata['d_title'] : '';

        $this->response($data,200);
    }
    public function index_get($d_id)
    {
        $data = array();

        // News
        $dbdata = $this->mymodel->OneSearchSql('news', '*,SUBSTR(d_date, 1,10) as d_date', array('d_id' => $d_id, 'd_enable' => "Y"));
        //echo $dbdata;
        $category = !empty($dbdata) ? $this->mymodel->OneSearchSql('news_type', 'd_id,d_title,d_color', array('d_id' => $dbdata['TID'], 'd_enable' => "Y")) : array();
        if (empty($d_id) || empty($dbdata) || empty($category)) {
            $this->useful->AlertPage('', '操作錯誤');
            return '';
        }

        // 各頁面的SEO
        $this->NetTitle = (!empty($dbdata['d_stitle']) ? $dbdata['d_stitle'] : $this->NetTitle);
        $this->Seokeywords = (!empty($dbdata['d_skeywords']) ? $dbdata['d_skeywords'] : '');
        $this->Seodescription = (!empty($dbdata['d_sdescription']) ? $dbdata['d_sdescription'] : '');

        $data['category'] = $category;
        $data['dbdata'] = $dbdata;
        if ($data) {
            $this->response($data,200);
        } else {
            $this->response(NULL,404);
        }

    }
}