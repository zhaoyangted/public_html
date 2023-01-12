<?php
class Orders extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        // 各專案後臺資料夾
		$AdminName = $this->webmodel->BaseConfig();
		$this->AdminName = $AdminName['d_title'];
        // 自動頁面設定
		$this->tableful->GetAutoPage();
        // 資料庫名稱
		$this->DBname = $this->tableful->MenuidDb['d_dbname'];
        //後台基本設定
		$this->autoful->backconfig();


	}
	public function index()
	{
		$data = array();
        // 特殊欄位處理
        // $this->tableful->TableTreat(0);
		if (!empty($_POST['MID'])) {
            // group_concat 有長度限制 1024字符，這裡要改寫
            $this->db->query('SET SESSION group_concat_max_len = 100000000');   // 2020/03/31增加查詢長度
            $Mdata = $this->mymodel->Writesql('select group_concat(d_id) as mid from member where d_account like "%' . $_POST['MID'] . '%"', '1');
            $SearStr = '';
            if (!empty($Mdata['mid'])) {
            	$this->tableful->WhereSql = str_replace(" MID like '%" . $_POST['MID'] . "%'", ' o.MID in (' . $Mdata['mid'] . ')', $this->tableful->WhereSql);
            }
        }

        if (!empty($_POST['OID'])) {

        	$this->tableful->WhereSql = str_replace("OID", 'o.OID', $this->tableful->WhereSql);
        }

        if (!empty($_POST['s_d_create_time']) || !empty($_POST['e_d_create_time'])) {

        	$this->tableful->WhereSql = str_replace("d_create_time", 'o.d_create_time', $this->tableful->WhereSql);
        }

        //$dbdata = $this->mymodel->SelectPage($this->DBname, $this->tableful->SqlList, $this->tableful->WhereSql, 'd_id desc');
        
        //select d_id,d_enable,OID,MID,d_mcode,d_name,d_create_time,d_orderstatus,d_paystatus from orders order by d_id desc limit 0,10

        $selectStr = 'o.d_id,o.d_enable,o.OID,o.MID,m.d_mcode,o.d_name,o.d_create_time,o.d_orderstatus,o.d_paystatus';
        $dbdata = $this->mymodel->SelectPage($this->DBname, $selectStr, 'o join member m on m.d_id=o.MID '.$this->tableful->WhereSql, 'o.d_id desc');
        // select o.d_id,o.d_enable,o.OID,o.MID,m.d_mcode,o.d_name,o.d_create_time,o.d_orderstatus,o.d_paystatus from orders o join member m on m.d_id=o.MID where o.MID in (809) order by o.d_id desc limit 0,10
        //print_r($dbdata);echo $this->db->last_query();
        
        foreach ($dbdata['dbdata'] as $key => $value) {
            // 會員帳號
        	$MemberData = $this->mymodel->OneSearchSql('member', 'd_account', array('d_id' => $value['MID']));
        	$dbdata['dbdata'][$key]['MID'] = (!empty($MemberData) ? $MemberData['d_account'] : '無此會員');
        }

        $data['dbdata'] = $dbdata;
        //print_r($dbdata);die;
        // $this->load->view(''.$this->autoful->FileName.'/autyopage/_list',$data);
        $this->load->view('' . $this->AdminName . '/' . $this->DBname . '/_list', $data);
    }
    
    // 下載訂單\
    public function DownOrder()
    {
    	$where = '';
    	if (!empty($_POST['allid'])) {
    		$where = ' and o.d_id in (' . implode(',', $_POST['allid']) . ')';
    	} else {
    		//p($_POST);
    		foreach ($_POST as $k => $p) {
    			if ($p != '') {
    				switch ($k) {
    					case 'd_orderstatus':
    					case 'd_paystatus':
    					$where .= ' and o.' . $k . ' =' . $p;
    					break;
    					case 's_d_create_time':
    					$where .= ' and o.d_create_time >="' . $p . '"';
    					break;
    					case 'e_d_create_time':
    					$where .= ' and o.d_create_time <="' . date('Ymd',strtotime($p.'+1 day')) . '"';
    					break;
    					case 'OID':
    					case 'd_name':
    					$where .= ' and o.' . $k . ' like "%' . $p . '%"';
    					break;
    					case 'MID':
    					$where .= ' and m.d_account like "%' . $p . '%"';
    					break;
    					case 'd_mcode':
    					$where .= ' and m.d_mcode like "%' . $p . '%"';
    					break;
    				}
    			}
    		}
    	}
    	if ($where == "") {
    		$this->useful->AlertPage('', '必須輸入搜尋條件才可進行匯出！');
    		exit();
    	}

    	$odata = $this->mymodel->Writesql('
    		select
    		o.*,o.d_id as odid,o.d_bonus as obonus,o.d_create_time as Ocreate_time,o.d_return_total,o.d_freight as onefreight,de.d_code as de_code,
    		pc.d_title as pctitle,sale.d_code as sale_code,cash.d_code as cash_code,m.d_mcode as d_mcode,m.d_pname,m.d_account,m.d_company_tel_area,m.d_company_tel,m.d_phone as d_mphone,
    		GROUP_CONCAT(od.d_title order by od.d_id asc) as odtitle,
    		GROUP_CONCAT(od.d_model order by od.d_id asc) as odmodel,
    		GROUP_CONCAT(od.d_num order by od.d_id asc) as odnum,
    		GROUP_CONCAT(od.d_price order by od.d_id asc) as odprice,
    		GROUP_CONCAT(od.d_pfreight_lv order by od.d_id asc) as FID,
    		GROUP_CONCAT(od.d_pfreight order by od.d_id asc) as d_freight,
    		GROUP_CONCAT(st.d_code) as stcode,
    		GROUP_CONCAT(od.d_total order by od.d_id asc) as odtotal,
    		GROUP_CONCAT(CASE WHEN (d_status = "3" or d_status = "4") THEN CONCAT(od.PID,"@#",od.SAID) ELSE NULL END order by od.d_id asc) as re_pid,
    		GROUP_CONCAT(CONCAT(od.PID,"@#",od.SAID) order by od.d_id asc) as odpid
    		from orders o
    		left join member m on m.d_id=o.MID
    		left join product_config pc on pc.d_val=o.d_paystatus
    		left join salesman sale on sale.d_id=m.SID
    		left join cashflow cash on cash.d_id=o.d_pay
    		left join orders_detail od on od.OID=o.d_id
    		left join products p on p.d_id=od.PID
    		left join products_stock st on p.SID=st.d_id
    		left join department de on o.d_department=de.d_id
    		where pc.d_type=11' . $where . '
    		group by od.OID
    		order by Ocreate_time desc
    		');
    	//echo $this->db->last_query();die;
    	$this->load->library('Mylib/OrderExportful');


    	$data_array = $this->orderexportful->GetOrderExport($odata);

    	$filename = date('ymdhis') . '會員訂單';

    	$excelTemplate = 'uploads/ExcelSimple/OrderSimple.xlsx';

    	$this->orderexportful->DownExcel($data_array, $filename, 'csv', $excelTemplate);
    }

}
