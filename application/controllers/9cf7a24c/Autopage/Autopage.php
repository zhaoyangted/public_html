<?php
class Autopage extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		// 各專案後臺資料夾
        $FileName=$this->webmodel->BaseConfig();
        $this->Filename=$FileName['d_title'];
	}

	public function index($d_id='0'){
		//頭尾、基本設定
		$this->autoful->backconfig();
		$where='';
		if($d_id!=0){
			$dbname='auto_page_menu_sub';
			$where=' where SID='.$d_id.'';
		}else{
			$dbname='auto_page_menu';
		}
		$data=$this->mymodel->SelectPage($dbname,'*',$where,'d_sort');
		if($d_id!=0){
			$this->load->view($this->Filename.'/autopage/config',$data);
		}else{
			$this->load->view($this->Filename.'/autopage/index',$data);
		}
	}
	public function info($d_id=''){
		//頭尾、基本設定
		$this->autoful->backconfig();
		$data=array();

		$data['CssType']=array('fas fa-sitemap','fas fa-bullhorn','fas fa-shopping-cart','fas fa-cog','far fa-address-card','far fa-handshake','fas fa-donate','fas fa-question-circle','far fa-eye','fas fa-user-alt','fas fa-id-card-alt','fas fa-home');

		if(!empty($d_id)){
			$data['dbdata']=$this->mymodel->OneSearchSql('auto_page_menu','*',array('d_id'=>$d_id));
			$data['Subdata']=$this->mymodel->SelectSearch('auto_page_menu_sub','','*',' where SID='.$d_id.'','d_sort');
		}

        $data['max']=$this->mymodel->WriteSQL('SELECT (max(d_id)+1) as d_id FROM auto_page_menu_sub where d_id !="" order by d_id desc limit 1',1);

		$this->load->view($this->Filename.'/autopage/info',$data);
	}
	public function config_info($d_id){
		//頭尾、基本設定
		$this->autoful->backconfig();
		$data=array();

		$data['cdata']=$this->AutoConfig();
		$data['sdata']=$this->AutoConfig('2');
		$data['idata']=$this->AutoConfig('3');

		$dbdata=$this->mymodel->OneSearchSql('auto_page_menu_sub','*',array('d_id'=>$d_id));

		if(!empty($dbdata['d_dbname']))
			$data['fdata']=$this->GetFiled($dbdata['d_dbname']);

		$data['d_menu_id']=$d_id;
		$data['Upid']=$dbdata['SID'];

		$data['dbdata']=$this->mymodel->SelectSearch('auto_page','','*',' where d_menu_id='.$d_id.'','d_list asc ,d_sort asc');

		$this->load->view($this->Filename.'/autopage/config_info',$data);
	}
	//撈取資料表欄位資料
	public function GetFiled($dbname){
		$dbdata=$this->mymodel->WriteSQL('SHOW FULL COLUMNS FROM '.$dbname.'');
		return $dbdata;
	}
	//資料增刪修
    public function data_AED(){

        $d_id=(!empty($_POST['d_id']))?$_POST['d_id']:'';
        $dbname=(!empty($_POST['dbname']))?$_POST['dbname']:'';
        $delSql=(!empty($_POST['delSql']))?$_POST['delSql']:'';
        $delid=(!empty($_POST['delid']))?$_POST['delid']:'';
        if($delSql!='Y'){

            if($dbname=='auto_page_menu'){

            	$data=(!empty($d_id))?$this->useful->DB_Array($_POST,'create'):$this->useful->DB_Array($_POST);

				$bigdata['d_title']=$data['d_title'];
				$bigdata['d_sort']=$data['d_sort'];
				$bigdata['d_icon']=$data['d_icon'];
				$bigdata['d_enable']=(!empty($data['d_enable']))?'Y':'N';


				if($d_id!=''){
					$this->mymodel->UpdateData($dbname,$bigdata,(' where d_id='.$d_id));
					$msg='修改成功';
				}else{
					$this->mymodel->InsertData($dbname,$bigdata);
					$msg=($this->mymodel->create_id)?'新增成功':'新增失敗';
				}

				$SID=!empty($this->mymodel->create_id)?$this->mymodel->create_id:$d_id;
				if(!empty($data['d_menuname'])){

					$dbname='auto_page_menu_sub';

					foreach ($data['d_menuname'] as $key => $value) {
						$iudata=array(
							'SID'=>$SID,
							'd_jur'=>(!empty($data['d_jur'][$key]))?$data['d_jur'][$key]:'',
							'd_title'=>(!empty($data['d_menuname'][$key]))?$data['d_menuname'][$key]:'',
							'd_ctitle'=>(!empty($data['d_listname'][$key]))?$data['d_listname'][$key]:'',
							'd_dbname'=>(!empty($data['d_dbname'][$key]))?$data['d_dbname'][$key]:'',
							'd_sort'=>(!empty($data['d_sort_son'][$key]))?$data['d_sort_son'][$key]:'',
							'd_link'=>(!empty($data['d_link'][$key]))?$data['d_link'][$key]:'',
							'd_oc'=>(!empty($data['d_oc'][$key]))?'Y':'N',
							'd_search'=>(!empty($data['d_search'][$key]))?'Y':'N',
							'd_add'=>(!empty($data['d_add'][$key]))?'Y':'N',
							'd_edit'=>(!empty($data['d_edit'][$key]))?'Y':'N',
							'd_del'=>(!empty($data['d_del'][$key]))?'Y':'N',
							'd_enable'=>(!empty($data['d_enable_son'][$key]))?'Y':'N',
						);

						$chkdata=$this->mymodel->OneSearchSql('auto_page_menu_sub','d_id',array('d_id'=>$key));
						if(!empty($chkdata)){
							$this->mymodel->UpdateData($dbname,$iudata,('where d_id='.$key));
						}else{
							$this->mymodel->InsertData($dbname,$iudata);
						}


					}
					// 將所有權限寫到最高權限者
					$HighJurData=$this->mymodel->WriteSQL('
						select GROUP_CONCAT(d_jur) as jur from auto_page_menu_sub where d_jur!="j_pstype" and d_jur!="j_pssstype"','1');
					$this->mymodel->UpdateData('admin_jur',array('d_jur'=>$HighJurData['jur']),'where d_id=1');
				}
				$this->useful->AlertPage($this->Filename.'/Autopage/Autopage/info/'.$SID.'',$msg);
				return '';
			}
			if($dbname=='auto_page'){
				$data=$this->useful->DB_Array($_POST,'1','1');
				$d_id=!empty($data['sid'])?$data['sid']:'';
				unset($data['sid']);
				unset($data['dbname']);
				if(empty($_POST['d_title'])){
					$this->useful->AlertPage('','操作錯誤');
					exit();
				}
				if(!empty($d_id)){
					$this->mymodel->UpdateData($dbname,$data,' where d_id='.$d_id);
				}else{
					$this->mymodel->InsertData($dbname,$data);
				}
				$msg=($this->mymodel->create_id)?'新增成功':'新增失敗';
				$this->useful->AlertPage('/'.$this->Filename.'/Autopage/Autopage/config_info/'.$data['d_menu_id'].'');
				return '';
			}
        }else
            $where='where d_id='.$delid;

        $this->useful->AlertPage($this->Filename.'/'.$backurl,$msg);
    }

    // 資料刪除
    public function delData($dbname,$delid,$config=''){
    	$where=' where d_id='.$delid;
    	$this->mymodel->DelectData($dbname,$where);
    	if(!empty($config))
    		$this->useful->AlertPage('/'.$this->Filename.'/Autopage/Autopage/config_info/'.$config.'','刪除成功');
    	else
	    	$this->useful->AlertPage('/'.$this->Filename.'/Autopage/Autopage/','刪除失敗');
    }
    //自動網頁設定
	public function AutoConfig($type='1'){
		$sql='select d_id,d_val,d_title,d_content from auto_config';
		$sql.=' where d_type="'.$type.'"';
		$query = $this->db->query($sql)->result_array();
		return $query;
	}
}
