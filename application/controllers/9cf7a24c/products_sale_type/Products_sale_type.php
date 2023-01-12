<?php
class Products_sale_type extends CI_Controller
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
        $dbdata = $this->mymodel->SelectPage($this->DBname, $this->tableful->SqlList, $this->tableful->WhereSql, 'd_sort asc,d_create_time desc');
        $data['dbdata'] = $dbdata;

        // $this->load->view(''.$this->autoful->FileName.'/autopage/_list',$data);
        $this->load->view('' . $this->AdminName . '/' . $this->DBname . '/_list', $data);
    }

    // 報表匯出
    public function DownSale()
    {
        $where = '';
        if (!empty($_POST['allid'])) {
            $where .= ' and t.d_id in (' . implode(',', $_POST['allid']) . ')';
        } else {
            foreach ($_POST as $k => $p) {
                if ($p != '') {
                    switch ($k) {
                        case 'd_title':
                            $where .= ' and t.' . $k . ' like "%' . $p . '%"';
                            break;
                    }
                }
            }
        }
        if (empty($where)) {
            $this->useful->AlertPage('', '必須輸入選擇項目或搜尋條件才可進行匯出！');
            exit();
        }

        $odata = $this->mymodel->Writesql('
										select t.d_start,t.d_end,t.d_title as Ttitle,s.d_title as Stitle,
										d.d_model,d.d_title,d.d_price1,d.d_price2,d.d_price3,c.d_title as Ctitle,s.d_price as Sprice,d.d_num
										from products_sale_type t
										left join products_sale s on s.TID=t.d_id
										left join products_sale_detail d on d.SID=s.d_id
										left join product_config c on c.d_val=s.d_type
										where c.d_type=17' . $where . '
                                        GROUP by d.d_model
										order by t.d_sort asc,s.d_sort asc,d.d_id desc
									');
        // print_r($where);
        // exit();
        $this->load->library('Mylib/OrderExportful');

        foreach ($odata as $key => $value) {
            $data_array[] = array_values($value);
        }

        $filename = date('ymdhis') . '專案銷售報表';

        $excelTemplate = 'uploads/ExcelSimple/SingleSaleSimple.xlsx';

        $this->DownExcel($data_array, $filename, 'csv', $excelTemplate, true);
    }

    // 下載EXCEL(自訂表單匯出)
    private function DownExcel($data_array = array(), $filename, $Type = '', $excelTemplate, $Isreturn = false)
    {
        // 清空輸出緩沖區
        if (ob_get_length()) {
            ob_end_clean();
        }

        //欄位矩陣
        $row_n = array(
            '0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E',
            '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J',
            '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O',
            '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T',
            '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z',
        );

        // 載入PHPExcel類庫
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        // 判斷 Excel 檔案是否存在
        if (!file_exists($excelTemplate)) {
            exit('Please run template.php first.' . EOL);
        }

        // 載入 Excel
        $objPHPExcel = IOFactory::load($excelTemplate);
        // 從第二行開始輸出數據內容
        $row = 2;

        foreach ($data_array as $key => $value) {
            foreach ($value as $pdkey => $pdvalue) {
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey, $row)->setValueExplicit($pdvalue, PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $row++;
        }
        // 特殊處理
        //輸出excel文件
        $objPHPExcel->setActiveSheetIndex(0);

        // 設置HTTP頭
        if ($Type == 'csv') {
            $Httptype = 'text/x-csv';
            $Filetype = '.csv';
        } else {
            $Httptype = 'application/vnd.ms-excel';
            $Filetype = '.xls';
        }
        header('Content-Type: ' . $Httptype . '; charset=utf-8');
        header('Content-Disposition: attachment;filename="' . mb_convert_encoding($filename, "Big-5", "UTF-8") . $Filetype . '"');
        header('Cache-Control: max-age=0');

        // 第二個參數可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}
