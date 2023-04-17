<?php
defined('BASEPATH') or exit('No direct script access allowed');
/** 購物系統
 *
 */
class Cart extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // 前台共用
        $this->autoful->FrontConfig();
        // 網頁標題
        $this->NetTitle = '購物車';
        // 會員等級
        $this->Mlv = $this->autoful->Mlv;
        // 會員ID
        $this->Mid = $this->autoful->Mid;
    }

    // Step1 首頁
    public function Index()
    {
        $cart = $this->chkCart();
        $data['CartProduct'] = $this->cartful->Order;
        // print_r($data['CartProduct']);
        // 加價購，載入加購產品
        $Notid = (!empty($_SESSION[CCODE::MEMBER]['AddData']) ? ' and d_id not in (' . $_SESSION[CCODE::MEMBER]['AddData'] . ')' : ''); //排除現已在購物車內的加購商品
        $Mdata = $this->mymodel->SelectSearch('products_markup', '', 'd_id,d_title,d_img,d_aprice,d_price', 'where d_enable="Y" and d_aprice<=' . $data['CartProduct']['AllTotal'] . ' and d_stock>0 ' . $Notid . '', 'd_aprice desc');
        $data['Mdata'] = $Mdata;

        $this->load->view('front/cart', $data);
    }
    // 購物車登入頁面
    public function cart_login()
    {
        $this->chkCart();
        if (!empty($_SESSION[CCODE::MEMBER]['IsLogin'])) {
            $this->useful->AlertPage('cart/cart_payment', '');
            exit();
        }
        $this->load->view('front/cart_login', array());
    }
    // Step2 贈品選擇頁
    public function cart_payment()
    {
        $cart = $this->chkCart();
        $data['CartProduct'] = $this->cartful->Order;
        $this->chkCart_alert($data['CartProduct']);
        // 滿額贈品
        $data['Gdata'] = $this->chkGift($data['CartProduct']['AllTotal']);
        if (!empty($data['Gdata']) && !empty($this->Mid)) {
            $this->load->view('front/cart_payment', $data);
        } else {
            $this->useful->AlertPage('cart/cart_information', '');
            exit();
        }

    }
    // Step3 訂單資訊填寫
    public function cart_information()
    {
        // 購物車資訊
        $cart = $this->chkCart();
        $data['CartProduct'] = $this->cartful->Order;
        $this->chkCart_alert($data['CartProduct']);
        // 滿額贈
        if (!empty($_POST['d_gift'])) {
            $data['Gdata'] = $this->chkGift($data['CartProduct']['AllTotal'], 'and d_id in (' . implode(',', $_POST['d_gift']) . ')', count($_POST['d_gift']));
            $_SESSION[CCODE::MEMBER]['GiftData'] = !empty($data['Gdata']) ? array_column($data['Gdata'], 'd_id') : array();
        }
        // 運送方式
        $data['Ldata'] = $Ldata = $this->mymodel->SelectSearch('logistics', '', 'd_id,d_title', 'where d_enable="Y"');
        // 付款方式
        $data['Pdata'] = $Pdata = $this->mymodel->SelectSearch('cashflow', '', 'd_id,d_title,d_content', 'where d_enable="Y"');
        // 捐贈發票
        $data['Idata'] = $this->mymodel->SelectSearch('invoice', '', 'd_id,d_title', 'where d_enable="Y"');
        // 發票類型
        $data['ITtypedata'] = $ITtypedata = $this->mymodel->GetConfig(9);
        // 部門(自建訂單-客服用)
        $data['Department'] = $this->mymodel->SelectSearch('department', '', 'd_id,d_code,d_title', 'where d_enable="Y"', 'd_sort asc');
        // 會員資訊
        if (!empty($this->Mid)) {
            $Mdata = $this->mymodel->OneSearchSql('member',
                'd_user_type,d_pname,d_company_title,d_company_tel,d_phone,d_county,d_district,d_zipcode,d_address,d_account,d_company_county,d_company_district,d_company_zipcode,d_company_address,d_bonus,d_company_number'
                , array('d_id' => $this->Mid));
            $MArray = array(
                'd_pname' => $Mdata['d_pname'],
                'd_phone' => $Mdata['d_phone'],
                'd_account' => $Mdata['d_account'],
                'd_county' => ($Mdata['d_user_type'] == 1) ? $Mdata['d_county'] : $Mdata['d_company_county'],
                'd_district' => ($Mdata['d_user_type'] == 1) ? $Mdata['d_district'] : $Mdata['d_company_district'],
                'd_zipcode' => ($Mdata['d_user_type'] == 1) ? $Mdata['d_zipcode'] : $Mdata['d_company_zipcode'],
                'd_address' => ($Mdata['d_user_type'] == 1) ? $Mdata['d_address'] : $Mdata['d_company_address'],
                'd_company_title' => ($Mdata['d_user_type'] == 1) ? '' : $Mdata['d_company_title'],
                'd_company_tel' => ($Mdata['d_user_type'] == 1) ? '' : $Mdata['d_company_tel'],
                'd_company_number' => ($Mdata['d_user_type'] == 1) ? '' : $Mdata['d_company_number'],
                'd_bonus' => $Mdata['d_bonus'],
            );
            $data['Mdata'] = $MArray;
            // 預設資料
            $data['Preset_send'] = $this->mymodel->OneSearchSql('member_send', 'd_cname as d_company_title,d_name as d_pname,d_mobile as d_phone,d_phone as d_company_tel,d_city as d_county,d_area as d_district,d_zip as d_zipcode,d_address', array('MID' => $this->Mid, 'd_preset' => 'Y'));
            $data['Preset_invoice'] = $this->mymodel->OneSearchSql('member_invoice', 'd_cname as d_company_title,d_um as d_company_number,d_mail as d_account,d_city as d_county,d_area as d_district,d_zip as d_zipcode,d_address', array('MID' => $this->Mid, 'd_preset' => 'Y'));
        }

        $data['post'] = $_POST;

        $this->load->view('front/cart_information', $data);
    }
    // 寫入訂單
    public function Addorder()
    {
        $cart = $this->chkCart();

        $OrderData = $_POST;
        if (empty($OrderData['d_backagree']) || $OrderData['d_backagree'] != 'Y') {
            $this->useful->AlertPage('', '請勾選同意由台灣美麗平台股份有限公司代為處理退貨相關內容。');
            exit();
        }
        $Subbonus = (!empty($OrderData['SubBonus']) ? $OrderData['SubBonus'] : '');
        // 紀錄用-表單折抵紅利
        $_SESSION[CCODE::MEMBER]['SubBonus'] = $Subbonus;
        // 檢查表單
        $this->ChkData($OrderData);
        // 檢查商品資訊+選配+加價
        $this->cartful->GetCart($cart, $Subbonus); // 帶入折抵紅利
        $CartProduct = $this->cartful->Order;
        $this->chkCart_alert($CartProduct);
        // 離島另收
        $Freight = $CartProduct['Freight'] + $CartProduct['BigFreight'] + ($OrderData['d_logistics'] == 2 ? $CartProduct['Outisland'] : 0);
        // 訂單總計 需+離島運費
        $CartProduct['AllTotal'] = $CartProduct['Total'] + $Freight - $Subbonus;
        // 檢查紅利
        $subbonus = $this->chkBonus($Subbonus, $CartProduct['Total'], $Freight);
        if ($subbonus['Status'] != 'OK') {
            $this->useful->AlertPage('', $subbonus['Status']);
            exit();
        }
        // 檢查贈品
        if (!empty($_SESSION[CCODE::MEMBER]['GiftData'])) {
            $GiftData = $this->chkGift($CartProduct['AllTotal'], 'and d_id in (' . implode(',', $_SESSION[CCODE::MEMBER]['GiftData']) . ')', count($_SESSION[CCODE::MEMBER]['GiftData']));
        }

        // 寫入訂單流程
        $date = date('Ymd');
        $Odata = $this->mymodel->WriteSql('select OID from orders where SUBSTRING(OID,1,8)=' . $date . '  order by OID desc limit 0,1', '1');
        $OID = !empty($Odata) ? $Odata['OID'] + 1 : $date . '0001';

        if (empty($this->Mid)) {
            $Mdata = $this->mymodel->OneSearchSql('member', 'd_id', array('d_account' => $OrderData['d_email']));
            if (!empty($Mdata['d_id'])) {
                $this->useful->AlertPage('', '此信箱帳號已有註冊，請洽管理人員');
                exit();
            }

            $post['d_chked'] = 4; // 會員審核.
            $post['d_password'] = $OrderData['d_moblie'];
            $dbdata = $this->useful->DB_Array($post, '', '', '1');
            // 會員代碼
            $Mdata = $this->mymodel->WriteSql('select substr(d_mcode,-6) as d_mcode from member order by d_id desc limit 0,1', '1');

            if (!empty($Mdata)) {
                $Mcode = 'BG' . substr('000000' . ($Mdata['d_mcode'] + 1), -6);
            } else {
                $Mcode = 'BG000001';
            }

            $dbdata['d_mcode'] = $Mcode;
            //加密
            $this->load->library('encryption');
            $dbdata['d_password'] = $this->encryption->encrypt($post['d_password']);
            $dbdata['d_newsletter'] = 'N'; // 電子信
            $dbdata['d_lv'] = 1; // 會員等級
            $dbdata['d_pname'] = $OrderData['d_name']; // 會員姓名
            $dbdata['d_account'] = $OrderData['d_email'];
            $dbdata['d_county'] = $OrderData['d_city'];
            $dbdata['d_district'] = $OrderData['d_area'];
            $dbdata['d_zipcode'] = $OrderData['d_zip'];
            $dbdata['d_address'] = $OrderData['d_address'];
            $dbdata['SID'] = '10';

            $this->mymodel->InsertData('member', $dbdata);
            // 寄驗證信給帳號人員
            $this->SendVri($dbdata['d_account']);
            $_SESSION[CCODE::MEMBER]['TempoLID'] = $LID = $this->mymodel->create_id;
        } else {
            $LID = $this->Mid;
        }

        // 寫入主訂單
        $AddOrder = array(
            'OID' => $OID,
            'MID' => $LID,
            'd_price' => $CartProduct['Total'],
            'd_freight' => $CartProduct['Freight'],
            'd_bigfreight' => $CartProduct['BigFreight'],
            'd_outisland' => $OrderData['d_logistics'] == 2 ? $CartProduct['Outisland'] : 0,
            'd_usebonus' => $CartProduct['Subbonus'],
            'd_total' => $CartProduct['AllTotal'],
            'd_bonus' => $CartProduct['BonusTotal'],
            'd_pay' => $OrderData['d_pay'],
            'd_logistics' => $OrderData['d_logistics'],
            'd_invoice' => $OrderData['d_invoice'],
            'd_cname' => $OrderData['d_cname'],
            'd_name' => $OrderData['d_name'],
            'd_moblie' => $OrderData['d_moblie'],
            'd_phone' => $OrderData['d_phone'],
            'd_mail' => $OrderData['d_email'],
            'd_zip' => $OrderData['d_zip'],
            'd_city' => $OrderData['d_city'],
            'd_area' => $OrderData['d_area'],
            'd_address' => $OrderData['d_address'],
            'd_content' => $OrderData['d_content'],
            'd_donate' => $OrderData['d_donate'],
            'd_othername' => $OrderData['d_othername'],
            'd_icname' => $OrderData['d_icname'],
            'd_ium' => $OrderData['d_ium'],
            // 'd_imail' => $OrderData['d_imail'],
            'd_imail' => (!empty($_SESSION[CCODE::MEMBER]['LEmail']) ? $_SESSION[CCODE::MEMBER]['LEmail'] : $OrderData['d_email']), // 改為會員登入時，抓會員帳號  非會員，抓帳號欄位
            'd_Invoicecity' => $OrderData['d_Invoicecity'],
            'd_Invoicearea' => $OrderData['d_Invoicearea'],
            'd_Invoicezip' => $OrderData['d_Invoicezip'],
            'd_iaddress' => $OrderData['d_iaddress'],
            'd_backagree' => $OrderData['d_backagree'],
            'd_admin' => (!empty($_SESSION[CCODE::MEMBER]['NoBonus']) ? "Y" : "N"),
            'd_bonusarr' => json_encode($_SESSION['BonusArray']),
            'd_create_time' => $this->useful->get_now_time(),
            'd_update_time' => $this->useful->get_now_time(),
            'd_edit_ip' => $this->useful->get_ip(),
            'd_orderstatus' => ($CartProduct['Special'] == true) ? 10 : (($OrderData['d_pay'] == 2) ? 9 : 1),
            'd_department' => (!empty($OrderData['d_department']) ? $OrderData['d_department'] : 0),
            'd_adminaccount' => (!empty($_SESSION[CCODE::MEMBER]['Admin']) ? $_SESSION[CCODE::MEMBER]['Admin'] : ''),
        );

        $this->mymodel->InsertData('orders', $AddOrder);
        $NewID = $this->mymodel->create_id;

        empty($NewID) ? $this->useful->AlertPage('index', '訂單成立失敗，請重新下單') : '';

        foreach ($CartProduct['Cart'] as $key => $value) {
            $SAID = $value['IsSale'] ? $this->autoful->DiscountData[$value['d_id']]['d_id'] : '0'; // 促銷ID
            $AddorderDetail = array(
                'OID' => $NewID,
                'PID' => $value['d_id'],
                'SAID' => $SAID,
                'd_title' => $value['d_title'],
                'd_img' => $value['d_img'],
                'd_num' => $value['num'],
                'd_price' => $value['d_price'],
                'd_total' => $value['d_total'],
                'd_model' => $value['d_model'],
                'd_pfreight_lv' => $value['d_pfreight_lv'],
                'd_pfreight' => $OrderData['d_logistics'] == 2 ? $value['d_poutisland'] + $value['d_pfreight'] : $value['d_pfreight'],
                'd_addtitle' => (!empty($value['AddData']['AddTitle']) ? $value['AddData']['AddTitle'] : ''),
                'd_addprice' => (!empty($value['AddData']['AddPrice']) ? $value['AddData']['AddPrice'] : ''),
                'd_addid' => (!empty($value['AddData']['Addid']) ? $value['AddData']['Addid'] : ''),
                'd_create_time' => $this->useful->get_now_time(),
                'd_update_time' => $this->useful->get_now_time(),
                'd_edit_ip' => $this->useful->get_ip(),
            );
            $this->mymodel->InsertData('orders_detail', $AddorderDetail);
            // 扣除庫存 & 選配庫存
            $this->SubStock('products', $value['num'], $value['d_id']);
            $this->SaleStock('products_sale_detail', $value['num'], $value['d_id'], $SAID);

            if (!empty($value['AddData'])) {
                $this->SubStock('products_optional', $value['num'], $value['AddData']['Addid']);
            }
        }

        // 加價購紀錄
        if (!empty($CartProduct['AddData'])) {
            foreach ($CartProduct['AddData'] as $key => $value) {
                $AddMakeupDetail = array(
                    'OID' => $NewID,
                    'MID' => $value['d_id'],
                    'd_title' => $value['d_title'],
                    'd_img' => $value['d_img'],
                    'd_aprice' => $value['d_aprice'],
                    'd_price' => $value['d_price'],
                    'd_create_time' => $this->useful->get_now_time(),
                    'd_update_time' => $this->useful->get_now_time(),
                    'd_edit_ip' => $this->useful->get_ip(),
                );
                $this->mymodel->InsertData('orders_makeup_detail', $AddMakeupDetail);
                // 扣除加價購庫存
                $this->SubStock('products_markup', 1, $value['d_id']);
            }
            unset($_SESSION[CCODE::MEMBER]['AddData']);
        }
        // 贈品紀錄
        if (!empty($GiftData)) {
            foreach ($GiftData as $key => $value) {
                $AddGiftDetail = array(
                    'OID' => $NewID,
                    'GID' => $value['d_id'],
                    'd_title' => $value['d_title'],
                    'd_img' => $value['d_img'],
                    'd_create_time' => $this->useful->get_now_time(),
                    'd_update_time' => $this->useful->get_now_time(),
                    'd_edit_ip' => $this->useful->get_ip(),
                );
                $this->mymodel->InsertData('orders_gift_detail', $AddGiftDetail);
                // 扣除贈品庫存
                $this->SubStock('products_gift', 1, $value['d_id']);
            }
            unset($_SESSION[CCODE::MEMBER]['GiftData']);
        }
        // 試用品紀錄
        if (!empty($CartProduct['TrialData'])) {
            foreach ($CartProduct['TrialData'] as $key => $value) {
                $AddTrialDetail = array(
                    'OID' => $NewID,
                    'TID' => $value['d_id'],
                    'MID' => $LID,
                    'd_title' => $value['d_title'],
                    'd_img' => $value['d_img'],
                    'd_model' => $value['d_model'],
                    'd_enable' => 'Y',
                    'd_deadline' => date('Y-m-d', strtotime("+" . $value['d_days'] . " days")),
                    'd_create_time' => $this->useful->get_now_time(),
                    'd_update_time' => $this->useful->get_now_time(),
                    'd_edit_ip' => $this->useful->get_ip(),
                );
                $this->mymodel->InsertData('orders_trial_detail', $AddTrialDetail);
                // 扣除試用品庫存
                $this->SubStock('products_trial', 1, $value['d_id']);
            }
            unset($_SESSION[CCODE::MEMBER]['TrialData']);
        }

        unset($_SESSION[CCODE::MEMBER]['SubBonus']);
        unset($_SESSION['BonusArray']);

        setcookie("BeautyCart", "", time() - 43200, '/');
        if ($OrderData['d_pay'] != 2) { // 不是刷卡的，先扣紅利
            $CartProduct['Subbonus'] != 0 ? $this->SubBouns($CartProduct['Subbonus'], $OID) : '';
        }
        // 訂單內含有特殊運費之商品，先訂單保留，待管理者評估完運費
        if ($CartProduct['Special'] == true) {
            $this->useful->AlertPage('cart/order_completed/' . $OID . '', '您好，訂購的商品中包含特殊運費商品，因此訂單尚未建立完成。\\n待運費報價後，方可繼續進行付款作業。\\n現在為您導向訂單詳細頁面。');
            exit();
        } else if ($OrderData['d_pay'] == 2 && $CartProduct['AllTotal'] > 0) { // id=2  刷卡
            $config['lidm'] = $OID;
            $config['purchAmt'] = $CartProduct['AllTotal'];
            $config['AuthResURL'] = base_url('pay_result');
            $this->load->library('Cash_flow', $config);
            // 傳送至金流
            $data = $this->cash_flow->creditCard_getForm();
            echo $data;
            exit();
        } else if ($OrderData['d_pay'] == 4 && $CartProduct['AllTotal'] > 0) { // id=4 , WebATM
            $config['lidm'] = $OID;
            $config['purchAmt'] = $CartProduct['AllTotal'];
            $this->load->library('Cash_flow', $config);
            // 傳送至金流
            $Account = $this->cash_flow->webATM();
            $this->mymodel->UpdateData('orders', array('d_webatm' => $Account), ' where d_id=' . $NewID . '');

            $Message = $this->load->view('front/_webatm', array('account' => $Account, 'total' => $CartProduct['AllTotal']), true);

            $this->tableful->Sendmail($OrderData['d_email'], '美麗平台訂單-WebATM轉帳資訊', $Message);
        }
        // 假設整筆訂單全以紅利折抵完，直接狀態變更為已付款
        if ($CartProduct['AllTotal'] == 0) {
            $this->mymodel->UpdateData('orders', array('d_paystatus' => 2, 'd_orderstatus' => 1), ' where d_id=' . $NewID . '');
        }
        $this->useful->AlertPage('cart/order_completed/' . $OID . '', '訂單建立成功，將導向詳細頁');
    }
    // 完成訂單
    public function order_completed($OrderNum = '')
    {
        if (!empty($OrderNum)) {
            $data = array();
            $Odata = $this->mymodel->OneSearchSql('orders', '*', array('OID' => $OrderNum));

            if (!empty($Odata) and ($Odata['MID'] == $this->Mid or $Odata['MID'] == $_SESSION[CCODE::MEMBER]['TempoLID'])) {
                $data['Detaildata'] = $Detaildata = $this->mymodel->WriteSQL('select od.*,s.d_title as Stitle from orders_detail od left join products_sale s on s.d_id=od.SAID where OID=' . $Odata['d_id']);
                // 加價購
                $data['Adddata'] = $Adddata = $this->mymodel->SelectSearch('orders_makeup_detail', '', '*', 'where OID=' . $Odata['d_id'] . '');
                // 試用品
                $data['Trialdata'] = $Trialdata = $this->mymodel->SelectSearch('orders_trial_detail', '', '*', 'where OID=' . $Odata['d_id'] . '');
                // 贈品
                $data['Giftdata'] = $Giftdata = $this->mymodel->SelectSearch('orders_gift_detail', '', '*', 'where OID=' . $Odata['d_id'] . '');
                // 物流方式
                $data['Paystatus'] = $this->mymodel->OneSearchSql('logistics', 'd_title,d_content', array('d_id' => $Odata['d_logistics']));
                // 金流
                $data['Cashflow'] = $this->mymodel->OneSearchSql('cashflow', 'd_title', array('d_id' => $Odata['d_pay']));
                // 發票類型
                $data['ITtypedata'] = $ITtypedata = $this->mymodel->GetConfig(9);
                // 訂單狀態
                $data['Orders_status'] = $this->mymodel->GetConfig('10', 'and d_enable="Y"');
                // 付款狀態
                $data['Pay_status'] = $this->mymodel->GetConfig('11', 'and d_enable="Y"');
                $data['Odata'] = $Odata;
                $this->load->view('front/order_completed', $data);
            } else {
                $this->useful->AlertPage('index', '操作錯誤');
                exit();
            }
        } else {
            $this->useful->AlertPage('index', '操作錯誤');
            exit();
        }
    }
    // 檢查購物車內有無商品
    private function chkCart()
    {
        if (empty($_COOKIE['BeautyCart']) && empty($_SESSION[CCODE::MEMBER]['TrialData'])) {
            $this->useful->AlertPage('index', '購物車無任何產品');
            exit();
        }
        return !empty($_COOKIE['BeautyCart']) ? $_COOKIE['BeautyCart'] : array();
    }
    // 檢查購物車商品及選配庫存
    private function chkCart_alert($data)
    {
        if ($data['Chkpay'] == 'N') {
            $this->useful->AlertPage('cart', '購物車內有部分商品庫存已不足，請刪除後再繼續！');
            exit();
        } else if ($data['Chkop'] == 'N') {
            $this->useful->AlertPage('cart', '購物車內部分選配商品庫存已不足，請刪除後重新選擇選配商品！');
            exit();
        } else if ($data['ChkTri'] == 'N') {
            $this->useful->AlertPage('cart', '購物車內部分試用品庫存已不足，請刪除後再繼續！');
            exit();
        } else if ($data['ChkTriHad'] == 'N') {
            $this->useful->AlertPage('cart', '購物車內部分試用品已領取過，請重新選擇試用品！');
            exit();
        }
    }
    // 檢查贈品
    private function chkGift($Total, $whereSql = '', $num = '')
    {
        $Gdata = $this->mymodel->WriteSQL('select d_id,PID,d_type,d_img,d_title from products_gift where d_price<=' . $Total . ' and d_enable = "Y" and d_stock > 0 ' . $whereSql);
        if (!empty($num) && count($Gdata) != $num) {
            $this->useful->AlertPage('cart/cart_payment', '購物車內部分贈品已無庫存，請重新選擇贈品！');
            exit();
        }
        foreach ($Gdata as $key => $value) {
            // 是否有由產品資料來的
            if ($value['d_type'] == 2) {
                $Pdata = $this->mymodel->OneSearchSql('products', 'd_img1,d_title', array('d_id' => $value['PID']));
                if (!empty($Pdata)) {
                    $Gdata[$key]['d_title'] = $Pdata['d_title'];
                    $Gdata[$key]['d_img'] = $Pdata['d_img1'];
                } else {
                    unset($Gdata[$key]);
                }
            }
        }
        return $Gdata;
    }

    // 紅利檢查
    private function chkBonus($Bonus, $AllTotal, $freight)
    {
        if ($Bonus < 0) {
            $data['Status'] = '紅利折扣不得為負';
            return $data;
        }
        $Mdata = $this->mymodel->OneSearchSql('member', 'd_bonus', array('d_id' => $this->Mid));
        if ($Bonus > $Mdata['d_bonus']) {
            $data['Status'] = '您輸入的紅利已超過擁有的數量,請重新輸入,謝謝!';
            return $data;
        }
        $Total = ($AllTotal + $freight) - $Bonus;
        if ($Total < 0) {
            $data['Status'] = '您輸入的紅利已超過總計金額,請重新輸入,謝謝!';
            return $data;
        }
        $data['Subbonus'] = number_format(($Total));
        $data['Status'] = 'OK';
        return $data;
    }

    // 各條件判定
    private function ChkData($post)
    {
        $Chkarray = array(
            '商品運送方式' => 'd_logistics',
            '付款方式' => 'd_pay',
            '收貨人姓名' => 'd_name',
            '手機號碼' => 'd_moblie',
            'E-mail' => 'd_email',
            '城市' => 'd_city',
            '鄉鎮' => 'd_area',
            '區碼' => 'd_zip',
            '地址' => 'd_address',
        );
        if (!empty($_SESSION[CCODE::MEMBER]['NoBonus'])) {
            $Chkarray['部門'] = 'd_department';
        }

        if (!empty($post['d_invoice'])) {
            if ($post['d_invoice'] == '2') {
                $Chkarray += array(
                    '捐贈機關/團體' => 'd_donate',
                );
            }
            if ($post['d_invoice'] == '3') {
                $Chkarray += array(
                    '公司戶電子發票-統一編號' => 'd_ium',
                    // '公司戶電子發票-E-mail' => 'd_imail', // 改為會員登入時，抓會員帳號  非會員，抓帳號欄位，無須在前台顯示
                    '公司戶電子發票-城市' => 'd_Invoicecity',
                    '公司戶電子發票-鄉鎮' => 'd_Invoicearea',
                    '公司戶電子發票-區碼' => 'd_Invoicezip',
                    '公司戶電子發票-地址' => 'd_iaddress',
                );
            }
        }

        $err = '';
        foreach ($Chkarray as $key => $value) {
            if (empty($post[$value])) {
                $err .= $key . '需必填\n';
            }

        }
        if (!empty($err)) {
            $this->useful->AlertPage('', $err);
            exit();
        }
    }
    // 寄驗證信給帳號人員
    private function SendVri($Account = '')
    {
        if (!empty($Account)) {
            $url = site_url('/login/Cheackaccount?') . $this->useful->encrypt('acc=' . $Account . '&type=2', 'jddtshin');
            $Message = "請點選下面連結已完成驗證:<br><a href='" . $url . "' target='_blank'>" . $url . "</a><br>謝謝！";
            $this->tableful->Sendmail($Account, '美麗平台會員-會員驗證信', $Message);
        }
    }
    // 扣除會員紅利
    private function SubBouns($bonus, $OID)
    {
        $Sdata = array(
            'MID' => $this->Mid,
            'OID' => $OID,
            'd_type' => '2',
            'd_num' => $bonus,
            'd_content' => '扣抵訂單',
            'd_create_date' => date('Y-m-d'),
        );

        $dbdata = $this->useful->DB_Array($Sdata, '', '', '1');

        $this->mymodel->InsertData('member_point', $dbdata);
        $this->mymodel->SimpleWriteSQL('update member set d_bonus=d_bonus-' . $bonus . ' where d_id=' . $this->Mid . '');
        // 撈取快過期的點數
        $this->DetailSub($bonus, $OID);
    }
    // 撈取快過期的點數
    private function DetailSub($bonus, $OID)
    {
        $Edata = $this->mymodel->WriteSQL('
            select d_id,d_total,d_deduct from member_point where MID=' . $this->Mid . ' and d_type=1 and d_enable="Y" and d_total>0 order by d_create_time
        ', '1');
        if (!empty($Edata['d_total'])) {
            $Subbonus = $Edata['d_total'] - $bonus;
            $deduct = json_decode($Edata['d_deduct'], true);
            if ($Subbonus < 0) {
                $deduct[$OID] = $Edata['d_total'];
                $udata = array(
                    'd_total' => 0,
                    'd_deduct' => json_encode($deduct),
                );
                $this->mymodel->UpdateData('member_point', $udata, ' where d_id=' . $Edata['d_id'] . '');
                $this->DetailSub(abs($Subbonus));
            } else {
                $deduct[$OID] = $bonus;
                $udata = array(
                    'd_total' => $Subbonus,
                    'd_deduct' => json_encode($deduct),
                );
                $this->mymodel->UpdateData('member_point', $udata, ' where d_id=' . $Edata['d_id'] . '');
            }
        }
    }
    // 扣除庫存
    private function SubStock($table, $num, $id)
    {
        $this->mymodel->SimpleWriteSQL('update ' . $table . ' set d_stock=d_stock-' . $num . ' where d_id=' . $id . '');
        // $Pdata = $this->mymodel->OneSearchSql($table, 'd_stock', array('d_id' => $id));
        // if ($Pdata['d_stock'] <= 0) {
        //     $this->mymodel->SimpleWriteSQL('update ' . $table . ' set d_enable="N" where d_id=' . $id . '');
        // }
    }
    // 促銷組數
    public function SaleStock($table, $num, $id, $SAID)
    {
        $this->mymodel->SimpleWriteSQL('update ' . $table . ' set d_num=d_num+' . $num . ' where PID=' . $id . ' and SID=' . $SAID . ' and d_enable="Y"');
    }
    // AJAX運送方式 判斷是否為離島 加收運費
    public function ChangeSend()
    {
        $id = $_POST['id'];
        $subBonus = $_POST['subBonus'];
        $outisland = $_POST['outisland'];
        $freight = $_POST['freight'];
        $logistics = $this->mymodel->OneSearchSql('logistics', 'd_title', array('d_id' => $id, 'd_enable' => 'Y'));
        if (!empty($logistics)) {
            if ($id == 2) {
                $freight += $outisland;
            } else {
                $outisland = 0;
            }
            echo json_encode(array('status' => 'success', 'freight' => $freight, 'Addfreight' => $outisland));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }
    // AJAX-更改購物車數量
    public function ChangeCart()
    {
        $numArr = $_POST['numArr'];
        $Arr = array();
        foreach ($numArr as $v) {
            $Search = array_column($Arr, 3);
            if (in_array($v[0] . '@#' . $v[2], $Search)) {
                $key = array_search($v[0] . '@#' . $v[2], $Search);
                $Arr[$key][1] += $v[1];
            } else {
                $v[3] = $v[0] . '@#' . $v[2];
                array_push($Arr, $v);
            }
        }

        foreach ($Arr as $ck => $cart) {
            $products = $this->mymodel->OneSearchSql('products', 'd_stock', array('d_id' => $cart[0], 'd_enable' => "Y"));
            if (!empty($products)) {
                $cart[1] = $products['d_stock'] < $cart[1] ? $products['d_stock'] : $cart[1];
                unset($cart[3]);
                $Arr[$ck] = implode('@#', $cart);
            } else {
                unset($Arr[$ck]);
            }
        }

        $Arr = array_values($Arr);
        $BeautyCart = implode(';', $Arr);

        setcookie("BeautyCart", "", time() - 43200, '/');
        setcookie("BeautyCart", $BeautyCart, time() + 43200, '/');
    }
    // AJAX-刪除購物車
    public function RemoveCart()
    {
        $id = $_POST['id'];
        $num = $_POST['num'];
        if (!empty($_COOKIE['BeautyCart'])) {
            $cart = explode(';', $_COOKIE['BeautyCart']);
            $Cdata = explode('@#', $cart[$id]);
            if ($Cdata[1] > $num && $num != 0) {
                $Cdata[1] -= $num;
                $cart[$id] = implode('@#', $Cdata);
            } else {
                unset($cart[$id]);
            }
            setcookie("BeautyCart", "", time() - 43200, '/');

            $cart = array_values($cart);
        }
        $cart = implode(';', $cart);
        setcookie("BeautyCart", $cart, time() + 43200, '/');
    }
    // AJAX-更改購物車數量-加購價
    public function ChangeAddCart()
    {
        $id = $_POST['id'];
        $AddCart = (!empty($_SESSION[CCODE::MEMBER]['AddData']) ? $_SESSION[CCODE::MEMBER]['AddData'] : '');
        if (!empty($AddCart)) {
            $AddCart = $AddCart . ',' . $id;
        } else {
            $AddCart = $id;
        }
        $_SESSION[CCODE::MEMBER]['AddData'] = $AddCart;
    }
    // AJAX-刪除購物車-加購價
    public function RemoveAddCart()
    {
        if (!empty($_POST['id']) && $this->input->is_ajax_request()) {
            $this->cartful->RemoveCart('AddData', $_POST['id']);
        }
    }
    // AJAX-刪除購物車-試用品
    public function RemoveTrialCart()
    {
        if (!empty($_POST['id']) && $this->input->is_ajax_request()) {
            $this->cartful->RemoveCart('TrialData', $_POST['id']);
        }
    }
    // AJAX-紅利計算
    public function BonusOperation()
    {
        $Subbonus = $_POST['Bonus'];
        $Total = $_POST['Total'];
        $BonusArray = $_SESSION['BonusArray'];
        $freight = (!empty($_POST['freight']) ? $_POST['freight'] : 0);
        if ($Subbonus != "") {
            $data = $this->chkBonus($Subbonus, $Total, $freight);
            if ($data['Status'] == 'OK') {
                $data['BonusTotal'] = $this->autoful->CountBonus($BonusArray, $Total - $Subbonus);
            }
            echo json_encode($data);
        }
    }
    // 撈取收件人資料
    public function GetSend()
    {
        if (!empty($_POST['id'])) {
            $Sdata = $this->mymodel->OneSearchSql('member_send', 'd_id,d_cname,d_name,d_mobile,d_phone,d_city,d_area,d_zip,d_address', array('d_id' => $_POST['id']));
            echo json_encode($Sdata);
        } else {
            $Sdata = $this->mymodel->SelectSearch('member_send', '', 'd_id,d_cname,d_name,d_mobile,d_phone,d_city,d_area,d_zip,d_address,d_preset', 'where MID=' . $this->Mid . '');
            $html = '';
            foreach ($Sdata as $key => $value) {
                $html .= '<ul>
                  <li style="width:170px"><a class="btn-style10" href="javascript: void(0)" id="PresetSend" rel="' . $value['d_id'] . '" >' . ($value['d_preset'] == 'N' ? '預設' : '取消預設') . '</a><a class="btn-style10" href="javascript: void(0)" id="PostSend" rel="' . $value['d_id'] . '">寄送給</a></li>
                  <li>' . $value['d_name'] . '</li>
                  <li>' . $value['d_zip'] . ' ' . $value['d_city'] . $value['d_area'] . $value['d_address'] . '</li>
                  <li>
                    <a class="btn-style10 fancybox" href="#add_info02" id="EditSend" rel="' . $value['d_id'] . '">修改</a>
                    <a class="btn-style10" href="javascript: void(0)" id="DelSend" rel="' . $value['d_id'] . '">刪除</a>
                  </li>
                </ul>';
            }
            echo $html;
        }
    }
    // 寫入/修改收件人資料
    public function AddSend()
    {
        $Postarray = $_POST['Postarray'];
        $idata = array(
            'MID' => $this->Mid,
            'd_cname' => (!empty($Postarray[0]) ? $Postarray[0] : ''),
            'd_name' => (!empty($Postarray[1]) ? $Postarray[1] : ''),
            'd_mobile' => (!empty($Postarray[2]) ? $Postarray[2] : ''),
            'd_phone' => (!empty($Postarray[3]) ? $Postarray[3] : ''),
            'd_city' => (!empty($Postarray[4]) ? $Postarray[4] : ''),
            'd_area' => (!empty($Postarray[5]) ? $Postarray[5] : ''),
            'd_zip' => (!empty($Postarray[6]) ? $Postarray[6] : ''),
            'd_address' => (!empty($Postarray[7]) ? $Postarray[7] : ''),
        );
        if (!empty($_POST['d_id'])) {
            $this->mymodel->UpdateData('member_send', $idata, ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
            echo '修改成功';
        } else {
            $msg = $this->mymodel->InsertData('member_send', $idata);
            if (!empty($msg)) {
                echo '新增成功';
            } else {
                echo '新增失敗';
            }
        }
    }
    // 預設收件人資料
    public function PresetSend()
    {
        if (!empty($_POST['d_id'])) {
            $Preset = $this->mymodel->OneSearchSql('member_send', 'd_preset', array('d_id' => $_POST['d_id'], 'MID' => $this->Mid));
            if ($Preset['d_preset'] == 'N') {
                $this->mymodel->UpdateData('member_send', array('d_preset' => 'N'), ' where MID=' . $this->Mid . '');
                $this->mymodel->UpdateData('member_send', array('d_preset' => 'Y'), ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
                echo 'Y';
            } else {
                $this->mymodel->UpdateData('member_send', array('d_preset' => 'N'), ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
            }
        }
    }
    // 刪除收件人資料
    public function DelSend()
    {
        if (!empty($_POST['d_id'])) {
            $this->mymodel->DelectData('member_send', ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
            echo '刪除成功';
        }
    }
    // 撈取統編備忘錄
    public function GetInvoice()
    {
        if (!empty($_POST['id'])) {
            $Sdata = $this->mymodel->OneSearchSql('member_invoice', 'd_id,d_cname,d_um,d_mail,d_city,d_area,d_zip,d_address', array('d_id' => $_POST['id']));
            echo json_encode($Sdata);
        } else {
            $Sdata = $this->mymodel->SelectSearch('member_invoice', '', 'd_id,d_cname,d_um,d_preset', 'where MID=' . $this->Mid . '');
            $html = '';
            foreach ($Sdata as $key => $value) {
                $html .= '<ul>
                  <li style="width:170px"><a class="btn-style10" href="javascript: void(0)" id="PresetInvoice" rel="' . $value['d_id'] . '" >' . ($value['d_preset'] == 'N' ? '預設' : '取消預設') . '</a><a class="btn-style10" href="javascript: void(0)" id="PostInvoice" rel="' . $value['d_id'] . '">寄送給</a></li>
                  <li>' . $value['d_cname'] . '</li>
                  <li>' . $value['d_um'] . '</li>
                  <li>
                    <a class="btn-style10 fancybox" href="#invoice_info02" id="EditInvoice" rel="' . $value['d_id'] . '">修改</a>
                    <a class="btn-style10" href="javascript: void(0)" id="DelInvoice" rel="' . $value['d_id'] . '">刪除</a>
                  </li>
                </ul>';
            }
            echo $html;
        }
    }
    // 寫入/修改統編備忘錄
    public function AddInvoice()
    {
        $Postarray = $_POST['Postarray'];
        $idata = array(
            'MID' => $this->Mid,
            'd_cname' => (!empty($Postarray[0]) ? $Postarray[0] : ''),
            'd_um' => (!empty($Postarray[1]) ? $Postarray[1] : ''),
            'd_mail' => (!empty($_SESSION[CCODE::MEMBER]) ? $_SESSION[CCODE::MEMBER]['LEmail'] : ''),
            'd_city' => (!empty($Postarray[3]) ? $Postarray[3] : ''),
            'd_area' => (!empty($Postarray[4]) ? $Postarray[4] : ''),
            'd_zip' => (!empty($Postarray[5]) ? $Postarray[5] : ''),
            'd_address' => (!empty($Postarray[6]) ? $Postarray[6] : ''),
        );
        if (!empty($_POST['d_id'])) {
            $this->mymodel->UpdateData('member_invoice', $idata, ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
            echo '修改成功';
        } else {
            $msg = $this->mymodel->InsertData('member_invoice', $idata);
            if (!empty($msg)) {
                echo '新增成功';
            } else {
                echo '新增失敗';
            }
        }
    }
    // 預設統編備忘錄
    public function PresetInvoice()
    {
        if (!empty($_POST['d_id'])) {
            $Preset = $this->mymodel->OneSearchSql('member_invoice', 'd_preset', array('d_id' => $_POST['d_id'], 'MID' => $this->Mid));
            if ($Preset['d_preset'] == 'N') {
                $this->mymodel->UpdateData('member_invoice', array('d_preset' => 'N'), ' where MID=' . $this->Mid . '');
                $this->mymodel->UpdateData('member_invoice', array('d_preset' => 'Y'), ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
                echo 'Y';
            } else {
                $this->mymodel->UpdateData('member_invoice', array('d_preset' => 'N'), ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
            }
        }
    }
    // 刪除統編備忘錄
    public function DelInvoice()
    {
        if (!empty($_POST['d_id'])) {
            $this->mymodel->DelectData('member_invoice', ' where d_id=' . $_POST['d_id'] . ' and MID=' . $this->Mid . '');
            echo '刪除成功';
        }
    }
    // 寄回覆信
    private function Sendmail()
    {
        $Cdata = $this->webmodel->BaseConfig(1);

        $Subject = $Cdata['d_title'] . '-新訂單通知信';
        $Message = '有新的訂單，請至後台管理查看!';

        $Mailarray = $this->webmodel->BaseConfig(3);
        $Mail = $Mailarray['d_title'];

        $this->tableful->Sendmail($Mail, $Subject, $Message);
    }
    // 加密
    private function encryptStr($str, $key)
    {
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $enc_str = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        return base64_encode($enc_str);
    }
    // 解密
    private function decryptStr($str, $key)
    {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }
}
?>
