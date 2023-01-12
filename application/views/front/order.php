<?php include '_header.php';?>
<main>
    <article>
      <!--bread--> 
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="index.php">首頁</a></li>
          <li class="active">訂單查詢</li>
        </ul>
      </div>
      <!--//bread--> 
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">訂單查詢</div>
            <div class="w15 center" style="margin-top:-30px;">以下系統僅供非會員訂單查詢</div>
            <!--會員登入-->
            <div class="member" style="margin-top:30px;">
              <div class="mbox">
                <form action="javascript:void(0)">
                  <ul class="styled-input">
                    <li>
                      <h2>訂單號碼*</h2>
                      <input type="text" id="order_no"/>
                    </li>
                    <li>
                      <h2>手機號碼*</h2>
                      <input type="text" id="phone"/>
                    </li>
                    <li>
                      <h2>驗証碼*</h2>
                      <input type="text" id="code"/>
                    </li>
                    <li class="contact-captcha">
                      <img src="images/demo/code.png" />
                    </li>
                    <li style="text-align:center;">
                      <input type="submit" class="btn-style02" value="確認送出" onclick="location='order_view.php'"/> <input type="submit" class="btn-style02" value="重新填寫"/>
                    </li>         
                  </ul>	
                </form>
              </div>          
            </div>
            <!--//會員登入--> 
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>