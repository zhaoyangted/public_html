<?php
class Cartful
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('MyModel/Webmodel', 'webmodel');
        $this->CartNum = 0;
    }

    // 撈取購物車資訊
    public function GetCart($cart, $Subbonus = '0')
    {
        $cart = !empty($cart) ? explode(';', $cart) : array();
        $Order = $CartArray = $AddArray = $TrialArray = $Option_sum = $BonusArray = array();
        $Chkpay = $Chkop = $ChkTri = 'Y';
        $AllTotal = $Addprice = $One_total = $BigFreight = $Outisland = 0;
        $OneFreight = $this->CI->mymodel->OneSearchSql('freight', 'd_free,d_freight,d_outisland', array('d_id' => 1));
        $is_have_One = $special = false; // 訂單內是否含有一般運費商品，特殊商品

        // 是否有試用品
        if (!empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y' && !empty($_SESSION[CCODE::MEMBER]['TrialData'])) {
            $TrialArray = $this->chkTrial();
            $TriStock = array_column($TrialArray, 'd_stock');
            if (in_array('0', $TriStock)) {
                $ChkTri = 'N';
            } else {
                $is_have_One = true;
            }
        }
        // 是否有加購價產品
        $AddArray = $this->chkAddData();
        foreach ($AddArray as $key => $value) {
            $Addprice += $value['d_price'];
        }

        $Filed = 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_dprice,d_sprice,d_model,d_stock,d_bonus,FID,MTID';
        // 檢查是否有特價
        $NewCart = $this->chkSale($cart);
        $cart = $NewCart[2];

        $Lvcount=1;
         if($this->CI->autoful->Mlv>=4){
             $MlvData = $this->CI->mymodel->WriteSql('select d_count from member_lv where d_id='.$this->CI->autoful->Mlv.'', '1');
             $Lvcount=(100-$MlvData['d_count'])/100;
         }
         // echo $Lvcount;
        // print_r($cart);
        // exit();
        // print_r($NewCart);

        foreach ($cart as $key => $value) {

            $IsSale = false;
            $SAID = 0;
            $Optional = array();
            $cart1 = explode('@#', $value);

            $products = $this->CI->mymodel->OneSearchSql('products', $Filed, array('d_id' => $cart1[0], 'd_enable' => "Y"));

            if (!empty($products)) {
                
                if($this->CI->autoful->UserType==1 or empty($this->CI->autoful->UserType)){
                    $products['d_price']=$products['d_price1'];
                }else{
                    $Mtype1=explode('@#',$this->CI->autoful->Mtype.'@#'.$this->CI->autoful->Mtype1);
                    $Mtype1=array_unique($Mtype1);
                    
                    $MTID=$products['MTID'];
                    $TypeData1=explode('@#',$MTID);

                    $result1 = array_intersect ($TypeData1, $Mtype1);
                    
                    if(count($result1)!=0){
                        $products['d_price']=$products['d_price3']*$Lvcount;
                    }else{
                        $products['d_price']=$products['d_price2'];
                    }
                }
                // if($Lvcount!=1){
                //     $products['d_price']=$d_price=$products['d_price3']*$Lvcount;
                //     // echo $d_price;
                // }else{
                //     $products['d_price']=$products['d_price'.$this->CI->autoful->Mlv.''];
                // }

                $Stock = $products['d_stock'];

                // 判斷 促銷價 先 出清價 後
                $products['d_price'] = $products['d_sprice'] != 0 ? $products['d_sprice'] : ($products['d_dprice'] != 0 ? $products['d_dprice'] : $products['d_price']);
                // 特價優先
                $Discount = $this->CI->autoful->ChkSingleSale(array($cart1[0], 0),$products['d_price']);
                if (!empty($Discount)) {
                // if (!empty($this->CI->autoful->DiscountData[$cart1[0]])) {
                    // 特價購買量
                    $SaleNum = $this->CI->autoful->DiscountData[$cart1[0]]['num'];
                    if (in_array($value, $NewCart[2])) {
                        $products['d_price'] = $this->CI->autoful->DiscountData[$cart1[0]]['d_price'];
                        $products['d_bonus'] = ($this->CI->autoful->DiscountData[$cart1[0]]['GetBonus'] == 'Y') ? $products['d_bonus'] : 0;
                        $this->CI->autoful->DiscountData[$cart1[0]]['maxnum'] > 0 ? $Stock = $this->CI->autoful->DiscountData[$cart1[0]]['maxnum'] : '';
                        $SAID = $this->CI->autoful->DiscountData[$cart1[0]]['d_id'];
                        $IsSale = true;
                    } else {
                        $Stock -= $this->CI->autoful->DiscountData[$cart1[0]]['maxnum'];
                    }
                }

                // 庫存判定-
                if ($cart1[1] > $Stock) {
                    $cart1[1] = $Stock;
                }
                // 購物車總數量
                $this->CartNum += $cart1[1];

                // 判斷是否有無庫存產品
                if ($Stock <= 0) {
                    $Chkpay = 'N';
                }

                // 商品選配
                if (!empty($cart1[2])) {
                    $Option_sum[$cart1[2]] = empty($Option_sum[$cart1[2]]) ? $cart1[1] : $Option_sum[$cart1[2]] + $cart1[1];
                    $Optional = $this->chkOption($cart1[2], $Option_sum[$cart1[2]]);
                    if ($Optional['Chkop'] == 'N') {
                        empty($Optional['AddPrice']) ? $Optional['AddPrice'] = 0 : '';
                        empty($Optional['AddTitle']) ? $Optional['AddTitle'] = '此選配商品已不存在' : '';
                        empty($Optional['Addstock']) ? $Optional['Addstock'] = 0 : '';
                        $Chkop = 'N';
                    }
                    $Total = ($products['d_price'] + $Optional['AddPrice']) * $cart1[1];
                } else {
                    $Total = $products['d_price'] * $cart1[1];
                }

                $PdataArr = array(
                    'd_id' => $products['d_id'],
                    'd_title' => $products['d_title'],
                    'd_img' => $products['d_img1'],
                    'd_img1' => $products['d_img1'],
                    'stock' => $Stock,
                    'd_model' => $products['d_model'],
                    'num' => $cart1[1],
                    'd_num' => $cart1[1],
                    'd_price' => $products['d_price'],
                    'd_total' => $Total,
                    'd_pfreight_lv' => $products['FID'],
                    'd_pfreight' => 0,
                    'd_poutisland' => 0,
                    'AddData' => (!empty($Optional) ? $Optional : ''),
                    'IsSale' => $IsSale,
                    'Ckey' => $NewCart[1][$key],
                );

                // 運費撈取
                $Fdata = $this->CI->mymodel->OneSearchSql('freight', 'd_id,d_title,d_freight,d_outisland', array('d_id' => $products['FID'], 'd_enable' => 'Y'));

                switch ($products['FID']) {
                    case '1': //一般運
                        $is_have_One = true;
                        $One_total += $Total;
                        break;
                    case '6': //免運
                        $Outisland += $Fdata['d_outisland'] * $cart1[1];
                        $PdataArr['d_poutisland'] = $Fdata['d_outisland'] * $cart1[1];
                        break;
                    case '7': //特殊運費
                        $special = true;
                        break;
                    default: //大型運
                        $PdataArr['d_pfreight'] = $Fdata['d_freight'] * $cart1[1];
                        $PdataArr['d_poutisland'] = $Fdata['d_outisland'] * $cart1[1];
                        $BigFreight += $Fdata['d_freight'] * $cart1[1];
                        $Outisland += $Fdata['d_outisland'] * $cart1[1];
                        break;
                }

                $CartArray[] = $PdataArr;

                $AllTotal += $Total;
                // 單件商品紅利*購買數量
                $BonusArray[$products['d_bonus']][$products['d_id'] . '@#' . $SAID] = $Total;

            } else {
                unset($cart[$key]);
                $cart = array_values($cart);
                $_COOKIE['BeautyCart'] = implode(';', $cart);
            }
        }

        // 紅利陣列
        $_SESSION['BonusArray'] = $BonusArray;
        // 判斷是否試用有被領過
        $Order['ChkTriHad'] = !empty($TrialArray) ? $TrialArray['had'] : 'Y';
        unset($TrialArray['had']);
        // 購物車總數量
        $this->CartNum += count($TrialArray);
        // 加價購
        $Order['AddData'] = $AddArray;
        // 試用品
        $Order['TrialData'] = $TrialArray;
        // 判斷是否有無庫存產品
        $Order['Chkpay'] = $Chkpay;
        // 判斷是否有無庫存選配
        $Order['Chkop'] = $Chkop;
        // 判斷是否有無庫存試用
        $Order['ChkTri'] = $ChkTri;
        // 扣除紅利
        $Order['Subbonus'] = $Subbonus;
        // 此次獲得總紅利
        $Order['BonusTotal'] = $this->CI->autoful->CountBonus($BonusArray, (int)$AllTotal + (int)$Addprice - (int)$Subbonus);
        // 產品金額
        $Order['Total'] = $AllTotal + $Addprice;
        // 大型運費
        $Order['BigFreight'] = $BigFreight;
        // 一般運費規則
        $Order['OneFreight'] = $OneFreight;
        // 離島另收
        $Order['Outisland'] = $Outisland + ($is_have_One ? $OneFreight['d_outisland'] : 0);
        // 一般運費
        $Order['Freight'] = ($is_have_One && $One_total < $OneFreight['d_free'] ? $OneFreight['d_freight'] : 0);
        // 特殊運費
        $Order['Special'] = $special;
        // 產品總金額金額 (小計+加購+大型運費+一般運費)-紅利折抵
        $Order['AllTotal'] = ((int)$AllTotal + (int)$BigFreight + (int)$Order['Freight'] + (int)$Addprice) - (int)$Subbonus;
        // 購物車資訊
        $Order['Cart'] = $CartArray;

        $this->Order = $Order;
    }

    // 檢查試用品
    private function chkTrial()
    {
        $Tdata = $this->CI->mymodel->WriteSQL('
        select p.d_id,p.PID,p.TID,p.d_type,p.d_img,p.d_img as d_img1,p.d_title,t.d_days,p.d_stock,p.GPID,t.d_try,p.d_model
        from products_trial p
        left join products_trial_type t on t.d_id=p.TID
        where p.d_enable = "Y" and t.d_enable = "Y" and p.d_id in (' . $_SESSION[CCODE::MEMBER]['TrialData'] . ')'
        );

        foreach ($Tdata as $key => $value) {

            $Tdata[$key]['d_num'] = 1;
            $Tdata[$key]['d_price'] = '0';

            if ($value['d_try'] == 1) { // 所有規格均可索取一次
                $chkhad = $this->CI->mymodel->WriteSQL('
              select d_id
              from orders_trial_detail
              where d_enable = "Y" and d_deadline>CURDATE() and MID="' . $_SESSION[CCODE::MEMBER]['LID'] . '" and TID=' . $value['d_id'] . '
              ');
            } else { // 選一種規格索取一次
                $AllTrial = $this->CI->mymodel->WriteSQL('select group_concat(d_id) as d_id from products_trial where TID = ' . $value['TID'] . ' group by TID');
                $chkhad = $this->CI->mymodel->WriteSQL('
              select d_id
              from orders_trial_detail
              where d_enable = "Y" and d_deadline>CURDATE() and MID="' . $_SESSION[CCODE::MEMBER]['LID'] . '" and TID in (' . $AllTrial[0]['d_id'] . ')
              ');
            }

            $Tdata['had'] = 'Y';
            // 檢查是否有被領過
            if (!empty($chkhad)) {
                $this->RemoveCart('TrialData', $value['d_id']);
                $Tdata['had'] = 'N';
            }

            // 是否有由產品資料來的
            if ($value['d_type'] == 2) {
                $Pdata = $this->CI->mymodel->OneSearchSql('products', 'd_img1,d_title', array('d_id' => $value['GPID']));
                if (!empty($Pdata)) {
                    $Tdata[$key]['d_title'] = $Pdata['d_title'];
                    $Tdata[$key]['d_img1'] = $Pdata['d_img1'];
                } else {
                    unset($Tdata[$key]);
                }
            }
        }
        return $Tdata;
    }
    // 檢查選配
    private function chkOption($id, $sum)
    {
        $Optional = $this->CI->mymodel->OneSearchSql('products_optional', 'd_title as AddTitle,d_price as AddPrice,d_id as Addid,d_stock as Addstock', array('d_id' => $id, 'd_enable' => 'Y'));
        $Optional['Chkop'] = empty($Optional) || $Optional['Addstock'] < $sum ? 'N' : 'Y';
        return $Optional;
    }
    // 檢查加價購
    private function chkAddData()
    {
        $AddArray = array();
        if (!empty($_SESSION[CCODE::MEMBER]['AddData'])) {
            $Addid = $_SESSION[CCODE::MEMBER]['AddData'];
            $AddArray = $this->CI->mymodel->SelectSearch('products_markup', '', 'd_id,d_title,d_img,d_aprice,d_price,d_stock', 'where d_enable="Y" and d_stock > 0 and d_id in (' . $Addid . ')');
            if (count(explode(',', $_SESSION[CCODE::MEMBER]['AddData'])) != count($AddArray)) {
                $diff = array_diff(explode(',', $_SESSION[CCODE::MEMBER]['AddData']), array_column($AddArray, 'd_id'));
                foreach ($diff as $id) {
                    $this->RemoveCart('AddData', $id);
                }
                $this->CI->useful->AlertPage('cart', '購物車內部分加價購商品已無庫存，請重新選擇加價購商品！');
                exit();
            }
        }
        return $AddArray;
    }
    // 刪除購物車-加購價 試用品
    public function RemoveCart($name = '', $id = '')
    {
        if (!empty($_SESSION[CCODE::MEMBER][$name])) {
            $cart = explode(',', $_SESSION[CCODE::MEMBER][$name]);
            foreach ($cart as $key => $value) {
                if ($value == $id) {
                    unset($cart[$key]);
                }
            }
            $cart = array_values($cart);
        }
        $cart = implode(',', $cart);
        $_SESSION[CCODE::MEMBER][$name] = $cart;
    }
    // 檢查是否有特價 並將特價數量拆開
    private function chkSale($cart)
    {
        $NewArr = $hasChk = $Ckey = $Skey = array();

        $Filed = 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_dprice,d_sprice,d_model,d_stock,d_bonus,FID,MTID';

        foreach ($cart as $key => $c) {
            $cartArr = explode('@#', $c);
            $products = $this->CI->mymodel->OneSearchSql('products',$Filed, array('d_id' => $cartArr[0], 'd_enable' => "Y"));

            $Lvcount=1;
            if($this->CI->autoful->Mlv>=4){
                $MlvData = $this->CI->mymodel->WriteSql('select d_count from member_lv where d_id='.$this->CI->autoful->Mlv.'', '1');
                $Lvcount=(100-$MlvData['d_count'])/100;
            }

            if (!empty($products)) {

                if($this->CI->autoful->UserType==1 or empty($this->CI->autoful->UserType)){
                    $products['d_price']=$products['d_price1'];
                }else{
                    $Mtype1=explode('@#',$this->CI->autoful->Mtype.'@#'.$this->CI->autoful->Mtype1);
                    $Mtype1=array_unique($Mtype1);
                    
                    $MTID=$products['MTID'];
                    $TypeData1=explode('@#',$MTID);

                    $result1 = array_intersect ($TypeData1, $Mtype1);
                    
                    if(count($result1)!=0){
                        $products['d_price']=$products['d_price3']*$Lvcount;
                    }else{
                        $products['d_price']=$products['d_price2'];
                    }
                }


                // 庫存判定-
                $Stock = $products['d_stock'];
                if ($cartArr[1] > $Stock) {
                    $cartArr[1] = $Stock;
                }
                // 同類型的商品
                if (!in_array($cartArr[0] . '@#' . $cartArr[2], $hasChk)) {
                    // 是否有活動
                    $IsSale = $this->CI->autoful->ChkSingleSale($cartArr, $products['d_price']);
                    // 特價
                    if (!empty($IsSale)) {
                        if ($cartArr[1] != $IsSale[1]) { // 部分數量特價 部分原價
                            $cartArr[1] -= $IsSale[1];
                            $c = implode('@#', $cartArr);
                            $NewArr[] = $c;
                            $Ckey[] = $key;
                        }
                        $NewArr[] = implode('@#', $IsSale);
                        $Skey[] = count($NewArr) - 1;
                        $Ckey[] = $key;
                    } else {
                        $NewArr[] = $c;
                        $Ckey[] = $key;
                    }
                    array_push($hasChk, $cartArr[0] . '@#' . $cartArr[2]);
                } else {
                    $NewArr[] = $c;
                    $Ckey[] = $key;
                }
            }
        }
        return array($Skey, $Ckey, $NewArr);
    }

}
