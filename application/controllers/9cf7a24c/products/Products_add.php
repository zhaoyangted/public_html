<?php
class Products_add extends CI_Controller {
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
        $this->tableful->TableTreat(0,' and TID=0 or (d_enable="N" and TID=0)');
        $this->tableful->TableTreat(1);
        $this->tableful->TableTreat(2);
        $this->tableful->TableTreat(4);
        $this->tableful->TableTreat(7);
        $this->tableful->TableTreat(24,'','d_model');
        $this->tableful->TableTreat(25,'','d_model');

        // 次分類撈取
        $data['Subtype']=$this->mymodel->SelectSearch('products_type','','d_id,d_title,TID','where TID!=0 and TTID =0','d_sort');
        // 次次分類撈取
        $data['SubSubtype']=$this->mymodel->SelectSearch('products_type','','d_id,d_title,TTID','where TTID!=0','d_sort');

        // $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
        $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_info',$data);
	}

	public function add(){
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $url=$dbname=$msg='';

        $this->tableful->Search['TTID']=array(4,'產品次分類','_Select');
        // $this->tableful->Search['TTTID']=array(4,'產品次次分類','_Select');

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
				/*特殊檢查位置-產品編號不能重複*/
				$this->load->library('form_validation');
				$this->form_validation->set_rules('d_model', '產品編號', 'is_unique[products.d_model]');
				if ($this->form_validation->run()== FALSE) {
					echo "<script>alert('產品編號 已經重複');history.go(-1);</script>";
					return '';
				}
				/*特殊檢查位置-產品編號不能重複*/

        $_POST['TID']=implode('@#',$_POST['TID']);
        $_POST['TTID']=implode('@#',$_POST['TTID']);
        $_POST['TTTID']=(!empty($_POST['TTTID'])?implode('@#',$_POST['TTTID']):'');

        $_POST['MTID']=(!empty($_POST['MTID'])?implode('@#',$_POST['MTID']):'');

        $this->UploadPic();

        $post=(!empty($_POST))?$_POST:'';
        $d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

		$dbdata=$this->useful->DB_Array($post,$d_id);

        $UnsetArray=array('d_id','dbname','BackPageid');
        $dbdata=$this->useful->UnsetArray($dbdata,$UnsetArray);
        /*特殊檢查位置*/
        $dbdata['d_push']=(!empty($dbdata['d_push'])?implode('@#',$dbdata['d_push']):'');
        $dbdata['d_watch']=(!empty($dbdata['d_watch'])?implode('@#',$dbdata['d_watch']):'');
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
        for ($i=1; $i<6  ; $i++) {
            $Config=array(
                'Fname'=>'d_img'.$i.'',
                'Filename'=>'products',
                'r_width'=>'800',
                'r_height'=>'800'
            );
            $this->autoful->DefaultUpload($_FILES,$Config);
        }
    }
}
