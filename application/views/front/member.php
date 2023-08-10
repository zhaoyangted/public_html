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
          <div class="row contact_box">
            <div class="box-contact-us">
              <p><b><? echo $_SESSION[CCODE::MEMBER]['LName'] ?> 您好</b></p>
              <!-- <?php if ($dbdata['d_chked']==3): ?>
                <?php if (!empty($Next_lv)): ?>
                  <dt> 再消費<span class="r16">NT$<? echo $dbdata['last_money'] ?></span>，即升級為<? echo $Next_lv['d_title'] ?></dt>
                <?php else: ?>
                  <dt> 已為最高等級會員</dt>
                <?php endif; ?>
                <dt>效期<span class="r16"><? echo $dbdata['d_upgrade_date'] ?> ~ <? echo date('Y-m-d', strtotime("+".$dbdata['d_deadline']." day", strtotime($dbdata['d_upgrade_date']))); ?></span></dt>
              <?php elseif($dbdata['d_chked']==2): ?>
                <p><b>您的帳號正在審核中</b></p>
              <?php else: ?>
                <p><b><a href="<? echo site_url('member/review') ?>">您的資料尚未補齊，請前往申請審核</a></b></p>
              <?php endif; ?> -->
            </div>

            <div class="box-contact-us">
              <p> <b>您的目前等級：<? echo $_SESSION[CCODE::MEMBER]['Mlv_title'] ?></b></p>
              <?php if ($_SESSION[CCODE::MEMBER]['Mlv']!=1): ?>
                <p> <b>您的會員分類：<? echo !empty($Member_type) && $dbdata['d_chked']==3 ?implode(',',array_column($Member_type,'d_title')):'無分類'; ?></b></p>
              <?php endif; ?>
            </div>

          </div>
          <div class="member_info">
            <li>
              <div class="title06">訂單狀態與管理</div>
              <div class="member_info_btn">
                <a href="<? echo site_url('member/orders') ?>">
                  <ul>訂單查詢 (<? echo empty($Orders_total['total']) ? '0' : $Orders_total['total'] ; ?>)</ul>
                </a>
                <!-- <a href="<? echo site_url('member/invoice') ?>">
                  <ul>電子發票歸戶</ul>
                </a> -->
              </div>
            </li>
            <li>
              <div class="title06">個人帳戶管理/資料維護</div>
              <div class="member_info_btn">
                <a href="<? echo site_url('member/account') ?>">
                  <ul>會員資料修改</ul>
                </a>
                <a href="<? echo site_url('member/favorite') ?>">
                  <ul>我的收藏清單</ul>
                </a>
                <a href="<? echo site_url('member/point') ?>">
                  <ul>會員點數查詢</ul>
                </a>
              </div>
            </li>
            <li>
              <div class="title06">客服中心</div>
              <div class="member_info_btn">
                <a href="<? echo site_url('qa') ?>">
                  <ul>購物說明</ul>
                </a>
                <a href="<? echo site_url('clause') ?>">
                  <ul>隱私權條款</ul>
                </a>
                <a href="<? echo site_url('contact') ?>">
                  <ul>與我們聯繫</ul>
                </a>
                <!-- <a href="<? echo site_url('edm') ?>">
                  <ul>型錄下載</ul>
                </a> -->
              </div>
            </li>
          </div>
        </section>
      </div>
    </div>
  </article>
</main>
<?php include '_footer.php';?>
