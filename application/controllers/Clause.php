<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clause extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->autoful->FrontConfig();
        // 網頁標題
        $this->NetTitle='隱私權條款';
	}
    // 列表
    public function Index(){
        $data=array();
        $data['ClauseData']=$this->mymodel->GetCkediter('2');
        $this->load->view('front/clause',$data);
    }

}
