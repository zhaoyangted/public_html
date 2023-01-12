<?php
class Netdata extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        // 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];
        // 檔案名
        $this->EditFunction='netdata';
        // 開放欄位
        $this->OpenFiled='2,3,5,6,7,8,9,10,11,12,13,14,18';
    }

    public function index(){
        $this->tableful->GetAutoPage();
        
        $this->autoful->backconfig();
        $this->Auto_page['AutoPage']=$this->CreateAuto();
        $data['FunctionType']='edit';
        // print_r($this->Auto_page['AutoPage']);
        $dbdata=$this->GetData();
        foreach ($dbdata as $key => $value) {
            $dbdata1[$value['d_id']]=$value['d_title'];
        }
        $data['dbdata']=$dbdata1;
        $this->load->view($this->autoful->FileName.'/autopage/netdata',$data);

    }
    // 編輯
    public function edit(){

        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $url=$dbname=$msg='';

        $Auto_page=$this->CreateAuto();
        foreach ($Auto_page as $akey => $avalue){
            if(!empty($avalue['d_search']) and $avalue['d_search']!='Y'){
                $Cdata['web_config']['Chk'][$avalue['d_fname']]=array(
                    $avalue['d_search'],$avalue['d_title'],$avalue['d_type']
                );
            }

        }

        $d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';

        if(!empty($Cdata[$dbname]['Chk'])){
            foreach ($Cdata[$dbname]['Chk'] as $Ckey => $Cvalue) {
                if(!in_array($Cvalue[2],array('8','14')))
                    $check->fname[]=array($Cvalue[0],Comment::SetValue($Ckey),$Cvalue[1]);
                else{
                     if((empty($_POST[''.$Ckey.'_ImgHidden']) and $Cvalue[2]==8) or(empty($_POST[''.$Ckey.'_Hidden']) and $Cvalue[2]==14))
                        $check->fname[]=array($Cvalue[0],$Ckey,$Cvalue[1]);
                }
            }
        }
        $Cck=$check->main();
        if(!empty($Cck)){
            echo $check->main($url);
            return '';
        }

        /*特殊檢查位置*/

        /*特殊檢查位置*/

        if(!empty($Cdata[$dbname]['NoVal'])){
            $_POST=$this->useful->UnsetArray($_POST,$Cdata[$dbname]['NoVal']);
        }

        /*特殊檢查位置*/
        $this->UploadPic();
        /*特殊檢查位置*/

        $post=(!empty($_POST))?$_POST:'';
        $d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';


        $dbdata=$this->useful->DB_Array($post,$d_id,'1');


        $dbdata=$this->useful->UnsetArray($dbdata,array('d_id','dbname','3_ImgHidden','4_ImgHidden'));

        foreach ($dbdata as $key => $value) {
            $Udata=array('d_title'=>$value);
            $this->mymodel->UpdateData('web_config',$Udata,' where d_id='.$key.'');
        }

        $this->useful->AlertPage($this->AdminName.'/netdata/netdata','修改完成');
    }
    private function CreateAuto(){
        $dbdata=$this->GetData();
        // print_r($dbdata);
        foreach ($dbdata as $key => $value) {
            $d_type=in_array($value['d_id'],array(7,8,9))?5:1;
            if(in_array($value['d_id'],array(3,4)))
                $d_type=8;
            $d_search=in_array($value['d_id'],array(2))?'_String':'';
            $d_content='';
            if($value['d_id']==3)
                $d_content='(建議尺寸:寬269px X 高 178px)';
            if($value['d_id']==6)
                $d_content='最多六個字';

            $AutoPage[$key]=array(
                'd_fname'=>$value['d_id'],
                'd_title'=>$value['d_content'],
                'd_type'=>$d_type,
                'd_search'=>$d_search,
                'd_content'=>$d_content
            );
        }

        return $AutoPage;
    }
    private function GetData(){
        $dbdata=$this->mymodel->SelectSearch('web_config','','d_id,d_title,d_content','where d_id in('.$this->OpenFiled.') and d_enable="Y"','d_sort');
        return $dbdata;
    }
    // 圖片上傳
    private function UploadPic(){
        $Picarray=array(3);
        // Fname=>欄位名稱
        // Filename=>檔案
        // tmp=>是否有縮圖
        // r_width=>圖片比例寬度 (可空值為原圖)
        // r_height=>圖片比例高度 (可空值為原圖)
        // Souce=> 空值為原圖
        foreach ($Picarray as $value) {
            $width=($value==3)?'269':'314';
            $height=($value==3)?'178':'61';

            $Config=array(
                'Fname'=>$value,
                'Filename'=>'admin',
                'r_width'=>$width,
                'r_height'=>$height
            );
            $this->autoful->DefaultUpload($_FILES,$Config);
        }
    }
}
