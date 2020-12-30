﻿<?php  require_once"inc/syntax/clswebservicegrid.php";class cFTPGrid extends cWebserviceGrid{function cFTPGrid($option){$this->Name="ftp";$this->Filename=$_SESSION["INSTALLPATH"].$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"]["NAME"];}function LoadFromXML(){if(file_exists($this->Filename)){$parser=new ParseXML();$this->xml=$parser->GetXMLTree($this->Filename);$items=$this->xml["FTP"][0]["HOST"];unset($parser);}if($items)foreach($items as$index=>$host)if($host)foreach($host as$var=>$value)$return[$index][strtolower($var)]=$this->xml2arr($value,$var);$this->Data=$return;}function saveToXML(){if($this->Data)foreach($this->Data as$index=>$host)if($host)foreach($host as$var=>$value){if(!($var=='value'&&!isset($value[0]["VALUE"])))$return[$index][strtoupper($var)]=$this->arr2xml($value,strtolower($var));}return$return;}function Save(){$arr=$this->SaveToXML();$parser=new ParseXML();if(file_exists($this->Filename))$this->xml=$parser->GetXMLTree($this->Filename);$this->xml["FTP"][0]["HOST"]=$arr;$xmlstr=$parser->Array2XML($this->xml,0,false);if($xmlstr!=""){file_put_contents($this->Filename,$xmlstr);}else@unlink($this->Filename);}function xml2arr($item,$key){switch(strtolower($key)){case'attributes':$return=$item["ATTRRIBUTES"];break;case'permissions':case'ipaccess':case'alias':if(isset($item[0]["ITEM"])){foreach($item[0]["ITEM"]as$k=>$v)if(is_array($v["ATTRIBUTES"]))foreach($v["ATTRIBUTES"]as$k2=>$v2)$return[$k][strtolower($k2)]=$v2;}break;case'groups':case'access':case'sync':if(isset($item[0]["ITEM"]))foreach($item[0]["ITEM"]as$ik=>$iv){if($iv)foreach($iv as$k=>$v){if($k!="OPTIONS"&&$k!="SCHEDULE"&&$k!="SOURCE"&&$k!="VALUE")$return[strtolower($ik)][strtolower($k)]=$this->xml2arr($v,$k);else if($k!="VALUE"){$pom=$this->xml2arr($v,$k);$return[strtolower($ik)]=@array_merge($pom,$return[strtolower($ik)]);}}}break;case'destination':$return=$item[0]["ATTRIBUTES"]["DIRECTORY"];break;case'source':case'schedule':if($item[0]["ATTRIBUTES"])foreach($item[0]["ATTRIBUTES"]as$ok=>$ov)$return[strtolower($key)."_".strtolower($ok)]=$ov;break;case'ip':if(isset($item[0]["ITEM"]))foreach($item[0]["ITEM"]as$ik=>$iv)$return.=$iv["VALUE"].';';break;case'options':if($item[0])foreach($item[0]as$ok=>$ov)if($ok!="VALUE")$return[strtolower($ok)]=$ov[0]["VALUE"];break;case'value':default:if(is_array($item)&&trim($item[0]["VALUE"])!="")$return=$item[0]["VALUE"];break;}return$return;}function arr2xml($value,$key){$option_list=array(0=>"downloadspeedlimit","uploadspeedlimit","downloadamountlimit","uploadamountlimit","uploaddownloadratio");switch(strtolower($key)){case"attributes":if($value)foreach($value as$k=>$v){$return[0]["ATTRRIBUTES"][strtoupper($k)]=htmlspecialchars($v);}break;case'permissions':case'ipaccess':case'alias':if(is_array($value))foreach($value as$k=>$v)if($v)foreach($v as$k2=>$v2){if($k2!='path'&&$k2!='fullpath'&&$v2=='')$v3=htmlspecialchars(intval($v2));else$v3=htmlspecialchars($v2);$return[0]['ITEM'][$k]['ATTRIBUTES'][strtoupper($k2)]=htmlspecialchars($v3);}break;case'sync':if(is_array($value))foreach($value as$k=>$v){if($v)foreach($v as$k2=>$v2){if(substr_count($k2,"schedule")>0){$return[0]["ITEM"][$k]['SCHEDULE'][0]["ATTRIBUTES"][str_replace("schedule_","",$k2)]=htmlspecialchars($v2);}else if(substr_count($k2,"source")>0){$return[0]["ITEM"][$k]['SOURCE'][0]["ATTRIBUTES"][str_replace("source_","",$k2)]=htmlspecialchars($v2);}else$return[0]["ITEM"][$k]['DESTINATION'][0]["ATTRIBUTES"]["DIRECTORY"]=htmlspecialchars($v2);}}break;case'groups':case'access':if(is_array($value))foreach($value as$k=>$v){if($v)foreach($v as$k2=>$v2){if(in_array($k2,$option_list))$options[0][strtoupper($k2)][0]["VALUE"]=htmlspecialchars($v2);else$return[0]["ITEM"][$k][strtoupper($k2)]=$this->arr2xml($v2,strtoupper($k2));if(count($options[0])==5)$return[0]["ITEM"][$k]["OPTIONS"]=htmlspecialchars($options);}}break;case'destination':$return[0]["ATTRIBUTES"]["DIRECTORY"]=htmlspecialchars($value);break;case'ip':$ips=explode(";",$value);unset($ips[count($ips)-1]);if($ips)foreach($ips as$key=>$ip)$return[0]['ITEM'][$key]['VALUE']=htmlspecialchars($ip);break;case'permissions':if($value)foreach($value as$k=>$v)if($v)foreach($v as$k2=>$v2)$return[0]["ITEM"][$k]["ATTRIBUTES"][strtoupper($k2)]=htmlspecialchars($v2);break;default:$return[0]['VALUE']=htmlspecialchars($value);break;}return$return;}function getObjectJS($selector,$vindex,$aFunction=false,$option){global$skin_dir,$sGridItem;$arr["dname"]=$this->Name;@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;if($this->Data)foreach($this->Data as$key2=>$val2){if($vindex)foreach($vindex as$key3=>$val3){$val=htmlspecialchars(strtolower($val3));$item=eregi_replace("([\\\'\"])","\\\\1",$val2[$val3]);$arr["items"]["num"][$key2]['item']["num"][$key3]['value']=htmlspecialchars(str_replace(CRLF,";",$item));$arr["items"]["num"][$key2]['item']["num"][$key3]['label']='<a href="javascript:processdatagrid(\\\'edit\\\',\\\''.$dialog.'\\\',\\\''.$this->Name.'\\\',\\\'\\\','.$width.','.$height.',\\\'\\\',\\\''.$this->count.'\\\',0,\\\''.$key2.'\\\',\\\''.$this->uid.'\\\');">'.$arr["items"]["num"][$key2]['item']["num"][$key3]['value'].'</a>';$arr["items"]["num"][$key2]['item']["num"][$key3]['sort']=$arr["items"]["num"][$key2]['item']["num"][$key3]['value'];}$arr["items"]["num"][$key2]["cislo"]=htmlspecialchars($key2);if($this->Name.$key2==$sGridItem)$arr["items"]["num"][$key2]["selected"]=1;}$arr["selector"]=$selector;return template($skin_dir."dgridjs2.tpl",$arr);}}?>