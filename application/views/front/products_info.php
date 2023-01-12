<?php include '_header.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/quantity.js')?>"></script>

<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url('')?>">首頁</a></li>
          <?echo $Menutitle;?>
        </ul>
      </div>
      <!--//bread-->
      <div class="box-1">
        <!--產品上半部資訊-->
        <section class="proview_topbox">
          <div class="left_img">
            <ul class="product-slick">
              <?for ($i=1; $i <=5 ; $i++):if(!empty($dbdata['d_img'.$i])):?>
                <li><a data-fancybox="images" href="<? echo CCODE::DemoPrefix.('/'.$dbdata['d_img'.$i].'')?>"><img src="<? echo CCODE::DemoPrefix.('/'.$dbdata['d_img'.$i].'')?>" alt="" /></a></li>
              <?endif;endfor;?>
            </ul>
            <ul class="product-nav">
              <?for ($i=1; $i <=5 ; $i++):if(!empty($dbdata['d_img'.$i])):?>
                <li><img src="<? echo CCODE::DemoPrefix.('/'.$dbdata['d_img'.$i].'')?>" alt="" /></li>
              <?endif;endfor;?>
            </ul>
          </div>
          <div class="right_info">
            <div class="name"><?echo $dbdata['d_title'];?></div>
            <?if(!empty($this->autoful->DiscountData[$dbdata['d_id']])):?>
              <div class="sale">
                <a href="<?echo site_url('products/sales/'.$this->autoful->DiscountData[$dbdata['d_id']]['d_id'].'');?>"><i class="fas fa-exclamation-triangle"></i><?echo $this->autoful->DiscountData[$dbdata['d_id']]['d_title'];?></a>
              </div>
            <?endif;?>
            <?if(!empty($dbdata['pbtitle'])):?>
              <div class="info_list">
                <div class="dtt">商品品牌</div>
                <div class="spec"><a href="<? echo site_url('products/blist/'.$dbdata['pbid'].'') ?>"><?echo $dbdata['pbtitle'];?></a></div>
              </div>
            <?endif;?>
            <div class="info_list">
              <div class="dtt">商品編號</div>
              <div class="spec"><?echo $dbdata['d_model'];?></div>
            </div>
            <div class="info_list">
              <div class="dtt">庫存數量</div>
              <div class="spec"><?echo $dbdata['d_stock'];?></div>
            </div>
            <div class="info_list">
              <div class="dtt">運費</div>
              <?if($dbdata['fid']==1):?>
                <div class="spec">訂單滿NT$.<?echo number_format($dbdata['d_free']);?>免運費</div>
              <?else:?>
                <div class="spec"><?echo $dbdata['ftitle'];?></div>
              <?endif;?>
            </div>
            <div class="info_line"></div>
            <?if($dbdata['Chked']=='Y'):?>
              <div class="info_list">
                <div class="dtt"><?echo $this->autoful->Lvtitle;?></div>
                <div class="spec">NT$.<?echo number_format($dbdata['d_price']);?></div>
              </div>
              <?if(!empty($this->autoful->UpLvtitle)):?>
                <div class="info_list">
                  <div class="dtt"><?echo $this->autoful->UpLvtitle;?></div>
                  <div class="spec">資格不符</div>
                </div>
              <?endif;?>
            <?else:?>
              <div class="info_list">
                <div class="dtt">市價</div>
                <div class="spec">NT$.<?echo number_format($dbdata['d_price']);?></div>
              </div>
            <?endif;?>
            <?if($dbdata['d_dprice']!=0):?>
              <div class="info_list">
                <div class="dtt">出清價</div>
                <div class="spec">NT$.<?echo number_format($dbdata['d_dprice']);?></div>
              </div>
            <?elseif($dbdata['d_sprice']!=0):?>
              <div class="info_list">
                <div class="dtt">促銷價</div>
                <div class="spec">NT$.<?echo number_format($dbdata['d_sprice']);?></div>
              </div>
            <?endif;?>
            <?if(!empty($this->autoful->DiscountData[$dbdata['d_id']])):?>
              <div class="info_list">
                <div class="dtt">特價</div>
                <div class="spec">NT$.<?echo number_format($this->autoful->DiscountData[$dbdata['d_id']]['d_price']);?></div>
              </div>
            <?endif;?>
            <?if(((!empty($this->autoful->DiscountData[$dbdata['d_id']]) && $this->autoful->DiscountData[$dbdata['d_id']]['GetBonus']=='Y') || empty($this->autoful->DiscountData[$dbdata['d_id']])) && $dbdata['d_bonus']!=0):?>
              <div class="info_list">
                <div class="dtt"><i class="fas fa-dollar-sign"></i> 獲得紅利</div>
                <div class="spec"><?echo $dbdata['d_bonus'].'%';?></div>
              </div>
            <?endif;?>
            <div class="info_line"></div>
            <?if(!empty($SpecData)):?>
              <div class="info_list">
                <div class="dtt">商品規格</div>
                <div class="spec">
                  <select id="ChangeSpec" class="select_pd">
                    <option selected="selected" value="<?echo $dbdata['d_id']?>"><?echo $dbdata['d_spectitle']?></option>
                    <?foreach ($SpecData as $key => $value):?>
                      <option value="<?echo $value['d_id']?>"><?echo $value['d_spectitle']?></option>
                    <?endforeach;?>
                  </select>
                </div>
              </div>
            <?endif;?>
            <?if(!empty($AddData)):?>
              <div class="info_list">
                <div class="dtt">商品選配</div>
                <div class="spec">
                  <select id="Addid" class="select_pd">
                        <option selected="selected" value="0">請選擇</option>
                        <?foreach ($AddData as $key => $value):?>
                          <option value="<?echo $value['d_id']?>"><?echo $value['d_title'].'+ NT$.'.$value['d_price'];?></option>
                        <?endforeach;?>
                  </select>
                </div>
              </div>
            <?endif;?>
            <div class="info_list">
              <div class="dtt">商品數量</div>
              <div class="spec">
                <div class="quantity buttons_added">
                  <input type="button" value="-" class="minus" <?=$Dis?>>
                  <input type="number" step="1" min="<?=$Qty?>" max="<?echo $dbdata['d_stock'];?>" id="d_num" value="<?=$Qty?>" title="Qty" class="input-text qty text" size="4" <?=$Dis?>>
                  <input type="button" value="+" class="plus" <?=$Dis?>>
                </div>
              </div>
            </div>
            <div class="info_line"></div>
            <div class="buy_sbox">
              <?if($dbdata['d_stock']>0):?>
                <a href="javascript:void(0)" class="btn-style03" id="AddCart" rel="<?echo $dbdata['d_id'];?>">加入購物車</a>
              <?else:?>
                <a href="javascript:void(0)" class="btn-style03">補貨中</a>
              <?endif;?>
              <a href="javascript:void(0)" class="btn-style03" id="AddFavourite" rel="<?echo $dbdata['d_id'];?>"><i class="far fa-heart"></i> 加入收藏</a>
              <a href="<?echo site_url('products/products_more/'.$dbdata['d_id'].'')?>" class="btn-style04">批量購買</a>
              <?if(!empty($TrialData['d_id'])):?>
                <a href="javascript:void(0)" id="AddTrialCart" rel="<?echo $TrialData['d_id'];?>" class="btn-style04"><i class="fas fa-gift"></i> 試用品索取</a>
              <?endif;?>
            </div>
            <div class="community">
              <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?echo site_url('products/info/'.$dbdata['d_id'].'')?>" target="_blank"><img src="<? echo CCODE::DemoPrefix.('/images/front/facebook.svg')?>" alt="" /></a></li>
              <li><a href="http://twitter.com/home/?status=<?echo site_url('products/info/'.$dbdata['d_id'].'')?>" target="_blank"><img src="<? echo CCODE::DemoPrefix.('/images/front/twitter.svg')?>" alt="" /></a></li>
              <li><a href="https://lineit.line.me/share/ui?url=<?echo site_url('products/info/'.$dbdata['d_id']);?>" target="_blank"><img src="<? echo CCODE::DemoPrefix.('/images/front/line.svg')?>" alt="" /></a></li>
            </div>
            <a href="<?echo site_url('contact/index/'.$dbdata['d_id'])?>" class="btn-style05"><i class="fas fa-question-circle"></i> 詢問此產品</a>
          </div>
        </section>
        <!--//產品上半部資訊-->
        <!--產品資訊-->
        <section class="abgne_tab">
          <ul class="tabs">
            <li><a href="#tab1">產品說明</a></li>
            <li><a href="#tab2">問與答</a></li>
            <li><a href="#tab3">購買說明</a></li>
          </ul>
          <div class="tab_container">
            <div id="tab1" class="tab_content">
              <!-- <div class="user_editor line-height"> -->
                <!--文字編輯器內容-->
                <?echo (!empty($dbdata['d_content'])?stripslashes($dbdata['d_content']):'');?>
                <!--//文字編輯器內容-->
            </div>
            <div id="tab2" class="tab_content">
              <div class="pd_qa">
                <!--文字編輯器內容-->
                <?echo (!empty($dbdata['d_qacontent'])?stripslashes($dbdata['d_qacontent']):'');?>
                <!--//文字編輯器內容-->
              </div>
            </div>
            <div id="tab3" class="tab_content">
              <!--文字編輯器內容-->
              <?echo (!empty($dbdata['d_bcontent'])?stripslashes($dbdata['d_bcontent']):'');?>
              <!--//文字編輯器內容-->
            </div><!--tab_container end-->
        </section><!--abgne_tab end-->
        <!--產品資訊-->
        <!--推薦商品-->
        <?if(!empty($PushData)):?>
          <section class="pd_more">
            <div class="title05 center">相關產品推薦</div>
            <div class="shortcut">
              <div class="slider responsive" >
                <!--item_pd-->
                <? foreach ($PushData as $key => $value):?>
                  <ul class="item_pd">
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
                    <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin']) and $value['Chked']=='Y'): ?>
                      <li class="TicPystxc02"><?echo $this->autoful->Lvtitle ?>：NT$.<? echo number_format($value['d_price'])?></li>
                    <?endif; ?>
                    <ul class="bantBOX">
                        <li class="butt01"><input type="button" class="bant01" value="立即購買" onclick="javascript:window.location.href='<? echo site_url('products/info/'.$value['d_id'].'') ?>'"></li>
                        <li class="butt02"><input type="button" class="bant02" value="加入最愛" id="AddFavourite" rel="<?echo $value['d_id']?>"></li>
                    </ul>
                  </ul>
                <?endforeach;?>
                <!--//item_pd-->
              </div>
            </div>
          </section>
        <?endif;?>
        <!--//推薦商品-->
        <!--看過此商品的人也看過下列商品-->
        <?if(!empty($WatchData)):?>
          <section class="pd_more">
            <div class="title05 center">看過此商品的人也看過下列商品</div>
            <div class="shortcut">
              <div class="slider responsive" >
                <!--item_pd-->
                <? foreach ($WatchData as $key => $value):?>
                  <ul class="item_pd">
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
                    <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin']) and $value['Chked']=='Y'): ?>
                      <li class="TicPystxc02"><?echo $this->autoful->Lvtitle ?>：NT$.<? echo number_format($value['d_price'])?></li>
                    <?endif; ?>
                    <ul class="bantBOX">
                        <li class="butt01"><input type="button" class="bant01" value="立即購買" onclick="javascript:window.location.href='<? echo site_url('products/info/'.$value['d_id'].'') ?>'"></li>
                        <li class="butt02"><input type="button" class="bant02" value="加入最愛" id="AddFavourite" rel="<?echo $value['d_id']?>"></li>
                    </ul>
                  </ul>
                <?endforeach;?>
                <!--//item_pd-->
              </div>
            </div>
          </section>
        <?endif;?>

        <!--//看過此商品的人也看過下列商品-->
        <!--最近瀏覽商品-->
        <?if(!empty($TodayWatchData)):?>
          <section class="pd_more">
            <div class="title05 center">最近瀏覽商品</div>
            <div class="shortcut">
              <div class="slider responsive" >
                <!--item_pd-->
                <? foreach ($TodayWatchData as $key => $value):?>
                  <ul class="item_pd">
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
                    <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin']) and $value['Chked']=='Y'): ?>
                      <li class="TicPystxc02"><?echo $this->autoful->Lvtitle ?>：NT$.<? echo number_format($value['d_price'])?></li>
                    <?endif; ?>
                    <ul class="bantBOX">
                        <li class="butt01"><input type="button" class="bant01" value="立即購買" onclick="javascript:window.location.href='<? echo site_url('products/info/'.$value['d_id'].'') ?>'"></li>
                        <li class="butt02"><input type="button" class="bant02" value="加入最愛" id="AddFavourite" rel="<?echo $value['d_id']?>"></li>
                    </ul>
                  </ul>
                <?endforeach;?>
                <!--//item_pd-->
              </div>
            </div>
          </section>
        <?endif;?>

        <!--//最近瀏覽商品-->
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<?php include('_Cartjs.php');?>
<!--TAB效果-->
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/tab.css')?>"/>
<script type="text/javascript">
  $(function(){
    // 預設顯示第一個 Tab
    var _showTab = 0;
    var $defaultLi = $('ul.tabs li').eq(_showTab).addClass('active');
    $($defaultLi.find('a').attr('href')).siblings().hide();

    // 當 li 頁籤被點擊時...
    // 若要改成滑鼠移到 li 頁籤就切換時, 把 click 改成 mouseover
    $('ul.tabs li').click(function() {
      // 找出 li 中的超連結 href(#id)
      var $this = $(this),
        _clickTab = $this.find('a').attr('href');
      // 把目前點擊到的 li 頁籤加上 .active
      // 並把兄弟元素中有 .active 的都移除 class
      $this.addClass('active').siblings('.active').removeClass('active');
      // 淡入相對應的內容並隱藏兄弟元素
      $(_clickTab).stop(false, true).fadeIn().siblings().hide();

      return false;
    }).find('a').focus(function(){
      this.blur();
    });
  });
</script>
<!--//TAB效果-->
<!--產品照片 -->
<script>
  // 加入最愛
  $('a[id="AddFavourite"]').click(function(){
    Id=$(this).attr('rel');
    AID=$('#Addid').val();
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
          // location.reload();
        }
        if(response=='IsHave' || response=='Success'){
          alert('已加入我的最愛');
          // location.reload();
        }
      }
    });
  });
  // 加入購物車
  $('a[id="AddCart"]').click(function(){
    Id=$(this).attr('rel');
    num=$('#d_num').val();
    AID=$('#Addid').val();
    if(num<=0){
      alert('數量不得為零或負數');
      return '';
    }
    // console.log(Id);console.log(num);console.log(AID);
    $.ajax({
      type: "POST",
      url: "<? echo site_url('products/Addcart')?>",
      data: {
        did:Id,
        num:num,
        AID:AID
      },
      dataType: "text",
      success: function(data) {
        if(data=='ok'){
          alert('已加入購物車');
          location.reload();
        }else{
          alert(data);
        }
      },
    });
  });
  // 加入試用品購物車
  $('a[id="AddTrialCart"]').click(function(){
    Id=$(this).attr('rel');
    num=1;
    if(num<=0){
      alert('數量不得為零或負數');
      return '';
    }
    $.ajax({
      type: "POST",
      url: "<? echo site_url('products/AddTrialCart')?>",
      data: {
        did:Id,
        num:num,
      },
      dataType: "text",
      success: function(data) {
        if(data=='OK'){
          alert('已加入購物車');
          location.reload();
        }else{
          alert(data);
        }
      },
    });
  });
  $('#ChangeSpec').change(function(){
    id=$(this).val();
    window.location.href="<?echo CCODE::DemoPrefix.('/products/info/')?>"+id;
  });
	$(document).ready(function() {
		// product slick
		$('.product-slick').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			fade: true,
			dots: false,
			arrows: false,
			asNavFor: '.product-nav',
		});
		// product nav
		$('.product-nav').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			dots: false,
			arrows: false,
			focusOnSelect: true,
			asNavFor: '.product-slick',
		});
	});
</script>
<!--//產品照片 -->
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.accordion.js')?>"></script>
<!--推薦商品-->
<script type="text/javascript">
$('.responsive').slick({
  arrows: true,
  infinite: false,
  autoplay: false,
  speed: 300,
  slidesToShow: 6,
  slidesToScroll: 6,
  responsive: [
    {
      breakpoint: 1370,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 5,
      }
    },
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    },
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
<!---//推薦商品-->
