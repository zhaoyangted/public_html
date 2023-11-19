<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->autoful->FrontConfig();
        // 網頁標題
        $this->NetTitle='關於千冠莉';
	}
    // 列表
    public function Index(){
        $data=array();
        $data['AboutData']=$this->mymodel->GetCkediter('1');
				$data['AboutMap']=$this->mymodel->SelectSearch('about_map','','d_title,d_address,d_tel,d_fax,d_time,d_link','where d_enable="Y"','d_sort');
        $this->load->view('front/about',$data);
    }

}
