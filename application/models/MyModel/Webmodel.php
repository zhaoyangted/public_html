<?php
class Webmodel extends CI_Model{
	public $create_id='';
	public function __construct(){
        parent::__construct();

		$this->load->database();

	}
	// 共用函式
    public function GetWebData(){
    	$sql='select * from web_config';
		$WebConfig = $this->db->query($sql)->result_array();
        foreach ($WebConfig as $value) {
            $WebData[$value['d_id']]=$value['d_title'];
        }
        return $WebData;
    }

	//基本設定抓取
	public function BaseConfig($type='1'){
		$sql='select d_title from web_config';
		$sql.=' where d_id="'.$type.'"';
		$query = $this->db->query($sql)->row_array();
		return $query;
	}

	//抓取列表資訊
	public function GetMenu($SID='',$d_id=''){
		if(!empty($SID)){
			$FILED='a.*,am.d_title as amtitle';
			$dbname='auto_page_menu_sub ';
		}
		else{
			$FILED='a.*';
			$dbname='auto_page_menu';
		}

		$sql='select '.$FILED.' from '.$dbname.' a';
		if(!empty($SID))
			$sql.=' inner join auto_page_menu am on am.d_id=a.SID';
		$sql.=' where a.d_enable="Y" ';
		if(!empty($d_id))
			$sql.=' and a.d_id='.$d_id;

		$sql.=' order by a.d_sort ';
		
		if(!empty($d_id))
			$query = $this->db->query($sql)->row_array();
		else
			$query = $this->db->query($sql)->result_array();
		return $query;
	}
	//抓取列表資訊
	public function GetMenuList($SID='' ,$where=''){
		$sql='select * from auto_page_menu_sub';
		$sql.=' where d_enable="Y"';
		$sql.=' and SID='.$SID.$where;
		$sql.=' order by d_sort ';
		$query = $this->db->query($sql)->result_array();
		return $query;
	}
}