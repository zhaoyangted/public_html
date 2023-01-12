// 列表全選
function check_all(obj,cName){ 
    var checkboxs = document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
// 列表勾選啟動關閉功能
function ChangeEnable(Status){
  var str='';
  var show='Y';
  var DB=$('#jsdbname').val();    //資料庫
  $("input[name='allid[]']:checked").each(function(){   
      str+=$(this).val()+';';   
  })   
  if(str==''){
      alert('請選取項目');
      return  '';
  }

  if(Status=='Downline'){
    show='N';
  }
  $.ajax({
      url:$('#FileName').attr('fval')+'/AConfig/AConfig/oc_data',
      type:'POST',
      data: 'DB='+DB+'&id='+str+'&oc='+show,
      dataType: 'text',
      success: function( json ) 
      {
          alert(json);
          window.location.reload();
          // console.log(json);
      }
  });
}
// 列表跳頁
function changepage(Topage){
  $('#ToPage').val(Topage);
  $("#SearchForm").submit();
}
$(function() {
  $('a[id="menu_list"]').click(function(){
    post_to_url($('#FileName').attr('fval')+'/'+$(this).attr("d_url"),'', {'Menuid':$(this).attr("rel")}); 
  });
  $("a[id='del_actions']").click(function(){
      if(confirm('確定刪除?')){
        post_to_url($('#FileName').attr('fval')+'/'+$(this).attr("dbrel")+'/'+$(this).attr("dbrel")+'_edit/deletefile','', {'d_id':$(this).attr("rel"),'deltype':'Y','dbname':$(this).attr("dbrel")});
      }
  });
  // 下載圖片
  $('a[id="DownPic"]').click(function(){
    window.location.href=$('#FileName').attr('fval')+'/AConfig/AConfig/DownPic/'+$('#dbname').val()+'/'+$('#d_id').val()+'/'+$(this).attr('rel');
  });
  // 刪除圖片
  $('a[id="DelPic"]').click(function(){
    if(confirm('確定刪除此圖片?')){
      $.ajax({
        url:$('#FileName').attr('fval')+'/AConfig/AConfig/DelPic',
        type:'POST',
        data: 'DBname='+$('#dbname').val()+'&Did='+$('#d_id').val()+'&FiledName='+$(this).attr('rel'),
        dataType: 'text',
        success: function(response){
          if(response=='OK'){
            alert('刪除成功');
            location.reload()
          }
        }
      });
    }
  });
})

//GET轉POST
function post_to_url(path,targets, params) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  if(targets!=""){
    form.setAttribute("target", targets);
  }
  form.setAttribute("action", path);
  form.setAttribute("name", "newForm");
  form.setAttribute("id", "newForm");
  //加入時間參數以免讀到舊資料
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "parm");
    hiddenField.setAttribute("value", new Date().getTime());
    form.appendChild(hiddenField);
  for(var key in params) {        
    var hiddenField = document.createElement("input");        
    hiddenField.setAttribute("type", "hidden");        
    hiddenField.setAttribute("name", key);         
    hiddenField.setAttribute("value", params[key]);        
    form.appendChild(hiddenField);    
  }    
  document.body.appendChild(form);
  form.submit();
  // work_mesg(true);//鎖定按鈕並顯示訊息
  document.body.removeChild(document.getElementById("newForm"));
}
