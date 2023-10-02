<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<? echo site_url('') ?>">首頁</a></li>
          <li class="active">關於千冠莉</li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">關於千冠莉</div>
            <!--user_editor-->
            <div class="user_editor line-height">
              <? echo !empty($AboutData)?stripslashes($AboutData):''; ?>
            </div>
            <!--//user_editor-->
            <div class="store">
              <a id="position" style=" position: relative;top: -80px;display: block;height: 0;overflow: hidden;"></a>
              <?php foreach ($AboutMap as $m): ?>
                <li>
                  <h2><?php echo $m['d_title'] ?></h2>
                  <div class="sbox">
                    <div class="stxt">
                      <h3>地　　址：<?php echo $m['d_address'] ?></h3>
                      <h3>電　　話：<?php echo $m['d_tel'] ?></h3>
                      <h3>傳　　真：<?php echo $m['d_fax'] ?></h3>
                      <h3>營業時間：<?php echo $m['d_time'] ?></h3>
                    </div>
                    <div class="smap">
                      <?php echo $m['d_link'] ?>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
           </div>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
