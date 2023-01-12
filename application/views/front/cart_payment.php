<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url('')?>">首頁</a></li>
          <li class="active">購物車</li>
        </ul>
      </div>
      <form action="<? echo site_url('cart/cart_information')?>" method="post" id="Cart2">
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">清單確認</div>
            <!--cart-->
            <div class="cart02">
              <div class="titlebox">
                <div class="name">商品</div>
                <div class="number">數量</div>
                <div class="price">小計</div>
              </div>
              <!-- 一般商品 -->
              <?foreach ($CartProduct['Cart'] as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></a></dd>
                      <dt>
                        <div class="tt"><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><?echo $value['d_title'];?></a></div>
                        <div class="sbox">
                          <div class="dtt">商品編號</div>
                          <div class="spec"><?echo $value['d_model'];?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">單價</div>
                          <div class="spec"><span>NT$.<?echo number_format($value['d_price']);?></span></div>
                        </div>
                        <?if(!empty($value['AddData'])):?>
                          <div class="sbox">
                            <div class="dtt">商品加購</div>
                            <div class="spec"><?echo $value['AddData']['AddTitle'].'+ NT$.'.$value['AddData']['AddPrice'].''?> </div>
                          </div>
                        <?endif;?>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="number"><?=$value['num']?></div>
                    <div class="price">$<?echo number_format($value['d_total']);?></div>
                  </div>
                  <!-- <div class="salesbox">
                    <div class="slist">
                      <div class="icon"><span class="icon_ok">符合</span></div>
                      <div class="sales"><a href="pd_sales.php" target="_blank">人氣推薦．2件 1,200</a></div>
                    </div>
                  </div>    -->
                </ul>
              <?endforeach;?>
              <!-- 一般商品 -->
              <!-- 加價購 -->
              <?foreach ($CartProduct['AddData'] as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></dd>
                      <dt>
                        <div class="tt"><?echo $value['d_title'];?></div>
                        <div class="sbox">
                          <div class="dtt">加購價</div>
                          <div class="spec"><span>NT$.<?echo number_format($value['d_price']);?></span></div>
                        </div>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="number">1</div>
                    <div class="price">$<?echo number_format($value['d_price']);?></div>
                  </div>
                  <div class="salesbox">
                    <div class="slist">
                      <div class="sales">加購價產品</div>
                    </div>
                  </div>
                </ul>
              <?endforeach;?>
              <!-- 加價購 -->

              <div class="cart_line"></div>
            </div>
            <!--//cart-->
          </section>
        </div>
      </div>
      <?if(!empty($Gdata)):?>
        <div class="gray_bg">
          <div class="container">
            <div class="col-lg-">
              <div class="content_box">
                <!--滿額贈-->
                <section class="pd_more02">
                  <div class="title05 center">滿額贈</div>
                  <div class="shortcut">
                    <div class="slider responsive" >
                      <!--item_pd-->
                      <?foreach ($Gdata as $key => $value):?>
                        <ul class="item_pd">
                          <li class="PordPHt"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></li>
                          <li class="PordTxs"><span class="PordTxsB"><input type="checkbox" name="d_gift[]" value="<?echo $value['d_id'];?>"><?echo $value['d_title'];?></span></li>
                        </ul>
                      <?endforeach;?>
                    <!--item_pd-->
                    </div>
                  </div>
                </section>
              <!--滿額贈-->
              </div>
            </div>
          </div>
        </div>
      <?endif;?>
        <div class="container">
          <div class="col-lg-">
            <section class="content_box">
              <div class="cart01">
                <!--小計-->
                <div class="all">
                  <div class="cost">
      	          <ul>
                      <dd>小計</dd>
                      <dt>$<?echo number_format($CartProduct['Total']);?></dt>
                    </ul>
                    <ul>
                      <dd>大型運費</dd>
                      <dt>$<?echo number_format($CartProduct['BigFreight']);?></dt>
                    </ul>
                    <ul>
                      <dd>一般運費</dd>
                      <dt>$<?echo number_format($CartProduct['Freight']) ;?></dt>
                    </ul>
                    <ul><h1>訂單滿<em>$<?echo number_format($CartProduct['OneFreight']['d_free']);?></em>元，免一般運費</h1></ul>
                    <div class="cart_line"></div>
                    <ul>
                      <dd><b>總計</b></dd>
                      <dt><span class="txt_total">$<?echo number_format($CartProduct['AllTotal']);?></span></dt>
                    </ul>
                    <ul>
                      <dd><b>本次訂單累計紅利</b></dd>
                      <dt><?echo number_format($CartProduct['BonusTotal']);?>點</dt>
                    </ul>
                    <div class="cart_line"></div>
                    <!-- <?if(!empty($this->autoful->Mlv)):?>
                      <ul>
                        本次消費達<span class="r16">NT$<? //echo number_format($last_money);?></span>，即可升級為<? //echo $Next_lv['d_title'] ?>
                      </ul>
                    <?endif;?> -->
                  </div>
                </div>
                <!--//小計-->
                <div class="text_right">
                  <input type="button" class="btn-style07" onClick="location='<?echo site_url('cart');?>'" value="上一步" />
                  <input type="submit" class="btn-style07" value="下一步" />
                </div>
              </div>
            </section>
          </div>
        </div>
      </form>
    </article>
</main>
<?php include '_footer.php';?>
<!--加價購-->
<script type="text/javascript">
$('#SubBonus').change(function(){
  Bonus=$(this).val();
  if(Bonus>=0){
    $.ajax({
      url:'<? echo site_url('cart/BonusOperation')?>',
      type:'POST',
      data: 'Bonus='+Bonus,
      dataType: 'json',
      success: function(json){
        if(json.Status=='OK'){
          $('.txt_total').html('$'+json.Subbonus);
        }else{
          $('#SubBonus').val('0');
          alert(json.Status);
        }
      }
    });
  }else{
    alert('紅利折扣不得為負');
    $(this).val('0');
  }
});
$('.responsive').slick({
  arrows: true,
  infinite: false,
  autoplay: false,
  speed: 300,
  slidesToShow: 5,
  slidesToScroll: 5,
  responsive: [
    {
      breakpoint: 1100,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    },
	{
      breakpoint: 950,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
      }
    },
	{
      breakpoint: 680,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
      }
    },
    {
      breakpoint: 400,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }

    }
  ]
});
</script>
<!---//加價購-->
