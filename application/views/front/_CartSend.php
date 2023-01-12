<!-- add_info -->
<div id="add_info" class="fancy-box">
  <section class="addinfo">
    <!--add-list-->
    <div class="add-list-atitle">
      <dd>收件人通訊錄</dd>
      <dd><a class="btn-style10 fancybox" href="#add_info02" id="OpenSendInfo">新增收件人</a></dd>
    </div>
    <div class="add-list" id="SendDiv"></div>
    <!--add-list-->
  </section>
</div>
<!-- //add_info -->
  <!-- add_info -->
  <div id="add_info02" class="fancy-box">
    <section class="addinfo">
      <!--add-list-->
      <div class="add-list-atitle">
        <dd id="Sendtitle">新增收件人資料</dd>
      </div>
      <ul class="styled-input" style="margin-top: 30px">
        <li class="half">
          <h2>公司大名</h2>
          <input type="text" id="d_cname"/>
        </li>
        <li class="half">
          <h2>收貨人姓名*</h2>
          <input type="text" id="d_name">
        </li>
        <li class="half">
          <h2>手機號碼*</h2>
          <input type="text" id="d_mobile">
        </li>
        <li class="half">
          <h2>市話</h2>
          <input type="text" id="d_phone">
        </li>
        <li>
          <h2>地址*</h2>
          <div class="mem_add" id="twzipcode03">
            <div data-role="county" data-style="mem_add_inpt" class="mem_inpt"></div>
            <div data-role="district" data-style="mem_add_inpt" class="mem_inpt"></div>
            <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt"></div>
          </div>
          <input type="text2" id="d_address"/>
        </li>
        <li style="text-align:center;">
          <input type="hidden" name="d_id" id="d_id">
          <input type="button" class="btn-style07" value="確認送出" id="SendButton"/>
          <input type="reset" class="btn-style07" id="ResetButton" value="重新填寫"/>
        </li>
      </ul>
      <!--add-list-->
    </section>
  </div><!-- //add_info -->
<script>
$('#OpenSendInfo').click(function(){
  $('#Sendtitle').html('新增收件人資料');
  d_id=$('#d_id').val('');
  CleanSend();
});
$('#GetSend').click(function(){
  $.ajax({
    url:'<? echo site_url('cart/GetSend')?>',
    type:'POST',
    dataType: 'text',
    success: function( json ){
      $('#SendDiv').html(json);
      SendEditDelFunction();
    }
  });
});
$('#twzipcode03').twzipcode({
    'countyName'   : 'd_Sendcity',   // 預設值為 county
    'districtName' : 'd_Sendarea', // 預設值為 district
    'zipcodeName'  : 'd_Sendzip',  // 預設值為 zipcode
    'readonly': false
});
$('#SendButton').click(function(){
  d_id=$('#d_id').val();
  cname=$('#d_cname').val();
  name=$('#d_name').val();
  mobile=$('#d_mobile').val();
  phone=$('#d_phone').val();
  city=$('#twzipcode03').twzipcode('get', 'county');
  area=$('#twzipcode03').twzipcode('get', 'district');
  zip=$('#twzipcode03').twzipcode('get', 'zipcode');
  address=$('#d_address').val();
  if(name!='' && mobile!='' && city!='' && area !='' && zip!=''&& address!=''){
    var Postarray=[cname,name,mobile,phone,city[0],area[0],zip[0],address];
    $.ajax({
      url:'<? echo site_url('cart/AddSend')?>',
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
$('#ResetButton').click(function(){
  CleanSend();
});
function CleanSend(){
  cname=$('#d_cname').val(' ');
  name=$('#d_name').val(' ');
  mobile=$('#d_mobile').val(' ');
  phone=$('#d_phone').val(' ');
  $('#twzipcode03').twzipcode('reset');
  address=$('#d_address').val(' ');
}
function SendEditDelFunction(){
  $('.fancybox').fancybox();
  $('a[id="EditSend"]').click(function(){
    $('#Sendtitle').html('修改收件人資料');
    id=$(this).attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/GetSend')?>',
      type:'POST',
      data:{
          id:id,
      },
      dataType: 'json',
      success: function( json ){
        console.log(json);
        $('#d_cname').val(json.d_cname);
        $('#d_name').val(json.d_name);
        $('#d_mobile').val(json.d_mobile);
        $('#d_phone').val(json.d_phone);
        $('#twzipcode03').twzipcode('set', {
            'county': json.d_city,
            'district': json.d_area,
            'zipcode': json.d_zip
        });
        $('#d_address').val(json.d_address);
        $('#d_id').val(json.d_id);
      }
    });
  });
  $('a[id="PresetSend"]').click(function(){
    elem = $(this);
    d_id=elem.attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/PresetSend')?>',
      type:'POST',
      data:{
          d_id:d_id,
      },
      dataType: 'text',
      success: function( json ){
        $('a[id="PresetSend"]').text('預設');
        if (json=="Y") {
          elem.text('取消預設');
        }
      }
    });
  });
  $('a[id="DelSend"]').click(function(){
    d_id=$(this).attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/DelSend')?>',
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
  $('a[id="PostSend"]').click(function(){
    d_id=$(this).attr('rel');
    $.ajax({
      url:'<? echo site_url('cart/GetSend')?>',
      type:'POST',
      data:{
          id:d_id,
      },
      dataType: 'json',
      success: function( json ){
        parent.$.fancybox.close();
        $('input[name="d_cname"]').val(json.d_cname);
        $('input[name="d_name"]').val(json.d_name);
        $('input[name="d_mobile"]').val(json.d_mobile);
        $('input[name="d_phone"]').val(json.d_phone);
        $('input[name="d_address"]').val(json.d_address);
        $('#twzipcode').twzipcode('set', {
            'county': json.d_city,
            'district': json.d_area,
            'zipcode': json.d_zip
        });
      }
    });
  });
}
</script>
