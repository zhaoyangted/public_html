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
          <div class="w16 center">推薦Beauty Garage 台灣美麗平台給朋友，朋友成功加入會員，並首次購物成功，您可獲得 <span class="r18"><b>100</b></span> 紅利點數</div>
          <!--friend-->
          <form action="<? echo site_url('member/check/friend') ?>" method="post">
            <ul class="styled-input" style="margin-top:30px;">
              <div class="join_line"></div>
              <li>
                <h2>朋友的E-mail*</h2>
                <input type="text" name="d_Femail" value="" />
              </li>
              <div class="join_line"></div>
              <li style="text-align:center;">
                <input type="submit" class="btn-style02" value="確認送出" /> <input type="reset" class="btn-style02" value="重新填寫" />
              </li>
            </ul>
          </form>
          <!--//friend-->
        </section>
      </div>
    </div>
  </article>
</main>
<?php include '_footer.php';?>
