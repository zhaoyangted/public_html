<?
class Tableful {
	protected $CI;
	public $cf=array();
	public function __construct($cf=array()){
		$this->CI =& get_instance();
		$this->cf=$cf;
		$this->CI->load->model('MyModel/Webmodel','webmodel');
		$this->CI->load->model('MyModel/Mymodel','mymodel');

	}

    // 檔案自動頁面2.1
    public function GetAutoPage($Type='',$DBid=''){
        // 標題
        if(!empty($Type)){
            $MenuUrl=str_replace('_'.$Type,'',str_replace(CCODE::DemoPrefix.'/'.$this->CI->AdminName.'/','',$_SERVER['REQUEST_URI']));
            $MenuUrl=str_replace('/'.$Type,'',$MenuUrl);
        }else{
            $MenuUrl=str_replace(CCODE::DemoPrefix.'/'.$this->CI->AdminName.'/','',$_SERVER['REQUEST_URI']);
            $Type=1;
        }
        if(strpos($MenuUrl,'/index'))
            $MenuUrl=substr($MenuUrl,0,strpos($MenuUrl,'/index'));
        if($MenuUrl=='ckeditpage/ckeditpage' or !empty($DBid)){
            $where=' where aps.d_id="'.$DBid.'"';
        }else{
            $where=' where aps.d_link="'.$MenuUrl.'"';
        }
        $MenuidDb=$this->CI->mymodel->Writesql('
            select aps.d_id,aps.d_jur,ap.d_icon,aps.d_title,aps.d_dbname,aps.d_oc,aps.d_search,aps.d_add,aps.d_edit,aps.d_del from auto_page_menu_sub aps
            inner join auto_page_menu ap on ap.d_id=aps.SID
            '.$where.'
        ','1');
        // 撈取自動網頁
        $this->GetAupageData($MenuidDb['d_id'],$Type);
        // print_r($MenuidDb);
        $this->MenuidDb=$MenuidDb;
    }

    // 撈取自動網頁
    public function GetAupageData($SID,$Type){
        if(empty($_POST['Searching']))
            unset($_SESSION['AT']["where"]);
        $search_default_array=array();

        $SqlList=array('d_id','d_enable');
        $AddEditType=array('1');
        $Search=array();
        $SqlType=($Type!=1)?'2':'1';
        $AddEditType[]=($Type=='add')?'2':'3';
        $Menu=$this->CI->mymodel->Writesql("
            SELECT d_fname,d_title,d_type,d_config,d_search,d_content,d_inputtype FROM `auto_page` where d_menu_id='".$SID."' and d_list=".$SqlType." and d_view=1 and d_add_fix in (".implode(',',$AddEditType).") order by d_sort
        ");
        foreach ($Menu as $key => $value) {
            $SqlList[]=$value['d_fname'];

            if(!empty($value['d_config'])){
                if($value['d_type']==10){
                    $search_default_array[]='s_'.$value['d_fname'];
                    $search_default_array[]='e_'.$value['d_fname'];
                }
                $search_default_array[]=$value['d_fname'];
            }

            if(!empty($value['d_search']) and $Type!=1){
                $Search[$value['d_fname']]=array($value['d_type'],$value['d_title'],$value['d_search']);

            }else if(!empty($value['d_config']) and $Type==1){
                $Search[$value['d_fname']]=array($value['d_type'],$value['d_title']);
                $search_default_array[]=$value['d_fname'];
                $this->CI->mymodel->search_session($search_default_array);
                if($_SESSION["AT"]["where"][$value['d_fname']]!=""){
                    // SearchFunction
                    if($value['d_type']==1){
                        $where_array[]="".$value['d_fname']." like '%".$_SESSION["AT"]["where"][$value['d_fname']]."%'";
                    }
                    if(in_array($value['d_type'],array('2','3','4'))){
                        $where_array[]="".$value['d_fname']." = '".$_SESSION["AT"]["where"][$value['d_fname']]."'";
                    }
                }
                if($value['d_type']==10){
                    if($_SESSION["AT"]["where"]['s_'.$value['d_fname']]!=""){
                        $where_array[]="".$value['d_fname'].">='".$_SESSION["AT"]["where"]['s_'.$value['d_fname']]."'";
                    }
                    if($_SESSION["AT"]["where"]['e_'.$value['d_fname']]!=""){
                        $where_array[]="".$value['d_fname']."<='".date('Ymd',strtotime($_SESSION["AT"]["where"]['e_'.$value['d_fname']].'+1 day'))."'";
                    }
                }
            }
            if (preg_match("/product_config_/i", $value['d_config'])){
                $ConfigType=substr($value['d_config'],15);
                $Config=$this->GetProConfig($ConfigType);
                $Menu[$key]['Config']=$Config;
                if($Type==1){
                    $Search[$value['d_fname']]['Config']=$Config;
                }
            }

            if(isset($value['d_inputtype'])){
                $InputType=$this->CI->mymodel->Writesql("SELECT d_val FROM `auto_config` where d_id='".$value['d_inputtype']."'",'1');
                $Menu[$key]['InputType']=$InputType['d_val'];
            }
        }
        // print_r($Menu);
        $this->Type=$Type;
        $this->SqlList=implode(',',$SqlList);
        $this->Menu=$Menu;
        $this->Search=$Search;
        $this->WhereSql=!empty($where_array)?"where ".implode(" and ",$where_array):"";
    }
    //撈取查詢資料(列表)
    public function GetProConfig($Type){
        $Cdata=$this->CI->mymodel->Writesql('select * from product_config where d_enable="Y" and d_type='.$Type.'');
        foreach ($Cdata as $key => $value) {
            $config[$value['d_val']]=$value['d_title'];
        }
        return $config;
    }
    // 不同的資料表處理函式
    public function TableTreat($TableId,$SqlWhere='',$Filed='d_title'){
        $dbname=$this->Menu[$TableId]['d_config'];
        if ($TableId==31) {
            //獲取顏色
            $Typedata=$this->CI->mymodel->SelectSearch($dbname,'','d_id,'.$Filed.'','where d_enable="Y" and variant_id=1'.$SqlWhere.'');
        } else if ($TableId==32) {
            //獲取尺寸
            $Typedata=$this->CI->mymodel->SelectSearch($dbname,'','d_id,'.$Filed.'','where d_enable="Y" and variant_id=3'.$SqlWhere.'');
        } else if ($TableId==33) {
            //獲取材質
            $Typedata=$this->CI->mymodel->SelectSearch($dbname,'','d_id,'.$Filed.'','where d_enable="Y" and variant_id=2'.$SqlWhere.'');
        } else {
        $Typedata=$this->CI->mymodel->SelectSearch($dbname,'','d_id,'.$Filed.'','where d_enable="Y" '.$SqlWhere.'');
        }
        if(!empty($Typedata)){
            foreach ($Typedata as $key => $value) {
                $Tconfig[$value['d_id']]=$value[''.$Filed.''];
            }
            $this->Menu[$TableId]['Config']=$Tconfig;
            if($this->Type==1){
                $Fname=$this->Menu[$TableId]['d_fname'];
                $this->Search[$Fname]['Config']=$Tconfig;
            }
        }
    }
    //-----------------------------------------------------------------------------------
    // 函數名：send_mail($sender_domain, $sender, $addressee, $subject, $message)
    // 作 用 ：寄信
    // 參 數 ：$sender_domain 寄件人信箱網域名
    // $sender 寄件人
    // $addressee 收件人
    // $subject 主旨
    // $message 內容
    // 返回值：無
    // 備 注 ：無
    //-----------------------------------------------------------------------------------
    public function Sendmail($Address='', $Subject, $Message,$attach=''){
        //沒有收件者email不寄信
        if($Address != '')
        {

            //網站信件發送信箱
            $domin=$this->CI->webmodel->BaseConfig(5);
            $from=trim($domin['d_title']);
            //網站信件發送名稱
            $sender=$this->CI->webmodel->BaseConfig(6);
            $Sename=trim($sender['d_title']);

            //email lib
            $this->CI->load->library('email');
            // //enter method
            $this->CI->email->set_newline("\r\n");
            // //寄件人
            $this->CI->email->from($from,$Sename);
            // //收件人
            $this->CI->email->to($Address);
            // //主旨
            $this->CI->email->subject($Subject);
            // // 附件 $attach=>array形式 array('路徑','路徑','路徑')
            if(!empty($attach)){
                foreach ($attach as $value) {
                    $this->CI->email->attach($value);
                }
            }
            // //內容
            $this->CI->email->message($Message);
            // 寄送
            $this->CI->email->send();
        }
    }
    // 下載EXCEL
    public function DownExcel($title_array='', $data_array='', $filename,$Type=''){
        // 清空輸出緩沖區
        if(ob_get_length() > 0) {
            ob_clean();
        }

        //欄位矩陣
        $row_n=array(
            '0'=>'A', '1'=>'B', '2'=>'C', '3'=>'D', '4'=>'E',
            '5'=>'F', '6'=>'G', '7'=>'H', '8'=>'I', '9'=>'J',
            '10'=>'K', '11'=>'L', '12'=>'M', '13'=>'N', '14'=>'O',
            '15'=>'P', '16'=>'Q', '17'=>'R', '18'=>'S', '19'=>'T',
            '20'=>'U', '21'=>'V', '22'=>'W', '23'=>'X', '24'=>'Y', '25'=>'Z'
        );

        // 載入PHPExcel類庫
        $this->CI->load->library('PHPExcel');
        $this->CI->load->library('PHPExcel/IOFactory');

        // 創建PHPExcel對象
        $objPHPExcel = new PHPExcel();

        // 設置excel文件屬性描述
        $objPHPExcel->getProperties()
                    ->setTitle("reports")
                    ->setDescription("")
                    ->setCreator("wepower");

        // 設置當前工作表
        $objPHPExcel->setActiveSheetIndex(0);

        // 設置表頭
        foreach($title_array as $key => $value)
        {
            $fields[] = $value;
        }

        // 列編號從0開始，行編號從1開始
        $col = 0;
        $row = 1;
        foreach($fields as $key => $field)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($row_n[$key])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
            $col++;
        }

        // 從第二行開始輸出數據內容
        $row = 2;
        foreach ($data_array as $key => $value)
        {
            foreach ($value as $pdkey => $pdvalue)
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension($row_n[$pdkey])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($pdkey, $row)->setValueExplicit($pdvalue, PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $row++;
        }

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
