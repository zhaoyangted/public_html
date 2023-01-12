<?php
class Products_allspec extends CI_Controller {
	public function __construct(){
		parent::__construct();
		// 各專案後臺資料夾
		$AdminName=$this->webmodel->BaseConfig();
		$this->AdminName=$AdminName['d_title'];
        // 自動頁面設定
		$this->tableful->GetAutoPage();
        // 資料庫名稱
		$this->DBname=$this->tableful->MenuidDb['d_dbname'];
        //後台基本設定
		$this->autoful->backconfig();
	}

	public function index(){
		$data=array();
		// 特殊欄位處理
		//$this->tableful->TableTreat(1, '' , 'd_model');	// 產品編號

		$post = $this->input->post();

		$this->tableful->SqlList .= ', PID';
		$dbdata=$this->mymodel->SelectPage($this->DBname,$this->tableful->SqlList, $this->tableful->WhereSql,'d_sort');
		//p($dbdata);die;
		// 處理產品編號
		$this->load->model('db_model', 'dbx');
		$this->dbx->useDataSheet('products');
		$rs = $this->dbx->rs(array(
			'select'	=> 'd_id, d_model',
			'where'  => array('d_id >' => 0),
		));
		foreach($rs as $k => $v){
			$data['model'][$v['d_id']]= $v['d_model'];
		}

		if( !empty($post) && !empty($post['PID'] )){
			//echo "<BR>$post[PID]<BR>";
			$data_array = array();
			foreach($dbdata['dbdata'] as $v){
					// 拆解字串
				$ar = explode('@#', $v['PID']);
				foreach($ar as $v2){
						//$xStr[] = $data['model'][$v];
						//echo $v2.'-'.$data['model'][$v2].'<BR>';
						//var_dump( strpos($data['model'][$v2], $post['PID']) );
					if( strpos($data['model'][$v2], $post['PID'])!== false ){
						$data_array[] = $v;
						break;
					}
				}
			}

			$dbdata['dbdata'] = $data_array;
			//p($dbdata['dbdata'] );die;
		}

		/**
		 * 分頁有誤，還沒改好
		 */

		$data['dbdata']=$dbdata;

		//$this->load->view(''.$this->autoful->FileName.'/autopage/_list',$data);
		$this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_list',$data);
	}


}