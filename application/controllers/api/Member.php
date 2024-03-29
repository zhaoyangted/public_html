<?php

defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/RestController.php';
//require_once 'Format.php';

use Restserver\Libraries\RestController;

/**
 * Description of RestGetController
 *
 * @author https://roytuts.com
 */
class Member extends RestController
{

	function __construct()
	{
        /* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
        header("Access-Control-Allow-Credentials: true");
		parent::__construct();
		//$this->load->database();
		$this->load->model('MyModel/Webmodel', 'webmodel');
		$this->autoful->FrontConfig('1');
        $this->load->library('form_validation');
        $this->Mid = $this->autoful->Mid;
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
    public function index_get()
    {
        $this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        $data = array(
            'dbdata' => $this->autoful->member_info, // 會員資訊
            'Member_type' => !empty($this->autoful->Mtype) ? $this->mymodel->SelectSearch('member_type', '', 'd_title', 'where d_id in (' . implode(',', explode('@#', $this->autoful->Mtype)) . ')', 'd_sort') : '', //會員分類
            'Orders_total' => $this->mymodel->WriteSql('select count(d_id) as total from orders where MID="' . $this->Mid . '"', '1'), //訂單總數
        );
        $this->Mlv = (!empty($_SESSION[CCODE::MEMBER]['Mlv']) ? $_SESSION[CCODE::MEMBER]['Mlv'] : 1);
        $Mlv = $this->Mlv;
        $UpLvID = ($Mlv == 7) ? array('7') : array($Mlv, $Mlv + 1);
        $Lvdata = $this->mymodel->WriteSql('select d_title from member_lv where d_id in (' . implode(',', $UpLvID) . ') order by d_id');
        $this->Lvtitle = $Lvdata[0]['d_title'];
        $this->UpLvtitle = (!empty($Lvdata[1]['d_title']) ? $Lvdata[1]['d_title'] : '');
        $data['Lvtitle']=$this->Lvtitle;
        $data['UpLvtitle']=$this->UpLvtitle;
        $this->NetTitle = '會員中心';
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->response(NULL,404);
        }
        //$this->load->view('front/member', $data);
    }
    
    // 會員補齊資料頁
    public function review_get()
    {
        $this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        
        $data = array(
            'Member_rules' => $this->mymodel->GetCkediter(3),
            'Member_types' => $this->mymodel->SelectSearch('member_type', '', 'd_id,d_title', 'where d_enable="Y"', 'd_sort'),
            'Member_user_types' => $this->mymodel->GetConfig('6', 'and d_enable="Y"'),
            'Member_company_types' => $this->mymodel->GetConfig('4', 'and d_enable="Y"'),
            'Member_operate_types' => $this->mymodel->GetConfig('5', 'and d_enable="Y"'),

        );
        $data['Mdata'] = $this->mymodel->OneSearchSql('member', 'd_account', array('d_id' => $this->Mid));
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->response(NULL,404);
        }
        $this->NetTitle = '會員資料修改';
        //$this->load->view('front/member_review', $data);
    }
    // $page => 頁面，$id => 訂單ID
    public function checkaccount_post()
    {
        $post = $this->input->post(null, true);
        //print_r($post);
        if ($this->form_validation->run('account') == true) { // 表單驗證

             //會員修改
                if ($_SESSION[CCODE::MEMBER]['VcodeNum'] != $post['d_captcha']) {
                    $msg='驗證碼輸入錯誤';
                    $this->response(['msg'=>$msg],404);
                    exit();
                }


                $dbdata = $this->useful->DB_Array($post, '1', '', '1');
                    $dbdata['d_newsletter'] = (!empty($post['d_newsletter']) ? 'Y' : 'N'); // 電子信
                    $dbdata = $this->useful->UnsetArray($dbdata, array('d_repassword', 'd_password', 'd_captcha'));
                    if (!empty($post['d_password'])) { //有修改密碼才加密新密碼
                        $this->load->library('encryption');
                        $dbdata['d_password'] = $this->encryption->encrypt($post['d_password']);
                    }

                    $msg = $this->mymodel->UpdateData('member', $dbdata, 'where d_id =' . $this->Mid);
                    if ($msg){
                            $this->response($dbdata,200);
                        }else{
                            $this->response(['msg'=>'修改失敗，請重新輸入'],404);
                        }
            
            } else {
                $this->form_validation->set_error_delimiters('', '\n');
                $this->response(['msg'=>validation_errors()],404);
                //$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
            }
        }
    // 邀請好友
    public function checkafriend_post()
    {
        $this->load->library('encryption');
        while (1) { // 無窮迴圈檢查亂碼是否重複
            $dbdata['d_Fcode'] = $this->encryption->encrypt($this->Mid);
            $query = $this->mymodel->OneSearchSql('member_friend', 'd_id', array('d_Fcode' => $dbdata['d_Fcode']));
            if (empty($query)) { //不重複即跳出迴圈
                break;
            }
        }
        $dbdata['MID'] = $this->Mid;
        $dbdata = $this->useful->DB_Array($dbdata, '', '', '1');
        $msg = $this->mymodel->InsertData('member_friend', $dbdata);
        if ($msg) {
            $CTitle = $this->webmodel->BaseConfig('6'); // 公司名稱
            $Subject = $_SESSION[CCODE::MEMBER]['LName'] . "邀請您加入" . $CTitle['d_title'] . "會員";
            $Message = "您好！<br><br>
            您的朋友 " . $_SESSION[CCODE::MEMBER]['LName'] . "<br><br>
            邀請您加入 " . $CTitle['d_title'] . "的會員<br><br>
            以下是您的註冊邀請網址 <a href='" . site_url('login/join?F=' . $dbdata['d_Fcode']) . "'>點選我前往註冊</a><br><br>
            進入以上網址進行註冊，且完成第一次購物，您的朋友將獲得紅利回饋 ";
            $this->tableful->Sendmail($this->post('d_Femail'), $Subject, $Message);
            $this->response(['msg'=>'您已成功寄發邀請給您的好友！'],200);
            //$this->useful->AlertPage('member', '您已成功寄發邀請給您的好友！');
        } else {
            $this->response(['msg'=>'寄發邀請失敗，請重新輸入！'],404);
            //$this->useful->AlertPage('', '寄發邀請失敗，請重新輸入！');
        }
    }
    
    // 註冊寫入
    public function review_register_post()
    {

        $post = $this->input->post();

        if (empty($post['chkok'])) {
            $this->useful->AlertPage('', '請勾選我已詳細閱讀<會員條款>');
            exit();
        }

        if ($this->form_validation->run('register') == true) {
            $this->_chk_Captcha($post['d_captcha']);
            $post['d_chked'] = 2; // 會員審核
            if ($post['d_user_type'] == 2) {
                $post['d_chked'] = 2; // 會員審核
                $post['d_operate_service'] = json_encode($post['d_operate_service'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // 服務項目
                $post['TID'] = implode('@#', $post['TID']); // 會員分類
            }

            $dbdata = $this->useful->DB_Array($post, '', '', '1');
            //加密
            $this->load->library('encryption');
            $dbdata['d_password'] = $this->encryption->encrypt($post['d_password']);
            $dbdata['d_newsletter'] = (!empty($post['d_newsletter']) ? 'Y' : 'N'); // 電子信
            $dbdata['d_lv'] = 1; // 會員等級

            $dbdata = $this->useful->UnsetArray($dbdata, array('d_repassword', 'chkok', 'd_captcha', 'd_review'));

            if (!empty($this->mymodel->UpdateData('member', $dbdata, ' where d_id=' . $this->Mid . ''))) {
                $this->useful->AlertPage('member', '修改成功，將交由管理者進行審核');
            } else {
                $this->useful->AlertPage('member', '修改失敗');
            }
        } else {
            $this->form_validation->set_error_delimiters('', '\n');
            $this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
            exit();
        }
    }
    // 會員修改
    public function account_get()
    {
        $this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        
        $dbdata = $this->mymodel->WriteSQL('
          select m.*,con.d_title
          from member as m
          left join product_config as con on con.d_type = 6 and con.d_val = m.d_user_type
          where m.d_id = "' . $this->Mid . '"', '1');

        // if ($dbdata['d_chked'] == 1) {
        //     $this->useful->AlertPage('member/review', '請先補齊資料，謝謝');
        //     exit();
        // }

        $data = array(
            'dbdata' => $dbdata, // 會員資訊
        );
        $this->NetTitle = '會員資料修改';
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->response(NULL,404);
        }
        //$this->load->view('front/member_account', $data);
    }
    // 我的收藏
    public function favorite_get()
    {
        $data = array();
        if (empty($_SESSION[CCODE::MEMBER]['LID'])){
            $this->response(NULL,200,['msg'=>'not login']);
            exit();
        }
        //$this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        $dbdata = $this->mymodel->FrontSelectPage('member_favorite', '*', 'where MID = ' . $this->Mid . '', 'd_create_time desc', '5');
        if (!empty($dbdata['dbdata'])) {
            $data['AID'] = array_column($dbdata['dbdata'], 'AID', 'PID');
            // join選配，將收藏商品資訊以及選配選項帶出
            $dbdata['dbdata'] = $this->mymodel->WriteSQL('
               select GROUP_CONCAT(pro_add.d_id) as d_add_id,GROUP_CONCAT(pro_add.d_title) as d_add_title,GROUP_CONCAT(pro_add.d_price) as d_add_price,GROUP_CONCAT(pro_add.d_stock) as d_add_stock,GROUP_CONCAT(pro_add.d_enable) as d_add_enable,pro.d_stock,pro.d_spectitle,pro.d_id,pro.d_title,pro.d_img1,pro.d_model,pro.d_price' . $this->autoful->Mlv . ' as d_pro_price
               from products as pro
               left join products_optional as pro_add on pro_add.PID like CONCAT("%",pro.d_id , "@#%") or pro_add.PID like CONCAT("%@#", pro.d_id, "%") or pro_add.PID like CONCAT("%@#", pro.d_id, "@#%") or pro.d_id=pro_add.PID
               where pro.d_id in (' . implode(',', array_column($dbdata['dbdata'], 'PID')) . ') and pro.d_enable="Y"
               group by pro.d_id
               order by pro_add.d_create_time asc');
            $dbdata['lvtitle']=$this->autoful->Lvtitle;
            $data['dbdata'] = $dbdata;
        }
        
        $this->NetTitle = '我的收藏';
       // if (!empty($data)){
            $this->response($data,200);
       // }else{
        //    $this->response(NULL,404);
       // }
        //$this->load->view('front/member_favorite', $data);
    }
    // 會員點數查詢
    public function point_get()
    {
        
        $data = array();
        if (empty($_SESSION[CCODE::MEMBER]['LID'])){
            $this->response(NULL,200,['msg'=>'not login']);
            exit();
        }
        //$this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        // 紅利點數說明
        $data['Content'] = $this->webmodel->BaseConfig('16');
        $dbdata = $this->mymodel->FrontSelectPage('member_point', 'OID,d_type,d_num,d_content,d_create_date,d_create_time,d_enable', 'where MID=' . $this->Mid . '', 'd_create_date desc,d_id desc', 10);

        foreach ($dbdata['dbdata'] as $key => $value) {
            $Odata = $this->mymodel->OneSearchSql('orders', 'd_id', array('OID' => $value['OID']));
            $dbdata['dbdata'][$key]['orderid'] = $Odata['d_id'];
            if ($value['d_type'] == 1) {
                $dbdata['dbdata'][$key]['Daedline'] = date("Y-m-d", strtotime("+1 year", strtotime($value['d_create_time'])));
            }
        }
        // 撈取快過期的點數
        $data['Edata'] = $this->mymodel->WriteSQL('
           select sum(d_total) as d_total,DATE_FORMAT(d_create_time,"%Y-%m-%d") as d_date,DATE_FORMAT(date_add(d_create_time,interval 1 year),"%Y-%m-%d") as Daedline from member_point where MID=' . $this->Mid . ' and d_type=1 and d_enable="Y" and d_total>0 group by d_date order by d_date
           ', '1');
        // 會員點數資料
        $data['Mdata'] = $this->mymodel->OneSearchSql('member', 'd_bonus', array('d_id' => $this->Mid));
        // print_r($Edata);
        $data['dbdata'] = $dbdata;
        $this->NetTitle = '會員點數查詢';
        if (!empty($data)){
            $this->response($data,200);
        }else{
            $this->response(NULL,404);
        }
        //$this->load->view('front/member_point', $data);
    }
    // 訂單列表，未完成，前台畫面皆須再測試
    // $type => 訂單類型，$id => 訂單ID
    public function orders_post($type = '', $id = '0')
    {
        //print_r($this->post('order_source'));
        $Pages=$this->post('page');
		$Limit=$this->post('limit');
        $this->Mid = (!empty($_SESSION[CCODE::MEMBER]['LID']) ? $_SESSION[CCODE::MEMBER]['LID'] : '');
        switch ($type) {
            case 'info': //內頁
            $dbdata = $this->_check_order($id);
            $Odata = $this->mymodel->OneSearchSql('orders', '*', array('d_id' => $id));
            $Detaildata = $this->mymodel->WriteSQL('select od.*,s.d_title as Stitle from orders_detail od left join products_sale s on s.d_id=od.SAID where OID='.$Odata['d_id']);
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
            $data['Odata'] = $Odata;
            $data['Detaildata'] = $Detaildata;
                $data['Orders_status'] = $this->mymodel->GetConfig('10', 'and d_enable="Y"'); // 訂單狀態
                $data['Pay_status'] = $this->mymodel->GetConfig('11', 'and d_enable="Y"'); // 付款狀態
                // 物流商
                $Shipdata = $this->mymodel->SelectSearch('ship', '', 'd_id,d_title,d_link', 'where d_enable="Y"');
                $data['Shipdata_link'] = array_column($Shipdata, 'd_link', 'd_id');
                $data['Shipdata_title'] = array_column($Shipdata, 'd_title', 'd_id');
                $view_file = 'front/member_order_info';
                break;
            case 'pay': //回覆付款
            $dbdata = $this->_check_order($id);
            $data['OID'] = $dbdata['OID'];
            $data['id'] = $id;
            $data['create_date'] = date('Y-m-d', strtotime($dbdata['d_create_time']));
            $view_file = 'front/member_order_pay';
            break;
            case 'ask': //訂單詢問
            $dbdata = $this->_check_order($id);
            $data['OID'] = $dbdata['OID'];
            $data['id'] = $id;
            $data['member_info'] = $_SESSION[CCODE::MEMBER];
            $view_file = 'front/member_order_ask';
            break;
            case 'cancel': //取消訂單
            $dbdata = $this->_check_order($id);
            $data['OID'] = $dbdata['OID'];
            $data['id'] = $id;
            $data['member_info'] = $_SESSION[CCODE::MEMBER];
            $data['orders_detail'] = $this->mymodel->SelectSearch('orders_detail', '', '*', 'where OID = "' . $id . '"', 'd_id');
            $view_file = 'front/member_order_cancel';
            break;
            case 'refund': //退貨訂單
            $dbdata = $this->_check_order($id);
                // 確定訂單內有無正貨，無的話不給退貨
            $chkTrial = $this->mymodel->OneSearchSql('orders_detail', 'd_id', array('OID' => $dbdata['d_id']));
            if (empty($chkTrial)) {
                $this->useful->AlertPage('', '此訂單購買項目內只有試用品，不能退貨！');
                exit();
            }
            $data['OID'] = $dbdata['OID'];
            $data['id'] = $id;
            $data['member_info'] = $_SESSION[CCODE::MEMBER];
            $data['orders_detail'] = $this->mymodel->WriteSQL('select od.*,s.d_title as Stitle from orders_detail od left join products_sale s on s.d_id=od.SAID where OID='.$id.' order by d_id asc');
            $view_file = 'front/member_order_refund';
            break;

            case 'specPay':
            $dbdata = $this->_check_order($id);
            if ($dbdata['d_orderstatus'] != 11) {
                $this->useful->AlertPage('', '此訂單目前尚未完成報價，無法繼續付款，請洽管理人員！');
                exit();
            }

                if ($dbdata['d_pay'] == 2) { // id=2  刷卡
                    $this->mymodel->UpdateData('orders', array('d_orderstatus' => 9), ' where d_id=' . $dbdata['d_id'] . '');
                    $config['lidm'] = $dbdata['OID'];
                    $config['purchAmt'] = $dbdata['d_total'];
                    $config['AuthResURL'] = base_url('pay_result');
                    $this->load->library('Cash_flow', $config);
                    // 傳送至金流
                    $data = $this->cash_flow->creditCard_getForm();
                    echo $data;
                    exit();
                } else if ($dbdata['d_pay'] == 4) { // id=4 , WebATM
                    $this->mymodel->UpdateData('orders', array('d_orderstatus' => 1), ' where d_id=' . $dbdata['d_id'] . '');
                    $config['lidm'] = $dbdata['OID'];
                    $config['purchAmt'] = $dbdata['d_total'];
                    $this->load->library('Cash_flow', $config);
                    // 傳送至金流
                    $Account = $this->cash_flow->webATM();
                    $this->mymodel->UpdateData('orders', array('d_webatm' => $Account), ' where d_id=' . $dbdata['d_id'] . '');

                    $mail_content = array('account' => $Account, 'total' => $dbdata['d_total']);
                    $Message = $this->load->view('front/_webatm', compact('mail_content'), true);

                    $this->tableful->Sendmail($dbdata['d_mail'], '千冠莉訂單-WebATM轉帳資訊', $Message);
                } else {
                    $this->mymodel->UpdateData('orders', array('d_orderstatus' => 1), ' where d_id=' . $dbdata['d_id'] . '');
                    $this->useful->AlertPage('cart/order_completed/' . $dbdata['OID'] . '', '訂單建立成功，將導向詳細頁');
                    exit();
                }
                break;

            default: //訂單列表
            $post = $this->input->post(null, true);
            $sql_where = 'where MID="' . $this->Mid . '"';
                $sql_where .= !empty($post['pay_type']) ? ' and d_pay="' . $post['pay_type'] . '"' : ''; // 付款方式
                $sql_where .= !empty($post['order_source']) ? ' and d_source="' . $post['order_source'] . '"' : ''; // 訂單來源

                $dbdata = $this->mymodel->APISelectPage('orders', 'd_id,OID,d_total,d_pay,d_paystatus,d_orderstatus,d_invoicenumber,d_remit_account,orders.d_create_time,d_successdate', $sql_where, 'd_create_time desc',$Pages,'5');
                $data = array(
                    'dbdata' => $dbdata, // 訂單資訊，分頁，5筆一頁
                    'Orders_status' => $this->mymodel->GetConfig('10', 'and d_enable="Y"'), // 訂單狀態
                    'Pay_types' => $this->mymodel->SelectSearch('cashflow', '', 'd_id,d_title', 'where d_enable="Y"', 'd_sort'), // 付款方式
                );
                //$view_file = 'front/member_order';
                break;
            }
            $this->NetTitle = '購物紀錄與訂單查詢';
            if (!empty($data)){
                $this->response($data,200);
            }else{
                $this->response(NULL,404);
            }
            //$this->load->view($view_file, $data);
        }
    // 檢查訂單
        private function _check_order($id, $is_ajax = false)
        {
            if ($id == '0') {
                if ($is_ajax) {
                    echo json_encode(array('status' => 'error'));
                    exit();
                } else {
                    $this->useful->AlertPage('', '此訂單不存在！');
                    exit();
                }
            } else {
                $query = $this->mymodel->OneSearchSql('orders', '*', array('MID' => $this->Mid, 'd_id' => $id));
            if (empty($query) && !$is_ajax) { // 不是ajax，錯誤訊息
                $this->useful->AlertPage('', '此訂單不存在！');
                exit();
            } else if (empty($query) && $is_ajax) { // 是 ajax，用json回傳錯誤
                echo json_encode(array('status' => 'error'));
                exit();
            }
            return $query;
        }
    }
    public function check_post($page, $id = '')
    {
        $post = $this->input->post(null, true);

        if ($this->form_validation->run($page) == true) { // 表單驗證

            switch ($page) {
                case 'account': //會員修改
                if ($_SESSION[CCODE::MEMBER]['VcodeNum'] != $post['d_captcha']) {
                    $this->useful->AlertPage('', '驗證碼輸入錯誤');
                    exit();
                }

                $dbdata = $this->useful->DB_Array($post, '1', '', '1');
                    $dbdata['d_newsletter'] = (!empty($post['d_newsletter']) ? 'Y' : 'N'); // 電子信
                    $dbdata = $this->useful->UnsetArray($dbdata, array('d_repassword', 'd_password', 'd_captcha'));
                    if (!empty($post['d_password'])) { //有修改密碼才加密新密碼
                        $this->load->library('encryption');
                        $dbdata['d_password'] = $this->encryption->encrypt($post['d_password']);
                    }

                    $msg = $this->mymodel->UpdateData('member', $dbdata, 'where d_id =' . $this->Mid);
                    if ($msg) {
                        $this->useful->AlertPage('member', '您已成功修改個人資料！');
                    } else {
                        $this->useful->AlertPage('', '修改失敗，請重新輸入！');
                    }
                    break;

                case 'friend': // 邀請好友
                $this->load->library('encryption');
                    while (1) { // 無窮迴圈檢查亂碼是否重複
                        $dbdata['d_Fcode'] = $this->encryption->encrypt($this->Mid);
                        $query = $this->mymodel->OneSearchSql('member_friend', 'd_id', array('d_Fcode' => $dbdata['d_Fcode']));
                        if (empty($query)) { //不重複即跳出迴圈
                            break;
                        }
                    }
                    $dbdata['MID'] = $this->Mid;
                    $dbdata = $this->useful->DB_Array($dbdata, '', '', '1');
                    $msg = $this->mymodel->InsertData('member_friend', $dbdata);
                    if ($msg) {
                        $CTitle = $this->webmodel->BaseConfig('6'); // 公司名稱
                        $Subject = $_SESSION[CCODE::MEMBER]['LName'] . "邀請您加入" . $CTitle['d_title'] . "會員";
                        $Message = "您好！<br><br>
                        您的朋友 " . $_SESSION[CCODE::MEMBER]['LName'] . "<br><br>
                        邀請您加入 " . $CTitle['d_title'] . "的會員<br><br>
                        以下是您的註冊邀請網址 <a href='" . site_url('login/join?F=' . $dbdata['d_Fcode']) . "'>點選我前往註冊</a><br><br>
                        進入以上網址進行註冊，且完成第一次購物，您的朋友將獲得紅利回饋 ";
                        $this->tableful->Sendmail($post['d_Femail'], $Subject, $Message);
                        $this->useful->AlertPage('member', '您已成功寄發邀請給您的好友！');
                    } else {
                        $this->useful->AlertPage('', '寄發邀請失敗，請重新輸入！');
                    }
                    break;

                case 'ask': // 訂單詢問
                $query = $this->_check_order($id);
                $post['OID'] = $query['OID'];
                $post['d_status'] = 1;
                $dbdata = $this->useful->DB_Array($post, '', '', '1');
                $msg = $this->mymodel->InsertData('orders_ask', $dbdata);
                if ($msg) {
                        $CMail = $this->webmodel->BaseConfig('12'); // 管理者信箱
                        $Message = '
                        您好，以下是詢問表單內容 <br>
                        -------------------------------- <br>
                        姓名 : ' . stripslashes($dbdata['d_name']) . '<br>
                        E-mail : ' . stripslashes($dbdata['d_email']) . '<br>
                        聯絡電話 : ' . stripslashes($dbdata['d_phone']) . '<br>
                        詢問內容 : ' . stripslashes($dbdata['d_content']) . '<br>';
                        $this->tableful->Sendmail($CMail, '美麗平台會員-訂單' . $query['OID'] . '詢問', $Message);
                        $this->response(['msg'=>'已提交相關人員，我們將盡快回覆您'],200);
                        //$this->useful->AlertPage('member/orders', '您已成功提交訂單詢問！');
                    } else {
                        $this->response(['msg'=>'提交失敗，請重新輸入'],404);
                        //$this->useful->AlertPage('', '詢問訂單失敗，請重新輸入！');
                    }
                    break;

                case 'cancel': // 訂單取消
                $query = $this->_check_order($id);
                    if ($query['d_orderstatus'] > 2 && $query['d_orderstatus'] != 10 && $query['d_orderstatus'] != 11) { // 訂單狀態須為未處理、處理中、報價處理中、報價完成，才可取消訂單
                        $this->useful->AlertPage('', '此訂單狀態不允許取消訂單，無法為您取消訂單！');
                    } else {
                        $post['d_orderstatus'] = 7; // 狀態更改為取消申請
                        $dbdata = $this->useful->DB_Array($post, '1', '', '1');
                        $CancelName = $dbdata['d_cancel_name'];
                        unset($dbdata['d_cancel_name']);
                        $msg = $this->mymodel->UpdateData('orders', $dbdata, 'where OID =' . $query['OID']);
                        if ($msg) {
                            $CMail = $this->webmodel->BaseConfig('12'); // 管理者信箱
                            $Message = '
                            您好，以下是取消表單內容 <br>
                            -------------------------------- <br>
                            姓名 : ' . stripslashes($CancelName) . '<br>
                            E-mail : ' . stripslashes($dbdata['d_cancel_email']) . '<br>
                            聯絡電話 : ' . stripslashes($dbdata['d_cancel_phone']) . '<br>
                            取消訂單原因 : ' . stripslashes($dbdata['d_cancel_content']) . '<br>';
                            $this->tableful->Sendmail($CMail, '美麗平台會員-訂單' . $query['OID'] . '取消', $Message);
                            $this->response(['msg'=>'您已成功提交訂單取消！'],200);
                            //$this->useful->AlertPage('member/orders', '您已成功提交訂單取消！');
                        } else {
                            $this->response(['msg'=>'詢問訂單失敗，請重新輸入！'],404);
                            //$this->useful->AlertPage('', '詢問訂單失敗，請重新輸入！');
                        }
                    }
                    break;

                case 'pay': // 訂單回覆付款
                $query = $this->_check_order($id);
                    if ($query['d_paystatus'] != 1 || $query['d_pay'] != 1) { //付款狀態需為未付款且付款方式需為ATM，才可填寫回覆
                        $this->useful->AlertPage('', '此訂單狀態不允許匯款回覆！');
                    } else {
                        $post['d_paystatus'] = 3; // 付款狀態更改為匯款確認中
                        $dbdata = $this->useful->DB_Array($post, '1', '', '1');
                        $msg = $this->mymodel->UpdateData('orders', $dbdata, 'where d_id =' . $query['d_id']);
                        if ($msg) {
                            $CMail = $this->webmodel->BaseConfig('12'); // 管理者信箱
                            $Message = "訂單編號：" . $query['OID'] . "已回覆匯款，請至管理系統確認！";
                            $this->tableful->Sendmail($CMail, '美麗平台會員-訂單' . $query['OID'] . '匯款', $Message);
                            $this->response(['msg'=>'您已成功填寫匯款回覆！'],200);
                            //$this->useful->AlertPage('member/orders', '您已成功填寫匯款回覆！');
                        } else {
                            $this->response(['msg'=>'填寫匯款回覆失敗，請重新輸入！'],404);
                            //$this->useful->AlertPage('', '填寫匯款回覆失敗，請重新輸入！');
                        }
                    }
                    break;

                case 'refund': // 訂單退貨
                $query = $this->_check_order($id);
                    if ($query['d_orderstatus'] != 3) { // 狀態須為已出貨，才可退貨
                        $this->useful->AlertPage('', '此訂單狀態不允許申請退貨！');
                    } else {
                        $post['d_orderstatus'] = 5;
                        $back = $post['d_back'];
                        unset($post['d_back']);
                        $dbdata = $this->useful->DB_Array($post, '1', '', '1');
                        $this->mymodel->UpdateData('orders', $dbdata, ' where d_id =' . $id);
                        // 將退貨商品轉變為退貨狀態
                        $msg = $this->mymodel->UpdateData('orders_detail', array('d_status' => 3), ' where OID = "' . $id . '" and d_id in (' . implode(',', $back) . ')');

                        if ($msg) {
                            $CMail = $this->webmodel->BaseConfig('12');
                            $Message = "訂單編號：" . $query['OID'] . "已申請退貨，請至管理系統確認！";
                            $this->tableful->Sendmail($CMail, '美麗平台會員-訂單' . $query['OID'] . '退貨', $Message);
                            $this->response(['msg'=>'您已成功申請退貨！'],200);
                            //$this->useful->AlertPage('member/orders', '您已成功申請退貨！');
                        } else {
                            $this->response(['msg'=>'申請退貨失敗，請重新輸入！'],404);
                            //$this->useful->AlertPage('', '申請退貨失敗，請重新輸入！');
                        }
                    }
                    break;
                }
            } else {
                $this->form_validation->set_error_delimiters('', '\n');
                $this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
            }
        }
    // ajax 發票明細 (已寫好，目前棄用)
    public function invoice_pro()
    {
        $post = $this->input->post(null, true);
        if (!empty($post['id'])) {
            $dbdata['orders'] = $this->_check_order($post['id'], true);
            $dbdata['orders_detail'] = $this->mymodel->SelectSearch('orders_detail', '', '*', 'where OID = "' . $post['id'] . '"', 'd_id');
            $data = array(
                'status' => 'success',
                'dbdata' => $this->load->view('front/invoice_info', $dbdata, true),
            );
            echo json_encode($data);
            exit();
        }
        echo json_encode(array('status' => 'error'));
    }
    // ajax 刪除收藏
    public function delFavorite()
    {
        $post = $this->input->post(null, true);
        if (!empty($post)) {
            $this->mymodel->DelectData('member_favorite', 'where PID=' . $post['id'] . ' and MID=' . $this->Mid);
            echo 'success';
            exit();
        }
        echo 'error';
    }
    // 邀請好友
    /* public function friend()
    {
        $data = array();
        $this->NetTitle = '邀請好友加入會員';
        $this->load->view('front/member_friend', $data);
    } */
    // 表單送出後檢查，未完成，皆須再測試
    private function _chk_Captcha($code)
    {
        if ($_SESSION[CCODE::MEMBER]['VcodeNum'] != $code) {
            $this->useful->AlertPage('', '驗證碼輸入錯誤');
            exit();
        }
    }
}