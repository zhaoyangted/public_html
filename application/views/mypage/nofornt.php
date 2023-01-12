<!-- 沒有前台時用的畫面-->
<?$WebConfigData=$this->webmodel->GetWebData();?>
目前無前台<br>
<a href="<? echo site_url($WebConfigData[1]).'/index';?>">後臺管理</a>