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
                <div class="page_block_title"><?echo (($this->FunctionType=='add')?'新增':'修改').$this->tableful->MenuidDb['d_title']?></div>
               <form action="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_'.$this->FunctionType.'/'.$this->FunctionType);?>" method="post" enctype="multipart/form-data">
                <div class="form-content">
                    <div class="row">
                        <ul class=" form_wrap">
                            <li class="col-md-6 col-sm-6">
                                <div class="form_wrap_title">*<?echo $this->FiledList['d_subject']?></div>
                                <?php echo !empty($dbdata['d_subject'])?$SubjectData[$dbdata['d_subject']]:'';?>
                            </li>
                            <li class="col-md-6 col-sm-6">
                                <div class="form_wrap_title">*<?echo $this->FiledList['d_name']?></div>
                                <?php echo !empty($dbdata['d_name'])?$dbdata['d_name']:'';?>
                            </li>
                            <li class="col-md-6 col-sm-6">
                                <div class="form_wrap_title">*<?echo $this->FiledList['d_company']?></div>
                                <?php echo !empty($dbdata['d_company'])?$dbdata['d_company']:'';?>
                            </li>
                            <li class="col-md-6 col-sm-6">
                                <div class="form_wrap_title">*<?echo $this->FiledList['d_country']?></div>
                                <?php echo !empty($dbdata['d_country'])?$CountryData[$dbdata['d_country']]:'';?>
                            </li>
                            <li class="col-md-6 col-sm-6">
                                <div class="form_wrap_title">*<?echo $this->FiledList['d_mail']?></div>
                                <?php echo !empty($dbdata['d_mail'])?$dbdata['d_mail']:'';?>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <ul class=" form_wrap">
                            <li class="col-md-6 col-sm-6">
                                <div class="form_wrap_title">*<?echo $this->FiledList['d_content']?></div>
                                <?php echo !empty($dbdata['d_content'])?$dbdata['d_content']:'';?>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="form_wrap">
                            <li class="">
                                <div class="form_wrap_title"><?echo $this->FiledList['d_reply']?></div>
                                <textarea name="d_reply" id="d_reply"><?php echo !empty($dbdata['d_reply'])?$dbdata['d_reply']:'';?></textarea>
                            </li>
                            <li class="">
                                <div class="form_wrap_title"><?echo $this->FiledList['d_status']?></div>
                                <select name="d_status"  class="form-select">
                                    <?if(!empty($TypeData)):foreach ($TypeData as $key => $value):?>
                                        <option value="<?=$key;?>" <? echo ($dbdata['d_status']==$key)?'selected':'';?>><?=$value;?></option>
                                    <?endforeach;endif;?>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="search-btn">
                <input type="hidden" name="dbname" id="dbname" value="<?=$this->DBname?>">
                <input type="hidden" name="d_id" id="d_id" value="<? echo !empty($d_id)?$d_id:''?>">
                <input type="button" value="回上頁" class="other" onclick="javascript:window.location.href='<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname);?>'">
                <input type="submit" name="" value="送出" class="inquire">
            </div>
        </div>
        </form>
    </div>
    </div>
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/myjava/ckeditor/ckeditor.js');?>"></script> 
<script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/myjava/ckeditor.js');?>"></script>
<script>
CKEDITOR.replace("d_reply",config);
</script>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
