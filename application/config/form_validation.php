<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
$config = array(
    //
    'login' => array(
        array('field' => 'd_account', 'label' => '帳號', 'rules' => 'required|trim'),
        array('field' => 'd_password', 'label' => '密碼', 'rules' => 'required|trim|alpha_dash'),
        array('field' => 'd_captcha', 'label' => '驗證碼', 'rules' => 'required'),
    ),
    //
    'contact' => array(
        array('field' => 'd_type', 'label' => '詢問類型', 'rules' => 'trim|required'),
        array('field' => 'd_content', 'label' => '內容', 'rules' => 'trim|required'),
        array('field' => 'd_name', 'label' => '姓名', 'rules' => 'trim|required'),
        array('field' => 'd_cname', 'label' => '公司名稱', 'rules' => ''),
        array('field' => 'd_mobile', 'label' => '聯絡電話', 'rules' => 'trim|required'),
        array('field' => 'd_mail', 'label' => 'E-mail', 'rules' => 'trim|required|valid_email'),
        array('field' => 'd_county', 'label' => '縣市', 'rules' => 'trim|required'),
        array('field' => 'd_district', 'label' => '鄉鎮市區', 'rules' => 'trim|required'),
        array('field' => 'd_zipcode', 'label' => '郵遞區號', 'rules' => 'trim|required|integer|min_length[3]|max_length[5]'),
        array('field' => 'd_address', 'label' => '地址', 'rules' => 'trim|required'),
        array('field' => 'd_captcha', 'label' => '驗證碼', 'rules' => 'trim|required'),
    ),
    //
    'account' => array(
        array('field' => 'd_password', 'label' => '密碼', 'rules' => 'trim|alpha_dash|min_length[6]|max_length[30]'),
        array('field' => 'd_repassword', 'label' => '確認密碼', 'rules' => 'trim|alpha_dash|min_length[6]|max_length[30]|matches[d_password]'),
        array('field' => 'd_phone', 'label' => '聯絡電話', 'rules' => 'integer|exact_length[10]'),
        (isset($_POST['d_county']))?array('field' => 'd_county', 'label' => '縣市', 'rules' => 'trim|required'):'',
        (isset($_POST['d_district']))?array('field' => 'd_district', 'label' => '鄉鎮市區', 'rules' => 'trim|required'):'',
        (isset($_POST['d_zipcode']))?array('field' => 'd_zipcode', 'label' => '郵遞區號', 'rules' => 'trim|required|integer|min_length[3]|max_length[5]'):'',
        (isset($_POST['d_address']))?array('field' => 'd_address', 'label' => '地址', 'rules' => 'trim|required'):'',
        array('field' => 'd_captcha', 'label' => '驗證碼', 'rules' => 'trim|required'),
    ),
    //
    'ask' => array(
        array('field' => 'd_name', 'label' => '姓名', 'rules' => 'trim|required'),
        array('field' => 'd_email', 'label' => 'E-mail', 'rules' => 'trim|required|valid_email'),
        array('field' => 'd_phone', 'label' => '聯絡電話', 'rules' => 'trim|required|integer|exact_length[10]'),
        array('field' => 'd_content', 'label' => '詢問內容', 'rules' => 'trim|required'),
    ),
    //
    'pay' => array(
        array('field' => 'd_remit_account', 'label' => '匯款帳號末五碼', 'rules' => 'trim|required|integer|exact_length[5]'),
        array('field' => 'd_remit_price', 'label' => '匯款金額', 'rules' => 'trim|required|integer'),
        array('field' => 'd_remit_time', 'label' => '匯款時間', 'rules' => 'trim|required'),
    ),
    //
    'cancel' => array(
        array('field' => 'd_cancel_email', 'label' => 'E-mail', 'rules' => 'trim|required|valid_email'),
        array('field' => 'd_cancel_phone', 'label' => '聯絡電話', 'rules' => 'trim|required|integer|exact_length[10]'),
        array('field' => 'd_cancel_content', 'label' => '取消訂單原因', 'rules' => 'trim|required'),
    ),
    //
    'refund' => array(
        array('field' => 'd_back[]', 'label' => '退貨商品', 'rules' => 'required|greater_than[0]'),
        array('field' => 'd_return_email', 'label' => 'E-mail', 'rules' => 'trim|required|valid_email'),
        array('field' => 'd_return_phone', 'label' => '聯絡電話', 'rules' => 'trim|required|integer|exact_length[10]'),
        array('field' => 'd_return_content', 'label' => '商品退貨原因', 'rules' => 'trim|required'),
    ),

    'register' => array(
        array('field' => 'd_user_type', 'label' => '用戶類型', 'rules' => 'trim|required'),
        (!isset($_POST['d_review']))?array('field' => 'd_account', 'label' => 'E-MAIL', 'rules' => 'required|trim|valid_email|is_unique[member.d_account]'):'',
        array('field' => 'd_password', 'label' => '密碼', 'rules' => 'required|trim|alpha_dash|min_length[6]|max_length[30]'),
        array('field' => 'd_repassword', 'label' => '確認密碼', 'rules' => 'required|trim|alpha_dash|min_length[6]|max_length[30]|matches[d_password]'),
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_type', 'label' => '公司類型', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_name', 'label' => '公司名稱', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'TID', 'label' => '主要營業類別', 'rules' => 'trim|required'):'',
        (isset($_POST['d_company_type'])&&$_POST['d_company_type']==1&&$_POST['d_user_type']==2)?array('field' => 'd_company_title', 'label' => '公司抬頭', 'rules' => 'trim|required'):'',
        (isset($_POST['d_company_type'])&&$_POST['d_company_type']==1&&$_POST['d_user_type']==2)?array('field' => 'd_company_number', 'label' => '公司統編', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_tel', 'label' => '公司電話', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_county', 'label' => '公司縣市', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_district', 'label' => '公司鄉鎮市區', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_zipcode', 'label' => '公司郵遞區號', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_company_address', 'label' => '公司地址', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)?array('field' => 'd_operate_type', 'label' => '營業狀況', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2&&$_POST['d_operate_type']==2)?array('field' => 'd_operate_date', 'label' => '開業日期', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2&&$_POST['d_operate_type']==2)?array('field' => 'd_operate_address', 'label' => '預定地址', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)&&$_POST['d_operate_type']==2?array('field' => 'd_operate_people', 'label' => '員工人數', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==2)&&$_POST['d_operate_type']==2?array('field' => 'd_operate_service[]', 'label' => '營業服務項目', 'rules' => 'trim|required'):'',
        array('field' => 'd_pname', 'label' => '姓名', 'rules' => 'trim|required'),
        array('field' => 'd_job', 'label' => '職稱', 'rules' => 'trim|required'),
        array('field' => 'd_birthday', 'label' => '生日', 'rules' => ''),
        array('field' => 'd_phone', 'label' => '手機號碼', 'rules' => 'integer|exact_length[10]'),
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==1)?array('field' => 'd_county', 'label' => '縣市', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==1)?array('field' => 'd_district', 'label' => '鄉鎮市區', 'rules' => 'trim|required'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==1)?array('field' => 'd_zipcode', 'label' => '郵遞區號', 'rules' => 'trim|required|integer|min_length[3]|max_length[5]'):'',
        (isset($_POST['d_user_type'])&&$_POST['d_user_type']==1)?array('field' => 'd_address', 'label' => '地址', 'rules' => ''):'',
        array('field' => 'SID', 'label' => '業務員', 'rules' => 'trim|required'),
        array('field' => 'd_captcha', 'label' => '驗證碼', 'rules' => 'trim|required'),
    ),
    //
    'forget' => array(
        array('field' => 'd_account', 'label' => 'E-mail', 'rules' => 'required|trim|valid_email'),
    ),
    //
    'friend' => array(
        array('field' => 'd_Femail', 'label' => '朋友的E-mail', 'rules' => 'required|trim|valid_email'),
    ),

);
