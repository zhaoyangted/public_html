<?php
class Products_trial_edit extends CI_Controller {
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
						$this->tableful->TableTreat(2,'','d_model');

						$this->db->query('SET SESSION group_concat_max_len = 1000000');	// 2020/04/27增加查詢長度

						$Pdata=$this->mymodel->Writesql('SELECT GROUP_CONCAT(PID) as PID FROM products_trial where d_id!='.$d_id.' and PID !=""','1');
            $Where=(!empty($Pdata['PID'])?' and d_id not in ('.str_replace('@#',',',$Pdata['PID']).')':'');
            $this->tableful->TableTreat(4,$Where,'d_model');

            $data['d_id']=$d_id;
            $dbdata=$this->mymodel->OneSearchSql($this->DBname,$this->tableful->SqlList,array('d_id'=>$d_id));
            $data['dbdata']=$dbdata;

						// 撈取上層標題
						$Tdata=$this->mymodel->OneSearchSql('products_trial_type','d_title',array('d_id'=>$dbdata['TID']));
						if (empty($Tdata)) {
		            $this->useful->AlertPage('', '操作錯誤');
		            exit();
		        }
						$data['Uptitle']=$Tdata['d_title'];

             // $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
            $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_info',$data);
        }else
            $this->useful->AlertPage('','操作錯誤');
    }
    // 編輯
	public function edit(){
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $url=$dbname=$msg='';

				if($_POST['d_type']==2){
            unset($this->tableful->Search['d_title']);
            unset($this->tableful->Search['d_img']);
        }else{
            unset($this->tableful->Search['GPID']);
        }

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
				/*特殊檢查位置-商品編號不能重複*/
				$chktitle=$this->mymodel->OneSearchSql($this->DBname,'d_model',array('d_model'=>$_POST['d_model'],'d_id!'=>$_POST['d_id']));
				if (!empty($chktitle)) {
					echo "<script>alert('試用品編號 已經重複');history.go(-1);</script>";
					return '';
				}
				/*特殊檢查位置-商品編號不能重複*/

				if($_POST['d_type']==1){
            // 圖片上傳
            $this->UploadPic();
        }

        $post=(!empty($_POST))?$_POST:'';
		$d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

		$dbdata=$this->useful->DB_Array($post,$d_id);

        $UnsetArray=array('d_id','dbname','BackPageid','d_img_ImgHidden');
		$dbdata=$this->useful->UnsetArray($dbdata,$UnsetArray);

        /*特殊檢查位置*/
				$dbdata['PID']=(!empty($dbdata['PID'])?implode('@#',$dbdata['PID']):'');
        /*特殊檢查位置*/

        $msg=$this->mymodel->UpdateData($dbname,$dbdata,' where d_id='.$d_id.'');

        $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname.'/index/'.$dbdata['TID'],'修改成功');
    }
    // 刪除
    public function deletefile(){
        if($_POST['deltype']=='Y'){
            $dbname=$_POST['dbname'];

            // $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'刪除成功');

						// 特殊處理
            $d_id=$_POST['d_id'];
            $Tdata=$this->mymodel->OneSearchSql('products_trial','TID,d_title',array('d_id'=>$d_id));
            if(!empty($Tdata['TID'])){
                $TID=$Tdata['TID'];
            }

						$this->mymodel->DelectData($dbname,' where d_id='.$_POST['d_id'].'');

						$this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname.'/index/'.$TID.'','刪除成功');
        }else
            $this->useful->AlertPage('','操作錯誤');
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
