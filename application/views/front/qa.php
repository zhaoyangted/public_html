<?php include '_header.php';?>
<main>
    <article>
      <!--bread-->
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url(' ');?>">首頁</a></li>
          <li class="active">常見問題 Q&A</li>
        </ul>
      </div>
      <!--//bread-->
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="title01 center">常見問題 Q&A</div>
            <div class="title02">常見問題</div>
            <div class="qa">
              <?if(!empty($HotQaData)):foreach ($HotQaData as $key => $value):?>
                <li>
                  <a href="<?echo site_url('qa/info/'.$value['d_id'].'');?>">
                    <div class="tt">
                      <b>Q</b><?echo $value['d_title'];?></div>
                  </a>
                </li>
              <?endforeach;endif;?>
            </div>
            <!--qa_category-->
            <div class="qa_row">
              <?if(!empty($QaData)):foreach ($QaData as $key => $value):?>
                <div class="col-md-6">
                  <div class="accordion"><?echo $key;?></div>
                  <div class="panel">
                    <div class="qa02">
                      <?if(!empty($value)):foreach ($value as $qvalue):?>
                        <li>
                          <a href="<?echo site_url('qa/info/'.$qvalue['d_id'].'');?>">
                            <div class="tt">
                              <b>Q</b><?echo $qvalue['d_title'];?>
                            </div>
                          </a>
                        </li>
                      <?endforeach;endif;?>
                      <div class="title04 text_right"><a href="<?echo site_url('qa/qa_list/'.$value[0]['TID'].'');?>">查看更多</a></div>
                    </div>
                  </div>
                </div>
              <?endforeach;endif;?>
            </div>
            <!--//qa_category-->
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/front/jquery.accordion.js')?>"></script>
