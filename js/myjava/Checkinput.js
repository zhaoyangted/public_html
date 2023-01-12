//只能輸入數字
function ValidateNumber(e, pnumber)
{
	var reg=/^\d+$/;
    if (!reg.test(pnumber)){
        $(e).val(($(e).val()).slice(0,-1));
    }
    return false;
}
//只能輸入數字 可輸入小數點
function ValidateFloat(e, pnumber)
{
	var reg=/^\d+[.]?\d*$/;
    if (!reg.test(pnumber)){
    	$(e).val(($(e).val()).slice(0,-1));
    }
    return false;
}
//數字、字母、底線
function ValidateEngNumDown(e, pnumber)
{
	var reg=/^\w+$/;
    if (!reg.test(pnumber)){
    	$(e).val(($(e).val()).slice(0,-1));
    }
    return false;
}
//英文
function ValidateEng(e, pnumber)
{
	var reg=/^[a-zA-Z]+$/;
    if (!reg.test(pnumber)){
    	$(e).val(($(e).val()).slice(0,-1));
    }
    return false;
}
//英數
function ValidateEngNum(e, pnumber)
{
	var reg=/^[a-zA-Z0-9]+$/;
    if (!reg.test(pnumber)){
    	$(e).val(($(e).val()).slice(0,-1));
    }
    return false;
}
//信箱專用
function ValidateMail(e, pnumber)
{
    var reg=/^[a-zA-Z@.]+$/;
    if (!reg.test(pnumber)){
        $(e).val(($(e).val()).slice(0,-1));
    }
    return false;
}