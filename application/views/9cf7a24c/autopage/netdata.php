<link rel="stylesheet" type="text/css" href="<? echo base_url('css/backend/toolbar-btn.css')?>" />
<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
    <div class="indexView">
        <div class="indexView_block">
            <div class="indexView_title">
                <div class="line l-blue"></div>
                <i class="fas fa-bullhorn"></i>
                <sapn class="top-title">系統資料設定</sapn>
            </div>
            <div class="page_block">
                <form method="post" enctype="multipart/form-data" id="FormSubmit" action="<? echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->EditFunction.'/'.$this->EditFunction.'/'.$FunctionType)?>">
                    <div class="form-content">
                        <?if(!empty($this->Auto_page['AutoPage'])):
                            $Disabled=($this->autoful->EditView==3)?'disabled':'';
                        ?>
                        <div class="row">
                            <ul class=" form_wrap">
                                <?foreach ($this->Auto_page['AutoPage'] as $fkey => $fvalue):$Star=(!empty($fvalue['d_search']))?'*':'';
                                    //Input Text
                                    if($fvalue['d_type']==1):
                                ?>
                                    <li class="col-md-6 col-sm-6">
                                        <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                                        <input type="text" class="form-control" name="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" <?echo ($fvalue['d_fname']==6)?'maxlength="6"':'';?> <?echo $Disabled;?>>
                                        <? if(!empty($fvalue['d_content'])):?>
                                            <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                                        <?endif;?>
                                    </li>
                                <?endif;
                                    //Input textarea
                                    if($fvalue['d_type']==5):
                                ?>
                                    <li class="col-md-6 col-sm-6">
                                        <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                                        <textarea name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"  class="form-control"  rows="8" cols="50" <?echo $Disabled;?>><?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?></textarea>
                                    </li>

                                <?endif;
                                   //Input Imgfile
                                    if($fvalue['d_type']==8):
                                ?>
                                    <li class="col-md-6 col-sm-6">
                                        <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                                        <div>
                                            <?if($this->autoful->EditView==2):?>
                                                <div class="file_upload_btn">
                                                    <label for="file-upload_<?=$fvalue['d_fname']?>" class="custom-file-upload">
                                                        <img class="" src="<?echo CCODE::DemoPrefix.'/'.('images/backend/ico_upload.png');?>">
                                                        <span class="upload">檔案上傳</span>
                                                    </label>
                                                    <input id="file-upload_<?=$fvalue['d_fname']?>" type="file"  name="<?=$fvalue['d_fname']?>"/>
                                                </div>
                                            <? endif;?>
                                            <? if(!empty($dbdata[$fvalue['d_fname']])):?>
                                                <a href="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" target="_BALNK">
                                                <img src="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" width="10%" >
                                                </a>
                                                <div class="toolbar fileBar">
                                                    <a href="javascript:void(0)" id="DownPic" rel="d_title" rid="<?=$fvalue['d_fname']?>"  class="toolbar-btn enable file-btn">下載</a>
                                                    <?if($this->autoful->EditView==2):?>
                                                        <a href="javascript:void(0)" id="DelPic" rel="d_title" rid="<?=$fvalue['d_fname']?>" class="toolbar-btn disable file-btn">刪除</a>
                                                    <? endif;?>

                                                </div>
                                                <input type="hidden" name="<?=$fvalue['d_fname'].'_ImgHidden'?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>"/>
                                            <? endif;?>  
                                        </div>
                                        <? if(!empty($fvalue['d_content'])):?>
                                            <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                                        <?endif;?>
                                    </li>
                                <?endif;endforeach;?>
                     
                            </ul>
                        </div>
                        <?endif;?>
                    </div>
            </div>
            <div class="search-btn">
                <input type="hidden" name="dbname" value="web_config">
                <?if($this->autoful->EditView==2):?>
                    <input type="submit" name="" value="送出" class="inquire">
                <?endif;?>
            </div>
        </div>
    </div>
    </form>
    </div>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
<script>
// 下載圖片
$('a[id="DownPic"]').click(function(){
window.location.href=$('#FileName').attr('fval')+'/AConfig/AConfig/DownPic/web_config/'+$(this).attr('rid')+'/'+$(this).attr('rel');
});
// 刪除圖片
$('a[id="DelPic"]').click(function(){
if(confirm('確定刪除此圖片?')){
  $.ajax({
    url:$('#FileName').attr('fval')+'/AConfig/AConfig/DelPic',
    type:'POST',
    data: 'DBname=web_config&Did='+$(this).attr('rid')+'&FiledName='+$(this).attr('rel'),
    dataType: 'text',
    success: function(response){
      if(response=='OK'){
        alert('刪除成功');
        location.reload()
      }
    }
  });
}
});
</script>