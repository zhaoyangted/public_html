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
            <!--contact-->
            <form action="<? echo site_url('member/check/pay/'.$id) ?>" method="post">
              <ul class="styled-input">
                
                <div class="title03" style="margin-top:30px;">匯款回覆</div>
                <div class="join_line"></div>
                <li class="half">
                  <h2>訂單編號</h2>
                  <h4><? echo $OID ?></h4>
                </li>
                <li class="half">
                  <h2>訂購日期</h2>
                  <h4><? echo $create_date ?></h4>
                </li>
                <li class="half">
                  <h2>帳號末五碼*</h2>
                  <input type="text" name="d_remit_account" maxlength="5" />
                </li>
                <li class="half">
                  <h2>匯款金額*</h2>
                  <input type="text" name="d_remit_price" />
                </li>
                <li>
                  <h2>匯款時間*</h2>
                  <input type="text" name="d_remit_time" id="d_remit_time" />
                </li>
                <div class="join_line"></div>
                <li style="text-align:center;">
                  <input type="submit" class="btn-style02" value="確認送出"/> 
                  <input type="reset" class="btn-style02" value="重新填寫"/>
                </li>
              </ul>
            </form>
            <!--//contact-->
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<!-- DateMaker -->
<script src="<? echo CCODE::DemoPrefix.('/js/myjava/datepicker/jquery.datetimepicker.full.js');?>"></script>
<link type="text/css" rel="stylesheet" href="<? echo CCODE::DemoPrefix.('/js/myjava/datepicker/jquery.datetimepicker.css');?>">
<script>
$.datetimepicker.setLocale('zh-TW');

$("#d_remit_time").datetimepicker({
  timepicker:false,
  format:'Y-m-d',
  scrollMonth : false
});
</script>