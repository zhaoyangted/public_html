<?php
  $WebConfigData=$this->webmodel->GetWebData();
?>
<!DOCTYPE html>
<html>
  <head>
    <style>
    .center {
      text-align: center
    }
    .e404 {
      width: auto;
      display: block;
      padding: 0
    }
    .e404 img {
      max-width: 100%
    }
    .e404 dd {
      width: auto;
      display: inline-block;
      margin: 5px 15px;
      padding: 0;
      list-style: none
    }
    .e404 dd.error {
      font-family: Helvetica, sans-serif;
      font-size: 40px;
      font-weight: 700;
      color: #333;
      margin: 11px 0 0;
      vertical-align: top
    }
    .e404 dd.text {
      font-size: 14px;
      color: #333;
      text-align: left;
      line-height: 1.6
    }
    .e404 dd.text a {
      color: #4DB0B9
    }
    .e404 dd.topbtn {
      vertical-align: top;
      margin: 18px 0 0
    }
    .e404 dd a.btn {
      outline: none;
      border-radius: 3px;
      font-size: 13px;
      line-height: 13px;
      text-align: center;
      color: #ffF;
      padding: 5px 10px;
      border: 2px solid #ff4e57;
      text-decoration: none;
      vertical-align: top;
      background: #ff4e57
    }
    .e404 dd .btn:hover {
      background: #4DB0B9;
      border: 2px solid #4DB0B9;
      color: #FFF
    }
    img { vertical-align: top; }
    * { -webkit-box-sizing: border-box; box-sizing: border-box; }
    .user_editor img.f-nav {
      z-index: 9999;
      position: fixed;
      left: 0;
      top: 0;
      width: 100%
    }
	  body {background: url(https://www.jddt.tw/images/404_bg.gif) repeat; font-family:"微軟正黑體", Arial, Helvetica, sans-serif,"新細明體";}
    </style>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
      <div class="e404 center" style="margin-top:5%;">
        <div style="margin:0 0 10px 0;"><img src="https://www.jddt.tw/images/404.png"></div>
        <dd class="error">ERROR</dd>
        <dd class="text">此網頁不存在，請您點選下列網址，<br>進入<? echo $WebConfigData[2] ?>首頁：<a href="<? echo base_url() ?>">
            <? echo base_url() ?></a></dd>
        <dd class="topbtn"><a class="btn" href="javascript:history.back(1)">返回到剛才的頁面 ></a></dd>
      </div>
   
  </body>
</html>
