<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<? echo site_url('') ?>">首頁</a></li>
          <li><a href="<? echo site_url('news') ?>">最新消息</a></li>
          <li class="active"><? echo $category['d_title']; ?></li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="news_view">
              <div class="line">
                <span class="NewsTA" style="background-color:<? echo $category['d_color']; ?>"><? echo $category['d_title']; ?></span>
                <div class="tiltebox">
                  <div class="tilte"><? echo $dbdata['d_title']; ?></div>
                  <div class="data"><? echo $dbdata['d_date']; ?></div>
                </div>
              </div>
            </div>
            <!--user_editor-->
            <div class="user_editor line-height">
              <? echo $dbdata['d_content']; ?>
            </div>
            <!--//user_editor-->
            <div class="center" style="margin-top:30px"><a href="javascript:history.back(1)" class="btn-style01 hvr-bob">回上一頁</a></div>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
