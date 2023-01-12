<?php include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<script src='<?php echo CCODE::DemoPrefix.('/js/myjava/Config.js');?>'></script>
<script src='<?php echo CCODE::DemoPrefix.('/js/myjava/Checkinput.js');?>'></script>

<link rel="stylesheet" type="text/css" href="<?php echo CCODE::DemoPrefix.('/css/backend/toolbar-btn.css')?>" />
<form action="<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_'.$this->FunctionType.'/'.$this->FunctionType);?>" method="post" enctype="multipart/form-data">
  <div class="indexView">
      <div class="indexView_block">
          <div class="indexView_title">
              <div class="line l-blue"></div>
              <i class="<?php echo $this->tableful->MenuidDb['d_icon']?>"></i>
              <sapn class="top-title"><?php echo $this->tableful->MenuidDb['d_title'];?></sapn>
          </div>
          <?php $Disabled=($this->autoful->EditView==3)?'disabled':'';?>
          <div class="page_block">
            <?php if($this->autoful->EditView==2):?>
              <?php if ($dbdata['reOrderShow']): ?>
                <div class="search-btn">
                  <?php if (!empty($dbdata['RID'])): ?>
                    <input id="Rbtn" type="button" value="查看銷退單" class="inquire" onclick="location.href='<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/orders_return/orders_return_edit/index/'.$dbdata['d_id']) ?>'" style="background:#4dc138;">
                  <?php else: ?>
                    <input id="Rbtn" type="button" value="生成銷退單" class="inquire" onclick="create_return();" style="background:#4dc138;">
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
                <!-- 一般產品 -->
                <div class="page_block_title">產品細項</div>
                <div style="overflow-x:scroll;">
                      <table width="100%" border="1">
                      <tr bgcolor="eeeeee">
                        <td align="center"> <input type="checkbox" id="chkall"></td>
                        <td align="center"> 項次</td>
                        <td align="center"> 品名</td>
                        <td align="center"> 貨品編號 </td>
                        <td align="center"> 單價 </td>
                        <td align="center"> 數量 </td>
                        <td align="center"> 金額小計 </td>
                        <td align="center"> 功能 </td>
                        <td align="center"> 運費等級 </td>
                        <td align="center"> 大型運費 </td>
                        <td align="center"> 出貨日期 </td>
                        <td align="center"> 到貨日期 </td>
                        <td align="center"> 物流單號 </td>
                        <td align="center"> 物流商 </td>
                      </tr>
                      <?php foreach($oddata as $akey=> $avalue):?>
                          <tr>
                            <td ><input type="checkbox" name="odckb[]" value="1"></td>
                            <td ><?php echo $akey+1;?></td>
                            <td ><?php echo $avalue['d_title']; ?></td>
                            <td ><?php echo $avalue['d_model']; ?></td>
                            <td ><?php echo $avalue['d_price']; ?></td>
                            <td ><?php echo $avalue['d_num']; ?></td>
                            <td ><?php echo $avalue['d_total'];?></td>
                            <td >
                              <select <?php echo $dbdata['d_orderstatus']!=4 && $dbdata['d_orderstatus']!=6 ?'name="Ostatus['.$avalue['d_id'].']"':'disabled'; ?> <?php echo $Disabled;?>>
                                <?php foreach ($Ostatus as $key => $value):?>
                                  <option value="<?php echo $key?>" <?php echo ($key==$avalue['d_status'])?'selected':'';?>><?php echo $value?></option>
                                <?php endforeach;?>
                              </select>
                            </td>
                            <td ><?php echo !empty($Oship_freight[$avalue['d_pfreight_lv']])?$Oship_freight[$avalue['d_pfreight_lv']]:'無資料'; ?></td>
                            <td >
                              <?php if ($dbdata['d_orderstatus']==10 && $avalue['d_pfreight_lv']==7): ?>
                                <input type="text" style="width:50px;" name="Oship_pfreight[<?php echo $avalue['d_id'] ?>]" class="Oship_pfreight" value="<?php echo $avalue['d_pfreight'];?>" <?php echo $Disabled;?>>
                              <?php else: ?>
                                <?php echo $avalue['d_pfreight'];?>
                              <?php endif; ?>
                            </td>
                            <td ><input type="text" onchange="odedit(this)" style="width:100px;" class="Oship_date" <?php echo $dbdata['d_orderstatus']!=4 && $dbdata['d_orderstatus']!=6 ?'name="Oship_date['.$avalue['d_id'].']"':'disabled'; ?> value="<?php echo $avalue['d_ship_date'];?>" <?php echo $Disabled;?>></td>
                            <td ><input type="text" onchange="odedit(this)" style="width:100px;" class="Oarrival_date" <?php echo $dbdata['d_orderstatus']!=4 && $dbdata['d_orderstatus']!=6 ?'name="Oarrival_date['.$avalue['d_id'].']"':'disabled'; ?> value="<?php echo $avalue['d_arrival_date'];?>" <?php echo $Disabled;?>></td>
                            <td ><input type="text" oninput="odedit(this)" <?php echo $dbdata['d_orderstatus']!=4 && $dbdata['d_orderstatus']!=6 ?'name="Oship_number['.$avalue['d_id'].']"':'disabled'; ?> class="Oship_number" value="<?php echo $avalue['d_shipnumber'];?>" <?php echo $Disabled;?>></td>
                            <td >
                              <select <?php echo $dbdata['d_orderstatus']!=4 && $dbdata['d_orderstatus']!=6 ?'name="Oship_company['.$avalue['d_id'].']"':'disabled'; ?> name="Oship_company[<?php echo $avalue['d_id']?>]" onchange="odedit(this)" class="Oship_company" <?php echo $Disabled;?>>
                                <option value="">請選擇</option>
                                <?php foreach ($Oship_company as $key => $value):?>
                                  <option value="<?php echo $value['d_id']?>" <?php echo ($value['d_id']==$avalue['SHID'])?'selected':'';?>><?php echo $value['d_title']?></option>
                                <?php endforeach;?>
                              </select>
                            </td>
                          </tr>
                          <?php endforeach;?>
                    </table>
                </div>
                <!-- 一般產品 -->
                <!-- 加價購 -->
                <?php if(!empty($Adata)):?>
                    <div class="page_block_title">加價購細項</div>
                    <tr>
                      <td width="80%" class="formT_row">
                        <table style="width:99%;" border="1">
                        <tr bgcolor="eeeeee">
                            <td align="center"> 項次</td>
                            <td align="center"> 品名</td>
                            <td align="center"> 加價購金額</td>
                            <!-- <td align="center"> 功能 </td>
                            <td align="center"> 退貨備註 </td> -->
                        </tr>
                        <?php foreach($Adata as $Bkey=>$Bvalue):?>
                            <tr>
                              <td align="center"><?php echo $Bkey+1;?></td>
                              <td align="center"><?php echo $Bvalue['d_title']; ?></td>
                              <td align="center"><?php echo $Bvalue['d_price']; ?></td>
                              <!-- <td align="center">
                                <select name="Bstatus[<?php echo $Bvalue['d_id']?>]">
                                  <?php foreach ($Ostatus as $key => $value):?>
                                    <option value="<?php echo $key?>" <?php echo ($key==$Bvalue['d_status'])?'selected':'';?>><?php echo $value?></option>
                                  <?php endforeach;?>
                                </select>
                              </td>
                              <td align="center"><?php echo ($Bvalue['d_content']);?></td> -->
                            </tr>
                            <?php endforeach;?>
                      </table>
                      </td>
                    </tr>
                <?php endif;?>
                <!-- 加價購 -->
                <!-- 試用品 -->
                <?php if(!empty($Tdata)):?>
                    <div class="page_block_title">試用品細項</div>
                    <tr>
                      <td width="80%" class="formT_row">
                        <table style="width:99%;" border="1">
                        <tr bgcolor="eeeeee">
                            <td align="center"> 項次</td>
                            <td align="center"> 品名</td>
                            <td align="center"> 貨品編號</td>
                        </tr>
                        <?php foreach($Tdata as $Tkey=>$Tvalue):?>
                            <tr>
                              <td align="center"><?php echo $Tkey+1;?></td>
                              <td align="center"><?php echo $Tvalue['d_title']; ?></td>
                              <td align="center"><?php echo $Tvalue['d_model']; ?></td>
                            </tr>
                            <?php endforeach;?>
                      </table>
                      </td>
                    </tr>
                <?php endif;?>
                <!-- 試用品 -->
                <!-- 贈品 -->
                <?php if(!empty($Gdata)):?>
                    <div class="page_block_title">贈品細項</div>
                    <tr>
                      <td width="80%" class="formT_row">
                        <table style="width:99%;" border="1">
                        <tr bgcolor="eeeeee">
                          <td align="center"> 項次</td>
                          <!-- <td align="center"> 圖片 </td> -->
                          <td align="center"> 品名</td>
                        </tr>
                        <?php foreach($Gdata as $Gkey=>$Gvalue):?>
                            <tr>
                              <td align="center"><?php echo $Gkey+1;?></td>
                              <!-- <td align="center"><img src="<?php echo CCODE::DemoPrefix.'/'.$Gvalue['d_img']?>" width="10%" ></td> -->
                              <td align="center"><?php echo $Gvalue['d_title']; ?></td>
                            </tr>
                            <?php endforeach;?>
                      </table>
                      </td>
                    </tr>
                <?php endif;?>
                <!-- 贈品 -->

              <div class="page_block_title"><?php echo (($this->FunctionType=='add')?'新增':'修改').$this->tableful->MenuidDb['d_title']?></div>

              <div class="form-content">
                  <ul class="form_wrap">
                <?php if(!empty($this->tableful->Menu)):foreach ($this->tableful->Menu as $fvalue)://p($fvalue);
                    $Star=(!empty($fvalue['d_search']))?'<span style="color:RED;">*</span>':'';
                ?>

                    <?php if($fvalue['d_type']==1):?>
                        <!--Input Text-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <input type="text" class="form-control" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" <?php if(!empty($fvalue['InputType'])):?>onkeyup="return <?php echo $fvalue['InputType']?>($(this),value)"<?php endif;?>>
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
                              <input value="<?php echo $Ckey?>" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname'].$Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?($dbdata[$fvalue['d_fname']]==$Ckey)?'checked':'':($Ckey==1 or $Ckey=='Y')?'checked':'';?> type="radio"><label for="<?php echo $fvalue['d_fname'].$Ckey?>" style="margin-right: 5px;"><?php echo $Cvalue?></label>
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
                              <input value="<?php echo $Ckey?>" name="<?php echo $fvalue['d_fname']?>[]" id="<?php echo $fvalue['d_fname'].$Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'checked':'':'';?> type="checkbox"><label for="<?php echo $fvalue['d_fname'].$Ckey?>" style="margin-right: 5px;"><?php echo $Cvalue?></label>
                            <?php endforeach;?>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==4):?>
                        <?php 
							// 報價處理中或報價完成時 不能修改付款狀態
							$dis = '';
							if($fvalue['d_fname'] == 'd_paystatus' &&  ($dbdata['d_orderstatus'] == 10 || $dbdata['d_orderstatus'] == 11) ) {
								$dis = 'disabled';
							}
						?>
						
						<!--Input select -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <select name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" class="form-select" <?php echo $Disabled;?> <?php echo $dis;?> >
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
                            <textarea name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>"  class="contentin-table-textarea" ><?php echo !empty($dbdata[$fvalue['d_fname']])?stripslashes($dbdata[$fvalue['d_fname']]):'';?></textarea>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==7):?>
                        <!--Input view -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <?php echo (!empty($fvalue['Config'])?(!empty($fvalue['Config'][$dbdata[$fvalue['d_fname']]])?$fvalue['Config'][$dbdata[$fvalue['d_fname']]]:'無資料'):(isset($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:''));?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==8):?>
                        <!--Input Imgfile -->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div>
                            <div>
                                <div class="file_upload_btn">
                                    <label for="file-upload_<?php echo $fvalue['d_fname'];?>" class="custom-file-upload">
                                        <img class="" src="<?php echo CCODE::DemoPrefix.'/images/backend/ico_upload.png'?>">
                                        <span class="upload">檔案上傳</span>
                                    </label>
                                    <input id="file-upload_<?php echo $fvalue['d_fname'];?>" type="file" name="<?php echo $fvalue['d_fname']?>"/>
                                </div>
                                <?php if(!empty($dbdata[$fvalue['d_fname']])):?>
                                    <a href="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" target="_BALNK">
                                    <img src="<?php echo CCODE::DemoPrefix.'/'.(!empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'');?>" width="10%" >
                                    </a>
                                    <div class="toolbar fileBar">
                                        <a href="javascript:void(0)" id="DownPic" rel="<?php echo $fvalue['d_fname']?>" class="toolbar-btn enable file-btn">下載</a>
                                        <a href="javascript:void(0)" id="DelPic" rel="<?php echo $fvalue['d_fname']?>" class="toolbar-btn disable file-btn">刪除</a>
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
                            <input type="text" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>" class="form-control">
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
                                    <input id="file-upload_<?php echo $fvalue['d_fname'];?>" type="file" name="<?php echo $fvalue['d_fname']?>" />
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
                            <select name="<?php echo $fvalue['d_fname']?>[]" id="<?php echo $fvalue['d_fname']?>" multiple class="form-select">
                                <?php foreach ($fvalue['Config'] as $Ckey=> $Cvalue):?>
                                  <option value="<?php echo $Ckey?>" <?php echo !empty($dbdata[$fvalue['d_fname']])?(in_array($Ckey,explode('@#',$dbdata[$fvalue['d_fname']])))?'selected':'':'';?>><?php echo $Cvalue?></option>
                                <?php endforeach;?>
                            </select>
                            <?php if(!empty($fvalue['d_content'])):?>
                                <span class="form-remark"><?php echo $fvalue['d_content'];?></span>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                    <?php if($fvalue['d_type']==18):?>
                        <!--數字欄位-->
                        <li>
                            <div class="form_wrap_title"><?php echo $Star.$fvalue['d_title']?></div><br>
                            <input type="number" class="form-control" name="<?php echo $fvalue['d_fname']?>" id="<?php echo $fvalue['d_fname']?>" min="0" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:0;?>" style="width: 100px;">
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

                    <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
                        <li>
                            <div class="form_wrap_title">狀態</div>
                            <select name="d_enable" id="d_enable" class="form-select">
                                <option value="Y" <?php echo !empty($dbdata['d_enable'])?($dbdata['d_enable']=='Y')?'selected':'':'';?>>啟動</option>
                                <option value="N" <?php echo !empty($dbdata['d_enable'])?($dbdata['d_enable']=='N')?'selected':'':'';?>>關閉</option>
                            </select>
                        </li>
                    <?php endif;?>
                  </ul>
              </div>
          </div>
          <div class="search-btn">
              <input type="hidden" name="BackPageid" value="<?php echo $_SESSION[CCODE::ADMIN]['PageNum1']?>">
              <input type="hidden" name="dbname" id="dbname" value="<?php echo $this->DBname?>">
              <input type="hidden" name="d_id" id="d_id" value="<?php echo !empty($d_id)?$d_id:''?>">
              <input type="button" value="回上頁" class="other" onclick="javascript:window.location.href='<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname);?>'">
              <?php if($this->autoful->EditView==2):?>
                <input type="submit" name="" value="送出" class="inquire">
              <?php endif;?>
          </div>
      </div>
  </div>
</form>
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
$('.Oship_date').datetimepicker(DateOnly);
$('.Oarrival_date').datetimepicker(DateOnly);
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

  function odedit(elem) {
    if ($(elem).closest('tr').find('input[name="odckb[]"]:checked').val()==1) {
      val = $(elem).val();
      name = $(elem).attr('class');
      $('input[name="odckb[]"]:checked').each(function(){
        $(this).closest('tr').find('.'+name).val(val);
      });
    }
  }

  $('#chkall').click(function() {
    if ($(this).is(":checked")) {
      $('input[name="odckb[]"]').prop('checked',true);
    }else{
      $('input[name="odckb[]"]').prop('checked',false);
    }
  });

  function create_return() {
    $.ajax({
        type: "post",
        url: $('#FileName').attr('fval')+'/orders/orders_edit/createRetrun',
        data: {
            id:<?php echo $d_id?>
        },
        dataType :'text',
        cache: false,
        success: function (response) {
          if(response=='OK'){
            $('#Rbtn').val('查看銷退單');
            $('#Rbtn').attr('onclick','location.href="'+$('#FileName').attr('fval')+'/orders_return/orders_return_edit/index/'+<?php echo $d_id?>+'"');
            alert('生成銷貨單成功！');
          }else{
            alert('生成銷貨單失敗，請重整後再試！');
          }
        }
    });
  }
  
  
	$('form').submit(function() {
		 $("#d_paystatus").removeAttr("disabled");
		 return true;
	});
</script>
<?php include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
