<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cash_flow
{

    /* 第一銀行金流
     * 2020/02/24
     * 信用卡一次付清
     * By Kevin.
     *
     *
     *
     */

    // 店家介接設定 (變數)

    // 測試模式或正式 FALSE=測試模式， TRUE=正式模式
    private $mode = true;

    // 測試前綴(防止測試期訂單編號重複一定只能 英文數字)
    // private $prefix                = '';
    // 網站特店自訂代碼
    private $merID = '53329313';
    // 特店代碼
    private $MerchantID = '007533293139001';
    // 端末機
    private $TerminalID = '90010001';
    // 客製化付款授權網頁辨識碼
    private $customize = '0';
    // 表單action
    private $gateway = '';
    // 交易金額
    private $purchAmt = '';
    // 交易結果回傳網址
    private $AuthResURL = '';
    // 訂單編號
    private $lidm = '';

    public function __construct($config = array())
    {
        foreach ($config as $key => $value) {
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
        if ($this->mode) {
            // 正式交易網址
            $this->gateway = "https://www.focas.fisc.com.tw/FOCAS_WEBPOS/online/";
        } else {
            // 測試網址
            $this->gateway = "https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/online/";
        }
    }

    // 信用卡
    public function creditCard_getForm()
    {
        $form = '<form name="autoForm" action="' . $this->gateway . '" method="POST">';
        $form .= '<input type="hidden" name="merID" value="' . $this->merID . '" />'; //<!--網站特店自訂代碼-->
        $form .= '<input type="hidden" name="MerchantID" value="' . $this->MerchantID . '" />'; //<!--商店代號-->
        $form .= '<input type="hidden" name="TerminalID" value="' . $this->TerminalID . '" />'; //<!--機台代號-->
        $form .= '<input type="hidden" name="customize" value="' . $this->customize . '" />'; //<!--網頁辨識碼-->
        $form .= '<input type="hidden" name="lidm" value="' . $this->lidm . '" />'; //<!--訂單編號--->
        $form .= '<input type="hidden" name="purchAmt" value="' . floor($this->purchAmt) . '" />'; //<!--訂單金額-->
        $form .= '<input type="hidden" name="AuthResURL" value="' . $this->AuthResURL . '" />'; //<!--交易結果回傳網址-->
        $form .= '</form>';
        $button = '<div style="text-align:center">' . $form . '</div>';
        $form .= '<script>autoForm.submit();</script>';

        return $form;
    }
    public function creditCard_getForm_data()
    {
        $form['gateway'] = $this->gateway ;
        $form ['merID']=  $this->merID ;
        $form ['MerchantID']= $this->MerchantID ; //<!--商店代號-->
        $form ['TerminalID']= $this->TerminalID ; //<!--機台代號-->
        $form ['customize']= $this->customize ; //<!--網頁辨識碼-->
        $form ['lidm']= $this->lidm ; //<!--訂單編號--->
        $form ['purchAmt']= floor($this->purchAmt) ; //<!--訂單金額-->
        $form ['AuthRestURL']= $this->AuthResURL ; //<!--交易結果回傳網址-->

        return $form;
    }
    // 虛擬ATM
    public function webATM()
    {
        // 繳費期限 3天
        $Account = '12169' . substr($this->lidm, -5) . date('m', strtotime("+ 3 days")) . date('d', strtotime("+ 3 days"));
        // 轉帳金額
        $Price = sprintf("%08s", floor($this->purchAmt));
        $Weight = $Account . $Price;
        $Y1 = $this->Weights($Weight, 1);
        $Y2 = $this->Weights($Weight, 2);

        // 檢查碼 O、P
        $chkO = 10 - $Y1;
        $chkP = 10 - $Y2;
        
        /**
         * 1216950001021806
         *  2022-02-15 避免檢查碼變成兩位數
         */
        $chkO = ($chkO == 10)?0:$chkO;
        $chkP = ($chkP == 10)?0:$chkP;

        // 完整虛擬帳號
        $Account .= $chkO . $chkP;
        return $Account;
    }
    
    // 權數
    private function Weights($Str, $type)
    {
        $X = 0;
        $count = 8;
        foreach (str_split($Str) as $k => $v) {
            switch ($k % 3) {
                case 0:
                if ($type == 1) {
                    $X += $v * 3;
                } else {
                    $X += $v * $count;
                }
                break;
                case 1:
                if ($type == 1) {
                    $X += $v * 7;
                } else {
                    $X += $v * $count;
                }
                break;
                case 2:
                if ($type == 1) {
                    $X += $v;
                } else {
                    $X += $v * $count;
                }
                break;
            }
            $count--;
            if ($count == 0) {
                $count = 8;
            }
        }
        return $X % 10;
    }

}
