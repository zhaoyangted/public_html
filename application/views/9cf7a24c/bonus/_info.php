<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<script src='<? echo CCODE::DemoPrefix.('/js/myjava/Config.js');?>'></script>
<script src='<? echo CCODE::DemoPrefix.('/js/myjava/Checkinput.js');?>'></script>

<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/toolbar-btn.css')?>" />
  <div class="indexView">
      <div class="indexView_block">
          <div class="indexView_title">
              <div class="line l-blue"></div>
              <i class="<?echo $this->tableful->MenuidDb['d_icon']?>"></i>
              <sapn class="top-title"><?echo $this->tableful->MenuidDb['d_title']?></sapn>
          </div>
          <?$Disabled=($this->autoful->EditView==3)?'disabled':'';?>
          <div class="page_block">
              <div class="page_block_title"><?echo $this->tableful->MenuidDb['d_title']?></div>
              <form action="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'/'.$this->FunctionType);?>" method="post" enctype="multipart/form-data">
              <div class="form-content">
                <div>
                  <ul class="form_wrap">
                    <li>
                        <div class="form_wrap_title"></div>
                        推薦朋友加入會員，並首次購物成功獲得
                        <input type="number" class="form-control" name="15" value="<?php echo !empty($dbdata['15'])?$dbdata['15']:'0';?>" style="width: 10%;" <?echo $Disabled;?>>
                        紅利點數
                    </li>
                  </ul>
                  <ul class="form_wrap">
                    <li>
                        <div class="form_wrap_title"></div>
                        首次加入會員成功後，即獲得
                        <input type="number" class="form-control" name="17" value="<?php echo !empty($dbdata['17'])?$dbdata['17']:'0';?>" style="width: 10%;" <?echo $Disabled;?>>
                        紅利點數
                    </li>
                  </ul>
                  <ul class="form_wrap">
                    <li>
                      <?$this->useful->CKediter('bonus');?>
                        <div class="form_wrap_title">紅利點數說明</div><br>
                        <textarea name="16" id="16" class="contentin-table-textarea" style="margin: 0px; width: 500px; height: 137px;" <?echo $Disabled;?>><?php echo !empty($dbdata['16'])?$dbdata['16']:'';?></textarea>
                    </li>
                  </ul>
                </div>
            </div>
          </div>
          <div class="search-btn">
              <input type="hidden" name="dbname" id="dbname" value="<?=$this->DBname?>">
              <input type="hidden" name="d_id" id="d_id" value="<? echo !empty($d_id)?$d_id:''?>">
              <?if($this->autoful->EditView==2):?>
                <input type="submit" name="" value="送出" class="inquire">
              <?endif;?>
          </div>
      </form>
  </div>
</div>
<!-- Ckediter -->
<script src="<? echo CCODE::DemoPrefix.'/js/myjava/ckeditor/ckeditor.js';?>"></script>
<script src="<? echo CCODE::DemoPrefix.'/js/myjava/ckeditor.js';?>"></script>
<script>CKEDITOR.replace('16',config);</script>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>

