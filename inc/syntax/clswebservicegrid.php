﻿<?php  require_once"inc/syntax/clsstdgrid.php";class cWebserviceGrid extends cStdGrid{function cWebserviceGrid($option){$this->Name="webservice";$this->Filename=$_SESSION["INSTALLPATH"].$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"]["NAME"];}function arr2xml($value,$key){switch(strtolower($key)){case'mime':if(!is_array($value)){$return[0]['VALUE']=htmlspecialchars($value);break;}case'directories':case'defaults':case'errors':case'headers':case'redirect':case'extensions':case'users':case'access':if(is_array($value))foreach($value as$k=>$v){foreach($v as$k2=>$v2){$return[0]["ITEM"][$k][strtoupper($k2)]=$this->arr2xml($v2,strtoupper($k2));if(count($options[0])==5)$return[0]["ITEM"][$k]["OPTIONS"]=$options;}}break;default:$return[0]['VALUE']=htmlspecialchars($value);break;}return$return;}function xml2arr($item,$key){switch(strtolower($key)){case'mime':if(is_array($item)&&trim($item[0]["VALUE"])!=""){$return=$item[0]["VALUE"];break;}case'directories':case'defaults':case'errors':case'headers':case'redirect':case'extensions':case'users':case'access':if(isset($item[0]["ITEM"]))foreach($item[0]["ITEM"]as$ik=>$iv)foreach($iv as$k=>$v)if($k!="VALUE")$return[strtolower($ik)][strtolower($k)]=$this->xml2arr($v,$k);break;default:if(is_array($item)&&trim($item[0]["VALUE"])!="")$return=$item[0]["VALUE"];break;}return$return;}function saveToXML(){foreach($this->Data as$index=>$host)foreach($host as$var=>$value){if(!($var=='value'&&!isset($value[0]["VALUE"])))$return[$index][strtoupper($var)]=$this->arr2xml($value,$var);}return$return;}function LoadFromXML(){if(file_exists($this->Filename)){$parser=new ParseXML();$xml=$parser->GetXMLTree($this->Filename);$items=$xml["WEB"][0]["HOST"];$this->Head=$xml["WEB"][0]["OPTIONS"];unset($parser);}if($items)foreach($items as$index=>$host)foreach($host as$var=>$value)$return[$index][strtolower($var)]=$this->xml2arr($value,$var);$this->Data=$return;}function Load($gObj,$array){$this->LoadFromXML();if(!$array)$this->SaveToSession($gObj);}function Save(){$arr=$this->SaveToXML();$xml["WEB"][0]["OPTIONS"]=$this->Head;$xml["WEB"][0]["HOST"]=$arr;$parser=new ParseXML();$xmlstr=$parser->Array2XML($xml,true);if($xmlstr!=""){@unlink($this->Filename);$fp=fopen($this->Filename,'w+');fwrite($fp,trim($xmlstr));@fclose($fp);}else@unlink($this->Filename);}function getObjectJS($selector,$vindex,$function=false,$option){global$skin_dir,$sGridItem,$alang;$arr["dname"]=$this->Name;@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;if($this->Data)foreach($this->Data as$key2=>$val2){foreach($vindex as$key3=>$val3){$val=strtolower($val3);if($key2==0&&$val=="name")$item=$alang["TStrings_defaulthost"];else if($val=="useglobal")$item=$val2[$val3]?"Y":"N";else$item=eregi_replace("([\\\'\"])","\\\\1",$val2[$val3]);$arr["items"]["num"][$key2]['item']["num"][$key3]['value']=str_replace(CRLF,";",$item);if($function[$val3]){$function[$val3]['option']['ATTRIBUTES']['COUNT']=$key3;formfunctionvalues($function[$val3]['function'],$arr["items"]["num"][$key2]['item']["num"][$key3]['value'],true,$function[$val3]['option']['ATTRIBUTES']['VINDEX'],$error,$function[$val3]['option']);}$arr["items"]["num"][$key2]['item']["num"][$key3]['label']='<a href="javascript:processdatagrid(\\\'edit\\\',\\\''.$dialog.'\\\',\\\''.$this->Name.'\\\',\\\'\\\','.$width.','.$height.',\\\'\\\',\\\''.$this->count.'\\\',0,\\\''.$key2.'\\\',\\\''.$this->uid.'\\\');">'.$arr["items"]["num"][$key2]['item']["num"][$key3]['value'].'</a>';$arr["items"]["num"][$key2]['item']["num"][$key3]['sort']=$arr["items"]["num"][$key2]['item']["num"][$key3]['value'];}$arr["items"]["num"][$key2]["cislo"]=$key2;if($this->Name.$key2==$sGridItem)$arr["items"]["num"][$key2]["selected"]=1;}$arr["selector"]=$selector;return template($skin_dir."dgridjs2.tpl",$arr);}}?>