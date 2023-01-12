<?php
class Bonus extends CI_Controller {
	public function __construct(){
		parent::__construct();
        // 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];

        $this->FunctionType='edit';
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
        $this->DBname='bonus';
        // 後台基本設定
        $this->autoful->backconfig();
        // 開放欄位
        $this->OpenFiled='15,16,17';
	}

	public function index(){
        $data=array();
        $dbdata=$this->GetData();
        foreach ($dbdata as $key => $value) {
            $dbdata1[$value['d_id']]=$value['d_title'];
        }
        $data['dbdata']=$dbdata1;  
        $this->load->view(''.$this->AdminName.'/bonus/_info',$data);
    }
    // 編輯
	public function edit(){
        $post=(!empty($_POST))?$_POST:'';
        $dbdata=$this->useful->DB_Array($post,'','1');
        $dbdata=$this->useful->UnsetArray($dbdata,array('d_id','dbname'));
        foreach ($dbdata as $key => $value) {
            $Udata=array('d_title'=>$value);
            $this->mymodel->UpdateData('web_config',$Udata,' where d_id='.$key.'');    
        }

        $this->useful->AlertPage($this->AdminName.'/bonus/bonus','修改成功');
    }
    // 撈取資料
    private function GetData(){
        $dbdata=$this->mymodel->SelectSearch('web_config','','d_id,d_title,d_content','where d_id in('.$this->OpenFiled.') and d_enable="Y"','d_sort');
        return $dbdata;
    }
}