﻿<?php  require_once"inc/syntax/clsstdgrid.php";class cCFHeaderGrid extends cStdGrid{function cCFHeaderGrid($option,$name){$this->Name="val";$this->NameGrid="header";}function Load($gObj,$array){$pom=$gObj->Base[strtolower($this->Name)];if(!is_array($pom)){$lines=explode("\n",$pom);if($lines)for($i=0;$i<count($lines)-1;$i++){$item=array();$item["action"]=substr($lines[$i],0,1);if($lines[$i][1]=='^'){$string=substr($lines[$i],2,strlen($lines[$i]));$pom=explode(":",$string);$pom2=explode("||",$pom[1]);$item["regex"]=1;$item["regex_value"]=trim($pom2[0]);$item["header"]=$pom[0];$item["header_value"]=trim($pom2[1]);}else{$item["regex"]=0;$item["regex_value"]="";$string=substr($lines[$i],1,strlen($lines[$i]));$pom=explode(":",$string);$item["header"]=$pom[0];$item["header_value"]=trim($pom[1]);}$items[]=$item;}}else$items=$pom;$gObj->Base[strtolower($this->Name)]=$items;$this->Data=$items;}function Save(){}function getObjectJS($selector,$vindex,$aFunction=false,$option){global$skin_dir,$sGridItem,$alang;$arr["dname"]="header";@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;if($this->Data)foreach($this->Data as$key2=>$val2){foreach($vindex as$key3=>$val3){$item=eregi_replace("([\\\'\"])","\\\\1",$val2[strtolower($val3)]);if($key3=="action"&&$item==0)$item=$alang["TContentEditHeader_ActionEdit0"];else if($key3=="action")$item=$alang["TContentEditHeader_ActionEdit1"];$arr["items"]["num"][$key2]['item']["num"][$key3]['value']=trim(str_replace(CRLF,";",$item));$arr["items"]["num"][$key2]['item']["num"][$key3]['label']='<a href="javascript:processdatagrid(\\\'edit\\\',\\\''.$dialog.'\\\',\\\''.$this->Name.'\\\',\\\'\\\','.$width.','.$height.',\\\'\\\',\\\''.$this->count.'\\\',0,\\\''.$key2.'\\\',\\\''.$this->uid.'\\\');">'.$arr["items"]["num"][$key2]['item']["num"][$key3]['value'].'</a>';$arr["items"]["num"][$key2]['item']["num"][$key3]['sort']=$arr["items"]["num"][$key2]['item']["num"][$key3]['value'];}$arr["items"]["num"][$key2]["cislo"]=$key2;if($this->Name.$key2==$sGridItem)$arr["items"]["num"][$key2]["selected"]=1;}$arr["selector"]=$selector;return template($skin_dir."dgridjs2.tpl",$arr);}function getGridButtons($option,$type){global$alang,$_REQUEST,$formapi,$value;@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];if($option["BUTTONS"][0]["BUTTON"])foreach($option["BUTTONS"][0]["BUTTON"]as$bk=>$bv){unset($item);@$item["type"]=$bv["ATTRIBUTES"]["TYPE"];@$item["label"]=$alang[$bv["ATTRIBUTES"]["LABEL"]];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;$item["onclick"]='processdatagrid("'.$item["type"].'","'.$dialog.'","'.$this->Name.'","",'.$width.','.$height.',"",'.$this->count.',"","","'.$this->uid.'");';switch($item["type"]){case'removeall':case't_refresh':case'delete':case'up':case'ftpsyncnow':case'down':if($type=="datagrid")$nr='document.getElementById("noreload").value=1;';else$nr='';$item["onclick"]=' '.$nr.'document.getElementById("gridval").value="'.$this->NameGrid.'";document.getElementById("gridaction").value="'.$item["type"].'";document.forms[0].submit();';break;case'editfile':if(!$this->Filename)$this->Filename=$file;if($this->commentFile){$commentFile=',"'.$this->commentFile.'"';}else{$commentFile=',""';}$item["onclick"]=' buttonedit("textfile","'.$_REQUEST['object'].'","'.urlencode($this->Filename).'"'.',"datagrid|'.$this->Name.'|'.$this->uid.'");';break;}$items[]=$item;}return$items;}}?>