<?php
class Banner_add extends CI_Controller {
	public function __construct(){
		parent::__construct();

        // 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];

        $this->FunctionType='add';
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
        $this->DBname=$this->tableful->MenuidDb['d_dbname'];
        //後台基本設定
        $this->autoful->backconfig();
	}

	public function index(){
        $data=array();
        // 特殊欄位處理
        $this->tableful->TableTreat(2);
        $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
        // $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_info',$data);
	}

	public function add(){
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $url=$dbname=$msg='';

        foreach ($this->tableful->Search as $key => $value) {
            if($value[2]=='_CheckFile'){
                $check->fname[]=array($value[2],$key,$value[1]);
            }else
                $check->fname[]=array($value[2],Comment::SetValue($key),$value[1]);
        }

        $Cck=$check->main();
        if(!empty($Cck)){
            echo $check->main($url);
            return '';
        }

        /*特殊檢查位置*/
        $this->UploadPic();
        /*特殊檢查位置*/

        $post=(!empty($_POST))?$_POST:'';
        $d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

		$dbdata=$this->useful->DB_Array($post,$d_id);

        $UnsetArray=array('d_id','dbname','BackPageid');
        $dbdata=$this->useful->UnsetArray($dbdata,$UnsetArray);
        /*特殊檢查位置*/
				if(!empty($dbdata['d_lv'])){
						$dbdata['d_lv']=implode('@#',$dbdata['d_lv']);
				}else{
						$dbdata['d_lv']='';
				}
        /*特殊檢查位置*/
        $msg=$this->mymodel->InsertData($dbname,$dbdata);

        if(!empty($msg)){
            $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'新增完成');
        }else{
            $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'新增失敗');
        }
    }
    // 圖片上傳
    private function UploadPic(){
        $Config=array(
            'Fname'=>'d_img',
            'Filename'=>'banner',
        );
        $this->autoful->addImages($_FILES,$Config);
        //$this->autoful->DefaultUpload($_FILES,$Config);
    }
}
