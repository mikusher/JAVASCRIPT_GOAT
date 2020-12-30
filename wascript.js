function WA_SHOW(name){
/*alert(document.getElementById(name).tagName);
	switch(document.getElementById(name).tagName.toLowerCase()){
		case "div":
		break;
		case "tr":
		break;
	}
*/
	document.getElementById(name).style.visibility = 'visible';
}
function WA_INIT()
{
  var Sections = [];
	var Groups = [];
	var Options = [];
	
	var sCount = 0;
	var gCount = 0;
	var oCount = 0;
	
	var Divs = document.getElementsByTagName('div');
	for (i=0;i<Divs.length;i++){
		if (Divs[i].id.toLowerCase().indexOf('section')!=-1) Sections[sCount++] = Divs[i];//ne
		if (Divs[i].id.toLowerCase().indexOf('group')!=-1) Groups[gCount++] = Divs[i];//ne
	}
	var Trows = document.getElementsByTagName('tr');
	for (i=0;i<Trows.length;i++)
		if (Trows[i].id!='') Options[oCount++] = Trows[i]; 

		
}
function WA_ENABLE(name,value){
/*alert(document.getElementById(name).tagName);
	switch(document.getElementById(name).tagName.toLowerCase()){
		case "div":
		break;
		case "tr":
		break;
	}
*/
	document.getElementById('input_'+name).disabled=false;
}
