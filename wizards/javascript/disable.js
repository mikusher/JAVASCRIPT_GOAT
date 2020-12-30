function disable(value,id)
{
	if (value==0)
		document.getElementById(id).disabled = true;
	else
		document.getElementById(id).disabled = false;
}