function Init(iid)
{
		document.getElementById(iid).focus();
}
function Back()
{
			document.getElementById('wizard_step').value=parseInt(document.getElementById('wizard_step').value)-2;
			document.getElementById('frm1').submit();
}
function Next()
{
	document.getElementById('frm1').submit();
}
function Cancel()
{
    if(window.name=='main_win')
      window.location.href = "wizards.html";
    else
		  window.close();
}
