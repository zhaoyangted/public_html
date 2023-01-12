<?php include '_header.php';?>
<main>
    <article>
      <!--bread--> 
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<? echo site_url('') ?>">首頁</a></li>
          <li class="active">會員中心</li>
        </ul>
      </div>
      <!--//bread--> 
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">會員中心</div>
            <?php include '_member_menu.php';?>
            <?php include '_order.php';?>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>