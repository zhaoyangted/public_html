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
          <div class="title01 center title-join">加入會員</div>
          <p class="title01-ps">
          ‧為保護沙龍店家權益，加入會員時請選擇企業用戶，資格審核通過後，將取得沙龍價資格。<br>
<br>
‧台灣千冠莉採用人工審核會員資格，工作時間約1-3工作天。加入官方line帳號：@ckltw，提供店家資料可加快審核。
<br>
<br>
‧會員加入教學請參考：<a href="/news/info/115" target="_blank" rel="noopener">/news/info/115</a>
<br>
<br>
如造成不便敬請見諒。<br><br>
          </p>
          <form action="<? echo site_url('login/register') ?>" method="post" id="Joinform">
            <div class="title03">請選擇</div>
            <div class="mbox">
              <dd class="sell" style="margin-top:-20px;">
                <?php $Mnum=1;foreach ($Member_user_types as $k => $u): ?>
                  <li>
                    <label class="method-label" for="user<? echo $k ?>"><input type="radio" onclick="user_type(this.value)" name="d_user_type" id="user<? echo $k ?>" value="<? echo $k ?>" <?echo ($Mnum==2)?'checked':'';?>/><? echo $u ?></label>
                  </li>
                <?php $Mnum++;endforeach; ?>
              </dd>
            </div>
            <ul class="styled-input">
              <div class="title03" style="margin-top:30px;">建立帳戶</div>
              <div class="join_line"></div>
              <li>
                <h2>E-mail*</h2>
                <input type="text" name="d_account" required />
              </li>
              <li class="ps">E-mail為您的登入帳號，務必填寫有效E-mail</li>
              <li>
                <h2>密碼*</h2>
                <input type="password" name="d_password" required/>
              </li>
              <li class="ps">請輸入6位數以上英數混合字元，密碼大小寫有差別</li>
              <li>
                <h2>確認密碼*</h2>
                <input type="password" name="d_repassword" required/>
              </li>
            <article id="company">
              <div class="title03" style="margin-top:30px;">公司資訊</div>
              <div class="join_line"></div>
              <div class="mbox02">
                <div class="sell02">
                  <?php $Mnum=1;foreach ($Member_company_types as $k => $c): ?>
                    <div class="sell_list">
                      <label class="method-label" for="company<? echo $k ?>"><input type="radio" name="d_company_type" id="company<? echo $k ?>" value="<? echo $k ?>" <?echo ($Mnum==1)?'checked':'';?>/><? echo $c ?></label>
                    </div>
                  <?php $Mnum++;endforeach; ?>
                </div>
              </div>
              <div class="company_box">
                <li>
                  <h2>名稱*</h2>
                  <input type="text" name="d_company_name"  />
                </li>
                <li>
                  <h2>主要營業類別*</h2>
                  <div class="company_list">
                    <?php foreach ($Member_types as $t): ?>
                      <dd>
                        <label for="TID_<?echo $t['d_id']?>">
                          <input type="radio" name="TID" value="<? echo $t['d_id'] ?>" id="TID_<?echo $t['d_id']?>"/>
                          <? echo $t['d_title'] ?>
                        </label>
                      </dd>
                    <?php endforeach; ?>
                  </div>
                </li>
                <li>
                  <h2>次要營業類別</h2>
                  <div class="company_list">
                    <?php foreach ($Member_types as $t): ?>
                      <dd>
                        <label for="TID1_<?echo $t['d_id']?>">
                          <input type="checkbox" name="TID1[]" value="<? echo $t['d_id'] ?>" id="TID1_<?echo $t['d_id']?>"/>
                          <? echo $t['d_title'] ?>
                        </label>
                      </dd>
                    <?php endforeach; ?>
                  </div>
                </li>
                <li>
                  <h2 id="d_company_title">公司抬頭*</h2>
                  <input type="text" name="d_company_title" />
                </li>
                <li>
                  <h2 id="d_company_number">公司統編*</h2>
                  <input type="text" name="d_company_number" />
                </li>
                <li class="half">
                  <h2>電話*</h2>
                  <input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:13%;float:inherit;" name="d_company_tel_area" placeholder="區域碼" />-
                  <input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:40%;float:inherit;" name="d_company_tel" placeholder="電話"/>
                </li>
                <li class="half">
                  <h2>傳真</h2>
                  <input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:13%;float:inherit;" name="d_company_fax_area" placeholder="區域碼"/>-
                  <input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:40%;float:inherit;" name="d_company_fax" placeholder="傳真"/>
                </li>
                <li>
                  <h2>公司網站</h2>
                  <input type="text" name="d_company_website" />
                </li>
                <li>
                  <h2>公司地址*</h2>
                  <div class="mem_add" id="twzipcode">
                    <div data-role="county" data-style="mem_add_inpt" class="mem_inpt"></div>
                    <div data-role="district" data-style="mem_add_inpt" class="mem_inpt"></div>
                    <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt"></div>
                  </div>
                  <input type="text2" name="d_company_address" value="" />
                </li>
              </div>
              <div class="title03" style="margin-top:30px;">營業狀況</div>
              <div class="join_line"></div>
              <div class="mbox02">
                <div class="sell02">
                  <?php $Mnum=1;foreach ($Member_operate_types as $k => $o): ?>
                    <div class="sell_list">
                      <label class="method-label03" for="operate<? echo $k ?>"><input type="radio" name="d_operate_type" id="operate<? echo $k ?>" value="<? echo $k ?>" <?echo ($Mnum==1)?'checked':'';?>/><? echo $o ?></label>
                    </div>
                  <?php $Mnum++;endforeach; ?>
                </div>
              </div>
              <span id="Open">
                <div class="company_box">
                  <li>
                    <h2>開業日期*</h2>
                    <input type="text" name="d_operate_date" id="datepicker" />
                  </li>
                  <li>
                    <h2>預定地址*</h2>
                    <input type="text" name="d_operate_address"/>
                  </li>
                </div>
                <div class="company_box">
                  <li>
                    <h2>員工人數*</h2>
                    <input type="text" name="d_operate_people" />
                  </li>
                  <li>
                    <h2>服務項目*</h2>
                    <div class="company_list">
                      <? $service = array('剪髮', '洗髮', '染髮', '燙髮', '接髮', '刮鬍', '頭部按摩', '臉部保養', '身體(瘦身/含放鬆)',
                       '除毛', '足部按摩', '整體按摩', '美睫', '美甲/足部美甲', '行動美容', '針灸美容', '接骨・整骨');?>
                      <? foreach ($service as $s): ?>
                        <dd>
                          <label class="method-label03" for="service<? echo $s ?>">
                            <input type="checkbox" name="d_operate_service[]" value="<? echo $s ?>" id="service<? echo $s ?>"/><? echo $s ?>
                          </label>
                        </dd>
                      <? endforeach; ?>
                      <dt><input type="checkbox" name="d_operate_service[]" onclick="service_Other()" id="service_other" value="" />其他：</dt>
                      <input type="text" class="other" onchange="service_Other()" id="service_other_value" />
                    </div>
                  </li>
                </div>
              </span>
            </article>
              <div class="title03" style="margin-top:30px;">會員資料</div>
              <div class="join_line"></div>
              <li class="half">
                <h2>姓名*</h2>
                <input type="text" name="d_pname" required/>
              </li>
              <li class="half">
                <h2>職稱*</h2>
                <input type="text" name="d_job" required/>
              </li>
              <li class="half">
                <h2>生日*</h2>
                <input type="text" name="d_birthday" id="datepicker02" placeholder="<?php echo date('Y/m/d') ?>" required/>
              </li>
              <li class="half">
                <h2>手機號碼*</h2>
                <input type="text" name="d_phone" required/>
              </li>
              <li id="user_address">
                <h2>地址*</h2>
                <div class="mem_add" id="twzipcode02">
                  <div data-role="county" data-style="mem_add_inpt" class="mem_inpt"></div>
                  <div data-role="district" data-style="mem_add_inpt" class="mem_inpt"></div>
                  <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt"></div>
                </div>
                <input type="text2" name="d_address" value="" required/>
              </li>
              <li class="half">
                <h2>業務員*</h2>
                <select class="mem_add_inpt" name="SID" required>
                  <?php foreach ($Member_Sales as $s): ?>
                    <option value="<?php echo $s['d_id'] ?>" <?php echo ($s['d_id']=='10')?'selected':''; ?>><?php echo $s['d_title'] ?></option>
                  <?php endforeach; ?>
                </select>
              </li>
              <div class="check_list_box">
                <div class="check_list">
                  <input type="checkbox" id="c2" name="d_newsletter" value="1" /><label for="c2"><span></span>
                    <h5>訂閱電子報</h5>
                  </label>
                </div>
                <div class="check_list">
                  <input type="checkbox" id="c3" name="chkok" value="1" /><label for="c3"><span></span>
                    <h5>我已詳細閱讀<a href="#member_terms" class="fancybox">會員條款</a></h5>
                  </label>
                </div>
              </div>
              <li>
                <h2>驗証碼*</h2>
                <input type="text" name="d_captcha" value="" required/>
              </li>
              <li class="contact-captcha">
                <img width="10%" id="captcha" src="<? echo site_url('login/make_vcode_img') ?>" />
              </li>
              <li style="text-align:center;">
                <input type="submit" class="btn-style02" value="確認送出" /> <input type="reset" class="btn-style02" value="重新填寫" />
                <?php if (!empty($FID)): ?>
                  <input type="hidden" name="FID" value="<? echo $FID ?>" />
                <?php endif; ?>
              </li>
            </ul>
          </form>
        </section>
      </div>
    </div>
  </article>
</main>
<!-- member_terms -->
<div id="member_terms" class="fancy-box">
  <section class="member-terms">
    <!--user_editor-->
    <div class="user_editor">
      <? echo !empty($Member_rules)?$Member_rules:''; ?>
    </div>
    <!--user_editor-->
  </section>
</div><!-- //member_terms -->
<?php include '_footer.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.twzipcode.min.js')?>"></script>
<script src="<? echo CCODE::DemoPrefix.('/js/front/cart.js')?>"></script>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/datepicker-zh-TW.js')?>"></script>

<script>
  $(document).ready(function() {
    $('#Open').hide();
    $('input[name="d_company_type"]').change(function(){
      id=$(this).val();
      if(id==1){
        $('#d_company_title').html('公司抬頭*');
        $('#d_company_number').html('公司統編*');
        $('input[name="d_company_title"],input[name="d_company_number"]').attr('required',true);
      }else{
        $('#d_company_title').html('公司抬頭');
        $('#d_company_number').html('公司統編');
        $('input[name="d_company_title"],input[name="d_company_number"]').attr('required',false);
      }

    });
    $('input[name="d_operate_type"]').change(function(){
      id=$(this).val();
      if(id==1){
        $('#Open').hide();
      }else{
        $('#Open').show();
        $('input[name="d_operate_date"],input[name="d_operate_address"],input[name="d_operate_people"]').attr('required',true);
      }
    });

    // 生日日期
    $.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
    $("#datepicker, #datepicker02").datepicker({ 
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy/mm/dd',
        yearRange: "-100:+0", 
    });
    // 地址選擇
    $('#twzipcode').twzipcode({
      'countyName'   : 'd_company_county',
      'districtName' : 'd_company_district',
      'zipcodeName'  : 'd_company_zipcode'
    });
    $('#twzipcode02').twzipcode({
      'countyName'   : 'd_county',
      'districtName' : 'd_district',
      'zipcodeName'  : 'd_zipcode'
    });
    

    // checkbox
    $('.method-label').click(function() {
      $(this).parent().siblings().find('.method-label').removeClass('checked');
      $(this).addClass('checked');
    });
    $('.method-label02').click(function() {
      $(this).parent().siblings().find('.method-label02').removeClass('checked');
      $(this).addClass('checked');
    });
    $('.method-label03').click(function() {
      $(this).parent().siblings().find('.method-label03').removeClass('checked');
      $(this).addClass('checked');
    });

    $(".request").click(function() {
      $(".join_clickbox").fadeToggle(800);
      $(".join_clickbox02").fadeOut(100);
    });
    $(".request02").click(function() {
      $(".invoice_box02").fadeToggle(800);
      $(".invoice_box").fadeOut(100);
    });

    $('#captcha').click(function() {
      $(this).attr('src', $(this).attr('src') + '?' + Math.random());
    });

    $('input[type="radio"]:checked').closest('label').addClass('checked');

    user_type($('input[type="radio"]:checked').val());
    service_Other();
  });
  $("#Joinform").submit(function(e){
    if(!$("input[name='chkok']").prop("checked")) {
      alert('請勾選我已詳細閱讀<會員條款>');
      e.preventDefault();
    }
  });
  function service_Other() {
    if ($('#service_other').prop("checked")) {
      $('#service_other').val($('#service_other_value').val());
    }else{
      $('#service_other').val('');
    }
  }

  function user_type(id) {
    if (id==1) {
      $('#company').hide();
      $('#user_address').show();
      $('input[name="d_address"]').attr('required',true);
      $('input[name="TID"],input[name="d_company_name"],input[name="d_company_tel_area"],input[name="d_company_tel"],input[name="d_company_address"],input[name="d_company_title"],input[name="d_company_number"]').attr('required',false);
    }else{
      $('#company').show();
      $('#user_address').hide();
      $('input[name="d_address"]').attr('required',false);
      $('input[name="TID"],input[name="d_company_name"],input[name="d_company_tel_area"],input[name="d_company_tel"],input[name="d_company_address"],input[name="d_company_title"],input[name="d_company_number"]').attr('required',true);

    }
  }

</script>
