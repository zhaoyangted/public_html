<?php
class Products_type_edit extends CI_Controller {
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
            $this->tableful->TableTreat(1);
						$this->tableful->TableTreat(2);
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

        $this->UploadPic();

        // 各會員分類
        $_POST['MTID']=(!empty($_POST['MTID'])?implode('@#',$_POST['MTID']):'');
				// 各會員等級
				$_POST['d_lv']=(!empty($_POST['d_lv'])?implode('@#',$_POST['d_lv']):'');

        $post=(!empty($_POST))?$_POST:'';
		$d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

		$dbdata=$this->useful->DB_Array($post,$d_id);

        $UnsetArray=array('d_id','dbname','BackPageid','d_img1_ImgHidden','d_img2_ImgHidden','d_img3_ImgHidden','d_img4_ImgHidden','d_img5_ImgHidden');
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

            // 特殊處理
            $d_id=$_POST['d_id'];
            $Tdata=$this->mymodel->OneSearchSql('products_type','TID,TTID,d_title',array('d_id'=>$d_id));
            if(!empty($Tdata['TID'])){
                $TID=$Tdata['TID'];
            }
            if(!empty($Tdata['TTID'])){
                $TTID=$Tdata['TTID'];
            }

            $this->mymodel->DelectData($dbname,' where d_id='.$_POST['d_id'].'');
            if(!empty($TTID)){
                $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname.'_ss/index/'.$TID.'/'.$TTID,'刪除成功');
            }elseif(!empty($TID)){
                $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname.'_s/index/'.$TID.'','刪除成功');
            }else{
                $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'刪除成功');
            }
        }else
            $this->useful->AlertPage('','操作錯誤');
    }
    // 圖片上傳
    private function UploadPic(){
        for ($i=1; $i<6  ; $i++) {
            $Config=array(
                'Fname'=>'d_img'.$i.'',
                'Filename'=>'products_type',
                'r_width'=>'770',
                'r_height'=>'390'
            );
            $this->autoful->DefaultUpload($_FILES,$Config);
        }
    }

}
