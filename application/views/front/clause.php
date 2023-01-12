<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<? echo site_url('') ?>">首頁</a></li>
          <li class="active">隱私權條款</li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">隱私權條款</div>
            <!--user_editor-->
            <div class="user_editor line-height">
              <? echo !empty($ClauseData)?stripslashes($ClauseData):''; ?>
            </div>
            <!--//user_editor-->
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
