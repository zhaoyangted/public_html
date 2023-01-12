<?php
class AConfig extends CI_Controller{

	public function __construct(){
		parent::__construct();

	}
    //--AJAX 開啟關閉資料專用
    public function oc_data(){
        $DB=$this->input->post('DB');       //資料表
        $id=$this->input->post('id');       //修改ID 需有分號區隔
        $oc=$this->input->post('oc');       //Open Close Value

        $id_val=explode(';',$id);
        array_pop($id_val);

        foreach ($id_val as $value) {
        	$udata=array('d_enable'=>$oc);
            $this->mymodel->UpdateData($DB,$udata,'where d_id='.$value.'');
        }
        echo '修改完成';
    }
    //--AJAX 開啟關閉資料專用

    //地區切換
	public function selectcity(){
		$cid=$_POST['cid'];
		$dbdata=$this->mymodel->get_area_data($cid);
		echo json_encode($dbdata);
	}
	//地區切換
	// 下載圖片
	public function DownPic($dbname,$d_id,$Filed){
		$dbdata=$this->mymodel->OneSearchSql($dbname,$Filed,array('d_id'=>$d_id));
		$Fname=explode('/',$dbdata[$Filed]);
		$FileName=$File=$Fname[2];
		$Filels=($dbname=='web_config')?'admin':$dbname;
		$this->useful->DownloadFile($File,$FileName,$Filels);
	}
	// 刪除圖片
	public function DelPic(){
		$dbdata=$this->mymodel->OneSearchSql($_POST['DBname'],$_POST['FiledName'],array('d_id'=>$_POST['Did']));
		unlink($dbdata[$_POST['FiledName']]);
		$this->mymodel->UpdateData($_POST['DBname'],array($_POST['FiledName']=>''),' where d_id='.$_POST['Did'].'');
		echo 'OK';
	}
	// 刪除檔案
	public function DelFile(){
		$dbdata=$this->mymodel->OneSearchSql($_POST['DBname'],$_POST['FiledName'],array('d_id'=>$_POST['Did']));
		unlink($dbdata[$_POST['FiledName']]);
		$this->mymodel->UpdateData($_POST['DBname'],array($_POST['FiledName']=>''),' where d_id='.$_POST['Did'].'');
		echo 'OK';
	}
	// 複製資料(單品促銷專案)
	public function DataCopy(){

		$DB=$this->input->post('DB');       //資料表
		$id=$this->input->post('id');       //修改ID 需有分號區隔

		$id_val=explode(';',$id);
		array_pop($id_val);

		foreach ($id_val as $value) {

			$TypeData=$this->mymodel->OneSearchSql($DB,'d_title,d_getbonus,d_sort,d_enable',array('d_id'=>$value));
			$TypeData['d_start'] = date('Y-m-d',strtotime('+1 day'));
			$TypeData['d_end'] = date('Y-m-d',strtotime('+1 day'));
			$TypeData['d_create_time'] = date('ymdhis');
			$TypeData['d_edit_ip'] = $this->useful->get_ip();
			$TypeData['d_enable'] = 'N';
			$this->mymodel->InsertData($DB, $TypeData);
			$TID = $this->mymodel->create_id;

			$SaleData=$this->mymodel->SelectSearch('products_sale','','*','where TID="'.$value.'"','d_sort');
			foreach ($SaleData as $s) {

				$SaleDetail=$this->mymodel->SelectSearch('products_sale_detail','','*','where SID="'.$s['d_id'].'"','');
				unset($s['d_id']);
				$s['TID']=$TID;
				$s['d_create_time'] = date('ymdhis');
				$s['d_edit_ip'] = $this->useful->get_ip();
				$this->mymodel->InsertData('products_sale', $s);
				$SID = $this->mymodel->create_id;

				foreach ($SaleDetail as $d) {
					unset($d['d_id']);
					$d['SID'] = $SID;
					$d['d_num'] = 0;
					$d['d_create_time'] = date('ymdhis');
					$d['d_edit_ip'] = $this->useful->get_ip();
					$this->mymodel->InsertData('products_sale_detail', $d);
				}

			}

		}
		echo '複製完成';

	}
}
