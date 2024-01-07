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
class Pay_result extends RestController
{

	function __construct()
	{
		header("Access-Control-Allow-Credentials: true");
		parent::__construct();
		
	}
    public function index()
    {
        $post = $this->input->post(null, true);

        if (!empty($post)) {

            $OID = $post['lidm'];
            $orderData = $this->db->select('d_id,d_paystatus,d_usebonus,MID')->where('OID', $OID)->get('orders')->row_array();
            //訂單存在
            if (!empty($orderData)) {
                // 未付款狀態 且 授權成功
                if ($orderData['d_paystatus'] == 1 && $post['status'] == 0 && !empty($post['authCode'])) {

                    // 扣除紅利
                    if ($orderData['d_usebonus'] > 0) {
                        $this->SubBouns($orderData['d_usebonus'], $OID, $orderData['MID']);
                    }
                    //更改成未處理及付款成功
                    $this->db->where('d_id', $orderData['d_id'])->update('orders', array('d_paystatus' => 2, 'd_orderstatus' => 1, 'd_bankf' => $post['errDesc']));
                    
                    $this->response(200);
                    //echo "<script>alert('訂單建立成功，將導向詳細頁！');</script>";
                    //echo "<script>location.href='" . base_url('cart/order_completed/' . $OID) . "'</script>";

                } else { // 庫存回充

                    $this->autoful->ReStock($orderData['d_id']);
                    $this->response(['msg'=>'刷卡失敗，將導向詳細頁！'],404);
                    //echo "<script>alert('刷卡失敗，將導向詳細頁！');</script>";
                    //echo "<script>location.href='" . base_url('cart/order_completed/' . $OID) . "'</script>";

                }

            } else {
                $this->response(['msg'=>'系統錯誤，將導回首頁！'],404);
                //echo "<script>alert('系統錯誤，將導回首頁！');</script>";
                //echo "<script>location.href='" . base_url() . "'</script>";
            }

        } else {
            $this->response(['msg'=>'輸入錯誤！'],404);
            //echo "<script>location.href='" . base_url() . "'</script>";
        }

        exit();
    }
    // 扣除會員紅利
    private function SubBouns($bonus, $OID, $MID)
    {
        $Sdata = array(
            'MID' => $MID,
            'OID' => $OID,
            'd_type' => '2',
            'd_num' => $bonus,
            'd_content' => '扣抵訂單',
        );

        $dbdata = $this->useful->DB_Array($Sdata, '', '', '1');

        $this->mymodel->InsertData('member_point', $dbdata);
        $this->mymodel->SimpleWriteSQL('update member set d_bonus=d_bonus-' . $bonus . ' where d_id=' . $MID . '');
        // 撈取快過期的點數
        $this->DetailSub($bonus, $MID);
    }

    // 撈取快過期的點數
    private function DetailSub($bonus, $MID)
    {
        $Edata = $this->mymodel->WriteSQL('
            select d_id,d_total from member_point where MID=' . $MID . ' and d_type=1 and d_enable="Y" and d_total>0 order by d_create_time
        ', '1');
        if (!empty($Edata['d_total'])) {
            $Subbonus = $Edata['d_total'] - $bonus;
            if ($Subbonus < 0) {
                $this->mymodel->SimpleWriteSQL('update member_point set d_total=0 where d_id=' . $Edata['d_id'] . '');
                $this->DetailSub(abs($Subbonus), $MID);
            } else {
                $this->mymodel->SimpleWriteSQL('update member_point set d_total=' . $Subbonus . ' where d_id=' . $Edata['d_id'] . '');
            }
        }
    }

    // 虛擬ATM
    public function webATM()
    {
        $post = $this->input->post(null, true);

        if (!empty($post) && $post['txnType'] == 'P') {
            $status = substr($post['content'], 72, 1);
            // 正常交易狀態
            if ($status == '0') {
                $account = substr($post['content'], 73, 16);

                $orderData = $this->db->select('d_id,d_paystatus,d_pay')->where('d_webatm', $account)->get('orders')->row_array();
                //訂單存在 未付款狀態 且是 webatm付款方式
                if (!empty($orderData) && $orderData['d_paystatus'] == 1 && $orderData['d_pay'] == 4) {
                    //更改成未處理及付款成功
                    $this->db->where('d_id', $orderData['d_id'])->update('orders', array('d_paystatus' => 2, 'd_orderstatus' => 1, 'd_webatm_request' => json_encode($post)));
                }
            }
            echo '0000';
        } else {
            redirect(base_url());
        }

        exit();
    }
}