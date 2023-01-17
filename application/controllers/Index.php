<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct(){
		parent::__construct();
        // 前台共用
        $this->autoful->FrontConfig();
	}
    public function Index(){
        $data=$HotData=array();
				// 大圖會員權限可看
				$Lv_where = !empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y' ? ' and (d_lv like "'.$_SESSION[CCODE::MEMBER]['Mlv'].'@#%" or d_lv like "%@#'.$_SESSION[CCODE::MEMBER]['Mlv'].'" or d_lv like "%@#'.$_SESSION[CCODE::MEMBER]['Mlv'].'@#%" or d_lv='.$_SESSION[CCODE::MEMBER]['Mlv'].' or d_lv = "" )' : ' and d_lv="" ';
        // Banner
        $BannerData=$this->mymodel->WriteSql('
        SELECT d_img,d_link FROM `banner`
            where if(d_start!="",d_start<=now(),1) and (if(d_end!="",d_end>=now(),1) or d_end="0000-00-00 00:00") and d_enable="Y"'.$Lv_where.'
            ORDER BY d_sort
        ');
        $data['BannerData']=$BannerData;
        // Action
        $data['ActionData']=$this->mymodel->SelectSearch('action_list','','d_title,d_img,d_link','where d_enable="Y"','d_sort');
        // NEW
        $NewProductsData=$this->mymodel->SelectSearch('products','','d_id,d_title,d_img1,d_price1,d_price2,d_price3,d_price4,d_dprice,d_sprice,concat(TID,",",TTID,",",TTTID) as TID,MTID','where d_enable="Y" and d_new="Y"','d_sort');
        // 根據會員等級顯示金額
        $NewProductsData=$this->autoful->GetProductPrice($NewProductsData);

        $data['NewProductsData']=$NewProductsData;

        // Hot
				$HotArray = array();
				$HotData=$this->mymodel->WriteSql('
        	select pt.d_title as pttitle,pt.d_id as PTID,p.d_id,p.d_title,p.d_img1,p.d_price1,p.d_price2,p.d_price3,p.d_price4,p.d_dprice,p.d_sprice,concat(p.TID,",",p.TTID,",",p.TTTID) as TID,p.MTID
        	from products_hot h
					inner join products p on p.d_id=h.PID
					inner join products_hot_type pt on pt.d_id=h.TID
        	where p.d_enable="Y" and h.d_enable="Y" and pt.d_enable="Y"
        	order by pt.d_sort asc,h.d_sort asc
        ');

				// 根據會員等級顯示金額
				$HotData=$this->autoful->GetProductPrice($HotData);
				foreach ($HotData as $h) {
					if (isset($HotArray[$h['pttitle']]) && count($HotArray[$h['pttitle']])>9) {
						continue;
					}
					$HotArray[$h['pttitle']][]=$h;
				}

        $data['HotData']=$HotArray;
        // News
        $data['NewsData']=$this->mymodel->WriteSql('
        	select n.d_id,nt.d_color,nt.d_title as nttitle,SUBSTR(n.d_date, 1,10) as d_date,n.d_title
        	from news n
        	inner join news_type nt on nt.d_id=n.TID
        	where n.d_enable="Y"  and n.d_date<=now()
        	order by n.d_date desc limit 6
        ');
        // print_r($NewsData);
        // 紀錄瀏覽人數
        $this->AddVisit();
        $this->load->view('front/index',$data);
    }
    // 紀錄瀏覽人數
    private function AddVisit(){
        if(empty($_SESSION[CCODE::MEMBER]['VisitCount'])){
            $sql = "INSERT visit_count (d_date, d_num) values ('".date('Y-m-d')."', '1') ON DUPLICATE KEY UPDATE d_num=d_num+1";
            $this->db->query($sql);
            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
              $ip=$_SERVER['HTTP_CLIENT_IP'];
            }else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
              $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
              $ip=$_SERVER['REMOTE_ADDR'];
            }
            $_SESSION[CCODE::MEMBER]['VisitCount']=$ip;
        }
    }
    // 404頁面
    public function show404(){
        $this->load->view('errors/404');
    }


}
