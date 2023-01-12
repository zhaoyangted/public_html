<?php
class _edit extends CI_Controller {
	public function __construct(){
		parent::__construct();

        // 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];

        $this->FunctionType='edit';
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
        $this->DBname=$this->tableful->MenuidDb['d_dbname'];
        // 後台基本設定
        $this->autoful->backconfig();
	}

	public function index($d_id){
        if(!empty($d_id)){

            // 特殊欄位處理
            // $this->tableful->TableTreat(0);

            $data['d_id']=$d_id;
            $dbdata=$this->mymodel->OneSearchSql($this->DBname,$this->tableful->SqlList,array('d_id'=>$d_id));
            $data['dbdata']=$dbdata;  

             $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
        // $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_info',$data);
        }else
            $this->useful->AlertPage('','操作錯誤');
    }
    // 編輯
	public function edit(){
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $url=$dbname=$msg='';
        foreach ($this->tableful->Search as $key => $value) {
            if($value[2]=='_CheckFile'){
                if((empty($_POST[''.$key.'_ImgHidden']) and $value[0]==8) or(empty($_POST[''.$key.'_Hidden']) and $value[0]==14))
                    $check->fname[]=array($value[2],$key,$value[1]);
            }else
                $check->fname[]=array($value[2],Comment::SetValue($key),$value[1]);
        }

        $Cck=$check->main('');
        if(!empty($Cck)){
            echo $check->main($url);
            return '';
        }

        $post=(!empty($_POST))?$_POST:'';
		$d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

		$dbdata=$this->useful->DB_Array($post,$d_id);

        $UnsetArray=array('d_id','dbname','BackPageid');
		$dbdata=$this->useful->UnsetArray($dbdata,$UnsetArray);

        /*特殊檢查位置*/
        
        /*特殊檢查位置*/

        $msg=$this->mymodel->UpdateData($dbname,$dbdata,' where d_id='.$d_id.'');

        $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'修改成功');
    }
    // 刪除
    public function deletefile(){
        if($_POST['deltype']=='Y'){
            $dbname=$_POST['dbname'];
            $this->mymodel->DelectData($dbname,' where d_id='.$_POST['d_id'].'');
            $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'刪除成功');
        }else
            $this->useful->AlertPage('','操作錯誤');
    }
   
}