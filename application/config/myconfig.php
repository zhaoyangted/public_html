<?php
session_start();
class CCODE {
	const LANGUAGE="zh_tw";//預設語系//zh_tw啟用簡體轉換
	const RECORD_NUM=20;//預設每頁資料筆數
	const ADMIN="beautygarage_admin";//二位數session代碼，用來區分其他網站不要造成衝突
	const AGENT="beautygarage_agent";//二位數session代碼，用來區分其他網站不要造成衝突
	const MEMBER="beautygarage_member";//二位數session代碼，用來區分其他網站不要造成衝突
	const VIEW_SPACE=true;//html縮排減少檔案傳送byte數,增加速度
	const SH="";//搜尋列表專用
	const DemoPrefix="";  //DEmo前綴詞

}
$config["site_config"]=array();//預設
