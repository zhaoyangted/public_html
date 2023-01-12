<link rel="stylesheet" type="text/css" href="<? echo base_url('css/backend/toolbar-btn.css')?>" />
<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<div class="indexView">
  <div class="indexView_block">
    <div class="indexView_title">
      <div class="line l-blue">
      </div>
      <i class='fas fa-shopping-cart'> </i>
      <sapn class="top-title">
        自動頁面管理
      </sapn>
      <div class="toolbar">
        <a href="<?echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage/info');?>" class="toolbar-btn add">+新增</a>
      </div>
    </div>
    <div class="listGrid-responsive">
      <table>
        <thead>
          <tr>
            <th>狀態</th>
            <th>排序</th>
            <th>標題</th>
            <th>欄位編輯</th>
            <th>修改</th>
            <th>刪除</th>
          </tr>
        </thead>
        <tbody>
          <?if(!empty($dbdata)):foreach($dbdata as $value):?>
            <tr>
              <td><?=$this->useful->ChkOC($value['d_enable'])?></td>
              <td><?=$value['d_sort']?></td>
              <td><?=$value['d_title']?></td>
              <td class="text-center">
                <a href="<?echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage/index/'.$value['d_id'].'');?>"><img class="ico-operating" src="<? echo base_url('images/backend/ico_p_edit.png')?>"></a>
              </td>
              <td class="text-center">
                <a href="<?echo site_url((!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage/info/'.$value['d_id'].'');?>"><img class="ico-operating" src="<? echo base_url('images/backend/ico_p_edit.png')?>"></a>
              </td>
              <td class="text-center">
                <a href="javascript:void(0)" rel="<?echo $value['d_id'];?>" id="DelMenu"> <img class="ico-operating" src="<? echo base_url('images/backend/ico_p_trash.png')?>"></a>
              </td>
            </tr>
          <? endforeach;endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
<script>
$('a[id="DelMenu"]').click(function(){
    id=$(this).attr('rel');
    if(confirm('確定刪除?')){
        $.ajax({
          type: "POST",
          url: "<? echo site_url(''.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage/delData')?>",
          data: {
            id:id,
            dbname:'auto_page_menu'
          },
          dataType: "text",
          success: function(data) {
            if(data=='OK'){
                location.reload();
            }
            
          },
        });
    }
});
</script>