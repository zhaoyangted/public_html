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
          <div class="page_block">
              <div class="page_block_title"><?echo (($this->FunctionType=='add')?'新增':'修改').$this->tableful->MenuidDb['d_title']?></div>
              <form action="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_'.$this->FunctionType.'/'.$this->FunctionType);?>" method="post" enctype="multipart/form-data">
              <div class="form-content">
                <div>
                  <ul class="form_wrap">
                <? if(!empty($this->tableful->Menu)):
                    $Disabled=($this->autoful->EditView==3)?'disabled':'';
                    foreach ($this->tableful->Menu as $Fkey=> $fvalue):
                    $Star=(!empty($fvalue['d_search']))?'<span style="color:RED;">*</span>':'';
                ?>

                    <?if($fvalue['d_type']==1):?>
                        <!--Input Text-->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" <? if(!empty($fvalue['InputType'])):?>onkeyup="return <? echo $fvalue['InputType']?>($(this),value)"<? endif;?> <?echo $Disabled;?>>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==2):?>
                        <!--Input Radio -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <?foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                              <input value="<?=$Ckey?>" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname'].$Ckey?>" <? echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]==$Ckey)?'checked':'':($Ckey==1 or $Ckey=='Y')?'checked':'';?> type="radio" <?echo $Disabled;?>><label for="<?=$fvalue['d_fname'].$Ckey?>" style="margin-right: 5px;"><?=$Cvalue?></label>
                            <? endforeach;?>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==3):?>
                        <!--Input checkbox -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <?foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                              <input value="<?=$Ckey?>" name="<?=$fvalue['d_fname']?>[]" id="<?=$fvalue['d_fname'].$Ckey.'_checkbox'?>" <? echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'checked':'':'';?> type="checkbox" <?echo $Disabled;?>><label for="<?=$fvalue['d_fname'].$Ckey.'_checkbox'?>" style="margin-right: 5px;"><?=$Cvalue?></label>
                            <? endforeach;?>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==4):?>
                        <!--Input select -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <select name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" class="form-select" <?echo $Disabled;?>>
                            <option value="">請選擇</option>
                            <? foreach ($fvalue['Config'] as  $Ckey=> $Cvalue):?>
                              <option value="<?=$Ckey?>" <? echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]==$Ckey)?'selected':'':'';?>><?=$Cvalue?></option>
                            <? endforeach;?>
                            </select>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==5):?>
                        <!--Input textarea -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <textarea name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"  class="contentin-table-textarea" style="margin: 0px; width: 322px; height: 137px;" <?echo $Disabled;?>><?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?></textarea>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==6):$this->useful->CKediter($this->DBname);?>
                        <!--Input ckediter -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <textarea name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"  class="contentin-table-textarea" <?echo $Disabled;?>><?php echo !empty($dbdata[$fvalue['d_fname']])?stripslashes($dbdata[$fvalue['d_fname']]):'';?></textarea>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==7):?>
                        <!--Input view -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <?php echo (!empty($fvalue['Config'])?$fvalue['Config'][$dbdata[$fvalue['d_fname']]]:(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:''));?>
                            <?php //特殊處理 補寄驗證信 ?>
                            <?if($fvalue['d_fname']=='d_chked' && $dbdata[$fvalue['d_fname']]==4):?>
                            <div class="search-btn">
                              <?php if (!empty($_SESSION[CCODE::ADMIN]['ReSendVri'.$d_id]) && $_SESSION[CCODE::ADMIN]['ReSendVri'.$d_id]): ?>
                                <input type="button" value="已重發驗證信，需待5分鐘後可再寄發一次" class="inquire" style="margin-left: 30px;background:#4dc138;" <?echo $Disabled;?>>
                              <?php else: ?>
                                <input type="button" value="重發驗證信" class="inquire" onclick="ReSendVri(this);" style="margin-left: 30px;background:#4dc138;" <?echo $Disabled;?>>
                              <?php endif; ?>
                            </div>
                            <?endif;?>
                            <?php //特殊處理 補寄驗證信 ?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==8):?>
                        <!--Input Imgfile -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div>
                                <div class="file_upload_btn">
                                    <label for="file-upload_<?echo $fvalue['d_fname'];?>" class="custom-file-upload">
                                        <img class="" src="<?=CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                        <span class="upload">檔案上傳</span>
                                    </label>
                                    <input id="file-upload_<?echo $fvalue['d_fname'];?>" type="file" name="<?=$fvalue['d_fname']?>"/>
                                </div>
                                <? if(!empty($dbdata[$fvalue['d_fname']])):?>
                                    <a href="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" target="_BALNK">
                                    <img src="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" width="10%" >
                                    </a>
                                    <div class="toolbar fileBar">
                                        <a href="javascript:void(0)" id="DownPic" rel="<?=$fvalue['d_fname']?>" class="toolbar-btn enable file-btn">下載</a>
                                        <a href="javascript:void(0)" id="DelPic" rel="<?=$fvalue['d_fname']?>" class="toolbar-btn disable file-btn">刪除</a>
                                    </div>
                                    <input type="hidden" name="<?=$fvalue['d_fname'].'_ImgHidden'?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>"/>
                                <? endif;?>
                            </div>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==9):?>
                        <!--Input hidden -->
                        <input type="hidden" name="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>">
                    <?endif;?>
                    <?if($fvalue['d_type']==10):?>
                        <!--Input date -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]!='0000-00-00')?date("Y-m-d", strtotime($dbdata[$fvalue['d_fname']])):'0000-00-00':'';?>" class="form-control" <?echo $Disabled;?>>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==11):?>
                        <!--Input time -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==12):?>
                        <!--Input 日期時間 -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==13):?>
                        <!--address -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div id="<?=$fvalue['d_fname']?>"></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>"  value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==14):?>
                        <!--File -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div>
                                <div class="file_upload_btn">
                                    <label for="file-upload_<?echo $fvalue['d_fname'];?>" class="custom-file-upload">
                                        <img class="" src="<?=CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                        <span class="upload">檔案上傳</span>
                                    </label>
                                    <input id="file-upload_<?echo $fvalue['d_fname'];?>" type="file" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"/>
                                </div>
                                <? if(!empty($dbdata[$fvalue['d_fname']])):?>
                                  <? echo '目前檔案:'.$dbdata[$fvalue['d_fname']];//目前檔案?>
                                <? endif;?>
                                <input type="hidden" name="<?=$fvalue['d_fname'].'_Hidden'?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>"/>
                            </div>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==15):?>
                        <!--Password-->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="password" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" <?echo $Disabled;?>>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==16):?>
                        <!--只有城市地區 -->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div id="<?=$fvalue['d_fname']?>"></div>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==17):?>
                        <!--搜尋下拉-->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <select name="<?=$fvalue['d_fname']?>[]" id="<?=$fvalue['d_fname']?>" multiple class="form-select">
                                <? foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                                  <option value="<?=$Ckey?>" <? echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'selected':'':'';?>><?=$Cvalue?></option>
                                <? endforeach;?>
                            </select>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==18):?>
                        <!--數字欄位-->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <input type="number" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" min="0" value="<? echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:0;?>" style="width: 100px;" <?echo $Disabled;?>>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==19):?>
                        <!--顏色欄位-->
                        <li id="<?=$fvalue['d_fname'].'_li'?>">
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <input type="color" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<? echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'#ff0000';?>" style="width: 100px;height:50px;">
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==20):?>
                        <!--DIV標籤-->
                        <div class="page_block_title" id="<?=$Fkey.'_li'?>"><?=$Star.$fvalue['d_title']?></div>
                    <?endif;?>
                    <?endforeach;endif;?>
                    <?if(!empty($LastOrder)):?>
                        <li>
                            <div class="form_wrap_title">最後一次交易時間與訂單號</div>
                            <?php echo$LastOrder?>
                        </li>
                    <?endif;?>
                    <?if($this->tableful->MenuidDb['d_oc']=='Y'):?>
                        <li>
                            <div class="form_wrap_title">狀態</div>
                            <select name="d_enable" id="d_enable" class="form-select" <?echo $Disabled;?>>
                                <option value="Y" <? echo !empty($dbdata['d_enable'])?($dbdata['d_enable']=='Y')?'selected':'':'';?>>啟動</option>
                                <option value="N" <? echo !empty($dbdata['d_enable'])?($dbdata['d_enable']=='N')?'selected':'':'';?>>關閉</option>
                            </select>
                        </li>
                    <?endif;?>
                  </ul>
                </div>
            </div>
          </div>
          <div class="search-btn">
              <input type="hidden" name="BackPageid" value="<?=$_SESSION[CCODE::ADMIN]['PageNum1']?>">
              <input type="hidden" name="dbname" id="dbname" value="<?=$this->DBname?>">
              <input type="hidden" name="d_id" id="d_id" value="<? echo !empty($d_id)?$d_id:''?>">
              <input type="button" value="回上頁" class="other" onclick="javascript:window.location.href='<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname);?>'">
                <?if($this->autoful->EditView==2):?>
                  <input type="submit" name="" value="送出" class="inquire">
                  <?if($this->FunctionType=='edit'):?>
                    <input type="button" value="自建訂單" class="inquire" onclick="LoginMember(<?echo $d_id?>);" style="margin-left: 150px;background:#4dc138;">
                  <?endif;?>
                <?endif;?>

          </div>
      </form>
  </div>
</div>

<!-- Ckediter -->
<script src="<? echo CCODE::DemoPrefix.'/js/myjava/ckeditor/ckeditor.js';?>"></script>
<script src="<? echo CCODE::DemoPrefix.'/js/myjava/ckeditor.js';?>"></script>
<!-- DateMaker -->
<script src="<? echo CCODE::DemoPrefix.('/js/myjava/datepicker/jquery.datetimepicker.full.js');?>"></script>
<link type="text/css" rel="stylesheet" href="<? echo CCODE::DemoPrefix.('/js/myjava/datepicker/jquery.datetimepicker.css');?>">
<script src="<? echo CCODE::DemoPrefix.('/js/myjava/datepicker/usedate.js');?>"></script>
<!-- Address -->
<script src="<? echo CCODE::DemoPrefix.('/js/myjava/jquery.twzipcode.js');?>"></script>
<!-- Select2 -->
<link type="text/css" rel="stylesheet" href="<? echo CCODE::DemoPrefix.('/js/myjava/select2/css/select2.min.css');?>">
<script src="<? echo CCODE::DemoPrefix.('/js/myjava/select2/js/select2.full.js');?>"></script>

<script>
// 自建訂單
function LoginMember(Mid){
  var w = window.open('');
  $.ajax({
      type: "post",
      url: $('#FileName').attr('fval')+'/member/member/Mlogin',
      data: {
          Mid:Mid
      },
      dataType :'text',
      cache: false,
      success: function (response) {
        if(response=='OK'){
           w.location = "<?echo site_url('index')?>";
        }
      }
  });
}

function ReSendVri(elem) {
  $.ajax({
      type: "post",
      url: $('#FileName').attr('fval')+'/member/member_edit/ReSendVri',
      data: {
          Mid:<?echo $d_id?>
      },
      dataType :'text',
      cache: false,
      success: function (response) {
        if(response=='OK'){
          elem.value = "已重發驗證信，需待5分鐘後可再寄發一次";
          elem.disabled = true;
          setTimeout(function() {
              elem.disabled = false;
          }, 300000);
        }
      }
  });
}

// 顯示部分資訊
// DisTable($('#d_user_type option:selected').val());
$('#d_user_type').change(function(){
    id=$(this).val();
    // console.log(id);
    // DisTable(id);
});

function DisTable(type){
    if(type==1){
        $('<?echo $DisArray?>'+',#28_li,#16_li').hide();
    }else{
        $('<?echo $DisArray?>'+',#28_li,#16_li').show();
    }
}
<? if(!empty($this->tableful->Menu)):foreach ($this->tableful->Menu as $fvalue):?>
    <?if($fvalue['d_type']==6):?>
        CKEDITOR.replace(<?=$fvalue['d_fname']?>,config);
    <?endif;?>
    <?if($fvalue['d_type']==10):?>
        $("#<?=$fvalue['d_fname']?>").datetimepicker(DateOnly);
    <?endif;?>
    <?if($fvalue['d_type']==11):?>
        $("#<?=$fvalue['d_fname']?>").datetimepicker(Time);
    <?endif;?>
    <?if($fvalue['d_type']==12):?>
        $("#<?=$fvalue['d_fname']?>").datetimepicker(Datetime);
    <?endif;?>
    <?if($fvalue['d_type']==13):?>
        $("#<?=$fvalue['d_fname']?>").twzipcode({
            css: ["city form-select", "town form-select", "zip form-select"], // 自訂 "城市"、"地區" class 名稱
            countyName: "<?echo $fvalue['d_fname'].'_city';?>", // 自訂城市 select 標籤的 name 值
            districtName: "<?echo $fvalue['d_fname'].'_area';?>", // 自訂地區 select 標籤的 name 值
            zipcodeName: "<?echo $fvalue['d_fname'].'_zip';?>" // 自訂ZIP select 標籤的 name 值
        });
    <?endif;?>
    <?if($fvalue['d_type']==16):?>
        $("#<?=$fvalue['d_fname']?>").twzipcode({
            css: ["city form-select", "town form-select", "zip form-select"], // 自訂 "城市"、"地區" class 名稱
            countyName: "<?echo $fvalue['d_fname'].'_city';?>", // 自訂城市 select 標籤的 name 值
            districtName: "<?echo $fvalue['d_fname'].'_area';?>", // 自訂地區 select 標籤的 name 值
            zipcodeName: "<?echo $fvalue['d_fname'].'_zip';?>" // 自訂ZIP select 標籤的 name 值
        });
    <?endif;?>
    <?if($fvalue['d_type']==17):?>
        $("#<?=$fvalue['d_fname']?>").select2({width:'1000px'});
    <?endif;?>
<?endforeach;endif;?>
</script>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
