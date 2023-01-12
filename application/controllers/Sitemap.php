<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->autoful->FrontConfig();
		// 網頁標題
		$this->NetTitle = '網站導覽';
	}
	// 列表
	public function Index() {
		$data = array(
			'qa' => $this->mymodel->SelectSearch('qa', '', 'd_id,d_title', 'where d_hot="Y"', 'd_sort'),
			'products_type' => $this->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where d_enable="Y" and TID is null', 'd_sort'),
		);
		$this->load->view('front/sitemap', $data);
	}

}
