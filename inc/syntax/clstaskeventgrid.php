﻿<?php
require_once"inc/syntax/clsstdgrid.php";class cTaskEventGrid extends cStdGrid{function cTaskEventGrid($name,$option){@$item=$option["SYNTAX"][0]["OBJECT"][0];@$length=$item["ATTRIBUTES"]["LENGTH"];@$seps=$item["SEPS"][0]["VALUE"];@$this->showFunction=$item["ATTRIBUTES"]["SHOW"];if(!$this->showFunction)$this->showFunction=false;$this->Item=new cStdItem($length,$seps);$this->Filename=$_SESSION["INSTALLPATH"].securepath($option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"]["NAME"]);@$this->Name=$name;}function loadFromBuffer(){if($this->Buffer){$ic=0;$lines=explode(CRLF,$this->Buffer);for($i=0;$i<count($lines);$i++){$item=array();if($lines[$i]=="")continue;if($this->Item->Length==1)$item[0]=$lines[$i];else{$pom=$lines[$i];$shift=0;for($j=0;$j<$this->Item->Length-1;$j++){$pom=explode($this->Item->Seps[$j],$pom,2);$item[$j+$shift]=str_replace("%20"," ",$pom[0]);if($j==2)base64_decode($item[2]);if($j==4){$item4=explode("|",$item[$j]);for($k=0;$k<6;$k++)if($k==3||$k==5)$item[$j+$k]=$item4[$k];else$item[$j+$k]=base64_decode($item4[$k]);$shift=5;}$pom=$pom[1];}$item[$j+$shift]=$pom;}$items[$ic++]=$item;}$this->Data=$items;}}function saveToBuffer(){$buffer="";if($this->Data)foreach($this->Data as$i=>$val){if($this->Item->Length==1)$buffer.=$this->Data[$i][0].CRLF;else{$shift=0;for($j=0;$j<$this->Item->Length-1;$j++){if($j==4){$buffer.=base64_encode($this->Data[$i][$j])."|".base64_encode($this->Data[$i][$j+1])."|".base64_encode($this->Data[$i][$j+2])."|".$this->Data[$i][$j+3]."|".base64_encode($this->Data[$i][$j+4])."|".$this->Data[$i][$j+5].",";$shift=5;}else$buffer.=$this->Data[$i][$j+$shift].$this->Item->Seps[$j];}$buffer.=$this->Data[$i][$j+$shift].CRLF;}}$this->Buffer=$buffer;return$buffer;}}?>