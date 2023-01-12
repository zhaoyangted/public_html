<!doctype html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1 user-scalable=no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=9,IE=10,IE=11,IE=EDGE" />
	<link rel="stylesheet" type="text/css" href="<?echo base_url('css/backend/login.css');?>"/>
	<script src="<? echo base_url('js/backend/jquery-3.1.1.js')?>"></script>
	<title><? echo !empty($header[0]['d_title'])?$header[0]['d_title'].'網站管理台':"未設定"?></title>
</head>
<body>
	<div class="login-wrapper">
		<div class="container">
			<div class="login-block">
				<div class="login-block_panel">
					<div class="login_title">網站管理平台
					</div>
					<div class="login_content row">
						<div class="left_block col-md-6 col-sm-6">
							<? echo !empty($header[1]['d_title'])?'<img src="'.base_url($header[1]['d_title']).'" class="login-logo">':"未設定"?>
						</div>
						<div class="right_block col-md-6 col-sm-6">
							<form class="cd-form" action="<? echo base_url().(!empty($this->Filename)?$this->Filename:'admin_sys').'/index/login_chk'?>" method="post" >
								<div class="login-input">
									<div class="pic-item">
										<img src="<?echo base_url('images/backend/login/ico_account.png');?>" >
									</div>
									<input name="d_account" type="text" class="textbox">
								</div>
								<div class="login-input">
									<div class="pic-item">
										<img style="width: 15px;" src="<?echo base_url('images/backend/login/ico_padlock.png');?>" >
									</div>
									<input type="password" name="d_password" type="text" class="textbox">
								</div>
								<div class="login-input final">
									<div class="pic-item">
										<img src="<?echo base_url('images/backend/login/ico_pin.png');?>" >
									</div>
									<input name="vcode" type="text" class="textbox">
								</div>
								<div class="pin_test">
									<img class="pin-pic" src="<? echo base_url().(!empty($this->Filename)?$this->Filename:'admin_sys').'/index/make_vcode_img'?>" style="zoom:200%" id="codeimg"> 
									<a href="javascript:void(0);" id="imgcode">
							         <img src="<?echo base_url('images/backend/login/img_pinReplace.png');?>" >
							        </a>
								</div>
								<input class="login_btn" type="submit" value="登入">
						</div>
					</div>


					</form>
				</div>
					<img class="login-block_bg" src="<?echo base_url('images/backend/login/img_reflection.png');?>" >
			</div>
		</div>
	</div>
	</div>
	</div>
</body>
</html>
<script>
$('#imgcode').click(function(){
    $('#codeimg').attr('src', $('#codeimg').attr('src')+'?'+Math.random());
});
</script>