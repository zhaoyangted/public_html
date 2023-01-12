<?php
class Orders_return_edit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // 各專案後臺資料夾
        $AdminName = $this->webmodel->BaseConfig();
        $this->AdminName = $AdminName['d_title'];

        $this->FunctionType = 'edit';
        // 自動頁面設定
        $this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
        $this->DBname = $this->tableful->MenuidDb['d_dbname'];
        // 後台基本設定
        $this->autoful->backconfig();
    }

    public function index($d_id)
    {

        if (!empty($d_id)) {
            // 特殊欄位處理
            // $this->tableful->TableTreat(10);

            $data['d_id'] = $d_id;
            $dbdata = $this->mymodel->OneSearchSql($this->DBname, '*', array('d_id' => $d_id));

            // 訂單細項
            $oddata = $this->mymodel->WriteSql('select * from orders_detail where OID=' . $dbdata['d_id'] . ' and d_status < 5 and d_status > 2');

            $data['oddata'] = $oddata;

            // 細項狀態
            $data['Ostatus'] = $this->mymodel->GetConfig('12');
            // 細項物流商
            $Oship_company = $this->mymodel->SelectSearch('ship', '', 'd_id,d_title', 'where d_enable="Y"');
            $data['Oship_company']  = array_column($Oship_company, 'd_title', 'd_id');
            // 細項運費
            $Freight = $this->mymodel->SelectSearch('freight', '', 'd_id,d_title', 'where d_enable="Y"');
            $data['Oship_freight'] = array_column($Freight, 'd_title', 'd_id');

            $data['dbdata'] = $dbdata;

            // $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
            $this->load->view('' . $this->AdminName . '/' . $this->DBname . '_return/_info', $data);
        } else {
            $this->useful->AlertPage('', '操作錯誤');
        }

    }

}
