<?php
class Autoful
{
    protected $CI;
    public $cf = array(),
    $header = '',
    $menu = '',
    $AEDurl = '',
    $SqlList = '',
    $where = '';
    public function __construct($cf = array())
    {
        $this->CI = &get_instance();
        $this->cf = $cf;
        $this->CI->load->model('MyModel/Webmodel', 'webmodel');
        $this->CI->load->helper('form');
        
    }
    //前台基本設定
    public function FrontConfig($chkIogin = '')
    {
        // 檢查登入
        if (!empty($chkIogin)) {
            if (!empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y') {
                $Chk_member = $this->CI->mymodel->OneSearchSql('member', 'd_pname', array('d_id' => $_SESSION[CCODE::MEMBER]['LID']));
                if (empty($Chk_member)) {
                    $this->CI->useful->AlertPage('login/logout', '此會員已不存在！');
                    exit();
                }
            } else {
                unset($_SESSION[CCODE::MEMBER]);
                $this->CI->useful->AlertPage('login', '請先登入會員！');
            }
        }
        // 會員ID
        $this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        // 會員分類牽動產品顯示
        // 一般用戶-售價
        // 企業用戶-會員價
        // 企業用戶有勾選-沙龍價 以等級去做折扣
        // 會員類型
        $this->UserType = (!empty($_SESSION[CCODE::MEMBER]['UserType']) ? $_SESSION[CCODE::MEMBER]['UserType'] : '');
        // 分類
        $this->Mtype = (!empty($_SESSION[CCODE::MEMBER]['Mtype']) ? $_SESSION[CCODE::MEMBER]['Mtype'] : '1');
        // 次要營業類別
        $this->Mtype1 = (!empty($_SESSION[CCODE::MEMBER]['Mtype1']) ? $_SESSION[CCODE::MEMBER]['Mtype1'] : '');
        // 會員等級牽動產品價格
        $this->Mlv = (!empty($_SESSION[CCODE::MEMBER]['Mlv']) ? $_SESSION[CCODE::MEMBER]['Mlv'] : 1);
        $Mlv = $this->Mlv;
        $UpLvID = ($Mlv == 7) ? array('7') : array($Mlv, $Mlv + 1);
        $Lvdata = $this->CI->mymodel->WriteSql('select d_title from member_lv where d_id in (' . implode(',', $UpLvID) . ') order by d_id');
        $this->Lvtitle = $Lvdata[0]['d_title'];
        $this->UpLvtitle = (!empty($Lvdata[1]['d_title']) ? $Lvdata[1]['d_title'] : '');

        // 會員人數
        $MTotal = $this->CI->mymodel->WriteSql('select count(d_id) as cdid from member where d_enable="Y"', '1');
        $this->MTotal = (!empty($MTotal) ? number_format($MTotal['cdid']) : 0);
        //  商品總數
        $PTotal = $this->CI->mymodel->WriteSql('select count(d_id) as cdid from products where d_enable="Y"', '1');
        $this->PTotal = (!empty($PTotal) ? number_format($PTotal['cdid']) : 0);
        //全站分類
        $Pdata = $this->CI->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where d_enable="Y" and TID=0', 'd_sort asc,d_create_time desc');
        foreach ($Pdata as $value) {
            $Sdata = $this->CI->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where TID="' . $value['d_id'] . '" and TTID=0 and d_enable="Y"', 'd_sort asc,d_create_time desc');
            if (!empty($Sdata)) {
                foreach ($Sdata as $key => $svalue) {
                    $Subdata = $this->CI->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where TTID="' . $svalue['d_id'] . '" and d_enable="Y"', 'd_sort asc,d_create_time desc');
                    if (!empty($Subdata)) {
                        $Sdata[$key]['Subdata'] = $Subdata;
                    }
                }
                $SideMenu[$value['d_title'] . '_' . $value['d_id']] = $Sdata;
            }
        }
        // print_r($this->Mtype);
        // print_r($SideMenu);
        $this->SideMenu = $SideMenu;

        if (!empty($this->Mid)) {
            // 下一級的會員
            $this->Next_lv = $this->CI->mymodel->OneSearchSql('member_lv', 'd_title', array('d_id' => $this->Mlv + 1));
            $dbdata = $this->CI->mymodel->WriteSQL('
               select m.d_pname,m.d_upgrade_total,m.d_upgrade_date,m.d_chked,m.TID
               from member as m
               left join member_lv as lv on lv.d_id = "' . $this->Mlv . '"
               where m.d_id = "' . $this->Mid . '"'
               , '1');
            // 期限內訂單總數計算
            // $this->last_money = $this->GetLastMoney($dbdata['d_upgrade_date'], $dbdata['d_deadline'], $dbdata['d_upgrade'], $dbdata['d_upgrade_total']);
            // 會員資訊
            $this->member_info = $dbdata;
            //print_r($this->member_info['d_chked']);
        }

        // 購物車
        $Cart = (!empty($_COOKIE['BeautyCart']) ? $_COOKIE['BeautyCart'] : '');
        $this->CI->load->library('Mylib/Cartful', 'cartful');
        $this->CI->cartful->GetCart($Cart);
        // 數量
        $this->CartNum = $this->CI->cartful->CartNum;
        // 產品
        $this->ProductCart = array_merge($this->CI->cartful->Order['Cart'], $this->CI->cartful->Order['TrialData']);
        $this->CartTotal = $this->CI->cartful->Order['Total'];
        $this->CartBonus = $this->CI->cartful->Order['BonusTotal'];
        // 一般運費
        $this->OneFreight = $this->CI->cartful->Order['OneFreight'];
    }
    // 根據會員等級顯示金額
    public function GetProductPrice($Pdata = '')
    {
        // print_r($Pdata);
        $Mtype = explode(',', str_replace('@#', ',', $this->Mtype));
        $Lvcount=1;
        if($this->Mlv>=4){
         $MlvData = $this->CI->mymodel->WriteSql('select d_count from member_lv where d_id='.$this->Mlv.'', '1');
         $Lvcount=(100-$MlvData['d_count'])/100;
     }
        // echo $Lvcount;

     foreach ($Pdata as $key => $value) {
            // 是否夠資格
        $Chked = 'Y';

        $TID = implode(',', explode('@#', $value['TID']));
        $TID = implode(',', array_filter(explode(',', $TID)));
        $TypeData = $this->CI->mymodel->WriteSql('select GROUP_CONCAT(MTID) as MTID from products_type where d_id in(' . $TID . ')', '1');
        $TypeData = array_unique(explode(',', str_replace('@#', ',', $TypeData['MTID'] . ',' . $value['MTID'])));
        $result = array_intersect($TypeData, $Mtype);

        if($this->UserType==1 or empty($this->UserType) or $this->member_info['d_chked']==2){
            $Pdata[$key]['d_price'] = $value['d_price1'];
            $Pdata[$key]['Lvtitle']='會員價';
            $Chked = 'N';
        } else {
                // 20210818-新增功能
            $Mtype1=explode('@#',$this->Mtype.'@#'.$this->Mtype1);
            $Mtype1=array_unique($Mtype1);
            
                // $Mtype1=explode('@#',$this->Mtype1);
            $MTID=$value['MTID'];
            $TypeData1=explode('@#',$MTID);
            $result1 = array_intersect ($TypeData1, $Mtype1);
            
            if(count($result1)!=0){
                $Pdata[$key]['d_price'] = $value['d_price3']*$Lvcount;
                $Pdata[$key]['Lvtitle']=$this->Lvtitle;
            }else{
                $Pdata[$key]['d_price'] = $value['d_price2'];
                $Pdata[$key]['Lvtitle']='會員價';
            }
        }

        $Pdata[$key]['Discount'] = 0;
            // 是否有活動
        $Discount = $this->ChkSingleSale(array($value['d_id'], 0), $Pdata[$key]['d_price']);
            // print_r($Discount);
        if (!empty($Discount)) {
            $Pdata[$key]['d_price'] = $this->DiscountData[$value['d_id']]['d_price'];
            $Pdata[$key]['Discount'] = $this->DiscountData[$value['d_id']]['type'];
        }

        $Pdata[$key]['Chked'] = $Chked;
    }
        // print_r($Pdata);
    return $Pdata;
}

    // 是否有特價商品活動
public function ChkSingleSale($cart, $price)
{
    $Ddata = $this->CI->mymodel->WriteSql('
       select s.d_id,d.d_num,s.d_num as d_maxnum,s.d_price,s.d_type,s.d_title,t.d_getbonus
       from products_sale s
       left join products_sale_type t on t.d_id=s.TID
       left join products_sale_detail d on d.SID=s.d_id
       where (s.PID like "' . $cart[0] . '@#%" or s.PID like "%@#' . $cart[0] . '" or s.PID like "%@#' . $cart[0] . '@#%" or s.PID=' . $cart[0] . ')
       and s.d_enable="Y" and t.d_start<="' . date('Y-m-d') . '" and t.d_end>="' . date('Y-m-d') . '" and t.d_start!="" and t.d_end!="" and t.d_enable="Y" and d.PID=' . $cart[0] . ' and d.d_enable="Y"
       ', '1');

        // 沒有特價
    if (empty($Ddata)) {
        return array();
        exit();
        } else if ($Ddata['d_type'] == 1) { //折扣
            $price = floor($price * $Ddata['d_price'] / 100);
        } else if ($Ddata['d_type'] == 2) { //金額
            if ($this->Mlv == 1) { // 一般會員*1.2
                $Ddata['d_price'] = round($Ddata['d_price'] * 1.2);
            }
            if ($price < $Ddata['d_price']) { // 特價比會員本身購買價還貴，不納入特價
                //2021-10-05 Bis. 影響圖標顯示
                //return array();exit();
                $Ddata['d_price'] = $price; // 顯示會員價
                
            }
            $price = $Ddata['d_price'];
        } else {
            $this->useful->AlertPage('cart', '購物車內部分商品特價有誤，請重新選擇特價商品！');
            exit();
        }

        // 特價可購買量
        $CanBuy = $Ddata['d_maxnum'];

        // 限定組數
        if ($Ddata['d_maxnum'] != 0) {
            $CanBuy = $Ddata['d_maxnum'] - $Ddata['d_num'];
            if ($CanBuy <= 0) { // 特價已無，回歸原價
                return array();
                exit();
            } else if ($CanBuy < $cart[1]) { // 購買數量大於可特價數
                $cart[1] = $CanBuy;
            }
        }

        $Sdata = array(
            'd_id' => $Ddata['d_id'],
            'd_price' => $price,
            'd_title' => $Ddata['d_title'],
            'num' => $cart[1],
            'maxnum' => $CanBuy,
            'GetBonus' => $Ddata['d_getbonus'],
            'type' => $Ddata['d_type'],
            'type_price' => $Ddata['d_price'],
        );

        $this->DiscountData[$cart[0]] = $Sdata;

        return $cart;
    }

    //後台基本設定
    public function backconfig($Usetype = '1')
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // 各專案後臺資料夾
        $FileName = $this->CI->webmodel->BaseConfig();
        $this->FileName = $FileName['d_title'];
        $this->Jur = !empty($_SESSION[CCODE::ADMIN]['Jur']) ? $_SESSION[CCODE::ADMIN]['Jur'] : '';
        $this->JurDBName = 'admin_jur';
        $this->MenuName = !empty($_SESSION[CCODE::ADMIN]['Aacc']) ? $_SESSION[CCODE::ADMIN]['Aacc'] : '';

        if (empty($_SESSION[CCODE::ADMIN]['Aid'])) {
            $this->CI->useful->AlertPage('/' . $this->FileName . '/index/logout', '連線逾時，請重新登入');
        }

        //列表
        $this->menu = $this->GetMenu($Usetype);
        // print_r($this->CI->tableful->MenuidDb);
        //標題
        $header = $this->CI->webmodel->BaseConfig('2');
        $this->header = $header['d_title'];
        //管理台內頁圖片
        $Logo = $this->CI->webmodel->BaseConfig(4);
        $this->Logo = $Logo['d_title'];

        // 跳頁
        $this->PageNum = !empty($_SESSION[CCODE::ADMIN]['PageNum']) ? $_SESSION[CCODE::ADMIN]['PageNum'] : '';
        unset($_SESSION[CCODE::ADMIN]['PageNum']);
    }
    //  期限內訂單總數計算
    private function GetLastMoney($UpgradeDate, $deadline, $lastmoney, $d_upgrade_total = 0)
    {
        $deaddate = date('Y-m-d', strtotime("+" . $deadline . " day", strtotime($UpgradeDate)));
        $Odata = $this->CI->mymodel->WriteSQL('
            select sum(d_total) as total
            from orders
            where MID="' . $this->Mid . '" and d_orderstatus=4 and (d_create_time between "' . $UpgradeDate . '" and "' . $deaddate . '")
            ', 1);

        if (!empty($Odata['total'])) {
            $lastmoney -= $Odata['total'];
        }
        $today = date('Y-m-d');
        if (strtotime($today) < strtotime($deaddate)) {
            $lastmoney -= $d_upgrade_total;
        }
        return $lastmoney;
    }
    //列表設定
    public function GetMenu()
    {
        $EditView=$DID = $sdata = array();
        $cdata = $this->CI->webmodel->GetMenu();
        if (!empty($cdata)) {
            // 權限設定
            $jurdata = $this->GetJur();
            foreach ($cdata as $ckey => $cvalue) {
                $dbdata = $this->CI->webmodel->GetMenuList($cvalue['d_id']);

                foreach ($dbdata as $dkey => $dvalue) {
                    // if($this->Jur==15){
                    if (!empty($jurdata[$dvalue['d_jur']])) {
                        if($jurdata[$dvalue['d_jur']]!=1){
                            $sub[] = array(
                                'd_jur' => $dvalue['d_jur'],
                                'd_ctitle' => $dvalue['d_ctitle'],
                                'd_link' => $dvalue['d_link'],
                                'd_id' => (!empty($Extraid)) ? $Extraid : $dvalue['d_id'],
                                'EditView'=>$jurdata[$dvalue['d_jur']]
                            );
                        }
                        $EditView[$dvalue['d_jur']]=$jurdata[$dvalue['d_jur']];
                    }
                        // print_r($sub);
                    // }else{
                    //     if (in_array($dvalue['d_jur'], $jurdata)) {
                    //         $sub[] = array(
                    //             'd_jur' => $dvalue['d_jur'],
                    //             'd_ctitle' => $dvalue['d_ctitle'],
                    //             'd_link' => $dvalue['d_link'],
                    //             'd_id' => (!empty($Extraid)) ? $Extraid : $dvalue['d_id'],
                    //         );

                    //     }
                    // }

                    $DID[] = $dvalue['d_id'];
                    $Extraid = '';
                }
                // print_r($DID);
                if (!empty($sub)) {
                    $DID = implode('!@#', $DID);
                    $sdata[$cvalue['d_title'] . '_' . $cvalue['d_icon'] . '_' . $DID] = $sub;
                }
                $DID = $sub = array();
            }
            
            if(!empty($EditView) and !empty($this->CI->tableful->MenuidDb['d_jur'])){
                $NowJur=$this->CI->tableful->MenuidDb['d_jur'];
                // echo $NowJur;
                if(in_array($NowJur,array('j_pstype','j_pssstype'))){
                    $NowJur='j_ptype';
                }
                if(in_array($NowJur,array('j_sale'))){
                    $NowJur='j_sale_type';
                }
                if(in_array($NowJur,array('j_trial'))){
                    $NowJur='j_trial_type';
                }
                $this->EditView=$EditView[$NowJur];
            }else{
                $this->EditView=2;
            }

            return $sdata;
        }
    }

    // 權限設定
    private function GetJur()
    {

        $jurdata = $this->CI->mymodel->OneSearchSql($this->JurDBName, 'd_jur', array('d_id' => $this->Jur));
        // if($this->Jur==15){
        $Jur=json_decode($jurdata['d_jur'],true);
            // print_r($Jur);y
        return $Jur;
        // }else
        //     return explode(',', $jurdata['d_jur']);
    }
    //權限設定專用
    public function GetJurList($Usetype, $where = '')
    {
        $cdata = $this->CI->webmodel->GetMenu('', '', $Usetype);
        if (!empty($cdata)) {
            foreach ($cdata as $ckey => $cvalue) {
                $dbdata = $this->CI->webmodel->GetMenuList($cvalue['d_id'], $where);

                foreach ($dbdata as $dkey => $dvalue) {

                    $sub[] = array(
                        'd_jur' => $dvalue['d_jur'],
                        'd_ctitle' => $dvalue['d_ctitle'],
                        'd_dbname' => $dvalue['d_dbname'],
                        'd_link' => $dvalue['d_link'],
                        'd_id' => (!empty($Extraid)) ? $Extraid : $dvalue['d_id'],
                    );
                    $Extraid = '';
                }
                $sdata[$cvalue['d_title']] = $sub;
                $sub = array();
            }
            return $sdata;
        }
    }

    //創建qrcode
    public function qrcode_produce($path, $data, $image_name, $correct = 'L', $size = '4', $version = '0', $repeat = '0')
    {
        $this->CI->load->library('Qrcode');
        $this->CI->qrcode->clear();
        $this->CI->qrcode->set_file_path($path);
        $this->CI->qrcode->set_data($data);
        $this->CI->qrcode->set_error_correct($correct);
        $this->CI->qrcode->set_module_size($size);
        $this->CI->qrcode->set_version($version);
        $qrcode_file = $this->CI->qrcode->build($image_name, $repeat);
        return $path . $qrcode_file;
    }

    //上傳函式
    public function DefaultUpload($FILE = '', $Config = array())
    {
        /*
        Fname=>欄位名稱
        Filename=>檔案
        tmp=>是否有縮圖
        r_width=>圖片比例寬度 (可空值為原圖)
        r_height=>圖片比例高度 (可空值為原圖)
        Souce=> 空值為原圖
        LimitSize=> 空值為不限制 單位為byte

        $Config=array(
        'Fname'=>'d_img',
        'Filename'=>$this->Filename,

        );
         */
        $this->CI->load->library('up_image');
        // 預覽圖
        if ($FILE[$Config['Fname']]['name']) {
            if (!empty($Config['LimitSize'])) {
                if ($_FILES[$Config['Fname']]['size'] > $Config['LimitSize']) {
                    echo '<script>alert("圖片已超過限制大小，請重新上傳!");history.go(-1);</script>';
                    exit();
                }
            }

            if ($FILE[$Config['Fname']]['type'] != 'image/png' and $FILE[$Config['Fname']]['type'] != 'image/jpeg' and $FILE[$Config['Fname']]['type'] != 'image/gif') {
                echo '<script>alert("預覽圖檔案格式錯誤，請上傳jpg or png or gif副檔名");history.go(-1);</script>';
                exit();
            }
            if (!empty($_POST['' . $Config['Fname'] . '_ImgHidden']) and empty($Config['Nodel'])) {
                $ImgStr = explode('/', $_POST['' . $Config['Fname'] . '_ImgHidden']);
                $Tmp = './' . $ImgStr[0] . '/' . $ImgStr[1] . '/tmp/tmp_' . $ImgStr[2];
                if (is_file($Tmp)) {
                    unlink($Tmp);
                }

                unlink('./' . $_POST['' . $Config['Fname'] . '_ImgHidden']);
            }
            $path = './uploads/' . $Config['Filename'] . '/'; //路徑
            $this->CI->useful->create_dir($path);
            // 撈取副檔名
            $Subname = explode('/', $FILE[$Config['Fname']]['type']);
            $imgtype = $Subname[1];

            $icon = $this->CI->up_image->uploadimage($FILE[$Config['Fname']], date('YmdHis') . rand(0, 9999) . '.' . $imgtype . '', $path, $Config);
            sleep(0.5);

            if (empty($icon['error'])) {
                $_POST[$Config['Fname']] = substr($icon['path'], 2);
            } else {
                echo '<script>alert("' . $icon['error'] . '");history.go(-1);</script>';
                exit();
            }

        }
    }
    // 檔案上傳
    public function DefaultUploadDoc($FILE = '', $Config = array())
    {
        /*
        Fname=>欄位名稱
        Filename=>檔案

        $Config=array(
        'Fname'=>'d_img',
        'Filename'=>$this->Filename,
        'AllowedExts'=>array()
        );
         */
        if ($_FILES[$Config['Fname']]['name']) {
            $this->CI->load->library('up_image');
            $path = './uploads/' . $Config['Filename'] . '/'; //路徑
            $this->CI->useful->create_dir($path);
            $_FILES[$Config['Fname']]['path'] = $path;

            $icon = $this->CI->up_image->uploadDoc($_FILES[$Config['Fname']], $Config['AllowedExts']);

            if (!empty($icon['error'])) {
                echo '<script>alert("' . $icon['error'] . '");history.go(-1);</script>';
                exit();
            } else {
                $_POST[$Config['Fname']] = substr($icon['path'], 2);
            }

        }

    }
    // 匯入EXCEL
    public function ImportExcel($file, $Fname, $Filename)
    {
        // $text=$this->autoful->ImportExcel($_FILES,'SpecFile','Spec');
        // array_shift($text);
        $Config = array(
            'Fname' => $Fname,
            'Filename' => $Filename,
            'AllowedExts' => '',
        );
        $this->DefaultUploadDoc($file, $Config);
        $FilePath = $_POST[$Fname];
        // print_r($FilePath);
        // 載入PHPExcel類庫
        $this->CI->load->library('PHPExcel');
        $this->CI->load->library('PHPExcel/IOFactory');
        $objPHPExcel = IOFactory::load($FilePath);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        unlink($FilePath);
        return $sheetData;
    }
    // 計算紅利
    public function CountBonus($BonusArray, $BounsCount, $ReturnArray = array(), $GetDiscount = false)
    {
        $AllBonus = 0;
        $DiscountArray = array(); // 折扣後金額陣列
        if ($BounsCount > 0 || $GetDiscount) {
            // %數排序
            krsort($BonusArray);
            foreach ($BonusArray as $percent => $Ptotal) {
                // 金額排序 大至小
                arsort($Ptotal);
                foreach ($Ptotal as $PID => $total) {
                    // 退貨商品略過不計算
                    if (empty($ReturnArray) || !in_array($PID, $ReturnArray)) {
                        if ($BounsCount >= $total) {
                            $DiscountArray[$PID] = $total; // 折扣後金額
                            $BounsCount -= $total;
                            $AllBonus += floor($total * ($percent / 100));
                        } else {
                            if ($BounsCount < 0) {
                                $BounsCount = 0;
                            }
                            $DiscountArray[$PID] = $BounsCount; // 折扣後金額
                            $AllBonus += floor(($BounsCount) * ($percent / 100));
                            $BounsCount = 0;
                        }
                    }
                }
            }
        }
        if ($GetDiscount) {
            return array('bonus' => $AllBonus, 'discount' => $DiscountArray);
        } else {
            return $AllBonus;
        }
    }
    // 歸還紅利
    public function ReBackBonus($reBack = '0', $MID = '0', $OID = '0')
    {
        $this->CI->mymodel->SimpleWriteSQL('update member set d_bonus=d_bonus+' . $reBack . ' where d_id=' . $MID);

        $GetPoint = $this->CI->db->select('d_id,d_deduct,d_create_time')->like('d_deduct', '"' . $OID . '":')->get('member_point')->result_array();
        foreach ($GetPoint as $detail) {

            $deduct_detail = json_decode($detail['d_deduct'], true);

            $Sdata = array(
                'MID' => $MID,
                'OID' => $OID,
                'd_type' => '1',
                'd_num' => ($deduct_detail[$OID] > $reBack) ? $reBack : $deduct_detail[$OID],
                'd_content' => '退還紅利',
                'd_total' => ($deduct_detail[$OID] > $reBack) ? $reBack : $deduct_detail[$OID],
                'd_create_date' => date('Y-m-d'),
            );

            $reBack -= $deduct_detail[$OID];

            $dbdata = $this->CI->useful->DB_Array($Sdata, '', '', '1');
            $dbdata['d_create_time'] = $detail['d_create_time'];

            $this->CI->mymodel->InsertData('member_point', $dbdata);
            if ($reBack <= 0) {
                break;
            }
        }
    }
    // 庫存回沖
    public function ReStock($d_id)
    {
        $oddata = $this->CI->mymodel->SelectSearch('orders_detail', '', 'PID,d_num', 'where OID=' . $d_id);
        foreach ($oddata as $v) {
            $this->CI->mymodel->SimpleWriteSQL('update products set d_stock=d_stock+' . $v['d_num'] . ' where d_id=' . $v['PID'] . '');
        }
    }
    //upload to S3
    public function addImages($FILE = '', $Config = array())
    {       
        $this->CI->load->library('Mylib/S3_upload');
        $this->CI->load->library('Mylib/S3');
        if ($FILE[$Config['Fname']]['name']) {
            if (!empty($Config['LimitSize'])) {
                if ($_FILES[$Config['Fname']]['size'] > $Config['LimitSize']) {
                    echo '<script>alert("圖片已超過限制大小，請重新上傳!");history.go(-1);</script>';
                    exit();
                }
            }

            if ($FILE[$Config['Fname']]['type'] != 'image/png' and $FILE[$Config['Fname']]['type'] != 'image/jpeg' and $FILE[$Config['Fname']]['type'] != 'image/gif') {
                echo '<script>alert("預覽圖檔案格式錯誤，請上傳jpg or png or gif副檔名");history.go(-1);</script>';
                exit();
            }
            if (!empty($_POST['' . $Config['Fname'] . '_ImgHidden']) and empty($Config['Nodel'])) {
                $ImgStr = explode('/', $_POST['' . $Config['Fname'] . '_ImgHidden']);
                $Tmp = './' . $ImgStr[0] . '/' . $ImgStr[1] . '/tmp/tmp_' . $ImgStr[2];
                if (is_file($Tmp)) {
                    unlink($Tmp);
                }

                //unlink('./' . $_POST['' . $Config['Fname'] . '_ImgHidden']);
            }
            $path = 'uploads/' . $Config['Filename'] . '/'; //路徑
            $this->CI->useful->create_dir($path);
            // 撈取副檔名
            $Subname = explode('/', $FILE[$Config['Fname']]['type']);
            $imgtype = $Subname[1];

            //$icon = $this->CI->up_image->uploadimage($FILE[$Config['Fname']], date('YmdHis') . rand(0, 9999) . '.' . $imgtype . '', $path, $Config);
            //sleep(0.5);
            $icon = $this->CI->s3_upload->upload_file($FILE[$Config['Fname']], date('YmdHis') . rand(0, 9999) . '.' . $imgtype . '', $path, $Config);  
            sleep(0.5);
            if (!empty($icon['error'])) {
                echo '<script>alert("' . $icon['error'] . '");</script>';
                exit();
            }   else {
                $_POST[$Config['Fname']] = $icon;
                //return $icon;
            }         

        }

    }


}

