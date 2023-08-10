<?php
class Mymodel extends CI_Model{
	public $create_id='';
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}
	// 分頁搜尋資料查詢(前台)
	public function FrontSelectPage($dbname,$filed='*',$where='',$order='',$PageNum='20'){
		$backdata=array();
		// 分頁程式 start
		$page=new page();
		$page->SetMySQL($this->db);
		$page->SetPagSize($PageNum);
		$qpage=$page->PageStar($dbname,'',$where);
		$backdata['PageList']=$this->load->view('mypage/page_front',$qpage,true);
        //分頁程式 end

		$backdata['dbdata']= $this->SelectSearch($dbname,$qpage['result'],$filed,$where,$order);
		return $backdata;
	}
	// 分頁搜尋資料查詢(API)
	public function APISelectPage($dbname,$filed='*',$where='',$order='',$pages=1,$limit=12){
		$backdata=array();
		// 分頁程式 start
		$page=new page();
		$page->SetMySQL($this->db);
		$page->SetPagSize($limit);
		$qpage=$page->PageStar($dbname,'',$where);
		$backdata['PageList']=$qpage;
        //分頁程式 end
		$result=" limit ".$pages*$limit.",".$limit;
		//print_r($result);
		$backdata['dbdata']= $this->SelectSearch($dbname,$result/* $qpage['result'] */,$filed,$where,$order);
		return $backdata;
	}
	// 分頁搜尋資料查詢(後台)
	public function SelectPage($dbname,$filed='*',$where='',$order='',$PageNum='10'){
		if(!empty($_SESSION[CCODE::ADMIN]['PageNum_'.$dbname.''])){
			$PageNum=$_SESSION[CCODE::ADMIN]['PageNum_'.$dbname.''];
		}
		$backdata=array();
		// 分頁程式 start
		$page=new page();
		$page->SetMySQL($this->db);
		$page->SetPagSize($PageNum);
		//echo $where;die;
		$qpage=$page->PageStar($dbname,'',$where);

		//print_r($qpage['result']);
		$backdata['PageList']=$this->load->view('mypage/page',$qpage,true);
        //分頁程式 end

		$backdata['dbdata']= $this->SelectSearch($dbname,$qpage['result'],$filed,$where,$order);
		return $backdata;
	}

    //單筆資料查詢
	public function OneSearchSql($table="",$filed="*",$set='',$where_type='and'){
		$sql=" select ".$filed." from ".$table." ";
		if($set!=''){
			$sql.=" where 1=1 ";
			foreach ($set as $key => $value) {
				if($value !== '')
					$sql.=$where_type.' '.$key.'="'.$value.'"';
			}
		}
		$query = $this->db->query($sql);
		return $query->row_array();
	}

  	//搜尋用 套用where_array專用 取代 select_page_form
	public function SelectSearch($table="",$page="",$filed="*",$where='',$order=''){
		$sql=" select ".$filed." from ".$table." ";
		if(!empty($where)){
			$sql.=$where;
		}

		if($order!=''){
			$sql.=' order by '.$order;
		}
		//echo $page;
		$sql.=$page;
		//echo $sql;
		$query = $this->db->query($sql)->result_array();
		//echo $this->db->last_query();print_r($query);die;
		return $query;
	}
	// 抓取文字編輯器
	public function GetCkediter($d_id){
		$dbdata=$this->OneSearchSql('ckeditpage','d_title,d_content',array('d_id'=>$d_id));
		return $dbdata['d_content'];
	}
	//抓取系統資料
	public function GetConfig($Type='', $where=''){
		$where_sql = 'where d_type='.$Type.'';
		if (!empty($where)) {
			$where_sql .= ' '.$where;
		}
		$dbdata=$this->SelectSearch('product_config','','d_title,d_val',$where_sql);
		$Config=array();
		foreach ($dbdata as $value){
			$Config[$value['d_val']]=$value['d_title'];
		}
		return $Config;
	}
	// 抓取地區資料(台灣)
	public function get_area_data($s_city_id='0'){
		$sql="SELECT s_id,s_name FROM city_category where ";
		$sql.=" s_city_id=".$s_city_id;
		$sql.=" order by s_sort";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	//地址編排
	public function GetAddress($city,$area){
		$sql="SELECT REPLACE(GROUP_CONCAT(s_name),',','') as Address  FROM city_category c where s_id in (".$city.",".$area.")";
			return $this->db->query($sql)->row_array();
		}

	public function search_session($search_default_array){//查詢整理
		$data=$this->check_session($search_default_array,"AT");
		if(!empty($_SESSION["AT"]["where"]["ToPage"])){//給跳頁使用
			$_POST["ToPage"]=$_SESSION["AT"]["where"]["ToPage"];
		}
		return $data;
	}
	public function check_session($search_default_array,$ST){//查詢整理
		@session_start();
		if(Comment::SetValue("del_search")=="Y" || Comment::Set_GET("del_search")=="Y"){//每個功能轉換時將查詢資料清空
			$_SESSION[$ST]["where"]=array();
		}
		if(!empty($search_default_array)){
			foreach($search_default_array as $val){
				if(!isset($_SESSION[$ST]["where"][$val])){
					$_SESSION[$ST]["where"][$val]="";
				}
				if(isset($_POST[$val])){
					$_SESSION[$ST]["where"][$val]=Comment::SetValue($val);
				}
			}
			return $_SESSION[$ST]["where"];
		}
	}
	//新增資料
	public function InsertData($table,$indata){
		$sql='INSERT INTO '.$table.'';
		$sql .= " (`".implode("`, `", array_keys($indata))."`)";
		$sql .= " VALUES ('".implode("', '", $indata)."') ";
		$success=$this->db->query($sql);
		$this->create_id=$this->db->insert_id();
		return $success;
	}
	//修改資料
	public function UpdateData($table,$udata,$where){
		$sql='UPDATE '.$table.' SET ';
		foreach ($udata as $key => $value){
			if(isset($value))
				$sql.=$key.'='.$this->db->escape($value).',';
		}

		$sql=substr($sql,0,-1);
		$sql.=$where;
		return $this->db->query($sql);
	}
	//刪除資料
	public function DelectData($table,$where){
		$sql='DELETE FROM '.$table.' ';
		$sql.= $where;
		return $this->db->query($sql);
	}

	//抓取資料庫總數
	public function SqlTotal($table='',$set='',$where_type='and'){
		$type=$where_type;
		$sql=" select * from ".$table." ";
		if($set!=''){
			$sql.=" where 1=1 ";
			foreach ($set as $key => $value) {
				$sql.=$where_type.' '.$key.'="'.$value.'"';
			}
		}

		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	//由C寫入SQL指令
	public function WriteSQL($Sql,$Sign=''){
		$query = $this->db->query($Sql);
		if(!empty($Sign))
			return $query->row_array();
		else
			return $query->result_array();

	}
	//由C寫入SQL指令
	public function SimpleWriteSQL($Sql){
		$query = $this->db->query($Sql);
	}
	// 20190605-分類專用
	public function GetTypeData($dbname,$where='',$filed='d_id,d_title'){
		$dbdata=$this->WriteSQL('select '.$filed.' from '.$dbname.' '.$where);
		foreach ($dbdata as $key => $value) {
			$Tconfig[$value['d_id']]=$value['d_title'];
		}
		return $Tconfig;
	}
}
