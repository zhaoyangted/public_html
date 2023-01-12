<?php
class Orders_edit extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

        // 各專案後臺資料夾
		$AdminName = $this->webmodel->BaseConfig();
		$this->AdminName = $AdminName['d_title'];

		$this->FunctionType = 'edit';


        // 自動頁面設定
		$this->tableful->GetAutoPage($this->FunctionType);
        // 資料庫名稱
		$this->DBname = $this->tableful->MenuidDb['d_dbname'];
        // 後台基本設定
		$this->autoful->backconfig();
	}

	public function index($d_id)
	{

        if (!empty($d_id)) {
            //p($this->tableful->Menu);
            // exit();
            // 特殊欄位處理
            $this->tableful->TableTreat(10);
            $this->tableful->TableTreat(11);

            $data['d_id'] = $d_id;
            $dbdata = $this->mymodel->OneSearchSql($this->DBname, '*', array('d_id' => $d_id));

            // 訂單細項
            $oddata = $this->mymodel->WriteSql('select * from orders_detail where OID=' . $dbdata['d_id'] . '');
            //p($dbdata['d_orderstatus']);die;
            // 判斷是否要顯示銷退單
            if ($dbdata['d_orderstatus'] == 5 || $dbdata['d_orderstatus'] == 6 || ($dbdata['d_orderstatus'] == 4 && in_array(4, array_column($oddata, 'd_status')))) {
                $dbdata['reOrderShow'] = true;
            } else {
                $dbdata['reOrderShow'] = false;
            }
            // 是否要顯示銷退單
            if (!$dbdata['reOrderShow']) {
                for ($i = 16; $i < 25; $i++) {
                    unset($this->tableful->Menu[$i]);
                }
            }
            // 是否已匯款
            if (empty($dbdata['d_remit_account'])) {
                unset($this->tableful->Menu[13]);
                unset($this->tableful->Menu[14]);
                unset($this->tableful->Menu[15]);
            }
            // 是否已取消訂單
            if (empty($dbdata['d_cancel_content'])) {
                unset($this->tableful->Menu[41]);
                unset($this->tableful->Menu[42]);
                unset($this->tableful->Menu[43]);
                unset($this->tableful->Menu[44]);
            }

            // 訂單來源
            $dbdata['d_source'] = ($dbdata['d_source'] == 1) ? '網路' : '門市';
            // 地址拼湊
            $dbdata['d_address'] = $dbdata['d_zip'] . $dbdata['d_city'] . $dbdata['d_area'] . $dbdata['d_address'];
            // 會員
            $MemberData = $this->mymodel->OneSearchSql('member', 'd_account,d_mcode', array('d_id' => $dbdata['MID']));
            $dbdata['MID'] = $MemberData['d_account'];
            $dbdata['d_mcode'] = $MemberData['d_mcode'];

            $data['oddata'] = $oddata;
            // 加價購細項
            $data['Adata'] = $Adata = $this->mymodel->WriteSql('select d_id,d_title,d_img,d_price,d_status,d_content from orders_makeup_detail where OID=' . $dbdata['d_id'] . '');
            // 試用品細項
            $data['Tdata'] = $Tdata = $this->mymodel->WriteSql('select d_id,d_title,d_img,d_model from orders_trial_detail where OID=' . $dbdata['d_id'] . '');
            // 贈品細項
            $data['Gdata'] = $Gdata = $this->mymodel->WriteSql('select d_title,d_img from orders_gift_detail where OID=' . $dbdata['d_id'] . '');
            // 細項狀態
            $data['Ostatus'] = $this->mymodel->GetConfig('12');
            // 細項物流商
            $data['Oship_company'] = $this->mymodel->SelectSearch('ship', '', 'd_id,d_title', 'where d_enable="Y"');
            // 細項運費
            $Freight = $this->mymodel->SelectSearch('freight', '', 'd_id,d_title', 'where d_enable="Y"');
            $data['Oship_freight'] = array_column($Freight, 'd_title', 'd_id');
            // 最後編輯者顯示時間
            $dbdata['d_lastedit'] = $dbdata['d_lastedit'] . '--' . $dbdata['d_update_time'];
            // 訂單不可逆操作
            //p($dbdata);die;
            $this->DelectStatus($dbdata['d_orderstatus']);

            //隱藏欄位
            if ($dbdata['d_invoice'] == 1) {
                for ($i = 35; $i <= 40; $i++) {
                    unset($this->tableful->Menu[$i]);
                }
            } elseif ($dbdata['d_invoice'] == 2) {

                //$this->tableful->TableTreat(35);

                for ($i = 37; $i <= 40; $i++) {
                    unset($this->tableful->Menu[$i]);
                }
                if ($dbdata['d_donate'] != 'Other') {
                    unset($this->tableful->Menu[36]);
                } else {
                    $this->tableful->Menu[35]['Config'] = array('Other' => '其他捐贈');
                }
            } else {
                for ($i = 35; $i <= 36; $i++) {
                    unset($this->tableful->Menu[$i]);
                }
                // 地址拼湊
                $dbdata['d_iaddress'] = $dbdata['d_Invoicezip'] . $dbdata['d_Invoicecity'] . $dbdata['d_Invoicearea'] . $dbdata['d_iaddress'];
            }

            // 客戶代號
            $mcode[] = array('d_fname' => 'd_mcode', 'd_title' => '客戶代號', 'd_type' => 7);
            array_splice($this->tableful->Menu, 2, 0, $mcode);
            $data['dbdata'] = $dbdata;

            //p( $data);
            // 訂單完成 選項拔除 不讓人為操作
            //unset($this->tableful->Menu[48]['Config'][4]);
            //p($this->tableful->Menu);
            // $this->load->view(''.$this->AdminName.'/autopage/_info',$data);
            $this->load->view('' . $this->AdminName . '/' . $this->DBname . '/_info', $data);
        } else {
            $this->useful->AlertPage('', '操作錯誤');
        }

    }
    // 編輯
    public function edit()
    {


        $this->load->library('mylib/CheckInput');
        $this->load->model('MyModel/Webmodel', 'webmodel');
        $check = new CheckInput;
        $url = $dbname = $msg = '';
        foreach ($this->tableful->Search as $key => $value) {
            if ($value[2] == '_CheckFile') {
                if ((empty($_POST['' . $key . '_ImgHidden']) and $value[0] == 8) or (empty($_POST['' . $key . '_Hidden']) and $value[0] == 14)) {
                    $check->fname[] = array($value[2], $key, $value[1]);
                }

            } else {
                $check->fname[] = array($value[2], Comment::SetValue($key), $value[1]);
            }

        }

        /*特殊檢查位置*/
        if (isset($_POST['d_orderstatus'])) {
            if ($_POST['d_orderstatus'] == 3 && ($_POST['d_successdate'] == '0000-00-00' or empty($_POST['d_successdate']))) { // 訂單狀態為已出貨，管理者備註儲存
            	$_POST['d_oldcontent'] = $_POST['d_admincontent'];
            	$_POST['d_admincontent'] = '';
            }
        } else { // 已完成後訂單已鎖死，移除必填檢查
        	unset($check->fname);
        }
        /*特殊檢查位置*/

        $Cck = $check->main('');
        if (!empty($Cck)) {
        	echo $check->main($url);
        	return '';
        }
        /*特殊檢查位置*/
        $Aacc = ($_SESSION[CCODE::ADMIN]['Aacc'] == 'rootshin') ? 'rootb' : $_SESSION[CCODE::ADMIN]['Aacc'];
        $_POST['d_lastedit'] = $Aacc;

        // 細項更改
        if (isset($_POST['d_orderstatus']) && $_POST['d_orderstatus'] != 4) {
        	$this->UpdateDetail('d_status', $_POST['Ostatus']);
        	$this->UpdateDetail('d_ship_date', $_POST['Oship_date']);
        	$this->UpdateDetail('d_arrival_date', $_POST['Oarrival_date']);
        	$this->UpdateDetail('d_shipnumber', $_POST['Oship_number']);
        	$this->UpdateDetail('SHID', $_POST['Oship_company']);
        	isset($_POST['Oship_pfreight']) ? $this->UpdateDetail('d_pfreight', $_POST['Oship_pfreight']) : '';
        }
        /*特殊檢查位置*/

        $post = (!empty($_POST)) ? $_POST : '';
        $d_id = (!empty($_POST['d_id'])) ? $_POST['d_id'] : '';
        $dbname = (!empty($_POST['dbname'])) ? $_POST['dbname'] : '';

        $dbdata = $this->useful->DB_Array($post, $d_id);

        $UnsetArray = array('d_id', 'odckb', 'dbname', 'BackPageid', 'Ostatus', 'Bstatus', 'Oship_date', 'Oarrival_date', 'Oship_number', 'Oship_company', 'Oship_pfreight');
        $dbdata = $this->useful->UnsetArray($dbdata, $UnsetArray);

        /*特殊檢查位置*/
        if (isset($_POST['d_orderstatus'])) {
            // 已出貨且出貨日為空 或 退貨已完成
        	if (($_POST['d_orderstatus'] == 3 and ($dbdata['d_successdate'] == '0000-00-00' or empty($dbdata['d_successdate']))) || $_POST['d_orderstatus'] == 6) {
        		$dbdata['d_successdate'] = date('Y-m-d H:i:s');
                // 是否已達升級標準
                // $this->UpdateLv($d_id);
        	}

        	$query = $this->mymodel->OneSearchSql('orders', '*', array('d_id' => $d_id));
            // 取消訂單後，庫存回充
            if ($_POST['d_orderstatus'] == 8 && $query['d_orderstatus'] == 7) { //必須是原為取消訂單改為取消完成才會回充，防呆
            	if ($query['d_usebonus']>0) {
            		$this->autoful->ReBackBonus($query['d_usebonus'],$query['MID'],$query['OID']);
            	}
            	$this->autoful->ReStock($d_id);
            } else if ($_POST['d_orderstatus'] == 11) { // 報價已完成，改變訂單總額
            	$specfreight = isset($_POST['Oship_pfreight']) ? array_sum(array_values($_POST['Oship_pfreight'])) : 0;
            	$new_total = $query['d_freight'] + $query['d_bigfreight'] + $query['d_outisland'] + $specfreight + $query['d_price'] - $query['d_usebonus'];
            	$dbdata['d_total'] = $new_total;
            	$dbdata['d_specfreight'] = $specfreight;
                // 寄發通知信
            	$CTitle = $this->webmodel->BaseConfig('6');
            	$Subject = $CTitle['d_title'] . '-訂單報價已完成通知信';
            	$Message = '您好，您的訂單 ' . $query['OID'] . ' 已完成報價，點選以下連結可繼續完成訂單！<br>提醒您，超過7天未選擇繼續付款，系統將自動取消該訂單，有問題歡迎請洽服務人員。<br><a href="' . base_url('member/orders/info/' . $d_id) . '">點我繼續完成</a>';
            	$this->tableful->Sendmail($query['d_mail'], $Subject, $Message);
                // 寫入報價完成日
            	if ($query['d_specsuccessdate'] == '0000-00-00' or empty($query['d_specsuccessdate'])) {
            		$dbdata['d_specsuccessdate'] = date('Y-m-d H:i:s');
            	}
            } else if ($_POST['d_orderstatus'] == 6) { // 退貨已完成
            	$return_data = $this->Return_count($query);
            	$dbdata = array_merge($dbdata, $return_data);
            }

        }

        /*特殊檢查位置*/

        $msg = $this->mymodel->UpdateData($dbname, $dbdata, ' where d_id=' . $d_id . '');

        $this->useful->AlertPage($this->AdminName . '/' . $dbname . '/' . $dbname, '修改成功');
    }


    // 刪除
    public function deletefile()
    {
    	if ($_POST['deltype'] == 'Y') {
    		$dbname = $_POST['dbname'];
    		$this->mymodel->DelectData($dbname, ' where d_id=' . $_POST['d_id'] . '');
    		$this->useful->AlertPage($this->AdminName . '/' . $dbname . '/' . $dbname, '刪除成功');
    	} else {
    		$this->useful->AlertPage('', '操作錯誤');
    	}

    }
    // 生成銷貨單
    public function createRetrun()
    {
    	$d_id = $this->input->post('id', true);
    	if ($this->input->is_ajax_request() && !empty($d_id)) {
    		$Odata = $this->mymodel->WriteSql('select * from orders where d_id=' . $d_id . ' and (d_orderstatus=5 or d_orderstatus=6)', '1');
            // 退貨處理中 or 退貨已完成
    		if (!empty($Odata) && $Odata['RID'] == "") {
    			$return_data = $this->Return_count($Odata);
    			$date = date('Ymd');
    			$GetRID = $this->mymodel->WriteSql('select RID from orders where SUBSTRING(RID,1,8)=' . $date . '  order by RID desc limit 0,1', '1');
    			$return_data['RID'] = !empty($GetRID) ? $GetRID['RID'] + 1 : $date . '0001';
    			$return_data['d_return_time'] = date('YmdHis');
    			$this->mymodel->UpdateData('orders', $return_data, ' where d_id=' . $d_id . '');
    			echo 'OK';
    			exit();
    		}
    	}
    }

    // 訂單不可逆操作
    private function DelectStatus($Status)
    {
    	//p($this->tableful->Menu);echo $Status;die;

    	//p($this->tableful->Menu[47]);
        if ($Status == 10 || $Status == 11) { // 報價處理中或報價完成時，只出現報價處理中、報價完成 2種選項
        	// 先不移除，不然沒有付款狀態，無法送出
        	/*for ($i = 0; $i < 10; $i++) {
        		unset($this->tableful->Menu[47]['Config'][$i]);
        	}*/
        } else {
        	//echo "<BR>測試2<BR>";
            if ($Status == 9) { // 刷卡失敗或退貨已完成，訂單狀態鎖死
            	$this->tableful->Menu[47]['d_type'] = 7;
            } else if ($Status == 4 || $Status == 8 || $Status == 6) { // 交易完成或取消完成，全部畫面鎖死單純觀看，只留備註可填寫
            	foreach ($this->tableful->Menu as $k => $v) {
                    if ($v['d_type'] != 20) { //略過DIV標籤
                    	if ($k == 49) {
                    		$this->tableful->Menu[$k]['d_type'] = 5;
                    	} else {
                    		$this->tableful->Menu[$k]['d_type'] = 7;
                    	}
                    }
                }
                //echo "<BR>測試3<BR>";
            } else {
            	//echo "<BR>測試4<BR>";
                // 訂單完成、刷卡失敗、報價處理中、報價完成 選項拔除
            	unset($this->tableful->Menu[47]['Config'][4]);
            	unset($this->tableful->Menu[47]['Config'][9]);
            	unset($this->tableful->Menu[47]['Config'][10]);
            	unset($this->tableful->Menu[47]['Config'][11]);
                if ($Status == 3) { //已出貨
                    $this->tableful->Menu[48]['d_type'] = 7; //管理者備註從隱藏改為純觀看
                }
            }
        }
    }
    // 細項更改
    private function UpdateDetail($field_name, $detail)
    {
    	foreach ($detail as $key => $value) {
    		$udata = array($field_name => $value);
    		$this->mymodel->UpdateData('orders_detail', $udata, ' where d_id=' . $key . '');
    	}
    }
    // 退貨紅利計算
    private function Return_count($OrderData)
    {

        // 退貨總額 一般運費退貨總計 加收運費 退還紅利 退還現金 紅利重算 需再付現金
    	$return_total = $return_Ototal = $freight = $return_reback = $return_money = $return_point = $need_pay = 0;
        // 非一般運費總額
    	$Ftotal = $this->mymodel->WriteSql('select sum(d_pfreight) as d_pfreight from orders_detail where OID=' . $OrderData['d_id'] . ' and d_pfreight_lv!=1', '1');
        // 原一般運費總額
    	$One_total = $this->mymodel->WriteSql('select sum(d_total) as d_total from orders_detail where OID=' . $OrderData['d_id'] . ' and d_pfreight_lv=1', '1');
        // 原一般運費(離島加收)
    	$One_out = abs($Ftotal['d_pfreight'] - ($OrderData['d_bigfreight'] + $OrderData['d_specfreight'] + $OrderData['d_outisland']));

    	if (!isset($_POST['Ostatus'])) {
    		$GetOstatus = $this->mymodel->WriteSql('select d_id,d_status from orders_detail where OID=' . $OrderData['d_id']);
    		$Ostatus = array_column($GetOstatus, 'd_status', 'd_id');
    	} else {
    		$Ostatus = $_POST['Ostatus'];
    	}

    	foreach ($Ostatus as $key => $value) {
            if ($value == 3 || $value == 4) { // 細項 退貨申請 或 退貨完成
            	$return_detail = $this->mymodel->OneSearchSql('orders_detail', 'd_total,d_pfreight,d_pfreight_lv', array('d_id' => $key));
            	if ($return_detail['d_pfreight_lv'] == 1) {
            		$return_Ototal += $return_detail['d_total'];
            	}
            	$return_total += $return_detail['d_total'] + $return_detail['d_pfreight'];
            }
        }

        if ($return_total > 0) {

        	$OneFreight = $this->mymodel->OneSearchSql('freight', 'd_free,d_freight,d_outisland', array('d_id' => 1));
            // 原訂單內有買過一般運費商品
        	if ($One_total['d_total'] > 0) {
                if ($return_Ototal == $One_total['d_total']) { // 一般運費商品全退光，運費退還
                    // 退還一般運費
                	$return_total += ($OrderData['d_freight'] > 0 ? $OrderData['d_freight'] : 0) + ($OrderData['d_logistics'] == 2 ? $One_out : 0);
                } else if ($One_total['d_total'] - $return_Ototal < $OneFreight['d_free'] && $OrderData['d_freight'] == 0) { // 退貨後未達免運且之前沒收
                    // 加收運費
                	$freight = $OneFreight['d_freight'];
                }
            }

            // 退貨金額 > 原實際支付
            if ($OrderData['d_total'] < $return_total) {
                $return_reback = $return_total - $OrderData['d_total']; // 退還紅利
                if ($OrderData['d_total'] > 0) {
                    $return_money = $OrderData['d_total']; // 退還金額 = 原單實際支付金額
                }
            } else {
                $return_money = $return_total; // 退還金額 = 退貨金額
            }

            // 加收運費
            if ($freight > 0) {
            	if ($return_reback > $freight) {
            		$return_reback -= $freight;
            	} else if ($return_money > $freight - $return_reback) {
            		$return_money = $return_money - ($freight - $return_reback);
            		$return_reback = 0;
            	} else {
                    // 需再付運費
            		$need_pay = $freight - $return_reback - $return_money;
            		$return_reback = $return_money = 0;
            	}
            }

            $BonusArray = json_decode($OrderData['d_bonusarr'], true);
            // 退貨後重計獲得紅利 退還現金小於 當初實際支付之現金
            if ($return_money < ($OrderData['d_price'] - $OrderData['d_usebonus'])) {
            	$return_point = $this->autoful->CountBonus($BonusArray, $OrderData['d_price'] - $OrderData['d_usebonus'] - $return_money, array_keys($_POST['Ostatus']));
            }

            $udata['d_return_reback'] = $return_reback;

            // 立刻退還紅利
            if (isset($_POST['d_orderstatus']) && $_POST['d_orderstatus'] == 6) {
            	if ($return_reback > 0) {
            		$this->autoful->ReBackBonus($return_reback,$OrderData['MID'],$OrderData['OID']);
            	}
            }

        }

        $udata['d_return_total'] = $return_total;
        $udata['d_return_point'] = $return_point;
        $udata['d_return_money'] = $return_money;
        $udata['d_return_pay'] = $need_pay;

        return $udata;
    }
}
