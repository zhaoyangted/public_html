<?php
class Plow extends CI_Controller {
	public function __construct() {
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
	public function index() {
		$data = array();
		$this->tableful->TableTreat(2);
		$wheresql = ' where '.$this->DBname.'.d_stock<'.$this->webmodel->BaseConfig(18)['d_title'].' or ('.$this->DBname.'.d_low>0 and '.$this->DBname.'.d_stock<'.$this->DBname.'.d_low)';
		$dbdata = $this->mymodel->SelectPage($this->DBname, '*',$wheresql, 'd_sort');
		// print_r($dbdata['dbdata']);
		$data['dbdata'] = $dbdata;

		// $this->load->view('' . $this->autoful->FileName . '/autopage/_list', $data);
		$this->load->view(''.$this->AdminName.'/plow/_list',$data);
	}
	// 匯出低庫存資料
	public function Export(){
		$this->DBname='products';
		$wheresql = ' where '.$this->DBname.'.d_stock<'.$this->webmodel->BaseConfig(18)['d_title'].' or ('.$this->DBname.'.d_low>0 and '.$this->DBname.'.d_stock<'.$this->DBname.'.d_low)';
		$dbdata = $this->mymodel->WriteSQL(
			'select products.d_id,products.d_title,products.d_model,ps.d_code
			from products
			inner join products_stock ps on ps.d_id=products.SID
			'.$wheresql.'
			order by products.d_sort
		');

		foreach ($dbdata as $key => $value) {
			$data_array[]=array(
				$value['d_model'],
				$value['d_title'],
				$value['d_code'],
			);
		}
		// print_r($data_array);

		$filename=date('ymdhis').'低庫存資料';

		$excelTemplate = 'uploads/ExcelSimple/PlowSimple.xlsx';

		$this->DownExcel($data_array,$filename,'csv',$excelTemplate);
	}
	// 下載EXCEL(自訂表單匯出)
    private function DownExcel($data_array='', $filename,$Type='',$excelTemplate){
        // 清空輸出緩沖區
        if (ob_get_length()) ob_end_clean();

        //欄位矩陣
        $row_n=array(
            '0'=>'A', '1'=>'B', '2'=>'C', '3'=>'D', '4'=>'E',
            '5'=>'F', '6'=>'G', '7'=>'H', '8'=>'I', '9'=>'J',
            '10'=>'K', '11'=>'L', '12'=>'M', '13'=>'N', '14'=>'O',
            '15'=>'P', '16'=>'Q', '17'=>'R', '18'=>'S', '19'=>'T',
            '20'=>'U', '21'=>'V', '22'=>'W', '23'=>'X', '24'=>'Y', '25'=>'Z'
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

        foreach ($data_array as $key => $value){
					foreach ($value as $pdkey => $pdvalue){
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey, $row)->setValueExplicit($pdvalue, PHPExcel_Cell_DataType::TYPE_STRING);
					}
          $row++;
        }
        // 特殊處理
        //輸出excel文件
        $objPHPExcel->setActiveSheetIndex(0);

        // 設置HTTP頭
        if($Type=='csv'){
            $Httptype='text/x-csv';
            $Filetype='.csv';
        }else{
            $Httptype='application/vnd.ms-excel';
            $Filetype='.xls';
        }
        header('Content-Type: '.$Httptype.'; charset=utf-8');
        header('Content-Disposition: attachment;filename="'.mb_convert_encoding($filename, "Big-5", "UTF-8").$Filetype.'"');
        header('Cache-Control: max-age=0');

        // 第二個參數可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    public function Excel_Import(){
        // 允許上傳的
        $allowedExts = array("xls", "xlsx", "csv");
        $temp = explode(".", $_FILES["excel_file"]["name"]);
        $extension = end($temp); // 獲取檔案字尾名

        // 判別是不是.xls檔案，判別是不是excel檔案
        if (in_array(strtolower($extension), $allowedExts)) {
            $tmp_file = $_FILES['excel_file']['tmp_name'];
            $savePath = "./uploads/Excel_import/";
            // 以時間來命名上傳的檔案
            $file_name = date('Ymdhis') . "." . $extension;
            // 是否上傳成功
            if (!copy($tmp_file, $savePath . $file_name)) {
                $this->useful->AlertPage('', '上傳失敗，請重新上傳！');
                exit();
            }

            // 要獲得新的檔案路徑+名字
            $fullpath = $savePath . $file_name;

            $this->load->library('PHPExcel');
            $this->load->library('PHPExcel/IOFactory');

            $inputFileType = IOFactory::identify($fullpath);
        	$objReader = IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($fullpath);

            $data = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            if (!empty($data)) {
            	//移除標題列
	            unset($data['1']);
	            foreach ($data as $row => $date_row) {
                    $Model=$date_row['A'];
	            	$SID=$date_row['C'];
	            	$Pdata=$this->mymodel->OneSearchSql('products','d_id',array('d_model'=>$Model));
                    $Sdata=$this->mymodel->OneSearchSql('products_stock','d_id',array('d_code'=>$SID));
	            	if(!empty($Pdata) and !empty($Sdata)){
	            		$Stock=$date_row['D'];
	            		$this->mymodel->SimpleWriteSQL('update products set d_stock='.$Stock.',SID='.$Sdata['d_id'].' where d_id='.$Pdata['d_id'].'');
	            	}
	            }
	        }
            unlink($fullpath);
            $this->useful->AlertPage($this->AdminName . '/plow/plow', '匯入成功！');
            exit();

        } else {
            $this->useful->AlertPage('', '不是Excel檔案，請重新上傳！');
        }
    }
}
