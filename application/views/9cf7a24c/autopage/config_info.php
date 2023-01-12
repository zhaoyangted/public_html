<link rel="stylesheet" type="text/css" href="<? echo base_url('css/backend/toolbar-btn.css')?>" />
<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<div class="indexView">
  <div class="indexView_block">
    <div class="indexView_title">
      <div class="line l-blue">
      </div>
      <i class='fas fa-shopping-cart'> </i>
      <sapn class="top-title">自動頁面管理</sapn>
      <form action="<? echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys');?>/Autopage/Autopage/data_AED" method="post" enctype="multipart/form-data">
      <div class="toolbar">
        <input type="submit" value="+新增" class="toolbar-btn add">
        <a href="<?echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage/index/'.$Upid.'');?>" class="toolbar-btn disable">回首頁</a>
      </div>
    </div>
    <div class="listGrid-responsive">
      <table>
        <thead>
          <tr>         
            <th>列表?</th>
            <th>欄位名稱</th>
            <th>名稱</th>
            <th>類型</th>
            <th>排序</th>
            <th>查詢資料欄位(product_config)</th>
          </tr>
        </thead>
        <tbody>
            <tr>
              <!-- 列表 -->
              <td>
                <select name="d_list" id='d_list'>
                  <option value="1" >列表頁</option>
                  <option value="2" >內文頁</option>
                </select>
              </td>
              <!-- 欄位名稱 -->
              <td>
                <select name="d_fname" id="d_fname">
                  <option value="0">請選擇</option>
                  <? if(!empty($fdata)):foreach ($fdata as $key => $fvalue):?>
                    <option value='<?=$fvalue['Field']?>' rel="<? echo $fvalue['Comment']?>"><?=$fvalue['Field'].'('.$fvalue['Comment'].')'?></option>
                  <? endforeach;endif;?>
                </select>
              </td>
              <!-- 名稱 -->
              <td><input type="text" name="d_title"  id="d_title" value=""></td>
              <!-- 類型 -->
              <td>
                <select name="d_type">
                  <option value="0">請選擇</option>
                  <? if(!empty($cdata)):foreach ($cdata as $key => $value):?>
                    <option value="<?=$value['d_val']?>" ><?=$value['d_title'].'('.$value['d_content'].')'?></option>
                  <? endforeach;endif;?>
                </select>
              </td>
              <!-- 排序 -->
              <td><input type="number" name="d_sort" id="d_sort" min='0' value="5" style="width:50px;"></td>
              <!-- 查詢資料欄位 -->
              <td><input type="text" name="d_config"  id="d_config"></td>
            </tr>
        </tbody>
      </table>
      <table>
        <thead>
          <tr>
            <th>是否顯示</th>
            <th>必填檢查方式</th>
            <th>新增修改是否有此欄位</th>
            <th>欄位限制英數</th>
            <th>欄位補充說明</th>
          </tr>
        </thead>
        <tbody>
            <tr>
              <!-- 是否顯示 -->
              <td>
                <select name="d_view">
                  <option value="1">是</option>
                  <option value="2">否</option>
                </select>
              </td>
              <!-- 必填檢查方式 -->
              <td>
                <select name="d_search" id="d_search">
                  <option value=" ">請選擇</option>
                  <? if(!empty($sdata)):foreach ($sdata as $key => $value):?>
                    <option value="<?=$value['d_title']?>" ><?=$value['d_title'].'('.$value['d_content'].')'?></option>
                <? endforeach;endif;?>
              </select>
              </td>
              <!-- 新增修改是否有此欄位 -->
              <td>
                <select name="d_add_fix" id="d_add_fix">
                  <option value="1">兩個都有</option>
                  <option value="2">ADD</option>
                  <option value="3">EDIT</option>
                </select>
              </td>
              <!-- 欄位限制英數 -->
              <td>
                <select name="d_inputtype" id="d_inputtype">
                  <option value=" ">請選擇</option>
                  <? if(!empty($idata)):foreach ($idata as $key => $value):?>
                    <option value="<?=$value["d_id"]?>" ><?=$value["d_title"].'('.$value['d_content'].')'?></option>
                  <? endforeach;endif;?>
                </select>
              </td>
              <!-- 欄位補充說明 -->
              <td><input type="text" name="d_content"  id="d_content" value=""></td>
            </tr>
            <input type="hidden" name="d_menu_id" value="<? echo $d_menu_id;?>">
            <input type="hidden" name="dbname" value="auto_page">
        </tbody>
      </table>
      </form>
    </div>
    <div style="height: 10px;background-color: #000;"></div>
        <? if(!empty($dbdata)):foreach ($dbdata as $key => $value):?>
          <form action="<? echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys');?>/Autopage/Autopage/data_AED" method="post" enctype="multipart/form-data" >
          <table width="100%" border="0" cellspacing="1" cellpadding="4" class="listT">
            <tbody >
              <tr align="center" class="listT_field" style="<? echo ($value['d_list']==1)?'background-color:#d0d0d0':'background-color:yellow';?>">
                <td ><? echo ($value['d_list']==1)?'列表頁':'內文頁';?></td>
                <td>
                  <select name="d_fname" id="d_fname">
                    <option value="0">請選擇</option>
                    <? if(!empty($fdata)):foreach ($fdata as $fvalue):?>
                      <option value='<?=$fvalue['Field']?>' rel="<? echo $fvalue['Comment']?>" <? echo ($fvalue['Field']==$value['d_fname'])?'selected':'';?>><?=$fvalue['Field'].'('.$fvalue['Comment'].')'?></option>
                    <? endforeach;endif;?>
                  </select>
                </td>
                <td><input type="text" name="d_title"  id="d_title" value="<? echo $value['d_title']?>"></td>
                <td>
                  <select name="d_type">
                    <option value="0">請選擇</option>
                    <? if(!empty($cdata)):foreach ($cdata as $cvalue):?>
                      <option value="<?=$cvalue['d_val']?>" <? echo ($cvalue['d_val']==$value['d_type'])?'selected':'';?>><?=$cvalue['d_title'].'('.$cvalue['d_content'].')'?></option>
                    <? endforeach;endif;?>
                  </select>
                </td>
                <td><input type="number" name="d_sort" min='0' value="<? echo $value['d_sort']?>" style="width:50px;"></td>
                <? //if($value['d_list']==1):?>
                  <td><input type="text" name="d_config"  value="<? echo $value['d_config']?>"></td>
                <? //else:echo '<td></td>';endif;?>
              </tr>
              <tr class="E7E7E7">
                  <td>
                    <select name="d_view">
                      <option value="1" <? echo ($value['d_view']==1)?'selected':'';?>>是</option>
                      <option value="2" <? echo ($value['d_view']==2)?'selected':'';?>>否</option>
                    </select>
                  </td>
                  <? if($value['d_list']==2):?>
                    <td>
                      <select name="d_search" >
                        <option value=" ">請選擇</option>
                        <? if(!empty($sdata)):foreach ($sdata as $svalue):?>
                          <option value="<?=$svalue['d_title']?>" <? echo ($svalue['d_title']==$value['d_search'])?'selected':'';?>><?=$svalue['d_title'].'('.$svalue['d_content'].')'?></option>
                      <? endforeach;endif;?>
                    </select>
                    </td>
                    
                    <!-- <td><input type="text"  name="d_maxlength" value="<?=$value['d_maxlength']?>" style="width:50px;" ></td> -->
                    
                    <td>
                      <select name="d_add_fix" >
                        <option value="1" <? echo ($value['d_add_fix']==1)?'selected':'';?>>兩個都有</option>
                        <option value="2" <? echo ($value['d_add_fix']==2)?'selected':'';?>>ADD</option>
                        <option value="3" <? echo ($value['d_add_fix']==3)?'selected':'';?>>EDIT</option>
                      </select>
                    </td>
                    <td>
                      <select name="d_inputtype">
                        <option value=" ">請選擇</option>
                        <? if(!empty($idata)):foreach ($idata as $ivalue):?>
                          <option value="<?=$ivalue["d_id"]?>" <? echo ($ivalue['d_id']==$value['d_inputtype'])?'selected':'';?>><?=$ivalue["d_title"].'('.$ivalue['d_content'].')'?></option>
                        <? endforeach;endif;?>
                      </select>
                    </td>
                  <td><input type="text" name="d_content" value="<?echo $value['d_content']?>"></td>

                  <? else: echo '<td></td><td></td><td></td><td></td>';?>
                  <?endif;?>
                  <input type="hidden" name="d_menu_id" value="<? echo $d_menu_id;?>">
                  <input type="hidden" name="dbname" value="auto_page">
                  <input type="hidden" name="sid" value="<? echo $value['d_id'];?>">
                  <td><input type="submit" value="修改" class="btn btn-primary btn-sm" >
                  <input type="button" id="delData" rel="<? echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys');?>/Autopage/Autopage/delData/auto_page/<?=$value['d_id']?>/<? echo $value['d_menu_id']?>" value="刪除" class="btn btn-primary btn-sm" ></td>
              </tr>
            </tbody> 
          </table>
        </form>
        <? endforeach;endif;?>
        </div> 
    </div>
  </div>
  
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
<script>
$('input[id="delData"]').click(function(){
  if(confirm('確定刪除?')){
    window.location.href=$(this).attr('rel');
  }
});
// 隱藏不必要的欄位
$('#d_search,#d_add_fix,#d_inputtype,#d_content').hide();
$('#d_list').change(function(){
  dsort=parseInt($('input[name="d_sort"]:last').val())+5;
  if($(this).val()==1){
    $('#d_search,#d_add_fix,#d_inputtype,#d_content').hide();
    $('#d_sort').val('');
    $('#addbody').css('background-color','');
  }else{
    $('#d_search,#d_add_fix,#d_inputtype,#d_content').show();
    $('#d_sort').val(dsort);
    $('#addbody').css('background-color','yellow');
  }
});
$('#d_fname').change(function(){
  $('#d_title').val($('#d_fname option:selected').attr('rel'));
});
</script>