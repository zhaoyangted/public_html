function initMenu() {
    $(".sub-menu").hide();
    $(".current_page_item .sub-menu").show();
    $('#menu li a').click(

    function() {
        var checkElement = $(this).next();
        if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            return false;
        }
        if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            console.log(checkElement.parentsUntil('#menu'));
            $('#menu ul:visible').not(checkElement.parentsUntil('#menu')).slideUp('normal');
            checkElement.slideDown('normal');
            return false;
        }
    });
}
$(function() {
    initMenu();
});


$(document).ready(function () {
    // 初始化:隱藏所有內容
    $('.pd-accord-content').hide();
    

    $('.accord-title').click(function(e){
        e.preventDefault();
        // 使得使用者只能觀看單筆內容
        $('pd-.accord-content').slideUp();
        // 預設所有圖示回到 + 號
        $('.accord-title').find('i').attr('class', 'fa fa-plus');
        // 判斷當前的內容區 css 是否為隱藏
        if($('+.pd-accord-content',this).css('display') == 'none') {
            // 若是 則顯示效果
            $('+.pd-accord-content', this).slideDown();
            // icon change
            $(this).find('i').attr('class', 'fa fa-minus');
        }
        /*
         * 要讓使用者能同時觀看多筆內容 請打開 else 內容 並 隱藏行數9 
         */
        // else {
        //     $('+.accord-content', this).slideUp();
        // }

    });
	//mobile-list展開
	  $('.pro-item-title').click(function(event){
        event.preventDefault();
		$('.mobile-prolist').toggleClass('is-active');

    })
    
});
