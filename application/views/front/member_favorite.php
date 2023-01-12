<?php include '_header.php';?>
<main>
  <article>
    <!--bread-->
    <div class="box-1">
      <ul class="breadcrumb">
        <li><a href="<? echo site_url('') ?>">首頁</a></li>
        <li class="active">會員中心</li>
      </ul>
    </div>
    <!--//bread-->
    <div class="container">
      <div class="col-lg-">
        <section class="content_box">
          <div class="title01 center">會員中心</div>
          <?php include '_member_menu.php';?>
          <!--favorite-->
          <div class="favorite">
            <div class="titlebox">商品</div>
            <?php if (!empty($dbdata['dbdata'])): ?>
              <?php foreach ($dbdata['dbdata'] as $v): ?>
                <ul>
                  <div class="namebox">
                    <div class="name">
                      <dd><a href="<? echo site_url('products/info/'.$v['d_id']) ?>"><img src="<? echo site_url($v['d_img1']) ?>" /></a></dd>
                      <dt>
                        <div class="tt"><a href="<? echo site_url('products/info/'.$v['d_id']) ?>" target="_blank"><? echo $v['d_title'] ?></a></div>
                        <div class="sbox">
                          <div class="dtt">商品編號</div>
                          <div class="spec"><? echo $v['d_model'] ?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">商品規格</div>
                          <div class="spec"><? echo $v['d_spectitle'] ?></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt"><?echo $this->autoful->Lvtitle;?></div>
                          <div class="spec"><span>NT$<? echo number_format($v['d_pro_price']) ?></span></div>
                        </div>
                        <div class="sbox">
                          <div class="dtt">商品加購</div>
                          <div class="spec">
                            <?php if (!empty($v['d_add_id'])): ?>
                              <? $Add_title = explode(',',$v['d_add_title']); ?>
                              <? $Add_price = explode(',',$v['d_add_price']); ?>
                              <? $Add_id = explode(',',$v['d_add_id']); ?>
                              <? $Add_stock = explode(',',$v['d_add_stock']); ?>
                              <? $Add_enable = explode(',',$v['d_add_enable']); ?>
                              <select class="select_pd">
                                <option>請選擇</option>
                                  <? for ($i=0; $i < count($Add_id) ; $i++) { ?>
                                    <?php if ($Add_stock[$i]>0&&$Add_enable[$i]=='Y'): ?>
                                      <option value="<? echo $Add_id[$i] ?>" <? echo !empty($AID[$v['d_id']]) && $AID[$v['d_id']] == $Add_id[$i] ?'selected':''; ?> ><? echo $Add_title[$i] ?> + NT$.<? echo $Add_price[$i] ?></option>
                                    <?php endif; ?>
                                  <? } ?>
                              </select>
                            <?php else: ?>
                              無
                            <?php endif; ?>
                          </div>
                        </div>
                      </dt>
                    </div>
                  </div>
                  <div class="numberbox">
                    <div class="del">
                      <a href="javascript:void(0)" onclick="delFavorite(<? echo $v['d_id'] ?>);" class="btn-style06"><i class="fas fa-times"></i><span>刪　　除</span></a>
                      <a href="javascript:void(0)" rel="<? echo $v['d_id'];?>" onclick="AddCart(this)" class="btn-style06"><i class="fas fa-shopping-bag"></i><span>加入購物</span></a>
                    </div>
                  </div>
                </ul>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <!--//favorite-->
          <? echo !empty($dbdata['dbdata'])?$dbdata['PageList']:'';?>
        </section>
      </div>
    </div>
  </article>
</main>
<?php include '_footer.php';?>
<script>
  function delFavorite(id) {
    $.ajax({
      type: 'POST',
			dataType: 'text',
			url: '<? echo site_url('member/delFavorite') ?>',
			data: {
        'id' : id,
      },
			success: function(data) {
        if (data=='success') {
          alert('刪除收藏項目成功！');
          location.reload();
        }else{
          alert('刪除收藏項目失敗，請重新整理後再試！');
        }
			},
			error: function(data) {
				alert('系統發生未知的錯誤，請重新整理後再試！');
			}
    });
  }

  // 加入購物車
  function AddCart(elem){
    Id=$(elem).attr('rel');
    num=1;
    AID=$(elem).closest('ul').find('select').val();
    if(num<=0){
      alert('數量不得為零或負數');
      return '';
    }

    $.ajax({
      type: "POST",
      url: "<? echo site_url('products/Addcart')?>",
      data: {
        did:Id,
        num:num,
        AID:AID
      },
      dataType: "text",
      success: function(data) {
        if(data=='ok'){
          alert('已加入購物車');
          location.reload();
        }else{
          alert(data);
        }
      },
    });
  }
</script>
