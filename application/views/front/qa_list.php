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
            <div class="qa02">
              <? if(!empty($dbdata['dbdata'])):foreach ($dbdata['dbdata'] as $key => $value):?>
                <li>
                  <a href="<?echo site_url('qa/info/'.$value['d_id'].'');?>">
                    <div class="tt">
                      <b>Q</b><?echo $value['d_title'];?>
                    </div>
                  </a>
                </li>
              <? endforeach;else:?>
                目前尚無資料
              <?endif;?>
            </div>
            <?echo (!empty($dbdata['dbdata'])?$dbdata['PageList']:'');?>
          </section>
        </div>
      </div>
    </article>
</main>
<?php include '_footer.php';?>