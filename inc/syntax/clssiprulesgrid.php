﻿<?php  require_once"inc/syntax/clswebservicegrid.php";class cSIPRulesGrid extends cWebserviceGrid{function cSIPRulesGrid($option){$this->Name="sip_rules";$this->Filename=$_SESSION["INSTALLPATH"].securepath($option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"]["NAME"]);}function LoadFromXML(){if(file_exists($this->Filename)){$parser=new ParseXML();$this->xml=$parser->GetXMLTree($this->Filename);$items=$this->xml["RULES"][0]["RULE"];unset($parser);}if($items)foreach($items as$index=>$host)if($host)foreach($host as$var=>$value)if(strtolower($var)=="action")$return[$index]=array_merge($return[$index],$this->xml2arr($value,$var));else$return[$index][strtolower($var)]=$this->xml2arr($value,$var);$this->Data=$return;}function saveToXML(){if($this->Data)foreach($this->Data as$index=>$host)if($host)foreach($host as$var=>$value){if(!$var)continue;$specialtags=array("action","next","usegw","gateway","respond","response","usetarget","target");if(in_array($var,$specialtags)){$return[$index][strtoupper($var)]=$this->arr2xml($value,strtolower($var));}else if(!($var=='value'&&!isset($value[0]["VALUE"])))$return[$index][strtoupper($var)]=$this->arr2xml($value,strtolower($var));}return$return;}function Save(){$arr=$this->SaveToXML();$parser=new ParseXML();if(file_exists($this->Filename))$this->xml=$parser->GetXMLTree($this->Filename);$this->xml["RULES"]=self::htmlspecialchars_array($this->xml["RULES"]);$this->xml["RULES"][0]["RULE"]=$arr;$xmlstr=$parser->Array2XML($this->xml,true);if($xmlstr!=""){$fp=@fopen($this->Filename,'w');fwrite($fp,trim($xmlstr));@fclose($fp);}else@unlink($this->Filename);}function xml2arr($item,$key){switch(strtolower($key)){case"action":if(is_array($item[0]))foreach($item[0]as$k=>$v)if(is_array($v))$return[strtolower($k)]=$v[0]["VALUE"];break;case"conditions":if(is_array($item[0]))foreach($item[0]["CONDITION"]as$k=>$v)foreach($v as$ck=>$cv)if(is_array($cv))$return[$k][strtolower($ck)]=$cv[0]["VALUE"];break;case'value':default:if(is_array($item)&&trim($item[0]["VALUE"])!="")$return=$item[0]["VALUE"];break;}return$return;}function arr2xml($value,$key){switch(strtolower($key)){case"conditions":if($value)foreach($value as$k=>$v)foreach($v as$k2=>$v2){if($k2!="value")$return[0]["CONDITION"][$k][strtoupper($k2)][0]["VALUE"]=$v2;}break;default:$return[0]['VALUE']=$value;break;}return$return;}function getObjectJS($selector,$vindex,$aFunction=false,$option){global$skin_dir,$sGridItem;$arr["dname"]=$this->Name;@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;if($this->Data)foreach($this->Data as$key2=>$val2){foreach($vindex as$key3=>$val3){$val=strtolower($val3);$item=eregi_replace("([\\\'\"])","\\\\1",$val2[$val3]);$arr["items"]["num"][$key2]['item']["num"][$key3]['value']=str_replace(CRLF,";",$item);$arr["items"]["num"][$key2]['item']["num"][$key3]['label']='<a href="javascript:processdatagrid(\\\'edit\\\',\\\''.$dialog.'\\\',\\\''.$this->Name.'\\\',\\\'\\\','.$width.','.$height.',\\\'\\\',\\\''.$this->count.'\\\',0,\\\''.$key2.'\\\',\\\''.$this->uid.'\\\');">'.$arr["items"]["num"][$key2]['item']["num"][$key3]['value'].'</a>';$arr["items"]["num"][$key2]['item']["num"][$key3]['sort']=$arr["items"]["num"][$key2]['item']["num"][$key3]['value'];}$arr["items"]["num"][$key2]["cislo"]=$key2;if($this->Name.$key2==$sGridItem)$arr["items"]["num"][$key2]["selected"]=1;}$arr["selector"]=$selector;return template($skin_dir."dgridjs2.tpl",$arr);}static public function htmlspecialchars_array($array){if($array){foreach($array as$key=>$val){if(is_array($val)){$return[$key]=self::htmlspecialchars_array($val);}else{$return[$key]=htmlspecialchars($val);}}}else{return$value;}return$return;}}?>