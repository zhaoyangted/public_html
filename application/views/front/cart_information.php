<?php include '_header.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.twzipcode.min.js')?>"></script>
<form action="<? echo site_url('cart/Addorder')?>" method="post" >
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
            <div class="title01 center">填寫訂購資訊</div>
            <!--cart-->
            <div class="cart02">
              <div class="titlebox">
                <div class="name">商品</div>
                <div class="number">數量</div>
                <div class="price">小計</div>
              </div>
              <!-- 購物車產品 -->
              <!-- 一般商品 -->
              <?foreach ($CartProduct['Cart'] as $key => $value):?>
                <ul>
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
                            <div class="dtt">商品加購</div>
                            <div class="spec"><?echo $value['AddData']['AddTitle'].'+ NT$.'.$value['AddData']['AddPrice'].''?> </div>
                          </div>
                        <?endif;?>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="number"><?=$value['num']?></div>
                    <div class="price">$<?echo number_format($value['d_total']);?></div>
                  </div>
                  <?php if ($value['IsSale']): ?>
                    <div class="salesbox">
                      <div class="slist">
                        <div class="icon"><span class="icon_ok">符合</span></div>
                        <div class="sales"><a href="<? echo site_url('products/sales/'.$value['d_id'].'') ?>" target="_blank"><?php echo $this->autoful->DiscountData[$value['d_id']]['d_title'] ?></a></div>
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
                  </div>
                  <div class="salesbox">
                    <div class="slist">
                      <div class="slist">加購價產品</div>
                    </div>
                  </div>
                </ul>
              <?endforeach;?>
              <!-- 加價購 -->
              <!-- 試用品 -->
              <?foreach ($CartProduct['TrialData'] as $key => $value):?>
                <ul>
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
                  </div>
                  <div class="salesbox">
                  <div class="slist">試用品</div>
                </div>
                </ul>
              <?endforeach;?>
              <!-- 試用品 -->
              <!-- 贈品 -->
              <?if(!empty($Gdata)):foreach ($Gdata as $key => $value):?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><img src="<? echo CCODE::DemoPrefix.'/'.$value['d_img']?>" alt=""></dd>
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
                  <div class="slist">滿額贈品</div>
                </div>
                </ul>
              <?endforeach;endif;?>
              <!-- 贈品 -->
              <!--金、物流-->
              <div class="cart_box_allsbox">
                <!--選擇付款及配送方式-->
                <div class="cart_box">
                    <div class="sign_up_sexbar03">
                      <div class="spcar_ck_tips">請選擇商品運送方式</div>
                        <div class="off_cudinp_box">
                            <div class="logistics_selection">
                                <select id="sp3_t1" class="sign_up_inpt" name="d_logistics">
                                  <option value="">請選擇運送方式</option>
                                  <?if(!empty($Ldata)):foreach ($Ldata as $key => $value):?>
                                      <option value="<?echo $value['d_id'];?>"><?echo $value['d_title'];?></option>
                                  <?endforeach;endif;?>
                                </select>
                            </div>
                            <div class="spcar_ck_tips02 sp3t1ax01">宅配 NT$<?echo $CartProduct['OneFreight']['d_freight'];?>， 滿<?echo number_format($CartProduct['OneFreight']['d_free']);?>元免一般運費！請填寫您的收件資訊。</div>
                            <div class="spcar_ck_tips02 outisland" style="display: none;">離島需另收物流費用，不適用於免運費優惠！</div>
                        </div>
                        <div class="spcar_ck_tips">請選擇付款方式</div>
                        <div class="off_cudinp_box">
                            <div class="logistics_selection">
                                <select  id="sp3_t2" class="sign_up_inpt sp3_t2" name="d_pay">
                                    <option value="">請選擇付款方式</option>
                                    <?if(!empty($Pdata)):foreach ($Pdata as $key => $value):?>
                                      <option value="<?echo $value['d_id'];?>"><?echo $value['d_title'];?></option>
                                    <?endforeach;endif;?>
                                </select>
                            </div>
                            <?if(!empty($Pdata)):foreach ($Pdata as $key => $value):?>
                              <div class="spcar_ck_tips02 sp3t2ax0<?echo $value['d_id'];?>"><?echo $value['d_content'];?></div>
                            <?endforeach;endif;?>
                        </div>
                    </div>
                </div>
                <!--//選擇付款及配送方式-->
                <div class="all02">
                  <div class="cost">
    	            <ul>
                      <dd>小計</dd>
                      <dt>$<?echo number_format($CartProduct['Total']);?></dt>
                    </ul>

                    <? if(!empty($Mdata)):if($Mdata['d_bonus']!=0):?>
                      <ul>
                        <dd>可用紅利點數：<?echo number_format($Mdata['d_bonus']);?>點</dd>
                        <dt><input class="select_point" value="<?php echo !empty($_SESSION[CCODE::MEMBER]['SubBonus'])?$_SESSION[CCODE::MEMBER]['SubBonus']:0; ?>" size="5" type="number" min="0" max="<?echo $Mdata['d_bonus'];?>" id="SubBonus" name="SubBonus" onchange="SubBonus_Change()"></dt>
                      </ul>
                    <? endif;endif;?>
                      <ul>
                        <dd>大型運費</dd>
                        <dt>$<?echo number_format($CartProduct['BigFreight']);?></dt>
                      </ul>
                      <ul>
                        <dd>一般運費</dd>
                        <dt>$<?echo number_format($CartProduct['Freight']) ;?></dt>
                      </ul>
                      <ul>
                        <dd>離島另收</dd>
                        <dt id="outisland">$0</dt>
                      </ul>
                      <ul><h1>訂單小計滿<em>$<?echo number_format($CartProduct['OneFreight']['d_free']);?></em>元，免一般運費</h1></ul>
                    <div class="cart_line"></div>
                    <ul>
                      <dd><b>總計</b></dd>
                      <dt><span class="txt_total" id="AllTotal">$<?echo number_format($CartProduct['AllTotal']);?></span></dt>
                    </ul>
                    <ul>
                      <dd><b>本次訂單累計紅利</b></dd>
                      <dt id="BonusTotal">$<?echo number_format($CartProduct['BonusTotal']);?></dt>
                    </ul>
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
                  <div class="title03">收件人資料</div>
                  <div class="join_line"></div>
                  <?if(!empty($this->Mid)):?>
                    <div class="add_box" style="width:200px;">
                      <li style="text-align:center;width:100%;"><a href="#add_info" class="fancybox" id="GetSend">選擇/管理 收件人通訊錄</a></li>
                    </div>
                    <?php empty($Preset_send)?$Preset_send = $Mdata:''; ?>
                  <?endif;?>
                  <ul class="styled-input">
                    <li class="half">
                      <h2>公司大名</h2>
                      <input type="text" name="d_cname" value="<?echo (!empty($Preset_send['d_company_title'])?$Preset_send['d_company_title']:'')?>"/>
                    </li>
                    <li class="half">
                      <h2>收貨人姓名*</h2>
                      <input type="text" name="d_name" value="<?echo (!empty($Preset_send['d_pname'])?$Preset_send['d_pname']:'')?>" />
                    </li>
                    <li class="half">
                      <h2>手機號碼*</h2>
                      <input type="text" name="d_moblie" value="<?echo (!empty($Preset_send['d_phone'])?$Preset_send['d_phone']:'')?>" placeholder="<?echo (!empty($this->Mid)?'':'請輸入正確號碼，此為您的會員密碼');?>"/>
                    </li>
                    <li class="half">
                      <h2>市話</h2>
                      <input type="text" name="d_phone" value="<?echo (!empty($Preset_send['d_company_tel'])?$Preset_send['d_company_tel']:'')?>" />
                    </li>
                    <li>
                      <h2>E-mail*</h2>
                      <input type="text" name="d_email" value="<?echo (!empty($Mdata['d_account'])?$Mdata['d_account']:'')?>" placeholder="<?echo (!empty($this->Mid)?'':'請輸入正確信箱，並收得到信件，此為您的會員帳號');?>"/>
                    </li>
                    <li>
                      <h2>地址*</h2>
                      <div class="mem_add" id="twzipcode">
                        <div data-role="county" data-style="mem_add_inpt" class="mem_inpt" data-value="<?echo (!empty($Preset_send['d_county'])?$Preset_send['d_county']:'')?>" ></div>
                        <div data-role="district" data-style="mem_add_inpt" class="mem_inpt" data-value="<?echo (!empty($Preset_send['d_district'])?$Preset_send['d_district']:'')?>" ></div>
                        <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt" data-value="<?echo (!empty($Preset_send['d_zipcode'])?$Preset_send['d_zipcode']:'')?>" ></div>
                      </div>
                      <input type="text2" name="d_address" value="<?echo (!empty($Preset_send['d_address'])?$Preset_send['d_address']:'')?>"/>
                    </li>
                    <li>
                      <h2>備註</h2>
                      <textarea rows="5" name="d_content"></textarea>
                    </li>
                  </ul>
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
                  <?if(!empty($this->Mid)):?>
                    <div id="Invoice_fancybox" class="add_box" style="width:200px;display:none;">
                      <li style="text-align:center;width:100%;"><a href="#invoice_info" class="fancybox" id="GetInvoice">選擇/管理 統一編號</a></li>
                    </div>
                    <?php empty($Preset_invoice)?$Preset_invoice = $Mdata:''; ?>
                  <?endif;?>
                  <div class="invoice_list">
                    <?foreach ($ITtypedata as $key => $value):?>
                      <dd><input name="d_invoice" type="radio" value="<?echo $key?>" <?echo ($key==1)?'checked':'';?> /><?echo $value?></dd>
                    <?endforeach;?>
                  </div>
                  <ul class="styled-input invoice_box02" style="display: none;">
                    <li>
                      <h2>捐贈機關/團體*</h2>
                      <select name="d_donate" id="select_invoice" class="select_point">
                        <option value="" >請選擇捐贈機關/團體</option>
                        <?if(!empty($Idata)):foreach ($Idata as $key => $value):?>
                          <option value="<?echo $value['d_id'];?>"><?echo $value['d_title'];?></option>
                        <?endforeach;endif;?>
                        <option value="Other">其他捐贈</option>
                      </select>
                    </li>
                    <li class="other invoice03">請輸入捐贈碼或受捐贈機關/團體名：
                      <input type="text3" id="invoice_other" name="d_othername"/><a href="images/demo/invoice.pdf"><span>(捐贈清冊下載<i class="fas fa-download"></i>)</span></a></li>
                    <div class="cart_line"></div>
                  </ul>
                  <ul class="styled-input invoice_box" style="display: none;">
                    <li class="half">
                      <h2>公司大名</h2>
                      <input type="text" name="d_icname" value="<?echo (!empty($Preset_invoice['d_company_title'])?$Preset_invoice['d_company_title']:'')?>">
                    </li>
                    <li class="half">
                      <h2>統一編號*</h2>
                      <input type="text" name="d_ium" value="<?echo (!empty($Preset_invoice['d_company_number'])?$Preset_invoice['d_company_number']:'')?>">
                    </li>
                    <li>
                      <h2>中獎寄送地址*</h2>
                      <div class="mem_add" id="twzipcode02">
                        <div data-role="county" data-style="mem_add_inpt" class="mem_inpt" data-value="<?echo (!empty($Preset_invoice['d_county'])?$Preset_invoice['d_county']:'')?>" ></div>
                        <div data-role="district" data-style="mem_add_inpt" class="mem_inpt" data-value="<?echo (!empty($Preset_invoice['d_district'])?$Preset_invoice['d_district']:'')?>" ></div>
                        <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt" data-value="<?echo (!empty($Preset_invoice['d_zipcode'])?$Preset_invoice['d_zipcode']:'')?>" ></div>
                      </div>
                      <input type="text2" name="d_iaddress" value="<?echo (!empty($Preset_invoice['d_address'])?$Preset_invoice['d_address']:'')?>"/>
                    </li>
                    <div class="cart_line"></div>
                  </ul>
                  <div class="cart_line"></div>
                  <div class="invoice_list02">
                    <input type="checkbox" name="d_backagree" value="Y">我同意辦理退貨時，由台灣千冠莉國際股份有限公司代為處理電子發票及銷貨退回折讓單以加速退款作業。
                  </div>
              </div>
              <!--發票資訊-->
              <?php if (!empty($_SESSION[CCODE::MEMBER]['NoBonus'])): ?>
                <br>
                <div class="cart_box02">
                    <div class="title03">管理者操作區</div>
                    <div class="join_line"></div>
                    <ul class="styled-input">
                      <li>
                        <h2>部門*</h2>
                        <select name="d_department" class="select_point">
                          <option value="" >請選擇</option>
                          <?if(!empty($Department)):foreach ($Department as $d):?>
                            <option value="<?echo $d['d_id'];?>"><?echo $d['d_code'].'-'.$d['d_title'];?></option>
                          <?endforeach;endif;?>
                        </select>
                      </li>
                    </ul>
                </div>
              <?php endif; ?>
              <div class="text_right" style="margin-top: 30px;">
                <input type="button" class="btn-style07" value="上一步" onClick="javascript:history.go(-1);"/>
                <input type="submit" class="btn-style07" value="下一步" />
              </div>
          </section>
        </div>
      </div>
    </article>
</main>
<input type="hidden" name="d_freight" value="<?echo $CartProduct['Freight']+$CartProduct['BigFreight']?>">
<input type="hidden" name="AllTotal" value="<?echo (!empty($CartProduct['AllTotal'])?$CartProduct['AllTotal']:0)?>">
</form>
<?include('_CartSend.php');?>
<?include('_CartInvoice.php');?>

<?php include '_footer.php';?>

<script src="<? echo CCODE::DemoPrefix.('/js/front/cart.js')?>"></script>
<script>
<?php if (!empty($Mdata)&&$Mdata['d_bonus']!=0) {?>
  $(function() {
    SubBonus_Change();
  });

  function SubBonus_Change() {
    Total='<?php echo $CartProduct['Total'] ?>';
    Bonus=$('#SubBonus').val();
    freight=$('input[name="d_freight"]').val();
    if (Bonus=="") {
      $('#SubBonus').val('0');
      $('#AllTotal').html('$'+addCommas(parseInt(Total)+parseInt(freight)));
    }else{
      if(Bonus>=0){
        $.ajax({
          url:'<? echo site_url('cart/BonusOperation')?>',
          type:'POST',
          data: {
            'Bonus':Bonus,
            'freight':freight,
            'Total':Total,
          },
          dataType: 'json',
          success: function(json){
            if(json.Status=='OK'){
              $('#BonusTotal').html('$'+json.BonusTotal);
              $('#AllTotal').html('$'+json.Subbonus);
            }else{
              $('#SubBonus').val('0');
              $('#AllTotal').html('$'+addCommas(parseInt(Total)+parseInt(freight)));
              alert(json.Status);
            }
          }
        });
      }else{
        alert('紅利折扣不得為負');
        $('#SubBonus').val('0');
      }
    }
  }
<?php } ?>

$('#sp3_t1').change(function(){
  id=$(this).val();
  if(id!=''){
    GetSendSelect(id);
  }else{
    $('#outisland').html('$0');
    $('#AllTotal').html('$'+addCommas(parseInt(<?php echo $CartProduct['AllTotal'] ?>)-parseInt(((typeof($('#SubBonus').val())=='undefined')?0:$('#SubBonus').val()))));
    $('input[name="d_freight"]').val(<?php echo $CartProduct['Freight']+$CartProduct['BigFreight'] ?>);
    $('.sp3t1ax01,.sp_ship01,.outisland').hide();
  }
});

function GetSendSelect(id){
  Total="<?php echo $CartProduct['Total'] ?>";
  subBonus=(typeof($('#SubBonus').val())=='undefined')?0:$('#SubBonus').val();
  $.ajax({
    url:'<? echo site_url('cart/ChangeSend')?>',
    type:'POST',
    data:{
        id:id,
        freight:"<?php echo $CartProduct['Freight']+$CartProduct['BigFreight'] ?>",
        subBonus:subBonus,
        outisland:"<?php echo $CartProduct['Outisland'] ?>",
    },
    dataType: 'json',
    success: function( json ){
      if (json.status=='error') {
        alert('該運送方式已不存在！');
        location.reload();
      }else if(json.status=='success'){
        $('input[name="d_freight"]').val(json.freight);
        $('#outisland').html('$'+addCommas(json.Addfreight));
        $('#AllTotal').html('$'+addCommas(parseInt(Total)+parseInt(json.freight)-parseInt(subBonus)));
      }
    }
  });
}


function addCommas(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
$('#twzipcode').twzipcode({
  'countyName'   : 'd_city',   // 預設值為 county
  'districtName' : 'd_area', // 預設值為 district
  'zipcodeName'  : 'd_zip',  // 預設值為 zipcode
});
</script>
