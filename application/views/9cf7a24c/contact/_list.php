<link rel="stylesheet" type="text/css" href="<? echo base_url('css/backend/toolbar-btn.css')?>" />
<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<script src='<? echo CCODE::DemoPrefix.'/js/myjava/Config.js';?>'></script>
<form class="cd-form" method="post" enctype="multipart/form-data" id="SearchForm">
<div class="indexView">
  <div class="indexView_block">
    <div class="indexView_title">
      <div class="line l-blue">
      </div>
      <i class='<?echo $this->tableful->MenuidDb['d_icon']?>'> </i>
      <sapn class="top-title"><?echo $this->tableful->MenuidDb['d_title']?></sapn>

      <div class="indexView_block_search">
          <div class="searchBox">
              <div class="search-title">
                <select name='d_status'>
                  <option value="">請選擇<?echo $this->FiledList['d_status']?></option>
                  <?if(!empty($TypeData)):foreach ($TypeData as $key => $value):?>
                    <option value="<?=$key;?>" <? echo !empty($_SESSION["AT"]["where"]['d_status'])?($_SESSION["AT"]["where"]['d_status']==$key)?'selected':'':''?>><?=$value;?></option>
                  <?endforeach;endif;?>
                </select>
                <input name="d_name" type="text" style="width:150px;"  value="<? echo !empty($_SESSION["AT"]["where"]['d_name'])?$_SESSION["AT"]["where"]['d_name']:''?>" placeholder="請輸入<?echo $this->FiledList['d_name']?>">
              </div>
          </div>
          <div class="search-btn">
            <input type="submit" name="" value="查詢" class="inquire">
            <a class="other" href="javascript:void(0)" onclick="javascript:window.location.href='<? echo $_SERVER['REQUEST_URI']?>'">顯示全部</a>
          </div>
      </div>
      <!--<div class="toolbar">
        <input type="hidden" id="jsdbname" value="<?=$this->DBname?>">
        <a href="javascript:void(0)" class="toolbar-btn enable" onclick="ChangeEnable('Upline')">啟用</a>
        <a href="avascript:void(0)" class="toolbar-btn disable" onclick="ChangeEnable('Downline')">停用</a>
         <a href="#" class="toolbar-btn copy">複製</a>
        <a href="#" class="toolbar-btn delete">多筆刪除</a> -->
        <!-- <a href="<? echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_add');?>" class="toolbar-btn add">+新增</a> 
      </div>-->
    </div>
    <div class="listGrid-responsive">
      <table>
        <thead>
          <tr>
            <!-- <th><input type="checkbox" onclick="check_all(this,'allid[]')"></th> -->
            <? foreach ($this->FiledList as $AutoValue):if(!empty($AutoValue)):?>
              <th><?=$AutoValue?></th>
            <?endif;endforeach;?>
            <th>操作</th>
          </tr>
        </thead>
       <tbody>
          <?if(!empty($dbdata['dbdata'])):foreach($dbdata['dbdata'] as $key=> $dbval):?>
          <tr>
            <? foreach ($this->FiledList as $AutoKey=> $AutoValue):if(!empty($AutoValue)):
                if($AutoKey=='d_img'):
            ?>
                <td style="width:10%"><img src="<?=CCODE::DemoPrefix.'/'.$dbval[$AutoKey]?>" style="max-width: 100%" ></td>
            <?else:?>
                <td><? echo (!empty($AutoValue)?$dbval[$AutoKey]:'');?></td>
            <?endif;endif;endforeach;?>
            <td class="text-center">
              <a href="<? echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_edit'.'/index/'.$dbval['d_id'];?>"><img class="ico-operating" src="<? echo CCODE::DemoPrefix.'/'.'images/backend/ico_p_edit.png'?>"></a>
              <a href="javascript:void(0)" id="del_actions" rel="<?=$dbval['d_id']?>"  dbrel="<? echo $this->DBname?>"> <img class="ico-operating" src="<? echo CCODE::DemoPrefix.'/'.'images/backend/ico_p_trash.png'?>"></a>
            </td>
          </tr>
          <?endforeach;endif;?>
        </tbody>
      </table>
    </div>
    <? echo !empty($dbdata['dbdata'])?$dbdata['PageList']:'';?>
  </div>
  </form>
</div>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>