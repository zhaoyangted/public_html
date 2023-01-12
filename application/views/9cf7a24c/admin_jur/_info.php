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
                    <?foreach ($Earray as $key => $value):$key++;?>
                        <input type="radio" onclick="check_all1(<?echo $key;?>)" name="All"/><?echo $value;?>
                    <?endforeach;?>
                    <div class="listGrid-responsive">
                      <table>
                        <?//print_r($this->GetJurList);
                        foreach($this->GetJurList as $key =>$val):?>
                        <thead>
                          <tr>
                            <th colspan="2"><? echo $key?></th>
                          </tr>
                        </thead>
                        <tbody>
                            <? if(!empty($val)):foreach($val as $key1 =>$val1):?>
                              <tr>
                                    <td><?=$val1['d_ctitle']?></td>
                                    <td>
                                        <?foreach ($Earray as $key => $value):$key++;?>
                                            <input type="radio" name="d_jur[<?echo $val1['d_jur']?>]" value="<?echo $key;?>" id="<?echo $val1['d_jur'].$key;?>" <? echo (!empty($dbdata['d_jur'][$val1['d_jur']]))?($dbdata['d_jur'][$val1['d_jur']]==$key)?'checked':'':($key==2)?'checked':'';?>><label for="<?echo $val1['d_jur'].$key;?>"><?echo $value;?></label>
                                        <?endforeach;?>
                                    </td>
                              </tr>
                          <?endforeach;endif;?>
                        </tbody>
                        <?endforeach;?>
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
<script>
function check_all1(Num){
    $("input[name*='d_jur'][value='"+Num+"']").prop("checked", true);
}

</script>