<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/toolbar-btn.css')?>" />
    <div class="indexView">
        <div class="indexView_block">
            <div class="indexView_title">
                <div class="line l-blue"></div>
                <i class="<?echo $this->tableful->MenuidDb['d_icon']?>"></i>
                <sapn class="top-title"><?echo $this->tableful->MenuidDb['d_title']?></sapn>
            </div>
            <div class="page_block">
                <div class="page_block_title"><?echo $this->tableful->MenuidDb['d_title']?></div>
                <form action="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/ckeditpage/ckeditpage/edit');?>" method="post" enctype="multipart/form-data">
                <div class="form-content">
                    <div>
                        <ul class="form_wrap">
                            <li class="">
                                <div class="form_wrap_title"><?echo $this->FiledList['d_content']?></div>
                                <textarea name="d_content" id="d_content" ><?php echo !empty($dbdata['d_content'])?stripslashes($dbdata['d_content']):'';?></textarea>
                                <? if(!empty($dbdata['d_text'])):?>
                                    <span class="form-remark"><? echo $dbdata['d_text'];?></span>
                                <?endif;?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="search-btn">
                <input type="hidden" name="d_id" id="d_id" value="<? echo !empty($d_id)?$d_id:''?>">
                <?if($this->autoful->EditView==2):?>
                    <input type="submit" name="" value="送出" class="inquire">
                <?endif;?>
            </div>
        </div>
        </form>
    </div>
    </div>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/myjava/ckeditor/ckeditor.js');?>"></script> 
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/myjava/ckeditor.js');?>"></script>
<script>
CKEDITOR.replace("d_content",config);
</script>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
