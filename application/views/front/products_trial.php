<?php include '_header.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/quantity.js')?>"></script>
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
              <?for ($i=1; $i <=5 ; $i++):if(!empty($Tdata['d_img'.$i])):?>
                <li><a data-fancybox="images" href="<? echo CCODE::DemoPrefix.('/'.$Tdata['d_img'.$i].'')?>"><img src="<? echo CCODE::AWSS3.('/'.$Tdata['d_img'.$i].'')?>" alt="" /></a></li>
              <?endif;endfor;?>
            </ul>
            <ul class="product-nav">
              <?for ($i=1; $i <=5 ; $i++):if(!empty($Tdata['d_img'.$i])):?>
                <li><img src="<? echo CCODE::AWSS3.('/'.$Tdata['d_img'.$i].'')?>" alt="" /></li>
              <?endif;endfor;?>
            </ul>
          </div>
          <div class="right_info">
            <div class="name"><?echo $Tdata['d_title'];?></div>
            <?if(!empty($dbdata['pbtitle'])):?>
              <div class="info_list">
                <div class="dtt">商品品牌</div>
                <div class="spec"><?echo $dbdata['pbtitle'];?></div>
              </div>
            <?endif;?>
            <div class="info_list">
              <div class="dtt">商品編號</div>
              <div class="spec"><?echo $Tdata['d_model'];?></div>
            </div>
            <div class="info_list">
              <div class="dtt">庫存數量</div>
              <div class="spec"><?echo $Tdata['d_stock'];?></div>
            </div>
            <div class="info_line"></div>
            <div class="info_list">
              <div class="dtt">該試用品的產品:</div>
              <div class="spec">
                  <?if(!empty($dbdata)):foreach ($dbdata as $key => $value):?>
                    <div>
                      <a class="name" href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><?echo $value['d_title'];?></a>
                    </div>
                  <?endforeach;endif;?>
              </div>
						</div>
						<div class="info_list">
              <div class="dtt">索取規則</div>
              <div class="spec"><?echo $TryRule[$Tdata['d_try']];?></div>
            </div>
            <div class="info_line"></div>
            <div class="buy_sbox">
              <?if($Tdata['d_stock']>0):?>
                <a href="javascript:void(0)" class="btn-style03" id="AddTrialCart" rel="<?echo $Tdata['d_id'];?>">加入購物車</a>
              <?else:?>
                <a href="javascript:void(0)" class="btn-style03">補貨中</a>
              <?endif;?>
            </div>
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
                <?echo (!empty($Tdata['d_content'])?stripslashes($Tdata['d_content']):'');?>
                <!--//文字編輯器內容-->
            </div>
            <div id="tab2" class="tab_content">
              <div class="pd_qa">
                <!--文字編輯器內容-->
                <?echo (!empty($Tdata['d_qacontent'])?stripslashes($Tdata['d_qacontent']):'');?>
                <!--//文字編輯器內容-->
              </div>
            </div>
            <div id="tab3" class="tab_content">
              <!--文字編輯器內容-->
              <?echo (!empty($Tdata['d_bcontent'])?stripslashes($Tdata['d_bcontent']):'');?>
              <!--//文字編輯器內容-->
            </div><!--tab_container end-->
        </section><!--abgne_tab end-->
        <!--產品資訊-->
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<?php include('_Cartjs.php');?>

<!--產品照片 -->
<script>

  // 加入購物車
  $('a[id="AddTrialCart"]').click(function(){
    Id=$(this).attr('rel');
    num=1;
    if(num<=0){
      alert('數量不得為零或負數');
      return '';
    }
    $.ajax({
      type: "POST",
      url: "<? echo site_url('products/AddTryCart')?>",
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
