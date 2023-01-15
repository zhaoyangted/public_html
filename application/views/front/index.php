<?php include '_header.php';?>
<main>
    <article>
        <? if(!empty($BannerData)):?>
        <div class="int_bannerCS">
            <?foreach ($BannerData as $value):?>
                <ul><a href="<? echo (!empty($value['d_link'])?$value['d_link']:'javascript:void(0)')?>"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></a></ul>
            <? endforeach;?>
        </div>
        <?endif;?>
        <? if(!empty($ActionData)):?>
            <div class="commBannBx">
                <div class="commBannItm">
                    <?foreach ($ActionData as $value):?>
                        <ul>
                          <a href="<? echo $value['d_link']?>" target="_BLANK">
                            <li class="comm_phtBox"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></li>
                            <li class="comm_TxBx"><? echo $value['d_title']?></li>
                          </a>
                        </ul>
                    <? endforeach;?>
                </div>
            </div>
        <?endif;?>
        <div class="build-your-own">
            <div class="addMBRBx">
                <ul class="addMBTX"><a href="<? echo site_url('login/join') ?>">[ 立即加入會員 ]</a></ul>
            </div>
        </div>

        <div class="CnTBox">
            <? if(!empty($NewProductsData)):?>
                <div class="CnTT">最新產品 初登場</div>
                <div class="NewPord">
                    <?foreach ($NewProductsData as $value):?>
                        <ul>
                            <li class="NewTopTT">初登場新品上市</li>
                            <li class="NewPordPHt"><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img1']?>" alt=""></a>
                              <?if($value['Discount']==1):?>
                                <div class="p_discount"><em><?echo round($this->autoful->DiscountData[$value['d_id']]['type_price']/10,1);?><br>折</em></div>
                              <?elseif($value['Discount']==2): ?>
                                <div class="p_discount"><em>特<br>價</em></div>
                              <?endif;?>
                            </li>
                            <li class="NewPordTxs"><span class="NewPordTxsB"><? echo $value['d_title']?></span></li>
                            <li class="TicPystxc" style="text-decoration:none;">市價：NT$.<? echo number_format($value['d_price1'])?></li>
                            <?if ($value['Discount']!=0): ?>
                              <li class="TicPystxc02">特價：NT$.<? echo number_format($value['d_price'])?></li>
                            <?elseif ($value['d_dprice']!=0): ?>
                              <li class="TicPystxc02">出清價：NT$.<? echo number_format($value['d_dprice'])?></li>
                            <?elseif ($value['d_sprice']!=0): ?>
                              <li class="TicPystxc02">促銷價：NT$.<? echo number_format($value['d_sprice'])?></li>
                            <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $value['Chked']=='Y'): ?>
                              <li class="TicPystxc02"><?echo $value['Lvtitle']; ?>：NT$.<? echo number_format($value['d_price'])?></li>
                            <?endif; ?>
                            <ul class="bantBOX">
                                <li class="butt01"><input type="button" class="bant01" value="立即購買" onclick="javascript:window.location.href='<? echo site_url('products/info/'.$value['d_id'].'') ?>'"></li>
                                <!-- <li class="butt02"><input type="button" class="bant02" value="加入最愛" id="AddFavourite" rel="<?echo $value['d_id']?>"></li> -->
                            </ul>
                        </ul>
                    <? endforeach;?>
                </div>
            <?endif;?>
            <?php $Hotcount=0; ?>
            <? if(!empty($HotData)):foreach ($HotData as $key=> $pvalue):?>
                <div class="CnTBox03" style="margin-top:50px;">
                  <?php if ($Hotcount==0): ?>
                    <div class="CnTT">人氣商品特區</div>
                  <?php endif; ?>
                    <div class="Pord_cfBox"><?echo $key;?></div>
                    <div class="Pord_BoxStr">
                        <?foreach ($pvalue as $value):?>
                            <ul>
                                <li class="PordPHt"><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img1']?>" alt=""></a>
                                  <?if($value['Discount']==1):?>
                                    <div class="p_discount"><em><?echo round($this->autoful->DiscountData[$value['d_id']]['type_price']/10,1);?><br>折</em></div>
                                  <?elseif($value['Discount']==2): ?>
                                    <div class="p_discount"><em>特<br>價</em></div>
                                  <?endif;?>
                                </li>
                                <li class="PordTxs"><span class="PordTxsB"><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><? echo $value['d_title']?></a></span></li>
                                <li class="TicPystxc" style="text-decoration:none;">市價：NT$.<? echo number_format($value['d_price1'])?></li>
                                <?if ($value['Discount']!=0): ?>
                                  <li class="TicPystxc02">特價：NT$.<? echo number_format($value['d_price'])?></li>
                                <?elseif ($value['d_dprice']!=0): ?>
                                  <li class="TicPystxc02">出清價：NT$.<? echo number_format($value['d_dprice'])?></li>
                                <?elseif ($value['d_sprice']!=0): ?>
                                  <li class="TicPystxc02">促銷價：NT$.<? echo number_format($value['d_sprice'])?></li>
                                <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin']) ): ?>
                                  <li class="TicPystxc02"><?echo $value['Lvtitle']; ?>：NT$.<? echo number_format($value['d_price'])?></li>
                                <?endif; ?>
                                <ul class="bantBOX">
                                    <li class="butt01"><input type="button" class="bant01" value="立即購買" onclick="javascript:window.location.href='<? echo site_url('products/info/'.$value['d_id'].'') ?>'"></li>
                                    <!-- <li class="butt02"><input type="button" class="bant02" value="加入最愛" id="AddFavourite" rel="<?echo $value['d_id']?>"></li> -->
                                </ul>
                            </ul>
                        <?endforeach;?>
                        <ul>
                            <li class="PordPHt"><a href="<? echo site_url('products/hot/'.$value['PTID'].'') ?>"><img src="<? echo CCODE::DemoPrefix.('/images/front/topPord.jpg')?>" alt=""></a></li>
                        </ul>
                    </div>
                </div>
                <?php $Hotcount++; ?>
            <?endforeach;endif;?>
            <div class="NewsALLBox">
                <div class="NewsBox">
                    <div class="NewsTpALBx">
                        <div class="CnTT"><a href="<? echo site_url('news') ?>">[ 最新消息 & 活動公告 ]</a></div>
                    </div>
                    <div class="NewsConBox">
                        <? if(!empty($NewsData)):foreach ($NewsData as $value):?>
                            <ul class="Ntur"><b><? echo $value['d_date']?></b>
                                <span class="NewsTA NEWPDS" style="background-color:<? echo $value['d_color']?>"><? echo $value['nttitle']?></span>
                            <a href="<? echo site_url('news/info/'.$value['d_id'].'') ?>"><? echo $value['d_title']?></a></ul>
                        <?endforeach;endif;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="build-your-own02">
                <div class="ContUs">若有相關購物問題請您，撥打客服專線，或至 <a href="<?echo site_url('contact')?>">聯絡我們</a> 留下您的相關問題我們將盡快為您服務!~^^謝謝</div>
        </div>
    </article>
</main>
<?php include('_Cartjs.php');?>

<script>
$('.int_bannerCS').slick({
  dots: true,
      arrows: true,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      centerMode: true,
      variableWidth: true,
      autoplay: true,
      autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        variableWidth: false,
        centerPadding: '0px',
        slidesToShow: 1
      }
    }
  ]
});
</script>
<script>
$('.NewPord').slick({
  arrows: true,
  dots: false,
  infinite: false,
  autoplay: true,
  autoplaySpeed: 1500,
  slidesToShow: 6,
  slidesToScroll: 6,
  responsive: [
    {
      breakpoint: 1260,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 5,
      }
    },
    {
      breakpoint: 1050,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    },
    {
      breakpoint: 870,
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
      breakpoint: 350,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }

    }
  ]
});
</script>
<script>
$('.Pord_BoxStr').slick({
  arrows: true,
  dots: false,
  infinite:  true,
  autoplay: true,
  autoplaySpeed: 1500,
  slidesToShow: 6,
  slidesToScroll: 6,
  responsive: [
    {
      breakpoint: 1260,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 5,
      }
    },
    {
      breakpoint: 1050,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    },
    {
      breakpoint: 870,
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
      breakpoint: 350,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }

    }
  ]
});
</script>
<?php include '_footer.php';?>
