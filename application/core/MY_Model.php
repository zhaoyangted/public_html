<?php if (!defined('BASEPATH')) exit ( 'No direct script access allowed' );
class MY_model extends CI_Model {

	/*
	 * 
	 */

	 // 資料表名稱
	protected $tabName;

    // 欄位名稱前綴
	protected $tabPrefix;

	//自動加載
	public function __construct(){
		parent::__construct();

	}

	public function rs($array) {

		$select			= '';
		$join			= '';
		$where			= '';
		$or_where		= '';
		$and_where_or	= '';
		$where_in 		= '';
		$or_where_in	= '';
		$where_not_in	= '';
		$find_in_set	= '';
		$like			= '';
		$like_or		= '';
		$order			= '';
		$group			= '';
		$having			= '';
		$limit			= '';
		$offset			= '';
		$from			= '';
		/**
		 * 竟然用 $or_like 變數會有問題
		 * 
		 */


		extract($array);
		//p($array);
		if($join){
			foreach($join as $k => $v){
				if(empty($v[2])){
					$this->db->join($v[0], $v[1]);
				}else{
					$this->db->join($v[0], $v[1], $v[2]);
				}
			}
		}

		if($select){
			$this->db->select($select);
		}

		if($where_in){
			foreach($where_in as $k => $v){
				$this->db->where_in($k, $v);
			}	
		}

		if($or_where){
		//	p($or_where);
			foreach($or_where as $k => $v){
				//p($v);
				if(is_array($v))
				{
					foreach ($v as $key => $value) {
						
						$this->db->or_where($key, $value);
					}
				}
				else
				{
					$this->db->or_where($k, $v);
				}
			}	
		}
		
		// 2017-10-12 加入
		//  生成一段 where id=1 and (status='live' OR status='dead') 
		if($and_where_or){
			$this->db->where($and_where_or);
		}

		if($or_where_in){
			foreach($or_where_in as $k => $v){
				$this->db->or_where_in($k, $v);
			}	
		}

		if($where_not_in){

			foreach($where_not_in as $k => $v){
				$this->db->where_not_in($k, $v);
			}	
		}

		if($find_in_set){
			//var_dump( $find_in_set );die;

			foreach($find_in_set as $k => $v){
				
				if( count ($v) > 1){
					foreach($v as $v2){
						$ar[] ="FIND_IN_SET($v2, $k)";
					}

					$str = implode(' OR ', $ar);

					$this->db->where("( $str)");
					
				}else{
					$this->db->where("FIND_IN_SET($v[0], $k)");
				}
				
			}	
		}

		if($like){
			$this->db->like($like);
		}
		if($like_or){
			foreach($like_or as $k => $v){
				//p($v);
				$this->db->or_like(array($k => $v));

				// 不可以用 or_LIKE 因為會無法接 where 條件，造成不該出現的資料
			}	
		}
		if($order){
			if(is_array($order))
			{
				foreach($order as $k => $v){
					$this->db->order_by($k, $v);
				}	
			}
			else
			{
				$this->db->order_by($order);
			}
			
		}
		if($group){


			foreach($group as $v){
				$this->db->group_by($v);
			}
		}

		if($having){
			foreach($having as $k => $v){

				if(!empty($v[1]) && $v[1] =='OR'){
					
					$this->db->or_having($v[0]);
				}else{
					$this->db->having($v[0]);
				}
			}
		}
		
		if($limit){
			//var_dump($limit);
			//echo $limit;
			$this->db->limit( $limit );
		}

		if($offset)
		{
			$this->db->offset($offset);
		}

		if(is_array($where))
		{
			
			if( empty($from) )
			{
				return $this->db->get_where($this->tabName, $where)->result_array();
			}
			else
			{
				// 有使用表 別名
				return $this->db->get_where($from, $where)->result_array();
			}

		}else{
			if( !empty($from) ) {
				if( !empty($exit) && $exit == TRUE)
				{
				// 不是陣列（自定義字串）
					$this->db->where($where, NULL, FALSE);
					return $this->db->get($from)->result_array();
				}
				else
				{
				// 不是陣列（自定義字串）
					$this->db->where($where);
					return $this->db->get($from)->result_array();
				}
			}
			else{
				if( !empty($exit) && $exit == TRUE)
				{
				// 不是陣列（自定義字串）
					$this->db->where($where, NULL, FALSE);
					return $this->db->get($this->tabName)->result_array();
				}
				else
				{
				// 不是陣列（自定義字串）
					$this->db->where($where);
					return $this->db->get($this->tabName)->result_array();
				}
			}
		}


	}

	public function rs1($array) {

		$select			= '';
		$join			= '';
		$where			= '';
		$or_where		= '';
		$or_where_in	= '';
		$like			= '';
		$order			= '';
		$group			= '';
		$limit			= '';

		extract($array);

		if($join){
			foreach($join as $k => $v){
				if(empty($v[2])){

					$this->db->join($v[0], $v[1]);
				}else{
					$this->db->join($v[0], $v[1], $v[2]);
				}
			}
		}

		if($select){
			$this->db->select($select);
		}

		if($like){
			$this->db->like($like);
		}

		if($order){
			foreach($order as $k => $v){
				$this->db->order_by($k, $v);
			}
		}
		if($group){
			foreach($group as $v){
				$this->db->group_by($v);
			}
		}
		if($limit){
			$this->db->limit($limit);
		}

		if(is_array($where)){
			return $this->db->get_where($this->tabName, $where)->row_array();
		}else{
			// 不是陣列（自定義字串）
			$this->db->where($where);
			return $this->db->get($this->tabName)->row_array();
		}


	}

	// 取得 pk號的資料集
	public function getPK($id) {
		return $this->db->get_where($this->tabName, array($this->tabPrefix.'PK' => $id))->row_array();
	}


	//取得 工號值
	public function getR($id) {
		return $this->db->get_where($this->tabName, array($this->tabPrefix.'R' => $id))->row_array();
	}

	public function getPK1($id,$array) {
		$join			= '';
		extract($array);
		if($join){
			foreach($join as $k => $v){
				if(empty($v[2])){

					$this->db->join($v[0], $v[1]);
				}else{
					$this->db->join($v[0], $v[1], $v[2]);
				}
			}
		}
		return $this->db->get_where($this->tabName, array($this->tabPrefix.'PK' => $id))->row_array();
	}

	// 不存在才新增 需搭配 UNIQUE
	public function ins($data, $ignore = false) {

		$insert_query = $this->db->insert_string($this->tabName, $data);

		if ($ignore) $insert_query = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);

		$this->db->query($insert_query);

		$pkid = $this->db->insert_id();

		return $pkid;
	}

	public function ins_batch($data, $ignore = false){
		$CI = &get_instance();
		$sql = '';

		if ($this->tabName && !empty($data)){
			$rows = [];

			foreach ($data as $row) {
				$insert_string = $CI->db->insert_string($this->tabName,$row);
				if(empty($rows) && $sql ==''){
					$sql = substr($insert_string,0,stripos($insert_string,'VALUES'));
				}
				$rows[] = trim(substr($insert_string,stripos($insert_string,'VALUES')+6));
			}

			$sql.=' VALUES '.implode(',',$rows);

			if ($ignore) $sql = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
		}
		//return $sql;
		$this->db->query($sql);
	}

	// 新增
	/*public function ins($data) {

		$this->db->insert($this->tabName, $data);

		$pkid = $this->db->insert_id();

		return $pkid;
	}*/

	//批量新增
	/*public function ins_batch($data) {

		$this->db->insert_batch($this->tabName, $data);
	}*/

	// 新增 不重複才 ins
	public function ins_only($olny, $other='') {
		$sql	= '';
		$str1 	= ''; 
		$str1v	= array();

		// 不重複才寫入
		$sql = 'INSERT INTO '. $this->db->dbprefix.$this->tabName . '(';

		foreach($olny as $k => $v){

			$str1 .= $k.',';
			$str1v[] = $v;

		}

		if($other){

			foreach($other as $k => $v){

				$str1 .= $k.',';
				$str1v[] = $v;
			}
		}
		$str1 = rtrim($str1, ",");


		$sql .= $str1;

		$sql .= ') SELECT * FROM (SELECT ';

		$i =1;

		foreach ($olny as $k => $v){
			$sql .= $v.' AS Z'.$i . ',';
			$i++;
		}

		if($other){

			foreach($other as $k => $v){

				$sql .= $v.' AS Z'.$i . ',';
				$i++;
			}
		}

		$sql = rtrim($sql, ",");

		$sql .= ') AS tmp WHERE NOT EXISTS (SELECT ';

		foreach ($olny as $k => $v){
			$sql .= $k. ',';
		}

		$sql = rtrim($sql, ",");

		$sql .= ' FROM '. $this->db->dbprefix.$this->tabName.' WHERE ';

		foreach ($olny as $k => $v){
			$sql .= $k.' = '.$v.' AND ';
		}

		$sql = rtrim($sql, "AND ");

		$sql .= ') LIMIT 1';

		$this->db->query($sql);
		$pkid = $this->db->insert_id();

		return $pkid;
	}

	// 修改
	public function update($data, $id, $set='', $whereArr='', $format='') {
		/**
		 * 
		 * set = set 时，可以对 update 下 where 条件，但此时 id 就没作用
		 * set 不为空时，为单一更新条件 如 where userid = $id 写作 $id, $set = 'userid',
		 * $format 控制是否要强制字符串
		 */
		if(empty($set)){
			$this->db->update($this->tabName, $data, $this->tabPrefix.'PK = '.$id);
		}elseif($set == 'set'){
			if(is_array($whereArr)){

				$this->db->where($whereArr);

				foreach($data as $k => $v){
					if($format != true)
					{
						$this->db->set($k, $v, FALSE);
					}
					else
					{
						$this->db->set($k, $v);
					}
					
				}
				$this->db->update($this->tabName);

			}else{
				$this->db->where($this->tabPrefix.'PK = '.$id);
				foreach($data as $k => $v){
					if($format != true)
					{
						$this->db->set($k, $v, FALSE);
					}
					else
					{
						$this->db->set($k, $v);
					}
				}
				$this->db->update($this->tabName);
			}
		}elseif($set !='' && $set != 'set'){
			$where = "$set = '$id'";
			$this->db->where($where);
			$this->db->update($this->tabName, $data);
		}
	}

	// 刪除
	public function del($where) {
		$this->db->delete($this->tabName, $where);
	}

	// CSV 用
	public function get($array) {

		$select	= '';
		$join	= '';
		$where	= '';
		$like	= '';
		$order	= '';
		$group	= '';
		$limit	= '';

		extract($array);

		if($join){
			foreach($join as $k => $v){
				if(empty($v[2])){
					$this->db->join($v[0], $v[1]);
				}else{
					$this->db->join($v[0], $v[1], $v[2]);
				}
			}
		}

		if($select){
			$this->db->select($select, FALSE);
		}

		if($like){
			$this->db->like($like);
		}

		if($order){
			foreach($order as $k => $v){
				$this->db->order_by($k, $v);
			}
		}
		if($group){
			$this->db->group_by($group);
		}
		if($limit){
			$this->db->limit($limit);
		}

		if(is_array($where)){
			return $this->db->get_where($this->tabName, $where);
		}else{
			// 不是陣列（自定義字串）
			$this->db->where($where);
			return $this->db->get($this->tabName);
		}


	}

	public function rs_count($array){

		$where	= '';
		$like	= '';
		$join	= '';
		$group	= '';
		$find_in_set = '' ;

		extract($array);

		if($like){
			$this->db->like($like);
		}

		if($join){
			foreach($join as $k => $v){
				if(empty($v[2])){
					$this->db->join($v[0], $v[1]);
				}else{
					$this->db->join($v[0], $v[1], $v[2]);
				}
			}
		}
		if($group){
			$this->db->group_by($group);
		}
		if($find_in_set){
			//var_dump( $find_in_set );die;

			foreach($find_in_set as $k => $v){
				
				if( count ($v) > 1){
					foreach($v as $v2){
						$ar[] ="FIND_IN_SET($v2, $k)";
					}

					$str = implode(' OR ', $ar);

					$this->db->where("( $str)");
					
				}else{
					$this->db->where("FIND_IN_SET($v[0], $k)");
				}
				
			}	
		}		

		$this->db->where($where);
		$this->db->from($this->tabName);
		return $this->db->count_all_results();
	}

	// tree 遞迴
	public function tree($where) {

		// 这个要按 路由大小来排，不然会在刷新子部门路由时，上级路由刷新不正确
		$result = $this->db->order_by($this->tabPrefix."C00", "asc", $this->tabPrefix."O", "asc")->get_where($this->tabName, $where)->result_array();

		return $result;
	}
}