//Global variable for selected gtid items
var si = new Array();

//OPENS A NEW WINDOW
function newwindow(url, title, width, height,fullscreen)
{
  if (isNaN(width)) width = 640;
  if (isNaN(height)) height = 480;
  if (title == "undefined") title = "MerakMailServer";
  if(fullscreen){
  	fullscreen = "fullscreen=yes,";
  	var size = "width="+screen.width+",height="+screen.height;
  }else{
  	fullscreen = "";
  	var size = "width="+width+",height="+height;
  }
  newwin = window.open(url, title, "resizable=yes,scrollbars=yes,status=0,"+fullscreen+size );

  newwin.focus();
}

//CHECK IF ELM EXISTS IN ARRAY
function inarray(arr,elm){
        for(var i in arr){
           if(arr[i]==elm) return i;
        }
        return -1;
}

// BUTTON HANDLING FUNCTIONS IN EDITFORM - allow save ?
function AllowSave()
{
	document.getElementById('okbutton').disabled=true;
	var frm = document.forms[0];
	for (i=0;i<frm.elements.length;i++){
		e = frm.elements[i];
		var tname = e.tagName.toLowerCase();
		if( tname=="input" || tname=="select" || tname=="textarea" ){
			e.onchange = function (){	document.getElementById('okbutton').disabled=false;	};
		}
	}
}

//Item CSS Class objects
WM_removeClass = function(el, className) {
        if (!(el && el.className)) {
                return;
        }
        var cls = el.className.split(" ");
        var ar = new Array();
        for (var i = cls.length; i > 0;) {
                if (cls[--i] != className) {
                        ar[ar.length] = cls[i];
                }
        }
        el.className = ar.join(" ");
};

WM_addClass = function(el, className) {
        WM_removeClass(el, className);
        if(el.className=="") el.className = className; else el.className += " " + className;
};

WM_hasClass = function(el, className) {
        if (!(el && el.className)) {
                return false;
        }
        var cls = el.className.split(" ");
        for (var i = cls.length; i > 0;) {
                if (cls[--i] == className) {
                        return true;
                }
        }
        return false;
};

function CheckAllout(form, elm)
{
  if (form.elements==null){
  	var name = "sel_" + form + "[]";
  	form = document.forms[0];
	for (var i=0;i<form.elements.length;i++)
  	{
    	var e=form.elements[i];
		if ((e.type=='checkbox') && (e.name==name))
    		e.checked=elm.checked;
  	}
  }
  else
  for (var i=0;i<form.elements.length;i++)
  {
    var e=form.elements[i];
	if (e.type == 'checkbox')
    	e.checked=elm.checked;
  }
}
function getCookie(name)
{
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1){
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }else{
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1){
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}

// BUTTON HANDLING FUNCTIONS IN EDITFORM
function AddButtons(page,type,value)
{
	if (window.opener!=null)
	{
		if (window.opener.location.href.toLowerCase().indexOf('leftmenu.html')==-1)
		{
			var button1 = document.createElement('input');
			button1.type = 'submit';
			button1.id = 'okbutton';
			button1.className = 'button';
			button1.value = alang["OK"];

      if (value=="contentfilters") 
        button1.onclick = function(){
            if (document.getElementById('input_Title').value=='')
            {
              alert(alang["missing_title"]);
              return false;
            }
        }
      
      
			document.form.appendChild(button1);

			var button2 = document.createElement('input');
			button2.type = 'button';
			if (page=="group" && type=="add") 
			     button2.onclick = function () {
			                            window.close();
                                  newwindow("mainframe.html?post=1&special=1&object=group&deleteflag=1&acc[0]="+value, "Delete");   
                             };
		  else
		       button2.onclick = function () {window.close();};
		  
			button2.className = 'button';
			button2.value = alang["CANCEL"];
			document.form.appendChild(button2);
			}
		else // FireFox hack
		{
			var button1 = document.createElement('input');
			button1.type = 'submit';
			button1.id = 'okbutton';
			button1.className = 'button apply';
			button1.value = alang["APPLY"];
			document.form.appendChild(button1);
		}
	}
	else
	{

		var button1 = document.createElement('input');
		button1.type = 'submit';
		button1.id = 'okbutton';
		button1.className = 'button apply';
		button1.value = alang["APPLY"];
		document.form.appendChild(button1);
	}
}

//HELP ID showing
if (!parent.document.getElementById('helpid'))
{
	sethelpid('toc');
}
else
{
	if (parent.document.getElementById('helpid').innerHTML=='')
	{
		sethelpid('toc');
	}
}
function sethelpid(helpid)
{
	if (parent.document.getElementById('helpid'))
	{
		var helpelm = parent.document.getElementById('helpid');
		helpelm.innerHTML = helpid;
		var helpid = parent.document.getElementById('helpid').innerHTML;
	}
	var hbtn = parent.document.getElementById('xhelp');
	if (hbtn)
	{
		hbtn.onclick = function(){show_help(helpid);}
	}
	
}

//Last visited node stored in cookie
function setLastNode(id){
  document.cookie="lastnode=" + id + ";expires=Fri, 31 Dec 2099 23:59:59 GMT;";
}
//Shows the top panel in main window
function setInfoValue(icon,name)
{
	parent.document.getElementById('readname').innerHTML=name;
	parent.document.getElementById('img1').src=icon;
}


//This function is a hack do not remove it !!!!!!!!
function setMenuDomainValue(domain)
{
	var arr = parent.document.getElementById('LINKS').innerHTML.split('value=');
	for (i=0; i<arr.length; i++){
		if (i<arr.length-1)
			arr[i]+='value='+domain;
		if (i>0){
		var pos = arr[i].indexOf('\'');
		arr[i] = arr[i].substring(pos,arr[i].length);
		}
	}
	var result = String();
	for (i=0; i<arr.length; i++){
		result+=arr[i];
	}
	document.cookie = "domainv=" + domain +";expires=Fri, 31 Dec 2099 23:59:59 GMT;";
	parent.document.getElementById('LINKS').innerHTML = result;
}


function logssubmit()
{
if (document.getElementById('ldate').value!='')
	{
		document.getElementById('form').submit();
	}
}
function getfile(flag)
{
	document.getElementById('action').value = flag;
	document.getElementById('form').submit();
	return true;
}

function readfile(url)
{
 var req;
 if (document.all){
   req = new ActiveXObject("Microsoft.XMLHTTP");
 }
 else if (netscape){
   if (document.getElementById){
     req = new XMLHttpRequest();
   }
   else {
     req = new NS4HttpRequest();
   }
 }
 req.open("GET",url,false);
 req.send(null);
 return req.responseText;
}

//DOMAINS
function getselecteddomain()
{
  var dom="";
  for (var i=0;i<document.form.elements.length;i++)
  {
    var e=document.form.elements[i];
    if(e.type=="checkbox" && e.checked)
    {
      dom = e.value;
      break;
    }
  }
  return dom;
}

function show_help(helpid)
{
 newwindow("help.html?helpid="+helpid,"helpwindow");
}

function finishedit2(obj,action)
{
   //Special
   if(obj=="user_message_file"){
    window.close();
    return;
   }

   
   //Default
   if ( (obj=="service" || obj=="schedule" || obj=="list" || obj=="group" || obj=="domain" || obj=="retry_intervals" || obj=="listserv" || obj=="user_rules"|| obj=="listserv" || obj=="user") && 
        (action=="delete" || action=="up" || action=="down"))
        return;
   if (window.opener!=null)
   if (window.opener.location.href.toLowerCase().indexOf("leftmenu.html")==-1)
	 {
     if (obj=="datagrid" || obj=="headerfiles" ){
        window.opener.document.getElementById('noreload').value=1;
        window.opener.document.forms[0].submit();
			}else {
			     if(obj=="user_rules" || obj=="domain_rules"){
              var object = window.opener.document.forms[0]["object"].value;
              var type = window.opener.document.forms[0]["type"].value;
              var value = window.opener.document.forms[0]["value"].value;

              window.opener.top.location.href += '?object=' + object + '&type=' + type + '&value=' + value;
            }else{
              //ACTUAL : FIXME ?????
              var pomoc = window.opener.top.location.href;
              pomoc = pomoc.replace("#","");
              window.opener.top.location.href = pomoc;
            }
        //OLD : USE THIS STUFF ?
        //window.opener.top.location.reload();
        
      }
  	}
   window.close();
}


