<?php include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<style>
  .select2-container--default .select2-results__option[aria-disabled=true] { display: none;}
</style>
<script src='<?php echo CCODE::DemoPrefix.('/js/myjava/Config.js');?>'></script>
<script src='<?php echo CCODE::DemoPrefix.('/js/myjava/Checkinput.js');?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo CCODE::DemoPrefix.('/css/backend/toolbar-btn.css')?>" />
  <div class="indexView">
      <div class="indexView_block">
          <div class="indexView_title">
              <div class="line l-blue"></div>
              <i class="<?php echo $this->tableful->MenuidDb['d_icon']?>"></i>
              <sapn class="top-title"><?php echo $this->tableful->MenuidDb['d_title']?></sapn>
          </div>
          <div class="page_block">
              <div class="page_block_title"><?php echo (($this->FunctionType=='add')?'新增':'修改').$this->tableful->MenuidDb['d_title']?></div>
              <form action="<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_'.$this->FunctionType.'/'.$this->FunctionType);?>" method="post" enctype="multipart/form-data">
              <div class="form-content">
                <div>
                  <ul class="form_wrap">
                <?php if(!empty($this->tableful->Menu)):
                    $Disabled=($this->autoful->EditView==3)?'disabled':'';
                    foreach ($this->tableful->Menu as $fvalue):
                    $Star=(!empty($fvalue['d_search']))?'<span style="color:RED;">*</span>':'';
                ?>

                    <?php if($fvalue['d_type']==1):?>
                        <!--Input Text-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="text" class="form-control" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" <?php if(!empty($fvalue['InputType'])):?>onkeyup="return <?php echo $fvalue['InputType']?>($(this),value)"<?php endif;?> <?php echo $Disabled;?>>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==2):?>
                        <!--Input Radio -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <?php foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                              <input value="<?php echo $Ckey?>" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname'].$Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]==$Ckey)?'checked':'':($Ckey==1 or $Ckey=='Y')?'checked':'';?> type="radio" <?php echo $Disabled;?>><label for="<?php echo $fvalue['d_fname'].$Ckey?>"  style="margin-right: 5px;"><?php echo $Cvalue?></label>
                            <?php endforeach;?>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==3):?>
                        <!--Input checkbox -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <?php foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                              <input value="<?php echo $Ckey?>" name="<?php echo $fvalue['d_fname']?>[]" id="<?php echo $fvalue['d_fname'].$Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'checked':'':'';?> type="checkbox" <?php echo $Disabled;?>><label for="<?php echo $fvalue['d_fname'].$Ckey?>" style="margin-right: 5px;"><?php echo $Cvalue?></label>
                            <?php endforeach;?>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==4):?>
                        <!--Input select -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <select name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" class="form-select" <?php echo $Disabled;?>>
                            <option value="">請選擇</option>
                            <?php foreach ($fvalue['Config'] as  $Ckey=> $Cvalue):?>
                              <option value="<?php echo $Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]==$Ckey)?'selected':'':'';?>><?php echo $Cvalue?></option>
                            <?php endforeach;?>
                            </select>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==5):?>
                        <!--Input textarea -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div><br>
                            <textarea name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>"  class="contentin-table-textarea" style="margin: 0px; width: 322px; height: 137px;" <?php echo $Disabled;?>><?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?></textarea>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==6):$this->useful->CKediter($this->DBname);?>
                        <!--Input ckediter -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div><br>
                            <textarea name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>"  class="contentin-table-textarea" <?php echo $Disabled;?>><?php echo !empty($dbdata[$fvalue['d_fname']])?stripslashes($dbdata[$fvalue['d_fname']]):'';?></textarea>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==7):?>
                        <!--Input view -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <?php echo (!empty($fvalue['Config'])?$fvalue['Config'][$dbdata[$fvalue['d_fname']]]:(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:''));?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==8):?>
                        <!--Input Imgfile -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <div>
                                <?php if($this->autoful->EditView==2):?>
                                    <div class="file_upload_btn">
                                        <label for="file-upload_<?php echo $fvalue['d_fname'];?>" class="custom-file-upload">
                                            <img class="" src="<?php echo CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                            <span class="upload">檔案上傳</span>
                                        </label>
                                        <input id="file-upload_<?php echo $fvalue['d_fname'];?>" type="file" name="<?php echo $fvalue['d_fname']?>"/>
                                    </div>
                                <?php endif;?>

                                <span id="UploadFile_<?php echo $fvalue['d_fname']?>"></span>
                                <?php if(!empty($dbdata[$fvalue['d_fname']])):?>
                                    <a href="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" target="_BALNK">
                                    <img src="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" width="10%" >
                                    </a>
                                    <div class="toolbar fileBar">
                                        <a href="javascript:void(0)" id="DownPic" rel="<?php echo $fvalue['d_fname']?>" class="toolbar-btn enable file-btn">下載</a>
                                        <?php if($this->autoful->EditView==2):?>
                                            <a href="javascript:void(0)" id="DelPic" rel="<?php echo $fvalue['d_fname']?>" class="toolbar-btn disable file-btn">刪除</a>
                                        <?php endif;?>
                                    </div>
                                    <input type="hidden" name="<?php echo $fvalue['d_fname'].'_ImgHidden'?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>"/>
                                <?php endif;?>
                            </div>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==9):?>
                        <!--Input hidden -->
                        <input type="hidden" name="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>">
                    <?php endif;?>
                    <?php if($fvalue['d_type']==10):?>
                        <!--Input date -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?date("Y-m-d", strtotime($dbdata[$fvalue['d_fname']])):'';?>" class="form-control">
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==11):?>
                        <!--Input time -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==12):?>
                        <!--Input 日期時間 -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==13):?>
                        <!--address -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <div id="<?php echo $fvalue['d_fname']?>"></div>
                            <input type="text" name="<?php echo $fvalue['d_fname']?>"  value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==14):?>
                        <!--File -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <div>
                                <div class="file_upload_btn">
                                    <label for="file-upload_<?php echo $fvalue['d_fname'];?>" class="custom-file-upload">
                                        <img class="" src="<?php echo CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                        <span class="upload">檔案上傳</span>
                                    </label>
                                    <input id="file-upload_<?php echo $fvalue['d_fname'];?>" type="file" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>"/>
                                </div>
                                <?php if(!empty($dbdata[$fvalue['d_fname']])):?>
                                  <?php echo '目前檔案:'.$dbdata[$fvalue['d_fname']];//目前檔案?>
                                <?php endif;?>
                                <input type="hidden" name="<?php echo $fvalue['d_fname'].'_Hidden'?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>"/>
                            </div>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==15):?>
                        <!--Password-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="password" class="form-control" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>">
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==16):?>
                        <!--只有城市地區 -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <div id="<?php echo $fvalue['d_fname']?>"></div>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==17):?>
                        <!--搜尋下拉-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <select name="<?php echo $fvalue['d_fname']?>[]" id="<?php echo $fvalue['d_fname']?>" multiple class="form-select" <?php echo $fvalue['d_fname']=='TID'?'onchange="category_select(this)"':'';?> <?php echo $Disabled;?>>
                                <?php foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                                  <option value="<?php echo $Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'selected':'':'';?>><?php echo $Cvalue?></option>
                                <?php endforeach;?>
                            </select>
                            <?php if($fvalue['d_fname']=='TID'):?>
                                <select name="TTID[]" id="TTID" multiple class="form-select" onchange="category_select(this)" <?php echo $Disabled;?>>
                                    <?php foreach ($Subtype as $Ckey=> $Cvalue1):?>
                                      <option data-id="<?php echo $Cvalue1['TID']?>"  value="<?php echo $Cvalue1['d_id']?>" <?php echo !empty($dbdata['TTID'])?(in_array($Cvalue1['d_id'],explode('@#',$dbdata['TTID'])))?'selected':'':'';?>><?php echo $Cvalue1['d_title']?></option>
                                    <?php endforeach;?>
                                </select>
                                <select name="TTTID[]" id="TTTID" multiple class="form-select" <?php echo $Disabled;?>>
                                    <?php foreach ($SubSubtype as $Ckey=> $Cvalue1):?>
                                      <option data-id="<?php echo $Cvalue1['TTID']?>" value="<?php echo $Cvalue1['d_id']?>" <?php echo !empty($dbdata['TTTID'])?(in_array($Cvalue1['d_id'],explode('@#',$dbdata['TTTID'])))?'selected':'':'';?>><?php echo $Cvalue1['d_title']?></option>
                                    <?php endforeach;?>
                                </select>
                            <?php endif;?>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==18):?>
                        <!--數字欄位-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="number" class="form-control" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" min="0" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:0;?>" style="width: 100px;" <?php echo $Disabled;?>>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==19):?>
                        <!--顏色欄位-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div><br>
                            <input type="color" class="form-control" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'#ff0000';?>" style="width: 100px;height:50px;">
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==20):?>
                        <!--DIV標籤-->
                        <div class="page_block_title"><?php echo $Star.$fvalue['d_title']?></div>
                    <?php endif;?>
                    <?php endforeach;endif;?>
                    <?php if($this->FunctionType=='edit'):?>
                        <li>
                            <div class="form_wrap_title">產品Qrcode</div>
                            <div>
                                <a href="<?php echo CCODE::DemoPrefix.'/uploads/qrcode/'.$dbdata['d_model'].'.png'?>" target="_BALNK">
                                <img src="<?php echo CCODE::DemoPrefix.'/uploads/qrcode/'.$dbdata['d_model'].'.png'?>" width="10%" >
                                </a>
                                <div class="toolbar fileBar">
                                    <a href="javascript:void(0)" id="DownQrcode" class="toolbar-btn enable file-btn">下載</a>
                                </div>
                            </div>
                        </li>
                    <?php endif;?>
                    <br>
                    <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
                        <li>
                            <div class="form_wrap_title">狀態</div>
                            <select name="d_enable" id="d_enable" class="form-select" <?php echo $Disabled;?>>
                                <option value="Y" <?php echo !empty($dbdata['d_enable'])?($dbdata['d_enable']=='Y')?'selected':'':'';?>>啟動</option>
                                <option value="N" <?php echo !empty($dbdata['d_enable'])?($dbdata['d_enable']=='N')?'selected':'':'';?>>關閉</option>
                            </select>
                        </li>
                    <?php endif;?>
                  </ul>
                </div>
            </div>
          </div>
          <div class="search-btn">
                <input type="hidden" name="BackPageid" value="<?php echo $_SESSION[CCODE::ADMIN]['PageNum1']?>">
                <input type="hidden" name="dbname" id="dbname" value="<?php echo $this->DBname?>">
                <input type="hidden" name="d_id" id="d_id" value="<?php echo !empty($d_id)?$d_id:''?>">
                <input type="button" value="回上頁" class="other" onclick="javascript:window.location.href='<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname);?>'">
                <?php if($this->autoful->EditView==2):?>
                    <input type="submit" value="送出" class="inquire">
                    <?php if($this->FunctionType=='edit'):?>
                        <input type="button" value="複製並執行下一筆" class="inquire" id="Addnew" style="margin-left: 150px;background:#4dc138;">
                    <?php endif;?>
                <?php endif;?>
          </div>
      </form>
  </div>
</div>

<!-- Ckediter -->
<script src="<?php echo CCODE::DemoPrefix.'/js/myjava/ckeditor/ckeditor.js';?>"></script>
<script src="<?php echo CCODE::DemoPrefix.'/js/myjava/ckeditor.js';?>"></script>
<!-- DateMaker -->
<script src="<?php echo CCODE::DemoPrefix.('/js/myjava/datepicker/jquery.datetimepicker.full.js');?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo CCODE::DemoPrefix.('/js/myjava/datepicker/jquery.datetimepicker.css');?>">
<script src="<?php echo CCODE::DemoPrefix.('/js/myjava/datepicker/usedate.js');?>"></script>
<!-- Address -->
<script src="<?php echo CCODE::DemoPrefix.('/js/myjava/jquery.twzipcode.js');?>"></script>
<!-- Select2 -->
<link type="text/css" rel="stylesheet" href="<?php echo CCODE::DemoPrefix.('/js/myjava/select2/css/select2.min.css');?>">
<script src="<?php echo CCODE::DemoPrefix.('/js/myjava/select2/js/select2.full.js');?>"></script>

<script>

<?php if($this->FunctionType=='edit'):?>
    $('#Addnew').click(function(){
        id=$("#d_id").val();
        // console.log($("#d_id").val());
        $.ajax({
            type: "post",
            url: $('#FileName').attr('fval')+'/products/products_edit/CopyProducts',
            data: {
                d_id:id
            },
            dataType :'json',
            cache: false,
            success: function (response) {
                if(response.status=='OK'){
                    // console.log(response);
                    alert('複製完成，前往下一筆');
                    window.location.href=""+$('#FileName').attr('fval')+"/products/products_edit/index/"+response.Newid+"";
                }
            }
        });
    });
    $('a[id="DownQrcode"]').click(function(){
        window.location.href=$('#FileName').attr('fval')+'/products/products_edit/DownQrcode/<?php echo !empty($d_id)?$d_id:''?>';
    });
<?php endif;?>
$("input[id^='file-upload_']").change(function () {
    inputid=$(this).attr('name');
    $('#UploadFile_'+inputid).html($(this)[0].files[0].name);
});
// 商品加購區
$('#AddBuy').click(function(){
    Title=$('#Buytitle').val();
    Money=$('#Buymoney').val();
    PID=$(this).attr('rel');
    if(Title!='' && Money!=''){
        $.ajax({
            type: "post",
            url: $('#FileName').attr('fval')+'/products/products_edit/AddProducts',
            data: {
                d_title:Title,
                d_price:Money,
                PID:PID
            },
            dataType :'text',
            cache: false,
            success: function (response) {
                if(response=='OK'){
                    alert('新增完成');
                    location.reload();
                }
            }
        });
    }else{
        alert('標題或金額尚未填寫');
        return '';
    }
});
$('input[id="EditBuy"]').click(function(){
    d_id=$(this).attr('rel');
    Title=$('#EditBuytitle_'+d_id).val();
    Money=$('#EditBuymoney_'+d_id).val();

    if(Title!='' && Money!=''){
        $.ajax({
            type: "post",
            url: $('#FileName').attr('fval')+'/products/products_edit/EditProducts',
            data: {
                d_title:Title,
                d_price:Money,
                d_id:d_id
            },
            dataType :'text',
            cache: false,
            success: function (response) {
                if(response=='OK'){
                    alert('修改完成');
                    location.reload();
                }
            }
        });
    }else{
        alert('標題或金額尚未填寫');
        return '';
    }
});
$('input[id="DelBuy"]').click(function(){
    d_id=$(this).attr('rel');
    if(confirm('確定刪除?')){
        $.ajax({
            type: "post",
            url: $('#FileName').attr('fval')+'/products/products_edit/DelProducts',
            data: {
                d_id:d_id
            },
            dataType :'text',
            cache: false,
            success: function (response) {
                if(response=='OK'){
                    alert('刪除成功');
                    location.reload();
                }
            }
        });
    }
});

<?php if(!empty($this->tableful->Menu)):foreach ($this->tableful->Menu as $fvalue):?>
    <?php if($fvalue['d_type']==6):?>
        CKEDITOR.replace(<?php echo $fvalue['d_fname']?>,config);
    <?php endif;?>
    <?php if($fvalue['d_type']==10):?>
        $("#<?php echo $fvalue['d_fname']?>").datetimepicker(DateOnly);
    <?php endif;?>
    <?php if($fvalue['d_type']==11):?>
        $("#<?php echo $fvalue['d_fname']?>").datetimepicker(Time);
    <?php endif;?>
    <?php if($fvalue['d_type']==12):?>
        $("#<?php echo $fvalue['d_fname']?>").datetimepicker(Datetime);
    <?php endif;?>
    <?php if($fvalue['d_type']==13):?>
        $("#<?php echo $fvalue['d_fname']?>").twzipcode({
            css: ["city form-select", "town form-select", "zip form-select"], // 自訂 "城市"、"地區" class 名稱
            countyName: "<?php echo $fvalue['d_fname'].'_city';?>", // 自訂城市 select 標籤的 name 值
            districtName: "<?php echo $fvalue['d_fname'].'_area';?>", // 自訂地區 select 標籤的 name 值
            zipcodeName: "<?php echo $fvalue['d_fname'].'_zip';?>" // 自訂ZIP select 標籤的 name 值
        });
    <?php endif;?>
    <?php if($fvalue['d_type']==16):?>
        $("#<?php echo $fvalue['d_fname']?>").twzipcode({
            css: ["city form-select", "town form-select", "zip form-select"], // 自訂 "城市"、"地區" class 名稱
            countyName: "<?php echo $fvalue['d_fname'].'_city';?>", // 自訂城市 select 標籤的 name 值
            districtName: "<?php echo $fvalue['d_fname'].'_area';?>", // 自訂地區 select 標籤的 name 值
            zipcodeName: "<?php echo $fvalue['d_fname'].'_zip';?>" // 自訂ZIP select 標籤的 name 值
        });
    <?php endif;?>
    <?php if($fvalue['d_type']==17):?>
            $("#<?php echo $fvalue['d_fname']?>").select2({width:'1000px'});
    <?php endif;?>
<?php endforeach;endif;?>

  // TID =>主層, TTID => 次層, TTTID => 次次層
  $("#TTID option,#TTTID option").prop('disabled',true); // TTID TTTID 的 option 隱藏
  $("#TID,#TTID,#TTTID").select2({width:'300px'}); // select2套件實例化
  category_select($('#TID')); // 修改頁面時，需要先載入一次選項
  category_select($('#TTID')); // 修改頁面時，需要先載入一次選項
  
  
  function category_select(elem) { // change時，下一層的option改動
    var first = $(elem); // 當前改變的select
    var second = $(elem).nextAll('select').eq(0); // 下一層的select
    second.find('option').each(function() { // 下一層select option進 each loop
      if(jQuery.inArray($(this).attr('data-id'), first.select2("val")) != -1) { // 如果data-id 有在目前父層選中的選項之中
          $(this).prop('disabled',false); // option出現
      }else{ //if not in array
          $(this).prop('disabled',true); // option隱藏
          $(this).prop('selected',false); // option 取消選中
      }
    });
    second.select2({width:'300px'}).trigger('change'); // refresh option 刷新select2套件
  }

</script>
<?php include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
