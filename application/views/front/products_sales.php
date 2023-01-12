<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url('')?>">首頁</a></li>
          <!-- <li><a href="products.php">美髮理容產品</a></li> -->
          <!-- <li class="active">染髮劑（商務用）</li> -->
        </ul>
      </div>
      <!--//bread-->
      <div class="box-1">
          <section class="left_box">
            <!-- <h2>美髮理容產品</h2> -->
            <div class="pd-catalog"><?php //include('_pd_catalog.php')?></div>
          </section>
          <section class="right_box">
            <div class="title05 center"><?echo $Adata['d_title']?></div>
            <div class="sortbox">
              <form method="post" id="Orderform">
                <div class="sort">
                  <select id="Orderby" name="Orderby" class="select_sort">
                    <?foreach ($OrderArray as $key => $value):?>
                      <option value="<?echo $key?>" <?echo ($key==$Orderid)?'selected':'';?>><?echo $value?></option>
                    <?endforeach;?>
                  </select>
                </div>
              </form>
            </div>
            <?if(!empty($dbdata['dbdata'])):?>
            <div class="pd">
                <? foreach ($dbdata['dbdata'] as $key => $value):?>
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
                        <?elseif ($this->autoful->Mlv!=1 && !empty($_SESSION[CCODE::MEMBER]['IsLogin'])): ?>
                          <li class="TicPystxc02"><?echo $value['Lvtitle']; ?>：NT$.<? echo number_format($value['d_price'])?></li>
                        <?endif; ?>
                        <ul class="bantBOX">
                            <li class="butt01"><input type="button" class="bant01" value="立即購買" onclick="javascript:window.location.href='<? echo site_url('products/info/'.$value['d_id'].'') ?>'"></li>
                            <li class="butt02"><input type="button" class="bant02" value="加入最愛" id="AddFavourite" rel="<?echo $value['d_id']?>"></li>
                        </ul>
                    </ul>
                <?endforeach;?>
            </div>
            <?else:echo '此分類尚無資料';endif;?>
            <?echo (!empty($dbdata['dbdata'])?$dbdata['PageList']:'');?>
          </section>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<script>
  $("#search_form").append('<input type="hidden" name="Orderby" value="<?echo (!empty($Orderid)?$Orderid:'');?>">');
  $('#Orderby').change(function(){
    $('#Orderform').submit();
  });
</script>
