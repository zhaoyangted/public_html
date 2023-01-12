<script type="text/javascript" src="js/jquery.twzipcode.min.js"></script>
<script>
  $(function() {
    // 生日日期
	$( "#datepicker" ).datepicker();
	// 地址選擇
	$('#twzipcode').twzipcode();
	$.datepicker.setDefaults( $.datepicker.regional[ "zh-TW" ] );
  });
</script>
<div class="mem_add" id="twzipcode">
                      <div data-role="county" data-style="mem_add_inpt" class="mem_inpt" data-value="110"></div>
                      <div data-role="district" data-style="mem_add_inpt" class="mem_inpt" data-value="臺北市"></div>
                      <div data-role="zipcode" data-style="mem_add_inpt" class="mem_inpt" data-value="信義區"></div>
                    </div>
                    <input type="text2" id="add" value="北區進化北路392-2號號8樓之2"/>