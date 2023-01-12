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
            <div class="title01 center">忘記密碼</div>
            <!--會員登入-->
            <div class="member">
              <div class="mbox">
                <form action="<? echo site_url('login/forget_ok') ?>" method="post">
                  <ul class="styled-input">
                    <li>
                      <h2>E-mail</h2>
                      <input type="text" name="d_account"/>
                    </li>
                    <li>
                      <h2>驗証碼*</h2>
                      <input type="text" name="d_captcha"/>
                    </li>
                    <li class="contact-captcha">
                      <img id="captcha" src="<? echo site_url('login/make_vcode_img') ?>" />
                    </li>
                    <div class="pw"><a href="<? echo site_url('login') ?>">會員登入</a></div>
                    <li style="text-align:center;">
                      <input type="submit" class="btn-style02" value="發送至信箱"/> <input type="button" class="btn-style02" value="加入會員" onclick="location='<? echo site_url('join') ?>'"/>
                    </li>
                  </ul>
                </form>
              </div>
            </div>
            <!--//會員登入-->
          </section>
        </div>
      </div>
    </article>
</main>
<script>
  $('#captcha').click(function() {
    $(this).attr('src', $(this).attr('src') + '?' + Math.random());
  });
</script>
<?php include '_footer.php';?>
