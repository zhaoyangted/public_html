<div id="orderprint" class="container">
        <div class="col-lg-">
          <section class="content_box03">
            <?php if($Odata['d_orderstatus']!=10): ?>
              <?php if ($Odata['d_pay']==4): ?>
              <div class="center w14" style="font-size: 18px;background-color: #ff3c6c;color: #fff;font-weight: bolder;">您的轉帳資料已寄送至收件人信箱，準備可上網之電腦，持任一家金融機構發行之晶片金融卡且已申請非約定轉帳服務功能就可繳款。</div>
              <?php endif; ?>
              <?php if ($Odata['d_orderstatus']==11): ?>
                <div class="center w14" style="font-size: 18px;background-color: #ff3c6c;color: #fff;font-weight: bolder;">您的訂單內含有特殊運費之商品，目前已完成運費報價，點選下方同意付款，即可完成該訂單。</div>
              <?php endif; ?>
            <?php else: ?>
              <div class="center w14" style="font-size: 18px;background-color: #ff3c6c;color: #fff;font-weight: bolder;">您訂購的商品中包含特殊運費商品，因此訂單尚未建立完成。<br>待運費報價後，方可繼續進行付款作業。</div>
            <?php endif; ?>
            <div class="center w14" style="font-size: 18px;background-color: #ff3c6c;color: #fff;font-weight: bolder;"><?php echo ($Odata['d_orderstatus']!=10)?'您的訂單資料已確認送出，':''; ?>我們將盡快處理，謝謝！如有任何問題或意見，請聯繫我們。</div>
            <div class="order02">
              <ul>
                <li>
                  <div class="dbox"><dd>訂單編號</dd><em><a href="<?echo site_url('member/orders/'.$Odata['OID'].'')?>"><? echo $Odata['OID']?></a></em></div>
                  <div class="dbox"><dd>訂購日期</dd><? echo substr($Odata['d_create_time'],0,10)?></div>
                </li>
               <li>
                  <div class="dbox"><dd>訂單金額</dd><b><? echo number_format($Odata['d_total']);?></b></div>
                  <div class="dbox"><dd>付款方式</dd><?echo !empty($Cashflow['d_title'])?$Cashflow['d_title']:'付款方式已不存在';?></div>
                </li>
                <li>
                  <div class="dbox"><dd>訂單狀態</dd><? echo $Orders_status[$Odata['d_orderstatus']] ?></div>
                  <div class="dbox"><dd>付款狀態</dd><? echo $Pay_status[$Odata['d_paystatus']] ?></div>
                </li>
              </ul>
            </div>
            <!--cart-->
            <div class="cart02" style="margin-top:30px;">
              <div class="titlebox">
                <div class="name">商品</div>
                <div class="number">數量</div>
                <div class="price">小計</div>
              </div>
              <!-- 一般產品 -->
              <? if(!empty($Detaildata)):foreach ($Detaildata as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" /></dd>
                      <dt>
                        <div class="tt"><?echo $value['d_title'].(($value['d_status']==3||$value['d_status']==4)?' <span style="color:red">(退貨商品)</span>':'');?></div>
                        <div class="sbox">
                          <div class="dtt">商品編號</div>
                          <div class="spec"><?echo $value['d_model'];?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">單價</div>
                          <div class="spec"><span>NT$.<?echo number_format($value['d_price']);?></span></div>
                        </div>
                        <?if(!empty($value['d_addtitle'])):?>
                          <div class="sbox">
                            <div class="dtt">商品加購</div>
                            <div class="spec"><?echo $value['d_addtitle'].'+ NT$.'.$value['d_addprice'].''?></div>
                          </div>
                        <?endif;?>
                        <div class="sbox">
                          <div class="dtt">出貨日期</div>
                          <div class="spec"><?echo $value['d_ship_date']!='0000-00-00'?$value['d_ship_date']:'無';?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">到貨日期</div>
                          <div class="spec"><?echo $value['d_arrival_date']!='0000-00-00'?$value['d_arrival_date']:'無';?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">物流單號</div>
                          <div class="spec"><?echo !empty($value['d_shipnumber'])?$value['d_shipnumber']:'無';?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">物流商</div>
                          <div class="spec"><?echo !empty($Shipdata_title[$value['SHID']])?'<a href="'.$Shipdata_link[$value['SHID']].'" target="_blank">'.$Shipdata_title[$value['SHID']].'</a>':'無';?></div>
                        </div>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="number"><?=$value['d_num']?></div>
                    <div class="price">$<?echo number_format($value['d_total']);?></div>
                  </div>
                  <?php if (!empty($value['Stitle'])): ?>
                    <div class="salesbox">
                      <div class="slist">
                        <div class="icon"><span class="icon_ok">符合</span></div>
                        <div class="sales"><a href="javascript:void(0)"><?php echo $value['Stitle'] ?></a></div>
                      </div>
                    </div>
                  <?php endif; ?>
                </ul>
              <?endforeach;endif;?>
              <!-- 一般產品 -->
              <!-- 加價購 -->
              <? if(!empty($Adddata)):foreach ($Adddata as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" /></dd>
                      <dt>
                        <div class="tt"><?echo $value['d_title'];?></div>
                        <div class="sbox">
                          <div class="dtt">單價</div>
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
                      <div class="slist">加價購</div>
                    </div>
                  </div>
                </ul>
              <?endforeach;endif;?>
              <!-- 加價購 -->
              <!-- 試用品 -->
              <? if(!empty($Trialdata)):foreach ($Trialdata as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" /></dd>
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
                  </div>
                  <div class="salesbox">
                    <div class="slist">
                      <div class="slist">試用品</div>
                    </div>
                  </div>
                </ul>
              <?endforeach;endif;?>
              <!-- 試用品 -->
              <!-- 贈品 -->
              <? if(!empty($Giftdata)):foreach ($Giftdata as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" /></dd>
                      <dt>
                        <div class="tt"><?echo $value['d_title'];?></div>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="number">1</div>
                    <div class="price">---</div>
                  </div>
                  <div class="salesbox">
                    <div class="slist">
                      <div class="slist">滿額贈</div>
                    </div>
                  </div>
                </ul>
              <?endforeach;endif;?>
              <!-- 贈品 -->

              <!--金、物流-->
              <div class="cart_box_allsbox">
                <!--選擇付款及配送方式-->
                <div class="cart_box">
                  <div class="sign_up_sexbar03">
                       <div class="spcar_ck_tips">商品運送方式</div>
                        <div class="off_cudinp_box">
                            <div class="spcar_ck_tips02"><?echo $Paystatus['d_title']?></div>
                        </div>
                        <div class="spcar_ck_tips">付款方式</div>
                        <div class="off_cudinp_box">
                            <div class="spcar_ck_tips02"><?echo $Cashflow['d_title']?></div>
                        </div>
                        <?php if (!empty($Odata['d_webatm'])): ?>
                          <div class="spcar_ck_tips02">銀行代號：007<br>ATM虛擬帳號：<?echo $Odata['d_webatm'];?><br>繳款金額：<? echo number_format($Odata['d_total']);?><br>*請於下單後三天內完成付款，逾期請勿進行繳納。</div>
                        <?php endif; ?>
                  </div>
                </div>
                <!--//選擇付款及配送方式-->
                <div class="all02">
                  <div class="cost">
    	            <ul>
                      <dd>小計</dd>
                      <dt>$<? echo number_format($Odata['d_price']);?></dt>
                    </ul>
                    <? if(!empty($Odata['d_usebonus'])):?>
                      <ul>
                        <dd>使用紅利點數</dd>
                        <dt>-$<? echo $Odata['d_usebonus'];?></dt>
                      </ul>
                    <?endif;?>
                    <ul>
                      <dd>大型運費</dd>
                      <dt>$<? echo number_format($Odata['d_bigfreight']);?></dt>
                    </ul>
                    <ul>
                      <dd>一般運費</dd>
                      <dt>$<? echo number_format($Odata['d_freight']);?></dt>
                    </ul>
                    <ul>
                      <dd>離島另收</dd>
                      <dt>$<? echo number_format($Odata['d_outisland']);?></dt>
                    </ul>
                    <?php if (!empty($Odata['d_specfreight']) && $Odata['d_orderstatus']!=10): ?>
                      <ul>
                        <dd>特殊運費</dd>
                        <dt>$<? echo number_format($Odata['d_specfreight']);?></dt>
                      </ul>
                    <?php endif; ?>
                    <div class="cart_line"></div>
                    <ul>
                      <dd><b>總計</b></dd>
                      <dt><span class="txt_total">$<? echo number_format($Odata['d_total']);?></span></dt>
                    </ul>
                    <ul>
                      <dd><?php echo ($Odata['d_orderstatus']==6)?'<s>':''; ?><b>本次訂單累計紅利</b><?php echo ($Odata['d_orderstatus']==6)?'</s>':''; ?></dd>
                      <dt><?php echo ($Odata['d_orderstatus']==6)?'<s>':''; ?>$<?echo number_format($Odata['d_bonus']);?><?php echo ($Odata['d_orderstatus']==6)?'</s>':''; ?></dt>
                    </ul>
                    <?php if ($Odata['d_orderstatus']==6): ?>
                      <div class="cart_line"></div>
                      <ul>
                        <dd><b>退貨總計</b></dd>
                        <dt>$<? echo number_format($Odata['d_return_total']);?></dt>
                      </ul>
                      <ul>
                        <dd><b>退還現金</b></dd>
                        <dt>$<? echo number_format($Odata['d_return_money']);?></dt>
                      </ul>
                      <ul>
                        <dd><b>退還紅利</b></dd>
                        <dt>$<? echo number_format($Odata['d_return_reback']);?></dt>
                      </ul>
                      <?php if ($Odata['d_return_pay']>0): ?>
                        <ul>
                          <dd><b>未符合小物免運標準</b></dd>
                          <dt>補收 $<? echo number_format($Odata['d_return_pay']);?></dt>
                        </ul>
                      <?php endif; ?>
                      <ul>
                        <dd><b>本次訂單累計紅利</b></dd>
                        <dt>$<? echo number_format($Odata['d_return_point']);?></dt>
                      </ul>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <!--//金、物流-->
            </div>
            <!--//cart-->
          </section>
        </div>
      </div>
      <div class="gray_bg">
        <div class="container">
          <div class="col-lg-">
            <div class="content_box">
              <!--收件人資料-->
              <div class="cart_box02">
                <form action="javascript:void(0)">
                  <div class="title03">收件人資料</div>
                  <div class="join_line"></div>
                  <ul class="styled-input">
                    <?if(!empty($Odata['d_cname'])):?>
                      <li class="half">
                        <h2>公司大名</h2>
                        <h4><?echo $Odata['d_cname'];?></h4>
                      </li>
                    <?endif;?>
                    <li>
                      <h2>收貨人姓名</h2>
                      <h4><?echo $Odata['d_name'];?></h4>
                    </li>
                    <li class="half">
                      <h2>手機號碼</h2>
                      <h4><?echo $Odata['d_moblie'];?></h4>
                    </li>
                    <?if(!empty($Odata['d_phone'])):?>
                      <li class="half">
                        <h2>市話</h2>
                        <h4><?echo $Odata['d_phone'];?></h4>
                      </li>
                    <?endif;?>
                    <li>
                      <h2>E-mail</h2>
                      <h4><?echo $Odata['d_mail'];?></h4>
                    </li>
                    <li>
                      <h2>地址</h2>
                      <h4><?echo $Odata['d_zip'].' '.$Odata['d_city'].$Odata['d_area'].$Odata['d_address'];?></h4>
                    </li>
                    <?if(!empty($Odata['d_content'])):?>
                      <li>
                        <h2>備註</h2>
                        <h4><?echo $Odata['d_content'];?></h4>
                      </li>
                    <?endif;?>
                  </ul>
                </form>
              </div>
              <!--收件人資料-->
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <!--發票資訊-->
              <div class="cart_box02">
                  <div class="title03">發票資訊</div>
                  <div class="join_line"></div>
                  <div class="invoice_list"><dd><?echo $ITtypedata[$Odata['d_invoice']]?></dd></div>
                  <?if($Odata['d_invoice']==3):?>
                    <ul class="styled-input invoice_box">
                      <?if(!empty($Odata['d_icname'])):?>
                        <li class="half">
                          <h2>公司大名</h2>
                          <h4><?echo $Odata['d_icname'];?></h4>
                        </li>
                      <?endif;?>
                      <li class="half">
                        <h2>統一編號</h2>
                        <h4><?echo $Odata['d_ium'];?></h4>
                      </li>
                      <li>
                        <h2>E-mail</h2>
                        <h4><?echo $Odata['d_imail'];?></h4>
                      </li>
                      <li>
                        <h2>中獎寄送地址</h2>
                        <h4><?echo $Odata['d_Invoicezip'].' '.$Odata['d_Invoicecity'].$Odata['d_Invoicearea'].$Odata['d_iaddress'];?></h4>
                      </li>
                      <div class="cart_line"></div>
                    </ul>
                  <?endif;?>
                  <div class="cart_line"></div>
              </div>
              <!--發票資訊-->
              <div class="text_right"  style="margin-top: 30px;">
                <input type="button" class="btn-style07" value="列印訂單" onclick="printDiv();" />
                <?php if ($Odata['d_orderstatus']==11): ?>
                  <input type="button" class="btn-style07" style="background-color:#ff3c6c;color:#fff" value="同意付款" onClick="location='<?echo site_url('member/orders/specPay/'.$Odata['d_id']);?>'"/>
                <?php else: ?>
                  <input type="button" class="btn-style07" value="訂單查詢" onClick="location='<?echo site_url('member/orders');?>'"/>
                <?php endif; ?>
              </div>
          </section>
        </div>
      </div>
<script>
  function printDiv() {
     var printContents = document.getElementById('orderprint').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
     return false;
  }
</script>
