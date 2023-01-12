<!DOCTYPE html>
<html lang="zh-tw">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="Shortcut icon" href="<? echo !empty($header[2]['d_title'])?$header[2]['d_title']:"/"?>" type="image/x-icon" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="expires" content="0"> 
  <meta http-equiv="cache-control" content="no-cache"> 
  <meta http-equiv="pragma" content="no-cache"> 
<script src="<? echo CCODE::DemoPrefix.('/js/templates/jquery-1.9.1.js')?>"></script>
  
</head>

</body>
</html>
<script type="text/javascript">
  alert('登出成功');
  var stateObj = { test: "123" };

  history.pushState(stateObj, "new Page", "<? echo CCODE::DemoPrefix.'/'.$this->Filename?>/index/logout1/<? echo $Backurl;?>");

  window.location.href = '<? echo CCODE::DemoPrefix.'/'.$this->Filename?>/index/logout1/<? echo $Backurl;?>';
</script>