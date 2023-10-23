<?php include '_header.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/quantity.js')?>"></script>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url('')?>">首頁</a></li>
        </ul>
      </div>
      <!--//bread-->
      <div class="box-1">
        <section class="content_box">
          <div class="products_more">
            <div class="toppic"><img src="<? echo CCODE::AWSS3.'/'.$Pdata['d_img1']?>" alt=""></div>
            <ul>
              <? foreach ($dbdata as $key => $value):
                  $Qty=0;$Dis='';
                  if($value['d_stock']<=0){
                    $Qty=0;
                    $Dis='disabled';
                  }
              ?>
                <li>
                  <div class="name"><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><? echo $value['d_title']?></a></div>
                  <div class="pic"><a href="<? echo site_url('products/info/'.$value['d_id'].'') ?>"><img src="<? echo CCODE::AWSS3.'/'.$value['d_img1']?>" alt=""></a></div>
                  <div class="info_list">
                    <div class="dtt">庫存數量</div>
                    <div class="spec"><? echo ($value['d_stock']>0)?$value['d_stock']:'補貨中';?></div>
                  </div>
                  <?if ($value['Discount']!=0): ?>
                    <div class="info_list">
                      <div class="dtt">特價</div>
                      <div class="spec">NT$.<? echo number_format($value['d_price'])?></div>
                    </div>
                  <?elseif ($value['d_dprice']!=0): ?>
                    <div class="info_list">
                      <div class="dtt">出清價</div>
                      <div class="spec">NT$.<? echo number_format($value['d_dprice'])?></div>
                    </div>
                  <?elseif ($value['d_sprice']!=0): ?>
                    <div class="info_list">
                      <div class="dtt">促銷價</div>
                      <div class="spec">NT$.<? echo number_format($value['d_sprice'])?></div>
                    </div>
                  <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin'])): ?>
                    <div class="info_list">
                      <div class="dtt"><?echo $value['Lvtitle']; ?></div>
                      <div class="spec">NT$.<? echo number_format($value['d_price'])?></div>
                    </div>
                  <?else:?>
                    <div class="info_list">
                      <div class="dtt">市價</div>
                      <div class="spec">NT$.<? echo number_format($value['d_price1'])?></div>
                    </div>
                  <?endif; ?>
                  <div class="info_list">
                    <div class="dtt">數量</div>
                    <div class="spec">
                      <div class="quantity buttons_added">
                        <input type="button" value="-" class="minus" <?=$Dis?>>
                        <input type="number" step="1" min="<?=$Qty?>" max="<?echo $value['d_stock'];?>" name="d_num_<?echo $value['d_id']?>" value="<?=$Qty?>" title="Qty" class="input-text qty text" size="4" <?=$Dis?>>
                        <input type="button" value="+" class="plus" <?=$Dis?>>
                      </div>
                    </div>
                  </div>
                </li>
                <input type="hidden" name="d_id" value="<?echo $value['d_id']?>">
              <?endforeach;?>
            </ul>
            <div id="sticker" class="buyicon">
              <a href="javascript:void(0)" class="btn-style09" id="AddCart">加入購物車</a>
            </div>
          </div>
        </section>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<script>
// 加入購物車
// $('a[id="AddCart"]').click(function(){
//   var idarray=new Array();
//   var numarray=new Array();
//   $("input[name='d_id']").each(function(){
//     Id=$(this).val();
//     idarray.push(Id);
//     num=$("input[name='d_num_"+Id+"']").val();
//     numarray.push(num);

//   });
//   // console.log(idarray);
//     // console.log(numarray);
//   $.ajax({
//     type: "POST",
//     url: "<? echo site_url('products/Addcartmore')?>",
//     data: {
//       did:idarray,
//       num:numarray
//     },
//     dataType: "text",
//     success: function(data) {
//       console.log(data);
//       // if(data!='ok'){
//       //   alert(data);
//       //   return '';
//       // }
//     }
//   });
//   // alert('已加入購物車');
//   // location.reload();
// });
$('a[id="AddCart"]').click(function(){
  Str='';
  $("input[name='d_id']").each(function(){
    Id=$(this).val();
    num=$("input[name='d_num_"+Id+"']").val();

    if(num<0){
      alert('數量不得為負數');
      return '';
    }
    if(num>0){
      $.ajax({
        type: "POST",
        url: "<? echo site_url('products/Addcart')?>",
        data: {
          did:Id,
          num:num,
        },
        dataType: "text",
        async: false ,
        success: function(data) {
          Str=data;
        }
      });
    }
  });
  if(Str=='ok'){
    alert('已加入購物車');
    location.reload();
  }else{
    alert(data);
  }
});
function Addcart(Id,num){

}
</script>
