<?include(dirname(dirname(__FILE__)).'/main/_header.php');?>
<div class="indexView">
  <div class="indexView_block">
    <div class="indexView_title">
      <div class="line l-blue"></div>
    </div>
    <div class="page_block">
      <div class="page_block_title">照片批次上傳(最多<?echo $this->Piclimit?>張)</div>
      <div class="form-content">
        <div>
          <ul class="form_wrap">
            <?if($Chkupload=='N'):?>
              <li>
                <div class="form_wrap_title">圖片上傳拖曳</div>
                <div class="dropzone"></div>
              </li>
            <?endif;?>
            <li style="display: inline-block;">
              <div class="form_wrap_title">圖片(目前<?echo $Nowpic;?>張)</div>
                <ul  id="sortable" style="width:100%;">
                <?if(!empty($dbdata['d_img'])):foreach ($dbdata['d_img'] as $key => $value):?>
                  <li class="ui-state-default" id="image_<?echo $key?>" style="display: inline-block;">
                    <img width="10%" src="<?php echo CCODE::DemoPrefix.'/'.(!empty($value)?$value:'');?>" >
                    <a href="javascript:void(0)" id="DelPic1" rel="<?=$value?>" style="font-size:5px;color:red;">刪除</a>
                  </li>
                <?endforeach;endif;?>
                </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<? echo CCODE::DemoPrefix.'/js/myjava/uploadimg/dropzone.js';?>"></script>
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/js/myjava/uploadimg/dropzone.css')?>" />
<script src="<? echo CCODE::DemoPrefix.'/js/myjava/uploadimg/jquery-ui.js';?>"></script>
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/js/myjava/uploadimg/jquery-ui.css')?>" />
<link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/js/myjava/uploadimg/style.css')?>" />
<style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
  #sortable img { margin: 3px 3px 3px 0; padding: 1px;float: left; width: 100%; height: 85px; font-size: 4em; text-align: center; }
  #sortable li { margin: 3px 3px 3px 0; padding: 1px;float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<style>
  #sortable1 { list-style-type: none; margin: 0; padding: 0; width: 450px; }
  #sortable1 li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
  </style>
<script>
Dropzone.autoDiscover = false;
$(document).ready(function () {
    $(".dropzone").dropzone({
        url: $('#FileName').attr('fval')+"/<?echo $this->DBname?>/dropimg/Addimg/<? echo $d_id?>",
        // addRemoveLinks: true,
        dictDefaultMessage: "圖片移置此處或點擊上傳",
        dictCancelUpload:'取消上傳',
        dictRemoveFile:'刪除',
        success: function (file, response) {
          
          file.previewElement.querySelector("img").alt = response;
          file.previewElement.querySelector("[data-dz-name]").innerHTML = response;
        },
        complete: function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            location.reload();
          }
        },
        removedfile: function(file) {
           var name = file.name; 
           $.ajax({
             type: 'POST',
             url:  $('#FileName').attr('fval')+'/<?echo $this->DBname?>/dropimg/Delimg/<? echo $d_id?>',
             data: {name: name},
             success: function(data){
                console.log(data);
             }
           });
           var _ref;
            return (_ref = file.previewElement) != null?_ref.parentNode.removeChild(file.previewElement) : void 0;
         }
    });
})
$('a[id="DelPic1"]').click(function(){
  if(confirm('確定刪除?')){
    id=$(this).attr('rel');
    $.ajax({
      type: 'POST',
      url: $('#FileName').attr('fval')+'/<?echo $this->DBname?>/dropimg/Delimg/<? echo $d_id?>',
      data: {name1: id},
      success: function(data){
        // console.log(data);
        location.reload();
      }
    });
  }
});
$( "#sortable" ).sortable({
    // axis: 'x',
    update: function (event, ui) {
        var imgarr = [];
        $('#sortable li').each(function(){
           var id = $(this).attr('id');
           var split_id = id.split("_");
           imgarr.push(split_id[1]);
        });
        // POST to server using $.post or $.ajax
        $.ajax({
            data: {imgid: imgarr},
            type: 'POST',
            url: $('#FileName').attr('fval')+'/<?echo $this->DBname?>/dropimg/Sortimg/<? echo $d_id?>',
            success: function(data){
              // console.log(data);
              location.reload();
            }
        });
    }
});

$( "#sortable" ).disableSelection();


$( "#sortable1" ).sortable();
    $( "#sortable1" ).disableSelection();
</script>

<?include(dirname(dirname(__FILE__)).'/main/_footer.php');?>
