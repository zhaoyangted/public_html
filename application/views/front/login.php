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
          <div class="title01 center">會員登入</div>
          <!--會員登入-->
          <div class="member">
            <div class="mbox">
              <form action="<? echo site_url('login/login_ok') ?>" method="post">
                <ul class="styled-input">
                  <li>
                    <h2>帳號*</h2>
                    <input type="text" name="d_account" />
                  </li>
                  <li>
                    <h2>密碼*</h2>
                    <input type="password" name="d_password" />
                  </li>
                  <li>
                    <h2>驗証碼*</h2>
                    <input type="text" name="d_captcha" />
                  </li>
                  <li class="contact-captcha">
                    <img width="20%" id="captcha" src="<? echo site_url('login/make_vcode_img') ?>" />
                  </li>
                  <div class="pw"><a href="<? echo site_url('login/forgetpwd') ?>">忘記密碼?</a></div>
                  <li style="text-align:center;">
                    <input type="submit" class="btn-style02" value="登入" /> <input type="button" class="btn-style02" value="加入會員" onclick="location='<? echo site_url('login/join') ?>'" />
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
<?php include '_footer.php';?>
<script>
  $('#captcha').click(function() {
    $(this).attr('src', $(this).attr('src') + '?' + Math.random());
  });
</script>
