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
              <div class="search-title">
                <input name="d_allspec" type="text" style="width:200px;"  value="<?php echo !empty($_SESSION["AT"]["where"]['d_allspec'])?$_SESSION["AT"]["where"]['d_allspec']:''?>" placeholder="<?php echo '請輸入總碼編號';?>">
              </div>
              <select name="d_search" class="form-control input-sm" style="display:inline">
                <option value=""><?php echo '請選擇搜尋方式';?></option>
                <?php foreach ($SearchArray as $Ckey => $Cvalue):?>
                  <option value="<?php echo $Ckey?>" <?php echo (!empty($_SESSION["AT"]["where"]['d_search'])?($_SESSION["AT"]["where"]['d_search']==$Ckey)?'selected':'':'');?>><?php echo $Cvalue?></option>
                <?php endforeach;?>
              </select>
            </div>
			<div class="search-title">
                  <input name="d_xxx" type="text" style="width:200px;"  value="<?php echo !empty($_SESSION["AT"]["where"]['d_xxx'])?$_SESSION["AT"]["where"]['d_xxx']:''?>" placeholder="搜尋多個品號，用 , 分開">
                </div>
            <div class="search-btn">
              <input type="submit" name="" value="查詢" class="inquire">
              <a class="other" href="javascript:void(0)" onclick="javascript:window.location.href='<?php echo $_SERVER['REQUEST_URI']?>'">顯示全部</a>
              <?php if($this->autoful->EditView==2):?>
                <div class="file_upload_btn">
                  <label for="excel_upload" class="custom-file-upload">
                      <img class="" src="<?php echo CCODE::AWSS3.'/images/backend/ico_upload.png'?>">
                      <span class="upload">商品Excel上傳</span>
                  </label>
                  <input id="excel_upload" type="file" name="excel_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="imgDisplay(this.files)"/>
                </div>
                <span name="fileName"></span>
                <a class="other" href="javascript:void(0)" id="Excel_Import">Excel匯入</a>
				<?php echo CCODE::DemoPrefix;?>
				
				<br /><br />
				
				<a class="other" href="javascript:void(0)" id="DownProduct">產品匯出</a>
				
				<div class="search-btn">
				<a class="btn btn-danger" href="javascript:void(0)" onclick="window.open('<?php echo site_url().$this->autoful->FileName.'/products/products/correction';?>', 'formA', 'location=no, status=no, toolbar=no, directories=no, menubar=no, scrollbars=yes, width=1680, height=1024');">校正產品分類目錄</a>
				</div>
              
			  	
			  <?php endif;?>
				
            </div>
        </div>
      <?php endif;?>
        <!-- <div class="indexView_block_search">
            <div class="search-btn">
              <a class="other" href="javascript:void(0)" id="CopyProducts">複製商品</a>
            </div>
        </div> -->
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
            <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <th width="10%">狀態</th>
            <?php endif;?>

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
              <td><?php echo (!empty($AutoValue['Config'][$dbval[$AutoValue['d_fname']]])?$AutoValue['Config'][$dbval[$AutoValue['d_fname']]]:'無資料');?></td>
            <?php elseif($AutoValue['d_type']==8): ?>
              <td><img src="<?php echo CCODE::AWSS3.'/'.$dbval[$AutoValue['d_fname']]?>" style="max-width: 10%" ></td>
            <?php elseif($AutoValue['d_type']==10): ?>
              <td><?php echo date("Y-m-d", strtotime($dbval[$AutoValue['d_fname']]));?></td>
            <?php else:?>
              <td><?php echo $dbval[$AutoValue['d_fname']];?></td>
            <?php endif;endforeach;?>
            <?php if($this->tableful->MenuidDb['d_oc']=='Y'):?>
              <td class="text-center"><?php echo $this->useful->ChkOC($dbval['d_enable'])?></td>
            <?php endif;?>
            <?php if($this->tableful->MenuidDb['d_edit']=='Y'):?>
              <td class="text-center">
                <a href="<?php echo CCODE::AWSS3.'/'.((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$this->DBname.'/'.$this->DBname.'_edit/index/'.$dbval['d_id']);?>"><img class="ico-operating" src="<?php echo CCODE::AWSS3.'/'.('images/backend/ico_p_edit.png')?>"></a>
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
<script>
$('#CopyProducts').click(function(){
  var q2 = $('input[name="allid[]"]:checked').length;
  if(q2!=0){
    // alert("複製成功，總筆數為"+q2+"筆");
    $('#SearchForm').attr('action','products/CopyProducts').submit();
  }else{
    alert("請勾選欲複製的產品");
  }
});

$('#DownProduct').click(function(){
  $('#SearchForm').attr('action','products/DownProduct').submit();
  $('#SearchForm').attr('action','');
});


$('#Excel_Import').click(function(){
  if(document.getElementById("excel_upload").files.length == 0){
    alert("請選擇檔案！");
  }else{
    $('#SearchForm').attr('action','products/Excel_Import').submit();
  }
});



function imgDisplay(files) {
    if (files.length) {
      for (var i = 0; i < files.length; i++) {
        document.getElementsByName('fileName')[i].innerHTML = files[i].name;
      }
    }
  }
</script>
