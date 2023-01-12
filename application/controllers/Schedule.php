<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
    // 發送紅利排程(每天12點排程)
    public function SendBouns(){
        $Odata=$this->mymodel->SelectSearch('orders','','d_id,OID,MID,d_bonus,d_return_point,d_successdate,d_admin,d_orderstatus','where d_send="N" and (d_orderstatus=3 or d_orderstatus=6) and d_paystatus=2 and d_successdate!="0000-00-00 00:00:00" and d_successdate!="" and d_successdate <=date_sub(now(),interval 20 DAY)');
        foreach ($Odata as $key => $value) {
            if ($value['d_admin']=="N") {
                $this->GetBonus($value);
                $this->ChkFirst($value['d_id']);
            }
			// 已出貨 20天後改為已完成
            $this->mymodel->UpdateData('orders', array('d_orderstatus' => 4), ' where d_id=' . $value['d_id'] . '');
        }

        // 檢查紅利是否過期
        $this->Chkbonus();
        // 檢查會員是否可升等
        $this->UpdateLv();
		// 檢查試用品週期
        $this->ChkTrial();
		// 檢查訂單報價完成
        $this->ChkOrderSpec();
		// 檢查webATM
        $this->ChkATM();
        // 產品最新上架30天移除
        $this->ChkNew();
    }
    // 檢查會員是否降等-一年一次
    public function DownLv(){
        $StartDate=date('Y', strtotime('-1 year')).'-01-01';
        $EndDate=date('Y', strtotime('-1 year')).'-12-31';
        $Mdata=$this->mymodel->SelectSearch('member','','d_id,d_lv','where d_enable="Y" and d_lv>=4');
        foreach ($Mdata as $key => $value) {
            $YearNum=$OrderNum=$OrderTotal=0;
            // 撈出條件
            $lv=$value['d_lv'];
            $LVdata=$this->mymodel->OneSearchSql('member_lv','d_orderyear,d_order,d_price',array('d_id'=>$lv));
            // 一年內次數達
            $Downyear=$LVdata['d_orderyear'];
            // 訂單終身累積
            $Downorder=$LVdata['d_order'];
            // 消費金額終身累積
            $Downprice=$LVdata['d_price'];

            $Odata=$this->mymodel->Writesql('
                select 1 as tp1,count(d_id) as Orderdata from orders where d_admin="N" and d_orderstatus=4 and MID='.$value['d_id'].' and d_successdate between "'.$StartDate.'" and "'.$EndDate.'"
                union all
                select 2 as tp1,count(d_id) as Orderdata from orders where d_admin="N" and d_orderstatus=4 and MID='.$value['d_id'].'
                union all
                select 3 as tp1,sum(d_total) as Orderdata from orders where d_admin="N" and d_orderstatus=4 and MID='.$value['d_id'].'

                ');
            // 一年內次數達?次
            $YearNum=(!empty($Odata[0]['Orderdata'])?$Odata[0]['Orderdata']:0);
            // 訂單終身累積?筆
            $OrderNum=(!empty($Odata[1]['Orderdata'])?$Odata[1]['Orderdata']:0);
            // 消費金額終身累積?元
            $OrderTotal=(!empty($Odata[2]['Orderdata'])?$Odata[2]['Orderdata']:0);
            if($YearNum<$Downyear || $OrderNum<$Downorder || $OrderTotal<$Downprice){
                $MID=$value['d_id'];
                $Downlv=$lv-1;
                $this->mymodel->SimpleWriteSQL('update member set d_lv='.$Downlv.' where d_id='.$MID.'');
                // echo $MID;
            }
        }
    }
    // 產品最新上架30天移除
    private function ChkNew(){
        $Pdata=$this->mymodel->Writesql('
            select group_concat(d_id) as Allid
            from products
            where d_new="Y" and d_create_time <=date_sub(now(),interval 30 DAY)
            order by d_id
            ','1');
        if(!empty($Pdata['Allid'])){
            $this->mymodel->SimpleWriteSQL('update products set d_new="N" where d_id in ('.$Pdata['Allid'].')');
        }
    }
    // 是否為首次購物
    private function ChkFirst($OID=''){
        $Odata=$this->mymodel->OneSearchSql('orders','MID',array('d_id'=>$OID));
        $Mdata=$this->mymodel->OneSearchSql('member','FID,d_first',array('d_id'=>$Odata['MID']));
        if($Mdata['FID']!=0 and $Mdata['d_first']=='Y'){
            // 撈取首次購物紅利
            $Bdata=$this->webmodel->BaseConfig('15');
            $Bonus=$Bdata['d_title'];
            // 點數備註
            $Sdata=array(
                'MID'=>$Mdata['FID'],
                'd_type'=>'1',
                'd_num'=>$Bonus,
                'd_total'=>$Bonus,
                'd_content'=>'邀請好友獲得點數'
            );

            $dbdata=$this->useful->DB_Array($Sdata,'','','1');

            $this->mymodel->InsertData('member_point',$dbdata);
            // 加好友點數
            $this->mymodel->SimpleWriteSQL('update member set d_bonus=d_bonus+'.$Bonus.' where d_id='.$Mdata['FID'].'');
            // 首次購物勾選
            $this->mymodel->SimpleWriteSQL('update member set d_first="N" where d_id='.$Odata['MID'].'');
        }
    }
    // 檢查紅利是否過期
    private function Chkbonus(){
        $Bdata=$this->mymodel->SelectSearch('member_point','','d_id,MID,d_total','where d_enable="Y" and d_type=1 and DATE_FORMAT(d_create_time,"%Y-%m-%d") < DATE_FORMAT(date_sub(now(),interval 1 year),"%Y-%m-%d")');
        if(!empty($Bdata)){
            foreach ($Bdata as $key => $value) {
                if($value['d_total']!=0){
                    $Mdata=$this->mymodel->OneSearchSql('member','d_bonus',array('d_id'=>$value['MID']));
                    $Bonus=$Mdata['d_bonus']-$value['d_total'];
                    $Bonuschk=($Bonus>=0)?$Bonus:0;
                    // 減掉過期紅利
                    $this->mymodel->SimpleWriteSQL('update member set d_bonus='.$Bonuschk.' where d_id='.$value['MID'].'');
                }
                // 過期標為無效
                $this->mymodel->SimpleWriteSQL('update member_point set d_enable="N" where d_id='.$value['d_id'].'');
            }
        }
    }
    // 檢查會員是否可升等
    private function UpdateLv(){
        $StartDate=date('Y').'-01-01';
        $EndDate=date('Y').'-12-31';
        $Mdata=$this->mymodel->SelectSearch('member','','d_id,d_lv','where d_enable="Y" and d_lv>=3 and d_lv!=7');
        foreach ($Mdata as $key => $value) {
            $YearNum=$OrderNum=$OrderTotal=0;
            // 撈出條件
            $Uplv=$value['d_lv']+1;
            $LVdata=$this->mymodel->OneSearchSql('member_lv','d_orderyear,d_order,d_price',array('d_id'=>$Uplv));
            // 一年內次數達
            $Uporderyear=$LVdata['d_orderyear'];
            // 訂單終身累積
            $Uporder=$LVdata['d_order'];
            // 消費金額終身累積
            $Upprice=$LVdata['d_price'];

            $Odata=$this->mymodel->Writesql('
                select 1 as tp1,count(d_id) as Orderdata from orders where d_admin="N" and d_orderstatus=4 and MID='.$value['d_id'].' and d_successdate between "'.$StartDate.'" and "'.$EndDate.'"
                union all
                select 2 as tp1,count(d_id) as Orderdata from orders where d_admin="N" and d_orderstatus=4 and MID='.$value['d_id'].'
                union all
                select 3 as tp1,sum(d_total) as Orderdata from orders where d_admin="N" and d_orderstatus=4 and MID='.$value['d_id'].'

                ');
            // 一年內次數達?次
            $YearNum=(!empty($Odata[0]['Orderdata'])?$Odata[0]['Orderdata']:0);
            // 訂單終身累積?筆
            $OrderNum=(!empty($Odata[1]['Orderdata'])?$Odata[1]['Orderdata']:0);
            // 消費金額終身累積?元
            $OrderTotal=(!empty($Odata[2]['Orderdata'])?$Odata[2]['Orderdata']:0);
            if($YearNum>=$Uporderyear && $OrderNum>=$Uporder && $OrderTotal>=$Upprice){
                $MID=$value['d_id'];
                $this->mymodel->SimpleWriteSQL('update member set d_lv='.$Uplv.' where d_id='.$MID.'');
                // echo $value['d_id'];
            }
        }
    }
    // 新增紅利
    private function GetBonus($data){
		// 退貨已完成  用退貨後新獲得紅利派發
      if ($data['d_orderstatus']==6) {
         $bonus = $data['d_return_point'];
     } else {
         $bonus = $data['d_bonus'];
     }
     if($bonus!=0){
            // 點數備註
        $Sdata=array(
            'OID'=>$data['OID'],
            'MID'=>$data['MID'],
            'd_type'=>'1',
            'd_num'=> $bonus,
            'd_total'=> $bonus,
            'd_content'=>'訂單新增',
            'd_create_date' => date('Y-m-d'),
        );
        $dbdata=$this->useful->DB_Array($Sdata,'','','1');
        $this->mymodel->InsertData('member_point',$dbdata);
            // // 加會員點數
        $this->mymodel->SimpleWriteSQL('update member set d_bonus=d_bonus+'.$bonus.' where d_id='.$data['MID'].'');
            // // 已發送紅利
        $this->mymodel->SimpleWriteSQL('update orders set d_send="Y" where OID='.$data['OID'].'');
    }
}
    // 檢查試用品週期
private function ChkTrial(){
  $Tdata=$this->mymodel->SelectSearch('orders_trial_detail','','d_id','where d_enable="Y" and d_deadline <CURDATE()');
  foreach ($Tdata as $t) {
     $this->mymodel->UpdateData('orders_trial_detail',array('d_enable'=>'N'),' where d_id='.$t['d_id'].'');
 }
}
    // 檢查訂單報價完成
private function ChkOrderSpec(){
  $Odata=$this->mymodel->SelectSearch('orders','','d_id','where d_orderstatus=11 and d_specsuccessdate!="0000-00-00 00:00:00" and d_specsuccessdate!="" and d_specsuccessdate <=date_sub(now(),interval 7 DAY)');
  foreach ($Odata as $o) {
     $this->mymodel->UpdateData('orders',array('d_orderstatus'=>'8'),' where d_id='.$o['d_id'].'');
     if ($o['d_usebonus']>0) {
        $this->autoful->ReBackBonus($o['d_usebonus'],$o['MID'],$o['OID']);
    }
    $this->autoful->ReStock($o['d_id']);
}
}
    // 計算天數
private function GetDay($Edate){
    $startdate=strtotime($Edate);
    $enddate=strtotime(date('Y-m-d'));
    $days=round(($enddate-$startdate)/3600/24) ;
    return $days;
}
	// webATM過期
private function ChkATM(){
  $Odata=$this->mymodel->SelectSearch('orders','','d_id,d_usebonus,MID,OID','where d_pay=4 and d_paystatus=1 and d_orderstatus=1 and d_create_time!="0000-00-00 00:00:00" and d_create_time!="" and d_create_time <=date_sub(now(),interval 3 DAY)');
  foreach ($Odata as $o) {
     $this->mymodel->UpdateData('orders',array('d_orderstatus'=>'8'),' where d_id='.$o['d_id'].'');
     if ($o['d_usebonus']>0) {
        $this->autoful->ReBackBonus($o['d_usebonus'],$o['MID'],$o['OID']);
    }
    $this->autoful->ReStock($o['d_id']);
}
}
}
