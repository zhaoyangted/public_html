<?php include '_header.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/quantity.js')?>"></script>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url('')?>">首頁</a></li>
          <li class="active">購物車</li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">購物清單</div>
            <!--cart-->
            <div class="cart01">
              <div class="titlebox">
                <div class="name">商品</div>
                <div class="number">數量</div>
                <div class="price">小計</div>
                <div class="del"></div>
              </div>
              <!-- 一般商品 -->
              <?foreach ($CartProduct['Cart'] as $key => $value):?>
                <ul <?php echo $value['stock']<=0||(!empty($value['AddData']['Chkop'])&&$value['AddData']['Chkop']=='N') ?'class="stock_btn"':''; ?>>
                  <?php if ($value['stock']<=0): ?>
                    <div class="stock"><span>目前無庫存</span></div>
                  <?php elseif(!empty($value['AddData']['Chkop'])&&$value['AddData']['Chkop']=='N'): ?>
                    <div class="stock"><span>目前商品選配【<?php echo $value['AddData']['AddTitle'] ?>】庫存剩餘：<?php echo $value['AddData']['Addstock'] ?></span></div>
                  <?php endif; ?>
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
                            <div class="dtt">商品選配</div>
                            <div class="spec"><?echo $value['AddData']['AddTitle'].'+ NT$.'.$value['AddData']['AddPrice'].''?> </div>
                          </div>
                        <?endif;?>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="number">
                      <div class="quantity buttons_added" <?php echo (!empty($value['AddData']['Chkop'])&&$value['AddData']['Chkop']=='N')?'style="z-index: 1;position: relative;background-color: #ffffff;"':''; ?>>
                        <input type="button" value="-" class="minus">
                        <input type="number" step="1" min="1" max="<?echo $value['stock'];?>" name="d_num" value="<?=$value['num']?>" title="Qty" class="input-text qty text" size="4" spec="<?echo (!empty($value['AddData']['Addid'])?$value['AddData']['Addid']:'')?>" rel="<?echo $value['d_id']?>">
                        <input type="button" value="+" class="plus">
                      </div>
                    </div>
                    <div class="price">$<?echo number_format($value['d_total']);?></div>
                    <div class="del">
                        <a href="javascript: void(0)" class="btn-style06" id="Delete" rel="<?= $value['Ckey'];?>" data-num="<?=$value['num']?>"><i class="fas fa-times"></i><span>刪　　除</span></a>
                        <?if(!empty($_SESSION[CCODE::MEMBER]['LID'])):?>
                          <a href="javascript: void(0)" class="btn-style06" id="AddFavourite" rel="<?echo $value['d_id'];?>" spec="<?echo (!empty($value['AddData']['Addid'])?$value['AddData']['Addid']:'')?>"><i class="far fa-heart"></i><span>移至收藏</span></a>
                        <?endif;?>
                    </div>
                  </div>
                  <?php if ($value['IsSale']): ?>
                    <div class="salesbox">
                      <div class="slist">
                        <div class="icon"><span class="icon_ok">符合</span></div>
                        <div class="sales"><a href="<? echo site_url('products/sales/'.$this->autoful->DiscountData[$value['d_id']]['d_id'].'') ?>" target="_blank"><?php echo $this->autoful->DiscountData[$value['d_id']]['d_title'] ?></a></div>
                      </div>
                    </div>
                  <?php endif; ?>
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
                    <div class="del">
                        <a href="javascript: void(0)" class="btn-style06" id="DeleteAdd" rel="<?echo $value['d_id'];?>"><i class="fas fa-times"></i><span>刪　　除</span></a>
                    </div>
                  </div>
                  <div class="salesbox">
                    <div class="slist">
                      <div class="sales">加購價產品</div>
                    </div>
                  </div>
                </ul>
              <?endforeach;?>
              <!-- 加價購 -->
              <?php if (!empty($CartProduct['TrialData'])): ?>
                <!-- 試用品 -->
                <?foreach ($CartProduct['TrialData'] as $key => $value):?>
                  <ul <?php echo $value['d_stock']<=0 ?'class="stock_btn"':''; ?>>
                    <?php if ($value['d_stock']<=0): ?>
                      <div class="stock"><span>目前無庫存</span></div>
                    <?php endif; ?>
                    <div class="namebox">
                      <div class="name">
                        <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></dd>
                        <dt>
                          <div class="tt"><?echo $value['d_title'];?></div>
                          <div class="sbox">
                            <div class="dtt">商品編號</div>
                            <div class="spec"><?echo $value['d_model'];?></div>
                          </div>
                        </dt>
                      </div>
                    </div>
                    <div class="numberbox">
                      <div class="number">1</div>
                      <div class="price">---</div>
                      <div class="del">
                          <a href="javascript: void(0)" class="btn-style06" id="DeleteTrial" rel="<?echo $value['d_id'];?>"><i class="fas fa-times"></i><span>刪　　除</span></a>
                      </div>
                    </div>
                    <div class="salesbox">
                      <div class="slist">
                        <div class="slist">試用品</div>
                      </div>
                    </div>
                  </ul>
                <?endforeach;?>
                <!-- 試用品 -->
              <?php endif; ?>
              <div class="cart_line"></div>
            </div>
            <!--//cart-->
          </section>
        </div>
      </div>
      <?if(!empty($Mdata)):?>
        <div class="gray_bg">
          <div class="container">
            <div class="col-lg-">
              <div class="content_box">
                <!--加價購-->
                <section class="pd_more02">
                  <div class="title05 center">滿額<span>$<?echo $Mdata[0]['d_aprice'];?></span>，您可加價購買</div>
                  <div class="shortcut">
                    <div class="slider responsive" >
                      <!--item_pd-->
                      <?foreach ($Mdata as $key => $value):?>
                        <ul class="item_pd">
                            <li class="PordPHt"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></li>
                            <li class="PordTxs"><span class="PordTxsB"><?echo $value['d_title'];?></span></li>
                            <li class="TicPystxc02">加購價：NT$.<?echo number_format($value['d_price']);?></li>
                            <ul class="bantBOX">
                                <li class="butt01">
                                    <input type="button" class="bant01" value="立即購買" id="Addcart" rel="<?echo $value['d_id'];?>">
                                </li>
                            </ul>
                        </ul>
                      <?endforeach;?>
                    <!--//item_pd-->
                  </div>
                </div>
              </section>
              <!--//加價購-->
              </div>
            </div>
          </div>
        </div>
      <?endif;?>
      <div class="gray_bg">
        <div class="container">
          <div class="col-lg-">
            <section class="content_box">
              <!--cart-->
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
                    <ul><h1>訂單小計滿<em>$<?echo number_format($CartProduct['OneFreight']['d_free']);?></em>元，免一般運費</h1></ul>
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
                <!--小計-->
                <div class="text_right">
                  <input type="button" class="btn-style07" onClick="location='<?echo site_url();?>'" value="繼續購物" />
                  <?if($CartProduct['Chkpay']=='Y' && $CartProduct['Chkop']=='Y'):?>
                    <input type="button" class="btn-style07" onClick="location='<?echo site_url('cart/cart_login');?>'" value="結帳" />
                  <?else:?>
                    <input type="button" class="btn-style07" value="請先刪除無庫存產品" />
                  <?endif;?>
                </div>
              </div>
              <!--//cart-->
            </section>
          </div>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>

<!--加價購-->
<script type="text/javascript">
$('input[id="Addcart"]').click(function(){
  id=$(this).attr('rel');
  $.ajax({
      url:'<? echo site_url('cart/ChangeAddCart')?>',
      type:'POST',
      data: 'id='+id,
      dataType: 'text',
      success: function( json ){
        // console.log(json);
        window.location.reload();
      }
  });
});
$('a[id="DeleteAdd"]').click(function(){
  if(confirm('確定從購物車刪除?')){
    id=$(this).attr('rel');
    $.ajax({
        url:'<? echo site_url('cart/RemoveAddCart')?>',
        type:'POST',
        data: 'id='+id,
        dataType: 'text',
        success: function( json ){
          window.location.reload();
        }
    });
  }
});
$('a[id="DeleteTrial"]').click(function(){
  if(confirm('確定從購物車刪除?')){
    id=$(this).attr('rel');
    $.ajax({
        url:'<? echo site_url('cart/RemoveTrialCart')?>',
        type:'POST',
        data: 'id='+id,
        dataType: 'text',
        success: function( json ){
          window.location.reload();
        }
    });
  }
});
$("input[name='d_num']").change(function(){
  str='';
  is=0;
  numArr = [];
  $("input[name='d_num']").each(function(){
      data = [];
      num=$(this).val();
      id=$(this).attr('rel');
      if(num<=0){
          is++;
      }
      spec=$(this).attr('spec');
      data[0] = id;
      data[1] = num;
      data[2] = spec;
      numArr.push(data);

  });
  if(is!=0){
      alert('數量不得為零或負數');
      return '';
  }
  $.ajax({
      url:'<? echo site_url('cart/ChangeCart')?>',
      type:'POST',
      data: {numArr:numArr},
      dataType: 'text',
      success: function( json ){
        window.location.reload();
      }
  });
});
$('a[id="Delete"]').click(function(){
  if(confirm('確定從購物車刪除?')){
    id=$(this).attr('rel');
    num=$(this).attr('data-num');
    $.ajax({
        url:'<? echo site_url('cart/RemoveCart')?>',
        type:'POST',
        data: 'id='+id+'&num='+num,
        dataType: 'text',
        success: function( json ){
          window.location.reload();
        }
    });
  }
})
// 加入最愛
  $('a[id="AddFavourite"]').click(function(){
    Id=$(this).attr('rel');
    AID=$(this).attr('spec');
    $.ajax({
      type: "post",
      url: '<? echo CCODE::DemoPrefix.('/products/AddFavourite')?>',
      data: {
          PID:Id,
          AID:AID
      },
      dataType :'text',
      cache: false,
      success: function (response) {
        if(response=='NoLogin'){
          alert('請先登入會員');
          window.location.href="<? echo CCODE::DemoPrefix.('/login')?>";
        }
        if(response=='IsHave' || response=='Success'){
          alert('已加入我的最愛');
        }
      }
    });
  });
$('.responsive').slick({
  arrows: true,
  infinite: false,
  autoplay: false,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 4,
  responsive: [
	{
      breakpoint: 1000,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
      }
    },
	{
      breakpoint: 780,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
      }
    },
    {
      breakpoint: 510,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }

    }
  ]
});
</script>
