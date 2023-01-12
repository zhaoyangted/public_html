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
					<div class="title01 center">會員資料修改</div>
					<?php include '_member_menu.php';?>
					<form action="<? echo site_url('member/check/account') ?>" method="post">
						<div class="mbox">
							<dd class="sell" style="margin-top:-20px;">
								<li>
									<label class="method-label" for="deliver02"><input type="radio" id="deliver02" checked /><? echo $dbdata['d_title'] ?></label>
								</li>
							</dd>
						</div>
						<ul class="styled-input">
							<?php if ($dbdata['d_user_type']==2): ?>
								<article id="company">
		              <div class="title03" style="margin-top:30px;">公司資訊</div>
		              <div class="join_line"></div>
									<div class="company_box">
										<li>
											<h2>名稱*</h2>
											<input type="text" name="d_company_name"  value="<? echo $dbdata['d_company_name'] ?>"/>
										</li>
										<li>
											<h2 id="d_company_title">公司抬頭*</h2>
											<input type="text" name="d_company_title" value="<? echo $dbdata['d_company_title'] ?>"/>
										</li>
										<li>
											<h2 id="d_company_number">公司統編*</h2>
											<input type="text" name="d_company_number" value="<? echo $dbdata['d_company_number'] ?>"/>
										</li>
										<li class="half">
											<h2>電話*</h2>
											<input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:13%;float:inherit;" name="d_company_tel_area" placeholder="區域碼" value="<? echo $dbdata['d_company_tel_area'] ?>"/>-
											<input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:40%;float:inherit;" name="d_company_tel" placeholder="電話" value="<? echo $dbdata['d_company_tel'] ?>"/>
										</li>
										<li class="half">
											<h2>傳真</h2>
											<input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:13%;float:inherit;" name="d_company_fax_area" placeholder="區域碼" value="<? echo $dbdata['d_company_fax_area'] ?>"/>-
											<input type="text" onkeyup="value=value.replace(/[^\d]/g,'') " style="width:40%;float:inherit;" name="d_company_fax" placeholder="傳真" value="<? echo $dbdata['d_company_fax'] ?>"/>
										</li>
										<li>
											<h2>公司網站</h2>
											<input type="text" name="d_company_website" value="<? echo $dbdata['d_company_website'] ?>"/>
										</li>
										<li>
											<h2>公司地址*</h2>
											<div class="mem_add" id="twzipcode02">
												<div data-role="county" data-style="mem_add_inpt" class="mem_inpt" data-value="<? echo $dbdata['d_company_county'] ?>"></div>
												<div data-role="district" data-style="mem_add_inpt" class="mem_inpt" data-value="<? echo $dbdata['d_company_district'] ?>"></div>
												<div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt" data-value="<? echo $dbdata['d_company_zipcode'] ?>"></div>
											</div>
											<input type="text2" name="d_company_address" value="<? echo $dbdata['d_company_address'] ?>" />
										</li>
									</div>
								</article>
							<?php endif; ?>
							<div class="title03" style="margin-top:30px;">建立帳戶</div>
							<div class="join_line"></div>
							<li>
								<h2>E-mail*</h2>
								<h4><? echo $dbdata['d_account'] ?></h4>
							</li>
							<li>
								<h2>密碼*</h2>
								<input type="password" name="d_password" />
							</li>
							<li>
								<h2>確認密碼*</h2>
								<input type="password" name="d_repassword" />
							</li>
							<div class="title03" style="margin-top:30px;">會員資料</div>
							<div class="join_line"></div>
							<li class="half">
								<h2>姓名*</h2>
								<h4><? echo $dbdata['d_pname'] ?></h4>
							</li>
							<li class="half">
								<h2>職稱*</h2>
								<input type="text" name="d_job" value="<? echo $dbdata['d_job'] ?>" />
							</li>
							<li class="half">
								<h2>生日*</h2>
								<h4><? echo $dbdata['d_birthday'] ?></h4>
							</li>
							<li class="half">
								<h2>手機號碼*</h2>
								<input type="text" name="d_phone" value="<? echo $dbdata['d_phone'] ?>" />
							</li>
							<?php if ($dbdata['d_user_type']==1): ?>
								<li>
	                <h2>地址*</h2>
	                <div class="mem_add" id="twzipcode">
	                  <div data-role="county" data-style="mem_add_inpt" class="mem_inpt" data-value="<? echo $dbdata['d_county'] ?>"></div>
	                  <div data-role="district" data-style="mem_add_inpt" class="mem_inpt" data-value="<? echo $dbdata['d_district'] ?>"></div>
	                  <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt" data-value="<? echo $dbdata['d_zipcode'] ?>"></div>
	                </div>
	                <input type="text2" name="d_address" value="<? echo $dbdata['d_address'] ?>" />
	              </li>
							<?php endif; ?>
							<div class="check_list_box">
								<div class="check_list">
									<input type="checkbox" id="c2" name="d_newsletter" <? echo $dbdata['d_newsletter']=='Y' ?'checked':''; ?> value="Y" /><label for="c2"><span></span>
										<h5>訂閱電子報</h5>
									</label>
								</div>
							</div>
							<li>
								<h2>驗証碼*</h2>
								<input type="text" name="d_captcha"/>
							</li>
							<li class="contact-captcha">
								<img width="10%" id="captcha" src="<? echo site_url('login/make_vcode_img') ?>" />
							</li>
							<li style="text-align:center;">
								<input type="submit" class="btn-style02" value="確認送出" /> <input type="reset" class="btn-style02" value="重新填寫" />
							</li>
						</ul>
					</form>
				</section>
			</div>
		</div>
	</article>
</main>
<?php include '_footer.php';?>
<script>
	$(document).ready(function() {
		// 生日日期
		$("#datepicker").datepicker();
		// 地址選擇
    $('#twzipcode').twzipcode({
      'countyName'   : 'd_county',
      'districtName' : 'd_district',
      'zipcodeName'  : 'd_zipcode'
    });
		$('#twzipcode02').twzipcode({
      'countyName'   : 'd_company_county',
      'districtName' : 'd_company_district',
      'zipcodeName'  : 'd_company_zipcode'
    });
		$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
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
		$('input[type="radio"]:checked').parent().addClass('checked');
		$(".error-order").click(function() {
			$(".invoice_box").fadeIn();
		});
		$('#captcha').click(function() {
      $(this).attr('src', $(this).attr('src') + '?' + Math.random());
    });
	});
</script>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.twzipcode.min.js')?>"></script>
<script src="<? echo CCODE::DemoPrefix.('/js/front/cart.js')?>"></script>
