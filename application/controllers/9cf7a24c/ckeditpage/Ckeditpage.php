<?php //文字編輯器專用
class Ckeditpage extends CI_Controller{
	public function __construct(){
		parent::__construct();
        // 各專案後臺資料夾
        $AdminName=$this->webmodel->BaseConfig();
        $this->AdminName=$AdminName['d_title'];
        $this->FunctionType='edit';
        // ID
        $this->DBid=(!empty($_POST['Menuid'])?$_POST['Menuid']:$_SESSION[CCODE::ADMIN]['Muid']);
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType,$this->DBid);
        
        // 撈取欄位
        $this->FiledList=array('d_id'=>'','d_content'=>'內容');
        // 撈取檢查欄位
        $this->CheckFiled=array('d_content'=>'_String');
	}

	public function index(){
		$this->autoful->backconfig();
        $AutoData=$this->mymodel->OneSearchSql('ckeditpage','d_id,d_content,d_text',array('MID'=>$this->DBid));
        $this->Auto_page['Title']['d_dbname']='ckeditpage';
        $data['d_id']=$AutoData['d_id'];
		$dbdata['d_content']=$AutoData['d_content'];
        $dbdata['d_text']=$AutoData['d_text'];
        

        $_SESSION['ckeditor_url']=CCODE::DemoPrefix.'/uploads/ckeditpage';

        $_SESSION[CCODE::ADMIN]['Muid']=$this->DBid;
		$data['dbdata']=$dbdata;
        $this->load->view($this->AdminName.'/ckeditpage/_info',$data);
	}

	// 編輯
	public function edit(){
        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel','webmodel');
        $check=new CheckInput;
        $msg='';
        $dbname='ckeditpage';
        $url='';
        
        //參數設定
        /*
            $Cdata[$DB]=>填入需抓取的資料庫名
            Chk=>建立至陣列裡，Key為欄位名稱，陣列內0為判斷選項，1為錯誤顯示字元
            NoVal=>去除非必要欄位
            DBUrl=>需要特別轉跳頁面設定此值
            Msg=>需要特別轉跳字元設定此值
        */
        if(empty($_POST['d_content'])){
            echo "<script>alert('請輸入內文');history.go(-1);</script>";
            return '';
        }

        $post=(!empty($_POST))?$_POST:'';
		$d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';


		$dbdata=$this->useful->DB_Array($post,$d_id);

		$dbdata=$this->useful->UnsetArray($dbdata,array('d_id','dbname'));
        // print_r($post);
        // exit();
        $dbdata['d_content']=$_POST['d_content'];
        $msg=$this->mymodel->UpdateData($dbname,$dbdata,' where d_id='.$d_id.'');
        
        $this->useful->AlertPage($this->AdminName.'/ckeditpage/ckeditpage','修改成功');
    }
}