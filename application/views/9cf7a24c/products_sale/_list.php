<link rel="stylesheet" type="text/css" href="<? echo base_url('css/backend/toolbar-btn.css')?>" />
<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<div class="indexView">
  <div class="indexView_block">
    <div class="indexView_title">
      <div class="line l-blue">
      </div>
      <i class='<?echo $this->tableful->MenuidDb['d_icon']?>'> </i>
      <sapn class="top-title"><?echo $this->tableful->MenuidDb['d_title'].'>'.$Uptitle?></sapn>
      <form class="cd-form" method="post" enctype="multipart/form-data" id="SearchForm">
      <?if($this->tableful->MenuidDb['d_search']=='Y'):?>
        <div class="indexView_block_search">
            <div class="searchBox">
              <? foreach ($this->tableful->Search as $SearchKey=> $SearchValue):
                  if($SearchValue[0]=='1'):
              ?>
                <div class="search-title">
                  <input name="<?=$SearchKey?>" type="text" style="width:200px;"  value="<? echo !empty($_SESSION["AT"]["where"][$SearchKey])?$_SESSION["AT"]["where"][$SearchKey]:''?>" placeholder="<? echo '請輸入'.$SearchValue[1];?>">
                </div>
                <?endif;?>
                <?if(in_array($SearchValue[0],array('2','3','4'))): ?>
                    <select name="<?=$SearchKey?>" class="form-control input-sm" style="display:inline">
                      <option value=""><? echo '請選擇'.$SearchValue[1];?></option>
                      <? foreach ($SearchValue['Config'] as $Ckey => $Cvalue):?>
                        <option value="<?=$Ckey?>" <? echo ($_SESSION["AT"]["where"][$SearchKey]==$Ckey)?'selected':'';?>><?=$Cvalue?></option>
                      <? endforeach;?>
                    </select>
                <?endif;?>
              <?endforeach;?>
            </div>
            <div class="search-btn">
              <input type="submit" name="" value="查詢" class="inquire">
              <a class="other" href="javascript:void(0)" onclick="javascript:window.location.href='<? echo $_SERVER['REQUEST_URI']?>'">顯示全部</a>
            </div>
        </div>
      <?endif;?>
        <div class="toolbar">
          <?if($this->autoful->EditView==2):?>
            <?if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <input type="hidden" id="jsdbname" value="<?=$this->DBname?>">
              <a href="javascript:void(0)" class="toolbar-btn enable" onclick="ChangeEnable('Upline')">啟用</a>
              <a href="avascript:void(0)" class="toolbar-btn disable" onclick="ChangeEnable('Downline')">停用</a>
            <?endif;?>
            <?if($this->tableful->MenuidDb['d_add']=='Y'):?>
              <a href="<? echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_add/index/'.$TID.'');?>" class="toolbar-btn add">+新增</a>
            <?endif;?>
          <?endif;?>
            <a href="<? echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'_type/'.$this->DBname.'_type');?>" class="toolbar-btn copy" style="float: right;">返回</a>
        </div>

    </div>
    <div class="listGrid-responsive">
      <table>
        <thead>
          <tr>
            <?if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <th width="5%"><input type="checkbox" onclick="check_all(this,'allid[]')"></th>
            <?endif;?>
            <? foreach ($this->tableful->Menu as $AutoValue):?>
              <th><?=$AutoValue['d_title']?></th>
            <?endforeach;?>
            <th width="10%">狀態</th>
            <?if($this->tableful->MenuidDb['d_edit']=='Y'):?>
              <th width="10%"><?echo ($this->autoful->EditView==2)?'修改':'查看';?></th>
            <?endif;?>
            <?if($this->autoful->EditView==2):?>
              <?if($this->tableful->MenuidDb['d_del']=='Y'):?>
                <th width="10%">刪除</th>
              <?endif;?>
            <?endif;?>
          </tr>

        </thead>
        <tbody>
          <?if(!empty($dbdata['dbdata'])):foreach($dbdata['dbdata'] as $key=> $dbval):?>
          <tr>
            <?if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <td><input type="checkbox" name="allid[]" value="<?=$dbval['d_id']?>"></td>
            <?endif;?>
            <? foreach ($this->tableful->Menu as $AutoValue):
                if($AutoValue['d_type']==4):
            ?>
              <td><?=$AutoValue['Config'][$dbval[$AutoValue['d_fname']]];?></td>
          <?php elseif($AutoValue['d_type']==10): ?>
              <td><?=date("Y-m-d", strtotime($dbval[$AutoValue['d_fname']]));?></td>
            <?else:?>
              <td><?=$dbval[$AutoValue['d_fname']];?></td>
            <?endif;endforeach;?>
            <td class="text-center"><?=$this->useful->ChkOC($dbval['d_enable'])?></td>
            <?if($this->tableful->MenuidDb['d_edit']=='Y'):?>
              <td class="text-center">
                <a href="<? echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_edit/index/'.$dbval['d_id']);?>"><img class="ico-operating" src="<? echo CCODE::DemoPrefix.'/'.('images/backend/ico_p_edit.png')?>"></a>
              </td>
            <?endif;?>
            <?if($this->autoful->EditView==2):?>
              <?if($this->tableful->MenuidDb['d_del']=='Y'):?>
                <td class="text-center">
                  <a href="javascript:void(0)" id="del_actions" rel="<?=$dbval['d_id']?>" dbrel="<? echo $this->DBname?>"> <img class="ico-operating" src="<? echo CCODE::DemoPrefix.'/'.('images/backend/ico_p_trash.png')?>"></a>
                </td>
              <?endif;?>
            <?endif;?>
            
          </tr>
          <?endforeach;endif;?>
        </tbody>
      </table>
    </div>
    <? echo !empty($dbdata['dbdata'])?$dbdata['PageList']:'';?>
     </form>
  </div>

</div>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
