<?php
class Products_type_s extends CI_Controller {
	public function __construct(){
		parent::__construct();
		// 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];
       
        // 自動頁面設定
        $this->tableful->GetAutoPage();
        // 資料庫名稱
        $this->DBname=$this->tableful->MenuidDb['d_dbname'];
        //後台基本設定
		$this->autoful->backconfig();
	}
	public function index($TID=''){
		if(empty($TID)){
            $this->useful->AlertPage('','操作錯誤');
            exit();
        }
		$data=array();

		// 撈取上層標題
		$Tdata=$this->mymodel->OneSearchSql('products_type','d_title',array('d_id'=>$TID));
		$data['Uptitle']=$Tdata['d_title'];
        $data['TID']=$TID;

        $this->tableful->WhereSql=(!empty($this->tableful->WhereSql)?$this->tableful->WhereSql.'and TID='.$TID.' and TTID =0':'where TID='.$TID.' and TTID =0');

		$dbdata=$this->mymodel->SelectPage($this->DBname,$this->tableful->SqlList,$this->tableful->WhereSql,'d_sort asc,d_create_time desc');
		$data['dbdata']=$dbdata;

		// $this->load->view(''.$this->autoful->FileName.'/autopage/_list',$data);
        $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/s_list',$data);
	}


}
