<link rel="stylesheet" type="text/css" href="<? echo base_url('css/backend/toolbar-btn.css')?>" />
<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
    <div class="indexView">
        <div class="indexView_block">
            <div class="indexView_title">
                <div class="line l-blue"></div>
                <i class="fas fa-bullhorn"></i>
                <sapn class="top-title">列表編輯內文</sapn>
            </div>
            <form action="<?echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage/data_AED');?>" method="post" enctype="multipart/form-data">
            <div class="page_block">
                    <div class="form-content">
                        <div class="row">
                            <ul class=" form_wrap">
                                <li class="col-md-6 col-sm-6">
                                    <div class="form_wrap_title">大標題</div>
                                    <input type="text" class="form-control" name="d_title" value="<?php echo !empty($dbdata['d_title'])?$dbdata['d_title']:'';?>">
                                    <div class="form_wrap_title">排序</div>
                                    <input type="number" class="form-control" name="d_sort" value="<?php echo !empty($dbdata['d_sort'])?$dbdata['d_sort']:'';?>">
                                    <div class="form_wrap_title">狀態</div>
                                    <input type="checkbox" name="d_enable" value="Y" <? echo (empty($dbdata['d_enable']) or $dbdata['d_enable']=='Y')?'checked':'';?>>
                                    
                                </li>
                                <div class="form_wrap_title">Icon設定</div>
                                  <select name="d_icon" id="d_icon">
                                    <?foreach ($CssType as $value):?>
                                      <option value="<?=$value?>"><?=$value?></option>
                                    <?endforeach;?>
                                  </select>
                                  <span id="ViewIcon"><i class="<?php echo !empty($dbdata['d_icon'])?$dbdata['d_icon']:'';?>"></i></span>
                            </ul>
                        </div>
                    </div>
                    <div class="toolbar">
                    <a href="javascript:void(0)" id="add_menu" class="toolbar-btn add">+新增</a>
                    </div>
                <div class="listGrid-responsive">
                  <table>
                    <thead>
                      <tr>
                        <th>權限</th>
                        <th>標題</th>
                        <th>列表名稱</th>
                        <th>資料表名稱</th>
                        <th>排序</th>
                        <th>上下架功能</th> 
                        <th>搜尋功能</th>  
                        <th>新增功能</th>  
                        <th>修改功能</th>  
                        <th>刪除功能</th>
                        <th>額外連結</th>
                        <th>啟動</th>
                      </tr>
                    </thead>
                    <tbody id="addbody">
                       <? 
                        if(!empty($Subdata)){
                          foreach($Subdata as $val){
                        ?>
                            <tr align="center" class="listT_row01">
                                  <td><input type="text" name="d_jur[<?=$val['d_id']?>]" value="<?php echo !empty($val['d_jur'])?$val['d_jur']:'';?>" ></td>
                                  <td><input type="text" name="d_menuname[<?=$val['d_id']?>]" value="<?php echo $val['d_title']?>" ></td>
                                  <td><input type="text" name="d_listname[<?=$val['d_id']?>]" value="<?php echo $val['d_ctitle']?>" ></td>
                                  <td><input type="text" name="d_dbname[<?=$val['d_id']?>]" value="<?php echo $val['d_dbname']?>" ></td>
                                  <td><input type="text" name="d_sort_son[<?=$val['d_id']?>]" value="<?php echo $val['d_sort']?>" ></td>
                                  <td><input <? echo ($val['d_oc']=='Y')?'checked':'';?> name="d_oc[<?=$val['d_id']?>]" value="Y" type="checkbox"></td>
                                  <td><input <? echo ($val['d_search']=='Y')?'checked':'';?> name="d_search[<?=$val['d_id']?>]" value="Y" type="checkbox"></td>
                                  <td><input <? echo ($val['d_add']=='Y')?'checked':'';?> name="d_add[<?=$val['d_id']?>]" value="Y" type="checkbox"></td>
                                  <td><input <? echo ($val['d_edit']=='Y')?'checked':'';?> name="d_edit[<?=$val['d_id']?>]" value="Y" type="checkbox"></td>
                                  <td><input <? echo ($val['d_del']=='Y')?'checked':'';?> name="d_del[<?=$val['d_id']?>]" value="Y" type="checkbox"></td>
                                  <td><input type="text" name="d_link[<?=$val['d_id']?>]" value="<?php echo $val['d_link']?>"></td>
                                  <td><input <? echo ($val['d_enable']=='Y')?'checked':'';?> name="d_enable_son[<?=$val['d_id']?>]" value="Y" type="checkbox"></td>
                                  <input type="hidden" name="sid[<?=$val['d_id']?>]" value="<?=$val['d_id']?>">
                            </tr>
                      <? }} ?>
                    </tbody>
                  </table>
                </div>
            </div>
            <div class="search-btn">
                <input type="hidden" name="d_id" value="<? echo !empty($dbdata['d_id'])?$dbdata['d_id']:''?>">
                <input type="hidden" name="dbname" value="auto_page_menu">
                <input type="submit" name="" value="送出" class="inquire">
                <input type="button" value="回上頁" class="other" onclick="javascript:window.location.href='<?echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage');?>'">
            </div>
        </div>
    </div>
    </form>
    </div>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
<script>
$('#d_icon').change(function(){
  $("#ViewIcon").html('<i class="'+$(this).val()+'"></i>');
});
var Maxnum=<? echo !empty($max['d_id'])?$max['d_id']:'""'?>;
$('#add_menu').click(function(){
  
  $('#addbody').append(
    '<tr class="E7E7E7">'+
      '<td><input type="text" name="d_jur['+Maxnum+']"></td>'+
      '<td><input type="text" name="d_menuname['+Maxnum+']"></td>'+
      '<td><input type="text" name="d_listname['+Maxnum+']"></td>'+
      '<td><input type="text" name="d_dbname['+Maxnum+']"></td>'+
      '<td><input type="text" name="d_sort_son['+Maxnum+']"></td>'+
      '<td><input name="d_oc['+Maxnum+']" value="Y" type="checkbox"></td>'+
      '<td><input name="d_search['+Maxnum+']" value="Y" type="checkbox"></td>'+
      '<td><input name="d_add['+Maxnum+']" value="Y" type="checkbox"></td>'+
      '<td><input name="d_edit['+Maxnum+']" value="Y" type="checkbox"></td>'+
      '<td><input name="d_del['+Maxnum+']" value="Y" type="checkbox"></td>'+
      '<td><input type="text" name="d_link['+Maxnum+']"></td>'+
      '<td><input name="d_enable_son['+Maxnum+']" value="Y" type="checkbox"></td>'+
    '</tr>'
  );
  Maxnum++;
});
</script>