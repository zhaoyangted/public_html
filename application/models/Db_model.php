<?php if (!defined('BASEPATH')) exit ( 'No direct script access allowed' );
class Db_model extends MY_Model{	

	/*
	 *  權限分類
	 */

	//自動加載
	public function __construct(){
		parent::__construct();
		$this->tabName = '';
	}

	function useDataSheet($dataSheet = ''){
		$this->tabName = $dataSheet;
	}
}