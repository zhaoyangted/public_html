<!-- invoice_info -->
<div id="invoice_info" class="fancy-box">
  <section class="addinfo">
    <!--add-list-->
    <div class="add-list-atitle">
      <dd>統編備忘錄</dd>
      <dd><a class="btn-style10 fancybox" href="#invoice_info02" id="OpenInvoiceInfo">新增公司統編</a></dd>
    </div>
    <div class="invoice-data" id="InvoiceDiv"></div>
    <!--add-list-->
  </section>
</div><!-- //invoice_info -->

<!-- add_info -->
<div id="invoice_info02" class="fancy-box">
  <section class="addinfo">
    <!--add-list-->
    <div class="add-list-atitle">
      <dd id="Invoicetitle">新增公司統編</dd>
    </div>
      <ul class="styled-input" style="margin-top: 30px">
        <li class="half">
          <h2>公司大名</h2>
          <input type="text" id="d_vcname" >
        </li>
        <li class="half">
          <h2>統一編號*</h2>
          <input type="text" id="d_vum">
        </li>
        <!-- <li>
          <h2>E-mail*</h2>
          <input type="text" id="d_vmail">
        </li> -->
        <li>
          <h2>中獎寄送地址*</h2>
          <div class="mem_add" id="twzipcode05">
            <div data-role="county" data-style="mem_add_inpt" class="mem_inpt"></div>
            <div data-role="district" data-style="mem_add_inpt" class="mem_inpt"></div>
            <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt"></div>
          </div>
          <input type="text2" id="d_vaddress">
        </li>
        <li style="text-align:center;">
          <input type="hidden" id="d_vid">
          <input type="button" class="btn-style07" value="確認送出" id="InvoiceButton"/>
          <input type="reset" class="btn-style07" id="VResetButton" value="重新填寫"/>
        </li>
      </ul>
    <!--add-list-->
  </section>
</div>
<script>
$('#OpenInvoiceInfo').click(function(){
  $('#Invoicetitle').html('新增公司統編');
  $('#d_vid').val('');
  CleanInvoice();
});
$('#GetInvoice').click(function(){
  $.ajax({
    url:'<? echo site_url('cart/GetInvoice')?>',
    type:'POST',
    dataType: 'text',
    success: function( json ){
      $('#InvoiceDiv').html(json);
      InvoiceEditDelFunction();
    }
  });
});
$('#twzipcode02').twzipcode({
    'countyName'   : 'd_Invoicecity',   // 預設值為 county
    'districtName' : 'd_Invoicearea', // 預設值為 district
    'zipcodeName'  : 'd_Invoicezip',  // 預設值為 zipcode
    'readonly': false
});
$('#twzipcode05').twzipcode({
    'countyName'   : 'd_Invoicecity',   // 預設值為 county
    'districtName' : 'd_Invoicearea', // 預設值為 district
    'zipcodeName'  : 'd_Invoicezip',  // 預設值為 zipcode
    'readonly': false
});
$('#InvoiceButton').click(function(){
  d_id=$('#d_vid').val();
  cname=$('#d_vcname').val();
  vum=$('#d_vum').val();
  // vmail=$('#d_vmail').val();
  city=$('#twzipcode05').twzipcode('get', 'county');
  area=$('#twzipcode05').twzipcode('get', 'district');
  zip=$('#twzipcode05').twzipcode('get', 'zipcode');
  address=$('#d_vaddress').val();
  if(vum!='' && city!='' && area !='' && zip!=''&& address!=''){
    var Postarray=[cname,vum,vmail,city[0],area[0],zip[0],address];
    $.ajax({
      url:'<? echo site_url('cart/AddInvoice')?>',
      type:'POST',
      data:{
          Postarray:Postarray,
          d_id:d_id
      },
      dataType: 'text',
      success: function( json ){
        alert(json);
        parent.$.fancybox.close();
        parent.$.fancybox.close();
        // location.reload();
      }
    });
  }else{
    alert('必填欄位尚未填寫');
    return '';
  }
});
$('#VResetButton').click(function(){
  CleanInvoice();
});
function CleanInvoice(){
  $('#d_vcname').val(' ');
  $('#d_vum').val(' ');
  // $('#d_vmail').val(' ');
  $('#twzipcode05').twzipcode('reset');
  $('#d_vaddress').val(' ');
}
function InvoiceEditDelFunction(){
  $('.fancybox').fancybox();
  $('a[id="EditInvoice"]').click(function(){
    $('#Invoicetitle').html('修改公司統編');
    id=$(this).attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/GetInvoice')?>',
      type:'POST',
      data:{
          id:id,
      },
      dataType: 'json',
      success: function( json ){
        $('#d_vcname').val(json.d_cname);
        $('#d_vum').val(json.d_um);
        // $('#d_vmail').val(json.d_mail);
        $('#twzipcode05').twzipcode('set', {
            'county': json.d_city,
            'district': json.d_area,
            'zipcode': json.d_zip
        });
        $('#d_vaddress').val(json.d_address);
        $('#d_vid').val(json.d_id);
      }
    });
  });
  $('a[id="PresetInvoice"]').click(function(){
    elem = $(this);
    d_id=elem.attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/PresetInvoice')?>',
      type:'POST',
      data:{
          d_id:d_id,
      },
      dataType: 'text',
      success: function( json ){
        $('a[id="PresetInvoice"]').text('預設');
        if (json=="Y") {
          elem.text('取消預設');
        }
      }
    });
  });
  $('a[id="DelInvoice"]').click(function(){
    d_id=$(this).attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/DelInvoice')?>',
      type:'POST',
      data:{
          d_id:d_id,
      },
      dataType: 'text',
      success: function( json ){
        alert(json);
        parent.$.fancybox.close();
      }
    });
  });
  $('a[id="PostInvoice"]').click(function(){
    d_id=$(this).attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/GetInvoice')?>',
      type:'POST',
      data:{
          id:d_id,
      },
      dataType: 'json',
      success: function( json ){
        parent.$.fancybox.close();
        // $("input[name='d_invoice'][value='3']").attr('checked',true);
        // GetInvoicejs(3);

        $('input[name="d_icname"]').val(json.d_cname);
        $('input[name="d_ium"]').val(json.d_um);
        // $('input[name="d_imail"]').val(json.d_mail);
        $('input[name="d_iaddress"]').val(json.d_address);
        $('#twzipcode02').twzipcode('set', {
            'county': json.d_city,
            'district': json.d_area,
            'zipcode': json.d_zip
        });
      }
    });
  });
}
</script>
