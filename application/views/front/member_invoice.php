<?php include '_header02.php';?>
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
            <div class="top-category">
              <ul>
                <li><a href="member_account.php"><i class="fas fa-user-edit"></i> 會員資料修改</a></li>
                <li><a href="member_order.php"><i class="fas fa-file-alt"></i> 購物紀錄與訂單查詢</a></li>
                <li><a href="member_invoice.php" class="active"><i class="fas fa-file-invoice-dollar"></i> 電子發票歸戶</a></li>
                <li><a href="member_point.php"><i class="fas fa-dollar-sign"></i> 會員點數查詢</a></li>
                <li><a href="member_favorite.php"><i class="far fa-heart"></i> 我的收藏</a></li>
                <li><a href="member_friend.php"><i class="fas fa-user-friends"></i> 邀請好友加入會員</a></li>
              </ul>
            </div>
            <div class="w16 center">提醒您，完成歸戶後若領獎方式選擇銀行帳戶，將由「財政部電子發票整合服務平台」進行發票對獎、領獎相關通知及作業，<br>Beauty Garage 台灣美麗平台將不再另行通知</div>
            <!--會員登入-->
            <div class="member" style="margin-top: 10px;">
              <div class="mbox">
                  <div class="invoice_box03"> 
                    <li>會員帳號：</li>
                    <li>service@jddt.tw</li>
                    <li><a class="btn-style10" href="https://www.einvoice.nat.gov.tw/APMEMBERVAN/membercardlogin" target="_blank">立即歸戶</a></li>
                  </div>
              </div>          
            </div>
            <!--//會員登入--> 
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>