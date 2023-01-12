<ul class="previews_block">
  <li>
    <a class="p_back" href="<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/main';?>">後臺首頁</a>
  </li>
  <li>
    <a class="p_front" href="<?echo site_url();?>" target="_BLANK">前臺預覽</a>
  </li>
</ul>
<ul class="sub-menu">
  <? if(!empty($this->autoful->menu)):$menui=1;foreach($this->autoful->menu as $MenuKey =>$Menuval):
      $TtitleIcon=explode('_',$MenuKey);

  ?>
    <li>
      <a href="javascript:void(0);" class="sub-menu_item" data-index="<?echo $TtitleIcon[2];?>"><i class='<?=$TtitleIcon[1];?>'></i><?=$TtitleIcon[0];?><div class="oi oi-caret-bottom drop-down"></div></a>
      <ul class="subs-drop">
        <? if(!empty($Menuval)):foreach($Menuval as $MenuSubval):
        	if($MenuSubval['d_link']=='ckeditpage/ckeditpage'):
        ?>
			<li><a class="subs-drop_item" href="javascript:void(0)" id="menu_list" rel="<?=$MenuSubval['d_id']?>" d_url="<?=$MenuSubval['d_link']?>"><?=$MenuSubval['d_ctitle']?></a></li>
        <?else:?>
          <li><a class="subs-drop_item" href="<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/'.$MenuSubval['d_link'];?>" rel="<?=$MenuSubval['d_id']?>"><?=$MenuSubval['d_ctitle']?></a></li>
        <?endif;$menui++;endforeach;endif;?>

      </ul>
    </li>
  <?endforeach;endif;?>

  <?if($_SESSION[CCODE::ADMIN]['Aid']==1):?>
      <li>
        <a href="javascript:void(0)" class="sub-menu_item" data-index="re">自動頁面設定<div class="oi oi-caret-bottom drop-down"></div></a>
        <ul class="subs-drop">
        <li><a class="subs-drop_item" href="<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/Autopage/Autopage';?>">自動頁面設定</a></li>
        </ul>
      </li>
  <?endif;?>
  <?//print_r($_POST['Menuid']);?>
<script>
$(function() {
  $(".subs-drop").hide();
  pathname=location.pathname;
  if(pathname.match('_add')!=null){
    pathname=pathname.split('_add')[0];
  }
  if(pathname.match('_edit')!=null){
    pathname=pathname.split('_edit')[0];
  }
  var target1=$("a[href='"+pathname+"']").attr('rel');
  if (typeof target1 === "undefined") {
    target1='<?echo (!empty($this->DBid)?$this->DBid:'rr');?>';
  }
  $( ".sub-menu_item" ).each(function() {
    Mid=$(this).attr('data-index').split('!@#');
    if($.inArray(target1, Mid)>=0){
      var target = $(this);
      $("+ul",target).slideDown();
      $("a[rel='"+target1+"']").css("color","#ffd362");
      target.addClass("open");
    }
  });
  
});
$(".sub-menu_item").click(function() {
    $(".subs-drop").slideUp();
    $(".sub-menu_item").removeClass("open");
    if ($("+ul", this).css("display") == "none") {
      $("+ul", this).slideDown();
      $(this).addClass("open");
    }
  }).mouseover(function() {
    $(this).addClass("rollover");
  }).mouseout(function() {
    $(this).removeClass("rollover");
  });
</script>