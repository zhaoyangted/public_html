<?php include '_header.php';?>
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
          <section class="left_box">
            <h2><?echo (!empty($TID)?$this->MenuData['d_title']:'類別');?></h2>
            <?if(!empty($TID)):?>
            <div class="pd-catalog"><?php include('_pd_catalog.php');?></div>
            <?else:?>
            <div class="pd-catalog">
                <nav class="pd-catalog" role="navigation">
                    <ul class="nav__list">
                      <?foreach ($this->autoful->SideMenu as $key => $value):$val=explode('_',$key);?>
                        <li>
                          <input id="group-<?echo $val[1]?>" type="checkbox" hidden />
                          <label for="group-<?echo $val[1]?>"><span class="fas fa-angle-right"></span><?echo $val[0]?></label>
                          <ul class="group-list">
                            <?foreach ($value as $tvalue):if(!empty($tvalue['Subdata'])):?>
                                <li>
                                    <input id="sub-group-<?echo $tvalue['d_id']?>" type="checkbox" hidden />
                                    <label for="sub-group-<?echo $tvalue['d_id']?>"><span class="fas fa-angle-right"></span><?echo $tvalue['d_title']?></label>
                                    <ul class="sub-group-list">
                                      <?foreach ($tvalue['Subdata'] as $ttvalue):?>
                                        <li><a href="<?echo site_url('products/products_list/'.$ttvalue['d_id'].'')?>"><?echo $ttvalue['d_title']?></a></li>
                                      <?endforeach;?>
                                    </ul>
                                </li>
                                <?else:?>
                                    <li><a href="<?echo site_url('products/products_list/'.$tvalue['d_id'].'')?>"><?echo $tvalue['d_title']?></a></li>
                            <?endif;endforeach;?>
                          </ul>
                        </li>
                      <?endforeach;?>
                    </ul>
                </nav>
            <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/pd-catalog.css')?>"/>
            </div>
            <?endif;?>
          </section>
          <section class="right_box">
            <div class="title05 center"><?php echo $Htitle ?></div>
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
            <?else:echo '此人氣商品尚無資料';endif;?>
            <?echo (!empty($dbdata['dbdata'])?$dbdata['PageList']:'');?>
          </section>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<script>
  $("#search_form").append('<input type="hidden" name="Pkeyword" value="<?echo $searchtext;?>">');
  $('#Orderby').change(function(){
    $('#Orderform').submit();
  });
</script>
