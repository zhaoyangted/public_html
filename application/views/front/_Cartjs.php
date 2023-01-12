<script>
// 加入最愛
$('input[id="AddFavourite"]').click(function(){
  Id=$(this).attr('rel');
  $.ajax({
    type: "post",
    url: '<? echo CCODE::DemoPrefix.('/products/AddFavourite')?>',
    data: {
        PID:Id
    },
    dataType :'text',
    cache: false,
    success: function (response) {
      if(response=='NoLogin'){
        alert('請先登入會員');
        window.location.href="<? echo CCODE::DemoPrefix.('/login')?>";
        // location.reload();
      }
      if(response=='IsHave' || response=='Success'){
        alert('已加入我的最愛');
        // location.reload();
      }
    }
  });
});

</script>  