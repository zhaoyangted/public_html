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
		/* header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */
		header("Access-Control-Allow-Credentials: true");
		parent::__construct();
		//$this->load->database();
		$this->load->model('MyModel/Webmodel', 'webmodel');
		$this->autoful->FrontConfig();
		// 網頁標題
		$this->NetTitle = '產品介紹';
		//$this->autoful->FrontConfig();
		// 網頁標題
		//$this->NetTitle = '產品介紹';
		//$this->load->model('ContactModel', 'cm');
	}
	public function toplist_get($TID = ''){
		$Pages=$this->get('page');
		$Limit=$this->get('limit');
		if (empty($TID)) {
			$this->useful->AlertPage('', '操作錯誤');
			exit();
		}
		$data = array();
		// 分類撈取
		$this->GetProductsType($TID);
		// 各頁面的SEO
		$this->NetTitle = (!empty($this->MenuData['d_stitle']) ? $this->MenuData['d_stitle'] : $this->NetTitle);
		$this->Seokeywords = (!empty($this->MenuData['d_skeywords']) ? $this->MenuData['d_skeywords'] : '');
		$this->Seodescription = (!empty($this->MenuData['d_sdescription']) ? $this->MenuData['d_sdescription'] : '');
		// 產品撈取
		$Pdata = $this->mymodel->APISelectPage('products p left join products_brand pb on pb.d_id=p.BID
		left join freight f on f.d_id=p.FID', 'p.*,pb.d_id as pbid,pb.d_title as pbtitle,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where p.d_enable="Y" and (p.TID like "%' . $TID . '@#%" or p.TID like "%@#' . $TID . '%" or TID like "%@#' . $TID . '@#%" or TID=' . $TID . ')', 'd_update_time desc',  $Pages,$Limit);
		// 根據會員等級顯示金額
		$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
		// print_r($Pdata);
		$data['dbdata'] = $Pdata;
		$data['Menudata'] = $this->MenuData;
		$data['Menu']=$this->Menu;
		if ($data) {
			$this->response($data,200);
			} else {
				$this->response(Null,404);
			}
	}
	public function plist_get($TID = '')
	{
		$Pages=$this->get('page');
		$Limit=$this->get('limit');
		$Order=$this->get('order');
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
		$data['Orderid'] = $Orderid = (!empty($Order) ? $Order : '1');
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
				$Pdata = $this->mymodel->APISelectPage('products p left join products_brand pb on pb.d_id=p.BID
				left join freight f on f.d_id=p.FID', 'p.*,pb.d_id as pbid,pb.d_title as pbtitle,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where p.d_enable="Y" and (p.TID like "' . $TypeData['TID'] . '@#%" or p.TID like "%@#' . $TypeData['TID'] . '" or p.TID like "%@#' . $TypeData['TID'] . '@#%" or p.TID=' . $TypeData['TID'] . ') and (p.TTID like "' . $TypeData['TTID'] . '@#%" or p.TTID like "%@#' . $TypeData['TTID'] . '" or p.TTID like "%@#' . $TypeData['TTID'] . '@#%" or p.TTID=' . $TypeData['TTID'] . ') and (p.TTTID like "' . $TID . '@#%" or p.TTTID like "%@#' . $TID . '" or p.TTTID like "%@#' . $TID . '@#%" or p.TTTID=' . $TID . ')', $Order, $Pages,$Limit );
			} else {
				// 產品撈取
				$Pdata = $this->mymodel->APISelectPage('products p left join products_brand pb on pb.d_id=p.BID
				left join freight f on f.d_id=p.FID', 'p.*,pb.d_id as pbid,pb.d_title as pbtitle,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where p.d_enable="Y" and (p.TID like "' . $TypeData['TID'] . '@#%" or p.TID like "%@#' . $TypeData['TID'] . '" or p.TID like "%@#' . $TypeData['TID'] . '@#%" or p.TID=' . $TypeData['TID'] . ') and (p.TTID like "' . $TID . '@#%" or p.TTID like "%@#' . $TID . '" or p.TTID like "%@#' . $TID . '@#%" or p.TTID=' . $TID . ')', $Order, $Pages, $Limit);
			}
			// 根據會員等級顯示金額
			if (!empty($Pdata['dbdata'])) {$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
			$data['dbdata'] = $Pdata;
			$data['Menudata'] = $this->MenuData;
			$data['Menu']=$this->Menu;} else {
				$data=[];
			}
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
		
		//get 會員價
		if(((!empty($this->autoful->DiscountData[$dbdata['d_id']]) && $this->autoful->DiscountData[$dbdata['d_id']]['GetBonus']=='Y') || empty($this->autoful->DiscountData[$dbdata['d_id']])) && $dbdata['d_bonus']!=0){
			$dbdata['isBonus']=true;
		}
		if(!empty($this->autoful->DiscountData[$dbdata['d_id']])) {
		$dbdata['isSalePrice'] = number_format($this->autoful->DiscountData[$dbdata['d_id']]['d_price']);
		}
		if(!empty($this->autoful->Lvtitle)) { $dbdata['isMember']=$this->autoful->Lvtitle;}
		if(!empty($this->autoful->UpLvtitle)) {
		$dbdata['isNotAvail'] = $this->autoful->UpLvtitle;
		}
		//}
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
		$this->AddVisit($d_id);
		if ($data) {
			//$this->AddVisit($d_id);
			$this->response($data, 200);
		} else {
			$this->response(NULL, 404);
		}
		//$this->load->view('front/products_info',$data);
	}
	public function newproducts_get(){
		$NewProductsData =array();
		//$NewProductsData = $this->mymodel->SelectSearch('products', '', 
		//'d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where d_enable="Y" and d_new="Y"', 'd_sort');
		$NewProductsData = $this->mymodel->WriteSql('
		select p.d_id,p.d_title,p.d_img1,p.d_img2,p.d_price1,p.d_price2,p.d_price3,p.d_price4,p.d_dprice,p.d_sprice,p.d_stock,p.d_model,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,pb.d_id as pbid,pb.d_title as pbtitle,concat(TID,",",TTID,",",p.TTTID) as TID,p.MTID
				from products p
				left join products_brand pb on pb.d_id=p.BID
				left join freight f on f.d_id=p.FID
				where p.d_enable="Y" and p.d_new="Y"
				order by p.d_sort asc
				');

		$NewProductsData = $this->autoful->GetProductPrice($NewProductsData);
		//if ($NewProductsData) {
			//$this->AddVisit($d_id);
			$this->response($NewProductsData, 200);
		//} else {
		//	$this->response(NULL, 404);
		//}
	}
	public function hot_get()
	{
		// Hot
		$HotArray = array();
		$HotData = $this->mymodel->WriteSql('
				select pt.d_title as pttitle,pt.d_id as PTID,p.d_id,p.d_title,p.d_img1,p.d_img2,p.d_price1,p.d_price2,p.d_price3,p.d_price4,p.d_dprice,p.d_sprice,p.d_stock,p.d_model,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,pbh.d_id as pbid,pbh.d_title as pbtitle,concat(p.TID,",",p.TTID,",",p.TTTID) as TID,p.MTID
				from products_hot h
				left join products_brand pb on pb.d_id=h.PID
				
						inner join products p on p.d_id=h.PID
						left join products_brand pbh on pbh.d_id=p.BID
						inner join products_hot_type pt on pt.d_id=h.TID
						left join freight f on f.d_id=p.FID
				where p.d_enable="Y" and h.d_enable="Y" and pt.d_enable="Y"
				order by pt.d_sort asc,h.d_sort asc
			');
	
		// 根據會員等級顯示金額
		$HotData = $this->autoful->GetProductPrice($HotData);
		foreach ($HotData as $h) {
		  if (isset($HotArray[$h['pttitle']]) && count($HotArray[$h['pttitle']]) > 9) {
			continue;
		  }
		  $HotArray[$h['pttitle']][] = $h;
		}
		if ($HotArray) {
			//$this->AddVisit($d_id);
			$this->response($HotArray, 200);
		} else {
			$this->response(NULL, 404);
		}
	}
	//品牌列表
	public function blist_get($BID = '')
	{
		$Pages=$this->get('page');
		$Limit=$this->get('limit');
		$Order=$this->get('order');
		if (empty($BID)) {
			$this->useful->AlertPage('', '操作錯誤');
			exit();
		}
		$data = array();

		// 排序
		$data['OrderArray'] = $OrderArray = array('1' => '依上架時間：新至舊', '2' => '依上架時間：舊至新', '3' => '依價格排序：低至高', '4' => '依價格排序：高至低');
		$data['Orderid'] = $Orderid = (!empty($Order) ? $Order : '1');
		$Order = 'd_update_time desc';
		if ($Orderid == 2) {
			$Order = 'd_update_time';
		} elseif ($Orderid == 3) {
			$Order = 'd_price1 ';
		} elseif ($Orderid == 4) {
			$Order = 'd_price1 desc';
		}

		// 產品撈取
		$Pdata = $this->mymodel->APISelectPage('products p left join products_brand pb on pb.d_id=p.BID
		left join freight f on f.d_id=p.FID', 'p.*,pb.d_id as pbid,pb.d_title as pbtitle,f.d_free,f.d_freight,f.d_title as ftitle,f.d_id as fid,concat(TID,",",TTID,",",TTTID) as TID,MTID,TID as GTID,concat(TTID,",",TTTID) as GTTID ', 'where p.d_enable="Y" and p.BID=' . $BID, $Order, $Pages,$Limit);

		/* $Pdata = $this->mymodel->FrontSelectPage('products', 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID,TID as GTID,concat(TTID,",",TTTID) as GTTID', 'where d_enable="Y" and BID=' . $BID, $Order, '12'); */
		/* $query = $db->query('SELECT d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID,TID as GTID,concat(TTID,",",TTTID) as GTTID FROM products WHERE d_enable="Y" and BID=$BID $Order'); */
		/* $this->db->select('d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID,TID as GTID,concat(TTID,",",TTTID) as GTTID');
		$this->db->from('products');
		$this->db->where('d_enable',"Y");
		$this->db->where('BID',$BID);
		$query=$this->db->get();
		$Pdata['dbdata']=$query->result_array(); */

		//print_r($Pdata);
		// 品牌資料
		$data['BrandData'] = $BrandData = $this->mymodel->OneSearchSql('products_brand', 'd_title', array('d_id' => $BID));

		// 分類撈取
		$this->db->query('SET SESSION group_concat_max_len = 1000000');	// 2020/04/15增加查詢長度

		$Bdata = $this->mymodel->OneSearchSql('products', 'group_concat(TID) as TID,group_concat(TTID) as TTID,group_concat(TTTID) as TTTID', array('BID' => $BID));
		$tID = array_unique(array_filter(explode(',', str_replace('@#', ',', $Bdata['TID']))));
		$ttID = array_unique(array_filter(explode(',', str_replace('@#', ',', $Bdata['TTID']))));
		$tttID = array_unique(array_filter(explode(',', str_replace('@#', ',', $Bdata['TTTID']))));
		$TID = array_sum($tID) == 0 ? '' : implode(',', $tID);
		$TTID = array_sum($ttID) == 0 ? '' : implode(',', $ttID);
		$TTTID = array_sum($tttID) == 0 ? '' : implode(',', $tttID);

		$this->GetProductsType_PID($TID, $TTID, $TTTID);
		// print_r($this->Menu);
		// print_r($TID);

		// print_r($Pdata['dbdata']);
		// $this->GetProductsType($TID1,'1');


		// 根據會員等級顯示金額
		if (!empty($Pdata['dbdata'])) {
			$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
			$data['dbdata'] = $Pdata;
			//$data['Menudata'] = $this->MenuData;
			$data['Menu']=$this->Menu;
		} else {
				$data['dbdata']=[];
			}
		//$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
		//$data['dbdata'] = $Pdata;
		//$data['Menudata'] = $this->MenuData;
		//$data['Menu']=$this->Menu;
		if ($data['dbdata']) {
			//$this->AddVisit($d_id);
			$this->response($data, 200);
		} else {
			$this->response(NULL, 404);
		}
		//$this->load->view('front/products_blist', $data);
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
	public function search_post()
	{
		$keyword=$this->post('Pkeyword');
		$type=$this->post('Ptype');
		$data = array();
		$Pages=$this->post('page');
		$Limit=$this->post('limit');
		$Order=$this->post('order');
		if (empty($keyword)) {	// 2020/05/06 可以不輸入文字搜尋
			// $this->useful->AlertPage('','請輸入搜尋文字');
			// exit();
			if (!empty($type)) {
				$data['TID'] = $TID = $type;
				$data['searchtext'] = '';
				$Search = 'and (TID like "' . $type . '@#%" or TID like "%@#' . $type . '" or TID like "%@#' . $type . '@#%" or TID=' . $type . ')';
				// 分類撈取
				$this->GetProductsType($TID);
			} else {
				$this->useful->AlertPage('', '請選擇分類');
				exit();
			}
		} else {

			$data['searchtext'] = $keyword = $keyword;

			if (!empty($type)) {
				$data['TID'] = $TID = $type;
				$Search = 'and (TID like "' . $TID . '@#%" or TID like "%@#' . $TID . '" or TID like "%@#' . $TID . '@#%" or TID=' . $TID . ') and (d_title like "%' . $keyword . '%" or d_model like "%' . $keyword . '%")';
				// 分類撈取
				$this->GetProductsType($TID);
			} else {
				$Search = 'and (d_title like "%' . $keyword . '%" or d_model like "%' . $keyword . '%")';
			}
			// 總碼
			$Allspec = $this->mymodel->WriteSql('select PID from products_allspec where d_title like "%' . $keyword . '%" and PID !=""', '');
			if (!empty($Allspec)) {
				$Search .= ' or d_id in(';
				foreach (array_column($Allspec, 'PID') as $v) {
					$Search .= implode(',', explode('@#', $v));
				}
				$Search .= ')';
			}
		}

		// 排序
		$data['OrderArray'] = $OrderArray = array('1' => '依上架時間：新至舊', '2' => '依上架時間：舊至新', '3' => '依價格排序：低至高', '4' => '依價格排序：高至低');
		$data['Orderid'] = $Orderid = (!empty($_POST['Orderby']) ? $_POST['Orderby'] : '1');
		$Order = 'd_update_time desc';
		if ($Orderid == 2) {
			$Order = 'd_update_time';
		} elseif ($Orderid == 3) {
			$Order = 'd_price1 ';
		} elseif ($Orderid == 4) {
			$Order = 'd_price1 desc';
		}

		// 產品撈取
		$Pdata = $this->mymodel->APISelectPage('products', 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where d_enable="Y" ' . $Search . ' and TID!=""', $Order,$Pages,'12');
		// if($this->autoful->Mtype!='1'){
		$Mtype = explode(',', str_replace('@#', ',', $this->autoful->Mtype));

		foreach ($Pdata['dbdata'] as $key => $value) {

			$TID = implode(',', explode('@#', $value['TID']));
			$TID = implode(',', array_filter(explode(',', $TID)));

			$this->db->query('SET SESSION group_concat_max_len = 1000000');	// 2020/04/15增加查詢長度

			$TypeData = $this->mymodel->WriteSql('select GROUP_CONCAT(MTID) as MTID from products_type where d_id in(' . $TID . ')', '1');
			$TypeData = array_unique(explode(',', str_replace('@#', ',', $TypeData['MTID'] . $value['MTID'])));
			// print_r($TypeData);
			$result = array_intersect($TypeData, $Mtype);
			if (count($result) == 0) {
				$Pdata['dbdata'][$key]['d_price'] = $value['d_price1'];
			} else {
				$Pdata['dbdata'][$key]['d_price'] = $value['d_price' . $this->autoful->Mlv . ''];
			}
		}

		// }
		if (!empty($type)) {
		$data['Menudata'] = $this->MenuData;
		$data['Menu']=$this->Menu;
		}else{
		$data['Menudata'] = $this->autoful->SideMenu;
		}
		// 根據會員等級顯示金額
		$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
		$data['dbdata'] = $Pdata;
		// print_r($Pdata);
		if ($data['dbdata']) {
			//$this->AddVisit($d_id);
			$this->response($data, 200);
		} else {
			$this->response(NULL, 404);
		}
		//$this->load->view('front/products_search', $data);
	}
	// 活動內頁
	public function sales_get($SID = '')
	{
		$SID="3";
		/* if (empty($SID)) {
			$this->useful->AlertPage('', '操作錯誤');
			exit();
		} */
		$data = array();

		// 活動撈取
		$data['Adata'] = $Adata = $this->mymodel->WriteSql('
			select s.d_title,s.PID
			from products_sale s
			left join products_sale_type t on t.d_id=s.TID
			left join products_sale_detail d on d.SID=s.d_id
			where s.d_id=' . $SID . ' and s.d_enable="Y" and t.d_start<="' . date('Y-m-d') . '" and t.d_end>="' . date('Y-m-d') . '" and t.d_start!="" and t.d_end!="" and t.d_enable="Y" and d.d_enable="Y"
			', '1');

		if (empty($Adata)) {
			$this->useful->AlertPage('', '操作錯誤');
			exit();
		}

		// 排序
		$data['OrderArray'] = $OrderArray = array('1' => '依上架時間：新至舊', '2' => '依上架時間：舊至新', '3' => '依價格排序：低至高', '4' => '依價格排序：高至低');
		$data['Orderid'] = $Orderid = (!empty($_POST['Orderby']) ? $_POST['Orderby'] : '1');
		$Order = 'd_update_time desc';
		if ($Orderid == 2) {
			$Order = 'd_update_time';
		} elseif ($Orderid == 3) {
			$Order = 'd_price1 ';
		} elseif ($Orderid == 4) {
			$Order = 'd_price1 desc';
		}

		// 產品撈取
		$Pdata = $this->mymodel->FrontSelectPage('products', 'd_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,concat(TID,",",TTID,",",TTTID) as TID,MTID', 'where d_enable="Y" and d_id in (' . str_replace('@#', ',', $Adata['PID']) . ')', $Order, '12');

		// 根據會員等級顯示金額
		$Pdata['dbdata'] = $this->autoful->GetProductPrice($Pdata['dbdata']);
		$data['dbdata'] = $Pdata;
		if ($data['dbdata']) {
			//$this->AddVisit($d_id);
			$this->response($data, 200);
		} else {
			$this->response(NULL, 404);
		}
		// print_r($Pdata);
		//$this->load->view('front/products_sales', $data);
	}
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
					$Menutitle .= '<li><a href="' . '/products/top_list/' . $rs['d_id'] . '' . '">' . $rs['d_title'] . '</a></li>';
				} else {
					$Menutitle .= '<li><a href="' . '/products/products_list/' . $rs['d_id'] . '' . '">' . $rs['d_title'] . '</a></li>';
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
