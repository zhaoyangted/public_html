<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {


	public function __construct(){
		parent::__construct();
        // 各專案後臺資料夾
        $FileName=$this->webmodel->BaseConfig();
        $this->Filename=$FileName['d_title'];
	}

	// 登入畫面
	public function index(){
		// 尚未有人登入
		if(empty($_SESSION[CCODE::ADMIN]['Aid'])){
			$data['header']=$this->mymodel->WriteSql('select d_title from web_config where d_id in(2,3) and d_enable="Y"');
			$this->load->view($this->Filename.'/login/index',$data);	
		}else{
			$this->useful->AlertPage($this->Filename.'/main');
        }
	}
	// 登入驗證
	public function login_chk(){
		$check=new CheckInput;
        $url=$dbname=$msg='';
        $check->fname[]=array('_String',Comment::SetValue('d_account'),'帳號');
        $check->fname[]=array('_String',Comment::SetValue('d_password'),'密碼');
        if(!empty($_SESSION[CCODE::ADMIN]['VcodeNum']))
        	$check->fname[]=array('_CheckSome',array(Comment::SetValue('vcode'),$_SESSION[CCODE::ADMIN]['VcodeNum']),'驗證碼輸入錯誤');

        $Cck=$check->main();
        if(!empty($Cck)){
            echo $check->main(CCODE::DemoPrefix.'/'.$this->Filename);
            return '';
        }
        $dbdata=$this->mymodel->OneSearchSql('admin_user','d_id,d_account,d_name,d_password,d_enable,JID',array('d_account'=>Comment::SetValue('d_account')));
        
        if(!empty($dbdata)){
        	if($dbdata['d_enable']=='Y'){
				$this->load->library('encryption');//加密
				$d_password = $this->encryption->decrypt($dbdata['d_password']);

				if(Comment::SetValue('d_password')==$d_password){	
					$this->mymodel->UpdateData('admin_user',array('d_last_time'=>$this->useful->get_now_time()),'where d_id ='.$dbdata['d_id'].'');
					$_SESSION[CCODE::ADMIN]['Aid']=$dbdata['d_id'];//管理者ID
					$_SESSION[CCODE::ADMIN]['Aacc']=$dbdata['d_account'];//管理者帳號
					$_SESSION[CCODE::ADMIN]['Jur']=$dbdata['JID'];//管理者權限
                    $_SESSION[CCODE::ADMIN]['Aname']=$dbdata['d_name'];

					unset($_SESSION[CCODE::ADMIN]['VcodeNum']);

					$this->useful->AlertPage($this->Filename.'/main','登入成功');
				}else
	        		$this->useful->AlertPage($this->Filename.'','登入錯誤');
	        }else
	        	$this->useful->AlertPage($this->Filename.'','此帳號已停權，若有疑問請連絡管理人員');
        }else
        	$this->useful->AlertPage($this->Filename.'','登入錯誤');
	}
	//驗證碼網址
    public function make_vcode_img(){ 

        $len=5;
        unset($_SESSION[CCODE::ADMIN]['VcodeNum']);
        $Vcode=$this->useful->random_vcode($len); 
        
        $_SESSION[CCODE::ADMIN]['VcodeNum']=implode('',$Vcode);

        Header("Content-type: image/PNG");
        $im = imagecreate($len*11,18);
        $back = ImageColorAllocate($im, 245,245,245);
        imagefill($im,0,0,$back); //背景

        for($i=0;$i<$len;$i++){
            $font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255));
            imagestring($im, 5, 2+$i*10, 1, $Vcode[$i], $font);
        }
        ImagePNG($im);
        ImageDestroy($im);
    } 
    
	// 登出頁面
	public function logout($Backurl=''){
        $data['Backurl']=(!empty($Backurl))?$Backurl:$this->Filename;
		$this->load->view($this->Filename.'/login/logout',$data);
		return '';
	}
    // 登出
    public function logout1($Backurl=''){
        unset($_SESSION[CCODE::ADMIN]);
        $Backurl=(!empty($Backurl))?$Backurl:$this->Filename;
        $this->useful->AlertPage($Backurl.'','');
        return '';
    }
    // AJAX驗證是否還有Session
    public function GetSession(){
        if(empty($_SESSION[CCODE::ADMIN]['Aid'])){
            echo 'No';
        }else{
            $_SESSION[CCODE::ADMIN]['Aid']=$_SESSION[CCODE::ADMIN]['Aid'];//管理者ID
            $_SESSION[CCODE::ADMIN]['Aacc']=$_SESSION[CCODE::ADMIN]['Aacc'];//管理者帳號
            $_SESSION[CCODE::ADMIN]['Jur']=$_SESSION[CCODE::ADMIN]['Jur'];//管理者權限
        }
    }
}