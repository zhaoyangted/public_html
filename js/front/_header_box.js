$(function () {
      $(window).scroll(function () {
        var LMOV = $(this).scrollTop()
            if( LMOV >= "100" ) {
              $('.HeadLogoBx').addClass("ovhe");
              $('.LogoBox').addClass("ovhe");
              $('.LogoTXBX').addClass("ovhe");
              $('.TopLinkb2').addClass("ovhe");
              $('.TopLinkb3').addClass("ovhe");
              $('.searchAllBox').addClass("ovhe");
              $('.Sech_ASBox').addClass("ovhe");
              $('.select_inpt').addClass("ovhe");
              $('.searICBXsit').addClass("ovhe");
              $('.HtIcBx01').addClass("ovhe");
              $('.ICBox').addClass("ovhe");
              $('.ICBoTX').addClass("ovhe");
              $('.select_all_box_r03').addClass("ovhe");
              $('.HeadNavUB').addClass("ovhe");
            } else {
              $('.HeadLogoBx').removeClass("ovhe");
              $('.LogoBox').removeClass("ovhe");
              $('.LogoTXBX').removeClass("ovhe");
              $('.TopLinkb2').removeClass("ovhe");
              $('.TopLinkb3').removeClass("ovhe");
              $('.searchAllBox').removeClass("ovhe");
              $('.Sech_ASBox').removeClass("ovhe");
              $('.select_inpt').removeClass("ovhe");
              $('.searICBXsit').removeClass("ovhe");
              $('.HtIcBx01').removeClass("ovhe");
              $('.ICBox').removeClass("ovhe");
              $('.ICBoTX').removeClass("ovhe");
              $('.select_all_box_r03').removeClass("ovhe");
              $('.HeadNavUB').removeClass("ovhe");
            }
      });

      $('li .nav-submenu').click(function() {
        if ($('ul',this).length==0) {
          location.href=$('a',this).attr('href');
        }
      });
});
