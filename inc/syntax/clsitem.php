﻿<?php  require_once"./inc/clsparsexml.php";class cStdItem{var$Length;var$Seps;function cStdItem($length,$seps){$this->Length=$length;if($length==2)$this->Seps[0]=$seps;if($length>2)$this->Seps=explode("SEP",$seps);}}class cMultilineItem{var$Labelvar;var$Textarea;var$Variable;var$VarNames;function cMultilineItem($item){if(@$item["SYNTAX"][0]["OBJECT"][0]["ATTRIBUTES"]["LABELVAR"])$this->Labelvar=$item["SYNTAX"][0]["OBJECT"][0]["ATTRIBUTES"]["LABELVAR"];else$this->Labelvar=false;if(@$item["SYNTAX"][0]["OBJECT"][0]["TEXTAREA"][0])$this->Textarea=$item["SYNTAX"][0]["OBJECT"][0]["TEXTAREA"][0]["VALUE"];else$this->Textarea=false;@$this->Variable=$item["SYNTAX"][0]["OBJECT"][0]["VARIABLE"];if($this->Variable){foreach($this->Variable as$val)$items[]=strtolower($val["VALUE"]);$this->VarNames=$items;}}}class cWebSettingsItem{var$VarNames;function cWebSettingsItem(){$parser=new ParseXML();$sxml=@$parser->GetXMLTree("xml/interface/advanced/websettings.xml");@$this->Variable=$sxml["SYNTAX"][0]["OBJECT"][0]["VARIABLE"];foreach($this->Variable as$val)$items[]=strtolower($val["VALUE"]);$this->VarNames=$items;}}class cSBItem{function cSBItem(){$this->Length=6;$this->Seps=explode("SEP",";SEP;SEP;SEP;SEP;SEP;");}}class cMemberItem{function cMemberItem(){$this->Length=2;$this->Seps[]=";";}}?>