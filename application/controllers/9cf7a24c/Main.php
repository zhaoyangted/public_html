<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {

	public function index(){
		//後台載入函式
		$this->autoful->backconfig();
		// 瀏覽人次資訊
		$data['ChartData']=$this->GetVisit();
		// 聯絡我們總數
		$data['ContactNum']=$this->GetContact();
		// 訂單總數
		$data['OrderNum']=$this->GetOrder();

		$this->load->view($this->autoful->FileName.'/main/index',$data);

	}
	// 瀏覽人次資訊
	private function GetVisit(){
		if(!empty($_POST['year'])){
			$Year=$_POST['year'];
			$Month=$_POST['month'];
			$VisitNum=$this->mymodel->WriteSql("
				SELECT d_date,d_num FROM `visit_count` where substr(d_date,1,7) ='".$Year."-".substr('00'.$Month,-2)."' order by d_date
			");
		}else{
			$VisitNum=$this->mymodel->WriteSql("
				SELECT d_date,d_num FROM `visit_count` where date_format(d_date, '%Y%m' ) = date_format( curdate() , '%Y%m') order by d_date
			");
			$Year=date('Y');
			$Month=date('m');
		}
		// 年度網頁瀏覽總人數
		$VisitData['AllYear']=$this->mymodel->WriteSql("
			SELECT SUM(d_num) as Allnum FROM `visit_count` where substr(d_date,1,4) ='".$Year."'
		",'1');
		$VisitData['WeekData']=$this->GetWeekData($Year,$Month);
		$VisitData['NowYear']=$Year;
		$VisitData['NowMonth']=substr('00'.$Month,-2);
		$VisitData['VisitNum']=$VisitNum;
		return $VisitData;
	}
	// 當月各周人數統計
	private function GetWeekData($Year,$Month){
		$WeekData['EndDay']=$EndDay=date('t', strtotime(''.$Year.'-'.$Month.''));
		$WeekData['WeekArray']=$WeekArray=array(
			'0'=>array('01','07'),
			'1'=>array('08','14'),
			'2'=>array('15','21'),
			'3'=>array('22','28'),
			'4'=>array('29',$EndDay),
		);
		$WeekNum=$this->mymodel->WriteSql("
			SELECT 'One' as tp,SUM(d_num) as Allnum FROM `visit_count` where d_date between '".$Year."-".$Month."-01' and '".$Year."-".$Month."-07'
			union all SELECT 'Two' as tp,SUM(d_num) as Allnum  FROM `visit_count` where d_date between '".$Year."-".$Month."-08' and '".$Year."-".$Month."-14'
			union all SELECT 'There' as tp,SUM(d_num) as Allnum  FROM `visit_count` where d_date between '".$Year."-".$Month."-15' and '".$Year."-".$Month."-21'
			union all SELECT 'Four' as tp,SUM(d_num) as Allnum  FROM `visit_count` where d_date between '".$Year."-".$Month."-22' and '".$Year."-".$Month."-28'
			union all SELECT 'Five' as tp,SUM(d_num) as Allnum  FROM `visit_count` where d_date between '".$Year."-".$Month."-29' and '".$Year."-".$Month."-".$EndDay."'
		");
		foreach ($WeekNum as $value) {
			// 第一周
			if($value['tp']=='One')
				$WeekData['WeekData1'][0]=!empty($value['Allnum'])?$value['Allnum']:0;
			// 第二周
			if($value['tp']=='Two')
				$WeekData['WeekData1'][1]=!empty($value['Allnum'])?$value['Allnum']:0;
			// 第三周
			if($value['tp']=='There')
				$WeekData['WeekData1'][2]=!empty($value['Allnum'])?$value['Allnum']:0;
			// 第四周
			if($value['tp']=='Four')
				$WeekData['WeekData1'][3]=!empty($value['Allnum'])?$value['Allnum']:0;
			// 第五周
			if($value['tp']=='Five' and $EndDay!=28)
				$WeekData['WeekData1'][4]=!empty($value['Allnum'])?$value['Allnum']:0;
		}
		return $WeekData;
	}
	// 聯絡我們總數
	private function GetContact(){
		$ContactNum=$this->mymodel->WriteSql("
			select count(d_id) as num from contact where d_status='1'
			union all
			select count(d_id) as num from contact where d_status='2'
		");
		return $ContactNum;
	}

	// 訂單總數
	private function GetOrder(){
		$OrderNum=$this->mymodel->WriteSql("
			select count(d_id) as num from orders
			union all
			select count(d_id) as num from orders where d_orderstatus='1'
			union all
			select count(d_id) as num from orders where d_orderstatus='5'
			union all
			select count(d_id) as num from orders where d_orderstatus='7'
			union all
			select count(d_id) as num from products where products.d_stock<".$this->webmodel->BaseConfig(18)['d_title']." or (products.d_low>0 and products.d_stock<products.d_low)
		");
		return $OrderNum;
	}

	public function ChangeLang(){
		$Lang=$_POST['Lang'];
		@session_start();
		$_SESSION[CCODE::ADMIN]['Lang']=$Lang;

	}
}
