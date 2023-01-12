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
            <!--cart-->
            <form action="<? echo site_url('member/check/refund/'.$id) ?>" method="post">
            <div class="w16 center">請勾選欲退貨品項</div>
            <div class="cart02" style="margin-top:30px;">
              <div class="titlebox">
                <div class="name">商品</div>
                <div class="number">數量</div>
                <div class="price">小計</div>
              </div>
              <?foreach ($orders_detail as $key => $value):?>
                <ul>
                    <div class="namebox">
                      <div class="name">
                        <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" /></dd>
                        <dt>
                          <div class="tt"><input type="checkbox" name="d_back[]" value="<?echo $value['d_id'];?>" /><?echo $value['d_title'];?></div>
                          <div class="sbox">
                            <div class="dtt">商品編號</div>
                            <div class="spec"><?echo $value['d_model'];?></div>
                          </div>
                          <div class="sbox">
                            <div class="dtt">特價</div>
                            <div class="spec"><span>NT$.<?echo number_format($value['d_price']);?></span></div>
                          </div>
                          <?if(!empty($value['d_addtitle'])):?>
                            <div class="sbox">
                              <div class="dtt">商品加購</div>
                              <div class="spec"><?echo $value['d_addtitle'].'+ NT$.'.$value['d_addprice'].''?> </div>
                            </div>
                        <?endif;?>
                        </dt>
                      </div>
                    </div>
                    <div class="numberbox">
                      <div class="number"><?=$value['d_num']?></div>
                      <div class="price">$<?echo number_format($value['d_total']);?></div>
                    </div>
                    <?php if (!empty($value['Stitle'])): ?>
                      <div class="salesbox">
                        <div class="slist">
                          <div class="icon"><span class="icon_ok">符合</span></div>
                          <div class="sales"><a href="javascript:void(0)"><?php echo $value['Stitle'] ?></a></div>
                        </div>
                      </div>
                    <?php endif; ?>
                </ul>
              <?endforeach;?>
              <div class="cart_line"></div>
            </div>
            <!--//cart-->
            <!--apply-->

              <ul class="styled-input">

                <div class="title03" style="margin-top:30px;">申請退貨</div>
                <div class="join_line"></div>
                <li>
                  <h2>姓名</h2>
                  <h4><? echo $member_info['LName'] ?></h4>
                </li>
                <li class="half">
                  <h2>E-mail</h2>
                  <input type="text" name="d_return_email" value="<? echo $member_info['LEmail'] ?>" />
                </li>
                <li class="half">
                  <h2>聯絡電話*</h2>
                  <input type="text" name="d_return_phone" value="<? echo $member_info['LPhone'] ?>" />
                </li>
                <li>
                  <h2>退貨原因*</h2>
                   <textarea name="d_return_content" rows="5"></textarea>
                </li>
                <div class="join_line"></div>
                <li style="text-align:center;">
                  <input type="submit" class="btn-style02" value="確認送出"/>
                  <input type="reset" class="btn-style02" value="重新填寫"/>
                </li>

              </ul>
            </form>
            <!--//apply-->
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
