<div class="top-category">
  <ul>
    <li><a href="<? echo site_url('member') ?>" id="main"><i class="fas fa-user"></i> 會員中心</a></li>
    <li><a href="<? echo site_url('member/account') ?>" id="account"><i class="fas fa-user-edit"></i> 會員資料修改</a></li>
    <li><a href="<? echo site_url('member/orders') ?>"  id=orders><i class="fas fa-file-alt"></i> 購物紀錄與訂單查詢</a></li>
    <!-- <li><a href="<? echo site_url('member/invoice') ?>" id="invoice"><i class="fas fa-file-invoice-dollar"></i> 電子發票歸戶</a></li> -->
    <li><a href="<? echo site_url('member/point') ?>" id="point"><i class="fas fa-dollar-sign"></i> 會員點數查詢</a></li>
    <li><a href="<? echo site_url('member/favorite') ?>" id="favorite"><i class="far fa-heart"></i> 我的收藏</a></li>
    <!-- <li><a href="<? echo site_url('member/friend') ?>" id="friend"><i class="fas fa-user-friends"></i> 邀請好友加入會員</a></li> -->
  </ul>
</div>
<script>
pname=location.pathname;
path='main';
if(pname.match("orders")){
    path='orders';
}else if(pname.match("invoice")){
    path='invoice';
}else if(pname.match("point")){
    path='point';
}else if(pname.match("favorite")){
    path='favorite';
}else if(pname.match("friend")){
    path='friend';
}else if(pname.match("account")){
    path='account';
}
$('#'+path).addClass("active");
</script>
