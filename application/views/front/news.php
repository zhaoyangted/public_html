<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<? echo site_url('') ?>">首頁</a></li>
          <li><a href="<? echo site_url('news') ?>">最新消息</a></li>
          <li class="active"><? echo !empty($TID)?$TID:'全部'; ?></li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">最新消息</div>
            <div class="top-category">
              <ul>
                <li><a href="<? echo site_url('news') ?>" class="active">全部</a></li>
                <?php foreach ($News_type as $type): ?>
                  <li><a href="<? echo site_url('news/index/'.$type['d_id']) ?>" class="active"><? echo $type['d_title'] ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="NewsConBox">
              <?php if (!empty($NewsData['dbdata'])): ?>
                <?php $category = array_column($News_type,'d_title','d_id'); ?>
                <?php $color_type = array_column($News_type,'d_color','d_id'); ?>
                <?php foreach ($NewsData['dbdata'] as $n): ?>
                <ul class="Ntur"><b><? echo $n['d_date'] ?></b> <span class="NewsTA" style="background-color:<? echo $color_type[$n['TID']] ?>"><? echo $category[$n['TID']] ?></span><a href="<? echo site_url('news/info/'.$n['d_id']) ?>"><? echo $n['d_title'] ?></a></ul>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <? echo !empty($NewsData['dbdata'])?$NewsData['PageList']:'';?>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
