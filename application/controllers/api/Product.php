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
class Product extends RestController
{

	function __construct()
	{
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->database();
		$this->load->model('MyModel/Webmodel', 'webmodel');
		$this->autoful->FrontConfig();
		// 網頁標題
		$this->NetTitle = '產品介紹';
		//$this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
	public function plist_get($TID = '')
	{

		if (empty($TID)) {
			$this->useful->AlertPage('', '操作錯誤');
			exit();
		}
		$data = array();
		// 判斷此分類是第幾層
		$data['TypeData'] = $TypeData = $this->mymodel->OneSearchSql('products_type', 'd_id,d_title,TID,TTID,d_stitle,d_skeywords,d_sdescription', array('d_id' => $TID, 'd_enable' => "Y"));
		$TID1 = $TypeData['TID'];

		// 各頁面的SEO
		$this->NetTitle = (!empty($TypeData['d_stitle']) ? $TypeData['d_stitle'] : $this->NetTitle);
		$this->Seokeywords = (!empty($TypeData['d_skeywords']) ? $TypeData['d_skeywords'] : '');
		$this->Seodescription = (!empty($TypeData['d_sdescription']) ? $TypeData['d_sdescription'] : '');

		// 分類撈取
		$this->GetProductsType($TID1);
		// 排序
		$data['OrderArray'] = $OrderArray = array('1' => '依上架時間：新至舊', '2' => '依上架時間：舊至新', '3' => '依價格排序：低至高', '4' => '依價格排序：高至低', '5' => '瀏覽最多商品');
		$data['Orderid'] = $Orderid = (!empty($_POST['Orderby']) ? $_POST['Orderby'] : '1');
		$Order = 'd_update_time desc';
		if ($Orderid == 2) {
			$Order = 'd_update_time';
		} elseif ($Orderid == 3) {
			$Order = 'd_price1 ';
		} elseif ($Orderid == 4) {
			$Order = 'd_price1 desc';
		} elseif ($Orderid == 5) {
			$Order = 'd_view desc';
		}
		// print_r($_POST);

		// 產品撈取
		if (!empty($TypeData['TID'])) {
			if (!empty($TypeData['TTID'])) {
				// 產品撈取
				$Pdata = $this->mymodel->FrontSelectPage('products', 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where d_enable="Y" and (TID like "' . $TypeData['TID'] . '@#%" or TID like "%@#' . $TypeData['TID'] . '" or TID like "%@#' . $TypeData['TID'] . '@#%" or TID=' . $TypeData['TID'] . ') and (TTID like "' . $TypeData['TTID'] . '@#%" or TTID like "%@#' . $TypeData['TTID'] . '" or TTID like "%@#' . $TypeData['TTID'] . '@#%" or TTID=' . $TypeData['TTID'] . ') and (TTTID like "' . $TID . '@#%" or TTTID like "%@#' . $TID . '" or TTTID like "%@#' . $TID . '@#%" or TTTID=' . $TID . ')', $Order, '12');
			} else {
				// 產品撈取
				$Pdata = $this->mymodel->FrontSelectPage('products', 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where d_enable="Y" and (TID like "' . $TypeData['TID'] . '@#%" or TID like "%@#' . $TypeData['TID'] . '" or TID like "%@#' . $TypeData['TID'] . '@#%" or TID=' . $TypeData['TID'] . ') and (TTID like "' . $TID . '@#%" or TTID like "%@#' . $TID . '" or TTID like "%@#' . $TID . '@#%" or TTID=' . $TID . ')', $Order, '12');
			}
			// 根據會員等級顯示金額
			$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
			$data['dbdata'] = $Pdata;
		}
		// print_r($Pdata);
		if ($data) {
		$this->response($data,200);
		} else {
			$this->response(Null,404);
		}
	}
	public function index_get($d_id = '')
	{

		/* if (empty($d_id)) {
			//$this->useful->AlertPage('', '操作錯誤');
			exit();
		} */
		$data = array();
		// 撈取產品
		$dbdata = $this->mymodel->WriteSql('
			select p.*,pb.d_id as pbid,pb.d_title as pbtitle,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,concat(p.TID,",",p.TTID,",",p.TTTID) as TID1,p.MTID
			from products p
			left join products_brand pb on pb.d_id=p.BID
			left join freight f on f.d_id=p.FID
			where p.d_id=' . $d_id . ' and p.d_enable="Y"
			', 1);
		/* if (empty($dbdata)) {
			$this->useful->AlertPage('', '此產品已下架');
			exit();
		} */
		// 根據會員等級顯示金額
		// 是否夠資格
		$Chked = 'Y';
		$Mtype = explode(',', str_replace('@#', ',', $this->autoful->Mtype));

		$TID = implode(',', explode('@#', $dbdata['TID1']));
		$TID = implode(',', array_filter(explode(',', $TID)));

		$TypeData = $this->mymodel->WriteSql('select GROUP_CONCAT(MTID) as MTID from products_type where d_id in(' . $TID . ')', '1');
		$TypeData = array_unique(explode(',', str_replace('@#', ',', $TypeData['MTID'] . ',' . $dbdata['MTID'])));
		$result = array_intersect($TypeData, $Mtype);

		$Lvcount = 1;
		if ($this->autoful->Mlv >= 4) {
			$MlvData = $this->mymodel->WriteSql('select d_count from member_lv where d_id=' . $this->autoful->Mlv . '', '1');
			$Lvcount = (100 - $MlvData['d_count']) / 100;
		}
		// echo $Lvcount;


		if ($this->autoful->UserType == 1 or empty($this->autoful->UserType)) {
			$dbdata['d_price'] = $dbdata['d_price1'];
			$this->autoful->Lvtitle = '會員價';
			$this->autoful->UpLvtitle = '沙龍價(銅)';
			$Chked = 'N';
		} else {
			$Mtype1 = explode('@#', $this->autoful->Mtype . '@#' . $this->autoful->Mtype1);
			$Mtype1 = array_unique($Mtype1);

			$MTID = $dbdata['MTID'];
			$TypeData1 = explode('@#', $MTID);
			$result1 = array_intersect($TypeData1, $Mtype1);

			if (count($result1) != 0) {
				$dbdata['d_price'] = $dbdata['d_price3'] * $Lvcount;
			} else {
				$dbdata['d_price'] = $dbdata['d_price2'];
				$this->autoful->Lvtitle = '會員價';
				$this->autoful->UpLvtitle = '沙龍價(銅)';
			}
		}
		// 是否有活動
		$SaleArr = array($dbdata['d_id'], 0, "");
		$this->autoful->ChkSingleSale($SaleArr, $dbdata['d_price']);

		$dbdata['Chked'] = $Chked;
		// print_r($dbdata);
		// 各頁面的SEO
		$this->NetTitle = (!empty($dbdata['d_stitle']) ? $dbdata['d_stitle'] : $this->NetTitle);
		$this->Seokeywords = (!empty($dbdata['d_skeywords']) ? $dbdata['d_skeywords'] : '');
		$this->Seodescription = (!empty($dbdata['d_sdescription']) ? $dbdata['d_sdescription'] : '');

		$data['dbdata'] = $dbdata;

		// 撈取標題
		$data['Menutitle'] = $this->GetMenutitle($dbdata);
		// 會員等級
		$data['ChkType'] = $this->autoful->Mlv;
		// 撈取規格
		$SpecData = $this->mymodel->WriteSql('select PID from products_allspec where (PID like "' . $d_id . '@#%" or PID like "%@#' . $d_id . '" or PID like "%@#' . $d_id . '@#%" or PID=' . $d_id . ')', 1);
		if (!empty($SpecData['PID'])) {
			$data['SpecData'] = $this->mymodel->WriteSql('select d_id,d_spectitle from products where d_enable="Y" and d_id in (' . str_replace('@#', ',', $SpecData['PID']) . ') and d_id!=' . $d_id . '');
		}
		// 撈取試用品資料
		if (!empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y') {
			$data['TrialData'] = $this->mymodel->WriteSql('
						select p.d_id,t.d_try,p.TID
						from products_trial p
						left join products_trial_type t on t.d_id=p.TID
						where (p.PID like "' . $d_id . '@#%" or p.PID like "%@#' . $d_id . '" or p.PID like "%@#' . $d_id . '@#%" or p.PID=' . $d_id . ') and t.d_enable="Y" and p.d_enable="Y" and p.d_stock>0
						', '1');

			// 檢查是否有領過
			if (!empty($data['TrialData'])) {

				if ($data['TrialData']['d_try'] == 1) { // 所有規格均可索取一次
					$chkhad = $this->mymodel->WriteSQL('
						select d_id
						from orders_trial_detail
						where d_enable = "Y" and d_deadline>CURDATE() and MID="' . $_SESSION[CCODE::MEMBER]['LID'] . '" and TID=' . $data['TrialData']['d_id'] . '
						');
				} else { // 選一種規格索取一次
					$AllTrial = $this->mymodel->WriteSQL('select group_concat(d_id) as d_id from products_trial where TID = ' . $data['TrialData']['TID'] . ' group by TID');
					if (!empty($AllTrial)) {
						$chkhad = $this->mymodel->WriteSQL('
							select d_id
							from orders_trial_detail
							where d_enable = "Y" and d_deadline>CURDATE() and MID="' . $_SESSION[CCODE::MEMBER]['LID'] . '" and TID in (' . $AllTrial[0]['d_id'] . ')
							');
					} else {
						$chkhad = array();
					}
				}
				// 檢查是否有被領過
				if (!empty($chkhad)) {
					unset($data['TrialData']);
				}
			}
		}

		// 商品加購
		$data['AddData'] = $this->mymodel->SelectSearch('products_optional', '', 'd_id,d_title,d_price', 'where (PID like "' . $d_id . '@#%" or PID like "%@#' . $d_id . '" or PID like "%@#' . $d_id . '@#%" or PID=' . $d_id . ') and d_enable="Y" and d_stock>0');
		// print_r($data['AddData']);
		// 相關產品推薦
		if (!empty($dbdata['d_push'])) {
			$PushData = $this->mymodel->WriteSql('select d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,concat(TID,",",TTID,",",TTTID) as TID,MTID from products where d_enable="Y" and d_id in (' . str_replace('@#', ',', $dbdata['d_push']) . ')');
			// 根據會員等級顯示金額
			$PushData = $this->autoful->GetProductPrice($PushData);

			$data['PushData'] = $PushData;
		}
		// 看過此商品的人也看過下列商品
		if (!empty($dbdata['d_watch'])) {
			$WatchData = $this->mymodel->WriteSql('select d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID from products where d_enable="Y" and d_id in (' . str_replace('@#', ',', $dbdata['d_watch']) . ')');
			// 根據會員等級顯示金額
			$WatchData = $this->autoful->GetProductPrice($WatchData);
			$data['WatchData'] = $WatchData;
		}

		// 判斷是否還有庫存
		$Qty = 1;
		$Dis = '';
		if ($dbdata['d_stock'] <= 0) {
			$Qty = 0;
			$Dis = 'disabled';
		}
		$data['Qty'] = $Qty;
		$data['Dis'] = $Dis;


		// 最近瀏覽商品紀錄
		if (!empty($_SESSION[CCODE::MEMBER]['Watch'])) {
			$Watch = explode('@#', $_SESSION[CCODE::MEMBER]['Watch']);
			$Is = 'N';
			foreach ($Watch as $key => $value) {
				if ($value == $d_id) {
					$Is = 'Y';
				}
			}
			if ($Is == 'N')
				$Watch[] = $d_id;
			$_SESSION[CCODE::MEMBER]['Watch'] = implode('@#', $Watch);

			$TodayWatchData = $this->mymodel->WriteSql('select d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID from products where d_enable="Y" and d_id in (' . str_replace('@#', ',', $_SESSION[CCODE::MEMBER]['Watch']) . ')');
			// 根據會員等級顯示金額
			$TodayWatchData = $this->autoful->GetProductPrice($TodayWatchData);
			$data['TodayWatchData'] = $TodayWatchData;
		} else {
			$_SESSION[CCODE::MEMBER]['Watch'] = $d_id;
		}
		// 紀錄瀏覽人數
		if ($data) {
			//$this->AddVisit($d_id);
			$this->response($data, 200);
		} else {
			$this->response(NULL, 404);
		}
		//$this->load->view('front/products_info',$data);
	}
	/* function contacts_get() {
        $contacts = $this->cm->get_contact_list();

        if ($contacts) {
            $this->response($contacts, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    function contact_get() {
        if (!$this->get('id')) {
            $this->response(NULL, 400);
        }

        $contact = $this->cm->get_contact($this->get('id'));

        if ($contact) {
            $this->response($contact, 200); // 200 being the HTTP response code
        } else {
            $this->response(NULL, 404);
        }
    } */
	// 分類撈取
	private function GetProductsType($TID = '')
	{
		$Menu = array();
		$Pdata = $this->mymodel->OneSearchSql('products_type', 'd_title,d_img1,d_img2,d_img3,d_img4,d_img5,d_stitle,d_skeywords,d_sdescription', array('d_id' => $TID, 'd_enable' => 'Y'));

		$Sdata = $this->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where TID="' . $TID . '" and TTID=0 and d_enable="Y"', 'd_sort');
		if (!empty($Sdata)) {
			foreach ($Sdata as $key => $svalue) {
				$Subdata = $this->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where TTID="' . $svalue['d_id'] . '" and d_enable="Y"', 'd_sort');
				// if(!empty($Subdata)){
				$Sdata[$key]['Subdata'] = $Subdata;
				// }
			}
		}
		$this->MenuData = $Pdata;
		$this->Menu = $Sdata;
	}
	// 分類撈取(已分類ID篩選)
	private function GetProductsType_PID($TID = '', $TTID = '', $TTTID = '')
	{
		$Menu = $Pdata = array();
		if (!empty($TID)) {
			$Pdata = $this->mymodel->WriteSql('
				select d_id,d_title from products_type where d_id in (' . $TID . ')
				');
		}
		if (!empty($TTID)) {
			foreach ($Pdata as $key => $value) {
				$Sdata = $this->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where TID="' . $value['d_id'] . '" and TTID=0 and d_id in (' . $TTID . ')', 'd_sort');
				if (!empty($Sdata) && !empty($TTTID)) {
					foreach ($Sdata as $key => $svalue) {
						$Subdata = $this->mymodel->SelectSearch('products_type', '', 'd_id,d_title', 'where TTID="' . $svalue['d_id'] . '" and d_id in (' . $TTTID . ')', 'd_sort');
						$Sdata[$key]['Subdata'] = $Subdata;
					}
				}
				$Menu[$value['d_title']] = $Sdata;
			}
		}

		$this->Menu = $Menu;
	}
	// 撈取標題
	private function GetMenutitle($dbdata)
	{
		//p($dbdata);
		$a = array();

		$TTID = explode('@#', $dbdata['TTID']);
		$TTTID = explode('@#', $dbdata['TTTID']);

		$last = '';
		//取得最後一個 ID 
		if (!empty($TTTID) && $TTTID != 0) {
			$last = end($TTTID);
		} else {
			$last = end($TTID);
		}
		$rs = $this->mymodel->OneSearchSql('products_type', 'd_id, TID, TTID, d_title', array('d_id' => $last));
		//p($rs);
		$a[]	= $rs['TID'];
		$a[] 	= $rs['TTID'];
		$a[]	= $last;
		//p($a);

		$a = array_unique($a);
		//p($a);

		//p($a);
		$Menutitle = '';
		foreach ($a as $k => $v) {
			if (!empty($v) && $v != 0) {

				$rs = $this->mymodel->OneSearchSql('products_type', 'd_id, d_title', array('d_id' => $v));
				//p($rs);
				if ($k == 0) {
					$Menutitle .= '<li><a href="' . site_url('products/index/' . $rs['d_id'] . '') . '">' . $rs['d_title'] . '</a></li>';
				} else {
					$Menutitle .= '<li><a href="' . site_url('products/products_list/' . $rs['d_id'] . '') . '">' . $rs['d_title'] . '</a></li>';
				}
			}
		}


		//echo $Menutitle;	die;

		return $Menutitle;
	}
	// 紀錄瀏覽人數
	private function AddVisit($id = '')
	{
		if (empty($_SESSION[CCODE::MEMBER]['VisitProduct_' . $id])) {
			$this->mymodel->SimpleWriteSQL('update products set d_view=d_view+1 where d_id=' . $id . '');
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			$_SESSION[CCODE::MEMBER]['VisitProduct_' . $id] = $ip;
		}
	}
}
