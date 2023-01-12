<?
  $NowText='繁體中文';
  $LangArray=array('en'=>'ENGLISH','cn'=>'简体中文');
    if(!empty($_SESSION[CCODE::ADMIN]['Lang'])){
    if($_SESSION[CCODE::ADMIN]['Lang']=='en'){
      $NowText='ENGLISH';
      $LangArray=array(''=>'繁體中文','cn'=>'简体中文');
    }elseif($_SESSION[CCODE::ADMIN]['Lang']=='cn'){
      $NowText='简体中文';
      $LangArray=array(''=>'繁體中文','en'=>'ENGLISH');
    }
  }
?>
<li>
  <div class="lau">
    <ul class="lau_view">
      <a href="" class="lau">
        <img class="ico_lau" src="<? echo base_url('images/backend/ico_lau.png')?>">
      </a>
    </ul>
    <span class="oi oi-caret-bottom">
    </span>
    <ul class="lau_hiden">
      <li>
        <a href="">
          CHINESE
        </a>
      </li>
      <li>
        <a href="">
          ENGLISH
        </a>
      </li>
    </ul>
  </div>
</li>