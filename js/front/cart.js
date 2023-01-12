// JavaScript Document

$(function(){
    $('select#sp3_t1').change(function()
        {
        if($(this).val()=="0"){
          $(".sp3t1ax01").fadeOut(100);
          $(".outisland").fadeOut(100);
        }

        if($(this).val()=="1"){
          $(".sp3t1ax01").fadeIn(300);
          $(".outisland").fadeOut(100);
      		$(".sp_ship01").fadeIn(300);
      		$(".sp_ship02").fadeOut(100);
        }else if($(this).val()=="2"){
          $(".sp3t1ax01").fadeIn(300);
          $(".outisland").fadeIn(300);
      		$(".sp_ship01").fadeIn(300);
      		$(".sp_ship02").fadeOut(100);
        }

    });

    $('select#sp3_t2').change(function()
        {
        if($(this).val()=="0"){
        $(".sp3t2ax01").fadeOut(100);
        $(".sp3t2ax02").fadeOut(100);
        $(".sp3t2ax03").fadeOut(100);
		$(".sp3t2ax04").fadeOut(100);
        }

        if($(this).val()=="1"){
        $(".sp3t2ax01").fadeIn(300);
        $(".sp3t2ax02").fadeOut(100);
		$(".sp3t2ax03").fadeOut(100);
		$(".sp3t2ax04").fadeOut(100);
        }

        if($(this).val()=="2"){
        $(".sp3t2ax02").fadeIn(300);
        $(".sp3t2ax01").fadeOut(100);
		$(".sp3t2ax03").fadeOut(100);
		$(".sp3t2ax04").fadeOut(100);
        }

        if($(this).val()=="3"){
        $(".sp3t2ax03").fadeIn(300);
        $(".sp3t2ax01").fadeOut(100);
		$(".sp3t2ax02").fadeOut(100);
		$(".sp3t2ax04").fadeOut(100);

		$(".sp_ship02").fadeIn(300);

        }

        if($(this).val()=="4"){
		$(".sp3t2ax04").fadeIn(300);
        $(".sp3t2ax01").fadeOut(100);
		$(".sp3t2ax02").fadeOut(100);
		$(".sp3t2ax03").fadeOut(100);
        }

    });

});

$(function(){
    id=$('input[name="d_invoice"]:checked').val();
    GetInvoicejs(id);
    $('input[name="d_invoice"]').change(function(){
        id=$(this).val();
        GetInvoicejs(id);

    });
    $(".request").click(function(){
        $(".invoice_box").fadeToggle(800);
        $(".invoice_box02").fadeOut(100);
    });
    $(".request02").click(function(){
        $(".invoice_box02").fadeToggle(800);
        $(".invoice_box").fadeOut(100);
    });

    $('select#select_invoice').change(function(){
        if($(this).val()=="Other"){
            $(".invoice03").fadeIn(300);
        }else{
            $(".invoice03").fadeOut(100);
        }
    });

});
function GetInvoicejs(id){
    if(id==2){
        $(".invoice_box02").fadeToggle(800);
        $(".invoice_box").fadeOut(100);
        $('#Invoice_fancybox').hide();
    }else if(id==3){
        $(".invoice_box").fadeToggle(800);
        $(".invoice_box02").fadeOut(100);
        $('#Invoice_fancybox').show();
    }else{
        $(".invoice_box").fadeOut(100);
        $(".invoice_box02").fadeOut(100);
        $('#Invoice_fancybox').hide();
    }
}
