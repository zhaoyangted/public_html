<?php
class Dropimg extends CI_Controller {
	public function __construct(){
		parent::__construct();

        // 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];

        // 資料庫名稱
        $this->DBname='products';
        //後台基本設定
        $this->autoful->backconfig();
        // 最多幾張照片
        $this->Piclimit=10;

	}
	public function index($d_id=''){
        if(!empty($d_id)){
            $data=array();
     
            $dbdata =$this->mymodel->OneSearchSql('products','d_img',array('d_id'=>$d_id));
            $dbdata['d_img']=json_decode($dbdata['d_img'],true);
            // 現在數量
            $data['Nowpic']=$Nowpic=count($dbdata['d_img']);
            $data['Chkupload']=($Nowpic>=$this->Piclimit)?'Y':'N';

            $data['d_id']=$d_id;

            $data['dbdata'] = $dbdata;

            $this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_dropimg',$data);
        }else{
            $this->useful->AlertPage('','操作錯誤');
            exit();
        }
	}
    // 上傳圖片
    public function Addimg($d_id=''){
        if(!empty($d_id)){
            $target_dir = "..".CCODE::DemoPrefix."/uploads/".$this->DBname."/"; // Upload directory
            $filedir='uploads/'.$this->DBname.'/';
            $this->useful->create_dir($target_dir);

            $Subname=explode('/',$_FILES["file"]['type']);
            $imgtype=$Subname[1];
            // Upload file
            $FileName=date('YmdHis').rand(0,9999).'.'.$imgtype;
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$FileName)) {
                $Pdata=$this->mymodel->OneSearchSql($this->DBname,'d_img',array('d_id'=>$d_id));
                $filename1=$filedir.$FileName;
                if(!empty($Pdata['d_img'])){
                    $img=json_decode($Pdata['d_img'],true);
                    // 是否已超過限定數量
                    if(count($img)>=$this->Piclimit){
                        exit();
                    }
                    array_push($img,$filename1);
                    $data['d_img']=json_encode($img);
                }else{
                    $data['d_img']=json_encode(array('0'=>$filename1));
                }
                $this->mymodel->UpdateData($this->DBname,$data,'where d_id='.$d_id.'');
            }
        }
    }
    // 刪除圖片
    public function Delimg($d_id=''){
        if(!empty($d_id)){
            $filedir='uploads/'.$this->DBname.'/';
            if(!empty($_POST['name'])){
                $filename = $filedir.$_POST['name'];  
            }else{
                $filename = $_POST['name1'];  
            }

            $Pdata=$this->mymodel->OneSearchSql('products','d_img',array('d_id'=>$d_id));
            $img=json_decode($Pdata['d_img'],true);
            
            if (($key = array_search($filename, $img)) !== false) {
                unset($img[$key]);
            }
            $data['d_img']=json_encode($img);
            $this->mymodel->UpdateData('products',$data,'where d_id='.$d_id.'');
            // Remove file
            unlink($filename); 
            exit;
        }
    }
    // 排序圖片
    public function Sortimg($d_id=''){
        if(!empty($d_id)){
            $imgid=$_POST['imgid'];
            $Pdata=$this->mymodel->OneSearchSql('products','d_img',array('d_id'=>$d_id));
            $NewArray=array();
            $img=json_decode($Pdata['d_img'],true);
            foreach ($imgid as $key => $value) {
                $NewArray[]=$img[$value];
            }
            // print_r($NewArray);
            $data['d_img']=json_encode($NewArray);
            $this->mymodel->UpdateData('products',$data,'where d_id='.$d_id.'');
        }   
    }
}