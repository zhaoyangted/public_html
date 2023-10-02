<?php include '_header.php';?>
<main>
  <article>
    <!--bread-->
    <div class="box-1">
      <ul class="breadcrumb">
        <li><a href="<? echo site_url('') ?>">首頁</a></li>
        <li class="active">網站導覽</li>
      </ul>
    </div>
    <!--//bread-->
    <div class="container">
      <div class="col-lg-">
        <section class="content_box">
          <div class="title01 center">網站導覽</div>
          <div class="site_box">
            <div class="title02">關於千冠莉</div>
            <ul>
              <li><a href="<? echo site_url('about') ?>">公司簡介</a></li>
              <li><a href="<? echo site_url('clause') ?>">隱私權條款</a></li>
              <li><a href="<? echo site_url('contact') ?>">聯絡我們</a></li>
            </ul>
          </div>
          <div class="site_box">
            <div class="title02">常見問題 Q&A</div>
            <ul>
              <?php foreach ($qa as $q): ?>
              <li><a href="<? echo site_url('qa/info/'.$q['d_id']) ?>"><? echo $q['d_title'] ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="site_box">
            <div class="title02">產品分類</div>
            <ul>
              <?php foreach ($products_type as $t): ?>
              <li><a href="<? echo site_url('products/index/'.$t['d_id']) ?>"><? echo $t['d_title'] ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php if (!empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y'): ?>
            <div class="site_box">
              <div class="title02">會員服務</div>
              <ul>
                <li><a href="<? echo site_url('member') ?>">會員中心</a></li>
                <li><a href="<? echo site_url('member/orders') ?>">購物紀錄與訂單查詢</a></li>
                <li><a href="<? echo site_url('member/favorite') ?>">我的收藏</a></li>
                <li><a href="<? echo site_url('member/friend') ?>">邀請好友加入會員</a></li>
                <li><a href="<? echo site_url('member/account') ?>">會員資料修改</a></li>
                <li><a href="<? echo site_url('member/point') ?>">會員點數查詢</a></li>
                <li><a href="<? echo site_url('member/account') ?>">訂閱/取消 電子報</a></li>
              </ul>
            </div>
          <?php endif; ?>
        </section>
      </div>
    </div>
  </article>
</main>
<?php include '_footer.php';?>
