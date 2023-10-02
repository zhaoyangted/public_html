<!DOCTYPE html>
<html lang="zh-Hant-TW">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>台灣千冠莉</title>
    <link rel="stylesheet" type="text/css" href="css/edm.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/slick.js"></script>
    
    <link rel="shortcut icon" href="images/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <link rel="stylesheet" href="css/js-offcanvas.css">
    <script type="text/javascript" src="js/js-offcanvas.pkgd.js"></script>
    <script>
		$( function(){

			$( document ).on( "beforecreate.offcanvas", function( e ){
				var dataOffcanvas = $( e.target ).data('offcanvas-component');
				console.log(dataOffcanvas);
				dataOffcanvas.onInit =  function() {
					console.log(this);
				};
			} );

			$( document ).on( "create.offcanvas", function( e ){
				var dataOffcanvas = $( e.target ).data('offcanvas-component');
				console.log(dataOffcanvas.options);
				dataOffcanvas.onOpen =  function() {
					console.log('Callback onOpen');
				};
				dataOffcanvas.onClose =  function() {
					console.log('Callback onClose');
				};
			} );

			$( document ).on( "clicked.offcanvas-trigger clicked.offcanvas", function( e ){
				var dataBtnText = $( e.target ).text();
				console.log(e.type + '.' + e.namespace + ': ' + dataBtnText);
			} );

			$( document ).on( "open.offcanvas", function( e ){
				var dataOffcanvasID = $( e.target ).attr('id');
				console.log(e.type + ': #' + dataOffcanvasID);
			} );

			$( document ).on( "resizing.offcanvas", function( e ){
				var dataOffcanvasID = $( e.target ).attr('id');
				console.log(e.type + ': #' + dataOffcanvasID);
			} );

			$( document ).on( "close.offcanvas", function( e ){
				var dataOffcanvasID = $( e.target ).attr('id');
				console.log(e.type + ': #' + dataOffcanvasID);
			} );

			$( document ).on( "destroy.offcanvas", function( e ){
				var dataOffcanvasID = $( e.target ).attr('id');
				console.log(e.type + ': #' + dataOffcanvasID);
			} );

			$( '#bottom' ).on( "create.offcanvas", function( e ){
				var api = $(this).data('offcanvas-component');

				console.log(api);
				$('.js-destroy').on('click', function () {
					api.destroy();
					//$( '#top' ).data('offcanvas-component').destroy();
					console.log(api);
					console.log( $( '#top' ).data() );
				});
			} );

			$( '#left' ).offcanvas( {
				modifiers: "left,overlay",
				triggerButton: '.js-offcanvas-trigger-left'
			} );

			$('.js-enhance').on('click', function () {
				console.log('enhance');
				$( document ).trigger( "enhance" );
			});

			$( document ).trigger( "enhance" );
		});
    </script>   
    
    <script src='js/jquery.zoom.js'></script>
<script type="text/javascript" src="js/jquery.localScroll.min.js"></script>
<script>
        $(document).ready(function() {
          // localscroll
          $('.localscroll').localScroll();
          // zoom
          $(window).on('load resize', function (){
            if ($(window).width() > 1210) {
              $('.edm .zoom').zoom();
            }
          });
        });
      </script>
    
   
    
  </head>
<body>
<div class="head">
    <div class="logo">
      <a href="edm.php"><div class="LogoBox"><img src="images/beautygarage_logo.svg" alt=""></div></a>
    </div>
    <div class="LogoSTTx"><a class="js-offcanvas-trigger" data-offcanvas-trigger="top" href="#top">2019全新型錄 <i class="fas fa-chevron-down"></i></a></div>
</div>
<div class="content_box04">
    <div class="edm">
      <ul class="zoom"><img src="images/pord/edm01.jpg" alt=""></ul>
      <ul class="zoom"><img src="images/pord/edm02.jpg" alt=""></ul>
      <ul class="zoom"><img src="images/pord/edm03.jpg" alt=""></ul>
      <ul class="zoom"><img src="images/pord/edm02.jpg" alt=""></ul>
      <ul class="zoom"><img src="images/pord/edm02.jpg" alt=""></ul>
      <ul class="zoom"><img src="images/pord/edm02.jpg" alt=""></ul>
      <ul class="zoom"><img src="images/pord/edm02.jpg" alt=""></ul>
    </div>    
</div>
<div class="foot">
    <a class="js-offcanvas-trigger edm_btn" data-offcanvas-trigger="bottom" href="#bottom">顯示本頁所有產品(5)</a>
    <a class="edm_btn" href="images/demo/123.pdf">下載<i class="fas fa-download"></i></a>
</div>
<aside class="js-offcanvas" data-offcanvas-options='{"modifiers":"top,fixed,overlay"}' id="top" role="complementary">
    <button data-focus class="js-offcanvas-close edm_btn3" data-button-options='{"modifiers":"m1,m2"}'><i class="fas fa-times"></i></button>
    <div class="dm_list">
      <ul>
        <div class="tt">所有刊物</div>
        <li><a href="edm.php">2019 全新型錄</a></li>
        <li><a href="edm.php">夢想廚房專刊</a></li>
        <li><a href="edm.php">美麗時尚節</a></li>
        <li><a href="edm.php">由後台建立</a></li>
        <li><a href="edm.php">2019 全新型錄</a></li>
        <li><a href="edm.php">夢想廚房專刊</a></li>
        <li><a href="edm.php">美麗時尚節</a></li>
        <li><a href="edm.php">由後台建立</a></li>
        <li><a href="edm.php">2019 全新型錄</a></li>
        <li><a href="edm.php">夢想廚房專刊</a></li>
        <li><a href="edm.php">美麗時尚節</a></li>
        <li><a href="edm.php">由後台建立</a></li>
      </ul>
    </div>
</aside>
<aside class="js-offcanvas " data-offcanvas-options='{"modifiers":"bottom, fixed, overlay"}' id="bottom" role="complementary">
    <button class="js-offcanvas-close edm_btn2" data-button-options='{"modifiers":"m1,m2"}'>隱藏本頁產品</button>
    <section class="edm_pd">
          <div class="shortcut">             
            <div class="slider responsive" >  
              <!--item_pd-->  
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>  
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts02.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts03.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts04.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts05.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/t02.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php target="_blank""><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <ul class="item_pd">
                        <li class="PordPHt"><a href="products_view.php" target="_blank"><img src="images/pord/Pord_ts01.jpg" alt=""></a></li>
                        <li class="PordTxs"><span class="PordTxsB"><a href="products_view.php" target="_blank">ANTIO 玫瑰萃取精華面膜1枚入</a></span></li>
                        <li class="TicPystxc">市價：NT$.870</li>
                        <li class="TicPystxc02">售價：NT$.820</li>
                        <ul class="bantBOX">
                            <li class="butt01">
                                <input type="submit" class="bant01" value="立即購買">
                            </li>
                            <li class="butt02">
                                <input type="button" class="bant02" value="加入最愛" onClick="location='member_favorite.php'">
                            </li>
                        </ul>
              </ul>
              <!--//item_pd--> 
            </div>
          </div>
    </section>
</aside>

<script>
    $('.edm').slick({
      dots: true,
	  arrows: true,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      adaptiveHeight: true
    });
</script>
<script type="text/javascript">
$('.responsive').slick({
  arrows: true,
  infinite: false,
  autoplay: false,
  speed: 300,
  slidesToShow: 7,
  slidesToScroll: 7,
  responsive: [
    {
      breakpoint: 1680,
      settings: {
        slidesToShow: 6,
        slidesToScroll: 6,
      }
    },
    {
      breakpoint: 1300,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 5,
      }
    },
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    },
	{
       breakpoint: 1000,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
      }
    },
  {
      breakpoint: 780,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
      }
    },
    {
      breakpoint: 510,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }
	  
    }
  ]
});
</script>