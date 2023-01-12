<?php
class Orders_return extends CI_Controller
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
        if (empty($_POST['RID'])) {
            if (!empty($this->tableful->WhereSql)) {
                $this->tableful->WhereSql .= ' and RID!=""';
            } else {
                $this->tableful->WhereSql .= ' where RID!=""';
            }
        }

        $dbdata = $this->mymodel->SelectPage($this->DBname, $this->tableful->SqlList, $this->tableful->WhereSql, 'd_return_time desc');

        $data['dbdata'] = $dbdata;

        // $this->load->view(''.$this->autoful->FileName.'/autopage/_list',$data);
        $this->load->view('' . $this->AdminName . '/' . $this->DBname . '_return/_list', $data);
    }
    // 下載訂單
    public function DownOrder()
    {
        // if ($_POST['OID']=="" && $_POST['RID']=="") {
        //     $this->useful->AlertPage('', '必須輸入搜尋條件才可進行匯出！');
        //     exit();
        // } else {
        $where = ' and RID!=""';

        if (!empty($_POST['allid'])) {
            $where = ' and o.d_id in (' . implode(',', $_POST['allid']) . ')';
        } else {
            foreach ($_POST as $k => $p) {
                if (!empty($p)) {
                    switch ($k) {
                        case 's_d_return_time':
                            $where .= ' and o.d_return_time >="' . $p . '"';
                            break;
                        case 'e_d_return_time':
                            $where .= ' and o.d_return_time <="' . date('Ymd', strtotime("$p +1 day")) . '"';
                            break;
                        case 'OID':
                        case 'RID':
                            $where .= ' and o.' . $k . ' like "%' . $p . '%"';
                            break;
                    }
                }
            }
        }
        // }

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
									order by o.d_return_time desc
								');

        $this->load->library('Mylib/OrderExportful');

        $data_array = $this->orderexportful->GetOrderExport($odata);
        $Rdata_array = $this->orderexportful->GetOrderExport($odata, true, array_column($data_array, 27));

        $filename = date('ymdhis') . '銷退單';

        $excelTemplate = 'uploads/ExcelSimple/OrderReturnSimple.xlsx';

        $this->orderexportful->DownExcel($Rdata_array, $filename, 'csv', $excelTemplate,true);
    }

}
