<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->autoful->FrontConfig('1');
		$this->load->library('form_validation');
	}
	// 會員首頁
	public function Index() {
		$dbdata = $this->mymodel->WriteSQL('
		select m.d_pname,m.d_upgrade_total,m.d_upgrade_date,m.d_chked,m.TID,lv.d_deadline,lv.d_upgrade - m.d_upgrade_total as last_money
		from member as m
		left join member_lv as lv on lv.d_id = "' . $_SESSION[CCODE::MEMBER]['Mlv'] . '"
		where m.d_id = "' . $_SESSION[CCODE::MEMBER]['LID'] . '"', '1');

		$data = array(
			'dbdata' => $dbdata,
			'Next_lv' => $this->mymodel->OneSearchSql('member_lv', 'd_title', array('d_id' => $_SESSION[CCODE::MEMBER]['Mlv'] + 1)),
			'Member_type' => !empty($dbdata['TID']) ? $this->mymodel->SelectSearch('member_type', '', 'd_title', 'where d_id in (' . implode(',', explode('@#', $dbdata['TID'])) . ')', 'd_sort') : '',
			'Orders_total' => $this->mymodel->WriteSql('select count(d_id) as total from orders where MID="'.$_SESSION[CCODE::MEMBER]['LID'].'"','1'),
		);
		$this->NetTitle = '會員中心';
		$this->load->view('front/member', $data);
	}
	// 會員修改
	public function account() {
		$dbdata = $this->mymodel->WriteSQL('
		select m.d_address,m.d_district,m.d_zipcode,m.d_county,m.d_user_type,m.d_pname,m.d_upgrade_total,m.d_upgrade_date,m.TID,m.d_account,m.d_job,m.d_birthday,m.d_phone,m.d_newsletter,con.d_title
		from member as m
		left join product_config as con on con.d_type = 6 and con.d_val = m.d_user_type
		where m.d_id = "' . $_SESSION[CCODE::MEMBER]['LID'] . '"', '1');

		$data = array(
			'dbdata' => $dbdata,
		);
		$this->NetTitle = '會員資料修改';
		$this->load->view('front/member_account', $data);
	}
	// 我的收藏
	public function favorite() {
		$data = array();

		$dbdata = $this->mymodel->FrontSelectPage('member_favorite', '*', 'where MID = ' . $_SESSION[CCODE::MEMBER]['LID'] . '', 'd_create_time desc', '5');
		if (!empty($dbdata['dbdata'])) {
			$data['AID'] = array_column($dbdata['dbdata'], 'AID', 'PID');
			$dbdata['dbdata'] = $this->mymodel->WriteSQL('
			select GROUP_CONCAT(pro_add.d_id) as d_add_id,GROUP_CONCAT(pro_add.d_title) as d_add_title,GROUP_CONCAT(pro_add.d_price) as d_add_price,pro.d_spectitle,pro.d_id,pro.d_title,pro.d_img1,pro.d_model,pro.d_price' . $_SESSION[CCODE::MEMBER]['Mlv'] . ' as d_pro_price
			from products as pro
			left join products_add as pro_add on pro_add.PID = pro.d_id
			where pro.d_id in (' . implode(',', array_column($dbdata['dbdata'], 'PID')) . ')
			group by pro.d_id
			order by pro_add.d_sort asc');

			$data['dbdata'] = $dbdata;
		}

		$this->NetTitle = '我的收藏';
		$this->load->view('front/member_favorite', $data);
	}
	// 訂單列表
	public function orders($type = '',$id = '0') {
		switch ($type) {
			case 'pay':

				break;
			case 'ask':
				$dbdata = $this->_check_order($id);
				$data['OID'] = $dbdata['OID'];
				$data['id'] = $id;
				$data['member_info'] = $_SESSION[CCODE::MEMBER];
				$view_file = 'front/member_order_ask';
				break;
			case 'cancel':

				break;

			default:
				$post = $this->input->post(null,true);
				$sql_where = 'where MID="' . $_SESSION[CCODE::MEMBER]['LID'] . '"';
				$sql_where .= !empty($post['pay_type']) ? ' and d_pay="'.$post['pay_type'].'"': '';
				$sql_where .= !empty($post['order_source']) ? ' and d_source="'.$post['order_source'].'"': '';

				$dbdata = $this->mymodel->FrontSelectPage('orders', 'd_id,OID,d_price,d_pay,d_paystatus,d_orderstatus,d_invoicenumber,d_remit_account,orders.d_create_time,d_arrival_date,d_shipnumber', $sql_where, 'd_create_time desc', '5');
				$data = array(
					'dbdata' => $dbdata,
					'Orders_status' => $this->mymodel->GetConfig('10', 'and d_enable="Y"'),
					'Pay_types' => $this->mymodel->SelectSearch('cashflow', '','d_id,d_title', 'where d_enable="Y"','d_sort'),
				);
				$view_file = 'front/member_order';
				break;
		}
		$this->NetTitle = '購物紀錄與訂單查詢';
		$this->load->view($view_file, $data);
	}
	// 檢查訂單
	private function _check_order($id , $is_ajax = false)
	{
		$query = $this->mymodel->OneSearchSql('orders', '*', array('MID' => $_SESSION[CCODE::MEMBER]['LID'], 'd_id' => $id));
		if (empty($query) && !$is_ajax) {
			$this->useful->AlertPage('', '此訂單不存在！');
			exit();
		}else if (empty($query) && $is_ajax){
			echo json_encode(array('status' => 'error'));
			exit();
		}
		return $query;
	}
	// ajax 發票明細
	public function invoice_pro()
	{
		$post = $this->input->post(null,true);
		if (!empty($post['id'])) {
			$dbdata['orders'] = $this->_check_order($post['id'],true);
			$dbdata['orders_detail'] = $this->mymodel->SelectSearch('orders_detail', '','*', 'where OID = "'.$post['id'].'"','d_id');
			$data = array(
				'status' => 'success',
				'dbdata' => $this->load->view('front/invoice_info', $dbdata ,true),
			);
			echo json_encode($data);
			exit();
		}
		echo json_encode(array('status' => 'error'));
	}
	// ajax 刪除收藏
	public function delFavorite() {
		$post = $this->input->post(null,true);
		if (!empty($post)) {
			$this->mymodel->DelectData('member_favorite', 'where PID=' . $post['id'] . ' and MID=' . $_SESSION[CCODE::MEMBER]['LID']);
			echo 'success';
			exit();
		}
		echo 'error';
	}
	// 邀請好友
	public function friend() {
		$data = array();
		$this->NetTitle = '邀請好友加入會員';
		$this->load->view('front/member_friend', $data);
	}
	// 表單送出後檢查
	public function check($page, $id = '') {
		$post = $this->input->post(null, true);
		if ($this->form_validation->run($page) == true && !empty($post)) {
			switch ($page) {
			case 'account':
				if ($_SESSION[CCODE::MEMBER]['VcodeNum'] != $post['d_captcha']) {
					$this->useful->AlertPage('', '驗證碼輸入錯誤');
					exit();
				}

				$dbdata = $this->useful->DB_Array($post, '1', '', '1');
				$dbdata['d_newsletter'] = (!empty($post['d_newsletter']) ? 'Y' : 'N'); // 電子信
				$dbdata = $this->useful->UnsetArray($dbdata, array('d_repassword', 'd_password', 'd_captcha'));
				if (!empty($post['d_password'])) {
					$this->load->library('encryption');
					$dbdata['d_password'] = $this->encryption->encrypt($post['d_password']);
				}

				$msg = $this->mymodel->UpdateData('member', $dbdata, 'where d_id =' . $_SESSION[CCODE::MEMBER]['LID']);
				if ($msg) {
					$this->useful->AlertPage('member', '您已成功修改個人資料！');
				} else {
					$this->useful->AlertPage('', '修改失敗，請重新輸入！');
				}
				exit();
				break;

			case 'friend':
				$this->load->library('encryption');
				$dbdata['d_Fcode'] = $this->encryption->encrypt($_SESSION[CCODE::MEMBER]['LID']);
				while (1) {
					$query = $this->mymodel->OneSearchSql('member_friend', '*', array('d_Fcode' => $dbdata['d_Fcode']));
					if (!empty($query)) {
						$dbdata['d_Fcode'] = $this->encryption->encrypt($_SESSION[CCODE::MEMBER]['LID']);
					}else{
						break;
					}
				}
				$dbdata['MID'] = $_SESSION[CCODE::MEMBER]['LID'];
				$dbdata = $this->useful->DB_Array($dbdata, '', '', '1');
				$msg = $this->mymodel->InsertData('member_friend', $dbdata);
				if ($msg) {
					$CTitle = $this->webmodel->BaseConfig('2');
					$Subject = $_SESSION[CCODE::MEMBER]['LName'] . "邀請您加入" . $CTitle['d_title'] . "會員";
					$Message = "您好！<br><br>
							        您的朋友 " . $_SESSION[CCODE::MEMBER]['LName'] . "<br><br>
							        邀請您加入 " . $CTitle['d_title'] . "的會員<br><br>
							        以下是您的註冊邀請網址 <a href='" . site_url('login/join?F=' . $dbdata['d_Fcode']) . "'>點選我前往註冊</a><br><br>
							        進入以上網址進行註冊，且完成第一次購物，您的朋友將獲得紅利回饋 ";
					$this->tableful->Sendmail($post['d_Femail'], $Subject, $Message);
					$this->useful->AlertPage('member', '您已成功寄發邀請給您的好友！');
				} else {
					$this->useful->AlertPage('', '寄發邀請失敗，請重新輸入！');
				}
				exit();
				break;

			case 'ask':
				$query = $this->_check_order($id);
				$post['OID'] = $query['OID'];
				$dbdata = $this->useful->DB_Array($post, '', '', '1');
				$msg = $this->mymodel->InsertData('orders_ask', $dbdata);
				if ($msg) {
					$Message ='
	            您好，以下是詢問表單內容 <br>
	            -------------------------------- <br>
	            姓名 : <br>'.stripslashes($dbdata['d_name']).'<br>
							E-mail : <br>'.stripslashes($dbdata['d_email']).'<br>
							聯絡電話 : <br>'.stripslashes($dbdata['d_phone']).'<br>
							詢問內容 : <br>'.stripslashes($dbdata['d_content']).'<br>';
					$this->tableful->Sendmail($this->webmodel->BaseConfig('12'), '美麗平台會員-訂單詢問-編號 ' . $query['OID'], $Message);
					$this->useful->AlertPage('member/orders', '您已成功提交訂單詢問！');
				} else {
					$this->useful->AlertPage('', '詢問訂單失敗，請重新輸入！');
				}
				exit();
				break;

			case 'cancel':
				$query = $this->_check_order($id);
				if ($query['d_orderstatus'] > 2 ) {
					$this->useful->AlertPage('', '此訂單狀態不允許取消訂單，無法為您取消訂單！');
					exit();
				}else{
					$post['d_orderstatus'] = '6';
					$dbdata = $this->useful->DB_Array($post, '1', '', '1');
					$msg = $this->mymodel->UpdateData('orders', $dbdata, 'where OID =' . $query['OID']);
					if ($msg) {
						$Message ='
								您好，以下是取消表單內容 <br>
								-------------------------------- <br>
								姓名 : <br>'.stripslashes($dbdata['d_cancel_name']).'<br>
								E-mail : <br>'.stripslashes($dbdata['d_cancel_email']).'<br>
								聯絡電話 : <br>'.stripslashes($dbdata['d_cancel_phone']).'<br>
								取消訂單原因 : <br>'.stripslashes($dbdata['d_cancel_content']).'<br>';
						$this->tableful->Sendmail($this->webmodel->BaseConfig('12'), '美麗平台會員-訂單編號 ' . $query['OID'] . ' 訂單取消申請', $Message);
						$this->useful->AlertPage('member/orders', '您已成功提交訂單取消！');
					} else {
						$this->useful->AlertPage('', '詢問訂單失敗，請重新輸入！');
					}
				}
				exit();
				break;

			case 'pay':
				$query = $this->_check_order($id);
				if ($query['item']['pay_status'] != 0 || $query['item']['pay_type'] != 2) {
					message_alert('message', '此訂單狀態不允許匯款回覆！', base_url('member/order'), 'error');
				} else {
					$post['status'] = 2;
					$post['pay_status'] = 1;
					$this->db->where('id', $id)->update('orders', $post);
					$mail_content = "訂單編號：" . $query['item']['order_id'] . "已回覆匯款，請至管理系統確認！";
					Send_Mail('鴻伊會員-訂單編號 ' . $query['item']['order_id'] . ' 匯款回覆', $query['item']['name'], $query['item']['email'], $website->website_email, $mail_content);
					message_alert('message', '您已成功填寫匯款回覆！', base_url('member/order'), 'success');
				}
				break;

			case 'refund':
				$query = $this->_order_check($id);
				if ($query['item']['status'] != 4) {
					message_alert('message', '此訂單狀態不允許申請退貨！', base_url('member/order'), 'error');
				} else {
					$products = json_decode($query['item']['products'], true);
					foreach ($products as $k => $p) {
						if (in_array($p['id'], $post['refund'])) {
							$p['status'] = 1;
							$products[$k] = $p;
							$post['refund_total'] += $p['price'] * $p['amount'];
						}
					}
					unset($post['refund']);
					$post['products'] = json_encode($products);
					$post['status'] = 5;
					$this->db->where('id', $id)->update('orders', $post);
					$mail_content = "訂單編號：" . $query['item']['order_id'] . "已申請退貨，請至管理系統確認！";
					Send_Mail('鴻伊會員-訂單編號 ' . $query['item']['order_id'] . ' 退貨申請', $query['item']['name'], $query['item']['email'], $website->website_email, $mail_content);
					message_alert('message', '您已成功申請退貨！', base_url('member/order'), 'success');
				}
				break;
			}
		}
		$this->form_validation->set_error_delimiters('', '\n');
		$this->useful->AlertPage('', preg_replace("/\n/", "", validation_errors()));
		exit();
	}

	//驗證碼網址
	public function makeVcodeImg() {

		$len = 5;
		unset($_SESSION[CCODE::MEMBER]['VcodeNum']);
		$Vcode = $this->useful->random_vcode($len);

		$_SESSION[CCODE::MEMBER]['VcodeNum'] = implode('', $Vcode);

		Header("Content-type: image/PNG");
		$im = imagecreate($len * 11, 18);
		$back = ImageColorAllocate($im, 245, 245, 245);
		imagefill($im, 0, 0, $back); //背景

		for ($i = 0; $i < $len; $i++) {
			$font = ImageColorAllocate($im, rand(100, 255), rand(0, 100), rand(100, 255));
			imagestring($im, 5, 2 + $i * 10, 1, $Vcode[$i], $font);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}

}
