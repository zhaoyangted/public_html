<?php include '_header02.php';?>
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
            <?php include '_order.php';?>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>