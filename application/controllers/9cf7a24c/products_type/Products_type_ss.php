<?php
class Products_type_ss extends CI_Controller {
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
	public function index($TID='',$TTID=''){
		if(empty($TID) and empty($TTID)){
            $this->useful->AlertPage('','操作錯誤');
            exit();
        }
		$data=array();

		// 撈取上層標題
		$Tdata=$this->mymodel->WriteSql('select REPLACE(group_concat(d_title),",",">") as d_title from products_type where d_id in ('.$TID.','.$TTID.')','1');
		$data['Uptitle']=$Tdata['d_title'];
        $data['TID']=$TID;
        $data['TTID']=$TTID;

        $this->tableful->WhereSql=(!empty($this->tableful->WhereSql)?$this->tableful->WhereSql.'and TTID='.$TTID.'':'where TTID='.$TTID.'');

		$dbdata=$this->mymodel->SelectPage($this->DBname,$this->tableful->SqlList,$this->tableful->WhereSql,'d_sort asc,d_create_time desc');
		$data['dbdata']=$dbdata;

		// $this->load->view(''.$this->autoful->FileName.'/autopage/_list',$data);
        $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/ss_list',$data);
	}


}
