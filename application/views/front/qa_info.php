<?php include '_header.php';?>
<main>
    <article>
      <!--bread--> 
      <div class="box-1">
        <ul class="breadcrumb">
          <li><a href="<?echo site_url(' ');?>">首頁</a></li>
          <li><a href="<?echo site_url('qa');?>">常見問題 Q&A</a></li>
          <li class="active"><?echo $TypeData['d_title']?></li>
        </ul>
        </ul>
      </div>
      <!--//bread--> 
      <div class="container">
        <div class="col-lg-">
          <section class="content_box">
            <div class="qa_view">
              <li>
                <div class="tt">
                  <b>Q</b><? echo $dbdata['d_title'];?>
                </div>
              </li>
            </div>
            <!--user_editor--> 
            <div class="qa_view">
              <div class="a">A</div>
              <div class="abox">
                <div class="user_editor line-height">
                  <!--文字編輯器內容-->
                  <? echo stripcslashes($dbdata['d_content']);?>
                  <!--//文字編輯器內容-->
                </div>
              </div>
              <!--//user_editor--> 
            </div>
            <?if(!empty($Qdata)):?>
              <div class="title02" style="margin-top:50px;">相關問題</div>
              <div class="qa03">
                <?foreach ($Qdata as $key => $value):?>
                  <li>
                    <a href="<?echo site_url('qa/info/'.$value['d_id'].'');?>">
                      <div class="tt">
                        <b>Q</b><?echo $value['d_title'];?></div>
                    </a>
                  </li>
                <?endforeach;?>
              </div>
            <?endif;?>
            <div class="center" style="margin-top:30px"><a href="javascript:history.back(1)" class="btn-style01 hvr-bob">回上一頁</a></div>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>