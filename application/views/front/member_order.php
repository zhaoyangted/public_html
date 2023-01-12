<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<? echo site_url('') ?>">首頁</a></li>
          <li class="active">會員中心</li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">會員中心</div>
            <?php include '_member_menu.php';?>
            <!--order-->
            <div class="center">
              <form id="form" action="<?php echo site_url('member/orders') ?>" method="post">
                <select name="pay_type" onchange="$('#form').submit()" class="select_sort">
                  <option value="">---付款方式---</option>
                  <?php foreach ($Pay_types as $p): ?>
                    <option value="<? echo $p['d_id'] ?>" <? echo !empty($_POST['pay_type'])&&$_POST['pay_type']==$p['d_id'] ?'selected':''; ?>><? echo $p['d_title'] ?></option>
                  <?php endforeach; ?>
                </select>
                <select name="order_source" onchange="$('#form').submit()" class="select_sort">
                  <option value="">---訂單來源---</option>
                  <option value="1" <? echo !empty($_POST['order_source'])&&$_POST['order_source']=='1' ?'selected':''; ?>>網路</option>
                  <option value="2" <? echo !empty($_POST['order_source'])&&$_POST['order_source']=='2' ?'selected':''; ?>>門市</option>
                </select>
              </form>
            </div>
            <div class="order">
              <h1>購物紀錄</h1>
              <?php if (!empty($dbdata['dbdata'])): ?>
                <?php $Pay_types = array_column($Pay_types,'d_title','d_id'); ?>
                <?php foreach ($dbdata['dbdata'] as $v): ?>
                  <ul>
                    <li>
                      <div class="dbox"><dd>訂單編號</dd><em><a href="<? echo site_url('member/orders/info/'.$v['d_id']) ?>"><? echo $v['OID'] ?></a></em></div>
                      <div class="dbox"><dd>訂購日期</dd><? echo date('Y-m-d',strtotime($v['d_create_time'])) ?></div>
                    </li>
                   <li>
                      <div class="dbox"><dd>訂單金額</dd><b><? echo number_format($v['d_total']) ?></b></div>
                      <div class="dbox"><dd>付款方式</dd><? echo !empty($Pay_types[$v['d_pay']])?$Pay_types[$v['d_pay']]:'付款方式已不存在'; ?></div>
                      <!-- <div class="dbox"><dd>發票狀態</dd><? echo $v['d_invoicenumber'] ?>，尚未對獎<a href="javascript:void(0)" onclick="get_information(<?php echo $v['d_id'] ?>)" >(發票明細)</a></div> -->
                    </li>
                    <li>
                      <div class="dbox"><dd>訂單狀態</dd><? echo $Orders_status[$v['d_orderstatus']].(!empty($v['d_shipnumber'])?'(物流單號：<a href="https://www.t-cat.com.tw/inquire/trace.aspx" target="_blank">'.$v['d_shipnumber'].'</a>)':'') ?></div>
                      <div class="dbox">
                        <?php if ($v['d_pay'] == 1 && $v['d_orderstatus']<3 ): ?>
                          <a class="bn<? echo $v['d_paystatus']==3 ?'2':''; ?>" href="<?php echo $v['d_paystatus']==3 ?'javascript: void(0)':site_url('member/orders/pay/'.$v['d_id']); ?>"> <?php echo $v['d_paystatus']==3 ?'已填寫':'匯款回覆'; ?></a>
                        <?php endif; ?>
                        <?php if ($v['d_orderstatus']==3): ?>
                          <a class="bn" href="<? echo site_url('member/orders/refund/'.$v['d_id']) ?>">申請退貨</a>
                        <?php endif; ?>
                        <a class="bn" href="<? echo site_url('member/orders/ask/'.$v['d_id']) ?>">訂單詢問</a>
                        <?php if ($v['d_orderstatus']<3 || $v['d_orderstatus']==10 || $v['d_orderstatus']==11): ?>
                          <a class="bn" href="<? echo site_url('member/orders/cancel/'.$v['d_id']) ?>">取消訂單</a>
                        <?php endif; ?>
                        <?php if ($v['d_orderstatus']==11): ?>
                          <a class="bn" href="<? echo site_url('member/orders/info/'.$v['d_id']) ?>" style="background-color:#ff3c6c;border: 1px solid #ff3c6c;">繼續付款</a>
                        <?php endif; ?>
                      </div>
                    </li>
                  </ul>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <!--//order-->
            <? echo !empty($dbdata['dbdata'])?$dbdata['PageList']:'';?>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<!-- invoice_info -->
<div id="invoice_info" class="fancy-box">
</div>
<!-- //invoice_info -->
<script>
// function get_information(id) {
//   $.ajax({
//     type: 'POST',
// 		dataType: 'JSON',
// 		url: "<? echo site_url('member/invoice_pro') ?>",
// 		data: {
//       id : id
//     },
// 		success: function(data) {
// 			if (data.status == 'error') {
// 				alert('發票明細獲取失敗，請重新整理後再試！');
// 			} else if (data.status == 'success') {
//         $('#invoice_info').html(data.dbdata);
//         $.fancybox.open({ src: '#invoice_info', type : 'inline' });
// 			}
// 		},
// 		error: function(data) {
// 			alert('系統發生未知的錯誤，請重新整理後再試！');
// 		}
//   });
// }
</script>
