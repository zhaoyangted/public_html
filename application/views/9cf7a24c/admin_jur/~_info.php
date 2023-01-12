<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<script src='<? echo CCODE::DemoPrefix.('/js/myjava/Config.js');?>'></script>
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
                    <? if(!empty($this->tableful->Menu)):
                          foreach ($this->tableful->Menu as $fvalue):
                            $Star=(!empty($fvalue['d_search']))?'<span style="color:RED;">*</span>':'';
                        //Input Text
                        if($fvalue['d_type']==1):
                    ?>
                            <div class="row">
                                <ul class=" form_wrap">
                                    <li class="col-md-6 col-sm-6">
                                        <div class="form_wrap_title"><?=$Star.$fvalue['d_title']?></div>
                                        <input type="text" class="form-control" name="<?=$fvalue['d_fname']?>" value="<?php echo !empty($dbdata[$fvalue['d_fname']])?$dbdata[$fvalue['d_fname']]:'';?>">
                                    </li>
                                </ul>
                            </div>
                        <?endif;?>
                    <?endforeach;endif;?>
                    <input type="checkbox" onclick="check_all(this,'d_jur[]')" />全選
                    <div class="listGrid-responsive">
                      <table>
                        <thead>
                          <tr>
                            <th>大標題</th>
                            <th>小標題</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?foreach($this->GetJurList as $key =>$val):?>
                          <tr>
                                <td><? echo $key?></td>
                                <td>
                                <? if(!empty($val)):foreach($val as $key1 =>$val1):?>
                                    <input <? echo (!empty($dbdata['d_jur']))?(strstr($dbdata['d_jur'],$val1['d_jur']))?'checked':'':'';?> type="checkbox" name="d_jur[]" id="<? echo $val1['d_jur']?>" value="<? echo $val1['d_jur']?>" ><label for="<? echo $val1['d_jur']?>"><?=$val1['d_ctitle']?></label>
                                <?endforeach;endif;?>
                                </td>
                          </tr>
                          <?endforeach;?>
                        </tbody>
                      </table>
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
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
