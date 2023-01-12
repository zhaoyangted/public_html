<?php
class Member_lv extends CI_Controller {
	public function __construct() {
		parent::__construct();
		// 各專案後臺資料夾
		$AdminName = $this->webmodel->BaseConfig();
		$this->AdminName = $AdminName['d_title'];
		// 自動頁面設定
		$this->tableful->GetAutoPage();
		// 資料庫名稱
		$this->DBname = $this->tableful->MenuidDb['d_dbname'];
		//後台基本設定
		$this->autoful->backconfig();
	}
	public function index() {
		$data = array();
		$dbdata = $this->mymodel->SelectPage($this->DBname, $this->tableful->SqlList, $this->tableful->WhereSql, 'd_id');
		$data['dbdata'] = $dbdata;

		$this->load->view('' . $this->autoful->FileName . '/autopage/_list', $data);
		// $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_list',$data);
	}

}
