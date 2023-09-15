<?php
class Products_edit extends CI_Controller {
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
            // print_r($this->tableful->Menu);
            // 特殊欄位處理
            $this->tableful->TableTreat(0,' and TID=0 or (d_enable="N" and TID=0)');
            $this->tableful->TableTreat(1);
            $this->tableful->TableTreat(2);
            $this->tableful->TableTreat(4);
            $this->tableful->TableTreat(7);
            $this->tableful->TableTreat(24,'and d_id!='.$d_id.'','d_model');
            $this->tableful->TableTreat(25,'and d_id!='.$d_id.'','d_model');

            // 次分類撈取
            $data['Subtype']=$this->mymodel->SelectSearch('products_type','','d_id,d_title,TID','where TID!=0 and TTID =0','d_sort');
            // 次次分類撈取
            $data['SubSubtype']=$this->mymodel->SelectSearch('products_type','','d_id,d_title,TTID','where TTID!=0','d_sort');

            $data['d_id']=$d_id;
            $dbdata=$this->mymodel->OneSearchSql($this->DBname,$this->tableful->SqlList.',TTID,TTTID',array('d_id'=>$d_id));

            $data['dbdata']=$dbdata;

            // 產品QR code
            $this->GetQrcode($d_id,$dbdata['d_model']);

            // print_r($Adata);

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

        // $this->tableful->Search['TTID']=array(4,'產品次分類','_Select');
        // $this->tableful->Search['TTTID']=array(4,'產品次次分類','_Select');

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
				/*特殊檢查位置-產品編號不能重複*/
				$check_model=$this->mymodel->OneSearchSql($this->DBname,'d_model',array('d_model'=>$_POST['d_model'],'d_id!'=>$_POST['d_id']));
				if (!empty($check_model)) {
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

        $UnsetArray=array('d_id','dbname','BackPageid','d_img1_ImgHidden','d_img2_ImgHidden','d_img3_ImgHidden','d_img4_ImgHidden','d_img5_ImgHidden');
		$dbdata=$this->useful->UnsetArray($dbdata,$UnsetArray);

        /*特殊檢查位置*/
        $dbdata['d_push']=(!empty($dbdata['d_push'])?implode('@#',$dbdata['d_push']):'');
        $dbdata['d_watch']=(!empty($dbdata['d_watch'])?implode('@#',$dbdata['d_watch']):'');
        /*特殊檢查位置*/

        $msg=$this->mymodel->UpdateData($dbname,$dbdata,' where d_id='.$d_id.'');

        $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'修改成功');
    }
    // 刪除
    public function deletefile(){
        if($_POST['deltype']=='Y'){
            $dbname=$_POST['dbname'];
            $dbdata=$this->mymodel->OneSearchSql('products','d_model',array('d_id'=>$_POST['d_id']));
            // 刪除Qrcode 圖片
						if (file_exists('./uploads/qrcode/'.$dbdata['d_model'].'.png')) {
							unlink('./uploads/qrcode/'.$dbdata['d_model'].'.png');
						}
            $this->mymodel->DelectData($dbname,' where d_id='.$_POST['d_id'].'');
            $this->useful->AlertPage($this->AdminName.'/'.$dbname.'/'.$dbname,'刪除成功');
        }else
            $this->useful->AlertPage('','操作錯誤');
    }
    // 撈取分類
    public function GetProductType(){
        $Type=(!empty($_POST['Edit'])?$_POST['Edit']:'');
        $TID=(!empty($_POST['TID'])?$_POST['TID']:'');
        $TTID=(!empty($_POST['TTID'])?$_POST['TTID']:'');
        $TTTID=(!empty($_POST['TTTID'])?$_POST['TTTID']:'');

        if(!empty($TID)){
            if(!empty($Type))
                $Tdata=$this->mymodel->SelectSearch('products_type','','d_id,d_title','where TID in ('.implode(',',explode('@#',$TID)).') and TTID is null','d_sort');
            else
                $Tdata=$this->mymodel->SelectSearch('products_type','','d_id,d_title','where TID in ('.implode(',',$TID).') and TTID is null','d_sort');
        }
        if(!empty($TTID)){
            if(!empty($Type))
                $Tdata=$this->mymodel->SelectSearch('products_type','','d_id,d_title','where TTID in ('.implode(',',explode('@#',$TTID)).') ','d_sort');
            else
                $Tdata=$this->mymodel->SelectSearch('products_type','','d_id,d_title','where TTID in ('.implode(',',$TTID).') ','d_sort');

        }
        echo json_encode($Tdata);
    }
    // 圖片上傳
    private function UploadPic(){
        for ($i=1; $i<6  ; $i++) {
            $Config=array(
                'Fname'=>'d_img'.$i.'',
                'Filename'=>'products',
                'r_width'=>'800',
                'r_height'=>'800',
                'Nodel'=>'no'
            );
            //$this->autoful->DefaultUpload($_FILES,$Config);
            $this->autoful->addImages($_FILES,$Config);
        }
    }
    // Qr code 產生及下載
    private function GetQrcode($d_id='1',$model='Xladsk1'){
        $this->autoful->qrcode_produce('./uploads/qrcode/',site_url('products/info/'.$d_id.''),$model);
    }
    // 下載圖片
    public function DownQrcode($d_id){
        $dbdata=$this->mymodel->OneSearchSql('products','d_model',array('d_id'=>$d_id));
        $this->useful->DownloadFile($dbdata['d_model'].'.png',$dbdata['d_model'].'.png','qrcode');
    }
    // 複製商品
    public function CopyProducts(){
        if(empty($_POST['d_id'])){
            $this->useful->AlertPage('','請勾選複製商品');
            exit();
        }
        $id=$_POST['d_id'];
        $Pdata=$this->mymodel->OneSearchSql('products','*',array('d_id'=>$id));
				$Pdata['d_enable']='N';
				$Pdata['d_model']='';
        // print_r($Pdata);
        $PID=$Pdata['d_id'];
        unset($Pdata['d_id']);
        for ($i=1; $i <6 ; $i++) {
            if(!empty($Pdata['d_img'.$i])){
                $img=$Pdata['d_img'.$i];
                $imgdata=explode('/',$img);
                $Pic=explode('.',$imgdata[2]);
                $imgdata[2]=$Pic[0].'_copy'.rand(0,99999).'.'.$Pic[1];
                $Picpath=implode('/',$imgdata);
                // print_r($Picpath);
                $Pdata['d_img'.$i]=$Picpath;
                copy($img,$Picpath);
            }
        }
        $dbdata=$this->useful->DB_Array($Pdata,'','','1');
        $this->mymodel->InsertData('products',$dbdata);
				$NewPid=$this->mymodel->create_id;
        // $NewPid=35;
        if(!empty($NewPid)){
            $Statusaray=array('status'=>'OK','Newid'=>$NewPid);
            echo json_encode($Statusaray);
            exit();
        }
    }
}
