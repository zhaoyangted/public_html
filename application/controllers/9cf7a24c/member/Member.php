<?php
class Member extends CI_Controller {
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

		$this->tableful->TableTreat(0);
		//p($this->tableful->Search);die;


		$dbdata = $this->mymodel->SelectPage($this->DBname, $this->tableful->SqlList, $this->tableful->WhereSql, 'd_create_time desc');
		$data['dbdata'] = $dbdata;

		// $this->load->view('' . $this->autoful->FileName . '/autopage/_list', $data);
		$this->load->view(''.$this->AdminName.'/'.$this->DBname.'/_list',$data);
	}
	// 後台自建訂單 登入至前台
	public function Mlogin(){
		$MID=$_POST['Mid'];
		$dbdata = $this->mymodel->WriteSQL('select m.d_id,m.d_account,m.d_phone,m.d_pname,m.d_lv,m.d_user_type,m.d_chked,m.TID,m.d_enable,m.d_chked,lv.d_title from member as m left join member_lv as lv on m.d_lv=lv.d_id where m.d_id="'.$MID.'"', '1');
		if(!empty($dbdata)){
			unset($_SESSION[CCODE::MEMBER]);
			$_SESSION[CCODE::MEMBER]['LName'] = $dbdata['d_pname'];
			$_SESSION[CCODE::MEMBER]['LEmail'] = $dbdata['d_account'];
			$_SESSION[CCODE::MEMBER]['LPhone'] = $dbdata['d_phone'];
			$_SESSION[CCODE::MEMBER]['LID'] = $dbdata['d_id'];
			$_SESSION[CCODE::MEMBER]['Mtype'] = $dbdata['TID'];
			$_SESSION[CCODE::MEMBER]['UserType'] = $dbdata['d_user_type'];
			$_SESSION[CCODE::MEMBER]['Mlv'] = $dbdata['d_lv'];
			$_SESSION[CCODE::MEMBER]['Mlv_title'] = $dbdata['d_title'];
			$_SESSION[CCODE::MEMBER]['IsLogin'] = 'Y';
			$_SESSION[CCODE::MEMBER]['NoBonus'] = 'Y';
			$_SESSION[CCODE::MEMBER]['Admin'] = $_SESSION[CCODE::ADMIN]['Aacc'];
			echo "OK";
		}
	}

	public function DownMember()
	{
		$data_array = array();
		$where = '';

		if (!empty($_POST['allid'])) {
			$where =' and m.d_id in ('.implode(',',$_POST['allid']).')';
		}else{
			foreach ($_POST as $k => $p) {
				if ($p!='') {
					switch ($k) {
						case 'd_lv':
						case 'd_chked':
						$where .= ' and m.'.$k.'='.$p;
						break;
						case 'd_account':
						case 'd_pname':
						$where .= ' and m.'.$k.' like "%'.$p.'%"';
						break;
						case 's_d_update_time':
						$where .= ' and m.d_update_time>="'.$p.'"';
						break;
						case 'e_d_update_time':
						$where .= ' and m.d_update_time<="'.date('Ymd',strtotime($p.'+1 day')).'"';
						break;
					}
				}
			}
		}

		$Member=$this->mymodel->Writesql('
			select m.d_user_type,m.d_mcode,m.d_company_title,m.d_company_name,m.d_pname,m.d_phone,m.d_company_tel_area,m.d_company_tel,m.d_company_fax_area,m.d_company_fax,m.d_account,m.d_company_number,
			m.d_zipcode,m.d_county,m.d_district,m.d_address,m.d_company_zipcode,m.d_company_county,m.d_company_district,m.d_company_address,m.d_enable,CAST(m.d_create_time AS DATE) as date,
			t.d_title as Ttitle,s.d_title as Stitle,c.d_title as Ctitle,(select CAST(o.d_create_time AS DATE) as Odate from orders o where o.MID = m.d_id order by o.d_create_time desc limit 1) as Odate,mt.d_code as MTcode
			from member m
			left join product_config t on t.d_val=m.d_user_type
			left join salesman s on s.d_id=m.SID
			left join product_config c on c.d_val=m.d_chked
			left join member_type mt on mt.d_id=m.TID
			where c.d_type=8 and t.d_type=6'.$where.'
			order by m.d_create_time desc
			');
		// 地區別
		$Dcode = array(
			'301' => ['基隆市','台北市','臺北市','新北市','桃園縣','新竹縣','宜蘭縣','花蓮縣','台東縣','臺東縣'],
			'302' => ['苗栗縣','雲林縣','台中市','臺中市','彰化縣','南投縣'],
			'303' => ['嘉義縣','台南市','臺南市','高雄市','屏東縣'],
			'304' => ['澎湖縣','金門縣','連江縣'],
		);

		foreach ($Member as $m) {

			$area = '';
			$county = ($m['d_user_type']==1) ? $m['d_county'] : $m['d_company_county'];
			foreach ($Dcode as $c => $addre) {
				if (in_array($county,$addre)) {
					$area = $c;
					break;
				}
			}

			$data_array[] = array(
				$m['d_mcode'],
				$m['d_company_title'],
				$m['d_company_name'],
				$m['d_pname'],
				$m['d_phone'],
				(!empty($m['d_company_tel_area'])?'('.$m['d_company_tel_area'].')-':'').$m['d_company_tel'],
				(!empty($m['d_company_fax_area'])?'('.$m['d_company_fax_area'].')-':'').$m['d_company_fax'],
				$m['d_account'],
				$m['d_company_number'],
				$m['d_account'],
				($m['d_user_type']==1) ? $m['d_zipcode'] : $m['d_company_zipcode'],
				($m['d_user_type']==1) ? $m['d_county'].$m['d_district'].$m['d_address']:$m['d_company_county'].$m['d_company_district'].$m['d_company_address'],
				'104',
				$m['MTcode'],
				$area,
				'401',
				$m['date'],
				$m['Ttitle'],
				$m['Stitle'],
				$m['Ctitle'],
				$m['Odate'],
				($m['d_enable']=='Y')?'啟動':'停用',
			);
		}

		$filename=date('ymdhis').'會員資料';

		$excelTemplate = 'uploads/ExcelSimple/MemberSimple.xlsx';

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


}
