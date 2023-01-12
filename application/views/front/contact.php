<?php include '_header.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.twzipcode.min.js')?>"></script>
<main>
  <article>
    <!--bread-->
    <div class="box-1">
      <ul class="breadcrumb">
        <li><a href="<? echo site_url('') ?>">首頁</a></li>
        <li class="active">聯絡我們</li>
      </ul>
    </div>
    <!--//bread-->
    <div class="container">
      <div class="col-lg-">
        <section class="content_box">
          <div class="title01 center">聯絡我們</div>
          <div class="w16 center">如果您有任何問題或疑慮，請隨時通過下面的表單與我們聯繫，我們會儘快與您連絡，<br>如果您急於更改訂單，請通過電話與我們聯繫。</div>

          <div class="row contact_box">
            <div class="box-contact-us">
              <p> <b>客服專線：<?echo $WebConfigData[10];?></b></p>
              <p>  （服務時間：<?echo strip_tags($WebConfigData[11]) ?>）</p>
            </div>
            <div class="box-contact-us">
              <p> <b><a href="<? echo site_url('qa') ?>">更多常見問題</a></b></p>
              <p> （您可在常見問題來解決您的問題！）</p>
            </div>
          </div>
          <!--contact-->
          <form id="form" action="<? echo site_url('contact/AddContact') ?>" method="post">
            <ul class="styled-input">
              <div class="join_line"></div>
              <li>
                <h2>詢問類型*</h2>
                <select name="d_type">
                  <option value="">---請選擇---</option>
                  <?php foreach ($Contact_type as $t): ?>
                    <option value="<? echo $t['d_id'] ?>" <?php echo !empty($PID)&&$t['d_id']==1?'selected':''; ?>><? echo $t['d_title'] ?></option>
                  <?php endforeach; ?>
                </select>
              </li>
              <li>
                <h2>內容*</h2>
                <textarea rows="5" name="d_content"><?php echo !empty($PID)?$PID:''; ?></textarea>
              </li>
              <div class="title03" style="margin-top:30px;">個人資訊</div>
              <div class="join_line"></div>
              <li class="half">
                <h2>姓名*</h2>
                <input type="text" name="d_name" />
              </li>
              <li class="half">
                <h2>公司名稱</h2>
                <input type="text" name="d_cname" />
              </li>
              <li class="half">
                <h2>聯絡電話*</h2>
                <input type="text" name="d_mobile" />
              </li>
              <li class="half">
                <h2>E-mail*</h2>
                <input type="text" name="d_mail" />
              </li>
              <li>
                <h2>地址*</h2>
                <div class="mem_add" id="twzipcode">
                  <div data-role="county" data-style="mem_add_inpt" class="mem_inpt"></div>
                  <div data-role="district" data-style="mem_add_inpt" class="mem_inpt"></div>
                  <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt"></div>
                </div>
                <input type="text2" name="d_address" />
              </li>
              <li>
                <h2>驗証碼*</h2>
                <input type="text" name="d_captcha"/>
              </li>
              <li class="contact-captcha">
                <img width="10%" id="captcha" src="<? echo site_url('contact/make_vcode_img') ?>" />
              </li>
              <div class="join_line"></div>
              <li style="text-align:center;">
                <input id="send" type="button" class="btn-style02" value="確認送出" /> <input type="reset" class="btn-style02" value="重新填寫" />
                <input type="hidden" name="d_type_Hide">
              </li>
            </ul>
          </form>
          <!--//contact-->
        </section>
      </div>
    </div>
  </article>
</main>
<script>
  $(function() {
    // 地址選擇
    $('#twzipcode').twzipcode({
      'countyName'   : 'd_county',
      'districtName' : 'd_district',
      'zipcodeName'  : 'd_zipcode'
    });
    $.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
    $('#captcha').click(function() {
      $(this).attr('src', $(this).attr('src') + '?' + Math.random());
    });
    $('#send').click(function() {
      $('input[name="d_type_Hide"]').val($('select[name="d_type"]').find(":selected").text());
      $('#form').submit();
    });
  });
</script>
<?php include '_footer.php';?>
