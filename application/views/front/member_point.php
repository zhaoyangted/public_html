<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="index.php">首頁</a></li>
          <li class="active">會員中心</li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">會員中心</div>
            <?php include '_member_menu.php';?>
            <!--會員紅利-->
            <div class="point_info">
              <ul>
                <h1><?echo $Mdata['d_bonus'];?><em>點</em></h1>
                <h2>目前可用紅利點數</h2>
              </ul>
              <?if(!empty($Edata)):?>
                <ul>
                  <h1><?echo $Edata['d_total'];?><em>點</em></h1>
                  <h2><?echo $Edata['Daedline'];?>到期</h2>
                </ul>
              <?endif;?>
              <li>
                <div class="title03">紅利點數說明</div>
                <div class="user_editor" style="margin-top:-2px;">
                  <div class="w14_2">
                    <?echo stripcslashes($Content['d_title']);?>
                  </div>
                </div>
              </li>
            </div>
            <div class="discount">
              <h1>紅利點數記錄</h1>
              <!-- class="current" 已過期 -->
              <?if(!empty($dbdata['dbdata'])):foreach ($dbdata['dbdata'] as $key => $value):?>
                <ul <?echo ($value['d_enable']=='N')?'class="current"':'';?>>
                  <li>
                    <div class="dbox"><dd>訂單編號</dd>
                      <?if(!empty($value['OID'])):?>
                        <em><a href="<?echo ($value['d_enable']=='N')?'javascript:void(0)':site_url('member/orders/info/'.$value['orderid']);?>"><?echo $value['OID']?></a></em>
                      <?else:echo '---';endif;?>
                    </div>
                    <div class="dbox"><dd>說　　明</dd><?echo $value['d_content']?></div>
                    <!-- <div class="dbox"><dd>紅利來源</dd>網路</div> -->
                  </li>
                  <li>
                    <div class="dbox"><dd>發送日期</dd><?echo ($value['d_type']==1)?$value['d_create_date']:'---';?></div>
                    <div class="dbox"><dd>使用期限</dd><?echo ($value['d_type']==1)?$value['Daedline']:'---';?></div>
                  </li>
                  <li><dd>點數</dd><b><?echo (($value['d_type']==1)?'+':'-').$value['d_num']?></b></li>
                  <!-- <li><dd>狀態</dd>待發效</li> -->
                </ul>
              <?endforeach;endif;?>
            </div>
            <!--//會員紅利-->
            <? echo !empty($dbdata['dbdata'])?$dbdata['PageList']:'';?>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
