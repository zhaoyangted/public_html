<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="tw">
<head>
    <meta charset="utf-8">
    <title>Site Under Maintenance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
  body {
  /* background: rgb(103, 66, 230);
  background: linear-gradient(
    135deg,
    rgba(103, 66, 230, 1) 35%,
    rgba(114, 68, 237, 1) 100%
  ); */
  /* color: white; */
  background:url(https://bgtwmedia.s3.ap-northeast-1.amazonaws.com/uploads/IMG_8142.JPG);
  background-repeat: no-repeat;
  font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial,
    sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
  z-index: 9999;
  }

.error-image {
  max-width: 200px;
  margin: 5px;
  text-align: center;
}

.error-image h1  {
  color:#111;
  font-size: 120px;
  margin: 48px auto 20px;
  margin-top:40px;
}
.error-image h2 h3 {
  margin-top:40px;
}
.error-msg-container h2{
  color:#111;
  font-size: 40px;
  font-weight:200;
  margin-top:40px;
  margin: 5px auto 20px;
  
}
.fluid {
  background:url(https://bgtwmedia.s3.ap-northeast-1.amazonaws.com/uploads/IMG_8142.JPG);
  background-repeat: no-repeat;
}

.error-msg-container {
  display: flex;
  flex-direction:row;
  justify-content: center;
  padding:20px;
  }
  .error-msg{
  width: 600px;
  text-align: center;
  margin: 100px auto 30px auto;
  text-align: center;
  background-color:#f4f8fb;
  opacity: 0.6;
  border-radius:20px;
  z-index: 999;
  padding:40px;
  backdrop-filter:blur(10px);
  }
img {
    max-width:200px;
    height: auto;
    width: min-content;
    position: relative;
    left: 0;
    right: 0;
    margin: auto;
    display: block;
    
}

</style>
</head>
<body >
  <div class="error-msg-container">
    <div class="error-msg">
        <h1>網站維護公告</h1>
        <h2>Network Maintenance Notice</h2>
        <h3>為提供您更好的用戶體驗，網站將於4月8日00:00至4月9日23:59進行系統維護</h3>
        <h3>造成不便敬請見諒！</h3>
        <h3>如有其他需求請掃描QR Code，</h3>
        <h3>加入LINE官方客戶進行諮詢。</h3>
    </div>
  </div>
  <div class="error-msg-container">
        <div class="error-image">
          <img src="https://bgtwmedia.s3.ap-northeast-1.amazonaws.com/uploads/IMG_8143.JPG"/>
        </div>
        
  </div>
  <div class="error-msg-container">
    <div class="error-image">
          <img src="https://bgtwmedia.s3.ap-northeast-1.amazonaws.com/uploads/IMG_8144.PNG"/>
    </div>
</div>
</body>
</html>