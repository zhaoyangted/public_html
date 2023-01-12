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
          <form action="<? echo site_url('member/check/ask/'.$id) ?>" method="post">
            <ul class="styled-input">
              <div class="title03" style="margin-top:30px;">訂單詢問</div>
              <div class="join_line"></div>
              <li class="half">
                <h2>訂單編號</h2>
                <h4><? echo $OID ?></h4>
              </li>
              <li>
                <h2>姓名*</h2>
                <input type="text" name="d_name" value="<? echo $member_info['LName'] ?>" />
              </li>
              <li class="half">
                <h2>E-mail*</h2>
                <input type="text" name="d_email" value="<? echo $member_info['LEmail'] ?>" />
              </li>
              <li class="half">
                <h2>聯絡電話*</h2>
                <input type="text" name="d_phone" value="<? echo $member_info['LPhone'] ?>" />
              </li>
              <li>
                <h2>詢問內容</h2>
                <textarea name="d_content" rows="5"></textarea>
              </li>
              <div class="join_line"></div>
              <li style="text-align:center;">
                <input type="submit" class="btn-style02" value="確認送出" /> <input type="reset" class="btn-style02" value="重新填寫" />
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
