<?php
class Products extends CI_Controller
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
        // $this->tableful->TableTreat(0,'and TID is null and TTID is null');
        // $this->tableful->TableTreat(1,'and TID is not null and TTID is null');
        // $this->tableful->TableTreat(2,'and TID is not null and TTID is not null');
        $this->tableful->TableTreat(0);

        // 搜尋陣列
        $data['SearchArray'] = $SearchArray = array(
            'd_sprice' => '特價商品',
            'd_dprice' => '出清價商品',
            'd_bonus' => '紅利商品',
            'd_hot' => '人氣商品',
            'd_enable' => '停用商品',
        );
        if (!empty($_POST['d_search'])) {
            //p($_POST);
            $search = $_POST['d_search'];
            $_SESSION["AT"]["where"]['d_search'] = $search;
            if ($search == 'd_sprice') {
                $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and d_sprice !=0' : 'where d_sprice!=0');
            }
            if ($search == 'd_dprice') {
                $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and d_dprice !=0' : 'where d_dprice!=0');
            }
            if ($search == 'd_bonus') {
                $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and d_bonus !=0' : 'where d_bonus!=0');
            }
            if ($search == 'd_hot') {
                $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and d_hot="Y"' : 'where d_hot="Y"');
            }
            if ($search == 'd_enable') {
                $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and d_enable="N"' : 'where d_enable="N"');
            }
        }
        // 總碼編號
        if (!empty($_POST['d_allspec'])) {
            $search = $_POST['d_allspec'];
            $_SESSION["AT"]["where"]['d_allspec'] = $search;
            $Allspec = $this->mymodel->WriteSql('select PID from products_allspec where d_title like "%' . $search . '%" and PID !=""', '');
            if (!empty($Allspec)) {
                $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and d_id in(' : 'where d_id in(');
                foreach (array_column($Allspec, 'PID') as $v) {
                    $this->tableful->WhereSql .= implode(',', explode('@#', $v));
                }
                $this->tableful->WhereSql .= ')';
            }
        }

        // 多樣產品編號篩選
        if (!empty($_POST['d_xxx'])) {
            $search = $_POST['d_xxx'];
            $_SESSION["AT"]["where"]['d_xxx'] = $search;

            $rs = explode(',', $search);
            if (!empty($rs)) {
                $str = array();
                foreach ($rs as $v) {
                    $str[] .= 'd_model like \'%'.$v.'%\'';
                }
                //p($str);
                $str = implode(' or ', $str);
            }
            $this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and (' . $str . ' )' : 'where '.  $str);
        }
        //echo $this->tableful->WhereSql;
        $dbdata = $this->mymodel->SelectPage($this->DBname, $this->tableful->SqlList, $this->tableful->WhereSql, 'd_update_time desc');

        $data['dbdata'] = $dbdata;

        // $this->load->view(''.$this->autoful->FileName.'/autopage/_list',$data);
        $this->load->view('' . $this->AdminName . '/' . $this->DBname . '/_list', $data);
    }
    // 複製商品
    public function CopyProducts()
    {
        if (empty($_POST['allid'])) {
            $this->useful->AlertPage('', '請勾選複製商品');
            exit();
        }
        $id = implode(',', $_POST['allid']);
        // $id='3,4';

        $Pdata = $this->mymodel->SelectSearch('products', '', '*', 'where d_id in (' . $id . ')');
        foreach ($Pdata as $key => $value) {
            $PID = $value['d_id'];
            unset($value['d_id']);
            for ($i = 1; $i < 6; $i++) {
                if (!empty($value['d_img' . $i])) {
                    $img = $value['d_img' . $i];
                    $imgdata = explode('/', $img);
                    $Pic = explode('.', $imgdata[2]);
                    $imgdata[2] = $Pic[0] . '_copy' . rand(0, 99999) . '.' . $Pic[1];
                    $Picpath = implode('/', $imgdata);
                    // print_r($Picpath);
                    $value['d_img' . $i] = $Picpath;
                    copy($img, $Picpath);
                }
            }
            $dbdata = $this->useful->DB_Array($value, '', '', '1');
            $this->mymodel->InsertData('products', $dbdata);
        }
        $this->useful->AlertPage($this->AdminName . '/products/products', '複製商品成功');
        exit();
    }

    public function Excel_Import(){
        // 允許上傳的
        $allowedExts = array("xls");
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

            $objReader = IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($fullpath);
            $data = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            // 取得現有產品目錄陣列
            $pType = $this->mymodel->Writesql('select d_id from products_type');
            $pTypeArr = array();

            foreach($pType as $k => $v){
                $pTypeArr[] = $v['d_id'];
            }

            if (!empty($data)) {
                $push = $watch = array();
                $column = array(
                    'A'     =>  'd_model',
                    'B'     =>  'd_title',
                    'C'     =>  'TID',
                    'D'     =>  'TTID',
                    'E'     =>  'TTTID',
                    'F'     =>  array('products_brand', 'd_title', 'BID'),
                    'G'     =>  array('member_type', 'd_code', 'MTID'),
                    'H'     =>  array('freight', 'd_num', 'FID'),
                    'I'     =>  'd_spectitle',
                    'J'     =>  array('products_stock', 'd_code', 'SID'),
                    'K'     =>  'd_stock',
                    'L'     =>  'd_low',
                    'M'     =>  'd_sprice', // 促銷價
                    'N'     =>  'd_dprice', // 出清價
                    'O'     =>  'd_price1',
                    'P'     =>  'd_price2',
                    'Q'     =>  'd_price3',
                    'R'     =>  'd_img1', // 圖片 1 
                    'S'     =>  'd_img2', // 圖片 2
                    'T'     =>  'd_img3', // 圖片 3
                    'U'     =>  'd_img4', // 圖片 4
                    'V'     =>  'd_img5', // 圖片 5
                    'W'     =>  'd_content', // 產品內容
                    'X'     =>  'd_qacontent', // 問與答
                    'Y'     =>  'd_bcontent',    // 購買說明
                    'Z'     =>  'd_push',    // 相關產品推薦(產品編號)
                    'AA'     =>  'd_watch',    // 看過此商品的人也看過下列商品(產品編號)
                    'AB'     =>  'd_hot',
                    'AC'     =>  'd_new',
                    'AD'     =>  'd_stitle',        // 標題標籤
                    'AE'     =>  'd_skeywords',     // 關鍵字
                    'AF'     =>  'd_sdescription',  // 網頁描述
                    'AG'     =>  'd_bonus',         // 獲得紅利
                    'AH'     =>  'd_enable'
                );

                //移除標題列
                unset($data['1']);
                //移除說明列
                unset($data['2']);
                //移除說明列
                unset($data['3']);

                // 資料預處理
                foreach ($data as $row => $date_row) {
                    // 先把空值拿掉
                    foreach($date_row as $def_k => $def){
                        $date_row[ $def_k ] = ($def == '')?'':$def;
                    }
                    //p($date_row);

                    //處理圖片路徑
                    $data[$row]['R'] = ($date_row['R']!='')?'uploads/products/'.$date_row['R']:'';
                    $data[$row]['S'] = ($date_row['S']!='')?'uploads/products/'.$date_row['S']:'';
                    $data[$row]['T'] = ($date_row['T']!='')?'uploads/products/'.$date_row['T']:'';
                    $data[$row]['U'] = ($date_row['U']!='')?'uploads/products/'.$date_row['U']:'';
                    $data[$row]['V'] = ($date_row['V']!='')?'uploads/products/'.$date_row['V']:'';
                }



                // p($data);die;
                foreach ($data as $row => $date_row) {
                    //echo "<BR>總碼欄位為空";
                    foreach ($column as $k => $v) {
                        //echo "<BR>測試$date_row[$k] <BR>";
                        //p($date_row);die;
                        //echo $k.'-'.p($v).'<BR>';
                        //var_dump(strpos($date_row[$k], '!@')).'<BR>';
                        // 處理 , 轉 @#
                        $strA = array();
                        if($k == 'C' || $k == 'D' || $k == 'E')
                        {
                            // 這邊要處理已經被刪除的目錄要抽離
                            //p($date_row[$k]);
                            $strA = explode(',', $date_row[$k]);
                            if( !empty($strA)) {
                                foreach($strA as $kkk => $vvv){
                                    //p($vvv);
                                    if( !in_array($vvv, $pTypeArr)) {
                                        unset($strA[$kkk]);
                                    }
                                }
                            }
                            $date_row[$k] = implode('@#', $strA);
                        }

                        if (strpos($date_row[$k], '!@') === false && $date_row[$k] != '') {
                            if (is_array($v)) { 
                                // 多層目錄用  [0] => products_brand    [1] => d_title     [2] => BID
                                $dbdata[$v[2]] = $this->Exceldata($date_row[$k], $v[0], $v[1], 'd_id');

                            } else {
                                $dbdata[$v] = $date_row[$k];
                            }
                        } elseif ( strpos($date_row[$k], '!@') !== false ) {
                            // 遇到 '!@' 設為空值
                            if( $k == 'R' || $k == 'S' || $k == 'T' || $k == 'U' || $k == 'V' || $k == 'W' || $k == 'X' || $k == 'Y' || $k == 'AD' || $k == 'AE' || $k == 'AF') {
                                // 圖片欄位不能設為 null，不然會有路徑的圖出現
                                $dbdata[$v] = '';    
                            }
                            else
                            {
                                $dbdata[$v] = 'NULL';  
                            }
                        }
                    }


                    // 驗證以下欄位有填入值：產品名稱、產品分類A、品牌分類、運費等級、庫別、庫存量、安全庫存、市價、會員價、沙龍價、紅利百分比、圖片1。如果有項目沒填，EXCEL匯入後產品會建立但狀態為【停用】。
                    if ($data[$row]['B'] == '' || $data[$row]['B'] == '!@' || $data[$row]['C'] == '' || $data[$row]['C'] == '!@' || $data[$row]['F'] == '' || $data[$row]['F'] == '!@' || $data[$row]['H'] == '' || $data[$row]['H'] == '!@' || $data[$row]['J'] == '' || $data[$row]['J'] == '!@' || $data[$row]['K'] == '' || $data[$row]['K'] == '!@' || $data[$row]['L'] == '' || $data[$row]['L'] == '!@' || $data[$row]['O'] == '' || $data[$row]['O'] == '!@' || $data[$row]['P'] == '' || $data[$row]['P'] == '!@' || $data[$row]['Q'] == '' || $data[$row]['Q'] == '!@' || $data[$row]['R'] == '' || $data[$row]['R'] == '!@') {
                        //echo "<BR>偵測到必填欄位空值，停用產品<BR>";
                        $dbdata['d_enable'] = 'N';
                    }
                    //p($dbdata);die;
                    $chkModel = $this->mymodel->OneSearchSql('products', 'd_id', array('d_model' => $date_row['A']));
                    //echo "<BR>查看產品是否存在<BR>";
                        //p($chkModel);
                        //p($dbdata);die;
                    if (!empty($chkModel)) {
                        //echo "<BR>產品存在，更新 $chkModel[d_id]<BR>"; //p( $dbdata);
                        //p($dbdata);
                        $this->db->where('d_id', $chkModel['d_id'])->update('products', $dbdata);
                        //echo $this->db->last_query();die;
                        $NewID = $chkModel['d_id'];
                    } else {
                        $dbdata['d_create_time']=date('Y-m-d H:i:s');

                        //echo "<BR>產品不存在，添加產品<BR>"; //p( $dbdata);
                        $this->db->insert('products', $dbdata);
                        $NewID = $this->db->insert_id();
                        //echo $this->db->last_query();
                    }
                    //echo $date_row['Z'];
                    !empty($date_row['Z'])&&$date_row['Z']!='' ? $push[$NewID] = $date_row['Z'] : '';
                    !empty($date_row['AA'])&&$date_row['AA']!='' ? $watch[$NewID] = $date_row['AA'] : '';
                    unset($data[$row]);
                }

                // 更新總碼部份另寫
                /*foreach ($data as $date_row) {

                    $Allspec = $this->mymodel->OneSearchSql('products_allspec', 'd_id,d_title', array('d_title' => $date_row['A']));
                    $dbdata['PID'] = $this->Exceldata($date_row['B'], 'products', 'd_model', 'd_id','',true);
                    if (!empty($Allspec)) {
                        $Updateallspec = array(
                            'd_title' => $date_row['A'],
                            'PID' => $dbdata['PID'],
                            'd_try' => 1,
                            'd_update_time' => date('Y-m-d H:i:s'),
                            'd_edit_ip' => $this->useful->get_ip(),
                        );
                        //echo "<BR>更新<BR>";
                        //$this->mymodel->UpdateData('products_allspec', $Updateallspec, ' where d_id=' . $Allspec['d_id'] . '');
                    } else {
                        $Addallspec = array(
                            'd_title' => $date_row['A'],
                            'PID' => $dbdata['PID'],
                            'd_try' => 1,
                            'd_enable' => 'Y',
                            'd_create_time' => date('Y-m-d H:i:s'),
                            'd_edit_ip' => $this->useful->get_ip(),
                        );
                        //echo "<BR>插入<BR>";
                        //$this->mymodel->InsertData('products_allspec', $Addallspec);
                    }
                }*/
                //echo "<BR>更新產品d_push、d_watch<BR>";
                //p($push);
                $puArr = array();
                foreach ($push as $PID => $value) {
                    $r = explode(',', $value);
                    foreach ($r as $v2) {
                        $getPid = $this->mymodel->OneSearchSql('products', 'd_id', array('d_model' => trim($v2) ) );
                        //echo $this->db->last_query();
                        if(!empty($getPid)){
                            $puArr[] = $getPid['d_id'];
                        }
                    }
                    //$dbdata = $this->Exceldata($value[0], 'products_allspec', 'd_title', 'PID');
                    //$dbdata = $this->Exceldata($value[1], 'products', 'd_model', 'd_id', $dbdata);
                    
                    // 2021-11-09 還要判斷總碼
                    foreach ($r as $v2) {
                        $getX = $this->mymodel->OneSearchSql('products_allspec', 'PID', array('d_title' => trim($v2) ) );
                        if(!empty($getX)){
                            $a = explode('@#', $getX['PID']);
                            foreach($a as $value2) {
                                //p($value2);
                                $puArr[] = $value2;
                            }
                        }
                    }

                    $puArr = array_filter($puArr);  // 移除空陣列
                    if(!empty($puArr)){
                        $pushStr = implode('@#', $puArr);
                        //p($pushStr); die;
                        $this->mymodel->UpdateData('products', array('d_push' => $pushStr), ' where d_id=' . $NewID . '');
                        //echo $this->db->last_query();
                    }
                }

                //p($watch);
                $waArr = array();
                foreach ($watch as $PID => $value) {
                    $r = explode(',', $value);
                    foreach ($r as $v2) {
                        $getPid = $this->mymodel->OneSearchSql('products', 'd_id', array('d_model' => trim($v2) ) );
                        if(!empty($getPid)){
                            $waArr[] = $getPid['d_id'];
                        }
                    }

                    // 2021-11-09 還要判斷總碼
                    foreach ($r as $v2) {
                        $getX = $this->mymodel->OneSearchSql('products_allspec', 'PID', array('d_title' => trim($v2) ) );
                        if(!empty($getX)){
                            $a = explode('@#', $getX['PID']);
                            foreach($a as $value2) {
                                //p($value2);
                                $waArr[] = $value2;
                            }
                        }
                    }

                    $waArr = array_filter($waArr);  // 移除空陣列
                    if(!empty($waArr)){
                        $watchStr = implode('@#', $waArr);
                        $this->mymodel->UpdateData('products', array('d_watch' => $watchStr), ' where d_id=' . $NewID . '');
                    }
                    //echo $this->db->last_query();
                }
            }
            //die;
            unlink($fullpath);
            //die;
            $this->useful->AlertPage($this->AdminName . '/products/products', '匯入商品成功！');
            exit();

        } else {
            $this->useful->AlertPage('', '不是Excel檔案，請重新上傳！');
        }

    }

    private function Exceldata($data, $table, $where, $field, $dbdata = '',$chk=false)
    {
        if (strpos($data, ',')) {
            foreach (explode(',', $data) as $value) {
                if ($chk) {
                    $chkPid = $this->mymodel->OneSearchSql('products', $field, array($where => $value));
                    $chkAllspec=$this->mymodel->WriteSql('select PID from products_allspec where (PID like "'.$chkPid[$field].'@#%" or PID like "%@#'.$chkPid[$field].'" or PID like "%@#'.$chkPid[$field].'@#%" or PID='.$chkPid[$field].')',1);
                    echo $this->db->last_query();
                    if (!empty($chkAllspec)) {
                        continue;
                    }
                }

                if (empty($where_in)) {
                    $where_in = '"' . $value . '"';
                } else {
                    $where_in .= ',"' . $value . '"';
                }
            }

            $get_data = $this->mymodel->SelectSearch($table, '', $field, 'where ' . $where . ' in (' . $where_in . ')');
            //echo $this->db->last_query();
            foreach ($get_data as $k => $p) {
                if ($k == 0 && empty($dbdata)) {
                    $dbdata = $p[$field];
                } else {
                    $dbdata .= '@#' . $p[$field];
                }
            }
        } else {
            $get_data = $this->mymodel->OneSearchSql($table, $field, array($where => $data));
            if (!empty($get_data)) {
                if (empty($dbdata)) {
                    $dbdata = $get_data[$field];
                } else {
                    $dbdata .= '@#' . $get_data[$field];
                }
            }
        }
        return $dbdata;
    }


    public function DownProduct()
    {
        /**
         * 匯出產品excel
         * @var array
         */
        $data_array = array();
        $where = '';

        // 品牌 [BID] => 1
        // 產品名稱 [d_title] => 
        // 產品編號 [d_model] => 
        // 總碼編號 [d_allspec] => 
        // 搜尋方式 [d_search] => 
        // [ToPage] => 1
        //p($_POST);die;
        if (!empty($_POST['BID'])) {
            $where .=' and b.d_id =\''.$_POST['BID'].'\'';
        }

        if (!empty($_POST['d_title'])) {
            $where .=' and p.d_title like \'%'.$_POST['d_title'].'%\'';
        }

        if (!empty($_POST['d_model'])) {
            $where .=' and p.d_model like \'%'.$_POST['d_model'].'%\'';
        }

        // 假如有輸入總碼
        $data = array();
        if (!empty($_POST['d_allspec'])) {

            $spec = $this->mymodel->Writesql('select * from products_allspec where d_title like \'%'.$_POST['d_allspec'].'%\'');
            //p($spec);
            if(!empty($spec)) {
                $xx = explode('@#', $spec[0]['PID']);
                $w = ' AND (';
                $wStr= '';
                foreach ( $xx as $v) {
                    $wStr .= ' or p.d_id  = '. $v;
                }
                $wStr = substr($wStr,3);

                $where .= $w.$wStr. ')';
                // AND ( p.PID = 269 or p.PID = 270 or p.PID = 271)
            }
        }

        // 多樣產品編號篩選
        if (!empty($_POST['d_xxx'])) {
            $search = $_POST['d_xxx'];
            $_SESSION["AT"]["where"]['d_xxx'] = $search;

            $rs = explode(',', $search);
            if (!empty($rs)) {
                $str = array();
                foreach ($rs as $v) {
                    $str[] .= 'd_model like \'%'.$v.'%\'';
                }
                //p($str);
                $str = implode(' or ', $str);
            }
            //$this->tableful->WhereSql .= (!empty($this->tableful->WhereSql) ? ' and (' . $str . ' )' : 'where '.  $str);
            $where .= ' and (' . $str . ' )';
        }

        $Member=$this->mymodel->Writesql('
            select s.d_code as d_code, s.d_title as d_title_a, p.MTID, p.d_img1, p.d_img2, p.d_img3, p.d_img4, p.d_id as PID, p.d_img5, p.d_content, p.d_qacontent, p.d_bcontent, b.d_title as d_title_b, f.d_id, f.d_num, f.d_title as d_title_f, p.TID,p.TTID,p.TTTID, p.BID,p.FID, p.d_title, p.d_stitle, p.d_push, p.d_watch, p.d_skeywords, p.d_sdescription, p.d_spectitle,p.d_model,p.d_stock,p.d_low,p.d_sprice,p.d_dprice,p.d_price1,p.d_price2,p.d_price3,p.d_bonus,p.d_hot,p.d_new,p.d_enable
            from products p
            LEFT JOIN products_stock s ON s.d_id = p.SID
            LEFT JOIN products_brand b ON p.BID = b.d_id
            LEFT JOIN freight f ON p.FID = f.d_id
            where p.d_id > 0'.$where.'
            order by p.d_update_time desc
            limit 5000
            ');
        
        //echo $this->db->last_query();die;

        if (empty($Member)) 
        {
            $this->useful->AlertPage('', '查詢資料為 0 ，請調整篩選條件！');
        }

        // 主要營業類別陣列
        $type=$this->mymodel->Writesql('select * from member_type');
        $md = $md_a = array();
        foreach ($type as $k => $v) {
            $md[$v['d_id']] = $v['d_code'];
            $mc[$v['d_id']] = $v['d_title'];
            $md_a[] = $v['d_id'];
        }

        // 產品分類
        $type=$this->mymodel->Writesql('select * from products_type');
        $pd = $pd_a = array();
        foreach ($type as $k => $v) {
            $pd[$v['d_id']] = $v['d_title'];
            $pd_a[] = $v['d_id'];
        }

        // 推薦商品 瀏覽過
        $type=$this->mymodel->Writesql('select d_id,d_model from products');
        $pdd = $pdd_a = array();

        foreach ($type as $k => $v) {
            $pdd[$v['d_id']] = $v['d_model'];
            $pdd_a[] = $v['d_id'];
        }

        //p($pdd);p($pdd_a);die;

        // 拆解分類字段
        foreach ($Member as $m) {

            $ar1 = explode('@#', $m['TID']);
            $ar2 = explode('@#', $m['TTID']);
            $ar3 = explode('@#', $m['TTTID']);
            $ar4 = explode('@#', $m['MTID']);
            $ar5 = explode('@#', $m['d_push']);
            $ar6 = explode('@#', $m['d_watch']);

            $typeName1 = $typeName2 = $typeName3 = $typeName4 = $typeName5 = $typeName6 = $typeName7 = array();
            //p($ar1);
            foreach($ar1 as $v){
                if($v == 2) {
                    //p($v);
                }else{
                    if(in_array($v, $pd_a)){
                        $typeName1[] = $pd[ $v ];
                    }  
                }
            }  
            foreach($ar2 as $v){
                //p($v);
                if(in_array($v, $pd_a)){
                    //p($pd[ $v ]);
                    $typeName2[] = $pd[ $v ];
                }
            }
            foreach($ar3 as $v){
                if(in_array($v, $pd_a)){
                    $typeName3[] = $pd[ $v ];
                }
            } 

            foreach($ar4 as $v){
                if(in_array($v, $md_a)){
                    $typeName4[] = $md[ $v ];
                }
            } 
            // 推薦＆瀏覽過
            foreach($ar5 as $v){
                if(in_array($v, $pdd_a)){
                    $typeName5[] = $pdd[ $v ];
                }
            }

            foreach($ar6 as $v){
                if(in_array($v, $pdd_a)){
                    $typeName6[] = $pdd[ $v ];
                }
            }

            foreach($ar4 as $v){
                if(in_array($v, $md_a)){
                    $typeName7[] = $mc[ $v ];
                }
            } 

            $data_array[] = array(
                $m['d_model'],              //產品編號
                $m['d_title'],              //產品名稱
                implode(',', $ar1),         //產品分類1
                implode(',', $typeName1),   //產品分類1
                implode(',', $ar2),         //產品分類2
                implode(',', $typeName2),   //產品分類2
                implode(',', $ar3),         //產品分類3
                implode(',', $typeName3),   //產品分類3
                $m['d_title_b'],            //品牌分類
                implode(',', $typeName4),   //主要營業類別
                implode(',', $typeName7),   //主要營業類別名稱
                $m['d_num'],            //運費等級
                $m['d_title_f'],        //運費等級
                $m['d_spectitle'],      //規格名稱
                $m['d_code'],           //庫別
                $m['d_title_a'],        //庫別名稱
                $m['d_stock'],          //庫存量
                $m['d_low'],            //低庫存
                $m['d_sprice'],         //促銷價
                $m['d_dprice'],         //出清價
                $m['d_price1'],         //市價
                $m['d_price2'],         //會員價
                $m['d_price3'],         //沙龍價
                str_replace('uploads/products/','',$m['d_img1']),         // 圖片1
                str_replace('uploads/products/','',$m['d_img2']),         // 圖片2
                str_replace('uploads/products/','',$m['d_img3']),         // 圖片3
                str_replace('uploads/products/','',$m['d_img4']),         // 圖片4
                str_replace('uploads/products/','',$m['d_img5']),         // 圖片5
                $m['d_content'],        // 產品內容
                $m['d_qacontent'],      // 問與答
                $m['d_bcontent'],       // 購買說明
                implode(',', $typeName5),            // 相關產品推薦(產品編號)
                implode(',', $typeName6),            // 看過此商品的人也看過下列商品(產品編號)
                $m['d_hot'],            // 人氣產品(Y=啟用，N=停用)
                $m['d_new'],            // 最新產品(Y=啟用，N=停用)
                $m['d_stitle'],         // 標題標籤
                $m['d_skeywords'],      // 關鍵字
                $m['d_sdescription'],   // 網頁描述
                $m['d_bonus'],          // 獲得紅利(%)
                $m['d_enable'],         //狀態
            );

        }

        $filename=date('ymd_his').'_匯出產品資料';

        $excelTemplate = 'uploads/ExcelSimple/ProductSimple.xls';

        $this->DownExcel($data_array,$filename,'xls',$excelTemplate);
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
            '20'=>'U', '21'=>'V', '22'=>'W', '23'=>'X', '24'=>'Y', '25'=>'Z', '26'=>'AA'
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
        // 從第4行開始輸出數據內容
        $row = 4;

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


    // 校正所有產品分類目錄
    public function correction(){
        echo "<BR>取得現有產品目錄陣列 →<BR>";
        // 取得現有產品目錄陣列
        $pType = $this->mymodel->Writesql('select d_id from products_type');
        $pTypeArr = array();

        foreach($pType as $k => $v){
            $pTypeArr[] = $v['d_id'];
        }

        echo "<BR>取得現有產品陣列 →<BR>";
        // 取得現有產品目錄陣列
        $pds = $this->mymodel->Writesql('select d_id, TID, TTID, TTTID, d_model from products');
        $count = 0;
        $t1 = $t2 = $t3 = array();
        $sqlData = array();
        foreach($pds as $k => $v){
            $i = 0;
            
            if($v['TID'] != '') {
                $t1 = explode('@#', $v['TID']);
                if(!empty($t1)){
                    //echo "<BR>測試1<BR>";
                    foreach($t1 as $k1 => $v1){
                        if( !in_array($v1, $pTypeArr)) {
                            unset($t1[$k1]);
                            $i++;
                        }
                    } 
                    $sqlData['TID'] = implode('@#', $t1);
                }
            }

            if($v['TTID'] != '' && $v['TTID'] != 0) {
                $t2 = explode('@#', $v['TTID']);
                //var_dump($v['TTID'] == 0);
                if(!empty($t2)){
                    //echo "<BR>測試2<BR>";
                    foreach($t2 as $k2 => $v2){
                        if( !in_array($v2, $pTypeArr)) {
                            unset($t2[$k2]);
                            $i++;
                        }
                    } 
                    $sqlData['TTID'] = implode('@#', $t2);
                }
            }

            if($v['TTTID'] != '' && $v['TTTID'] != 0) {
                $t3 = explode('@#', $v['TTTID']);
                if(!empty($t3)){
                    //echo "<BR>測試3<BR>";
                    foreach($t3 as $k3 => $v3){
                        if( !in_array($v3, $pTypeArr)) {
                            unset($t3[$k3]);
                            $i++;
                        }
                    }
                    $sqlData['TTTID'] = implode('@#', $t3);
                }
            }


            if( $i != 0) {
                echo "<BR>校正產品id:$v[d_id] model:$v[d_model] →<BR>";
                // echo "<BR>原始陣列 →<BR>";
                // p($v);
                // echo "<BR>更新陣列 →<BR>";
                // p($sqlData);
                $this->db->where('d_id', $v['d_id'])->update('products', $sqlData);
                echo $this->db->last_query();
                $count ++;


            }
            $sqlData = array();
        }
        echo "<BR>共計更新 產品 $count 筆<BR>";

    }

}
