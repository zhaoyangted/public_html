<?php
class Products_trial_add extends CI_Controller {
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

	public function index($TID=''){
			if(empty($TID)){
					$this->useful->AlertPage('','操作錯誤');
					exit();
			}

        $data=array();
				
				// 撈取上層標題
				$Tdata=$this->mymodel->OneSearchSql('products_trial_type','d_title',array('d_id'=>$TID));
				if (empty($Tdata)) {
					$this->useful->AlertPage('','操作錯誤');
					exit();
				}
				$data['Uptitle']=$Tdata['d_title'];
				$data['dbdata']['TID']=$TID;

        // 特殊欄位處理
				$this->tableful->TableTreat(2,'','d_model');

				$this->db->query('SET SESSION group_concat_max_len = 1000000');	// 2020/04/27增加查詢長度

				$Pdata=$this->mymodel->Writesql('SELECT GROUP_CONCAT(PID) as PID FROM products_trial where PID !=""','1');
        $Where=(!empty($Pdata['PID'])?' and d_id not in ('.str_replace('@#',',',$Pdata['PID']).')':'');
        $this->tableful->TableTreat(4,$Where,'d_model');

        // $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
        $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_info',$data);
	}

	public function add(){
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $url=$dbname=$msg='';

				if($_POST['d_type']==2){
            unset($this->tableful->Search['d_title']);
            unset($this->tableful->Search['d_img']);
        }else{
            unset($this->tableful->Search['PID']);
        }

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

				/*特殊檢查位置-試用品編號不能重複*/
				$chktitle=$this->mymodel->OneSearchSql($this->DBname,'d_model',array('d_model'=>$_POST['d_model'],'d_id!'=>$_POST['d_id']));
				if (!empty($chktitle)) {
					echo "<script>alert('試用品編號 已經重複');history.go(-1);</script>";
					return '';
				}
				/*特殊檢查位置-試用品編號不能重複*/

				if($_POST['d_type']==1){
            // 圖片上傳
            $this->UploadPic();
        }

        $post=(!empty($_POST))?$_POST:'';
        $d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

		$dbdata=$this->useful->DB_Array($post,$d_id);

        $UnsetArray=array('d_id','dbname','BackPageid');
        $dbdata=$this->useful->UnsetArray($dbdata,$UnsetArray);
        /*特殊檢查位置*/
				$dbdata['PID']=(!empty($dbdata['PID'])?implode('@#',$dbdata['PID']):'');
        /*特殊檢查位置*/
        $msg=$this->mymodel->InsertData($dbname,$dbdata);

        if(!empty($msg)){
            $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname.'/index/'.$dbdata['TID'],'新增完成');
        }else{
            $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname.'/index/'.$dbdata['TID'],'新增失敗');
        }
    }
		// 圖片上傳
    private function UploadPic(){
        $Config=array(
            'Fname'=>'d_img',
            'Filename'=>'trial',
            'r_width'=>'800',
            'r_height'=>'800',
            'Nodel'=>'no'
        );
        $this->autoful->DefaultUpload($_FILES,$Config);
    }
}
