<?header("Cache-control: private");?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1 user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="IE=9,IE=10,IE=11,IE=EDGE" />
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<? echo CCODE::DemoPrefix.('/images/backend/favicons/ms-icon-144x144.png')?>">
  <meta name="theme-color" content="#ffffff">
  <title><? echo isset($this->autoful->header)?$this->autoful->header:'預設值'.'-後台管理系統'?></title>
  <link rel="apple-touch-icon" sizes="57x57" href="<? echo CCODE::DemoPrefix.('/images/backend/favicons/apple-icon-57x57.png')?>" />
  <link rel="apple-touch-icon" sizes="72x72" href="<? echo CCODE::DemoPrefix.('/images/backend/favicons/apple-icon-72x72.png')?>" />
  <link rel="apple-touch-icon" sizes="114x114" href="<? echo CCODE::DemoPrefix.('/images/backend/favicons/apple-icon-114x114.png')?>" />
  <link rel="icon" sizes="192x192" href="<? echo CCODE::DemoPrefix.('/images/backend/favicons/apple-icon-192x192.png')?>" type="image/png" />
  <link rel="shortcut icon" href="<? echo CCODE::DemoPrefix.('/images/backend/favicons/favicon.ico')?>" type="image/x-icon" />
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/component.css')?>" />
  <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/hamburgers.css')?>" />
  <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/fontawesome.css')?>" />
  <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/icon/css/open-iconic-bootstrap.css')?>" />
  <link rel="stylesheet" type="text/css" href="<? echo CCODE::DemoPrefix.('/css/backend/master.css')?>" />
  <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/backend/jquery-3.1.1.js')?>"></script>
  <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/backend/slick.js')?>"></script>
  <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/backend/jquery.dlmenu.js')?>"></script>
  <script type="text/javascript" src="<? echo CCODE::DemoPrefix.('/js/backend/hamburgers.js')?>"></script>
  <script src='<? echo CCODE::DemoPrefix.'/js/myjava/Config.js';?>'></script>
</head>

<body onload="startTime()">
  <span id="FileName" fval="<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys');?>"></span>
  <header>
    <div class="navbar">
      <div class="navbar_right">
        <ul class="navbar_right_block">
          <!-- <li>
            <div class="lau">
              <ul class="lau_view">
                <div class="lau">
                  <img class="ico_lau" src="<?php echo CCODE::DemoPrefix.('/images/backend/ico_lau.png');?>">
                </div>
              </ul>
              <span class="oi oi-caret-bottom">
              </span>
              <ul class="lau_hiden" style="display: none;">
                <li onclick="location.href='<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/main';?>'">
                  <div>
                    CHINESE123
                  </div>
                </li>
              </ul>
            </div>
          </li> -->
          <li>
            <div class="account">
              <img class="account_pic" src="<? echo CCODE::DemoPrefix.('/images/backend/img_account.png')?>">
              <sapn class="account_name">
                <? echo $_SESSION[CCODE::ADMIN]['Aname'];?>
              </sapn>
              <button class="log_out" id="BackendOut"></button>
              </button>
            </div>
          </li>
          <li>
            <div class="cdt_block">
              <img src="<? echo CCODE::DemoPrefix.('/images/backend/img_time.png')?>">
              <sapn class="cdt_no" id="TimeOut"></sapn>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div id="menuToggle" class="menu_btn">
      <input type="checkbox" />
      <span></span>
      <span></span>
      <span></span>
      <div id="menu"><?php include '_sub-menu.php';?></div>
    </div>
    <div class="hamburger-btn">
      <nav role="navigation"></nav>
    </div>
  </header>
<script type="text/javascript">
  

  $(".hamburger-bg").click(function() {
    $(".nav_mobile_box").fadeToggle(200);
  });

  $(".note").click(function() {
    $(".note_info").toggle();
  });

  $(".lau_view").click(function() {
    $(".lau_hiden").toggle();
  });

  $('#BackendOut').click(function(){
    if(confirm('確定登出?')){
      window.location.href="<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/index/logout';?>";
    }
  });
  var c = 1800;
  function startTime() {
    $('#TimeOut').html(c);
    c -= 1;
    if (c <= 0){
      window.location.href="<?echo CCODE::DemoPrefix.'/'.(!empty($this->autoful->FileName)?$this->autoful->FileName:'admin_sys').'/index/logout';?>";
    }
    var t = setTimeout(function(){ startTime() }, 1000);
  }
</script>
