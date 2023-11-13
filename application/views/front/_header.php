<?php
header("Cache-control: private");
$WebConfigData=$this->webmodel->GetWebData();
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title><? echo (!empty($this->NetTitle)?$this->NetTitle.' | ':'').$WebConfigData[2];?></title>
    <? if(!empty($WebConfigData[7])):?>
      <meta name="author" content="<? echo $WebConfigData[7];?>">
    <? endif;?>
    <? if(!empty($this->Seokeywords)):?>
      <meta name="keywords" content="<? echo $this->Seokeywords;?>">
    <? elseif(!empty($WebConfigData[8])):?>
      <meta name="keywords" content="<? echo $WebConfigData[8];?>">
    <? endif;?>
    <? if(!empty($this->Seodescription)):?>
      <meta name="description" content="<? echo $this->Seodescription;?>">
    <? elseif(!empty($WebConfigData[9])):?>
      <meta name="description" content="<? echo $WebConfigData[9];?>">
    <? endif;?>
    <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/master.css')?>"/>
    <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/layout.css')?>"/>
    <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/slick.css')?>"/>
    <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/slick-theme.css')?>"/>
    <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/font-awesome.min.css')?>"/>
    <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/front/jquery-ui.min.css')?>">
    <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery-1.8.3.min.js')?>"></script>
    <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/slick.js')?>"></script>
    <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery-ui.min.js')?>"></script>
    <link rel="shortcut icon" href="<? echo CCODE::DemoPrefix.('/images/front/favicon/favicon.ico')?>" type="image/x-icon">
    <link rel="icon" href="<? echo CCODE::DemoPrefix.('/images/front/favicon/favicon.ico')?>" type="image/x-icon">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<? echo CCODE::DemoPrefix.('/images/front/favicon/ms-icon-144x144.png')?>">
    <meta name="theme-color" content="#ffffff">
    <!--ZOOM -->
    <link rel="stylesheet" href="<? echo CCODE::DemoPrefix.('/js/front/fancybox/jquery.fancybox.css')?>" />
    <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/fancybox/jquery.fancybox.js')?>"></script>
    <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.rwdImageMaps.min.js')?>"></script>
    <script type="text/javascript">
		$(document).ready(function(e) {
			$('.fancybox').fancybox();
      $('img[usemap]').rwdImageMaps();
		});
    </script>
    <!--//ZOOM -->
    <!--//mobile menu -->
    <link rel="stylesheet" href="<? echo CCODE::DemoPrefix.('/css/front/nav-core.css')?>">
    <link rel="stylesheet" href="<? echo CCODE::DemoPrefix.('/css/front/nav-layout.css')?>">
    <!--//mobile menu -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GEG4PVDP1N"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-GEG4PVDP1N');
    </script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-50600153-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-50600153-1');
    </script>
    <!-- Google tag (gtag.js) new 11/12 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WNFWQ95M0X">
    </script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-WNFWQ95M0X');
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-50600153-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-50600153-2');
    </script> -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TVM3JRC');</script>
    <!-- End Google Tag Manager -->
  </head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TVM3JRC"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
<a href="#"><div class="gotoTOP">TOP</div></a>
<header>
    <div class="HeadConALLBOX">
        <div class="HeadLogoBx">
            <div class="LogoALBox">
                <a href="<?echo site_url('')?>"><div class="LogoBox"><img src="https://bgtwmedia.s3.ap-northeast-1.amazonaws.com/images/front/CKL_LOGO.svg" alt=""></div></a>
                <div class="LogoTXBX">
                    <div class="LogoTXB"><!-- Professional Beauty Supply From Japan --></div>
                    <? if(!empty($WebConfigData[13])):?>
                        <div class="LogoSTTx"><?echo $WebConfigData[13];?></div>
                    <?endif;?>
                    <? if(!empty($WebConfigData[14])):?>
                        <div class="LogoSTTx"><?echo $WebConfigData[14];?></div>
                    <?endif;?>
                </div>
            </div>
        </div>
    </div>
    <div class="TopLinkb2">
        <div class="TLtxLf">
            <!-- <ul class="TLTxst"><a href="<?echo site_url('order')?>">非會員訂單查詢</a></ul> -->
            <!-- <ul class="TLTxst">會員人數：<?echo $this->autoful->MTotal;?> | 商品總數：<?echo $this->autoful->PTotal;?></ul> -->
        </div>
        <div class="TLtxRf">
            <? if(!empty($WebConfigData[10])):?>
                <ul class="TLTxst02"><img src="<? echo CCODE::DemoPrefix.('/images/front/TpTLEic01.svg')?>">客服專線：<?echo $WebConfigData[10];?></ul>
            <? endif;?>
            <ul class="TLTxst03"><img src="<? echo CCODE::DemoPrefix.('/images/front/TpTLEic02.svg')?>" alt=""><a href="<?echo site_url('contact')?>">相關協助與聯繫我們</a></ul>
        </div>
    </div>
    <div class="TopLinkb3">
      <?php if (!empty($_SESSION[CCODE::MEMBER]['IsLogin']) && $_SESSION[CCODE::MEMBER]['IsLogin'] == 'Y'): ?>
        <ul class="IntMemberStatus"><font color="#E7004F"><? echo $_SESSION[CCODE::MEMBER]['LName'] ?> 您好!您的目前等級：<? echo $_SESSION[CCODE::MEMBER]['Mlv_title'] ?></font></ul>
        <ul class="IntMemberStatus">　|　</ul>
        <ul class="IntMemberStatus"><a href="<? echo site_url('login/logout') ?>">會員登出</a></ul>
      <?php else: ?>
        <ul class="IntMemberStatus"><a href="<? echo site_url('login/join') ?>">加入會員</a></ul>
        <ul class="IntMemberStatus">　|　</ul>
        <ul class="IntMemberStatus"><a href="<? echo site_url('login') ?>">會員登入</a></ul>
      <?php endif; ?>
    </div>
    <div class="searchAllBox">
        <div class="Sech_ASBox">
          <form method="post" action="<? echo site_url('products/search');?>">
            <select class="select_all_box_r03 form-control02" name="Ptype">
                <option style="height: 40px" value="0">選擇分類</option>
                <?if(!empty($this->autoful->SideMenu)):foreach ($this->autoful->SideMenu as $key => $value):$val=explode('_',$key);?>
		          <option style="height: 40px" value="<?echo $val[1];?>"><?echo $val[0];?></option>
                <?endforeach;endif;?>
		    </select>
            <input type="text" class="select_inpt" name="Pkeyword"  value="<? echo (!empty($Pkeyword)?$Pkeyword:'')?>">
            <input type="submit" class="searICBXsit" value=" ">
          </form>
        </div>
        <div class="HtIcALLB">
            <div class="HtIcBx01">
                <ul class="ICBox"><img src="<? echo CCODE::DemoPrefix.('/images/front/htmen01.svg')?>" alt=""></ul>
                <ul class="ICBoTX">
                    會員服務
                    <ul>
                        <div class="HdNAVUBTopTT02">會員服務專區</div>
                        <div class="mendercont">
                            <div class="ImeALBox">
                                <ul class="IntmenderCr"><a href="<? echo site_url('member') ?>">前往會員中心</a></ul>
                                <ul class="ImePs"><a href="<? echo site_url('member/orders') ?>">購物紀錄與訂單查詢</a></ul>
                                <ul class="ImeLovepord"><a href="<? echo site_url('member/favorite') ?>">我的收藏</a></ul>
                                <ul class="ImeFriend"><a href="<? echo site_url('member/friend') ?>">邀請好友加入會員</a></ul>
                            </div>
                            <div class="ImeALBox">
                                <ul class="ImcoutBx"><a href="<? echo site_url('member/account') ?>">會員資料修改</a></ul>
                                <ul class="ImcoutBx"><a href="<? echo site_url('member/point') ?>">會員點數查詢</a></ul>
                                <ul class="ImcoutBx"><a href="<? echo site_url('member/account') ?>">訂閱/取消 電子報</a></ul>
                                <ul class="ImcoutBx"><a href="<? echo site_url('qa') ?>">常見問題</a></ul>
                            </div>
                            <div class="ImeALBox">
                                <ul class="ImeTel">服務專線：<?echo $WebConfigData[10].' '.$WebConfigData[11];?></ul>
                                <? if(!empty($WebConfigData[12])):?>
                                    <ul class="ImeEml">聯絡我們：<?echo $WebConfigData[12];?></ul>
                                <? endif;?>
                            </div>
                        </div>
                    </ul>
                </ul>
            </div>
            <div class="HtIcBx01">
                <?if($this->autoful->CartNum!=0):?>
                    <div class="ICrTQ"><?echo $this->autoful->CartNum;?></div>
                <?endif;?>
                <ul class="ICBox"><a href="<? echo site_url('cart') ?>"><img src="<? echo CCODE::DemoPrefix.('/images/front/htCAR01.svg')?>" alt=""></a></ul>

                    <ul class="ICBoTX">
                        購物車
                        <?if(!empty($this->autoful->ProductCart)):?>
                        <ul>
                            <div class="HdNAVUBTopTT03">購物車狀態</div>
                            <div class="IndSPCont">
                              <div style="overflow-y:scroll;height:200px;width:100%;">
                                <? foreach ($this->autoful->ProductCart as $key => $value):?>
                                    <div class="IndSPContUr" >
                                        <ul class="IndSprPHT"><img src="<? echo CCODE::AWSS3.'/'.$value['d_img1']?>" alt=""></ul>
                                        <ul class="IndSprTxBx">
                                            <li class="CrProdName"><? echo $value['d_title']?></li>
                                        </ul>
                                        <ul class="IndSprBaX">
                                            <li class="CrProdName">x<? echo $value['d_num']?></li>
                                        </ul>
                                        <ul class="IndSprBaX">
                                            <li class="TicPystxc04">單價：NT$.<?echo number_format($value['d_price']);?></li>
                                            <!-- <li class="TicPystxc04">售價：NT$.820</li> -->
                                        </ul>
                                    </div>
                                <?endforeach;?>
                              </div>

                                <div class="IndSPContUr02">
                                    <div class="MneyBox">
                                        <div class="tpSCtxb">會員購物滿額 <?echo number_format($this->autoful->OneFreight['d_free']);?>元免運費</div>
                                        <div class="tpSCtxb">商品紅利小計 <font color="#FF9EDB"><?echo number_format($this->autoful->CartBonus);?>點</font></div>
                                        <div class="tpSCtxb02">金額小計</div>
                                        <div class="MenyToT"><?echo number_format($this->autoful->CartTotal);?> 元整</div>
                                    </div>
                                </div>
                                <div class="IndSPContUr04">
                                  <?//if(!empty($this->autoful->Mid)):?>
                                      <!-- 再消費<font color="#FF9EDB">NT$<?//echo number_format($this->autoful->last_money);?></font>，即升級為<?// echo $this->autoful->Next_lv['d_title'] ?> -->
                                  <?//endif;?>
                                </div>
                                <div class="IndSPContUr03">
                                    <input type="button" class="CarSbant01" value="前往購物車結帳" onClick="location='<? echo site_url('cart') ?>'">
                                </div>
                            </div>
                        </ul>
                        <?endif;?>
                    </ul>

            </div>
            <!--mobile menu -->
            <a href="#" class="nav-button">Menu</a>


            <nav class="nav">
              <ul>
                <?if(!empty($this->autoful->SideMenu)):foreach ($this->autoful->SideMenu as $key => $value):$val=explode('_',$key);?>
                    <li class="nav-submenu"><a href="<?echo site_url('products/index/'.$val[1].'')?>"><?echo $val[0];?></a>
                        <?if(!empty($value)):?>
                        <ul>
                            <?foreach ($value as $tvalue):?>
                                <li class="nav-submenu">
                                    <a href="<?echo site_url('products/products_list/'.$tvalue['d_id'].'')?>"><?echo $tvalue['d_title']?></a>
                                        <?if(!empty($tvalue['Subdata'])):?>
                                            <ul>
                                                <?foreach ($tvalue['Subdata'] as $ttvalue):?>
                                                <li>
                                                    <a href="<?echo site_url('products/products_list/'.$ttvalue['d_id'].'')?>"><?echo $ttvalue['d_title']?></a>
                                                </li>
                                                <?endforeach;?>
                                            </ul>
                                        <?endif;?>
                                </li>
                            <?endforeach;?>
                        </ul>
                        <?endif;?>
                    </li>
                <?endforeach;endif;?>
              </ul>
             </nav>

            <a href="#" class="nav-close">Close Menu</a>


            <script src="<? echo CCODE::DemoPrefix.('/js/front/nav.jquery.min.js')?>"></script>
            <script>
              $('.nav').nav();
            </script>

            <!--//mobile menu -->
        </div>
    </div>
    <div class="HeadNavBx">
        <?php $k=0; ?>
        <?if(!empty($this->autoful->SideMenu)):foreach ($this->autoful->SideMenu as $key => $value):$val=explode('_',$key);?>
        <?php ($k>4)?$k=5:$k++; ?>
            <ul class="HeadNavUB MuT2_T<?php echo $k ?>">
                <?echo $val[0];?>
                <ul>
                    <div class="HdNAVUBTopTT"><a href="<?echo site_url('products/index/'.$val[1].'')?>"><?echo $val[0];?> TOP</a></div>
                    <?if(!empty($value)):?>
                        <div class="HdNAVUBcont">
                            <?foreach ($value as $tvalue):?>
                                <ul><a href="<?echo site_url('products/products_list/'.$tvalue['d_id'].'')?>"><?echo $tvalue['d_title']?></a></ul>
                            <?endforeach;?>
                        </div>
                    <?endif;?>
                </ul>
            </ul>
        <?endforeach;endif;?>
    </div>
</header>
<script src="<? echo CCODE::DemoPrefix.('/js/front/_header_box.js')?>"></script>
