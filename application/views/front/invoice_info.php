<section class="addinfo">
  <!--info-->
  <div class="cart04">
    <ul>
      <li><dd>訂單編號</dd><? echo $orders['OID'] ?></li>
      <li><dd>發票號碼</dd><? echo $orders['d_invoicenumber'] ?></li>
    </ul>
    <ul>
      <li><dd>訂購日期</dd><? echo date('Y-m-d',strtotime($orders['d_create_time'])) ?></li>
      <li><dd>開立日期</dd>2019-05-20</li>
    </ul>
  </div>
  <div class="cart03">
    <div class="titlebox">
      <div class="name">商品</div>
      <div class="number">數量</div>
      <div class="price">單價</div>
      <div class="money">金額</div>
    </div>
    <?php $count = 0 ; ?>
    <?php foreach ($orders_detail as $o): ?>
      <?php $count = $count + number_format($o['d_num']); ?>
      <ul>
        <div class="namebox">
          <div class="name"><? echo $o['d_title'].(!empty($o['d_addtitle'])?'<br>('.$o['d_addtitle'].'+ NT$.'.number_format($o['d_addprice']).')':'') ?></div>
        </div>
        <div class="numberbox">
          <div class="number"><? echo $o['d_num'] ?></div>
          <div class="price"><? echo number_format($o['d_price']) ?></div>
          <div class="money"><? echo number_format($o['d_total']) ?></div>
        </div>
      </ul>
    <?php endforeach; ?>
    <ul>
      <div class="namebox">
        <div class="name">運費</div>
      </div>
      <div class="numberbox">
        <div class="number">　</div>
        <div class="price">　</div>
        <div class="money"><? echo number_format($orders['d_freight']) ?></div>
      </div>
    </ul>
    <ul>
      <div class="namebox">
        <div class="name">紅利折抵</div>
      </div>
      <div class="numberbox">
        <div class="number">　</div>
        <div class="price">　</div>
        <div class="money"><? echo '-'.number_format($orders['d_usebonus']) ?></div>
      </div>
    </ul>
    <div class="allbox">
      <div class="name">總計</div>
      <div class="number"><? echo $count ?></div>
      <div class="price">　</div>
      <div class="money"><? echo number_format($orders['d_total']) ?></div>
    </div>
  </div>
  <div class="cart05">
    <ul><img src="<? echo site_url('images/demo/invoice.jpg') ?>" /></ul>
    <ul>
      <h1>交易明細</h1>
      <h2>詳購物清單</h2>
      <li><dd>稅別</dd>xxx</li>
      <li><dd>總計</dd><? echo number_format($orders['d_total']) ?></li>
      <li style="margin-top:20px;"><dd>備註</dd>請隨貨附上發票</li>
      <li><dd>信用卡未4碼</dd>1234</li>
      <li><dd>個人識別碼</dd>1234</li>
      <li><dd>載具號碼</dd>/ANIS9DKS</li>
    </ul>
  </div>
  <div class="cart06">
    <li>依財政部令本副本僅提供查核，不可直接兌獎。</li>
    <li>台灣千冠莉國際股份有限公司將於開獎月29日至電子發票整合服務平台下載中獎清冊，會員若有中獎本公司將於開獎日翌日起10日內以電子郵件方式通知會員，並以掛號郵寄方式提供中獎電子發票證明聯予中獎會員作為兌獎憑證，會員亦可至「我的帳戶」查詢兌獎憑證預計寄送日期情形。</li>
    <li>根據財政部台財稅字第0952400194號訂定之「電子發票實施作業要點」，我們將為您將發票檔案儲存於您的訂單資料中，並將發票檔上傳到政府的「電子發票整合服務平台」。</li>
  </div>
  <!--//info-->
</section>
