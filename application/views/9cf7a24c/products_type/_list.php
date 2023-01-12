<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/backend/toolbar-btn.css')?>" />
<?php include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<div class="indexView">
  <div class="indexView_block">
    <div class="indexView_title">
      <div class="line l-blue">
      </div>
      <i class='<?php echo $this->tableful->MenuidDb['d_icon']?>'> </i>
      <sapn class="top-title"><?php echo $this->tableful->MenuidDb['d_title']?></sapn>
      <form class="cd-form" method="post" enctype="multipart/form-data" id="SearchForm">
      <?php if($this->tableful->MenuidDb['d_search']=='Y'):?>
        <div class="indexView_block_search">
            <div class="searchBox">
              <?php foreach ($this->tableful->Search as $SearchKey=> $SearchValue):
                  if($SearchValue[0]=='1'):
              ?>
                <div class="search-title">
                  <input name="<?php echo $SearchKey?>" type="text" style="width:200px;"  value="<?php echo !empty($_SESSION["AT"]["where"][$SearchKey])?$_SESSION["AT"]["where"][$SearchKey]:''?>" placeholder="<?php echo '請輸入'.$SearchValue[1];?>">
                </div>
                <?php endif;?>
                <?php if(in_array($SearchValue[0],array('2','3','4'))): ?>
                    <select name="<?php echo $SearchKey?>" class="form-control input-sm" style="display:inline">
                      <option value=""><?php echo '請選擇'.$SearchValue[1];?></option>
                      <?php foreach ($SearchValue['Config'] as $Ckey => $Cvalue):?>
                        <option value="<?php echo $Ckey?>" <?php echo ($_SESSION["AT"]["where"][$SearchKey]==$Ckey)?'selected':'';?>><?php echo $Cvalue?></option>
                      <?php endforeach;?>
                    </select>
                <?php endif;?>
              <?php endforeach;?>
            </div>
            <div class="search-btn">
              <input type="submit" name="" value="查詢" class="inquire">
              <a class="other" href="javascript:void(0)" onclick="javascript:window.location.href='<?php echo $_SERVER['REQUEST_URI']?>'">顯示全部</a>
            </div>
        </div>
      <?php endif;?>
        <div class="toolbar">
          <?php if($this->autoful->EditView==2):?>
            <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <input type="hidden" id="jsdbname" value="<?php echo $this->DBname?>">
              <a href="javascript:void(0)" class="toolbar-btn enable" onclick="ChangeEnable('Upline')">啟用</a>
              <a href="avascript:void(0)" class="toolbar-btn disable" onclick="ChangeEnable('Downline')">停用</a>
            <?php endif;?>
          <?php endif;?>
          <?php if($this->autoful->EditView==2):?>
            <?php if($this->tableful->MenuidDb['d_add']=='Y'):?>
              <a href="<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_add');?>" class="toolbar-btn add">+新增</a>
            <?php endif;?>
          <?php endif;?>
        </div>

    </div>
    <div class="listGrid-responsive">
      <table>
        <thead>
          <tr>
            <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <th width="5%"><input type="checkbox" onclick="check_all(this,'allid[]')"></th>
            <?php endif;?>
            <?php foreach ($this->tableful->Menu as $AutoValue):?>
              <th><?php echo $AutoValue['d_title']?></th>
            <?php endforeach;?>
            <th width="10%">ID</th>
            <th width="10%">狀態</th>
            <th width="10%">次分類管理</th>
            <?php if($this->tableful->MenuidDb['d_edit']=='Y'):?>
              <th width="10%"><?php echo ($this->autoful->EditView==2)?'修改':'查看';?></th>
            <?php endif;?>
            <?php if($this->autoful->EditView==2):?>
              <?php if($this->tableful->MenuidDb['d_del']=='Y'):?>
                <th width="10%">刪除</th>
              <?php endif;?>
            <?php endif;?>
          </tr>

        </thead>
        <tbody>
          <?php if(!empty($dbdata['dbdata'])):foreach($dbdata['dbdata'] as $key=> $dbval):?>
          <tr>
            <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <td><input type="checkbox" name="allid[]" value="<?php echo $dbval['d_id']?>"></td>
            <?php endif;?>
            
            <?php foreach ($this->tableful->Menu as $AutoValue):
                if($AutoValue['d_type']==4):
            ?>
              <td><?php echo $AutoValue['Config'][$dbval[$AutoValue['d_fname']]];?>
              
              </td>
          	<?php elseif($AutoValue['d_type']==10): ?>
              <td><?php echo date("Y-m-d", strtotime($dbval[$AutoValue['d_fname']]));?></td>
            <?php else:?>
              <td>
			  <?php //if( $AutoValue['d_title']== '標題'):?>
              <?php //echo '【'.$dbval['d_id'].'】' ;?>
              <?php //endif?>
              
			  <?php echo $dbval[$AutoValue['d_fname']];?>
              </td>
            <?php endif;endforeach;?>
            <td class="text-center"><?php echo $dbval['d_id'] ;?></td>
            <td class="text-center"><?php echo $this->useful->ChkOC($dbval['d_enable'])?></td>
            <td class="text-center">
              <a href="<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_s/index/'.$dbval['d_id']);?>"><img class="ico-operating" src="<?php echo CCODE::DemoPrefix.'/'.('images/backend/ico_p_edit.png')?>"></a>
            </td>
            <?php if($this->tableful->MenuidDb['d_edit']=='Y'):?>
              <td class="text-center">
                <a href="<?php echo CCODE::DemoPrefix.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_edit/index/'.$dbval['d_id']);?>"><img class="ico-operating" src="<?php echo CCODE::DemoPrefix.'/'.('images/backend/ico_p_edit.png')?>"></a>
              </td>
            <?php endif;?>
            <?php if($this->autoful->EditView==2):?>
            <?php if($this->tableful->MenuidDb['d_del']=='Y'):?>
                <td class="text-center">
                  <a href="javascript:void(0)" id="del_actions" rel="<?php echo $dbval['d_id']?>" dbrel="<?php echo $this->DBname?>"> <img class="ico-operating" src="<?php echo CCODE::DemoPrefix.'/'.('images/backend/ico_p_trash.png')?>"></a>
                </td>
              <?php endif;?>
            <?php endif;?>
            
          </tr>
          <?php endforeach;endif;?>
        </tbody>
      </table>
    </div>
    <?php echo !empty($dbdata['dbdata'])?$dbdata['PageList']:'';?>
     </form>
  </div>

</div>
<?php include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
