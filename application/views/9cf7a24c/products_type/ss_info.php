<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<script src='<? echo CCODE::DemoPrefix.('/js/myjava/Config.js');?>'></script>
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/toolbar-btn.css')?>" />
  <div class="indexView">
      <div class="indexView_block">
          <div class="indexView_title">
              <div class="line l-blue"></div>
              <i class="<?echo $this->tableful->MenuidDb['d_icon']?>"></i>
              <sapn class="top-title"><?echo $this->tableful->MenuidDb['d_title'].'>'.$Uptitle?></sapn>
          </div>
          <div class="page_block">
              <div class="page_block_title"><?echo (($this->FunctionType=='add')?'新增':'修改').$this->tableful->MenuidDb['d_title']?></div>
              <form action="<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_ss_'.$this->FunctionType.'/'.$this->FunctionType);?>" method="post" enctype="multipart/form-data">
              <div class="form-content">
                <div>
                  <ul class="form_wrap">
                <? if(!empty($this->tableful->Menu)):foreach ($this->tableful->Menu as $fvalue):
                    $Star=(!empty($fvalue['d_search']))?'<span style="color:RED;">*</span>':'';
                    $Disabled=($this->autoful->EditView==3)?'disabled':'';
                ?>

                    <?if($fvalue['d_type']==1):?>
                        <!--Input Text-->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" <? if(!empty($fvalue['InputType'])):?>onkeyup="return <? echo $fvalue['InputType']?>($(this),value)"<? endif;?> <?echo $Disabled;?>>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==2):?>
                        <!--Input Radio -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <?foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                              <input value="<?=$Ckey?>" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname'].$Ckey?>" <? echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]==$Ckey)?'checked':'':($Ckey==1 or $Ckey=='Y')?'checked':'';?> type="radio"><label for="<?=$fvalue['d_fname'].$Ckey?>"><?=$Cvalue?></label>
                            <? endforeach;?>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==3):?>
                        <!--Input checkbox -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <?foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                              <input value="<?=$Ckey?>" name="<?=$fvalue['d_fname']?>[]" id="<?=$fvalue['d_fname'].$Ckey?>" <? echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'checked':'':'';?> type="checkbox" <?echo $Disabled;?>><label for="<?=$fvalue['d_fname'].$Ckey?>" style="margin-right: 5px;" ><?=$Cvalue?></label>
                            <? endforeach;?>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==4):?>
                        <!--Input select -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <select name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" class="form-select">
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
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <textarea name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"  class="contentin-table-textarea" style="margin: 0px; width: 322px; height: 137px;"><?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?></textarea>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==6):$this->useful->CKediter($this->DBname);?>
                        <!--Input ckediter -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <textarea name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"  class="contentin-table-textarea"><?php echo !empty($dbdata[$fvalue['d_fname']])?stripslashes($dbdata[$fvalue['d_fname']]):'';?></textarea>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==7):?>
                        <!--Input view -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <?php echo (!empty($fvalue['Config'])?$fvalue['Config'][$dbdata[$fvalue['d_fname']]]:(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:''));?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==8):?>
                        <!--Input Imgfile -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div>
                                <div class="file_upload_btn">
                                    <label for="file-upload" class="custom-file-upload">
                                        <img class="" src="<?=CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                        <span class="upload">檔案上傳</span>
                                    </label>
                                    <input id="file-upload" type="file" name="<?=$fvalue['d_fname']?>"/>
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
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?date("Y-m-d", strtotime($dbdata[$fvalue['d_fname']])):'';?>" class="form-control">
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==11):?>
                        <!--Input time -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==12):?>
                        <!--Input 日期時間 -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="text" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==13):?>
                        <!--address -->
                        <li>
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
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div>
                                <div class="file_upload_btn">
                                    <label for="file-upload" class="custom-file-upload">
                                        <img class="" src="<?=CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                        <span class="upload">檔案上傳</span>
                                    </label>
                                    <input id="file-upload" type="file" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>"/>
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
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <input type="password" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>">
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==16):?>
                        <!--只有城市地區 -->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                            <div id="<?=$fvalue['d_fname']?>"></div>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==17):?>
                        <!--搜尋下拉-->
                        <li>
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
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <input type="number" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" min="0" value="<? echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:0;?>" style="width: 100px;" <?echo $Disabled;?>>
                            <? if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><? echo $fvalue['d_content'];?></span>
                            <?endif;?>
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==19):?>
                        <!--顏色欄位-->
                        <li>
                            <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div><br>
                            <input type="color" class="form-control" name="<?=$fvalue['d_fname']?>" id="<?=$fvalue['d_fname']?>" value="<? echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'#ff0000';?>" style="width: 100px;height:50px;">
                        </li>
                    <?endif;?>
                    <?if($fvalue['d_type']==20):?>
                        <!--DIV標籤-->
                        <div class="page_block_title"><?=$Star.$fvalue['d_title']?></div>
                    <?endif;?>
                    <?endforeach;endif;?>
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
              <input type="button" value="回上頁" class="other" onclick="javascript:window.location.href='<?echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_ss/index/'.$dbdata['TID'].'/'.$dbdata['TTID']);?>'">
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
<? if(!empty($this->tableful->Menu)):foreach ($this->tableful->Menu as $fvalue):?>
    <?if($fvalue['d_type']==6):?>
        CKEDITOR.replace(<?=$fvalue['d_fname']?>,config);
    <?endif;?>
    <?if($fvalue['d_type']==10):?>
        $("#<?=$fvalue['d_fname']?>").datetimepicker({
          timepicker: false,
          format:'Y-m-d',
          scrollMonth : false
        });
    <?endif;?>
    <?if($fvalue['d_type']==11):?>
        $("#<?=$fvalue['d_fname']?>").datetimepicker({
          datepicker: false,
          format:'H:i:s',
          scrollMonth : false
        });
    <?endif;?>
    <?if($fvalue['d_type']==12):?>
        $("#<?=$fvalue['d_fname']?>").datetimepicker({
          format:'Y-m-d H:i:s',
          scrollMonth : false
        });
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
